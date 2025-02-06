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
        Schema::table('parties', function (Blueprint $table) {
            $table->string('pic_2')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('legality')->nullable()->change();
            $table->integer('term_payment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->dropColumn('pic_2');
            $table->dropColumn('phone_2');
            $table->string('legality')->nullable(false)->change();
            $table->integer('term_payment')->nullable(false)->change();
        });
    }
};
