<div class="space-y-6">
    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
        <!-- Search Input -->
        <div class="flex-1">
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Search programs..." 
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
            @foreach(['draft', 'ongoing', 'completed', 'cancelled'] as $s)
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

    <!-- Programs Grid -->
    @if ($programs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($programs as $program)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
                    <!-- Program Cover Image -->
                    <div class="relative w-full h-48 bg-gray-200 overflow-hidden">
                        @if($program->cover_image)
                            <img src="{{ asset('storage/' . $program->cover_image) }}" 
                                 alt="{{ $program->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-lnu-blue to-blue-700 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/30" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                                </svg>
                            </div>
                        @endif
                        <!-- Status Badge Overlay -->
                        <div class="absolute top-3 right-3">
                            <span class="inline-block capitalize px-3 py-1 rounded-full text-xs font-medium text-white bg-black/40 backdrop-blur-sm">
                                {{ $program->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Program Header with Status -->
                    <div class="p-6 pb-4">
                        <h3 class="text-lg font-bold text-lnu-blue line-clamp-2">
                            {{ $program->title }}
                        </h3>
                    </div>

                    <!-- Program Body -->
                    <div class="px-6 pb-6 space-y-4">
                        <!-- Description -->
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $program->description ?? 'No description' }}
                        </p>

                        <!-- Program Lead -->
                        <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($program->programLead->user->name) }}&background=003599&color=fff" 
                                 alt="{{ $program->programLead->user->name }}" 
                                 class="w-8 h-8 rounded-full">
                            <div>
                                <p class="text-xs text-gray-500">Program Lead</p>
                                <p class="text-sm font-medium text-gray-900">{{ $program->programLead->user->name }}</p>
                            </div>
                        </div>

                        <!-- Communities -->
                        <div>
                            <p class="text-xs text-gray-500 mb-2">Communities ({{ $program->communities->count() }})</p>
                            <div class="flex flex-wrap gap-1">
                                @forelse ($program->communities->take(3) as $community)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                        {{ Str::limit($community->name, 10) }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-500">No communities</span>
                                @endforelse
                                @if($program->communities->count() > 3)
                                    <span class="text-xs text-gray-500">+{{ $program->communities->count() - 3 }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Budget -->
                        <div class="pb-3 border-b border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Allocated Budget</p>
                            <p class="text-lg font-bold text-lnu-gold">₱{{ number_format($program->allocated_budget, 2) }}</p>
                        </div>

                        <!-- Progress Bar -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs text-gray-600">Progress</p>
                                <span class="text-xs font-medium text-gray-700">
                                    {{ $program->activity_progress }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-lnu-gold h-2 rounded-full" 
                                     style="width: {{ $program->activity_progress }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons - Show on Hover -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-2 opacity-0 group-hover:opacity-100 transition">
                        <a href="{{ route('programs.show', $program) }}" 
                           class="flex-1 px-3 py-2 bg-lnu-blue text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            View
                        </a>

                        @if (auth()->user()->role === 'director')
                            <a href="{{ route('programs.edit', $program) }}" 
                               class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium text-center flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                Edit
                            </a>

                            <button 
                                type="button"
                                wire:click="deleteProgram({{ $program->id }})"
                                wire:confirm="Are you sure you want to delete this program? This action cannot be undone."
                                class="flex-1 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium text-center flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Delete
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $programs->links() }}
        </div>
    @else
        <!-- No Programs Found -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-600 mb-4">No programs found</p>
            @if (auth()->user()->role === 'director')
                <a href="{{ route('programs.create') }}" class="inline-block px-6 py-2 bg-lnu-blue text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Create Your First Program
                </a>
            @endif
        </div>
    @endif
</div>
