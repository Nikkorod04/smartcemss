@props(['show' => false, 'title' => 'Success', 'message' => '', 'icon' => 'success'])

<div x-data="{ open: @js($show) }" 
     x-show="open"
     @class(['fixed inset-0 z-50 flex items-center justify-center p-4 animate-fade-in', 'hidden' => !$show])
     style="display: {{ $show ? 'flex' : 'none' }}">
    
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-50" @click="open = false"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white rounded-lg shadow-2xl max-w-md w-full p-6 animate-scale-in">
        <!-- Close Button -->
        <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Icon -->
        <div class="flex justify-center mb-4">
            @if($icon === 'success')
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            @elseif($icon === 'error')
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            @elseif($icon === 'warning')
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0H9m3 0h3" />
                    </svg>
                </div>
            @else
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-lnu-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            @endif
        </div>

        <!-- Title -->
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">{{ $title }}</h3>

        <!-- Message -->
        @if($message)
            <p class="text-gray-600 text-center mb-6">{{ $message }}</p>
        @else
            {{ $slot }}
        @endif

        <!-- Button -->
        <button @click="open = false" class="w-full btn-primary py-2">
            Close
        </button>
    </div>
</div>
