<?php

namespace App\Providers;

use App\Helpers\DeviceIdentifier;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Native\Laravel\Events\App\ApplicationBooted;
use Native\Laravel\Events\ChildProcess\ErrorReceived;
use Native\Laravel\Events\Settings\SettingChanged;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Facades\Settings;

class BackgroundServicesProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(ApplicationBooted::class, fn() => $this->startPrintWorker());

        Event::listen(SettingChanged::class, fn() => $this->restartPrintWorker());

        Event::listen(ErrorReceived::class, function (ErrorReceived $event) {
            Log::error("Child process '{$event->alias}' error: {$event->data}");
        });
    }


    private function startPrintWorker(): void
    {
        $env = $this->getWorkerEnv();

        Log::info('Starting print worker...', $env);

        ChildProcess::artisan(
            'print:worker',
            alias: 'print-worker',
            persistent: true,
            env: $env
        );
    }

    private function restartPrintWorker(): void
    {
        Log::info('Restarting print worker...');

        ChildProcess::stop('print-worker');
        $this->startPrintWorker();
    }

    private function getWorkerEnv(): array
    {
        return [
            'AUTH_TOKEN'      => (string) Settings::get('auth.token', ''),
            'DEVICE_ID'       => (string) DeviceIdentifier::get(),
            'RECEIPT_PRINTER' => (string) Settings::get('receipt_printer', ''),
            'LABEL_PRINTER'   => (string) Settings::get('label_printer', ''),
        ];
    }
}
