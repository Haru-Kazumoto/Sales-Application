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
        Schema::create('product_journal', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->float('amount', 4);
            $table->string('action');
            $table->text('description')->nullable();
            $table->dateTime('expiry_date');

            //relations
            $table->foreignId('warehouse_id')->constrained('warehouse');
            $table->foreignId('transactions_id')->constrained('transactions');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_journal');
    }
};
