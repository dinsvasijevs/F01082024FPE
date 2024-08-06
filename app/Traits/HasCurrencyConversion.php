<?php

namespace App\Traits;

trait HasCurrencyConversion
{
    public function convertCurrency($amount, $fromCurrencyCode, $toCurrencyCode)
    {
        // Get exchange rates for base currency (fromCurrencyCode)
        $exchangeRates = app(CurrencyExchangeService::class)->getExchangeRates($fromCurrencyCode);

        // Check if toCurrencyCode is available in exchange rates
        if (!isset($exchangeRates['rates'][$toCurrencyCode])) {
            throw new \InvalidArgumentException("Invalid to_currency_code: `$toCurrencyCode`");
        }

        // Calculate converted amount using exchange rate
        $convertedAmount = round(($amount / $exchangeRates['rates'][$fromCurrencyCode]) * $exchangeRates['rates'][$toCurrencyCode], 2);

        return $convertedAmount;
    }
}
