<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CodeController;

// Главная страница - создание сниппета
Route::get('/', [HomeController::class, 'index']);

// Просмотр сниппета
Route::get('/code/{hash}', [CodeController::class, 'show']);

// Создание сниппета (отдельная страница)
Route::get('/create', [CodeController::class, 'create']);
