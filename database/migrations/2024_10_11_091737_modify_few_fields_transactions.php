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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('term_of_payment')->nullable()->change();
            $table->dateTime('due_date')->nullable()->change();
            $table->float('sub_total')->nullable()->change();
            $table->float('total')->nullable()->change();
            $table->float('tax_amount')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('term_of_payment')->nullable(false)->change();
            $table->dateTime('due_date')->nullable(false)->change();
            $table->float('sub_total')->nullable(false)->change();
            $table->float('total')->nullable(false)->change();
            $table->float('tax_amount')->nullable(false)->change();
        });
    }
};
