<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Services\TransactionService;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use App\Models\Account;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;
    protected CurrencyService $currencyService;

    public function __construct(TransactionService $transactionService, CurrencyService $currencyService)
    {
        $this->transactionService = $transactionService;
        $this->currencyService = $currencyService;
    }

    public function transfer(TransferRequest $request): RedirectResponse
    {
        try {
            $fromAccount = $request->user()->account;
            $toAccount = Account::findOrFail($request->to_account_id);

            $amount = $this->currencyService->convert(
                $request->amount,
                $request->currency,
                $fromAccount->currency
            );

            $result = $this->transactionService->transfer(
                $fromAccount->id,
                $toAccount->id,
                $amount,
                $fromAccount->currency
            );

            Log::info('Transfer successful', [
                'from_account' => $fromAccount->id,
                'to_account' => $toAccount->id,
                'amount' => $amount,
                'currency' => $fromAccount->currency
            ]);

            return redirect()->back()->with('success', 'Transfer successful');
        } catch (\Exception $e) {
            Log::error('Transfer failed', [
                'error' => $e->getMessage(),
                'from_account' => $fromAccount->id,
                'to_account' => $request->to_account_id,
                'amount' => $request->amount,
                'currency' => $request->currency
            ]);

            return redirect()->back()->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }
}
