<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\CryptoService;
use Illuminate\Support\Facades\Cache;
use App\Models\Investment;
use Illuminate\Support\Facades\Auth;

class CryptocurrencyController extends Controller
{
    protected CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

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

        $userInvestments = Investment::where('user_id', Auth::id())->get()->keyBy('symbol');

        return view('cryptocurrencies.index', compact('cryptocurrencies', 'userInvestments'));
    }

    public function buy(Request $request)
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

            return redirect()->back()->with('success', "Successfully bought {$request->amount} {$request->symbol}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sell(Request $request)
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
