<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Str;
use Illuminate\Auth\MustVerifyEmail;

trait GeneratesIban
{
    public function generateIban($iban)
    {
        // Generate a random IBAN number
        $randomNumber = rand(0, 99999);

        // Format the IBAN number using str_pad
        return Str::pad($iban . $randomNumber, 22, '0', STR_PAD_LEFT);
    }
}
