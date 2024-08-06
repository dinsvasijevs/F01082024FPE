<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait GeneratesIban
{
    public function generateIban($iban)
    {
        // Generate a random 10-digit number
        $randomNumber = random_int(1000000000, 9999999999);

        // Format the IBAN number using str_pad
        return str_pad($iban . $randomNumber, 22, '0', STR_PAD_LEFT);
    }
}

