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
            'type' => $printer->type,
        ]);
    }

    public function getTestData(PrinterType $type): ReceiptData|LabelData|InstructionsData|PosSessionData
    {
        return match ($type) {
            PrinterType::RECEIPT => ReceiptData::from([
                'branchName' => 'Test Branch',
                'invoiceNumber' => 'INV-2026-001',
                'date' => date('Y-m-d H:i:s'),
                'items' => [
                    [
                        'name' => 'Panadol Extra 500mg',
                        'arabic_name' => 'بانادول اكسترا',
                        'quantity' => 2,
                        'unitPrice' => 15.00,
                        'totalPrice' => 30.00,
                    ],
                    [
                        'name' => 'Vitamin C 1000mg',
                        'arabic_name' => 'فيتامين سي',
                        'quantity' => 1,
                        'unitPrice' => 25.50,
                        'totalPrice' => 25.50,
                    ],
                    [
                        'name' => 'Augmentin 625mg',
                        'arabic_name' => 'اوجمنتين',
                        'quantity' => 1,
                        'unitPrice' => 45.00,
                        'totalPrice' => 45.00,
                    ],
                    [
                        'name' => 'Voltaren Gel 50g',
                        'arabic_name' => 'فولتارين جل',
                        'quantity' => 1,
                        'unitPrice' => 32.00,
                        'totalPrice' => 32.00,
                    ],
                    [
                        'name' => 'Zyrtec 10mg Tablets',
                        'arabic_name' => 'زيرتك حبوب',
                        'quantity' => 2,
                        'unitPrice' => 18.75,
                        'totalPrice' => 37.50,
                    ],
                    [
                        'name' => 'Nexium 40mg',
                        'arabic_name' => 'نيكسيوم',
                        'quantity' => 1,
                        'unitPrice' => 89.00,
                        'totalPrice' => 89.00,
                    ],
                    [
                        'name' => 'Brufen 400mg',
                        'arabic_name' => 'بروفين',
                        'quantity' => 3,
                        'unitPrice' => 12.00,
                        'totalPrice' => 36.00,
                    ],
                    [
                        'name' => 'Centrum Multivitamin',
                        'arabic_name' => 'سنتروم فيتامينات',
                        'quantity' => 1,
                        'unitPrice' => 75.00,
                        'totalPrice' => 75.00,
                    ],
                ],
                'subtotal' => 370.00,
                'tax' => 55.50,
                'total' => 425.50,
                'address' => 'King Fahd Road, Riyadh',
                'phone' => '0501234567',
                'client_name' => 'Mohammed Al-Qahtani',
                'client_tax_number' => '310123456700003',
                'userId' => 'USR-2026-001',
                'type' => 'sale',
                'returnCash' => null,
                'totalRefund' => null,
            ]),
            PrinterType::LABEL => LabelData::from([
                'productName' => 'Test Product',
                'barcode' => '1234567890123',
                'price' => 99.99,
                'expiry' => '2023.12.31',
                'copies' => 2,
            ]),

            PrinterType::INSTRUCTIONS => new InstructionsData(
                'فلان الفلاني',
                'فلان الفلاني',
                'Panadol Extra 12345',
                'حبة كل 8 ساعات',
                'بعد الأكل',
                'عن طريق الفم :)',
                'ألف سلامة',
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
                totalSession: 1,
                createdAt: '2024-12-17 22:24:06',
                updatedAt: '2024-12-17 22:41:05',
                closedAt: '2024-12-17 22:41:05',
                totalTime: '00:17:00'
            )
        };
    }
}
