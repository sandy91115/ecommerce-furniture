<div class="bg-white shadow-sm rounded-lg p-8">
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Linked Product</label>
                <select name="product_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">No product link</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ (string) old('product_id', $banner->product_id) === (string) $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="active" {{ old('status', $banner->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $banner->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Eyebrow</label>
                <input type="text" name="eyebrow" value="{{ old('eyebrow', $banner->eyebrow) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('eyebrow') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Offer Price</label>
                <input type="text" name="offer_price" value="{{ old('offer_price', $banner->offer_price) }}" placeholder="₹35,000" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('offer_price') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Offer Title</label>
                <input type="text" name="offer_title" value="{{ old('offer_title', $banner->offer_title) }}" placeholder="Epoxy Resin Coffee Table" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('offer_title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Season Year</label>
                    <input type="text" name="season_year" value="{{ old('season_year', $banner->season_year) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('season_year') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Season Text</label>
                    <input type="text" name="season_text" value="{{ old('season_text', $banner->season_text) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('season_text') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kicker</label>
                <input type="text" name="kicker" value="{{ old('kicker', $banner->kicker) }}" placeholder="Coffee Tables" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('kicker') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Main Title</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $banner->description) }}</textarea>
            @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Button Text</label>
                <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Button URL</label>
                <input type="text" name="button_url" value="{{ old('button_url', $banner->button_url) }}" placeholder="/shop or https://..." class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Text</label>
                <input type="text" name="secondary_button_text" value="{{ old('secondary_button_text', $banner->secondary_button_text) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Button URL</label>
                <input type="text" name="secondary_button_url" value="{{ old('secondary_button_url', $banner->secondary_button_url) }}" placeholder="/shop" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Theme Color</label>
                <input type="color" name="theme_color" value="{{ old('theme_color', $banner->theme_color ?: '#E3B505') }}" class="w-full h-12 p-1 border border-gray-300 rounded-lg">
                @error('theme_color') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                <input type="number" name="order" value="{{ old('order', $banner->order) }}" min="0" max="999" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Banner Image</label>
            @if($banner->image)
                <div class="mb-4 flex items-center space-x-4 p-4 bg-gray-50 rounded-lg border">
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-32 h-20 object-cover rounded border">
                    <div>
                        <p class="font-medium text-gray-900">Current image</p>
                        <p class="text-sm text-gray-500">Upload a new file to replace it.</p>
                    </div>
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="mt-1 text-xs text-gray-500">Optional. If empty, linked product image will be used.</p>
            @error('image') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="mt-8 flex space-x-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 font-medium">
                <i class="fas fa-save mr-2"></i>Save Banner
            </button>
            <a href="{{ route('admin.banners.index') }}" class="flex-1 bg-gray-200 text-gray-900 py-3 px-6 rounded-lg hover:bg-gray-300 font-medium text-center">Cancel</a>
        </div>
    </form>
</div>
