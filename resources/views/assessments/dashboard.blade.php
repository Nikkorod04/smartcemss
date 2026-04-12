<x-admin-layout header="Assessment Summary Dashboard">
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Assessment Summary Dashboard</h1>
                <p class="text-gray-600 mt-2">View comprehensive charts and analytics for community needs assessments</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Filter Results</h2>
                <form action="{{ route('assessments.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Community Filter -->
                    <div>
                        <label for="community_id" class="block text-sm font-medium text-gray-700 mb-2">Community</label>
                        <select name="community_id" id="community_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">All Communities</option>
                            @foreach($communities as $community)
                                <option value="{{ $community->id }}" @selected($selectedCommunity == $community->id)>
                                    {{ $community->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quarter Filter -->
                    <div>
                        <label for="quarter" class="block text-sm font-medium text-gray-700 mb-2">Quarter</label>
                        <select name="quarter" id="quarter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">All Quarters</option>
                            <option value="Q1" @selected($selectedQuarter == 'Q1')>Q1</option>
                            <option value="Q2" @selected($selectedQuarter == 'Q2')>Q2</option>
                            <option value="Q3" @selected($selectedQuarter == 'Q3')>Q3</option>
                            <option value="Q4" @selected($selectedQuarter == 'Q4')>Q4</option>
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <select name="year" id="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="2024" @selected($selectedYear == 2024)>2024</option>
                            <option value="2025" @selected($selectedYear == 2025)>2025</option>
                            <option value="2026" @selected($selectedYear == 2026)>2026</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Generate Dashboard
                        </button>
                    </div>
                </form>
            </div>

            <!-- Charts Livewire Component -->
            @if($selectedCommunity || $selectedQuarter)
                <livewire:assessment-summary-charts :communityId="$selectedCommunity" :quarter="$selectedQuarter" />
            @else
                <!-- Default: Show Anibong Q1 2025 -->
                <livewire:assessment-summary-charts communityId="null" quarter="null" />
            @endif
        </div>
    </div>
</x-admin-layout>
