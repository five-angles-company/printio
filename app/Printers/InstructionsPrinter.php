<?php

namespace App\Printers;

use App\Data\InstructionsData;
use App\Data\LabelSettings;
use App\Models\PrinterSettings;
use App\Models\PrintJob;
use App\Traits\WindowsPrinter;

class InstructionsPrinter extends BasePrinter
{
    use WindowsPrinter;

    /**
     * Print instructions directly to printer.
     */
    public function print(PrintJob $printJob)
    {
        /** @var InstructionsData $data */
        $data = $printJob->data;
        $printerInfo = $printJob->printer;
        $settings = $printerInfo->printerSettings;

        // 1. Render instructions image
        $tmpFile = $this->renderInstructions($data, $settings);

        // 2. Dispatch print job (non-blocking)
        $this->dispatchPrintJob($tmpFile, $printerInfo->name);

        // 3. Optional cleanup handled by OS
    }

    /**
     * Render the instructions Blade view into a PNG file.
     */
    private function renderInstructions(InstructionsData $data, PrinterSettings $printerSettings): string
    {
        /** @var LabelSettings $settings */
        $settings = $printerSettings->settings;

        $labelWidth = $settings->labelWidth;
        $labelHeight = $settings->labelHeight;
        $dpi = $settings->dpi;

        $widthPx  = (int)($labelWidth * $dpi / 25.4);
        $heightPx = (int)($labelHeight * $dpi / 25.4);

        // Render Blade template with data
        $html = view('labels.instructions', [
            'data' => $data,
            'width' => $widthPx,
            'height' => $heightPx,
        ])->render();

        // Generate PNG from HTML
        $snappy = app('snappy.image');
        $snappy->setOptions([
            'format'  => 'png',
            'quality' => 100,
            'width'                 => $widthPx,
            'height'                => $heightPx,
        ]);

        $imageData = $snappy->getOutputFromHtml($html);

        // Save as temporary PNG file
        $tmpFile = tempnam(sys_get_temp_dir(), 'instr_') . '.png';
        file_put_contents($tmpFile, $imageData);

        return $tmpFile;
    }
}
