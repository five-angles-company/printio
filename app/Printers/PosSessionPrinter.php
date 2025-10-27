<?php

namespace App\Printers;

use App\Data\PosSessionData;
use App\Data\PosSessionSettings;
use App\Encoders\Receipt\EscPosEncoder;
use App\Models\PrinterSettings;
use App\Models\PrintJob;
use App\Traits\PrintsRaw;

class PosSessionPrinter extends BasePrinter
{
    use PrintsRaw;

    /**
     * Render receipt Blade template to ESC/POS buffer.
     *
     * @param array $data
     * @return string
     */
    public function renderReceipt(PosSessionData $data, PrinterSettings $printerSettings): string
    {
        /** @var PosSessionSettings $settings */
        $settings = $printerSettings->settings;
        $paperSize = $this->mmToPx($settings->paperSize, $settings->dpi);
        $html = view('receipts.session', $data)->render();
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
            ->beep($settings->beep);

        return $encoder->getBuffer();
    }

    /**
     * Print receipt directly to printer.
     *
     * @param string $printerName
     * @param array $data
     * @return mixed
     */
    public function print(PrintJob $printJob)
    {
        /** @var PosSessionData $data */
        $data = $printJob->data;
        $printer = $printJob->printer;
        $settings = $printer->printerSettings;
        $buffer = $this->renderReceipt($data, $settings);
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
