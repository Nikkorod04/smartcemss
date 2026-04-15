<x-admin-layout>
    <div class="min-h-screen bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex items-start gap-4">
                <a href="{{ route('communities.show', $community) }}" class="inline-flex items-center justify-center w-10 h-10 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition mt-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $community->name }}</h1>
                    <p class="text-gray-600 mt-1">Community Needs Assessment Summary</p>
                </div>
            </div>

            <!-- Quarter/Year Selector -->
            @if($availablePeriods->count() > 0)
            <div class="mb-8 flex items-end gap-4">
                <div>
                    <label for="period-selector" class="block text-sm font-bold text-gray-900 mb-3">View Assessment by Period:</label>
                    <select id="period-selector" class="px-4 py-2 pr-10 rounded-lg border-2 border-indigo-600 bg-white text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer appearance-none" onchange="window.location.href = this.value">
                        @foreach($availablePeriods as $period)
                        <option value="{{ route('communities.assessment-summary', ['community' => $community, 'quarter' => $period->quarter, 'year' => $period->year]) }}"
                            @if($period->quarter === $quarter && $period->year === $year) selected @endif>
                            {{ $period->quarter }} {{ $period->year }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                    {{ $assessments->count() }} {{ Str::plural('response', $assessments->count()) }}
                </span>
            </div>
            @endif

            <!-- Demographics Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-blue-500">Demographics</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Gender Distribution -->
                    @if($formatted['demographics']['gender'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Gender Distribution</h3>
                        <div class="space-y-3">
                            @foreach($formatted['demographics']['gender'] as $gender => $percentage)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($gender) }}</span>
                                    <span class="text-sm font-bold text-blue-600">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Religion Distribution -->
                    @if($formatted['demographics']['religion'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Religion Distribution</h3>
                        <div class="space-y-3">
                            @foreach($formatted['demographics']['religion'] as $religion => $percentage)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($religion) }}</span>
                                    <span class="text-sm font-bold text-green-600">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Education Distribution -->
                    @if($formatted['demographics']['education'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Education Level Distribution</h3>
                        <div class="space-y-3">
                            @foreach($formatted['demographics']['education'] as $edu => $percentage)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($edu) }}</span>
                                    <span class="text-sm font-bold text-purple-600">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Civil Status Distribution -->
                    @if($formatted['demographics']['civil_status'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Civil Status Distribution</h3>
                        <div class="space-y-3">
                            @foreach($formatted['demographics']['civil_status'] as $status => $percentage)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($status) }}</span>
                                    <span class="text-sm font-bold text-orange-600">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Interests Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-indigo-500">Training & Interest Needs</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Livelihood Interests -->
                    @if($formatted['interests']['livelihood'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Livelihood Interests</h3>
                        <ol class="space-y-2">
                            @foreach($formatted['interests']['livelihood'] as $interest => $count)
                            <li class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold">{{ $loop->iteration }}</span>
                                <span class="text-sm text-gray-700">{{ ucfirst($interest) }}</span>
                                <span class="text-xs font-semibold text-indigo-600 ml-auto">{{ $count }} mentions</span>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                    @endif

                    <!-- Educational Interests -->
                    @if($formatted['interests']['educational'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Educational Interests</h3>
                        <ol class="space-y-2">
                            @foreach($formatted['interests']['educational'] as $interest => $count)
                            <li class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold">{{ $loop->iteration }}</span>
                                <span class="text-sm text-gray-700">{{ ucfirst($interest) }}</span>
                                <span class="text-xs font-semibold text-indigo-600 ml-auto">{{ $count }} mentions</span>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Problems Identified Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-red-500">Identified Community Problems</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Health Problems -->
                    @if($formatted['problems']['health'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-600"></span> Health
                        </h4>
                        <ol class="space-y-2 text-sm">
                            @foreach(array_slice($formatted['problems']['health'], 0, 3) as $problem => $count)
                            <li class="text-gray-700">{{ $loop->iteration }}. {{ ucfirst($problem) }} <span class="text-xs text-gray-500">({{ $count }})</span></li>
                            @endforeach
                        </ol>
                    </div>
                    @endif

                    <!-- Family Problems -->
                    @if($formatted['problems']['family'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-600"></span> Family
                        </h4>
                        <ol class="space-y-2 text-sm">
                            @foreach(array_slice($formatted['problems']['family'], 0, 3) as $problem => $count)
                            <li class="text-gray-700">{{ $loop->iteration }}. {{ ucfirst($problem) }} <span class="text-xs text-gray-500">({{ $count }})</span></li>
                            @endforeach
                        </ol>
                    </div>
                    @endif

                    <!-- Employment Problems -->
                    @if($formatted['problems']['employment'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-orange-600"></span> Employment
                        </h4>
                        <ol class="space-y-2 text-sm">
                            @foreach(array_slice($formatted['problems']['employment'], 0, 3) as $problem => $count)
                            <li class="text-gray-700">{{ $loop->iteration }}. {{ ucfirst($problem) }} <span class="text-xs text-gray-500">({{ $count }})</span></li>
                            @endforeach
                        </ol>
                    </div>
                    @endif

                    <!-- Economic Problems -->
                    @if($formatted['problems']['economic'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-600"></span> Economic
                        </h4>
                        <ol class="space-y-2 text-sm">
                            @foreach(array_slice($formatted['problems']['economic'], 0, 3) as $problem => $count)
                            <li class="text-gray-700">{{ $loop->iteration }}. {{ ucfirst($problem) }} <span class="text-xs text-gray-500">({{ $count }})</span></li>
                            @endforeach
                        </ol>
                    </div>
                    @endif

                    <!-- Infrastructure Problems -->
                    @if($formatted['problems']['infrastructure'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-600"></span> Infrastructure
                        </h4>
                        <ol class="space-y-2 text-sm">
                            @foreach(array_slice($formatted['problems']['infrastructure'], 0, 3) as $problem => $count)
                            <li class="text-gray-700">{{ $loop->iteration }}. {{ ucfirst($problem) }} <span class="text-xs text-gray-500">({{ $count }})</span></li>
                            @endforeach
                        </ol>
                    </div>
                    @endif

                    <!-- Security Problems -->
                    @if($formatted['problems']['security'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span> Security
                        </h4>
                        <ol class="space-y-2 text-sm">
                            @foreach(array_slice($formatted['problems']['security'], 0, 3) as $problem => $count)
                            <li class="text-gray-700">{{ $loop->iteration }}. {{ ucfirst($problem) }} <span class="text-xs text-gray-500">({{ $count }})</span></li>
                            @endforeach
                        </ol>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Infrastructure & Services Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-green-500">Infrastructure & Services</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Water Sources -->
                    @if($formatted['infrastructure']['water_sources'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Water Sources</h3>
                        <div class="space-y-3">
                            @foreach($formatted['infrastructure']['water_sources'] as $source => $percentage)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($source) }}</span>
                                    <span class="text-sm font-bold text-cyan-600">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-cyan-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- House Types -->
                    @if($formatted['infrastructure']['house_types'])
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">House Types</h3>
                        <div class="space-y-3">
                            @foreach($formatted['infrastructure']['house_types'] as $house => $percentage)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($house) }}</span>
                                    <span class="text-sm font-bold text-amber-600">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-amber-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-lg p-6 border border-cyan-200">
                        <p class="text-sm text-gray-700 font-medium">Electricity Access</p>
                        <p class="text-3xl font-bold text-cyan-600 mt-2">{{ round($formatted['infrastructure']['electricity_access_percentage'], 1) }}%</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                        <p class="text-sm text-gray-700 font-medium">Organization Participation</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">{{ round($formatted['infrastructure']['organization_membership_percentage'], 1) }}%</p>
                    </div>
                    <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-6 border border-pink-200">
                        <p class="text-sm text-gray-700 font-medium">Training Availability</p>
                        <p class="text-3xl font-bold text-pink-600 mt-2">{{ round($formatted['infrastructure']['training_availability_percentage'], 1) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            @if($chartData)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-indigo-500">Assessment Data Visualizations</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Age Distribution Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Age Distribution</h3>
                        <canvas id="ageChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Educational Attainment Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Educational Attainment</h3>
                        <canvas id="educationChart" class="max-h-80"></canvas>
                    </div>

                    <!-- House Types Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">House Types</h3>
                        <canvas id="housesChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Electricity Access Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Electricity Access</h3>
                        <canvas id="electricityChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Common Illnesses Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Common Illnesses</h3>
                        <canvas id="illnessesChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Health Problems Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Health Problems Reported</h3>
                        <canvas id="healthProblemsChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Toilet Access Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Own Toilet/Latrine</h3>
                        <canvas id="toiletChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Needs by Category Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Community Needs by Category</h3>
                        <canvas id="needsChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Training Interest Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Training Interest</h3>
                        <canvas id="trainingInterestChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Training Availability Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Available for Training</h3>
                        <canvas id="trainingAvailChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Organization Membership Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Organization Membership</h3>
                        <canvas id="organizationChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Service Ratings Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Government Service Ratings</h3>
                        <canvas id="serviceRatingsChart" class="max-h-80"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <!-- Footer Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800 text-center">
                <p><strong>Last Updated:</strong> {{ $summary->last_calculated_at?->format('M d, Y H:i A') ?? 'Never' }}</p>
                <p class="text-xs text-blue-600 mt-2">This summary is automatically recalculated when new assessments are submitted.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData ?? []);
        console.log('Chart Data', chartData);

        // Color palettes for charts
        const colors = {
            blue: '#3B82F6',
            indigo: '#6366F1',
            purple: '#A855F7',
            pink: '#EC4899',
            red: '#EF4444',
            orange: '#F97316',
            amber: '#F59E0B',
            yellow: '#EAB308',
            lime: '#84CC16',
            green: '#22C55E',
            emerald: '#10B981',
            teal: '#14B8A6',
            cyan: '#06B6D4',
            sky: '#0EA5E9',
            slate: '#64748B',
        };

        function getColorArray(count, startColor = 'blue') {
            const colorList = Object.values(colors);
            const result = [];
            for (let i = 0; i < count; i++) {
                result.push(colorList[i % colorList.length]);
            }
            return result;
        }

        // Helper function to safely create chart
        function createChart(id, config) {
            try {
                const element = document.getElementById(id);
                if (element) {
                    new Chart(element, config);
                    console.log(`Chart ${id} created successfully`);
                } else {
                    console.warn(`Canvas element with id "${id}" not found`);
                }
            } catch (error) {
                console.error(`Error creating chart ${id}:`, error);
            }
        }

        // 1. Age Distribution (Bar Chart)
        if (chartData.demographics && Object.keys(chartData.demographics).length > 0) {
            createChart('ageChart', {
                type: 'bar',
                data: {
                    labels: Object.keys(chartData.demographics),
                    datasets: [{
                        label: 'Number of Respondents',
                        data: Object.values(chartData.demographics),
                        backgroundColor: colors.blue,
                        borderColor: colors.blue,
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // 2. Educational Attainment (Pie Chart)
        if (chartData.education && Object.keys(chartData.education).length > 0) {
            const eduKeys = Object.keys(chartData.education);
            createChart('educationChart', {
                type: 'pie',
                data: {
                    labels: eduKeys,
                    datasets: [{
                        data: Object.values(chartData.education),
                        backgroundColor: getColorArray(eduKeys.length),
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // 3. House Types (Doughnut Chart)
        if (chartData.housing && chartData.housing.houseTypes && Object.keys(chartData.housing.houseTypes).length > 0) {
            const houseKeys = Object.keys(chartData.housing.houseTypes);
            createChart('housesChart', {
                type: 'doughnut',
                data: {
                    labels: houseKeys,
                    datasets: [{
                        data: Object.values(chartData.housing.houseTypes),
                        backgroundColor: getColorArray(houseKeys.length),
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // 4. Electricity Access (Doughnut Chart)
        if (chartData.housing && chartData.housing.electricity && Object.keys(chartData.housing.electricity).length > 0) {
            createChart('electricityChart', {
                type: 'doughnut',
                data: {
                    labels: Object.keys(chartData.housing.electricity),
                    datasets: [{
                        data: Object.values(chartData.housing.electricity),
                        backgroundColor: [colors.cyan, colors.slate],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // 5. Common Illnesses (Horizontal Bar Chart)
        if (chartData.health && chartData.health.commonIllnesses && Object.keys(chartData.health.commonIllnesses).length > 0) {
            createChart('illnessesChart', {
                type: 'bar',
                data: {
                    labels: Object.keys(chartData.health.commonIllnesses),
                    datasets: [{
                        label: 'Count',
                        data: Object.values(chartData.health.commonIllnesses),
                        backgroundColor: colors.orange,
                        borderColor: colors.orange,
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // 6. Health Problems (Horizontal Bar Chart)
        if (chartData.health && chartData.health.healthProblems && Object.keys(chartData.health.healthProblems).length > 0) {
            createChart('healthProblemsChart', {
                type: 'bar',
                data: {
                    labels: Object.keys(chartData.health.healthProblems),
                    datasets: [{
                        label: 'Count',
                        data: Object.values(chartData.health.healthProblems),
                        backgroundColor: colors.red,
                        borderColor: colors.red,
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // 7. Toilet Access (Doughnut Chart)
        if (chartData.housing && chartData.housing.hasToilet && Object.keys(chartData.housing.hasToilet).length > 0) {
            createChart('toiletChart', {
                type: 'doughnut',
                data: {
                    labels: Object.keys(chartData.housing.hasToilet),
                    datasets: [{
                        data: Object.values(chartData.housing.hasToilet),
                        backgroundColor: [colors.green, colors.slate],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // 8. Needs by Category (Bar Chart)
        if (chartData.problems && Object.keys(chartData.problems).length > 0) {
            createChart('needsChart', {
                type: 'bar',
                data: {
                    labels: Object.keys(chartData.problems),
                    datasets: [{
                        label: 'Number of Issues',
                        data: Object.values(chartData.problems),
                        backgroundColor: colors.indigo,
                        borderColor: colors.indigo,
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // 9. Training Interest (Pie Chart)
        if (chartData.training && (chartData.training.interested > 0 || chartData.training.notInterested > 0)) {
            createChart('trainingInterestChart', {
                type: 'pie',
                data: {
                    labels: ['Interested', 'Not Interested'],
                    datasets: [{
                        data: [chartData.training.interested, chartData.training.notInterested],
                        backgroundColor: [colors.emerald, colors.red],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // 10. Training Availability (Pie Chart)
        if (chartData.training && (chartData.training.availableForTraining > 0 || chartData.training.notAvailable > 0)) {
            createChart('trainingAvailChart', {
                type: 'pie',
                data: {
                    labels: ['Available', 'Not Available'],
                    datasets: [{
                        data: [chartData.training.availableForTraining, chartData.training.notAvailable],
                        backgroundColor: [colors.sky, colors.slate],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // 11. Organization Membership (Doughnut Chart)
        if (chartData.organization && Object.keys(chartData.organization).length > 0) {
            createChart('organizationChart', {
                type: 'doughnut',
                data: {
                    labels: Object.keys(chartData.organization),
                    datasets: [{
                        data: Object.values(chartData.organization),
                        backgroundColor: [colors.emerald, colors.slate],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // 12. Service Ratings (Bar Chart)
        if (chartData.serviceRatings && Object.keys(chartData.serviceRatings).length > 0) {
            createChart('serviceRatingsChart', {
                type: 'bar',
                data: {
                    labels: Object.keys(chartData.serviceRatings),
                    datasets: [{
                        label: 'Average Rating (out of 5)',
                        data: Object.values(chartData.serviceRatings),
                        backgroundColor: colors.purple,
                        borderColor: colors.purple,
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }
    </script>

            <!-- AI-Powered Community Analysis Section -->
            @if($summary && $summary->ai_analysis)
            <div class="mb-8 mt-12">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-bold text-gray-900 pb-3 border-b-2 border-amber-500">AI-Powered Community Analysis</h2>
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Powered by ChatGPT
                        </span>
                    </div>
                    <form method="POST" action="{{ route('communities.regenerate-analysis', $community) }}" class="inline">
                        @csrf
                        <input type="hidden" name="quarter" value="{{ $quarter }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-medium transition duration-200 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Regenerate Analysis
                        </button>
                    </form>
                </div>

                <!-- Analysis Content -->
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg shadow-md p-8 border border-amber-200">
                    <div class="prose prose-sm max-w-none text-gray-800 leading-relaxed prose-headings:text-amber-900 prose-strong:text-amber-900 prose-h1:text-xl prose-h2:text-lg prose-h3:text-base prose-h4:text-sm prose-ul:list-disc prose-ol:list-decimal prose-li:ml-4 prose-code:bg-amber-200 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-amber-900 prose-hr:border-amber-300">
                        {!! Str::markdown($summary->ai_analysis) !!}
                    </div>
                </div>

                <!-- Last Generated Time -->
                @if($summary->ai_analysis_generated_at)
                <div class="mt-4 text-xs text-gray-500 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Analysis generated on {{ $summary->ai_analysis_generated_at->format('M d, Y \a\t H:i A') }}
                </div>
                @endif
            </div>
            @else
            <!-- No AI Analysis yet - Show placeholder -->
            <div class="mb-8 mt-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 pb-3 border-b-2 border-gray-300">AI-Powered Community Analysis</h2>
                    <form method="POST" action="{{ route('communities.regenerate-analysis', $community) }}" class="inline">
                        @csrf
                        <input type="hidden" name="quarter" value="{{ $quarter }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-medium transition duration-200 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Generate Insights
                        </button>
                    </form>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-8 border border-gray-300 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-200 mb-4">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <p class="text-gray-600 font-medium">AI analysis will be generated automatically when new assessments are added.</p>
                    <p class="text-gray-500 text-sm mt-2">The system analyzes all responses for this period and creates insights, priorities, and recommendations.</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-admin-layout>
