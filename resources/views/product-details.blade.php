@extends('layouts.main')

@section('title', $product->seo_title ?: $product->name)

@section('content')
@php
    $formattedWhatsappNumber = preg_replace('/\D+/', '', (string) ($whatsappNumber ?? '')) ?: '1234567890';
    $quoteWhatsappMessage = rawurlencode("Hi! I want a quote for {$product->name}");
    $gallery = $product->images->isNotEmpty() ? $product->images : collect([(object) ['path' => null]]);
    $mainImage = $gallery->first()?->path ? asset('storage/' . $gallery->first()->path) : asset('assets/img/product/default.jpg');
    $price = $product->sale_price ?: $product->price;
@endphp

{{-- Removed sticky quotation form: now popup only on "Quote Now" button --}}

<div class="bg-[#F8F5F0] dark:bg-dark-secondary py-5 md:py-[30px]">
    <div class="container-fluid">
        <ul class="flex items-center gap-[10px] text-base md:text-lg leading-none font-normal text-title dark:text-white max-w-[1720px] mx-auto flex-wrap">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('shop') }}">Shop</a></li>
            <li>/</li>
            <li class="text-primary">{{ $product->name }}</li>
        </ul>
    </div>
</div>

@if (session('success'))
    <div class="container-fluid mt-8">
        <div class="max-w-[1720px] mx-auto rounded-[20px] border border-[#1CB28E]/20 bg-[#1CB28E]/10 px-6 py-4 text-[#1C7B64]">
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="s-py-50">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto grid gap-8 xl:grid-cols-[minmax(0,1.15fr)_minmax(340px,0.85fr)]">
            <div class="space-y-6">
                <div class="rounded-[28px] border border-bdr-clr dark:border-bdr-clr-drk bg-white dark:bg-dark-secondary p-5 sm:p-6">
                    <div class="rounded-[22px] overflow-hidden bg-[#F7F2EA] dark:bg-title">
                        <img id="productMainImage" data-product-main-image src="{{ $mainImage }}" alt="{{ $product->name }}" class="w-full object-cover" style="min-height: 340px; max-height: 620px;" onerror="this.onerror=null; this.src='{{ asset('assets/img/product/default.jpg') }}';">
                    </div>
                    @if($gallery->count() > 1)
                        <div class="grid grid-cols-4 sm:grid-cols-5 gap-3 mt-4">
                            @foreach($gallery as $image)
                                @php $imageUrl = $image->path ? asset('storage/' . $image->path) : asset('assets/img/product/default.jpg'); @endphp
                                <button type="button" class="border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] overflow-hidden p-1 {{ $loop->first ? 'ring-2 ring-primary' : '' }}" data-product-thumb data-image="{{ $imageUrl }}" data-alt="{{ $product->name }} image {{ $loop->iteration }}">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }} thumbnail {{ $loop->iteration }}" class="w-full h-20 object-cover" onerror="this.onerror=null; this.src='{{ asset('assets/img/product/default.jpg') }}';">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="rounded-[28px] border border-bdr-clr dark:border-bdr-clr-drk bg-white dark:bg-dark-secondary p-6 sm:p-8">
                    <h3 class="text-2xl font-semibold">Product Description</h3>
                    <div class="mt-4 text-base leading-7 text-title/75 dark:text-white/75">{!! $product->description ?: '<p>Description will be updated soon.</p>' !!}</div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-[28px] border border-bdr-clr dark:border-bdr-clr-drk bg-white dark:bg-dark-secondary p-6 sm:p-8">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">{{ $isQuotationProduct ? 'Quotation Product' : 'Ready to Buy' }}</span>
                        @if($product->category)
                            <span class="inline-flex items-center rounded-full bg-title/5 px-3 py-1 text-xs font-semibold text-title/70 dark:bg-white/10 dark:text-white/70">{{ $product->category->name }}</span>
                        @endif
                    </div>

                    <h2 class="mt-4 font-semibold leading-tight text-3xl md:text-4xl">{{ $product->name }}</h2>

                    <div class="mt-5">
                        @if($isQuotationProduct)
                            <h3 class="text-2xl md:text-3xl font-semibold text-primary">Starting from ${{ number_format((float) $price, 2) }}</h3>
                            <p class="mt-2 text-title/70 dark:text-white/70">Final price depends on quantity, size, finish and delivery location.</p>
                        @else
                            <div class="flex items-end gap-3 flex-wrap">
                                <h3 class="text-2xl md:text-3xl font-semibold text-primary">${{ number_format((float) $price, 2) }}</h3>
                                @if($product->sale_price)
                                    <span class="text-lg text-title/45 line-through dark:text-white/45">${{ number_format((float) $product->price, 2) }}</span>
                                @endif
                            </div>
                            <p class="mt-2 text-title/70 dark:text-white/70">{{ $product->stock > 0 ? $product->stock . ' items available' : 'Currently out of stock' }}</p>
                        @endif
                    </div>

                    @if($product->short_description)
                        <p class="mt-6 text-base leading-7 text-title/75 dark:text-white/75">{!! nl2br(e(strip_tags($product->short_description))) !!}</p>
                    @endif

                    <div class="grid gap-3 mt-6 sm:grid-cols-2">
                        @if($product->sku)<div class="rounded-[18px] bg-[#F8F5F0] dark:bg-title px-4 py-3"><span class="block text-xs uppercase tracking-[0.2em] text-title/45 dark:text-white/45">SKU</span><strong>{{ $product->sku }}</strong></div>@endif
                        @if($product->material)<div class="rounded-[18px] bg-[#F8F5F0] dark:bg-title px-4 py-3"><span class="block text-xs uppercase tracking-[0.2em] text-title/45 dark:text-white/45">Material</span><strong>{{ $product->material->name }}</strong></div>@endif
                        @if($product->color)<div class="rounded-[18px] bg-[#F8F5F0] dark:bg-title px-4 py-3"><span class="block text-xs uppercase tracking-[0.2em] text-title/45 dark:text-white/45">Color</span><strong>{{ $product->color->name }}</strong></div>@endif
                        @if($product->warranty_months)<div class="rounded-[18px] bg-[#F8F5F0] dark:bg-title px-4 py-3"><span class="block text-xs uppercase tracking-[0.2em] text-title/45 dark:text-white/45">Warranty</span><strong>{{ $product->warranty_months }} months</strong></div>@endif
                    </div>

                    @if($product->attributeMaps->isNotEmpty())
                        <div class="mt-6 flex flex-wrap gap-3">
                            @foreach($product->attributeMaps as $attributeMap)
                                @if($attributeMap->attribute)
                                    <span class="inline-flex items-center rounded-full border border-bdr-clr dark:border-bdr-clr-drk px-4 py-2 text-sm">{{ $attributeMap->attribute->name }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endif

@if($isQuotationProduct)
                        <div class="mt-8 rounded-[24px] border border-primary/15 bg-primary/5 p-5 sm:p-6">
                            <h4 class="text-xl font-semibold">Get Custom Quote</h4>
                            <p class="mt-3 text-base text-title/75 dark:text-white/75">Fill the quote form or send a WhatsApp message with quantity, size, finish and delivery city.</p>
                            <div class="flex flex-col sm:flex-row gap-4 mt-5">
                                <button type="button" class="btn btn-solid" data-quote-trigger data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ number_format((float) $price, 2) }}"><span>Quote Now</span></button>
                                <a href="https://wa.me/{{ $formattedWhatsappNumber }}?text={{ $quoteWhatsappMessage }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline"><span>WhatsApp</span></a>
                            </div>
                        </div>
                        @include('quotations.form')
                    @else
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-8 rounded-[24px] border border-bdr-clr dark:border-bdr-clr-drk p-5 sm:p-6">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <label for="productQuantity" class="block text-sm font-semibold mb-3">Quantity</label>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="flex items-center rounded-[16px] border border-bdr-clr dark:border-bdr-clr-drk overflow-hidden">
                                    <button type="button" class="px-4 py-3 text-lg" data-quantity-change="-1">-</button>
                                    <input id="productQuantity" type="number" name="quantity" min="1" value="1" class="w-16 text-center bg-transparent outline-none">
                                    <button type="button" class="px-4 py-3 text-lg" data-quantity-change="1">+</button>
                                </div>
                                <button type="submit" class="btn btn-solid {{ $product->stock < 1 ? 'pointer-events-none opacity-60' : '' }}" {{ $product->stock < 1 ? 'disabled' : '' }}><span>{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span></button>
                            </div>
                        </form>
                    @endif
                </div>

                @if($product->extra_title || $product->extra_description)
                    <div class="rounded-[28px] border border-bdr-clr dark:border-bdr-clr-drk bg-white dark:bg-dark-secondary p-6 sm:p-8">
                        <h3 class="text-2xl font-semibold">{{ $product->extra_title ?: 'Additional Information' }}</h3>
                        <div class="mt-4 text-base leading-7 text-title/75 dark:text-white/75">{!! $product->extra_description ?: '<p>More details will be shared on request.</p>' !!}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


@if($products->isNotEmpty())
    <div class="s-pb-100">
        <div class="container-fluid">
            <div class="max-w-[1720px] mx-auto">
                <div class="flex items-end justify-between gap-6 flex-wrap">
                    <div><h3 class="text-3xl md:text-4xl font-semibold">Related Products</h3></div>
                    <a href="{{ route('shop') }}" class="btn btn-outline"><span>Browse Shop</span></a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 sm:gap-8 mt-10">
                    @foreach($products as $relatedProduct)
                        @php $relatedImage = $relatedProduct->images->first()?->path ? asset('storage/' . $relatedProduct->images->first()->path) : asset('assets/img/product/default.jpg'); @endphp
                        <div class="rounded-[24px] overflow-hidden border border-bdr-clr dark:border-bdr-clr-drk bg-white dark:bg-dark-secondary">
                            <a href="{{ route('product-details', $relatedProduct->slug) }}"><img src="{{ $relatedImage }}" alt="{{ $relatedProduct->name }}" class="w-full h-64 object-cover" onerror="this.onerror=null; this.src='{{ asset('assets/img/product/default.jpg') }}';"></a>
                            <div class="p-6">
                                <h4 class="text-xl font-semibold"><a href="{{ route('product-details', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a></h4>
                                <p class="mt-3 text-title/70 dark:text-white/70">{{ $relatedProduct->product_type === 'quotation' ? 'Custom quotation available' : '$' . number_format((float) ($relatedProduct->sale_price ?: $relatedProduct->price), 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

@if($isQuotationProduct)
    @include('quotations.form')
@endif

@push('scripts')
<script>
(function () {
    const modal = document.getElementById('quotationModal');
    const form = document.getElementById('quotationForm');
    const statusBox = document.getElementById('quotationStatus');
    const mainImage = document.querySelector('[data-product-main-image]');
    const thumbButtons = document.querySelectorAll('[data-product-thumb]');
    const quantityInput = document.getElementById('productQuantity');

    const setStatus = function (message, type) {
        if (!statusBox) return;
        statusBox.textContent = message || '';
        statusBox.className = 'hidden rounded-[16px] px-4 py-3 text-sm font-medium';
        if (!message) return;
        statusBox.classList.remove('hidden');
        statusBox.classList.add(type === 'success' ? 'bg-green-100' : 'bg-red-100', type === 'success' ? 'text-green-700' : 'text-red-700');
    };

    const closeQuotationModal = function () {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        setStatus('');
    };

    const openQuotationModal = function (trigger) {
        if (!modal) return;
        document.getElementById('modalProductId').value = trigger.dataset.productId || '';
        document.getElementById('modalProductName').textContent = trigger.dataset.productName ? 'Quote for ' + trigger.dataset.productName : 'Get Quote';
        document.getElementById('modalProductPrice').textContent = trigger.dataset.productPrice ? 'Starting from $' + trigger.dataset.productPrice : '';
        if (form) form.reset();
        setStatus('');
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };

    window.closeQuotationModal = closeQuotationModal;
    window.openQuotationModal = openQuotationModal;

    document.querySelectorAll('[data-quote-trigger]').forEach(function (button) {
        button.addEventListener('click', function () {
            openQuotationModal(button);
        });
    });

    if (modal) {
        modal.querySelectorAll('[data-quotation-close]').forEach(function (button) {
            button.addEventListener('click', closeQuotationModal);
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeQuotationModal();
        }
    });

    if (form) {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            const submitButton = form.querySelector('button[type="submit"]');
            const buttonLabel = submitButton ? submitButton.textContent.trim() : 'Send Quote Request';
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Sending...';
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form)
                });
                const data = await response.json().catch(function () { return {}; });
                if (!response.ok) {
                    const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : '';
                    throw new Error(firstError || data.message || 'Unable to submit quotation request.');
                }
                setStatus(data.message || 'Quotation request submitted successfully.', 'success');
                form.reset();
                window.setTimeout(closeQuotationModal, 1200);
            } catch (error) {
                setStatus(error.message || 'Unable to submit quotation request.', 'error');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = buttonLabel;
                }
            }
        });
    }

    thumbButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (!mainImage) return;
            mainImage.src = button.dataset.image;
            mainImage.alt = button.dataset.alt;
            thumbButtons.forEach(function (item) { item.classList.remove('ring-2', 'ring-primary'); });
            button.classList.add('ring-2', 'ring-primary');
        });
    });

    document.querySelectorAll('[data-quantity-change]').forEach(function (button) {
        button.addEventListener('click', function () {
            if (!quantityInput) return;
            const nextValue = Math.max(1, (parseInt(quantityInput.value || '1', 10) || 1) + parseInt(button.dataset.quantityChange, 10));
            quantityInput.value = nextValue;
        });
    });
})();
</script>
@endpush

@include('includes.footer')
@endsection
