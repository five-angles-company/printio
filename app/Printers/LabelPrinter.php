<?php

namespace App\Printers;

use App\Data\LabelData;
use App\Encoders\BaseLabelEncoder;
use App\Encoders\DplEncoder;
use App\Encoders\EplEncoder;
use App\Encoders\TsplEncoder;
use App\Encoders\ZplEncoder;
use App\Enums\LabelEncoder;
use App\Enums\PrintAlign;
use App\Enums\PrintDensity;
use App\Enums\PrintSize;
use App\Enums\PrintSpeed;
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
        $buffer = $this->renderLabel($data,  LabelEncoder::TSPL);
        return $this->printRaw($printer->name, $buffer, $printJob->name, true);
    }

    private function renderLabel(LabelData $data, LabelEncoder $encoderType = LabelEncoder::TSPL): string
    {
        $encoder = $this->resolveEncoder($encoderType);

        // Set label width (assuming 4 inch standard label)
        $encoder->setLabelSize(2, 1);

        // Initialize with medium density and normal speed
        $encoder->initialize(PrintDensity::MEDIUM, PrintSpeed::NORMAL);

        // Add product name (centered, large)
        $encoder->addText(50, $data->productName, PrintSize::SMALL, PrintAlign::CENTER);

        // Add price (left aligned, normal)
        $encoder->addText(80, "Price: $" . number_format($data->price, 2), PrintSize::SMALL, PrintAlign::LEFT);

        // Add expiry date (right aligned, normal)
        $encoder->addText(80, "Exp: " . $data->expiry, PrintSize::SMALL, PrintAlign::RIGHT);

        // Add barcode (always centered)
        $encoder->addBarcode(130, $data->barcode, 60);

        // Set print copies
        $encoder->print($data->copies);

        return $encoder->getBuffer();
    }

    private function resolveEncoder(LabelEncoder $encoder): BaseLabelEncoder
    {
        return match ($encoder) {
            LabelEncoder::ZPL => new ZplEncoder(),
            LabelEncoder::EPL => new EplEncoder(),
            LabelEncoder::TSPL => new TsplEncoder(),
            LabelEncoder::DPL => new DplEncoder(),
        };
    }
}
