<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// Route::prefix('printers')->name('printers.')->group(function () {
//     Route::get('/', [PrinterController::class, 'index'])->name('index');
//     Route::post('/', [PrinterController::class, 'store'])->name('store');
//     Route::put('/{printer}', [PrinterController::class, 'update'])->name('update');
//     Route::delete('/{printer}', [PrinterController::class, 'destroy'])->name('destroy');
// });
// Route::get('/printers', [PrinterController::class, 'index'])->name('printers.index');
// Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
