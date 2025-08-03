<?php

namespace App\Services;

use App\Models\Code;
use App\Models\User;
use App\Models\Fingerprint;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class CodeService
{
    /**
     * Создать новый сниппет
     */
    public function createSnippet(array $data, ?User $user = null): Code
    {
        $code = new Code();
        $code->hash = $this->generateHash();
        $code->content = $data['content'];
        $code->language = $data['language'];
        $code->theme = $data['theme'];
        $code->is_encrypted = $data['is_encrypted'] ?? false;
        $code->is_guest = $user === null;
        $code->user_id = $user?->id;
        
        // Шифрование контента если нужно
        if ($code->is_encrypted) {
            $code->content = Crypt::encryptString($data['content']);
        }
        
        // Генерация токена для гостевых сниппетов
        if ($code->is_guest) {
            $code->edit_token = $this->generateEditToken();
        }
        
        // Установка времени истечения
        if (isset($data['expires_at']) && $data['expires_at']) {
            $code->expires_at = $data['expires_at'];
        }
        
        // Связывание с fingerprint
        $fingerprint = $this->getOrCreateFingerprint();
        if ($fingerprint) {
            $code->fingerprint_id = $fingerprint->id;
        }
        
        $code->save();
        
        return $code;
    }
    
    /**
     * Обновить сниппет
     */
    public function updateSnippet(Code $code, array $data): Code
    {
        $code->content = $data['content'];
        $code->language = $data['language'];
        $code->theme = $data['theme'];
        
        if (isset($data['is_encrypted'])) {
            $code->is_encrypted = $data['is_encrypted'];
            if ($code->is_encrypted) {
                $code->content = Crypt::encryptString($data['content']);
            }
        }
        
        if (isset($data['expires_at'])) {
            $code->expires_at = $data['expires_at'];
        }
        
        $code->save();
        
        return $code;
    }
    
    /**
     * Удалить сниппет
     */
    public function deleteSnippet(Code $code): bool
    {
        return $code->delete();
    }
    
    /**
     * Найти сниппет по хешу
     */
    public function findByHash(string $hash): ?Code
    {
        return Code::where('hash', $hash)->first();
    }
    
    /**
     * Проверить доступ к сниппету
     */
    public function canAccess(Code $code, ?User $user = null, ?string $editToken = null): bool
    {
        // Проверка истечения срока
        if ($code->isExpired()) {
            return false;
        }
        
        // Владелец всегда имеет доступ
        if ($user && $code->user_id === $user->id) {
            return true;
        }
        
        // Проверка токена для гостевых сниппетов
        if ($code->is_guest && $editToken && $code->edit_token === $editToken) {
            return true;
        }
        
        // Публичный доступ для просмотра
        return true;
    }
    
    /**
     * Проверить права на редактирование
     */
    public function canEdit(Code $code, ?User $user = null, ?string $editToken = null): bool
    {
        // Владелец всегда может редактировать
        if ($user && $code->user_id === $user->id) {
            return true;
        }
        
        // Проверка токена для гостевых сниппетов
        if ($code->is_guest && $editToken && $code->edit_token === $editToken) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Увеличить счетчик просмотров
     */
    public function incrementAccessCount(Code $code): void
    {
        $code->increment('access_count');
        $code->update(['last_accessed_at' => now()]);
    }
    
    /**
     * Получить или создать fingerprint
     */
    private function getOrCreateFingerprint(): ?Fingerprint
    {
        $fingerprintData = request()->header('X-Fingerprint');
        
        if (!$fingerprintData) {
            return null;
        }
        
        $fingerprintHash = Hash::make($fingerprintData);
        
        return Fingerprint::firstOrCreate(
            ['fingerprint_hash' => $fingerprintHash],
            [
                'browser_data' => [
                    'user_agent' => request()->userAgent(),
                    'ip' => request()->ip(),
                    'fingerprint' => $fingerprintData
                ],
                'last_seen_at' => now()
            ]
        );
    }
    
    /**
     * Генерировать уникальный хеш
     */
    private function generateHash(): string
    {
        do {
            $hash = Str::random(16);
        } while (Code::where('hash', $hash)->exists());
        
        return $hash;
    }
    
    /**
     * Генерировать токен для редактирования
     */
    private function generateEditToken(): string
    {
        return 'tk_' . Str::random(32);
    }
} 