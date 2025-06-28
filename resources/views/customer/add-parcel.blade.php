<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Parcel - Parcel Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('customer.dashboard') }}" class="flex-shrink-0 flex items-center">
                        <i class="fas fa-arrow-left text-gray-600 mr-2"></i>
                        <i class="fas fa-box text-2xl text-purple-600 mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">Parcel Tracker</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-home mr-1"></i>Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-red-600">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Add New Parcel</h1>
                <p class="mt-2 text-gray-600">Register a new parcel for tracking</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Add Parcel Form -->
            <div class="bg-white shadow rounded-lg relative" x-data="{ loading: false }">
                <form method="POST" action="{{ route('customer.parcels.store') }}" enctype="multipart/form-data" @submit="loading = true">
                    @csrf
                    
                    <!-- Loading Overlay -->
                    <div x-show="loading" x-transition class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 rounded-lg">
                        <div class="bg-white rounded-lg p-6 flex items-center">
                            <i class="fas fa-spinner fa-spin text-purple-600 mr-3"></i>
                            <span>Registering parcel...</span>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Basic Details</h3>
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter tracking number"
                                required
                            >
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
                                value="{{ old('receiver_name', $user->name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter receiver name"
                                required
                            >
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
                                value="{{ old('phone_number', $user->phone_number) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter phone number"
                                required
                            >
                        </div>
                    </div>

                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Item Information</h3>
                    </div>
                    
                    <div class="px-6 py-4 space-y-6">
                        <!-- Item Description -->
                        <div>
                            <label for="item_description" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-box-open mr-2"></i>Item Description (Keterangan barang) *
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Describe the item being shipped"
                                required
                            >{{ old('description') }}</textarea>
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                required
                            >
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
                                    class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="0.00"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Invoice Upload -->
                        <div>
                            <label for="invoice" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-file-upload mr-2"></i>Invoice (Optional)
                            </label>
                            <input 
                                type="file" 
                                id="invoice" 
                                name="invoice" 
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                            >
                            <p class="mt-1 text-sm text-gray-500">
                                Accepted formats: PDF, JPG, JPEG, PNG (Max: 2MB)
                            </p>
                        </div>

                        <!-- Collection Point -->
                        <div>
                            <label for="collection_point" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>Collection Point *
                            </label>
                            <select 
                                id="office_id" 
                                name="office_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                required
                            >
                                <option value="">Select a collection point</option>
                                @foreach($dropPoints as $dropPoint)
                                    <option value="{{ $dropPoint->id }}" {{ old('collection_point') == $dropPoint->id ? 'selected' : '' }}>
                                        {{ $dropPoint->name }} - {{ $dropPoint->address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <a 
                            href="{{ route('customer.dashboard') }}" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                        >
                            Cancel
                        </a>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 flex items-center"
                            :disabled="loading"
                        >
                            <span x-show="!loading">
                                <i class="fas fa-save mr-2"></i>Register Parcel
                            </span>
                            <span x-show="loading" class="flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Registering...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 