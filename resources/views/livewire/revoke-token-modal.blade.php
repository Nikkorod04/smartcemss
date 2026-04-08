<div>
    <!-- Revoke Button -->
    <button type="button" wire:click="openConfirmation" class="text-red-600 hover:text-red-700 font-medium transition">
        Revoke
    </button>

    <!-- Confirmation Modal -->
    @if($showConfirm)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" wire:click="closeConfirmation">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4" wire:click.stop>
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Revoke Token</h3>
                    <button type="button" wire:click="closeConfirmation" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Warning Message -->
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-800">
                        <strong>Warning:</strong> Revoking this token will immediately revoke the faculty member's access. This action cannot be undone.
                    </p>
                </div>

                <!-- Token Info -->
                <div class="mb-6 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-xs text-gray-600 mb-1">Token:</p>
                    <p class="text-sm font-mono text-gray-700">{{ $tokenPreview }}</p>
                </div>

                <!-- Expiration Info -->
                @if($token->expires_at)
                <div class="mb-6 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-xs text-gray-600 mb-1">Expires:</p>
                    <p class="text-sm text-gray-700">
                        {{ $token->expires_at->format('M d, Y') }}
                        <span class="text-xs {{ $token->isValid() ? 'text-green-600' : 'text-red-600' }}">
                            ({{ $token->getExpirationStatus() }})
                        </span>
                    </p>
                </div>
                @else
                <div class="mb-6 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-xs text-gray-600 mb-1">Expires:</p>
                    <p class="text-sm text-gray-700">Never</p>
                </div>
                @endif

                <!-- Confirmation Text -->
                <p class="text-sm text-gray-600 mb-6">Are you sure you want to revoke this token? The faculty member will lose access immediately.</p>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button type="button" wire:click="revokeToken" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" wire:loading wire:target="revokeToken" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span wire:loading.remove wire:target="revokeToken">Revoke Token</span>
                        <span wire:loading wire:target="revokeToken">Revoking...</span>
                    </button>
                    <button type="button" wire:click="closeConfirmation" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium rounded-lg transition">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Success Modal -->
    @if($showSuccess)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" wire:click="closeSuccess">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4" wire:click.stop>
            <div class="p-6 text-center">
                <!-- Success Icon -->
                <div class="mb-4 flex justify-center">
                    <div class="flex items-center justify-center w-16 h-16 rounded-full bg-green-100">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Success Message -->
                <h3 class="text-lg font-bold text-gray-900 mb-2">Token Revoked Successfully</h3>
                <p class="text-sm text-gray-600 mb-6">The access token has been revoked. The faculty member will lose access immediately.</p>

                <!-- OK Button -->
                <button type="button" wire:click="closeSuccess" onclick="setTimeout(() => location.reload(), 300)" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                    OK
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
