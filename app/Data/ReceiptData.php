<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ReceiptData extends Data
{
    public function __construct(
        public string $date,
        /** @var ReceiptItemData[] */
        public array $items,
        public float $subtotal,
        public float $tax,
        public float $total,
        public string $address,
        public string $phone,
        public string $clientId,
    ) {}
}
