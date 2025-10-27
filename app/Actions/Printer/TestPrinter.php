<?php

namespace App\Actions\Printer;

use App\Data\InstructionsData;
use App\Data\LabelData;
use App\Data\PosSessionData;
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

    public function getTestData(PrinterType $type): ReceiptData|LabelData|InstructionsData|PosSessionData
    {
        return match ($type) {
            PrinterType::RECEIPT =>  ReceiptData::from([
                'invoiceNumber' => 'INV001',
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
                'type' => 'sale',
                'returnCash' => null,
                'totalRefund' => null
            ]),
            PrinterType::LABEL =>  LabelData::from([
                'productName' => 'Test Product',
                'barcode' => '1234567890',
                'price' => 99.99,
                'expiry' => '2023-12-31',
                'copies' => 1,
            ]),

            PrinterType::INSTRUCTIONS => new InstructionsData(
                "فلان الفلاني",
                "فلان الفلاني",
                "Panadol Extra 12345",
                "حبة كل 8 ساعات",
                "بعد الأكل",
                "عن طريق الفم :)",
                "ألف سلامة",
                1
            ),

            PrinterType::POS_SESSION => new PosSessionData(
                id: 120241217001,
                branchName: 'PHARMACY 1',
                userName: 'YAHIA',
                status: 'closed',
                startBalance: '0.00',
                totalSales: 0.00,
                totalInsurance: 0.00,
                totalCash: 0.00,
                cashReturn: 0.00,
                insuranceReturn: 0.00,
                customerDiscount: 0.00,
                insuranceDiscount: 0.00,
                discrepancies: 0.00,
                totalVisa: 0.00,
                totalCard: 0.00,
                totalMada: 0.00,
                totalMoqafaat: 0.00,
                totalStc: 0.00,
                totalTabby: 0.00,
                totalTamara: 0.00,
                closingBalance: 0.00,
                createdBy: 'YAHIA',
                totalSaleInvoices: 0,
                totalReturnInvoices: 0,
                createdAt: '2024-12-17 22:24:06',
                updatedAt: '2024-12-17 22:41:05',
                closedAt: '2024-12-17 22:41:05',
                totalTime: '00:17:00'
            )
        };
    }
}
