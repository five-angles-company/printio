<?php

use App\Data\ReceiptData;
use App\Data\ReceiptItemData;
use Illuminate\Support\Facades\View;

/**
 * Sales orders are issued under the second brand, so the slip has to carry that
 * brand's logo. Everything else — ordinary sales, returns — keeps the original.
 */
function receiptOfType(string $type): ReceiptData
{
    return new ReceiptData(
        branchName: 'Branch',
        invoiceNumber: 'INV-1',
        date: '2026-08-31 10:00',
        items: [new ReceiptItemData('Panadol', 1, 100.0, 100.0, 0.0)],
        subtotal: 100.0,
        tax: 15.0,
        total: 115.0,
        address: 'address',
        phone: '0164325500',
        client_name: 'N/A',
        client_tax_number: null,
        userId: '1',
        type: $type,
    );
}

function logoOn(string $type): string
{
    $html = View::make('receipts.main', receiptOfType($type))->render();

    $default = base64_encode(file_get_contents(public_path('images/pharmacy.png')));
    $second = base64_encode(file_get_contents(public_path('images/pharmacy2.png')));

    return match (true) {
        str_contains($html, $second) => 'pharmacy2',
        str_contains($html, $default) => 'pharmacy',
        default => 'none',
    };
}

it('prints a sales order under the second brand', function () {
    expect(logoOn('sales_order'))->toBe('pharmacy2');
});

it('leaves every other receipt type on the original logo', function () {
    expect(logoOn('sale'))->toBe('pharmacy')
        ->and(logoOn('return'))->toBe('pharmacy');
});
