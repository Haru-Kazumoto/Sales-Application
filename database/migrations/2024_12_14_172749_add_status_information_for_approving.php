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
            $table->string('status')->nullable()->after('transaction_type_id');
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->dateTime('approve_at')->nullable()->after('approved_by');
            $table->text('approval_notes')->nullable()->after('approve_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_by', 'approve_at', 'approval_notes']);
        });
    }
};
