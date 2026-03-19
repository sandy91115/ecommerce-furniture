@extends('layouts.main')

@section('title', 'My Account')

@section('content')
<div class="s-py-100">
    <div class="container">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-title dark:text-white mb-8">My Account Dashboard</h1>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-4xl mx-auto mt-12">
                <a href="{{ route('frontend.account.orders') }}" class="group p-8 bg-white dark:bg-dark-secondary rounded-2xl shadow-lg hover:shadow-xl transition-all border hover:border-primary">
                    <div class="w-16 h-16 bg-primary/10 group-hover:bg-primary/20 rounded-xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h18l-2 12H5L3 3zm2 10h14M9 17h6"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2 group-hover:text-primary transition-colors">Orders</h3>
                    <p class="text-gray-600 dark:text-gray-300">View your order history</p>
                </a>
                <a href="{{ url('/wishlist') }}" class="group p-8 bg-white dark:bg-dark-secondary rounded-2xl shadow-lg hover:shadow-xl transition-all border hover:border-primary">
                    <div class="w-16 h-16 bg-pink-100 dark:bg-pink-900/20 group-hover:bg-pink-500/20 rounded-xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2 group-hover:text-pink-500 transition-colors">Wishlist</h3>
                    <p class="text-gray-600 dark:text-gray-300">Your saved items</p>
                </a>
                <a href="{{ url('/my-account') }}" class="group p-8 bg-white dark:bg-dark-secondary rounded-2xl shadow-lg hover:shadow-xl transition-all border hover:border-primary">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/20 group-hover:bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2 group-hover:text-blue-500 transition-colors">Profile</h3>
                    <p class="text-gray-600 dark:text-gray-300">Edit account details</p>
                </a>
                <a href="{{ route('frontend.cart') }}" class="group p-8 bg-white dark:bg-dark-secondary rounded-2xl shadow-lg hover:shadow-xl transition-all border hover:border-primary">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/20 group-hover:bg-green-500/20 rounded-xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-4V7m8 10v6m0 0l-8 4m8-4l-8-4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2 group-hover:text-green-500 transition-colors">Shopping Cart</h3>
                    <p class="text-gray-600 dark:text-gray-300">View your cart</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
