<x-admin-layout>
    <x-slot name="header">Dashboard</x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card 
            label="Total Programs" 
            value="12" 
            trend="2"
            icon="programs"
            icon-bg="bg-blue-100"
            icon-color="text-lnu-blue" />
        
        <x-stat-card 
            label="Active Beneficiaries" 
            value="245" 
            trend="18"
            icon="beneficiaries"
            icon-bg="bg-yellow-100"
            icon-color="text-lnu-gold" />
        
        <x-stat-card 
            label="Communities" 
            value="8" 
            trend="0"
            icon="communities"
            icon-bg="bg-green-100"
            icon-color="text-green-600" />
        
        <x-stat-card 
            label="Budget Used" 
            value="₱2.4M" 
            trend="-5"
            icon="budget"
            icon-bg="bg-orange-100"
            icon-color="text-orange-600" />
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Under Construction -->
        <x-card>
            <div class="flex items-center justify-center py-16">
                <div class="text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Under Construction</h3>
                    <p class="text-gray-600">New dashboard features coming soon</p>
                </div>
            </div>
        </x-card>
    </div>
</x-admin-layout>
