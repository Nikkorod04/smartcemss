<div wire:ignore>
    <x-admin-layout>
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900">Assessment Summary - Dashboards</h1>
                    <p class="text-gray-600 mt-2">{{ $community->name }} {{ $quarter ? '| ' . $quarter . ' ' . $year : '' }}</p>
                    <p class="text-sm text-gray-500 mt-1">Total Assessments: <span class="font-bold text-blue-600">{{ count($assessments) }}</span></p>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-sm text-gray-600">Total Responses</p>
                        <p class="text-3xl font-bold text-blue-600">{{ count($assessments) }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-sm text-gray-600">Average Age</p>
                        <p class="text-3xl font-bold text-green-600">{{ $summary['demographics']['avgAge'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-sm text-gray-600">With Electricity</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $summary['housing']['electricity']['Yes'] ?? 0 }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-sm text-gray-600">Training Interested</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $summary['training']['interested'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- Demographics Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Age Distribution Bar Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Age Distribution</h2>
                        <canvas id="ageChart"></canvas>
                    </div>

                    <!-- Education Pie Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Educational Attainment</h2>
                        <canvas id="educationChart"></canvas>
                    </div>

                    <!-- Housing Type Pie Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">House Types</h2>
                        <canvas id="housingChart"></canvas>
                    </div>

                    <!-- Electricity Access Doughnut Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Electricity Access</h2>
                        <canvas id="electricityChart"></canvas>
                    </div>
                </div>

                <!-- Health & Infrastructure Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Common Illnesses Horizontal Bar -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Top Common Illnesses</h2>
                        <canvas id="illnessesChart"></canvas>
                    </div>

                    <!-- Health Problems Horizontal Bar -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Top Health Problems</h2>
                        <canvas id="healthProblemsChart"></canvas>
                    </div>

                    <!-- Toilet Access -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Own Toilet</h2>
                        <canvas id="toiletChart"></canvas>
                    </div>

                    <!-- Problems by Category Bar Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Needs by Category</h2>
                        <canvas id="problemsChart"></canvas>
                    </div>
                </div>

                <!-- Training & Organization Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Training Interest -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Livelihood Training Interest</h2>
                        <canvas id="trainingChart"></canvas>
                    </div>

                    <!-- Available for Training -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Available for Training</h2>
                        <canvas id="availabilityChart"></canvas>
                    </div>

                    <!-- Organization Membership -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Organization Membership</h2>
                        <canvas id="organizationChart"></canvas>
                    </div>

                    <!-- Service Satisfaction -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Service Ratings (Avg Score)</h2>
                        <canvas id="serviceRatingsChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </x-admin-layout>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Define colors for charts
        const colors = {
            primary: 'rgb(59, 130, 246)',
            success: 'rgb(34, 197, 94)',
            warning: 'rgb(234, 179, 8)',
            danger: 'rgb(239, 68, 68)',
            info: 'rgb(59, 130, 246)',
            secondary: 'rgb(107, 114, 128)',
        };

        const chartColors = [
            'rgb(59, 130, 246)',
            'rgb(34, 197, 94)',
            'rgb(234, 179, 8)',
            'rgb(239, 68, 68)',
            'rgb(168, 85, 247)',
            'rgb(8, 145, 178)',
            'rgb(244, 63, 94)',
        ];

        // Age Distribution Chart
        new Chart(document.getElementById('ageChart'), {
            type: 'bar',
            data: {
                labels: @json(array_keys($summary['demographics']['ageGroups'] ?? [])),
                datasets: [{
                    label: 'Number of Respondents',
                    data: @json(array_values($summary['demographics']['ageGroups'] ?? [])),
                    backgroundColor: colors.primary,
                    borderColor: 'rgb(30, 58, 138)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Education Pie Chart
        new Chart(document.getElementById('educationChart'), {
            type: 'pie',
            data: {
                labels: @json(array_keys($summary['education'] ?? [])),
                datasets: [{
                    data: @json(array_values($summary['education'] ?? [])),
                    backgroundColor: chartColors,
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // Housing Type Pie Chart
        new Chart(document.getElementById('housingChart'), {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($summary['housing']['houseTypes'] ?? [])),
                datasets: [{
                    data: @json(array_values($summary['housing']['houseTypes'] ?? [])),
                    backgroundColor: chartColors,
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // Electricity Doughnut Chart
        new Chart(document.getElementById('electricityChart'), {
            type: 'doughnut',
            data: {
                labels: ['Has Electricity', 'No Electricity'],
                datasets: [{
                    data: [@json($summary['housing']['electricity']['Yes'] ?? 0), @json($summary['housing']['electricity']['No'] ?? 0)],
                    backgroundColor: [colors.success, colors.danger],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // Common Illnesses Horizontal Bar
        new Chart(document.getElementById('illnessesChart'), {
            type: 'bar',
            data: {
                labels: @json(array_keys($summary['health']['commonIllnesses'] ?? [])),
                datasets: [{
                    label: 'Count',
                    data: @json(array_values($summary['health']['commonIllnesses'] ?? [])),
                    backgroundColor: colors.warning,
                    borderColor: 'rgb(180, 128, 0)',
                    borderWidth: 1,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });

        // Health Problems Horizontal Bar
        new Chart(document.getElementById('healthProblemsChart'), {
            type: 'bar',
            data: {
                labels: @json(array_keys($summary['health']['healthProblems'] ?? [])),
                datasets: [{
                    label: 'Count',
                    data: @json(array_values($summary['health']['healthProblems'] ?? [])),
                    backgroundColor: colors.danger,
                    borderColor: 'rgb(153, 27, 27)',
                    borderWidth: 1,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });

        // Toilet Access Doughnut
        new Chart(document.getElementById('toiletChart'), {
            type: 'doughnut',
            data: {
                labels: ['Has Toilet', 'No Toilet'],
                datasets: [{
                    data: [@json($summary['housing']['hasToilet']['Yes'] ?? 0), @json($summary['housing']['hasToilet']['No'] ?? 0)],
                    backgroundColor: [colors.success, colors.danger],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // Problems by Category Bar Chart
        new Chart(document.getElementById('problemsChart'), {
            type: 'bar',
            data: {
                labels: @json(array_keys($summary['problems'] ?? [])),
                datasets: [{
                    label: 'Households with Problems',
                    data: @json(array_values($summary['problems'] ?? [])),
                    backgroundColor: colors.info,
                    borderColor: 'rgb(30, 58, 138)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Training Interest Pie
        new Chart(document.getElementById('trainingChart'), {
            type: 'pie',
            data: {
                labels: ['Interested', 'Not Interested'],
                datasets: [{
                    data: [@json($summary['training']['interested'] ?? 0), @json($summary['training']['notInterested'] ?? 0)],
                    backgroundColor: [colors.success, colors.secondary],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // Available for Training Pie
        new Chart(document.getElementById('availabilityChart'), {
            type: 'pie',
            data: {
                labels: ['Available', 'Not Available'],
                datasets: [{
                    data: [@json($summary['training']['availableForTraining'] ?? 0), @json($summary['training']['notAvailable'] ?? 0)],
                    backgroundColor: [colors.success, colors.danger],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // Organization Membership
        new Chart(document.getElementById('organizationChart'), {
            type: 'doughnut',
            data: {
                labels: ['Members', 'Non-Members'],
                datasets: [{
                    data: [@json($summary['organization']['Member'] ?? 0), @json($summary['organization']['NonMember'] ?? 0)],
                    backgroundColor: [colors.info, colors.secondary],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // Service Ratings Bar Chart
        new Chart(document.getElementById('serviceRatingsChart'), {
            type: 'bar',
            data: {
                labels: @json(array_keys($summary['serviceRatings'] ?? [])),
                datasets: [{
                    label: 'Average Rating (out of 5)',
                    data: @json(array_values($summary['serviceRatings'] ?? [])),
                    backgroundColor: colors.primary,
                    borderColor: 'rgb(30, 58, 138)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
    @endpush
</div>
