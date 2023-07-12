
@extends('layouts.pendaftar_akademik')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }

    .event-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .delete-btn-container {
        margin-left: auto;
    }

    

    
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Material Gallery</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Material Gallery</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-12 col-12">
                <div class="box">
                    <div class="box-body">
                        <h4 class="box-title mb-0 fw-500">Schedule</h4>	
                        <hr>
                        <div class="mb-4">
                         
                        </div>
                        <div class="box-footer">
                            <div id='event-creator'>
                                <input type='text' id='event-title' placeholder='Event title'>
                                <input type="datetime-local" id="event-start" name="start">
                                <button id='add-event'>Add Event</button>
                            </div>
                            <div id='calendar' style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->

    {{-- <div id="edit-event-modal" class="modal">
        <div class="modal-content">
          <label for="edit-event-title">Title:</label>
          <input type="text" id="edit-event-title" />
          <button id="save-edit-event">Save</button>
          <button id="close-edit-event-modal">Close</button>
        </div>
    </div> --}}

    <div id="edit-event-modal" class="modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- modal content-->
            <div class="modal-content" id="getModal">
                <div class="modal-header">
                    <div class="">
                        <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" id="close-edit-event-modal" data-dismiss="modal">
                            &times;
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                <div class="row col-md-12">
                    <div>
                        <div class="form-group">
                            <label for="edit-event-title">Title</label>
                            <input type="text" id="edit-event-title" class="form-control">
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="edit-start">Start</label>
                            <input type="datetime-local" class="form-control" id="edit-start" name="start">
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="edit-end">End</label>
                            <input type="datetime-local" class="form-control" id="edit-end" name="end">
                        </div>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group pull-right">
                        <div class="row">
                            <div class="col">
                                <button id="delete-edit-event" class="btn btn-danger btn-sm">Delete</button>
                            </div>
                            <div class="col">
                                <button id="save-edit-event" class="form-controlwaves-effect waves-light btn btn-primary btn-sm">Save</button>
                            </div>
                        </div>
                        {{-- <button id="close-edit-event-modal" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right">Close</button> --}}
                    </div>
                </div>
            </div>
        </div>
      </div>
    
    </div>
</div>
<!-- /.content-wrapper -->

<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/index.global.min.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.5/index.global.min.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.5/index.global.min.css' />
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.5/index.global.min.js'></script>

<script>
       document.addEventListener('DOMContentLoaded', function () {
        var options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: '',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            hiddenDays: [0, 6], // Hide Sunday (0) and Saturday (6)
            slotMinTime: '08:00:00', // Set the minimum visible time to 8 AM
            slotMaxTime: '17:00:00', // Set the maximum visible time to 5 PM (17:00)
            height: 'auto', // You can set this to 'auto' or a specific pixel value like '800px'
            aspectRatio: 1.35, // Set the width-to-height ratio for the calendar container
            allDaySlot: false, // Disable the all-day slot
            weekends: false, // Hide weekends
            events: '/AR/schedule/fetch', // Fetch events from the server
            editable: true,
            selectable: true,
            eventResizableFromStart: true, // Allow resizing event from the start
            durationEditable: true, // Enable duration editing by dragging the event's sides
            titleFormat: { // Customize the title format
                title: 'Lecturer Timetable',
                text: 'Lecturer Timetable'
            },
            dayHeaderFormat: { // Customize the day header format
                weekday: 'long'
            },
            eventClick: function (info) {
                const eventElement = info.el;
                if (eventElement.getAttribute('data-clicked') === 'true') {
                    openEditEventModal(info.event, calendar);
                } else {
                    eventElement.setAttribute('data-clicked', 'true');
                    setTimeout(() => {
                    eventElement.removeAttribute('data-clicked');
                    }, 300);
                }
            },

            // Add eventResize callback for updating event duration
            eventResize: async function (info) {
                var event = info.event;

                // Update event data
                var eventData = {
                    start: convertToPhpMyAdminDatetime(event.start),
                    end: event.end ? convertToPhpMyAdminDatetime(event.end) : null
                };

                // Send data to the backend
                const response = await fetch(`/AR/schedule/update/${event.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(eventData)
                });

                if (response.ok) {
                    alert('Event updated successfully');
                } else {
                    alert('Failed to update event');
                    info.revert();
                }
            },
        });

        calendar.render();

        // Add event button click listener
        document.getElementById('add-event').addEventListener('click', async function () {
            var eventTitle = document.getElementById('event-title').value.trim();
            var eventStart = convertToPhpMyAdminDatetime(new Date(document.getElementById('event-start').value));

            const slotMinTime = '08:00:00';
            const slotMaxTime = '17:00:00';

            const startHour = parseInt(eventStart.slice(11, 13));

            if (startHour < parseInt(slotMinTime.slice(0, 2)) || startHour > parseInt(slotMaxTime.slice(0, 2))) {
                alert('Error: Event start time is outside the allowed time range.');
                return;
            }

            if (eventTitle) {
                var currentDate = convertToPhpMyAdminDatetime(calendar.getDate());

                if(document.getElementById('event-start').value)
                {
                    var futureDate = convertToPhpMyAdminDatetime(new Date(new Date(document.getElementById('event-start').value).getTime() + 60 * 60000));
                }else{
                    var futureDate = convertToPhpMyAdminDatetime(new Date(calendar.getDate().getTime() + 60 * 60000));
                }

                var eventData = {
                    title: eventTitle,
                    start: (document.getElementById('event-start').value) ? eventStart : currentDate,
                    end: futureDate,
                    allDay: true
                };

                if(eventData.start != eventData.end)
                {

                    // Send data to the backend
                    const response = await fetch('/AR/schedule/create', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(eventData)
                    });

                    if (response.ok) {
                        const data = await response.json();
                        calendar.addEvent(data.event);
                        document.getElementById('event-title').value = ''; // Clear the input field
                        document.getElementById('event-start').value = ''; // Clear the input field
                        document.getElementById('event-end').value = ''; // Clear the input field
                        alert('Event added successfully.'); // Inform the user that the event was added
                    } else {
                        alert('Failed to add event.'); // Inform the user that adding the event failed
                    }

                }else{

                    alert('Please make sure start and end is not the same.');
                    
                }

            } else {
                alert('Please enter an event title.');
            }
        });

        // Event drop callback for updating event time
        calendar.setOption('eventDrop', async function (info) {
            var event = info.event;

            // Update event data
            var eventData = {
                start: convertToPhpMyAdminDatetime(event.start),
                end: event.end ? convertToPhpMyAdminDatetime(event.end) : null
            };

            // Send data to the backend
            const response = await fetch(`/AR/schedule/update/${event.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(eventData)
            });

            if (response.ok) {
                alert('Event updated successfully');
            } else {
                alert('Failed to update event');
                info.revert();
            }
        });

        async function handleEventDelete(event, calendar) {
            const response = await fetch('/AR/schedule/delete/' + event.id, {
                method: 'DELETE',
                headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            if (response.ok) {
                event.remove(); // Use remove() on the event object
            } else {
                alert('Error: Could not delete the event.');
            }
        }

        function openEditEventModal(event, calendar) {
            document.getElementById('edit-event-title').value = event.title;
            document.getElementById('edit-start').value = convertToPhpMyAdminDatetime(event.start);
            document.getElementById('edit-end').value = convertToPhpMyAdminDatetime(event.end);

            const saveButton = document.getElementById('save-edit-event');
            saveButton.onclick = function () {
                handleEventUpdate(event, calendar);
            };

            const deleteButton = document.getElementById('delete-edit-event');
            deleteButton.onclick = function () {
                handleEventDelete(event, calendar);
                closeModal();
            };

            const closeButton = document.getElementById('close-edit-event-modal');
            closeButton.onclick = function () {
                closeModal();
            };

            document.getElementById('edit-event-modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('edit-event-modal').style.display = 'none';
        }

        async function handleEventUpdate(event, calendar) {
            const newTitle = document.getElementById('edit-event-title').value;
            const newStart = document.getElementById('edit-start').value;
            const newEnd = document.getElementById('edit-end').value;

            const slotMinTime = '08:00:00';
            const slotMaxTime = '17:00:00';

            const startHour = parseInt(newStart.slice(11, 13));
            const endHour = parseInt(newEnd.slice(11, 13));

            if (startHour < parseInt(slotMinTime.slice(0, 2)) || endHour > parseInt(slotMaxTime.slice(0, 2))) {
                alert('Error: Event start or end time is outside the allowed time range.');
                return;
            }

            const response = await fetch('/AR/schedule/update2/' + event.id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    title: newTitle,
                    start: newStart,
                    end: newEnd
                }),
            });

            if (response.ok) {
                event.setProp('title', newTitle);
                event.setDates(newStart, newEnd);
                calendar.refetchEvents();
                closeModal();
            } else {
                alert('Error: Could not update the event.');
            }
        }


        function convertToPhpMyAdminDatetime(dateString) {
            // Parse the date string into a JavaScript Date object
            const dateObj = new Date(dateString);

            // Format the Date object into the PHPMyAdmin datetime format (YYYY-MM-DD HH:mm:ss)
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');
            const hours = String(dateObj.getHours()).padStart(2, '0');
            const minutes = String(dateObj.getMinutes()).padStart(2, '0');
            const seconds = String(dateObj.getSeconds()).padStart(2, '0');

            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

    });
    </script>

@stop