<?php

namespace App\Http\Controllers\Api;

use App\Contracts\SnippetSearchServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function __construct(
        private SnippetSearchServiceInterface $searchService
    ) {}

    /**
     * Получить общую статистику платформы
     */
    public function index(): JsonResponse
    {
        $statistics = $this->searchService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Получить доступные языки программирования
     */
    public function languages(): JsonResponse
    {
        $languages = $this->searchService->getAvailableLanguages();

        return response()->json([
            'success' => true,
            'data' => $languages
        ]);
    }
}
