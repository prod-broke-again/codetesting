<?php

namespace App\Http\Controllers;

use App\Contracts\CodeRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MySnippetsController extends Controller
{
    public function __construct(
        private CodeRepositoryInterface $codeRepository
    ) {}
    
    /**
     * Показать страницу моих сниппетов
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/auth');
        }

        $filters = [
            'privacy' => $request->input('privacy'),
            'language' => $request->input('language'),
            'search' => $request->input('search'),
            'sort' => $request->input('sort', 'latest'),
        ];

        $snippets = $this->codeRepository->getUserSnippetsWithFilters($user, $filters);

        return Inertia::render('MySnippets', [
            'snippets' => $snippets,
            'filters' => $filters,
            'title' => 'Мои сниппеты',
            'description' => 'Управляйте своими сниппетами'
        ]);
    }
}
