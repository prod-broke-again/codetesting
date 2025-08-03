<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Показать дашборд пользователя
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/auth');
        }

        // Статистика пользователя
        $stats = [
            'total_snippets' => Code::where('user_id', $user->id)->count(),
            'public_snippets' => Code::where('user_id', $user->id)->where('privacy', 'public')->count(),
            'private_snippets' => Code::where('user_id', $user->id)->where('privacy', 'private')->count(),
            'unlisted_snippets' => Code::where('user_id', $user->id)->where('privacy', 'unlisted')->count(),
            'total_views' => Code::where('user_id', $user->id)->sum('access_count'),
            'encrypted_snippets' => Code::where('user_id', $user->id)->where('is_encrypted', true)->count(),
        ];

        // Последние сниппеты
        $recentSnippets = Code::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Популярные сниппеты
        $popularSnippets = Code::where('user_id', $user->id)
            ->orderBy('access_count', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentSnippets' => $recentSnippets,
            'popularSnippets' => $popularSnippets,
            'title' => 'Дашборд',
            'description' => 'Обзор вашей активности'
        ]);
    }
}
