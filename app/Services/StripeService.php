<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent($amount, $currency, $customerId)
    {
        try {
            return PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'customer' => $customerId,
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Stripe API error: ' . $e->getMessage());
        }
    }
}
