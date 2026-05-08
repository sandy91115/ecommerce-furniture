@php
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
