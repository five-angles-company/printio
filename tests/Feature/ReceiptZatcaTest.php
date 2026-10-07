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

it('titles the document and names a B2B buyer\'s address, as the server sends them', function () {
    $html = View::make('receipts.main', ReceiptData::from(receiptJson([
        'titleEn' => 'Tax Invoice',
        'titleAr' => 'فاتورة ضريبية',
        'client_name' => 'مستشفى المجمعة',
        'client_tax_number' => '300000000000003',
        'clientAddress' => '1111 طريق الملك فهد، حي الفيصلية، المجمعة 15341',
    ])))->render();

    expect($html)
        ->toContain('Tax Invoice')
        ->toContain('فاتورة ضريبية')
        ->toContain('1111 طريق الملك فهد، حي الفيصلية، المجمعة 15341');
});

it('prints no QR code on a B2B invoice ZATCA has yet to clear, and says it follows', function () {
    $html = View::make('receipts.main', ReceiptData::from(receiptJson(['awaitingClearance' => true])))->render();

    expect($html)
        ->toContain('follows once ZATCA has cleared it')
        ->not->toContain('class="qr-code-box');
});

it('prints as before for a server that sends no title', function () {
    $html = View::make('receipts.main', ReceiptData::from(receiptJson()))->render();

    expect($html)->not->toContain('class="document-title"')->toContain('class="qr-code-box');
});
