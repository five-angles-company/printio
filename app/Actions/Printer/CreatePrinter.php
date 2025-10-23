<?php

namespace App\Actions\Printer;

use App\Data\InstructionsSettings;
use App\Data\LabelSettings;
use App\Data\ReceiptSettings;
use App\Enums\PrinterType;
use App\Models\Printer;
use Illuminate\Support\Arr;
use Native\Laravel\Facades\Settings;

final class CreatePrinter
{
    /**
     * Create a printer with default settings and set as default if needed.
     */
    public function handle(array $data): Printer
    {
        // Create the base printer
        $printer = Printer::create(Arr::except($data, ['settings']));

        // Attach printer settings (based on type)
        $printer->printerSettings()->create([
            'settings' => $this->getDefaultSettings($printer->type),
        ]);

        // Ensure a default printer is set for this type
        $this->setAsDefaultIfMissing($printer);

        return $printer;
    }

    /**
     * Get default settings by type.
     */
    private function getDefaultSettings(PrinterType $type): ReceiptSettings|LabelSettings|InstructionsSettings|array
    {
        return match ($type) {
            'Receipt' => new ReceiptSettings(),
            'Label' => new LabelSettings(),
            'Instructions' => new InstructionsSettings(),
            default => [],
        };
    }

    /**
     * If there’s no default printer of this type, set this one as default.
     */
    private function setAsDefaultIfMissing(Printer $printer): void
    {
        $key = match ($printer->type->value) {
            'Receipt' => 'receipt_printer',
            'Label' => 'label_printer',
            'Instructions' => 'instructions_printer',
            default => null,
        };

        if (! $key) {
            return;
        }

        // Get current value
        $current = Settings::get($key);

        // Only set it if not already defined
        if (empty($current)) {
            Settings::set($key, $printer->id); // ✅ use ID (safer than name)
        }
    }
}
