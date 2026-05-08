<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login</title>
    @php
        $adminFaviconPath = \App\Models\Setting::get('admin_favicon_path');
        $adminFaviconUrl = $adminFaviconPath ? asset('storage/' . $adminFaviconPath) : asset('assets/img/favicon.png');
    @endphp
    <link rel="icon" href="{{ $adminFaviconUrl }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ versioned_asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('assets/css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            background: #faf5ee;
        }

        .admin-login-page {
            min-height: 100vh;
        }

        .admin-password-field {
            position: relative;
        }

        .admin-password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #8d8376;
            background: transparent;
            border: 0;
            cursor: pointer;
            padding: 8px;
        }

        .admin-login-error {
            margin: 8px 0 0;
            color: #dc2626;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <main class="admin-login-page login-container">
        <section class="login-form-wrapper login-header" aria-labelledby="admin-login-title">
           
            <h1 id="admin-login-title">Admin Login</h1>
            <p>Secure access to the Carom Studios management panel.</p>

            <form method="POST" action="{{ route('admin.login.action') }}">
                @csrf

                <div>
                    <label class="login-label" for="email">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        Email
                    </label>
                    <input
                        class="login-field @error('email') is-invalid @enderror"
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                    >
                    @error('email')
                        <p class="admin-login-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="login-label" for="password">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        Password
                    </label>
                    <div class="admin-password-field">
                        <input
                            class="login-field @error('password') is-invalid @enderror"
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="admin-password-toggle" data-toggle-password aria-label="Show password">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="admin-login-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="login-checkbox">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="login-btn">
                    <span>Sign In</span>
                </button>
            </form>
        </section>
    </main>

    <script>
        document.querySelector('[data-toggle-password]')?.addEventListener('click', function () {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            const isVisible = password.type === 'text';

            password.type = isVisible ? 'password' : 'text';
            this.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            icon.classList.toggle('fa-eye', isVisible);
            icon.classList.toggle('fa-eye-slash', !isVisible);
        });
    </script>
</body>
</html>
=======
@extends('admin.layouts.app')

@section('title', 'Admin Login')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

<!-- Login Area Start - Symmetric with Register -->
<div class="login-container">
    <div class="login-form-wrapper login-header">
        <div class="login-icon">
            <i class="fas fa-sign-in-alt text-3xl text-white"></i>
        </div>
        <h2>Create Admin Login</h2>
        <p>Secure access to your furniture management dashboard</p>
        
        <form method="POST" action="{{ url('/admin/login') }}" class="space-y-0">
            @csrf
            
            <div>
                <label class="login-label" for="email">
                    <i class="fas fa-envelope mr-2 text-primary"></i>Email
                </label>
                <input class="login-field @error('email') border-red-500 @enderror" type="email" id="email" name="email" required value="{{ old('email') }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="login-label" for="password">
                    <i class="fas fa-lock mr-2 text-primary"></i>Password
                </label>
                <input class="login-field pr-12 @error('password') border-red-500 @enderror" type="password" id="password" name="password" required>
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <button type="button" onclick="togglePasswordAdmin()" class="absolute right-4 top-[calc(50%-28px)] text-gray-500 hover:text-primary transition-colors">
                    <i class="fas fa-eye" id="toggleIconAdmin"></i>
                </button>
            </div>

            <div class="login-checkbox">
                <input type="checkbox" id="remember" name="remember">
                <span>
                    <svg width="9" height="8" viewBox="0 0 9 8" fill="none">
                        <path d="M3.05203 7.04122C2.87283 7.04122 2.69433 6.97322 2.5562 6.83864L0.532492 4.8553C0.253409 4.58189 0.249159 4.13351 0.522576 3.85372C0.796701 3.57393 1.24578 3.57039 1.52416 3.84309L3.05203 5.34122L7.61512 0.868804C7.89491 0.595387 8.34328 0.59822 8.6167 0.87872C8.89082 1.1578 8.88657 1.60689 8.60749 1.8803L3.54787 6.83864C3.40974 6.97322 3.23124 7.04122 3.05203 7.04122Z"/>
                    </svg>
                </span>
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="login-btn" data-text="Sign In">
                <span>Sign In</span>
            </button>

            <p class="text-lg mt-[15px] text-center">
                Forgot password? <a href="#" class="login-link">Reset</a>
            </p>
        </form>
    </div>
</div>

<script>
function togglePasswordAdmin() {
    const password = document.getElementById('password');
    const icon = document.getElementById('toggleIconAdmin');
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection


>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
