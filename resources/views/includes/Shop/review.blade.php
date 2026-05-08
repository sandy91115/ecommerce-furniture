@php
    $approvedReviews = collect($reviews ?? [])->map(function ($r) {
        return [
            'id' => data_get($r, 'id', data_get($r, 'review.id')),
<<<<<<< HEAD
            'name' => data_get($r, 'reviewer_name', data_get($r, 'user.name', data_get($r, 'name', 'Anonymous'))),
=======
            'name' => data_get($r, 'user.name', data_get($r, 'name', 'Anonymous')),
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            'rating' => (int) data_get($r, 'rating', 5),
            'title' => trim((string) data_get($r, 'title', '')),
            'comment' => trim((string) data_get($r, 'comment', '')),
            'status' => data_get($r, 'status', 'approved'),
            'created_at' => data_get($r, 'created_at'),
            'review' => data_get($r, 'review', $r),
        ];
    })->filter(fn ($r) => $r['status'] === 'approved')->values();

    $avgRating = $approvedReviews->avg('rating') ?: 0;
    $totalReviews = $approvedReviews->count();
    $ratingBreakdown = collect(range(5, 1))->mapWithKeys(fn ($rating) => [$rating => $approvedReviews->where('rating', $rating)->count()]);
    $recommendedPercentage = $totalReviews > 0
        ? (int) round((($ratingBreakdown[5] + $ratingBreakdown[4]) / $totalReviews) * 100)
        : 0;
@endphp

