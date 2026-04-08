<x-admin-layout header="Create Beneficiary">
    <div class="max-w-full">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Form -->
            <form method="POST" action="{{ route('beneficiaries.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Personal Information Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                        </svg>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- First Name -->
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" class="required" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" 
                                :value="old('first_name')" placeholder="Juan" required />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <x-input-label for="middle_name" :value="__('Middle Name')" />
                            <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" 
                                :value="old('middle_name')" placeholder="de la Cruz" />
                            <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                        </div>

                        <!-- Last Name -->
                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')" class="required" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" 
                                :value="old('last_name')" placeholder="Santos" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Date of Birth -->
                        <div>
                            <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                            <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" 
                                :value="old('date_of_birth')" />
                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                        </div>

                        <!-- Age -->
                        <div>
                            <x-input-label for="age" :value="__('Age')" />
                            <x-text-input id="age" class="block mt-1 w-full" type="number" name="age" 
                                :value="old('age')" placeholder="25" min="1" max="150" />
                            <x-input-error :messages="$errors->get('age')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Gender -->
                        <div>
                            <x-input-label for="gender" :value="__('Gender')" />
                            <select id="gender" name="gender" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Gender</option>
                                <option value="male" @if(old('gender') === 'male') selected @endif>Male</option>
                                <option value="female" @if(old('gender') === 'female') selected @endif>Female</option>
                                <option value="other" @if(old('gender') === 'other') selected @endif>Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <!-- Marital Status -->
                        <div>
                            <x-input-label for="marital_status" :value="__('Marital Status')" />
                            <x-text-input id="marital_status" class="block mt-1 w-full" type="text" name="marital_status" 
                                :value="old('marital_status')" placeholder="Single, Married, etc." />
                            <x-input-error :messages="$errors->get('marital_status')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773c.418 1.265 1.215 2.807 2.453 4.045s2.78 2.035 4.045 2.453l.773-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2.57a8 8 0 01-7.992-7.992V3z" />
                        </svg>
                        Contact Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" 
                                :value="old('email')" placeholder="juan@example.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" 
                                :value="old('phone')" placeholder="+63 9XX XXX XXXX" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Address Information
                    </h3>

                    <div class="mb-4">
                        <x-input-label for="address" :value="__('Street Address')" />
                        <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" 
                            :value="old('address')" placeholder="123 Main Street" />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Barangay -->
                        <div>
                            <x-input-label for="barangay" :value="__('Barangay')" />
                            <x-text-input id="barangay" class="block mt-1 w-full" type="text" name="barangay" 
                                :value="old('barangay')" placeholder="Barangay Name" />
                            <x-input-error :messages="$errors->get('barangay')" class="mt-2" />
                        </div>

                        <!-- Municipality -->
                        <div>
                            <x-input-label for="municipality" :value="__('Municipality')" />
                            <x-text-input id="municipality" class="block mt-1 w-full" type="text" name="municipality" 
                                :value="old('municipality')" placeholder="City/Municipality" />
                            <x-input-error :messages="$errors->get('municipality')" class="mt-2" />
                        </div>

                        <!-- Province -->
                        <div>
                            <x-input-label for="province" :value="__('Province')" />
                            <x-text-input id="province" class="block mt-1 w-full" type="text" name="province" 
                                :value="old('province')" placeholder="Province" />
                            <x-input-error :messages="$errors->get('province')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Program & Community Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 000-2H7zM4 7a1 1 0 011-1h10a1 1 0 011 1v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7z" />
                        </svg>
                        Program & Community
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Community -->
                        <div>
                            <x-input-label for="community_id" :value="__('Community')" />
                            <select id="community_id" name="community_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Community</option>
                                @foreach ($communities as $community)
                                <option value="{{ $community->id }}" @if(old('community_id') == $community->id) selected @endif>
                                    {{ $community->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('community_id')" class="mt-2" />
                        </div>

                        <!-- Beneficiary Category -->
                        <div>
                            <x-input-label for="beneficiary_category" :value="__('Beneficiary Category')" />
                            <x-text-input id="beneficiary_category" class="block mt-1 w-full" type="text" name="beneficiary_category" 
                                :value="old('beneficiary_category')" placeholder="e.g., Student, Parent, Senior" />
                            <x-input-error :messages="$errors->get('beneficiary_category')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Programs -->
                    <div class="mt-4">
                        <x-input-label for="program_ids" :value="__('Programs')" />
                        <div class="mt-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3 bg-gray-50">
                            @foreach ($programs as $program)
                            <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                <input type="checkbox" name="program_ids[]" value="{{ $program->id }}" 
                                    @if(in_array($program->id, old('program_ids', []))) checked @endif
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50" />
                                <span class="ml-2 text-sm text-gray-700">{{ $program->title }}</span>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('program_ids')" class="mt-2" />
                    </div>
                </div>

                <!-- Socioeconomic Information Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H8.5z" />
                        </svg>
                        Socioeconomic Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Monthly Income -->
                        <div>
                            <x-input-label for="monthly_income" :value="__('Monthly Income (₱)')" />
                            <x-text-input id="monthly_income" class="block mt-1 w-full" type="number" name="monthly_income" 
                                :value="old('monthly_income')" placeholder="0.00" step="0.01" min="0" />
                            <x-input-error :messages="$errors->get('monthly_income')" class="mt-2" />
                        </div>

                        <!-- Occupation -->
                        <div>
                            <x-input-label for="occupation" :value="__('Occupation')" />
                            <x-text-input id="occupation" class="block mt-1 w-full" type="text" name="occupation" 
                                :value="old('occupation')" placeholder="e.g., Farmer, Teacher" />
                            <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Educational Attainment -->
                        <div>
                            <x-input-label for="educational_attainment" :value="__('Educational Attainment')" />
                            <select id="educational_attainment" name="educational_attainment" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Education Level</option>
                                <option value="Elementary" @if(old('educational_attainment') === 'Elementary') selected @endif>Elementary</option>
                                <option value="High School" @if(old('educational_attainment') === 'High School') selected @endif>High School</option>
                                <option value="College" @if(old('educational_attainment') === 'College') selected @endif>College</option>
                                <option value="Graduate" @if(old('educational_attainment') === 'Graduate') selected @endif>Graduate</option>
                            </select>
                            <x-input-error :messages="$errors->get('educational_attainment')" class="mt-2" />
                        </div>

                        <!-- Number of Dependents -->
                        <div>
                            <x-input-label for="number_of_dependents" :value="__('Number of Dependents')" />
                            <x-text-input id="number_of_dependents" class="block mt-1 w-full" type="number" name="number_of_dependents" 
                                :value="old('number_of_dependents')" placeholder="0" min="0" max="20" />
                            <x-input-error :messages="$errors->get('number_of_dependents')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Status & Notes Section -->
                <div class="pb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                        </svg>
                        Status & Notes
                    </h3>

                    <div class="mb-4">
                        <x-input-label for="status" :value="__('Status')" class="required" />
                        <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="active" @if(old('status') === 'active') selected @endif>Active</option>
                            <option value="inactive" @if(old('status') === 'inactive') selected @endif>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Notes')" />
                        <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                            placeholder="Any additional remarks or notes...">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-gray-200 pt-6 flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-lnu-blue text-white font-semibold rounded-lg hover:bg-blue-800 transition shadow-md">
                        Create Beneficiary
                    </button>
                    <a href="{{ route('beneficiaries.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-900 font-semibold rounded-lg hover:bg-gray-300 transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
