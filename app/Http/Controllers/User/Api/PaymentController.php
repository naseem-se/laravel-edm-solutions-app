<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\PlatformConfig;
use App\Models\Shift;
use App\Models\User;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripePaymentService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Get Weekly Summary for payment
     */
    public function getWeeklySummary(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'recipient_id' => 'required|exists:users,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = auth()->user();
            $recipientId = $request->recipient_id;

            // Get date range (default to current week)
            $startDate = $request->start_date ?? now()->startOfWeek();
            $endDate = $request->end_date ?? now()->endOfWeek();

            // Get completed shifts for the recipient
            $shifts = Shift::where('user_id', $recipientId)
                ->where('status', 5) // Status 5 = Completed, not paid yet
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $totalAmount = 0;
            $shiftDetails = [];

            foreach ($shifts as $shift) {
                // Calculate worked hours
                $claimShift = $shift->claimShift;
                if ($claimShift && $claimShift->check_in && $claimShift->check_out) {
                    $checkIn = \Carbon\Carbon::parse($claimShift->check_in);
                    $checkOut = \Carbon\Carbon::parse($claimShift->check_out);

                    if ($checkOut->lessThan($checkIn)) {
                        $checkOut->addDay();
                    }

                    $hoursWorked = $checkIn->diffInHours($checkOut, true);
                    $shiftAmount = $hoursWorked * $shift->pay_per_hour;

                    $totalAmount += $shiftAmount;

                    $shiftDetails[] = [
                        'shift_id' => $shift->id,
                        'date' => $shift->date,
                        'hours_worked' => round($hoursWorked, 2),
                        'pay_per_hour' => $shift->pay_per_hour,
                        'amount' => round($shiftAmount, 2),
                        'location' => $shift->location,
                    ];
                }
            }

            // Calculate platform fee (e.g., 10%)
            $platformFeePercentage = 10;
            $platformFee = ($totalAmount * $platformFeePercentage) / 100;
            $recipientAmount = $totalAmount - $platformFee;

            return response()->json([
                'success' => true,
                'data' => [
                    'total_shifts' => count($shiftDetails),
                    'total_amount' => round($totalAmount, 2),
                    'platform_fee' => round($platformFee, 2),
                    'platform_fee_percentage' => $platformFeePercentage,
                    'recipient_amount' => round($recipientAmount, 2),
                    'currency' => 'usd',
                    'shifts' => $shiftDetails,
                    'recipient' => [
                        'id' => $recipientId,
                        'name' => User::find($recipientId)->name,
                        'email' => User::find($recipientId)->email,
                    ],
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get summary: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Payment History
     */
    public function getPaymentHistory()
    {
        try {
            $user = auth()->user();

            // Payments received by user (as recipient)
            $paymentsReceived = Payment::where('recipient_id', $user->id)
                ->with(['recipient', 'shift'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Current month earnings
            $currentMonth = now()->month;
            $currentYear = now()->year;
            
            $thisMonthEarnings = Payment::where('recipient_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('amount');

            // Last month earnings
            $lastMonth = now()->subMonth();
            $lastMonthEarnings = Payment::where('recipient_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->sum('amount');
            
            // Next pending payment
            $nextPayment = Payment::where('recipient_id', $user->id)
                ->where('status', 'pending')
                ->orderBy('id', 'asc')
                ->first();

            return response()->json([
                'success' => true,
                'current_month_earnings' => round($thisMonthEarnings, 2),
                'last_month_earnings' => round($lastMonthEarnings, 2),
                'data' => PaymentResource::collection($paymentsReceived),
                'next_pending_payment' => $nextPayment ? new PaymentResource($nextPayment) : null,
                
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment history: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Create Payment Intent for paying a worker
     */
    public function createPaymentForWorker(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'recipient_id' => 'required|exists:users,id',
                'shift_ids' => 'required|array',
                'shift_ids.*' => 'exists:shifts,id',
                'amount' => 'required|numeric|min:1',
                'recipient_amount' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all(),
                ], 422);
            }

            $user = auth()->user(); // Payer (facility/admin)
            $recipient = User::findOrFail($request->recipient_id);

            // Check if recipient has connected account
            if (!$recipient->stripe_account_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recipient has not connected their Stripe account',
                    'requires_onboarding' => true,
                ], 400);
            }

            // Check if recipient is onboarded
            $account = $this->stripeService->getConnectedAccount($recipient->stripe_account_id);
            if (!$account->charges_enabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recipient account is not fully onboarded',
                    'requires_onboarding' => true,
                ], 400);
            }

            $metadata = [
                'payer_id' => $user->id,
                'recipient_id' => $recipient->id,
                'shift_ids' => implode(',', $request->shift_ids),
            ];

            $platformFeePercentage = PlatformConfig::getValue('commission_percentage', 10);
            $platformFee = ($request->amount * $platformFeePercentage) / 100;

            // Create payment intent with automatic transfer
            $result = $this->stripeService->createPaymentIntentWithTransfer(
                $request->amount,
                $recipient->stripe_account_id,
                $platformFee,
                'usd',
                $metadata
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);
            }

            // Save payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'recipient_id' => $recipient->id,
                'shift_id' => $request->shift_ids[0] ?? null, // Primary shift
                'payment_intent_id' => $result['payment_intent_id'],
                'amount' => $request->amount,
                'platform_fee' => $request->platform_fee,
                'recipient_amount' => $request->recipient_amount,
                'currency' => 'usd',
                'status' => 'pending',
                'transfer_status' => 'pending',
                'description' => "Payment for " . count($request->shift_ids) . " completed shifts",
                'metadata' => $metadata,
            ]);

            $shift = Shift::where('status', 5)->findOrFail($request->shift_ids[0]);
            $shift->update([
                'status' => 6
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment intent created successfully',
                'data' => [
                    'payment_id' => $payment->id,
                    'client_secret' => $result['client_secret'],
                    'payment_intent_id' => $result['payment_intent_id'],
                    'amount' => $payment->amount,
                    'platform_fee' => $payment->platform_fee,
                    'recipient_amount' => $payment->recipient_amount,
                    'currency' => $payment->currency,
                    'recipient' => [
                        'name' => $recipient->name,
                        'email' => $recipient->email,
                    ],
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Confirm Payment and Update Shifts
     */
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'payment_method_id' => 'nullable|string',
        ]);

        try {
            // Confirm payment in Stripe (API-only)
            $intent = $this->stripeService->confirmPaymentIntent($request->payment_intent_id, $request->payment_method_id);

            // Fetch payment record from DB
            $payment = Payment::where('payment_intent_id', $request->payment_intent_id)->firstOrFail();

            // Update payment status in DB
            $payment->update([
                'status' => $intent->status === 'succeeded' ? 'completed' : $intent->status,
                'payment_method_id' => $intent->payment_method ?? null,
                'paid_at' => $intent->status === 'succeeded' ? now() : null,
                'transfer_status' => $intent->status === 'succeeded' ? 'succeeded' : 'pending',
            ]);

            // ⚠️ Do NOT update shifts here; let webhook handle it

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'data' => [
                    'payment_id' => $payment->id,
                    'status' => $payment->status,
                    'transfer_status' => $payment->transfer_status,
                    'amount' => $payment->amount,
                    'recipient_amount' => $payment->recipient_amount,
                    'paid_at' => $payment->paid_at,
                ],
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm payment: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Onboard recipient for receiving payments
     */
    public function onboardRecipient(Request $request)
    {
        try {
            $user = auth()->user();

            $refreshUrl = $request->input('refresh_url', url('/api/payment/onboard-refresh'));
            $returnUrl = $request->input('return_url', url('/api/payment/onboard-return'));

            $accountLinkUrl = $this->stripeService->createAccountLink($user, $refreshUrl, $returnUrl);

            return response()->json([
                'success' => true,
                'message' => 'Onboarding link created',
                'data' => [
                    'onboarding_url' => $accountLinkUrl,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create onboarding link: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Check onboarding status
     */
    public function checkOnboardingStatus()
    {
        try {
            $user = auth()->user();

            if (!$user->stripe_account_id) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'onboarded' => false,
                        'charges_enabled' => false,
                        'payouts_enabled' => false,
                    ],
                ]);
            }

            $account = $this->stripeService->getConnectedAccount($user->stripe_account_id);

            $user->update([
                'stripe_onboarded' => $account->charges_enabled && $account->payouts_enabled,
                'stripe_capabilities' => [
                    'charges_enabled' => $account->charges_enabled,
                    'payouts_enabled' => $account->payouts_enabled,
                ],
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'onboarded' => $account->charges_enabled && $account->payouts_enabled,
                    'charges_enabled' => $account->charges_enabled,
                    'payouts_enabled' => $account->payouts_enabled,
                    'requirements' => $account->requirements->currently_due ?? [],
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check status: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle Onboard Return
     */
    public function handleOnboardReturn(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Onboarding process completed. Please check your onboarding status.',
        ]);
    }
    /**
     * Handle Onboard Refresh
     */
    public function handleOnboardRefresh(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Onboarding process refreshed. Please continue your onboarding.',
        ]);
    }
}