<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
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
        $siteName = Setting::get(Setting::SITE_NAME, config('app.name', 'Furniture Store')) ?: config('app.name', 'Furniture Store');
        $currencyCode = strtoupper((string) (Setting::get(Setting::CURRENCY, 'INR') ?: 'INR'));
        $price = (float) ($product->sale_price ?: $product->price);
        $canonicalUrl = route('product-details', $product->slug);
        $availability = $isQuotationProduct || (int) $product->stock > 0
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock';

        $schemaImageUrls = $product->images
            ->sortByDesc(fn ($image) => (int) $image->featured)
            ->map(fn ($image) => $image->path ? asset('storage/' . ltrim($image->path, '/')) : null)
            ->filter()
            ->values();

        if ($schemaImageUrls->isEmpty()) {
            $schemaImageUrls = collect([asset('assets/img/product/default.jpg')]);
        }

        $schemaDescription = trim((string) preg_replace(
            '/\s+/',
            ' ',
            strip_tags($product->seo_description ?: $product->short_description ?: $product->description ?: $product->name)
        ));

        $metaDescription = Str::limit($schemaDescription, 160, '');

        $productSchema = array_filter([
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $schemaImageUrls->count() === 1 ? $schemaImageUrls->first() : $schemaImageUrls->all(),
            'description' => $schemaDescription,
            'brand' => [
                '@type' => 'Brand',
                'name' => data_get($product, 'vendor.store_name') ?: $siteName,
            ],
            'sku' => $product->sku,
            'category' => $product->category?->name,
            'offers' => [
                '@type' => 'Offer',
                'url' => $canonicalUrl,
                'priceCurrency' => $currencyCode,
                'price' => number_format($price, 2, '.', ''),
                'availability' => $availability,
            ],
        ], fn ($value) => ! is_null($value) && $value !== '');

        return view('product-details', compact(
            'product',
            'products',
            'whatsappNumber',
            'isQuotationProduct',
            'metaDescription',
            'productSchema',
            'canonicalUrl'
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
