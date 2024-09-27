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
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_order_number');
            $table->dateTime('order_created');
            $table->string('customer_name');
            $table->string('legality');
            $table->string('customer_address');
            $table->string('term');
            $table->dateTime('due_date');
            $table->string('salesman');
            $table->float('amount');
            $table->float('discount_total');
            $table->float('sub_total');
            $table->float('total_after_ppn');
            $table->float('total');
            $table->string('status_co');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};
