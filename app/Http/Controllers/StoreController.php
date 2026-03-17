<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show($slug)
    {
        $vendor = Vendor::where('store_slug', $slug)->with('user')->firstOrFail();
        $products = Product::where('vendor_id', $vendor->id)
            ->where('status', 'active')
            ->with('images', 'category')
            ->paginate(12);

        return view('frontend.store', compact('vendor', 'products'));
    }
}

