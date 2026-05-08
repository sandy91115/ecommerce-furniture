@extends('admin.layouts.app')

@section('title', 'Recycle Bin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Recycle Bin</h1>
           
        </div>
        <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm text-blue-800 shadow-sm">
            <div class="font-semibold">Total Trashed Items: {{ $totalItems }}</div>
            <div class="mt-1">
                @if($canForceDelete)
                    Super Admin access detected. Permanent delete enabled.
                @else
                    Permanent delete disabled. Super Admin access required.
                @endif
            </div>
        </div>
    </div>

    @if($totalItems > 0)
        <div class="flex flex-wrap gap-3">
            @foreach($sections as $section)
                @if($section['items']->count() > 0)
                    <a href="#trash-{{ $section['type'] }}" class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:border-blue-300 hover:text-blue-600">
                        {{ $section['label'] }}
                        <span class="ml-2 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $section['items']->count() }}</span>
                    </a>
                @endif
            @endforeach
        </div>

        <div class="space-y-6">
            @foreach($sections as $section)
                @if($section['items']->count() > 0)
                    <section id="trash-{{ $section['type'] }}" class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $section['label'] }}</h2>
                            <p class="mt-1 text-sm text-gray-500">{{ $section['items']->count() }} item(s) currently in trash.</p>
                        </div>

                        @include('admin.trash.table', [
                            'items' => $section['items'],
                            'type' => $section['type'],
                            'canForceDelete' => $canForceDelete,
                        ])
                    </section>
                @endif
            @endforeach
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-gray-300 bg-white px-8 py-20 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-500">
                <i class="fas fa-recycle text-2xl"></i>
            </div>
            <h2 class="mt-5 text-2xl font-semibold text-gray-900">Recycle Bin Empty Hai</h2>
            <p class="mt-2 text-sm text-gray-500">Abhi koi supported record trash me nahi hai.</p>
        </div>
    @endif
</div>
@endsection

