<?php

namespace App\Actions\Printer;

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
    private function getDefaultSettings(string $type): array
    {
        return match ($type) {
            'Receipt' => [
                'paper_size' => '72',
                'print_density' => 'medium',
                'print_speed' => 'normal',
                'cut' => false,
                'beep' => false,
            ],
            'Label' => [
                'label_width' => '40',
                'label_height' => '20',
                'print_density' => 'medium',
                'print_speed' => 'normal',
                'encoder' => 'tspl',
            ],
            default => [],
        };
    }
}
