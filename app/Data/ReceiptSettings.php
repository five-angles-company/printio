<?php

namespace App\Data;

use App\Enums\PrintDensity;
use App\Enums\PrintSpeed;
use Spatie\LaravelData\Data;

class ReceiptSettings extends Data
{
    public function __construct(
        public int $paperSize = 58,
        public int $dpi = 203,
        public string $printDensity = PrintDensity::MEDIUM->value,
        public string $printSpeed = PrintSpeed::NORMAL->value,
        public bool $cut = false,
        public bool $beep = false
    ) {}
}
