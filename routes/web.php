<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');
    Route::post('/', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::prefix('printers')->name('printers.')->group(function () {
    Route::get('/', [PrinterController::class, 'index'])->name('index');
    Route::post('/', [PrinterController::class, 'store'])->name('store');
    Route::put('/{printer}', [PrinterController::class, 'update'])->name('update');
    Route::delete('/{printer}', [PrinterController::class, 'destroy'])->name('destroy');
    Route::post('/{printer}/test', [PrinterController::class, 'testPrint'])->name('test');
});

Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::put('/', [SettingsController::class, 'update'])->name('update');

    Route::prefix('update')->name('update.')->group(function () {
        Route::post('/check', [UpdateController::class, 'checkForUpdates'])->name('check');
        Route::post('/install', [UpdateController::class, 'installUpdate'])->name('install');
    });
});
