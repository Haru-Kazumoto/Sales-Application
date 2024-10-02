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
        Schema::rename('products', 'purchase_order_products');
        Schema::rename('product_customer_orders','customer_order_products');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('purchase_order_products', 'products');
        Schema::rename('customer_order_products','product_customer_orders');
    }
};
