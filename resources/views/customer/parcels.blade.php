@extends('layouts.app')

@section('title', 'My Parcels - NUJ Courier Management System')

@php
    use App\Services\ParcelService;
@endphp

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">My Parcels</h2>
                    <a href="{{ route('customer.parcels.create') }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add New Parcel
                    </a>
                </div>

                <!-- Search and Filter -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('customer.parcels') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by tracking number or receiver name..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <select name="status" 
                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Statuses</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Registered</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Received</option>
                                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Outbound to Drop Point</option>
                                <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Inbound to Drop Point</option>
                                <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>Ready to Collect</option>
                                <option value="6" {{ request('status') == '6' ? 'selected' : '' }}>Delivered</option>
                                <option value="7" {{ request('status') == '7' ? 'selected' : '' }}>Return</option>
                            </select>
                        </div>
                        <button type="submit" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('customer.parcels') }}" 
                               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Parcels Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tracking Number
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Receiver
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($parcels as $parcel)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $parcel->tracking_number }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $parcel->receiver_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $parcel->receiver_phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $parcel->status_badge_with_icon !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($parcel->total_price / 100, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $parcel->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('customer.parcels.show', $parcel) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    @if(request('search') || request('status'))
                                        No parcels found matching your criteria.
                                        <a href="{{ route('customer.parcels') }}" class="text-indigo-600 hover:text-indigo-900 ml-1">
                                            Clear filters
                                        </a>
                                    @else
                                        No parcels found. 
                                        <a href="{{ route('customer.parcels.create') }}" class="text-indigo-600 hover:text-indigo-900 ml-1">
                                            Create your first parcel
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($parcels->hasPages())
                <div class="mt-6">
                    {{ $parcels->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('status');
    
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