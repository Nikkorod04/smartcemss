@php
    use App\Http\Controllers\TimelineController;
@endphp

<x-admin-layout header="Program Timeline">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Header with Back Button -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('programs.show', $program) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition text-lnu-blue hover:text-blue-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-lnu-blue">{{ $program->title }}</h1>
                <p class="text-gray-600 text-sm mt-1">Timeline & Activity Schedule</p>
            </div>
        </div>

        <!-- Legend -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <p class="text-sm font-semibold text-gray-700 mb-3">Timeline Legend</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-blue-400"></div>
                    <span class="text-sm text-gray-700">Planned</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-green-500"></div>
                    <span class="text-sm text-gray-700">Completed</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-yellow-400"></div>
                    <span class="text-sm text-gray-700">Ongoing</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-red-400"></div>
                    <span class="text-sm text-gray-700">Cancelled</span>
                </div>
            </div>
        </div>

        <!-- Program Timeline Bar -->
        @if($program->planned_start_date && $program->planned_end_date)
        <div class="mb-8">
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Program Timeline</h3>
                <p class="text-sm text-gray-600">
                    {{ $program->planned_start_date->format('M d, Y') }} → {{ $program->planned_end_date->format('M d, Y') }}
                    ({{ $program->planned_start_date->diffInDays($program->planned_end_date) + 1 }} days)
                </p>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="relative w-full h-8 bg-gray-100 rounded-lg overflow-hidden">
                    <!-- Program bar -->
                    <div class="absolute top-0 h-full bg-gradient-to-r from-lnu-blue to-blue-600 rounded-lg shadow"
                         style="left: 0%; width: 100%;"
                         title="{{ $program->planned_start_date->format('M d, Y') }} - {{ $program->planned_end_date->format('M d, Y') }}">
                    </div>
                    <!-- Timeline labels -->
                    <div class="absolute inset-0 flex items-center justify-between px-3 text-white text-xs font-semibold">
                        <span>Start</span>
                        <span>End</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Activities Timeline -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Activities</h3>
            
            @if($activities->count() > 0)
                <div class="space-y-4">
                    <!-- Activities List -->
                    @foreach($activities as $activity)
                    @php
                        $planedStartPos = TimelineController::calculateBarPosition($activity->planned_start_date, $timelineData['startDate'], $timelineData['totalDays']);
                        $plannedWidth = TimelineController::calculateBarWidth($activity->planned_start_date, $activity->planned_end_date, $timelineData['startDate'], $timelineData['totalDays']);
                        
                        $actualStartPos = TimelineController::calculateBarPosition($activity->actual_start_date, $timelineData['startDate'], $timelineData['totalDays']);
                        $actualWidth = TimelineController::calculateBarWidth($activity->actual_start_date, $activity->actual_end_date, $timelineData['startDate'], $timelineData['totalDays']);
                        
                        // Color by status
                        $statusColor = match($activity->status) {
                            'completed' => 'bg-green-500 hover:bg-green-600',
                            'ongoing' => 'bg-yellow-400 hover:bg-yellow-500',
                            'cancelled' => 'bg-red-400 hover:bg-red-500',
                            default => 'bg-blue-400 hover:bg-blue-500',
                        };
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <!-- Activity Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $activity->title }}</h4>
                                <div class="flex items-center gap-3 mt-1 text-sm">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->status === 'completed') bg-green-100 text-green-800
                                        @elseif($activity->status === 'ongoing') bg-yellow-100 text-yellow-800
                                        @elseif($activity->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                    @if($activity->venue)
                                        <span class="text-gray-600">📍 {{ $activity->venue }}</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('activities.show', $activity) }}" class="text-lnu-blue hover:text-blue-800 text-sm font-medium">
                                View Details →
                            </a>
                        </div>

                        <!-- Dates Summary -->
                        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                            <div class="bg-blue-50 border border-blue-200 rounded p-2">
                                <p class="text-xs text-gray-600 font-semibold">Planned</p>
                                @if($activity->planned_start_date && $activity->planned_end_date)
                                    <p class="text-gray-800">{{ $activity->planned_start_date->format('M d') }} - {{ $activity->planned_end_date->format('M d') }}</p>
                                @else
                                    <p class="text-gray-500 italic">No planned dates</p>
                                @endif
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded p-2">
                                <p class="text-xs text-gray-600 font-semibold">Actual</p>
                                @if($activity->actual_start_date && $activity->actual_end_date)
                                    <p class="text-gray-800">{{ $activity->actual_start_date->format('M d') }} - {{ $activity->actual_end_date->format('M d') }}</p>
                                @elseif($activity->actual_start_date)
                                    <p class="text-gray-800">Started: {{ $activity->actual_start_date->format('M d') }}</p>
                                @else
                                    <p class="text-gray-500 italic">Not started</p>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline Bar -->
                        <div class="relative w-full h-12 bg-gray-100 rounded-lg overflow-hidden">
                            <!-- Planned bar (lighter) -->
                            @if($activity->planned_start_date && $activity->planned_end_date && $plannedWidth > 0)
                            <div class="absolute top-1 h-4 bg-blue-300 rounded opacity-60"
                                 style="left: {{ $planedStartPos }}%; width: {{ $plannedWidth }}%;"
                                 title="Planned: {{ $activity->planned_start_date->format('M d, Y') }} - {{ $activity->planned_end_date->format('M d, Y') }}">
                            </div>
                            @endif

                            <!-- Actual bar (darker) -->
                            @if($activity->actual_start_date && $activity->actual_end_date && $actualWidth > 0)
                            <div class="absolute bottom-1 h-4 {{ $statusColor }} rounded shadow"
                                 style="left: {{ $actualStartPos }}%; width: {{ $actualWidth }}%;"
                                 title="Actual: {{ $activity->actual_start_date->format('M d, Y') }} - {{ $activity->actual_end_date->format('M d, Y') }}">
                            </div>
                            @endif

                            <!-- Empty state message -->
                            @if((!$activity->planned_start_date || !$activity->planned_end_date) && (!$activity->actual_start_date || !$activity->actual_end_date))
                            <div class="absolute inset-0 flex items-center justify-center text-gray-500 text-xs">
                                No dates set
                            </div>
                            @endif
                        </div>

                        <!-- Info Row -->
                        <div class="flex items-center justify-between mt-3 text-xs text-gray-600">
                            <div class="flex gap-4">
                                @if($activity->description)
                                    <span title="{{ $activity->description }}">📝 Has description</span>
                                @endif
                                @if($activity->allocated_budget > 0)
                                    <span>₱{{ number_format($activity->allocated_budget, 2) }} budget</span>
                                @endif
                            </div>
                            <span class="text-gray-500">
                                @if($activity->actual_start_date && $activity->actual_end_date)
                                    Duration: {{ $activity->actual_start_date->diffInDays($activity->actual_end_date) + 1 }} days
                                @elseif($activity->planned_start_date && $activity->planned_end_date)
                                    Planned: {{ $activity->planned_start_date->diffInDays($activity->planned_end_date) + 1 }} days
                                @endif
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-600">No activities scheduled for this program yet</p>
                </div>
            @endif
        </div>

        <!-- Timeline Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-8">
            <p class="text-sm text-gray-700">
                <strong>Timeline Range:</strong> {{ $timelineData['startDate']->format('M d, Y') }} to {{ $timelineData['endDate']->format('M d, Y') }} 
                ({{ $timelineData['totalDays'] }} days)
            </p>
        </div>
    </div>
</x-admin-layout>
