<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with([
            'vendor.user',
            'category',
            'material',
            'color',
            'images',
            'variations',
            'attributeMaps.attribute.values',
        ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        return view('product-details', compact('product'));
    }

    public function index()
    {
        $products = Product::with('category')->where('status', 'active')->paginate(12);
        return view('shop', compact('products'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $products = Product::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate(12);

        return view('shop', compact('products', 'query'));
    }
}
