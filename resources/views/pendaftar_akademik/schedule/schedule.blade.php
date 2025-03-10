@extends('layouts.pendaftar_akademik')

@section('main')

<style>
    .cke_chrome {
        border: 1px solid #eee;
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

    /* This will increase the height of the slots in the FullCalendar */
    .fc-timegrid-slot {
        height: 60px !important; /* or whatever height you prefer */
    }

    /* This CSS rule makes sure that the height of the events match the slot height */
    .fc-timegrid-event {
        min-height: 1px !important; /* Adjust if you want a different minimum height */
    }

    /* Adjust the line height and vertical alignment of the slot labels */
    .fc-timegrid-slot-label .fc-timegrid-slot-label-cushion {
        /* This height should be the same as the slot height to ensure proper alignment. */
        height: 60px !important;
        line-height: 30px !important; 
        display: flex;
        align-items: start; 
    }

    .fc-event .program-info {
        text-align: center;
        font-size: smaller;
        font-weight: bold;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->	  
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Timetable</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Timetable</li>
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
                            <h4 class="box-title mb-0 fw-500">Room Timetable</h4>	
                            <hr>
                            <div class="mb-4">
                                <div class="box bg-success">
                                    <div class="box-body d-flex p-0">
                                        <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" 
                                             style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
                                            <div class="row">
                                                <div class="col-12 col-xl-12">
                                                    <h1 class="mb-0 fw-600">{{ $data['lecturerInfo']->name }}</h1>
                                                    <p class="my-10 fs-16"><strong>Ic : {{ $data['lecturerInfo']->ic }}</strong></p>
                                                    <p class="my-10 fs-16"><strong>Staff No. : {{ $data['lecturerInfo']->no_staf }}</strong></p>
                                                    <p class="my-10 fs-16"><strong>Email : {{ $data['lecturerInfo']->email }}</strong></p>
                                                    <div class="col-12 mt-45 d-md-flex align-items-center">
                                                        <div class="col-mx-4 me-30 mb-30 mb-md-0">
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-danger b-1 border-white rounded-circle">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </div>
                                                                <div>
                                                                    <h5 class="mb-0">Total Meeting Hours</h5>
                                                                    <p class="mb-0 text-white-70">{{ $data['details']->value('total_hour') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-mx-4 me-30 mb-30 mb-md-0">
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-primary b-1 border-white rounded-circle">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <div>
                                                                    <h5 class="mb-0">Meeting Hour Used</h5>
                                                                    <p class="mb-0 text-white-70">{{ $data['used']->value('total_hours') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-mx-4 me-30 mb-30 mb-md-0">
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-warning b-1 border-white rounded-circle">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </div>
                                                                <div>
                                                                    <h5 class="mb-0">Meeting Hour Left</h5>
                                                                    <p class="mb-0 text-white-70">
                                                                        {{ $data['details']->value('total_hour') - $data['used']->value('total_hours') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Additional info can go here if needed -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div id='event-creator'>
                                    <div class="row mt-3">
                                        <div class="col-md-4 mr-3">
                                            <div class="form-group">
                                                <label>Start Time</label>
                                                <input type="datetime-local" name="start" id="event-start" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4 mr-3">
                                            <div class="form-group">
                                                <label class="form-label" for="ses">Session</label>
                                                <select class="form-select" id="ses" name="ses">
                                                    <option value="-" selected>-</option>
                                                    @foreach($data['session'] as $ses)
                                                    <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mr-3">
                                            <div class="form-group">
                                                <label class="form-label" for="subject">Subject</label>
                                                <select class="form-select" id="subject" name="subject">
                                                    <option value="-" selected>-</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mr-3">
                                            <div class="form-group">
                                                <label class="form-label" for="group">Group</label>
                                                <select class="form-select" id="group" name="group">
                                                    <option value="-" selected>-</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4 mr-3">
                                            <div class="form-group">
                                                <label class="form-label" for="room">Room</label>
                                                <select class="form-select" id="room" name="room">
                                                    <option value="-" selected>-</option>
                                                    @foreach($data['lecture_room'] as $rm)
                                                    <option value="{{ $rm->id }}">{{ $rm->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group pull-right">
                                        <input type="submit" id="add-event" 
                                               class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" 
                                               value="Add Event">
                                    </div>
                                </div>
                                <h2 class="text-center">Last published on {{ date('d M Y', strtotime($data['time'])) }}</h2>
                                <div id='calendar' style="width: 100%;"></div>
                                <div class="row mt-4">
                                    <div class="form-group pull-right">
                                        <input type="submit" class="btn btn-primary pull-right" value="Publish" 
                                               style="margin-left: 10px;" id="publish-schedule">
                                        <input type="submit" class="btn btn-warning pull-right" value="Reset" 
                                               style="margin-left: 10px;" id="reset-schedule">
                                        <input type="submit" class="btn btn-info pull-right" value="Log" 
                                               style="margin-left: 10px;" id="log-schedule">
                                        <!-- Example button with inline script that passes lecturer data -->
                                        <button 
                                        id="print-schedule-btn" 
                                        class="btn btn-secondary"
                                        onclick="printScheduleTable(
                                        '{{ $data['lecturerInfo']->name }}',
                                        '{{ $data['lecturerInfo']->ic }}',
                                        '{{ $data['lecturerInfo']->no_staf }}',
                                        '{{ $data['lecturerInfo']->email }}'
                                        )"
                                        >
                                        Print Timetable
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Logged schedule table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <h4 class="box-title">Logged Timetable</h4>
                            <hr>
                            <div class="card-body">
                                <table id="complex_header" class="table table-striped projects display dataTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table">
                                        <!-- Filled by getLoggedSchedule() -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer"></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->

        <!-- Edit Event Modal -->
        <div id="edit-event-modal" class="modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- modal content-->
                <div class="modal-content" id="getModal">
                    <div class="modal-header">
                        <div class="">
                            <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" 
                                    id="close-edit-event-modal" data-dismiss="modal">
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
                                    <button id="save-edit-event" 
                                            class="form-controlwaves-effect waves-light btn btn-primary btn-sm">
                                        Save
                                    </button>
                                </div>
                            </div>
                            <!-- <button id="close-edit-event-modal" class="btn btn-primary btn-sm pull-right">Close</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Edit Event Modal -->

    </div>
</div>
<!-- /.content-wrapper -->

<!-- FullCalendar CSS/JS -->
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/index.global.min.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.5/index.global.min.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.5/index.global.min.css' />
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.5/index.global.min.js'></script>

<!-- Additional scripts (jQuery, Swal, etc.) should already be in your layout -->

<script>
    // 1. Document ready for some AJAX calls
    $(document).ready(function(){
        $("#collapsee").hide();
        $("#myButton").click(function(){
            $("#collapsee").slideToggle(500);
        });
    });

    // 2. Another document ready to fetch logged schedules
    $(document).ready(function() {
        getLoggedSchedule();
    });

    function getLoggedSchedule()
    {
        $.ajax({
            url: '/AR/schedule/log/{{ request()->id }}/getLoggedSchedule',
            type: "GET",
            dataType: "json",
            success: function(data) {
                $('#table').empty();
                var i = 1;
                $.each(data, function(key, value) {
                    $('#table').append(`
                        <tr>
                            <td>${i}</td>
                            <td>${value.date}</td>
                            <td>
                                <a href="/AR/schedule/log/{{ request()->id }}/view?idS=${value.date}" class="btn btn-primary btn-sm me-2">View</a>
                                <a onClick="deleteLog('${value.date}')" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    `);
                    i++;
                });
            }
        });
    }

    function deleteLog(id)
    {
        Swal.fire({
            title: "Are you sure?",
            text: "This will be permanent",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then(async function (res) {
            if (res.isConfirmed) {
                const response = await fetch('/AR/schedule/log/{{ request()->id }}/delete?idS=' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    } else {
                        Swal.fire('Success', data.success, 'success');
                        getLoggedSchedule();
                    }
                } else {
                    Swal.fire('Failed', 'Failed to reset schedule.', 'error');
                }
            }
        });
    }
</script>

<script>
    // On change of session => get subjects
    $('#ses').change(function() {
        var sessionID = $(this).val();
        if (sessionID) {
            $.ajax({
                url: '/AR/schedule/scheduleTable/{{ request()->id }}/getSubjectSchedule',
                type: "GET",
                dataType: "json",
                data: { sessionID: sessionID },
                success: function(data) {
                    $('#subject').empty();
                    $('#group').empty();
                    $('#subject').append('<option value="-" selected disabled>Select Subject</option>');
                    $.each(data, function(key, value) {
                        $('#subject').append(
                            `<option value="${value.id}|${value.Type}">${value.name}(${value.code})</option>`
                        );
                    });
                }
            });
        } else {
            $('#subject').empty().append('<option value="-" selected disabled>Select Subject</option>');
            $('#group').empty().append('<option value="-" selected disabled>Select Group</option>');
        }
    });

    // On change of subject => get groups
    $('#subject').change(function() {
        var groupID = $(this).val();
        if (groupID) {
            $.ajax({
                url: '/AR/schedule/scheduleTable/{{ request()->id }}/getGroupSchedule',
                type: "GET",
                dataType: "json",
                data: { groupID: groupID },
                success: function(data) {
                    $('#group').empty();
                    $('#group').append('<option value="-" selected disabled>Select Group</option>');
                    $.each(data, function(key, value) {
                        $('#group').append(
                            `<option value="${value.group_name}">${value.group_name}</option>`
                        );
                    });
                }
            });
        } else {
            $('#group').empty().append('<option value="-" selected disabled>Select Group</option>');
        }
    });

    // Helper function for random colors
    function getRandomColor() {
        const colors = ['blue', 'green', 'purple', 'orange', 'pink', 'cyan', 'magenta', '#34ebc9', '#eb34df'];
        return colors[Math.floor(Math.random() * colors.length)];
    }
</script>

<script>
    // Make calendar a global variable so we can access it in printScheduleTable
    var calendar;

    document.addEventListener('DOMContentLoaded', function () {
        // FullCalendar init
        var calendarEl = document.getElementById('calendar');
        var hiddenDays = [0, 6]; // Hide Sunday(0) & Saturday(6)

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: '',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            hiddenDays: hiddenDays,
            slotMinTime: '08:30:00',
            slotMaxTime: '18:00:00',
            slotDuration: '00:30:00',
            slotLabelInterval: '00:30:00',
            height: 'auto',
            aspectRatio: 1.35,
            allDaySlot: false,

            // events (including REHAT events) are combined below
            events: function(fetchInfo, successCallback, failureCallback) {
                // 1) Generate "REHAT" events
                var rehatEvents = [];
                var date = new Date(fetchInfo.start);
                while (date < fetchInfo.end) {
                    var dayOfWeek = date.getDay(); 
                    if (dayOfWeek >= 1 && dayOfWeek <= 4) {
                        // Monday-Thursday => 13:30 to 14:00
                        rehatEvents.push({
                            title: 'REHAT',
                            start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 13, 30, 0),
                            end: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 14, 0, 0),
                            allDay: false,
                            color: 'red'
                        });
                    } else if (dayOfWeek === 5) {
                        // Friday => 12:30 to 14:30
                        rehatEvents.push({
                            title: 'REHAT',
                            start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 12, 30, 0),
                            end: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 14, 30, 0),
                            allDay: false,
                            color: 'red'
                        });
                    }
                    date.setDate(date.getDate() + 1);
                }

                // 2) Fetch dynamic events
                fetch('/AR/schedule/fetch/{{ request()->id }}')
                    .then(response => response.json())
                    .then(data => {
                        const coloredEvents = data.map(event => ({
                            ...event,
                            color: getRandomColor()
                        }));
                        successCallback(rehatEvents.concat(coloredEvents));
                    })
                    .catch(error => {
                        console.error('Error fetching events:', error);
                        failureCallback(error);
                    });
            },

            // Show program info at bottom of event
            eventDidMount: function(info) {
                if (info.event.title !== 'REHAT') {
                    var programDiv = document.createElement('div');
                    programDiv.classList.add('program-info');
                    programDiv.style.position = 'absolute';
                    programDiv.style.bottom = '0';
                    programDiv.style.width = '100%';
                    programDiv.style.padding = '5px';
                    programDiv.textContent = 'Programs: ' + info.event.extendedProps.programInfo;
                    info.el.appendChild(programDiv);
                }
            },

            editable: true,
            selectable: true,
            eventResizableFromStart: true,
            durationEditable: true,
            titleFormat: {
                title: 'Lecturer Timetable',
                text: 'Lecturer Timetable'
            },
            dayHeaderFormat: {
                weekday: 'long'
            },

            // Customize event content
            eventContent: function(arg) {
                var titleElement = document.createElement('div');
                titleElement.classList.add('event-title');
                titleElement.style.fontWeight = 'bold';
                titleElement.textContent = arg.event.title;

                var timeElement = document.createElement('div');
                timeElement.classList.add('event-time');
                timeElement.textContent = arg.timeText; 

                var arrayOfDomNodes = [timeElement, titleElement];

                if (arg.event.extendedProps.description) {
                    var descriptionElement = document.createElement('div');
                    descriptionElement.textContent = arg.event.extendedProps.description;
                    arrayOfDomNodes.push(descriptionElement);
                }

                return { domNodes: arrayOfDomNodes };
            },

            // Click to edit or delete
            eventClick: function (info) {
                const eventElement = info.el;
                if (eventElement.getAttribute('data-clicked') === 'true') {
                    openEditEventModal(info.event);
                } else {
                    eventElement.setAttribute('data-clicked', 'true');
                    setTimeout(() => {
                        eventElement.removeAttribute('data-clicked');
                    }, 300);
                }
            },

            // Resize event
            eventResize: async function (info) {
                var event = info.event;
                var eventData = {
                    start: convertToPhpMyAdminDatetime(event.start),
                    end: event.end ? convertToPhpMyAdminDatetime(event.end) : null
                };

                const response = await fetch(`/AR/schedule/update/${event.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(eventData)
                });

                if (response.ok) {
                    const data = await response.json();
                    if(data.error) {
                        info.revert();
                        alert(data.error);
                    } else {
                        alert('Event updated successfully');
                    }
                } else {
                    alert('Failed to update event');
                    info.revert();
                }
            },

            // Drag start => highlight existing events
            eventDragStart: async function(info) {
                var event = info.event;
                const response = await fetch(`/AR/schedule/fetch/${event.id}/fetchExistEvent`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.error) {
                        info.revert();
                        alert(data.error);
                    } else {
                        data.forEach(fetchedEvent => {
                            const startTime = new Date();
                            startTime.setHours(...fetchedEvent.startTime.split(':'));

                            const endTime = new Date();
                            endTime.setHours(...fetchedEvent.endTime.split(':'));

                            const currentDay = startTime.getDay();
                            const targetDay = fetchedEvent.daysOfWeek[0]; 
                            const dayDifference = targetDay - currentDay;
                            startTime.setDate(startTime.getDate() + dayDifference);
                            endTime.setDate(endTime.getDate() + dayDifference);

                            var highlightEvent = {
                                id: 'highlight-' + fetchedEvent.id,
                                start: startTime,
                                end: endTime,
                                display: 'background',
                                backgroundColor: '#d3d3d3',
                                allDay: false
                            };
                            info.view.calendar.addEvent(highlightEvent);
                        });
                    }
                } else {
                    alert('Failed to fetch existing events');
                    info.revert();
                }
            },

            // Drag stop => remove highlight
            eventDragStop: function(info) {
                info.view.calendar.getEvents().forEach(e => {
                    if (e.id.startsWith('highlight-')) {
                        e.remove();
                    }
                });
            },

            // After drop => update
            eventDrop: async function(info) {
                var event = info.event;
                var eventData = {
                    start: convertToPhpMyAdminDatetime(event.start),
                    end: event.end ? convertToPhpMyAdminDatetime(event.end) : null
                };

                const response = await fetch(`/AR/schedule/update/${event.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(eventData)
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.error) {
                        info.revert();
                        alert(data.error);
                    } else {
                        alert('Event updated successfully');
                    }
                } else {
                    alert('Failed to update event');
                    info.revert();
                }

                // remove highlight events
                info.view.calendar.getEvents().forEach(e => {
                    if (e.id.startsWith('highlight-')) {
                        e.remove();
                    }
                });
            }
        });

        // Finally, render the calendar
        calendar.render();

        // Now set up the button click listeners that reference "calendar"

        // Add event
        document.getElementById('add-event').addEventListener('click', async function () {
            var session   = document.getElementById('ses').value;
            var combinedValue = document.getElementById('subject').value;
            var splitValues = combinedValue.split('|');

            var groupId = splitValues[0]; // This will contain the id
            var groupType = splitValues[1]; // This will contain the Type
            var groupName = document.getElementById('group').value;
            var roomId    = document.getElementById('room').value;
            
            var eventStart = convertToPhpMyAdminDatetime(
                new Date(document.getElementById('event-start').value)
            );

            const slotMinTime = '08:00:00';
            const slotMaxTime = '18:00:00';
            const startHour   = parseInt(eventStart.slice(11, 13));

            if (startHour < parseInt(slotMinTime.slice(0, 2)) || startHour > parseInt(slotMaxTime.slice(0, 2))) {
                alert('Error: Event start time is outside the allowed time range.');
                return;
            }

            if (session) {
                var currentDate = convertToPhpMyAdminDatetime(calendar.getDate());

                var futureDate;
                if (document.getElementById('event-start').value) {
                    futureDate = convertToPhpMyAdminDatetime(
                        new Date(new Date(document.getElementById('event-start').value).getTime() + 60 * 60000)
                    );
                } else {
                    futureDate = convertToPhpMyAdminDatetime(
                        new Date(calendar.getDate().getTime() + 60 * 60000)
                    );
                }

                var eventData = {
                    session: session,
                    groupId: groupId,
                    groupType: groupType,
                    groupName: groupName,
                    roomId: roomId,
                    start: (document.getElementById('event-start').value) ? eventStart : currentDate,
                    end: futureDate,
                    allDay: true
                };

                if(eventData.start != eventData.end) {
                    const response = await fetch('/AR/schedule/create/{{ request()->id }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(eventData)
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if(data.error) {
                            alert(data.error);
                        } else {
                            calendar.addEvent(data.event);
                            // Reset the fields
                            document.getElementById('ses').value = '';
                            document.getElementById('subject').value = '';
                            document.getElementById('group').value = '';
                            document.getElementById('room').value = '';
                            document.getElementById('event-start').value = '';
                            alert('Event added successfully.');
                        }
                    } else {
                        alert('Failed to add event.');
                    }
                } else {
                    alert('Please make sure start and end are not the same.');
                }
            } else {
                alert('Please select a session.');
            }
        });

        // Publish schedule
        document.getElementById('publish-schedule').addEventListener('click', async function () {
            var ic = '{{ $data['lecturerInfo']->ic }}';
            var eventData = { ic: ic };

            const response = await fetch('/AR/schedule/publish/{{ request()->id }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(eventData)
            });

            if (response.ok) {
                const data = await response.json();
                if(data.error) {
                    alert(data.error);
                } else {
                    alert(data.success);
                }
            } else {
                alert('Failed to publish event.');
            }
        });

        // Reset schedule
        document.getElementById('reset-schedule').addEventListener('click', async function () {
            var ic = '{{ $data['lecturerInfo']->ic }}';
            var eventData = { ic: ic };

            Swal.fire({
                title: "Are you sure?",
                text: "This will be permanent",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(async function (res) {
                if (res.isConfirmed) {
                    const response = await fetch('/AR/schedule/reset/{{ request()->id }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(eventData)
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.error) {
                            Swal.fire('Error', data.error, 'error');
                        } else {
                            Swal.fire('Success', data.success, 'success');
                            calendar.refetchEvents();
                        }
                    } else {
                        Swal.fire('Failed', 'Failed to reset schedule.', 'error');
                    }
                }
            });
        });

        // Log schedule
        document.getElementById('log-schedule').addEventListener('click', async function () {
            var ic = '{{ $data['lecturerInfo']->ic }}';
            var eventData = { ic: ic };

            const response = await fetch('/AR/schedule/log/{{ request()->id }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(eventData)
            });

            if (response.ok) {
                const data = await response.json();
                if(data.error) {
                    alert(data.error);
                } else {
                    alert(data.success);
                    getLoggedSchedule();
                }
            } else {
                alert('Failed to log event.');
            }
        });

        // // Print button
        // document.getElementById('print-schedule-btn').addEventListener('click', function() {
        //     printScheduleTable();
        // });
    });

    // --- Modal Handling (Edit/Delete) ---
    async function handleEventDelete(event) {
        const response = await fetch('/AR/schedule/delete/' + event.id, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });
        if (response.ok) {
            event.remove();
        } else {
            alert('Error: Could not delete the event.');
        }
    }

    function openEditEventModal(event) {
        document.getElementById('edit-event-title').value = event.title;
        document.getElementById('edit-start').value = convertToPhpMyAdminDatetime(event.start);
        document.getElementById('edit-end').value   = convertToPhpMyAdminDatetime(event.end);

        const saveButton   = document.getElementById('save-edit-event');
        const deleteButton = document.getElementById('delete-edit-event');
        const closeButton  = document.getElementById('close-edit-event-modal');

        saveButton.onclick   = function () { handleEventUpdate(event); };
        deleteButton.onclick = function () { handleEventDelete(event); closeModal(); };
        closeButton.onclick  = function () { closeModal(); };

        document.getElementById('edit-event-modal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('edit-event-modal').style.display = 'none';
    }

    async function handleEventUpdate(event) {
        const newTitle = document.getElementById('edit-event-title').value;
        const newStart = document.getElementById('edit-start').value;
        const newEnd   = document.getElementById('edit-end').value;

        const slotMinTime = '08:00:00';
        const slotMaxTime = '18:00:00';
        const startHour   = parseInt(newStart.slice(11, 13));
        const endHour     = parseInt(newEnd.slice(11, 13));

        if (startHour < parseInt(slotMinTime.slice(0, 2)) || endHour > parseInt(slotMaxTime.slice(0, 2))) {
            alert('Error: Event start or end time is outside the allowed time range.');
            return;
        }

        const response = await fetch('/AR/schedule/update2/' + event.id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                title: newTitle,
                start: newStart,
                end: newEnd
            }),
        });

        if (response.ok) {
            const data = await response.json();
            if(data.error) {
                alert(data.error);
            } else {
                event.setProp('title', newTitle);
                event.setDates(newStart, newEnd);
                calendar.render();
                closeModal();
                alert('Event updated successfully');
            }
        } else {
            alert('Failed to update event');
        }
    }

    // --- Convert to "YYYY-MM-DD HH:mm:ss" ---
    function convertToPhpMyAdminDatetime(dateString) {
        if(!dateString) return ''; // handle empty
        const dateObj = new Date(dateString);
        const year    = dateObj.getFullYear();
        const month   = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day     = String(dateObj.getDate()).padStart(2, '0');
        const hours   = String(dateObj.getHours()).padStart(2, '0');
        const minutes = String(dateObj.getMinutes()).padStart(2, '0');
        const seconds = String(dateObj.getSeconds()).padStart(2, '0');
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }
</script>

