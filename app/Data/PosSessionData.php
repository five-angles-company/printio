<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/** @typescript */
class PosSessionData extends Data
{
    public function __construct(
        public int $id,
        public string $branchName,
        public string $userName,
        public string $status,
        public string $startBalance,
        public float $totalSales,
        public float $totalInsurance,
        public float $totalCash,
        public float $cashReturn,
        public float $insuranceReturn,
        public float $customerDiscount,
        public float $insuranceDiscount,
        public float $discrepancies,
        public float $totalVisa,
        public float $totalCard,
        public float $totalMada,
        public float $totalMoqafaat,
        public float $totalStc,
        public float $totalTabby,
        public float $totalTamara,
        public float $closingBalance,
        public string $createdBy,
        public int $totalSaleInvoices,
        public int $totalReturnInvoices,
        public int $totalSession,
        public string $createdAt,
        public string $updatedAt,
        public string $closedAt,
        public string $totalTime,
        public float $netCash = 0
    ) {}
}
