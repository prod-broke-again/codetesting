<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\MySnippetsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ExportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Главная страница (создание сниппета)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Обзор публичных сниппетов
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

// Мои сниппеты (требует авторизации)
Route::get('/my-snippets', [MySnippetsController::class, 'index'])->name('my-snippets');

// Дашборд (требует авторизации)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Профиль пользователя (требует авторизации)
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Просмотр сниппета
Route::get('/code/{hash}', [CodeController::class, 'show'])->name('code.show');

// Авторизация (единая страница)
Route::get('/auth', [AuthController::class, 'showAuth'])->name('auth.show');

// Обработка форм авторизации
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Socialite маршруты
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/github', [SocialiteController::class, 'redirectToGithub'])->name('auth.github');
Route::get('/auth/github/callback', [SocialiteController::class, 'handleGithubCallback'])->name('auth.github.callback');

// Экспорт сниппетов (требует авторизации)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/export/json', [ExportController::class, 'exportJson'])->name('export.json');
    Route::get('/export/zip', [ExportController::class, 'exportZip'])->name('export.zip');
});
