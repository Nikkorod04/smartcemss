<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <!-- Profile Header -->
            <div class="flex items-start justify-between mb-8">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 relative group">
                        @if($faculty->avatar)
                            <img src="{{ asset('icons/' . $faculty->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-lnu-blue to-blue-700 rounded-full flex items-center justify-center text-white text-4xl font-bold">
                                {{ strtoupper(substr($faculty->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <button x-on:click="$dispatch('open-modal', 'select-avatar')" class="absolute bottom-0 right-0 bg-lnu-blue text-white p-2 rounded-full shadow-lg hover:bg-blue-700 transition opacity-0 group-hover:opacity-100">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                            </svg>
                        </button>
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

    <!-- Avatar Selection Modal -->
    <x-modal name="select-avatar" title="Select Avatar" max-width="md">
        <button x-on:click="$dispatch('close')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div class="space-y-6">
            <p class="text-gray-600">Choose your default avatar:</p>
            <div class="grid grid-cols-2 gap-6">
                <!-- Man Avatar Option -->
                <button type="button" @click="@this.call('updateAvatar', 'man.png')" class="flex flex-col items-center focus:outline-none group">
                    <div class="w-32 h-32 rounded-full border-4 @if($faculty->avatar === 'man.png') border-lnu-blue @else border-gray-200 group-hover:border-gray-300 @endif overflow-hidden mb-4 transition cursor-pointer">
                        <img src="{{ asset('icons/man.png') }}" alt="Man Avatar" class="w-full h-full object-cover">
                    </div>
                    <span class="font-semibold text-gray-900">Man</span>
                    @if($faculty->avatar === 'man.png')
                    <span class="text-xs text-lnu-blue font-medium mt-2">✓ Selected</span>
                    @endif
                </button>

                <!-- Woman Avatar Option -->
                <button type="button" @click="@this.call('updateAvatar', 'woman.png')" class="flex flex-col items-center focus:outline-none group">
                    <div class="w-32 h-32 rounded-full border-4 @if($faculty->avatar === 'woman.png') border-lnu-blue @else border-gray-200 group-hover:border-gray-300 @endif overflow-hidden mb-4 transition cursor-pointer">
                        <img src="{{ asset('icons/woman.png') }}" alt="Woman Avatar" class="w-full h-full object-cover">
                    </div>
                    <span class="font-semibold text-gray-900">Woman</span>
                    @if($faculty->avatar === 'woman.png')
                    <span class="text-xs text-lnu-blue font-medium mt-2">✓ Selected</span>
                    @endif
                </button>
            </div>
        </div>

        <x-slot name="footer">
            <button x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition font-medium">
                Close
            </button>
        </x-slot>
    </x-modal>
</div>
