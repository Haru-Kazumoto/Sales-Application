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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->float('redemp_price')->nullable()->comment('the main price of products that will use to be PO price (depends on product)');
            $table->float('retail_price')->nullable()->comment('the price of retail market');
            $table->float('grosir_price')->nullable()->comment('the price of grosir market');
            $table->float('end_user_price')->nullable()->comment('the price of end user segment');
            $table->float('all_segment_price')->nullable()->comment('the price of all segment');
            $table->decimal('percentage')->default(0)->nullable()->comment('percentage of products');
            $table->float('transportation_cost')->nullable()->comment('transportation cost on every products depends on delivery such as JABODETABEK - 9.000 (stored as value)');
            $table->float('oh_depo')->nullable()->comment('over head depo is one of main element of calculate pricing');
            $table->float('budget_marketing')->nullable()->comment('budget marketing is one of main element of calculate pricing');
            $table->float('bad_debt')->nullable()->comment('bad debt is one of main element of calculate pricing');
            $table->float('saving')->nullable()->comment('saving is one of main element of calculate pricing');
            $table->float('margin_retail')->comment('nett margin of retail price');
            $table->float('margin_grosir')->comment('nett margin of grosir price');
            $table->float('margin_end_user')->comment('nett margin of end user price');
            $table->float('margin_all_segment')->comment('nett margin of all segment price');
            $table->float('rounded_all_segment_price')->comment('rounded value of all segment price including to margin');

            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('shipping_id')->nullable()->constrained('shipping')->nullOnDelete();
            $table->foreignId('sub_shipping_id')->nullable()->constrained('sub_shipping')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
