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
            $table->integer('id_akun_beli')->after('product_type_id')->nullable();
            $table->integer('id_akun_jual')->after('product_type_id')->nullable();
            $table->integer('id_akun_persediaan')->after('product_type_id')->nullable();
            $table->integer('id_akun_jual_jalan')->after('product_type_id')->nullable();
            $table->integer('id_akun_beli_jalan')->after('product_type_id')->nullable();
            $table->integer('id_pajak_jual')->after('product_type_id')->nullable();
            $table->integer('id_pajak_beli')->after('product_type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'id_akun_beli',
                'id_akun_jual',
                'id_akun_persediaan',
                'id_akun_jual_jalan',
                'id_akun_beli_jalan',
                'id_pajak_jual',
                'id_pajak_beli',
            ]);
        });
    }
};
