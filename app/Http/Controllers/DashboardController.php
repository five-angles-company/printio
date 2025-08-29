<?php

namespace App\Http\Controllers;


class DashboardController extends Controller
{
    public function index()
    {
        return inertia('dashboard', [
            'stats' => [
                'totalPrinters' => 0,
                'totalJobs' => 0,
                'successRate' => 0,
            ],
            'jobs' => [],
        ]);
    }
}
