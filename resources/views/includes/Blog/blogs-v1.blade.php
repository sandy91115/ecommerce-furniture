@php
$blogs = $latestBlogs ?? collect();

if (isset($latestBlogs)) {
    $blogs = $latestBlogs->map(function ($blog) {
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
        <a href="{{ route('blog.show', $item['slug'] ?? Str::slug($item['title'])) }}" class="overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog">
        </a>
        <div class="text-center mt-4 px-3">
            <ul class="flex items-center justify-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none ">{{ $item['date'] }}</li>
                <li><a href="{{ isset($item['category_slug']) ? route('blog.index', ['category' => $item['category_slug']]) : route('blog.index') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-primary-midum">{{ $item['category'] ?? 'Blog' }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium leading-[1.5] text-xl"><a href="{{ route('blog.show', $item['slug'] ?? Str::slug($item['title'])) }}" class="text-underline">{{ $item['title'] }} </a></h5>
        </div>
    </div>
@empty
    <div class="col-span-full text-center py-12">
        <i class="fas fa-blog text-6xl text-gray-300 mb-4"></i>
        <p class="text-xl text-gray-500">No blog posts found matching your criteria.</p>
        <a href="{{ route('blog.index') }}" class="mt-4 inline-block bg-primary text-white px-6 py Asc-2 rounded-lg font-medium">View All Blogs</a>
    </div>
@endforelse

