<?php

namespace App\Services;

use App\Models\User;
use App\Models\Code;
use App\Contracts\CodeRepositoryInterface;

class UserStatsService
{
    public function __construct(
        private CodeRepositoryInterface $codeRepository
    ) {}

    /**
     * Получить статистику пользователя
     */
    public function getUserStats(User $user): array
    {
        $baseQuery = Code::where('user_id', $user->id);

        return [
            'total_snippets' => $baseQuery->count(),
            'public_snippets' => (clone $baseQuery)->where('privacy', 'public')->count(),
            'private_snippets' => (clone $baseQuery)->where('privacy', 'private')->count(), 
            'unlisted_snippets' => (clone $baseQuery)->where('privacy', 'unlisted')->count(),
            'total_views' => $baseQuery->sum('access_count'),
            'encrypted_snippets' => (clone $baseQuery)->where('is_encrypted', true)->count(),
        ];
    }

    /**
     * Получить последние сниппеты пользователя
     */
    public function getRecentSnippets(User $user, int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Code::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Получить популярные сниппеты пользователя
     */
    public function getPopularSnippets(User $user, int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Code::where('user_id', $user->id)
            ->orderBy('access_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
