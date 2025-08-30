<?php

namespace App\Printers;

use App\Data\ReceiptData;
use App\Encoders\EscPosEncoder;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Traits\PrintsRaw;
use Spatie\Browsershot\Browsershot;

class ReceiptPrinter extends BasePrinter
{
    use PrintsRaw;

    /**
     * Render receipt Blade template to ESC/POS buffer.
     *
     * @param array $data
     * @return string
     */
    public function renderReceipt(ReceiptData $data): string
    {
        $html = view('receipts.main', $data)->render();

        $screenshot = Browsershot::html($html)
            ->windowSize(576, 2000) // width fixed, height large enough
            ->setScreenshotType('png')
            ->fullPage()
            ->screenshot();

        $encoder = (new EscPosEncoder())
            ->initialize()
            ->align('center')
            ->text("test")
            ->feed(3)
            ->cut();
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
        /** @var ReceiptData $data */
        $data = $printJob->data;
        $printer = $printJob->printer;
        $buffer = $this->renderReceipt($data);
        return $this->printRaw($printer->name, $buffer, $printJob->name, true);
    }
}
