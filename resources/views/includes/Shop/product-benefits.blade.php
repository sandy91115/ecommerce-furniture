@php
    $productBenefits = [
        [
            'title' => 'Free Shipping',
            'desc' => 'On Every Single Order',
            'icon' => 'shipping',
        ],
        [
            'title' => 'Handcrafted',
            'desc' => 'Customization Specialist',
            'icon' => 'craft',
        ],
        [
            'title' => 'Secure Payment',
            'desc' => 'Multiple Payment Options Available',
            'icon' => 'payment',
        ],
        [
            'title' => 'Certified Craftsmanship',
            'desc' => 'Reliable support for you',
            'icon' => 'certified',
        ],
    ];
@endphp

<section class="product-benefits-strip" aria-label="Store benefits">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <div class="product-benefits-grid">
                @foreach($productBenefits as $benefit)
                    <article class="product-benefit-card">
                        <span class="product-benefit-card__icon" aria-hidden="true">
                            @if($benefit['icon'] === 'shipping')
                                <svg viewBox="0 0 48 48" fill="none">
                                    <path d="M6 15h24v18H6V15Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="M30 21h6l6 6v6H30V21Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="M11 37a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM35 37a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M6 24h13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @elseif($benefit['icon'] === 'craft')
                                <svg viewBox="0 0 48 48" fill="none">
                                    <path d="m15 34 19-19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="m28 9 11 11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="m10 13 25 25" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="m8 40 8-8 4 4-8 8H8v-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="m34 10 4-4 4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            @elseif($benefit['icon'] === 'payment')
                                <svg viewBox="0 0 48 48" fill="none">
                                    <rect x="8" y="13" width="32" height="22" rx="3" stroke="currentColor" stroke-width="2"/>
                                    <path d="M8 20h32M14 29h10M29 29h5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="m15 10 19-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 48 48" fill="none">
                                    <circle cx="24" cy="18" r="10" stroke="currentColor" stroke-width="2"/>
                                    <path d="m18 27-4 15 10-6 10 6-4-15" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="m19.5 18 3 3 6-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @endif
                        </span>
                        <h3>{{ $benefit['title'] }}</h3>
                        <p>{{ $benefit['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
