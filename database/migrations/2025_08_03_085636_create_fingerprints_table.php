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
        Schema::create('fingerprints', function (Blueprint $table) {
            $table->id();
            $table->string('fingerprint_hash', 64)->unique()->index(); // Уникальный fingerprint
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Связь с пользователем
            $table->json('browser_data')->nullable(); // User-Agent, разрешение, часовой пояс
            $table->timestamp('created_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerprints');
    }
};
