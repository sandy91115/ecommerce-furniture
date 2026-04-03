@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Product</h1>
            <p class="mt-1 text-sm text-gray-600">Create a new product and add it to your catalog.</p>
        </div>

        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-medium">
            Back to Products
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-8">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Single-vendor mode: vendor automatically set -->

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror">
                    @error('sku')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror">
                    @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price *</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                    @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                    <input type="number" name="sale_price" step="0.01" min="0" value="{{ old('sale_price') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sale_price') border-red-500 @enderror">
                    @error('sale_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax Slab *</label>
                    <select name="tax_slab" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tax_slab') border-red-500 @enderror">
                        <option value="">Select Tax Slab</option>
                        <option value="nil_rate" {{ old('tax_slab') == 'nil_rate' ? 'selected' : '' }}>0% (Nil Rate)</option>
                        <option value="5" {{ old('tax_slab') == '5' ? 'selected' : '' }}>5% Slab</option>
                        <option value="18" {{ old('tax_slab') == '18' ? 'selected' : '' }}>18% Slab</option>
                        <option value="40" {{ old('tax_slab') == '40' ? 'selected' : '' }}>40% Slab</option>
                        <option value="special_rates" {{ old('tax_slab') == 'special_rates' ? 'selected' : '' }}>Special Rates</option>
                    </select>
                    @error('tax_slab')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                    <input type="number" name="stock" min="0" value="{{ old('stock') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror">
                    @error('stock')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                    <input type="number" name="weight" step="0.01" min="0" value="{{ old('weight') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('weight') border-red-500 @enderror">
                    @error('weight')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                    <select name="material_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('material_id') border-red-500 @enderror">
                        <option value="">Select Material</option>
                        @foreach($materials as $material)
                        <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>
                            {{ $material->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('material_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <select name="color_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('color_id') border-red-500 @enderror">
                        <option value="">Select Color</option>
                        @foreach($colors as $color)
                        <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                            {{ $color->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('color_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Warranty (months)</label>
                    <input type="number" name="warranty_months" min="0" value="{{ old('warranty_months') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('warranty_months') border-red-500 @enderror">
                    @error('warranty_months')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center space-x-2 mt-8">
                    <input type="hidden" name="assembly_required" value="0">
                    <input type="checkbox" name="assembly_required" value="1" {{ old('assembly_required') ? 'checked' : '' }} class="rounded border-gray-300">
                    <label class="text-sm font-medium text-gray-700">Assembly Required</label>
                </div>

                <div class="flex items-center space-x-2 mt-8">
                    <input type="hidden" name="featured" value="0">
                    <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 font-medium">Featured Product</span>
                </div>

                <!-- Product Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Type *</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="product_type" value="sell" {{ old('product_type', 'sell') == 'sell' ? 'checked' : '' }} required class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Sell</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="product_type" value="quotation" {{ old('product_type') == 'quotation' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Quotation</span>
                        </label>
                    </div>
                    @error('product_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Sell: Regular product for cart/purchase. Quotation: Special inquiry form for customers.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SEO Title</label>
                    <input type="text" name="seo_title" value="{{ old('seo_title') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('seo_title') border-red-500 @enderror">
                    @error('seo_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SEO Description</label>
                    <input type="text" name="seo_description" value="{{ old('seo_description') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('seo_description') border-red-500 @enderror">
                    @error('seo_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Dimensions (JSON)</label>
                <textarea name="dimensions" rows="3" placeholder='{"length": 200, "width": 100, "height": 80, "unit": "cm"}' class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('dimensions') border-red-500 @enderror">{{ old('dimensions') }}</textarea>
                @error('dimensions')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Example: {"length": 200, "width": 100, "height": 80, "unit": "cm"}</p>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description *</label>
                <textarea name="short_description" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('short_description') border-red-500 @enderror">{{ old('short_description') }}</textarea>
                @error('short_description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea id="description" name="description" rows="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Extra Info (Optional) -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Extra Info Title (Optional)</label>
                <input type="text" name="extra_title" value="{{ old('extra_title') }}" placeholder="e.g., Refund Policy" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('extra_title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Extra Info Description (Optional)</label>
                <textarea id="extra_description" name="extra_description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('extra_description') border-red-500 @enderror">{{ old('extra_description') }}</textarea>
                @error('extra_description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Images (Multiple) *
                    <p class="text-xs text-gray-500 mt-1">Allowed: JPG, JPEG, PNG. Max 2MB per image. Images are converted to WEBP in the background.</p>
                </label>
                <input type="hidden" name="featured_image_index" id="featuredImageIndex" value="0">
                <input type="file" id="imagesInput" name="images[]" multiple accept=".jpg,.jpeg,.png" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('images') border-red-500 @enderror">
                @error('images')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="imagePreview" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="col-span-full rounded-xl border border-dashed border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                        
                        Selected image preview here. You can remove the image
                    </div>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                    Create Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('imagesInput');
        const preview = document.getElementById('imagePreview');
        const featuredIndexInput = document.getElementById('featuredImageIndex');

        if (!input || !preview || !featuredIndexInput) {
            return;
        }

        let selectedFiles = [];
        let featuredIndex = 0;
        let objectUrls = [];

        const fileKey = (file) => `${file.name}-${file.size}-${file.lastModified}`;

        const clearObjectUrls = () => {
            objectUrls.forEach((url) => URL.revokeObjectURL(url));
            objectUrls = [];
        };

        const syncInputFiles = () => {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach((file) => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
            featuredIndexInput.value = selectedFiles.length ? featuredIndex : 0;
        };

        const renderEmptyState = () => {
            preview.innerHTML = `
                <div class="col-span-full rounded-xl border border-dashed border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                        
                        Selected image preview here. You can remove the image
                </div>
            `;
        };

        const renderPreviews = () => {
            clearObjectUrls();

            if (!selectedFiles.length) {
                renderEmptyState();
                syncInputFiles();
                return;
            }

            if (featuredIndex >= selectedFiles.length) {
                featuredIndex = 0;
            }

            preview.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const objectUrl = URL.createObjectURL(file);
                objectUrls.push(objectUrl);

                const card = document.createElement('div');
                card.className = 'overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm';
                card.innerHTML = `
                    <div class="relative">
                        <img src="${objectUrl}" alt="${file.name}" class="h-48 w-full object-cover">
                        <button type="button" class="absolute right-3 top-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-red-600 text-white shadow-lg transition hover:bg-red-700" data-remove-index="${index}" aria-label="Remove image">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                        <span class="absolute left-3 top-3 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ${index === featuredIndex ? 'bg-blue-600 text-white' : 'bg-white/90 text-gray-700'}">
                            ${index === featuredIndex ? 'Featured image' : 'Selected image'}
                        </span>
                    </div>
                    <div class="space-y-3 p-4">
                        <div>
                            <p class="truncate text-sm font-semibold text-gray-800">${file.name}</p>
                            <p class="mt-1 text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                        </div>
                        <button type="button" class="inline-flex items-center rounded-lg px-3 py-2 text-xs font-semibold transition ${index === featuredIndex ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}" data-feature-index="${index}">
                            ${index === featuredIndex ? 'Featured selected' : 'Set as featured'}
                        </button>
                    </div>
                `;

                card.querySelector('[data-remove-index]').addEventListener('click', () => {
                    selectedFiles.splice(index, 1);

                    if (featuredIndex > index) {
                        featuredIndex -= 1;
                    } else if (featuredIndex === index) {
                        featuredIndex = 0;
                    }

                    renderPreviews();
                });

                card.querySelector('[data-feature-index]').addEventListener('click', () => {
                    featuredIndex = index;
                    renderPreviews();
                });

                preview.appendChild(card);
            });

            syncInputFiles();
        };

        input.addEventListener('change', (event) => {
            const incomingFiles = Array.from(event.target.files || []);

            if (!incomingFiles.length) {
                return;
            }

            const existingKeys = new Set(selectedFiles.map(fileKey));

            incomingFiles.forEach((file) => {
                const key = fileKey(file);
                if (!existingKeys.has(key)) {
                    selectedFiles.push(file);
                    existingKeys.add(key);
                }
            });

            renderPreviews();
        });

        window.addEventListener('beforeunload', clearObjectUrls);
    })();
</script>
@endpush
@endsection
