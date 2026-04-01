

@forelse ($featuredProducts as $product)
    <a href="{{ route('product-details', $product->slug) }}" class="relative before:absolute before:top-0 before:left-0 before:w-full before:h-full before:bg-title before:bg-opacity-70 group before:opacity-0 before:duration-300 hover:before:opacity-100 overflow-hidden before:z-10 block" style="aspect-ratio: 4 / 3;">
        <img class="w-full h-full object-cover transform duration-300 group-hover:scale-110" src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->path) : asset('assets/img/product/default.jpg') }}" alt="{{ $product->name }}">
        <div class="absolute z-20 w-full h-full flex top-0 left-0 flex-col items-center justify-center px-5">
            <h3 class="text-white leading-tight font-semibold transform -translate-y-5 opacity-0 duration-300 group-hover:-translate-y-0 group-hover:opacity-100 text-3xl">{{ $product->name }}</h3>
            <span class="text-white leading-none divide-black mt-3 transform translate-y-5 opacity-0 duration-300 group-hover:translate-y-0 group-hover:opacity-100">{{ currency($product->sale_price ?? $product->price, 0) }}</span>
        </div>
    </a>
@empty
<div class="text-white text-center p-8">No featured products available</div>
@endforelse
