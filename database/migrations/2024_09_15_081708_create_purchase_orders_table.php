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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_number')->unique();
            $table->string('supplier');
            $table->string('storehouse'); //take storehouse name from storehouse table
            $table->string('located');
            $table->date('purchase_order_date');
            $table->date('send_date');
            $table->string('payment_term');
            $table->date('due_date');
            $table->string('transportation');
            $table->string('sender');
            $table->string('delivery_type');
            $table->string('employee_name');
            $table->text('notes')->nullable();
            $table->float('sub_total');
            $table->float('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purcase_orders');
    }
};
