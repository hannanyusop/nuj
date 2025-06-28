@extends('layouts.auth')
@section('title', 'Register - Parcel Management System')
@section('header', 'Create Account')
@section('subheader', 'Join our parcel management system')

@section('content')
<form method="POST" action="{{ route('register') }}" @submit="loading = true">
                @csrf
                
                <!-- Name Field -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-user mr-2"></i>Full Name
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 bg-white bg-opacity-90 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="Enter your full name"
                            required
                            autocomplete="name"
                            :disabled="loading"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

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

                <!-- Phone Number Field -->
                <div class="mb-6">
                    <label for="phone_number" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-phone mr-2"></i>Phone Number
                    </label>
                    <div class="relative">
                        <input 
                            type="tel" 
                            id="phone_number" 
                            name="phone_number" 
                            value="{{ old('phone_number') }}"
                            class="w-full px-4 py-3 bg-white bg-opacity-90 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="Enter your phone number"
                            autocomplete="tel"
                            :disabled="loading"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                    </div>
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address Field -->
                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>Address
                    </label>
                    <div class="relative">
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="3"
                            class="w-full px-4 py-3 bg-white bg-opacity-90 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 transition-colors resize-none"
                            placeholder="Enter your address"
                            autocomplete="street-address"
                            :disabled="loading"
                        >{{ old('address') }}</textarea>
                        <div class="absolute top-3 right-3">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                    </div>
                    @error('address')
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
                            placeholder="Create a password (min 8 characters)"
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
                        <i class="fas fa-lock mr-2"></i>Confirm Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="w-full px-4 py-3 bg-white bg-opacity-90 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="Confirm your password"
                            required
                            autocomplete="new-password"
                            :disabled="loading"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input 
                            type="checkbox" 
                            name="terms" 
                            class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 mt-1"
                            required
                            :disabled="loading"
                        >
                        <span class="ml-2 text-sm text-white">
                            I agree to the 
                            <a href="#" class="text-purple-200 hover:text-white underline">Terms of Service</a> 
                            and 
                            <a href="#" class="text-purple-200 hover:text-white underline">Privacy Policy</a>
                        </span>
                    </label>
                    @error('terms')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="loading"
                >
                    <span x-show="!loading">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </span>
                    <span x-show="loading" class="flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6 flex items-center">
                <div class="flex-1 border-t border-gray-300 border-opacity-30"></div>
                <span class="px-4 text-sm text-white">or</span>
                <div class="flex-1 border-t border-gray-300 border-opacity-30"></div>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-white text-sm">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-purple-200 hover:text-white font-semibold transition-colors">
                        Sign in here
                    </a>
                </p>
            </div>
@endsection

@push('scripts')
<script>
    // Password validation and strength indicator
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

        function checkPasswordStrength() {
            const password = passwordInput.value;
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // You can add visual feedback here if needed
            // For example, update a strength indicator element
        }

        passwordInput.addEventListener('input', function() {
            validatePasswords();
            checkPasswordStrength();
        });

        confirmPasswordInput.addEventListener('input', validatePasswords);
    });
</script>
@endpush
