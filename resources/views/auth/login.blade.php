@extends('layouts.auth')

@section('title', 'Login - Parcel Management System')
@section('header', 'Welcome Back')
@section('subheader', 'Sign in to your account')

@section('content')
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('status'))
        <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
            <i class="fas fa-info-circle mr-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" @submit="loading = true">
        @csrf
        
        <!-- Email Field -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-white mb-2">
                <i class="fas fa-envelope mr-2"></i>Email Address
            </label>
            <div class="relative">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 bg-white bg-opacity-90 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 transition-colors"
                    placeholder="Enter your email"
                    required
                    autocomplete="email"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-white mb-2">
                <i class="fas fa-lock mr-2"></i>Password
            </label>
            <div class="relative">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full px-4 py-3 bg-white bg-opacity-90 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 transition-colors"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    name="remember" 
                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500"
                >
                <span class="ml-2 text-sm text-white">Remember me</span>
            </label>
            <!-- <a href="{{ route('password.request') }}" class="text-sm text-purple-200 hover:text-white transition-colors">
                Forgot password?
            </a> -->
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
            :disabled="loading"
        >
            <span x-show="!loading">
                <i class="fas fa-sign-in-alt mr-2"></i>Sign In
            </span>
            <span x-show="loading" class="flex items-center">
                <i class="fas fa-spinner fa-spin mr-2"></i>Signing In...
            </span>
        </button>
    </form>

    <!-- Divider -->
    <!-- <div class="my-6 flex items-center">
        <div class="flex-1 border-t border-gray-300 border-opacity-30"></div>
        <span class="px-4 text-sm text-white">or</span>
        <div class="flex-1 border-t border-gray-300 border-opacity-30"></div>
    </div> -->


    <!-- Register Link -->
    <!-- <div class="mt-4 text-center">
        <p class="text-white text-sm">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-purple-200 hover:text-white font-semibold transition-colors">
                Register here
            </a>
        </p>
    </div> -->
@endsection

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-blue-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    });
</script>
@endpush 