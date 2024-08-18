<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Services\CryptoService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    protected CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function index(): View|Factory|Application
    {
        $user = Auth::user();
        $investments = $user->investments()->get();

        foreach ($investments as $investment) {
            $currentPrice = $this->cryptoService->getCurrentPrice($investment->symbol);
            $investment->current_price = $currentPrice;
            $investment->current_value = $investment->amount * $currentPrice;
            $investment->profit_loss = $investment->current_value - ($investment->amount * $investment->average_buy_price);
            $investment->profit_loss_percentage = ($investment->profit_loss / ($investment->amount * $investment->average_buy_price)) * 100;
        }

        return view('investments.index', compact('investments'));
    }

    public function trade(Request $request): RedirectResponse
    {
        $request->validate([
            'symbol' => 'required|string',
            'action' => 'required|in:buy,sell',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        $user = Auth::user();
        $symbol = $request->symbol;
        $action = $request->action;
        $amount = $request->amount;

        try {
            DB::beginTransaction();

            $investment = $user->investments()->firstOrCreate(['symbol' => $symbol]);
            $currentPrice = $this->cryptoService->getCurrentPrice($symbol);

            if ($action === 'buy') {
                $this->buyInvestment($investment, $amount, $currentPrice);
            } else {
                $this->sellInvestment($investment, $amount, $currentPrice);
            }

            DB::commit();

            return redirect()->route('investments.index')->with('success', ucfirst($action) . ' successful!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function buyInvestment(Investment $investment, $amount, $currentPrice): void
    {
        $totalCost = $amount * $currentPrice;

        if (Auth::user()->balance < $totalCost) {
            throw new \Exception('Insufficient balance');
        }

        $newTotalAmount = $investment->amount + $amount;
        $newAverageBuyPrice = (($investment->amount * $investment->average_buy_price) + ($amount * $currentPrice)) / $newTotalAmount;

        $investment->amount = $newTotalAmount;
        $investment->average_buy_price = $newAverageBuyPrice;
        $investment->save();

        Auth::user()->decrement('balance', $totalCost);
    }

    private function sellInvestment(Investment $investment, $amount, $currentPrice): void
    {
        if ($investment->amount < $amount) {
            throw new \Exception('Insufficient cryptocurrency balance');
        }

        $totalEarned = $amount * $currentPrice;

        $investment->amount -= $amount;
        $investment->save();

        if ($investment->amount == 0) {
            $investment->delete();
        }

        Auth::user()->increment('balance', $totalEarned);
    }

}
