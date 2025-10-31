<?php

namespace App\Printers;

use App\Data\PosSessionData;
use App\Data\PosSessionSettings;
use App\Models\PrintJob;
use App\Models\PrinterSettings;
use App\Traits\WindowsPrinter;
use Illuminate\Support\Facades\Log;

class PosSessionPrinter extends BasePrinter
{
    use WindowsPrinter;

    /**
     * Print POS session receipt directly to printer.
     */
    public function print(PrintJob $printJob)
    {
        /** @var PosSessionData $data */
        $data = $printJob->data;
        $printerInfo = $printJob->printer;
        $settings = $printerInfo->printerSettings;

        // 1. Render receipt as image
        $tmpFile = $this->renderReceipt($data, $settings);

        // 2. Dispatch print job (non-blocking)
        $this->dispatchPrintJob($tmpFile, $printerInfo->name);

        // 3. Optionally let OS clean up temp files
    }

    /**
     * Render session receipt HTML to PNG file.
     */
    private function renderReceipt(PosSessionData $data, PrinterSettings $printerSettings): string
    {
        /** @var PosSessionSettings $settings */
        $settings = $printerSettings->settings;
        $dpi = $settings->dpi ?? 203;
        $paperSizeMm = $settings->paperSize ?? 80; // Default 80mm width
        $paperWidthPx = (int)($paperSizeMm * $dpi / 25.4);

        // Render Blade template
        $html = view('receipts.session', [
            ...$data->toArray(),
            'width' => $paperWidthPx,
        ])->render();

        // Generate image via Snappy
        $snappy = app('snappy.image');
        $snappy->setOptions([
            'format'  => 'jpg',
            'quality' => 70,
            'width'   => $paperWidthPx,
        ]);

        $imageData = $snappy->getOutputFromHtml($html);

        // Save as temp file
        $tmpFile = tempnam(sys_get_temp_dir(), 'possession_') . '.png';
        file_put_contents($tmpFile, $imageData);

        return $tmpFile;
    }
}
