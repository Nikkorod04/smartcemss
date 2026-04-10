@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Budget Management</h1>
            <p class="text-gray-600 mt-2">Program: {{ $program->title }}</p>
        </div>
        <a href="{{ route('programs.show', $program) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Program
        </a>
    </div>

    <!-- Budget Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- Allocated Budget -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 font-medium">Allocated Budget</p>
            <p class="text-2xl font-bold text-lnu-blue mt-2">₱{{ number_format($budgetData['allocated'], 2) }}</p>
        </div>

        <!-- Adjustments -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 font-medium">Adjustments</p>
            <p class="text-2xl font-bold {{ $budgetData['adjustments'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                {{ $budgetData['adjustments'] >= 0 ? '+' : '' }}₱{{ number_format($budgetData['adjustments'], 2) }}
            </p>
        </div>

        <!-- Net Budget -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 font-medium">Net Budget</p>
            <p class="text-2xl font-bold text-lnu-blue mt-2">₱{{ number_format($budgetData['net'], 2) }}</p>
        </div>

        <!-- Total Spent -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 font-medium">Total Spent</p>
            <p class="text-2xl font-bold text-orange-600 mt-2">₱{{ number_format($budgetData['spent'], 2) }}</p>
        </div>

        <!-- Remaining Budget -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 font-medium">Remaining</p>
            <p class="text-2xl font-bold {{ $budgetData['remaining'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                ₱{{ number_format($budgetData['remaining'], 2) }}
            </p>
        </div>
    </div>

    <!-- Budget Usage Progress -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Budget Utilization</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">Used: {{ $budgetData['percentageUsed'] }}%</span>
                <span class="text-sm text-gray-600">₱{{ number_format($budgetData['spent'], 2) }} / ₱{{ number_format($budgetData['net'], 2) }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div 
                    class="bg-gradient-to-r from-green-500 to-orange-500 h-3 rounded-full transition-all duration-300"
                    style="width: {{ min($budgetData['percentageUsed'], 100) }}%"
                ></div>
            </div>
            @if($budgetData['remaining'] < 0)
                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700 font-medium">⚠️ Budget Exceeded by ₱{{ number_format(abs($budgetData['remaining']), 2) }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Button -->
    <div class="mb-8">
        <a href="{{ route('programs.budgets.create', $program) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Transaction
        </a>
    </div>

    <!-- Budget Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Transactions</h2>
        </div>

        @if($program->budgetUtilizations->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Type</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Description</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-900">Amount</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-900">Attachment</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($program->budgetUtilizations()->orderBy('date_spent', 'desc')->get() as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-700">{{ $transaction->date_spent->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->transaction_type === 'expense' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($transaction->transaction_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700 max-w-xs truncate">{{ $transaction->description }}</td>
                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                            {{ $transaction->transaction_type === 'expense' ? '-' : '+' }}₱{{ number_format($transaction->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($transaction->attachment)
                                <a href="{{ route('programs.budgets.download', [$program, $transaction]) }}" class="text-lnu-blue hover:text-blue-700 font-medium text-sm">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('programs.budgets.edit', [$program, $transaction]) }}" class="text-lnu-blue hover:text-blue-700 font-medium transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('programs.budgets.destroy', [$program, $transaction]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this transaction?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No budget transactions recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-8 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p>No budget transactions recorded yet. Start by adding your first transaction.</p>
        </div>
        @endif
    </div>
</div>
@endsection
