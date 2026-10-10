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
        /** The taxpayer the branch invoices under; a server that predates it sends neither. */
        public ?string $sellerName = null,
        public ?string $sellerVatNumber = null,
        /** The Base64 TLV text of the receipt's ZATCA QR code; none on a sales order. */
        public ?string $zatcaQr = null,
        /** What the document is, as ZATCA asks it be titled; a server that predates it sends none. */
        public ?string $titleEn = null,
        public ?string $titleAr = null,
        /** The buyer's national address, which a B2B tax invoice names. */
        public ?string $clientAddress = null,
        /** A B2B invoice ZATCA has yet to clear: it prints no QR code until it has. */
        public bool $awaitingClearance = false,
        /** A sale on credit, which prints what has been paid on it, what is still owed and when it falls due; a server that predates it sends none. */
        public bool $creditSale = false,
        public ?float $paidAmount = null,
        public ?float $remainingAmount = null,
        public ?string $dueDate = null,
    ) {}
}
