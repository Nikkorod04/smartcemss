<x-admin-layout header="Community Needs Assessments">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-end">
            <div class="flex gap-2">
                <div class="flex gap-2 border-r pr-4">
                    <a href="{{ route('assessment.template.csv') }}" class="btn-secondary flex items-center gap-2" download>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        CSV Template
                    </a>
                </div>
                <a href="{{ route('assessments.create') }}" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5H7a1 1 0 100 2h3a1 1 0 001-1V7z" clip-rule="evenodd" />
                    </svg>
                    New Assessment
                </a>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-gray-50 rounded-lg p-4 space-y-4">
            <!-- Search Form -->
            <form action="{{ route('assessments.search') }}" method="GET">
                <div class="flex gap-3">
                    <input type="text" name="q" placeholder="Search by community or year..." value="{{ request('q', '') }}"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <button type="submit" class="px-6 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Search
                    </button>
                </div>
            </form>

            <!-- Quarter Filter -->
            <div class="flex gap-2">
                <a href="{{ route('assessments.index') }}" 
                   class="px-4 py-2 rounded-lg font-medium transition @if(request()->path() === 'assessments') bg-lnu-blue text-white @else bg-gray-200 text-gray-700 hover:bg-gray-300 @endif">
                    All
                </a>
                @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
                    <a href="{{ route('assessments.filter', $quarter) }}" 
                       class="px-4 py-2 rounded-lg font-medium transition @if(request()->segment(2) === 'filter' && request()->segment(3) === $quarter) bg-lnu-blue text-white @else bg-gray-200 text-gray-700 hover:bg-gray-300 @endif">
                        {{ $quarter }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Assessments Table -->
        @if($assessments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-lnu-blue">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Community</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Quarter/Year</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Respondent</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">File</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Created</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($assessments as $assessment)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <strong>{{ $assessment->community->name }}</strong>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $assessment->quarter }} / {{ $assessment->year }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $assessment->respondent_name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($assessment->file_path)
                                        <a href="{{ asset('storage/' . $assessment->file_path) }}" target="_blank" 
                                           class="text-blue-600 hover:underline flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-6-4m6 4l6-4" />
                                            </svg>
                                            Download
                                        </a>
                                    @else
                                        <span class="text-gray-400">Manual</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $assessment->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('assessments.show', $assessment) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                                        <a href="{{ route('assessments.edit', $assessment) }}" 
                                           class="text-gray-600 hover:text-gray-800 font-medium">Edit</a>
                                        <form action="{{ route('assessments.destroy', $assessment) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this assessment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $assessments->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No assessments found</h3>
                <p class="text-gray-600 mb-4">Start by creating your first community needs assessment</p>
                <a href="{{ route('assessments.create') }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5H7a1 1 0 100 2h3a1 1 0 001-1V7z" clip-rule="evenodd" />
                    </svg>
                    Create First Assessment
                </a>
            </div>
        @endif
    </div>
</x-admin-layout>
