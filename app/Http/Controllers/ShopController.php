<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['category', 'images'])
            ->where('status', 'active');
        
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->filled('min_price')) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [(float) $request->min_price]);
        }
        
        if ($request->filled('max_price')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [(float) $request->max_price]);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'price-asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price-desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'name-asc':
                $query->orderBy('name', 'ASC');
                break;
            case 'name-desc':
                $query->orderBy('name', 'DESC');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        $products = $query->paginate(12);

        $categories = Category::where('status', 'active')
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->take(8)
            ->get();

        $minPrice = Product::where('status', 'active')->min(DB::raw('COALESCE(sale_price, price)')) ?? 0;
        $maxPrice = Product::where('status', 'active')->max(DB::raw('COALESCE(sale_price, price)')) ?? 1000;

        $featuredProducts = Product::where('status', 'active')
            ->where('featured', true)
            ->with('images')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
        if ($featuredProducts->count() < 5) {
            $additional = Product::where('status', 'active')
                ->where('featured', false)
                ->with('images')
                ->inRandomOrder()
                ->limit(5 - $featuredProducts->count())
                ->get();
            $featuredProducts = $featuredProducts->concat($additional);
        }

        if ($request->ajax()) {
            $productsGrid = view('includes.Shop.shops-v3', ['products' => $products])->render();
            $pagination = $products->appends(request()->query())->links()->toHtml();
            return response()->json([
                'products_html' => $productsGrid,
                'pagination_html' => $pagination,
                'results_count' => $products->total(),
            ]);
        }

        return view('shop', compact('products', 'categories', 'featuredProducts', 'minPrice', 'maxPrice'));
    }
}

