<x-admin-layout header="Assessment Approvals">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Assessment Approvals</h1>
                <p class="text-sm text-gray-600 mt-1">Review and approve community assessments submitted by faculty</p>
            </div>
            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold">
                2 Pending
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="flex gap-2 flex-wrap border-b">
            <a href="#" class="px-4 py-3 border-b-2 border-blue-600 text-blue-600 font-medium">
                Pending (2)
            </a>
            <a href="#" class="px-4 py-3 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-900">
                Approved (8)
            </a>
            <a href="#" class="px-4 py-3 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-900">
                Rejected (1)
            </a>
        </div>

        <!-- Assessments Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Activity Proposal</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Faculty / Community</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Admin Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Submitted</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Assessment 1 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900">Community Livelihood Training</p>
                                <p class="text-xs text-gray-600 mt-1">Apr 20 - May 10, 2026</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900">Dr. Maria Santos</p>
                                <p class="text-xs text-gray-600">Barangay Poblacion</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-2 h-2 mr-1.5 rounded-full bg-green-600" fill="currentColor"></svg>
                                Approved by Admin
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">3 hours ago</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('secretary.assessments.show', 1) }}" class="text-blue-600 hover:text-blue-700 font-medium">Review</a>
                        </td>
                    </tr>

                    <!-- Assessment 2 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900">Health & Sanitation Campaign</p>
                                <p class="text-xs text-gray-600 mt-1">Apr 25 - May 15, 2026</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900">Dr. Juan Reyes</p>
                                <p class="text-xs text-gray-600">Barangay San Miguel</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-2 h-2 mr-1.5 rounded-full bg-green-600" fill="currentColor"></svg>
                                Approved by Admin
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">5 hours ago</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('secretary.assessments.show', 2) }}" class="text-blue-600 hover:text-blue-700 font-medium">Review</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div class="text-center py-12 hidden">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-600 font-medium">No pending assessments</p>
            <p class="text-sm text-gray-500">All assessments have been reviewed</p>
        </div>
    </div>
</x-admin-layout>
