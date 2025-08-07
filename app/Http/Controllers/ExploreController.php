<?php

namespace App\Http\Controllers;

use App\Contracts\SnippetSearchServiceInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExploreController extends Controller
{
    public function __construct(
        private SnippetSearchServiceInterface $searchService
    ) {}

    /**
     * Показать страницу обзора публичных сниппетов
     */
    public function index(Request $request)
    {
        $filters = [
            'language' => $request->input('language'),
            'search' => $request->input('search'),
            'sort' => $request->input('sort', 'latest'),
        ];

        $snippets = $this->searchService->searchPublicSnippets($filters);

        return Inertia::render('Explore', [
            'snippets' => $snippets,
            'filters' => $filters,
            'title' => 'Обзор сниппетов',
            'description' => 'Изучайте код других разработчиков'
        ]);
    }
}
