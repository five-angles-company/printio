<?php

namespace App\Actions\Printer;

use App\Models\Printer;

final class CreatePrinter
{
    public function handle($data)
    {
        $printer = Printer::create($data);

        return $printer;
    }
}
