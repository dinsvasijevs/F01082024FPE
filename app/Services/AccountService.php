<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    public function generateIBAN()
    {
        // Implement IBAN generation logic
        // This is a simplified example and not suitable for production
        return 'LT' . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT) . str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    public function createAccount($userId)
    {
        return Account::create([
            'user_id' => $userId,
            'iban' => $this->generateIBAN(),
            'balance' => 0,
            'currency' => 'USD',
        ]);
    }
}
