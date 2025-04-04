@extends('layouts.app')

@section('pageTitle', 'Scheduled Appointments')

@section('content')
<div class="container">
    <h2 class="mb-4">Appointments Calendar</h2>

    <!-- Displays the current calendar month/year dynamically (e.g. March 2025) -->
    <div class="mb-3 text-center">
        <h4 id="calendarMonthYear" class="fw-bold"></h4>
    </div>

    <!-- Calendar Controls: Navigation and view toggle buttons -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <!-- Calendar navigation: previous, today, next -->
        <div>
            <button class="btn btn-sm btn-outline-secondary" onclick="calendar.prev(); updateMonthYear();">‹</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="calendar.today(); updateMonthYear();">Today</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="calendar.next(); updateMonthYear();">›</button>
        </div>

        <!-- View type switcher: toggles between monthly, weekly, daily views -->
        <div class="btn-group">
            <button class="btn btn-sm btn-outline-primary" onclick="switchView('dayGridMonth')">Month</button>
            <button class="btn btn-sm btn-outline-primary" onclick="switchView('timeGridWeek')">Week</button>
            <button class="btn btn-sm btn-outline-primary" onclick="switchView('timeGridDay')">Day</button>
        </div>
    </div>

    <!-- FullCalendar.js container -->
    <div id="calendar"></div>

    <!-- Modal for creating and editing appointments manually -->
    <div id="eventModal" class="modal" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px; margin: auto;">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Create Appointment</h5>
                    <button type="button" class="btn-close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <!-- Hidden fields for internal logic -->
                        <input type="hidden" id="selectedDate">
                        <input type="hidden" id="editingEventId">

                        <!-- Customer name input -->
                        <div class="mb-2">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>

                        <!-- Appointment start time -->
                        <div class="mb-2">
                            <label class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="eventTime" required>
                        </div>

                        <!-- Duration of appointment in minutes -->
                        <div class="mb-2">
                            <label class="form-label">Duration (minutes)</label>
                            <input type="number" class="form-control" id="eventDuration" value="60" required>
                        </div>

                        <!-- Optional message/notes field -->
                        <div class="mb-2">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" id="eventDescription"></textarea>
                        </div>

                        <!-- Save & delete buttons -->
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

<!-- Load FullCalendar styles and script -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
    let calendar;

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        // Initialize FullCalendar instance
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            editable: true,
            selectable: true,
            eventResizableFromStart: true,
            events: "{{ route('calendar.data') }}", // Pull events from backend
            headerToolbar: false, // Disable default header (we use our own)

            // Customize how events are displayed in each cell
            eventContent: function(arg) {
                const event = arg.event;
                const time = event.start ? event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const endTime = event.end ? event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const duration = endTime ? `${time} - ${endTime}` : time;

                let container = document.createElement('div');
                container.innerHTML = `<strong>${event.title}</strong><br>${duration}<br>${event.extendedProps.description || ''}`;
                return { domNodes: [container] };
            },

            // When a date is clicked (e.g. empty day on the calendar)
            dateClick: function(info) {
                resetForm();
                document.getElementById('selectedDate').value = info.dateStr;
                document.getElementById('modalTitle').innerText = 'Create Appointment';
                document.getElementById('eventModal').style.display = 'block';
            },

            // When an existing event is clicked (e.g. to edit or delete)
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
            },

            // Hook: update the month/year label on calendar navigation
            datesSet: function() {
                updateMonthYear();
            }
        });

        // Render the calendar
        calendar.render();
        updateMonthYear(); // Set initial month/year label

        // Handle form submission (create or update appointment)
        document.getElementById('eventForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Gather form values
            const title = document.getElementById('eventTitle').value;
            const time = document.getElementById('eventTime').value;
            const duration = parseInt(document.getElementById('eventDuration').value, 10);
            const date = document.getElementById('selectedDate').value;
            const description = document.getElementById('eventDescription').value;

            const start = new Date(`${date}T${time}`);
            const end = new Date(start.getTime() + duration * 60000); // 1 hour set for appointments bu default

            // POST request to backend to save appointment
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
                    // Optimistically add event to calendar UI
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

        // Delete button handler
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

    // Change the calendar view (month, week, day)
    function switchView(view) {
        calendar.changeView(view);
        updateMonthYear();
    }

    // Dynamically updates the header above the calendar with visible month/year
    function updateMonthYear() {
        const currentDate = calendar.getDate();
        const monthYear = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
        document.getElementById('calendarMonthYear').textContent = monthYear;
    }

    // Close modal and clear form
    function closeModal() {
        document.getElementById('eventModal').style.display = 'none';
        resetForm();
    }

    // Reset form fields and hide delete button
    function resetForm() {
        document.getElementById('eventForm').reset();
        document.getElementById('editingEventId').value = '';
        document.getElementById('deleteBtn').style.display = 'none';
    }
</script>
@endsection
