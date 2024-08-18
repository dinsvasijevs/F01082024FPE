<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\CryptoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CryptocurrencyController extends Controller
{
    protected CryptoService $cryptoService;
    const CACHE_DURATION = 300; // 5 minutes

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function index(): View|Factory|Application
    {
        $cryptocurrencies = $this->getCachedCryptocurrencies();
        return view('cryptocurrencies.index', compact('cryptocurrencies'));
    }

    public function buy(Request $request): RedirectResponse
    {
        $request->validate([
            'symbol' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        try {
            $result = $this->cryptoService->buyCrypto(
                Auth::id(),
                $request->symbol,
                $request->amount
            );

            return redirect()->route('cryptocurrencies')
                ->with('success', "Successfully bought {$request->amount} {$request->symbol}.");
        } catch (\Exception $e) {
            \Log::error('Error buying cryptocurrency: ' . $e->getMessage());
            return redirect()->route('cryptocurrencies')
                ->with('error', $e->getMessage());
        }
    }

    public function sell(Request $request): RedirectResponse
    {
        $request->validate([
            'symbol' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        try {
            $this->cryptoService->sellCrypto(
                Auth::id(),
                $request->symbol,
                $request->amount
            );

            return redirect()->route('cryptocurrencies')
                ->with('success', "Successfully sold {$request->amount} {$request->symbol}");
        } catch (\Exception $e) {
            return redirect()->route('cryptocurrencies')
                ->with('error', $e->getMessage());
        }
    }

    private function getCachedCryptocurrencies(): array
    {
        $cacheData = DB::table('cache')->where('key', 'crypto_prices')->first();

        if ($cacheData && $this->isCacheValid($cacheData)) {
            return json_decode($cacheData->value, true);
        }

        $cryptocurrencies = $this->fetchCryptocurrencies();

        DB::table('cache')->updateOrInsert(
            ['key' => 'crypto_prices'],
            [
                'value' => json_encode($cryptocurrencies),
                'expiration' => $this->getExpirationTime()
            ]
        );

        return $cryptocurrencies;
    }

    private function isCacheValid($cacheData): bool
    {
        return $cacheData->expiration > time();
    }

    private function getExpirationTime(): int
    {
        return time() + self::CACHE_DURATION;
    }

    private function fetchCryptocurrencies(): array
    {
        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api_key')
        ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
            'limit' => 20,
            'convert' => 'USD'
        ]);

        return $response->json()['data'];
    }
}
