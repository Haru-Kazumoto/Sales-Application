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
        Schema::table('products', function (Blueprint $table) {
            // Ubah tipe data kolom redemp_price dari float ke decimal dengan presisi 17 dan skala 11
            $table->decimal('redemp_price', 17, 11)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Kembalikan tipe data kolom redemp_price ke float
            $table->float('redemp_price')->default(0)->change();
        });
    }
};
