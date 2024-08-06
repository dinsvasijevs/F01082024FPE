<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CurrencyExchangeRate extends Model
{
    protected $fillable = ['base_currency', 'exchange_rate'];

    public function getCacheKey($baseCurrency)
    {
        return "currency_exchange_rates_{$baseCurrency}";
    }

    public function retrieveFromCache($baseCurrency)
    {
        return Cache::rememberForever($this->getCacheKey($baseCurrency), function () use ($baseCurrency) {
            // Fetch data from external source
            $data = $this->fetchDataFromExternalSource($baseCurrency);
            // Store the data in the database
            $this->storeInDatabaseAndCache($data, $baseCurrency);
            return $data;
        });
    }

    private function fetchDataFromExternalSource($baseCurrency)
    {
        // Your external API call or logic to fetch currency exchange rates goes here
    }

    private function storeInDatabaseAndCache($data, $baseCurrency)
    {
        self::updateOrCreate(['base_currency' => $baseCurrency], ['exchange_rate' => $data['exchange_rate']]);
        Cache::forever($this->getCacheKey($baseCurrency), $data);
    }
}
