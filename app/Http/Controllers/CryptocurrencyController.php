<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\CryptoService;
use Illuminate\Support\Facades\Cache;


class CryptocurrencyController extends Controller
{
    public function index(): View|Factory|Application
    {
        $cryptocurrencies = Cache::remember('crypto_prices', 300, function () {
            $response = Http::withHeaders([
                'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api_key')
            ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
                'limit' => 10,
                'convert' => 'USD'
            ]);

            return $response->json()['data'];
        });

        return view('cryptocurrencies.index', compact('cryptocurrencies'));
    }
}
