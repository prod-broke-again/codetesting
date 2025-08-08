<?php

namespace App\Repositories;

use App\Contracts\CodeRepositoryInterface;
use App\Models\Code;
use App\Models\User;
use App\Specifications\ActiveSnippetSpecification;
use App\Specifications\PublicSnippetSpecification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CodeRepository implements CodeRepositoryInterface
{
    public function create(array $data): Code
    {
        return Code::create($data);
    }

    public function findByHash(string $hash): ?Code
    {
        return Code::where('hash', $hash)->first();
    }

    public function findByUser(User $user): Collection
    {
        return $user->codes()->orderBy('created_at', 'desc')->get();
    }

    public function update(Code $code, array $data): Code
    {
        $code->update($data);
        return $code->fresh();
    }

    public function delete(Code $code): bool
    {
        return $code->delete();
    }

    public function incrementAccessCount(Code $code): void
    {
        $code->increment('access_count');
        $code->update(['last_accessed_at' => now()]);
    }

    /**
     * Получить публичные сниппеты
     */
    public function getPublicSnippets(int $limit = 10): Collection
    {
        $query = Code::query();
        
        $publicSpec = new PublicSnippetSpecification();
        $activeSpec = new ActiveSnippetSpecification();
        
        $query = $publicSpec->toQuery($query);
        $query = $activeSpec->toQuery($query);
        
        return $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Найти сниппеты по языку программирования
     */
    public function findByLanguage(string $language, int $limit = 10): Collection
    {
        $query = Code::where('language', $language);
        
        $publicSpec = new PublicSnippetSpecification();
        $activeSpec = new ActiveSnippetSpecification();
        
        $query = $publicSpec->toQuery($query);
        $query = $activeSpec->toQuery($query);
        
        return $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Найти популярные сниппеты
     */
    public function findPopular(int $limit = 10): Collection
    {
        $query = Code::query();
        
        $publicSpec = new PublicSnippetSpecification();
        $activeSpec = new ActiveSnippetSpecification();
        
        $query = $publicSpec->toQuery($query);
        $query = $activeSpec->toQuery($query);
        
        return $query->orderBy('access_count', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Получить сниппеты пользователя с применением фильтров
     */
    public function getUserSnippetsWithFilters(User $user, array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Code::where('user_id', $user->id)
            ->with('user');
            
        // Фильтрация по приватности
        if (!empty($filters['privacy'])) {
            $query->where('privacy', $filters['privacy']);
        }
        
        // Фильтрация по языку
        if (!empty($filters['language'])) {
            $query->where('language', $filters['language']);
        }
        
        // Поиск по содержимому
        if (!empty($filters['search'])) {
            $query->where('content', 'like', '%' . $filters['search'] . '%');
        }
        
        // Сортировка
        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'popular':
                $query->orderBy('access_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Количество сниппетов пользователя
     */
    public function countByUser(int $userId): int
    {
        return Code::where('user_id', $userId)->count();
    }

    /**
     * Количество сниппетов пользователя по приватности
     */
    public function countByUserAndPrivacy(int $userId, string $privacy): int
    {
        return Code::where('user_id', $userId)
            ->where('privacy', $privacy)
            ->count();
    }

    /**
     * Получить общее количество просмотров сниппетов пользователя
     */
    public function getTotalViewsByUser(int $userId): int
    {
        return Code::where('user_id', $userId)
            ->sum('access_count');
    }

    /**
     * Получить количество зашифрованных сниппетов пользователя
     */
    public function countEncryptedByUser(int $userId): int
    {
        return Code::where('user_id', $userId)
            ->where('is_encrypted', true)
            ->count();
    }

    /**
     * Получить последние сниппеты пользователя
     */
    public function getRecentByUser(int $userId, int $limit = 5): Collection
    {
        return Code::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Удалить все сниппеты пользователя
     */
    public function deleteAllByUser(int $userId): bool
    {
        return (bool) Code::where('user_id', $userId)->delete();
    }
}
