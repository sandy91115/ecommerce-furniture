@php
$blogs = $relatedBlogs ?? collect();

if ($relatedBlogs) {
    $blogs = $relatedBlogs->map(function ($blog) {
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

@forelse ($blogs as $item)
    <div class="group">
        <a href="{{ route('blog.show', $item['slug']) }}" class="overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog">
        </a>
        <div class="p-5 relative z-10 before:absolute before:-z-10 before:top-0 before:left-0 before:w-full before:h-full before:bg-secondary dark:before:bg-dark-secondary before:transition-all before:duration-300 overflow-hidden before:opacity-0 group-hover:before:opacity-10 dark:group-hover:before:opacity-100">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ isset($item['category_slug']) ? route('blog.index', ['category' => $item['category_slug']]) : route('blog.index') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-[#dbcbbd]">{{ $item['category'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ route('blog.show', $item['slug']) }}" class="text-underline">{{ $item['title'] }}</a></h5>
        </div>
    </div>
@empty
    <div class="col-span-full text-center py-12">
        <i class="fas fa-blog text-6xl text-gray-300 mb-4"></i>
        <p class="text-xl text-gray-500">No related posts found.</p>
    </div>
@endforelse

