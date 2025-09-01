<?php

namespace App\Data;

use App\Enums\LabelEncoder;
use App\Enums\PrintDensity;
use App\Enums\PrintSpeed;
use Spatie\LaravelData\Data;

class LabelSettings extends Data
{
    public function __construct(
        public int $labelWidth = 40,
        public int $labelHeight = 20,
        public string $printDensity = PrintDensity::MEDIUM->value,
        public string $printSpeed = PrintSpeed::NORMAL->value,
        public string $encoder = LabelEncoder::TSPL->value,
    ) {}
}
