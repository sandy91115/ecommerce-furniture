@extends('admin.layouts.app')

@section('title', 'SEO Settings')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">SEO Settings</h1>
            <p class="text-sm text-gray-500">Global defaults used by every page when manual SEO fields are empty.</p>
        </div>
        <a href="{{ route('admin.seo.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
            <i class="fas fa-arrow-left"></i>
            SEO Dashboard
        </a>
    </div>

    <form action="{{ route('admin.seo.settings.update') }}" method="POST" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Title Suffix</label>
                <input type="text" name="{{ \App\Models\Setting::SEO_TITLE_SUFFIX }}" value="{{ $settings[\App\Models\Setting::SEO_TITLE_SUFFIX] }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Default OG Image Path</label>
                <input type="text" name="{{ \App\Models\Setting::SEO_DEFAULT_OG_IMAGE_PATH }}" value="{{ $settings[\App\Models\Setting::SEO_DEFAULT_OG_IMAGE_PATH] }}" placeholder="uploads/seo/default-og.webp" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Meta Description</label>
                <textarea name="{{ \App\Models\Setting::SEO_DEFAULT_DESCRIPTION }}" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ $settings[\App\Models\Setting::SEO_DEFAULT_DESCRIPTION] }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name</label>
                <input type="text" name="{{ \App\Models\Setting::SEO_ORGANIZATION_NAME }}" value="{{ $settings[\App\Models\Setting::SEO_ORGANIZATION_NAME] }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Organization Logo Path</label>
                <input type="text" name="{{ \App\Models\Setting::SEO_ORGANIZATION_LOGO_PATH }}" value="{{ $settings[\App\Models\Setting::SEO_ORGANIZATION_LOGO_PATH] }}" placeholder="site/logo.webp" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Organization Phone</label>
                <input type="text" name="{{ \App\Models\Setting::SEO_ORGANIZATION_PHONE }}" value="{{ $settings[\App\Models\Setting::SEO_ORGANIZATION_PHONE] }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Organization Email</label>
                <input type="email" name="{{ \App\Models\Setting::SEO_ORGANIZATION_EMAIL }}" value="{{ $settings[\App\Models\Setting::SEO_ORGANIZATION_EMAIL] }}" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Social Links</label>
                <textarea name="{{ \App\Models\Setting::SEO_SOCIAL_LINKS }}" rows="3" placeholder="One URL per line" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ $settings[\App\Models\Setting::SEO_SOCIAL_LINKS] }}</textarea>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <label class="inline-flex items-center gap-2 rounded-lg border border-gray-200 p-4 text-sm font-medium text-gray-700">
                <input type="hidden" name="{{ \App\Models\Setting::SEO_SEARCH_ACTION_ENABLED }}" value="0">
                <input type="checkbox" name="{{ \App\Models\Setting::SEO_SEARCH_ACTION_ENABLED }}" value="1" {{ filter_var($settings[\App\Models\Setting::SEO_SEARCH_ACTION_ENABLED], FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                Enable SearchAction schema
            </label>
            <label class="inline-flex items-center gap-2 rounded-lg border border-gray-200 p-4 text-sm font-medium text-gray-700">
                <input type="hidden" name="{{ \App\Models\Setting::SEO_DEFAULT_ROBOTS_INDEX }}" value="0">
                <input type="checkbox" name="{{ \App\Models\Setting::SEO_DEFAULT_ROBOTS_INDEX }}" value="1" {{ filter_var($settings[\App\Models\Setting::SEO_DEFAULT_ROBOTS_INDEX], FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                Default index
            </label>
            <label class="inline-flex items-center gap-2 rounded-lg border border-gray-200 p-4 text-sm font-medium text-gray-700">
                <input type="hidden" name="{{ \App\Models\Setting::SEO_DEFAULT_ROBOTS_FOLLOW }}" value="0">
                <input type="checkbox" name="{{ \App\Models\Setting::SEO_DEFAULT_ROBOTS_FOLLOW }}" value="1" {{ filter_var($settings[\App\Models\Setting::SEO_DEFAULT_ROBOTS_FOLLOW], FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                Default follow
            </label>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Priority</label>
                <input type="number" min="0.1" max="1" step="0.1" name="{{ \App\Models\Setting::SEO_SITEMAP_PRODUCTS_PRIORITY }}" value="{{ $settings[\App\Models\Setting::SEO_SITEMAP_PRODUCTS_PRIORITY] }}" class="w-full rounded-lg border border-gray-300 px-4 py-3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Priority</label>
                <input type="number" min="0.1" max="1" step="0.1" name="{{ \App\Models\Setting::SEO_SITEMAP_CATEGORIES_PRIORITY }}" value="{{ $settings[\App\Models\Setting::SEO_SITEMAP_CATEGORIES_PRIORITY] }}" class="w-full rounded-lg border border-gray-300 px-4 py-3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Page Priority</label>
                <input type="number" min="0.1" max="1" step="0.1" name="{{ \App\Models\Setting::SEO_SITEMAP_PAGES_PRIORITY }}" value="{{ $settings[\App\Models\Setting::SEO_SITEMAP_PAGES_PRIORITY] }}" class="w-full rounded-lg border border-gray-300 px-4 py-3">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Extra Robots Disallow Paths</label>
                <textarea name="{{ \App\Models\Setting::SEO_ROBOTS_EXTRA_DISALLOW }}" rows="3" placeholder="/compare&#10;/thank-you" class="w-full rounded-lg border border-gray-300 px-4 py-3">{{ $settings[\App\Models\Setting::SEO_ROBOTS_EXTRA_DISALLOW] }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Save Settings</button>
        </div>
    </form>
</div>
@endsection
