<div class="space-y-6">
    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
        <!-- Search Input -->
        <div class="flex-1">
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Search activities..." 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-lnu-blue focus:outline-none transition"
            />
        </div>

        <!-- Status Filter -->
        <div class="flex gap-2 flex-wrap">
            <button 
                wire:click="$set('status', '')"
                @class([
                    'px-4 py-2 rounded font-medium transition',
                    'bg-lnu-blue text-white' => !$this->status,
                    'bg-gray-100 text-gray-700 hover:bg-gray-200' => $this->status
                ])
            >
                All
            </button>
            @foreach(['pending', 'ongoing', 'completed'] as $s)
                <button 
                    wire:click="$set('status', '{{ $s }}')"
                    @class([
                        'px-4 py-2 rounded font-medium transition capitalize',
                        'bg-lnu-blue text-white' => $this->status === $s,
                        'bg-gray-100 text-gray-700 hover:bg-gray-200' => $this->status !== $s
                    ])
                >
                    {{ $s }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Activities Grid -->
    @if ($activities->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($activities as $activity)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
                    <!-- Activity Header Banner -->
                    <div class="bg-gray-50 p-6 relative border-b border-gray-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-lnu-blue line-clamp-3">
                                    {{ $activity->title }}
                                </h3>
                                <p class="text-gray-400 text-sm mt-2">
                                    {{ $activity->extensionProgram->title }}
                                </p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="inline-block capitalize px-3 py-1 rounded-full text-xs font-medium text-gray-700 bg-gray-200">
                                {{ $activity->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Activity Body -->
                    <div class="p-6 space-y-4">
                        <!-- Description -->
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $activity->description ?? 'No description' }}
                        </p>

                        <!-- Date Range -->
                        <div class="space-y-2 pb-3 border-b border-gray-200">
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v2h16V7a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v2H7V3a1 1 0 00-1-1zm0 5a2 2 0 002 2h8a2 2 0 002-2H6z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-600">
                                    @if($activity->actual_start_date && $activity->actual_end_date)
                                        {{ $activity->actual_start_date->format('M d') }} - {{ $activity->actual_end_date->format('M d, Y') }}
                                    @else
                                        No dates set
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-600">{{ $activity->venue ?? 'Venue not specified' }}</span>
                            </div>
                        </div>

                        <!-- Faculties -->
                        <div>
                            <p class="text-xs text-gray-500 mb-2">Assigned Faculties ({{ $activity->faculties->count() }})</p>
                            @if($activity->faculties->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($activity->faculties->take(3) as $faculty)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                            {{ Str::limit($faculty->user->name, 12) }}
                                        </span>
                                    @empty
                                    @endforelse
                                    @if($activity->faculties->count() > 3)
                                        <span class="text-xs text-gray-500">+{{ $activity->faculties->count() - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-gray-500">No faculties assigned</span>
                            @endif
                        </div>

                        <!-- Attendance -->
                        <div class="pb-3 border-b border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Total Attendance</p>
                            <p class="text-lg font-bold text-lnu-gold">{{ $activity->attendances->count() }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons - Show on Hover -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-2 opacity-0 group-hover:opacity-100 transition">
                        <a href="{{ route('activities.show', $activity) }}" 
                           class="flex-1 px-3 py-2 bg-lnu-blue text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            View
                        </a>

                        <a href="{{ route('activities.edit', $activity) }}" 
                           class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>
                            Edit
                        </a>

                        <button wire:click="deleteActivity({{ $activity->id }})" 
                                wire:confirm="Are you sure you want to delete this activity?"
                                class="flex-1 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $activities->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No activities found</h3>
            <p class="text-gray-600 mb-6">Get started by creating your first activity for a program</p>
            <a href="{{ route('activities.create') }}" class="inline-flex items-center gap-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Create First Activity
            </a>
        </div>
    @endif
</div>
