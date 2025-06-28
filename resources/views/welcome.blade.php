@extends('layouts.app')

@section('title', 'Welcome to Parcel Tracker')

@section('content')
<div class="bg-gradient-to-br from-purple-600 to-blue-500 py-20">
    <div class="max-w-4xl mx-auto px-4 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Parcel Tracker</h1>
        <p class="text-lg md:text-2xl mb-8 font-medium">Effortlessly manage, track, and deliver parcels with our all-in-one logistics platform.</p>
        <a href="{{ route('register') }}" class="inline-block bg-white text-purple-700 font-semibold px-8 py-3 rounded-lg shadow-lg hover:bg-purple-100 transition">Get Started Free</a>
    </div>
</div>

<div class="py-16 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div class="bg-white rounded-lg shadow p-8">
                <i class="fas fa-box fa-2x text-purple-600 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Easy Parcel Registration</h3>
                <p class="text-gray-600">Register parcels in seconds and generate unique tracking numbers for your customers.</p>
            </div>
            <div class="bg-white rounded-lg shadow p-8">
                <i class="fas fa-map-marked-alt fa-2x text-blue-600 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Real-Time Tracking</h3>
                <p class="text-gray-600">Track every parcel's journey from pickup to delivery with real-time status updates.</p>
            </div>
            <div class="bg-white rounded-lg shadow p-8">
                <i class="fas fa-users fa-2x text-green-600 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Role-Based Access</h3>
                <p class="text-gray-600">Admin, staff, runners, and customers each have tailored dashboards and permissions.</p>
            </div>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">Why Choose Parcel Tracker?</h2>
        <ul class="grid md:grid-cols-2 gap-8 text-left">
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                <span>Seamless parcel management for businesses and individuals</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                <span>Automated notifications and status updates</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                <span>Secure, role-based authentication and access control</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                <span>Modern, mobile-friendly interface</span>
            </li>
        </ul>
        <a href="{{ route('register') }}" class="mt-10 inline-block bg-purple-600 text-white font-semibold px-8 py-3 rounded-lg shadow-lg hover:bg-purple-700 transition">Start Now</a>
    </div>
</div>

<div class="py-12 bg-gray-50 border-t">
    <div class="max-w-4xl mx-auto px-4 text-center text-gray-500">
        &copy; {{ date('Y') }} Parcel Tracker. All rights reserved.
    </div>
</div>
@endsection
