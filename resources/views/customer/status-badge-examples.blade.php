@extends('layouts.app')

@section('title', 'Status Badge Examples - NUJ Courier Management System')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Status Badge Examples</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Status Badge -->
            <div class="border rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-3">Basic Status Badge</h3>
                <p class="text-sm text-gray-600 mb-2">Using: <code>$parcel->status_badge</code></p>
                <div class="space-y-2">
                    @foreach([1, 2, 3, 4, 5, 6, 7] as $status)
                        @php
                            $parcel = new \App\Models\Parcel(['status' => $status]);
                        @endphp
                        <div>{!! $parcel->status_badge !!}</div>
                    @endforeach
                </div>
            </div>

            <!-- Status Badge with Icon -->
            <div class="border rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-3">Status Badge with Icon</h3>
                <p class="text-sm text-gray-600 mb-2">Using: <code>$parcel->status_badge_with_icon</code></p>
                <div class="space-y-2">
                    @foreach([1, 2, 3, 4, 5, 6, 7] as $status)
                        @php
                            $parcel = new \App\Models\Parcel(['status' => $status]);
                        @endphp
                        <div>{!! $parcel->status_badge_with_icon !!}</div>
                    @endforeach
                </div>
            </div>

            <!-- Status Badge with Custom Attributes -->
            <div class="border rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-3">Status Badge with Custom Attributes</h3>
                <p class="text-sm text-gray-600 mb-2">Using: <code>$parcel->getStatusBadgeWithAttributes()</code></p>
                <div class="space-y-2">
                    @foreach([1, 2, 3, 4, 5, 6, 7] as $status)
                        @php
                            $parcel = new \App\Models\Parcel(['status' => $status]);
                        @endphp
                        <div>{!! $parcel->getStatusBadgeWithAttributes([
                            'onclick' => 'alert(\'Status: ' . $parcel->status_text . '\')',
                            'style' => 'cursor: pointer;',
                            'title' => 'Click for details'
                        ]) !!}</div>
                    @endforeach
                </div>
            </div>

            <!-- Status Badge with Icon and Custom Attributes -->
            <div class="border rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-3">Status Badge with Icon and Attributes</h3>
                <p class="text-sm text-gray-600 mb-2">Using: <code>$parcel->getStatusBadgeWithIconAndAttributes()</code></p>
                <div class="space-y-2">
                    @foreach([1, 2, 3, 4, 5, 6, 7] as $status)
                        @php
                            $parcel = new \App\Models\Parcel(['status' => $status]);
                        @endphp
                        <div>{!! $parcel->getStatusBadgeWithIconAndAttributes([
                            'onclick' => 'alert(\'Status: ' . $parcel->status_text . '\')',
                            'style' => 'cursor: pointer;',
                            'title' => 'Click for details'
                        ]) !!}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Usage Examples -->
        <div class="mt-8 border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-3">Usage Examples</h3>
            <div class="bg-gray-100 p-4 rounded">
                <h4 class="font-semibold mb-2">In Blade Templates:</h4>
                <pre class="text-sm"><code>// Basic usage
{!! $parcel->status_badge !!}

// With icon
{!! $parcel->status_badge_with_icon !!}

// With custom attributes
{!! $parcel->getStatusBadgeWithAttributes([
    'id' => 'status-' . $parcel->id,
    'onclick' => 'showDetails(' . $parcel->id . ')',
    'style' => 'cursor: pointer;'
]) !!}

// With icon and custom attributes
{!! $parcel->getStatusBadgeWithIconAndAttributes([
    'class' => 'custom-class',
    'data-parcel-id' => $parcel->id
]) !!}</code></pre>
            </div>
        </div>

        <!-- Available Attributes -->
        <div class="mt-6 border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-3">Available Attributes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold mb-2">Default Attributes:</h4>
                    <ul class="text-sm space-y-1">
                        <li><code>class</code> - CSS classes for styling</li>
                        <li><code>title</code> - Tooltip text</li>
                        <li><code>data-status</code> - Status code</li>
                        <li><code>data-status-text</code> - Status description</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Custom Attributes:</h4>
                    <ul class="text-sm space-y-1">
                        <li><code>id</code> - Element ID</li>
                        <li><code>onclick</code> - Click handler</li>
                        <li><code>style</code> - Inline styles</li>
                        <li><code>data-*</code> - Custom data attributes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 