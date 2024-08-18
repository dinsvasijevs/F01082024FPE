<?php

namespace App\Services;

use App\Models\User;
use App\Models\Investment;
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

            \Log::info("User account balance: " . $account->balance);
            \Log::info("Total cost: " . $totalCost);

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

            return $investment;
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
}
