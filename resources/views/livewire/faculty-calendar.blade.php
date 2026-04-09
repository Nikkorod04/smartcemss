<div wire:ignore.self>
    <!-- Calendar Container -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div id="calendar"></div>
    </div>

    <!-- Create Activity Modal -->
    <div id="createActivityModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md max-h-96 overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-lnu-blue">Create Activity</h2>
            </div>
            <form id="createActivityForm" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Activity Title</label>
                    <input type="text" id="activityTitle" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Program</label>
                    <select id="programSelect" name="extension_program_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" required>
                        <option value="">Select a program...</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Start Date</label>
                        <input type="datetime-local" id="activityStart" name="actual_start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">End Date</label>
                        <input type="datetime-local" id="activityEnd" name="actual_end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Venue</label>
                    <input type="text" id="activityVenue" name="venue" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Description</label>
                    <textarea id="activityDescription" name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-lnu-blue"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-lnu-blue text-white py-2 px-4 rounded-md hover:bg-blue-700 transition font-medium">
                        Create Activity
                    </button>
                    <button type="button" onclick="closeCreateModal()" class="flex-1 bg-gray-300 text-gray-900 py-2 px-4 rounded-md hover:bg-gray-400 transition font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
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
        selectConstraint: 'businessHours',
        editable: true,
        eventEditable: true,
        selectable: true,
        select: function(info) {
            // Open create activity modal when clicking empty space
            openCreateModal(info.startStr, info.endStr);
        }
    });

    calendar.render();
});

function openCreateModal(startStr, endStr) {
    const modal = document.getElementById('createActivityModal');
    const startInput = document.getElementById('activityStart');
    const endInput = document.getElementById('activityEnd');

    if (startStr) {
        startInput.value = startStr;
    }
    if (endStr) {
        endInput.value = endStr;
    }

    modal.classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createActivityModal').classList.add('hidden');
    document.getElementById('createActivityForm').reset();
}

// Handle form submission
document.getElementById('createActivityForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        title: document.getElementById('activityTitle').value,
        extension_program_id: document.getElementById('programSelect').value,
        actual_start_date: document.getElementById('activityStart').value.split('T')[0],
        actual_end_date: document.getElementById('activityEnd').value.split('T')[0],
        venue: document.getElementById('activityVenue').value,
        description: document.getElementById('activityDescription').value,
    };

    fetch('{{ route("api.calendar.activities.create") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            closeCreateModal();
            // Reload calendar events
            location.reload();
        } else {
            showNotification('error', data.error || 'Failed to create activity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Failed to create activity');
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
