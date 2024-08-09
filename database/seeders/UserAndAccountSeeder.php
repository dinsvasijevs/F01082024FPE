<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class UserAndAccountSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Amelia Blackwood', 'email' => 'amelia@example.com', 'iban' => 'LT123456789012345678'],
            ['name' => 'Ethan Zhao', 'email' => 'ethan@example.com', 'iban' => 'LT234567890123456789'],
            ['name' => 'Sofia Rodriguez', 'email' => 'sofia@example.com', 'iban' => 'LT345678901234567890'],
            ['name' => 'Liam O\'Connor', 'email' => 'liam@example.com', 'iban' => 'LT456789012345678901'],
            ['name' => 'Zara Patel', 'email' => 'zara@example.com', 'iban' => 'LT567890123456789012'],
            ['name' => 'Oscar Andersson', 'email' => 'oscar@example.com', 'iban' => 'LT678901234567890123'],
            ['name' => 'Mia Tanaka', 'email' => 'mia@example.com', 'iban' => 'LT789012345678901234'],
            ['name' => 'Felix Müller', 'email' => 'felix@example.com', 'iban' => 'LT890123456789012345'],
            ['name' => 'Naomi Okafor', 'email' => 'naomi@example.com', 'iban' => 'LT901234567890123456'],
            ['name' => 'Hugo Lefèvre', 'email' => 'hugo@example.com', 'iban' => 'LT012345678901234567'],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'), // You might want to use a more secure password
            ]);

            Account::create([
                'user_id' => $user->id,
                'iban' => $userData['iban'],
                'balance' => 1000, // Starting balance
                'currency' => 'USD',
            ]);
        }
    }
}
