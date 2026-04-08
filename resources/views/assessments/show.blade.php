<x-admin-layout header="Assessment Details">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-lnu-blue">{{ $assessment->community->name }}</h1>
                <p class="text-gray-600 mt-2">F-CES-001 Form | {{ $assessment->quarter }} / {{ $assessment->year }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('assessments.edit', $assessment) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-lnu-blue hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Edit
                </a>
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition">
                    Back
                </a>
            </div>
        </div>

        <div class="space-y-8">
            <!-- SECTION I: Identifying Information -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                    <span class="bg-blue-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">I</span>
                    Identifying Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Respondent Name</p>
                        <p class="text-gray-900 mt-1">
                            {{ $assessment->respondent_first_name ?? '-' }}
                            @if($assessment->respondent_middle_name) {{ $assessment->respondent_middle_name }} @endif
                            {{ $assessment->respondent_last_name ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Age</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->respondent_age ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Sex</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->respondent_sex ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Civil Status</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->respondent_civil_status ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Religion</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->respondent_religion ?? '-' }}</p>
                    </div>
                    @if($assessment->respondent_educational_attainment)
                    <div>
                        <p class="text-sm font-medium text-gray-600">Educational Attainment</p>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach((array) $assessment->respondent_educational_attainment as $edu)
                                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">{{ $edu }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SECTION II: Family Composition -->
            @if($assessment->family_adults || $assessment->family_children)
            <div class="border-b pb-6">
                <h3 class="text-lg font-bold text-green-900 mb-4 flex items-center gap-2">
                    <span class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">II</span>
                    Family Composition
                </h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Number of Adults in the Household</p>
                            <p class="text-sm text-gray-700">{{ $assessment->family_adults ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Number of Children in the Household</p>
                            <p class="text-sm text-gray-700">{{ $assessment->family_children ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- SECTION III: Economic Aspect -->
            @if($assessment->livelihood_options || $assessment->interested_in_livelihood_training)
            <div class="border-b pb-6">
                <h3 class="text-lg font-bold text-orange-900 mb-4 flex items-center gap-2">
                    <span class="bg-orange-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">III</span>
                    Economic Aspect
                </h3>
                <div class="space-y-4">
                    @if($assessment->livelihood_options)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Livelihood Options</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->livelihood_options as $option)
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">{{ $option }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-600">Interested in Livelihood Training?</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->interested_in_livelihood_training ?? '-' }}</p>
                    </div>
                    @if($assessment->desired_training)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Desired Training</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->desired_training as $training)
                                <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">{{ $training }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- SECTION IV: Educational Aspect -->
            @if($assessment->barangay_educational_facilities || $assessment->household_member_currently_studying)
            <div class="border-b pb-6">
                <h3 class="text-lg font-bold text-purple-900 mb-4 flex items-center gap-2">
                    <span class="bg-purple-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">IV</span>
                    Educational Aspect
                </h3>
                <div class="space-y-4">
                    @if($assessment->barangay_educational_facilities)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Barangay Educational Facilities</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->barangay_educational_facilities as $facility)
                                <span class="bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full">{{ $facility }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-600">Household Member Currently Studying?</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->household_member_currently_studying ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Interested in Continuing Studies?</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->interested_in_continuing_studies ?? '-' }}</p>
                    </div>
                    @if($assessment->areas_of_educational_interest)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Educational Interest Areas</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->areas_of_educational_interest as $area)
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">{{ $area }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-600">Preferred Training Time</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->preferred_training_time ?? '-' }}</p>
                    </div>
                    @if($assessment->preferred_training_days)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Preferred Training Days</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->preferred_training_days as $day)
                                <span class="bg-cyan-100 text-cyan-800 text-xs px-3 py-1 rounded-full">{{ $day }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- SECTION V: Health, Sanitation, Environmental -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-bold text-red-900 mb-4 flex items-center gap-2">
                    <span class="bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">V</span>
                    Health, Sanitation & Environment
                </h3>
                <div class="space-y-4">
                    @if($assessment->common_illnesses)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Common Illnesses</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->common_illnesses as $illness)
                                <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full">{{ $illness }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->action_when_sick)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Action When Sick</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->action_when_sick as $action)
                                <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full">{{ $action }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->barangay_medical_supplies_available)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Medical Supplies Available</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->barangay_medical_supplies_available as $supply)
                                <span class="bg-pink-100 text-pink-800 text-xs px-3 py-1 rounded-full">{{ $supply }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->programs_benefited_from)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Programs Benefited From</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->programs_benefited_from as $program)
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">{{ $program }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="grid grid-cols-2">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Health Programs Available?</p>
                            <p class="text-gray-900 mt-1">{{ $assessment->has_barangay_health_programs ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Benefits from Programs?</p>
                            <p class="text-gray-900 mt-1">{{ $assessment->benefits_from_barangay_programs ?? '-' }}</p>
                        </div>
                    </div>
                    @if($assessment->water_source)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Water Source</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->water_source as $source)
                                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">{{ $source }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-600">Water Source Distance</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->water_source_distance ?? '-' }}</p>
                    </div>
                    @if($assessment->garbage_disposal_method)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Garbage Disposal Method</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->garbage_disposal_method as $method)
                                <span class="bg-amber-100 text-amber-800 text-xs px-3 py-1 rounded-full">{{ $method }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="grid grid-cols-2">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Has Own Toilet?</p>
                            <p class="text-gray-900 mt-1">{{ $assessment->has_own_toilet ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Keeps Animals?</p>
                            <p class="text-gray-900 mt-1">{{ $assessment->keeps_animals ?? '-' }}</p>
                        </div>
                    </div>
                    @if($assessment->toilet_type)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Toilet Type</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->toilet_type as $type)
                                <span class="bg-slate-100 text-slate-800 text-xs px-3 py-1 rounded-full">{{ $type }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->animals_kept)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Animals Kept</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->animals_kept as $animal)
                                <span class="bg-orange-100 text-orange-800 text-xs px-3 py-1 rounded-full">{{ $animal }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SECTION VI: Housing and Basic Amenities -->
            @if($assessment->house_type || $assessment->tenure_status || $assessment->has_electricity)
            <div class="border-b pb-6">
                <h3 class="text-lg font-bold text-indigo-900 mb-4 flex items-center gap-2">
                    <span class="bg-indigo-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">VI</span>
                    Housing & Basic Amenities
                </h3>
                <div class="space-y-4">
                    @if($assessment->house_type)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">House Type</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->house_type as $type)
                                <span class="bg-fuchsia-100 text-fuchsia-800 text-xs px-3 py-1 rounded-full">{{ $type }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->tenure_status)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Tenure Status</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->tenure_status as $status)
                                <span class="bg-violet-100 text-violet-800 text-xs px-3 py-1 rounded-full">{{ $status }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-600">Has Electricity?</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->has_electricity ?? '-' }}</p>
                    </div>
                    @if($assessment->light_source_without_power)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Light Source Without Power</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->light_source_without_power as $source)
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">{{ $source }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->appliances_owned)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Appliances Owned</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->appliances_owned as $appliance)
                                <span class="bg-teal-100 text-teal-800 text-xs px-3 py-1 rounded-full">{{ $appliance }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- SECTION VII: Recreational Facilities -->
            @if($assessment->barangay_recreational_facilities || $assessment->member_of_organization)
            <div class="border-b pb-6">
                <h3 class="text-lg font-bold text-cyan-900 mb-4 flex items-center gap-2">
                    <span class="bg-cyan-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">VII</span>
                    Recreational Facilities & Organization
                </h3>
                <div class="space-y-4">
                    @if($assessment->barangay_recreational_facilities)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Recreational Facilities</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->barangay_recreational_facilities as $facility)
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">{{ $facility }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->use_of_free_time)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Use of Free Time</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->use_of_free_time as $use)
                                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">{{ $use }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-600">Member of Organization?</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->member_of_organization ?? '-' }}</p>
                    </div>
                    @if($assessment->organization_types)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Organization Types</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->organization_types as $org)
                                <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">{{ $org }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-600">Meeting Frequency</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->organization_meeting_frequency ?? '-' }}</p>
                    </div>
                    @if($assessment->position_in_organization)
                    <div>
                        <p class="text-sm font-medium text-gray-600">Position</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->position_in_organization }}</p>
                    </div>
                    @endif
                    @if($assessment->organization_usual_activities)
                    <div>
                        <p class="text-sm font-medium text-gray-600">Organization Activities</p>
                        <p class="text-gray-900 mt-1 whitespace-pre-wrap">{{ $assessment->organization_usual_activities }}</p>
                    </div>
                    @endif
                    @if($assessment->household_members_in_organization)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Members Involved</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->household_members_in_organization as $member)
                                <span class="bg-pink-100 text-pink-800 text-xs px-3 py-1 rounded-full">{{ $member }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- SECTION VIII: Other Needs & Problems -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-bold text-amber-900 mb-4 flex items-center gap-2">
                    <span class="bg-amber-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">VIII</span>
                    Identified Needs & Problems
                </h3>
                <div class="space-y-4">
                    @if($assessment->family_problems)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Family Problems</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->family_problems as $problem)
                                <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full">{{ $problem }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->health_problems)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Health Problems</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->health_problems as $problem)
                                <span class="bg-orange-100 text-orange-800 text-xs px-3 py-1 rounded-full">{{ $problem }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->educational_problems)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Educational Problems</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->educational_problems as $problem)
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">{{ $problem }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->employment_problems)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Employment Problems</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->employment_problems as $problem)
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">{{ $problem }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->infrastructure_problems)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Infrastructure Problems</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->infrastructure_problems as $problem)
                                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">{{ $problem }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->economic_problems)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Economic Problems</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->economic_problems as $problem)
                                <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">{{ $problem }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($assessment->security_problems)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Security Problems</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $assessment->security_problems as $problem)
                                <span class="bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full">{{ $problem }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SECTION IX: Summary -->
            <div class="pb-6">
                <h3 class="text-lg font-bold text-teal-900 mb-4 flex items-center gap-2">
                    <span class="bg-teal-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">IX</span>
                    Summary & Feedback
                </h3>
                <div class="space-y-4">
                    @if($assessment->barangay_service_ratings && is_array($assessment->barangay_service_ratings) && count($assessment->barangay_service_ratings) > 0)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Barangay Service Ratings</p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-lnu-blue">
                                        <th class="text-left py-2 px-3 font-semibold">Service</th>
                                        <th class="text-center py-2 px-1">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['Law Enforcement', 'Fire Protection', 'BNS Service', 'Street Lighting', 'Water system', 'Sanitation', 'Health Service', 'Education Service', 'Infrastructure Service'] as $service)
                                        @php $rating = $assessment->barangay_service_ratings[$service] ?? '-'; @endphp
                                        <tr class="border-b border-gray-200">
                                            <td class="py-2 px-3">{{ $service }}</td>
                                            <td class="text-center py-2">{{ $rating }}/5</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    @if($assessment->general_feedback)
                    <div>
                        <p class="text-sm font-medium text-gray-600">General Feedback</p>
                        <p class="text-gray-900 mt-1 whitespace-pre-wrap bg-gray-50 p-3 rounded">{{ $assessment->general_feedback }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-600">Available for Training?</p>
                        <p class="text-gray-900 mt-1">{{ $assessment->available_for_training ?? '-' }}</p>
                    </div>
                    @if($assessment->reason_not_available)
                    <div>
                        <p class="text-sm font-medium text-gray-600">Reason Not Available</p>
                        <p class="text-gray-900 mt-1 whitespace-pre-wrap">{{ $assessment->reason_not_available }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- File Section -->
            @if($assessment->file_path)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-600 mb-2">Uploaded Document</p>
                <a href="{{ asset('storage/' . $assessment->file_path) }}" target="_blank" 
                   class="inline-flex items-center gap-2 text-lnu-blue hover:underline font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-6-4m6 4l6-4" />
                    </svg>
                    Download Assessment File
                </a>
            </div>
            @endif

            <!-- Metadata -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                <p class="text-gray-600"><strong>Created:</strong> {{ $assessment->created_at->format('M d, Y H:i') }}</p>
                <p class="text-gray-600"><strong>Last Updated:</strong> {{ $assessment->updated_at->format('M d, Y H:i') }}</p>
                <p class="text-gray-600"><strong>Created By:</strong> {{ optional(\App\Models\User::find($assessment->uploaded_by))->name ?? 'System' }}</p>
            </div>
        </div>

        <!-- Delete Button -->
        <div class="mt-8 pt-6 border-t">
            <form action="{{ route('assessments.destroy', $assessment) }}" method="POST" class="inline" 
                  onsubmit="return confirm('Are you sure you want to delete this assessment?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H4v2h16V7h-3z" />
                    </svg>
                    Delete Assessment
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
