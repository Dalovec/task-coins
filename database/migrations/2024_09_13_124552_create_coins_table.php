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
        Schema::create('coins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('coin_id')->index()->unique();
            $table->string('name');
            $table->string('symbol');
            $table->mediumText('image')->nullable();
            $table->float('current_price')->nullable();
            $table->float('price_change_percentage_24h')->nullable();
            $table->float('market_cap')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coins');
    }
};
