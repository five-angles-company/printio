<?php

namespace App\Printers;

use App\Data\LabelData;
use App\Data\LabelSettings;
use App\Encoders\Label\EplEncoder;
use App\Encoders\Label\TsplEncoder;
use App\Encoders\Label\ZplEncoder;
use App\Enums\LabelEncoder;
use App\Encoders\Label\BaseEncoder;
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

        $encoder = $this->resolveEncoder($settings->encoder);

        // Set label width (assuming 4 inch standard label)
        return $encoder->initialize(2, 1)
            ->barcode('7640118193909', 30, 80)
            ->getBuffer();
    }

    private function resolveEncoder(string $encoder): BaseEncoder
    {
        return match ($encoder) {
            LabelEncoder::ZPL->value => new ZplEncoder(),
            LabelEncoder::EPL->value => new EplEncoder(),
            LabelEncoder::TSPL->value => new TsplEncoder(),
        };
    }
}
