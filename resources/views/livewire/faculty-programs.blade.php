<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">My Programs & Activities</h2>

        <!-- Programs Section -->
        @if(count($programsLed) > 0 || count($programsInvolved) > 0)
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Extension Programs</h3>
            
            <!-- Programs Led -->
            @if(count($programsLed) > 0)
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="inline-block w-1 h-6 bg-lnu-blue rounded"></span>
                    Programs I Lead
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($programsLed as $program)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-lnu-blue">
                        <div class="flex items-start justify-between mb-3">
                            <h5 class="font-semibold text-gray-900 text-lg">{{ $program->program_name }}</h5>
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
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="inline-block w-1 h-6 bg-purple-600 rounded"></span>
                    Programs I Participate In
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($programsInvolved as $program)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-purple-600">
                        <div class="flex items-start justify-between mb-3">
                            <h5 class="font-semibold text-gray-900 text-lg">{{ $program->program_name }}</h5>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Participant
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

        <!-- Activities Section -->
        @if(count($activities) > 0)
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-6">All Activities</h3>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Activity Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Program</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-900">{{ $activity->activity_name ?? 'Activity' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-600">{{ $activity->extensionProgram?->program_name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-600">
                                        @if($activity->actual_start_date)
                                            {{ $activity->actual_start_date->format('M d, Y') }}
                                        @else
                                            {{ $activity->planned_start_date?->format('M d, Y') ?? 'TBD' }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $activity->status ?? 'Active' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <p class="text-gray-600 text-lg">No programs or activities yet. Start participating in extension programs!</p>
        </div>
        @endif
    </div>
</div>
