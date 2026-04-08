@props(['type' => 'info', 'title' => '', 'dismissible' => true])

@php
    $colors = [
        'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-800', 'icon' => 'text-green-600'],
        'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'icon' => 'text-red-600'],
        'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-800', 'icon' => 'text-yellow-600'],
        'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'icon' => 'text-blue-600'],
    ];
    $color = $colors[$type] ?? $colors['info'];
@endphp

<div x-data="{ open: true }"
     x-show="open"
     x-transition
     @class(["rounded-lg border-l-4 p-4 animate-fade-in", $color['bg'], $color['border']])>
    <div class="flex items-start gap-3">
        <!-- Icon -->
        <div class="flex-shrink-0">
            @if($type === 'success')
                <svg class="w-5 h-5 {{ $color['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                </svg>
            @elseif($type === 'error')
                <svg class="w-5 h-5 {{ $color['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                </svg>
            @elseif($type === 'warning')
                <svg class="w-5 h-5 {{ $color['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
                </svg>
            @else
                <svg class="w-5 h-5 {{ $color['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                </svg>
            @endif
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            @if($title)
                <h3 @class(["text-sm font-medium", $color['text']])>{{ $title }}</h3>
                <div @class(["text-sm mt-1", $color['text']])>
                    {{ $slot }}
                </div>
            @else
                <div @class(["text-sm", $color['text']])>
                    {{ $slot }}
                </div>
            @endif
        </div>

        <!-- Close Button -->
        @if($dismissible)
            <button @click="open = false" class="flex-shrink-0 text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>
</div>
