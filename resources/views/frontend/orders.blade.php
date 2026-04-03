@extends('layouts.main')

@section('title', 'My Orders')

@section('content')
<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">My Orders</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-primary text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Orders</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Orders Table Start -->
<div class="s-py-100" data-aos="fade-up">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <!-- Sidebar Nav (same as my-account) -->
            <div class="flex items-start gap-8 md:gap-12 2xl:gap-24 flex-col md:flex-row my-profile-navtab mb-10">
                <div class="w-full md:w-[200px] lg:w-[300px] flex-none">
                    <ul class="divide-y dark:divide-paragraph text-title dark:text-white text-base sm:text-lg lg:text-xl flex flex-col justify-center leading-none">
                        <li class="pb-3 lg:pb-6 pl-6 lg:pl-12">
                            <a class="duration-300 hover:text-primary" href="{{ url('/my-account') }}">My Account</a>
                        </li>
                        <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                            <a class="duration-300 hover:text-primary" href="{{ url('/order-history') }}">Order History</a>
                        </li>
                        <li class="active text-primary py-3 lg:py-6 pl-6 lg:pl-12">
                            <a class="duration-300 hover:text-primary" href="{{ route('frontend.account.orders') }}">Orders</a>
                        </li>
                        <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                            <a class="duration-300 hover:text-primary" href="{{ url('/wishlist') }}">Wishlist</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-8 lg:p-[50px] order-history-table">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-semibold text-title dark:text-white">All Orders ({{ $orders->total() ?? 0 }})</h3>
                    {{ $orders->links() ?? '' }}
                </div>

                @if(isset($orders) && $orders->count() > 0)
                <ul class="order-history">
                    <!-- Table Heading -->
                    <li class="title flex items-center justify-between gap-5 pb-[10px] sm:pb-5 border-b border-bdr-clr dark:border-bdr-clr-drk">
                        <span class="cart-product-title text-lg md:text-xl font-semibold leading-none text-title dark:text-white block w-[270px] sm:w-[310px] xl:w-[330px]">Order Details</span>
                        <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[80px]">Date</span>
                        <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[80px]">Total</span>
                        <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[100px]">Status</span>
                        <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[100px]">Action</span>
                    </li>
                    @foreach($orders as $order)
                    @php
                    $items = is_array($order->items) ? $order->items : json_decode($order->items ?? '[]', true);
                    $firstItem = $items[0] ?? [];
                    $statusClass = match($order->status) {
                    \App\Enums\OrderStatus::PENDING => '#EC991D',
                    \App\Enums\OrderStatus::PROCESSING => '#EC991D',
                    \App\Enums\OrderStatus::SHIPPED => '#007BFF',
                    \App\Enums\OrderStatus::DELIVERED => '#31A051',
                    \App\Enums\OrderStatus::CANCELLED => '#E13939',
                    \App\Enums\OrderStatus::RETURNED => '#E13939',
                    \App\Enums\OrderStatus::REFUNDED => '#E13939',
                    default => '#31A051'
                    };
                    @endphp
                    <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk hover:bg-white dark:hover:bg-dark-secondary">
                        <div class="flex items-center gap-3 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                            <div class="w-16 sm:w-[90px] flex-none">
                                <img src="{{ image_url($firstItem['image'] ?? null) }}" alt="{{ $firstItem['name'] ?? 'Order Item' }}">
                            </div>
                            <div class="flex-1">
                                <span class="text-[15px] font-medium leading-none block text-title dark:text-white mb-1">Order #{{ $order->order_number }}</span>
                                <h5 class="font-semibold leading-none text-xl"><a href="#" class="hover:text-primary">{{ $firstItem['name'] ?? 'Multiple Items' }}</a></h5>
                            </div>
                        </div>
                        <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold w-[80px]">{{ $order->created_at->format('M d, Y') }}</span>
                        <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold w-[80px]">{{ currency($order->total_amount) }}</span>
                        <div class="w-[100px]">
                            <span style="background-color: {{ $statusClass }};" class="py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded inline-block">
                                {{ $order->status?->label() ?? 'Pending' }}
                            </span>
                        </div>
                        <div class="w-[100px]">
                            <a href="#" class="text-primary hover:underline font-medium">View Details</a>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
                @else
                <div class="text-center py-20">
                    <p class="text-title dark:text-white py-8 text-xl">No orders found.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">Continue Shopping</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
