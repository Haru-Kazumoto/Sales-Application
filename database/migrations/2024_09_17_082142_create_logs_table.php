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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // ID user yang melakukan perubahan
            $table->string('action'); // 'create', 'update', 'delete'
            $table->string('model_type'); // Nama model, misalnya 'Product'
            $table->unsignedBigInteger('model_id'); // ID dari model yang diubah
            $table->json('previous_data')->nullable(); // Data sebelum perubahan
            $table->json('new_data')->nullable(); // Data setelah perubahan
            $table->json('changed_fields')->nullable(); // Field yang berubah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
