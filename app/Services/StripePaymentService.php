<?php

namespace App\Services;

use App\Models\User;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Transfer;
use Exception;

class StripePaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create Connected Account for receiving payments
     */
    public function createConnectedAccount(User $user)
    {
        try {
            if ($user->stripe_account_id) {
                return $this->getConnectedAccount($user->stripe_account_id);
            }

            $account = Account::create([
                'type' => 'express', // or 'standard'
                'country' => 'US', // Change based on your country
                'email' => $user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            $user->update([
                'stripe_account_id' => $account->id,
            ]);

            return $account;
        } catch (Exception $e) {
            throw new Exception('Failed to create connected account: ' . $e->getMessage());
        }
    }

    /**
     * Create Account Link for onboarding
     */
    public function createAccountLink(User $user, $refreshUrl, $returnUrl)
    {
        try {
            if (!$user->stripe_account_id) {
                $this->createConnectedAccount($user);
            }

            $accountLink = AccountLink::create([
                'account' => $user->stripe_account_id,
                'refresh_url' => $refreshUrl,
                'return_url' => $returnUrl,
                'type' => 'account_onboarding',
            ]);

            return $accountLink->url;
        } catch (Exception $e) {
            throw new Exception('Failed to create account link: ' . $e->getMessage());
        }
    }

    /**
     * Get Connected Account
     */
    public function getConnectedAccount($accountId)
    {
        try {
            return Account::retrieve($accountId);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve account: ' . $e->getMessage());
        }
    }

    /**
     * Create Payment Intent with destination (for split payment)
     */
    public function createPaymentIntentWithTransfer($amount, $recipientAccountId, $platformFee, $currency = 'usd', $metadata = [])
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => $currency,
                'application_fee_amount' => $platformFee * 100, // Platform fee in cents
                'transfer_data' => [
                    'destination' => $recipientAccountId,
                ],
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create separate transfer (alternative method)
     */
    public function createTransfer($amount, $recipientAccountId, $paymentIntentId, $metadata = [])
    {
        try {
            $transfer = Transfer::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'destination' => $recipientAccountId,
                'source_transaction' => $paymentIntentId,
                'metadata' => $metadata,
            ]);

            return [
                'success' => true,
                'transfer' => $transfer,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create Payment Intent (regular payment to platform)
     */
    public function createPaymentIntent($amount, $currency = 'usd', $metadata = [])
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieve Payment Intent
     */
    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve payment: ' . $e->getMessage());
        }
    }
}
