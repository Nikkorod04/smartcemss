<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Generate Access Token</h2>
        <button wire:click="openModal" class="inline-flex items-center gap-2 px-4 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Generate Token
        </button>
    </div>

    <!-- Modal -->
    @if($isOpen)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" wire:click="closeModal">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4" wire:click.stop>
            <form wire:submit.prevent="generateToken" class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Generate Token</h3>
                    <button type="button" wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <p class="text-sm text-gray-600 mb-6">Choose how long this token should remain valid.</p>

                <!-- Expiration Type Selection -->
                <div class="space-y-3 mb-6">
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer {{ $expirationType === 'never' ? 'border-lnu-blue bg-blue-50' : '' }}">
                        <input type="radio" name="expirationType" wire:model.live="expirationType" value="never" class="w-4 h-4">
                        <div>
                            <p class="font-medium text-gray-900">Never Expires</p>
                            <p class="text-xs text-gray-600">Token remains valid indefinitely</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer {{ $expirationType === 'days' ? 'border-lnu-blue bg-blue-50' : '' }}">
                        <input type="radio" name="expirationType" wire:model.live="expirationType" value="days" class="w-4 h-4">
                        <div>
                            <p class="font-medium text-gray-900">Days from Now</p>
                            <p class="text-xs text-gray-600">Expires after specified days</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer {{ $expirationType === 'date' ? 'border-lnu-blue bg-blue-50' : '' }}">
                        <input type="radio" name="expirationType" wire:model.live="expirationType" value="date" class="w-4 h-4">
                        <div>
                            <p class="font-medium text-gray-900">Specific Date</p>
                            <p class="text-xs text-gray-600">Expires on selected date</p>
                        </div>
                    </label>
                </div>

                <!-- Days Input -->
                @if($expirationType === 'days')
                <div class="mb-6 animate-fadeIn">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Number of Days</label>
                    <input type="number" wire:model="expiresInDays" min="1" max="1095" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                    @error('expiresInDays')<span class="text-red-600 text-xs mt-1">{{ $message }}</span>@enderror
                    <p class="text-xs text-gray-500 mt-1">1-1095 days (up to 3 years)</p>
                </div>
                @endif

                <!-- Date Input -->
                @if($expirationType === 'date')
                <div class="mb-6 animate-fadeIn">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Expiration Date</label>
                    <input type="date" wire:model="expiresAt" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                    @error('expiresAt')<span class="text-red-600 text-xs mt-1">{{ $message }}</span>@enderror
                </div>
                @endif

                <!-- Success Message -->
                @if($generatedToken)
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm font-medium text-green-800 mb-3">✓ Token generated successfully!</p>
                    <p class="text-xs text-green-700 mb-3">Expires: <strong>{{ $generatedExpiration }}</strong></p>
                    
                    <div class="flex gap-2 items-center bg-white border border-green-300 rounded p-2 mb-3">
                        <code class="flex-1 text-xs font-mono break-all text-gray-700">{{ $generatedToken }}</code>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $generatedToken }}'); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000);" class="whitespace-nowrap px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition">
                            Copy
                        </button>
                    </div>
                    <p class="text-xs text-green-600">Share this token securely with the faculty member. It will not be shown again.</p>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    @if($generatedToken)
                    <button type="button" wire:click="closeModal" onclick="setTimeout(() => location.reload(), 300)" class="w-full px-4 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Close and Refresh
                    </button>
                    @else
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="flex-1 px-4 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" wire:loading wire:target="generateToken" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span wire:loading.remove wire:target="generateToken">Generate Token</span>
                        <span wire:loading wire:target="generateToken">Generating...</span>
                    </button>
                    <button type="button" wire:click="closeModal" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium rounded-lg transition">
                        Cancel
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-in-out;
    }
</style>
