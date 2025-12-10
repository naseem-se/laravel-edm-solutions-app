<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;

            case 'transfer.created':
                $this->handleTransferCreated($event->data->object);
                break;

            case 'transfer.paid':
                $this->handleTransferPaid($event->data->object);
                break;

            case 'account.updated':
                $this->handleAccountUpdated($event->data->object);
                break;

            default:
                Log::info('Unhandled webhook: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();

        if ($payment) {
            $payment->update([
                'status' => 'succeeded',
                'payment_method_id' => $paymentIntent->payment_method,
                'paid_at' => now(),
            ]);

            // Update shifts
            if (isset($payment->metadata['shift_ids'])) {
                $shiftIds = explode(',', $payment->metadata['shift_ids']);
                \App\Models\Shift::whereIn('id', $shiftIds)->update(['status' => 6]);
            }

            Log::info('Payment succeeded: ' . $paymentIntent->id);
        }
    }

    protected function handlePaymentIntentFailed($paymentIntent)
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();

        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'transfer_status' => 'failed',
            ]);

            Log::error('Payment failed: ' . $paymentIntent->id);
        }
    }

    protected function handleTransferCreated($transfer)
    {
        $payment = Payment::where('payment_intent_id', $transfer->source_transaction)->first();

        if ($payment) {
            $payment->update([
                'transfer_id' => $transfer->id,
                'transfer_status' => 'created',
            ]);

            Log::info('Transfer created: ' . $transfer->id);
        }
    }

    protected function handleTransferPaid($transfer)
    {
        $payment = Payment::where('transfer_id', $transfer->id)->first();

        if ($payment) {
            $payment->update([
                'transfer_status' => 'paid',
            ]);

            Log::info('Transfer paid: ' . $transfer->id);
        }
    }

    protected function handleAccountUpdated($account)
    {
        $user = \App\Models\User::where('stripe_account_id', $account->id)->first();

        if ($user) {
            $user->update([
                'stripe_onboarded' => $account->charges_enabled && $account->payouts_enabled,
                'stripe_capabilities' => [
                    'charges_enabled' => $account->charges_enabled,
                    'payouts_enabled' => $account->payouts_enabled,
                ],
            ]);

            Log::info('Account updated: ' . $account->id);
        }
    }
}
