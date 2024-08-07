<?php

// app/Services/TransactionService.php
namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function transfer($fromAccountId, $toAccountId, $amount, $currency)
    {
        return DB::transaction(function () use ($fromAccountId, $toAccountId, $amount, $currency) {
            $fromAccount = Account::findOrFail($fromAccountId);
            $toAccount = Account::findOrFail($toAccountId);

            $transaction = Transaction::create([
                'from_account_id' => $fromAccountId,
                'to_account_id' => $toAccountId,
                'amount' => $amount,
                'currency' => $currency,
            ]);

            $fromAccount->user->notify(new TransferSuccessful($transaction));
            $toAccount->user->notify(new TransferSuccessful($transaction));

            return $transaction;
        });
    }
}
