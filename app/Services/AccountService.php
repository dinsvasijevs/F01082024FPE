<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    public function generateIBAN(): string
    {
        $countryCode = 'LT';
        $checkDigits = str_pad(mt_rand(0, 99), 2, '0', STR_PAD_LEFT);
        $bankCode = '00000';
        $accountNumber = str_pad(mt_rand(0, 9999999999), 11, '0', STR_PAD_LEFT);

        return $countryCode . $checkDigits . $bankCode . $accountNumber;
    }

    public function createAccount($userId)
    {
        do {
            $iban = $this->generateIBAN();
        } while (Account::where('iban', $iban)->exists());

        return Account::create([
            'user_id' => $userId,
            'iban' => $iban,
            'balance' => 0,
            'currency' => 'USD',
        ]);
    }
}
