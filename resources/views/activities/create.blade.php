<x-admin-layout header="Create Activity">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Create New Activity</h1>

                <form action="{{ route('activities.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Program Selection -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 000-2H7zM4 7a1 1 0 011-1h10a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1V7zM2 11a2 2 0 012-2h12a2 2 0 012 2v3a2 2 0 01-2 2H4a2 2 0 01-2-2v-3z" />
                            </svg>
                            Program Selection
                        </h3>
                        <div>
                            <x-input-label for="extension_program_id" :value="__('Select Program')" />
                            <select id="extension_program_id" name="extension_program_id" required
                                    class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Choose a Program --</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" @if(old('extension_program_id') == $program->id) selected @endif>
                                        {{ $program->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('extension_program_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Activity Details -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            Activity Details
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="title" :value="__('Activity Title')" />
                                <x-text-input id="title" class="block w-full mt-1" type="text" name="title" 
                                             placeholder="e.g., Week 1 Tutoring Sessions" value="{{ old('title') }}" required />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="4"
                                         placeholder="Provide a detailed description of the activity..."
                                         class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                         required>{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="actual_start_date" :value="__('Start Date')" />
                                    <x-text-input id="actual_start_date" class="block w-full mt-1" type="date" 
                                                name="actual_start_date" value="{{ old('actual_start_date') }}" required />
                                    <x-input-error :messages="$errors->get('actual_start_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="actual_end_date" :value="__('End Date')" />
                                    <x-text-input id="actual_end_date" class="block w-full mt-1" type="date" 
                                                name="actual_end_date" value="{{ old('actual_end_date') }}" required />
                                    <x-input-error :messages="$errors->get('actual_end_date')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="venue" :value="__('Venue')" />
                                <x-text-input id="venue" class="block w-full mt-1" type="text" name="venue" 
                                             placeholder="e.g., San Juan Community Center" value="{{ old('venue') }}" required />
                                <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" required
                                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="pending" @if(old('status') == 'pending') selected @endif>Pending</option>
                                    <option value="ongoing" @if(old('status') == 'ongoing') selected @endif>Ongoing</option>
                                    <option value="completed" @if(old('status') == 'completed') selected @endif>Completed</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Involved Faculties -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.5m-12 0h12m-12 0V3.75a2.25 2.25 0 012.25-2.25h2.5V1.5m0 0A2.25 2.25 0 0110.5 1.5" stroke="currentColor" stroke-width="1.5" fill="none" />
                            </svg>
                            Involved Faculties
                        </h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($faculties as $faculty)
                                <label class="flex items-center p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                    <input type="checkbox" name="faculties[]" value="{{ $faculty->id }}" 
                                          @if(is_array(old('faculties')) && in_array($faculty->id, old('faculties'))) checked @endif
                                          class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                    <span class="ml-3 text-gray-900">
                                        <strong>{{ $faculty->user->name }}</strong>
                                        <span class="text-sm text-gray-600 block">{{ $faculty->department }}</span>
                                    </span>
                                </label>
                            @empty
                                <p class="text-gray-600">No faculties available</p>
                            @endforelse
                        </div>
                        <x-input-error :messages="$errors->get('faculties')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H6a6 6 0 016 6v3.5a4.5 4.5 0 11-9 0V5a1 1 0 00-1 1v5a7 7 0 1014 0v-5a1 1 0 10-2 0v5a5 5 0 11-10 0V5z" clip-rule="evenodd" />
                            </svg>
                            Additional Notes
                        </h3>
                        <textarea id="notes" name="notes" rows="3"
                                 placeholder="Add any additional notes for this activity..."
                                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4 pt-6 border-t">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Activity
                        </button>
                        <a href="{{ route('activities.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
