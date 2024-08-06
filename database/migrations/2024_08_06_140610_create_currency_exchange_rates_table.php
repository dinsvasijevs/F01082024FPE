<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyExchangeRatesTable extends Migration
{
    public function up()
    {
        Schema::create('currency_exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency');
            $table->float('exchange_rate');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('currency_exchange_rates');
    }
}



