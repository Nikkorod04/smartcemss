<x-admin-layout header="Activities">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Activities</h1>
                    <p class="text-gray-600 mt-2">Track all program activities, attendance, and progress</p>
                </div>
                <a href="{{ route('activities.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Activity
                </a>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <!-- Search Form -->
                <form action="{{ route('activities.search') }}" method="GET" class="mb-4">
                    <div class="flex gap-3">
                        <input type="text" name="q" placeholder="Search by activity title or program..." value="{{ request('q', '') }}"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                            Search
                        </button>
                    </div>
                </form>

                <!-- Status Filter -->
                <div class="flex gap-2">
                    <a href="{{ route('activities.filter', 'pending') }}" 
                       class="flex-1 px-4 py-2 text-center rounded-lg font-medium transition @if(request()->segment(2) === 'filter' && request()->segment(3) === 'pending') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif">
                        Pending
                    </a>
                    <a href="{{ route('activities.filter', 'ongoing') }}" 
                       class="flex-1 px-4 py-2 text-center rounded-lg font-medium transition @if(request()->segment(2) === 'filter' && request()->segment(3) === 'ongoing') bg-blue-100 text-blue-800 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif">
                        Ongoing
                    </a>
                    <a href="{{ route('activities.filter', 'completed') }}" 
                       class="flex-1 px-4 py-2 text-center rounded-lg font-medium transition @if(request()->segment(2) === 'filter' && request()->segment(3) === 'completed') bg-green-100 text-green-800 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif">
                        Completed
                    </a>
                    <a href="{{ route('activities.index') }}" 
                       class="flex-1 px-4 py-2 text-center rounded-lg font-medium transition @if(request()->path() === 'activities') bg-indigo-100 text-indigo-800 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif">
                        All
                    </a>
                </div>
            </div>

            <!-- Activities List -->
            @if($activities->count() > 0)
                <div class="space-y-4">
                    @foreach($activities as $activity)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $activity->title }}</h3>
                                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                                            @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($activity->status === 'ongoing') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-sm mt-1">
                                        <strong>Program:</strong> {{ $activity->extensionProgram->title }}
                                    </p>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4">{{ Str::limit($activity->description, 150) }}</p>

                            <div class="grid grid-cols-3 gap-4 mb-4 text-sm">
                                <div>
                                    <p class="text-gray-600"><strong>Start Date:</strong></p>
                                    <p class="text-gray-900">{{ $activity->actual_start_date?->format('M d, Y') ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600"><strong>End Date:</strong></p>
                                    <p class="text-gray-900">{{ $activity->actual_end_date?->format('M d, Y') ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600"><strong>Faculties:</strong></p>
                                    <p class="text-gray-900">{{ $activity->faculties->count() }} assigned</p>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm mb-4">
                                <strong>Venue:</strong> {{ $activity->venue }}
                            </p>

                            <div class="flex gap-3">
                                <a href="{{ route('activities.show', $activity) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('activities.edit', $activity) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="inline-block" 
                                      onsubmit="return confirm('Are you sure you want to delete this activity?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H4v2h16V7h-3z" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
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
                    <p class="text-gray-600 mb-6">Start by creating your first activity for a program</p>
                    <a href="{{ route('activities.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create First Activity
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