<section class="review-shell" data-review-explorer>
    <div class="review-layout {{ $totalReviews === 0 ? 'is-empty' : '' }}">
        <aside class="review-panel review-summary-panel">
            <p class="review-panel-kicker">Customer reviews</p>

            <div class="review-score-row">
                <div class="review-score">{{ number_format($avgRating, 1) }}</div>
                <div>
                    <div class="review-star-row">
                        @for($i = 1; $i <= 5; $i++)
                            <svg width="18" height="17" viewBox="0 0 15 14" fill="currentColor" class="{{ $i <= round($avgRating) ? 'is-filled' : '' }}">
                                <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="review-count-copy">{{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}</p>
                </div>
            </div>

            <div class="review-bars">
                @foreach(range(5, 1) as $rating)
                    @php
                        $ratingCount = $ratingBreakdown[$rating] ?? 0;
                        $ratingPercentage = $totalReviews > 0 ? (int) round(($ratingCount / $totalReviews) * 100) : 0;
                    @endphp
                    <button type="button" class="review-rating-row" data-review-rating-filter="{{ $rating }}">
                        <span class="review-rating-label">{{ $rating }} star</span>
                        <span class="review-rating-track">
                            <span class="review-rating-track-fill" style="width: {{ $ratingPercentage }}%"></span>
                        </span>
                        <span class="review-rating-value">{{ $ratingPercentage }}%</span>
                    </button>
                @endforeach
            </div>

            <div class="review-metrics">
                <div class="review-metric-card">
                    <p class="review-metric-label">Global ratings</p>
                    <p class="review-metric-value">{{ $totalReviews }}</p>
                </div>
                <div class="review-metric-card">
                    <p class="review-metric-label">Would recommend</p>
                    <p class="review-metric-value">{{ $recommendedPercentage }}%</p>
                </div>
            </div>

            <div class="review-cta-panel">
                <h3>Review this product</h3>
                <p>Share your thoughts with other customers and help them decide faster.</p>
                <a href="#review-form" class="review-action-link">Write a review</a>
                @if($totalReviews > 0)
                    <button type="button" class="review-clear-btn hidden" data-review-clear>Clear star filter</button>
                @endif
            </div>
        </aside>

        @if($totalReviews > 0)
            <div class="review-main-column">
                <div class="review-panel review-toolbar-panel">
                    <div class="review-toolbar">
                        <div class="review-toolbar-copy">
                            <p class="review-panel-kicker review-panel-kicker--blue">Browse feedback</p>
                            <h3>What customers are saying</h3>
                            <p>Showing <span data-review-visible-count>{{ $totalReviews }}</span> of {{ $totalReviews }} {{ Str::plural('review', $totalReviews) }} in <span data-review-active-label>Top reviews</span>.</p>
                        </div>

                        @if($totalReviews > 1)
                            <div class="review-sort-group">
                                <button type="button" class="review-sort-btn is-active" data-review-sort-btn="top">Top reviews</button>
                                <button type="button" class="review-sort-btn" data-review-sort-btn="latest">Most recent</button>
                                <button type="button" class="review-sort-btn" data-review-sort-btn="highest">Highest rating</button>
                                <button type="button" class="review-sort-btn" data-review-sort-btn="lowest">Lowest rating</button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="review-list" data-review-list>
                    @foreach($approvedReviews as $review)
                        @php
                            $headline = $review['title'] ?: Str::limit($review['comment'], 72);
                            $reviewDate = $review['created_at'];
                            $reviewTimestamp = optional($reviewDate)->timestamp ?? 0;
                            $reviewAuthor = $review['name'] ?: 'Anonymous';
                        @endphp
                        <article class="review-card" data-review-item data-review-rating="{{ $review['rating'] }}" data-review-created="{{ $reviewTimestamp }}" itemscope itemtype="https://schema.org/Review">
                            <div class="review-card-head">
                                <div class="review-author-box">
                                    <div class="review-avatar">{{ Str::upper(Str::substr($reviewAuthor, 0, 1)) }}</div>
                                    <div>
                                        <h4 itemprop="author">{{ $reviewAuthor }}</h4>
                                        <p>
                                            {{ $reviewDate ? $reviewDate->format('d M Y') : 'Recently added' }}
                                            @if($reviewDate)
                                                <span class="review-date-dot"></span>{{ $reviewDate->diffForHumans() }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <span class="review-rating-badge">
                                    {{ $review['rating'] }}.0
                                    <svg width="14" height="14" viewBox="0 0 15 14" fill="currentColor" aria-hidden="true">
                                        <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                                    </svg>
                                </span>
                            </div>

                            <div class="review-card-stars">
                                <div class="review-star-row">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg width="18" height="17" viewBox="0 0 15 14" fill="currentColor" class="{{ $i <= $review['rating'] ? 'is-filled' : '' }}">
                                            <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                                        </svg>
                                    @endfor
                                </div>
                                @if($headline)
                                    <h5 itemprop="name">{{ $headline }}</h5>
                                @endif
                            </div>

                            <div class="review-card-body" itemprop="reviewBody">
                                {!! nl2br(e($review['comment'] ?: $review['title'] ?: 'Customer shared a rating without a written comment.')) !!}
                            </div>

                            <div class="review-card-meta">
                                <span class="review-approved-pill">Approved review</span>
                                @if(!empty($product))
                                    <span>For {{ $product->name }}</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="review-empty-state review-panel hidden" data-review-empty-state>
                    <h3>No reviews match this filter</h3>
                    <p>Try another star rating or clear the current filter to see all reviews.</p>
                </div>
            </div>
        @else
            <div class="review-panel review-zero-panel">
                <div class="review-zero-art" aria-hidden="true">
                    <span class="is-long"></span>
                    <span></span>
                    <span class="is-medium"></span>
                </div>
                <p class="review-panel-kicker review-panel-kicker--blue">Be the first voice</p>
                <h3 class="review-zero-title">No reviews yet</h3>
                <p class="review-zero-copy">This product does not have customer feedback yet. Add the first review and it will appear here after approval.</p>
                <div class="review-zero-actions">
                    <a href="#review-form" class="review-action-link">Write first review</a>
                    <p class="review-zero-note">Sorting and review browsing will appear automatically once reviews start coming in.</p>
                </div>
            </div>
        @endif
    </div>
</section>
