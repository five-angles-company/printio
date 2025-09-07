<?php

namespace App\Console\Commands;

use App\Enums\PrinterType;
use App\Enums\PrintJobStatus;
use App\Models\PrintJob;
use App\Services\PrintFetchService;
use Illuminate\Console\Command;

class PrintJobWorker extends Command
{
    protected $signature = 'print:worker';
    protected $description = 'Continuously fetch and dispatch print jobs from API';

    public function handle(PrintFetchService $fetcher)
    {
        $this->info('Print worker started...');

        while (true) {
            try {
                try {
                    $authToken      = $this->requireEnv('AUTH_TOKEN');
                    $deviceId       = $this->requireEnv('DEVICE_ID');
                    $labelPrinter   = $this->requireEnv('LABEL_PRINTER');
                    $receiptPrinter = $this->requireEnv('RECEIPT_PRINTER');
                } catch (\Throwable $e) {
                    $this->error("Startup error: " . $e->getMessage());
                    return Command::FAILURE;
                }

                $jobs = $fetcher->fetchAndDispatch($authToken, $deviceId);
                $this->info("Fetched " . count($jobs) . " print jobs for device {$deviceId}");

                foreach ($jobs as $jobData) {
                    try {
                        $printerId = $this->resolvePrinterId(
                            $jobData['type'],
                            $labelPrinter,
                            $receiptPrinter
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

                        $fetcher->updateRemoteJob($authToken, $jobData['id'], [
                            'status'     => 'completed',
                        ]);
                    } catch (\Throwable $e) {
                        $fetcher->updateRemoteJob($authToken, $jobData['id'], [
                            'status' => 'failed',
                            'error'  => $e->getMessage(),
                        ]);
                        $this->error("Job {$jobData['id']} failed: " . $e->getMessage());
                    }
                }
            } catch (\Throwable $e) {
                $this->error("Worker error: " . $e->getMessage());
            }

            usleep(1000000); // 1 sec sleep
        }
    }



    private function resolvePrinterId(string $type, string $labelPrinter, string $receiptPrinter): ?int
    {
        return match ($type) {
            PrinterType::LABEL->value   => $labelPrinter,
            PrinterType::RECEIPT->value => $receiptPrinter,
            default                     => null,
        };
    }

    private function requireEnv(string $key): string
    {
        $value = getenv($key);
        if ($value === false || $value === '') {
            throw new \RuntimeException("Missing required env: {$key}");
        }
        return $value;
    }
}
