<x-admin-layout header="Communities">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-lnu-blue">Communities</h1>
            </div>

            @if (auth()->user()->role === 'director')
            <a href="{{ route('communities.create') }}" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Community
            </a>
            @endif
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
            <form action="{{ route('communities.search') }}" method="GET" class="flex gap-3">
                <div class="flex-1">
                    <input type="text" name="q" placeholder="Search by name, municipality, or province..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue"
                        value="{{ request('q') }}" />
                </div>
                <button type="submit" class="px-6 py-2 bg-lnu-blue text-white rounded-lg hover:bg-blue-800 transition font-medium">
                    Search
                </button>
            </form>

            <!-- Status Filter -->
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('communities.index') }}" class="px-4 py-2 rounded-full font-medium {{ !request('status') ? 'bg-lnu-blue text-white' : 'bg-gray-200 text-gray-900 hover:bg-gray-300' }}">
                    All
                </a>
                <a href="{{ route('communities.filter', 'active') }}" class="px-4 py-2 rounded-full font-medium {{ request('status') === 'active' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-900 hover:bg-gray-300' }}">
                    Active
                </a>
                <a href="{{ route('communities.filter', 'inactive') }}" class="px-4 py-2 rounded-full font-medium {{ request('status') === 'inactive' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-900 hover:bg-gray-300' }}">
                    Inactive
                </a>
                <a href="{{ route('communities.filter', 'archived') }}" class="px-4 py-2 rounded-full font-medium {{ request('status') === 'archived' ? 'bg-gray-500 text-white' : 'bg-gray-200 text-gray-900 hover:bg-gray-300' }}">
                    Archived
                </a>
            </div>
        </div>

        <!-- Communities Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($communities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Community Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Location</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Contact Person</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Contact Number</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Programs</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($communities as $community)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('communities.show', $community) }}" class="text-lnu-blue hover:text-blue-800 font-semibold">
                                    {{ $community->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $community->municipality }}, {{ $community->province }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $community->contact_person }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $community->contact_number }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block capitalize px-3 py-1 rounded-full text-xs font-medium
                                    @if($community->status === 'active') bg-green-100 text-green-800
                                    @elseif($community->status === 'inactive') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($community->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-block bg-blue-50 text-lnu-blue px-3 py-1 rounded font-medium">
                                    {{ $community->extensionPrograms->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('communities.show', $community) }}" class="text-lnu-blue hover:text-blue-800 transition" title="View">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    @if (auth()->user()->role === 'director')
                                    <a href="{{ route('communities.edit', $community) }}" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('communities.destroy', $community) }}" class="inline"
                                          onsubmit="return confirm('Are you sure? This will soft delete the community.');">
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
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $communities->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                <p class="text-gray-500 text-lg mb-4">No communities found</p>
                @if (auth()->user()->role === 'director')
                <a href="{{ route('communities.create') }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Your First Community
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
    </div>
</x-admin-layout>
