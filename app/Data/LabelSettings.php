<?php

namespace App\Data;

use App\Enums\LabelEncoder;
use Spatie\LaravelData\Data;

class LabelSettings extends Data
{
    public function __construct(
        public string $labelWidth = "40",
        public string $labelHeight = "20",
        public int $dpi = 203,
    ) {}
}
