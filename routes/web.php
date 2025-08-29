<?php

use App\Http\Controllers\PrinterController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::get('/printers', [PrinterController::class, 'index'])->name('printers.index');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
