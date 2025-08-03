<?php

use Illuminate\Support\Facades\Route;

// Главная страница - отдаем Vue приложение
Route::get('/', function () {
    return view('welcome');
});

// Роуты для Vue приложения - все остальные запросы отдаем в Vue
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
