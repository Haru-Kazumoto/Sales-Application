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
        Schema::create('payment_promos', function (Blueprint $table) {
            $table->id();
            $table->string('payment_status');
            $table->dateTime('payment_date');
            $table->float('payment_nominal');

            $table->foreignId('promo_claim_id')->constrained('promo_claims')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_promos');
    }
};
