<?php

namespace App\Services;

use App\Models\User;
use App\Models\Investment;
use App\Models\CryptoTransaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class CryptoService
{
    public function buyCrypto($userId, $symbol, $amount)
    {
        return DB::transaction(function () use ($userId, $symbol, $amount) {
            $user = User::findOrFail($userId);
            $account = $user->account;

            if (!$account) {
                throw new \Exception("User doesn't have an associated account.");
            }

            $cryptoPrice = $this->getCryptoPrice($symbol);
            $totalCost = $amount * $cryptoPrice;

            if ($account->balance < $totalCost) {
                throw new \Exception("Insufficient balance. You need $" . $totalCost . ", but you only have $" . $account->balance);
            }

            $account->balance -= $totalCost;
            $account->save();

            $investment = Investment::firstOrNew([
                'user_id' => $userId,
                'symbol' => $symbol
            ]);

            $newAmount = ($investment->amount ?? 0) + $amount;
            $newTotalCost = (($investment->amount ?? 0) * ($investment->average_buy_price ?? 0)) + $totalCost;
            $investment->amount = $newAmount;
            $investment->average_buy_price = $newTotalCost / $newAmount;

            $investment->save();

            $this->recordTransaction($account->id, $account->id, $symbol, $amount, $cryptoPrice, 'buy');

            return $investment;
        });
    }

    public function sellCrypto($userId, $symbol, $amount)
    {
        return DB::transaction(function () use ($userId, $symbol, $amount) {
            $user = User::findOrFail($userId);
            $account = $user->account;

            if (!$account) {
                throw new \Exception("User doesn't have an associated account.");
            }

            $investment = Investment::where('user_id', $userId)
                ->where('symbol', $symbol)
                ->firstOrFail();

            if ($investment->amount < $amount) {
                throw new \Exception('Insufficient cryptocurrency balance');
            }

            $cryptoPrice = $this->getCryptoPrice($symbol);
            $totalValue = $amount * $cryptoPrice;

            $account->balance += $totalValue;
            $account->save();

            $investment->amount -= $amount;
            $investment->save();

            if ($investment->amount == 0) {
                $investment->delete();
            }

            $this->recordTransaction($account->id, $account->id, $symbol, $amount, $cryptoPrice, 'sell');

            return $investment;
        });
    }

    public function transferCrypto($fromAccountId, $toAccountId, $symbol, $amount)
    {
        return DB::transaction(function () use ($fromAccountId, $toAccountId, $symbol, $amount) {
            $fromAccount = Account::findOrFail($fromAccountId);
            $toAccount = Account::findOrFail($toAccountId);

            $fromInvestment = $fromAccount->user->investments()->where('symbol', $symbol)->firstOrFail();

            if ($fromInvestment->amount < $amount) {
                throw new \Exception('Insufficient cryptocurrency balance');
            }

            $fromInvestment->amount -= $amount;
            $fromInvestment->save();

            $toInvestment = $toAccount->user->investments()->firstOrNew(['symbol' => $symbol]);
            $toInvestment->amount = ($toInvestment->amount ?? 0) + $amount;
            $toInvestment->save();

            $cryptoPrice = $this->getCryptoPrice($symbol);
            $this->recordTransaction($fromAccountId, $toAccountId, $symbol, $amount, $cryptoPrice, 'transfer');

            return $toInvestment;
        });
    }

    public function getCryptoPrice($symbol)
    {
        $cacheData = DB::table('cache')->where('key', 'crypto_prices')->first();

        if ($cacheData && $this->isCacheValid($cacheData)) {
            $cryptocurrencies = json_decode($cacheData->value, true);
            foreach ($cryptocurrencies as $crypto) {
                if ($crypto['symbol'] === $symbol) {
                    return $crypto['quote']['USD']['price'];
                }
            }
        }

        throw new \Exception("Price for {$symbol} not found in cache.");
    }

    private function isCacheValid($cacheData): bool
    {
        return $cacheData->expiration > time();
    }

    public function recordTransaction($fromAccountId, $toAccountId, $symbol, $amount, $price, $type): void
    {
        CryptoTransaction::create([
            'from_account_id' => $fromAccountId,
            'to_account_id' => $toAccountId,
            'symbol' => $symbol,
            'amount' => $amount,
            'price' => $price,
            'type' => $type,
        ]);
    }
}
