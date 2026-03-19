@php
$blogs = $latestBlogs ?? collect();

if ($blogs->isEmpty()) {
    $blogs = collect([
        [
            'id' => 1,
            'image_url' => 'assets/img/shortcode/blog/blog-01.jpg',
            'title' => 'Auctor sit elementum habitant vel tempor varius.',
            'tags' => ['Interior'],
            'published_at' => now(),
        ],
        [
            'id' => 2,
            'image_url' => 'assets/img/shortcode/blog/blog-02.jpg',
            'title' => 'Consectetur purus habitasse ut diam habitant varius.',
            'tags' => ['Chair'],
            'published_at' => now(),
        ],
        [
            'id' => 3,
            'image_url' => 'assets/img/shortcode/blog/blog-03.jpg',
            'title' => 'Far far away of furniture of this habitant vel tempor.',
            'tags' => ['Vase'],
            'published_at' => now(),
        ],
    ]);
}

$blogs = $blogs->map(function ($blog) {
    $blogId = isset($blog->id) ? $blog->id : ($blog['id'] ?? 0);
    $blogImage = isset($blog->image_url) ? $blog->image_url : ($blog['image_url'] ?? '');
    $blogTitle = isset($blog->title) ? $blog->title : ($blog['title'] ?? '');
    $blogTags = isset($blog->tags) ? $blog->tags : ($blog['tags'] ?? []);
    $blogPublished = isset($blog->published_at) ? $blog->published_at : ($blog['published_at'] ?? now());
    
    return [
        'id' => $blogId,
        'img' => $blogImage,
        'title' => $blogTitle,
        'tag' => $blogTags[0] ?? 'Blog',
        'date' => $blogPublished->format('d M, Y'),
    ];
})->take(5);
@endphp

@foreach ($blogs as $item)
<div class="group">
    <a href="{{ route('blog.show', Str::slug($item['title'])) }}" class="overflow-hidden block">
        <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog"> </a>
    <div class="text-center mt-4 px-3">
        <ul class="flex items-center justify-center gap-[10px] flex-wrap">
            <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
            <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-2 px-[10px] rounded-md bg-primary-midum">{{ $item['tag'] }}</a></li>
        </ul>
        <h5 class="text-xl mt-3 font-medium dark:text-white leading-[1.5]"><a href="{{ route('blog.show', Str::slug($item['title'])) }}" class="text-underline">{{ $item['title'] }} </a></h5>

    </div>
</div>
@endforeach

