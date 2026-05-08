@extends('admin.layouts.app')

@section('title', 'Blogs')

@section('content')
<<<<<<< HEAD
<div class=" px-6 py-8">
=======
<div class="max-w-7xl mx-auto px-6 py-8">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Blogs</h1>
        <a href="{{ route('admin.blogs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
            <i class="fas fa-plus mr-2"></i> Create New Blog
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($blogs as $blog)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-12 h-12 object-cover rounded">
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 truncate max-w-xs" title="{{ $blog->title }}">
                                {{ Str::limit($blog->title, 50) }}
                            </div>
                            <div class="text-sm text-gray-500">{{ Str::limit($blog->excerpt, 80) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                @if($blog->status == 'published') bg-green-100 text-green-800 @elseif($blog->status == 'draft') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($blog->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $blog->published_at?->format('M d, Y') ?: 'Not published' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $blog->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.blogs.show', $blog) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-blog text-4xl mb-4 block"></i>
                            <p>No blogs found. <a href="{{ route('admin.blogs.create') }}" class="text-blue-600 hover:underline font-medium">Create your first blog!</a></p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-4">
            {{ $blogs->links() }}
        </div>
    </div>
</div>
@endsection

