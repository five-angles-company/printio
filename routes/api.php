<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Native\Laravel\Facades\Settings;

Route::get('/device-id', function (Request $request) {
    return Settings::get('app.unique_id');
});
