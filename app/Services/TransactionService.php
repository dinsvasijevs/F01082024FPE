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

            // Add currency conversion logic here if needed
            $convertedAmount = $this->convertCurrency($amount, $currency, $fromAccount->currency);

            if ($fromAccount->balance < $convertedAmount) {
                throw new \Exception('Insufficient funds');
            }

            $fromAccount->balance -= $convertedAmount;
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
    private function convertCurrency($amount, $fromCurrency, $toCurrency)
    {
        // Implement currency conversion logic here
        // For now, we'll just return the original amount
        return $amount;
    }
}
