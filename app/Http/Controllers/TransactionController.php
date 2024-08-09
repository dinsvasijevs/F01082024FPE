<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Services\TransactionService;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
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
}
