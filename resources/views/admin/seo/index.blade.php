@extends('admin.layouts.app')

@section('title', 'SEO Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">SEO Dashboard</h1>
            <p class="text-sm text-gray-500">Central overview for product, category, CMS, schema, and image SEO readiness.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.seo.pages') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                <i class="fas fa-file-lines"></i>
                Page SEO
            </a>
            <a href="{{ route('admin.seo.settings') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                <i class="fas fa-gear"></i>
                Settings
            </a>
            <a href="{{ route('admin.seo.health') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <i class="fas fa-heartbeat"></i>
                Website Health
            </a>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Overall SEO readiness</p>
                <p class="mt-1 text-4xl font-bold text-gray-900">{{ $audit['score'] }}%</p>
                @if(!empty($audit['score_breakdown']))
                    <p class="mt-2 text-xs text-gray-500">
                        Product avg {{ $audit['score_breakdown']['product_average'] }}% · Issue health {{ $audit['score_breakdown']['issue_health'] }}% · Open issues {{ $audit['score_breakdown']['open_issue_count'] }}
                    </p>
                @endif
            </div>
            <div class="h-3 w-full overflow-hidden rounded-full bg-gray-100 md:max-w-md">
                <div class="h-full rounded-full {{ $audit['score'] >= 80 ? 'bg-green-500' : ($audit['score'] >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $audit['score'] }}%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach($audit['cards'] as $card)
            @php
                $tone = [
                    'blue' => 'bg-blue-50 text-blue-700 border-blue-100',
                    'green' => 'bg-green-50 text-green-700 border-green-100',
                    'amber' => 'bg-amber-50 text-amber-700 border-amber-100',
                    'red' => 'bg-red-50 text-red-700 border-red-100',
                ][$card['tone']] ?? 'bg-gray-50 text-gray-700 border-gray-100';
            @endphp
            <div class="rounded-xl border p-5 {{ $tone }}">
                <p class="text-sm font-semibold">{{ $card['label'] }}</p>
                <p class="mt-2 text-3xl font-bold">{{ number_format($card['value']) }}</p>
            </div>
        @endforeach
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Recent Product SEO Audit</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Product</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Score</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Issues</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($audit['products'] as $product)
                        <tr>
                            <td class="px-6 py-4">
                                <a href="{{ $product['url'] }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-gray-900 hover:text-blue-600">{{ $product['name'] }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $product['score'] >= 80 ? 'bg-green-100 text-green-700' : ($product['score'] >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $product['score'] }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $product['issues'] ? implode(', ', $product['issues']) : 'No major issues' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ $product['admin_url'] }}" class="font-semibold text-blue-600 hover:text-blue-800">Edit SEO</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        @foreach($audit['bulk'] as $title => $items)
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ str_replace('_', ' ', ucfirst($title)) }}</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($items as $item)
                        <a href="{{ $item['url'] }}" class="block px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">{{ $item['label'] }}</a>
                    @empty
                        <p class="px-6 py-4 text-sm text-green-700">No issues found</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
