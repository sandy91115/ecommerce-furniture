<!-- resources/views/payment-confirmation.blade.php -->
@extends('layouts.main')

<<<<<<< HEAD
@section('title', 'Payment Confirmation')

@section('content')
=======
@section('title', 'Payment-Confirmation Page')

@section('content')



<!-- Banner Start -->
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD

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
=======
<!-- Banner End -->

<!-- Payment confirmation Start -->
<div class="s-py-100">
    <div class="container">
        <div class="max-w-[800px] mx-auto">
            <h3 class="font-semibold leading-none text-2xl md:text-3xl mb-[30px]">
                Confirm Your Payment
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border-t border-t-dashed border-t-title pt-[30px] pb-[30px] aos-init aos-animate payment-confirmation-meta-table block border-b border-dashed border-title dark:border-white aos-init aos-animate">
                    <tbody class="w-full">
                        <tr class="first-th-item pb-4 text-base sm:text-lg text-paragraph dark:text-white">
                            <th class="text-base sm:text-lg font-normal">Date</th>
                            <th class='text-base sm:text-lg font-normal'>Card Holder</th>
                            <th class='text-base sm:text-lg font-normal'>Card Type</th>
                        </tr>
                        <tr class="first-td-item pb-12 text-lg sm:text-xl text-title dark:text-white font-medium">
                            <td>12/31/2025</td>
                            <td>John Smith Doe</td>
                            <td>Visa</td>
                        </tr>
                        <tr class="two-th-item pb-4 text-base sm:text-lg font-normal text-paragraph dark:text-white">
                            <th class="text-base sm:text-lg font-normal">Card Number</th>
                            <td class="text-base sm:text-lg font-normal">eMail</td>
                            <td class="text-base sm:text-lg font-normal">Phone</td>
                        </tr>
                        <tr class="two-td-item text-lg sm:text-xl text-title dark:text-white font-medium">
                            <th class='font-medium'>**** **** **** 1234</th>
                            <td class='font-medium'>demomail@gmail.com</td>
                            <td class='font-medium'>(+11) 01234 56789</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center flex-wrap gap-4 mt-[30px]">
                <h4 class="text-xl font-medium leading-none mb-0">
                    Subtotal : $850
                </h4>
                <div class="flex gap-3">
                    <a href="#" class="btn btn-secondory-solid hover:!border-[#bb976d] hover:dark:border-[#bb976d]" data-text="Cancel Payment">
                        <span>Cancel Payment</span>
                    </a>
                    <button class="btn btn-solid" data-text="Confirm Payment">
                        <span>Confirm Payment</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Payment confirmation End -->

@include('includes.footer')
  
@endsection
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
