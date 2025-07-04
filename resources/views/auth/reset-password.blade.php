@extends('layouts.auth')

@section('title', 'Reset Password - Parcel Management System')

@section('header', 'Reset Password')

@section('subheader', 'Enter your new password')

@section('content')
<div class="min-h-screen gradient-bg flex items-center justify-center p-4" x-data="{ loading: false }">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                <i class="fas fa-box text-3xl text-purple-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Reset Password</h1>
            <p class="text-purple-100">Enter your new password</p>
        </div>

        <!-- Reset Password Card -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}" @submit="loading = true">
                @csrf
                
                <!-- Token -->
                <input type="hidden" name="token" value="{{ $token }}">
                
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
                            value="{{ $email ?? old('email') }}"
                            class="w-full px-4 py-3 bg-white bg-opacity-90 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="Enter your email address"
                            required
                            autocomplete="email"
                            :disabled="loading"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-lock mr-2"></i>New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 bg-white bg-opacity-90 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="Enter your new password"
                            required
                            autocomplete="new-password"
                            :disabled="loading"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-lock mr-2"></i>Confirm New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="w-full px-4 py-3 bg-white bg-opacity-90 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="Confirm your new password"
                            required
                            autocomplete="new-password"
                            :disabled="loading"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="loading"
                >
                    <span x-show="!loading">
                        <i class="fas fa-key mr-2"></i>Reset Password
                    </span>
                    <span x-show="loading" class="flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Resetting...
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6 flex items-center">
                <div class="flex-1 border-t border-gray-300 border-opacity-30"></div>
                <span class="px-4 text-sm text-white">or</span>
                <div class="flex-1 border-t border-gray-300 border-opacity-30"></div>
            </div>

            <!-- Back to Login -->
            <div class="text-center">
                <p class="text-white text-sm">
                    Remember your password? 
                    <a href="{{ route('login') }}" class="text-purple-200 hover:text-white font-semibold transition-colors">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-purple-100 text-sm">
                &copy; {{ date('Y') }} Parcel Management System. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center">
            <i class="fas fa-spinner fa-spin text-purple-600 mr-3"></i>
            <span>Resetting password...</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Password validation
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        function validatePasswords() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword && password !== confirmPassword) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        }

        passwordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);
    });
</script>
@endpush 