<x-admin-layout header="Review Assessment">
    <div class="space-y-6">
        <!-- Back Link -->
        <a href="{{ route('secretary.assessments.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Assessments
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content (2 columns) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Proposal & Admin Approval Info -->
                <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                    <div class="mb-6 pb-6 border-b">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Community Livelihood Training Program</h1>
                        <p class="text-gray-600">Faculty: Dr. Maria Santos | Community: Barangay Poblacion</p>
                    </div>

                    <!-- Admin Approval Badge -->
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Admin Approval Details</h3>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">Approved by Admin</p>
                                    <p class="text-sm text-gray-700 mt-2">Approved on April 14, 2026 by Dr. Robert Morrison</p>
                                    <div class="mt-3 p-3 bg-white rounded border border-gray-200">
                                        <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Admin Remarks:</p>
                                        <p class="text-sm text-gray-900">Good proposal. Please include more detailed budget breakdown. Special Order provided.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attached SO Document -->
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 2.586L15.414 5A2 2 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                            </svg>
                            Special Order Document
                        </h3>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 2.586L15.414 5A2 2 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">SO-2026-04-14-001.pdf</p>
                                    <p class="text-xs text-gray-600">1.8 MB • Uploaded by Admin</p>
                                </div>
                            </div>
                            <a href="#" class="text-blue-600 text-sm font-medium">View</a>
                        </div>
                    </div>

                    <!-- Assessment Summary -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Assessment Summary</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Respondents:</span>
                                <span class="font-semibold text-gray-900">48 farmers</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Assessment Date:</span>
                                <span class="font-semibold text-gray-900">April 25-30, 2026</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Data Quality:</span>
                                <span class="font-semibold text-green-700">Complete & Valid</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Key Finding:</span>
                                <span class="font-semibold text-gray-900">85% interested in organic farming</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Form (1 column) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Decision</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-2 h-2 mr-1.5 rounded-full bg-yellow-600" fill="currentColor"></svg>
                            Pending Your Review
                        </span>
                    </div>

                    <form class="space-y-6">
                        @csrf

                        <!-- Secretary Decision -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">Your Decision *</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50">
                                    <input type="radio" name="decision" value="approve" class="w-4 h-4" checked />
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">Approve Assessment</p>
                                        <p class="text-xs text-gray-600">Data will be saved to database</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-red-50">
                                    <input type="radio" name="decision" value="reject" class="w-4 h-4" />
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">Reject Assessment</p>
                                        <p class="text-xs text-gray-600">Return for revision</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Secretary Remarks -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Your Remarks</label>
                            <textarea name="remarks" rows="4" placeholder="Provide feedback on the assessment..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Optional - Share observations or conditions</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-4 border-t">
                            <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Approve & Save
                            </button>

                            <a href="{{ route('secretary.assessments.index') }}" class="block w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 text-center">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-900">
                            <span class="font-semibold">Status:</span> Once you approve, the assessment data will be permanently saved to the database.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
