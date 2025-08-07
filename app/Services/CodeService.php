<?php

namespace App\Services;

use App\Contracts\Encryptable;
use App\Events\CodeAccessed;
use App\Enums\SnippetPrivacy;
use App\Models\Code;
use App\Models\Fingerprint;
use App\Models\User;
use App\Repositories\CodeRepository;
use App\ValueObjects\SnippetHash;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CodeService implements Encryptable
{
    public function __construct(
        private CodeRepository $codeRepository
    ) {}

    /**
     * Создать новый сниппет
     */
    public function createSnippet(array $data, ?User $user = null): Code
    {
        // Генерируем уникальный хеш
        $hash = $this->generateHash();
        
        // Подготавливаем данные
        $snippetData = [
            'hash' => $hash,
            'content' => $data['content'],
            'language' => $data['language'],
            'theme' => $data['theme'] ?? 'vs-dark',
            'is_encrypted' => $data['is_encrypted'] ?? false,
            'privacy' => $data['privacy'] ?? SnippetPrivacy::PUBLIC,
            'is_guest' => !$user,
            'edit_token' => $this->generateEditToken(),
            'user_id' => $user?->id,
            'expires_at' => isset($data['expires_at']) ? $data['expires_at'] : null,
        ];

        // Шифруем контент если нужно
        if ($snippetData['is_encrypted']) {
            $snippetData['content'] = $this->encrypt($snippetData['content']);
        }

        // Создаем сниппет
        $code = $this->codeRepository->create($snippetData);

        // Связываем с fingerprint если есть
        if ($fingerprint = $this->getOrCreateFingerprint()) {
            $code->update(['fingerprint_id' => $fingerprint->id]);
        }

        return $code;
    }

    /**
     * Обновить сниппет
     */
    public function updateSnippet(Code $code, array $data): Code
    {
        $updateData = [];

        if (isset($data['content'])) {
            $updateData['content'] = $code->is_encrypted 
                ? $this->encrypt($data['content']) 
                : $data['content'];
        }

        if (isset($data['language'])) {
            $updateData['language'] = $data['language'];
        }

        if (isset($data['theme'])) {
            $updateData['theme'] = $data['theme'];
        }

        if (isset($data['privacy'])) {
            $updateData['privacy'] = $data['privacy'];
        }

        if (isset($data['expires_at'])) {
            $updateData['expires_at'] = $data['expires_at'];
        }

        return $this->codeRepository->update($code->id, $updateData);
    }

    /**
     * Удалить сниппет
     */
    public function deleteSnippet(Code $code): bool
    {
        return $this->codeRepository->delete($code->id);
    }

    /**
     * Найти сниппет по хешу
     */
    public function findByHash(string $hash): ?Code
    {
        return $this->codeRepository->findByHash($hash);
    }

    /**
     * Проверить доступ к сниппету
     */
    public function canAccess(Code $code, ?User $user = null, ?string $editToken = null): bool
    {
        // Проверяем истечение срока
        if ($code->isExpired()) {
            return false;
        }

        // Приватные сниппеты только для владельца
        if ($code->privacy === SnippetPrivacy::PRIVATE) {
            return $user && $code->user_id === $user->id;
        }

        // Непубличные сниппеты для владельца или с токеном
        if ($code->privacy === SnippetPrivacy::UNLISTED) {
            if ($user && $code->user_id === $user->id) {
                return true;
            }
            return $editToken && $code->edit_token === $editToken;
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
     * Проверить права на удаление
     */
    public function canDelete(Code $code, ?User $user = null, ?string $editToken = null): bool
    {
        return $this->canEdit($code, $user, $editToken);
    }

    /**
     * Увеличить счетчик просмотров
     */
    public function incrementAccessCount(Code $code, ?User $user = null): void
    {
        $code->increment('access_count');
        $code->update(['last_accessed_at' => now()]);

        CodeAccessed::dispatch($code, $user, request()->ip(), request()->userAgent());
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
     * Имплементация метода encrypt интерфейса Encryptable
     */
    public function encrypt(string $data): string
    {
        return Crypt::encryptString($data);
    }

    /**
     * Имплементация метода decrypt интерфейса Encryptable
     */
    public function decrypt(string $encryptedData): string
    {
        try {
            return Crypt::decryptString($encryptedData);
        } catch (Exception $e) {
            return 'Ошибка расшифровки контента';
        }
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