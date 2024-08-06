<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class CurrencyExchangeService
{
    private $apiUrl = 'https://currencybeacon.com/api/v1/';
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getExchangeRates(string $baseCurrency)
    {
        $response = $this->client->get($this->apiUrl . 'convert?from=' . urlencode($baseCurrency) . '&to=EUR');

        return json_decode($response->getBody()->getContents(), true);
    }
}
