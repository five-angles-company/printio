<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ReceiptSettings extends Data
{
    public function __construct(
        public int $paperSize = 58,
        public int $dpi = 203,
        public int $maxPaperHeight = 800, // mm - safe default for most thermal printers
    ) {}
}
