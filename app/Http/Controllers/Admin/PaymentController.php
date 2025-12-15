<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PlatformConfig;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     *  Get All Earnings Overview
     */
    public function index()
    {

        $invoice = $this->getFacilityInvoices();
        $invoiceCount = count($invoice);

        // Prepare data array with only required fields
        $paymentData = $this->getSummary();
        $paymentData['invoices'] = $invoice;
        $paymentData['invoiceCount'] = $invoiceCount;

        return view('pages.admin.payments', $paymentData);

    }

    private function getFacilityInvoices()
    {
        // Fetch all invoices where current user is the payer (sending payments)
        $invoices = Payment::with(['payer'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'invoice_id' => 'INV-' . str_pad($payment->id, 3, '0', STR_PAD_LEFT),
                    'facility_name' => $payment->payer->full_name ?? 'N/A',
                    'payer_name' => $payment->payer->name ?? 'N/A',
                    'amount' => number_format((float) $payment->amount, 2),
                    'invoice_date' => $payment->created_at->format('M d, Y'),
                    'due_date' => $payment->due_date?->format('M d, Y') ?? 'N/A',
                    'status' => ucfirst($payment->status === 'completed' ? 'Paid' : $payment->status),
                ];
            });

        return $invoices;
    }

    public function payouts()
    {
        // Fetch all invoices where current user is the recipient (receiving payments)
        // Fetch all payments grouped by recipient (payer in your case)
        $payouts = Payment::with(['recipient'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('shift_id')
            ->map(function ($paymentGroup) {
                $firstPayment = $paymentGroup->first();
                $user = $firstPayment->recipient;

                // Get initials from user name
                $nameParts = explode(' ', $user->full_name ?? '');
                $initials = strtoupper(substr($nameParts[0], 0, 1)) .
                    (isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '');

                // Calculate totals for this recipient
                $totalAmount = $paymentGroup->sum('amount');
                $shiftsCompleted = $paymentGroup->count();
                $latestDate = $paymentGroup->max('created_at');

                return [
                    'id' => $firstPayment->id,
                    'payout_id' => 'PAY-' . str_pad($firstPayment->id, 3, '0', STR_PAD_LEFT),
                    'user_name' => $user->full_name ?? 'N/A',
                    'user_initials' => $initials,
                    'amount' => number_format((float) $totalAmount, 2),
                    'shifts_completed' => $shiftsCompleted,
                    'payment_date' => $latestDate->format('M d, Y'),
                    'status' => 'Paid',
                    'status_color' => 'green',
                ];
            })
            ->values();

        $payoutCount = $payouts->count();
        $paymentData = $this->getSummary();

        return view('pages.admin.payout-payments', [
            'payouts' => $payouts,
            'payoutCount' => $payoutCount,
            ...$paymentData
        ]);
    }

    private function getSummary()
    {
        // Total Earnings - sum of all completed/paid payments
        $totalEarnings = Payment::where('status', 'completed')
            ->sum('amount');

        // Pending Payments - sum of pending payments with count
        $pendingPayments = Payment::where('status', 'pending')->sum('amount');
        $pendingInvoiceCount = Payment::where('status', 'pending')->count();

        // Commission Earned - calculate based on platform fee percentage (10%)
        $platformFeePercentage = PlatformConfig::getValue('commission_percentage', 10);
        $commissionEarned = Payment::where('status', 'completed')
            ->sum('amount') * $platformFeePercentage / 100;

        // Month-over-month comparison
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth();

        $lastMonthEarnings = Payment::where('status', 'completed')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('amount');

        $earningsIncrease = $totalEarnings - $lastMonthEarnings;

        // Prepare data array with only required fields
        $paymentData = [
            'totalEarnings' => number_format($totalEarnings, 2),
            'earningsIncrease' => number_format($earningsIncrease, 2),
            'pendingPayments' => number_format($pendingPayments, 2),
            'pendingInvoiceCount' => $pendingInvoiceCount,
            'commissionEarned' => number_format($commissionEarned, 2),
            'platformFeePercentage' => 10
        ];

        return $paymentData;
    }

    public function commissionTracking()
    {
        $commissionData = $this->getSummary();

        // Get commission percentage (e.g. 10)
        $commissionPercentage = PlatformConfig::getValue('commission_percentage', 0);

        $payments = Payment::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($item) use ($commissionPercentage) {
                $total = (float) $item->total_amount;
                $commission = ($total * $commissionPercentage) / 100;

                return [
                    'month_label' => Carbon::create($item->year, $item->month)->format('F Y'),
                    'total_amount' => $total,
                    'commission_percentage' => $commissionPercentage,
                    'commission_amount' => $commission,
                ];
            });

        return view('pages.admin.commission-tracking', $commissionData, [
            'payments' => $payments
        ]);
    }

}
