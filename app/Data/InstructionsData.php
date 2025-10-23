<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class InstructionsData extends Data
{
    public function __construct(
        public string $pharmacistName,
        public string $patientName,
        public string $productName,
        public string $line1,
        public string $line2,
        public string $line3,
        public string $line4,
        public int $copies,
    ) {}
}
