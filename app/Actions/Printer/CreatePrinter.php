<?php

namespace App\Actions\Printer;

use App\Data\LabelSettings;
use App\Data\ReceiptSettings;
use App\Models\Printer;
use Illuminate\Support\Arr;

final class CreatePrinter
{
    /**
     * Create a printer with default settings depending on type.
     */
    public function handle(array $data): Printer
    {
        // Create the base printer
        $printer = Printer::create(Arr::except($data, ['settings']));

        $settings =  $this->getDefaultSettings($data['type']);

        // Create printer settings
        $printer->printerSettings()->create([
            'settings' => $settings,
        ]);

        return $printer;
    }

    /**
     * Get default settings by type.
     */
    private function getDefaultSettings(string $type): ReceiptSettings|LabelSettings
    {
        return match ($type) {
            'Receipt' => new ReceiptSettings(),
            'Label' => new LabelSettings(),
            default => [],
        };
    }
}
