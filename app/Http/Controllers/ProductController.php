<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

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

        $products = Product::with(['images', 'category'])
            ->where('status', 'active')
            ->where('product_type', $product->product_type)
            ->whereKeyNot($product->id)
            ->when($product->category_id, function ($query) use ($product) {
                $query->where('category_id', $product->category_id);
            })
            ->latest()
            ->take(4)
            ->get();

        $whatsappNumber = Setting::get(Setting::WHATSAPP_NUMBER);
        $isQuotationProduct = $product->product_type === 'quotation';

        return view('product-details', compact(
            'product',
            'products',
            'whatsappNumber',
            'isQuotationProduct'
        ));
    }

    public function index()
    {
        $products = Product::with('category')->where('status', 'active')->paginate(12);
        return view('shop', compact('products'));
    }

    public function quotationProducts(Request $request)
    {
        $query = Product::with(['images', 'category'])
            ->where('status', 'active')
            ->where('product_type', 'quotation');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($categoryQuery) use ($request) {
                $categoryQuery->where('slug', $request->category);
            });
        }

        $products = $query->latest()->paginate(12);

        return view('quotation-products', compact('products'));
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
