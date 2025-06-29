@extends('layouts.app')

@section('title', 'Status Badge Examples - NUJ Courier Management System')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">Status Badge Examples & Documentation</h2>
                
                <!-- Basic Usage -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Usage</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Using Model Attributes</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Status Badge (Basic):</p>
                                <div class="flex items-center space-x-4">
                                    @foreach([1, 2, 3, 4, 5, 6, 7] as $status)
                                        @php
                                            $parcel = new \App\Models\Parcel();
                                            $parcel->status = $status;
                                        @endphp
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">{{ $parcel->status_label }}</p>
                                            {!! $parcel->status_badge !!}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Status Badge with Icon:</p>
                                <div class="flex items-center space-x-4">
                                    @foreach([1, 2, 3, 4, 5, 6, 7] as $status)
                                        @php
                                            $parcel = new \App\Models\Parcel();
                                            $parcel->status = $status;
                                        @endphp
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">{{ $parcel->status_label }}</p>
                                            {!! $parcel->status_badge_with_icon !!}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Individual Attributes -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Individual Attributes</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Status Information</h4>
                                <div class="space-y-3">
                                    @foreach([1, 2, 3, 4, 5, 6, 7] as $status)
                                        @php
                                            $parcel = new \App\Models\Parcel();
                                            $parcel->status = $status;
                                        @endphp
                                        <div class="border rounded p-3">
                                            <p class="text-sm font-medium">{{ $parcel->status_label }}</p>
                                            <p class="text-xs text-gray-600">Text: {{ $parcel->status_text }}</p>
                                            <p class="text-xs text-gray-600">Icon: {{ $parcel->status_icon }}</p>
                                            <p class="text-xs text-gray-600">Color: {{ $parcel->status_color }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Code Examples</h4>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 mb-2">Basic Badge:</p>
                                        <code class="text-xs bg-gray-100 p-2 rounded block">
                                            {!! '{{ $parcel->status_badge }}' !!}
                                        </code>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 mb-2">Badge with Icon:</p>
                                        <code class="text-xs bg-gray-100 p-2 rounded block">
                                            {!! '{{ $parcel->status_badge_with_icon }}' !!}
                                        </code>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 mb-2">Individual Attributes:</p>
                                        <code class="text-xs bg-gray-100 p-2 rounded block">
                                            Status: {{ '{{ $parcel->status_text }}' }}<br>
                                            Label: {{ '{{ $parcel->status_label }}' }}<br>
                                            Icon: {{ '{{ $parcel->status_icon }}' }}<br>
                                            Color: {{ '{{ $parcel->status_color }}' }}
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Attributes -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Custom Attributes</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Badge with Custom Attributes:</p>
                                <div class="flex items-center space-x-4">
                                    @foreach([1, 2, 3, 4, 5, 6, 7] as $status)
                                        @php
                                            $parcel = new \App\Models\Parcel();
                                            $parcel->status = $status;
                                        @endphp
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">{{ $parcel->status_label }}</p>
                                            {!! $parcel->getStatusBadgeWithAttributes([
                                                'id' => 'status-' . $status,
                                                'onclick' => 'alert(\'Status: ' . $parcel->status_text . '\')',
                                                'style' => 'cursor: pointer;'
                                            ]) !!}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Badge with Icon and Custom Attributes:</p>
                                <div class="flex items-center space-x-4">
                                    @foreach([1, 2, 3, 4, 5, 6, 7] as $status)
                                        @php
                                            $parcel = new \App\Models\Parcel();
                                            $parcel->status = $status;
                                        @endphp
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">{{ $parcel->status_label }}</p>
                                            {!! $parcel->getStatusBadgeWithIconAndAttributes([
                                                'id' => 'status-icon-' . $status,
                                                'onclick' => 'alert(\'Status: ' . $parcel->status_text . '\')',
                                                'style' => 'cursor: pointer;'
                                            ]) !!}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-3">Code Examples for Custom Attributes</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-2">With Custom Attributes:</p>
                                    <code class="text-xs bg-gray-100 p-2 rounded block">
                                        {!! '$parcel->getStatusBadgeWithAttributes([<br>
                                        &nbsp;&nbsp;\'id\' => \'status-\' . $parcel->id,<br>
                                        &nbsp;&nbsp;\'onclick\' => \'showStatusDetails(\' . $parcel->id . \')\',<br>
                                        &nbsp;&nbsp;\'style\' => \'cursor: pointer;\'<br>
                                        ])' !!}
                                    </code>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-2">With Icon and Custom Attributes:</p>
                                    <code class="text-xs bg-gray-100 p-2 rounded block">
                                        {!! '$parcel->getStatusBadgeWithIconAndAttributes([<br>
                                        &nbsp;&nbsp;\'id\' => \'status-icon-\' . $parcel->id,<br>
                                        &nbsp;&nbsp;\'onclick\' => \'showStatusDetails(\' . $parcel->id . \')\',<br>
                                        &nbsp;&nbsp;\'style\' => \'cursor: pointer;\'<br>
                                        ])' !!}
                                    </code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Constants -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Constants</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Available Status Constants</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm">STATUS_REGISTERED:</span>
                                        <span class="text-sm font-mono">{{ \App\Models\Parcel::STATUS_REGISTERED }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm">STATUS_RECEIVED:</span>
                                        <span class="text-sm font-mono">{{ \App\Models\Parcel::STATUS_RECEIVED }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm">STATUS_OUTBOUND_TO_DROP_POINT:</span>
                                        <span class="text-sm font-mono">{{ \App\Models\Parcel::STATUS_OUTBOUND_TO_DROP_POINT }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm">STATUS_INBOUND_TO_DROP_POINT:</span>
                                        <span class="text-sm font-mono">{{ \App\Models\Parcel::STATUS_INBOUND_TO_DROP_POINT }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm">STATUS_READY_TO_COLLECT:</span>
                                        <span class="text-sm font-mono">{{ \App\Models\Parcel::STATUS_READY_TO_COLLECT }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm">STATUS_DELIVERED:</span>
                                        <span class="text-sm font-mono">{{ \App\Models\Parcel::STATUS_DELIVERED }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm">STATUS_RETURN:</span>
                                        <span class="text-sm font-mono">{{ \App\Models\Parcel::STATUS_RETURN }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-3">Usage in Code</h4>
                                <code class="text-xs bg-gray-100 p-2 rounded block">
                                    // Check if parcel is delivered<br>
                                    if ($parcel->status === Parcel::STATUS_DELIVERED) {<br>
                                    &nbsp;&nbsp;// Handle delivered parcel<br>
                                    }<br><br>
                                    
                                    // Check if parcel is in transit<br>
                                    $inTransit = in_array($parcel->status, [<br>
                                    &nbsp;&nbsp;Parcel::STATUS_RECEIVED,<br>
                                    &nbsp;&nbsp;Parcel::STATUS_OUTBOUND_TO_DROP_POINT,<br>
                                    &nbsp;&nbsp;Parcel::STATUS_INBOUND_TO_DROP_POINT<br>
                                    ]);
                                </code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Best Practices -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Best Practices</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-2">✅ Do's</h4>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>• Use model attributes instead of calling ParcelService directly</li>
                                    <li>• Use status constants for comparisons</li>
                                    <li>• Use the appropriate badge method for your needs</li>
                                    <li>• Add custom attributes when you need interactivity</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-2">❌ Don'ts</h4>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>• Don't hardcode status values in your views</li>
                                    <li>• Don't call ParcelService methods directly in views</li>
                                    <li>• Don't create custom status arrays in views</li>
                                    <li>• Don't forget to handle unknown status values</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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