<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SnippetSearchServiceInterface
{
    /**
     * Поиск публичных сниппетов с фильтрацией
     */
    public function searchPublicSnippets(array $filters, int $perPage = 20): LengthAwarePaginator;
    
    /**
     * Поиск сниппетов пользователя с фильтрацией
     */
    public function searchUserSnippets(User $user, array $filters, int $perPage = 20): LengthAwarePaginator;
    
    /**
     * Получить доступные языки программирования из существующих сниппетов
     */
    public function getAvailableLanguages(): array;
    
    /**
     * Получить статистику по сниппетам
     */
    public function getStatistics(): array;
}
