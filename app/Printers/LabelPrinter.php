<?php

namespace App\Printers;

use App\Data\LabelData;
use App\Data\LabelSettings;
use App\Encoders\Label\BaseEncoder;
use App\Encoders\Label\TSPLEncoder;
use App\Encoders\Label\ZPLEncoder;
use App\Enums\LabelEncoder;
use App\Models\PrinterSettings;
use App\Models\PrintJob;
use App\Traits\PrintsRaw;

class LabelPrinter extends BasePrinter
{
    use PrintsRaw;

    public function print(PrintJob $printJob)
    {
        /** @var LabelData $data */
        $data = $printJob->data;
        $printer = $printJob->printer;
        $settings = $printer->printerSettings;
        $buffer = $this->renderLabel($data, $settings);
        return $this->printRaw($printer->name, $buffer, $printJob->name, true);
    }

    private function renderLabel(LabelData $data, PrinterSettings $printerSettings): string
    {
        /** @var LabelSettings $settings */
        $settings = $printerSettings->settings;
        $labelWidth = (int) $settings->labelWidth;
        $labelHeight = (int) $settings->labelHeight;
        $fontSize = $settings->fontSize;
        $barcodeSize = $settings->barcodeSize;
        $encoder = $this->resolveEncoder($settings->encoder);
        $buffer = $encoder
            ->size($labelWidth, $labelHeight)
            ->text("Almoharib Pharmacy", 'center', 'top', $fontSize, 0, 2)        // Large font, centered top
            ->text($data->productName, 'center', 'top', $fontSize, 0, 5)        // Large font, centered top
            ->barcode($data->barcode, 'center', 'center', $barcodeSize, 0, -2)     // Medium height barcode
            ->text("SR:{$data->price}", 'left', 'bottom', $fontSize, 2, 3)   // Small font, left bottom
            ->text($data->expiry, 'right', 'bottom', $fontSize, 2, 3)           // Small font, right bottom
            ->copies($data->copies)
            ->getBuffer();

        return $buffer;
    }
    private function resolveEncoder(string $encoder): BaseEncoder
    {
        return match ($encoder) {
            LabelEncoder::ZPL->value => new ZPLEncoder(),
            LabelEncoder::TSPL->value => new TSPLEncoder(),
        };
    }
}
