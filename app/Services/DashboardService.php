<?php

namespace App\Services;

use App\Enums\PrintJobStatus;
use App\Models\Printer;
use App\Models\PrintJob;

class DashboardService
{
    public function getStats(): array
    {
        return [
            'totalPrinters' => $this->getTotalPrinters(),
            'totalJobs'     => $this->getTotalJobs(),
            'successRate'   => $this->getSuccessRate(),
        ];
    }

    public function getRecentJobs(int $limit = 10)
    {
        return PrintJob::with('printer')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    private function getTotalPrinters(): int
    {
        return Printer::count();
    }

    private function getTotalJobs(): int
    {
        return PrintJob::count();
    }

    private function getSuccessRate(): float
    {
        $total = PrintJob::count();
        $completed = PrintJob::where('status', PrintJobStatus::COMPLETED)->count();

        if ($total === 0) {
            return 0.0;
        }

        return ($completed / $total) * 100;
    }
}
