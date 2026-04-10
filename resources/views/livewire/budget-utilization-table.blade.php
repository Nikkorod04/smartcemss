<div class="space-y-6">
    <!-- Budget Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-gray-700 font-medium">Total Allocated</p>
            <p class="text-3xl font-bold text-blue-900 mt-2">₱{{ number_format($totalAllocated, 2) }}</p>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
            <p class="text-sm text-gray-700 font-medium">Total Spent</p>
            <p class="text-3xl font-bold text-orange-900 mt-2">₱{{ number_format($totalSpent, 2) }}</p>
            <p class="text-xs text-gray-600 mt-2">{{ count($budgets) }} transactions</p>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
            <p class="text-sm text-gray-700 font-medium">Budget Usage</p>
            <p class="text-3xl font-bold text-green-900 mt-2">{{ $percentageUsed }}%</p>
            <div class="w-full bg-green-200 rounded-full h-2 mt-3">
                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($percentageUsed, 100) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Budget Table with Header -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-bold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
                Budget Utilization Details
            </h3>
            <button 
                wire:click="openAddExpenseModal"
                class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-gray-50 transition font-medium text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Add Expense
            </button>
        </div>

        @if(count($budgets) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Budget Source</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($budgets as $budget)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($budget->date_spent)->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">₱{{ number_format($budget->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700">{{ Str::limit($budget->description ?? 'N/A', 30) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($budget->budget_source)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ $budget->budget_source }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 text-sm">Not specified</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($budget->approval_status === 'approved') bg-green-100 text-green-800
                                        @elseif($budget->approval_status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($budget->approval_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            type="button"
                                            wire:click="openViewExpenseModal({{ $budget->id }})"
                                            title="View details"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition"
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                        <button 
                                            type="button"
                                            wire:click="openEditExpenseModal({{ $budget->id }})"
                                            title="Edit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 transition"
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </button>
                                        <button 
                                            type="button"
                                            title="Delete"
                                            wire:click="deleteExpense({{ $budget->id }})"
                                            wire:confirm="Are you sure you want to delete this expense? This action cannot be undone."
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition"
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Details Row -->
                            <tr class="bg-gray-50 hidden hover:table-row">
                                <td colspan="7" class="px-6 py-4">
                                    @if($budget->source_description)
                                        <p class="text-sm text-gray-700"><strong>Source Details:</strong> {{ $budget->source_description }}</p>
                                    @endif
                                    @if($budget->people_involved && count($budget->people_involved) > 0)
                                        <p class="text-sm text-gray-700 mt-2"><strong>People Involved:</strong></p>
                                        <ul class="text-sm text-gray-600 mt-1">
                                            @foreach($budget->people_involved as $person)
                                                <li>• {{ $person['name'] }} ({{ $person['position'] }}) @if(isset($person['office'])) - {{ $person['office'] }} @endif</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 font-medium">No budget records found</p>
                <p class="text-gray-500 text-sm mt-1">Click "Add Expense" to record your first budget transaction</p>
            </div>
        @endif
    </div>

    <!-- Add Expense Modal -->
    @if($showAddExpenseModal)
        <livewire:add-expense-modal :programId="$programId" />
    @endif

    <!-- View Expense Modal -->
    @if($showViewExpenseModal && $selectedExpenseId)
        <livewire:view-expense-modal :expenseId="$selectedExpenseId" />
    @endif

    <!-- Edit Expense Modal -->
    @if($showEditExpenseModal && $selectedExpenseId)
        <livewire:edit-expense-modal :expenseId="$selectedExpenseId" />
    @endif
</div>

