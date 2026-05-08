@extends('layouts.main')

@section('title', 'Login | Furniture Store')

@section('content')
<link rel="stylesheet" href="{{ versioned_asset('assets/css/login.css') }}">

@php
    $showcaseFeatures = [
        [
            'title' => 'Pick up where you left off',
            'copy' => 'Reopen your saved wishlist, recent orders and curated furniture choices in one place.',
        ],
        [
            'title' => 'Fast checkout flow',
            'copy' => 'Stay signed in on your device and move from inspiration to purchase without friction.',
        ],
        [
            'title' => 'Track orders with ease',
            'copy' => 'Manage purchases, quotations and account details from a clean customer dashboard.',
        ],
    ];
@endphp

<section class="auth-page auth-page--login">
    <div class="auth-shell">
        <aside class="auth-showcase" style="--auth-showcase-image: url('{{ asset('assets/img/bg/login.jpg') }}');">
            <div class="auth-showcase__content">
                <span class="auth-showcase__eyebrow">Member Access</span>
                <h1 class="auth-showcase__title">Welcome back to your furniture space.</h1>
                <p class="auth-showcase__copy">
                    Sign in to continue exploring premium pieces, manage your orders and keep your shortlist ready for checkout.
                </p>

                <div class="auth-showcase__list">
                    @foreach ($showcaseFeatures as $feature)
                        <article class="auth-showcase__feature">
                            <span class="auth-showcase__feature-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <h3>{{ $feature['title'] }}</h3>
                                <p>{{ $feature['copy'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </aside>

        <div class="auth-panel">
            <div class="auth-panel__body">
                <span class="auth-panel__eyebrow">Sign In</span>
                <h2 class="auth-panel__title">Access your account</h2>
                <p class="auth-panel__copy">
                    Use your email and password to continue shopping, save favorites and stay connected with your latest orders.
                </p>

                <form method="POST" action="{{ route('login.action') }}" class="auth-form">
                    @csrf

                    <div class="auth-field">
                        <label class="auth-label" for="email">Email address</label>
                        <div class="auth-input-wrap">
                            <span class="auth-input-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M4 7.5 12 13l8-5.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                    <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </span>
                            <input
                                class="auth-input @error('email') is-invalid @enderror"
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                placeholder="Enter your email address"
                                required
                            >
                        </div>
                        @error('email')
                            <p class="auth-feedback auth-feedback--error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label class="auth-label" for="password">Password</label>
                        <div class="auth-input-wrap">
                            <span class="auth-input-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7.5 10V8a4.5 4.5 0 1 1 9 0v2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                    <rect x="4" y="10" width="16" height="10" rx="3" stroke="currentColor" stroke-width="1.8"/>
                                    <circle cx="12" cy="15" r="1.3" fill="currentColor"/>
                                </svg>
                            </span>
                            <input
                                class="auth-input @error('password') is-invalid @enderror"
                                type="password"
                                id="password"
                                name="password"
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                required
                            >
                            <button type="button" class="auth-toggle" data-toggle-password="password" aria-label="Show password">
                                <svg class="auth-toggle__icon auth-toggle__icon--show" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                                <svg class="auth-toggle__icon auth-toggle__icon--hide" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M4 4 20 20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M10.6 6.2A10.9 10.9 0 0 1 12 6c6.5 0 10 6 10 6a18.6 18.6 0 0 1-3.3 4.2M6.6 8.1C3.9 10 2 12 2 12s3.5 6 10 6c1.7 0 3.2-.4 4.5-1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.9 10a3 3 0 0 0 4.1 4.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="auth-feedback auth-feedback--error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="auth-meta">
                        <label class="auth-check" for="remember">
                            <input type="checkbox" id="remember" name="remember">
                            <span>Remember me on this device</span>
                        </label>
                        <span class="auth-meta__hint">Secure sign in for orders and wishlist access.</span>
                    </div>

                    <button type="submit" class="auth-submit">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12h13" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                            <path d="m13 8 4 4-4 4" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                        </svg>
                        Sign in
                    </button>

                    <p class="auth-note">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3 4 7v5c0 5 3.4 8.6 8 10 4.6-1.4 8-5 8-10V7l-8-4Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                        </svg>
                        Your customer session stays protected while you browse, save favorites and complete checkout.
                    </p>

                    <p class="auth-footnote">
                        New here? <a href="{{ route('register') }}" class="auth-link">Create your account</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-toggle-password]').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const input = document.getElementById(toggle.getAttribute('data-toggle-password'));

            if (!input) {
                return;
            }

            const isVisible = input.type === 'text';
            input.type = isVisible ? 'password' : 'text';
            toggle.classList.toggle('is-active', !isVisible);
            toggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
        });
    });
});
</script>
@endsection

