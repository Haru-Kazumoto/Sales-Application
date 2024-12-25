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
            $table->text('return_address')->after('address')->nullable();
            $table->string('owner')->after('return_address')->nullable();
            $table->string('pic')->after('owner')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->dropColumn('return_address');
            $table->dropColumn('owner');
            $table->dropColumn('pic');
        });
    }
};
