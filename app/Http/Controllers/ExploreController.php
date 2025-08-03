<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExploreController extends Controller
{
    /**
     * Показать страницу обзора публичных сниппетов
     */
    public function index(Request $request)
    {
        $query = Code::where('privacy', 'public')
            ->where('is_guest', false)
            ->with('user');

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

        return Inertia::render('Explore', [
            'snippets' => $snippets,
            'filters' => [
                'language' => $request->language,
                'search' => $request->search,
                'sort' => $sort
            ],
            'title' => 'Обзор сниппетов',
            'description' => 'Изучайте код других разработчиков'
        ]);
    }
}
