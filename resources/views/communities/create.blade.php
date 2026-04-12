<x-admin-layout header="Create School or Community">
    <div class="max-w-full">
        <form method="POST" action="{{ route('communities.store') }}" class="space-y-6">
            @csrf

            <!-- Main Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-lnu-blue px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">Add New School or Community</h1>
                    <p class="text-blue-100 mt-1">Register a new school or community for extension programs</p>
                </div>

                <!-- Form Content -->
                <div class="p-8 space-y-8">
                    <!-- Basic Information Section -->
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-lnu-blue rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Basic Information</h2>
                        </div>
                        
                        <div class="space-y-5">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('School or Community Name')" class="font-semibold text-gray-800 block mb-2" />
                                <x-text-input id="name" class="block w-full input-field" type="text" name="name" 
                                    :value="old('name')" placeholder="e.g., Barangay San Juan" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description')" class="font-semibold text-gray-800 block mb-2" />
                                <textarea id="description" name="description" rows="4" class="block w-full input-field rounded-lg border border-gray-300" 
                                    placeholder="Describe the community, its needs, demographics, etc.">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Location Section -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-lnu-gold rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Location</h2>
                        </div>
                        
                        <div class="space-y-5">
                            <!-- Address -->
                            <div>
                                <x-input-label for="address" :value="__('Address')" class="font-semibold text-gray-800 block mb-2" />
                                <x-text-input id="address" class="block w-full input-field" type="text" name="address" 
                                    :value="old('address')" placeholder="Street address or landmarks" />
                                <x-input-error :messages="$errors->get('address')" class="mt-1" />
                            </div>

                            <!-- Municipality and Province -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="municipality" :value="__('Municipality')" class="font-semibold text-gray-800 block mb-2" />
                                    <x-text-input id="municipality" class="block w-full input-field" type="text" name="municipality" 
                                        :value="old('municipality')" placeholder="e.g., Sta. Cruz" required />
                                    <x-input-error :messages="$errors->get('municipality')" class="mt-1" />
                                </div>
                                <div>
                                    <x-input-label for="province" :value="__('Province')" class="font-semibold text-gray-800 block mb-2" />
                                    <x-text-input id="province" class="block w-full input-field" type="text" name="province" 
                                        :value="old('province')" placeholder="e.g., Laguna" required />
                                    <x-input-error :messages="$errors->get('province')" class="mt-1" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-green-600 rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Contact Information</h2>
                        </div>
                        
                        <div class="space-y-5">
                            <!-- Contact Person -->
                            <div>
                                <x-input-label for="contact_person" :value="__('Contact Person')" class="font-semibold text-gray-800 block mb-2" />
                                <x-text-input id="contact_person" class="block w-full input-field" type="text" name="contact_person" 
                                    :value="old('contact_person')" placeholder="Name of the main contact" required />
                                <x-input-error :messages="$errors->get('contact_person')" class="mt-1" />
                            </div>

                            <!-- Contact Number and Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="contact_number" :value="__('Contact Number')" class="font-semibold text-gray-800 block mb-2" />
                                    <x-text-input id="contact_number" class="block w-full input-field" type="text" name="contact_number" 
                                        :value="old('contact_number')" placeholder="e.g., 09xxxxxxxxx" required />
                                    <x-input-error :messages="$errors->get('contact_number')" class="mt-1" />
                                </div>
                                <div>
                                    <x-input-label for="email" :value="__('Email Address')" class="font-semibold text-gray-800 block mb-2" />
                                    <x-text-input id="email" class="block w-full input-field" type="email" name="email" 
                                        :value="old('email')" placeholder="community@example.com" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-1 h-8 bg-blue-600 rounded"></div>
                            <h2 class="text-xl font-bold text-gray-900">Status</h2>
                        </div>
                        
                        <div>
                            <x-input-label for="status" :value="__('School or Community Status')" class="font-semibold text-gray-800 block mb-2" />
                            <select id="status" name="status" class="block w-full input-field rounded-lg border border-gray-300" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="border-t border-gray-200 pt-8">
                        <x-input-label for="notes" :value="__('Additional Notes')" class="font-semibold text-gray-800 block mb-2" />
                        <textarea id="notes" name="notes" rows="3" class="block w-full input-field rounded-lg border border-gray-300" 
                            placeholder="Any additional notes or special considerations...">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 pt-8 flex gap-4">
                        <button type="submit" class="flex-1 px-6 py-3 bg-lnu-blue text-white font-semibold rounded-lg hover:bg-blue-800 transition shadow-md">
                            Create Community
                        </button>
                        <a href="{{ route('communities.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-900 font-semibold rounded-lg hover:bg-gray-300 transition text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
