<x-admin-layout header="Beneficiary Details">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-lnu-blue">{{ $beneficiary->first_name }} {{ $beneficiary->last_name }}</h1>
                <div class="flex items-center gap-4 mt-2">
                    <span class="inline-block capitalize px-3 py-1 rounded-full text-sm font-medium
                        @if($beneficiary->status === 'active') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($beneficiary->status) }}
                    </span>
                    <span class="text-gray-600 text-sm">Created {{ $beneficiary->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('beneficiaries.edit', $beneficiary) }}" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('beneficiaries.destroy', $beneficiary) }}" class="inline"
                      onsubmit="return confirm('Are you sure? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Left Column (2/3 width) -->
            <div class="col-span-2 space-y-6">
                <!-- Personal Information -->
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Personal Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Full Name</p>
                            <p class="text-gray-900 font-medium">
                                {{ $beneficiary->first_name }} 
                                @if($beneficiary->middle_name) {{ $beneficiary->middle_name }} @endif 
                                {{ $beneficiary->last_name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Age / Date of Birth</p>
                            <p class="text-gray-900 font-medium">
                                @if($beneficiary->age) {{ $beneficiary->age }} years @endif
                                @if($beneficiary->date_of_birth) ({{ $beneficiary->date_of_birth->format('M d, Y') }}) @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Gender</p>
                            <p class="text-gray-900 font-medium">{{ ucfirst($beneficiary->gender ?? 'Not specified') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Marital Status</p>
                            <p class="text-gray-900 font-medium">{{ $beneficiary->marital_status ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </x-card>

                <!-- Contact Information -->
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Contact Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-gray-900 font-medium">
                                @if($beneficiary->email)
                                    <a href="mailto:{{ $beneficiary->email }}" class="text-lnu-blue hover:underline">
                                        {{ $beneficiary->email }}
                                    </a>
                                @else
                                    Not provided
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="text-gray-900 font-medium">
                                @if($beneficiary->phone)
                                    <a href="tel:{{ $beneficiary->phone }}" class="text-lnu-blue hover:underline">
                                        {{ $beneficiary->phone }}
                                    </a>
                                @else
                                    Not provided
                                @endif
                            </p>
                        </div>
                    </div>
                </x-card>

                <!-- Address Information -->
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Address Information</h2>
                    <div class="space-y-3">
                        @if($beneficiary->address)
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-lnu-blue mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">Street</p>
                                <p class="text-gray-900 font-medium">{{ $beneficiary->address }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="grid grid-cols-3 gap-4">
                            @if($beneficiary->barangay)
                            <div>
                                <p class="text-sm text-gray-600">Barangay</p>
                                <p class="text-gray-900 font-medium">{{ $beneficiary->barangay }}</p>
                            </div>
                            @endif
                            @if($beneficiary->municipality)
                            <div>
                                <p class="text-sm text-gray-600">City/Municipality</p>
                                <p class="text-gray-900 font-medium">{{ $beneficiary->municipality }}</p>
                            </div>
                            @endif
                            @if($beneficiary->province)
                            <div>
                                <p class="text-sm text-gray-600">Province</p>
                                <p class="text-gray-900 font-medium">{{ $beneficiary->province }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </x-card>

                <!-- Program & Community -->
                @if($beneficiary->community || $beneficiary->extensionPrograms->count() > 0)
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Program & Community</h2>
                    <div class="space-y-4">
                        @if($beneficiary->community)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Community</p>
                            <p class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                {{ $beneficiary->community->name }}
                            </p>
                        </div>
                        @endif

                        @if($beneficiary->beneficiary_category)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Beneficiary Category</p>
                            <p class="inline-block px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                {{ $beneficiary->beneficiary_category }}
                            </p>
                        </div>
                        @endif

                        @if($beneficiary->extensionPrograms->count() > 0)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Enrolled Programs</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($beneficiary->extensionPrograms as $program)
                                <a href="{{ route('programs.show', $program) }}" class="inline-block px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full hover:bg-indigo-200 transition">
                                    {{ $program->title }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </x-card>
                @endif

                <!-- Socioeconomic Information -->
                <x-card>
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Socioeconomic Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Monthly Income</p>
                            <p class="text-gray-900 font-medium">
                                @if($beneficiary->monthly_income)
                                    ₱{{ number_format($beneficiary->monthly_income, 2) }}
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Occupation</p>
                            <p class="text-gray-900 font-medium">{{ $beneficiary->occupation ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Educational Attainment</p>
                            <p class="text-gray-900 font-medium">{{ $beneficiary->educational_attainment ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Number of Dependents</p>
                            <p class="text-gray-900 font-medium">
                                {{ $beneficiary->number_of_dependents ?? '0' }}
                            </p>
                        </div>
                    </div>
                </x-card>

                <!-- Notes -->
                @if($beneficiary->notes)
                <x-card>
                    <h2 class="text-lg font-semibold text-lnu-blue mb-2">Notes</h2>
                    <p class="text-gray-700">{{ $beneficiary->notes }}</p>
                </x-card>
                @endif
            </div>

            <!-- Right Column (1/3 width) -->
            <div class="col-span-1 space-y-6">
                <!-- Summary Card -->
                <x-card>
                    <h2 class="text-lg font-semibold text-lnu-blue mb-4">Summary</h2>
                    <div class="space-y-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 uppercase tracking-wide">Status</p>
                            <p class="text-lg font-bold text-gray-900 capitalize mt-1">
                                {{ $beneficiary->status }}
                            </p>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 uppercase tracking-wide">Programs Enrolled</p>
                            <p class="text-lg font-bold text-gray-900 mt-1">
                                {{ $beneficiary->extensionPrograms->count() }}
                            </p>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 uppercase tracking-wide">Created</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                {{ $beneficiary->created_at->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 uppercase tracking-wide">Last Updated</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                {{ $beneficiary->updated_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </x-card>

                <!-- Action Buttons -->
                <div class="space-y-2">
                    <a href="{{ route('beneficiaries.edit', $beneficiary) }}" class="w-full btn-primary flex items-center justify-center gap-2 text-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit Beneficiary
                    </a>
                    <a href="{{ route('beneficiaries.index') }}" class="w-full px-4 py-2 bg-gray-200 text-gray-900 font-medium rounded-lg hover:bg-gray-300 transition text-center inline-block">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
