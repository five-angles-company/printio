<?php

namespace App\Observers;

use App\Jobs\ProcessPrintJob;
use App\Models\PrintJob;

class PrintJobObserver
{

    /**
     * Handle the PrintJob "created" event.
     */
    public function created(PrintJob $printJob): void
    {
        ProcessPrintJob::dispatch($printJob);
    }
}
