<!-- resources/views/login.blade.php -->
@extends('layouts.main')

@section('title', 'Login Page')

@section('content')



<div class="flex">
    <div class="w-1/2 hidden md:block lg:flex-1">
        <img class="h-full object-cover" src="{{ asset('assets/img/bg/login.jpg') }}" alt="login">
    </div>
    <div class="w-full md:w-1/2 lg:max-w-lg xl:max-w-3xl lg:w-full py-16 px-[20px] sm:px-8 lg:p-16 xl:p-24 relative z-10 flex items-center overflow-hidden">
        
        <div class="mx-auto md:mx-0 max-w-md">
<h2 class="leading-none text-4xl font-bold" data-aos="fade-up">Welcome back !</h2>
            <p class="text-lg mt-[15px]" data-aos="fade-up" data-aos-delay="100">Buy & sale your exclusive product only on Furnixar</p>
            <form class="mt-8 space-y-6 bg-white/80 dark:bg-slate-800/50 backdrop-blur-xl p-8 rounded-3xl shadow-2xl" method="POST" action="{{ route('login.action') }}">
                @csrf
                
                <!-- Email Field -->
                <div data-aos="fade-up" data-aos-delay="200">
                    <label for="email" class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white flex items-center">
                        <i class="mdi mdi-email-outline mr-2 text-primary"></i>Email
                    </label>
                    <input id="email" class="w-full h-12 md:h-14 px-4 py-3 bg-white dark:bg-transparent border border-bdr-clr rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/50 focus:outline-none duration-300 @error('email') border-red-500 ring-2 ring-red-500/50 @enderror" type="email" name="email" placeholder="Enter your email address" required value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="mdi mdi-alert-circle-outline mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="relative" data-aos="fade-up" data-aos-delay="300">
                    <label for="password" class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white flex items-center">
                        <i class="mdi mdi-lock-outline mr-2 text-primary"></i>Password
                    </label>
                    <input id="password" class="w-full h-12 md:h-14 px-4 py-3 pr-12 bg-white dark:bg-transparent border border-bdr-clr rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/50 focus:outline-none duration-300 @error('password') border-red-500 ring-2 ring-red-500/50 @enderror" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                    <button type="button" class="absolute right-4 top-[60px] sm:top-[68px] p-1 text-gray-500 hover:text-primary focus:outline-none" onclick="togglePassword()" data-aos="fade-up" data-aos-delay="350">
                        <i class="mdi mdi-eye-outline text-xl" id="toggleIcon"></i>
                    </button>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="mdi mdi-alert-circle-outline mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                <!-- Remember Me -->
                <div class="flex items-center justify-between" data-aos="fade-up" data-aos-delay="400">
                    <label for="remember" class="flex items-center text-base sm:text-lg text-title dark:text-white">
                        <input id="remember" name="remember" type="checkbox" class="h-5 w-5 text-primary focus:ring-primary border-gray-300 dark:border-white dark:bg-transparent rounded duration-300">
                        <span class="ml-3 select-none">Remember Me</span>
                    </label>
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-dark duration-300">Forgot Password?</a>
                </div>
                <!-- Submit Button -->
                <div data-aos="fade-up" data-aos-delay="500">
                    <button type="submit" class="group relative w-full flex justify-center py-4 px-6 border-0 font-bold rounded-2xl shadow-xl bg-gradient-to-r from-primary to-primary-dark text-white hover:from-primary-dark hover:to-primary text-lg hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-primary/30 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                        <i class="mdi mdi-login group-hover:-translate-x-1 transition-transform duration-300 mr-2"></i>
                        <span>Sign In</span>
                    </button>
                </div>

                <!-- Links -->
                <div class="text-center pt-4" data-aos="fade-up" data-aos-delay="550">
                    <p class="text-base text-title dark:text-white">
                        Don't have an account? <a href="{{ url('/register') }}" class="font-bold text-primary hover:text-primary-dark duration-300">Register Now</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

@include('includes.footer')

@endsection
