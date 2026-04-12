<x-admin-layout header="Program Details">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-lnu-blue">{{ $program->title }}</h1>
                <div class="flex items-center gap-4 mt-2">
                    <span class="inline-block capitalize px-3 py-1 rounded-full text-sm font-medium
                        @if($program->status === 'draft') bg-yellow-100 text-yellow-800
                        @elseif($program->status === 'ongoing') bg-green-100 text-green-800
                        @elseif($program->status === 'completed') bg-blue-100 text-blue-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($program->status) }}
                    </span>
                    <span class="text-gray-600 text-sm">Created {{ $program->created_at->diffForHumans() }}</span>
                </div>
            </div>

            @if (auth()->user()->role === 'director')
            <div class="flex gap-2">
                <a href="{{ route('programs.edit', $program) }}" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('programs.destroy', $program) }}" class="inline"
                      onsubmit="return confirm('Are you sure? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Cover Image Section -->
        @if ($program->cover_image)
        <div class="rounded-lg overflow-hidden shadow-md mb-6">
            <img src="{{ asset('storage/' . $program->cover_image) }}" alt="{{ $program->title }}" class="w-full h-64 object-cover">
        </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Left Column (2/3 width) -->
            <div class="col-span-2 space-y-6">
                <!-- Overview -->
                <x-card>
                    <div class="mb-4">
                        <h2 class="text-xl font-semibold text-lnu-blue mb-4">Overview</h2>
                    </div>
                    <p class="text-gray-700 mb-4">{{ $program->description }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Goals</p>
                            <p class="text-gray-900">{{ $program->goals }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Objectives</p>
                            <p class="text-gray-900">{{ $program->objectives }}</p>
                        </div>
                    </div>
                </x-card>

                <!-- Timeline -->
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Timeline</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-l-4 border-lnu-blue pl-4">
                            <p class="text-sm text-gray-600">Start Date</p>
                            <p class="text-lg font-semibold text-lnu-blue">
                                {{ $program->planned_start_date->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="border-l-4 border-lnu-gold pl-4">
                            <p class="text-sm text-gray-600">End Date</p>
                            <p class="text-lg font-semibold text-lnu-gold">
                                {{ $program->planned_end_date->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Duration</p>
                        <p class="text-sm font-medium">
                            {{ $program->planned_start_date->diffInDays($program->planned_end_date) }} days
                        </p>
                    </div>
                </x-card>

                <!-- Communities -->
                @if ($program->communities->count() > 0)
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Partner Communities ({{ $program->communities->count() }})</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($program->communities as $community)
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full hover:bg-blue-200 transition">
                            {{ $community->name }}
                        </span>
                        @endforeach
                    </div>
                </x-card>
                @endif

                <!-- Activities -->
                @if ($program->activities->count() > 0)
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Activities ({{ $program->activities->count() }})</h2>
                    <div class="space-y-3">
                        @foreach ($program->activities as $activity)
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-200 last:border-b-0">
                            <span class="inline-block capitalize px-3 py-1 rounded-full text-sm font-medium
                                @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($activity->status === 'ongoing') bg-green-100 text-green-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ $activity->status }}
                            </span>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $activity->title }}</p>
                                <p class="text-sm text-gray-600">{{ $activity->description }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </x-card>
                @endif

                <!-- Notes -->
                @if ($program->notes)
                <x-card>
                    <h2 class="text-lg font-semibold text-lnu-blue mb-2">Notes</h2>
                    <p class="text-gray-700">{{ $program->notes }}</p>
                </x-card>
                @endif

                <!-- Gallery Images -->
                @php
                    $galleryImages = $program->gallery_images;
                    if (!is_array($galleryImages)) {
                        $galleryImages = [];
                    }
                @endphp
                @if (count($galleryImages) > 0)
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Gallery ({{ count($galleryImages) }})</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($galleryImages as $image)
                        <div class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition">
                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery" class="w-full h-40 object-cover group-hover:scale-110 transition duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <a href="{{ asset('storage/' . $image) }}" target="_blank" class="text-white bg-lnu-blue px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800">
                                    View Full
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </x-card>
                @endif

                <!-- Attachments -->
                @if ($program->attachments && is_array($program->attachments) && count($program->attachments) > 0)
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Attachments ({{ count($program->attachments) }})</h2>
                    <div class="space-y-2">
                        @foreach ($program->attachments as $attachment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                @if (str_ends_with($attachment, '.pdf'))
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                                </svg>
                                @else
                                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                                </svg>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ basename($attachment) }}</p>
                                    <p class="text-xs text-gray-600">{{ strtoupper(pathinfo($attachment, PATHINFO_EXTENSION)) }} Document</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $attachment) }}" download class="inline-flex items-center gap-2 px-4 py-2 bg-lnu-blue text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                        </div>
                        @endforeach
                    </div>
                </x-card>
                @endif
            </div>

            <!-- Right Column (1/3 width) -->
            <div class="space-y-6">
                <!-- Program Stats -->
                <x-card>
                    <h2 class="text-lg font-semibold text-lnu-blue mb-4">Program Summary</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Program Lead</p>
                            <div class="flex items-center gap-2 mt-1">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($program->programLead->user->name) }}&background=003599&color=fff" 
                                     alt="{{ $program->programLead->user->name }}" 
                                     class="w-8 h-8 rounded-full">
                                <span class="font-medium">{{ $program->programLead->user->name }}</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-600">Target Beneficiaries</p>
                            <p class="text-2xl font-bold text-lnu-blue">{{ $program->target_beneficiaries }}</p>
                        </div>

                        <div class="border-t border-gray-200 pt-4" x-data="{ showBudgetSources: false }">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-gray-600">Allocated Budget</p>
                                <button type="button" 
                                        class="text-gray-400 hover:text-lnu-blue transition-colors p-1 rounded hover:bg-gray-100"
                                        title="View budget source details"
                                        @click="showBudgetSources = !showBudgetSources">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 8a1 1 0 000 2h6a1 1 0 100-2H8z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-2xl font-bold text-lnu-gold">₱{{ number_format($program->allocated_budget, 2) }}</p>
                            
                            <!-- Budget Sources Summary (collapsible) -->
                            <div x-show="showBudgetSources" 
                                 x-transition 
                                 class="mt-4 pt-4 border-t border-gray-200 space-y-3"
                                 style="display: none;">
                                @php
                                $budgetSources = $program->budgetUtilizations()
                                    ->whereNotNull('budget_source')
                                    ->distinct('budget_source')
                                    ->pluck('budget_source');
                                @endphp
                                
                                @if($budgetSources->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($budgetSources as $source)
                                            <div class="bg-blue-50 rounded p-2 text-sm">
                                                <p class="font-medium text-blue-900">{{ $source }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                    <a href="#budget-details" class="inline-block text-xs text-lnu-blue hover:underline font-medium">
                                        View full details ↓
                                    </a>
                                @else
                                    <p class="text-xs text-gray-500 italic">No budget source information recorded yet</p>
                                @endif
                            </div>
                        </div>

                        @if ($program->budgetUtilizations->count() > 0)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-600">Budget Utilized</p>
                            @php
                            $totalUtilized = $program->budgetUtilizations->sum('amount');
                            $percentage = ($totalUtilized / $program->allocated_budget) * 100;
                            @endphp
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm">₱{{ number_format($totalUtilized, 2) }}</span>
                                <span class="text-sm font-medium">{{ number_format($percentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-lnu-gold h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                        </div>
                        @endif

                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-600 mb-3">Partner Organizations</p>
                            @php
                                $partners = is_array($program->partners) ? $program->partners : json_decode($program->partners, true) ?? [];
                            @endphp
                            @if (!empty($partners) && count($partners) > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach ($partners as $partner)
                                <span class="inline-block px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">
                                    {{ $partner }}
                                </span>
                                @endforeach
                            </div>
                            @else
                            <p class="text-xs text-gray-500">No partners listed</p>
                            @endif
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-gray-600">Overall Progress</p>
                                <span class="text-sm font-semibold text-lnu-gold">{{ $program->activity_progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-lnu-blue to-lnu-gold h-3 rounded-full transition-all" 
                                     style="width: {{ $program->activity_progress }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Based on activity completion rate</p>
                        </div>
                    </div>
                </x-card>

                <!-- Partners -->
                @if ($program->partners && is_array($program->partners) && count($program->partners) > 0)
                <x-card>
                    <h2 class="text-lg font-semibold text-lnu-blue mb-4">Partner Organizations</h2>
                    <ul class="space-y-2">
                        @foreach ($program->partners as $partner)
                        <li class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-lnu-blue rounded-full"></span>
                            <span class="text-sm">{{ $partner }}</span>
                        </li>
                        @endforeach
                    </ul>
                </x-card>
                @endif

                <!-- Back Button -->
                <a href="{{ route('programs.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-900 rounded-lg text-center font-medium hover:bg-gray-300 transition">
                    Back to Programs
                </a>
            </div>
        </div>

        <!-- Budget Utilization Section -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <livewire:budget-utilization-table :programId="$program->id" />
        </div>
    </div>
</x-admin-layout>
