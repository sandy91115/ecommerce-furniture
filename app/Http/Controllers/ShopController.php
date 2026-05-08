<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Seo\SeoManager;

class ShopController extends Controller
{
    public function index(Request $request, ?Category $category = null)
    {
        if ($category && $category->status !== 'active') {
            abort(404);
        }

        $selectedCategory = $category?->loadMissing('parent');
        $requestedCategorySlug = trim((string) $request->query('category'));
        $hasInvalidCategoryFilter = false;

        if ($requestedCategorySlug !== '' && (!$selectedCategory || $selectedCategory->slug !== $requestedCategorySlug)) {
            $requestedCategory = Category::query()
                ->where('status', 'active')
                ->with('parent')
                ->where('slug', $requestedCategorySlug)
                ->first();

            if ($requestedCategory) {
                if (! $request->ajax()) {
                    return redirect()->route(
                        'shop.category',
                        array_merge(['category' => $requestedCategory], $request->except('category')),
                        301
                    );
                }

                $selectedCategory = $requestedCategory;
            } elseif (! $selectedCategory) {
                $hasInvalidCategoryFilter = true;
            }
        }

        $query = Product::query()
            ->with(['category.parent', 'images'])
            ->where('status', 'active');

        if ($selectedCategory) {
            $query->whereHas('category', function (Builder $categoryQuery) use ($selectedCategory) {
                $categoryQuery->where('slug', $selectedCategory->slug);
            });
        } elseif ($hasInvalidCategoryFilter) {
            $query->whereRaw('1 = 0');
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
            ->with('parent:id,name')
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('name')
            ->get();

        $priceRangeQuery = Product::query()->where('status', 'active');

        if ($selectedCategory) {
            $priceRangeQuery->whereHas('category', function (Builder $categoryQuery) use ($selectedCategory) {
                $categoryQuery->where('slug', $selectedCategory->slug);
            });
        } elseif ($hasInvalidCategoryFilter) {
            $priceRangeQuery->whereRaw('1 = 0');
        }

        $minPrice = $priceRangeQuery->min(DB::raw('COALESCE(sale_price, price)')) ?? 0;
        $maxPrice = $priceRangeQuery->max(DB::raw('COALESCE(sale_price, price)')) ?? 1000;

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

        $breadcrumbCategories = $selectedCategory ? $this->buildCategoryTrail($selectedCategory) : collect();
        $selectedCategorySlug = $selectedCategory?->slug;
        $pageTitle = $selectedCategory?->meta_title ?: ($selectedCategory?->name ?: 'Shop');
        $pageDescription = $selectedCategory?->meta_description ?: 'Explore our premium furniture collection.';
        $canonicalUrl = $selectedCategory
            ? route('shop.category', ['category' => $selectedCategory->slug])
            : route('shop');
        $seo = app(SeoManager::class)->forShop($selectedCategory);

        if ($request->ajax()) {
            $productsGrid = view('includes.Shop.shops-v3', ['products' => $products])->render();
            $pagination = $products->appends(request()->query())->links()->toHtml();
            return response()->json([
                'products_html' => $productsGrid,
                'pagination_html' => $pagination,
                'results_count' => $products->total(),
            ]);
        }

        return view('shop', compact(
            'products',
            'categories',
            'featuredProducts',
            'minPrice',
            'maxPrice',
            'selectedCategory',
            'selectedCategorySlug',
            'breadcrumbCategories',
            'pageTitle',
            'pageDescription',
            'canonicalUrl',
            'seo'
        ));
    }

    protected function buildCategoryTrail(Category $category)
    {
        $trail = collect();
        $currentCategory = $category->loadMissing('parent');

        while ($currentCategory) {
            $trail->prepend($currentCategory);
            $currentCategory = $currentCategory->parent?->loadMissing('parent');
        }

        return $trail->values();
    }
}

