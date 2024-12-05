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
        Schema::dropIfExists('purchase_order_products');
        Schema::dropIfExists('customer_order_products');
        Schema::dropIfExists('sub_sales_order_products');
        Schema::dropIfExists('customer_orders');
        Schema::dropIfExists('pajak_test');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('sub_sales_orders');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
