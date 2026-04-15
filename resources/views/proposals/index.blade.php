<x-faculty-layout header="Activity Proposals">
    <div class="m-6 md:m-8 bg-white rounded-lg shadow-lg p-6 md:p-8">
        <!-- Header Section -->
        <div class="mb-8 pb-6 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-900">Activity Proposals</h1>
        </div>

        <!-- Filter Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Filter by Status</h3>
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="#" class="px-4 py-2 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 text-sm font-medium hover:bg-blue-100 transition">
                    All Proposals
                </a>
                <a href="#" class="px-4 py-2 rounded-lg text-gray-600 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition">
                    Pending Admin
                </a>
                <a href="#" class="px-4 py-2 rounded-lg text-gray-600 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition">
                    Pending Assessment
                </a>
                <a href="#" class="px-4 py-2 rounded-lg text-gray-600 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition">
                    Pending Secretary
                </a>
                <a href="#" class="px-4 py-2 rounded-lg text-gray-600 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition">
                    Approved
                </a>
                <a href="#" class="px-4 py-2 rounded-lg text-gray-600 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition">
                    Rejected
                </a>
            </div>
        </div>

        <!-- Proposals Table -->
        <div class="mb-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-900 text-sm uppercase tracking-wider">Proposal</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-900 text-sm uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-900 text-sm uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-900 text-sm uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Proposal 1: Pending -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-5">
                                <p class="font-semibold text-gray-900">Agricultural Training Program</p>
                                <p class="text-sm text-gray-500 mt-1">📎 2 documents attached</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    <svg class="w-2 h-2 rounded-full bg-yellow-600" fill="currentColor"></svg>
                                    Pending Admin
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-600">April 13, 2026</td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('proposals.show', 1) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">View</a>
                            </td>
                        </tr>

                        <!-- Proposal 2: Pending Assessment Submission -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-5">
                                <p class="font-semibold text-gray-900">Women Entrepreneurship Seminar</p>
                                <p class="text-sm text-gray-500 mt-1">📎 3 documents attached</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                    <svg class="w-2 h-2 rounded-full bg-orange-600" fill="currentColor"></svg>
                                    Pending Assessment
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-600">April 10, 2026</td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('proposals.show', 2) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">View</a>
                            </td>
                        </tr>

                        <!-- Proposal 3: Pending Secretary Review -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-5">
                                <p class="font-semibold text-gray-900">Community Health Awareness Campaign</p>
                                <p class="text-sm text-gray-500 mt-1">📎 2 documents attached</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                    <svg class="w-2 h-2 rounded-full bg-purple-600" fill="currentColor"></svg>
                                    Pending Secretary Review
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-600">April 5, 2026</td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('proposals.show', 3) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">View</a>
                            </td>
                        </tr>

                        <!-- Proposal 4: Approved -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-5">
                                <p class="font-semibold text-gray-900">Youth Skills Training Workshop</p>
                                <p class="text-sm text-gray-500 mt-1">📎 2 documents attached</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <svg class="w-2 h-2 rounded-full bg-green-600" fill="currentColor"></svg>
                                    Approved
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-600">April 1, 2026</td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('proposals.show', 4) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">View</a>
                            </td>
                        </tr>

                        <!-- Proposal 5: Rejected -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-5">
                                <p class="font-semibold text-gray-900">Environmental Conservation Drive</p>
                                <p class="text-sm text-gray-500 mt-1">📎 1 document attached</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <svg class="w-2 h-2 rounded-full bg-red-600" fill="currentColor"></svg>
                                    Rejected
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-600">March 28, 2026</td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('proposals.show', 5) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div class="text-center py-16 hidden">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-600 font-semibold text-lg">No proposals yet</p>
            <p class="text-gray-500 mt-1">Create your first activity proposal to get started</p>
            <a href="{{ route('proposals.create') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                Create Proposal
            </a>
        </div>
    </div>

    <!-- Floating Action Button - Create Proposal -->
    <a href="{{ route('proposals.create') }}" class="fixed bottom-8 right-8 w-16 h-16 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-2xl transition-all transform hover:scale-110 z-40" title="Create New Proposal">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </a>
</x-faculty-layout>
