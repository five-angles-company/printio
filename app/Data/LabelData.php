<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class LabelData extends Data
{
    public function __construct(
        public string $productName,
        public string $barcode,
        public float $price,
        public string $expiry,
        public int $copies,
    ) {}
}
