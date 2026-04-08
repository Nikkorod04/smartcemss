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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activities -->
        <div class="lg:col-span-2">
            <x-card title="Recent Activities">
                <div class="space-y-4">
                    <!-- Activity Item 1 -->
                    <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-50 transition">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-lnu-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">New Program Created</p>
                            <p class="text-sm text-gray-600">Community Health Initiative launched</p>
                            <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                        </div>
                    </div>

                    <!-- Activity Item 2 -->
                    <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-50 transition">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Attendance Recorded</p>
                            <p class="text-sm text-gray-600">45 participants in Skills Training</p>
                            <p class="text-xs text-gray-500 mt-1">5 hours ago</p>
                        </div>
                    </div>

                    <!-- Activity Item 3 -->
                    <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-50 transition">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-lnu-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Budget Approved</p>
                            <p class="text-sm text-gray-600">₱150,000 for Environmental Program</p>
                            <p class="text-xs text-gray-500 mt-1">1 day ago</p>
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <a href="#" class="text-sm text-lnu-blue hover:text-lnu-gold font-medium">View all →</a>
                </x-slot>
            </x-card>
        </div>

        <!-- Sidebar Widgets -->
        <div class="space-y-6">
            <!-- Program Status -->
            <x-card title="Program Status">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            Draft
                        </span>
                        <span class="text-sm font-semibold text-lnu-blue">3</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            Ongoing
                        </span>
                        <span class="text-sm font-semibold text-lnu-blue">7</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 flex items-center gap-2">
                            <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                            Completed
                        </span>
                        <span class="text-sm font-semibold text-lnu-blue">2</span>
                    </div>
                </div>
            </x-card>

            <!-- Quick Actions -->
            <x-card title="Quick Actions">
                <div class="space-y-2">
                    <button class="w-full btn-primary text-sm py-2">
                        + New Program
                    </button>
                    <button class="w-full btn-secondary text-sm py-2">
                        Generate Report
                    </button>
                </div>
            </x-card>
        </div>
    </div>
</x-admin-layout>
