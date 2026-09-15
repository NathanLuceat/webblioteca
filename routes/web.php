<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\ReservaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
Route::get('/livros/{id}', [LivroController::class, 'show'])->name('livros.show');

Route::get('/dashboard', function () {
    $emprestimosAtivos = \App\Models\Emprestimo::where('usuario_id', auth()->id())
        ->whereNull('data_devolucao')
        ->count();

    $reservasAtivas = \App\Models\ReservaSala::where('usuario_id', auth()->id())->count();

    return view('dashboard', [
        'emprestimosAtivos' => $emprestimosAtivos,
        'reservasAtivas' => $reservasAtivas,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/meus-emprestimos', [EmprestimoController::class, 'index'])->name('emprestimos.index');
    Route::post('/emprestimos', [EmprestimoController::class, 'store'])->name('emprestimos.store');
    Route::post('/emprestimos/{emprestimo}/devolver', [EmprestimoController::class, 'devolver'])->name('emprestimos.devolver');
});

Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');
Route::get('/salas/{id}', [SalaController::class, 'show'])->name('salas.show');

Route::middleware('auth')->group(function () {
    Route::get('/minhas-reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::post('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'painel'])->name('painel');
    Route::get('/livros', [AdminController::class, 'livrosForm'])->name('livros.form');
    Route::post('/livros', [AdminController::class, 'livrosStore'])->name('livros.store');
    Route::get('/salas', [AdminController::class, 'salasForm'])->name('salas.form');
    Route::post('/salas', [AdminController::class, 'salasStore'])->name('salas.store');
    Route::get('/reservas', [AdminController::class, 'reservas'])->name('reservas');
});

require __DIR__.'/auth.php';
