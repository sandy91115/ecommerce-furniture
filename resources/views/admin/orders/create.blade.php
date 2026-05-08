@extends('admin.layouts.app')

@section('title', 'Create Order')

@section('content')
<<<<<<< HEAD
<div class="">
=======
<div class="max-w-2xl">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>

    <form method="POST" action="{{ route('admin.orders.store') }}" class="bg-white shadow-lg rounded-xl p-8">
        @csrf
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Create New Order</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-blue-500"></i>User *
                </label>
                <select name="user_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 ring-2 ring-red-500/50 @enderror">
                    <option value="">Select User</option>
@php $customers = \App\Models\User::role('customer')->get() ?: \App\Models\User::whereDoesntHave('roles')->get(); @endphp @foreach($customers as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-store mr-2 text-green-500"></i>Vendor (Optional)
                </label>
                <select name="vendor_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">No Vendor</option>
                    @foreach(\App\Models\Vendor::all() as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->store_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
            <input type="number" name="total_amount" step="0.01" min="0" value="{{ old('total_amount', 0) }}" class="w-full p-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" readonly required>
            <p class="mt-2 text-xs text-gray-500">Automatically calculated from selected products and quantities.</p>
            @error('total_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
<<<<<<< HEAD
                    @foreach(\App\Enums\OrderStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status', 'pending') === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                    @endforeach
=======
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select name="payment_status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
<<<<<<< HEAD
                    @foreach(\App\Models\Order::PAYMENT_STATUSES as $value => $label)
                        <option value="{{ $value }}" {{ old('payment_status', 'pending') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
=======
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                </select>
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
            <textarea name="shipping_address" rows="4" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
            @error('shipping_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Order Items</label>

            <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                        <select id="item-product" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-sku="{{ $product->sku }}" data-price="{{ $product->price }}">{{ $product->name }} ({{ currency($product->price) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" id="item-quantity" min="1" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price per unit</label>
                        <input type="number" id="item-price" step="0.01" min="0" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                        <button type="button" id="add-item" class="mt-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">+ Add Item</button>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between rounded-lg bg-white px-4 py-3 text-sm text-gray-600">
                    <span>Current Item Total</span>
                    <span id="current-item-total" class="font-semibold text-gray-900">{{ currency(0) }}</span>
                </div>
            </div>
            <textarea name="items" id="items-json" style="display: none;" class="w-full p-3 border border-gray-300 rounded-xl">@json(old('items', []))</textarea>
            @error('items') <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
            <div id="items-preview" class="mt-4 space-y-2"></div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition duration-300">
            <i class="fas fa-save mr-2"></i> Create Order
        </button>
    </form>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const initialItems = {!! json_encode(old('items', [])) !!};
    let items = Array.isArray(initialItems) ? initialItems : [];
    const preview = document.getElementById('items-preview');
    const productSelect = document.getElementById('item-product');
    const quantityInput = document.getElementById('item-quantity');
    const priceInput = document.getElementById('item-price');
    const addBtn = document.getElementById('add-item');
    const jsonField = document.getElementById('items-json');
    const totalField = document.querySelector('input[name="total_amount"]');
    const currentItemTotal = document.getElementById('current-item-total');
    const currencySymbol = @json(currency_symbol());

    items = items.map(normalizeItem).filter(Boolean);

    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        priceInput.value = selectedOption.dataset.price || '';
        updateCurrentItemTotal();
    });

    quantityInput.addEventListener('input', updateCurrentItemTotal);
    priceInput.addEventListener('input', updateCurrentItemTotal);

    addBtn.addEventListener('click', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productId = parseInt(productSelect.value, 10) || 0;
        const quantity = parseInt(quantityInput.value, 10) || 0;
        const price = parseFloat(priceInput.value) || 0;

        if (!productId) {
            alert('Please select a product.');
            return;
        }
        if (quantity <= 0) {
            alert('Quantity must be > 0.');
            return;
        }
        if (price <= 0) {
            alert('Price must be > 0.');
            return;
        }

        const item = normalizeItem({
            product_id: productId,
            product_name: selectedOption.dataset.name || selectedOption.textContent.trim(),
            product_sku: selectedOption.dataset.sku || null,
            quantity,
            price
        });

        items.push(item);
        updatePreview();
        updateJson();
        updateTotal();
        clearForm();
    });

    function formatCurrency(value) {
        return currencySymbol + Number(value).toFixed(2);
    }

    function updatePreview() {
        preview.innerHTML = '';

        if (!items.length) {
            preview.innerHTML = '<div class="rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">No items added yet.</div>';
            return;
        }

        items.forEach(function(item, index) {
            const row = document.createElement('div');
            row.className = 'flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-sm';
            row.innerHTML = `
                <div class="flex items-center space-x-3">
                    <span class="font-medium">${item.product_name || 'Product #' + item.product_id}</span>
                    <span class="text-sm text-gray-600">Qty: ${item.quantity} × ${formatCurrency(item.price)} = ${formatCurrency(item.quantity * item.price)}</span>
                </div>
                <button type="button" onclick="removeItem(${index})" class="text-red-600 hover:text-red-800 font-medium">Remove</button>
            `;
            preview.appendChild(row);
        });
    }

    function updateJson() {
        jsonField.value = JSON.stringify(items);
    }

    function updateTotal() {
        const total = items.reduce(function(sum, item) {
            return sum + (item.quantity * item.price);
        }, 0);

        if (totalField) {
            totalField.value = total.toFixed(2);
        }
    }

    function updateCurrentItemTotal() {
        const quantity = parseInt(quantityInput.value, 10) || 0;
        const price = parseFloat(priceInput.value) || 0;
        currentItemTotal.textContent = formatCurrency(quantity * price);
    }

    function clearForm() {
        productSelect.value = '';
        quantityInput.value = '';
        priceInput.value = '';
        updateCurrentItemTotal();
    }

    function normalizeItem(item) {
        if (!item || typeof item !== 'object') {
            return null;
        }

        const productId = parseInt(item.product_id ?? item.id, 10) || 0;
        const quantity = parseInt(item.quantity ?? item.qty, 10) || 0;
        const price = parseFloat(item.price) || 0;

        if (!productId || quantity <= 0 || price < 0) {
            return null;
        }

        return {
            product_id: productId,
            product_name: item.product_name ?? item.name ?? null,
            product_sku: item.product_sku ?? item.sku ?? null,
            quantity,
            price
        };
    }

    window.removeItem = function(index) {
        items.splice(index, 1);
        updatePreview();
        updateJson();
        updateTotal();
    };

    updatePreview();
    updateJson();
    updateTotal();
    updateCurrentItemTotal();
});
</script>
@endpush

@endsection

