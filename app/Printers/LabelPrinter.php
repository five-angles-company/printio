<?php

namespace App\Printers;

use App\Data\LabelData;
use App\Data\LabelSettings;
use App\Models\PrintJob;
use App\Models\PrinterSettings;
use App\Traits\PrintsRaw;
use App\Traits\WindowsPrinter;
use Native\Laravel\Facades\ChildProcess;
use Illuminate\Support\Facades\Log;
use Milon\Barcode\DNS1D;
use Milon\Barcode\Facades\DNS1DFacade;

class LabelPrinter extends BasePrinter
{
    use WindowsPrinter;

    public function print(PrintJob $printJob)
    {
        /** @var LabelData $data */
        $data = $printJob->data;
        $printerInfo = $printJob->printer;

        /** @var LabelSettings $settings */
        $settings = $printerInfo->printerSettings->settings;

        // 1. Render label image
        $tmpFile = $this->renderLabel($data, $settings);

        $this->dispatchPrintJob($tmpFile, $printerInfo->name, $data->copies);
    }

    private function renderLabel(LabelData $data, LabelSettings $settings): string
    {
        $labelWidth = $settings->labelWidth;
        $labelHeight = $settings->labelHeight;
        $dpi = $settings->dpi;

        $widthPx  = (int)($labelWidth * $dpi / 25.4);
        $heightPx = (int)($labelHeight * $dpi / 25.4);
        $barcodeImg = DNS1DFacade::getBarcodePNG($data->barcode, 'C128');
        $viewData = [
            'storeName'   => 'Almoharib Pharmacy',
            'productName' => $data->productName,
            'barcode'     => $data->barcode,
            'barcodeImg' => $barcodeImg,
            'price'       => number_format($data->price, 2),
            'expiry'      => $data->expiry,
            'width'       => $widthPx,
            'height'      => $heightPx,
        ];

        $html = view('labels.main', $viewData)->render();



        $snappy = app('snappy.image');
        $snappy->setOptions([
            'format'                => 'png',
            'quality'               => 100,
            'width'                 => $widthPx,
            'height'                => $heightPx,
        ]);

        $imageData = $snappy->getOutputFromHtml($html);
        $tmpFile   = tempnam(sys_get_temp_dir(), 'label_') . '.png';
        file_put_contents($tmpFile, $imageData);

        return $tmpFile;
    }
}
