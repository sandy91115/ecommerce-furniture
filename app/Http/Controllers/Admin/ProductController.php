<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Services\ImageUploadService;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use App\Services\ProductService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productService;
    protected $imageService;

    public function __construct(ProductService $productService, ImageUploadService $imageService)
    {
        $this->productService = $productService;
        $this->imageService = $imageService;
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
            $this->imageService->queueProductImages(
                product: $product,
                files: $request->file('images', []),
                featuredIndex: $request->integer('featured_image_index', 0),
                altTexts: $request->input('image_alts', []),
            );
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully. Images processing in background.');
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

        if ($request->boolean('replace_images')) {
            $this->imageService->deleteProductImages($product);
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
                    ->each(fn ($image) => $this->imageService->deleteImage($image));
            }
        }

        if ($request->hasFile('images')) {
            $this->imageService->queueProductImages(
                product: $product,
                files: $request->file('images', []),
                featuredIndex: $request->integer('featured_image_index', 0),
                altTexts: $request->input('image_alts', []),
            );
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully. New images processing in background.');
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




}
