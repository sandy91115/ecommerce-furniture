<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Services\Seo\SeoManager;
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
            'reviews.user:id,name',
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

        if ($products->isEmpty()) {
            $products = Product::with(['images', 'category'])
                ->where('status', 'active')
                ->whereKeyNot($product->id)
                ->latest()
                ->take(8)
                ->get();
        }

        $whatsappNumber = Setting::get(Setting::WHATSAPP_NUMBER);
        $isQuotationProduct = $product->product_type === 'quotation';
        $seo = app(SeoManager::class)->forProduct($product);

        return view('product-details', compact(
            'product',
            'products',
            'whatsappNumber',
            'isQuotationProduct',
            'seo'
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
        $seo = app(SeoManager::class)->forCurrentPage(
            'Custom Quotation Products',
            'Browse made-to-order furniture and custom products available for quotation.'
        );

        return view('quotation-products', compact('products', 'seo'));
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
        $seo = app(SeoManager::class)->forCurrentPage(
            $query ? "Search results for {$query}" : 'Search',
            'Search furniture products and home decor collections.'
        );

        return view('shop', compact('products', 'query', 'seo'));
    }
}
