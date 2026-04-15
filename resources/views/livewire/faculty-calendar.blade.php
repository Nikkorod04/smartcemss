<div wire:ignore.self>
    <!-- Legend -->
    <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
            <div class="w-4 h-4 rounded" style="background-color: #003599;"></div>
            <span class="text-xs font-medium text-gray-900">Programs</span>
        </div>
        <div class="flex items-center gap-2 p-3 bg-green-50 rounded-lg border border-green-200">
            <div class="w-4 h-4 rounded" style="background-color: #28a745;"></div>
            <span class="text-xs font-medium text-gray-900">Activities</span>
        </div>
        <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg border border-gray-300">
            <div class="w-4 h-4 rounded" style="background-color: #6c757d;"></div>
            <span class="text-xs font-medium text-gray-900">Approved</span>
        </div>
        <div class="flex items-center gap-2 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
            <div class="w-4 h-4 rounded" style="background-color: #ffc107;"></div>
            <span class="text-xs font-medium text-gray-900">Pending</span>
        </div>
    </div>

    <!-- Main Content: Calendar + Upcoming Events Below -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Calendar (Full Width) -->
        <div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Upcoming Events Section (Full Width Below) -->
        <div>
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-lg p-6 border border-blue-200">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <h2 class="text-xl font-bold text-gray-900">Upcoming Events <span class="text-sm text-gray-500">({{ isset($upcomingEvents) ? count($upcomingEvents) : 0 }})</span></h2>
                </div>

                @if(isset($upcomingEvents) && is_array($upcomingEvents) && count($upcomingEvents) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        @foreach($upcomingEvents as $event)
                        <a href="{{ $event['url'] }}" class="p-4 bg-white rounded-lg border-t-4 hover:shadow-md transition-all group" style="border-color: {{ $event['color'] }};">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    @if($event['type'] === 'program')
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 truncate">{{ $event['title'] }}</h3>
                                    <p class="text-xs text-gray-600 mt-1">
                                        {{ $event['start_date']->format('M d, Y') }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($event['type'] === 'program')
                                                bg-blue-100 text-blue-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($event['type']) }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($event['status'] === 'approved')
                                                bg-gray-100 text-gray-800
                                            @elseif($event['status'] === 'ongoing')
                                                bg-purple-100 text-purple-800
                                            @else
                                                bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($event['status']) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- See All Link -->
                    <div class="mt-6 pt-4 border-t border-blue-200">
                        <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                            View All Events
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="py-8 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0 5 5 0 01-10 0z" />
                        </svg>
                        <p class="text-gray-600 font-medium mb-2">No Upcoming Events</p>
                        <p class="text-sm text-gray-500">You don't have any scheduled programs or activities coming up.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Availability Modal -->
    <div id="addAvailabilityModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md my-8">
            <div class="bg-white border-b border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-lnu-blue">Add Your Availability</h2>
                <p class="text-gray-600 text-sm mt-1">Let the director know when you're available</p>
            </div>
            <form id="addAvailabilityForm" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">From Date</label>
                        <input type="date" id="availabilityDateFrom" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">To Date</label>
                        <input type="date" id="availabilityDateTo" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Time Slot</label>
                    <select id="availabilityTimeSlot" name="time_slot" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" required>
                        <option value="">Select time slot...</option>
                        <option value="Morning (8:00 AM - 12:00 PM)">Morning (8:00 AM - 12:00 PM)</option>
                        <option value="Afternoon (1:00 PM - 5:00 PM)">Afternoon (1:00 PM - 5:00 PM)</option>
                        <option value="Whole Day (8:00 AM - 5:00 PM)">Whole Day (8:00 AM - 5:00 PM)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Remarks (Optional)</label>
                    <textarea id="availabilityRemarks" name="remarks" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" placeholder="Any additional notes..."></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-lnu-blue text-white py-2 px-4 rounded-md hover:bg-blue-700 transition font-medium">
                        Add Availability
                    </button>
                    <button type="button" onclick="closeAvailabilityModal()" class="flex-1 bg-gray-300 text-gray-900 py-2 px-4 rounded-md hover:bg-gray-400 transition font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- FullCalendar JS & CSS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
        contentHeight: 'auto',
        events: {
            url: '{{ route("api.calendar.events") }}',
            success: function(data) {
                console.log('Events loaded:', data);
            },
            error: function() {
                console.error('Failed to load events');
            }
        },
        eventClick: function(info) {
            // Navigate to the event
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        eventDrop: function(info) {
            // Update event dates on drag-drop
            const eventId = info.event.id;
            const start = info.event.start ? info.event.start.toISOString().split('T')[0] : null;
            const end = info.event.end ? 
                new Date(info.event.end.getTime() - 24 * 60 * 60 * 1000).toISOString().split('T')[0] : 
                start;

            if (start && end) {
                fetch('{{ route("api.calendar.events.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        eventId: eventId,
                        start: start,
                        end: end
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', data.message);
                    } else {
                        showNotification('error', data.error || 'Failed to update dates');
                        info.revert();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Failed to update dates');
                    info.revert();
                });
            }
        },
        editable: true,
        eventEditable: true,
        selectable: true,
        select: function(info) {
            // Open availability modal when clicking empty space (faculty only - set availability)
            openAvailabilityModal(info.startStr);
        }
    });

    calendar.render();
});

