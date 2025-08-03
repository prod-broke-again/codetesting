<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MySnippetsController extends Controller
{
    /**
     * Показать страницу моих сниппетов
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/auth');
        }

        $query = Code::where('user_id', $user->id)
            ->with('user');

        // Фильтрация по приватности
        if ($request->has('privacy') && $request->privacy) {
            $query->where('privacy', $request->privacy);
        }

        // Фильтрация по языку
        if ($request->has('language') && $request->language) {
            $query->where('language', $request->language);
        }

        // Поиск по содержимому
        if ($request->has('search') && $request->search) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        // Сортировка
        $sort = $request->get('sort', 'latest');
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

        $snippets = $query->paginate(20);

        return Inertia::render('MySnippets', [
            'snippets' => $snippets,
            'filters' => [
                'privacy' => $request->privacy,
                'language' => $request->language,
                'search' => $request->search,
                'sort' => $sort
            ],
            'title' => 'Мои сниппеты',
            'description' => 'Управляйте своими сниппетами'
        ]);
    }
}
