@extends(isset(Auth::user()->usrtype) ? ((Auth::user()->usrtype == "AR") ? 'layouts.pendaftar_akademik' : '') : 'layouts.student')

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
    /* Increase the height of the slots in the FullCalendar */
    .fc-timegrid-slot {
        height: 60px !important;
    }
    /* Ensure events match the slot height */
    .fc-timegrid-event {
        min-height: 1px !important;
    }
    /* Align slot labels */
    .fc-timegrid-slot-label .fc-timegrid-slot-label-cushion {
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
    .fc-event .lecturer-info {
        text-align: center;
        font-size: smaller;
        font-weight: bold;
    }
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Timetable</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#"><i class="mdi mdi-home-outline"></i></a>
                                </li>
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
                                                    @if(request()->type == 'std')
                                                        <h1 class="mb-0 fw-600">{{ $data['studentInfo']->name }}</h1>
                                                        <p class="my-10 fs-16"><strong>Ic : {{ $data['studentInfo']->ic }}</strong></p>
                                                        <p class="my-10 fs-16"><strong>Matric No. : {{ $data['studentInfo']->no_matric }}</strong></p>
                                                        <p class="my-10 fs-16"><strong>Session : {{ $data['studentInfo']->session }}</strong></p>
                                                    @elseif(request()->type == 'lcr')
                                                        <h1 class="mb-0 fw-600">{{ $data['roomInfo']->name }}</h1>
                                                        <p class="my-10 fs-16"><strong>Time : {{ $data['roomInfo']->start }} - {{ $data['roomInfo']->end }}</strong></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div id='calendar' style="width: 100%;"></div>
                                <div class="row mt-4">
                                    <div class="form-group pull-right">
                                        <input type="submit" class="btn btn-primary pull-right" value="Publish" style="margin-left: 10px;" id="publish-schedule">
                                        <input type="submit" class="btn btn-warning pull-right" value="Reset" style="margin-left: 10px;" id="reset-schedule">
                                        <input type="submit" class="btn btn-info pull-right" value="Log" style="margin-left: 10px;" id="log-schedule">
                                        @if(request()->type == 'std')
                                            <button 
                                                id="print-schedule-btn" 
                                                class="btn btn-secondary"
                                                onclick="printScheduleTable(
                                                    '{{ $data['studentInfo']->name }}',
                                                    '{{ $data['studentInfo']->ic }}',
                                                    '{{ $data['studentInfo']->no_matric }}',
                                                    '{{ $data['studentInfo']->session }}',
                                                    'std'
                                                )"
                                            >
                                                Print Schedule
                                            </button>
                                        @elseif(request()->type == 'lcr')
                                            <button 
                                                id="print-schedule-btn" 
                                                class="btn btn-secondary"
                                                onclick="printScheduleTable(
                                                    '{{ $data['roomInfo']->name }}',
                                                    '{{ $data['roomInfo']->start }}',
                                                    '{{ $data['roomInfo']->end }}',
                                                    '',
                                                    'lcr'
                                                )"
                                            >
                                                Print Timetable
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->

        <!-- Edit Event Modal -->
        <div id="edit-event-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
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
    $(document).ready(function(){
		$("#collapsee").hide();
		$("#myButton").click(function(){
			$("#collapsee").slideToggle(500);
		});
	});
</script>

<script>
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
                        $('#subject').append('<option value="' + value.id + '">' + value.name + '(' + value.code + ')' + '</option>');
                    });
                }
            });
        } else {
            $('#subject').empty().append('<option value="-" selected disabled>Select Subject</option>');
            $('#group').empty().append('<option value="-" selected disabled>Select Group</option>');
        }
    });

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
                        $('#group').append('<option value="' + value.group_name + '">' + value.group_name + '</option>');
                    });
                }
            });
        } else {
            $('#group').empty().append('<option value="-" selected disabled>Select Group</option>');
        }
    });

    function getRandomColor() {
        const colors = ['blue', 'green', 'purple', 'orange', 'pink', 'cyan', 'magenta', '#34ebc9', '#eb34df'];
        return colors[Math.floor(Math.random() * colors.length)];
    }
</script>

