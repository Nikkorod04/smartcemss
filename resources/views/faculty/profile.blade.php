<x-admin-layout>
    <x-slot name="header">
        Faculty Profile
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <!-- Avatar & Name Section -->
            <div class="flex items-start gap-6 mb-8">
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



            <!-- Personal Information Section -->
            <div class="mb-8">
                <div class="group flex items-start justify-between mb-6 p-4 rounded-lg hover:bg-gray-50 transition cursor-pointer">
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
            </div>

            <!-- Professional Information Section -->
            <div class="mb-8">
                <div class="group flex items-start justify-between mb-6 p-4 rounded-lg hover:bg-gray-50 transition cursor-pointer">
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
            </div>

            <!-- Address Section -->
            <div>
                <div class="group flex items-start justify-between mb-6 p-4 rounded-lg hover:bg-gray-50 transition cursor-pointer">
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

        <!-- Programs Section -->
        @if(count($programsLed) > 0 || count($programsInvolved) > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">My Programs</h2>
            
            <!-- Programs Led -->
            @if(count($programsLed) > 0)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Leading</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($programsLed as $program)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-start justify-between mb-3">
                            <h4 class="font-semibold text-gray-900">{{ $program->title }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Lead
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($program->description ?? 'No description', 100) }}</p>
                        <div class="pt-4 border-t border-gray-200 flex items-center justify-between text-sm">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium text-green-600">{{ $program->status ?? 'Active' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Programs Involved -->
            @if(count($programsInvolved) > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Involved</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($programsInvolved as $program)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-start justify-between mb-3">
                            <h4 class="font-semibold text-gray-900">{{ $program->title }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Involved
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($program->description ?? 'No description', 100) }}</p>
                        <div class="pt-4 border-t border-gray-200 flex items-center justify-between text-sm">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium text-green-600">{{ $program->status ?? 'Active' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

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
        @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <p class="text-gray-600 text-lg">No activities yet. Start participating in extension programs!</p>
        </div>
        @endif
    </div>
</div>

<!-- Success Alert Script -->
<script>
    document.addEventListener('livewire:navigated', () => {
        // Listen for success alerts from Livewire components
    });

    Livewire.on('alert', (data) => {
        // Show success alert using Livewire event
        alert(data.message || 'Changes saved successfully!');
    });
</script>
</x-admin-layout>
