@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Customers</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">Dashboard</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Customers</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    @can('customers.create')
    <a href="{{ route('admin.customers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
        <i class="fas fa-plus mr-2"></i> Create Customer
    </a>
    @endcan
</div>

<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b dark:border-gray-600">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Orders</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Verified By</th>
                    <th scope="col" class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $customer->name }}</td>
                    <td class="px-6 py-4">{{ $customer->email }}</td>
                    <td class="px-6 py-4">{{ $customer->orders_count ?? 0 }}</td>
                    <td class="px-6 py-4">
                        @switch($customer->verification_status)
                            @case('pending')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                                @break
                            @case('verified')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Verified</span>
                                @break
                            @case('rejected')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Rejected</span>
                                @break
                            @default
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">Pending</span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4">
                        @if($customer->verifier)
                            <span class="text-gray-600 dark:text-gray-400">{{ $customer->verifier->name }}</span>
                        @else
                            <span class="text-gray-400 italic text-xs">Not verified</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <div class="flex justify-end items-center space-x-2">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('customers.update')
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            @can('customers.verify')
                            @if($customer->verification_status === 'pending')
                            <form action="{{ route('admin.customers.verify', $customer) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Verify">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.customers.reject', $customer) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Reject verification?')" title="Reject">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </form>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-users text-4xl mb-3 opacity-20"></i>
                            <p>No customers found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t dark:border-gray-700">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
