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
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->float('transportation_cost')->after('status_co');
            $table->float('cashback')->after('transportation_cost');
            $table->float('unloading_cost')->after('cashback');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->dropColumn('transportation_cost');
            $table->dropColumn('cashback');
            $table->dropColumn('unloading_cost');
        });
    }
};
