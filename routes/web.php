<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DespachoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de Despachos
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/despachos', [DespachoController::class, 'index'])->name('despachos.index');
    Route::get('/despachos/importar', [DespachoController::class, 'import'])->name('despachos.import');
    Route::post('/despachos', [DespachoController::class, 'store'])->name('despachos.store');
    Route::get('/despachos/{despacho}', [DespachoController::class, 'show'])->name('despachos.show');
    
    // Exportación COMPLETA (existente)
    Route::get('/despachos/{despacho}/pdf', [DespachoController::class, 'generatePDF'])->name('despachos.pdf');
    Route::get('/despachos/{despacho}/llaves', [DespachoController::class, 'generateImagenLlaves'])->name('despachos.llaves');
    
    // Exportación PERSONALIZADA (nueva) - Adicionales
    Route::get('/despachos/{despacho}/pdf-personalizado', [DespachoController::class, 'generatePDFPersonalizado'])->name('despachos.pdf.personalizado');
    Route::get('/despachos/{despacho}/llaves-personalizadas', [DespachoController::class, 'generateImagenLlavesPersonalizadas'])->name('despachos.llaves.personalizadas');
});

// Gestión de Usuarios (solo admin)
Route::middleware(['auth', 'role:admin'])->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
});

// Reportes (solo admin) - ¡CORREGIDO!
Route::middleware(['auth', 'role:admin'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/despachos-por-usuario', [ReportController::class, 'despachosPorUsuario'])
        ->name('despachos-por-usuario');
    Route::get('/historico-completo', [ReportController::class, 'historicoCompleto'])
        ->name('historico-completo');
});

require __DIR__.'/auth.php';
