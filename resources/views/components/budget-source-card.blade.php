<div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition">
    <!-- Budget Amount and Source -->
    <div class="space-y-3">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($budget->date_spent)->format('M d, Y') }}</p>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($budget->amount, 2) }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                @if($budget->approval_status === 'approved') bg-green-100 text-green-800
                @elseif($budget->approval_status === 'rejected') bg-red-100 text-red-800
                @else bg-yellow-100 text-yellow-800
                @endif">
                {{ ucfirst($budget->approval_status ?? 'pending') }}
            </span>
        </div>

        <!-- Description -->
        <div>
            <p class="text-sm font-medium text-gray-700">{{ $budget->description ?? 'No description' }}</p>
            <p class="text-xs text-gray-500 mt-1">Type: {{ ucfirst($budget->transaction_type) }}</p>
        </div>

        <!-- Budget Source Badge -->
        @if($budget->budget_source)
            <div class="bg-blue-50 border border-blue-200 rounded p-2">
                <p class="text-xs text-gray-600 font-medium">SOURCE</p>
                <p class="text-sm font-semibold text-blue-900">{{ $budget->budget_source }}</p>
            </div>
        @endif

        <!-- People & Offices Count -->
        @if(($budget->people_involved && count($budget->people_involved) > 0) || ($budget->offices_involved && count($budget->offices_involved) > 0))
            <div class="grid grid-cols-2 gap-2 pt-2 border-t">
                @if($budget->people_involved && count($budget->people_involved) > 0)
                    <div class="flex items-center space-x-1 text-green-700 bg-green-50 p-2 rounded">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                        </svg>
                        <span class="text-xs font-medium">{{ count($budget->people_involved) }} People</span>
                    </div>
                @endif
                
                @if($budget->offices_involved && count($budget->offices_involved) > 0)
                    <div class="flex items-center space-x-1 text-purple-700 bg-purple-50 p-2 rounded">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        <span class="text-xs font-medium">{{ count($budget->offices_involved) }} Offices</span>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
