<x-admin-layout header="Faculty Management">
    <div class="space-y-6">
        <!-- Header with Create Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Faculty Members</h2>
                <p class="text-sm text-gray-600 mt-1">Manage faculty members and generate access tokens</p>
            </div>
            <a href="{{ route('faculties.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Faculty Member
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        <!-- Faculty Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($faculties->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Employee ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Department</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Position</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Active Tokens</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Programs</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($faculties as $faculty)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($faculty->user->name) }}&background=003599&color=fff" alt="{{ $faculty->user->name }}" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $faculty->user->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $faculty->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $faculty->employee_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $faculty->department }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $faculty->position }}</td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $activeTokenCount = $faculty->tokens()->where(function ($query) {
                                        $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
                                    })->count();
                                @endphp
                                @if($activeTokenCount > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $activeTokenCount }} Active
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500">None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-700">
                                {{ $faculty->extensionPrograms->count() }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('faculties.show', $faculty) }}" class="text-lnu-blue hover:text-blue-700 font-medium transition">View</a>
                                    <a href="{{ route('faculties.edit', $faculty) }}" class="text-lnu-blue hover:text-blue-700 font-medium transition">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <p class="text-gray-600">No faculty members yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($faculties->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $faculties->links() }}
            </div>
            @endif
            @else
            <div class="p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3.936a3 3 0 01-2.964-2.401l-2.147-12A3 3 0 015.934 2h12.132a3 3 0 012.964 2.599l2.147 12a3 3 0 01-2.964 3.401m-6-2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-gray-600 mb-4">No faculty members found</p>
                <a href="{{ route('faculties.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create First Faculty Member
                </a>
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>
