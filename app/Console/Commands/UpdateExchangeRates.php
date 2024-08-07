<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CurrencyService;


class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency exchange rates';

    /**
     * Execute the console command.
     */
    public function handle(CurrencyService $currencyService)
    {
        $currencyService->updateExchangeRates();
        $this->info('Exchange rates updated successfully.');
    }
}
