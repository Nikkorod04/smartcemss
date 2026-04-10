<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <!-- Main Modal -->
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[85vh]">
        <!-- Header - Fixed -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white px-8 py-6 flex justify-between items-center rounded-t-xl border-b border-amber-700 flex-shrink-0">
            <h3 class="text-2xl font-bold">Edit Expense</h3>
            <button type="button" wire:click="closeModal" class="text-white hover:bg-amber-700 p-2 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body - Scrollable -->
        <form wire:submit.prevent="saveExpense" class="flex-1 overflow-y-auto p-8 space-y-8">
            <!-- Expense Details Section -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-800">Expense Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Spent *</label>
                        <input 
                            type="date" 
                            wire:model="date_spent"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                        />
                        @error('date_spent') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount (₱) *</label>
                        <input 
                            type="number" 
                            wire:model="amount"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                        />
                        @error('amount') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea 
                        wire:model="description"
                        placeholder="What is this expense for?"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                        rows="3"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                    <select wire:model="transaction_type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition bg-white">
                        <option value="expense">Expense</option>
                        <option value="adjustment">Adjustment</option>
                    </select>
                </div>
            </div>

            <!-- Budget Source Section -->
            <div class="space-y-4 pt-6 border-t">
                <h4 class="text-lg font-semibold text-gray-800">Budget Source</h4>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source Name *</label>
                    <input 
                        type="text" 
                        wire:model="budget_source"
                        placeholder="e.g., University Fund, External Grant, Donation"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                    />
                    @error('budget_source') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source Description</label>
                    <textarea 
                        wire:model="source_description"
                        placeholder="Provide details about the budget source..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                        rows="2"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Approval Status</label>
                    <select wire:model="approval_status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition bg-white">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <!-- People Involved Section -->
            <div class="space-y-4 pt-6 border-t">
                <h4 class="text-lg font-semibold text-gray-800">People Involved <span class="text-gray-500 font-normal text-sm">(Optional)</span></h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input 
                        type="text" 
                        wire:model="personName" 
                        placeholder="Full Name"
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                    />
                    <input 
                        type="text" 
                        wire:model="personPosition" 
                        placeholder="Position/Title"
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                    />
                    <input 
                        type="text" 
                        wire:model="personOffice" 
                        placeholder="Office/Department"
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                    />
                </div>
                <button 
                    type="button" 
                    wire:click="addPerson"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium text-sm inline-flex items-center"
                >
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Add Person
                </button>

                <!-- Listed People -->
                @if(count($people_involved) > 0)
                    <div class="space-y-2 bg-green-50 p-4 rounded-lg border border-green-200">
                        @foreach($people_involved as $index => $person)
                            <div class="bg-white border border-green-200 rounded-lg p-3 flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $person['name'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $person['position'] }}</p>
                                    @if($person['office'])
                                        <p class="text-sm text-gray-500">{{ $person['office'] }}</p>
                                    @endif
                                </div>
                                <button 
                                    type="button"
                                    wire:click="removePerson({{ $index }})"
                                    class="text-red-500 hover:text-red-700 p-1"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Offices Involved Section -->
            <div class="space-y-4 pt-6 border-t pb-6">
                <h4 class="text-lg font-semibold text-gray-800">Offices/Departments Involved <span class="text-gray-500 font-normal text-sm">(Optional)</span></h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <input 
                        type="text" 
                        wire:model="officeName" 
                        placeholder="Office/Department Name"
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                    />
                    <input 
                        type="text" 
                        wire:model="officeContact" 
                        placeholder="Contact Person/Phone"
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:outline-none transition"
                    />
                </div>
                <button 
                    type="button" 
                    wire:click="addOffice"
                    class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-medium text-sm inline-flex items-center"
                >
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Add Office
                </button>

                <!-- Listed Offices -->
                @if(count($offices_involved) > 0)
                    <div class="space-y-2 bg-purple-50 p-4 rounded-lg border border-purple-200">
                        @foreach($offices_involved as $index => $office)
                            <div class="bg-white border border-purple-200 rounded-lg p-3 flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $office['name'] }}</p>
                                    @if($office['contact'])
                                        <p class="text-sm text-gray-600">{{ $office['contact'] }}</p>
                                    @endif
                                </div>
                                <button 
                                    type="button"
                                    wire:click="removeOffice({{ $index }})"
                                    class="text-red-500 hover:text-red-700 p-1"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </form>

        <!-- Footer - Fixed -->
        <div class="border-t border-gray-200 bg-white px-8 py-4 flex justify-end space-x-3 rounded-b-xl flex-shrink-0">
            <button 
                type="button"
                wire:click="closeModal"
                class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium"
            >
                Cancel
            </button>
            <button 
                type="button"
                wire:click="saveExpense"
                wire:loading.attr="disabled"
                class="px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium inline-flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path>
                </svg>
                <span wire:loading.remove>Save Changes</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </div>
</div>
