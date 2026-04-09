<x-faculty-layout header="My Availability">
    <div class="space-y-6">
    <!-- Legend -->
    <div class="grid grid-cols-3 gap-4">
        <div class="flex items-center gap-2 px-4 py-2 bg-yellow-50 rounded-lg border border-yellow-200">
            <span class="w-3 h-3 rounded bg-yellow-400"></span>
            <span class="text-sm font-medium text-gray-900">Pending Approval</span>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-green-50 rounded-lg border border-green-200">
            <span class="w-3 h-3 rounded bg-green-500"></span>
            <span class="text-sm font-medium text-gray-900">Approved</span>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-red-50 rounded-lg border border-red-200">
            <span class="w-3 h-3 rounded bg-red-500"></span>
            <span class="text-sm font-medium text-gray-900">Rejected</span>
        </div>
    </div>

    <!-- Add New Availability Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-lnu-blue mb-4">Add Your Availability</h3>
        
        <form action="{{ route('faculty.availability.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Date</label>
                    <input type="date" name="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" required>
                    @error('date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Time Slot</label>
                    <select name="time_slot" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" required>
                        <option value="">Select time slot...</option>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot }}">{{ $slot }}</option>
                        @endforeach
                    </select>
                    @error('time_slot')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">&nbsp;</label>
                    <button type="submit" class="w-full bg-lnu-blue text-white py-2 px-4 rounded-md hover:bg-blue-700 transition font-medium">
                        Add Availability
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Remarks (Optional)</label>
                <textarea name="remarks" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" placeholder="Any additional notes..."></textarea>
            </div>
        </form>
    </div>

    <!-- Availability List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-lnu-blue">Your Availability Entries</h3>
        </div>

        @if($availabilities->items())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Time Slot</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Remarks</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($availabilities as $availability)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $availability->date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $availability->time_slot }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                        @if($availability->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($availability->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($availability->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $availability->remarks ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($availability->status === 'pending')
                                        <form method="POST" action="{{ route('faculty.availability.destroy', $availability) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200">
                {{ $availabilities->links() }}
            </div>
        @else
            <div class="p-12 text-center text-gray-500">
                <p class="text-lg">No availability entries yet. Add one to get started!</p>
            </div>
        @endif
    </div>
</x-faculty-layout>