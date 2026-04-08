<x-admin-layout header="Create Extension Program">
    <div class="max-w-full">
        <form method="POST" action="{{ route('programs.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Main Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-lnu-blue px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">Create New Program</h1>
                    <p class="text-blue-100 mt-1">Fill in the details to add a new extension program</p>
                </div>

                <!-- Form Content -->
                <div class="p-8 space-y-8">
                    <!-- Program Overview Section -->
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-lnu-blue rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Program Overview</h2>
                        </div>
                        
                        <div class="space-y-5">
                            <!-- Title -->
                            <div>
                                <x-input-label for="title" :value="__('Program Title')" class="font-semibold text-gray-800 block mb-2" />
                                <x-text-input id="title" class="block w-full input-field" type="text" name="title" 
                                    :value="old('title')" placeholder="e.g., Sustainable Agriculture Training Program" 
                                    required minlength="5" maxlength="150" pattern="[a-zA-Z0-9\s\-\.&,()]+" title="Title must be 5-150 characters (letters, numbers, and basic punctuation only)" />
                                <p class="text-xs text-gray-500 mt-1">5-150 characters | Letters, numbers, spaces, and punctuation only</p>
                                <x-input-error :messages="$errors->get('title')" class="mt-1" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description')" class="font-semibold text-gray-800 block mb-2" />
                                <textarea id="description" name="description" rows="4" class="block w-full input-field rounded-lg border border-gray-300" 
                                    placeholder="Detailed description of the program..." required minlength="10" maxlength="1500">{{ old('description') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">10-1500 characters minimum</p>
                                <x-input-error :messages="$errors->get('description')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Goals & Objectives -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-lnu-gold rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Goals & Objectives</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="goals" :value="__('Goals')" class="font-semibold text-gray-800 block mb-2" />
                                <textarea id="goals" name="goals" rows="3" class="block w-full input-field rounded-lg border border-gray-300" 
                                    placeholder="What do you want to accomplish?" required minlength="10" maxlength="1000">{{ old('goals') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Minimum 10 characters</p>
                                <x-input-error :messages="$errors->get('goals')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="objectives" :value="__('Objectives')" class="font-semibold text-gray-800 block mb-2" />
                                <textarea id="objectives" name="objectives" rows="3" class="block w-full input-field rounded-lg border border-gray-300" 
                                    placeholder="Specific, measurable outcomes..." required minlength="10" maxlength="1000">{{ old('objectives') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Minimum 10 characters</p>
                                <x-input-error :messages="$errors->get('objectives')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Section -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-green-600 rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Timeline</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="planned_start_date" :value="__('Start Date')" class="font-semibold text-gray-800 block mb-2" />
                                <x-text-input id="planned_start_date" class="block w-full input-field" type="date" 
                                    name="planned_start_date" :value="old('planned_start_date')" required />
                                <x-input-error :messages="$errors->get('planned_start_date')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="planned_end_date" :value="__('End Date')" class="font-semibold text-gray-800 block mb-2" />
                                <x-text-input id="planned_end_date" class="block w-full input-field" type="date" 
                                    name="planned_end_date" :value="old('planned_end_date')" required />
                                <x-input-error :messages="$errors->get('planned_end_date')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Budget & Beneficiaries Section -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-blue-600 rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Budget & Beneficiaries</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="target_beneficiaries" :value="__('Target Beneficiaries')" class="font-semibold text-gray-800 block mb-2" />
                                <x-text-input id="target_beneficiaries" class="block w-full input-field" type="number" 
                                    name="target_beneficiaries" :value="old('target_beneficiaries')" placeholder="0" 
                                    required min="1" max="1000000" />
                                <p class="text-xs text-gray-500 mt-1">Numbers only (1-1,000,000)</p>
                                <x-input-error :messages="$errors->get('target_beneficiaries')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="allocated_budget" :value="__('Allocated Budget (₱)')" class="font-semibold text-gray-800 block mb-2" />
                                <x-text-input id="allocated_budget" class="block w-full input-field" type="number" 
                                    name="allocated_budget" :value="old('allocated_budget')" placeholder="0.00" 
                                    step="0.01" required min="0" max="999999999.99" />
                                <p class="text-xs text-gray-500 mt-1">Numbers only with up to 2 decimal places</p>
                                <x-input-error :messages="$errors->get('allocated_budget')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Management Section -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-purple-600 rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Management</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="program_lead_id" :value="__('Program Lead')" class="font-semibold text-gray-800 block mb-2" />
                                <select id="program_lead_id" name="program_lead_id" class="block w-full input-field rounded-lg border border-gray-300" required>
                                    <option value="" selected disabled>-- Select Faculty Member --</option>
                                    @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ old('program_lead_id') == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->user->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('program_lead_id')" class="mt-1" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Initial Status')" class="font-semibold text-gray-800 block mb-2" />
                                <select id="status" name="status" class="block w-full input-field rounded-lg border border-gray-300" required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Partners & Communities -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-orange-600 rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Partners & Communities</h2>
                        </div>
                        
                        <!-- Communities Selector -->
                        <div class="mb-6">
                            <x-input-label for="communities" :value="__('Select Communities')" class="font-semibold text-gray-800 block mb-3" />
                            <div class="border border-gray-300 rounded-lg overflow-hidden bg-white">
                                <div class="max-h-56 overflow-y-auto space-y-1 p-2">
                                    @forelse ($communities as $community)
                                    <label class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded cursor-pointer transition">
                                        <input type="checkbox" name="communities[]" value="{{ $community->id }}" 
                                            class="w-5 h-5 rounded mt-0.5 accent-lnu-blue" 
                                            {{ in_array($community->id, old('communities', [])) ? 'checked' : '' }} />
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $community->name }}</p>
                                            <p class="text-xs text-gray-600">{{ $community->municipality }}, {{ $community->province }}</p>
                                        </div>
                                    </label>
                                    @empty
                                    <p class="text-center text-gray-500 py-4">No communities available</p>
                                    @endforelse
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('communities')" class="mt-1" />
                        </div>

                        <!-- Partner Organizations -->
                        <div>
                            <x-input-label for="partners" :value="__('Partner Organizations')" class="font-semibold text-gray-800 block mb-2" />
                            <x-text-input id="partners" class="block w-full input-field" type="text" name="partners" 
                                :value="old('partners')" placeholder="e.g., NGO A, Local Gov't, Private Sector (separate with commas)" />
                            <x-input-error :messages="$errors->get('partners')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="border-t border-gray-200 pt-8">
                        <x-input-label for="notes" :value="__('Additional Notes')" class="font-semibold text-gray-800 block mb-2" />
                        <textarea id="notes" name="notes" rows="3" class="block w-full input-field rounded-lg border border-gray-300" 
                            placeholder="Any additional information or remarks...">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>

                    <!-- Cover Image -->
                    <div class="border-t border-gray-200 pt-8">
                        <x-input-label for="cover_image" :value="__('Cover Image (Optional)')" class="font-semibold text-gray-800 block mb-3" />
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:border-lnu-blue hover:bg-blue-50 transition cursor-pointer" id="coverImageDropZone">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <input type="file" id="cover_image" name="cover_image" accept="image/*" class="hidden" onchange="updateCoverImagePreview()" />
                            <p class="text-gray-700 font-medium mb-1">Click or drag image to upload</p>
                            <p class="text-xs text-gray-600">PNG, JPG, JPEG, GIF up to 5MB</p>
                        </div>
                        <div id="coverImagePreview" class="mt-4"></div>
                        <x-input-error :messages="$errors->get('cover_image')" class="mt-1" />
                    </div>

                    <!-- Media & Attachments Section -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-indigo-600 rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Media & Attachments</h2>
                        </div>
                        
                        <!-- Gallery Images -->
                        <div class="mb-6">
                            <x-input-label for="gallery_images" :value="__('Gallery Images (Optional)')" class="font-semibold text-gray-800 block mb-3" />
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:border-lnu-blue hover:bg-blue-50 transition cursor-pointer" id="imageDropZone">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <input type="file" id="gallery_images" name="gallery_images[]" multiple accept="image/*" 
                                    class="hidden" onchange="updateImagePreview()" />
                                <p class="text-gray-700 font-medium mb-1">Click or drag images here</p>
                                <p class="text-xs text-gray-600">PNG, JPG, JPEG up to 5MB each. Multiple images allowed.</p>
                            </div>
                            <div id="imagePreview" class="grid grid-cols-3 md:grid-cols-4 gap-3 mt-4"></div>
                            <x-input-error :messages="$errors->get('gallery_images')" class="mt-1" />
                        </div>

                        <!-- Attachments -->
                        <div>
                            <x-input-label for="attachments" :value="__('Attachments (Optional)')" class="font-semibold text-gray-800 block mb-3" />
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:border-lnu-blue hover:bg-blue-50 transition cursor-pointer" id="fileDropZone">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <input type="file" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip" 
                                    class="hidden" onchange="updateFilePreview()" />
                                <p class="text-gray-700 font-medium mb-1">Click or drag files here</p>
                                <p class="text-xs text-gray-600">PDF, DOC, XLS, PPT, TXT, ZIP up to 10MB each. Multiple files allowed.</p>
                            </div>
                            <div id="filePreview" class="space-y-2 mt-4"></div>
                            <x-input-error :messages="$errors->get('attachments')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 pt-8 flex gap-4">
                        <button type="submit" class="flex-1 px-6 py-3 bg-lnu-blue text-white font-semibold rounded-lg hover:bg-blue-800 transition shadow-md">
                            Create Program
                        </button>
                        <a href="{{ route('programs.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-900 font-semibold rounded-lg hover:bg-gray-300 transition text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Image preview for gallery
        function updateImagePreview() {
            const input = document.getElementById('gallery_images');
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="w-full h-24 object-cover rounded-lg border-2 border-gray-300">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center transition cursor-pointer" onclick="removeImagePreview(${index})">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        // Cover image preview
        function updateCoverImagePreview() {
            const input = document.getElementById('cover_image');
            const preview = document.getElementById('coverImagePreview');
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative w-full md:w-64 h-40 rounded-lg overflow-hidden shadow-md';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Cover Preview" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4V5h12v10z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // File preview for attachments
        function updateFilePreview() {
            const input = document.getElementById('attachments');
            const preview = document.getElementById('filePreview');
            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200';
                    div.innerHTML = `
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">${file.name}</span>
                            <span class="text-xs text-gray-500">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                        </div>
                        <button type="button" onclick="removeFilePreview(${index})" class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    `;
                    preview.appendChild(div);
                });
            }
        }

        // Drag and drop for cover image
        const coverImageDropZone = document.getElementById('coverImageDropZone');
        if (coverImageDropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                coverImageDropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                coverImageDropZone.addEventListener(eventName, () => {
                    coverImageDropZone.classList.add('border-lnu-blue', 'bg-blue-50');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                coverImageDropZone.addEventListener(eventName, () => {
                    coverImageDropZone.classList.remove('border-lnu-blue', 'bg-blue-50');
                });
            });

            coverImageDropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                document.getElementById('cover_image').files = files;
                updateCoverImagePreview();
            });

            coverImageDropZone.addEventListener('click', () => {
                document.getElementById('cover_image').click();
            });
        }

        // Drag and drop for images
        const imageDropZone = document.getElementById('imageDropZone');
        if (imageDropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                imageDropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                imageDropZone.addEventListener(eventName, () => {
                    imageDropZone.classList.add('border-lnu-blue', 'bg-blue-50');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                imageDropZone.addEventListener(eventName, () => {
                    imageDropZone.classList.remove('border-lnu-blue', 'bg-blue-50');
                });
            });

            imageDropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                document.getElementById('gallery_images').files = files;
                updateImagePreview();
            });

            imageDropZone.addEventListener('click', () => {
                document.getElementById('gallery_images').click();
            });
        }

        // Drag and drop for files
        const fileDropZone = document.getElementById('fileDropZone');
        if (fileDropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                fileDropZone.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                fileDropZone.addEventListener(eventName, () => {
                    fileDropZone.classList.add('border-lnu-blue', 'bg-blue-50');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                fileDropZone.addEventListener(eventName, () => {
                    fileDropZone.classList.remove('border-lnu-blue', 'bg-blue-50');
                });
            });

            fileDropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                document.getElementById('attachments').files = files;
                updateFilePreview();
            });

            fileDropZone.addEventListener('click', () => {
                document.getElementById('attachments').click();
            });
        }

        function removeImagePreview(index) {
            const input = document.getElementById('gallery_images');
            const dt = new DataTransfer();
            const files = input.files;
            
            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }
            
            input.files = dt.files;
            updateImagePreview();
        }

        function removeFilePreview(index) {
            const input = document.getElementById('attachments');
            const dt = new DataTransfer();
            const files = input.files;
            
            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }
            
            input.files = dt.files;
            updateFilePreview();
        }

        function removeImage(index) {
            // This function can be enhanced to mark images for deletion
            document.getElementById('deleteImage_' + index).closest('div').remove();
        }

        function removeAttachment(index) {
            // This function can be enhanced to mark attachments for deletion
            event.target.closest('div').remove();
        }
    </script>
</x-admin-layout>
