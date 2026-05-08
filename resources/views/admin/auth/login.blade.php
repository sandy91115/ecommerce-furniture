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
