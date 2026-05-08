<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
<<<<<<< HEAD
use App\Models\BlogCategory;
use App\Models\Product;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\HomeBanner;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\Seo\SeoManager;
=======
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('status','active')
            ->with(['category','vendor','images'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::whereNull('parent_id')
            ->where('status', 'active')
            ->with('children')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->take(8)
            ->get();

        $latestBlogs = Blog::where('status', 'published')
<<<<<<< HEAD
            ->with('categories')
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            ->latest()
            ->take(5)
            ->get();

        $hotDeals = Product::where('status', 'active')
            ->where('sale_price', '<', DB::raw('price'))
            ->with(['images', 'category'])
            ->orderBy('sale_price', 'asc')
            ->take(4)
            ->get();

<<<<<<< HEAD
        $homeBanners = HomeBanner::active()
            ->ordered()
            ->with(['product.images', 'product.category'])
            ->take(6)
            ->get();

        if ($homeBanners->isEmpty()) {
            $fallbackProducts = Product::where('status', 'active')
                ->where('featured', true)
                ->with(['images', 'category'])
                ->latest()
                ->take(4)
                ->get();

            if ($fallbackProducts->count() < 2) {
                $fallbackProducts = $fallbackProducts->concat(
                    Product::where('status', 'active')
                        ->when(
                            $fallbackProducts->isNotEmpty(),
                            fn ($query) => $query->whereNotIn('id', $fallbackProducts->pluck('id'))
                        )
                        ->with(['images', 'category'])
                        ->latest()
                        ->take(4 - $fallbackProducts->count())
                        ->get()
                )->values();
            }

            $homeBanners = $fallbackProducts->map(function (Product $product, int $index) {
                $banner = new HomeBanner([
                  'product_id' => $product->id,
                  'eyebrow' => 'Featured Pick',
                  'season_year' => '2026',
                  'season_text' => 'Summer',
                  'title' => $index % 2 === 0 ? 'Wooden Furnitures' : 'Modern Collections',
                  'description' => $index % 2 === 0
                      ? 'Design your home with our luxury wooden furnitures.'
                      : 'Upgrade your living space with our latest modern furniture collections.',
                  'button_text' => 'View Product',
                  'secondary_button_text' => 'Shop Collection',
                  'theme_color' => ['#E3B505', '#8B6F47', '#6B8E23'][$index % 3],
                  'status' => 'active',
                ]);

                $banner->setRelation('product', $product);

                return $banner;
            });
        }
        $featuredPromoImagePath = Setting::get(Setting::HOME_FEATURED_PROMO_IMAGE_PATH);
        $featuredPromo = [
            'enabled' => filter_var(Setting::get(Setting::HOME_FEATURED_PROMO_ENABLED, true), FILTER_VALIDATE_BOOLEAN),
            'label' => Setting::get(Setting::HOME_FEATURED_PROMO_LABEL, 'Custom Orders'),
            'title' => Setting::get(Setting::HOME_FEATURED_PROMO_TITLE, 'Need a made-to-measure piece?'),
            'description' => Setting::get(Setting::HOME_FEATURED_PROMO_DESCRIPTION, 'Share your size, finish and resin color requirements. Our team will help craft a coffee table for your space.'),
            'button_text' => Setting::get(Setting::HOME_FEATURED_PROMO_BUTTON_TEXT, 'Request Custom Order'),
            'button_url' => Setting::get(Setting::HOME_FEATURED_PROMO_BUTTON_URL, route('quotation-products.index')),
            'image_url' => $featuredPromoImagePath ? asset('storage/' . $featuredPromoImagePath) : asset('assets/img/home-v1/choose-us-bg.jpg'),
        ];

        $seo = app(SeoManager::class)->forCurrentPage(
            Setting::get(Setting::SITE_NAME, 'Furniture Store'),
            'Premium furniture and home decor crafted for modern homes.'
        );

        return view('index', compact('products', 'categories', 'latestBlogs', 'hotDeals', 'homeBanners', 'featuredPromo', 'seo'));
=======
        $bannerProducts = Product::where('status', 'active')
            ->where('featured', true)
            ->with(['images', 'category'])
            ->latest()
            ->take(4)
            ->get();

        if ($bannerProducts->count() < 2) {
            $bannerProducts = $bannerProducts->concat(
                Product::where('status', 'active')
                    ->when(
                        $bannerProducts->isNotEmpty(),
                        fn ($query) => $query->whereNotIn('id', $bannerProducts->pluck('id'))
                    )
                    ->with(['images', 'category'])
                    ->latest()
                    ->take(4 - $bannerProducts->count())
                    ->get()
            )->values();
        }

        $bannerColors = ['#BB976D', '#627952'];
        $bannerTitles = ['Wooden Furnitures', 'Modern Collections'];
        $bannerDescs = ['Design your home with our luxury wooden furnitures.', 'Upgrade your living space with our latest modern furniture collections.'];
        $shopUrls = [route('shop'), route('shop')];

        return view('index', compact('products', 'categories', 'latestBlogs', 'hotDeals', 'bannerProducts', 'bannerColors', 'bannerTitles', 'bannerDescs', 'shopUrls'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function indexV2()
    {
        return view('index-v2');  
    }

    public function indexV3()
    {
        return view('index-v3');  
    }

    public function indexV4()
    {
        return view('index-v4');  
    }

<<<<<<< HEAD
public function indexV5()
    {
        $products = Product::where('status','active')
            ->with(['category','vendor','images'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::whereNull('parent_id')
            ->where('status', 'active')
            ->with(['children', 'products'])
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->take(9)
            ->get();

        return view('index-v5', compact('products', 'categories'));  
=======
    public function indexV5()
    {
        return view('index-v5');  
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function indexV6()
    {
        return view('index-v6');  
    }

    public function about()
    {
<<<<<<< HEAD
        $page = $this->activeCmsPage('about');
        $seo = $page ? app(SeoManager::class)->forCmsPage($page) : null;

        return view('about', compact('page', 'seo'));
=======
        return view('about');  
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function pricing()
    {
        return view('pricing');  
    }

    public function team()
    {
        return view('team');  
    }

    public function ourClients()
    {
        return view('our-clients');  
    }

    public function faq()
    {
        return view('faq');  
    }

    public function termsAndConditions()
    {
<<<<<<< HEAD
        return $this->fixedCmsPage('terms-and-conditions', 'terms-and-conditions');
=======
        return view('terms-and-conditions');  
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function portfolioV1()
    {
        return view('portfolio-v1');  
    }

    public function portfolioV2()
    {
        return view('portfolio-v2');  
    }

    public function portfolioV3()
    {
        return view('portfolio-v3');  
    }

    public function portfolioDetailsV1()
    {
        return view('portfolio-details-v1');  
    }

    public function portfolioDetailsV2()
    {
        return view('portfolio-details-v2');  
    }

    public function error()
    {
        return view('error');  
    }

    public function myProfile()
    {
        $user = auth()->user();
        return view('my-profile', compact('user'));  
    }

    public function myAccount()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        
        $dashboardService = new \App\Services\DashboardService();
        $stats = $dashboardService->userStats($user->id);
        
        return view('my-account', $stats);
    }

    public function editAccount()
    {
        $user = auth()->user();
        return view('edit-account', compact('user'));  
    }

    public function updateAccount(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'social_links' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function orderHistory()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $orders = $user->orders()->latest()->paginate(10);
        return view('order-history', compact('orders'));
    }

<<<<<<< HEAD
    public function quotationHistory()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $quotations = $user->quotations()->with('product.images')->latest()->paginate(10);
        return view('quotation-history', compact('quotations'));
=======
    public function userOrders()
    {
        return redirect()->route('frontend.account.orders');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function wishlist()
    {
        return view('wishlist');  
    }
    
    public function login()
    {
        return view('login');  
    }

    public function register()
    {
        return view('register');  
    }

    public function forgerPassword()
    {
        return view('forger-password');  
    }

    public function comingSoon()
    {
        return view('coming-soon');  
    }

    public function thankYou()
    {
        return view('thank-you');  
    }

    public function shippingMethod()
    {
        return view('shipping-method');  
    }

    public function paymentMethod()
    {
        return view('payment-method');  
    }

    public function invoice()
    {
        return view('invoice');  
    }

<<<<<<< HEAD
    public function paymentConfirmation(\Illuminate\Http\Request $request)
    {
        $order = null;

        if ($request->filled('order')) {
            $order = \App\Models\Order::with('payment')->findOrFail($request->integer('order'));
            $user = auth()->user();

            abort_unless($user && ((int) $order->user_id === (int) $user->id || $user->can('admin.access')), 403);
        }

        return view('payment-confirmation', compact('order'));  
=======
    public function paymentConfirmation()
    {
        return view('payment-confirmation');  
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function paymentSuccess(\App\Models\Order $order)
    {
<<<<<<< HEAD
        $user = auth()->user();

        abort_unless($user && ((int) $order->user_id === (int) $user->id || $user->can('admin.access')), 403);

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return view('payment-success', compact('order'));  
    }
    
    public function paymentFailure()
    {
        return view('payment-failure');  
    }

    public function shopV1()
    {
        return view('shop');  
    }

    public function shopV2()
    {
        return view('shop-v2');  
    }

    public function shopV3()
    {
        return view('shop-v3');  
    }

    public function shopV4()
    {
        return view('shop-v4');  
    }

    public function productCategory()
    {
        return view('product-category');  
    }
    
    public function productDetails()
    {
        $product = Product::where('status', 'active')->latest()->first();

        if (!$product) {
            return redirect()->route('shop');
        }

        return redirect()->route('product-details', $product->slug);
    }

    public function cart()
    {
        return view('cart');  
    }

    public function checkout()
    {
        return view('checkout');  
    }

    public function blogV2()
    {
        return view('blog-v2');
    }

    public function blogDetailsV3($title = null)
    {
<<<<<<< HEAD
        $blogQuery = Blog::published()->with('categories');

        $blog = $title
            ? (clone $blogQuery)->where('slug', $title)->firstOrFail()
            : $blogQuery->firstOrFail();

        $prevBlog = Blog::published()->with('categories')->where('id', '<', $blog->id)->orderBy('id', 'desc')->first();
        $nextBlog = Blog::published()->with('categories')->where('id', '>', $blog->id)->orderBy('id')->first();
        $recentBlogs = Blog::published()->with('categories')->where('id', '!=', $blog->id)->limit(4)->get();

        $relatedQuery = Blog::published()->with('categories')->where('id', '!=', $blog->id);
        $categoryIds = $blog->categories->pluck('id');

        if ($categoryIds->isNotEmpty()) {
            $relatedQuery->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('blog_categories.id', $categoryIds);
            });
        }

        $relatedBlogs = $relatedQuery->limit(5)->get();

        $categories = BlogCategory::whereHas('blogs', fn ($query) => $query->published())
            ->orderBy('name')
            ->pluck('name');
        $tags = $blog->categories->pluck('name')->values()->all();
        $seo = app(SeoManager::class)->forBlog($blog);
=======
        $blog = $title
            ? Blog::published()->where('slug', $title)->firstOrFail()
            : Blog::published()->firstOrFail();

        $prevBlog = Blog::published()->where('id', '<', $blog->id)->orderBy('id', 'desc')->first();
        $nextBlog = Blog::published()->where('id', '>', $blog->id)->orderBy('id')->first();
        $recentBlogs = Blog::published()->where('id', '!=', $blog->id)->limit(4)->get();

        $relatedQuery = Blog::published()->where('id', '!=', $blog->id);
        if ($blog->tags) {
            $relatedQuery->where(function ($q) use ($blog) {
                foreach ($blog->tags as $tag) {
                    $q->orWhereJsonContains('tags', $tag);
                }
            });
        }
        $relatedBlogs = $relatedQuery->limit(5)->get();

        $categories = Blog::published()->pluck('tags')->flatten()->unique()->values();
        $tags = $blog->tags ?? [];
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return view('blog-details-v3', compact(
            'blog', 'prevBlog', 'nextBlog', 
            'recentBlogs', 'relatedBlogs', 
<<<<<<< HEAD
            'categories', 'tags', 'seo'
=======
            'categories', 'tags'
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        ));
    }

    public function blogTag()
    {
        return view('blog-tag');  
    }

    public function shortCode()
    {
        return view('short-code');  
    }

    public function contact()
    {
<<<<<<< HEAD
        $page = $this->activeCmsPage('contact');
        $seo = $page ? app(SeoManager::class)->forCmsPage($page) : null;

        return view('contact', compact('page', 'seo'));
    }

    public function returnPolicy()
    {
        return $this->fixedCmsPage('return-policy', 'return-policy');
    }

    public function privacyPolicy()
    {
        return $this->fixedCmsPage('privacy-policy', 'privacy-policy');
    }

    private function fixedCmsPage(string $slug, string $fallbackView)
    {
        $page = $this->activeCmsPage($slug);

        if (! $page) {
            return view($fallbackView);
        }

        $seo = app(SeoManager::class)->forCmsPage($page);

        return view('cms-page', compact('page', 'seo'));
    }

    private function activeCmsPage(string $slug): ?CmsPage
    {
        $titleAliases = match ($slug) {
            'about' => ['About', 'About Us'],
            'contact' => ['Contact', 'Contact Us'],
            default => [],
        };

        return CmsPage::query()
            ->where('status', 'active')
            ->where(function ($query) use ($slug, $titleAliases) {
                $query->where('slug', $slug);

                if ($titleAliases !== []) {
                    $query->orWhereIn('title', $titleAliases);
                }
            })
            ->first();
=======
        return view('contact');  
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}
