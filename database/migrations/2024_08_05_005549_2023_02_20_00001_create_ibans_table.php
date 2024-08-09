<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This table is not needed if IBAN is already in the accounts table
        // If you decide to keep it, uncomment the following:
        /*
        Schema::create('ibans', function (Blueprint $table) {
            $table->id();
            $table->string('iban')->unique();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibans');

    }
};
