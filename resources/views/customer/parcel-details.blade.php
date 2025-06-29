@extends('layouts.app')

@section('title', 'Parcel Details - NUJ Courier Management System')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('customer.parcels') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>Back to Parcels
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Parcel Details</h2>
                        <p class="text-gray-600 mt-1">Tracking Number: {{ $parcel->tracking_number }}</p>
                    </div>
                    <div class="text-right">
                        {!! $parcel->status_badge_with_icon !!}
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Parcel Information -->
                    <div class="lg:col-span-2">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tracking Number</label>
                                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ $parcel->tracking_number }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <div class="mt-1">
                                        {!! $parcel->status_badge !!}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Created Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $parcel->created_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $parcel->updated_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Receiver Information -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Receiver Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $parcel->receiver_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $parcel->receiver_phone }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Address</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $parcel->receiver_address }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Parcel Details -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Parcel Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $parcel->description }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $parcel->quantity }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Weight</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $parcel->weight }} kg</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Dimensions</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $parcel->length }} × {{ $parcel->width }} × {{ $parcel->height }} cm</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Timeline -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Timeline</h3>
                            <div class="space-y-4">
                                @php
                                    $statuses = [
                                        \App\Models\Parcel::STATUS_REGISTERED => ['icon' => 'fas fa-clipboard-list', 'title' => 'Parcel Registered', 'description' => 'Your parcel has been registered in our system'],
                                        \App\Models\Parcel::STATUS_RECEIVED => ['icon' => 'fas fa-box-open', 'title' => 'Parcel Received', 'description' => 'Your parcel has been received at our facility'],
                                        \App\Models\Parcel::STATUS_OUTBOUND_TO_DROP_POINT => ['icon' => 'fas fa-truck', 'title' => 'Outbound to Drop Point', 'description' => 'Your parcel is on its way to the drop point'],
                                        \App\Models\Parcel::STATUS_INBOUND_TO_DROP_POINT => ['icon' => 'fas fa-warehouse', 'title' => 'Inbound to Drop Point', 'description' => 'Your parcel has arrived at the drop point'],
                                        \App\Models\Parcel::STATUS_READY_TO_COLLECT => ['icon' => 'fas fa-hand-holding-box', 'title' => 'Ready to Collect', 'description' => 'Your parcel is ready for collection'],
                                        \App\Models\Parcel::STATUS_DELIVERED => ['icon' => 'fas fa-check-circle', 'title' => 'Delivered', 'description' => 'Your parcel has been successfully delivered'],
                                        \App\Models\Parcel::STATUS_RETURN => ['icon' => 'fas fa-undo', 'title' => 'Returned', 'description' => 'Your parcel has been returned']
                                    ];
                                @endphp

                                @foreach($statuses as $statusCode => $statusInfo)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center 
                                                        {{ $parcel->status >= $statusCode ? 'bg-green-500' : 'bg-gray-300' }}">
                                                <i class="{{ $statusInfo['icon'] }} text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $statusInfo['title'] }}</h4>
                                            <p class="text-sm text-gray-500">{{ $statusInfo['description'] }}</p>
                                        </div>
                                        @if($parcel->status >= $statusCode)
                                            <div class="ml-4">
                                                <i class="fas fa-check text-green-500"></i>
                                            </div>
                                        @endif
                                    </div>
                                    @if(!$loop->last)
                                        <div class="ml-4 border-l-2 border-gray-200 h-4"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Pricing & Actions -->
                    <div class="lg:col-span-1">
                        <!-- Pricing Information -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Base Price:</span>
                                    <span class="text-sm font-medium text-gray-900">${{ number_format($parcel->price / 100, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Tax:</span>
                                    <span class="text-sm font-medium text-gray-900">${{ number_format($parcel->tax / 100, 2) }}</span>
                                </div>
                                <hr class="border-gray-300">
                                <div class="flex justify-between">
                                    <span class="text-sm font-semibold text-gray-900">Total:</span>
                                    <span class="text-sm font-semibold text-gray-900">${{ number_format($parcel->total_price / 100, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                            <div class="space-y-3">
                                @if($parcel->invoice_url)
                                    <a href="{{ Storage::url($parcel->invoice_url) }}" 
                                       target="_blank"
                                       class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <i class="fas fa-file-invoice mr-2"></i>View Invoice
                                    </a>
                                @endif
                                
                                <button onclick="window.print()" 
                                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-print mr-2"></i>Print Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 