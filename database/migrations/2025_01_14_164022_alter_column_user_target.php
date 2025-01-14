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
            $table->float('annual_target')->nullable()->change();
            $table->float('monthly_target')->nullable()->change();
            $table->year('period')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_target', function (Blueprint $table) {
            $table->float('annual_target')->change();
            $table->float('monthly_target')->change();
            $table->year('period')->change();
        });
    }
};
