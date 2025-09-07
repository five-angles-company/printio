<?php

namespace App\Services;

use App\Enums\PrinterType;
use App\Jobs\ProcessPrintJob;
use App\Models\PrintJob;
use Illuminate\Support\Facades\Http;
use Native\Laravel\Facades\Settings;
use Native\Laravel\Facades\System;
use RuntimeException;

class PrintFetchService
{
    public function fetchAndDispatch(string $token, string $deviceId): array
    {
        $response = Http::withToken($token)->get(
            config('services.remote_api.url') . '/api/print/jobs',
            ['device_id' => $deviceId]
        );
        if (! $response->successful()) {
            throw new RuntimeException('Failed to fetch print jobs: ' . $response->body());
        }

        return $response->json();
    }

    public function updateRemoteJob(string $token, int $id, array $data)
    {
        $response = Http::withToken($token)->put(
            config('services.remote_api.url') . "/api/print/jobs/$id",
            $data
        );

        if (! $response->successful()) {
            throw new RuntimeException('Failed to update print job: ' . $response->body());
        }

        return $response->json();
    }
}
