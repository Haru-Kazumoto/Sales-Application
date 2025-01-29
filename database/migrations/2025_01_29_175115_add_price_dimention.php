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
        Schema::table('dimention', function (Blueprint $table) {
            $table->float('price_dimention')->default(0)->after('max_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dimention', function (Blueprint $table) {
            $table->dropColumn('price_dimention');
        });
    }
};
