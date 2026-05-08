@extends('admin.layouts.app')

@section('title', $blog->title)

@section('content')
<<<<<<< HEAD
<div class="px-6 py-8">
=======
<div class="max-w-4xl mx-auto px-6 py-8">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $blog->title }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.blogs.edit', $blog) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Edit
            </a>
            <a href="{{ route('admin.blogs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition">
                Back to Blogs
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        @if($blog->featured_image)
        <div class="h-64 bg-gradient-to-r from-blue-500 to-indigo-600 relative overflow-hidden">
<<<<<<< HEAD
            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
=======
            <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            <div class="absolute inset-0 bg-black/20"></div>
        </div>
        @endif

        <div class="p-8">
            <div class="flex items-center text-sm text-gray-500 mb-6">
                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium mr-3">
                    {{ ucfirst($blog->status) }}
                </span>
                <span>{{ $blog->published_at?->format('M d, Y h:i A') ?: 'Not published' }}</span>
                @if($blog->tags && count($blog->tags))
                    <div class="ml-auto flex flex-wrap gap-1">
                        @foreach($blog->tags as $tag)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $blog->title }}</h2>
            <p class="text-gray-600 mb-8 leading-relaxed">{{ $blog->excerpt }}</p>

            <div class="prose max-w-none">
                {!! $blog->content !!}
            </div>
        </div>
    </div>
</div>
@endsection

