<!-- resources/views/return-policy.blade.php -->
@extends('layouts.main')

@section('title', 'Return Policy')

@section('content')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Return Policy</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Return Policy</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Return Policy Area Start -->
<div class="s-py-100">
    <div class="container">
        <div class="max-w-[940px] mx-auto" data-aos="fade-up">
            <article class="prose prose-h3:!text-3xl prose-h4:!text-2xl sm:prose-lg dark:prose-p:text-white-light dark:prose-li:text-white-light max-w-full">

                <h3>1. Eligibility for Returns</h3>
                <p>We want you to be completely satisfied with your furniture purchase. You may return eligible items within <strong>7 days</strong> of delivery.</p>
                <ul>
                    <li>Item must be unused and in original packaging</li>
                    <li>Product and packaging must be undamaged</li>
                    <li>All accessories and documentation included</li>
                    <li>Not applicable to customized or made-to-order furniture</li>
                </ul>

                <h3>2. Non-Returnable Items</h3>
                <p>The following items cannot be returned:</p>
                <ul>
                    <li>Custom-made or personalized furniture</li>
                    <li>Sale or clearance items</li>
                    <li>Perishable items or those with expiry dates</li>
                    <li>Damaged by improper handling during customer unboxing</li>
                    <li>Furniture showing signs of use or assembly</li>
                </ul>

                <h3>3. Return Process</h3>
                <ol>
                    <li>Contact our support team within 48 hours of delivery via email (caromstudios@gmail.com) or phone (+91 9205100855)</li>
                    <li>Provide order number, product details, and photos of the issue/packaging</li>
                    <li>Receive return authorization and shipping instructions</li>
                    <li>Pack item securely in original packaging</li>
                    <li>Ship back using our provided label (prepaid for eligible returns)</li>
                </ol>

                <h3>4. Refunds & Credits</h3>
                <p>Once we receive and inspect your return (2-5 business days):</p>
                <ul>
                    <li>Full refund to original payment method (5-10 business days)</li>
                    <li>Store credit issued immediately</li>
                    <li>Refund excludes original shipping charges</li>
                    <li>Customer responsible for return shipping unless defective</li>
                </ul>

                <h3>5. Damaged or Defective Items</h3>
                <p>For damaged/defective items upon delivery:</p>
                <ul>
                    <li><strong>Inspect immediately upon receipt</strong> and report within 24 hours</li>
                    <li>Provide clear photos of damage and packaging</li>
                    <li>Free replacement or full refund (your choice)</li>
                    <li>No return shipping charges</li>
                </ul>

                <h3>6. Large Furniture Returns</h3>
                <p>Due to size and logistics:</p>
                <ul>
                    <li>Return pickup arranged at scheduled time</li>
                    <li>Customer must ensure access for pickup crew</li>
                    <li>Restocking fee may apply (5-15% for standard items)</li>
                </ul>

                <h3>7. International Returns</h3>
                <p>Customer responsible for all shipping costs and customs duties.</p>

                <h3>8. Warranty vs Returns</h3>
                <p>Returns are separate from our manufacturing warranty. Warranty covers defects after 7-day return window.</p>

                <h3>9. Contact Support</h3>
                <p>Questions? Contact us anytime:</p>
                <ul>
                    <li><strong>Email:</strong> caromstudios@gmail.com</li>
                    <li><strong>Phone:</strong> +91 9205100855 | 9205191155</li>
                    <li><strong>Hours:</strong> Mon-Sat, 10AM-7PM IST</li>
                </ul>

            </article>
        </div>
    </div>
</div>
<!-- Return Policy Area End -->

@include('includes.footer')

@endsection

