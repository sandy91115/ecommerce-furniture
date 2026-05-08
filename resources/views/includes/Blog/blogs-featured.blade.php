@php
$blogs = $featuredBlogs ?? collect();

if (isset($featuredBlogs)) {
    $blogs = $featuredBlogs->map(function ($blog) {
        return [
            'id' => $blog->id,
            'slug' => $blog->slug,
            'img' => $blog->image_url,
            'title' => $blog->title,
            'category' => $blog->categories->first()?->name ?? 'Blog',
            'category_slug' => $blog->categories->first()?->slug,
            'date' => $blog->published_at ? $blog->published_at->format('d M, Y') : $blog->created_at->format('d M, Y'),
        ];
    });
}
@endphp

@foreach ($blogs as $item)
    <div class="relative group">
        <a href="{{ route('blog.show', $item['slug'] ?? Str::slug($item['title'])) }}" class="overflow-hidden block">           <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </a>
        <div class="sm:bg-white sm:bg-opacity-90 sm:dark:bg-title sm:dark:bg-opacity-90 mt-4 sm:p-5 md:p-6 sm:absolute z-10 bottom-0 left-0 sm:w-11/12 max-w-md ">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ isset($item['category_slug']) ? route('blog.index', ['category' => $item['category_slug']]) : route('blog.index') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-[#dbcbbd]">{{ $item['category'] ?? ($item['tag'] ?? 'Blog') }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ route('blog.show', $item['slug'] ?? Str::slug($item['title'])) }}" class="text-underline">{{ $item['title'] }} </a></h5>
        </div>
    </div>
@endforeach
