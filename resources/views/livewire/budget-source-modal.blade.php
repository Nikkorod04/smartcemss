<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @keydown.escape="@this.call('close')">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold">Budget Source Information</h3>
            <button wire:click="$parent.closeModal()" class="text-white hover:bg-blue-700 p-1 rounded">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <form wire:submit.prevent="saveBudgetSource" class="p-6 space-y-6">
            <!-- Budget Source Section -->
            <div class="space-y-4 border-b pb-6">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                    Budget Source
                </h4>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source Name *</label>
                    <input 
                        type="text" 
                        wire:model="budgetSource"
                        placeholder="e.g., University Fund, External Grant, Donation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    @error('budgetSource') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source Description</label>
                    <textarea 
                        wire:model="sourceDescription"
                        placeholder="Provide details about how this budget was acquired..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        rows="3"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Approval Status</label>
                    <select wire:model="approvalStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <!-- People Involved Section -->
            <div class="space-y-4 border-b pb-6">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                    </svg>
                    People Involved
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input 
                        type="text" 
                        wire:model="personName" 
                        placeholder="Full Name"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    />
                    <input 
                        type="text" 
                        wire:model="personPosition" 
                        placeholder="Position/Title"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    />
                    <input 
                        type="text" 
                        wire:model="personOffice" 
                        placeholder="Office/Department"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    />
                </div>
                <button 
                    type="button" 
                    wire:click="addPerson"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition flex items-center"
                >
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Add Person
                </button>

                <!-- Listed People -->
                <div class="space-y-2">
                    @foreach($peopleInvolved as $index => $person)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $person['name'] }}</p>
                                <p class="text-sm text-gray-600">{{ $person['position'] }}</p>
                                @if($person['office'])
                                    <p class="text-sm text-gray-500">Office: {{ $person['office'] }}</p>
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
            </div>

            <!-- Offices Involved Section -->
            <div class="space-y-4 border-b pb-6">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Offices/Departments Involved
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <input 
                        type="text" 
                        wire:model="officeName" 
                        placeholder="Office/Department Name"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    />
                    <input 
                        type="text" 
                        wire:model="officeContact" 
                        placeholder="Contact Person/Phone"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    />
                </div>
                <button 
                    type="button" 
                    wire:click="addOffice"
                    class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition flex items-center"
                >
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Add Office
                </button>

                <!-- Listed Offices -->
                <div class="space-y-2">
                    @foreach($officesInvolved as $index => $office)
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $office['name'] }}</p>
                                @if($office['contact'])
                                    <p class="text-sm text-gray-600">Contact: {{ $office['contact'] }}</p>
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
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4">
                <button 
                    type="button"
                    wire:click="$parent.closeModal()"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path>
                    </svg>
                    Save Budget Source
                </button>
            </div>

            @if (session()->has('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
        </form>
    </div>
</div>
