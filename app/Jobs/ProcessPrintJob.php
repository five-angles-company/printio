<?php

namespace App\Jobs;

use App\Enums\PrinterType;
use App\Enums\PrintJobStatus;
use App\Models\PrintJob;
use App\Printers\LabelPrinter;
use App\Printers\ReceiptPrinter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPrintJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PrintJob $printJob;

    /**
     * Create a new job instance.
     */
    public function __construct(PrintJob $printJob)
    {
        $this->printJob = $printJob;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $printerModel = $this->printJob->printer;

        // choose printer implementation based on type
        $printer = match ($printerModel->type) {
            PrinterType::RECEIPT => app(ReceiptPrinter::class),
            PrinterType::LABEL => app(LabelPrinter::class),
            default   => throw new \RuntimeException("Unsupported printer type: {$printerModel->type}"),
        };
        $printer->print($this->printJob);

        $this->printJob->update([
            'status' => PrintJobStatus::COMPLETED,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        dd($exception);
        $this->printJob->update([
            'status' => PrintJobStatus::FAILED,
        ]);
    }
}
