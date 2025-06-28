@extends('layouts.app')

@section('title', 'Customer Dashboard - NUJ Courier Management System')

@php
    use App\Services\ParcelService;
@endphp

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Welcome Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Dashboard Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 bg-purple-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600">Track your parcels and manage your account</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Parcels -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-blue-600 rounded-md flex items-center justify-center">
                            <i class="fas fa-box text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Parcels</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Parcels -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-yellow-600 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['pending'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Transit -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-indigo-600 rounded-md flex items-center justify-center">
                            <i class="fas fa-truck text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">In Transit</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['in_transit'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ready to Collect -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-green-600 rounded-md flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ready to Collect</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['ready_to_collect'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('customer.parcels.add') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-green-600 rounded-md flex items-center justify-center">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Add Parcel</h4>
                        <p class="text-sm text-gray-500">Register a new parcel for tracking</p>
                    </div>
                </a>
                
                <a href="{{ route('customer.parcels') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-purple-600 rounded-md flex items-center justify-center">
                            <i class="fas fa-search text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Track Parcels</h4>
                        <p class="text-sm text-gray-500">View all your parcels and their status</p>
                    </div>
                </a>
                
                <a href="{{ route('customer.profile') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-blue-600 rounded-md flex items-center justify-center">
                            <i class="fas fa-user-edit text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Update Profile</h4>
                        <p class="text-sm text-gray-500">Manage your account information</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Parcels -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Parcels</h3>
                <a href="{{ route('customer.parcels') }}" class="text-sm text-purple-600 hover:text-purple-500">View all</a>
            </div>
            
            @if($recentParcels->count() > 0)
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receiver</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentParcels as $parcel)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $parcel->tracking_no }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $parcel->receiver_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {!! $parcel->getStatusBadgeWithAttributes([
                                            'id' => 'status-' . $parcel->id,
                                            'onclick' => 'showStatusDetails(' . $parcel->id . ')',
                                            'style' => 'cursor: pointer;'
                                        ]) !!}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $parcel->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-box text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No parcels found. Start by adding your first parcel!</p>
                    <a href="{{ route('customer.parcels.add') }}" class="mt-4 inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        Add Parcel
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Function to show status details when badge is clicked
    function showStatusDetails(parcelId) {
        // You can implement modal or tooltip here
        console.log('Showing details for parcel:', parcelId);
        
        // Example: Show a simple alert with status info
        const statusElement = document.getElementById('status-' + parcelId);
        const statusText = statusElement.getAttribute('data-status-text');
        const statusValue = statusElement.getAttribute('data-status');
        
        alert(`Parcel Status Details:\nStatus: ${statusText}\nStatus Code: ${statusValue}\nParcel ID: ${parcelId}`);
    }

    // Example of how to use the status badge attributes
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to status badges
        const statusBadges = document.querySelectorAll('[data-status]');
        statusBadges.forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.transition = 'transform 0.2s ease';
            });
            
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });
</script>
@endpush
@endsection 