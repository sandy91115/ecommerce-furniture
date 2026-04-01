<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Services\ProductService;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->all();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->whereNull('parent_id')->get();
        $materials = \App\Models\Material::all();
        $colors = \App\Models\Color::all();
        // Single-vendor: no vendors dropdown
        return view('admin.products.create', compact('categories', 'materials', 'colors'));
    }

    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $product = $this->productService->create($data);

        if ($request->hasFile('images')) {
            $featuredIndex = (int) $request->input('featured_image_index', 0);
            $imageFiles = $request->file('images');
            $featuredImageId = null;

            foreach ($imageFiles as $index => $image) {
                $path = $image->store('products', 'public');
                $this->mirrorPublicStorageFile($path);
                $productImage = ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'featured' => $index == $featuredIndex,
                    'alt' => $product->name,
                ]);

                if ($index === $featuredIndex) {
                    $featuredImageId = $productImage->id;
                }
            }

            $this->ensureSingleFeaturedImage($product, $featuredImageId);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['images', 'category', 'vendor']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', 'active')->whereNull('parent_id')->get();
        $vendors = Schema::hasTable('vendors') ? Vendor::where('status', 'active')->get() : collect([]); // Single-vendor mode: empty vendors list
        $materials = \App\Models\Material::all();
        $colors = \App\Models\Color::all();
        return view('admin.products.edit', compact('product', 'categories', 'vendors', 'materials', 'colors'));
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $product = $this->productService->update($product->id, $data);

        if (!$product) {
            return back()->withErrors(['error' => 'Product update failed or not found.']);
        }

        // Handle image deletion/replacement
        if ($request->boolean('replace_images')) {
            $product->deleteFiles();
        } else {
            $deleteIds = collect($request->input('delete_image', []))
                ->filter(fn ($value) => filled($value))
                ->map(fn ($value) => (int) $value)
                ->unique()
                ->values();

            if ($deleteIds->isNotEmpty()) {
                $product->images()
                    ->whereIn('id', $deleteIds)
                    ->get()
                    ->each(function (ProductImage $image) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                    });
            }
        }

        // Add new images
        $featuredImageId = null;

        if ($request->hasFile('images')) {
            $featuredIndex = (int) $request->input('featured_image_index', 0);
            $imageFiles = $request->file('images');

            foreach ($imageFiles as $index => $image) {
                $path = $image->store('products', 'public');
                $this->mirrorPublicStorageFile($path);
                $productImage = ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'featured' => $index == $featuredIndex,
                    'alt' => $product->name,
                ]);

                if ($index === $featuredIndex) {
                    $featuredImageId = $productImage->id;
                }
            }
        }

        $this->ensureSingleFeaturedImage($product->fresh(), $featuredImageId);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product->id);
        return redirect()->route('admin.products.index')->with('success', 'Product moved to Recycle Bin successfully.');
    }

    public function lowStock()
    {
        $products = $this->productService->lowStock();
        return view('admin.products.low-stock', compact('products'));
    }

    public function quotationProducts()
    {
        $products = Product::with(['category', 'vendor'])
            ->where('product_type', 'quotation')
            ->paginate(10);
        return view('admin.products.quotation-products', compact('products'));
    }

    protected function mirrorPublicStorageFile(string $relativePath): void
    {
        $sourceRoot = storage_path('app/public');
        $publicRoot = public_path('storage');

        if (realpath($sourceRoot) === realpath($publicRoot)) {
            return;
        }

        $normalizedPath = ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath), DIRECTORY_SEPARATOR);
        $sourcePath = $sourceRoot . DIRECTORY_SEPARATOR . $normalizedPath;
        $publicPath = $publicRoot . DIRECTORY_SEPARATOR . $normalizedPath;

        if (! File::exists($sourcePath)) {
            return;
        }

        File::ensureDirectoryExists(dirname($publicPath));
        File::copy($sourcePath, $publicPath);
    }

    protected function ensureSingleFeaturedImage(Product $product, ?int $featuredImageId = null): void
    {
        $images = $product->images()->orderBy('id')->get();

        if ($images->isEmpty()) {
            return;
        }

        $primaryImage = $featuredImageId
            ? $images->firstWhere('id', $featuredImageId)
            : ($images->firstWhere('featured', true) ?? $images->first());

        if (! $primaryImage) {
            return;
        }

        $product->images()->where('id', '!=', $primaryImage->id)->update(['featured' => false]);

        if (! $primaryImage->featured) {
            $product->images()->whereKey($primaryImage->id)->update(['featured' => true]);
        }
    }
}
