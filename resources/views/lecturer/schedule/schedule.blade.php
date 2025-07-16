@extends('layouts.ketua_program')

@section('main')

<!-- Include the custom CSS -->
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --info: #4895ef;
        --warning: #f72585;
        --danger: #e63946;
        --light: #f8f9fa;
        --dark: #212529;
        --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        --transition-speed: 0.3s;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fa;
    }

    .content-wrapper {
        background-color: #f5f7fa;
    }

    .card, .box {
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: none;
        transition: transform var(--transition-speed), box-shadow var(--transition-speed);
        overflow: hidden;
    }

    .card:hover, .box:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2);
    }

    .box-title {
        font-weight: 700;
        color: #212529;
        position: relative;
        padding-bottom: 10px;
    }

    .box-title::after {
        content: '';
        position: absolute;
        width: 50px;
        height: 3px;
        background: linear-gradient(to right, var(--primary), var(--info));
        bottom: 0;
        left: 0;
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
    }

    /* Calendar specific styles */
    .fc-timegrid-slot {
        height: 60px !important;
    }

    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #e0e0e0;
    }

    .fc .fc-button-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .fc .fc-button-primary:hover {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }

    .fc .fc-col-header-cell-cushion {
        padding: 10px;
    }

    .fc-event {
        background-color: var(--primary);
        border-color: var(--primary);
        border-radius: 6px;
        transition: all var(--transition-speed);
        overflow: visible !important;
        display: flex;
        flex-direction: column;
    }

    .fc-event:hover {
        transform: scale(1.02);
    }

    .fc-h-event .fc-event-title-container {
        padding: 5px;
    }

    .fc-event-title {
        font-weight: bold;
        margin-bottom: 4px;
    }

    .event-time, .event-program, .event-lecturer {
        margin-top: 3px;
        font-size: 0.75rem;
    }

    /* Improved event content styling */
    .fc-event-main {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .event-title {
        font-weight: bold !important;
        font-size: 0.85rem;
        padding: 2px 0;
    }

    .event-time {
        font-size: 0.7rem;
        opacity: 0.9;
        font-weight: bold;
    }

    .event-description {
        font-size: 0.7rem;
        opacity: 0.8;
        white-space: normal;
        overflow: visible;
        margin-bottom: 5px;
    }

    /* Profile card at the top */
    .profile-card {
        background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
        color: white;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        margin-bottom: 20px;
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-position: right bottom;
        background-size: auto 100%;
        opacity: 0.2;
    }

    .profile-content {
        position: relative;
        z-index: 1;
        padding: 30px;
    }

    /* Calendar loading indicator */
    .calendar-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.8);
        padding: 15px;
        border-radius: 8px;
        z-index: 10;
        box-shadow: var(--card-shadow);
    }

    .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all var(--transition-speed);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
    }

    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background-color: var(--secondary);
        border-color: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    /* Notifications styling */
    .toast-notification {
        animation: slide-in 0.3s ease-out forwards;
    }

    @keyframes slide-in {
        0% {
            transform: translateY(-20px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<!-- Google Fonts - Poppins -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" />

<!-- FullCalendar CSS -->
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/index.global.min.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.5/index.global.min.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.5/index.global.min.css' />

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
                                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
                                <li class="breadcrumb-item" aria-current="page">Timetable</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
                <!-- Add html2pdf library -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xl-12 col-12">
                    <!-- Profile Card for Lecturer -->
                    <div class="profile-card">
                        <div class="profile-content">
                            <div class="row">
                                <div class="col-12 col-xl-6">
                                    <h1 class="mb-2 fw-700">Lecturer Timetable</h1>
                                    <p class="mb-0 fs-16">View your weekly class timetable</p>
                                </div>
                                <div class="col-12 col-xl-6 mt-4 mt-xl-0 d-flex justify-content-xl-end align-items-center">
                                    <div class="last-published">
                                        <span class="badge bg-info p-2">
                                            <i class="fas fa-sync me-1"></i>
                                            Last updated: {{ date('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Calendar Box -->
                    <div class="box mb-4">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="box-title mb-0 fw-700">Lecturer Timetable</h4>
                            </div>
                            <hr>
                            
                            <div id='calendar' style="width: 100%;"></div>
                            
                            <div class="action-buttons">
                                <button id="print-schedule-btn" class="btn btn-secondary">
                                    <i class="fas fa-print me-2"></i> Print Timetable
                                </button>
                                <button id="download-pdf-btn" class="btn btn-primary ms-2">
                                    <i class="fas fa-file-pdf me-2"></i> Download PDF
                                </button>
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

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.5/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.5/index.global.min.js'></script>

<!-- Custom JavaScript -->
<script>
    /**
     * Lecturer Schedule Management JavaScript
     * Enhanced with modern UX and animations
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips for better user guidance
        initializeTooltips();
        
        // Initialize the calendar
        setupCalendar();
        
        // Set up print button functionality
        // Set up print button with improved error handling
        const printBtn = document.getElementById('print-schedule-btn');
        if (printBtn) {
            printBtn.addEventListener('click', function() {
                // Show loading state in button
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Preparing...';
                this.disabled = true;
                
                // Slight delay to show the loading state
                setTimeout(() => {
                    try {
                        printSchedule();
                    } catch (error) {
                        console.error('Print error:', error);
                        showNotification('Print error: ' + error.message, 'error');
                    } finally {
                        // Restore button state
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                }, 500);
            });
        }
    
    // Set up PDF download button
    const pdfBtn = document.getElementById('download-pdf-btn');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', function() {
            // Show loading state in button
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating PDF...';
            this.disabled = true;
            
            // Use html2pdf library for PDF generation if available, or fallback
            if (typeof html2pdf !== 'undefined') {
                // Capture current calendar view
                const calendarEl = document.getElementById('calendar');
                
                // Get current month and year for the filename
                const date = new Date();
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                                   'July', 'August', 'September', 'October', 'November', 'December'];
                const month = monthNames[date.getMonth()];
                const year = date.getFullYear();
                
                // Configure html2pdf options
                const options = {
                    margin: 0.5,
                    filename: `Lecturer_Schedule_${month}_${year}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
                };
                
                // Generate PDF using the calendar element
                html2pdf().set(options).from(calendarEl).save().then(() => {
                    showNotification('PDF downloaded successfully', 'success');
                    // Restore button state
                    this.innerHTML = originalText;
                    this.disabled = false;
                }).catch(error => {
                    console.error('PDF generation error:', error);
                    showNotification('Error generating PDF: ' + error.message, 'error');
                    // Restore button state
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
            } else {
                // If html2pdf is not available, try alternative approach using the same HTML from printSchedule()
                try {
                    showNotification('Generating PDF using browser print function...', 'info');
                    
                    // Use the same HTML generation as in printSchedule()
                    const scheduleHtml = generateScheduleHTML();
                    
                    // Create a blob and download link
                    const blob = new Blob([scheduleHtml], { type: 'text/html' });
                    const url = URL.createObjectURL(blob);
                    
                    // Create and trigger download
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'Lecturer_Schedule.html';
                    document.body.appendChild(a);
                    a.click();
                    
                    // Clean up
                    setTimeout(() => {
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    }, 0);
                    
                    showNotification('HTML file downloaded. Please open it and use browser print function to create PDF', 'info', false);
                } catch (error) {
                    console.error('HTML download error:', error);
                    showNotification('Error generating document: ' + error.message, 'error');
                } finally {
                    // Restore button state
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            }
        });
    }
    });

    /**
     * Initialize tooltips for interactive elements
     */
    function initializeTooltips() {
        const addTooltip = (element, message) => {
            if (!element) return;
            
            element.addEventListener('mouseenter', (e) => {
                const tooltip = document.createElement('div');
                tooltip.className = 'custom-tooltip';
                tooltip.innerText = message;
                tooltip.style.position = 'absolute';
                tooltip.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
                tooltip.style.color = 'white';
                tooltip.style.padding = '5px 10px';
                tooltip.style.borderRadius = '4px';
                tooltip.style.fontSize = '0.8rem';
                tooltip.style.zIndex = '1000';
                tooltip.style.pointerEvents = 'none';
                tooltip.style.top = `${e.pageY - 30}px`;
                tooltip.style.left = `${e.pageX + 10}px`;
                document.body.appendChild(tooltip);
            });
            
            element.addEventListener('mouseleave', () => {
                const tooltips = document.querySelectorAll('.custom-tooltip');
                tooltips.forEach(tip => tip.remove());
            });
        };
        
        // Add tooltips to buttons
        addTooltip(document.getElementById('print-schedule-btn'), 'Print current timetable');
    }

    /**
     * Show notification for user feedback
     */
    function showNotification(message, type = 'info', autoHide = true) {
        // Create toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';
            toastContainer.style.position = 'fixed';
            toastContainer.style.top = '10px';
            toastContainer.style.left = '50%';
            toastContainer.style.transform = 'translateX(-50%)';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        // Generate a unique ID for this toast
        const toastId = 'toast-' + Date.now();
        
        // Create the toast HTML structure
        const toastHTML = `
            <div id="${toastId}" class="toast-notification toast-${type}" style="
                background-color: ${type === 'success' ? '#4cc9f0' : type === 'error' ? '#e63946' : type === 'warning' ? '#f72585' : '#4895ef'};
                color: #fff;
                padding: 12px 20px;
                margin-bottom: 10px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: flex-start;
                max-width: 400px;
                word-break: break-word;
                position: relative;
            ">
                <i class="fas ${
                    type === 'success' ? 'fa-check-circle' : 
                    type === 'error' ? 'fa-exclamation-triangle' : 
                    type === 'warning' ? 'fa-exclamation-circle' : 'fa-info-circle'
                } me-2" style="margin-top: 2px;"></i>
                <div style="flex: 1;">${message}</div>
                <button onclick="document.getElementById('${toastId}').remove();" style="
                    margin-left: 10px;
                    background: none;
                    border: none;
                    color: #fff;
                    font-size: 24px;
                    font-weight: bold;
                    cursor: pointer;
                    padding: 0 8px;
                    position: relative;
                    z-index: 10;
                ">&times;</button>
            </div>
        `;
        
        // Insert the toast into the container
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        
        // Get the newly created toast element
        const toast = document.getElementById(toastId);
        
        // Auto hide after 4 seconds
        if (autoHide) {
            setTimeout(() => {
                if (toast && toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 4000);
        }
        
        return toast;
    }

    /**
     * Generate random colors from modern palette
     */
    function getRandomColor() {
        // Modern color palette
        const colors = [
            '#4361ee', // Primary blue
            '#4cc9f0', // Light blue
            '#3a0ca3', // Deep purple
            '#7209b7', // Purple
            '#f72585', // Pink
            '#4895ef', // Sky blue
            '#560bad', // Dark purple
            '#b5179e', // Magenta
            '#3f37c9'  // Indigo
        ];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    // Calendar global variable
    var calendar;

    /**
     * Set up the FullCalendar
     */
    function setupCalendar() {
        var calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;
        
        // Define hidden days - here we show all days
        var hiddenDays = [5, 6]; // Hide Friday(5) & Saturday(6)

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Today',
                week: 'Week',
                day: 'Day'
            },
            hiddenDays: hiddenDays,
            slotMinTime: '07:00:00',
            slotMaxTime: '20:00:00',
            slotDuration: '00:30:00',
            slotLabelInterval: '00:30:00',
            height: 'auto',
            aspectRatio: 1.35,
            allDaySlot: false,
            slotLabelFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            nowIndicator: true,
            navLinks: true,
            dayMaxEvents: true,
            
            // Events fetching (REHAT + dynamic events)
            events: function(fetchInfo, successCallback, failureCallback) {
                // Show loading indicator
                $('#calendar').addClass('loading');
                $('<div class="calendar-loading"><i class="fas fa-circle-notch fa-spin"></i> Loading...</div>').appendTo('#calendar');
                
                // Generate "REHAT" events
                var rehatEvents = [];
                var date = new Date(fetchInfo.start);
                while (date < fetchInfo.end) {
                    var dayOfWeek = date.getDay(); 
                                    if (dayOfWeek >= 1 && dayOfWeek <= 4) {
                    // Monday-Thursday => 13:15 to 14:15
                    rehatEvents.push({
                        title: 'REHAT',
                        start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 13, 30, 0),
                        end: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 14, 0, 0),
                        allDay: false,
                        color: '#e63946',
                        textColor: '#ffffff',
                        borderColor: '#e63946'
                    });
                    } else if (dayOfWeek === 5) {
                        // Friday => 12:30 to 14:30
                        rehatEvents.push({
                            title: 'REHAT',
                            start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 12, 30, 0),
                            end: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 14, 30, 0),
                            allDay: false,
                            color: '#e63946',
                            textColor: '#ffffff',
                            borderColor: '#e63946'
                        });
                    }
                    date.setDate(date.getDate() + 1);
                }

                // Fetch dynamic events from the server
                fetch('/lecturer/class/schedule/fetch')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fetched events:', data); // Log fetched events
                        const coloredEvents = data.map(event => ({
                            ...event,
                            color: getRandomColor(),
                            textColor: '#ffffff',
                            borderColor: 'rgba(255,255,255,0.2)'
                        }));
                        
                        // Remove loading overlay
                        $('#calendar').removeClass('loading');
                        $('.calendar-loading').remove();
                        
                        successCallback(rehatEvents.concat(coloredEvents));
                    })
                    .catch(error => {
                        console.error('Error fetching events:', error);
                        
                        // Remove loading overlay
                        $('#calendar').removeClass('loading');
                        $('.calendar-loading').remove();
                        
                        // Show error notification
                        showNotification('Error loading calendar events: ' + error.message, 'error');
                        
                        failureCallback(error);
                    });
            },

            // Enhanced event styling
            eventDidMount: function(info) {
                // Special styling for REHAT events
                if (info.event.title === 'REHAT') {
                    info.el.style.backgroundColor = '#e63946';
                    info.el.style.color = 'white';
                }
            },

            editable: false, // Set to false as per original
            selectable: false, // Set to false as per original
            
            titleFormat: {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            },
            
            dayHeaderFormat: {
                weekday: 'long'
            },

            // Event content customization
            eventContent: function(arg) {
                // Create the main container
                var container = document.createElement('div');
                container.style.height = '100%';
                container.style.display = 'flex';
                container.style.flexDirection = 'column';
                container.style.padding = '2px';
                
                // Time element at the top
                var timeElement = document.createElement('div');
                timeElement.classList.add('event-time');
                timeElement.style.fontSize = '0.7rem';
                timeElement.style.opacity = '0.9';
                timeElement.style.fontWeight = 'bold';
                timeElement.textContent = arg.timeText;
                
                // Title element below time
                var titleElement = document.createElement('div');
                titleElement.classList.add('event-title');
                titleElement.style.fontWeight = 'bold';
                titleElement.style.fontSize = '0.85rem';
                titleElement.style.padding = '2px 0';
                titleElement.style.margin = '2px 0';
                titleElement.textContent = arg.event.title;
                
                // Add elements to container
                container.appendChild(timeElement);
                container.appendChild(titleElement);
                
                // Description (if available)
                if (arg.event.extendedProps.description) {
                    var descriptionElement = document.createElement('div');
                    descriptionElement.classList.add('event-description');
                    descriptionElement.style.fontSize = '0.7rem';
                    descriptionElement.style.opacity = '0.8';
                    descriptionElement.style.whiteSpace = 'normal';
                    descriptionElement.style.overflow = 'visible';
                    descriptionElement.style.marginBottom = '5px';
                    descriptionElement.textContent = arg.event.extendedProps.description;
                    container.appendChild(descriptionElement);
                }

                // Add program info directly in the content instead of appending later
                if (arg.event.title !== 'REHAT' && arg.event.extendedProps) {
                    // Program info
                    if (arg.event.extendedProps.programInfo) {
                        var programDiv = document.createElement('div');
                        programDiv.classList.add('event-program');
                        programDiv.style.fontSize = '0.7rem';
                        programDiv.style.padding = '2px 4px';
                        programDiv.style.marginTop = 'auto';
                        programDiv.style.backgroundColor = 'rgba(0, 0, 0, 0.1)';
                        programDiv.style.borderRadius = '3px';
                        programDiv.style.fontWeight = 'bold';
                        programDiv.textContent = 'Program: ' + arg.event.extendedProps.programInfo;
                        container.appendChild(programDiv);
                    }
                }
                
                return { domNodes: [container] };
            },

            // Event click handling
            eventClick: function (info) {
                const eventElement = info.el;
                if (eventElement.getAttribute('data-clicked') === 'true') {
                    // Show event details in a modal or tooltip
                    showEventDetails(info.event);
                } else {
                    eventElement.setAttribute('data-clicked', 'true');
                    setTimeout(() => {
                        eventElement.removeAttribute('data-clicked');
                    }, 300);
                }
            }
        });

        calendar.render();
    }

    /**
     * Show event details in a modal or tooltip
     */
     function showEventDetails(event) {
        // Create custom tooltip or use SweetAlert for a nice modal
        Swal.fire({
            title: event.title,
            html: `
                <div class="event-details">
                    <p><strong>Time:</strong> ${event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${event.end ? event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'N/A'}</p>
                    ${event.extendedProps.description ? `<p><strong>Description:</strong> ${event.extendedProps.description}</p>` : ''}
                    ${event.extendedProps.programInfo ? `<p><strong>Program:</strong> ${event.extendedProps.programInfo}</p>` : ''}
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Close',
            confirmButtonColor: '#4361ee'
        });
    }

    /**
     * Generate HTML for schedule
     * Extracted as a separate function so it can be used by both print and PDF functions
     */
     function generateScheduleHTML() {
    // Build days array
    const dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const hiddenDays = [4, 5]; // Hide Friday(4) & Saturday(5) in dayNames array (0-based index)

    // Build time slots with proper 15-minute intervals during lunch period
    let times = [];
    let currentHour = 7; // From 7:00 as per calendar config
    let currentMinute = 0;
    let endHour = 20; // Until 20:00 as per calendar config

    while (currentHour < endHour || (currentHour === endHour && currentMinute === 0)) {
        let hh = String(currentHour).padStart(2, '0');
        let mm = String(currentMinute).padStart(2, '0');
        times.push(`${hh}:${mm}`);
        
        // Special handling for 13:00-15:00 period (15-minute intervals)
        if (currentHour === 13 && currentMinute === 0) {
            // Add all 15-minute intervals from 13:00 to 15:00
            times.push('13:15');
            times.push('13:30');
            times.push('13:45');
            times.push('14:00');
            times.push('14:15');
            times.push('14:30');
            times.push('14:45');
            // Jump to 15:00 for next iteration
            currentHour = 15;
            currentMinute = 0;
        } else {
            // Regular 30-minute increment
            currentMinute += 30;
            if (currentMinute === 60) {
                currentMinute = 0;
                currentHour++;
            }
        }
    }

    // Get events from FullCalendar
    const events = calendar.getEvents();

    // Build a 2D array scheduleData[dayIndex][timeIndex] = [events]
    let scheduleData = [];
    for (let d = 0; d < 7; d++) { // 7 days of the week
        scheduleData[d] = [];
        for (let t = 0; t < times.length; t++) {
            scheduleData[d][t] = []; // Initialize with empty array
        }
    }

    // Helper function to convert Date to "HH:MM" format
    function toHHMM(dateObj) {
        let hh = String(dateObj.getHours()).padStart(2, '0');
        let mm = String(dateObj.getMinutes()).padStart(2, '0');
        return hh + ':' + mm;
    }

    // Helper function to find the appropriate time slot index for any time
    function findTimeSlotIndex(timeStr, times, isEndTime = false) {
        // First try exact match
        let exactIndex = times.indexOf(timeStr);
        if (exactIndex !== -1) {
            return exactIndex;
        }

        // Parse the time string
        let [hours, minutes] = timeStr.split(':').map(Number);
        let totalMinutes = hours * 60 + minutes;

        // Find the closest appropriate slot
        for (let i = 0; i < times.length; i++) {
            let [slotHours, slotMinutes] = times[i].split(':').map(Number);
            let slotTotalMinutes = slotHours * 60 + slotMinutes;
            
            if (isEndTime) {
                // For end times, find the slot that contains or comes after this time
                if (totalMinutes <= slotTotalMinutes) {
                    return i;
                }
            } else {
                // For start times, find the slot that contains this time or comes before
                if (totalMinutes <= slotTotalMinutes) {
                    return i;
                }
                
                // Check if time falls between current and next slot
                if (i < times.length - 1) {
                    let [nextSlotHours, nextSlotMinutes] = times[i + 1].split(':').map(Number);
                    let nextSlotTotalMinutes = nextSlotHours * 60 + nextSlotMinutes;
                    
                    if (totalMinutes > slotTotalMinutes && totalMinutes < nextSlotTotalMinutes) {
                        return i;
                    }
                }
            }
        }

        // Fallback: return appropriate boundary
        return isEndTime ? times.length : times.length - 1;
    }

    // Fill the scheduleData with events
    events.forEach(event => {
        let start = event.start;
        let end = event.end || new Date(start.getTime() + 60 * 60 * 1000);

        // Day of week (0=Sunday, 1=Monday, etc.)
        let dayIndex = start.getDay();
        if (dayIndex === 0) dayIndex = 6; // Move Sunday to the end (index 6)
        else dayIndex -= 1; // Adjust other days (Monday=0, Tuesday=1, etc.)

        let startTimeStr = toHHMM(start);
        let endTimeStr = toHHMM(end);

        // Find start and end indices - prioritize exact matches for REHAT events
        let startIndex = times.indexOf(startTimeStr);
        let endIndex = times.indexOf(endTimeStr);
        
        if (startIndex === -1) {
            startIndex = findTimeSlotIndex(startTimeStr, times, false);
        }
        if (endIndex === -1) {
            endIndex = findTimeSlotIndex(endTimeStr, times, true);
        }

        // For REHAT events, only place in the starting time slot to avoid duplication
        if (event.title === 'REHAT') {
            if (startIndex >= 0 && startIndex < times.length) {
                scheduleData[dayIndex][startIndex].push(event);
            }
        } else {
            // Fill each time slot with regular events
            for (let i = startIndex; i < endIndex && i < times.length; i++) {
                if (i >= 0) {
                    scheduleData[dayIndex][i].push(event);
                }
            }
        }
    });

    // Create processed tracking arrays
    let processedEvents = new Set();
    let skip = [];
    for (let d = 0; d < 7; d++) {
        skip[d] = new Array(times.length).fill(false);
    }

    // Add current date for footer
    const currentDate = new Date().toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Build HTML with modern styling (DECLARED ONCE)
    let html = `
        <html>
        <head>
            <title>Lecturer Timetable</title>
            <style>
                /* Control page breaks */
                @page {
                    size: A4 landscape;
                    margin: 0.5cm;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    margin: 0;
                    padding: 0;
                    color: #000000;
                    font-size: 9px;
                    font-weight: 500;
                }

                .container {
                    max-width: 100%;
                    margin: 0 auto;
                    padding: 10px;
                }

                .header {
                    text-align: center;
                    margin-bottom: 10px;
                    padding-bottom: 5px;
                    border-bottom: 1px solid #4361ee;
                }

                h1 {
                    color: #4361ee;
                    margin: 0;
                    font-size: 16px;
                    font-weight: 600;
                }

                .lecturer-info {
                    background-color: #f8f9fa;
                    border-radius: 4px;
                    padding: 5px;
                    margin-bottom: 10px;
                    border: 1px solid #e0e0e0;
                }

                .lecturer-info p {
                    margin: 2px 0;
                    font-size: 9px;
                    font-weight: 600;
                    color: #000000;
                }

                /* Table styling */
                table {
                    width: 100%;
                    border-collapse: collapse;
                    box-shadow: none;
                    border-radius: 0;
                    font-size: 8px;
                    page-break-inside: auto;
                }

                tr {
                    page-break-inside: avoid;
                    page-break-after: auto;
                }

                thead {
                    display: table-header-group;
                }

                tfoot {
                    display: table-footer-group;
                }

                th {
                    background-color: #1e40af;
                    color: white;
                    padding: 3px;
                    text-align: center;
                    font-weight: 700;
                    font-size: 9px;
                }

                th.time-column {
                    background-color: #1e40af;
                    color: white;
                    font-weight: 700;
                }

                td {
                    border: 1px solid #000000;
                    padding: 2px;
                    text-align: center;
                    vertical-align: middle;
                    background-color: #f8f8f8;
                    height: 20px; /* Slightly reduced height for mixed intervals */
                }

                .time-column {
                    background-color: #e0e0e0;
                    font-weight: 700;
                    color: #000000;
                    width: 60px;
                }

                .time-column.minor-slot {
                    background-color: #f0f0f0;
                    font-weight: 500;
                    font-size: 7px;
                    color: #666666;
                }

                .event-cell {
                    background-color: #d1e4ff;
                    border: 1.5px solid #000000;
                }

                .event-title {
                    font-weight: 700;
                    color: #000000;
                    margin-bottom: 1px;
                    font-size: 8px;
                }

                .event-description {
                    color: #333333;
                    font-size: 7px;
                    font-weight: 500;
                    line-height: 1.2;
                    margin: 1px 0;
                }

                .event-time-display {
                    color: #000000;
                    font-size: 7px;
                    font-weight: 600;
                    line-height: 1.2;
                    margin: 1px 0;
                }

                .rehat-cell {
                    background-color: #ffcccf;
                    border: 1.5px solid #000000;
                    color: #c62828;
                    font-weight: 700;
                }

                .multi-event-container {
                    display: flex;
                    flex-direction: column;
                    gap: 2px;
                }

                .event-divider {
                    border-top: 1px dashed #ccc;
                    margin: 1px 0;
                }

                .print-date {
                    text-align: right;
                    color: #999;
                    font-size: 8px;
                    margin-top: 5px;
                }

                footer {
                    text-align: center;
                    margin-top: 5px;
                    font-size: 8px;
                    color: #999;
                }

                /* Table footer for page number */
                .table-footer {
                    text-align: center;
                    font-size: 7px;
                    border: none !important;
                    background: transparent !important;
                }

                @media print {
                    body {
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }

                    .container {
                        padding: 0;
                    }

                    /* Ensure text is dark enough for printing */
                    * {
                        color: #000000 !important;
                    }

                    th {
                        background-color: #1e40af !important;
                        color: white !important;
                        font-weight: 800 !important;
                        border: 1px solid #000000 !important;
                    }

                    td {
                        background-color: #f8f8f8 !important;
                        border: 1px solid #000000 !important;
                    }

                    .time-column {
                        background-color: #e0e0e0 !important;
                        color: #000000 !important;
                        font-weight: 700 !important;
                    }

                    .time-column.minor-slot {
                        background-color: #f0f0f0 !important;
                        color: #666666 !important;
                        font-weight: 500 !important;
                        font-size: 7px !important;
                    }

                    .event-cell {
                        background-color: #d1e4ff !important;
                        border: 1.5px solid #000000 !important;
                    }

                    .rehat-cell {
                        background-color: #ffcccf !important;
                        color: #c62828 !important;
                        font-weight: 700 !important;
                        border: 1.5px solid #000000 !important;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Lecturer Timetable</h1>
                </div>

                <div class="lecturer-info">
                    <p><strong>Date Generated:</strong> ${currentDate}</p>
                </div>

                <table>
                    <!-- Table Header (repeats on each page) -->
                    <thead>
                        <tr>
                            <th class="time-column">Time</th>`;

    // Column headers for days (only show days that aren't hidden)
    const visibleDays = dayNames.filter((_, i) => !hiddenDays.includes(i));
    visibleDays.forEach(day => {
        html += `<th>${day}</th>`;
    });

    html += `</tr></thead>

                    <!-- Table Footer (repeats on each page) -->
                    <tfoot>
                        <tr>
                            <td colspan="${visibleDays.length + 1}" class="table-footer">
                                Lecturer Timetable - Generated on: ${currentDate}
                            </td>
                        </tr>
                    </tfoot>

                    <tbody>`;

    // For each timeslot row
    for (let t = 0; t < times.length; t++) {
        let currentTime = times[t];
        
        // Generate time label consistently
        let timeLabel = '';
        let isMinorSlot = false;
        
        // Check if this is a 15-minute interval during lunch period (13:00-15:00)
        let [hour, minute] = currentTime.split(':').map(Number);
        if (hour >= 13 && hour < 15) {
            // All slots in lunch period are treated as 15-minute intervals
            isMinorSlot = (minute === 15 || minute === 45);
        }
        
        // Generate time range label
        if (t + 1 < times.length) {
            timeLabel = currentTime + ' - ' + times[t + 1];
        } else {
            timeLabel = currentTime + ' - 20:00';
        }

        // Start a row
        html += `<tr>`;

        // Left column: time label with appropriate styling
        let timeColumnClass = isMinorSlot ? "time-column minor-slot" : "time-column";
        html += `<td class="${timeColumnClass}">${timeLabel}</td>`;

        // For each day column
        for (let d = 0; d < 7; d++) {
            // Skip if this day is hidden
            if (hiddenDays.includes(d)) continue;

            // If this slot is marked skip => do nothing
            if (skip[d][t]) {
                continue;
            }

            let eventList = scheduleData[d][t];

            if (eventList.length > 0) {
                // Check if there's a REHAT event in this cell
                let hasRehat = eventList.some(event => event.title === 'REHAT');

                // If there's a REHAT event, give it priority
                if (hasRehat) {
                    let rehatEvent = eventList.find(event => event.title === 'REHAT');

                    let start = rehatEvent.start;
                    let end = rehatEvent.end || new Date(start.getTime() + 60 * 60 * 1000);

                    let startTimeStr = toHHMM(start);
                    let endTimeStr = toHHMM(end);

                    // Find the exact time slots for start and end
                    let startIndex = times.indexOf(startTimeStr);
                    let endIndex = times.indexOf(endTimeStr);
                    
                    // If exact match not found, find closest slots
                    if (startIndex === -1) {
                        startIndex = findTimeSlotIndex(startTimeStr, times, false);
                    }
                    if (endIndex === -1) {
                        endIndex = findTimeSlotIndex(endTimeStr, times, true);
                    }

                    // Only create REHAT cell if this is the actual starting time slot
                    if (t === startIndex) {
                        let rowSpan = endIndex - startIndex;
                        
                        // Ensure minimum rowspan of 1
                        if (rowSpan < 1) {
                            rowSpan = 1;
                        }

                        // Mark future slots to skip
                        for (let k = 1; k < rowSpan; k++) {
                            if (t + k < times.length) {
                                skip[d][t + k] = true;
                            }
                        }

                        // Create cell with REHAT showing actual times
                        let rehatTimeDisplay = `${startTimeStr} - ${endTimeStr}`;
                        html += `<td rowspan="${rowSpan}" class="rehat-cell">
                                    <div class="event-title">REHAT</div>
                                    <div class="event-time-display">${rehatTimeDisplay}</div>
                                </td>`;
                    } else {
                        // This time slot is part of a REHAT that started earlier, skip it
                        continue;
                    }

                    // Skip processing other events in this cell
                    continue;
                }

                // Group non-REHAT events by their full time span
                let eventGroups = {};

                eventList.forEach(event => {
                    // Skip if we already processed this event
                    if (processedEvents.has(event.id)) return;

                    let start = event.start;
                    let end = event.end || new Date(start.getTime() + 60 * 60 * 1000);

                    let startTimeStr = toHHMM(start);
                    let endTimeStr = toHHMM(end);

                    let startIndex = findTimeSlotIndex(startTimeStr, times, false);
                    let endIndex = findTimeSlotIndex(endTimeStr, times, true);

                    // Create a unique key for this time span
                    let timeSpanKey = `${startIndex}-${endIndex}`;

                    // Initialize group if not exists
                    if (!eventGroups[timeSpanKey]) {
                        eventGroups[timeSpanKey] = {
                            events: [],
                            rowSpan: endIndex - startIndex
                        };
                    }

                    // Add event to the group
                    eventGroups[timeSpanKey].events.push(event);

                    // Mark event as processed
                    processedEvents.add(event.id);
                });

                // Get the keys sorted by start time
                let timeSpanKeys = Object.keys(eventGroups).sort();

                // Only process if we have groups and this is the starting row for a group
                if (timeSpanKeys.length > 0) {
                    let firstGroup = eventGroups[timeSpanKeys[0]];
                    let rowSpan = firstGroup.rowSpan;
                    let events = firstGroup.events;

                    // Mark future slots to skip
                    for (let k = 1; k < rowSpan; k++) {
                        if (t + k < times.length) {
                            skip[d][t + k] = true;
                        }
                    }

                    // Create cell with rowspan
                    html += `<td rowspan="${rowSpan}" class="event-cell">`;

                    // Start multi-event container if we have multiple events
                    if (events.length > 1) {
                        html += `<div class="multi-event-container">`;
                    }

                    // Add each event
                    events.forEach((event, index) => {
                        if (index > 0) {
                            html += `<div class="event-divider"></div>`;
                        }

                        // Title
                        html += `<div class="event-title">${event.title || '(No Title)'}</div>`;

                        // Add actual event times
                        let eventStart = event.start;
                        let eventEnd = event.end || new Date(eventStart.getTime() + 60 * 60 * 1000);
                        let eventTimeDisplay = `${toHHMM(eventStart)} - ${toHHMM(eventEnd)}`;
                        html += `<div class="event-time-display">${eventTimeDisplay}</div>`;

                        // Add description if available
                        if (event.extendedProps && event.extendedProps.description) {
                            html += `<div class="event-description">${event.extendedProps.description}</div>`;
                        }

                        // Add program info if available (only once, with special class)
                        if (event.extendedProps && event.extendedProps.programInfo) {
                            html += `<div class="event-description program-info">Program: ${event.extendedProps.programInfo}</div>`;
                        }
                    });

                    // Close multi-event container if needed
                    if (events.length > 1) {
                        html += `</div>`;
                    }

                    html += `</td>`;
                } else {
                    // No unprocessed events => empty cell
                    html += `<td></td>`;
                }
            } else {
                // No events => just a normal empty cell
                html += `<td></td>`;
            }
        }

        // Close row
        html += `</tr>`;
    }

    html += `
                    </tbody>
                </table>

                <footer>
                    © Timetable Management System
                </footer>
            </div>
        </body>
        </html>`;

    return html; // Return the generated HTML
}
    /**
    /**
     * Print the schedule
     */
     function printSchedule() {
        // Show loading notification
        showNotification('Preparing timetable for printing...', 'info');
        
        // Generate HTML using the extracted function
        const html = generateScheduleHTML();
        
        // Improved print function with better error handling
        try {
            // Create the print window
            let printWindow = window.open('', '_blank', 'width=1100,height=800');
            
            if (!printWindow) {
                // If window.open returns null, it was likely blocked by a popup blocker
                showNotification('Print window was blocked. Please allow popups for this site.', 'error', false);
                return;
            }
            
            // Write content to the window
            printWindow.document.open();
            printWindow.document.write(html);
            printWindow.document.close();
            
            // Add event listener to detect when the content is fully loaded
            printWindow.onload = function() {
                try {
                    // Focus on the window to bring it to front
                    printWindow.focus();
                    
                    // Trigger the print dialog
                    printWindow.print();
                    
                    // Show success notification
                    showNotification('Timetable printed successfully', 'success');
                    
                    // Close the print window after printing (optional)
                    // printWindow.close();
                } catch (printError) {
                    console.error('Print error:', printError);
                    showNotification('Error during printing: ' + printError.message, 'error', false);
                }
            };
            
            // Fallback in case onload doesn't trigger
            setTimeout(() => {
                if (printWindow.document.readyState === 'complete') {
                    if (!printWindow._printTriggered) {
                        printWindow._printTriggered = true;
                        printWindow.focus();
                        printWindow.print();
                        showNotification('Timetable printed successfully (fallback)', 'success');
                    }
                }
            }, 2000);
            
        } catch (error) {
            console.error('Print setup error:', error);
            showNotification('Error setting up print: ' + error.message, 'error', false);
        }
    }
    /**
     * Helper to convert Date -> "HH:MM"
     */
    function convertToPhpMyAdminDatetime(dateString) {
        if(!dateString) return ''; // handle empty
        const dateObj = new Date(dateString);
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');
        const hours = String(dateObj.getHours()).padStart(2, '0');
        const minutes = String(dateObj.getMinutes()).padStart(2, '0');
        const seconds = String(dateObj.getSeconds()).padStart(2, '0');
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }
</script>

@stop