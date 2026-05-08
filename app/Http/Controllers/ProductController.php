<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
<<<<<<< HEAD
use App\Services\Seo\SeoManager;
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD
            'reviews.user:id,name',
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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

<<<<<<< HEAD
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
=======
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
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return view('product-details', compact(
            'product',
            'products',
            'whatsappNumber',
            'isQuotationProduct',
<<<<<<< HEAD
            'seo'
=======
            'metaDescription',
            'productSchema',
            'canonicalUrl'
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD
        $seo = app(SeoManager::class)->forCurrentPage(
            'Custom Quotation Products',
            'Browse made-to-order furniture and custom products available for quotation.'
        );

        return view('quotation-products', compact('products', 'seo'));
=======

        return view('quotation-products', compact('products'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD
        $seo = app(SeoManager::class)->forCurrentPage(
            $query ? "Search results for {$query}" : 'Search',
            'Search furniture products and home decor collections.'
        );

        return view('shop', compact('products', 'query', 'seo'));
=======

        return view('shop', compact('products', 'query'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }
}
