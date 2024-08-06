<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Tests\CreatesApplication;

class CurrencyExchangeTest extends TestCase
{
    use CreatesApplication;

    public function test_convert_currency()
    {
        $user = new User();
        $amount = 1000.00;
        $fromCurrencyCode = 'USD';
        $toCurrencyCode = 'EUR';

        $convertedAmount = $user->convertCurrency($amount, $fromCurrencyCode, $toCurrencyCode);

        // Assert converted amount is correct
        $this->assertEquals(853.84, $convertedAmount);
    }
}
