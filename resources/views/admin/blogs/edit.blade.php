@extends('admin.layouts.app')

@section('title', 'Edit Blog')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Blog</h1>
        <a href="{{ route('admin.blogs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition">
            Back to Blogs
        </a>
    </div>

    <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-xl rounded-xl p-8">
        @method('PUT')
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $blog->title) }}" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $blog->slug) }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
            <textarea name="excerpt" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $blog->excerpt) }}</textarea>
            @error('excerpt')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
            <textarea name="content" id="content" rows="15" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror">{{ old('content', $blog->content) }}</textarea>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                @if($blog->featured_image)
                    <img src="{{ Storage::url($blog->featured_image) }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg mb-2">
                @endif
                <input type="file" name="featured_image" accept="image/*" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('featured_image') border-red-500 @enderror">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                    <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status', $blog->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        @include('admin.blogs.partials.tags-field', ['tags' => old('tags', $blog->tags ?? []), 'fieldId' => 'edit-blog-tags'])

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $blog->published_at?->format('Y-m-d\TH:i')) }}" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                Update Blog
            </button>
            <a href="{{ route('admin.blogs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<style>
    .ck-editor__editable[role="textbox"] {
        min-height: 500px;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@42.0.2/build/ckeditor.js"></script>
<script>
ClassicEditor
    .create( document.querySelector( '#content' ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo' ]
    } )
    .catch( error => {
        console.error( error );
    } );
</script>

@endsection

