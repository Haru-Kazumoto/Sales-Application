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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('type_parties');
            $table->string('legality')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('handphone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('npwp')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->text('description')->nullable();
            $table->integer('term_payment')->nullable();
             
            $table->foreignId('parties_group_id')->constained('parties_group');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
