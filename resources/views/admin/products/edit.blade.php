@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Product: {{ $product->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Update product details and inventory.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-medium">
            Back to Products
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-8">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vendor *</label>
                    <select name="vendor_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vendor_id') border-red-500 @enderror">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id', $product->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->store_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror">
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-2">
                        <a href="/product/{{ $product->slug }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Product <i class="fas fa-external-link-alt ml-1"></i></a>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price *</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                    <input type="number" name="sale_price" step="0.01" min="0" value="{{ old('sale_price', $product->sale_price) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sale_price') border-red-500 @enderror">
                    @error('sale_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                    <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                    <input type="number" name="weight" step="0.01" min="0" value="{{ old('weight', $product->weight) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('weight') border-red-500 @enderror">
                    @error('weight')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
<option value="pending" {{ old('status', $product->status) == 'pending' ? 'selected' : '' }}>Draft</option>
<option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
<option value="rejected" {{ old('status', $product->status) == 'rejected' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Type *</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="product_type" value="sell" {{ old('product_type', $product->product_type) == 'sell' ? 'checked' : '' }} required class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Sell</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="product_type" value="quotation" {{ old('product_type', $product->product_type) == 'quotation' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Quotation</span>
                        </label>
                    </div>
                    @error('product_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Sell: Regular product. Quotation: Customer inquiry form.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                    <select name="material_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('material_id') border-red-500 @enderror">
                        <option value="">Select Material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ old('material_id', $product->material_id) == $material->id ? 'selected' : '' }}>
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
                            <option value="{{ $color->id }}" {{ old('color_id', $product->color_id) == $color->id ? 'selected' : '' }}>
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
                    <input type="number" name="warranty_months" min="0" value="{{ old('warranty_months', $product->warranty_months) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('warranty_months') border-red-500 @enderror">
                    @error('warranty_months')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center space-x-2 mt-8">
                    <input type="hidden" name="assembly_required" value="0">
                    <input type="checkbox" name="assembly_required" value="1" {{ old('assembly_required', $product->assembly_required) ? 'checked' : '' }} class="rounded border-gray-300">
                    <label class="text-sm font-medium text-gray-700">Assembly Required</label>
                </div>

                <div class="flex items-center space-x-2 mt-8">
                    <input type="hidden" name="featured" value="0">
                    <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 font-medium">Featured Product</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SEO Title</label>
                    <input type="text" name="seo_title" value="{{ old('seo_title', $product->seo_title) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('seo_title') border-red-500 @enderror">
                    @error('seo_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SEO Description</label>
                    <input type="text" name="seo_description" value="{{ old('seo_description', $product->seo_description) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('seo_description') border-red-500 @enderror">
                    @error('seo_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Dimensions (JSON)</label>
                <textarea name="dimensions" rows="3" placeholder='{"length": 200, "width": 100, "height": 80, "unit": "cm"}' class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('dimensions') border-red-500 @enderror">{{ old('dimensions', is_array($product->dimensions) ? json_encode($product->dimensions) : $product->dimensions) }}</textarea>
                @error('dimensions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Example: {"length": 200, "width": 100, "height": 80, "unit": "cm"}</p>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description *</label>
                <textarea name="short_description" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('short_description') border-red-500 @enderror">{{ old('short_description', $product->short_description) }}</textarea>
                @error('short_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea id="description" name="description" rows="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Extra Info (Optional) -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Extra Info Title (Optional)</label>
                <input type="text" name="extra_title" value="{{ old('extra_title', $product->extra_title) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Extra Info Description (Optional)</label>
                <textarea id="extra_description" name="extra_description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('extra_description', $product->extra_description) }}</textarea>
            </div>

            <div class="mt-6">
                <label class="flex items-center mb-4">
                    <input type="checkbox" name="replace_images" value="1" id="replace_images" {{ old('replace_images') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                    <span class="text-sm font-medium text-gray-700">Replace all existing images with new ones?</span>
                </label>
                <p class="mb-4 text-xs text-gray-500">Agar replace enable karenge to purani images remove ho jayengi. Nayi selected images me cross icon se remove kar sakte hain.</p>

                <div id="currentImagesSection">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                    @php
                        $deletedImageIds = collect(old('delete_image', []))->map(fn ($id) => (int) $id)->all();
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        @forelse($product->images as $image)
                            @php
                                $markedForDeletion = in_array((int) $image->id, $deletedImageIds, true);
                            @endphp
                            <div class="relative overflow-hidden rounded-xl border bg-gray-50 shadow-sm {{ $markedForDeletion ? 'border-red-200 ring-2 ring-red-500 opacity-70' : 'border-gray-200' }}" data-existing-image-card>
                                <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}" class="h-44 w-full object-cover">

                                <div class="absolute inset-0 flex items-center justify-center bg-red-600/75 text-sm font-semibold text-white {{ $markedForDeletion ? '' : 'hidden' }}" data-remove-overlay>
                                    Marked for deletion
                                </div>

                                <span class="absolute left-3 top-3 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $image->featured ? 'bg-blue-600 text-white' : 'bg-white/90 text-gray-700' }}">
                                    {{ $image->featured ? 'Featured' : 'Current image' }}
                                </span>

                                <button
                                    type="button"
                                    class="absolute right-3 top-3 inline-flex h-9 w-9 items-center justify-center rounded-full shadow-lg transition {{ $markedForDeletion ? 'bg-red-700 text-white' : 'bg-white/90 text-red-600 hover:bg-red-600 hover:text-white' }}"
                                    data-image-remove-toggle
                                    aria-pressed="{{ $markedForDeletion ? 'true' : 'false' }}"
                                    aria-label="{{ $markedForDeletion ? 'Undo remove image' : 'Remove image' }}"
                                >
                                    <i class="fas {{ $markedForDeletion ? 'fa-undo' : 'fa-trash-alt' }} text-sm"></i>
                                </button>

                                <div class="p-3 text-xs text-gray-600">
                                    {{ basename($image->path) }}
                                </div>

                                <input type="checkbox" name="delete_image[]" value="{{ $image->id }}" class="sr-only" data-delete-checkbox {{ $markedForDeletion ? 'checked' : '' }}>
                            </div>
                        @empty
                            <div class="col-span-full rounded-xl border border-dashed border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                Is product par abhi koi current image nahi hai.
                            </div>
                        @endforelse
                    </div>
                </div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Add New Images (Optional - Multiple)
                    <p class="text-xs text-gray-500 mt-1">Max 5MB per image. First selected will be featured.</p>
                </label>
                <input type="hidden" name="featured_image_index" id="featuredImageIndex" value="{{ old('featured_image_index', 0) }}">
                <input type="file" id="imagesInput" name="images[]" multiple accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('images') border-red-500 @enderror">
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="imagePreview" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="col-span-full rounded-xl border border-dashed border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                        New selected images yahan preview hongi. Cross icon se instantly remove kar sakte hain.
                    </div>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                    Update Product
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
        const replaceToggle = document.getElementById('replace_images');
        const currentImagesSection = document.getElementById('currentImagesSection');

        const updateExistingCardState = (card, marked) => {
            const checkbox = card.querySelector('[data-delete-checkbox]');
            const overlay = card.querySelector('[data-remove-overlay]');
            const toggle = card.querySelector('[data-image-remove-toggle]');
            const icon = toggle.querySelector('i');

            checkbox.checked = marked;
            card.classList.toggle('border-red-200', marked);
            card.classList.toggle('ring-2', marked);
            card.classList.toggle('ring-red-500', marked);
            card.classList.toggle('opacity-70', marked);
            overlay.classList.toggle('hidden', !marked);
            toggle.classList.toggle('bg-red-700', marked);
            toggle.classList.toggle('text-white', marked);
            toggle.classList.toggle('bg-white/90', !marked);
            toggle.classList.toggle('text-red-600', !marked);
            toggle.setAttribute('aria-pressed', marked ? 'true' : 'false');
            toggle.setAttribute('aria-label', marked ? 'Undo remove image' : 'Remove image');
            icon.className = marked ? 'fas fa-undo text-sm' : 'fas fa-trash-alt text-sm';
        };

        document.querySelectorAll('[data-existing-image-card]').forEach((card) => {
            const toggle = card.querySelector('[data-image-remove-toggle]');
            const checkbox = card.querySelector('[data-delete-checkbox]');

            updateExistingCardState(card, checkbox.checked);

            toggle.addEventListener('click', () => {
                updateExistingCardState(card, !checkbox.checked);
            });
        });

        if (!input || !preview || !featuredIndexInput) {
            return;
        }

        let selectedFiles = [];
        let featuredIndex = Number(featuredIndexInput.value || 0);
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
                    New selected images yahan preview hongi. Cross icon se instantly remove kar sakte hain.
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

        if (replaceToggle && currentImagesSection) {
            const syncReplaceState = () => {
                currentImagesSection.classList.toggle('opacity-60', replaceToggle.checked);
            };

            replaceToggle.addEventListener('change', syncReplaceState);
            syncReplaceState();
        }

        window.addEventListener('beforeunload', clearObjectUrls);
    })();
</script>
@endpush
@endsection
