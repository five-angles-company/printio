<?php

namespace App\Data;

use App\Enums\LabelEncoder;
use Spatie\LaravelData\Data;

class LabelSettings extends Data
{
    public function __construct(
        public string $labelSize = "2X1",
        public int $dpi = 203,
        public string $encoder = LabelEncoder::TSPL->value,
    ) {}
}
