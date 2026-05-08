@php
    $themeColors = ['#F6EEE5', '#F6ECEE', '#EAF1F7', '#EDF1EA', '#EEEAF8', '#F6EEE8'];
    $fallbackImages = [
        'assets/img/home-v5/pdct-01.jpg',
        'assets/img/home-v5/pdct-02.jpg',
        'assets/img/home-v5/pdct-03.jpg',
        'assets/img/home-v5/pdct-04.jpg',
        'assets/img/home-v5/pdct-05.jpg',
        'assets/img/home-v5/pdct-06.jpg',
    ];

    $categoryCards = collect([]);

    if (isset($categories) && $categories->count() > 0) {
        $categoryCards = $categories->take(6)->values()->map(function ($category, $index) use ($themeColors, $fallbackImages) {
            $itemCount = $category->products_count ?? $category->products()->where('status', 'active')->count();

            return [
                'title' => $category->name,
                'count_label' => $itemCount . ' ' . ($itemCount === 1 ? 'Product' : 'Products'),
                'image' => $category->image ? asset('storage/' . $category->image) : asset($fallbackImages[$index % count($fallbackImages)]),
                'url' => route('shop.category', ['category' => $category->slug]),
                'accent' => $themeColors[$index % count($themeColors)],
            ];
        });
    }
@endphp

@if($categoryCards->count() > 0)
<div class="home-category-grid">
    @foreach ($categoryCards as $item)
        <a class="home-category-card group" href="{{ $item['url'] }}">
            <div class="home-category-card__media" style="--category-accent: {{ $item['accent'] }};">
                <img class="home-category-card__image" loading="lazy" src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
            </div>
            <div class="home-category-card__content">
                <h4>{{ $item['title'] }}</h4>
                <p>{{ $item['count_label'] }}</p>
            </div>
        </a>
    @endforeach
</div>
@endif
