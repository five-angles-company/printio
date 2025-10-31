<?php

namespace App\Actions\Printer;

use App\Data\LabelSettings;
use App\Data\ReceiptSettings;
use App\Enums\PrinterType;
use App\Exceptions\DuplicatePrintTypeException;
use App\Models\Printer;
use App\Models\PrinterSettings;
use Illuminate\Support\Arr;

final class CreatePrinter
{
    /**
     * Create a printer with default settings and set as default if needed.
     */
    public function handle(array $data): Printer
    {
        if (Printer::where('type', $data['type'])->exists()) {
            throw new DuplicatePrintTypeException();
        }
        $printer = Printer::create(Arr::except($data, ['settings']));
        $printer->printerSettings()->create([
            'settings' => $this->getDefaultSettings($printer->type),
        ]);

        return $printer;
    }

    /**
     * Get default settings by type.
     */
    private function getDefaultSettings(PrinterType $type): ReceiptSettings|LabelSettings|array
    {
        return match ($type) {
            PrinterType::RECEIPT => new ReceiptSettings(),
            PrinterType::LABEL => new LabelSettings(),
            PrinterType::INSTRUCTIONS => new LabelSettings(),
            PrinterType::POS_SESSION => new LabelSettings(),
            default => [],
        };
    }
}
