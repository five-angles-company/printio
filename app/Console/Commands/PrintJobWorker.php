<?php

namespace App\Console\Commands;

use App\Enums\PrinterType;
use App\Enums\PrintJobStatus;
use App\Models\PrintJob;
use App\Services\PrintFetchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PrintJobWorker extends Command
{
    protected $signature = 'print:worker';
    protected $description = 'Continuously fetch and dispatch print jobs from API';

    public function handle(PrintFetchService $fetcher)
    {
        $this->info('Print worker started...');
        Log::warning('Print worker started...');

        while (true) {
            try {
                try {
                    // required
                    $authToken = $this->requireEnv('AUTH_TOKEN');
                    $deviceId  = $this->requireEnv('DEVICE_ID');
                    $apiUrl    = $this->requireEnv('API_URL');

                    // optional
                    $labelPrinter        = $this->optionalEnv('LABEL_PRINTER');
                    $receiptPrinter      = $this->optionalEnv('RECEIPT_PRINTER');
                    $instructionsPrinter = $this->optionalEnv('INSTRUCTIONS_PRINTER');
                    $posSessionPrinter   = $this->optionalEnv('POS_SESSION_PRINTER');
                } catch (\Throwable $e) {
                    $this->error("Startup error: " . $e->getMessage());
                    return Command::FAILURE;
                }

                $jobs = $fetcher->fetchAndDispatch($apiUrl, $authToken, $deviceId);

                $this->info("Fetched " . count($jobs) . " print jobs for device {$deviceId}");
                Log::warning("Fetched " . count($jobs) . " print jobs for device {$deviceId}");

                foreach ($jobs as $jobData) {
                    try {
                        $printerId = $this->resolvePrinterId(
                            $jobData['type'],
                            $labelPrinter,
                            $receiptPrinter,
                            $instructionsPrinter,
                            $posSessionPrinter
                        );

                        PrintJob::firstOrCreate(
                            ['remote_id' => $jobData['id']],
                            [
                                'name'       => $jobData['name'],
                                'printer_id' => $printerId,
                                'type'       => $jobData['type'],
                                'data'       => $jobData['data'],
                                'status'     => PrintJobStatus::PENDING,
                            ]
                        );

                        $fetcher->updateRemoteJob($apiUrl, $authToken, $jobData['id'], [
                            'status' => 'completed',
                        ]);
                    } catch (\Throwable $e) {
                        $fetcher->updateRemoteJob($apiUrl, $authToken, $jobData['id'], [
                            'status' => 'failed',
                            'error'  => $e->getMessage(),
                        ]);

                        $this->error("Job {$jobData['id']} failed: " . $e->getMessage());
                        Log::error("Job {$jobData['id']} failed: " . $e->getMessage());
                    }
                }
            } catch (\Throwable $e) {
                $this->error("Worker error: " . $e->getMessage());
                Log::error("Worker error: " . $e->getMessage());
            }

            usleep(1000000); // 1 sec
        }
    }

    private function resolvePrinterId(
        string $type,
        ?string $labelPrinter,
        ?string $receiptPrinter,
        ?string $instructionsPrinter,
        ?string $posSessionPrinter
    ): ?int {
        $printerId = match ($type) {
            PrinterType::LABEL->value        => $labelPrinter,
            PrinterType::RECEIPT->value      => $receiptPrinter,
            PrinterType::INSTRUCTIONS->value => $instructionsPrinter,
            PrinterType::POS_SESSION->value  => $posSessionPrinter,
            default                          => null,
        };

        if (empty($printerId)) {
            throw new \RuntimeException("No printer configured for type: {$type}");
        }

        return $printerId;
    }

    private function requireEnv(string $key): string
    {
        $value = getenv($key);
        if ($value === false || $value === '') {
            throw new \RuntimeException("Missing required env: {$key}");
        }
        return $value;
    }

    private function optionalEnv(string $key): ?string
    {
        $value = getenv($key);
        return $value === false || $value === '' ? null : $value;
    }
}
