<x-admin-layout header="System Calendar">
    <div wire:ignore.self>
        <!-- Calendar Container -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div id="calendar"></div>
        </div>

        <!-- Legend -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-lg">
                <div class="w-4 h-4 rounded" style="background-color: #003599;"></div>
                <span class="text-sm font-medium text-gray-900">Programs (Planned Dates)</span>
            </div>
            <div class="flex items-center gap-2 p-3 bg-green-50 rounded-lg">
                <div class="w-4 h-4 rounded" style="background-color: #28a745;"></div>
                <span class="text-sm font-medium text-gray-900">Activities (Actual Dates)</span>
            </div>
            <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                <div class="w-4 h-4 rounded border border-gray-400"></div>
                <span class="text-sm font-medium text-gray-900">Faculty Availability</span>
            </div>
        </div>
    </div>
</x-admin-layout>

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
            // Navigate to event if link is available
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
                        alert(data.message);
                    } else {
                        alert(data.error || 'Failed to update dates');
                        info.revert();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update dates');
                    info.revert();
                });
            }
        },
        editable: true,
        eventEditable: true
    });

    calendar.render();
});
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
