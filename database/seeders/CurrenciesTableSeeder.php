<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;


class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = ['USD', 'EUR', 'GBP', 'JPY'];
        foreach ($currencies as $code) {
            Currency::create([
                'code' => $code,
                'rate' => rand(1, 2) // This is just for testing, in a real app you'd use actual exchange rates
            ]);
        }
    }
}
