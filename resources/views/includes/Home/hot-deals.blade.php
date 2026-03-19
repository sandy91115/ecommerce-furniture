@php
$deals = $hotDeals ?? collect();
if ($deals->isEmpty()) {
    $deals = collect([
        [
            'id' => 1,
            'name' => 'Classic Relaxable Chair',
            'slug' => 'classic-relaxable-chair',
            'sale_price' => 85.00,
            'price' => 185.00,
            'image_url' => 'assets/img/gallery/product-detls/product-01.jpg',
            'category' => 'Chair',
            'end_date' => now()->addDays(7)
        ]
    ]);
}
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 md:gap-8 w-full quick-view-popup-inner">
    @forelse($deals->take(1) as $deal)
    <div>
        <div class="relative">
            <div class="w-full h-[400px] relative overflow-hidden rounded-2xl">
                @php
                    $images = isset($deal->images) ? $deal->images->pluck('path') : collect([$deal['image_url'] ?? 'assets/img/gallery/product-detls/product-01.jpg']);
                    $dealImages = $images->map(fn($img) => str_starts_with($img, 'assets') ? asset($img) : asset('storage/' . $img))->toArray();
                @endphp
                <div class="portfolio-v3-slider owl-carousel quick-preview-slider h-full" data-carousel-animateout="false" data-carousel-loop="true" data-carousel-margin="0">
                    @foreach($dealImages as $img)
                        <img class="w-full h-full object-cover" src="{{ $img }}" alt="{{ $deal->name ?? $deal['name'] }}">
                    @endforeach
                </div>
                <div class="flex justify-between absolute [top:57%] transform -translate-y-1/2 z-20 w-full px-4">
                    <button class="prtflo03_prev w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:text-white hover:bg-primary p-2 rounded-xl shadow-lg">
                        <svg class="fill-current w-5 h-5" width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 9C19.5523 9 20 8.55228 20 8C20 7.44772 19.5523 7 19 7L19 9ZM0.292893 7.29289C-0.097631 7.68341 -0.0976311 8.31658 0.292893 8.7071L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34314C8.46159 1.95262 8.46159 1.31945 8.07107 0.92893C7.68054 0.538406 7.04738 0.538406 6.65686 0.92893L0.292893 7.29289ZM19 7L1 7L1 9L19 9L19 7Z"/>
                        </svg>
                    </button>
                    <button class="prtflo03_next w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:text-white hover:bg-primary p-2 rounded-xl shadow-lg">
                        <svg class="fill-current w-5 h-5" width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 7C0.447715 7 4.82823e-08 7.44772 0 8C-4.82823e-08 8.55228 0.447715 9 1 9L1 7ZM19.7071 8.70711C20.0976 8.31658 20.0976 7.68342 19.7071 7.29289L13.3431 0.928933C12.9526 0.538409 12.3195 0.538409 11.9289 0.928933C11.5384 1.31946 11.5384 1.95262 11.9289 2.34315L17.5858 8L11.9289 13.6569C11.5384 14.0474 11.5384 14.6805 11.9289 15.0711C12.3195 15.4616 12.9526 15.4616 13.3431 15.0711L19.7071 8.70711ZM1 9L19 9L19 7L1 7L1 9Z"/>
                        </svg>
                    </button>
                </div>
                @if(($deal->sale_price ?? $deal['sale_price'] ?? 0) < ($deal->price ?? $deal['price'] ?? 999))
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                        -{{ number_format((1 - ($deal->sale_price ?? $deal['sale_price'] ?? 0) / ($deal->price ?? $deal['price'] ?? 999)) * 100, 0) }}%
                    </div>
                @endif
            </div>
        </div>
        <div class="lg:max-w-[635px] w-full p-5 sm:p-8 md:pl-0 md:py-10 md:pr-8">
            <div class="pb-4 sm:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk">
                <h2 class="font-semibold leading-none text-[30px]">{{ $deal->name ?? $deal['name'] ?? 'Hot Deal' }}</h2>
                <div class="flex gap-4 items-center mt-[15px]">
                    <span class="text-lg leading-none block relative before:absolute before:border-b before:border-[1.5px] before:border-paragraph dark:before:border-white-light before:top-[6px] before:left-0 before:w-full line-through">
                        ${{ number_format($deal->price ?? $deal['price'] ?? 185, 2) }}
                    </span>
                    <span class="text-2xl text-primary leading-none block font-bold">
                        ${{ number_format($deal->sale_price ?? $deal['sale_price'] ?? 85, 2) }}
                    </span>
                </div>
                <div class="mt-5 md:mt-7">
                    <div class="py-3 px-4 bg-gradient-to-r from-orange-100 to-red-50 rounded-2xl flex items-center gap-3 shadow-md">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.99805 0C14.641 0 18.4209 3.77994 18.4209 8.42289C18.4209 13.0658 14.641 16.8457 9.99805 16.8457C5.3551 16.8457 1.5752 13.0658 1.5752 8.42289C1.5752 3.77994 5.3551 0 9.99805 0ZM9.99805 2.05751C6.16894 2.05751 3.37382 4.85263 3.37382 8.68174C3.37382 12.5109 6.16894 15.3059 9.99805 15.3059C13.8272 15.3059 16.6223 12.5109 16.6223 8.68174C16.6223 4.85263 13.8272 2.05751 9.99805 2.05751ZM9.99805 11.2602C8.98579 11.2602 8.22791 11.0181 7.74495 10.5351C7.26199 10.5351 7.50401 11.0181 7.50401 11.2602C7.50401 11.2602 7.74495 10.5351 7.26199 10.5351C7.26199 10.5351 8.22791 11.2602 8.98579 11.2602C9.99805 11.2602 10.7559 10.5032 11.2389 9.9202C11.7218 9.3372 11.4788 8.8602 11.4788 8.8602C11.4788 8.8602 11.7218 9.3372 11.2389 9.9202C10.6559 10.5032 9.99805 11.2602 9.99805 11.2602Z" fill="#FF6B35"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.99805 13.3448C9.05079 13.3448 8.22791 12.5219 8.22791 11.5746C8.22791 10.6273 9.05079 9.80444 9.99805 9.80444C10.9453 9.80444 11.7682 10.6273 11.7682 11.5746C11.7682 12.5219 10.9453 13.3448 9.99805 13.3448Z" fill="#FF6B35"/>
                        </svg>
                        <div>
                            <h6 class="text-lg font-semibold text-gray-800">Limited Time Deal!</h6>
                            <p class="text-sm text-gray-600">Ends in <span id="deal-timer" class="font-bold"></span></p>
                        </div>
                    </div>
                </div>
                <p class="sm:text-lg mt-5 md:mt-7">{{ $deal->short_description ?? 'Experience premium quality furniture at unbeatable prices. Limited stock available!' }}</p>
            </div>
            <div class="py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-sm font-medium text-gray-500">Category: </span>
                    <span class="font-semibold text-gray-800">{{ $deal->category->name ?? $deal['category'] ?? 'Furniture' }}</span>
                </div>
                <div class="inc-dec flex items-center gap-2 mb-4">
                    <button class="dec w-10 h-10 bg-gray-100 hover:bg-gray-200 flex items-center justify-center rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <input class="w-16 h-10 text-center border border-gray-300 rounded-lg text-lg font-semibold" type="number" value="1" min="1">
                    <button class="inc w-10 h-10 bg-gray-100 hover:bg-gray-200 flex items-center justify-center rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                </div>
                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('product-details', $deal->slug ?? $deal['slug']) }}" class="btn btn-primary px-6 py-2">
                        <i class="mdi mdi-eye-outline mr-1"></i> Quick View
                    </a>
                    <a href="#" class="btn btn-outline px-6 py-2">
                        <i class="mdi mdi-cart-outline mr-1"></i> Add to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-10 w-full col-span-2">
        <p class="text-gray-500">No hot deals available at the moment.</p>
    </div>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hot deal timer
    const endDate = new Date('{{ $deals->first()?->end_date ?? now()->addDays(7) }}');
    const timerEl = document.getElementById('deal-timer');
    
    function updateTimer() {
        const now = new Date().getTime();
        const distance = endDate - now;
        
        if (distance < 0) {
            timerEl.innerHTML = 'EXPIRED';
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        timerEl.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }
    
    updateTimer();
    setInterval(updateTimer, 1000);
});
</script>
