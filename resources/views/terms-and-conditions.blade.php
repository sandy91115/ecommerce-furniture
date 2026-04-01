<!-- resources/views/terms-and-conditions.blade.php -->
@extends('layouts.main')

@section('title', 'Terms-And-Conditions ')

@section('content')



<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Terms & Conditions</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Terms & Conditions</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Condition Area Start -->
<div class="s-py-100">
    <div class="container">
        <div class="max-w-[940px] mx-auto" data-aos="fade-up">
            <article class="prose prose-h3:!text-3xl prose-h4:!text-2xl sm:prose-lg dark:prose-p:text-white-light dark:prose-li:text-white-light max-w-full">

                <h3>1. Shipping & Delivery</h3>
                <p>We process all furniture orders within 2–5 business days. Delivery timelines vary depending on your location, product availability, and shipping method selected at checkout.</p>
                <ul>
                    <li>Metro cities: 3–7 business days</li>
                    <li>Non-metro areas: 5–12 business days</li>
                    <li>Custom or made-to-order furniture: 10–25 business days</li>
                </ul>
                <p>Once shipped, you will receive tracking details via email or SMS. Delays due to logistics partners, weather, or unforeseen circumstances are beyond our control.</p>

                <h3>2. Returns & Refunds</h3>
                <p>We accept returns under the following conditions:</p>
                <ul>
                    <li>Item is damaged, defective, or incorrect at the time of delivery</li>
                    <li>Return request is raised within 48 hours of delivery</li>
                    <li>Product is unused and in original packaging</li>
                </ul>
                <p>Refunds are processed within 5–10 business days after inspection. Customized or made-to-order furniture is non-returnable unless damaged or defective.</p>

                <h3>3. Order Cancellation</h3>
                <p>Orders can be cancelled before they are shipped.</p>
                <ul>
                    <li>Full refund if cancelled within 24 hours of placing the order</li>
                    <li>No cancellation allowed once the order is dispatched</li>
                    <li>Custom furniture orders cannot be cancelled once production begins</li>
                </ul>

                <h3>4. Payments</h3>
                <p>We accept secure payments through trusted payment gateways. Available methods include:</p>
                <ul>
                    <li>Credit/Debit Cards</li>
                    <li>UPI & Net Banking</li>
                    <li>Wallets and EMI options (if applicable)</li>
                </ul>
                <p>All transactions are encrypted. We do not store your payment details.</p>

                <h3>5. Warranty</h3>
                <p>We offer a limited warranty on selected furniture items:</p>
                <ul>
                    <li>6–24 months warranty depending on product category</li>
                    <li>Covers manufacturing defects only</li>
                    <li>Does not cover wear & tear, misuse, or accidental damage</li>
                </ul>

                <h3>6. Product Information</h3>
                <p>We strive to display accurate product descriptions, dimensions, and colors. However:</p>
                <ul>
                    <li>Slight variations may occur due to lighting and screen differences</li>
                    <li>Handcrafted furniture may have minor natural imperfections</li>
                </ul>

                <h3>7. User Responsibilities</h3>
                <p>By using our website, you agree to:</p>
                <ul>
                    <li>Provide accurate shipping and contact information</li>
                    <li>Use the website for lawful purposes only</li>
                    <li>Not misuse or attempt to harm the platform</li>
                </ul>

                <h3>8. Limitation of Liability</h3>
                <p>We are not liable for indirect, incidental, or consequential damages arising from the use of our products or website.</p>

                <h3>9. Changes to Terms</h3>
                <p>We reserve the right to update these Terms & Conditions at any time. Changes will be effective immediately upon posting.</p>

                <h3>10. Contact Us</h3>
                <p>If you have any questions regarding these Terms & Conditions, please contact our support team at:</p>
                <ul>
                    <li>Email: caromstudios@gmail.com</li>
                    <li>Phone: +91 9205100855 | +91 9205191155</li>
                </ul>

            </article>
        </div>
    </div>
</div>
<!-- Condition Area End -->

@include('includes.footer')

@endsection