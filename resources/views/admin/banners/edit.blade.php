@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
<div class="mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Banner</h1>
            <p class="text-gray-600 mt-1">{{ $banner->title }}</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>All Banners
        </a>
    </div>

    @include('admin.banners.partials.form', [
        'action' => route('admin.banners.update', $banner),
        'method' => 'PUT',
    ])
</div>
@endsection
