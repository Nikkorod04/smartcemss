<x-admin-layout>
    <div class="min-h-screen bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Program Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $program->title }}</h1>
                    <p class="text-gray-600 mt-1">Monitoring & Evaluation Metrics</p>
                </div>
                <div class="flex flex-col items-end gap-4">
                    <span class="inline-block capitalize px-4 py-2 rounded-full text-sm font-medium
                        @if($program->status === 'draft') bg-yellow-100 text-yellow-800
                        @elseif($program->status === 'ongoing') bg-green-100 text-green-800
                        @elseif($program->status === 'completed') bg-blue-100 text-blue-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($program->status) }}
                    </span>
                    <a href="{{ route('programs.show', $program) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Program
                    </a>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <livewire:metrics-dashboard :programId="$program->id" />
            </div>

            <!-- Footer Info -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
                <strong>Tier 1 Metrics:</strong> Program output (participation, completion, attendance, budget)  |  
                <strong>Tier 2 Metrics:</strong> Learning outcomes (knowledge gain, satisfaction)
            </div>
        </div>
    </div>
</x-admin-layout>
