<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;

class DashboardService
{
    public function stats()
    {
        return [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_customers' => User::role('user')->count(),
            'total_products' => Product::where('status', 'active')->count(),
            'recent_orders' => Order::with('user')->latest()->limit(5)->get(),
            'best_selling_products' => Product::withCount('orders as sales_count')
                ->orderBy('sales_count', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    public function salesChartData(int $days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        $dailySales = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailySales[$date] = Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
        }

        return $dailySales;
    }
}

