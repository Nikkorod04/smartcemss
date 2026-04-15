<x-admin-layout header="Proposal Approvals">
    <div class="bg-white rounded-lg shadow-lg -mx-6 -my-8 md:-mx-8 md:-my-8 p-6 md:p-8 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Activity Proposal Approvals</h1>
                <p class="text-sm text-gray-600 mt-1">Review and approve activity proposals from faculty members</p>
            </div>
            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold">
                3 Pending
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="flex gap-2 flex-wrap border-b">
            <a href="#" class="px-4 py-3 border-b-2 border-blue-600 text-blue-600 font-medium">
                Pending (3)
            </a>
            <a href="#" class="px-4 py-3 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-900">
                Approved (5)
            </a>
            <a href="#" class="px-4 py-3 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-900">
                Rejected (2)
            </a>
        </div>

        <!-- Proposals Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Proposal Card 1 -->
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Community Livelihood Training</h3>
                        <p class="text-sm text-gray-600">Submitted by Dr. Maria Santos</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending
                    </span>
                </div>

                <p class="text-sm text-gray-700 mb-4 line-clamp-3">
                    This activity aims to enhance the livelihood skills of farmers in the community by providing training on modern farming techniques and sustainable agriculture practices.
                </p>

                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-medium text-gray-900">Apr 20 - May 10, 2026</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Beneficiaries:</span>
                        <span class="font-medium text-gray-900">~50 farmers</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Attachments:</span>
                        <span class="font-medium text-gray-900">2 files</span>
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <a href="{{ route('admin.proposals.show', 1) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                        Review & Approve
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Proposal Card 2 -->
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Health & Sanitation Campaign</h3>
                        <p class="text-sm text-gray-600">Submitted by Dr. Juan Reyes</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending
                    </span>
                </div>

                <p class="text-sm text-gray-700 mb-4 line-clamp-3">
                    Educational program on proper hygiene practices and health awareness for the barangay community, targeting school children and families.
                </p>

                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-medium text-gray-900">Apr 25 - May 15, 2026</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Beneficiaries:</span>
                        <span class="font-medium text-gray-900">~200 students & families</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Attachments:</span>
                        <span class="font-medium text-gray-900">3 files</span>
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <a href="{{ route('admin.proposals.show', 2) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                        Review & Approve
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Proposal Card 3 -->
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Barangay Infrastructure Assessment</h3>
                        <p class="text-sm text-gray-600">Submitted by Dr. Carlos Mendoza</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending
                    </span>
                </div>

                <p class="text-sm text-gray-700 mb-4 line-clamp-3">
                    Comprehensive assessment of current infrastructure conditions in the barangay to identify gaps and areas for improvement.
                </p>

                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-medium text-gray-900">May 1 - May 20, 2026</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Beneficiaries:</span>
                        <span class="font-medium text-gray-900">Entire barangay</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Attachments:</span>
                        <span class="font-medium text-gray-900">1 file</span>
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <a href="{{ route('admin.proposals.show', 3) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                        Review & Approve
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
