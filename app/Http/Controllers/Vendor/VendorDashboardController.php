<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;
        
        if (!$user->hasRole('vendor')) {
            abort(403);
        }

        $totalProducts = Product::where('vendor_id', $vendor->id)->count();
        $totalOrders = Order::whereHas('items.product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->count();
        $totalEarnings = Order::whereHas('items.product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->where('payment_status', 'paid')->sum('total_price');
        
        $recentOrders = Order::whereHas('items.product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })
        ->with('items')
        ->latest()
        ->limit(5)
        ->get();

        return view('vendor.dashboard.index', compact('totalProducts', 'totalOrders', 'totalEarnings', 'recentOrders', 'vendor'));
    }
}

