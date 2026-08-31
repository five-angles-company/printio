<?php

use App\Data\ReceiptData;
use App\Data\ReceiptItemData;
use Illuminate\Support\Facades\View;

/**
 * The POS forgives a promotion giveaway on its own line: full price, a discount
 * that cancels it, nothing to pay. The printed receipt has to say so, or the
 * customer is handed a slip whose lines do not add up to the total on it.
 */
function receiptFor(array $items): ReceiptData
{
    return new ReceiptData(
        branchName: 'Branch',
        invoiceNumber: 'INV-1',
        date: '2026-08-31 10:00',
        items: $items,
        subtotal: 200.0,
        tax: 30.0,
        total: 230.0,
        address: 'address',
        phone: '0164325500',
        client_name: 'N/A',
        client_tax_number: null,
        userId: '1',
        type: 'sale',
    );
}

it('keeps the discount the POS sent on the line it belongs to', function () {
    $data = ReceiptData::from([
        'branchName' => 'Branch',
        'invoiceNumber' => 'INV-1',
        'date' => '2026-08-31 10:00',
        'items' => [
            ['name' => 'Panadol', 'quantity' => 2, 'unitPrice' => 100.0, 'totalPrice' => 200.0, 'discount' => 0.0],
            ['name' => 'Panadol', 'quantity' => 1, 'unitPrice' => 100.0, 'totalPrice' => 0.0, 'discount' => 100.0],
        ],
        'subtotal' => 200.0,
        'tax' => 30.0,
        'total' => 230.0,
        'address' => 'address',
        'phone' => '0164325500',
        'client_name' => 'N/A',
        'client_tax_number' => null,
        'userId' => '1',
        'type' => 'sale',
    ]);

    expect($data->items[1]->discount)->toBe(100.0)
        ->and($data->items[1]->totalPrice)->toBe(0.0);
});

it('prints the giveaway at its price with the value forgiven beside it', function () {
    $html = View::make('receipts.main', receiptFor([
        new ReceiptItemData('Panadol', 2, 100.0, 200.0, 0.0),
        new ReceiptItemData('Panadol', 1, 100.0, 0.0, 100.0),
    ]))->render();

    expect($html)->toContain('Discount');

    $rows = collect(explode('<tr', $html))->filter(fn (string $row) => str_contains($row, 'center padding-sm'));

    // Every item row carries the column, so nothing prints under the wrong heading.
    expect($rows)->toHaveCount(2);
    $rows->each(fn (string $row) => expect(substr_count($row, '<td'))->toBe(5));
});

it('leaves an ordinary receipt on its four columns', function () {
    // Paper is 58mm at its narrowest: a column that says nothing does not get printed.
    $html = View::make('receipts.main', receiptFor([
        new ReceiptItemData('Panadol', 2, 100.0, 200.0),
    ]))->render();

    expect($html)->not->toContain('Discount');

    $rows = collect(explode('<tr', $html))->filter(fn (string $row) => str_contains($row, 'center padding-sm'));

    expect($rows)->toHaveCount(1);
    $rows->each(fn (string $row) => expect(substr_count($row, '<td'))->toBe(4));
});

it('still prints a receipt queued before the POS sent discounts', function () {
    $data = ReceiptData::from([
        'branchName' => 'Branch',
        'invoiceNumber' => 'INV-OLD',
        'date' => '2026-08-31 10:00',
        'items' => [
            ['name' => 'Panadol', 'quantity' => 2, 'unitPrice' => 100.0, 'totalPrice' => 200.0],
        ],
        'subtotal' => 200.0,
        'tax' => 30.0,
        'total' => 230.0,
        'address' => 'address',
        'phone' => '0164325500',
        'client_name' => 'N/A',
        'client_tax_number' => null,
        'userId' => '1',
        'type' => 'sale',
    ]);

    expect($data->items[0]->discount)->toBe(0.0)
        ->and(View::make('receipts.main', $data)->render())->not->toContain('Discount');
});
