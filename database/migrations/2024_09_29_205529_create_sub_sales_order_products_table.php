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
        Schema::create('sub_sales_order_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('product_name');
            $table->integer('amount');
            $table->string('package');
            $table->float('product_price');
            $table->float('total_price');
            $table->float('ppn');

            $table->foreignId('sub_sales_order_id')->constrained('sub_sales_orders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_sales_order_products');
    }
};
