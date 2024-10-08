
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
        /* If the slot height is 60px, then set this to 60px as well. */
        height: 60px !important;
        
        /* Line height should be less than the container height to push text up towards the top */
        line-height: 30px !important; /* Adjust this value as needed to align text */
        
        /* Since the text is inside a flex container, you might need to adjust alignment using flex properties */
        display: flex;
        align-items: start; /* This aligns the child elements (text) to the start (top) of the flex container */
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
                <h4 class="page-title">Log Schedule</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Log Schedule</li>
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
                        <h4 class="box-title mb-0 fw-500">Log Schedule</h4>	
                        <hr>
                        <div class="mb-4">
                            <div class="box bg-success">
                                <div class="box-body d-flex p-0">
                                    <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
                                        <div class="row">
                                            <div class="col-12 col-xl-12">
                                                <h1 class="mb-0 fw-600">{{ $data['lecturerInfo']->name }}</h1>
                                                <p class="my-10 fs-16"><strong>Ic : {{ $data['lecturerInfo']->ic }}</strong> </p>
                                                <p class="my-10 fs-16"><strong>Staff No. : {{ $data['lecturerInfo']->no_staf }}</strong> </p>
                                                <p class="my-10 fs-16"><strong>Staff No. : {{ $data['lecturerInfo']->email }}</strong> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <h2 class="text-center">Last published on {{ date('d M Y', strtotime($data['time'])) }}</h2>
                            <div id='calendar' style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
    $(document).ready(function(){
		$("#collapsee").hide();
		$("#myButton").click(function(){
			$("#collapsee").slideToggle(500);
		});
	});
