@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<<<<<<< HEAD
<div class=" mx-auto">
=======
<div class="max-w-7xl mx-auto">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Products</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your product catalog.</p>
        </div>
<<<<<<< HEAD
        <div class="flex flex-wrap items-center justify-end gap-2">
            <a href="{{ route('admin.products.export') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-medium">
                <i class="fas fa-file-export"></i>
                Export Excel
            </a>
            <a href="{{ route('admin.products.import-template') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-600 text-white hover:bg-slate-700 font-medium">
                <i class="fas fa-file-excel"></i>
                Excel Format
            </a>
            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                @csrf
                <input type="file" name="products_file" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required class="w-56 max-w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-medium">
                    <i class="fas fa-file-import"></i>
                    Import Excel
                </button>
            </form>
=======
<div class="flex items-center gap-2">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-medium">
                Add Product
            </a>
        </div>
    </div>

<<<<<<< HEAD
    @if(session('product_import_errors') && count(session('product_import_errors')))
        <div class="mb-6 rounded-lg border border-yellow-300 bg-yellow-100 p-4 text-yellow-900">
            <p class="font-semibold">Import me kuch rows skip hui:</p>
            <ul class="mt-2 list-disc pl-5 text-sm space-y-1">
                @foreach(array_slice(session('product_import_errors'), 0, 12) as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            @if(count(session('product_import_errors')) > 12)
                <p class="mt-2 text-sm">Aur {{ count(session('product_import_errors')) - 12 }} errors bhi hain.</p>
            @endif
        </div>
    @endif

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vendor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->vendor->store_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ currency($product->price) }}</td>



                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php($isActive = $product->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
<div class="inline-flex items-center gap-2">
<<<<<<< HEAD
                                    <a href="{{ route('product-details', $product->slug) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1.5 rounded-md bg-green-600 text-white hover:bg-green-700 font-medium" title="Open frontend product page">
=======
                                    <a href="{{ route('admin.products.show', $product) }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-green-600 text-white hover:bg-green-700 font-medium" title="View Details">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                                        View
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-600 text-white hover:bg-red-700 font-medium" onclick="return confirm('Delete this product?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
