<?php

namespace App\Providers;

use App\Enums\PrinterType;
use App\Events\UserAuthStateChanged;
use App\Helpers\DeviceIdentifier;
use App\Models\Printer;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Native\Laravel\Events\App\ApplicationBooted;
use Native\Laravel\Events\ChildProcess\ErrorReceived;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Facades\Settings;

class BackgroundServicesProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(ApplicationBooted::class, fn() => $this->startPrintWorker());

        Event::listen([
            UserAuthStateChanged::class,
            'eloquent.created: ' . Printer::class,
            'eloquent.updated: ' . Printer::class,
            'eloquent.deleted: ' . Printer::class,
        ], fn() => $this->restartPrintWorker());

        Event::listen(ErrorReceived::class, function (ErrorReceived $event) {
            Log::error("Child process '{$event->alias}' error: {$event->data}");
        });
    }


    private function startPrintWorker(): void
    {
        $env = $this->getWorkerEnv();

        Log::warning('Starting print worker...', $env);

        ChildProcess::artisan(
            'print:worker',
            alias: 'print-worker',
            persistent: true,
            env: $env
        );
    }

    private function restartPrintWorker(): void
    {
        Log::warning('Restarting print worker...');

        ChildProcess::stop('print-worker');
        $this->startPrintWorker();
    }

    private function getWorkerEnv(): array
    {
        return [
            'AUTH_TOKEN'      => (string) Settings::get('auth.token', ''),
            'DEVICE_ID'       => (string) DeviceIdentifier::get(),
            'RECEIPT_PRINTER' => (string) Printer::where('type', PrinterType::RECEIPT->value)->first()?->id,
            'LABEL_PRINTER'   => (string) Printer::where('type', PrinterType::LABEL->value)->first()?->id,
            'INSTRUCTIONS_PRINTER' => (string) Printer::where('type', PrinterType::INSTRUCTIONS->value)->first()?->id,
            'POS_SESSION_PRINTER' => (string) Printer::where('type', PrinterType::POS_SESSION->value)->first()?->id,
            'API_URL'         => (string) Settings::get('api_url', ''),
        ];
    }
}
