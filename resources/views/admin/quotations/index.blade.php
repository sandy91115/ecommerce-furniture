@extends('admin.layouts.app')

@section('title', 'Quotation Requests')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Quotation Requests</h1>
            <p class="mt-1 text-sm text-gray-600">View and manage customer quotation inquiries.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Message</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($quotations as $quotation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $quotation->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/product/{{ $quotation->product->slug }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium text-sm">{{ $quotation->product->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $quotation->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <a href="mailto:{{ $quotation->email }}" class="text-blue-600 hover:text-blue-800">{{ $quotation->email }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $quotation->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs">
                                {{ \Illuminate\Support\Str::limit(filled($quotation->message) ? $quotation->message : 'No message provided', 80) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($quotation->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                    @if($quotation->status == 'contacted') bg-blue-100 text-blue-800 @endif
                                    @if($quotation->status == 'closed') bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($quotation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $quotation->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.quotations.show', $quotation) }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 font-medium text-xs">
                                        View Details
                                    </a>
                                    <form action="{{ route('admin.quotations.destroy', $quotation) }}" method="POST" onsubmit="return confirm('Delete this quotation request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-600 text-white hover:bg-red-700 font-medium text-xs">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-500">No quotations yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $quotations->links() }}
        </div>
    </div>
</div>
@endsection
