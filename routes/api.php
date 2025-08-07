<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CodeController;
use App\Http\Controllers\Api\StatsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API маршруты для сниппетов
Route::prefix('snippets')->group(function () {
    // Создание сниппета (публичный доступ)
    Route::post('/', [CodeController::class, 'store']);
    
    // Просмотр сниппета (публичный доступ)
    Route::get('/{hash}', [CodeController::class, 'show']);
    
    // Обновление сниппета (требует токен)
    Route::put('/{hash}', [CodeController::class, 'update']);
    
    // Удаление сниппета (требует токен)
    Route::delete('/{hash}', [CodeController::class, 'destroy']);
});

// API маршруты для статистики
Route::prefix('stats')->group(function () {
    // Общая статистика платформы
    Route::get('/', [StatsController::class, 'index']);
    
    // Доступные языки программирования
    Route::get('/languages', [StatsController::class, 'languages']);
});
