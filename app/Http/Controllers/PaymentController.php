<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Services\CurrencyExchangeService;
use App\Http\Requests\PaymentStoreRequest;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $currencyExchangeService;

    public function __construct(CurrencyExchangeService $currencyExchangeService)
    {
        $this->middleware('auth');
        $this->currencyExchangeService = $currencyExchangeService;
    }

    public function store(PaymentStoreRequest $request)
    {
        try {
            $amount = $request->validated('amount');
            $currency = $request->validated('currency');
            $baseCurrency = 'USD'; // Assume USD is your base currency

            // Convert the amount to your base currency if necessary
            if ($currency !== $baseCurrency) {
                $amount = $this->currencyExchangeService->convertCurrency($amount, $currency, $baseCurrency);
            }

            // Create a payment record
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'amount' => $amount,
                'currency' => $baseCurrency,
                'status' => 'completed',
            ]);

            return response()->json(['message' => 'Payment processed successfully', 'payment' => $payment]);
        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }

    public function index()
    {
        $payments = auth()->user()->payments()->latest()->get();
        return view('payments.index', compact('payments'));
    }

    public function getExchangeRate(Request $request)
    {
        try {
            $from = $request->input('from', 'USD');
            $to = $request->input('to', 'EUR');
            $rate = $this->currencyExchangeService->getExchangeRate($from, $to);
            return response()->json(['rate' => $rate]);
        } catch (\Exception $e) {
            Log::error('Exchange rate fetch failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch exchange rate'], 500);
        }
    }
}
