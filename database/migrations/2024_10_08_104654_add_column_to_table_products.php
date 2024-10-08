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
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable()->after('name');
            $table->string('supplier')->nullable()->after('category');
            $table->float('redemp_price')->nullable()->after('supplier');
            $table->float('retail_price')->nullable()->after('redemp_price');
            $table->float('restaurant_price')->nullable()->after('retail_price');
            $table->float('price_3')->nullable()->after('restaurant_price');
            $table->float('dd_price')->nullable()->after('price_3');
            $table->float('normal_margin')->nullable()->after('dd_price');
            $table->float('oh_depo')->nullable()->after('normal_margin');
            $table->float('saving')->nullable()->after('oh_depo');
            $table->float('bad_debt_dd')->nullable()->after('saving');
            $table->float('saving_marketing')->nullable()->after('bad_debt_dd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('supplier');
            $table->dropColumn('redemp_price');
            $table->dropColumn('retail_price');
            $table->dropColumn('restaurant_price');
            $table->dropColumn('price_3');
            $table->dropColumn('dd_price');
            $table->dropColumn('normal_margin');
            $table->dropColumn('oh_depo');
            $table->dropColumn('saving');
            $table->dropColumn('bad_debt_dd');
            $table->dropColumn('saving_marketing');
        });
    }
};
