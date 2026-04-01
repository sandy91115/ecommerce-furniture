@extends('layouts.main')

@section('title', 'Register | Furniture Store')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

@php
    $showcaseFeatures = [
        [
            'title' => 'Save the products you love',
            'copy' => 'Build a personal wishlist and revisit premium pieces whenever inspiration strikes.',
        ],
        [
            'title' => 'Checkout with less friction',
            'copy' => 'Create one account to move through purchases, inquiries and account updates more smoothly.',
        ],
        [
            'title' => 'Keep everything in one place',
            'copy' => 'Track orders, manage profile details and stay connected to your furniture journey.',
        ],
    ];
@endphp

<section class="auth-page">
    <div class="auth-shell">
        <aside class="auth-showcase" style="--auth-showcase-image: url('{{ asset('assets/img/bg/register.jpg') }}');">
            <div class="auth-showcase__content">
                <span class="auth-showcase__eyebrow">Create Profile</span>
                <h1 class="auth-showcase__title">Start a smoother furniture shopping journey.</h1>
                <p class="auth-showcase__copy">
                    Create your account to save favorites, manage orders and enjoy a polished shopping experience that matches the rest of the store.
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
                <span class="auth-panel__eyebrow">Register</span>
                <h2 class="auth-panel__title">Create your account</h2>
                <p class="auth-panel__copy">
                    Join today to store your favorites, speed up checkout and keep every order detail close at hand.
                </p>

                <form method="POST" action="{{ route('register.action') }}" class="auth-form">
                    @csrf

                    <div class="auth-field">
                        <label class="auth-label" for="name">Full name</label>
                        <div class="auth-input-wrap">
                            <span class="auth-input-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/>
                                </svg>
                            </span>
                            <input
                                class="auth-input @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                autocomplete="name"
                                placeholder="Enter your full name"
                                required
                            >
                        </div>
                        @error('name')
                            <p class="auth-feedback auth-feedback--error">{{ $message }}</p>
                        @enderror
                    </div>

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
                                id="email"
                                name="email"
                                type="email"
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
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                placeholder="Create a password with at least 8 characters"
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

                    <div class="auth-field">
                        <label class="auth-label" for="password_confirmation">Confirm password</label>
                        <div class="auth-input-wrap">
                            <span class="auth-input-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7.5 10V8a4.5 4.5 0 1 1 9 0v2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                    <rect x="4" y="10" width="16" height="10" rx="3" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="m10.8 15 1 1 2.4-2.6" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                            </span>
                            <input
                                class="auth-input"
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                placeholder="Repeat your password"
                                required
                            >
                            <button type="button" class="auth-toggle" data-toggle-password="password_confirmation" aria-label="Show confirm password">
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
                    </div>

                    <button type="submit" class="auth-submit">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 5v14" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/>
                            <path d="M5 12h14" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/>
                        </svg>
                        Create account
                    </button>

                    <p class="auth-note">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3 4 7v5c0 5 3.4 8.6 8 10 4.6-1.4 8-5 8-10V7l-8-4Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                        </svg>
                        Creating an account helps you keep wishlist items, order details and future checkout steps neatly organized.
                    </p>

                    <p class="auth-footnote">
                        Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in here</a>
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

