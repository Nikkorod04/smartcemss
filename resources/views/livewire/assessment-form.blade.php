<div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8">
    <h1 class="text-3xl font-bold text-lnu-blue mb-6">{{ $isEditing ? 'Edit' : 'Create' }} Community Needs Assessment (F-CES-001)</h1>

    <form wire:submit.prevent="submit" class="space-y-8">
        <!-- Basic Information -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Community *</label>
                    <select wire:model="community_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                        <option value="">Select Community</option>
                        @foreach($communities as $community)
                            <option value="{{ $community->id }}">{{ $community->name }}</option>
                        @endforeach
                    </select>
                    @error('community_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Quarter *</label>
                    <select wire:model="quarter" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                        <option value="">Select Quarter</option>
                        @foreach($quarters as $q)
                            <option value="{{ $q }}">{{ $q }}</option>
                        @endforeach
                    </select>
                    @error('quarter')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Year *</label>
                    <select wire:model="year" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                        <option value="">Select Year</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                    @error('year')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Input Method Selection -->
        @if(!$isEditing)
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Input Method</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="p-4 border-2 rounded-lg cursor-pointer transition {{ $input_method === 'manual' ? 'border-lnu-blue bg-blue-50' : 'border-gray-300' }}">
                    <input type="radio" name="input_method" wire:model.live="input_method" value="manual" class="w-4 h-4" />
                    <span class="ml-2 font-medium">Manual Input</span>
                    <p class="text-sm text-gray-600 mt-1">Enter data manually using the form below</p>
                </label>
                <label class="p-4 border-2 rounded-lg cursor-pointer transition {{ $input_method === 'file' ? 'border-lnu-blue bg-blue-50' : 'border-gray-300' }}">
                    <input type="radio" name="input_method" wire:model.live="input_method" value="file" class="w-4 h-4" />
                    <span class="ml-2 font-medium">Upload File(s)</span>
                    <p class="text-sm text-gray-600 mt-1">Upload up to 4 page images (auto-converts to PDF) or PDF directly</p>
                </label>
            </div>
        </div>

        <!-- File Upload Section -->
        @if($input_method === 'file' && !$isEditing)
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Upload Assessment File(s)</h2>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Select Files *</label>
                <input type="file" wire:model="assessment_files" accept=".jpg,.jpeg,.png,.pdf,.xlsx,.csv" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" />
                <p class="text-xs text-gray-500 mt-1">📸 Multiple images (max 4): Auto-converts to PDF. Single file: PDF, XLSX, CSV, JPG, or PNG (Max 10MB each)</p>
                
                <!-- File count indicator -->
                @if(!empty($assessment_files))
                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm font-medium text-blue-900">
                        📁 {{ count($assessment_files) }} file(s) selected
                        @if(count($assessment_files) > 1)
                            <span class="text-xs text-blue-700">(Will auto-convert to single PDF)</span>
                        @endif
                    </p>
                </div>
                @endif
                
                @error('assessment_files')<span class="text-red-600 text-sm block mt-2">{{ $message }}</span>@enderror
                @error('assessment_files.*')<span class="text-red-600 text-sm block mt-2">{{ $message }}</span>@enderror
            </div>

            <!-- Import Status Message -->
            @if($importStatus)
            <div class="mt-4 p-4 rounded-lg {{ $importStatus === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                <p class="text-sm {{ $importStatus === 'success' ? 'text-green-800' : 'text-red-800' }}">
                    {{ $importMessage }}
                </p>
            </div>
            @endif

            <!-- Imported Data Review Section -->
            @if($showImportedDataReview && !empty($importedData))
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                
                <!-- Raw OCR Text -->
                @if(!empty($rawOcrText))
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-900 mb-3 border-b pb-2">📄 Raw OCR Text</h3>
                    <pre class="bg-white p-4 rounded border border-gray-300 text-xs overflow-auto max-h-64 text-gray-700 whitespace-pre-wrap break-words">{{ $rawOcrText }}</pre>
                </div>
                @endif
                
                <!-- Cleaned Extracted Data -->
                @if(!empty($cleanedExtractedData))
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-900 mb-3 border-b pb-2">✨ LLM Cleaned Data (JSON)</h3>
                    <pre class="bg-white p-4 rounded border border-gray-300 text-xs overflow-auto max-h-64 text-gray-700 whitespace-pre-wrap break-words">{{ json_encode($cleanedExtractedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
                @endif
                
                <h3 class="text-md font-semibold text-blue-900 mb-4">Extracted Data Preview</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    @foreach($importedData as $fieldName => $value)
                        <div class="p-3 bg-white border border-gray-200 rounded">
                            <p class="text-xs font-medium text-gray-600">{{ str_replace('_', ' ', $fieldName) }}</p>
                            <p class="text-sm text-gray-900 font-medium mt-1">
                                @if(is_array($value))
                                    {{ implode(', ', $value) }}
                                @else
                                    {{ $value }}
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="flex gap-3">
                    <button type="button" wire:click="populateFromImportedData" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                        Confirm & Populate Fields
                    </button>
                    <button type="button" wire:click="clearImportedData" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">
                        Clear & Re-upload
                    </button>
                </div>
            </div>
            @endif
        </div>
        @endif
        @endif

        <!-- Manual Form Sections -->
        @if($input_method === 'manual' || $isEditing)

        <!-- SECTION I: Identifying Information -->
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-blue-900 bg-blue-100 px-4 py-3 rounded-lg mb-4">SECTION I: Identifying Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">First Name</label>
                    <input type="text" wire:model="respondent_first_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" />
                    @error('respondent_first_name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Middle Name (Optional)</label>
                    <input type="text" wire:model="respondent_middle_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Last Name</label>
                    <input type="text" wire:model="respondent_last_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" />
                    @error('respondent_last_name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Age</label>
                    <input type="number" wire:model="respondent_age" min="0" max="150" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Civil Status</label>
                    <input type="text" wire:model="respondent_civil_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" placeholder="e.g., Single, Married, Divorced" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Sex</label>
                    <select wire:model="respondent_sex" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Religion</label>
                    <input type="text" wire:model="respondent_religion" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Educational Attainment</label>
                    <div class="space-y-2">
                        <label class="flex items-center"><input type="checkbox" wire:model="respondent_educational_attainment" value="Elementary" class="w-4 h-4" /> <span class="ml-2 text-sm">Elementary</span></label>
                        <label class="flex items-center"><input type="checkbox" wire:model="respondent_educational_attainment" value="High School" class="w-4 h-4" /> <span class="ml-2 text-sm">High School</span></label>
                        <label class="flex items-center"><input type="checkbox" wire:model="respondent_educational_attainment" value="College" class="w-4 h-4" /> <span class="ml-2 text-sm">College</span></label>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION II: Family Composition -->
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-green-900 bg-green-100 px-4 py-3 rounded-lg mb-4">SECTION II: Family Composition</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Number of Adults in the Household</label>
                    <select wire:model="family_adults" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                        <option value="">Select</option>
                        @for ($i = 1; $i <= 100; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Number of Children in the Household</label>
                    <select wire:model="family_children" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                        <option value="">Select</option>
                        <option value="0">0</option>
                        @for ($i = 1; $i <= 100; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION III: Economic Aspect -->
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-orange-900 bg-orange-100 px-4 py-3 rounded-lg mb-4">SECTION III: Economic Aspect</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Livelihood Options</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach(['Farming', 'Raising animals', 'Selling', 'Driving', 'Remittance', 'Pension', '4Ps', 'Rentals', 'Service Work', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="livelihood_options" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Interested in Livelihood Training?</label>
                <div class="space-y-2">
                    <label class="flex items-center"><input type="radio" name="interested_in_livelihood_training" wire:model.live="interested_in_livelihood_training" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                    <label class="flex items-center"><input type="radio" name="interested_in_livelihood_training" wire:model.live="interested_in_livelihood_training" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                </div>
            </div>
            @if($show_training_interested)
            <div class="mb-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <label class="block text-sm font-medium text-gray-900 mb-2">Desired Training</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Cosmetology', 'Handicrafts', 'Electronics', 'Computer', 'Refrigerator & A/C', 'Food Processing', 'Dress Making', 'Pananom', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="desired_training" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- SECTION IV: Educational Aspect -->
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-purple-900 bg-purple-100 px-4 py-3 rounded-lg mb-4">SECTION IV: Educational Aspect</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Barangay Educational Facilities</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Daycare', 'Elementary Primary', 'Elementary Intermediate', 'Secondary', 'College'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="barangay_educational_facilities" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Household Member Currently Studying?</label>
                <div class="space-y-2">
                    <label class="flex items-center"><input type="radio" name="household_member_currently_studying" wire:model.live="household_member_currently_studying" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                    <label class="flex items-center"><input type="radio" name="household_member_currently_studying" wire:model.live="household_member_currently_studying" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                </div>
            </div>
            @if($show_education_fields)
            <div class="mb-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <label class="block text-sm font-medium text-gray-900 mb-2">Interested in Continuing Studies?</label>
                <div class="space-y-2">
                    <label class="flex items-center"><input type="radio" name="interested_in_continuing_studies" wire:model="interested_in_continuing_studies" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                    <label class="flex items-center"><input type="radio" name="interested_in_continuing_studies" wire:model="interested_in_continuing_studies" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Areas of Educational Interest</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach(['Reading', 'Writing', 'Math', 'English', 'Issues', 'Laws', 'Other'] as $option)
                            <label class="flex items-center"><input type="checkbox" wire:model="areas_of_educational_interest" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Preferred Training Time</label>
                        <select wire:model="preferred_training_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                            <option value="">Select</option>
                            <option value="Morning 8-12">Morning 8-12</option>
                            <option value="Afternoon 1:30-5">Afternoon 1:30-5</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Preferred Training Days</label>
                        <div class="space-y-2">
                            @foreach(['Wednesday', 'Saturday', 'Sunday'] as $day)
                                <label class="flex items-center"><input type="checkbox" wire:model="preferred_training_days" value="{{ $day }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $day }}</span></label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- SECTION V: Health, Sanitation, Environmental -->
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-red-900 bg-red-100 px-4 py-3 rounded-lg mb-4">SECTION V: Health, Sanitation, Environmental</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Common Illnesses</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach(['Colds', 'Flu', 'Asthma', 'Pneumonia', 'Diarrhea', 'Schistosomiasis', 'Hypertension', 'Diabetes', 'Vomiting', 'Headache', 'Stomach Ache', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="common_illnesses" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Action When Sick</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Hospital/Health Center', 'Herbal Medicine', 'Albularyo', 'Hilot', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="action_when_sick" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Barangay Medical Supplies Available</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Ambulance', 'Health Center', 'Medical Equipment', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="barangay_medical_supplies_available" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Has Barangay Health Programs?</label>
                    <div class="space-y-2">
                        <label class="flex items-center"><input type="radio" name="has_barangay_health_programs" wire:model="has_barangay_health_programs" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                        <label class="flex items-center"><input type="radio" name="has_barangay_health_programs" wire:model="has_barangay_health_programs" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Benefits from Barangay Programs?</label>
                    <div class="space-y-2">
                        <label class="flex items-center"><input type="radio" name="benefits_from_barangay_programs" wire:model="benefits_from_barangay_programs" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                        <label class="flex items-center"><input type="radio" name="benefits_from_barangay_programs" wire:model="benefits_from_barangay_programs" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Programs Benefited From</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Free Vaccine', 'Free Consultation', 'Pre-natal check-up', 'Check-up', 'Free Medicine', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="programs_benefited_from" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Water Source</label>
                    <div class="space-y-2">
                        @foreach(['NAWASA', 'Water Pump', 'Deep Well', 'Spring Water', 'River/Stream', 'Other'] as $option)
                            <label class="flex items-center"><input type="checkbox" wire:model="water_source" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Water Source Distance</label>
                    <select wire:model="water_source_distance" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                        <option value="">Select</option>
                        <option value="Just outside">Just outside</option>
                        <option value="250 meters away">250 meters away</option>
                        <option value="No idea">No idea</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Garbage Disposal Method</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Compost pit', 'Anywhere', 'In the river', 'Vacant lot', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="garbage_disposal_method" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Has Own Toilet?</label>
                    <div class="space-y-2">
                        <label class="flex items-center"><input type="radio" name="has_own_toilet" wire:model.live="has_own_toilet" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                        <label class="flex items-center"><input type="radio" name="has_own_toilet" wire:model.live="has_own_toilet" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                    </div>
                </div>
                @if($show_toilet_type)
                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Toilet Type</label>
                    <div class="space-y-2">
                        @foreach(['Flush toilet', 'Water sealed', 'Antipolo style', 'Other'] as $option)
                            <label class="flex items-center"><input type="checkbox" wire:model="toilet_type" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Keeps Animals?</label>
                    <div class="space-y-2">
                        <label class="flex items-center"><input type="radio" name="keeps_animals" wire:model.live="keeps_animals" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                        <label class="flex items-center"><input type="radio" name="keeps_animals" wire:model.live="keeps_animals" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                    </div>
                </div>
                @if($show_animals_kept)
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Animals Kept</label>
                    <div class="space-y-2">
                        @foreach(['Dog', 'Duck', 'Chicken', 'Cat', 'Other'] as $option)
                            <label class="flex items-center"><input type="checkbox" wire:model="animals_kept" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- SECTION VI: Housing and Basic Amenities -->
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-indigo-900 bg-indigo-100 px-4 py-3 rounded-lg mb-4">SECTION VI: Housing and Basic Amenities</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">House Type</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Wood/Bamboo', 'Makeshift', 'Wood', 'Half concrete/wood', 'Nipa/Bamboo', 'All concrete'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="house_type" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Tenure Status</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Own house/land', 'Own house/rent land', 'Rent house', 'NGO/Gov\'t given', 'Squatter', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="tenure_status" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Has Electricity?</label>
                    <div class="space-y-2">
                        <label class="flex items-center"><input type="radio" name="has_electricity" wire:model.live="has_electricity" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                        <label class="flex items-center"><input type="radio" name="has_electricity" wire:model.live="has_electricity" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                    </div>
                </div>
                @if($show_no_electricity_fields)
                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Light Source Without Power</label>
                    <div class="space-y-2">
                        @foreach(['Oil lamp', 'Candle', 'Solar lamp'] as $option)
                            <label class="flex items-center"><input type="checkbox" wire:model="light_source_without_power" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Appliances Owned</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach(['TV', 'Stove', 'Washing Machine', 'Gas Range', 'Refrigerator', 'Flat Iron', 'Rice Cooker', 'Computer', 'Electric Fan', 'Karaoke', 'Oven Toaster', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="appliances_owned" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SECTION VII: Recreational Facilities -->
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-cyan-900 bg-cyan-100 px-4 py-3 rounded-lg mb-4">SECTION VII: Recreational Facilities</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Barangay Recreational Facilities</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Basketball Court', 'Volleyball Court', 'Tennis Court', 'Soccer Field', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="barangay_recreational_facilities" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Use of Free Time</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Playing basketball', 'Watching TV', 'Playing cards', 'Radio drama', 'Chatting', 'Sleeping', 'Staying home', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="use_of_free_time" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Member of Organization?</label>
                    <div class="space-y-2">
                        <label class="flex items-center"><input type="radio" name="member_of_organization" wire:model="member_of_organization" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                        <label class="flex items-center"><input type="radio" name="member_of_organization" wire:model="member_of_organization" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Organization Types</label>
                    <div class="space-y-2">
                        @foreach(['Civic', 'Religious', 'LGU', 'Cultural', 'Sports/Recreational', 'Other'] as $option)
                            <label class="flex items-center"><input type="checkbox" wire:model="organization_types" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Organization Meeting Frequency</label>
                    <select wire:model="organization_meeting_frequency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                        <option value="">Select</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Twice a month">Twice a month</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Position in Organization</label>
                    <input type="text" wire:model="position_in_organization" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" />
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Organization Usual Activities</label>
                <textarea wire:model="organization_usual_activities" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Household Members in Organization</label>
                <div class="space-y-2">
                    @foreach(['Self', 'Spouse', 'Child', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="household_members_in_organization" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SECTION VIII: Other Needs & Problems -->
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-amber-900 bg-amber-100 px-4 py-3 rounded-lg mb-4">SECTION VIII: Other Needs & Problems</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Family Problems</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Cannot support family needs', 'Separated couple', 'Domestic violence', 'Poor family relationship', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="family_problems" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Health Problems</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Sickly children', 'House near dumpsite', 'Lack of health education', 'Lack of equipment', 'Lack of personnel', 'Malnourished', 'Disease outbreak', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="health_problems" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Educational Problems</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Lack of equipment', 'Lack of qualified teachers', 'Far school', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="educational_problems" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Employment Problems</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Lack of employment', 'Lack of skills', 'No receiving agency', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="employment_problems" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Infrastructure Problems</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Difficult roads', 'No irrigation', 'No waiting shed', 'No post harvest equipment', 'No electric posts', 'Weak bridge', 'Lack of classroom', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="infrastructure_problems" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Economic Problems</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Lack of buyers', 'No capital', 'No product transport', 'No livelihood ideas', 'Many dependents', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="economic_problems" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Security Problems</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach(['Always noisy', 'No police assigned', 'Theft', 'Other'] as $option)
                        <label class="flex items-center"><input type="checkbox" wire:model="security_problems" value="{{ $option }}" class="w-4 h-4" /> <span class="ml-2 text-sm text-xs">{{ $option }}</span></label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SECTION IX: Summary -->
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-teal-900 bg-teal-100 px-4 py-3 rounded-lg mb-4">SECTION IX: Summary</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-4">Barangay Service Ratings (1-5 Scale)</label>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-lnu-blue">
                                <th class="text-left py-2 px-3 font-semibold">Service</th>
                                <th class="text-center py-2 px-1">1</th>
                                <th class="text-center py-2 px-1">2</th>
                                <th class="text-center py-2 px-1">3</th>
                                <th class="text-center py-2 px-1">4</th>
                                <th class="text-center py-2 px-1">5</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['Law Enforcement', 'Fire Protection', 'BNS Service', 'Street Lighting', 'Water system', 'Sanitation', 'Health Service', 'Education Service', 'Infrastructure Service'] as $service)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-3">{{ $service }}</td>
                                @for ($rating = 1; $rating <= 5; $rating++)
                                <td class="text-center py-3">
                                    <input type="radio" name="service_rating_{{ $loop->index }}" wire:model="barangay_service_ratings.{{ $service }}" value="{{ $rating }}" class="w-4 h-4 cursor-pointer" />
                                </td>
                                @endfor
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">General Feedback</label>
                <textarea wire:model="general_feedback" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" placeholder="Any additional comments or feedback..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-2">Available for Training?</label>
                <div class="space-y-2">
                    <label class="flex items-center"><input type="radio" name="available_for_training" wire:model.live="available_for_training" value="Yes" class="w-4 h-4" /> <span class="ml-2 text-sm">Yes</span></label>
                    <label class="flex items-center"><input type="radio" name="available_for_training" wire:model.live="available_for_training" value="No" class="w-4 h-4" /> <span class="ml-2 text-sm">No</span></label>
                </div>
            </div>

            @if($show_reason_not_available)
            <div class="mb-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <label class="block text-sm font-medium text-gray-900 mb-2">Reason Not Available</label>
                <textarea wire:model="reason_not_available" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lnu-blue" placeholder="Please specify why not available..."></textarea>
            </div>
            @endif
        </div>

        @endif

        <!-- Form Actions -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('assessments.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-lnu-blue text-white rounded-lg hover:bg-blue-700 transition">{{ $isEditing ? 'Update' : 'Create' }} Assessment</button>
        </div>
    </form>
</div>
