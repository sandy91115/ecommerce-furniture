<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

class VendorProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category'])
            ->where('vendor_id', auth()->user()->vendor->id)
            ->paginate(10);
        return view('vendor.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        return view('vendor.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'images.*' => 'image|max:2048',
        ]);

        $data = $request->all();
        $data['vendor_id'] = auth()->user()->vendor->id;
        $data['status'] = 'pending';
        $data['slug'] = Str::slug($request->name);

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('vendor.products.index')->with('success', 'Product created and sent for approval.');
    }

    public function edit(Product $product)
    {
        if ($product->vendor_id !== auth()->user()->vendor->id) {
            abort(403);
        }

        $categories = Category::where('status', 'active')->get();
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->vendor_id !== auth()->user()->vendor->id) {
            abort(403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'images.*' => 'image|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $product->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('vendor.products.index')->with('success', 'Product updated and sent for approval.');
    }

    public function destroy(Product $product)
    {
        if ($product->vendor_id !== auth()->user()->vendor->id) {
            abort(403);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return redirect()->route('vendor.products.index')->with('success', 'Product deleted.');
    }
}


