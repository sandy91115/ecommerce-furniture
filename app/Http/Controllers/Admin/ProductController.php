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
            $featuredIndex = $request->input('featured_image_index', 0); // From radio button
            $imageFiles = $request->file('images');
            
            foreach ($imageFiles as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'featured' => $index == $featuredIndex,
                    'alt' => $product->name,
                ]);
            }
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

        if ($request->hasFile('images')) {
            $featuredIndex = $request->input('featured_image_index', -1);
            $imageFiles = $request->file('images');
            
            foreach ($imageFiles as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'featured' => $index == $featuredIndex,
                    'alt' => $product->name,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product->id);
        return redirect()->route('admin.products.index')->with('success', 'Product moved to Recycle Bin successfully.');
    }

    public function pending()
    {
        $products = Product::with(['category', 'vendor'])
            ->where('status', 'pending')
            ->paginate(10);
        return view('admin.products.pending', compact('products'));
    }

    public function lowStock()
    {
        $products = $this->productService->lowStock();
        return view('admin.products.low-stock', compact('products'));
    }

    public function approve(Product $product)
    {
        $product->update(['status' => 'active']);
        return redirect()->route('admin.products.pending')->with('success', 'Product approved.');
    }

    public function reject(Product $product)
    {
        $product->update(['status' => 'rejected']);
        return redirect()->route('admin.products.pending')->with('success', 'Product rejected.');
    }

    public function quotationProducts()
    {
        $products = Product::with(['category', 'vendor'])
            ->where('product_type', 'quotation')
            ->paginate(10);
        return view('admin.products.quotation-products', compact('products'));
    }
}
