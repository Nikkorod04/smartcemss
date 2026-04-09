<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <!-- Profile Header -->
            <div class="flex items-start justify-between mb-8">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 bg-gradient-to-br from-lnu-blue to-blue-700 rounded-full flex items-center justify-center text-white text-4xl font-bold">
                            {{ strtoupper(substr($faculty->user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $faculty->user->name }}</h1>
                        <p class="text-gray-600 mt-1">{{ $faculty->position ?? 'No Position Set' }} | {{ $faculty->department ?? 'No Department' }}</p>
                        <div class="mt-4 inline-flex items-center gap-2 px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                            <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                            Faculty Member
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 pb-8 border-b border-gray-200">
                <!-- Programs Count -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Programs Involved</p>
                            <p class="text-2xl font-bold text-lnu-blue mt-1">{{ count($programsLed) + count($programsInvolved) }}</p>
                        </div>
                        <svg class="w-8 h-8 text-lnu-blue opacity-30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                        </svg>
                    </div>
                </div>

                <!-- Hours Count -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Hours</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ $totalHours }}</p>
                        </div>
                        <svg class="w-8 h-8 text-green-600 opacity-30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                        </svg>
                    </div>
                </div>

                <!-- Activities Count -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Activities</p>
                            <p class="text-2xl font-bold text-purple-600 mt-1">{{ count($recentActivities) }}</p>
                        </div>
                        <svg class="w-8 h-8 text-purple-600 opacity-30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 13h-6v6h6v-6zm0-6h-6v6h6V7zM13 7H7v6h6V7zM7 13H1v6h6v-6zm12-12H1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V2c0-1.1-.9-2-2-2z" />
                        </svg>
                    </div>
                </div>

                <!-- Pending Submissions -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Pending Submissions</p>
                            <p class="text-2xl font-bold text-yellow-600 mt-1">0</p>
                        </div>
                        <svg class="w-8 h-8 text-yellow-600 opacity-30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 10.5V7c0 .55-.45 1-1 1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Profile Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Personal Information -->
                <div class="group flex items-start justify-between p-4 rounded-lg hover:bg-gray-50 transition cursor-pointer border border-gray-100">
                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-gray-900">{{ $faculty->user->email }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium text-gray-900">{{ $faculty->phone ?? 'Not Set' }}</span>
                            </div>
                        </div>
                    </div>
                    <livewire:edit-personal-info-modal :faculty="$faculty" />
                </div>

                <!-- Professional Information -->
                <div class="group flex items-start justify-between p-4 rounded-lg hover:bg-gray-50 transition cursor-pointer border border-gray-100">
                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Information</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Department:</span>
                                <span class="font-medium text-gray-900">{{ $faculty->department ?? 'Not Set' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Position:</span>
                                <span class="font-medium text-gray-900">{{ $faculty->position ?? 'Not Set' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Specialization:</span>
                                <span class="font-medium text-gray-900">{{ $faculty->specialization ?? 'Not Set' }}</span>
                            </div>
                        </div>
                    </div>
                    <livewire:edit-professional-info-modal :faculty="$faculty" />
                </div>

                <!-- Address -->
                <div class="group flex items-start justify-between p-4 rounded-lg hover:bg-gray-50 transition cursor-pointer border border-gray-100 md:col-span-2">
                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Address</h3>
                        <div>
                            <p class="text-gray-600">{{ $faculty->address ?? 'No Address Set' }}</p>
                        </div>
                    </div>
                    <livewire:edit-address-modal :faculty="$faculty" />
                </div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        @if(count($recentActivities) > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Recent Activities</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($recentActivities as $activity)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-grow">
                                <h4 class="font-semibold text-gray-900">{{ $activity->title ?? 'Activity' }}</h4>
                                <p class="text-gray-600 text-sm mt-1">
                                    @if($activity->actual_start_date)
                                        Started: {{ $activity->actual_start_date->format('M d, Y') }}
                                    @else
                                        Scheduled: {{ $activity->planned_start_date?->format('M d, Y') ?? 'TBD' }}
                                    @endif
                                </p>
                                @if($activity->description)
                                <p class="text-gray-600 text-sm mt-2">{{ Str::limit($activity->description, 150) }}</p>
                                @endif
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $activity->status ?? 'Active' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
