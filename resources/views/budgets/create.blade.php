@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Add Budget Transaction</h1>
            <p class="text-gray-600 mt-2">Program: {{ $program->title }}</p>
        </div>
        <a href="{{ route('programs.budgets.index', $program) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Budget
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 max-w-2xl">
        <form action="{{ route('programs.budgets.store', $program) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <!-- Date Spent -->
            <div class="mb-6">
                <label for="date_spent" class="block text-sm font-medium text-gray-900 mb-2">Date Spent</label>
                <input 
                    type="date" 
                    name="date_spent" 
                    id="date_spent"
                    value="{{ old('date_spent', date('Y-m-d')) }}"
                    max="{{ date('Y-m-d') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue focus:border-transparent"
                >
                @error('date_spent')<span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <!-- Transaction Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 mb-3">Transaction Type</label>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer {{ old('transaction_type') === 'expense' ? 'border-lnu-blue bg-blue-50' : '' }}">
                        <input 
                            type="radio" 
                            name="transaction_type" 
                            value="expense"
                            {{ old('transaction_type', 'expense') === 'expense' ? 'checked' : '' }}
                            required
                            class="w-4 h-4"
                        >
                        <div>
                            <p class="font-medium text-gray-900">Expense</p>
                            <p class="text-xs text-gray-600">Money spent on program activities</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer {{ old('transaction_type') === 'adjustment' ? 'border-lnu-blue bg-blue-50' : '' }}">
                        <input 
                            type="radio" 
                            name="transaction_type" 
                            value="adjustment"
                            {{ old('transaction_type') === 'adjustment' ? 'checked' : '' }}
                            class="w-4 h-4"
                        >
                        <div>
                            <p class="font-medium text-gray-900">Adjustment</p>
                            <p class="text-xs text-gray-600">Budget increase/decrease or reallocation</p>
                        </div>
                    </label>
                </div>
                @error('transaction_type')<span class="text-red-600 text-sm mt-2 block">{{ $message }}</span>@enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label for="amount" class="block text-sm font-medium text-gray-900 mb-2">Amount (₱)</label>
                <input 
                    type="number" 
                    name="amount" 
                    id="amount"
                    step="0.01"
                    min="0.01"
                    value="{{ old('amount') }}"
                    required
                    placeholder="0.00"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue focus:border-transparent"
                >
                @error('amount')<span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                <textarea 
                    name="description" 
                    id="description"
                    rows="4"
                    required
                    placeholder="Describe the expense or adjustment..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue focus:border-transparent"
                >{{ old('description') }}</textarea>
                @error('description')<span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <!-- Attachment -->
            <div class="mb-6">
                <label for="attachment" class="block text-sm font-medium text-gray-900 mb-2">Attachment (Optional)</label>
                <p class="text-xs text-gray-600 mb-3">Upload receipt, invoice, or documentation (PDF, JPG, PNG, DOC, DOCX - Max 5MB)</p>
                <input 
                    type="file" 
                    name="attachment" 
                    id="attachment"
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue focus:border-transparent"
                >
                @error('attachment')<span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="flex-1 px-4 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition"
                >
                    Save Transaction
                </button>
                <a 
                    href="{{ route('programs.budgets.index', $program) }}"
                    class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium rounded-lg transition text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
