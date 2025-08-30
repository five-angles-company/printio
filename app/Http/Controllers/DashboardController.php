<?php

namespace App\Http\Controllers;

use App\Models\PrintJob;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        return inertia('dashboard', [
            'stats' => $dashboardService->getStats(),
            'jobs' => $dashboardService->getRecentJobs(),
        ]);
    }
}