</script>
<script>
        function getRandomColor() {
            const colors = ['blue', 'green', 'purple', 'orange', 'pink', 'cyan', 'magenta', '#34ebc9', '#eb34df']; // Example colors
            return colors[Math.floor(Math.random() * colors.length)];
        }

        // Define the hiddenDays variable based on the condition
        var hiddenDays;
        hiddenDays = [0, 6];

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
                hiddenDays: hiddenDays, // Hide Sunday (0) and Saturday (6)
                slotMinTime: '08:30:00', // Set the minimum visible time to 8 AM
                slotMaxTime: '18:00:00', // Set the maximum visible time to 5 PM (17:00)
                slotDuration: '00:30:00', // Sets the duration of each time slot, e.g., '00:30:00' for 30 minutes
                slotLabelInterval: '00:30:00', // Sets the interval at which time labels are displayed, e.g., '00:30:00' for every 30 minutes
                height: 'auto', // You can set this to 'auto' or a specific pixel value like '800px'
                aspectRatio: 1.35, // Set the width-to-height ratio for the calendar container
                allDaySlot: false, // Disable the all-day slot
                // events: '/AR/schedule/fetch/{{ request()->id }}', // Fetch events from the server

                events: function(fetchInfo, successCallback, failureCallback) {
                    // Fixed "REHAT" events
                    var rehatEvents = [];
                    var date = new Date(fetchInfo.start);
                    while (date < fetchInfo.end) {
                        var dayOfWeek = date.getDay(); // 0 = Sunday, 1 = Monday, ..., 5 = Friday, 6 = Saturday
                        
                        if (dayOfWeek >= 1 && dayOfWeek <= 4) { // Monday to Thursday
                            // Add REHAT event from 13:30 to 14:00
                            rehatEvents.push({
                                title: 'REHAT',
                                start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 13, 30, 0),
                                end: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 14, 0, 0),
                                allDay: false,
                                color: 'red'
                            });
                        } else if (dayOfWeek === 5) { // Friday
                            // Add REHAT event from 12:30 to 14:30
                            rehatEvents.push({
                                title: 'REHAT',
                                start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 12, 30, 0),
                                end: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 14, 30, 0),
                                allDay: false,
                                color: 'red'
                            });
                        }
                        
                        // Move to the next day
                        date.setDate(date.getDate() + 1);
                    }

                    // Fetch dynamic events from the server
                    fetch('/AR/schedule/log/{{ request()->id }}/fetch?idS={{ request()->idS }}')
                        .then(response => response.json())
                        .then(data => {
                            console.log('Fetched events:', data); // Log fetched events
                            // Assuming 'data' is an array of event objects
                            const coloredEvents = data.map(event => ({
                                ...event,
                                color: getRandomColor() // Assign a random color to each event
                            }));
                            successCallback(rehatEvents.concat(coloredEvents));
                        })
                        .catch(error => {
                            console.error('Error fetching events:', error);
                            failureCallback(error);
                        });
                },
                eventDidMount: function(info) {
                    // Check if the event title is 'REHAT'
                    if (info.event.title !== 'REHAT') {
                        // Create a div for the program information
                        var programDiv = document.createElement('div');
                        programDiv.classList.add('program-info');
                        programDiv.style.position = 'absolute';
                        programDiv.style.bottom = '0';
                        programDiv.style.width = '100%';
                        // programDiv.style.backgroundColor = 'rgba(255, 255, 255, 0.8)'; // optional styling
                        programDiv.style.padding = '5px'; // optional padding
                        programDiv.textContent = 'Programs: ' + info.event.extendedProps.programInfo;

                        // Append the program div to the event element
                        info.el.appendChild(programDiv);
                    }
                },
                editable: false,
                selectable: false,
                eventResizableFromStart: false, // Allow resizing event from the start
                durationEditable: false, // Enable duration editing by dragging the event's sides
                titleFormat: { // Customize the title format
                    title: 'Lecturer Timetable',
                    text: 'Lecturer Timetable'
                },
                dayHeaderFormat: { // Customize the day header format
                    weekday: 'long'
                },
                eventContent: function(arg) {
                    
                    // Create an HTML element for the event title
                    var titleElement = document.createElement('div');
                    titleElement.classList.add('event-title');
                    titleElement.style.fontWeight = 'bold';  // Set text to be bold
                    titleElement.textContent = arg.event.title;

                    // Create an HTML element for the event time
                    var timeElement = document.createElement('div');
                    timeElement.classList.add('event-time');
                    timeElement.textContent = arg.timeText; // Default time text, e.g., "9:00 - 12:00"

                    // Container for both elements
                    var arrayOfDomNodes = [timeElement, titleElement];
                    
                    // You can conditionally add elements or modify them based on the event's properties
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
                        const data = await response.json();
                        if(data.error)
                        {
                            info.revert();
                            alert(data.error);
                        }else{
                            alert('Event updated successfully');
                        }
                    } else {
                        alert('Failed to update event');
                        info.revert();
                    }
                },

                eventDragStart: async function(info) {
                    var event = info.event;

                    // Send data to the backend
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
                            // Iterate over each fetched event and create a background highlight event
                            data.forEach(fetchedEvent => {
                                // Create specific date objects for start and end time
                                const startTime = new Date();
                                startTime.setHours(...fetchedEvent.startTime.split(':'));
                                
                                const endTime = new Date();
                                endTime.setHours(...fetchedEvent.endTime.split(':'));
                                
                                // Adjust for the specific day of the week
                                const currentDay = startTime.getDay();
                                const targetDay = fetchedEvent.daysOfWeek[0]; // assuming daysOfWeek is an array with one element
                                
                                const dayDifference = targetDay - currentDay;
                                startTime.setDate(startTime.getDate() + dayDifference);
                                endTime.setDate(endTime.getDate() + dayDifference);

                                // Create and add the highlight event
                                var highlightEvent = {
                                    id: 'highlight-' + fetchedEvent.id,
                                    start: startTime,
                                    end: endTime,
                                    display: 'background',
                                    backgroundColor: '#d3d3d3', // Highlight color
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
                    // Remove all the temporary highlight events
                    info.view.calendar.getEvents().forEach(event => {
                        if (event.id.startsWith('highlight-')) {
                            event.remove();
                        }
                    });
                },


                eventDrop: async function(info) {
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

                    // Remove all the temporary highlight events after drop
                    info.view.calendar.getEvents().forEach(event => {
                        if (event.id.startsWith('highlight-')) {
                            event.remove();
                        }
                    });
                }

            });

            calendar.render();

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