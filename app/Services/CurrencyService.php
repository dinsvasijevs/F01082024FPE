<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
//use App\Models\Currency;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.currency_beacon.api_key');
    }

    public function getExchangeRates()
    {
        return Cache::remember('exchange_rates', 3600, function () {
            $response = Http::get("https://api.currencybeacon.com/v1/latest", [
                'api_key' => $this->apiKey,
                'base' => 'USD'
            ]);

            return $response->json()['rates'];
        });
    }

    public function convert($amount, $from, $to): float|int
    {
        $rates = $this->getExchangeRates();
        $fromRate = $rates[$from];
        $toRate = $rates[$to];

        return ($amount / $fromRate) * $toRate;
    }
}
