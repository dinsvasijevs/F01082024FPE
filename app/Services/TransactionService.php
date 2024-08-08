<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function transfer($fromAccountId, $toAccountId, $amount, $currency)
    {
        return DB::transaction(function () use ($fromAccountId, $toAccountId, $amount, $currency) {
            $fromAccount = Account::findOrFail($fromAccountId);
            $toAccount = Account::findOrFail($toAccountId);

            if ($fromAccount->balance < $amount) {
                throw new \Exception('Insufficient funds');
            }

            $fromAccount->balance -= $amount;
            $toAccount->balance += $amount;

            $fromAccount->save();
            $toAccount->save();

            return Transaction::create([
                'from_account_id' => $fromAccountId,
                'to_account_id' => $toAccountId,
                'amount' => $amount,
                'currency' => $currency,
            ]);
        });
    }
}
