<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @keydown.escape="$dispatch('close-modal')">
    
    <!-- Main Modal -->
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col">
        <!-- Header - Fixed -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-6 flex justify-between items-center rounded-t-xl border-b border-green-700 flex-shrink-0">
            <h3 class="text-2xl font-bold">Record New Expense</h3>
            <button type="button" wire:click="$dispatch('close-modal')" class="text-white hover:bg-green-700 p-2 rounded-lg transition">
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
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
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
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
                        />
                        @error('amount') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea 
                        wire:model="description"
                        placeholder="What is this expense for?"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
                        rows="3"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                    <select wire:model="transaction_type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition bg-white">
                        <option value="expense">Expense</option>
                        <option value="adjustment">Adjustment</option>
                    </select>
                </div>
            </div>

            <!-- Link to Activity Section -->
            <div class="space-y-4 pt-6 border-t">
                <h4 class="text-lg font-semibold text-gray-800">Link to Activity <span class="text-gray-500 font-normal text-sm">(Optional)</span></h4>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Activity</label>
                    <select wire:model.live="activity_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition bg-white">
                        <option value="">-- No Activity --</option>
                        @foreach($activities as $activity)
                            <option value="{{ $activity['id'] }}">{{ $activity['title'] }}</option>
                        @endforeach
                    </select>
                </div>

                @if($activity_id && $this->getSelectedActivityRemainingBudget() !== null)
                    @php
                        $remainingBudget = $this->getSelectedActivityRemainingBudget();
                        $isLow = $remainingBudget < $amount;
                    @endphp
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-gray-700 mb-2"><strong>Activity Budget Remaining:</strong></p>
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl font-bold {{ $isLow ? 'text-red-600' : 'text-blue-600' }}">
                                ₱{{ number_format($remainingBudget, 2) }}
                            </p>
                            @if($isLow)
                                <span class="text-xs font-semibold text-red-600 bg-red-100 px-3 py-1 rounded-full">Warning: Over budget</span>
                            @endif
                        </div>
                    </div>
                @endif
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
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
                    />
                    @error('budget_source') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source Description</label>
                    <textarea 
                        wire:model="source_description"
                        placeholder="Provide details about the budget source..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
                        rows="2"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Approval Status</label>
                    <select wire:model="approval_status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition bg-white">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <!-- Attachments Section -->
            <div class="space-y-4 pt-6 border-t">
                <h4 class="text-lg font-semibold text-gray-800">Attachments <span class="text-gray-500 font-normal text-sm">(Optional - Max 3 files, 10MB each)</span></h4>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50 hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer" onclick="document.getElementById('file-upload').click()">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <p class="text-gray-700 font-medium mb-1">Click or drag files to upload</p>
                    <p class="text-xs text-gray-600">PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max 10MB each)</p>
                    <input 
                        id="file-upload"
                        type="file" 
                        wire:model="uploadedFiles"
                        multiple
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                        class="hidden"
                    />
                </div>

                <!-- Display uploaded files -->
                @if(count($attachments) > 0)
                    <div class="space-y-2 bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-sm font-medium text-gray-800 mb-3">{{ count($attachments) }} file(s) selected</p>
                        @foreach($attachments as $index => $attachment)
                            <div class="bg-white border border-blue-200 rounded-lg p-3 flex justify-between items-center">
                                <div class="flex items-center gap-3 flex-1">
                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800 text-sm">{{ $attachment['name'] }}</p>
                                        <p class="text-xs text-gray-600">{{ number_format($attachment['size'] / 1024, 2) }} KB</p>
                                    </div>
                                </div>
                                <button 
                                    type="button"
                                    wire:click="removeAttachment({{ $index }})"
                                    class="text-red-500 hover:text-red-700 p-1 ml-2 flex-shrink-0"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                @error('uploadedFiles')
                    <span class="text-red-500 text-sm block">{{ $message }}</span>
                @enderror
            </div>

            <!-- People Involved Section -->
            <div class="space-y-4 pt-6 border-t">
                <h4 class="text-lg font-semibold text-gray-800">People Involved <span class="text-gray-500 font-normal text-sm">(Optional)</span></h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input 
                        type="text" 
                        wire:model="personName" 
                        placeholder="Full Name"
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
                    />
                    <input 
                        type="text" 
                        wire:model="personPosition" 
                        placeholder="Position/Title"
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
                    />
                    <input 
                        type="text" 
                        wire:model="personOffice" 
                        placeholder="Office/Department"
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
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
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
                    />
                    <input 
                        type="text" 
                        wire:model="officeContact" 
                        placeholder="Contact Person/Phone"
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:outline-none transition"
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
                wire:click="$dispatch('close-modal')"
                class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium"
            >
                Cancel
            </button>
            <button 
                type="button"
                wire:click="saveExpense"
                wire:loading.attr="disabled"
                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium inline-flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path>
                </svg>
                <span wire:loading.remove>Record Expense</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </div>

    <!-- Success/Error Alert Modal - Only show if alert is active -->
    @if($showAlert)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[60] p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full overflow-hidden">
                <!-- Alert Header -->
                @if($alertType === 'success')
                    <div class="bg-green-600 text-white px-6 py-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="text-xl font-bold">Success!</h3>
                        </div>
                    </div>
                @else
                    <div class="bg-red-600 text-white px-6 py-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="text-xl font-bold">Error!</h3>
                        </div>
                    </div>
                @endif

                <!-- Alert Body -->
                <div class="px-6 py-4">
                    <p class="text-gray-800 font-medium">{{ $alertMessage }}</p>
                    @if($alertDetails)
                        <p class="text-gray-600 text-sm mt-2">{{ $alertDetails }}</p>
                    @endif
                </div>

                <!-- Alert Footer -->
                <div class="border-t border-gray-200 px-6 py-3 flex justify-end">
                    <button 
                        wire:click="closeAlert"
                        @class([
                            'px-6 py-2 text-white rounded-lg transition font-medium',
                            'bg-green-600 hover:bg-green-700' => $alertType === 'success',
                            'bg-red-600 hover:bg-red-700' => $alertType === 'error',
                        ])
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
