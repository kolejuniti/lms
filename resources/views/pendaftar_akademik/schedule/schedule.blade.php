@extends('layouts.pendaftar_akademik')

@section('main')

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

    .profile-card {
        background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
        color: white;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: url('images/svg-icon/color-svg/custom-30.svg');
        background-position: right bottom;
        background-size: auto 100%;
        opacity: 0.2;
    }

    .profile-content {
        position: relative;
        z-index: 1;
        padding: 30px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        transition: all var(--transition-speed);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .stat-card:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.2rem;
        margin-right: 15px;
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

    .btn-warning {
        background-color: var(--warning);
        border-color: var(--warning);
    }

    .btn-warning:hover {
        background-color: #d1214c;
        border-color: #d1214c;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 37, 133, 0.4);
    }

    .btn-info {
        background-color: var(--info);
        border-color: var(--info);
    }

    .btn-info:hover {
        background-color: #3572b8;
        border-color: #3572b8;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(72, 149, 239, 0.4);
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        background-color: #f8f9fa;
        transition: all var(--transition-speed);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        background-color: #fff;
    }

    label {
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .card-title, .box-title {
        font-weight: 700;
        color: #212529;
        position: relative;
        padding-bottom: 10px;
    }

    .card-title::after, .box-title::after {
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
    }

    .fc-event:hover {
        transform: scale(1.02);
    }

    .fc-h-event .fc-event-title-container {
        padding: 5px;
    }

    .fc-event .program-info {
        text-align: center;
        font-size: smaller;
        font-weight: bold;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table th, .table td {
        border: none;
        padding: 12px 15px;
        vertical-align: middle;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        transition: all var(--transition-speed);
    }

    .table tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: var(--card-shadow);
    }

    .modal-header {
        background-color: var(--primary);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 15px 20px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #e0e0e0;
    }

    .event-form {
        background-color: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
    }

    .event-form-title {
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--primary);
    }

    /* Tooltip styling */
    .custom-tooltip {
        position: absolute;
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        z-index: 1000;
        pointer-events: none;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: var(--danger);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 767px) {
        .action-buttons {
            justify-content: center;
        }
        
        .stat-cards-container {
            flex-direction: column;
        }
        
        .event-form {
            padding: 15px;
        }
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->	  
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Timetable Management</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i> Home</a></li>
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
                    <!-- Lecturer Profile Card -->
                    <div class="profile-card mb-4">
                        <div class="profile-content">
                            <div class="row">
                                <div class="col-12 col-xl-6">
                                    <h1 class="mb-2 fw-700">{{ $data['lecturerInfo']->name }}</h1>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-id-card me-2"></i>
                                        <p class="mb-0 fs-16"><strong>IC:</strong> {{ $data['lecturerInfo']->ic }}</p>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-user-tie me-2"></i>
                                        <p class="mb-0 fs-16"><strong>Staff No:</strong> {{ $data['lecturerInfo']->no_staf }}</p>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-envelope me-2"></i>
                                        <p class="mb-0 fs-16"><strong>Email:</strong> {{ $data['lecturerInfo']->email }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-6 mt-4 mt-xl-0">
                                    <div class="d-flex flex-wrap justify-content-between stat-cards-container">
                                        <div class="stat-card d-flex align-items-center">
                                            <div class="stat-icon bg-danger text-white">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">Total Hours</h5>
                                                <p class="mb-0 text-white-70 fs-18 fw-bold">{{ $data['details']->value('total_hour') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="stat-card d-flex align-items-center">
                                            <div class="stat-icon bg-primary text-white">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">Hours Used</h5>
                                                <p class="mb-0 text-white-70 fs-18 fw-bold">{{ $data['used']->value('total_hours') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="stat-card d-flex align-items-center">
                                            <div class="stat-icon bg-warning text-white">
                                                <i class="fa fa-hourglass-half"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">Hours Left</h5>
                                                <p class="mb-0 text-white-70 fs-18 fw-bold">
                                                    {{ $data['details']->value('total_hour') - $data['used']->value('total_hours') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event Creator Form -->
                    <div class="box mb-4" id="event-creator">
                        <div class="box-body">
                            <h5 class="box-title">
                                <i class="fas fa-plus-circle me-2"></i> Add New Schedule
                            </h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="event-start">
                                            <i class="fas fa-clock me-2"></i>Start Time
                                        </label>
                                        <input type="datetime-local" name="start" id="event-start" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="ses">
                                            <i class="fas fa-calendar-alt me-2"></i>Session
                                        </label>
                                        <select class="form-select" id="ses" name="ses">
                                            <option value="-" selected>Select Session</option>
                                            @foreach($data['session'] as $ses)
                                            <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="subject">
                                            <i class="fas fa-book me-2"></i>Subject
                                        </label>
                                        <select class="form-select" id="subject" name="subject">
                                            <option value="-" selected>Select Subject</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="group">
                                            <i class="fas fa-users me-2"></i>Group
                                        </label>
                                        <select class="form-select" id="group" name="group">
                                            <option value="-" selected>Select Group</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="room">
                                            <i class="fas fa-door-open me-2"></i>Room
                                        </label>
                                        <select class="form-select" id="room" name="room">
                                            <option value="-" selected>Select Room</option>
                                            @foreach($data['lecture_room'] as $rm)
                                            <option value="{{ $rm->id }}">{{ $rm->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 d-flex align-items-end mb-3">
                                    <button type="button" id="add-event" class="btn btn-primary w-100">
                                        <i class="fas fa-plus me-2"></i> Add to Calendar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Calendar Box -->
                    <div class="box mb-4">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="box-title mb-0 fw-700">Timetable Schedule</h4>
                                <div class="last-published">
                                    <span class="badge bg-info p-2">
                                        <i class="fas fa-sync me-1"></i>
                                        Last published: {{ date('d M Y', strtotime($data['time'])) }}
                                    </span>
                                </div>
                            </div>
                            <hr>
                            
                            <div id='calendar' style="width: 100%;"></div>
                            
                            <div class="action-buttons">
                                <button type="button" class="btn btn-primary" id="publish-schedule">
                                    <i class="fas fa-upload me-2"></i> Publish
                                </button>
                                
                                <button type="button" class="btn btn-warning" id="reset-schedule">
                                    <i class="fas fa-redo me-2"></i> Reset
                                </button>
                                
                                <button type="button" class="btn btn-info" id="log-schedule">
                                    <i class="fas fa-history me-2"></i> View Log
                                </button>
                                
                                <button type="button" id="print-schedule-btn" class="btn btn-secondary" 
                                        onclick="printScheduleTable(
                                            '{{ $data['lecturerInfo']->name }}',
                                            '{{ $data['lecturerInfo']->ic }}',
                                            '{{ $data['lecturerInfo']->no_staf }}',
                                            '{{ $data['lecturerInfo']->email }}'
                                        )">
                                    <i class="fas fa-print me-2"></i> Print
                                </button>
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
                            <h4 class="box-title">
                                <i class="fas fa-history me-2"></i> Timetable History
                            </h4>
                            <hr>
                            <div class="card-body">
                                <table id="complex_header" class="table table-striped projects">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table">
                                        <!-- Filled by getLoggedSchedule() -->
                                    </tbody>
                                </table>
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
                <!-- modal content-->
                <div class="modal-content" id="getModal">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-edit me-2"></i> Edit Schedule
                        </h5>
                        <button type="button" class="btn-close btn-close-white" id="close-edit-event-modal" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="edit-event-title">
                                        <i class="fas fa-heading me-2"></i> Title
                                    </label>
                                    <input type="text" id="edit-event-title" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="edit-start">
                                        <i class="fas fa-hourglass-start me-2"></i> Start
                                    </label>
                                    <input type="datetime-local" class="form-control" id="edit-start" name="start">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="edit-end">
                                        <i class="fas fa-hourglass-end me-2"></i> End
                                    </label>
                                    <input type="datetime-local" class="form-control" id="edit-end" name="end">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-between w-100">
                            <button id="delete-edit-event" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i> Delete
                            </button>
                            <button id="save-edit-event" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
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

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<!-- Google Fonts - Poppins -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced tooltips for better user guidance
        const addTooltip = (element, message) => {
            element.addEventListener('mouseenter', (e) => {
                const tooltip = document.createElement('div');
                tooltip.className = 'custom-tooltip';
                tooltip.innerText = message;
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
        addTooltip(document.getElementById('publish-schedule'), 'Publish the current timetable');
        addTooltip(document.getElementById('reset-schedule'), 'Reset changes to last published version');
        addTooltip(document.getElementById('log-schedule'), 'View timetable history');
        addTooltip(document.getElementById('print-schedule-btn'), 'Print current timetable');
        
        // Add visual feedback for form interactions
        const formSelects = document.querySelectorAll('.form-select');
        formSelects.forEach(select => {
            select.addEventListener('change', function() {
                if (this.value !== '-') {
                    this.classList.add('border-primary');
                } else {
                    this.classList.remove('border-primary');
                }
            });
        });
        
        // Add animation effects for the add event button
        const addEventBtn = document.getElementById('add-event');
        addEventBtn.addEventListener('mouseenter', function() {
            this.innerHTML = '<i class="fas fa-plus-circle me-2"></i> Add to Calendar';
        });
        
        addEventBtn.addEventListener('mouseleave', function() {
            this.innerHTML = '<i class="fas fa-plus me-2"></i> Add to Calendar';
        });
    });
</script>

<script>
    // Modern Timetable Management System JavaScript
// Enhanced with improved UX, animations, and error handling

// Document ready for UI initialization
$(document).ready(function(){
    // Animated panel toggling with modern easing
    $("#collapsee").hide();
    $("#myButton").click(function(){
        $("#collapsee").slideToggle(400);
    });
    
    // Fetch logged schedules
    getLoggedSchedule();
    
    // Initialize tooltips and enhance interactive elements
    initInteractiveElements();
});

// Initialize interactive elements with modern styling
function initInteractiveElements() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Add hover animations for buttons
    $('.btn').hover(
        function() { $(this).addClass('btn-hover-effect'); },
        function() { $(this).removeClass('btn-hover-effect'); }
    );
    
    // Initialize form validations
    setupFormValidation();
}

// Setup form validation
function setupFormValidation() {
    $('.form-control, .form-select').on('change input', function() {
        if ($(this).val() && $(this).val() !== '-') {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid');
        }
    });
}

// Show loading indicator
function showLoading(element) {
    $(element).html('<div class="loading-spinner"><i class="fas fa-circle-notch fa-spin"></i> Loading...</div>');
}

// Hide loading indicator
function hideLoading(element) {
    $(element).find('.loading-spinner').remove();
}

// Fetch and display logged schedules
function getLoggedSchedule() {
    showLoading('#table');
    
    $.ajax({
        url: '/AR/schedule/log/{{ request()->id }}/getLoggedSchedule',
        type: "GET",
        dataType: "json",
        success: function(data) {
            $('#table').empty();
            
            if (data.length === 0) {
                $('#table').html('<tr><td colspan="3" class="text-center py-4"><i class="fas fa-info-circle me-2"></i>No logged schedules found</td></tr>');
                return;
            }
            
            let i = 1;
            $.each(data, function(key, value) {
                const row = $(`
                    <tr>
                        <td>${i}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-day me-2 text-primary"></i>
                                ${value.date}
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="/AR/schedule/log/{{ request()->id }}/view?idS=${value.date}" 
                                   class="btn btn-primary btn-sm me-2" data-toggle="tooltip" title="View Schedule">
                                   <i class="fas fa-eye me-1"></i> View
                                </a>
                                <a onClick="deleteLog('${value.date}')" 
                                   class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete Schedule">
                                   <i class="fas fa-trash me-1"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                `);
                $('#table').append(row);
                i++;
            });
            
            // Re-initialize tooltips after adding rows
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function(xhr, status, error) {
            $('#table').html(`<tr><td colspan="3" class="text-center py-4 text-danger">
                <i class="fas fa-exclamation-triangle me-2"></i> Error loading data: ${error}
            </td></tr>`);
            console.error("Error fetching schedule logs:", error);
        }
    });
}

// Delete logged schedule with confirmation
function deleteLog(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This action cannot be undone",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Yes, delete it!',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel',
        confirmButtonColor: '#e63946',
        cancelButtonColor: '#6c757d'
    }).then(async function (res) {
        if (res.isConfirmed) {
            // Show loading indicator
            Swal.fire({
                title: 'Deleting...',
                html: '<i class="fas fa-spinner fa-spin fa-2x"></i>',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success
                        });
                        getLoggedSchedule();
                    }
                } else {
                    throw new Error('Network response was not ok.');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed to delete schedule log: ' + error.message
                });
            }
        }
    });
}

// Session change event - fetch subjects
$('#ses').change(function() {
    var sessionID = $(this).val();
    
    // Add visual feedback
    $(this).addClass('border-primary');
    
    if (sessionID) {
        // Show loading state
        $('#subject').html('<option value="-">Loading subjects...</option>');
        $('#subject').prop('disabled', true);
        
        $.ajax({
            url: '/AR/schedule/scheduleTable/{{ request()->id }}/getSubjectSchedule',
            type: "GET",
            dataType: "json",
            data: { sessionID: sessionID },
            success: function(data) {
                $('#subject').empty();
                $('#group').empty();
                $('#subject').append('<option value="-" selected disabled>Select Subject</option>');
                
                if (data.length === 0) {
                    $('#subject').append('<option value="-" disabled>No subjects available</option>');
                } else {
                    $.each(data, function(key, value) {
                        $('#subject').append(
                            `<option value="${value.id}|${value.Type}">${value.name} (${value.code})</option>`
                        );
                    });
                }
                
                $('#subject').prop('disabled', false);
                $('#group').append('<option value="-" selected disabled>Select Group</option>');
            },
            error: function(xhr, status, error) {
                $('#subject').empty();
                $('#subject').append('<option value="-" selected disabled>Error loading subjects</option>');
                $('#subject').prop('disabled', false);
                console.error("Error fetching subjects:", error);
            }
        });
    } else {
        $(this).removeClass('border-primary');
        $('#subject').empty().append('<option value="-" selected disabled>Select Subject</option>');
        $('#group').empty().append('<option value="-" selected disabled>Select Group</option>');
    }
});

// Subject change event - fetch groups
$('#subject').change(function() {
    var groupID = $(this).val();
    
    // Add visual feedback
    $(this).addClass('border-primary');
    
    if (groupID) {
        // Show loading state
        $('#group').html('<option value="-">Loading groups...</option>');
        $('#group').prop('disabled', true);
        
        $.ajax({
            url: '/AR/schedule/scheduleTable/{{ request()->id }}/getGroupSchedule',
            type: "GET",
            dataType: "json",
            data: { groupID: groupID },
            success: function(data) {
                $('#group').empty();
                $('#group').append('<option value="-" selected disabled>Select Group</option>');
                
                if (data.length === 0) {
                    $('#group').append('<option value="-" disabled>No groups available</option>');
                } else {
                    $.each(data, function(key, value) {
                        $('#group').append(
                            `<option value="${value.group_name}">${value.group_name}</option>`
                        );
                    });
                }
                
                $('#group').prop('disabled', false);
            },
            error: function(xhr, status, error) {
                $('#group').empty();
                $('#group').append('<option value="-" selected disabled>Error loading groups</option>');
                $('#group').prop('disabled', false);
                console.error("Error fetching groups:", error);
            }
        });
    } else {
        $(this).removeClass('border-primary');
        $('#group').empty().append('<option value="-" selected disabled>Select Group</option>');
    }
});

// Generate random colors from modern palette
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

// Initialize FullCalendar
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var hiddenDays = [0, 6]; // Hide Sunday(0) & Saturday(6)

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
        slotMinTime: '08:30:00',
        slotMaxTime: '18:00:00',
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
            $('<div class="calendar-loading"><i class="fas fa-circle-notch fa-spin"></i></div>').appendTo('#calendar');
            
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

            // 2) Fetch dynamic events
            fetch('/AR/schedule/fetch/{{ request()->id }}')
                .then(response => response.json())
                .then(data => {
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
                    showNotification('Error loading calendar events', 'error');
                    
                    failureCallback(error);
                });
        },

        // Enhanced event styling
        eventDidMount: function(info) {
            if (info.event.title !== 'REHAT') {
                // Add program info with better styling
                var programDiv = document.createElement('div');
                programDiv.classList.add('program-info');
                programDiv.style.position = 'absolute';
                programDiv.style.bottom = '0';
                programDiv.style.width = '100%';
                programDiv.style.padding = '5px';
                programDiv.style.backgroundColor = 'rgba(0,0,0,0.2)';
                programDiv.style.borderTop = '1px solid rgba(255,255,255,0.2)';
                programDiv.style.fontSize = '0.7rem';
                programDiv.textContent = info.event.extendedProps.programInfo || 'No program info';
                info.el.appendChild(programDiv);
            }
        },

        editable: true,
        selectable: true,
        eventResizableFromStart: true,
        durationEditable: true,
        
        titleFormat: {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        },
        
        dayHeaderFormat: {
            weekday: 'long',
            day: 'numeric'
        },

        // Event content customization
        eventContent: function(arg) {
            var titleElement = document.createElement('div');
            titleElement.classList.add('event-title');
            titleElement.style.fontWeight = 'bold';
            titleElement.style.fontSize = '0.85rem';
            titleElement.style.padding = '2px 0';
            titleElement.textContent = arg.event.title;

            var timeElement = document.createElement('div');
            timeElement.classList.add('event-time');
            timeElement.style.fontSize = '0.75rem';
            timeElement.style.opacity = '0.9';
            timeElement.textContent = arg.timeText; 

            var arrayOfDomNodes = [timeElement, titleElement];

            if (arg.event.extendedProps.description) {
            var descriptionElement = document.createElement('div');
            descriptionElement.classList.add('event-description');
            descriptionElement.style.fontSize = '0.7rem';
            descriptionElement.style.opacity = '0.8';
            descriptionElement.style.whiteSpace = 'normal';
            descriptionElement.style.overflow = 'visible';
            descriptionElement.textContent = arg.event.extendedProps.description;
            arrayOfDomNodes.push(descriptionElement);
            }

            return { domNodes: arrayOfDomNodes };
        },

        // Event click handling
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

        // Event resize handling
        eventResize: async function (info) {
            var event = info.event;
            var eventData = {
                start: convertToPhpMyAdminDatetime(event.start),
                end: event.end ? convertToPhpMyAdminDatetime(event.end) : null
            };

            // Show loading indicator
            showNotification('Updating event...', 'info', true);

            try {
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
                        
                        // Check if conflicting students list exists
                        if (data.conflicting_students && data.conflicting_students.length > 0) {
                            // Create list of student ICs
                            const studentList = data.conflicting_students.map(student => student.no_matric).join(', ');
                            showNotification(`${data.error}<br><br>Conflicting students: ${studentList}`, 'error', false);
                        } else {
                            showNotification(data.error, 'error');
                        }
                    } else {
                        showNotification('Event updated successfully', 'success');
                    }
                } else {
                    throw new Error('Failed to update event');
                }
            } catch (error) {
                info.revert();
                showNotification('Failed to update event: ' + error.message, 'error');
            }
        },

        // Event drag start
        eventDragStart: async function(info) {
            // Add visual drag effect
            $(info.el).addClass('event-dragging');
            
            var event = info.event;
            try {
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
                        showNotification(data.error, 'error');
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
                                backgroundColor: 'rgba(244, 67, 54, 0.15)',
                                borderColor: 'rgba(244, 67, 54, 0.3)',
                                allDay: false
                            };
                            info.view.calendar.addEvent(highlightEvent);
                        });
                    }
                } else {
                    throw new Error('Failed to fetch existing events');
                }
            } catch (error) {
                info.revert();
                showNotification('Error: ' + error.message, 'error');
            }
        },

        // Event drag stop
        eventDragStop: function(info) {
            // Remove drag effect
            $(info.el).removeClass('event-dragging');
            
            // Remove highlight events
            info.view.calendar.getEvents().forEach(e => {
                if (e.id.startsWith('highlight-')) {
                    e.remove();
                }
            });
        },

        // Event drop (after dragging)
        eventDrop: async function(info) {
            var event = info.event;
            var eventData = {
                start: convertToPhpMyAdminDatetime(event.start),
                end: event.end ? convertToPhpMyAdminDatetime(event.end) : null
            };

            // Show loading indicator
            showNotification('Updating event...', 'info', true);

            try {
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
                        
                        // Check if conflicting students list exists
                        if (data.conflicting_students && data.conflicting_students.length > 0) {
                            // Create list of student ICs
                            const studentList = data.conflicting_students.map(student => student.no_matric).join(', ');
                            showNotification(`${data.error}<br><br>Conflicting students: ${studentList}`, 'error', false);
                        } else {
                            showNotification(data.error, 'error');
                        }
                    } else {
                        showNotification('Event updated successfully', 'success');
                    }
                } else {
                    throw new Error('Failed to update event');
                }
            } catch (error) {
                info.revert();
                showNotification('Error: ' + error.message, 'error');
            }

            // Remove highlight events
            info.view.calendar.getEvents().forEach(e => {
                if (e.id.startsWith('highlight-')) {
                    e.remove();
                }
            });
        }
    });

    // Render the calendar
    calendar.render();

    // Add event button
    // Add event button
