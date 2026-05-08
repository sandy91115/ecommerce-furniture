<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
<<<<<<< HEAD
use App\Services\Seo\SeoManager;
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $store = [
            'name' => Setting::get('site_name', 'Furnixar'),
            'description' => Setting::get('store_description', 'Welcome to our store.'),
            'phone' => Setting::get('store_phone'),
            'logo_path' => Setting::get('store_logo_path'),
            'banner_path' => Setting::get('store_banner_path'),
        ];

        $products = Product::query()
            ->where('status', 'active')
            ->with(['images', 'category', 'reviews', 'variations'])
            ->paginate(12);

<<<<<<< HEAD
        $seo = app(SeoManager::class)->forCurrentPage($store['name'], $store['description']);

        return view('frontend.store', compact('store', 'products', 'seo'));
=======
        return view('frontend.store', compact('store', 'products'));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function show(string $slug)
    {
        // Backward compatibility for old /store/{slug} links.
        return redirect()->route('store.index');
    }
}

