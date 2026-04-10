<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-4">
        <h3 class="text-lg font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
            </svg>
            Budget Source Details
        </h3>
    </div>

    <div class="p-6 space-y-6">
        @if($budget->budget_source)
            <!-- Budget Source Section -->
            <div class="space-y-3">
                <h4 class="font-semibold text-gray-800 flex items-center">
                    <span class="w-1 h-8 bg-blue-600 rounded-full mr-3"></span>
                    Source Information
                </h4>
                <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
                    <p class="text-sm text-gray-600">Source:</p>
                    <p class="font-semibold text-gray-900 text-lg">{{ $budget->budget_source }}</p>
                    
                    @if($budget->source_description)
                        <p class="text-sm text-gray-700 mt-3">{{ $budget->source_description }}</p>
                    @endif
                </div>

                <!-- Approval Status Badge -->
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($budget->approval_status === 'approved') bg-green-100 text-green-800
                        @elseif($budget->approval_status === 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($budget->approval_status) }}
                    </span>
                </div>
            </div>

            <!-- People Involved Section -->
            @if($budget->people_involved && count($budget->people_involved) > 0)
                <div class="space-y-3 border-t pt-6">
                    <h4 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                        </svg>
                        People Involved ({{ count($budget->people_involved) }})
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($budget->people_involved as $person)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="font-semibold text-gray-900">{{ $person['name'] }}</p>
                                <p class="text-sm text-gray-700 mt-1">
                                    <span class="font-medium">Position:</span> {{ $person['position'] }}
                                </p>
                                @if(isset($person['office']) && $person['office'])
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Office:</span> {{ $person['office'] }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Offices Involved Section -->
            @if($budget->offices_involved && count($budget->offices_involved) > 0)
                <div class="space-y-3 border-t pt-6">
                    <h4 class="font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Offices/Departments Involved ({{ count($budget->offices_involved) }})
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($budget->offices_involved as $office)
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <p class="font-semibold text-gray-900">{{ $office['name'] }}</p>
                                @if(isset($office['contact']) && $office['contact'])
                                    <p class="text-sm text-gray-700 mt-1">
                                        <span class="font-medium">Contact:</span> {{ $office['contact'] }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Edit Button -->
            <div class="border-t pt-6 flex justify-end">
                <button 
                    @if(method_exists($this, 'openModal'))
                        wire:click="openModal('budget-source-modal', { budgetId: {{ $budget->id }} })"
                    @else
                        onclick="alert('Edit functionality not configured')"
                    @endif
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                    </svg>
                    Edit Budget Source
                </button>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 mb-4">No budget source information added yet</p>
                <button 
                    @if(method_exists($this, 'openModal'))
                        wire:click="openModal('budget-source-modal', { budgetId: {{ $budget->id }} })"
                    @else
                        onclick="alert('Add functionality not configured')"
                    @endif
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Add Budget Source
                </button>
            </div>
        @endif
    </div>
</div>
