<x-admin-layout header="Budget Breakdown Report">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Budget Breakdown Report</h1>
                    <p class="text-gray-600 mt-2">{{ $program->title }}</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm0 0h2a2 2 0 002-2m0 0V9m0 4a2 2 0 002-2m0 0V5a2 2 0 00-2-2m0 4V5m0 0H9m4 16l-1-1m0 0l-1 1m1-1v4m0-4H7m10 0H9" />
                        </svg>
                        Print Report
                    </button>
                    <a href="{{ route('programs.show', $program) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition">
                        Back to Program
                    </a>
                </div>
            </div>

            <!-- Program Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Allocated Budget -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Program Allocated Budget</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">₱{{ number_format($programAllocatedBudget, 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.16 5.314l4.897-1.596A1 1 0 0114.791 5v9.268a1 1 0 01-.894 1.053l-4.897 1.596A1 1 0 018 15.268V6.367a1 1 0 01.16-.053zm-4.3 5.886l4.897 1.596A1 1 0 008 13.268V4.367A1 1 0 006.791 3.314L1.894 4.91A1 1 0 001 5.963V15.23a1 1 0 01.894 1.053Zm3.353-2.01l2-1.324A1 1 0 1013.55 4.62L11.55 5.944a1 1 0 01-1.197-1.596Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Spent</p>
                            <p class="text-3xl font-bold text-red-600 mt-2">₱{{ number_format($programTotalSpent, 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 000-2H4a1 1 0 00-1 1v1H2a1 1 0 000 2h1v1a1 1 0 002 0V4h1a1 1 0 100-2H5V2a1 1 0 011-1zm0 5a2 2 0 11-4 0 2 2 0 014 0zM4.071 11.243a1 1 0 10-1.414 1.414L2.828 13l-.343.343a1 1 0 001.414 1.414L4.242 14.414l.343.343a1 1 0 001.414-1.414L5.656 13l.343-.343a1 1 0 00-1.414-1.414L4.242 11.657l-.171.586z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Remaining -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Remaining Budget</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">₱{{ number_format(max(0, $programAllocatedBudget - $programTotalSpent), 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 16a1 1 0 01-1-1v-5h-1a1 1 0 110-2h1V7a1 1 0 112 0v1h1a1 1 0 110 2h-1v5a1 1 0 01-1 1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activities Breakdown Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-900">Activities Budget Details</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ count($activityBreakdown) }} activity(ies) tracked</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Activity</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Allocated</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Spent</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Remaining</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Utilization</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($activityBreakdown as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('activities.show', $item['activity']) }}" class="text-blue-600 hover:underline font-medium">
                                            {{ $item['activity']->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 font-medium">
                                        ₱{{ number_format($item['allocated'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 font-medium">
                                        ₱{{ number_format($item['spent'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 font-medium">
                                        ₱{{ number_format($item['remaining'], 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-1">
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="h-2 rounded-full @if($item['isWarning']) bg-orange-500 @elseif($item['percentage'] > 50) bg-blue-500 @else bg-green-500 @endif transition-all duration-300"
                                                         style="width: {{ min($item['percentage'], 100) }}%">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm font-bold @if($item['isWarning']) text-orange-600 @else text-gray-600 @endif w-12 text-right">
                                                {{ number_format($item['percentage'], 1) }}%
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item['isWarning'])
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                Near Limit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                On Track
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        No activities with budget allocations yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-900">
                    <strong>Note:</strong> Budget utilization is calculated based on expenses linked to each activity. Activities with spending ≥ 85% are marked as "Near Limit".
                    <a href="{{ route('programs.budgets.index', $program) }}" class="text-blue-600 hover:underline font-medium">View full budget transactions →</a>
                </p>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
        }
    </style>
</x-admin-layout>
