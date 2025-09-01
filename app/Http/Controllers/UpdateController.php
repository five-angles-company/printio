<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Native\Laravel\Facades\AutoUpdater;

class UpdateController extends Controller
{
    public function checkForUpdates()
    {
        try {
            AutoUpdater::checkForUpdates();
            return back();
        } catch (\Throwable $th) {
            dd($th);
            Log::error($th);
        }
    }

    public function installUpdate()
    {
        try {
            AutoUpdater::quitAndInstall();
            return back();
        } catch (\Throwable $th) {
            dd($th);
            Log::error($th);
        }
    }
}
