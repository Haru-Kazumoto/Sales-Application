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
        Schema::table('user_target', function (Blueprint $table) {
            $table->tinyInteger('at_month')->nullable()->after('period');
            $table->float('annual_target')->default(0)->change();
            $table->float('monthly_target')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_target', function (Blueprint $table) {
            $table->dropColumn('at_month');
        });
    }
};
