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
        Schema::create('sub_shipping', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('it use for sub shipping such as DEPO has SUB DIST, DIRECT DEPO has regional city and many more');
            $table->foreignId('shipping_id')
                ->constrained('shipping')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_shipping');
    }
};
