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
            $table->dropColumn('supplier');
            // $table->unsignedBigInteger('supplier_id')->after('category')->nullable();

            $table->foreignId('supplier_id')
                ->after('category')
                ->nullable()
                ->constrained('parties')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            
            // Hapus foreign key constraint
            $table->dropForeign(['supplier_id']); // Pastikan nama kolom sesuai
            
            // Hapus kolom supplier_id
            $table->dropColumn('supplier_id');
            
            //menambahkan kembali supplier
            $table->string('supplier')->after('category')->nullable();
        });
    }
};
