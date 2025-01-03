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
        Schema::create('trade_promo', function (Blueprint $table) {
            $table->id('id');
            $table->string('grosir_account');
            $table->integer('discount_price');
            $table->integer('quota');
            $table->boolean('is_active');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_promo');
    }
};
