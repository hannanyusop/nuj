@extends('layouts.app')

@section('title', 'Add New Parcel - Parcel Management System')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('customer.parcels') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add New Parcel</h1>
                <p class="mt-2 text-gray-600">Register a new parcel for tracking</p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="list-disc list-inside mt-2 ml-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add Parcel Form -->
    <div class="bg-white shadow rounded-lg" x-data="{ loading: false }">
        <form method="POST" action="{{ route('customer.parcels.store') }}" enctype="multipart/form-data" @submit="loading = true">
            @csrf
            
            <!-- Loading Overlay -->
            <div x-show="loading" x-transition class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 rounded-lg">
                <div class="bg-white rounded-lg p-6 flex items-center">
                    <i class="fas fa-spinner fa-spin text-purple-600 mr-3"></i>
                    <span>Registering parcel...</span>
                </div>
            </div>
            
            <!-- Basic Details Section -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-purple-600"></i>
                    Basic Details
                </h3>
            </div>
            
            <div class="px-6 py-4 space-y-6">
                <!-- Tracking Number -->
                <div>
                    <label for="tracking_no" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-barcode mr-2"></i>Tracking Number *
                    </label>
                    <input 
                        type="text" 
                        id="tracking_no" 
                        name="tracking_no" 
                        value="{{ old('tracking_no') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tracking_no') border-red-500 @enderror"
                        placeholder="Enter tracking number"
                        required
                    >
                    @error('tracking_no')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Receiver Name -->
                <div>
                    <label for="receiver_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Receiver Name *
                    </label>
                    <input 
                        type="text" 
                        id="receiver_name" 
                        name="receiver_name" 
                        value="{{ old('receiver_name', auth()->user()->name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('receiver_name') border-red-500 @enderror"
                        placeholder="Enter receiver name"
                        required
                    >
                    @error('receiver_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2"></i>Phone Number *
                    </label>
                    <input 
                        type="tel" 
                        id="phone_number" 
                        name="phone_number" 
                        value="{{ old('phone_number', auth()->user()->phone_number) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('phone_number') border-red-500 @enderror"
                        placeholder="Enter phone number"
                        required
                    >
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Drop Point Selection -->
                <div>
                    <label for="office_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>Drop Point *
                    </label>
                    <select 
                        id="office_id" 
                        name="office_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('office_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select a drop point</option>
                        @foreach(\App\Models\Office::all() as $office)
                            <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                {{ $office->name }} - {{ $office->address }}
                            </option>
                        @endforeach
                    </select>
                    @error('office_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Item Information Section -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-box-open mr-2 text-purple-600"></i>
                    Item Information
                </h3>
            </div>
            
            <div class="px-6 py-4 space-y-6">
                <!-- Item Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-box-open mr-2"></i>Item Description (Keterangan barang) *
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('description') border-red-500 @enderror"
                        placeholder="Describe the item being shipped"
                        required
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-2"></i>Quantity *
                    </label>
                    <input 
                        type="number" 
                        id="quantity" 
                        name="quantity" 
                        value="{{ old('quantity', 1) }}"
                        min="1"
                        max="999"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('quantity') border-red-500 @enderror"
                        required
                    >
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill mr-2"></i>Price (Harga) RM *
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            RM
                        </span>
                        <input 
                            type="number" 
                            id="price" 
                            name="price" 
                            value="{{ old('price') }}"
                            step="0.01"
                            min="0"
                            max="999999.99"
                            class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('price') border-red-500 @enderror"
                            placeholder="0.00"
                            required
                        >
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Invoice Upload -->
                <div>
                    <label for="invoice" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-upload mr-2"></i>Invoice (Optional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="invoice" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                    <span>Upload a file</span>
                                    <input 
                                        id="invoice" 
                                        name="invoice" 
                                        type="file" 
                                        class="sr-only"
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                    >
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, JPG, PNG, DOC up to 10MB</p>
                        </div>
                    </div>
                    @error('invoice')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex items-center justify-between">
                <a href="{{ route('customer.parcels') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button 
                    type="submit" 
                    class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                    :disabled="loading"
                >
                    <i class="fas fa-save mr-2" x-show="!loading"></i>
                    <i class="fas fa-spinner fa-spin mr-2" x-show="loading"></i>
                    <span x-text="loading ? 'Registering...' : 'Register Parcel'"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-blue-900 mb-3 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Need Help?
        </h3>
        <div class="text-sm text-blue-800 space-y-2">
            <p><strong>Tracking Number:</strong> Enter the unique tracking number provided by your courier service.</p>
            <p><strong>Receiver Information:</strong> Provide the name and phone number of the person receiving the parcel.</p>
            <p><strong>Drop Point:</strong> Select the office where the parcel should be delivered.</p>
            <p><strong>Invoice:</strong> Upload the invoice or receipt for the parcel (optional but recommended).</p>
        </div>
    </div>
</div>
@endsection 