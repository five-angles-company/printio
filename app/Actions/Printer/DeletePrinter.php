<?php

namespace App\Actions\Printer;

use App\Models\Printer;
use Native\Laravel\Facades\Settings;

final class DeletePrinter
{
    public function handle(Printer $printer): void
    {
        // Delete the printer
        $printer->delete();

        // Clean up any Settings keys referencing this printer
        $this->removeFromSettings($printer->id);
    }

    /**
     * Remove this printer ID from all known printer Settings keys.
     */
    private function removeFromSettings(int $printerId): void
    {
        // List of all possible keys where a printer ID might be stored
        $keys = [
            'receipt_printer',
            'label_printer',
            'instructions_printer',
            'pos_session_printer',
            // add others if needed in the future
        ];

        foreach ($keys as $key) {
            $current = Settings::get($key);

            if ((int) $current === $printerId) {
                Settings::forget($key); // or Settings::set($key, null)
            }
        }
    }
}
