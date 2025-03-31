@extends('layouts.app')

@section('pageTitle', 'Scheduled Appointments')

@section('content')
<div class="container">
    <h2 class="mb-4">Appointments Calendar</h2>

    <!-- Navigation + View Switcher -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <button class="btn btn-sm btn-outline-secondary" onclick="calendar.prev()">‹</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="calendar.today()">Today</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="calendar.next()">›</button>
        </div>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline-primary" onclick="switchView('dayGridMonth')">Month</button>
            <button class="btn btn-sm btn-outline-primary" onclick="switchView('timeGridWeek')">Week</button>
            <button class="btn btn-sm btn-outline-primary" onclick="switchView('timeGridDay')">Day</button>
        </div>
    </div>

    <!-- Calendar -->
    <div id="calendar"></div>

    <!-- Modal for Create/Edit -->
    <div id="eventModal" class="modal" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px; margin: auto;">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Create Appointment</h5>
                    <button type="button" class="btn-close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <input type="hidden" id="selectedDate">
                        <input type="hidden" id="editingEventId">
                        <div class="mb-2">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="eventTime" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Duration (minutes)</label>
                            <input type="number" class="form-control" id="eventDuration" value="60" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" id="eventDescription"></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-danger" id="deleteBtn" style="display:none;">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar + Script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
    let calendar;

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            editable: true,
            selectable: true,
            eventResizableFromStart: true,
            events: "{{ route('calendar.data') }}",
            headerToolbar: false,

            eventContent: function(arg) {
                const event = arg.event;
                const time = event.start ? event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const endTime = event.end ? event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const duration = endTime ? `${time} - ${endTime}` : time;

                let container = document.createElement('div');
                container.innerHTML = `<strong>${event.title}</strong><br>${duration}<br>${event.extendedProps.description || ''}`;
                return { domNodes: [container] };
            },

            dateClick: function(info) {
                resetForm();
                document.getElementById('selectedDate').value = info.dateStr;
                document.getElementById('modalTitle').innerText = 'Create Appointment';
                document.getElementById('eventModal').style.display = 'block';
            },

            eventClick: function(info) {
                const event = info.event;
                document.getElementById('editingEventId').value = event.id;
                document.getElementById('eventTitle').value = event.title;
                document.getElementById('eventDescription').value = event.extendedProps.description || '';
                document.getElementById('selectedDate').value = event.start.toISOString().substring(0, 10);
                document.getElementById('eventTime').value = event.start.toISOString().substring(11, 16);
                if (event.end) {
                    const duration = (new Date(event.end) - new Date(event.start)) / 60000;
                    document.getElementById('eventDuration').value = duration;
                }

                document.getElementById('modalTitle').innerText = 'Edit Appointment';
                document.getElementById('deleteBtn').style.display = 'inline-block';
                document.getElementById('eventModal').style.display = 'block';
            }
        });

        calendar.render();

        // Submit form (create or update)
        document.getElementById('eventForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const title = document.getElementById('eventTitle').value;
            const time = document.getElementById('eventTime').value;
            const duration = parseInt(document.getElementById('eventDuration').value, 10);
            const date = document.getElementById('selectedDate').value;
            const description = document.getElementById('eventDescription').value;
            const start = new Date(`${date}T${time}`);
            const end = new Date(start.getTime() + duration * 60000);

            fetch("{{ route('calendar.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    title: title,
                    start: start.toISOString(),
                    end: end.toISOString(),
                    description: description
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    calendar.addEvent({ title, start, end, description });
                    closeModal();
                } else {
                    alert('Failed to save appointment.');
                }
            })
            .catch(err => {
                alert("Failed to save appointment.");
                console.error(err);
            });
        });

        // Delete appointment from database
        document.getElementById('deleteBtn').addEventListener('click', function () {
            const id = document.getElementById('editingEventId').value;

            fetch(`/calendar/delete/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const event = calendar.getEventById(id);
                    if (event) event.remove();
                    closeModal();
                } else {
                    alert("Failed to delete appointment.");
                }
            })
            .catch(err => {
                alert("An error occurred while deleting the appointment.");
                console.error(err);
            });
        });
    });

    function switchView(view) {
        calendar.changeView(view);
    }

    function closeModal() {
        document.getElementById('eventModal').style.display = 'none';
        resetForm();
    }

    function resetForm() {
        document.getElementById('eventForm').reset();
        document.getElementById('editingEventId').value = '';
        document.getElementById('deleteBtn').style.display = 'none';
    }
</script>
@endsection
