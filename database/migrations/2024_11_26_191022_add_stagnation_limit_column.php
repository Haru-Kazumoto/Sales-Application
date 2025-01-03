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
        Schema::table('product_journal', function (Blueprint $table) {
            $table->dateTime('stagnation_limit_date')
                ->after('expiry_date')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_journal', function (Blueprint $table) {
            $table->dropColumn('stagnation_limit_date');
        });
    }
};
