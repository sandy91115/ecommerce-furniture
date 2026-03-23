
<div id="quotationModal" class="flex items-center justify-center min-h-screen p-4 hidden" aria-hidden="true">
        <div class="bg-white dark:bg-dark-secondary rounded-[24px] shadow-2xl w-full max-w-[560px] mx-auto relative overflow-hidden" style="max-height: calc(100vh - 32px);">
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
                <div id="quotationStatus" class="hidden rounded-[16px] px-4 py-3 text-sm font-medium mb-4"></div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationCustomerName">Your Name <span class="text-red-500">*</span></label>
                        <input id="quotationCustomerName" type="text" name="customer_name" required maxlength="255" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Enter your full name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationEmail">Email <span class="text-red-500">*</span></label>
                        <input id="quotationEmail" type="email" name="email" required class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="your@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationPhone">Phone</label>
                        <input id="quotationPhone" type="tel" name="phone" maxlength="20" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Your phone number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationPrice">Desired Price</label>
                        <input id="quotationPrice" type="number" name="desired_price" step="0.01" min="0" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Your target budget">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-white" for="quotationMessage">Message</label>
                        <textarea id="quotationMessage" name="message" rows="4" maxlength="1000" class="w-full px-4 py-3 border border-bdr-clr dark:border-bdr-clr-drk rounded-[16px] bg-transparent outline-none dark:text-white" placeholder="Share quantity, size, finish and delivery city."></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-6">
                    <button type="button" class="btn btn-outline flex-1" data-quotation-close><span>Cancel</span></button>
                    <button type="submit" class="btn btn-solid flex-1"><span>Send Quote Request</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
