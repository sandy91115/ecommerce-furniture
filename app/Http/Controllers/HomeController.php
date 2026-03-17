<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Product;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('status','active')
            ->with(['category','vendor','images'])
            ->latest()
            ->take(8)
            ->get();

        return view('index', compact('products'));
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

    public function indexV5()
    {
        return view('index-v5');  
    }

    public function indexV6()
    {
        return view('index-v6');  
    }

    public function about()
    {
        return view('about');  
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
        return view('terms-and-conditions');  
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
        return view('my-profile');  
    }

    public function myAccount()
    {
        return view('my-account');  
    }

    public function editAccount()
    {
        return view('edit-account');  
    }

    public function orderHistory()
    {
        return view('order-history');  
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

    public function paymentConfirmation()
    {
        return view('payment-confirmation');  
    }

    public function paymentSuccess(\App\Models\Order $order)
    {
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
        return view('product-details');  
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

        return view('blog-details-v3', compact(
            'blog', 'prevBlog', 'nextBlog', 
            'recentBlogs', 'relatedBlogs', 
            'categories', 'tags'
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
        return view('contact');  
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}
