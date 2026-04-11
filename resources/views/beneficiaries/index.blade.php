<x-admin-layout header="Beneficiaries">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">

        <!-- Search and Filter Section -->
        <div class="border-t border-gray-200 pt-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <form action="{{ route('beneficiaries.search') }}" method="GET" class="md:col-span-2">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Search by name, email, or phone..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lnu-blue focus:border-transparent"
                            value="{{ request('q', '') }}"
                        />
                        <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-lnu-blue">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Status Filter -->
                <div class="flex gap-2">
                    <a href="{{ route('beneficiaries.filter', 'active') }}" 
                       class="flex-1 px-4 py-2 text-center rounded-lg font-medium transition @if(request()->segment(2) === 'filter' && request()->segment(3) === 'active') bg-green-100 text-green-800 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif">
                        Active
                    </a>
                    <a href="{{ route('beneficiaries.filter', 'inactive') }}" 
                       class="flex-1 px-4 py-2 text-center rounded-lg font-medium transition @if(request()->segment(2) === 'filter' && request()->segment(3) === 'inactive') bg-red-100 text-red-800 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif">
                        Inactive
                    </a>
                    <a href="{{ route('beneficiaries.index') }}" 
                       class="flex-1 px-4 py-2 text-center rounded-lg font-medium transition @if(request()->path() === 'beneficiaries') bg-blue-100 text-blue-800 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif">
                        All
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="border-t border-gray-200 pt-6 overflow-x-auto">
            @if ($beneficiaries->count() > 0)
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Contact</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Community</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Category</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($beneficiaries as $beneficiary)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $beneficiary->first_name }} {{ $beneficiary->last_name }}
                                </p>
                                <p class="text-xs text-gray-600">Age: {{ $beneficiary->age ?? 'N/A' }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                @if ($beneficiary->email)
                                <p class="text-sm text-gray-600">{{ $beneficiary->email }}</p>
                                @endif
                                @if ($beneficiary->phone)
                                <p class="text-sm text-gray-600">{{ $beneficiary->phone }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-gray-600">
                                {{ $beneficiary->community?->name ?? 'N/A' }}
                            </p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-gray-600">{{ $beneficiary->beneficiary_category ?? 'N/A' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block capitalize px-3 py-1 rounded-full text-xs font-medium
                                @if($beneficiary->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($beneficiary->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('beneficiaries.show', $beneficiary) }}" class="text-lnu-blue hover:text-blue-800 transition" title="View">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="{{ route('beneficiaries.edit', $beneficiary) }}" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('beneficiaries.destroy', $beneficiary) }}" class="inline"
                                      onsubmit="return confirm('Are you sure? This cannot be undone.');">
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

            <!-- Pagination -->
            <div class="mt-6">
                {{ $beneficiaries->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                </svg>
                <h3 class="text-gray-500 font-medium mb-1">No beneficiaries found</h3>
                <p class="text-gray-400 text-sm mb-4">Get started by creating your first beneficiary profile.</p>
                <a href="{{ route('beneficiaries.create') }}" class="inline-block btn-primary">
                    Add Beneficiary
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Floating Action Button - Add Beneficiary -->
    <a href="{{ route('beneficiaries.create') }}" 
       class="fixed bottom-8 right-8 z-40 w-16 h-16 bg-lnu-blue hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-2xl transition-all duration-300 flex items-center justify-center group"
       title="Add New Beneficiary">
        <svg class="w-8 h-8 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
    </a>
</x-admin-layout>
