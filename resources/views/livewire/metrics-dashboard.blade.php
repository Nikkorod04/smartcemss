<div class="space-y-8">
    <!-- TIER 1: OUTPUT METRICS -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-blue-500">
            Tier 1: Output Metrics
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Participation Rate -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                <p class="text-sm text-gray-700 font-medium">Participation Rate</p>
                <div class="mt-3">
                    <p class="text-3xl font-bold text-blue-600">{{ $metrics['tier1']['participation_rate'] }}%</p>
                    <p class="text-xs text-gray-600 mt-2">{{ $metrics['supporting']['actual_beneficiaries'] }}/{{ $metrics['supporting']['target_beneficiaries'] }} reached</p>
                </div>
                <div class="w-full bg-blue-200 rounded-full h-2 mt-4">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($metrics['tier1']['participation_rate'], 100) }}%"></div>
                </div>
            </div>

            <!-- Activity Completion Rate -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                <p class="text-sm text-gray-700 font-medium">Activity Completion</p>
                <div class="mt-3">
                    <p class="text-3xl font-bold text-green-600">{{ $metrics['tier1']['activity_completion_rate'] }}%</p>
                    <p class="text-xs text-gray-600 mt-2">{{ $metrics['supporting']['completed_activities'] }}/{{ $metrics['supporting']['total_activities'] }} completed</p>
                </div>
                <div class="w-full bg-green-200 rounded-full h-2 mt-4">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($metrics['tier1']['activity_completion_rate'], 100) }}%"></div>
                </div>
            </div>

            <!-- Attendance Consistency -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                <p class="text-sm text-gray-700 font-medium">Attendance Consistency</p>
                <div class="mt-3">
                    <p class="text-3xl font-bold text-purple-600">{{ $metrics['tier1']['attendance_consistency'] }}%</p>
                    <p class="text-xs text-gray-600 mt-2">Avg across activities</p>
                </div>
                <div class="w-full bg-purple-200 rounded-full h-2 mt-4">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min($metrics['tier1']['attendance_consistency'], 100) }}%"></div>
                </div>
            </div>

            <!-- Budget Utilization -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 border border-orange-200">
                <p class="text-sm text-gray-700 font-medium">Budget Utilization</p>
                <div class="mt-3">
                    <p class="text-3xl font-bold text-orange-600">{{ $metrics['tier1']['budget_utilization_rate'] }}%</p>
                    <p class="text-xs text-gray-600 mt-2">₱{{ number_format($metrics['supporting']['total_spent'], 0) }} spent</p>
                </div>
                <div class="w-full bg-orange-200 rounded-full h-2 mt-4">
                    <div class="bg-orange-600 h-2 rounded-full" style="width: {{ min($metrics['tier1']['budget_utilization_rate'], 100) }}%"></div>
                </div>
            </div>

            <!-- Cost per Beneficiary -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-6 border border-red-200">
                <p class="text-sm text-gray-700 font-medium">Cost/Beneficiary</p>
                <div class="mt-3">
                    <p class="text-3xl font-bold text-red-600">₱{{ number_format($metrics['tier1']['cost_per_beneficiary'], 0) }}</p>
                    <p class="text-xs text-gray-600 mt-2">Efficiency metric</p>
                </div>
            </div>
        </div>
    </div>

    <!-- TIER 2: OUTCOME METRICS -->
    @if($metrics['tier2']['average_knowledge_gain'] !== null || $metrics['tier2']['average_satisfaction'] !== null)
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-green-500">
            Tier 2: Outcome Metrics
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Knowledge Assessment Metrics -->
            @if($metrics['tier2']['average_knowledge_gain'] !== null)
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Knowledge Assessment</h4>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                    <p class="text-sm text-gray-700 font-medium">Pre-Assessment Average</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $metrics['tier2']['average_pre_assessment'] }}%</p>
                </div>

                <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-lg p-6 border border-cyan-200">
                    <p class="text-sm text-gray-700 font-medium">Post-Assessment Average</p>
                    <p class="text-3xl font-bold text-cyan-600 mt-2">{{ $metrics['tier2']['average_post_assessment'] }}%</p>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg p-6 border border-emerald-200">
                    <p class="text-sm text-gray-700 font-medium">Knowledge Gain</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">+{{ $metrics['tier2']['average_knowledge_gain'] }} points</p>
                    @if($metrics['tier2']['knowledge_gain_percentage'] !== null)
                    <p class="text-sm text-gray-600 mt-2">+{{ $metrics['tier2']['knowledge_gain_percentage'] }}% improvement</p>
                    @endif
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-6 border border-indigo-200">
                    <p class="text-sm text-gray-700 font-medium">Skill Proficiency (70+)</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $metrics['tier2']['skill_proficiency'] }}%</p>
                    <p class="text-xs text-gray-600 mt-2">Participants scoring 70+</p>
                </div>
            </div>
            @endif

            <!-- Satisfaction Metric -->
            @if($metrics['tier2']['average_satisfaction'] !== null)
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Satisfaction & Overview</h4>

                <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-6 border border-pink-200">
                    <p class="text-sm text-gray-700 font-medium">Average Satisfaction Rating</p>
                    <div class="mt-3">
                        <p class="text-4xl font-bold text-pink-600">{{ $metrics['tier2']['average_satisfaction'] }}<span class="text-xl">/5</span></p>
                        <div class="flex gap-1 mt-3">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($metrics['tier2']['average_satisfaction']))
                                    <span class="text-xl">⭐</span>
                                @else
                                    <span class="text-xl text-gray-300">⭐</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Program Summary Stats -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 space-y-3">
                    <h5 class="font-semibold text-gray-900">Program Summary</h5>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Target Beneficiaries:</span>
                            <span class="font-bold">{{ $metrics['supporting']['target_beneficiaries'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Actual Beneficiaries:</span>
                            <span class="font-bold">{{ $metrics['supporting']['actual_beneficiaries'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Activities:</span>
                            <span class="font-bold">{{ $metrics['supporting']['total_activities'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Allocated Budget:</span>
                            <span class="font-bold">₱{{ number_format($metrics['supporting']['allocated_budget'], 0) }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t">
                            <span class="text-gray-600">Total Spent:</span>
                            <span class="font-bold text-orange-600">₱{{ number_format($metrics['supporting']['total_spent'], 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- CHARTS SECTION -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-indigo-500">
            Visual Analytics
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Knowledge Gain Chart -->
            @if($metrics['tier2']['average_knowledge_gain'] !== null)
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Knowledge Gain Progression</h4>
                <div style="height: 300px;">
                    <canvas id="knowledgeChart"></canvas>
                </div>
            </div>
            @endif

            <!-- Budget Breakdown Chart -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Budget Allocation</h4>
                <div style="height: 300px;">
                    <canvas id="budgetChart"></canvas>
                </div>
            </div>

            <!-- Output Metrics Chart -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Output Metrics Overview</h4>
                <div style="height: 300px;">
                    <canvas id="outputMetricsChart"></canvas>
                </div>
            </div>

            <!-- Activity Status Chart -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Activity Status Distribution</h4>
                <div style="height: 300px;">
                    <canvas id="activityStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Flag to prevent multiple initializations
if (!window.metricsChartsInitialized) {
    window.metricsChartsInitialized = true;
    
    function initializeMetricsCharts() {
        // Small delay to ensure DOM is ready
        setTimeout(function() {
            // Knowledge Gain Chart
            @if($metrics['tier2']['average_knowledge_gain'] !== null)
            try {
                const knowledgeCtx = document.getElementById('knowledgeChart');
                if (knowledgeCtx && !knowledgeCtx.chart) {
                    knowledgeCtx.chart = new Chart(knowledgeCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Pre-Training', 'Post-Training'],
                            datasets: [{
                                label: 'Average Score',
                                data: [{{ $metrics['tier2']['average_pre_assessment'] }}, {{ $metrics['tier2']['average_post_assessment'] }}],
                                backgroundColor: ['#FCD34D', '#06B6D4'],
                                borderColor: ['#FBBF24', '#0891B2'],
                                borderWidth: 2,
                                borderRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: true, position: 'top' }
                            },
                            scales: {
                                y: {
                                    max: 100,
                                    beginAtZero: true,
                                    ticks: { callback: function(v) { return v + '%'; } }
                                }
                            }
                        }
                    });
                }
            } catch(e) { console.error('Knowledge chart error:', e); }
            @endif

            // Budget Chart
            try {
                const budgetCtx = document.getElementById('budgetChart');
                if (budgetCtx && !budgetCtx.chart) {
                    const spent = {{ $metrics['supporting']['total_spent'] }};
                    const remaining = Math.max(0, {{ $metrics['supporting']['allocated_budget'] - $metrics['supporting']['total_spent'] }});
                    
                    budgetCtx.chart = new Chart(budgetCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Spent', 'Remaining'],
                            datasets: [{
                                data: [spent, remaining],
                                backgroundColor: ['#F97316', '#86EFAC'],
                                borderColor: ['#EA580C', '#65A30D'],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: true, position: 'bottom' } }
                        }
                    });
                }
            } catch(e) { console.error('Budget chart error:', e); }

            // Output Metrics Radar Chart
            try {
                const outputMetricsCtx = document.getElementById('outputMetricsChart');
                if (outputMetricsCtx && !outputMetricsCtx.chart) {
                    outputMetricsCtx.chart = new Chart(outputMetricsCtx, {
                        type: 'radar',
                        data: {
                            labels: ['Participation', 'Activity Completion', 'Attendance', 'Budget Util.', 'Skill Prof.'],
                            datasets: [{
                                label: 'Performance (%)',
                                data: [
                                    {{ $metrics['tier1']['participation_rate'] }},
                                    {{ $metrics['tier1']['activity_completion_rate'] }},
                                    {{ $metrics['tier1']['attendance_consistency'] }},
                                    {{ $metrics['tier1']['budget_utilization_rate'] }},
                                    {{ $metrics['tier2']['skill_proficiency'] ?? 0 }}
                                ],
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                                borderWidth: 2,
                                pointBackgroundColor: '#3B82F6',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                r: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: { callback: function(v) { return v + '%'; } }
                                }
                            },
                            plugins: { legend: { display: true, position: 'top' } }
                        }
                    });
                }
            } catch(e) { console.error('Output metrics chart error:', e); }

            // Activity Status Chart
            try {
                const activityStatusCtx = document.getElementById('activityStatusChart');
                if (activityStatusCtx && !activityStatusCtx.chart) {
                    activityStatusCtx.chart = new Chart(activityStatusCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Completed', 'Ongoing', 'Pending'],
                            datasets: [{
                                data: [{{ $completed }}, {{ $ongoing }}, {{ $pending }}],
                                backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                                borderColor: ['#059669', '#D97706', '#DC2626'],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: true, position: 'bottom' } }
                        }
                    });
                }
            } catch(e) { console.error('Activity status chart error:', e); }
        }, 50);
    }
    
    // Initialize on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeMetricsCharts);
    } else {
        initializeMetricsCharts();
    }
}
</script>
