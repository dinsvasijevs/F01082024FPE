<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;


class CurrencySwitchController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function switch(Request $request)
    {
        $request->validate([
            'currency' => 'required|exists:currencies,code',
        ]);

        $user = $request->user();
        $account = $user->account;
        $newCurrency = $request->currency;

        $newBalance = $this->currencyService->convert(
            $account->balance,
            $account->currency,
            $newCurrency
        );

        $account->update([
            'balance' => $newBalance,
            'currency' => $newCurrency,
        ]);

        return redirect()->back()->with('success', 'Currency switched successfully.');
    }
}
