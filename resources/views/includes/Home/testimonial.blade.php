@php
<<<<<<< HEAD
    $reviewTestimonials = collect($testimonials ?? [])
        ->filter(fn ($review) => filled(data_get($review, 'comment')))
        ->values();
@endphp

@forelse ($reviewTestimonials as $review)
    @php
        $reviewerName = data_get($review, 'reviewer_name') ?: data_get($review, 'user.name') ?: 'Customer';
        $rating = max(1, min(5, (int) data_get($review, 'rating', 5)));
        $comment = \Illuminate\Support\Str::limit(strip_tags((string) data_get($review, 'comment')), 155);
    @endphp

    <article class="customer-say-card" data-carousel-item>
        <div class="customer-say-card__top">
            <div class="customer-say-card__avatar">{{ strtoupper(\Illuminate\Support\Str::substr($reviewerName, 0, 1)) }}</div>
            <div>
                <div class="customer-say-card__stars" aria-label="{{ $rating }} out of 5 stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $rating ? 'is-filled' : '' }}">&#9733;</span>
                    @endfor
                </div>
                <h4>{{ $reviewerName }}</h4>
                <p>Verified customer</p>
            </div>
        </div>
        <p class="customer-say-card__copy">{{ $comment }}</p>
        <span class="customer-say-card__badge">Verified Purchase</span>
    </article>
@empty
    <article class="customer-say-card" data-carousel-item>
        <div class="customer-say-card__top">
            <div class="customer-say-card__avatar">C</div>
            <div>
                <div class="customer-say-card__stars" aria-label="5 out of 5 stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="is-filled">&#9733;</span>
                    @endfor
                </div>
                <h4>Customer</h4>
                <p>Verified customer</p>
            </div>
        </div>
        <p class="customer-say-card__copy">Customer reviews will appear here once approved from admin.</p>
        <span class="customer-say-card__badge">Verified Purchase</span>
    </article>
@endforelse
=======
$testimonials = [
    [
        'img' => 'assets/img/testimonial/tmnl-02.jpg', 
        'name' => 'Jennifer Smith', 
        'title' => "Berminghum ,UK", 
        'desc' => "Furnixar exceeded my expectations with their exceptional furniture pieces. The quality craftsmanship and attention to detail truly shine through in every product. My home has been transformed into a stylish sanctuary thanks to Furnixar!", 
    ],
    [
        'img' => 'assets/img/testimonial/tmnl-03.jpg', 
        'name' => 'Jackyer Smith', 
        'title' => "Berminghum ,UK", 
        'desc' => "Furnixar exceeded my expectations with its exceptional furniture pieces. The quality craftsmanship and attention to detail truly shine through in every product. My home has been transformed into a stylish sanctuary thanks to Furnixar!", 
    ],
];
@endphp

@foreach ($testimonials as $item)
    <div class="text-center">
        <h6 class="dark:text-white italic font-normal text-lg">{{ $item['desc'] }} </h6>
        <div class="flex items-center justify-center gap-3 mt-6 author">
            <div class="w-11 h-11 rounded-full overflow-hidden p-1 bg-[#bb976d]">
                <img class="rounded-full" src="{{ asset($item['img']) }}" alt="testimonial">
            </div>
            <div class="text-left">
                <h5 class="dark:text-white font-medium leading-none text-xl">{{ $item['name'] }}</h5>
                <span class="block text-[14px] leading-none text-[#bb976d] mt-[5px]">{{ $item['title'] }}</span>
            </div>
        </div>
    </div>
@endforeach
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
