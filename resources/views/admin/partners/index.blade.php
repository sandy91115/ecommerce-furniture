@extends('admin.layouts.app')

@section('title', 'Partners')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Partners Management</h1>
        <p class="text-gray-600 mt-1">Manage trusted partner logos and links for frontend slider</p>
    </div>
    <a href="{{ route('admin.partners.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Add New Partner
    </a>
</div>



<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($partners as $partner)
                    <tr>
                        <td class="px-6 py-4">
                            @if($partner->image)
                                <img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}" class="w-16 h-16 object-contain rounded border">
                            @else
                                <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded border text-sm text-gray-500">No Image</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $partner->name }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ $partner->url }}" target="_blank" class="text-blue-600 hover:text-blue-900 text-sm truncate max-w-xs block" title="{{ $partner->url ?? 'No URL' }}">
                                {{ $partner->url ?: 'No URL' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $partner->order }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $partner->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($partner->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.partners.edit', $partner) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" class="inline" onsubmit="return confirm('Delete this partner? Image will be removed.')" style="display: inline;">
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
                            <i class="fas fa-handshake text-4xl mb-4 block"></i>
                            <p>No partners found. <a href="{{ route('admin.partners.create') }}" class="text-blue-600 hover:underline">Add your first partner</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($partners->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $partners->links() }}
        </div>
    @endif
</div>
@endsection

