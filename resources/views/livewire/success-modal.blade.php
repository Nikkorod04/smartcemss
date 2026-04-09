@if($show)
<div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" wire:click="closeModal">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden" wire:click.stop>
        <!-- Header -->
        <div class="px-6 py-4 {{ $type === 'success' ? 'bg-green-50 border-b-4 border-green-500' : 'bg-red-50 border-b-4 border-red-500' }}">
            <div class="flex items-center gap-3">
                @if($type === 'success')
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-green-900">{{ $title }}</h2>
                @else
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-red-900">{{ $title }}</h2>
                @endif
            </div>
        </div>

        <!-- Body -->
        <div class="px-6 py-4">
            <p class="text-gray-700">{{ $message }}</p>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
            <button 
                wire:click="closeAndReload"
                class="flex-1 px-4 py-2 {{ $type === 'success' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white font-medium rounded-lg transition"
            >
                OK
            </button>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener('livewire:navigated', () => {
        Livewire.on('reload-page', () => {
            window.location.reload();
        });
    });

    Livewire.on('reload-page', () => {
        window.location.reload();
    });
</script>
