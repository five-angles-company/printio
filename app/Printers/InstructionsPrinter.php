<?php

namespace App\Printers;

use App\Data\InstructionsData;
use App\Encoders\Receipt\EscPosEncoder;
use App\Models\PrinterSettings;
use App\Models\PrintJob;
use App\Traits\PrintsRaw;

class InstructionsPrinter extends BasePrinter
{
    use PrintsRaw;

    /**
     * Render Instructions Blade template to ESC/POS buffer.
     *
     * @param InstructionsData $data
     * @param PrinterSettings $printerSettings
     * @return string
     */
    public function renderInstructions(InstructionsData $data, PrinterSettings $printerSettings): string
    {
        /** @var InstructionsSettings $settings */
        $settings = $printerSettings->settings;
        $paperSize = $this->mmToPx($settings->paperSize, $settings->dpi);
        $html = view('instructions.main', ['data' => $data])->render();
        $snappy = app('snappy.image');

        $screenshot = $snappy
            ->setOption('format', 'png')
            ->setOption('width', $paperSize)
            ->getOutputFromHtml($html);

        $encoder = (new EscPosEncoder())
            ->initialize()
            ->image($screenshot, $paperSize, $settings->dpi)
            ->feed(6)
            ->cut($settings->cut)
            ->beep($settings->beep)
            ->copies($data->copies);

        return $encoder->getBuffer();
    }

    /**
     * Print Instructions directly to printer.
     *
     * @param PrintJob $printJob
     * @return mixed
     */
    public function print(PrintJob $printJob)
    {
        /** @var InstructionsData $data */
        $data = $printJob->data;
        $printer = $printJob->printer;
        $settings = $printer->printerSettings;
        $buffer = $this->renderInstructions($data, $settings);
        return $this->printRaw($printer->name, $buffer, $printJob->name, true);
    }

    private function mmToPx(string $mm, int $dpi = 203): int
    {
        // Convert string to float
        $mmValue = floatval($mm);

        // Convert mm to inches
        $inches = $mmValue / 25.4;

        // Convert inches to pixels
        $px = $inches * $dpi;

        // Return rounded pixel value
        return (int) round($px);
    }
}
