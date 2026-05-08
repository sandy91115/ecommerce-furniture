@extends('layouts.main')

@section('title', 'Register | Furniture Store')

@section('content')
<<<<<<< HEAD
<link rel="stylesheet" href="{{ versioned_asset('assets/css/login.css') }}">
=======
<link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

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
<<<<<<< HEAD

    $countryDialCodes = [
        ['code' => '+91', 'iso' => 'in', 'short' => 'IN', 'label' => 'India'],
        ['code' => '+1', 'iso' => 'us', 'short' => 'US', 'label' => 'United States'],
        ['code' => '+44', 'iso' => 'gb', 'short' => 'GB', 'label' => 'United Kingdom'],
        ['code' => '+971', 'iso' => 'ae', 'short' => 'AE', 'label' => 'United Arab Emirates'],
        ['code' => '+61', 'iso' => 'au', 'short' => 'AU', 'label' => 'Australia'],
        ['code' => '+65', 'iso' => 'sg', 'short' => 'SG', 'label' => 'Singapore'],
        ['code' => '+966', 'iso' => 'sa', 'short' => 'SA', 'label' => 'Saudi Arabia'],
        ['code' => '+974', 'iso' => 'qa', 'short' => 'QA', 'label' => 'Qatar'],
        ['code' => '+965', 'iso' => 'kw', 'short' => 'KW', 'label' => 'Kuwait'],
        ['code' => '+49', 'iso' => 'de', 'short' => 'DE', 'label' => 'Germany'],
        ['code' => '+33', 'iso' => 'fr', 'short' => 'FR', 'label' => 'France'],
    ];

    $oldPhoneValue = (string) old('phone', '');
    $phoneNumberValue = old('phone_number');
    $defaultCountryCode = '+91';
    $selectedDialCode = (string) old('phone_country_code', $defaultCountryCode);
    $availableDialCodes = array_column($countryDialCodes, 'code');

    if (! in_array($selectedDialCode, $availableDialCodes, true)) {
        $selectedDialCode = in_array($defaultCountryCode, $availableDialCodes, true) ? $defaultCountryCode : '+91';
    }

    if ($oldPhoneValue !== '' && old('phone_country_code') === null) {
        $oldPhoneDigits = preg_replace('/\D+/', '', $oldPhoneValue) ?: '';
        $sortedDialCodes = $countryDialCodes;

        usort($sortedDialCodes, function ($left, $right) {
            return strlen(preg_replace('/\D+/', '', $right['code'])) <=> strlen(preg_replace('/\D+/', '', $left['code']));
        });

        foreach ($sortedDialCodes as $country) {
            $dialDigits = preg_replace('/\D+/', '', $country['code']) ?: '';

            if ($dialDigits !== '' && str_starts_with($oldPhoneDigits, $dialDigits)) {
                $selectedDialCode = $country['code'];
                break;
            }
        }
    }

    if ($phoneNumberValue === null && $oldPhoneValue !== '') {
        $oldPhoneDigits = preg_replace('/\D+/', '', $oldPhoneValue) ?: '';
        $selectedDialDigits = preg_replace('/\D+/', '', $selectedDialCode) ?: '';
        $phoneNumberValue = $selectedDialDigits !== '' && str_starts_with($oldPhoneDigits, $selectedDialDigits)
            ? substr($oldPhoneDigits, strlen($selectedDialDigits))
            : $oldPhoneDigits;
    }

    $phoneDigits = preg_replace('/\D+/', '', (string) $phoneNumberValue) ?: '';
    $selectedDialDigits = preg_replace('/\D+/', '', $selectedDialCode) ?: '';
    $initialPhoneValue = $oldPhoneValue;

    if ($phoneDigits !== '') {
        $initialPhoneValue = $selectedDialDigits !== '' && strlen($phoneDigits) > 10 && str_starts_with($phoneDigits, $selectedDialDigits)
            ? '+' . $phoneDigits
            : $selectedDialCode . $phoneDigits;
    }

    $selectedCountry = collect($countryDialCodes)->firstWhere('code', $selectedDialCode) ?? $countryDialCodes[0];