document.getElementById('add-event').addEventListener('click', async function () {
    // Validate inputs
    const requiredInputs = ['ses', 'subject', 'group', 'room', 'event-start'];
    let isValid = true;
    
    requiredInputs.forEach(id => {
        const el = document.getElementById(id);
        if (!el.value || el.value === '-') {
            $(el).addClass('is-invalid');
            $(el).removeClass('is-valid');
            isValid = false;
        } else {
            $(el).removeClass('is-invalid');
            $(el).addClass('is-valid');
        }
    });
    
    if (!isValid) {
        showNotification('Please fill in all required fields', 'warning');
        return;
    }
    
    // Prepare event data
    var session = document.getElementById('ses').value;
    var combinedValue = document.getElementById('subject').value;
    var splitValues = combinedValue.split('|');

    var groupId = splitValues[0]; // This will contain the id
    var groupType = splitValues[1]; // This will contain the Type
    var groupName = document.getElementById('group').value;
    var roomId = document.getElementById('room').value;
    
    var eventStart = convertToPhpMyAdminDatetime(
        new Date(document.getElementById('event-start').value)
    );

    const slotMinTime = '08:00:00';
    const slotMaxTime = '18:00:00';
    const startHour = parseInt(eventStart.slice(11, 13));

    if (startHour < parseInt(slotMinTime.slice(0, 2)) || startHour > parseInt(slotMaxTime.slice(0, 2))) {
        showNotification('Event start time must be between 08:00 and 18:00', 'error');
        return;
    }

    // Show loading state
    $(this).prop('disabled', true);
    $(this).html('<i class="fas fa-spinner fa-spin me-2"></i> Adding...');

    try {
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

            // Check if response is JSON before trying to parse it
            const contentType = response.headers.get('content-type');
            let data;
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                // If not JSON, get the text and create an error object
                const textResponse = await response.text();
                // Log the raw text for debugging
                console.error('Non-JSON response:', textResponse);
                
                // Create a basic error object with the response HTML
                data = {
                    error: 'Server returned an invalid response. This could be due to a session timeout or server error.',
                    html_response: textResponse.substring(0, 100) + '...' // Truncate for display
                };
            }
            
            if (response.ok) {
                if(data.error) {
                    showNotification(data.error, 'error');
                } else {
                    // Add with animation
                    const eventObj = {
                        ...data.event,
                        color: getRandomColor()
                    };
                    calendar.addEvent(eventObj);
                    
                    // Reset form fields
                    requiredInputs.forEach(id => {
                        const el = document.getElementById(id);
                        $(el).val('').trigger('change');
                        $(el).removeClass('is-valid');
                    });
                    
                    showNotification('Event added successfully', 'success');
                }
            } else {
                // Parse the error message from the response
                let errorMessage = 'Failed to add event';
                
                if (data && data.error) {
                    errorMessage = data.error;
                } else if (data && data.message) {
                    errorMessage = data.message;
                } else if (response.statusText) {
                    errorMessage = `Failed to add event: ${response.statusText} (${response.status})`;
                }
                
                // If there are conflicting students, display them
                if (data && data.conflicting_students && data.conflicting_students.length > 0) {
                    const studentList = data.conflicting_students
                        .map(student => student.no_matric)
                        .join(', ');
                    errorMessage += `. Affected students: ${studentList}`;
                }
                
                showNotification(errorMessage, 'error');
            }
        } else {
            showNotification('Start and end times cannot be the same', 'warning');
        }
    } catch (error) {
        // Handle any JSON parsing or network errors
        console.error('Error details:', error);
        showNotification('Error: ' + (error.message || 'Could not process server response'), 'error');
    } finally {
        // Reset button state
        $(this).prop('disabled', false);
        $(this).html('<i class="fas fa-plus me-2"></i> Add to Calendar');
    }
});
    // Publish schedule button
    document.getElementById('publish-schedule').addEventListener('click', async function () {
        var ic = '{{ $data['lecturerInfo']->ic }}';
        var eventData = { ic: ic };

        // Show loading state
        $(this).prop('disabled', true);
        $(this).html('<i class="fas fa-spinner fa-spin me-2"></i> Publishing...');

        try {
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
                    showNotification(data.error, 'error');
                } else {
                    showNotification(data.success, 'success');
                    
                    // Update last published time
                    $('.last-published').html(`
                        <span class="badge bg-info p-2">
                            <i class="fas fa-sync me-1"></i>
                            Last published: ${new Date().toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}
                        </span>
                    `);
                }
            } else {
                throw new Error('Failed to publish schedule');
            }
        } catch (error) {
            showNotification('Error: ' + error.message, 'error');
        } finally {
            // Reset button state
            $(this).prop('disabled', false);
            $(this).html('<i class="fas fa-upload me-2"></i> Publish');
        }
    });

    // Reset schedule button
    document.getElementById('reset-schedule').addEventListener('click', async function () {
        var ic = '{{ $data['lecturerInfo']->ic }}';
        var eventData = { ic: ic };

        Swal.fire({
            title: "Reset Timetable?",
            text: "This action will remove all unsaved changes",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-redo me-1"></i> Yes, reset it!',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel',
            confirmButtonColor: '#f72585',
            cancelButtonColor: '#6c757d'
        }).then(async function (res) {
            if (res.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Resetting...',
                    html: '<i class="fas fa-spinner fa-spin fa-2x"></i>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                try {
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
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.success
                            });
                            
                            // Refresh calendar
                            calendar.refetchEvents();
                        }
                    } else {
                        throw new Error('Failed to reset schedule');
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Failed to reset schedule: ' + error.message
                    });
                }
            }
        });
    });

    // Log schedule button
    document.getElementById('log-schedule').addEventListener('click', async function () {
        var ic = '{{ $data['lecturerInfo']->ic }}';
        var eventData = { ic: ic };

        // Show loading state
        $(this).prop('disabled', true);
        $(this).html('<i class="fas fa-spinner fa-spin me-2"></i> Logging...');

        try {
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
                    showNotification(data.error, 'error');
                } else {
                    showNotification(data.success, 'success');
                    
                    // Refresh logged schedules
                    getLoggedSchedule();
                }
            } else {
                throw new Error('Failed to log schedule');
            }
        } catch (error) {
            showNotification('Error: ' + error.message, 'error');
        } finally {
            // Reset button state
            $(this).prop('disabled', false);
            $(this).html('<i class="fas fa-history me-2"></i> View Log');
        }
    });
});

