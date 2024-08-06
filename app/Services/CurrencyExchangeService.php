<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CurrencyExchangeService
{
    private $apiUrl = 'https://currencybeacon.com/api/v1/';
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getCacheTTL(): int
    {
        return 60 * 5; // 5 minutes
    }

    public function getCachedExchangeRates(string $baseCurrency)
    {
        $cacheKey = "exchange_rates_{$baseCurrency}";
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            return $cachedData;
        }

        $response = $this->client->get($this->apiUrl . 'convert?from=' . urlencode($baseCurrency) . '&to=EUR');
        $data = json_decode($response->getBody()->getContents(), true);

        Cache::put($cacheKey, $data, $this->getCacheTTL());

        return $data;
    }
}
