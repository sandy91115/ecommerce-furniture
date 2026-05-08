

@forelse ($products as $product)
    @php
        $productImage = $product->images->first();
        $imageUrl = $productImage ? asset('storage/' . $productImage->image) : asset('assets/img/product/default.jpg');
    @endphp
    <a href="{{ route('product-details', $product->slug) }}" class="relative group overflow-hidden">
        <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ $imageUrl }}" alt="{{ $product->name }}">
        <div class="absolute bottom-5 sm:bottom-8 lg:bottom-12 w-full left-0 px-7 flex justify-center">
            <div class=" bg-white bg-opacity-80 dark:bg-title dark:bg-opacity-80 p-5 z-10">
                <h4 class="font-semibold leading-[1.5] text-2xl">{{ $product->name }}</h4>
                <p class="leading-none mt-[10px]">{{ $product->category->name ?? '' }}</p>
            </div>
        </div>
    </a>
@empty
@endforelse