// Modal handling for edit/delete events
async function handleEventDelete(event) {
    Swal.fire({
        title: "Delete Event?",
        text: "This cannot be undone",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Delete',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel',
        confirmButtonColor: '#e63946',
        cancelButtonColor: '#6c757d'
    }).then(async (result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting...',
                html: '<i class="fas fa-spinner fa-spin fa-2x"></i>',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
                const response = await fetch('/AR/schedule/delete/' + event.id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                });
                
                if (response.ok) {
                    event.remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The event has been removed',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error('Could not delete the event');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            }
        }
    });
}

function openEditEventModal(event) {
    // Set current event values
    document.getElementById('edit-event-title').value = event.title;
    document.getElementById('edit-start').value = convertToPhpMyAdminDatetime(event.start);
    document.getElementById('edit-end').value = convertToPhpMyAdminDatetime(event.end);

    const saveButton = document.getElementById('save-edit-event');
    const deleteButton = document.getElementById('delete-edit-event');
    const closeButton = document.getElementById('close-edit-event-modal');

    // Setup button handlers
    saveButton.onclick = function () { handleEventUpdate(event); };
    deleteButton.onclick = function () { handleEventDelete(event); closeModal(); };
    closeButton.onclick = function () { closeModal(); };

    // Show modal
    $('#edit-event-modal').modal('show');
}

