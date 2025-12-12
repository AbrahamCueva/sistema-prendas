<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GarmentController;
use App\Http\Controllers\MotiveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StitchingLineController;
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

    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
});

Route::middleware('auth')->group(function () {
    // Dashboard principal, una vez que el usuario inicia sesión
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['verified'])->name('dashboard');

    // Rutas de CRUD para las entidades
    Route::resource('clients', ClientController::class);
    Route::resource('stitching-lines', StitchingLineController::class);
    Route::resource('motives', MotiveController::class);

    // ... Las rutas del CRUD principal (Garments) se agregarán más adelante
    Route::get('garments/export', [GarmentController::class, 'export'])->name('garments.export');
    Route::resource('garments', GarmentController::class);

    // Ruta específica para la entrega
    Route::put('garments/{garment}/deliver', [GarmentController::class, 'deliver'])->name('garments.deliver');
});

require __DIR__.'/auth.php';
