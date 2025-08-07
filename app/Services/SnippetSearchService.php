<?php

namespace App\Services;

use App\Contracts\SnippetSearchServiceInterface;
use App\Models\Code;
use App\Models\User;
use App\Enums\ProgrammingLanguage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SnippetSearchService implements SnippetSearchServiceInterface
{
    /**
     * Поиск публичных сниппетов
     */
    public function searchPublicSnippets(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Code::where('privacy', 'public')
            ->where('is_guest', false)
            ->with('user');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * Поиск сниппетов пользователя
     */
    public function searchUserSnippets(User $user, array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Code::where('user_id', $user->id)
            ->with('user');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * Применить фильтры к запросу
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        // Фильтрация по языку программирования
        if (!empty($filters['language'])) {
            try {
                $language = ProgrammingLanguage::from($filters['language']);
                $query->where('language', $language->value);
            } catch (\ValueError $e) {
                // Игнорируем неверный язык
            }
        }

        // Фильтрация по приватности (только для пользовательских сниппетов)
        if (!empty($filters['privacy'])) {
            $query->where('privacy', $filters['privacy']);
        }

        // Поиск по содержимому
        if (!empty($filters['search'])) {
            $query->where('content', 'like', '%' . $filters['search'] . '%');
        }

        // Сортировка
        $sort = $filters['sort'] ?? 'latest';
        match ($sort) {
            'popular' => $query->orderBy('access_count', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }
    
    /**
     * Получить доступные языки программирования из существующих сниппетов
     */
    public function getAvailableLanguages(): array
    {
        return Code::where('privacy', 'public')
            ->where('is_guest', false)
            ->select('language')
            ->distinct()
            ->pluck('language')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
    }
    
    /**
     * Получить статистику по сниппетам
     */
    public function getStatistics(): array
    {
        $totalSnippets = Code::count();
        $publicSnippets = Code::where('privacy', 'public')->count();
        $privateSnippets = Code::where('privacy', 'private')->count();
        
        // Топ языков программирования
        $topLanguages = Code::where('privacy', 'public')
            ->where('is_guest', false)
            ->select('language')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('language')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->language->value => $item->count];
            })
            ->toArray();
            
        // Активность по дням за последнюю неделю
        $weeklyActivity = Code::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
            
        return [
            'total_snippets' => $totalSnippets,
            'public_snippets' => $publicSnippets,
            'private_snippets' => $privateSnippets,
            'top_languages' => $topLanguages,
            'weekly_activity' => $weeklyActivity,
        ];
    }
}
