<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Currency;

class CurrencyService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.currency_beacon.api_key');
    }

    public function updateExchangeRates()
    {
        $response = Http::get("https://api.currencybeacon.com/v1/latest?api_key={$this->apiKey}&base=USD");

        if ($response->successful()) {
            $rates = $response->json()['rates'];
            foreach ($rates as $code => $rate) {
                Currency::updateOrCreate(
                    ['code' => $code],
                    ['rate' => $rate]
                );
            }
        }
    }

    public function convert($amount, $fromCurrency, $toCurrency)
    {
        $from = Currency::where('code', $fromCurrency)->first();
        $to = Currency::where('code', $toCurrency)->first();

        if (!$from || !$to) {
            throw new \Exception('Currency not found');
        }

        return $amount * ($to->rate / $from->rate);
    }
}
