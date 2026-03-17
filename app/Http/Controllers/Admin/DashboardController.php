<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $stats = $this->dashboardService->stats();
        $salesChartData = $this->dashboardService->salesChartData();
        return view('admin.dashboard.index', compact('stats', 'salesChartData'));
    }
}

