<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/livros', [LivroController::class, 'index']);
Route::get('/livros/{id}', [LivroController::class, 'show']);