<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\CurrencyService;
use App\Models\Currency;



class CurrencySwitchController extends Controller
{
    public function switch(Request $request): RedirectResponse
    {
        $request->validate([
            'currency' => 'required|exists:currencies,code',
        ]);

        $user = $request->user();
        $account = $user->account;
        $newCurrency = Currency::where('code', $request->currency)->first();

        // Convert balance to new currency
        $account->balance = $account->balance * ($newCurrency->rate / Currency::where('code', $account->currency)->first()->rate);
        $account->currency = $newCurrency->code;
        $account->save();

        return redirect()->back()->with('status', 'Currency switched successfully.');
    }
}
