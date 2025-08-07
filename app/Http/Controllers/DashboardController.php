<?php

namespace App\Http\Controllers;

use App\Services\UserStatsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        private UserStatsService $statsService
    ) {}

    /**
     * Показать дашборд пользователя
     */
    public function index(Request $request)
    {
        $this->requireAuth();
        $user = $request->user();

        $stats = $this->statsService->getUserStats($user);
        $recentSnippets = $this->statsService->getRecentSnippets($user);
        $popularSnippets = $this->statsService->getPopularSnippets($user);

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentSnippets' => $recentSnippets,
            'popularSnippets' => $popularSnippets,
            'title' => 'Дашборд',
            'description' => 'Обзор вашей активности'
        ]);
    }
}