@endphp

<section class="auth-page auth-page--register">
=======
@endphp

<section class="auth-page">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD
                        <label class="auth-label" for="phone_number">Mobile number</label>
                        <div class="auth-input-wrap auth-phone-wrap" data-phone-field>
                            <span class="auth-input-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <rect x="7" y="3" width="10" height="18" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M10.5 6h3M11 18h2" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/>
                                </svg>
                            </span>
                            <input type="hidden" id="phone_country_code" name="phone_country_code" value="{{ $selectedDialCode }}" data-phone-country>
                            <div class="auth-country-picker" data-country-picker>
                                <button
                                    type="button"
                                    class="auth-country-picker__button"
                                    aria-label="Select country code"
                                    aria-haspopup="listbox"
                                    aria-expanded="false"
                                    data-country-toggle
                                >
                                    <span class="auth-country-picker__flag" data-country-current-flag style="background-image: url('{{ asset('assets/img/flags/' . $selectedCountry['iso'] . '.svg') }}');"></span>
                                    <span class="auth-country-picker__code" data-country-current-code>{{ $selectedCountry['code'] }}</span>
                                    <svg class="auth-country-picker__chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                        <path d="m5 7 5 5 5-5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                    </svg>
                                </button>
                                <div class="auth-country-picker__menu" role="listbox" aria-label="Country code" data-country-menu hidden>
                                    @foreach ($countryDialCodes as $country)
                                        <button
                                            type="button"
                                            class="auth-country-picker__option"
                                            role="option"
                                            aria-selected="{{ $selectedDialCode === $country['code'] ? 'true' : 'false' }}"
                                            data-country-option
                                            data-code="{{ $country['code'] }}"
                                            data-digits="{{ preg_replace('/\D+/', '', $country['code']) }}"
                                            data-flag="{{ asset('assets/img/flags/' . $country['iso'] . '.svg') }}"
                                        >
                                            <span class="auth-country-picker__flag" style="background-image: url('{{ asset('assets/img/flags/' . $country['iso'] . '.svg') }}');"></span>
                                            <span class="auth-country-picker__short">{{ $country['short'] }}</span>
                                            <span class="auth-country-picker__option-code">{{ $country['code'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <input
                                class="auth-input auth-phone-number @error('phone') is-invalid @enderror"
                                id="phone_number"
                                name="phone_number"
                                type="tel"
                                value="{{ $phoneNumberValue }}"
                                autocomplete="tel-national"
                                inputmode="tel"
                                placeholder="98765 43210"
                                required
                                data-phone-number
                            >
                            <input type="hidden" id="phone" name="phone" value="{{ $initialPhoneValue }}" data-phone-full>
                        </div>
                        @error('phone')
                            <p class="auth-feedback auth-feedback--error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="auth-field">
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD
                        Create & sign in
=======
                        Create account
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                    </button>

                    <p class="auth-note">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3 4 7v5c0 5 3.4 8.6 8 10 4.6-1.4 8-5 8-10V7l-8-4Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                        </svg>
<<<<<<< HEAD
                        Your account will be created instantly and you'll be signed in automatically.
=======
                        Creating an account helps you keep wishlist items, order details and future checkout steps neatly organized.
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
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
<<<<<<< HEAD

    const phoneField = document.querySelector('[data-phone-field]');

    if (phoneField) {
        const countryInput = phoneField.querySelector('[data-phone-country]');
        const countryPicker = phoneField.querySelector('[data-country-picker]');
        const countryToggle = phoneField.querySelector('[data-country-toggle]');
        const countryMenu = phoneField.querySelector('[data-country-menu]');
        const currentFlag = phoneField.querySelector('[data-country-current-flag]');
        const currentCode = phoneField.querySelector('[data-country-current-code]');
        const countryOptions = Array.from(phoneField.querySelectorAll('[data-country-option]'));
        const phoneInput = phoneField.querySelector('[data-phone-number]');
        const phoneFullInput = phoneField.querySelector('[data-phone-full]');
        const phoneForm = phoneField.closest('form');

        if (countryInput && phoneInput && phoneFullInput) {
            const countryCodes = countryOptions
                .map(function (option) {
                    return {
                        code: option.getAttribute('data-code') || '',
                        digits: option.getAttribute('data-digits') || '',
                        flag: option.getAttribute('data-flag') || '',
                    };
                })
                .filter(function (country) {
                    return country.digits !== '';
                })
                .sort(function (left, right) {
                    return right.digits.length - left.digits.length;
                });

            const selectCountryFromDigits = function (digits) {
                const matchedCountry = countryCodes.find(function (country) {
                    return digits.startsWith(country.digits);
                });

                if (matchedCountry && countryInput.value !== matchedCountry.code) {
                    setSelectedCountry(matchedCountry.code, matchedCountry.flag);
                }
            };

            const closeCountryMenu = function () {
                if (!countryMenu || !countryToggle) {
                    return;
                }

                countryMenu.hidden = true;
                countryToggle.setAttribute('aria-expanded', 'false');
                countryPicker?.classList.remove('is-open');
            };

            const openCountryMenu = function () {
                if (!countryMenu || !countryToggle) {
                    return;
                }

                countryMenu.hidden = false;
                countryToggle.setAttribute('aria-expanded', 'true');
                countryPicker?.classList.add('is-open');
            };

            function setSelectedCountry(code, flag) {
                countryInput.value = code;

                if (currentCode) {
                    currentCode.textContent = code;
                }

                if (currentFlag && flag) {
                    currentFlag.style.backgroundImage = "url('" + flag + "')";
                }

                countryOptions.forEach(function (option) {
                    option.setAttribute('aria-selected', option.getAttribute('data-code') === code ? 'true' : 'false');
                });
            }

            const syncPhoneValue = function () {
                const rawValue = phoneInput.value.trim();
                const digits = rawValue.replace(/\D+/g, '');
                const selectedDigits = countryInput.value.replace(/\D+/g, '');

                if (digits === '') {
                    phoneFullInput.value = '';
                    return;
                }

                if (rawValue.startsWith('+')) {
                    selectCountryFromDigits(digits);
                    phoneFullInput.value = '+' + digits;
                    return;
                }

                if (digits.startsWith('00')) {
                    const internationalDigits = digits.slice(2);
                    selectCountryFromDigits(internationalDigits);
                    phoneFullInput.value = internationalDigits === '' ? '' : '+' + internationalDigits;
                    return;
                }

                phoneFullInput.value = selectedDigits !== '' && digits.length > 10 && digits.startsWith(selectedDigits)
                    ? '+' + digits
                    : countryInput.value + digits;
            };

            if (countryToggle && countryMenu) {
                countryToggle.addEventListener('click', function () {
                    if (countryMenu.hidden) {
                        openCountryMenu();
                        return;
                    }

                    closeCountryMenu();
                });

                countryOptions.forEach(function (option) {
                    option.addEventListener('click', function () {
                        setSelectedCountry(option.getAttribute('data-code') || countryInput.value, option.getAttribute('data-flag') || '');
                        closeCountryMenu();
                        syncPhoneValue();
                        phoneInput.focus();
                    });
                });

                document.addEventListener('click', function (event) {
                    if (!countryPicker || countryPicker.contains(event.target)) {
                        return;
                    }

                    closeCountryMenu();
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeCountryMenu();
                    }
                });
            }

            phoneInput.addEventListener('input', syncPhoneValue);

            if (phoneForm) {
                phoneForm.addEventListener('submit', syncPhoneValue);
            }

            syncPhoneValue();
        }
    }
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
});
</script>
@endsection

