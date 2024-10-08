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
        Schema::table('watch_dogs', function (Blueprint $table) {
            $table->float('set_price');
            $table->integer('change');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watch_dogs', function (Blueprint $table) {
            $table->dropColumn('set_price');
            $table->dropColumn('change');
        });
    }
};
