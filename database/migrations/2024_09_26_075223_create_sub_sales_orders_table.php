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
        Schema::create('sub_sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_number');
            $table->string('proof_number');
            $table->string('sales_order_number');
            $table->dateTime('order_date');
            $table->string('located');
            $table->string('supplier');
            $table->string('storehouse');
            $table->dateTime('send_date');
            $table->string('transportation');
            $table->string('sender');
            $table->string('delivery_type');
            $table->string('employee_name');
            $table->float('sub_total');
            $table->float('total_after_ppn');
            $table->float('total_price');
            $table->string('note');
            $table->string('term_of_payment');
            $table->dateTime('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_sales_orders');
    }
};
