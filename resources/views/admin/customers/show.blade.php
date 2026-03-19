@extends('admin.layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.customers.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left text-2xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Customer Details</h1>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.customers.edit', $customer) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
            <i class="fas fa-edit mr-2"></i> Edit
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        <div class="bg-white shadow-lg rounded-xl p-6 text-center">
            <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center text-4xl text-gray-500 font-bold">
                {{ substr($customer->name, 0, 1) }}
            </div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h2>
            <p class="text-gray-500">{{ $customer->designation ?? 'Customer' }}</p>
            <div class="mt-6 pt-6 border-t border-gray-100 flex justify-around">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-semibold">Orders</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $customer->orders_count ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Personal Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase mb-1">Full Name</label>
                    <p class="text-lg text-gray-900">{{ $customer->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase mb-1">Email Address</label>
                    <p class="text-lg text-gray-900">{{ $customer->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase mb-1">Phone Number</label>
                    <p class="text-lg text-gray-900">{{ $customer->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase mb-1">Location</label>
                    <p class="text-lg text-gray-900">{{ $customer->location ?? 'Not provided' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-500 uppercase mb-1">Bio</label>
                    <p class="text-lg text-gray-900">{{ $customer->bio ?? 'No bio provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase mb-1">Social Media</label>
                    <p class="text-lg text-gray-900">{{ $customer->social_links ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
