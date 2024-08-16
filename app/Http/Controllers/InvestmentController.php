<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function index(): View|Factory|Application
    {
        $user = auth()->user();
        $investments = $user->investments()->with('cryptocurrency')->get()->map(function ($investment) {
            $currentPrice = $this->cryptoService->getCurrentPrice($investment->cryptocurrency->symbol);
            $currentValue = $investment->amount * $currentPrice;
            $initialValue = $investment->amount * $investment->purchase_price;
            $return = ($currentValue - $initialValue) / $initialValue * 100;

            return [
                'name' => $investment->cryptocurrency->name,
                'symbol' => $investment->cryptocurrency->symbol,
                'amount' => $investment->amount,
                'current_value' => $currentValue,
                'return' => $return,
            ];
        });

        return view('investments.index', compact('investments'));
    }
}
