<?php

namespace App\Actions\Printer;

use App\Data\LabelData;
use App\Data\ReceiptData;
use App\Enums\PrinterType;
use App\Models\Printer;

final class TestPrinter
{
    public function handle(Printer $printer): void
    {
        $printer->printJobs()->create([
            'name' => 'Test Print Job',
            'data' => $this->getTestData($printer->type),
            'type' => $printer->type
        ]);
    }

    public function getTestData(PrinterType $type): ReceiptData|LabelData
    {
        return match ($type) {
            PrinterType::RECEIPT =>  ReceiptData::from([
                'date' => date('Y-m-d'),
                'items' => [
                    [
                        'name' => 'Test Item',
                        'quantity' => 1,
                        'unitPrice' => 99.99,
                        'totalPrice' => 99.99,
                    ]
                ],
                'subtotal' => 99.99,
                'tax' => 10.00,
                'total' => 109.99,
                'address' => 'Test Address, Test City',
                'phone' => '555-0123',
                'clientId' => 'TEST001',
            ]),
            PrinterType::LABEL =>  LabelData::from([
                'productName' => 'Test Product',
                'barcode' => '1234567890',
                'price' => 99.99,
                'expiry' => '2023-12-31',
                'copies' => 1,
            ]),
        };
    }
}