<!-- Print Schedule Script -->
<script>
function printScheduleTable(name, ic, staffNo, email) {
    const dayNames = ['Monday','Tuesday','Wednesday','Thursday','Friday'];

    // 1) Build half-hour time slots (08:30..18:00)
    let times = [];
    let startHour = 8;
    let startMinute = 30;
    let endHour = 18;

    while (startHour < endHour || (startHour === endHour && startMinute === 0)) {
        let hh = String(startHour).padStart(2, '0');
        let mm = String(startMinute).padStart(2, '0');
        times.push(`${hh}:${mm}`);
        startMinute += 30;
        if (startMinute === 60) {
            startMinute = 0;
            startHour++;
        }
    }

    // 2) Get events from FullCalendar
    const events = calendar.getEvents();

    // 3) Build a 2D array scheduleData[dayIndex][timeIndex] = event
    let scheduleData = [];
    for (let d = 0; d < dayNames.length; d++) {
        scheduleData[d] = new Array(times.length).fill(null);
    }

    events.forEach(event => {
        let start = event.start;
        let end   = event.end || new Date(start.getTime() + 60 * 60 * 1000);

        // Convert day-of-week (Mon=1..Fri=5 => index 0..4)
        let dayIndex = start.getDay() - 1; 
        if (dayIndex < 0 || dayIndex > 4) return; // skip Sat/Sun

        let startTimeStr = toHHMM(start);
        let endTimeStr   = toHHMM(end);

        let startIndex = times.indexOf(startTimeStr);
        if (startIndex === -1) return;

        let endIndex = times.indexOf(endTimeStr);
        if (endIndex === -1) endIndex = times.length;

        // Fill each half-hour slot with the same event reference
        for (let i = startIndex; i < endIndex; i++) {
            scheduleData[dayIndex][i] = event;
        }
    });

    // 4) We'll track cells to skip due to rowspan
    //    skip[d][t] = true means "don't print a cell for day d, timeslot t"
    let skip = [];
    for (let d = 0; d < dayNames.length; d++) {
        skip[d] = new Array(times.length).fill(false);
    }

    // 5) Build HTML
    let html = `
    <html>
    <head>
        <title>Print Schedule</title>
        <style>
            @page {
                size: A4 landscape;
                margin: 1cm;
            }
            @media print {
                html, body {
                    margin: 0; 
                    padding: 0;
                    font-family: Arial, sans-serif;
                }
                h2 {
                    margin: 0;
                    text-align: center;
                    font-size: 11px;
                    padding-bottom: 5px;
                }
                p {
                    margin: 0;
                    font-size: 10px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 4px;
                    text-align: center;
                    font-size: 9px; 
                }
                td b {
                    /* optional styling for time labels */
                }
            }
        </style>
    </head>
    <body>
        <h2>Lecturer Timetable</h2>
        <!-- Insert lecturer info here -->
        <div class="lecturer-info">
            <p style="margin:0;"><strong>${name}</strong></p>
            <p style="margin:0;">IC: ${ic}</p>
            <p style="margin:0;">Staff No.: ${staffNo}</p>
            <p style="margin:0;">Email: ${email}</p>
        </div>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Time</th>`;

    // Column headers for days
    dayNames.forEach(day => {
        html += `<th>${day}</th>`;
    });

    html += `</tr></thead><tbody>`;

    // 6) For each timeslot row
    for (let t = 0; t < times.length; t++) {
        // Build the time label, e.g. "08:30 - 09:00"
        let timeLabel = times[t];
        if (t < times.length - 1) {
            timeLabel += ' - ' + times[t + 1];
        } else {
            timeLabel += ' - END';
        }

        // Start a row
        html += `<tr>`;

        // Left column: time label
        html += `<td><b>${timeLabel}</b></td>`;

        // For each day column
        for (let d = 0; d < dayNames.length; d++) {
            // If this slot is marked skip => do nothing
            if (skip[d][t]) {
                continue; 
            }

            let event = scheduleData[d][t];
            if (event) {
                // 7) Count how many consecutive slots share the same event
                let rowSpan = 1;
                for (let k = t + 1; k < times.length; k++) {
                    if (scheduleData[d][k] === event) {
                        rowSpan++;
                    } else {
                        break;
                    }
                }

                // 8) Mark those future slots as skip
                for (let k = 1; k < rowSpan; k++) {
                    skip[d][t + k] = true;
                }

                // 9) Build event info
                let desc = '';
                if (event.extendedProps && event.extendedProps.description) {
                    desc = `<br><small>${event.extendedProps.description}</small>`;
                }

                // 10) Print a single cell with rowspan
                html += `<td rowspan="${rowSpan}">
                            ${event.title || '(No Title)'} 
                            ${desc}
                         </td>`;
            } else {
                // No event => just a normal empty cell
                html += `<td></td>`;
            }
        }

        // Close row
        html += `</tr>`;
    }

    html += `
            </tbody>
        </table>
    </body>
    </html>`;

    // 11) Open print window
    let printWindow = window.open('', '_blank', 'width=1100,height=800');
    printWindow.document.open();
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    // printWindow.close(); // optionally close
}

// Helper to convert Date -> "HH:MM"
function toHHMM(dateObj) {
    let hh = String(dateObj.getHours()).padStart(2, '0');
    let mm = String(dateObj.getMinutes()).padStart(2, '0');
    return hh + ':' + mm;
}

</script>

@stop
