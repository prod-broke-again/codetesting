<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Главная страница - создание сниппета
Route::get('/', function () {
    return Inertia::render('Home');
});

// Просмотр сниппета
Route::get('/code/{hash}', function (string $hash) {
    return Inertia::render('CodeView', [
        'hash' => $hash
    ]);
});

// Создание сниппета (отдельная страница)
Route::get('/create', function () {
    return Inertia::render('CodeCreate');
});
