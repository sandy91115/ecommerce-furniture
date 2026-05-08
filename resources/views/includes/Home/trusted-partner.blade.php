<div class="max-w-[1720px] mx-auto owl-carousel home-v1-partner-slider" data-carousel-items="6" data-carousel-margin="30" data-carousel-xl="5" data-carousel-lg="4" data-carousel-md="3" data-carousel-sm="2" data-carousel-xs="1" data-carousel-autoplay="true" data-carousel-loop="true" data-carousel-animateout="false">
    @forelse(App\Models\Partner::active()->ordered()->get() as $partner)
        <a href="{{ $partner->url ?? '#' }}" class="flex h-full min-h-[100px] w-full items-center justify-center px-4 py-3" aria-label="{{ $partner->name }} logo">
            @if($partner->image)
                <span class="flex h-full w-full items-center justify-center overflow-visible">
                    <img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}" class="block h-full w-full max-w-full object-contain object-center dark:invert" loading="lazy">
                </span>
            @else
                <div class="flex h-full w-full max-w-[160px] items-center justify-center rounded bg-gray-200 px-4 text-center text-xs text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                    {{ Str::limit($partner->name, 10) }}
                </div>
            @endif
        </a>
    @empty
        {{-- Fallback static partners if none configured --}}
        @php
            $fallbackPartners = [
                ['name' => 'Partner 1', 'url' => '#', 'image' => null],
                ['name' => 'Partner 2', 'url' => '#', 'image' => null],
            ];
        @endphp
        @foreach($fallbackPartners as $fallback)
            <a href="{{ $fallback['url'] }}" class="flex h-full min-h-[100px] w-full items-center justify-center px-4 py-3 text-gray-400">
                <div class="flex h-full w-full max-w-[160px] items-center justify-center rounded bg-gray-200 px-4 text-center text-xs dark:bg-gray-700">
                    Configure Partners
                </div>
            </a>
        @endforeach
    @endforelse
</div>
