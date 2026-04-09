<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('faculty.programs') }}" class="inline-flex items-center gap-2 text-lnu-blue hover:text-lnu-blue/80 mb-6 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="font-medium">Back to Programs</span>
        </a>

        <div class="bg-white rounded-lg shadow-lg p-8 space-y-6">
            <!-- Header with Status -->
            <div class="border-b border-gray-200 pb-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $program->program_name }}</h1>
                        <div class="flex items-center gap-4 flex-wrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                @if($program->status === 'draft') bg-yellow-100 text-yellow-800
                                @elseif($program->status === 'ongoing') bg-green-100 text-green-800
                                @elseif($program->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($program->status ?? 'Pending') }}
                            </span>
                            
                            @if($isFacultyLead)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    Program Lead
                                </span>
                            @elseif($isParticipant)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                    Participant
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">Created {{ $program->created_at->diffForHumans() }}</p>
            </div>

            <!-- Cover Image -->
            @if ($program->cover_image)
            <div class="rounded-lg overflow-hidden shadow-md">
                <img src="{{ asset('storage/' . $program->cover_image) }}" alt="{{ $program->program_name }}" class="w-full h-80 object-cover">
            </div>
            @endif

            <div class="grid grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="col-span-2 space-y-6">
                    <!-- Overview -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
                        <p class="text-gray-700 mb-6 leading-relaxed">{{ $program->description ?? 'No description available' }}</p>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm font-semibold text-gray-600 mb-2">Goals</p>
                                <p class="text-gray-900">{{ $program->goals ?? 'Not specified' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm font-semibold text-gray-600 mb-2">Objectives</p>
                                <p class="text-gray-900">{{ $program->objectives ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Timeline</h2>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="border-l-4 border-lnu-blue pl-4">
                                <p class="text-sm text-gray-600 mb-1">Start Date</p>
                                <p class="text-lg font-bold text-lnu-blue">
                                    {{ $program->planned_start_date?->format('M d, Y') ?? 'TBD' }}
                                </p>
                            </div>
                            <div class="border-l-4 border-amber-500 pl-4">
                                <p class="text-sm text-gray-600 mb-1">End Date</p>
                                <p class="text-lg font-bold text-amber-600">
                                    {{ $program->planned_end_date?->format('M d, Y') ?? 'TBD' }}
                                </p>
                            </div>
                        </div>
                        @if ($program->planned_start_date && $program->planned_end_date)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-1">Duration</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $program->planned_start_date->diffInDays($program->planned_end_date) }} days
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Communities -->
                    @if ($program->communities->count() > 0)
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Communities Served</h2>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach ($program->communities as $community)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <svg class="w-5 h-5 text-lnu-blue flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5" />
                                    <path d="M6.5 11l2.5 2.5 5-5" stroke="currentColor" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $community->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $community->barangay }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Activities -->
                    @if ($program->activities->count() > 0)
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Activities ({{ $program->activities->count() }})</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700">Activity Name</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700">Start Date</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($program->activities as $activity)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $activity->activity_name }}</td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $activity->planned_start_date?->format('M d, Y') ?? 'TBD' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                {{ ucfirst($activity->status ?? 'Pending') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Sidebar -->
                <div class="col-span-1 space-y-6">
                    <!-- Program Lead Card -->
                    <div class="border border-gray-200 rounded-lg p-6 sticky top-20">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Program Lead</h3>
                        @if ($program->programLead)
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-lnu-blue rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($program->programLead->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $program->programLead->user->name }}</p>
                                <p class="text-sm text-gray-600">Faculty</p>
                            </div>
                        </div>
                        @if ($program->programLead->email)
                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                            <p class="text-xs text-gray-600 mb-1">Email</p>
                            <a href="mailto:{{ $program->programLead->email }}" class="text-sm font-medium text-lnu-blue hover:underline break-all">
                                {{ $program->programLead->email }}
                            </a>
                        </div>
                        @endif
                        @else
                        <p class="text-gray-600">No lead assigned</p>
                        @endif
                    </div>

                    <!-- Program Info Card -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Program Info</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-600 font-semibold mb-1">Status</p>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($program->status ?? 'Pending') }}</p>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <p class="text-xs text-gray-600 font-semibold mb-1">Created</p>
                                <p class="text-sm font-medium text-gray-900">{{ $program->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <p class="text-xs text-gray-600 font-semibold mb-1">Participants</p>
                                <p class="text-sm font-medium text-gray-900">{{ $program->activities->count() }} activities</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
