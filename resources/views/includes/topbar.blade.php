
{{-- Topbar --}}
<div class="topbar-area bg-primary text-white py-3 hidden lg:block">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between text-sm">
            <!-- Left: Contact -->
            <div class="flex items-center space-x-6">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                    <span>Call: {{ \App\Models\Setting::where('key', 'contact_phone')->first()?->value ?? '+1 (555) 123-4567' }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg>
                    <span>{{ \App\Models\Setting::where('key', 'contact_email')->first()?->value ?? 'hello@furniture.com' }}</span>
                </div>
            </div>
            
            <!-- Right: Promos/Social -->
            <div class="flex items-center space-x-4">
                @php $promo = \App\Models\Setting::where('key', 'topbar_promo')->first()?->value ?? 'Free Shipping on Orders $99+'; @endphp
                <span class="font-medium">{{ $promo }}</span>
                {{-- Social Icons --}}
                <div class="flex space-x-2">
                    <a href="#" class="w-8 h-8 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all">
                        <i class="fab fa-twitter text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

