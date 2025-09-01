<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Native\Laravel\Events\App\ApplicationBooted;
use Native\Laravel\Events\AutoUpdater\UpdateAvailable;
use Native\Laravel\Facades\ChildProcess;

class BackgroundServicesProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(ApplicationBooted::class, function () {
            Log::info("Update available event received");
            $this->startReverb();
        });
        Event::listen(UpdateAvailable::class, function ($data) {
            Log::info("Update available event received $data");
        });
    }

    private function startReverb(): void
    {
        ChildProcess::artisan(
            'reverb:start',
            alias: 'reverb-server',
            persistent: true
        );
    }
}
