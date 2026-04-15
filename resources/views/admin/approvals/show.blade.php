<x-admin-layout header="Review Proposal">
    <div class="space-y-6">
        <!-- Back Link -->
        <a href="{{ route('admin.proposals.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Approvals
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content (2 columns) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Proposal Details Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Community Livelihood Training Program</h1>
                    <p class="text-gray-600 mb-6">Submitted by Dr. Maria Santos on April 13, 2026</p>

                    <!-- Description -->
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Description</h3>
                        <p class="text-gray-900 leading-relaxed">
                            This activity aims to enhance the livelihood skills of farmers in the community by providing training on modern farming techniques, crop diversification, and sustainable agriculture practices. The program will run for 3 weeks with hands-on sessions and consultation.
                        </p>
                    </div>

                    <!-- Objectives -->
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Objectives</h3>
                        <ul class="space-y-2">
                            <li class="flex gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-900">Increase crop productivity by 30%</span>
                            </li>
                            <li class="flex gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-900">Train at least 50 farmers</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Activity Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-blue-600 uppercase">Target</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">Farmers & Agricultural Workers</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-green-600 uppercase">Duration</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">Apr 20 - May 10, 2026</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-purple-600 uppercase">Participants</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">~50 people</p>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 2.586L15.414 5A2 2 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                            </svg>
                            Faculty Attachments (2)
                        </h3>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 2.586L15.414 5A2 2 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Activity-Proposal.pdf</p>
                                        <p class="text-xs text-gray-600">2.4 MB</p>
                                    </div>
                                </div>
                                <a href="#" class="text-blue-600 text-sm font-medium">View</a>
                            </div>
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 2.586L15.414 5A2 2 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Budget-Breakdown.docx</p>
                                        <p class="text-xs text-gray-600">1.2 MB</p>
                                    </div>
                                </div>
                                <a href="#" class="text-blue-600 text-sm font-medium">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Form (1 column) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Approval Decision</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-2 h-2 mr-1.5 rounded-full bg-yellow-600" fill="currentColor"></svg>
                            Pending Your Review
                        </span>
                    </div>

                    <form class="space-y-6">
                        @csrf

                        <!-- Admin Remarks -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Admin Remarks *</label>
                            <textarea name="remarks" rows="4" placeholder="Provide your feedback and conditions for approval..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required></textarea>
                            <p class="text-xs text-gray-500 mt-1">This will be visible to the faculty member</p>
                        </div>

                        <!-- Special Order Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Special Order Document (SO) *</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-blue-400"
                                onclick="document.getElementById('so-input').click()">
                                <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                                <p class="text-sm font-medium text-gray-700">Click to upload</p>
                                <p class="text-xs text-gray-500">PDF or DOCX (Max 10MB)</p>
                                <input id="so-input" type="file" name="so_file" accept=".pdf,.docx" class="hidden" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-4 border-t">
                            <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Approve Proposal
                            </button>

                            <button type="button" class="w-full px-4 py-3 bg-red-50 text-red-600 rounded-lg font-medium hover:bg-red-100 border border-red-200">
                                Reject Proposal
                            </button>

                            <a href="{{ route('admin.proposals.index') }}" class="block w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 text-center">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-900">
                            <span class="font-semibold">Note:</span> Approving this proposal will allow the faculty member to proceed with community assessments.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
