@extends('layouts.main')

@section('title', 'Cart Page')

@section('content')



<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center text-2xl">Cart</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Cart</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Cart Area Start -->
<div class="s-py-100" data-aos="fade-up">
    <div class="container ">
        <div class="flex xl:flex-row flex-col gap-[30px] lg:gap-[30px] xl:gap-[70px]">
            <div class="flex-1 overflow-hidden">
                <table id="cart-table" class="responsive nowrap table-wrapper" style="width:100%">
                    <thead class="table-header">
                        <tr>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Product Info</th>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Price</th>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Quantity</th>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Total</th>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Remove</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($cart as $id => $item)
                        <tr>
                            <td class="md:w-[42%]">
                                <div class="flex items-center gap-3 md:gap-4 lg:gap-6 cart-product">
                                    <div class="w-14 sm:w-20 flex-none">
                                        <img src="{{ isset($item['image']) && $item['image'] ? asset('storage/' . $item['image']) : asset('assets/img/product/default.jpg') }}" alt="{{ $item['name'] }}" onerror="this.src='{{ asset('assets/img/product/default.jpg') }}';">
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="leading-none font-medium text-lg">{{ $item['category'] ?? 'Product' }}</h6>
                                        <h5 class="font-semibold leading-none mt-2 text-xl">
                                            <a href="{{ route('product-details', $item['slug']) }}">{{ $item['name'] }}</a>
                                        </h5>
                                        @if(isset($item['variations']) && !empty($item['variations']))
                                            <p class="text-xs text-gray-500 mt-1">
                                                @foreach($item['variations'] as $label => $value)
                                                    {{ $label }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h6 class="text-base md:text-lg leading-none text-title dark:text-white font-semibold">${{ number_format($item['price'], 2) }}</h6>
                            </td>
                            <td>
                                <div class="inc-dec flex items-center gap-2">
                                    <button type="button" onclick="updateQuantity('{{ $id }}', -1)" class="dec w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                                        <svg class="fill-current text-title dark:text-white" width="14" height="2" viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10.4361 0.203613H12.0736L7.81774 0.203615H13.8729V1.80309H7.81774L3.50809 1.80309H1.87053L6.18017 1.80309H0.125V0.203615H6.18017L10.4361 0.203613Z"/>
                                        </svg>
                                    </button>
                                    <input class="w-6 h-auto outline-none bg-transparent text-base mg:text-lg leading-none text-title dark:text-white text-center" type="text" value="{{ $item['quantity'] }}" readonly>
                                    <button type="button" onclick="updateQuantity('{{ $id }}', 1)" class="inc w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                                        <svg class="fill-current text-title dark:text-white" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.18017 0.110352H7.81774V6.16553H13.8729V7.76501H7.81774V13.8963H6.18017V7.76501H0.125V6.16553H6.18017V0.110352Z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <h6 class="text-base md:text-lg leading-none text-title dark:text-white font-semibold">${{ number_format($item['price'] * $item['quantity'], 2) }}</h6>
                            </td>
                            <td>
                                <button type="button" onclick="removeFromCart('{{ $id }}')" class="w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center ml-auto duration-300 text-title dark:text-white">
                                    <svg class="fill-current " width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-10">
                                <p class="text-lg">Your cart is empty</p>
                                <a href="{{ route('shop') }}" class="btn btn-theme-solid mt-4" data-text="Start Shopping"><span>Start Shopping</span></a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="lg:w-[350px] xl:w-[400px]">
                <div class="bg-[#FAFAFA] dark:bg-dark-secondary pt-[30px] md:pt-[40px] px-[30px] md:px-[40px] pb-[30px] border border-[#17243026] border-opacity-15 rounded-xl">   
                    <h4 class="text-xl font-semibold mb-6">Cart Totals</h4>
                    <div class="text-right flex justify-end flex-col w-full ml-auto mr-0">
                        <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium">
                            <span>Sub Total:</span>
                            <span id="cart-subtotal">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium mt-3">
                            <span>Tax (10%):</span>
                            <span id="cart-tax">${{ number_format($tax, 2) }}</span>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-bdr-clr dark:border-bdr-clr-drk">
                        <div class="flex justify-between flex-wrap font-semibold leading-none text-2xl md:text-3xl">
                            <span>Total:</span>
                            <span id="cart-total">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col gap-4">
                       
                        <a href="{{ url('/shop') }}" class="btn btn-outline w-full text-center">
                            <span>Continue Shopping</span>
                        </a>
                         <a href="{{ url('/checkout') }}" class="btn btn-theme-solid w-full text-center" data-text="Proceed to Checkout">
                            <span>Proceed to Checkout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>


        </div>    
    </div>
</div>
<!-- Cart Area End -->
   
@include('includes.footer')
  
@endsection
