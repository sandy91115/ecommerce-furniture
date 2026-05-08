@extends('admin.layouts.app')

@section('title', $product->name . ' - Variations')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Product Variations</h1>
            <p class="text-gray-600">Manage variations for {{ $product->name }}</p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                Back to Product
            </a>
            <button onclick="generateVariations()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                Generate Variations
            </button>
        </div>
    </div>

    {{-- Attributes Selection for Generator --}}
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Select Attributes for Variations</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($product->attributes as $attribute)
                <div class="border p-4 rounded-lg">
                    <h4 class="font-medium mb-2">{{ $attribute->name }}</h4>
                    <select id="attr_{{ $attribute->id }}" class="w-full p-2 border rounded" multiple>
                        @foreach($attribute->values as $value)
                            <option value="{{ $value->slug }}" data-attribute="{{ $attribute->slug }}">{{ $value->value }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Variations List --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="variations-body" class="bg-white divide-y divide-gray-200">
                    @forelse ($variations as $variation)
                        <tr data-variation-id="{{ $variation->id }}">
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(json_decode($variation->variation_attributes, true) as $key => $value)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">{{ ucfirst($key) }}: {{ $value }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <input type="text" value="{{ $variation->sku }}" class="variation-sku w-32 px-2 py-1 border rounded text-sm" data-field="sku" data-id="{{ $variation->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" step="0.01" value="{{ $variation->price }}" class="variation-price w-24 px-2 py-1 border rounded text-sm" data-field="price" data-id="{{ $variation->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" step="0.01" value="{{ $variation->sale_price }}" class="variation-sale-price w-24 px-2 py-1 border rounded text-sm" data-field="sale_price" data-id="{{ $variation->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" value="{{ $variation->stock }}" class="variation-stock w-20 px-2 py-1 border rounded text-sm" data-field="stock" data-id="{{ $variation->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <input type="file" class="variation-image file-input hidden" data-id="{{ $variation->id }}">
                                @if($variation->image)
                                    <img src="{{ asset('storage/' . $variation->image) }}" alt="Variation Image" class="h-12 w-12 object-cover rounded cursor-pointer" onclick="this.nextElementSibling.click()">
                                    <div class="text-xs text-gray-500 mt-1">{{ $variation->image }}</div>
                                @else
                                    <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center cursor-pointer text-gray-500" onclick="this.nextElementSibling.click()">
                                        <i class="fas fa-image fa-lg"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="deleteVariation({{ $variation->id }})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No variations. Use the generator above to create combinations.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function generateVariations() {
    const attributes = {};
    document.querySelectorAll('[id^="attr_"]').forEach(select => {
        const attrId = select.id.replace('attr_', '');
        const attrName = document.querySelector(`select#attr_${attrId} option:checked`)?.dataset.attribute || '';
        const selectedValues = Array.from(select.selectedOptions).map(opt => opt.value);
        if (selectedValues.length > 0) {
            attributes[attrName] = selectedValues;
        }
    });

    if (Object.keys(attributes).length === 0) {
        alert('Select at least one attribute with values');
        return;
    }

    // Generate cartesian product
    const keys = Object.keys(attributes);
    const result = [];
    const cartesian = (index, current) => {
        if (index === keys.length) {
            result.push([...current]);
            return;
        }
        attributes[keys[index]].forEach(value => {
            cartesian(index + 1, [...current, { [keys[index]]: value }]);
        });
    };
    cartesian(0, []);

    // Add to table
    result.forEach(combination => {
        const variationAttrs = combination.reduce((acc, item) => ({ ...acc, ...item }), {});
        const row = `
            <tr data-variation-id="new-${Date.now()}">
                <td class="px-6 py-4">
                    <div class="flex flex-wrap gap-1">
                        ${Object.entries(variationAttrs).map(([key, value]) => `<span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">${key.toUpperCase()}: ${value}</span>`).join('')}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <input type="text" value="${Object.values(variationAttrs).join('-')}" class="variation-sku w-32 px-2 py-1 border rounded text-sm" data-field="sku">
                </td>
                <td class="px-6 py-4">
                    <input type="number" step="0.01" value="99.99" class="variation-price w-24 px-2 py-1 border rounded text-sm" data-field="price">
                </td>
                <td class="px-6 py-4">
                    <input type="number" step="0.01" value="" class="variation-sale-price w-24 px-2 py-1 border rounded text-sm" data-field="sale_price">
                </td>
                <td class="px-6 py-4">
                    <input type="number" value="10" class="variation-stock w-20 px-2 py-1 border rounded text-sm" data-field="stock">
                </td>
                <td class="px-6 py-4">
                    <input type="file" class="variation-image file-input hidden">
                    <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center cursor-pointer text-gray-500" onclick="this.previousElementSibling.click()">
                        <i class="fas fa-image fa-lg"></i>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="deleteVariation(this)" class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        document.querySelector('#variations-body').insertAdjacentHTML('beforeend', row);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Save variation changes
    document.body.addEventListener('input', function(e) {
        if (e.target.classList.contains('variation-price') || e.target.classList.contains('variation-stock') || e.target.classList.contains('variation-sku')) {
            // AJAX save
            const row = e.target.closest('tr');
            const variationId = row.dataset.variationId;
            const field = e.target.dataset.field;
            const value = e.target.value;
            
            fetch(`/admin/products/{{ $product->id }}/variations/${variationId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ [field]: value })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      e.target.style.backgroundColor = '#d4edda';
                      setTimeout(() => {
                          e.target.style.backgroundColor = '';
                      }, 1000);
                  }
              });
        }
    });

    // File upload
    document.body.addEventListener('change', function(e) {
        if (e.target.classList.contains('variation-image')) {
            const formData = new FormData();
            formData.append('image', e.target.files[0]);
            const row = e.target.closest('tr');
            const variationId = row.dataset.variationId;

            fetch(`/admin/products/{{ $product->id }}/variations/${variationId}/image`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const img = row.querySelector('img');
                      img.src = data.image_url;
                      img.style.display = 'block';
                  }
              });
        }
    });
});

function deleteVariation(button) {
    if (confirm('Are you sure?')) {
        const row = button.closest('tr');
        const variationId = row.dataset.variationId;
        
        fetch(`/admin/products/{{ $product->id }}/variations/${variationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  row.remove();
              }
          });
    }
}
</script>
@endsection

