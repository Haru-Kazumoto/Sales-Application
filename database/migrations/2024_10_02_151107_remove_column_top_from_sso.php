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
        Schema::table('sub_sales_orders', function (Blueprint $table) {
            $table->dropColumn('term_of_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_sales_orders', function (Blueprint $table) {
            $table->string('term_of_payment');
        });
    }
};
