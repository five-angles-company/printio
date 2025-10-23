<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Native\Laravel\Facades\Settings;
use RuntimeException;

class PrintFetchService
{
    public function fetchAndDispatch(string $apiUrl, string $token, string $deviceId): array
    {
        $response = Http::withToken($token)->get(
            $apiUrl . '/api/print/jobs',
            ['device_id' => $deviceId]
        );
        if (! $response->successful()) {
            throw new RuntimeException('Failed to fetch print jobs: ' . $response->body());
        }

        return $response->json();
    }

    public function updateRemoteJob(string $apiUrl, string $token, int $id, array $data)
    {
        $response = Http::withToken($token)->put(
            $apiUrl . "/api/print/jobs/$id",
            $data
        );

        if (! $response->successful()) {
            throw new RuntimeException('Failed to update print job: ' . $response->body());
        }

        return $response->json();
    }
}
