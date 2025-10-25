<?php

namespace App\Observers;

use App\Enums\PrintJobStatus;
use App\Jobs\ProcessPrintJob;
use App\Models\PrintJob;
use Illuminate\Container\Attributes\Log;

class PrintJobObserver
{

    /**
     * Handle the PrintJob "created" event.
     */
    public function created(PrintJob $printJob): void
    {
        try {
            ProcessPrintJob::dispatchSync($printJob);
        } catch (\Throwable $th) {
            Log::error($th);
            $printJob->update([
                'status' => PrintJobStatus::FAILED,
            ]);
        }
    }
}
