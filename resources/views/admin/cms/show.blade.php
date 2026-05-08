@extends('admin.layouts.app')

@section('title', $page->title)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $page->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">Preview and manage page details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.cms.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-list mr-2"></i>
                All Pages
            </a>
            <a href="{{ route('admin.cms.edit', $page) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent shadow-sm text-sm font-medium rounded-md text-white hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>
                Edit Page
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Page Info -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Page Information</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium">/ {{ $page->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $page->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($page->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sort Order</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->sort_order }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-500">{{ $page->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Updated</dt>
                        <dd class="mt-1 text-sm text-gray-500">{{ $page->updated_at->format('M d, Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        <!-- Content Preview -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Page Preview</h3>
                </div>
                <div class="p-8 prose max-w-none">
                    {!! $page->content !!}
                </div>
            </div>

            <!-- Meta -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">SEO Meta</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                            <p class="text-lg font-medium text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">{{ $page->meta_title ?? $page->title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <p class="text-sm text-gray-600 bg-gray-50 px-4 py-3 rounded-lg border max-w-2xl leading-relaxed">{{ $page->meta_description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

