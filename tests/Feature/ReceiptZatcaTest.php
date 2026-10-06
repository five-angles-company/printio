<?php

use App\Data\ReceiptData;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\View;

/**
 * The seller, VAT number and ZATCA QR code come from the server, taken from the
 * taxpayer the branch invoices under. A server that predates them sends none of
 * the three, and the receipt keeps printing what it always did.
 */
function receiptJson(array $extra = []): array
{
    return [
        'branchName' => 'Branch',
        'invoiceNumber' => 'PH1-000001',
        'date' => '2026-10-06 12:00',
        'items' => [
            ['name' => 'Panadol', 'quantity' => 1, 'unitPrice' => 100.0, 'totalPrice' => 100.0, 'discount' => 0.0],
        ],
        'subtotal' => 100.0,
        'tax' => 15.0,
        'total' => 115.0,
        'address' => 'address',
        'phone' => '0164325500',
        'client_name' => 'N/A',
        'client_tax_number' => null,
        'userId' => '1',
        'type' => 'sale',
        ...$extra,
    ];
}

function qrImage(string $text, int $size = 180): string
{
    return base64_encode(Builder::create()->data($text)->size($size)->margin(0)->build()->getString());
}

it('prints the seller, VAT number and ZATCA QR code the server sends', function () {
    $zatcaQr = 'AQxCb2JzIFJlY29yZHMCDzMxMDEyMjM5MzUwMDAwMwMUMjAyMi0wNC0yNVQxNTozMDowMFoEBzEwMDAuMDAFBjE1MC4wMA==';

    $html = View::make('receipts.main', ReceiptData::from(receiptJson([
        'sellerName' => 'مؤسسة الاجداد للتجارة',
        'sellerVatNumber' => '399999999900003',
        'zatcaQr' => $zatcaQr,
    ])))->render();

    expect($html)
        ->toContain('مؤسسة الاجداد للتجارة')
        ->toContain('399999999900003')
        ->not->toContain('310432040400003')
        // Twice the size, so the nine tags of a signed invoice still scan.
        ->toContain(qrImage($zatcaQr, 360))
        ->toContain('qr-code-box zatca');
});

it('keeps printing the VAT number it always did when the server sends none', function () {
    $html = View::make('receipts.main', ReceiptData::from(receiptJson()))->render();

    expect($html)
        ->toContain('310432040400003')
        ->toContain(qrImage('310432040400003'))
        ->not->toContain('Seller');
});
