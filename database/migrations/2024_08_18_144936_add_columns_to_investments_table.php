<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInvestmentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            if (!Schema::hasColumn('investments', 'amount')) {
                $table->decimal('amount', 18, 8)->default(0);
            }
            if (!Schema::hasColumn('investments', 'average_buy_price')) {
                $table->decimal('average_buy_price', 18, 8)->default(0);
            }
            if (!Schema::hasColumn('investments', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('investments', 'symbol')) {
                $table->string('symbol');
            }
        });
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn(['amount', 'average_buy_price', 'symbol']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
