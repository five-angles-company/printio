<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * One printed line of a receipt.
 *
 * `totalPrice` is what the line adds to the invoice — `unitPrice x quantity`
 * with `discount` already taken off. A promotion giveaway arrives as a
 * full-price line whose discount cancels it, never as a line priced at zero,
 * so the receipt shows both the goods handed over and the value forgiven.
 *
 * Defaults to zero: receipts queued before the POS started sending the field
 * still print.
 */
class ReceiptItemData extends Data
{
    public function __construct(
        public string $name,
        public int $quantity,
        public float $unitPrice,
        public float $totalPrice,
        public float $discount = 0.0,
    ) {}
}
