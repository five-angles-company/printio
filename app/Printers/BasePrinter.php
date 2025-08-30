<?php

namespace App\Printers;

use App\Models\Printer;
use App\Models\PrintJob;

abstract class BasePrinter
{
    abstract public function print(PrintJob $printJob);
}
