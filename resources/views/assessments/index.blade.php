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
                                <td class="px-6 py-4 text-sm text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('assessments.show', $assessment) }}" 
                                           class="text-lnu-blue hover:text-blue-800 transition" title="View">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('assessments.edit', $assessment) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('assessments.destroy', $assessment) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this assessment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Delete">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
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