function closeModal() {
    $('#edit-event-modal').modal('hide');
}

async function handleEventUpdate(event) {
    const newTitle = document.getElementById('edit-event-title').value;
    const newStart = document.getElementById('edit-start').value;
    const newEnd = document.getElementById('edit-end').value;

    // Validate inputs
    if (!newTitle || !newStart || !newEnd) {
        showNotification('All fields are required', 'warning');
        return;
    }

    const slotMinTime = '08:00:00';
    const slotMaxTime = '18:00:00';
    const startHour = parseInt(newStart.slice(11, 13));
    const endHour = parseInt(newEnd.slice(11, 13));

    if (startHour < parseInt(slotMinTime.slice(0, 2)) || endHour > parseInt(slotMaxTime.slice(0, 2))) {
        showNotification('Event times must be between 08:00 and 18:00', 'error');
        return;
    }

    // Show loading state
    const saveBtn = document.getElementById('save-edit-event');
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
    saveBtn.disabled = true;

    try {
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
                // Check if the response includes conflicting students list
                if (data.conflicting_students && data.conflicting_students.length > 0) {
                    // Create list of student ICs
                    const studentList = data.conflicting_students.map(student => student.no_matric).join(', ');
                    showNotification(`${data.error}<br><br>Conflicting students: ${studentList}`, 'error', false);
                } else {
                    showNotification(data.error, 'error');
                }
            } else {
                // Update the event on the calendar
                event.setProp('title', newTitle);
                event.setDates(newStart, newEnd);
                calendar.render();
                closeModal();
                showNotification('Event updated successfully', 'success');
            }
        } else {
            throw new Error('Failed to update event');
        }
    } catch (error) {
        showNotification('Error: ' + error.message, 'error');
    } finally {
        // Reset button state
        saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Save Changes';
        saveBtn.disabled = false;
    }
}

