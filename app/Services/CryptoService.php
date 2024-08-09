<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CryptoService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.coinmarketcap.api_key');
    }

    public function getCryptoPrices()
    {
        return Cache::remember('crypto_prices', 300, function () {
            $response = Http::withHeaders([
                'X-CMC_PRO_API_KEY' => $this->apiKey
            ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
                'limit' => 10,
                'convert' => 'USD'
            ]);

            return $response->json()['data'];
        });
    }
}