function openAvailabilityModal(dateStr) {
    const modal = document.getElementById('addAvailabilityModal');
    const dateFromInput = document.getElementById('availabilityDateFrom');
    const dateToInput = document.getElementById('availabilityDateTo');

    if (dateStr) {
        // Extract just the date part (YYYY-MM-DD)
        const dateOnly = dateStr.substring(0, 10);
        dateFromInput.value = dateOnly;
        dateToInput.value = dateOnly; // Default to same day
    }

    modal.classList.remove('hidden');
}

function closeAvailabilityModal() {
    document.getElementById('addAvailabilityModal').classList.add('hidden');
    document.getElementById('addAvailabilityForm').reset();
}

// Handle availability form submission
document.getElementById('addAvailabilityForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const dateFrom = document.getElementById('availabilityDateFrom').value;
    const dateTo = document.getElementById('availabilityDateTo').value;
    
    // Validate date range
    if (new Date(dateFrom) > new Date(dateTo)) {
        showNotification('error', 'End date must be after or equal to start date');
        return;
    }

    const formData = new FormData();
    formData.append('date_from', dateFrom);
    formData.append('date_to', dateTo);
    formData.append('time_slot', document.getElementById('availabilityTimeSlot').value);
    formData.append('remarks', document.getElementById('availabilityRemarks').value);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch('{{ route("faculty.availability.store") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Check if response contains success message
        if (data.includes('success') || response.ok) {
            showNotification('success', 'Availability submitted for approval!');
            closeAvailabilityModal();
            // Reload page to see updated availability
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('error', 'Failed to submit availability');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Failed to submit availability');
    });
});

function showNotification(type, message) {
    // Use Livewire dispatch or a simple alert for now
    alert(message);
}
</script>

<style>
.fc {
    font-family: inherit;
}

.fc .fc-button-primary {
    background-color: #003599;
    border-color: #003599;
}

.fc .fc-button-primary:hover {
    background-color: #002d7a;
    border-color: #002d7a;
}

.fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: #003599;
    border-color: #003599;
}

.fc .fc-daygrid-day.fc-day-other {
    background-color: #f5f5f5;
}

.fc .fc-col-header-cell {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.fc .fc-daygrid-day:hover {
    background-color: #f9f9f9;
}

.fc .fc-event {
    cursor: pointer;
    transition: all 0.2s ease;
}

.fc .fc-event:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}
</style>
</div>
