@php
<<<<<<< HEAD
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
=======
    $categorys = $categories ?? collect([
        [
            'id' => 1,
'image' => 'assets/img/product/default.jpg',
            'name' => '5 items',
            'title' => "New Seat Tools",
            'slug' => 'new-seat-tools'
        ],
        [
            'id' => 2,
'image' => 'assets/img/product/default.jpg',
            'name' => '13 items',
            'title' => "Flexible Sofa",
            'slug' => 'flexible-sofa'
        ],
        [
            'id' => 3,
'image' => 'assets/img/product/default.jpg',
            'name' => '23 items',
            'title' => "Interior Item",
            'slug' => 'interior-item'
        ],
    ]);

    if (isset($categories) && $categories->count() > 0) {
        $categorys = $categories->map(function ($category) {
$imagePath = $category->image ? asset('storage/' . $category->image) : asset('assets/img/product/default.jpg');
            $itemCount = $category->products_count ?? $category->products()->where('status', 'active')->count();
            return [
                'id' => $category->id,
                'image' => $imagePath,
                'name' => $itemCount . ' items',
                'title' => $category->name,
                'slug' => $category->slug
            ];
        })->take(8);
    }
@endphp

@foreach ($categorys as $item)
    @php
        $imageUrl = filter_var($item['image'], FILTER_VALIDATE_URL) ? $item['image'] : asset($item['image']);
    @endphp
    <a class="category-card group relative block overflow-hidden" href="{{ route('shop.category', ['category' => $item['slug']]) }}"
        style="aspect-ratio: 4 / 5;">
        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy"
            src="{{ $imageUrl }}" alt="{{ $item['title'] }}">
        <div class="absolute bottom-7 left-0 px-5 transform w-full flex justify-start">
            <div class="category-card__panel p-[15px] bg-white dark:bg-title w-auto">
                <span class="md:text-xl text-primary font-medium leading-none">{{ $item['name'] }}</span>
                <h4 class="text-xl md:text-2xl mt-[10px] font-semibold leading-[1.5]">{{ $item['title'] }}</h4>
            </div>
        </div>
    </a>
@endforeach
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
