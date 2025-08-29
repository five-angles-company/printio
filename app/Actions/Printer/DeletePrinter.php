<?php

namespace App\Actions\Printer;

use App\Models\Printer;

final class DeletePrinter
{
    public function handle(Printer $printer): void
    {
        $printer->delete();
    }
}
