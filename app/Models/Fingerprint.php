<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fingerprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'fingerprint_hash',
        'user_id',
        'browser_data',
        'created_at',
        'last_seen_at',
    ];

    protected $casts = [
        'browser_data' => 'array',
        'created_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    /**
     * Отношение к пользователю
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Отношение к сниппетам
     */
    public function codes(): HasMany
    {
        return $this->hasMany(Code::class);
    }

    /**
     * Генерация fingerprint хеша
     */
    public static function generateHash(array $browserData): string
    {
        $data = json_encode($browserData);
        return 'fp_' . substr(md5($data), 0, 16); // 16 символов + префикс
    }

    /**
     * Обновление времени последнего использования
     */
    public function updateLastSeen(): void
    {
        $this->update(['last_seen_at' => now()]);
    }

    /**
     * Получение связанных сниппетов
     */
    public function getRelatedCodes(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->codes()->orderBy('created_at', 'desc')->get();
    }
}
