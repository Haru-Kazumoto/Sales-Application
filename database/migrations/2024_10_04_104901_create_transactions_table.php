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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('document_code');
            $table->string('correlation_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('term_of_payment')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->text('description')->nullable();
            $table->float('sub_total')->nullable();
            $table->float('total')->nullable();
            $table->float('tax_amount')->nullable();

            //relations
            $table->foreignId('transaction_type_id')->constrained('transaction_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
