@extends('layouts.main')

@section('content')
<style>
    .login-submit-btn {
        background: linear-gradient(90deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border-color: transparent;
    }

    .login-submit-btn:hover {
        background: linear-gradient(90deg, #1d4ed8 0%, #1e40af 100%);
        color: #ffffff;
    }

    .login-submit-btn:focus {
        color: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-20 w-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                <i class="fas fa-sign-in-alt text-3xl text-white"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-lg text-gray-600">
                Welcome back! Please sign in to your account.
            </p>
        </div>
        <form class="mt-8 space-y-6 bg-white p-8 rounded-3xl shadow-2xl" method="POST" action="{{ route('login.action') }}">
            @csrf
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-blue-600"></i>Email Address
                </label>
                <input id="email" name="email" type="email" autocomplete="email" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                       value="{{ old('email') }}" placeholder="Enter your email">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="relative">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                </label>
                <input id="password" name="password" type="password" autocomplete="current-password" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-12 @error('password') border-red-500 @enderror" 
                       placeholder="Enter your password">
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                    <i class="fas fa-eye text-gray-500 hover:text-gray-700" id="toggleIcon"></i>
                </button>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="ml-2 block text-sm text-gray-700">Remember me</span>
                </label>
            </div>

            <!-- Login Button -->
            <div>
                <button type="submit" class="login-submit-btn group relative w-full flex justify-center py-3 px-4 border text-lg font-bold rounded-xl shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-[1.02]">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-arrow-right text-white"></i>
                    </span>
                    Sign In
                </button>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors duration-200">
                        Sign up here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
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

