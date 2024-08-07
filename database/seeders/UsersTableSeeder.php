<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\AccountService;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(AccountService $accountService)
    {
        User::factory(10)->create()->each(function ($user) use ($accountService) {
            $accountService->createAccount($user->id);
        });
    }
}
