@php
$categorys = $categories ?? collect([
    [
        'id' => 1,
        'image' => 'assets/img/home-v1/pdct-cgry-01.jpg', 
        'name' => '5 items', 
        'title' => "New Seat Tools", 
        'slug' => 'new-seat-tools'
    ],
    [
        'id' => 2,
        'image' => 'assets/img/home-v1/pdct-cgry-02.jpg', 
        'name' => '13 items', 
        'title' => "Flexible Sofa", 
        'slug' => 'flexible-sofa'
    ],
    [
        'id' => 3,
        'image' => 'assets/img/home-v1/pdct-cgry-03.jpg', 
        'name' => '23 items', 
        'title' => "Interior Item", 
        'slug' => 'interior-item'
    ],
]);

if (isset($categories) && $categories->count() > 0) {
    $categorys = $categories->map(function ($category) {
        $imagePath = $category->image ? asset('storage/' . $category->image) : asset('assets/img/product/default.jpg');
        $itemCount = $category->products()->count();
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
    <a class="relative block" href="{{ url('/product-category') }}">
        <img class="w-full object-cover" src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}">
        <div class="absolute bottom-7 left-0 px-5 transform w-full flex justify-start">
            <div class="p-[15px] bg-white dark:bg-title w-auto">
                <span class="md:text-xl text-primary font-medium leading-none">{{ $item['name'] }}</span>
                <h4 class="text-xl md:text-2xl mt-[10px] font-semibold leading-[1.5]">{{ $item['title'] }}</h4>
            </div>
        </div>
    </a>
@endforeach