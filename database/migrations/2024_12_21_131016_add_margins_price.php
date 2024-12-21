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
            // create column margin_retail, margin_end_user
            $table->float('margin_retail')->nullable()->after('package');
            $table->float('margin_end_user')->nullable()->after('margin_retail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('margin_retail');
            $table->dropColumn('margin_end_user');
        });
    }
};
