<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Services\TransactionService;
use App\Services\CryptoService;
use App\Models\Account;
use App\Models\CryptoTransaction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    protected TransactionService $transactionService;
    protected CryptoService $cryptoService;

    public function __construct(TransactionService $transactionService, CryptoService $cryptoService)
    {
        $this->transactionService = $transactionService;
        $this->cryptoService = $cryptoService;
    }

    public function transfer(TransferRequest $request): RedirectResponse
    {
        try {
            $fromAccount = $request->user()->account;
            $toAccount = Account::where('iban', $request->to_account_iban)->firstOrFail();

            $this->transactionService->transfer(
                $fromAccount->id,
                $toAccount->id,
                $request->amount,
                $request->currency
            );

            return redirect()->back()->with('success', 'Transfer successful');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }

    public function transferCrypto(Request $request): RedirectResponse
    {
        $request->validate([
            'to_account_iban' => 'required|string|exists:accounts,iban',
            'symbol' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        try {
            $fromAccount = $request->user()->account;
            $toAccount = Account::where('iban', $request->to_account_iban)->firstOrFail();

            $this->cryptoService->transferCrypto(
                $fromAccount->id,
                $toAccount->id,
                $request->symbol,
                $request->amount
            );

            return redirect()->back()->with('success', 'Crypto transfer successful');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Crypto transfer failed: ' . $e->getMessage());
        }
    }

    public function index(): View|Factory|Application
    {
        $user = auth()->user();
        $transactions = $user->account->transactions()->latest()->paginate(20);
        $cryptoTransactions = CryptoTransaction::where('from_account_id', $user->account->id)
            ->orWhere('to_account_id', $user->account->id)
            ->latest()
            ->paginate(20);

        return view('transactions.index', compact('transactions', 'cryptoTransactions'));
    }
}
