<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- White Content Container -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Programs Section -->
            @if(count($programsLed) > 0 || count($programsInvolved) > 0)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-1 w-12 bg-gradient-to-r from-lnu-blue to-transparent rounded-full"></div>
                    <h3 class="text-2xl font-bold text-gray-900">Programs</h3>
                    <span class="ml-auto inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                        {{ count($programsLed) + count($programsInvolved) }} program{{ (count($programsLed) + count($programsInvolved)) !== 1 ? 's' : '' }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($programsLed as $program)
                    <a href="{{ route('faculty.programs.show', $program) }}" class="group bg-gray-50 rounded-lg hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden hover:border-lnu-blue cursor-pointer block">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <h5 class="font-semibold text-gray-900 text-lg flex-1 group-hover:text-lnu-blue transition">{{ $program->title }}</h5>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-blue-100 text-blue-800 ml-2 flex-shrink-0">
                                    Lead
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $program->description ?? 'No description available' }}</p>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <span class="text-xs text-gray-500 font-medium">Status</span>
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $program->status ?? 'Active' }}</span>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach

                    @foreach($programsInvolved as $program)
                    <a href="{{ route('faculty.programs.show', $program) }}" class="group bg-gray-50 rounded-lg hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden hover:border-purple-600 cursor-pointer block">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <h5 class="font-semibold text-gray-900 text-lg flex-1 group-hover:text-purple-600 transition">{{ $program->title }}</h5>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-purple-100 text-purple-800 ml-2 flex-shrink-0">
                                    Participant
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $program->description ?? 'No description available' }}</p>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <span class="text-xs text-gray-500 font-medium">Status</span>
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $program->status ?? 'Active' }}</span>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Activities Section -->
            @if(count($activities) > 0)
            <div class="mt-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-1 w-12 bg-gradient-to-r from-amber-500 to-transparent rounded-full"></div>
                    <h3 class="text-2xl font-bold text-gray-900">All Activities</h3>
                    <span class="ml-auto inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-700">
                        {{ count($activities) }} activit{{ count($activities) !== 1 ? 'ies' : 'y' }}
                    </span>
                </div>
                <div class="bg-gray-50 rounded-lg overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100 border-b border-gray-200">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Activity Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Program</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Start Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($activities as $activity)
                                <tr class="hover:bg-white transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                                            <span class="font-semibold text-gray-900">{{ $activity->activity_name ?? 'Activity' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-sm font-medium bg-indigo-50 text-indigo-700">
                                            {{ $activity->extensionProgram?->title ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 text-gray-700 font-medium">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>
                                                @if($activity->actual_start_date)
                                                    {{ $activity->actual_start_date->format('M d, Y') }}
                                                @else
                                                    {{ $activity->planned_start_date?->format('M d, Y') ?? 'TBD' }}
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
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
            <div class="mt-12 bg-gray-50 rounded-lg border border-gray-200 p-12 text-center">
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Programs or Activities Yet</h3>
                <p class="text-gray-600 text-lg max-w-md mx-auto">Get started by joining extension programs and participating in activities to track your engagement and impact.</p>
            </div>
            @endif
        </div>
    </div>
</div>
