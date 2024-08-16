<?php

namespace App\Services;

use App\Models\User;
use App\Models\Investment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CryptoService
{
    public function buyCrypto($userId, $symbol, $amount)
    {
        return DB::transaction(function () use ($userId, $symbol, $amount) {
            $user = User::findOrFail($userId);
            $cryptoPrice = $this->getCryptoPrice($symbol);
            $totalCost = $amount * $cryptoPrice;

            if ($user->balance < $totalCost) {
                throw new \Exception('Insufficient funds');
            }

            $user->balance -= $totalCost;
            $user->save();

            $investment = Investment::updateOrCreate(
                ['user_id' => $userId, 'symbol' => $symbol],
                ['amount' => DB::raw("amount + $amount")]
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

    private function getCryptoPrice($symbol)
    {
        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => config('services.coinmarketcap.api_key')
        ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest', [
            'symbol' => $symbol,
            'convert' => 'USD'
        ]);

        $data = $response->json();
        return $data['data'][$symbol]['quote']['USD']['price'];
    }
}