// Convert to "YYYY-MM-DD HH:mm:ss" format
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

// Print schedule table function
function printScheduleTable(name, ic, staffNo, email) {
    // Show loading notification
    showNotification('Preparing timetable for printing...', 'info');
    
    const dayNames = ['Monday','Tuesday','Wednesday','Thursday','Friday'];

    // Build half-hour time slots (08:30..18:00)
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

    // Get events from FullCalendar
    const events = calendar.getEvents();

    // Build a 2D array scheduleData[dayIndex][timeIndex] = [events]
    // Changed to store arrays of events instead of single events
    let scheduleData = [];
    for (let d = 0; d < dayNames.length; d++) {
        scheduleData[d] = [];
        for (let t = 0; t < times.length; t++) {
            scheduleData[d][t] = []; // Initialize with empty array
        }
    }

    events.forEach(event => {
        let start = event.start;
        let end = event.end || new Date(start.getTime() + 60 * 60 * 1000);

        // Convert day-of-week (Mon=1..Fri=5 => index 0..4)
        let dayIndex = start.getDay() - 1; 
        if (dayIndex < 0 || dayIndex > 4) return; // skip Sat/Sun

        let startTimeStr = toHHMM(start);
        let endTimeStr = toHHMM(end);

        let startIndex = times.indexOf(startTimeStr);
        if (startIndex === -1) return;

        let endIndex = times.indexOf(endTimeStr);
        if (endIndex === -1) endIndex = times.length;

        // Fill each half-hour slot with the event
        for (let i = startIndex; i < endIndex; i++) {
            scheduleData[dayIndex][i].push(event); // Push to array instead of overwriting
        }
    });

    // Create processed tracking arrays
    let processedEvents = new Set();
    let skip = [];
    for (let d = 0; d < dayNames.length; d++) {
        skip[d] = new Array(times.length).fill(false);
    }

    // Build HTML with modern styling
    let html = `
    <html>
    <head>
        <title>Timetable - ${name}</title>
        <style>
            @page {
                size: A4 landscape;
                margin: 0.5cm;
            }
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                color: #333;
                font-size: 9px;
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
            }
            .lecturer-info strong {
                color: #4361ee;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                box-shadow: none;
                border-radius: 0;
                font-size: 9px;
            }
            th {
                background-color: #4361ee;
                color: white;
                padding: 5px;
                text-align: center;
                font-weight: 600;
                font-size: 10px;
            }
            td {
                border: 1px solid #e0e0e0;
                padding: 4px;
                text-align: center;
                vertical-align: middle;
            }
            .time-column {
                background-color: #f0f0f0;
                font-weight: 600;
                color: #555;
                width: 70px;
            }
            .event-cell {
                background-color: #ebf4ff;
                border: 1px solid #c5dbff;
            }
            .event-title {
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 2px;
            }
            .event-description {
                color: #7f8c8d;
                font-size: 8px;
            }
            .rehat-cell {
                background-color: #ffebee;
                border: 1px solid #ffcdd2;
                color: #c62828;
                font-weight: 600;
            }
            .multi-event-container {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }
            .event-divider {
                border-top: 1px dashed #ccc;
                margin: 2px 0;
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
            @media print {
                body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                .container {
                    padding: 0;
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
                <p><strong>Name:</strong> ${name}</p>
                <p><strong>IC:</strong> ${ic}</p>
                <p><strong>Staff No:</strong> ${staffNo}</p>
                <p><strong>Email:</strong> ${email}</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th class="time-column">Time</th>`;

    // Column headers for days
    dayNames.forEach(day => {
        html += `<th>${day}</th>`;
    });

    html += `</tr></thead><tbody>`;

    // For each timeslot row
    for (let t = 0; t < times.length; t++) {
        // Build the time label, e.g. "08:30 - 09:00"
        let timeLabel = times[t];
        if (t < times.length - 1) {
            timeLabel += ' - ' + times[t + 1];
        } else {
            timeLabel += ' - 18:00';
        }

        // Start a row
        html += `<tr>`;

        // Left column: time label
        html += `<td class="time-column">${timeLabel}</td>`;

        // For each day column
        for (let d = 0; d < dayNames.length; d++) {
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
                    
                    let startIndex = times.indexOf(startTimeStr);
                    let endIndex = times.indexOf(endTimeStr);
                    if (endIndex === -1) endIndex = times.length;
                    
                    let rowSpan = endIndex - startIndex;
                    
                    // Mark future slots to skip
                    for (let k = 1; k < rowSpan; k++) {
                        if (t + k < times.length) {
                            skip[d][t + k] = true;
                        }
                    }
                    
                    // Create cell with REHAT
                    html += `<td rowspan="${rowSpan}" class="rehat-cell">
                                <div class="event-title">REHAT</div>
                            </td>`;
                    
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
                    
                    let startIndex = times.indexOf(startTimeStr);
                    let endIndex = times.indexOf(endTimeStr);
                    if (endIndex === -1) endIndex = times.length;
                    
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
                    
                    // Now just display the events without REHAT check (handled earlier)
                    {
                        // Start multi-event container if we have multiple events
                        if (events.length > 1) {
                            html += `<div class="multi-event-container">`;
                        }
                        
                        // Add each event
                        events.forEach((event, index) => {
                            if (index > 0) {
                                html += `<div class="event-divider"></div>`;
                            }
                            
                            html += `<div class="event-title">${event.title || '(No Title)'}</div>`;
                            
                            // Add description if available
                            if (event.extendedProps && event.extendedProps.description) {
                                html += `<div class="event-description">${event.extendedProps.description}</div>`;
                            }
                            
                            // Add program info if available
                            if (event.extendedProps && event.extendedProps.programInfo) {
                                html += `<div class="event-description">Program: ${event.extendedProps.programInfo}</div>`;
                            }
                        });
                        
                        // Close multi-event container if needed
                        if (events.length > 1) {
                            html += `</div>`;
                        }
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

    // Add current date and footer
    const currentDate = new Date().toLocaleDateString('en-GB', {
        day: '2-digit', 
        month: 'short', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    html += `
            </tbody>
        </table>
        
        <div class="print-date">
            Generated on: ${currentDate}
        </div>
        
        <footer>
            © Timetable Management System
        </footer>
    </div>
    </body>
    </html>`;

    // Open print window
    let printWindow = window.open('', '_blank', 'width=1100,height=800');
    printWindow.document.open();
    printWindow.document.write(html);
    printWindow.document.close();
    
    // Wait for content to load before printing
    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
    }, 1000);
}

// Helper to convert Date -> "HH:MM"
function toHHMM(dateObj) {
    let hh = String(dateObj.getHours()).padStart(2, '0');
    let mm = String(dateObj.getMinutes()).padStart(2, '0');
    return hh + ':' + mm;
}

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
    
    // Create the toast HTML structure using innerHTML for more reliable rendering
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
    
    // Add a click handler directly to the close button
    const closeBtn = toast.querySelector('button');
    if (closeBtn) {
        closeBtn.onclick = function() {
            if (toast && toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        };
    }
    
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

// Initialize tooltips for interactive elements
function initializeTooltips() {
    // Add tooltips to action buttons
    $('.btn').each(function() {
        let tooltipText = '';
        
        if ($(this).attr('id') === 'add-event') {
            tooltipText = 'Add a new event to the timetable';
        } else if ($(this).attr('id') === 'publish-schedule') {
            tooltipText = 'Publish current timetable';
        } else if ($(this).attr('id') === 'reset-schedule') {
            tooltipText = 'Reset to last published version';
        } else if ($(this).attr('id') === 'log-schedule') {
            tooltipText = 'Save current timetable state to history';
        } else if ($(this).attr('id') === 'print-schedule-btn') {
            tooltipText = 'Print current timetable';
        } else if ($(this).hasClass('btn-primary') && $(this).text().includes('View')) {
            tooltipText = 'View this saved timetable';
        } else if ($(this).hasClass('btn-danger') && $(this).text().includes('Delete')) {
            tooltipText = 'Delete this saved timetable';
        }
        
        if (tooltipText) {
            $(this).attr('title', tooltipText);
            $(this).tooltip();
        }
    });
}
</script>

@stop