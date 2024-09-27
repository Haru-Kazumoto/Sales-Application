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
        Schema::create('product_customer_orders', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('package');

            $table->float('price');
            $table->float('discount_1')->nullable();
            $table->float('total_price_discount_1')->nullable();
            $table->float('discount_2')->nullable();
            $table->float('total_price_discount_2')->nullable();
            $table->float('discount_3')->nullable();
            $table->float('total_price_discount_3')->nullable();

            $table->foreignId('customer_order_id')->constrained('customer_orders');
            $table->foreignId('promo_claim_id')->nullable()->constrained('promo_claims');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_customer_orders');
    }
};
