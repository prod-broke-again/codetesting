<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ProgrammingLanguage;
use App\Enums\SnippetPrivacy;
use App\Enums\SnippetStatus;
use App\ValueObjects\SnippetHash;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash',
        'content',
        'language',
        'theme',
        'is_encrypted',
        'privacy',
        'password',
        'user_id',
        'is_guest',
        'edit_token',
        'access_count',
        'last_accessed_at',
        'expires_at',
        'fingerprint_id',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
        'is_guest' => 'boolean',
        'last_accessed_at' => 'datetime',
        'expires_at' => 'datetime',
        'language' => ProgrammingLanguage::class,
        'privacy' => SnippetPrivacy::class,
    ];

    /**
     * Отношение к пользователю
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Отношение к fingerprint
     */
    public function fingerprint(): BelongsTo
    {
        return $this->belongsTo(Fingerprint::class);
    }

    /**
     * Генерация уникального хеша для сниппета
     */
    public static function generateHash(): SnippetHash
    {
        return SnippetHash::generate();
    }

    /**
     * Генерация токена для редактирования
     */
    public static function generateEditToken(): string
    {
        return 'tk_' . bin2hex(random_bytes(16)); // 32 символа + префикс
    }

    /**
     * Проверка истечения срока действия
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Увеличение счетчика просмотров
     */
    public function incrementAccessCount(): void
    {
        $this->increment('access_count');
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Получить статус сниппета
     */
    public function getStatus(): SnippetStatus
    {
        if ($this->isExpired()) {
            return SnippetStatus::EXPIRED;
        }

        if ($this->privacy === SnippetPrivacy::PRIVATE) {
            return SnippetStatus::PRIVATE;
        }

        return SnippetStatus::ACTIVE;
    }
}
