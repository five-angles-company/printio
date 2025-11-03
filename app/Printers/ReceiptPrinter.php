<?php

namespace App\Printers;

use App\Data\ReceiptData;
use App\Data\ReceiptSettings;
use App\Models\PrintJob;
use App\Models\PrinterSettings;
use App\Traits\WindowsPrinter;
use Illuminate\Support\Facades\Log;

class ReceiptPrinter extends BasePrinter
{
    use WindowsPrinter;

    /**
     * Print receipt directly to printer.
     */
    public function print(PrintJob $printJob)
    {
        /** @var ReceiptData $data */
        $data = $printJob->data;
        $printerInfo = $printJob->printer;
        $settings = $printerInfo->printerSettings;

        // 1. Render receipt image
        $tmpFile = $this->renderReceipt($data, $settings);

        // 2. Dispatch print job
        $this->dispatchPrintJob($tmpFile, $printerInfo->name, 1);
    }

    /**
     * Render receipt HTML to PNG file.
     */
    private function renderReceipt(ReceiptData $data, PrinterSettings $printerSettings): string
    {
        /** @var ReceiptSettings $settings */
        $settings = $printerSettings->settings;
        $dpi = $settings->dpi ?? 203;
        $paperSizeMm = $settings->paperSize ?? 80;
        $paperWidthPx = (int)($paperSizeMm * $dpi / 25.4);

        // Render Blade template
        $html = view('receipts.main', $data)->render();

        // Generate PNG via Snappy
        $snappy = app('snappy.image');
        $snappy->setOptions([
            'format'  => 'png',
            'quality' => 80,
            'width' => $paperWidthPx,
        ]);

        $imageData = $snappy->getOutputFromHtml($html);

        // Save temporary file
        $tmpFile = tempnam(sys_get_temp_dir(), 'receipt_') . '.png';
        file_put_contents($tmpFile, $imageData);

        return $tmpFile;
    }
}
