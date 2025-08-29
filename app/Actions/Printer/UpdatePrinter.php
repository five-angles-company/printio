<?php

namespace App\Actions\Printer;

use App\Models\Printer;

final class UpdatePrinter
{
    public function handle(Printer $printer, $data): void
    {
        if (isset($data['settings'])) {
            $printer->printerSettings()->update([
                'settings' => $data['settings'],
            ]);
            unset($data['settings']);
        }
        $printer->update($data);
    }
}
