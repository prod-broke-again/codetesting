<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialiteController;

// Главная страница - создание сниппета
Route::get('/', [HomeController::class, 'index']);

// Просмотр сниппета
Route::get('/code/{hash}', [CodeController::class, 'show']);

// Создание сниппета (отдельная страница)
Route::get('/create', [CodeController::class, 'create']);

// Маршруты авторизации
Route::prefix('auth')->group(function () {
    // Формы авторизации
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    
    // API авторизации
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Socialite маршруты
    Route::get('/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
    Route::get('/github', [SocialiteController::class, 'redirectToGithub'])->name('auth.github');
    Route::get('/github/callback', [SocialiteController::class, 'handleGithubCallback']);
    
    // Получение связанных сниппетов
    Route::get('/related-snippets', [SocialiteController::class, 'getRelatedSnippets']);
});

// Защищенные маршруты (требуют авторизации)
Route::middleware('auth')->group(function () {
    // TODO: Добавить защищенные маршруты для премиум функций
});
