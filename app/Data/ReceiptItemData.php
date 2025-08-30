<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ReceiptItemData extends Data
{
    public function __construct(
        public string $name,
        public int $quantity,
        public float $unitPrice,
        public float $totalPrice,
    ) {}
}
