@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <div class="mx-auto h-24 w-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                <i class="fas fa-tachometer-alt text-3xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Welcome back, {{ Auth::user()->name }}!
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                This is your customer dashboard. Manage your account, view orders, wishlist and more.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">My Orders</h3>
                        <p class="text-gray-500">Track your recent purchases</p>
                    </div>
                </div>
<a href="/order-history" class="text-blue-600 hover:text-blue-500 font-semibold">View Orders →</a>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-heart text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Wishlist</h3>
                        <p class="text-gray-500">Saved items for later</p>
                    </div>
                </div>
<a href="/wishlist" class="text-blue-600 hover:text-blue-500 font-semibold">View Wishlist →</a>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">My Account</h3>
                        <p class="text-gray-500">Update profile and settings</p>
                    </div>
                </div>
<a href="/my-account" class="text-blue-600 hover:text-blue-500 font-semibold">Manage Account →</a>
            </div>
        </div>

        <div class="mt-12 text-center">
            <p class="text-gray-600 text-lg">
                Role: <span class="font-semibold text-blue-600">{{ Auth::user()->getRoleNames()->first() ?? 'customer' }}</span>
            </p>
        </div>
    </div>
</div>
@endsection

