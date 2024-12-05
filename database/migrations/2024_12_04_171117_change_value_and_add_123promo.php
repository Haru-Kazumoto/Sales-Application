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
        Schema::table('promo_products', function (Blueprint $table) {
            $table->renameColumn('promo_value', 'promo_value_1');
            $table->integer('promo_value_2')->after('promo_value_1')->nullable();
            $table->integer('promo_value_3')->after('promo_value_1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_products', function (Blueprint $table) {
            $table->dropColumn(['promo_value_2, promo_value_3']);
        });
    }
};
