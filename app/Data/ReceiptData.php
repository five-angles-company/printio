<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ReceiptData extends Data
{
    public function __construct(
        public string $branchName,
        public string $invoiceNumber,
        public string $date,
        /** @var ReceiptItemData[] */
        public array $items,
        public float $subtotal,
        public float $tax,
        public float $total,
        public string $address,
        public string $phone,
        public string $client_name,
        public ?string $client_tax_number,
        public string $userId,
        public string $type = 'sale',
        public ?float $returnCash = null,
        public ?float $totalRefund = null,
    ) {}
}
