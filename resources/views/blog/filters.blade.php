<div class="lg:w-1/4 pr-8">
    <div class="sticky top-24">
        <!-- Search -->
        <div class="mb-8 p-6 bg-white rounded-xl shadow-lg">
            <h4 class="font-semibold text-lg mb-4">Search Posts</h4>
            <form method="GET" action="{{ route('blog.index') }}">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search blogs..." 
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <button type="submit" class="absolute left-4 top-3.5">
                        <i class="fas fa-search text-gray-400"></i>
                    </button>
                </div>
                @if(request()->has('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </form>
        </div>

        <!-- Categories -->
        <div class="mb-8 p-6 bg-white rounded-xl shadow-lg">
            <h4 class="font-semibold text-lg mb-4">Categories ({{ $categories->count() }})</h4>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('blog.index') }}" class="flex items-center justify-between gap-3 p-3 rounded-lg hover:bg-gray-50 transition {{ !request('category') ? 'bg-primary text-white' : 'text-gray-700 hover:text-primary' }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <span class="w-10 h-10 rounded-lg bg-primary-midum flex items-center justify-center flex-none">
                                <i class="fas fa-layer-group"></i>
                            </span>
                            <span>All Categories</span>
                        </span>
                        <span class="font-semibold">{{ $publishedBlogsCount ?? $blogs->total() }}</span>
                    </a>
                </li>
                @foreach($categories as $category)
                <li>
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                       class="flex items-center justify-between gap-3 p-3 rounded-lg hover:bg-gray-50 transition {{ request('category') == $category->slug ? 'bg-primary text-white' : 'text-gray-700 hover:text-primary' }}">
                        <span class="flex items-center gap-3 min-w-0">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-10 h-10 rounded-lg object-cover flex-none">
                            @else
                                <span class="w-10 h-10 rounded-lg bg-primary-midum flex items-center justify-center flex-none">
                                    <i class="fas fa-tag"></i>
                                </span>
                            @endif
                            <span class="truncate">{{ $category->name }}</span>
                        </span>
                        <span class="font-semibold">{{ $category->blogs_count }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        @if(request('category') || request('search'))
        <div class="p-6 bg-white rounded-xl shadow-lg mb-8">
            <h4 class="font-semibold text-lg mb-4">Active Filters</h4>
            <div class="flex flex-wrap gap-2 mb-4">
                @if(request('category'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Category: {{ $selectedCategory->name ?? request('category') }}
                    <a href="{{ route('blog.index', request()->except('category')) }}" class="ml-2 hover:text-blue-900">&times;</a>
                </span>
                @endif
                @if(request('search'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Search: {{ request('search') }}
                    <a href="{{ route('blog.index', request()->except('search')) }}" class="ml-2 hover:text-green-900">&times;</a>
                </span>
                @endif
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Clear all filters</a>
        </div>
        @endif
    </div>
</div>

