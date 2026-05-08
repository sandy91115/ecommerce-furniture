@extends('admin.layouts.app')

@section('title', 'Edit Customer')

@section('content')
<<<<<<< HEAD
<div >
=======
<div class="max-w-4xl">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.customers.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to Customers
        </a>
    </div>

    <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="bg-white shadow-lg rounded-xl p-8">
        @csrf
        @method('PUT')
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Edit Customer: {{ $customer->name }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-blue-500"></i>Name *
                </label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 ring-2 ring-red-500/50 @enderror" required>
                @error('name') <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-green-500"></i>Email *
                </label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 ring-2 ring-red-500/50 @enderror" required>
                @error('email') <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-briefcase mr-2 text-indigo-500"></i>Designation
                </label>
                <input type="text" name="designation" value="{{ old('designation', $customer->designation) }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-phone mr-2 text-yellow-500"></i>Phone
                </label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Location
                </label>
                <input type="text" name="location" value="{{ old('location', $customer->location) }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-share-alt mr-2 text-blue-400"></i>Social Links
                </label>
                <input type="text" name="social_links" value="{{ old('social_links', $customer->social_links) }}" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="mb-6">
            <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-info-circle mr-2 text-gray-500"></i>Bio
            </label>
            <textarea name="bio" rows="4" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('bio', $customer->bio) }}</textarea>
        </div>

        <div class="mb-6">
            <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-purple-500"></i>Password (Leave blank to keep current)
            </label>
            <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 ring-2 ring-red-500/50 @enderror">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full p-3 mt-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('password') <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all duration-300 flex items-center justify-center">
            <i class="fas fa-save mr-2"></i> Update Customer
        </button>
    </form>
</div>
@endsection
