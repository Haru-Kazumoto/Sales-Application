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
        Schema::create('transaction_item', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->integer('quantity');
            $table->float('tax_amount');
            $table->float('amount')->nullable();
            $table->float('discount_1')->nullable();
            $table->float('discount_2')->nullable();
            $table->float('discount_3')->nullable();
            
            //relations
            $table->foreignId('transactions_id')->constrained('transactions');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('tax_id')->constrained('tax');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
