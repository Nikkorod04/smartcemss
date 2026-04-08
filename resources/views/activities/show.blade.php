<x-admin-layout header="Activity Details">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">{{ $activity->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $activity->extensionProgram->title }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('activities.edit', $activity) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition">
                        Back
                    </a>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-8">
                <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium
                    @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($activity->status === 'ongoing') bg-blue-100 text-blue-800
                    @else bg-green-100 text-green-800
                    @endif">
                    {{ ucfirst($activity->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Activity Overview -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 9V7a1 1 0 011-1h8a1 1 0 011 1v2M5 9c0 1.657-.895 3-2 3s-2-1.343-2-3m14 0c0 1.657.895 3 2 3s2-1.343 2-3M5 9c0-1.657.895-3 2-3s2 1.343 2 3m4-3c1.105 0 2 .895 2 2s-.895 2-2 2-2-.895-2-2 .895-2 2-2zm7 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Overview
                        </h2>
                        <div class="space-y-3 text-gray-700">
                            <p><strong>Description:</strong> {{ $activity->description }}</p>
                            @if($activity->notes)
                                <p><strong>Notes:</strong> {{ $activity->notes }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            Timeline
                        </h2>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-semibold">Start Date</p>
                                    <p class="text-gray-600">{{ $activity->actual_start_date?->format('l, F d, Y') ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-semibold">End Date</p>
                                    <p class="text-gray-600">{{ $activity->actual_end_date?->format('l, F d, Y') ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-semibold">Duration</p>
                                    <p class="text-gray-600">
                                        @php
                                            $duration = $activity->actual_start_date && $activity->actual_end_date 
                                                ? $activity->actual_end_date->diffInDays($activity->actual_start_date) + 1
                                                : 0;
                                        @endphp
                                        {{ $duration }} day(s)
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-purple-100">
                                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-semibold">Venue</p>
                                    <p class="text-gray-600">{{ $activity->venue }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Involved Faculties -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 12a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            Involved Faculties ({{ $activity->faculties->count() }})
                        </h2>
                        @if($activity->faculties->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($activity->faculties as $faculty)
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-bold">{{ substr($faculty->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-gray-900 font-semibold">{{ $faculty->user->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $faculty->department }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 py-4">No faculties assigned yet</p>
                        @endif
                    </div>

                    <!-- Attendance Tracking -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5H7a1 1 0 100 2h3a1 1 0 001-1V7z" />
                            </svg>
                            Attendance Tracking
                        </h2>

                        <!-- Attendance Statistics -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg">
                                <p class="text-blue-600 text-sm font-semibold">Total Marked</p>
                                <p class="text-3xl font-bold text-blue-900">{{ $attendanceStats['total_marked'] }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg">
                                <p class="text-green-600 text-sm font-semibold">Present</p>
                                <p class="text-3xl font-bold text-green-900">{{ $attendanceStats['total_present'] }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-lg">
                                <p class="text-red-600 text-sm font-semibold">Absent</p>
                                <p class="text-3xl font-bold text-red-900">{{ $attendanceStats['total_absent'] }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg">
                                <p class="text-yellow-600 text-sm font-semibold">Excused</p>
                                <p class="text-3xl font-bold text-yellow-900">{{ $attendanceStats['total_excused'] }}</p>
                            </div>
                        </div>

                        <!-- Attendance Percentage -->
                        @if($attendanceStats['total_marked'] > 0)
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-gray-900 font-semibold">Attendance Rate</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $attendanceStats['attendance_percentage'] }}%</p>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $attendanceStats['attendance_percentage'] }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Record Attendance Form -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Record Attendance</h3>
                            <form action="{{ route('activities.recordAttendance', $activity) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-900 mb-2">Beneficiary</label>
                                        <select name="beneficiary_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select Beneficiary</option>
                                            @foreach($beneficiaries as $beneficiary)
                                                <option value="{{ $beneficiary->id }}">
                                                    {{ $beneficiary->first_name }} {{ $beneficiary->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-900 mb-2">Date</label>
                                        <input type="date" name="attendance_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="excused">Excused</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 mb-2">Remarks (Optional)</label>
                                    <input type="text" name="remarks" placeholder="Add remarks..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                    Record Attendance
                                </button>
                            </form>
                        </div>

                        <!-- Attendance List -->
                        @if($activity->attendances->count() > 0)
                            <div class="mt-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Attendance Records</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Beneficiary</th>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y">
                                            @foreach($activity->attendances as $attendance)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-sm text-gray-900">
                                                        {{ $attendance->beneficiary->first_name }} {{ $attendance->beneficiary->last_name }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        {{ $attendance->attendance_date->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <span class="inline-flex px-2 py-1 rounded text-xs font-semibold
                                                            @if($attendance->status === 'present') bg-green-100 text-green-800
                                                            @elseif($attendance->status === 'absent') bg-red-100 text-red-800
                                                            @else bg-yellow-100 text-yellow-800
                                                            @endif">
                                                            {{ ucfirst($attendance->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">
                                                        {{ $attendance->remarks ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-600 mt-6 py-4">No attendance records yet</p>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Progress Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V9.414l-4.293 4.293a1 1 0 01-1.414-1.414l4.293-4.293H12z" clip-rule="evenodd" />
                            </svg>
                            Progress Summary
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-semibold text-gray-900">{{ ucfirst($activity->status) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Faculties:</span>
                                <span class="font-semibold text-gray-900">{{ $activity->faculties->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Attendance Records:</span>
                                <span class="font-semibold text-gray-900">{{ $activity->attendances->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Program:</span>
                                <span class="font-semibold text-gray-900 text-right">{{ Str::limit($activity->extensionProgram->title, 20) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Created Info -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Information</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-600 mb-1">Created:</p>
                                <p class="text-gray-900">{{ $activity->created_at->format('M d, Y - H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1">Last Updated:</p>
                                <p class="text-gray-900">{{ $activity->updated_at->format('M d, Y - H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
