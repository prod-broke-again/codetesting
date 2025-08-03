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
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique()->index(); // Уникальный идентификатор сниппета
            $table->longText('content'); // Зашифрованный код
            $table->string('language')->default('php'); // Язык программирования
            $table->string('theme')->default('vs-dark'); // Тема оформления
            $table->boolean('is_encrypted')->default(false); // Флаг шифрования
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Связь с пользователем
            $table->boolean('is_guest')->default(false); // Флаг гостевого сниппета
            $table->string('edit_token', 64)->nullable()->index(); // Токен для редактирования
            $table->integer('access_count')->default(0); // Количество просмотров
            $table->timestamp('last_accessed_at')->nullable(); // Время последнего просмотра
            $table->timestamp('expires_at')->nullable(); // Время истечения
            $table->unsignedBigInteger('fingerprint_id')->nullable(); // Связь с fingerprint (без внешнего ключа)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codes');
    }
};
