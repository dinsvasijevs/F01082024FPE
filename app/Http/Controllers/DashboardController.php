<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Currency;
use App\Models\CryptoTransaction;

class DashboardController extends Controller
{
    public function index(Request $request): View|Factory|Application
    {
        $user = $request->user();
        $account = $user->account;
        $transactions = Transaction::where('from_account_id', $account->id)
            ->orWhere('to_account_id', $account->id)
            ->latest()
            ->take(10)
            ->get();

        $currencies = Currency::all();

        $cryptoTransactions = CryptoTransaction::where('from_account_id', $account->id)
            ->orWhere('to_account_id', $account->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('user', 'account', 'transactions', 'currencies', 'cryptoTransactions'));
    }
}
