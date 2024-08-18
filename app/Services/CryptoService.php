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
            $cryptoPrice = $this->getCryptoPrice($symbol);
            $totalCost = $amount * $cryptoPrice;

            if ($user->balance < $totalCost) {
                throw new \Exception('Insufficient balance');
            }

            $user->balance -= $totalCost;
            $user->save();

            $investment = $user->investments()->updateOrCreate(
                ['symbol' => $symbol],
                [
                    'amount' => DB::raw("amount + {$amount}"),
                    'average_buy_price' => DB::raw("(average_buy_price * amount + {$totalCost}) / (amount + {$amount})")
                ]
            );

            return $investment;
        });
    }

    public function sellCrypto($userId, $symbol, $amount)
    {
        return DB::transaction(function () use ($userId, $symbol, $amount) {
            $user = User::findOrFail($userId);
            $investment = Investment::where('user_id', $userId)
                ->where('symbol', $symbol)
                ->firstOrFail();

            if ($investment->amount < $amount) {
                throw new \Exception('Insufficient cryptocurrency balance');
            }

            $cryptoPrice = $this->getCryptoPrice($symbol);
            $totalValue = $amount * $cryptoPrice;

            $user->balance += $totalValue;
            $user->save();

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
