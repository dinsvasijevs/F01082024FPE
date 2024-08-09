<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CurrenciesTableSeeder::class,  // First, seed the currencies
            UserAndAccountSeeder::class,   // Then, create the predefined users with accounts
            UsersTableSeeder::class,       // Create additional random users
            AccountsTableSeeder::class,    // Create accounts for the random users
        ]);
    }
}
