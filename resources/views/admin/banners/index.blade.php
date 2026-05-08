@extends('admin.layouts.app')

@section('title', 'Banners')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Banner Management</h1>
        <p class="text-gray-600 mt-1">Manage homepage offer banners and season text.</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Add Banner
    </a>
</div>

<div class="bg-white shadow-sm rounded-lg p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search banners"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <button class="bg-gray-900 text-white px-5 rounded-lg hover:bg-gray-800">Search</button>
    </form>
</div>

<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Offer</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Season</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($banners as $banner)
                    <tr>
                        <td class="px-6 py-4">
                            @if($banner->image)
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-24 h-16 object-cover rounded border">
                            @else
                                <div class="w-24 h-16 bg-gray-100 flex items-center justify-center rounded border text-xs text-gray-500">Product image</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $banner->title }}</div>
                            <div class="text-sm text-gray-500">{{ $banner->offer_price ?: 'Auto price' }} · {{ $banner->offer_title ?: 'Auto title' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $banner->season_year }} {{ $banner->season_text }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $banner->product?->name ?? 'Not linked' }}</td>
                        <td class="px-6 py-4">{{ $banner->order }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $banner->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($banner->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" class="inline" onsubmit="return confirm('Delete this banner?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-image text-4xl mb-4 block"></i>
                            <p>No banners found. <a href="{{ route('admin.banners.create') }}" class="text-blue-600 hover:underline">Add first banner</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($banners->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $banners->links() }}
        </div>
    @endif
</div>
@endsection
