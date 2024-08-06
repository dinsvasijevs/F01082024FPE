<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Str;
use App\Models\Traits\GeneratesIban;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        // Create a payment intent
        $stripe = new Stripe(config('services.stripe.secret'));
        $paymentIntent = $stripe->paymentIntents()->create([
            'amount' => 1000,
            'currency' => 'eur',
            'customer' => Customer::retrieve($request->input('customer_id')),
            'return_url' => route('payment.success'),
        ]);


        // Charge the customer's card
        $charge = $stripe->charges()->create([
            'amount' => 1000,
            'currency' => 'eur',
            'source' => $request->input('card_token'),
            'description' => 'Payment for order #12345',
        ]);

        // Return a success response
        return response()->json(['message' => 'Payment processed successfully.']);
    }

        public function index()
    {
        // Display payment options for the current user
        $user = auth()->user();
        return view('payments.index', compact('user'));
    }

    public function generateInvoice(Request $request)
    {
        // Generate an invoice for the current user
        $user = auth()->user();

        // Use the IBAN generation logic from the GeneratesIban trait
        $ibanGenerator = app(GeneratesIban::class);
        $iban = $ibanGenerator->generateIban($user->getIbanAttribute());

        return view('payments.invoice', compact('user', 'iban'));
    }

    public function processPayment(Request $request)
    {
        // Process a payment for the current user
        // TO DO: implement payment processing logic here
    }
}
