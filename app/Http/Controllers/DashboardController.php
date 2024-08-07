<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View|Factory|Application
    {
        $user = $request->user();
        $account = $user->account;
        $transactions = $account->transactions()->latest()->take(10)->get();

        return view('dashboard', compact('user', 'account', 'transactions'));
    }
}
