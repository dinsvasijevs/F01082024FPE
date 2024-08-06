<?php
namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class CurrencyExchangeTest extends TestCase
{
    public function test_convert_currency_to_eur()
    {
        $user = new User();
        $response = $user->convertCurrency(100, 'USD', 'EUR');

        $this->assertEquals('60.00 EUR', $response);
    }

    public function test_convert_currency_from_usd()
    {
        $user = new User();
        $response = $user->convertCurrency(60, 'EUR', 'USD');

        $this->assertEquals('100 USD', $response);
    }
}
