<x-admin-layout header="Community Details">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-lnu-blue">{{ $community->name }}</h1>
                <div class="flex items-center gap-4 mt-2">
                    <span class="inline-block capitalize px-3 py-1 rounded-full text-sm font-medium
                        @if($community->status === 'active') bg-green-100 text-green-800
                        @elseif($community->status === 'inactive') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($community->status) }}
                    </span>
                    <span class="text-gray-600 text-sm">Updated {{ $community->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            @if (auth()->user()->role === 'director')
            <div class="flex gap-2">
                <a href="{{ route('communities.edit', $community) }}" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('communities.destroy', $community) }}" class="inline"
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
            @endif
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Left Column (2/3 width) -->
            <div class="col-span-2 space-y-6">
                <!-- Overview -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Overview</h2>
                    @if ($community->description)
                    <p class="text-gray-700 mb-4">{{ $community->description }}</p>
                    @else
                    <p class="text-gray-500 italic">No description available</p>
                    @endif
                </div>

                <!-- Location Information -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Location</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Municipality</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $community->municipality }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Province</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $community->province }}</p>
                        </div>
                        @if ($community->address)
                        <div>
                            <p class="text-sm text-gray-600">Address</p>
                            <p class="text-gray-900">{{ $community->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Extension Programs -->
                @if ($community->extensionPrograms->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Extension Programs ({{ $community->extensionPrograms->count() }})</h2>
                    <div class="space-y-3">
                        @foreach ($community->extensionPrograms as $program)
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-200 last:border-b-0">
                            <span class="inline-block capitalize px-3 py-1 rounded-full text-sm font-medium
                                @if($program->status === 'draft') bg-yellow-100 text-yellow-800
                                @elseif($program->status === 'ongoing') bg-green-100 text-green-800
                                @elseif($program->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($program->status) }}
                            </span>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $program->title }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($program->description, 100) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Needs Assessments -->
                @if ($community->needsAssessments->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-lnu-blue mb-4">Needs Assessments ({{ $community->needsAssessments->count() }})</h2>
                    <div class="space-y-2">
                        @foreach ($community->needsAssessments as $assessment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <p class="font-medium text-gray-900">{{ $assessment->assessment_name ?? 'Assessment' }}</p>
                            <p class="text-xs text-gray-600">{{ $assessment->created_at->format('M d, Y') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Notes -->
                @if ($community->notes)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-lnu-blue mb-2">Notes</h2>
                    <p class="text-gray-700">{{ $community->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Right Column (1/3 width) -->
            <div class="space-y-6">
                <!-- Contact Information Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-lnu-blue to-blue-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">Contact Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Contact Person</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $community->contact_person }}</p>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-600">Contact Number</p>
                            <a href="tel:{{ $community->contact_number }}" class="text-lg font-semibold text-lnu-blue hover:text-blue-800 break-all">
                                {{ $community->contact_number }}
                            </a>
                        </div>

                        @if ($community->email)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-600">Email Address</p>
                            <a href="mailto:{{ $community->email }}" class="text-lg font-semibold text-lnu-blue hover:text-blue-800 break-all">
                                {{ $community->email }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-lnu-gold to-yellow-500 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">Statistics</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="text-center pb-4 border-b border-gray-200">
                            <p class="text-sm text-gray-600">Active Programs</p>
                            <p class="text-3xl font-bold text-lnu-blue">{{ $community->extensionPrograms->where('status', 'ongoing')->count() }}</p>
                        </div>

                        <div class="text-center pb-4 border-b border-gray-200">
                            <p class="text-sm text-gray-600">Total Programs</p>
                            <p class="text-3xl font-bold text-lnu-gold">{{ $community->extensionPrograms->count() }}</p>
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-600">Beneficiaries</p>
                            <p class="text-3xl font-bold text-green-600">{{ $community->beneficiaries->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <a href="{{ route('communities.index') }}" class="block w-full px-4 py-3 bg-gray-200 text-gray-900 rounded-lg text-center font-medium hover:bg-gray-300 transition">
                    Back to Communities
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
