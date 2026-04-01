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
            'total_customers' => User::where(function ($query) {
                $query->whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'customer');
                })->orWhere(function ($customerQuery) {
                    $customerQuery->whereDoesntHave('roles.permissions', function ($permissionQuery) {
                        $permissionQuery->where('name', 'admin.access');
                    })->whereDoesntHave('roles', function ($roleQuery) {
                        $roleQuery->where('name', 'vendor');
                    });
                });
            })->count(),
            'total_products' => Product::where('status', 'active')->count(),
            'total_enquiries' => \App\Models\Contact::count(),
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

    public function userStats($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        return [
            'user' => $user,
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'wishlist_count' => app(\App\Services\CartService::class)->wishlistCount(),
            'recent_orders' => $user->orders()->latest()->limit(5)->get(),
        ];
    }
}
