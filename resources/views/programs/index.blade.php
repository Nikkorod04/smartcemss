<x-admin-layout header="Extension Programs">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-lnu-blue">Extension Programs</h1>
            </div>

            @if (auth()->user()->role === 'director')
            <a href="{{ route('programs.create') }}" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Create Program
            </a>
            @endif
        </div>

        <!-- Search and Filter Bar -->
        <div class="bg-white rounded-lg shadow-md p-6 flex gap-4 items-end flex-wrap">
            <div class="flex-1 min-w-64">
                <form method="GET" action="{{ route('programs.search') }}" class="flex gap-2">
                    <input type="text" name="q" placeholder="Search programs..." class="input-field flex-1" value="{{ request('q') }}" />
                    <button type="submit" class="btn-primary">Search</button>
                </form>
            </div>

            <!-- Status Filter -->
            <div class="flex gap-2">
                <a href="{{ route('programs.index') }}" class="px-4 py-2 rounded {{ !request('status') ? 'bg-lnu-blue text-white' : 'bg-gray-100 text-gray-700' }} transition">
                    All
                </a>
                @foreach(['draft', 'ongoing', 'completed', 'cancelled'] as $status)
                <a href="{{ route('programs.filter', $status) }}" class="px-4 py-2 rounded capitalize {{ request('status') === $status ? 'bg-lnu-blue text-white' : 'bg-gray-100 text-gray-700' }} transition">
                    {{ $status }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Programs Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if ($programs->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Program Lead</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Communities</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Budget</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Progress</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($programs as $program)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-lnu-blue hover:text-lnu-gold transition">
                                <a href="{{ route('programs.show', $program) }}">{{ $program->title }}</a>
                            </div>
                            <p class="text-sm text-gray-600">{{ Str::limit($program->description, 50) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($program->programLead->user->name) }}&background=003599&color=fff" 
                                     alt="{{ $program->programLead->user->name }}" 
                                     class="w-8 h-8 rounded-full">
                                <span class="text-sm">{{ $program->programLead->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                @forelse ($program->communities as $community)
                                <span class="inline-block text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    {{ $community->name }}
                                </span>
                                @empty
                                <span class="text-sm text-gray-500">No communities</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block capitalize px-3 py-1 rounded-full text-sm font-medium
                                @if($program->status === 'draft') bg-yellow-100 text-yellow-800
                                @elseif($program->status === 'ongoing') bg-green-100 text-green-800
                                @elseif($program->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $program->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-900">₱{{ number_format($program->allocated_budget, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-lnu-gold h-2 rounded-full" 
                                     style="width: {{ $program->status === 'completed' ? 100 : ($program->status === 'ongoing' ? 60 : ($program->status === 'draft' ? 20 : 0)) }}%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('programs.show', $program) }}" class="text-lnu-blue hover:text-lnu-gold transition" title="View">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>

                                @if (auth()->user()->role === 'director')
                                <a href="{{ route('programs.edit', $program) }}" class="text-blue-600 hover:text-blue-800 transition" title="Edit">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('programs.destroy', $program) }}" class="inline" 
                                      onsubmit="return confirm('Are you sure? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Delete">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $programs->links() }}
            </div>
            @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-600 mb-4">No programs found</p>
                @if (auth()->user()->role === 'director')
                <a href="{{ route('programs.create') }}" class="btn-primary">Create Your First Program</a>
                @endif
            </div>
            @endif
        </div>
    </div>
    </div>
</x-admin-layout>
