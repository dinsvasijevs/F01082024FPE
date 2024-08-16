<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpirationToCacheTable extends Migration
{
    public function up(): void
    {
        Schema::table('cache', function (Blueprint $table) {
            if (!Schema::hasColumn('cache', 'expiration')) {
                $table->integer('expiration')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('cache', function (Blueprint $table) {
            $table->dropColumn('expiration');
        });
    }
}
