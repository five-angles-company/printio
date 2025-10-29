<?php

namespace App\Data;

use App\Enums\LabelEncoder;
use Spatie\LaravelData\Data;

class LabelSettings extends Data
{
    public function __construct(
        public string $labelWidth = "40",
        public string $labelHeight = "20",
        public string $fontSize = "m",
        public string $barcodeSize = "m",
        public int $dpi = 203,
        public string $encoder = LabelEncoder::TSPL->value,
    ) {}
}
