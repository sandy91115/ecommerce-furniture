<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeBannerController extends Controller
{
    public function index(Request $request)
    {
        $banners = HomeBanner::with('product')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('offer_title', 'like', "%{$search}%")
                        ->orWhere('season_text', 'like', "%{$search}%");
                });
            })
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create', [
            'banner' => new HomeBanner([
                'eyebrow' => 'Featured Pick',
                'season_year' => '2026',
                'season_text' => 'Summer',
                'title' => 'Modern Collections',
                'description' => 'Upgrade your living space with our latest modern furniture collections.',
                'button_text' => 'View Product',
                'secondary_button_text' => 'Shop Collection',
                'theme_color' => '#E3B505',
                'order' => (int) HomeBanner::max('order') + 1,
                'status' => 'active',
            ]),
            'products' => $this->products(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadImage($request);

        HomeBanner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(HomeBanner $banner)
    {
        return view('admin.banners.edit', [
            'banner' => $banner,
            'products' => $this->products(),
        ]);
    }

    public function update(Request $request, HomeBanner $banner)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($banner);
            $data['image'] = $this->uploadImage($request);
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(HomeBanner $banner)
    {
        $this->deleteImage($banner);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'eyebrow' => 'required|string|max:255',
            'offer_price' => 'nullable|string|max:100',
            'offer_title' => 'nullable|string|max:255',
            'season_year' => 'required|string|max:20',
            'season_text' => 'required|string|max:255',
            'kicker' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'button_text' => 'required|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'theme_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'order' => 'nullable|integer|min:0|max:999',
            'status' => 'required|in:active,inactive',
        ]);
    }

    private function products()
    {
        return Product::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'price', 'sale_price']);
    }

    private function uploadImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('banners', 'public');
    }

    private function deleteImage(HomeBanner $banner): void
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
    }
}
