<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <!-- Main Modal -->
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-8 py-6 flex justify-between items-center rounded-t-xl border-b border-blue-700">
            <h3 class="text-2xl font-bold">Expense Details</h3>
            <button type="button" wire:click="closeModal" class="text-white hover:bg-blue-700 p-2 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto">
            @if($expense)
                <!-- Basic Information -->
                <div class="space-y-4 pb-6 border-b">
                    <h4 class="text-lg font-semibold text-gray-800">Basic Information</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Date Spent</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $expense->date_spent->format('M d, Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Amount</p>
                            <p class="text-lg font-semibold text-green-600">
                                ₱{{ number_format($expense->amount, 2) }}
                            </p>
                        </div>
                    </div>
                    @if($expense->description)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Description</p>
                            <p class="text-gray-900">{{ $expense->description }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Transaction Type</p>
                        <p class="text-gray-900 capitalize">{{ $expense->transaction_type }}</p>
                    </div>
                </div>

                <!-- Budget Source -->
                <div class="space-y-4 pb-6 border-b">
                    <h4 class="text-lg font-semibold text-gray-800">Budget Source</h4>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Source Name</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            {{ $expense->budget_source }}
                        </span>
                    </div>
                    @if($expense->source_description)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Source Description</p>
                            <p class="text-gray-900">{{ $expense->source_description }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Approval Status</p>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($expense->approval_status === 'approved') bg-green-100 text-green-800
                            @elseif($expense->approval_status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($expense->approval_status ?? 'pending') }}
                        </span>
                    </div>
                </div>

                <!-- People Involved -->
                @if($expense->people_involved && count($expense->people_involved) > 0)
                    <div class="space-y-4 pb-6 border-b">
                        <h4 class="text-lg font-semibold text-gray-800">People Involved</h4>
                        <div class="space-y-3">
                            @foreach($expense->people_involved as $person)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <p class="font-semibold text-gray-900">{{ $person['name'] }}</p>
                                    <p class="text-sm text-gray-700 mt-1">
                                        <span class="font-medium">Position:</span> {{ $person['position'] }}
                                    </p>
                                    @if(isset($person['office']) && $person['office'])
                                        <p class="text-sm text-gray-700 mt-1">
                                            <span class="font-medium">Office:</span> {{ $person['office'] }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Offices Involved -->
                @if($expense->offices_involved && count($expense->offices_involved) > 0)
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-800">Offices/Departments Involved</h4>
                        <div class="space-y-3">
                            @foreach($expense->offices_involved as $office)
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
            @endif
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 bg-white px-8 py-4 flex justify-end gap-3 rounded-b-xl">
            <button 
                type="button"
                wire:click="closeModal"
                class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium"
            >
                Close
            </button>
        </div>
    </div>
</div>
