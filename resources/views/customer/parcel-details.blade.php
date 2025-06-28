@extends('layouts.app')

@section('title', 'Parcel Details - ' . $parcel->tracking_no)

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.parcels') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Parcel Details</h1>
                    <p class="text-gray-600 mt-1">Tracking number: {{ $parcel->tracking_no }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                @if($parcel->invoice_url)
                    <a href="{{ Storage::url($parcel->invoice_url) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-file-invoice mr-2"></i>View Invoice
                    </a>
                @endif
                <a href="{{ route('customer.parcels') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-list mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Parcel Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Parcel Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Basic Details</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tracking Number</dt>
                                    <dd class="text-sm text-gray-900 font-mono">{{ $parcel->tracking_no }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Parcel ID</dt>
                                    <dd class="text-sm text-gray-900">#{{ $parcel->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="text-sm text-gray-900">{{ $parcel->description }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                                    <dd class="text-sm text-gray-900">{{ $parcel->quantity }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($parcel->price, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Receiver Information</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Receiver Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $parcel->receiver_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                    <dd class="text-sm text-gray-900">{{ $parcel->phone_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Drop Point</dt>
                                    <dd class="text-sm text-gray-900">{{ $parcel->office->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date Created</dt>
                                    <dd class="text-sm text-gray-900">{{ $parcel->created_at->format('F d, Y \a\t g:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="text-sm text-gray-900">{{ $parcel->updated_at->format('F d, Y \a\t g:i A') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tracking Timeline -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Tracking Timeline</h3>
                </div>
                <div class="p-6">
                    @php
                        $statuses = [
                            0 => ['name' => 'Parcel Registered', 'description' => 'Your parcel has been registered and is awaiting processing', 'icon' => 'fas fa-clipboard-list', 'color' => 'text-yellow-600'],
                            1 => ['name' => 'In Transit', 'description' => 'Your parcel is being transported to the destination', 'icon' => 'fas fa-truck', 'color' => 'text-blue-600'],
                            3 => ['name' => 'Ready for Collection', 'description' => 'Your parcel is ready for collection at the drop point', 'icon' => 'fas fa-hand-holding', 'color' => 'text-purple-600'],
                            2 => ['name' => 'Delivered', 'description' => 'Your parcel has been successfully delivered', 'icon' => 'fas fa-check-circle', 'color' => 'text-green-600'],
                        ];
                    @endphp

                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($statuses as $statusCode => $status)
                                <li>
                                    <div class="relative pb-8">
                                        @if($statusCode < 3)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white 
                                                    {{ $parcel->status >= $statusCode ? 'bg-green-500' : 'bg-gray-300' }}">
                                                    <i class="{{ $status['icon'] }} {{ $parcel->status >= $statusCode ? $status['color'] : 'text-gray-400' }} text-sm"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $status['name'] }}
                                                        @if($parcel->status == $statusCode)
                                                            <span class="font-medium text-gray-900">(Current Status)</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-sm text-gray-400">{{ $status['description'] }}</p>
                                                </div>
                                                @if($parcel->status >= $statusCode)
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        @if($statusCode == 0)
                                                            {{ $parcel->created_at->format('M d, Y') }}
                                                        @elseif($parcel->status == $statusCode)
                                                            <span class="text-green-600 font-medium">Just now</span>
                                                        @else
                                                            <span class="text-green-600">✓</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Current Status</h3>
                    @php
                        $currentStatus = $statuses[$parcel->status];
                        $statusColors = [
                            0 => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            1 => 'bg-blue-100 text-blue-800 border-blue-200',
                            2 => 'bg-green-100 text-green-800 border-green-200',
                            3 => 'bg-purple-100 text-purple-800 border-purple-200'
                        ];
                    @endphp
                    <div class="border rounded-lg p-4 {{ $statusColors[$parcel->status] }}">
                        <div class="flex items-center">
                            <i class="{{ $currentStatus['icon'] }} text-xl mr-3"></i>
                            <div>
                                <p class="font-semibold">{{ $currentStatus['name'] }}</p>
                                <p class="text-sm opacity-75">{{ $currentStatus['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('customer.parcels') }}" 
                           class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-list mr-2"></i>View All Parcels
                        </a>
                        <a href="{{ route('customer.parcels.add') }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add New Parcel
                        </a>
                        @if($parcel->invoice_url)
                            <a href="{{ Storage::url($parcel->invoice_url) }}" 
                               target="_blank"
                               class="w-full flex items-center justify-center px-4 py-2 border border-blue-300 rounded-lg text-sm font-medium text-blue-700 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-download mr-2"></i>Download Invoice
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Need Help?</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-phone mr-3 text-gray-400"></i>
                            <span>+1 (555) 123-4567</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-gray-400"></i>
                            <span>support@parceltracker.com</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-3 text-gray-400"></i>
                            <span>Mon-Fri 9AM-6PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 