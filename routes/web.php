<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialiteController;

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

// Просмотр сниппета
Route::get('/code/{hash}', [CodeController::class, 'show'])->name('code.show');

// Авторизация (единая страница)
Route::get('/auth', [AuthController::class, 'showAuth'])->name('auth.show');

// Обработка форм авторизации
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Socialite маршруты
Route::get('/auth/{provider}', [SocialiteController::class, 'redirect'])->name('auth.socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('auth.socialite.callback');

// API маршруты для сниппетов
Route::prefix('api')->group(function () {
    Route::post('/codes', [CodeController::class, 'store'])->name('api.codes.store');
    Route::get('/codes/{hash}', [CodeController::class, 'show'])->name('api.codes.show');
});
