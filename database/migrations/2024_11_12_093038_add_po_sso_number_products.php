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
        Schema::table('product_journal', function (Blueprint $table) {
            $table->string('po_number')->nullable()->after('expiry_date');
            $table->string('sso_number')->nullable()->after('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_journal', function (Blueprint $table) {
            $table->dropColumn(['po_number', 'sso_number']);
        });
    }
};