<!-- resources/views/coming-soon.blade.php -->
@extends('layouts.no-header')

@section('title', 'Coming Soon | Carom Studios')

@php
    $siteLogoPath = \App\Models\Setting::get('site_logo_path');
    $logoUrl = $siteLogoPath ? asset('storage/' . $siteLogoPath) : asset('assets/img/footer-logo.svg');
    $launchAt = \App\Models\Setting::get('coming_soon_launch_at') ?: now()->addDays(02)->endOfDay()->toIso8601String();
@endphp

@section('content')
<main class="coming-soon-page bg-[#fbfaf7] text-title dark:bg-title dark:text-white">
    <section class="relative z-10 min-h-screen overflow-hidden pt-8 sm:pt-12">
        <img class="absolute top-0 left-[5%] -z-10 w-[12%] min-w-[70px] max-w-[180px]" src="{{ asset('assets/img/thumb/coming-soon.png') }}" alt="">
        <img class="absolute bottom-0 right-0 -z-10 w-1/4 min-w-[130px] max-w-[360px]" src="{{ asset('assets/img/shape/coming-soon-shape.png') }}" alt="">

        <div class="container">
            <div class="text-center">
                <a href="{{ url('/') }}" class="inline-block" aria-label="Carom Studios home">
                    <img class="mx-auto h-[96px] w-auto max-w-[190px] object-contain sm:h-[128px] sm:max-w-[230px]" src="{{ $logoUrl }}" alt="Carom Studios" onerror="this.onerror=null; this.src='{{ asset('assets/img/footer-logo.svg') }}';">
                </a>
            </div>

            <div class="mx-auto max-w-[920px] py-16 text-center sm:py-24 lg:py-[145px]">
                <p class="text-sm font-bold uppercase tracking-[0.32em] text-[#9b7845]" data-aos="fade-up">New experience loading</p>
                <h1 class="mt-4 text-3xl font-bold leading-none sm:text-5xl md:text-6xl lg:text-7xl" data-aos="fade-up" data-aos-delay="100">
                    We are coming soon
                </h1>
                <p class="mx-auto mt-3 max-w-[560px] text-base leading-7 text-title/70 dark:text-white/70 sm:mt-6 sm:text-lg" data-aos="fade-up" data-aos-delay="200">
                    Our website is getting polished for a better furniture shopping experience. We will be live shortly with fresh collections and smoother browsing.
                </p>

                <div class="countdown-clock mt-10 grid grid-cols-2 items-center justify-center gap-y-8 sm:mt-12 sm:flex sm:gap-8 md:gap-12" data-launch-at="{{ $launchAt }}" id="coming-soon-countdown" data-aos="fade-up" data-aos-delay="300">
                    @foreach([
                        'days' => 'Days',
                        'hours' => 'Hours',
                        'minutes' => 'Minutes',
                        'seconds' => 'Seconds',
                    ] as $key => $label)
                        <div class="countdown-item min-w-[94px] text-center">
                            <div class="ci-inner text-4xl font-[300] leading-none text-title dark:text-white md:text-5xl" data-countdown-unit="{{ $key }}">00</div>
                            <p class="mt-[10px] text-sm leading-none sm:text-lg md:text-xl">{{ $label }}</p>
                        </div>

                        @if(!$loop->last)
                            <div class="hidden sm:block" aria-hidden="true">
                                <svg width="25" height="41" viewBox="0 0 25 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="1.33261" y1="40.0015" x2="24.2798" y2="0.255847" stroke="#BB976D"/>
                                </svg>
                            </div>
                        @endif
                    @endforeach
                </div>

               
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const countdown = document.getElementById('coming-soon-countdown');

    if (!countdown) {
        return;
    }

    const target = new Date(countdown.dataset.launchAt).getTime();
    const units = {
        days: countdown.querySelector('[data-countdown-unit="days"]'),
        hours: countdown.querySelector('[data-countdown-unit="hours"]'),
        minutes: countdown.querySelector('[data-countdown-unit="minutes"]'),
        seconds: countdown.querySelector('[data-countdown-unit="seconds"]'),
    };
    const pad = (value) => String(value).padStart(2, '0');

    function updateCountdown() {
        const distance = Math.max(0, target - Date.now());
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
        const minutes = Math.floor((distance / (1000 * 60)) % 60);
        const seconds = Math.floor((distance / 1000) % 60);

        units.days.textContent = pad(days);
        units.hours.textContent = pad(hours);
        units.minutes.textContent = pad(minutes);
        units.seconds.textContent = pad(seconds);
    }

    updateCountdown();
    window.setInterval(updateCountdown, 1000);
});
</script>


@endsection
