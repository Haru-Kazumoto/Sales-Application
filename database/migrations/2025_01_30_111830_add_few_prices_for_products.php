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
            $table->decimal('percentage', 5, 2)->default(0);
            // $table->float('margin_retail')->nullable();
            // $table->float('margin_grosir')->nullable();
            // $table->float('margin_end_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('percentage');
            // $table->dropColumn('margin_retail');
            // $table->dropColumn('margin_grosir');
            // $table->dropColumn('margin_end_user');
        });
    }
};
