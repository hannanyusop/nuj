@extends('layouts.app')

@section('title', 'My Parcels - Parcel Management System')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Parcels</h1>
                    <p class="text-gray-600 mt-1">Track and manage all your parcels</p>
                </div>
            </div>
            <a href="{{ route('customer.parcels.add') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add New Parcel
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-blue-600 rounded-md flex items-center justify-center">
                        <i class="fas fa-box text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Parcels</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $parcels->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-yellow-600 rounded-md flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $parcels->where('status', 0)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-green-600 rounded-md flex items-center justify-center">
                        <i class="fas fa-check text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Delivered</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $parcels->where('status', 2)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-purple-600 rounded-md flex items-center justify-center">
                        <i class="fas fa-hand-holding text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ready to Collect</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $parcels->where('status', 3)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Parcels</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" placeholder="Search by tracking number or receiver name..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="md:w-48">
                    <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select id="status_filter" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">All Status</option>
                        <option value="0">Pending</option>
                        <option value="1">In Transit</option>
                        <option value="2">Delivered</option>
                        <option value="3">Ready to Collect</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Parcels List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Parcels List</h3>
        </div>
        
        @if($parcels->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receiver</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Drop Point</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($parcels as $parcel)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $parcel->tracking_no }}</div>
                                    <div class="text-sm text-gray-500">#{{ $parcel->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $parcel->receiver_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $parcel->phone_number }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ Str::limit($parcel->description, 50) }}</div>
                                    <div class="text-sm text-gray-500">Qty: {{ $parcel->quantity }} | ${{ number_format($parcel->price, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            0 => 'bg-yellow-100 text-yellow-800',
                                            1 => 'bg-blue-100 text-blue-800',
                                            2 => 'bg-green-100 text-green-800',
                                            3 => 'bg-purple-100 text-purple-800'
                                        ];
                                        $statusText = [
                                            0 => 'Pending',
                                            1 => 'In Transit',
                                            2 => 'Delivered',
                                            3 => 'Ready to Collect'
                                        ];
                                        $statusIcons = [
                                            0 => 'fas fa-clock',
                                            1 => 'fas fa-truck',
                                            2 => 'fas fa-check-circle',
                                            3 => 'fas fa-hand-holding'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$parcel->status] }}">
                                        <i class="{{ $statusIcons[$parcel->status] }} mr-1"></i>
                                        {{ $statusText[$parcel->status] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $parcel->office->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $parcel->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-400">{{ $parcel->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('customer.parcels.show', $parcel) }}" 
                                           class="text-purple-600 hover:text-purple-900" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($parcel->invoice_url)
                                            <a href="{{ Storage::url($parcel->invoice_url) }}" 
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-900" 
                                               title="View Invoice">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($parcels->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $parcels->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-box text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No parcels found</h3>
                <p class="text-gray-500 mb-6">You haven't registered any parcels yet. Start by adding your first parcel!</p>
                <a href="{{ route('customer.parcels.add') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Your First Parcel
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('status_filter');
    
    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Search functionality
    const performSearch = debounce(function() {
        const searchTerm = searchInput.value;
        const status = statusFilter.value;
        
        let url = new URL(window.location);
        if (searchTerm) {
            url.searchParams.set('search', searchTerm);
        } else {
            url.searchParams.delete('search');
        }
        
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        
        // Reset to first page when searching
        url.searchParams.delete('page');
        
        window.location.href = url.toString();
    }, 500);
    
    // Event listeners
    searchInput.addEventListener('input', performSearch);
    statusFilter.addEventListener('change', performSearch);
    
    // Set current values from URL params
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.get('status')) {
        statusFilter.value = urlParams.get('status');
    }
});
</script>
@endpush 