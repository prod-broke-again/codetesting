<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Показать главную страницу с формой создания сниппета
     */
    public function index()
    {
        return Inertia::render('Home', [
            'title' => 'Делитесь кодом безопасно',
            'description' => 'Создавайте зашифрованные сниппеты кода и делитесь ими с коллегами'
        ]);
    }
}
