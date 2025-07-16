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
    /* Increase the height of the slots in FullCalendar */
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
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Log Timetable</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Log Timetable</li>
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
                            <h4 class="box-title mb-0 fw-500">Log Timetable</h4>
                            <hr>
                            <div class="mb-4">
                                <div class="box bg-success">
                                    <div class="box-body d-flex p-0">
                                        <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
                                            <div class="row">
                                                <div class="col-12 col-xl-12">
                                                    <!-- Display lecturer info -->
                                                    <h1 class="mb-0 fw-600">{{ $data['lecturerInfo']->name }}</h1>
                                                    <p class="my-10 fs-16"><strong>Ic : {{ $data['lecturerInfo']->ic }}</strong></p>
                                                    <p class="my-10 fs-16"><strong>Staff No. : {{ $data['lecturerInfo']->no_staf }}</strong></p>
                                                    <p class="my-10 fs-16"><strong>Email : {{ $data['lecturerInfo']->email }}</strong></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <h2 class="text-center">Last published on {{ date('d M Y', strtotime($data['time'])) }}</h2>
                                <div id='calendar' style="width: 100%;"></div>
                                <!-- Print Schedule Button -->
                                <div class="row mt-4">
                                    <div class="form-group pull-right">
                                        <button 
                                            id="print-schedule-btn" 
                                            class="btn btn-secondary"
                                            onclick="printScheduleTable(
                                                '{{ $data['lecturerInfo']->name }}',
                                                '{{ $data['lecturerInfo']->ic }}',
                                                '{{ $data['lecturerInfo']->no_staf }}',
                                                '{{ $data['lecturerInfo']->email }}',
                                                'lect'
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
        </section>
        <!-- /.content -->
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

<!-- Additional scripts (jQuery, Swal, etc.) should be included in your layout -->

<script>
    $(document).ready(function(){
        $("#collapsee").hide();
        $("#myButton").click(function(){
            $("#collapsee").slideToggle(500);
        });
    });
</script>

<script>
    function getRandomColor() {
        const colors = ['blue', 'green', 'purple', 'orange', 'pink', 'cyan', 'magenta', '#34ebc9', '#eb34df'];
        return colors[Math.floor(Math.random() * colors.length)];
    }
    var hiddenDays = [0, 6];
    
    // Declare calendar in the global scope
    var calendar;

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        // Initialize the global calendar variable
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
                fetch('/AR/schedule/log/{{ request()->id }}/fetch?idS={{ request()->idS }}')
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
                    info.el.appendChild(programDiv);
                }
            },
            editable: false,
            selectable: false,
            eventResizableFromStart: false,
            durationEditable: false,
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
    });
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
    } else {
        infoHtml = `
            <p style="margin:0;"><strong>${param1}</strong></p>
            <p style="margin:0;">IC: ${param2}</p>
            <p style="margin:0;">Staff No.: ${param3}</p>
            <p style="margin:0;">Email: ${param4}</p>
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
        
        // Special handling for break time (13:00-14:30) - use 15-minute intervals
        if (startHour === 13 && startMinute === 0) {
            // Add 15-minute intervals during break time
            times.push('13:15');
            times.push('13:30');
            times.push('13:45');
            times.push('14:00');
            times.push('14:15');
            times.push('14:30');
            // Jump to 15:00 (next 30-minute slot after break)
            startHour = 15;
            startMinute = 0;
        } else {
            // Regular 30-minute intervals
            startMinute += 30;
            if (startMinute === 60) {
                startMinute = 0;
                startHour++;
            }
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
