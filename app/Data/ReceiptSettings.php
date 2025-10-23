<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ReceiptSettings extends Data
{
    public function __construct(
        public int $paperSize = 58,
        public int $dpi = 203,
        public bool $cut = false,
        public bool $beep = false
    ) {}
}
