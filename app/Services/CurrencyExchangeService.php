<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CurrencyExchangeService
{
    protected $apiUrl = 'https://api.currencybeacon.com/v1/';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.currencybeacon.key');
    }

    public function getExchangeRate($from, $to)
    {
        $cacheKey = "exchange_rate_{$from}_{$to}";

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($from, $to) {
            $response = Http::get($this->apiUrl . 'latest', [
                'api_key' => $this->apiKey,
                'base' => $from,
                'symbols' => $to,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['rates'][$to] ?? null;
            }

            throw new \Exception('Failed to fetch exchange rate');
        });
    }

    public function convertCurrency($amount, $from, $to)
    {
        $rate = $this->getExchangeRate($from, $to);
        return $amount * $rate;
    }
}
