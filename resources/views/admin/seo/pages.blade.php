@extends('admin.layouts.app')

@section('title', 'Page SEO')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Page SEO</h1>
            <p class="text-sm text-gray-500">Central SEO controls for fixed public pages.</p>
        </div>
        <a href="{{ route('admin.seo.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
            <i class="fas fa-arrow-left"></i>
            SEO Dashboard
        </a>
    </div>

    <div class="space-y-6">
        @foreach($pages as $page)
            <form action="{{ route('admin.seo.pages.update', $page['key']) }}" method="POST" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $page['label'] }}</h2>
                        <a href="{{ $page['url'] }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 hover:text-blue-800">{{ $page['url'] }}</a>
                    </div>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
                </div>

                @include('admin.seo.partials.advanced-fields', [
                    'seoMetadata' => $page['metadata'],
                    'includeTitleFields' => true,
                    'titleFallback' => $page['label'],
                    'descriptionFallback' => '',
                    'canonicalFallback' => $page['url'],
                    'schemaType' => 'WebPage',
                ])
            </form>
        @endforeach
    </div>
</div>
@endsection
