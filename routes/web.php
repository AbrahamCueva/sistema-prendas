<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GarmentController;
use App\Http\Controllers\MotiveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
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

    Route::get('/configuracion/sitio', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/configuracion/sitio', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['verified'])->name('dashboard');
    Route::resource('clients', ClientController::class);
    Route::resource('stitching-lines', StitchingLineController::class);
    Route::resource('motives', MotiveController::class);
    Route::get('garments/export', [GarmentController::class, 'export'])->name('garments.export');
    Route::resource('garments', GarmentController::class);
    Route::put('garments/{garment}/deliver', [GarmentController::class, 'deliver'])->name('garments.deliver');
    Route::get('/auditoria', [ActivityLogController::class, 'index'])->name('audit.index');
    Route::get('/auditoria/{activity}', [ActivityLogController::class, 'show'])->name('audit.show');
    Route::get('/auditoria/{activity}/reporte', [ActivityLogController::class, 'report'])
        ->name('audit.report');
    Route::get('garments/{garment}/add-stock', [GarmentController::class, 'showAddStockForm'])
        ->name('garments.add-stock.form');

    // Procesa el formulario y actualiza la cantidad_in del lote
    Route::post('garments/{garment}/add-stock', [GarmentController::class, 'processAddStock'])
        ->name('garments.add-stock.process');
});

require __DIR__.'/auth.php';
