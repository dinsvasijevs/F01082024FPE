<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'iban' => $this->faker->iban('LT'),
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'currency' => 'USD',
        ];
    }
}