<script>
    // Make calendar a global variable so we can access it in printScheduleTable
    var calendar;
    document.addEventListener('DOMContentLoaded', function () {
        var options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        var calendarEl = document.getElementById('calendar');
        var hiddenDays = [0, 6]; // Hide Sunday & Saturday
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
            events: function(fetchInfo, successCallback, failureCallback) {
                var rehatEvents = [];
                var date = new Date(fetchInfo.start);
                while (date < fetchInfo.end) {
                    var dayOfWeek = date.getDay();
                    if (dayOfWeek >= 1 && dayOfWeek <= 4) {
                        rehatEvents.push({
                            title: 'REHAT',
                            start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 13, 30, 0),
                            end: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 14, 0, 0),
                            allDay: false,
                            color: 'red'
                        });
                    } else if (dayOfWeek === 5) {
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
                fetch('/AR/schedule/fetch/{{ request()->id }}?type={{ request()->type }}')
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
            eventDidMount: function(info) {
                if (info.event.title !== 'REHAT') {
                    var programDiv = document.createElement('div');
                    programDiv.classList.add('program-info');
                    programDiv.style.position = 'absolute';
                    programDiv.style.bottom = '0';
                    programDiv.style.width = '100%';
                    programDiv.style.padding = '5px';
                    programDiv.textContent = 'Programs: ' + info.event.extendedProps.programInfo;
                    var lectDiv = document.createElement('div');
                    lectDiv.classList.add('lecturer-info');
                    lectDiv.style.position = 'absolute';
                    lectDiv.style.bottom = '15px';
                    lectDiv.style.width = '100%';
                    lectDiv.style.padding = '5px';
                    lectDiv.textContent = 'Lecturer: ' + info.event.extendedProps.lectInfo;
                    info.el.appendChild(programDiv);
                    info.el.appendChild(lectDiv);
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
            eventDragStop: function(info) {
                info.view.calendar.getEvents().forEach(e => {
                    if (e.id.startsWith('highlight-')) {
                        e.remove();
                    }
                });
            },
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
                info.view.calendar.getEvents().forEach(e => {
                    if (e.id.startsWith('highlight-')) {
                        e.remove();
                    }
                });
            }
        });
        calendar.render();
        // Add event listeners for add/publish/reset/log
        document.getElementById('add-event').addEventListener('click', async function () {
            var session = document.getElementById('ses').value;
            var groupId = document.getElementById('subject').value;
            var groupName = document.getElementById('group').value;
            var roomId = document.getElementById('room').value;
            var eventStart = convertToPhpMyAdminDatetime(new Date(document.getElementById('event-start').value));
            const slotMinTime = '08:30:00';
            const slotMaxTime = '18:00:00';
            const startHour = parseInt(eventStart.slice(11, 13));
            if (startHour < parseInt(slotMinTime.slice(0, 2)) || startHour > parseInt(slotMaxTime.slice(0, 2))) {
                alert('Error: Event start time is outside the allowed time range.');
                return;
            }
            if (session) {
                var currentDate = convertToPhpMyAdminDatetime(calendar.getDate());
                var futureDate;
                if(document.getElementById('event-start').value) {
                    futureDate = convertToPhpMyAdminDatetime(new Date(new Date(document.getElementById('event-start').value).getTime() + 60 * 60000));
                } else {
                    futureDate = convertToPhpMyAdminDatetime(new Date(calendar.getDate().getTime() + 60 * 60000));
                }
                var eventData = {
                    session: session,
                    groupId: groupId,
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
        // For publish/reset/log, use conditionals for ic if needed
        @if(request()->type == 'std')
            var ic = '{{ $data["studentInfo"]->ic }}';
        @elseif(request()->type == 'lcr')
            var ic = '{{ $data["roomInfo"]->ic ?? "" }}';
        @endif
        document.getElementById('publish-schedule').addEventListener('click', async function () {
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
        document.getElementById('reset-schedule').addEventListener('click', async function () {
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
        document.getElementById('log-schedule').addEventListener('click', async function () {
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
    });

    async function handleEventDelete(event, calendar) {
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

    function openEditEventModal(event, calendar) {
        document.getElementById('edit-event-title').value = event.title;
        document.getElementById('edit-start').value = convertToPhpMyAdminDatetime(event.start);
        document.getElementById('edit-end').value = convertToPhpMyAdminDatetime(event.end);
        const saveButton = document.getElementById('save-edit-event');
        saveButton.onclick = function () { handleEventUpdate(event, calendar); };
        const deleteButton = document.getElementById('delete-edit-event');
        deleteButton.onclick = function () { handleEventDelete(event, calendar); closeModal(); };
        const closeButton = document.getElementById('close-edit-event-modal');
        closeButton.onclick = function () { closeModal(); };
        document.getElementById('edit-event-modal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('edit-event-modal').style.display = 'none';
    }

    async function handleEventUpdate(event, calendar) {
        const newTitle = document.getElementById('edit-event-title').value;
        const newStart = document.getElementById('edit-start').value;
        const newEnd = document.getElementById('edit-end').value;
        const slotMinTime = '08:30:00';
        const slotMaxTime = '18:00:00';
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

    function convertToPhpMyAdminDatetime(dateString) {
        const dateObj = new Date(dateString);
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');
        const hours = String(dateObj.getHours()).padStart(2, '0');
        const minutes = String(dateObj.getMinutes()).padStart(2, '0');
        const seconds = String(dateObj.getSeconds()).padStart(2, '0');
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    function getLoggedSchedule() {
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

    function deleteLog(id) {
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

<!-- Print Schedule Script with Rowspan Merging and Header Info -->
<script>
function printScheduleTable(param1, param2, param3, param4, type) {
    let infoHtml = '';
    if (type === 'std') {
        infoHtml = `
            <p style="margin:0;"><strong>${param1}</strong></p>
            <p style="margin:0;">IC: ${param2}</p>
            <p style="margin:0;">Matric No.: ${param3}</p>
            <p style="margin:0;">Session: ${param4}</p>
        `;
    } else if (type === 'lcr') {
        infoHtml = `
            <p style="margin:0;"><strong>${param1}</strong></p>
            <p style="margin:0;">Time: ${param2} - ${param3}</p>
        `;
    }
    const dayNames = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
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
    const events = calendar.getEvents();
    let scheduleData = [];
    for (let d = 0; d < dayNames.length; d++) {
        scheduleData[d] = new Array(times.length).fill(null);
    }
    events.forEach(event => {
        let start = event.start;
        let end = event.end || new Date(start.getTime() + 60 * 60 * 1000);
        let dayIndex = start.getDay() - 1;
        if (dayIndex < 0 || dayIndex > 4) return;
        let startTimeStr = toHHMM(start);
        let endTimeStr = toHHMM(end);
        let startIndex = times.indexOf(startTimeStr);
        if (startIndex === -1) return;
        let endIndex = times.indexOf(endTimeStr);
        if (endIndex === -1) endIndex = times.length;
        for (let i = startIndex; i < endIndex; i++) {
            scheduleData[dayIndex][i] = event;
        }
    });
    let skip = [];
    for (let d = 0; d < dayNames.length; d++) {
        skip[d] = new Array(times.length).fill(false);
    }
    let html = `
    <html>
    <head>
        <title>Print Timetable</title>
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
                .lecturer-info {
                    text-align: center;
                    margin-bottom: 5px;
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
            }
        </style>
    </head>
    <body>
        <h2>Timetable</h2>
        <div class="lecturer-info">
            ${infoHtml}
        </div>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Time</th>`;
    dayNames.forEach(day => {
        html += `<th>${day}</th>`;
    });
    html += `</tr></thead><tbody>`;
    for (let t = 0; t < times.length; t++) {
        let timeLabel = times[t];
        if (t < times.length - 1) {
            timeLabel += ' - ' + times[t + 1];
        } else {
            timeLabel += ' - END';
        }
        html += `<tr><td><b>${timeLabel}</b></td>`;
        for (let d = 0; d < dayNames.length; d++) {
            if (skip[d][t]) continue;
            let event = scheduleData[d][t];
            if (event) {
                let rowSpan = 1;
                for (let k = t + 1; k < times.length; k++) {
                    if (scheduleData[d][k] === event) {
                        rowSpan++;
                    } else {
                        break;
                    }
                }
                for (let k = 1; k < rowSpan; k++) {
                    skip[d][t + k] = true;
                }
                let desc = '';
                if (event.extendedProps && event.extendedProps.description) {
                    desc = `<br><small>${event.extendedProps.description}</small>`;
                }
                html += `<td rowspan="${rowSpan}">
                            ${event.title || '(No Title)'}
                            ${desc}
                         </td>`;
            } else {
                html += `<td></td>`;
            }
        }
        html += `</tr>`;
    }
    html += `</tbody></table></body></html>`;
    let printWindow = window.open('', '_blank', 'width=1100,height=800');
    printWindow.document.open();
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
function toHHMM(dateObj) {
    let hh = String(dateObj.getHours()).padStart(2, '0');
    let mm = String(dateObj.getMinutes()).padStart(2, '0');
    return hh + ':' + mm;
}
</script>

@stop
