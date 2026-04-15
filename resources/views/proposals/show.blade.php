<x-faculty-layout header="View Proposal">
    <div class="m-6 md:m-8 bg-white rounded-lg shadow-lg p-6 md:p-8">
        <!-- Header -->
        <div class="flex items-start justify-between mb-6 pb-6 border-b gap-4">
            <a href="{{ route('proposals.index') }}" class="text-lnu-blue hover:text-lnu-blue/80 transition flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">Activity Proposal</h1>
                <p class="text-sm text-gray-600 mt-1">Submitted on April 13, 2026</p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 flex-shrink-0">
                <svg class="w-2.5 h-2.5 mr-2 rounded-full bg-yellow-600" fill="currentColor"></svg>
                Pending Admin
            </span>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Content (2 columns on lg) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Proposal Title -->
                <div>
                    <h2 class="text-sm font-semibold text-gray-600 mb-2">PROPOSAL TITLE</h2>
                    <p class="text-2xl font-bold text-gray-900">Agricultural Training Program for Community Farmers</p>
                </div>

                <!-- Proposal Description -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <p class="text-gray-700 leading-relaxed">
                            This activity proposal aims to conduct comprehensive agricultural training for 150 farmers in the community. 
                            The program will focus on modern farming techniques, crop management, and sustainability practices. 
                            The proposal includes detailed implementation plans, target outcomes, and budget breakdowns in 
                            the attached documents. We expect to collaborate with the local agricultural office and 
                            community leaders for successful implementation.
                        </p>
                    </div>
                </div>

                <!-- Assessment Deadline (shown when status is Pending Assessment Submission or later) -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.293.707l-.707.707a1 1 0 101.414 1.414L9 9.414V6z" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="font-semibold text-blue-900 mb-2">Assessment Submission Deadline</h3>
                            <p class="text-sm text-blue-800">
                                <strong>Deadline:</strong> April 30, 2026
                            </p>
                            <p class="text-sm text-blue-800 mt-1">
                                Please submit all required needs assessments before the deadline. The assessment documents should include detailed findings, analysis, and recommendations.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Rejection Notice (shown when status is Rejected) -->
                <!-- <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="font-semibold text-red-900 mb-2">Proposal Rejected</h3>
                            <p class="text-sm text-red-800 mb-3">
                                <strong>Rejected by:</strong> Dr. Maria Santos (Director)
                            </p>
                            <p class="text-sm text-red-800 mb-3">
                                <strong>Remarks:</strong>
                            </p>
                            <p class="text-sm text-red-800 bg-red-100 p-3 rounded">
                                The proposal needs more detailed budget breakdown and clear implementation timeline. Please revise and resubmit with additional supporting documents from community leaders.
                            </p>
                        </div>
                    </div>
                </div> -->

                <!-- Submitted Documents -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 2.586L15.414 5A2 2 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                        </svg>
                        Proposal Documents
                    </h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 2.586L15.414 5A2 2 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900">Proposal_2026.pdf</p>
                                    <p class="text-xs text-gray-600">3.2 MB • PDF</p>
                                </div>
                            </div>
                            <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                View
                            </a>
                        </div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 2.586L15.414 5A2 2 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900">Activity_Plan.docx</p>
                                    <p class="text-xs text-gray-600">2.1 MB • DOCX</p>
                                </div>
                            </div>
                            <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar (1 column on lg) -->
            <div class="lg:col-span-1">
                <!-- Proposal Status Timeline -->
                <div class="bg-gray-50 rounded-lg p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Approval Timeline</h3>
                    
                    <div class="space-y-8">
                        <!-- Stage 1: Submitted -->
                        <div class="relative mb-8">
                            <div class="flex items-start gap-4">
                                <div class="relative flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-green-100 border-4 border-white flex items-center justify-center z-10">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="w-1 h-12 bg-gray-300 mt-2"></div>
                                </div>
                                <div class="pt-2">
                                    <p class="font-semibold text-gray-900">Proposal Submitted</p>
                                    <p class="text-sm text-gray-600 mt-1">April 13, 2026</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stage 2: Admin Review -->
                        <div class="relative mb-8">
                            <div class="flex items-start gap-4">
                                <div class="relative flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-yellow-100 border-4 border-white flex items-center justify-center z-10 animate-pulse">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="w-1 h-12 bg-gray-300 mt-2"></div>
                                </div>
                                <div class="pt-2">
                                    <p class="font-semibold text-gray-900">Pending Admin Review</p>
                                    <p class="text-sm text-gray-600 mt-1">Waiting for approval...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stage 3: Assessment Submission -->
                        <div class="relative mb-8">
                            <div class="flex items-start gap-4">
                                <div class="relative flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-200 border-4 border-white flex items-center justify-center z-10">
                                        <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                                    </div>
                                    <div class="w-1 h-12 bg-gray-300 mt-2"></div>
                                </div>
                                <div class="pt-2">
                                    <p class="font-semibold text-gray-900">Pending Assessment Submission</p>
                                    <p class="text-sm text-gray-600 mt-1">Submit needs assessments by April 30, 2026</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stage 4: Secretary Review -->
                        <div class="relative">
                            <div class="flex items-start gap-4">
                                <div class="relative flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-200 border-4 border-white flex items-center justify-center z-10">
                                        <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <p class="font-semibold text-gray-900">Pending Secretary Review</p>
                                    <p class="text-sm text-gray-600 mt-1">Secretary reviews and approves</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-faculty-layout>
