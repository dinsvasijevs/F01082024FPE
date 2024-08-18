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
use App\Models\Investment;
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

        $userInvestments = Investment::where('user_id', Auth::id())->get()->keyBy('symbol');

        return view('cryptocurrencies.index', compact('cryptocurrencies', 'userInvestments'));
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
            'limit' => 10,
            'convert' => 'USD'
        ]);

        return $response->json()['data'];
    }

    public function buy(Request $request): RedirectResponse
    {
        $request->validate([
            'symbol' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        try {
            $result = $this->cryptoService->buyCrypto(
                auth()->user()->id,
                $request->symbol,
                $request->amount
            );

            return redirect()->route('cryptocurrencies')
                ->with('success', "Successfully bought {$request->amount} {$request->symbol}. Check your investments page for details.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sell(Request $request): RedirectResponse
    {
        $request->validate([
            'symbol' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        try {
            $result = $this->cryptoService->sellCrypto(
                Auth::id(),
                $request->symbol,
                $request->amount
            );

            return redirect()->back()->with('success', "Successfully sold {$request->amount} {$request->symbol}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
