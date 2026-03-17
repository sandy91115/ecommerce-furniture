<!-- resources/views/checkout.blade.php -->
@extends('layouts.main')

@section('title', 'Checkout Page')

@section('content')



<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center text-2xl">Checkout</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4 flex-wrap">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Checkout</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Checkout Area Start -->
<div class="s-py-100">
    <div class="container">
    <div class="max-w-[1220px] mx-auto grid lg:grid-cols-2 gap-[30px] lg:gap-[70px]">
        <div class="bg-[#FAFAFA] dark:bg-dark-secondary p-[30px] md:p-[40px] lg:p-[50px] border border-[#17243026] border-opacity-15 rounded-xl" data-aos="fade-up">
            
            <p class='mb-5 w-full bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300 whitespace-normal'>Are you missing your coupon code ? 
            <button class='ml-1 add-coupon-code underline text-[#209A60]'> Click here to add</button>
            </p>
            
            <div class="coupon-wrapper gap-3 flex-wrap mb-[30px] hidden">
                <input
                    class="max-w-[220px] w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                    type="text"
                    placeholder="Coupon code"
                >
                <a href="#" class="btn btn-sm-px btn-theme-solid " data-text="Apply coupon">
                    <span>Apply coupon</span>
                </a>
            </div>

            <script>
                const addCouponCode = document.querySelector('.add-coupon-code')
                const couponWrapper = document.querySelector('.coupon-wrapper')
                addCouponCode.addEventListener('click', () => {
                    couponWrapper.classList.toggle('hidden');
                    couponWrapper.classList.toggle('flex');
                })
            </script>
            <h4 class="font-semibold leading-none text-xl md:text-2xl mb-6 md:mb-[30px]">
                Billing Information
            </h4>
            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="grid gap-5 md:gap-6">
                    <div class="grid md:grid-cols-2 gap-5 md:gap-6">
                        <div>
                            <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                                First Name *
                            </label>
                            <input
                                name="first_name"
                                value="{{ Auth::user()->name }}"
                                required
                                class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                                type="text"
                                placeholder="First Name"
                            >
                        </div>
                        <div>
                            <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                                Last Name *
                            </label>
                            <input
                                name="last_name"
                                required
                                class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                                type="text"
                                placeholder="Last Name"
                            >
                        </div>
                    </div>
                    <div>
                        <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                            Email *
                        </label>
                        <input
                            name="email"
                            value="{{ Auth::user()->email }}"
                            required
                            class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                            type="email"
                            placeholder="Enter your email address"
                        >
                    </div>
                    <div>
                        <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                            Phone No. *
                        </label>
                        <input
                            name="phone"
                            required
                            class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                            type="text"
                            placeholder="Type your phone number"
                        >
                    </div>
                    <div class="grid md:grid-cols-2 gap-5 md:gap-6">
                        <div>
                            <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                            Town / City *
                            </label>
                            <input
                                name="city"
                                required
                                class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                                type="text"
                                placeholder="City"
                            >
                        </div>
                        <div>
                            <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                            Zip Code *
                            </label>
                            <input
                                name="zipcode"
                                required
                                class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                                type="text"
                                placeholder="1217"
                            >
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-5 md:gap-6">
                        <div>
                            <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                            State *
                            </label>
                            <input
                                name="state"
                                required
                                class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                                type="text"
                                placeholder="State"
                            >
                        </div>
                        <div>
                            <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                            Country *
                            </label>
                            <input
                                name="country"
                                required
                                class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                                type="text"
                                placeholder="Country"
                            >
                        </div>
                    </div>
                    <div>
                        <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                            Address *
                        </label>
                        <input
                            name="address"
                            required
                            class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                            type="text"
                            placeholder="Your full address"
                        >
                    </div>
                    <div>
                        <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">
                            Additional Text (Optional)
                        </label>
                        <textarea name="notes" class="w-full h-[120px] bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" placeholder="Type your message"></textarea>
                    </div>
                </div>
                
                <input type="checkbox" name="terms" checked class="hidden">
            </form>
        </div>

        <div data-aos="fade-up" data-aos-delay={200}>
            <div class="bg-[#FAFAFA] dark:bg-dark-secondary pt-[30px] md:pt-[40px] lg:pt-[50px] px-[30px] md:px-[40px] lg:px-[50px] pb-[30px] border border-[#17243026] border-opacity-15 rounded-xl">   
                <h4 class="font-semibold leading-none text-xl md:text-2xl mb-6 md:mb-10">
                    Product Information
                </h4>
                <div class="grid gap-5 mg:gap-6">
                    @foreach($cartItems as $item)
                    <div class="flex items-center justify-between gap-5">
                        <div class="flex items-center gap-3 md:gap-4 lg:gap-6 cart-product flex-wrap">
                            <div class="w-16 sm:w-[70px] flex-none">
                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('assets/img/product/default.jpg') }}" alt="{{ $item['name'] }}" class="w-16 sm:w-[70px] flex-none" onerror="this.onerror=null; this.src='{{ asset('assets/img/product/default.jpg') }}'">
                            </div>
                            <div class="flex-1">
                                <h6 class="leading-none font-medium text-lg">{{ $item['category'] ?? 'Product' }}</h6>
                                <h5 class="font-semibold leading-none mt-2 text-xl">
                                    <a href="{{ route('product-details', $item['slug']) }}">{{ $item['name'] }}</a>
                                </h5>
                                <p class="text-sm mt-1">Qty: {{ $item['quantity'] }}</p>
                                @if(isset($item['variations']) && !empty($item['variations']))
                                    <p class="text-xs text-gray-500">
                                        @foreach($item['variations'] as $label => $value)
                                            {{ $label }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </p>
                                @endif
                            </div>
                        </div>
                        <h6 class="leading-none text-lg font-bold">${{ number_format($item['price'] * $item['quantity'], 2) }}</h6>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-6 border-t border-bdr-clr dark:border-bdr-clr-drk text-right flex justify-end flex-col w-full ml-auto mr-0">
                    <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium">
                        <span>Sub Total:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium mt-3">
                        <span>Shipping:</span>
                        <span>Free</span>
                    </div>
                    <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium mt-3">
                        <span>Tax (10%):</span>
                        <span> ${{ number_format($tax, 2) }}</span>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-bdr-clr dark:border-bdr-clr-drk">
                    <div class="flex justify-between flex-wrap font-semibold leading-none text-2xl md:text-3xl">
                        <span>Total:</span>
                        <span>&nbsp;${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div> 
            <div class="mt-7 md:mt-12">
                <h4 class="font-semibold leading-none text-xl md:text-2xl mb-6 md:mb-10">Payment Method</h4>
                <div class="flex gap-5 sm:gap-8 md:gap-12 flex-wrap">
                    <div>
                        <label class="flex items-center gap-[10px] categoryies-iteem">
                            <input class="appearance-none hidden" type="radio" name="item-type">
                            <span class="w-4 h-4 rounded-full border border-title dark:border-white flex items-center justify-center duration-300">
                                <svg class="duration-300 opacity-0" width="8" height="8" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="10" height="10" rx="5" fill="#BB976D"/>
                                </svg>
                            </span>
                            <span class="sm:text-lg text-title dark:text-white block sm:leading-none transform translate-y-[3px] select-none">Cash On Delivery</span>
                        </label>
                        <p class="ml-6 text-[15px] leading-none mt-2">Time ( 07 - 10 ) Days</p>
                    </div>
                    <div>
                        <label class="flex items-center gap-[10px] categoryies-iteem">
                            <input class="appearance-none hidden" type="radio" name="item-type">
                            <span class="w-4 h-4 rounded-full border border-title dark:border-white flex items-center justify-center duration-300">
                                <svg class="duration-300 opacity-0" width="8" height="8" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="10" height="10" rx="5" fill="#BB976D"/>
                                </svg>
                            </span>
                            <span class="sm:text-lg text-title dark:text-white block sm:leading-none transform translate-y-[3px] select-none">Debit / Credit Card</span>
                        </label>
                        <p class="ml-6 text-[15px] leading-none mt-2">Time ( 07 - 10 ) Days</p>
                    </div>
                </div>
                <div class="mt-6 sm:mt-8 md:mt-10">
                    <label class="flex items-center gap-2 iam-agree">
                        <input class="appearance-none hidden" type="checkbox" name="categories">
                        <span class="w-6 h-6 rounded-[5px] border-2 border-title dark:border-white flex items-center justify-center duration-300">
                            <svg  class="duration-300 opacity-0 text-title dark:text-white fill-current" width="15" height="12" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3819 0.742676L6.10461 11.8998L2.25731 8.06381L0.763672 9.55745L6.20645 15.0002L20 2.32686L18.3819 0.742676Z"/>
                            </svg>
                        </span>
                        <span class="text-base sm:text-lg text-title dark:text-white leading-none sm:leading-none select-none inline-block transform translate-y-[3px]">I Agree all terms & Conditions</span> 
                    </label>
                </div>
                <div class="mt-4 md:mt-6 flex flex-wrap gap-3">
                    <a href="{{ url('/cart') }}" class="btn btn-outline" data-text="Back to Cart">
                        <span>Back to Cart</span>
                    </a>
                    <button type="button" id="place-order-btn" class="btn btn-theme-solid" data-text="Place Order">
                        <span>Place Order</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const placeOrderBtn = document.getElementById('place-order-btn');
    const checkoutForm = document.getElementById('checkout-form');

    if (placeOrderBtn && checkoutForm) {
        placeOrderBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Basic validation
            const requiredFields = checkoutForm.querySelectorAll('[required]');
            let isValid = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                alert('Please fill in all required fields.');
                return;
            }

            placeOrderBtn.disabled = true;
            placeOrderBtn.querySelector('span').innerText = 'Processing...';

            const formData = new FormData(checkoutForm);
            
            fetch("{{ route('checkout.process') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Something went wrong. Please try again.');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.querySelector('span').innerText = 'Place Order';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                placeOrderBtn.disabled = false;
                placeOrderBtn.querySelector('span').innerText = 'Place Order';
            });
        });
    }
});
</script>
<!-- Checkout Area End -->
   
@include('includes.footer6')
  
@endsection