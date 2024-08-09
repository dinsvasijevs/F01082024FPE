<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\CurrencyService;
use App\Models\Currency;


class CurrencySwitchController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function switch(Request $request): RedirectResponse
    {
        $request->validate([
            'currency' => 'required|string|size:3',
        ]);

        $user = $request->user();
        $account = $user->account;
        $newCurrency = $request->currency;

        $convertedBalance = $this->currencyService->convert(
            $account->balance,
            $account->currency,
            $newCurrency
        );

        $account->update([
            'balance' => $convertedBalance,
            'currency' => $newCurrency,
        ]);

        return redirect()->back()->with('status', 'Currency switched successfully.');
    }
}
