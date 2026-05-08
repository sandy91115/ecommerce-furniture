<!-- resources/views/payment-confirmation.blade.php -->
@extends('layouts.main')

@section('title', 'Payment Confirmation')

@section('content')
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Payment Confirmation</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Payment</li>
        </ul>
    </div>
</div>

<div class="s-py-100">
    <div class="container">
        <div class="max-w-[800px] mx-auto bg-[#FAFAFA] dark:bg-dark-secondary border border-[#17243026] rounded-xl p-6 md:p-10">
            <h3 class="font-semibold leading-none text-2xl md:text-3xl mb-5 dark:text-white">
                Secure Payment Pending
            </h3>

            @if(isset($order) && $order)
                <p class="text-base sm:text-lg text-paragraph dark:text-white mb-8">
                    Your order has been created. Complete payment through {{ strtoupper($order->payment_gateway ?? 'gateway') }} to confirm it.
                </p>

                <div class="grid sm:grid-cols-2 gap-4 text-title dark:text-white">
                    <div class="border border-bdr-clr dark:border-bdr-clr-drk rounded-lg p-4">
                        <span class="text-sm text-paragraph dark:text-white block mb-2">Order Number</span>
                        <strong>{{ $order->order_number }}</strong>
                    </div>
                    <div class="border border-bdr-clr dark:border-bdr-clr-drk rounded-lg p-4">
                        <span class="text-sm text-paragraph dark:text-white block mb-2">Amount</span>
                        <strong>{{ currency($order->total_amount) }}</strong>
                    </div>
                    <div class="border border-bdr-clr dark:border-bdr-clr-drk rounded-lg p-4">
                        <span class="text-sm text-paragraph dark:text-white block mb-2">Payment Status</span>
                        <strong>{{ ucfirst($order->payment_status) }}</strong>
                    </div>
                    <div class="border border-bdr-clr dark:border-bdr-clr-drk rounded-lg p-4">
                        <span class="text-sm text-paragraph dark:text-white block mb-2">Gateway</span>
                        <strong>{{ strtoupper($order->payment_gateway ?? 'COD') }}</strong>
                    </div>
                </div>

                <div class="mt-8 rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800">
                    Gateway credentials are now controlled from Admin > Payment Methods. Connect the provider callback/webhook there before taking live payments.
                </div>

                <div class="flex gap-3 flex-wrap mt-8">
                    <a href="{{ route('frontend.checkout') }}" class="btn btn-outline" data-text="Back to Checkout">
                        <span>Back to Checkout</span>
                    </a>
                    <a href="{{ route('order-history') }}" class="btn btn-solid" data-text="My Orders">
                        <span>My Orders</span>
                    </a>
                </div>
            @else
                <p class="text-base sm:text-lg text-paragraph dark:text-white">
                    No payment is waiting for confirmation right now.
                </p>
                <div class="mt-8">
                    <a href="{{ route('shop') }}" class="btn btn-solid" data-text="Continue Shopping">
                        <span>Continue Shopping</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@include('includes.footer')
@endsection
