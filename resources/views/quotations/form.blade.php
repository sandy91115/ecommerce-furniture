@php
    $quotationHasErrors = $errors->any() && collect([
        old('customer_name'),
        old('email'),
        old('phone'),
        old('city'),
        old('country'),
        old('pincode'),
        old('desired_price'),
        old('message'),
    ])->filter(fn ($value) => $value !== null)->isNotEmpty();
@endphp

<div
    id="quotationModal"
    class="fixed z-[999] hidden items-center justify-center bg-title/50 p-4 backdrop-blur-[3px]"
    style="inset: 0; background: rgba(23, 36, 48, 0.55); -webkit-backdrop-filter: blur(4px); backdrop-filter: blur(4px);"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    data-open-on-load="{{ $quotationHasErrors ? 'true' : 'false' }}"
>
    <div class="bg-white dark:bg-dark-secondary rounded-[24px] shadow-2xl w-full max-w-[560px] mx-auto relative overflow-hidden" style="width: min(100%, 560px); max-height: calc(100vh - 32px); margin: 0 auto; border-radius: 24px; overflow: hidden; background: #ffffff; box-shadow: 0 30px 80px rgba(15, 23, 42, 0.28);">
        <div class="p-6 border-b border-bdr-clr dark:border-bdr-clr-drk flex items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold dark:text-white" id="modalProductName">Get Quote</h3>
                <p class="mt-1 text-sm text-title/60 dark:text-white/60" id="modalProductPrice"></p>
            </div>
            <button type="button" data-quotation-close class="text-title/50 hover:text-title dark:text-white/60 dark:hover:text-white" aria-label="Close quote form">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="quotationForm" action="{{ route('quotation.store', $product->slug) }}" method="POST" class="p-6 overflow-y-auto" style="max-height: calc(100vh - 160px);">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId" value="{{ $product->id }}">

            <div class="space-y-4">
                <div
                    id="quotationFormStatus"
                    class="{{ $quotationHasErrors ? '' : 'hidden' }} rounded-[16px] border px-4 py-3 text-sm {{ $quotationHasErrors ? 'border-red-200 bg-red-50 text-red-700' : '' }}"
                    role="alert"
                >
                    @if($quotationHasErrors)
                        Please check the highlighted fields and try again.
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationCustomerName">Your Name <span class="text-red-500">*</span></label>
                    <input id="quotationCustomerName" type="text" name="customer_name" required maxlength="255" value="{{ old('customer_name') }}" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Enter your full name">
                    <p class="mt-2 text-sm text-red-600 {{ $errors->has('customer_name') ? '' : 'hidden' }}" data-error-for="customer_name">{{ $errors->first('customer_name') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationEmail">Email <span class="text-red-500">*</span></label>
                    <input id="quotationEmail" type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="your@email.com">
                    <p class="mt-2 text-sm text-red-600 {{ $errors->has('email') ? '' : 'hidden' }}" data-error-for="email">{{ $errors->first('email') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationPhone">Phone</label>
                    <input id="quotationPhone" type="tel" name="phone" maxlength="20" value="{{ old('phone') }}" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Your phone number">
                    <p class="mt-2 text-sm text-red-600 {{ $errors->has('phone') ? '' : 'hidden' }}" data-error-for="phone">{{ $errors->first('phone') }}</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationCity">City <span class="text-red-500">*</span></label>
                        <input id="quotationCity" type="text" name="city" required maxlength="120" value="{{ old('city') }}" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Delivery city">
                        <p class="mt-2 text-sm text-red-600 {{ $errors->has('city') ? '' : 'hidden' }}" data-error-for="city">{{ $errors->first('city') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationPincode">Pincode <span class="text-red-500">*</span></label>
                        <input id="quotationPincode" type="text" name="pincode" required maxlength="20" value="{{ old('pincode') }}" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Delivery pincode">
                        <p class="mt-2 text-sm text-red-600 {{ $errors->has('pincode') ? '' : 'hidden' }}" data-error-for="pincode">{{ $errors->first('pincode') }}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationCountry">Country <span class="text-red-500">*</span></label>
                    <input id="quotationCountry" type="text" name="country" required maxlength="120" value="{{ old('country', 'India') }}" data-default-value="India" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Delivery country">
                    <p class="mt-2 text-sm text-red-600 {{ $errors->has('country') ? '' : 'hidden' }}" data-error-for="country">{{ $errors->first('country') }}</p>
                </div>
                <!-- <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationPrice">Desired Price</label>
                    <input id="quotationPrice" type="number" name="desired_price" step="0.01" min="0" value="{{ old('desired_price') }}" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Your target budget">
                    <p class="mt-2 text-sm text-red-600 {{ $errors->has('desired_price') ? '' : 'hidden' }}" data-error-for="desired_price">{{ $errors->first('desired_price') }}</p>
                </div> -->
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationMessage">Message</label>
                    <textarea id="quotationMessage" name="message" rows="4" maxlength="1000" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Share quantity, size, finish and delivery city.">{{ old('message') }}</textarea>
                    <p class="mt-2 text-sm text-red-600 {{ $errors->has('message') ? '' : 'hidden' }}" data-error-for="message">{{ $errors->first('message') }}</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-6">
                <button type="button" class="btn btn-outline flex-1" data-quotation-close><span>Cancel</span></button>
                <button type="submit" class="btn btn-solid flex-1"><span>Send Quote Request</span></button>
            </div>
        </form>
    </div>
</div>
