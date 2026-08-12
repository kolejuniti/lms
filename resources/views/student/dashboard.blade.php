@extends('layouts.student')

<link rel="stylesheet" href="{{ asset('css/studentDashboard.css') }}">

@section('main')
<!-- Content Wrapper -->
<div class="content-wrapper">
  <div class="container-full">
    <!-- Content Header (Page header) -->	
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Dashboard</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </nav>
          </div>
        </div>
        <!-- Improved Current Date Display -->
        <div class="current-date-wrapper">
          <div class="current-date-container">
            <div class="current-date-icon">
              <i class="mdi mdi-calendar-text"></i>
            </div>
            <div class="current-date-text">
              <span id="current-day">{{ now()->format('l') }}</span>
              <span id="current-date">{{ now()->format('l, F j, Y') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Welcome Banner -->
      <div class="row">
        <div class="col-xl-12 col-12">
          <div class="profile-card mb-4">
            <div class="profile-content">
                <div class="row">
                  <div class="col-12 col-xl-7">
                    <h1 class="mb-0 fw-600">Welcome to UCMS, {{ Session::get('User')->name }}</h1>
                    <p class="mt-2 fs-16">Here's what's happening with your academic schedule today</p>
                    <div class="mt-3">
                      <a href="#today-classes" class="appendfield1 waves-effect waves-light btn btn-app btn-info-light">
                        <i class="mdi mdi-calendar-today me-1"></i> Today's Classes
                      </a>
                      {{-- <a href="#weekly-schedule" class="appendfield2 waves-effect waves-light btn btn-app btn-warning-light">
                        <i class="mdi mdi-calendar-week me-1"></i> View Full Schedule
                      </a> --}}
                    </div>
                  </div>
                </div>
            </div>
        </div>
        </div>
      </div>

      <!-- Hostel Status Section -->
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title modern-title">Hostel Information</h4>
              <div class="box-controls pull-right">
                <a href="/student/printStudentSlip/{{ Auth::guard('student')->user()->ic }}" class="btn btn-sm btn-info" target="_blank">
                  <i class="mdi mdi-printer me-1"></i> Print Slip
                </a>
              </div>
            </div>
            <div class="box-body p-0">
              @if(isset($data['hostel']) && $data['hostel'])
                <div class="p-4">
                  <div class="row">
                    <!-- Hostel Status Card -->
                    <div class="col-lg-4 col-md-6 mb-4">
                      <div class="p-4 rounded-lg border" style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.05) 0%, rgba(76, 201, 240, 0.05) 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <h5 class="mb-0">Assigned Hostel</h5>
                          <span class="badge {{ $data['hostel']->student_status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $data['hostel']->student_status }}
                          </span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                          <div class="me-3 d-flex justify-content-center align-items-center bg-info-light rounded-circle" style="width: 50px; height: 50px;">
                            <i class="mdi mdi-home-city fs-24 text-info">{{ $data['hostel']->name }}</i>
                          </div>
                          <div>
                            <h4 class="mb-0">{{ $data['hostel']->no_unit }} - {{ $data['hostel']->name }}</h4>
                            <p class="mb-0 text-muted">{{ $data['hostel']->location }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Duration Card -->
                    <div class="col-lg-4 col-md-6 mb-4">
                      <div class="p-4 rounded-lg border" style="background: linear-gradient(135deg, rgba(67, 201, 120, 0.05) 0%, rgba(38, 166, 154, 0.05) 100%);">
                        <h5 class="mb-3">Residence Period</h5>
                        <div class="d-flex align-items-center mb-2">
                          <i class="mdi mdi-calendar-plus me-2 text-success"></i>
                          <div>
                            <p class="mb-0 text-muted">Entry Date</p>
                            <p class="mb-0 fw-bold">{{ date('d M Y', strtotime($data['hostel']->entry_date)) }}</p>
                          </div>
                        </div>
                        <div class="d-flex align-items-center">
                          <i class="mdi mdi-calendar-end me-2 text-danger"></i>
                          <div>
                            <p class="mb-0 text-muted">Exit Date</p>
                            <p class="mb-0 fw-bold">{{ $data['hostel']->exit_date ? date('d M Y', strtotime($data['hostel']->exit_date)) : 'Not specified' }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Unit Status Card -->
                    <div class="col-lg-4 col-md-12 mb-4">
                      <div class="p-4 rounded-lg border" style="background: linear-gradient(135deg, rgba(247, 37, 133, 0.05) 0%, rgba(181, 23, 158, 0.05) 100%);">
                        <h5 class="mb-3">Facilities Status</h5>
                        <div class="d-flex justify-content-between mb-2">
                          <span>Unit Status:</span>
                          <span class="badge {{ $data['hostel']->block_unit_status == 'Good' ? 'bg-success' : ($data['hostel']->block_unit_status == 'Maintenance' ? 'bg-warning' : 'bg-danger') }}">
                            {{ $data['hostel']->block_unit_status }}
                          </span>
                        </div>
                        {{-- <div class="d-flex align-items-center">
                          <a href="#" class="btn btn-sm btn-outline-primary me-2">
                            <i class="mdi mdi-alert-circle-outline me-1"></i> Report Issue
                          </a>
                          <a href="#" class="btn btn-sm btn-outline-info">
                            <i class="mdi mdi-information-outline me-1"></i> Facilities Info
                          </a>
                        </div> --}}
                      </div>
                    </div>
                  </div>
                  
                  <!-- Quick Actions -->
                  {{-- <div class="row mt-2">
                    <div class="col-12">
                      <div class="d-flex flex-wrap gap-2">
                        <a href="#" class="btn btn-light-primary">
                          <i class="mdi mdi-file-document-outline me-1"></i> Apply for Extension
                        </a>
                        <a href="#" class="btn btn-light-warning">
                          <i class="mdi mdi-account-switch me-1"></i> Request Room Change
                        </a>
                        <a href="#" class="btn btn-light-danger">
                          <i class="mdi mdi-exit-to-app me-1"></i> Request Early Check-out
                        </a>
                        <a href="#" class="btn btn-light-info">
                          <i class="mdi mdi-history me-1"></i> View Residence History
                        </a>
                      </div>
                    </div>
                  </div> --}}
                </div>
              @else
                <div class="empty-state p-4">
                  <div class="empty-state-icon">
                    <i class="mdi mdi-home-remove"></i>
                  </div>
                  <h5>No Hostel Information Available</h5>
                  <p class="text-muted">You are not currently assigned to any hostel. Please contact the hostel administration if you believe this is an error.</p>
                  <a href="#" class="btn btn-primary">
                    <i class="mdi mdi-home-plus me-1"></i> Apply for Hostel
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Messaging Widget -->
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title modern-title">Quick Messages</h4>
              <div class="box-controls pull-right">
                <button class="btn btn-sm btn-primary" onclick="toggleMessagingPanel()">
                  <i class="mdi mdi-message-text me-1"></i> View All
                </button>
              </div>
            </div>
            <div class="box-body p-0">
              <div class="row g-0">
                <!-- Admin Messages Section -->
                <div class="col-xl-6 col-12">
                  <div class="quick-message-section border-end">
                    <div class="section-header">
                      <h5 class="section-title">
                        <i class="mdi mdi-account-tie text-primary me-2"></i>
                        Admin Messages
                      </h5>
                    </div>
                    <div class="admin-messages-container">
                      <div class="admin-message-item" onclick="getMessage('FN', 'FN')">
                        <div class="message-avatar bg-info">
                          <i class="mdi mdi-currency-usd"></i>
                        </div>
                        <div class="message-details">
                          <div class="message-name">UKP (Student Finance)</div>
                          <div class="message-preview">Financial matters & payments</div>
                        </div>
                        <div class="message-badge">
                          <span id="dashboard-ukp-count" class="count-circle hidden">0</span>
                        </div>
                      </div>
                      
                      <div class="admin-message-item" onclick="getMessage('RGS', 'RGS')">
                        <div class="message-avatar bg-warning">
                          <i class="mdi mdi-file-document"></i>
                        </div>
                        <div class="message-details">
                          <div class="message-name">KRP (Registration)</div>
                          <div class="message-preview">Course registration & enrollment</div>
                        </div>
                        <div class="message-badge">
                          <span id="dashboard-krp-count" class="count-circle hidden">0</span>
                        </div>
                      </div>
                      
                      <div class="admin-message-item" onclick="getMessage('AR', 'AR')">
                        <div class="message-avatar bg-primary">
                          <i class="mdi mdi-school"></i>
                        </div>
                        <div class="message-details">
                          <div class="message-name">Academic Registrar</div>
                          <div class="message-preview">Academic matters & transcripts</div>
                        </div>
                        <div class="message-badge">
                          <span id="dashboard-ar-count" class="count-circle hidden">0</span>
                        </div>
                      </div>
                      
                      <div class="admin-message-item" onclick="getMessage('HEA', 'HEA')">
                        <div class="message-avatar bg-success">
                          <i class="mdi mdi-heart-pulse"></i>
                        </div>
                        <div class="message-details">
                          <div class="message-name">HEP (Hal Ehwan Pelajar)</div>
                          <div class="message-preview">Student Events & Well-being</div>
                        </div>
                        <div class="message-badge">
                          <span id="dashboard-hep-count" class="count-circle hidden">0</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Student Messages Section -->
                <div class="col-xl-6 col-12">
                  <div class="quick-message-section">
                    <div class="section-header">
                      <h5 class="section-title">
                        <i class="mdi mdi-account-group text-primary me-2"></i>
                        Student Messages
                      </h5>
                    </div>
                    
                    <!-- Quick Search -->
                    <div class="student-search-container">
                      <div class="search-input-group">
                        <i class="mdi mdi-magnify search-icon"></i>
                        <input type="text" id="dashboard-student-search" placeholder="Search students..." class="search-input">
                        <div id="dashboard-search-results" class="search-results"></div>
                      </div>
                    </div>
                    
                    <!-- Recent Conversations -->
                    <div class="student-conversations-container">
                      <div id="dashboard-student-conversations" class="student-conversations">
                        <div class="empty-message-state">
                          <i class="mdi mdi-message-outline"></i>
                          <p>No recent conversations</p>
                          <span>Search for students to start chatting!</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add this right after the hostel status section -->
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title modern-title">Campus Weather</h4>
            </div>
            <div class="box-body">
              <div class="weather-widget">
                <div class="weather-card">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="weather-info">
                      <div class="temperature" id="current-temperature">
                        <div class="spinner-border spinner-border-sm text-light" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                      </div>
                      <div class="description" id="weather-description">Loading weather data...</div>
                      <div class="location mt-2">Kolej Uniti Port Dickson</div>
                      <!-- Small debug button for manual refresh -->
                      <button id="refresh-weather-btn" class="btn btn-sm btn-light mt-2" onclick="manualWeatherRefresh()" style="font-size: 0.7rem; padding: 0.1rem 0.5rem;">
                        <i class="mdi mdi-refresh"></i> Refresh
                      </button>
                    </div>
                    <div class="weather-icon" id="weather-icon">
                      <div class="sun-icon">
                        <div class="sun"></div>
                        <div class="rays"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      
      <!-- Statistics Summary Cards -->
      <div class="row">
        <div class="col-xl-3 col-md-6 col-12">
          <div class="box">
        <div class="box-body">
          <div class="d-flex align-items-start">
            <div class="me-3 d-flex justify-content-center align-items-center bg-primary-light rounded-circle" style="width: 50px; height: 50px;">
          <i class="mdi mdi-book-open-page-variant fs-24 text-primary"></i>
            </div>
            <div>
          <h4 class="mt-0 mb-0">??</h4>
          <p class="mb-0 text-muted">Enrolled Courses 
            <span class="badge bg-secondary ms-1">Available Soon</span>
          </p>
            </div>
          </div>
        </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
          <div class="box">
        <div class="box-body">
          <div class="d-flex align-items-start">
            <div class="me-3 d-flex justify-content-center align-items-center bg-success-light rounded-circle" style="width: 50px; height: 50px;">
          <i class="mdi mdi-account-check fs-24 text-success"></i>
            </div>
            <div>
          <h4 class="mt-0 mb-0">??</h4>
          <p class="mb-0 text-muted">Attendance Rate
            <span class="badge bg-secondary ms-1">Available Soon</span>
          </p>
            </div>
          </div>
        </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
          <div class="box">
        <div class="box-body">
          <div class="d-flex align-items-start">
            <div class="me-3 d-flex justify-content-center align-items-center bg-warning-light rounded-circle" style="width: 50px; height: 50px;">
          <i class="mdi mdi-clipboard-text fs-24 text-warning"></i>
            </div>
            <div>
          <h4 class="mt-0 mb-0">??</h4>
          <p class="mb-0 text-muted">Pending Assignments
            <span class="badge bg-secondary ms-1">Available Soon</span>
          </p>
            </div>
          </div>
        </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
          <div class="box">
        <div class="box-body">
          <div class="d-flex align-items-start">
            <div class="me-3 d-flex justify-content-center align-items-center bg-info-light rounded-circle" style="width: 50px; height: 50px;">
          <i class="mdi mdi-chart-line fs-24 text-info"></i>
            </div>
            <div>
          <h4 class="mt-0 mb-0">??</h4>
          <p class="mb-0 text-muted">Average Grade
            <span class="badge bg-secondary ms-1">Available Soon</span>
          </p>
            </div>
          </div>
        </div>
          </div>
        </div>
      </div>
      
      <!-- Today's Classes -->
      {{-- <div class="row" id="today-classes">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title modern-title">Today's Classes</h4>
              <div class="box-controls pull-right">
                <div class="lookup lookup-circle lookup-right">
                  <span id="today-date" class="badge bg-light text-dark p-2"></span>
                </div>
              </div>
            </div>
            <div class="box-body p-0">
              <div id="today-classes-container" class="p-3">
                <!-- Today's classes will be loaded here -->
                <div class="text-center p-4">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="mt-2 text-muted">Loading your classes...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
      
      <!-- Announcements Banner -->
      <div class="row">
        <div class="col-12">
          <div id="announcementBanner"></div>
        </div>
      </div>
      
      <!-- Weekly Schedule -->
      {{-- <div class="row" id="weekly-schedule">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title modern-title">Weekly Schedule</h4>
              <div class="box-controls pull-right">
                <a href="AR/schedule/scheduleTable/{{ Auth::guard('student')->user()->ic }}?type=std" class="btn btn-primary btn-sm">
                  <i class="mdi mdi-calendar-month me-1"></i> Full Timetable
                </a>
              </div>
            </div>
            <div class="box-body p-0">
              <div id="weekly-schedule-container">
                <!-- Weekly schedule will be loaded here -->
                <div class="text-center p-5">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="mt-2 text-muted">Loading your schedule...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
      
      <!-- Upcoming Deadlines -->
      <div class="row">
        <div class="col-xl-6 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title modern-title">Upcoming Deadlines
                <span class="badge bg-secondary ms-1">Available Soon</span>
              </h4>
            </div>
            <div class="box-body p-0">
              <div class="p-4">
                <!-- Deadline Item 1 -->
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                  <div class="me-3 bg-primary-light rounded p-3 text-center" style="min-width: 60px;">
                    <h3 class="mb-0 fw-bold">15</h3>
                    <span class="text-muted">Mar</span>
                  </div>
                  <div class="flex-grow-1">
                    <h5 class="mb-0">Physics Assignment</h5>
                    <p class="mb-0 text-muted">Wave Mechanics - Chapter 4</p>
                  </div>
                  <div>
                    <span class="badge bg-warning">Tomorrow</span>
                  </div>
                </div>
                
                <!-- Deadline Item 2 -->
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                  <div class="me-3 bg-primary-light rounded p-3 text-center" style="min-width: 60px;">
                    <h3 class="mb-0 fw-bold">18</h3>
                    <span class="text-muted">Mar</span>
                  </div>
                  <div class="flex-grow-1">
                    <h5 class="mb-0">Database Project</h5>
                    <p class="mb-0 text-muted">SQL Implementation</p>
                  </div>
                  <div>
                    <span class="badge bg-info">3 Days</span>
                  </div>
                </div>
                
                <!-- Deadline Item 3 -->
                <div class="d-flex align-items-center">
                  <div class="me-3 bg-primary-light rounded p-3 text-center" style="min-width: 60px;">
                    <h3 class="mb-0 fw-bold">22</h3>
                    <span class="text-muted">Mar</span>
                  </div>
                  <div class="flex-grow-1">
                    <h5 class="mb-0">Mid-Semester Exam</h5>
                    <p class="mb-0 text-muted">All core subjects</p>
                  </div>
                  <div>
                    <span class="badge bg-secondary">1 Week</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Quick Links -->
        <div class="col-xl-6 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title modern-title">Quick Access</h4>
            </div>
            <div class="box-body">
              <div class="row g-3">
                <!-- Quick Access Item 1 -->
                <div class="col-6 col-sm-4">
                  <a href="{{ route('student') }}" class="quick-access-item">
                    <div class="icon-container bg-gradient-blue">
                      <i class="mdi mdi-book-open-page-variant fs-24"></i>
                    </div>
                    <h6>My Courses</h6>
                  </a>
                </div>
                
                <!-- Quick Access Item 2 -->
                <div class="col-6 col-sm-4">
                  <a href="AR/schedule/scheduleTable/{{ Auth::guard('student')->user()->ic }}?type=std" class="quick-access-item" target="_blank">
                    <div class="icon-container bg-gradient-green">
                      <i class="mdi mdi-calendar-clock fs-24"></i>
                    </div>
                    <h6>Class Timetable</h6>
                  </a>
                </div>
                
                <!-- Quick Access Item 3 -->
                @php
                $now = now();
                $block_status = Auth::guard('student')->user()->block_status;
                $student = Session::get('User');
                
                // Check if there are any active result periods that match this student
                $hasActiveResultPeriod = false;
                
                if ($student) {
                    $activePeriods = DB::table('tblresult_period')
                        ->where('Start', '<=', $now)
                        ->where('End', '>=', $now)
                        ->get();
                    
                    foreach ($activePeriods as $period) {
                        $programs = json_decode($period->program, true) ?: [];
                        $sessions = json_decode($period->session, true) ?: [];
                        $semesters = json_decode($period->semester, true) ?: [];
                        
                        if (in_array($student->program, $programs) && 
                            in_array($student->session, $sessions) && 
                            in_array($student->semester, $semesters)) {
                            $hasActiveResultPeriod = true;
                            break;
                        }
                    }
                }
                @endphp

                @if($hasActiveResultPeriod && $block_status == 0)                <div class="col-6 col-sm-4">
                  <a href="{{ route('student.affair.result') }}" class="quick-access-item">
                    <div class="icon-container bg-gradient-orange">
                      <i class="mdi mdi-chart-line fs-24"></i>
                    </div>
                    <h6>My Results</h6>
                  </a>
                </div>
                @endif
                
                <!-- Quick Access Item 4 -->
                <div class="col-6 col-sm-4">
                  <a href="{{ route('student.setting') }}" class="quick-access-item">
                    <div class="icon-container bg-gradient-purple">
                      <i class="mdi mdi-account fs-24"></i>
                    </div>
                    <h6>Profile Settings</h6>
                  </a>
                </div>
                
                <!-- Quick Access Item 5 -->
                <div class="col-6 col-sm-4">
                  <a href="{{ route('yuran-pengajian') }}" class="quick-access-item">
                    <div class="icon-container bg-gradient-red">
                      <i class="mdi mdi-credit-card fs-24"></i>
                    </div>
                    <h6>Payment</h6>
                  </a>
                </div>
                
                <!-- Quick Access Item 6 -->
                <div class="col-6 col-sm-4">
                  <a href="{{ route('student.announcement.list') }}" class="quick-access-item">
                    <div class="icon-container bg-gradient-teal">
                      <i class="mdi mdi-bullhorn fs-24"></i>
                    </div>
                    <h6>Announcements</h6>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Class Details Modal -->
<div class="modal fade" id="classDetailsModal" tabindex="-1" aria-labelledby="classDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="classDetailsModalLabel">Class Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="classDetailsContent">
          <!-- Class details will be populated here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Custom Dashboard Styles -->
<style>
  /* Custom Dashboard Styles with FullCalendar enhancements */
.bg-primary-light { background-color: rgba(67, 97, 238, 0.1); }
.bg-success-light { background-color: rgba(76, 201, 240, 0.1); }
.bg-warning-light { background-color: rgba(247, 37, 133, 0.1); }
.bg-info-light { background-color: rgba(72, 149, 239, 0.1); }
.bg-danger-light { background-color: rgba(230, 57, 70, 0.1); }
.bg-secondary-light { background-color: rgba(33, 37, 41, 0.1); }

.box {
  border-radius: 12px;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  overflow: hidden;
}

.box:hover {
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
}

.class-card {
  border-radius: 8px;
  border: 1px solid #e0e0e0;
  padding: 12px;
  margin-bottom: 12px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.class-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.class-card.current-class {
  border: 2px solid #4361ee;
  box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
}

.current-date {
  font-size: 0.9rem;
  color: #555;
}

/* Animation for class cards */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animated-card {
  animation: fadeIn 0.3s ease forwards;
}

/* Empty state styling */
.empty-state {
  text-align: center;
  padding: 40px 20px;
}

.empty-state-icon {
  font-size: 48px;
  color: #ccc;
  margin-bottom: 15px;
}

/* FullCalendar custom styling */
.fc-theme-standard .fc-scrollgrid {
  border: 1px solid #e0e0e0;
}

.fc .fc-daygrid-day.fc-day-today,
.fc .fc-timegrid-col.fc-day-today {
  background-color: rgba(67, 97, 238, 0.05);
}

.fc .fc-timegrid-slot {
  height: 40px !important;
  border-bottom: 1px solid #e0e0e0;
}

.fc .fc-timegrid-slot-lane {
  background-color: #f8f9fa;
}

.fc .fc-col-header-cell-cushion {
  padding: 10px;
  color: #495057;
  font-weight: 600;
}

.fc .fc-timegrid-axis-cushion {
  font-weight: 500;
  color: #6c757d;
}

.fc-direction-ltr .fc-timegrid-slot-label-frame {
  text-align: center;
}

/* Enhance event styling */
.fc-event {
  border-radius: 6px;
  overflow: visible !important;
  border: none;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Style for REHAT events */
.fc-event[style*="rgb(230, 57, 70)"],
.fc-event[style*="#e63946"] {
  background-color: #e63946 !important;
  border-color: #e63946 !important;
}

.event-program, .event-lecturer {
  font-size: 0.65rem;
  opacity: 0.9;
  padding: 2px 4px;
  margin-top: 2px;
  border-radius: 2px;
}

.event-program {
  background-color: rgba(0, 0, 0, 0.1);
}

.event-lecturer {
  background-color: rgba(255, 255, 255, 0.25);
}

/* Now indicator styling */
.fc .fc-timegrid-now-indicator-line {
  border-color: #4361ee;
  border-width: 2px;
}

.fc .fc-timegrid-now-indicator-arrow {
  border-color: #4361ee;
  border-width: 5px;
}

/* Button styling */
.fc .fc-button-primary {
  background-color: #4361ee;
  border-color: #4361ee;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 0.75rem;
  border-radius: 6px;
  padding: 0.4rem 0.8rem;
}

.fc .fc-button-primary:hover {
  background-color: #3a0ca3;
  border-color: #3a0ca3;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

.fc .fc-button-primary:not(:disabled):active,
.fc .fc-button-primary:not(:disabled).fc-button-active {
  background-color: #3a0ca3;
  border-color: #3a0ca3;
}

.fc .fc-button-primary:focus {
  box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

/* Toolbar spacing */
.fc .fc-toolbar {
  margin-bottom: 1.5em;
}

.fc .fc-toolbar.fc-header-toolbar {
  margin-bottom: 1em;
}

/* Responsive tweaks */
@media (max-width: 768px) {
  .fc .fc-toolbar {
    flex-direction: column;
    gap: 10px;
  }
  
  .fc-header-toolbar .fc-toolbar-chunk {
    display: flex;
    justify-content: center;
  }
  
  .fc .fc-button {
    padding: 0.3rem 0.6rem;
    font-size: 0.7rem;
  }
}

/* Modern Quick Access Styling */
.quick-access-item {
display: flex;
flex-direction: column;
align-items: center;
padding: 1.25rem 1rem;
border-radius: 1rem;
background-color: #f8f9fa;
text-decoration: none;
transition: all 0.3s ease;
border: 1px solid rgba(0,0,0,0.05);
height: 100%;
position: relative;
overflow: hidden;
z-index: 1;
}

.quick-access-item::before {
content: '';
position: absolute;
top: 0;
left: 0;
right: 0;
height: 0;
background-color: rgba(255,255,255,0.2);
transition: height 0.3s ease;
z-index: -1;
}

.quick-access-item:hover {
transform: translateY(-5px);
box-shadow: 0 10px 15px rgba(0,0,0,0.1);
}

.quick-access-item:hover::before {
height: 100%;
}

.quick-access-item .icon-container {
width: 64px;
height: 64px;
display: flex;
justify-content: center;
align-items: center;
border-radius: 18px;
margin-bottom: 1rem;
color: white;
box-shadow: 0 4px 10px rgba(0,0,0,0.1);
transition: all 0.3s ease;
}

.quick-access-item:hover .icon-container {
transform: scale(1.1);
}

.quick-access-item h6 {
color: #495057;
font-weight: 600;
margin: 0;
transition: color 0.3s ease;
}

.quick-access-item:hover h6 {
color: #000;
}

/* Gradient Backgrounds */
.bg-gradient-blue {
background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.bg-gradient-green {
background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.bg-gradient-orange {
background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.bg-gradient-purple {
background: linear-gradient(135deg, #c471f5 0%, #fa71cd 100%);
}

.bg-gradient-red {
background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
}

.bg-gradient-teal {
background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
}

@media (max-width: 768px) {
.quick-access-item {
  padding: 1rem 0.75rem;
}

.quick-access-item .icon-container {
  width: 50px;
  height: 50px;
  border-radius: 15px;
}
}

/* Quick Messaging Widget Styles */
.quick-message-section {
  padding: 1.5rem;
  min-height: 350px;
}

.section-header {
  margin-bottom: 1.5rem;
  border-bottom: 1px solid #e9ecef;
  padding-bottom: 0.75rem;
}

.section-title {
  font-size: 1rem;
  font-weight: 600;
  margin: 0;
  color: #495057;
  display: flex;
  align-items: center;
}

/* Admin Messages Styles */
.admin-messages-container {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.admin-message-item {
  display: flex;
  align-items: center;
  padding: 1rem;
  border-radius: 0.75rem;
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.admin-message-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 100%;
  background: linear-gradient(90deg, rgba(79, 129, 199, 0.1), transparent);
  transition: width 0.3s ease;
  z-index: 0;
}

.admin-message-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-color: #4f81c7;
}

.admin-message-item:hover::before {
  width: 100%;
}

.message-avatar {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.25rem;
  margin-right: 1rem;
  z-index: 1;
  position: relative;
}

.message-details {
  flex: 1;
  z-index: 1;
  position: relative;
}

.message-name {
  font-weight: 600;
  font-size: 0.9rem;
  color: #333;
  margin-bottom: 0.25rem;
}

.message-preview {
  font-size: 0.8rem;
  color: #6c757d;
  line-height: 1.3;
}

.message-badge {
  z-index: 1;
  position: relative;
}

/* Student Messages Styles */
.student-search-container {
  margin-bottom: 1.5rem;
}

.search-input-group {
  position: relative;
  background: #f8f9fa;
  border-radius: 0.75rem;
  border: 1px solid #e9ecef;
  padding: 0.75rem;
  display: flex;
  align-items: center;
  transition: all 0.3s ease;
}

.search-input-group:focus-within {
  border-color: #4f81c7;
  box-shadow: 0 0 0 0.2rem rgba(79, 129, 199, 0.25);
}

.search-icon {
  color: #6c757d;
  margin-right: 0.75rem;
  font-size: 1.1rem;
}

.search-input {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  font-size: 0.9rem;
  color: #495057;
}

.search-input::placeholder {
  color: #adb5bd;
}

.search-results {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: white;
  border: 1px solid #e9ecef;
  border-radius: 0.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  max-height: 200px;
  overflow-y: auto;
  z-index: 1000;
  display: none;
  margin-top: 0.5rem;
}

.search-results.active {
  display: block;
}

.student-conversations-container {
  max-height: 200px;
  overflow-y: auto;
}

.student-conversations {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.empty-message-state {
  text-align: center;
  padding: 2rem 1rem;
  color: #6c757d;
}

.empty-message-state i {
  font-size: 2rem;
  margin-bottom: 0.75rem;
  color: #adb5bd;
}

.empty-message-state p {
  font-weight: 500;
  margin-bottom: 0.25rem;
  color: #495057;
}

.empty-message-state span {
  font-size: 0.85rem;
  color: #6c757d;
}

/* Search Result Items */
.dashboard-search-item {
  padding: 0.75rem;
  cursor: pointer;
  border-bottom: 1px solid #f0f0f0;
  transition: background-color 0.2s ease;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.dashboard-search-item:hover {
  background-color: #f8f9fa;
}

.dashboard-search-item:last-child {
  border-bottom: none;
}

.dashboard-search-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #4f81c7, #667eea);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.9rem;
  flex-shrink: 0;
}

.dashboard-search-details {
  flex: 1;
  min-width: 0;
}

.dashboard-search-name {
  font-weight: 600;
  font-size: 0.85rem;
  color: #333;
  margin-bottom: 0.125rem;
}

.dashboard-search-info {
  font-size: 0.75rem;
  color: #6c757d;
}

/* Conversation Items */
.dashboard-conversation-item {
  display: flex;
  align-items: center;
  padding: 0.75rem;
  cursor: pointer;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  gap: 0.75rem;
  border: 1px solid transparent;
}

.dashboard-conversation-item:hover {
  background-color: #f8f9fa;
  border-color: #e9ecef;
}

.dashboard-conversation-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #4f81c7, #667eea);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1rem;
  flex-shrink: 0;
  position: relative;
}

.dashboard-conversation-avatar.online::after {
  content: '';
  position: absolute;
  bottom: 0;
  right: 0;
  width: 12px;
  height: 12px;
  background: #2ed573;
  border: 2px solid white;
  border-radius: 50%;
}

.dashboard-conversation-details {
  flex: 1;
  min-width: 0;
}

.dashboard-conversation-name {
  font-weight: 600;
  font-size: 0.85rem;
  color: #333;
  margin-bottom: 0.125rem;
}

.dashboard-conversation-preview {
  font-size: 0.75rem;
  color: #6c757d;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.dashboard-conversation-preview.unread {
  font-weight: 600;
  color: #333;
}

.dashboard-conversation-meta {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.dashboard-unread-badge {
  background: #ff4757;
  color: white;
  border-radius: 50%;
  min-width: 24px;
  height: 24px;
  font-size: 0.8rem;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(255, 71, 87, 0.3);
}

/* Dashboard-specific count circles - scoped to avoid sidebar conflicts */
.quick-message-section .count-circle {
  background: #ff4757;
  color: white;
  border-radius: 50%;
  min-width: 24px;
  height: 24px;
  font-size: 0.8rem;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(255, 71, 87, 0.3);
}

.quick-message-section .count-circle.hidden {
  display: none !important;
}

/* Responsive Design */
@media (max-width: 1199px) {
  .quick-message-section {
    border-bottom: 1px solid #e9ecef !important;
    border-right: none !important;
  }
  
  .quick-message-section:last-child {
    border-bottom: none !important;
  }
}

@media (max-width: 768px) {
  .quick-message-section {
    padding: 1rem;
    min-height: 300px;
  }
  
  .admin-message-item {
    padding: 0.75rem;
  }
  
  .message-avatar {
    width: 40px;
    height: 40px;
    font-size: 1rem;
  }
  
  .message-name {
    font-size: 0.85rem;
  }
  
  .message-preview {
    font-size: 0.75rem;
  }
}
</style>

<!-- Dashboard JavaScript -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
  // Update current date
  updateCurrentDate();
  
  // Load today's classes
  loadTodayClasses();
  
  // Update weather animation based on time of day
  updateWeatherAnimation();
          
  // Fetch live weather data
  fetchWeatherData();
  
  // Initialize Weekly Schedule with FullCalendar
  //initializeWeeklySchedule();
});

// Update current date in header
function updateCurrentDate() {
  const currentDateEl = document.getElementById('current-date');
  const todayDateEl = document.getElementById('today-date');
  
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  const today = new Date();
  const dateString = today.toLocaleDateString('en-US', options);
  
  if (currentDateEl) {
    currentDateEl.textContent = dateString;
  }
  
  if (todayDateEl) {
    todayDateEl.textContent = today.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' });
  }
}

// Update weather animation based on time of day
function updateWeatherAnimation() {
  const weatherIcon = document.querySelector('.weather-icon');
  if (!weatherIcon) return;
  
  const hour = new Date().getHours();
  
  if (hour >= 6 && hour < 18) {
    // Daytime - Already has sun
  } else {
    // Nighttime - Replace with moon
    weatherIcon.innerHTML = `
      <div class="moon-icon">
        <div class="moon"></div>
        <div class="stars"></div>
      </div>
    `;
    
    // Add nighttime CSS
    const style = document.createElement('style');
    style.textContent = `
      .weather-card {
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      }
      .moon {
        width: 50px;
        height: 50px;
        background: #f1f1f1;
        border-radius: 50%;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
      }
      .moon-icon {
        position: relative;
        animation: float 3s ease-in-out infinite;
      }
      .stars {
        position: absolute;
        top: -20px;
        left: -20px;
        right: -20px;
        bottom: -20px;
      }
      .stars:before, .stars:after {
        content: '✦';
        position: absolute;
        color: white;
        font-size: 12px;
      }
      .stars:before {
        top: 10px;
        left: 30px;
        animation: twinkle 2s ease-in-out infinite;
      }
      .stars:after {
        top: 30px;
        left: 10px;
        animation: twinkle 3s ease-in-out infinite;
      }
      @keyframes twinkle {
        0%, 100% { opacity: 0.2; }
        50% { opacity: 1; }
      }
    `;
    document.head.appendChild(style);
  }
}

// Fetch real-time weather data for Port Dickson
function fetchWeatherData() {
  console.log('Starting weather data fetch process...');
  
  // Show loading state
  document.getElementById('current-temperature').innerHTML = `
    <div class="spinner-border spinner-border-sm text-light" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  `;
  document.getElementById('weather-description').textContent = 'Loading weather data...';
  
  // Fetch weather data from our Laravel backend
  fetch('/weather/current')
    .then(response => {
      console.log('Weather API response status:', response.status);
      if (!response.ok) {
        throw new Error(`Weather API error: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      console.log('✅ Successfully retrieved weather data');
      console.log('Weather data:', data);
      updateWeatherUI(data);
    })
    .catch(error => {
      console.error('❌ Error fetching weather data:', error);
      // The backend will handle fallback data, so we don't need additional fallback here
    });
  
  // Schedule the next update in 30 minutes
  console.log('Scheduling next weather update in 30 minutes');
  setTimeout(fetchWeatherData, 1800000);
}

// Helper function to map weather conditions to weather codes
function getWeatherCode(condition) {
  if (!condition) return 800;
  condition = condition.toLowerCase();
  
  if (condition.includes('thunder') || condition.includes('storm')) return 200;
  if (condition.includes('drizzle')) return 300;
  if (condition.includes('rain')) return 500;
  if (condition.includes('snow')) return 600;
  if (condition.includes('mist') || condition.includes('fog')) return 700;
  if (condition.includes('clear')) return 800;
  if (condition.includes('cloud')) {
    if (condition.includes('scattered')) return 802;
    if (condition.includes('broken')) return 803;
    if (condition.includes('overcast')) return 804;
    return 801;
  }
  return 800;
}

// Update the weather UI with the fetched data
function updateWeatherUI(data) {
  console.log('Updating weather UI with data:', data);
  
  const tempElement = document.getElementById('current-temperature');
  const descElement = document.getElementById('weather-description');
  const iconContainer = document.getElementById('weather-icon');
  
  if (!tempElement || !descElement || !iconContainer) {
    console.error('Weather UI elements not found in the DOM');
    return;
  }
  
  // Update temperature
  const temperature = Math.round(data.main.temp);
  console.log('Setting temperature to:', temperature + '°C');
  tempElement.textContent = `${temperature}°C`;
  
  // Update weather description
  const description = data.weather[0].description;
  const formattedDescription = description.charAt(0).toUpperCase() + description.slice(1);
  console.log('Setting weather description to:', formattedDescription);
  descElement.textContent = formattedDescription;
  
  // Update icon based on weather condition
  const weatherCode = data.weather[0].id;
  const isDay = isItDaytime();
  console.log('Weather code:', weatherCode, 'Is daytime:', isDay);
  
  // Clear the container
  iconContainer.innerHTML = '';
  
  // Append the appropriate weather icon
  const weatherIcon = createWeatherIcon(weatherCode, isDay);
  iconContainer.appendChild(weatherIcon);
  console.log('Weather icon updated based on condition:', weatherCode);
}

// Check if it's daytime
function isItDaytime() {
  const hour = new Date().getHours();
  return hour >= 6 && hour < 18;
}

// Create weather icon based on condition code
function createWeatherIcon(code, isDay) {
  const iconWrap = document.createElement('div');
  
  // Weather condition groups
  // 2xx: Thunderstorm, 3xx: Drizzle, 5xx: Rain, 6xx: Snow, 
  // 7xx: Atmosphere, 800: Clear, 80x: Clouds
  
  if (code >= 200 && code < 300) {
    // Thunderstorm
    iconWrap.innerHTML = `
      <div class="thunder-icon">
        <div class="cloud"></div>
        <div class="lightning"></div>
      </div>
    `;
    
    // Add styles
    const style = document.createElement('style');
    style.textContent = `
      .thunder-icon {
        position: relative;
        animation: float 3s ease-in-out infinite;
      }
      .cloud {
        width: 50px;
        height: 30px;
        background: #bbb;
        border-radius: 20px;
        position: relative;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
      }
      .cloud:before {
        content: '';
        position: absolute;
        top: -15px;
        left: 10px;
        width: 30px;
        height: 30px;
        background: #bbb;
        border-radius: 50%;
      }
      .lightning {
        position: absolute;
        top: 25px;
        left: 20px;
        width: 10px;
        height: 25px;
        background: #ffeb3b;
        clip-path: polygon(40% 0%, 100% 50%, 60% 50%, 100% 100%, 0% 50%, 50% 50%);
        animation: flash 2s ease-in-out infinite;
      }
      @keyframes flash {
        0%, 20%, 40% { opacity: 0; }
        10%, 30%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
      }
    `;
    document.head.appendChild(style);
  } else if ((code >= 300 && code < 400) || (code >= 500 && code < 600)) {
    // Rain or drizzle
    iconWrap.innerHTML = `
      <div class="rain-icon">
        <div class="cloud"></div>
        <div class="rain"></div>
      </div>
    `;
    
    // Add styles
    const style = document.createElement('style');
    style.textContent = `
      .rain-icon {
        position: relative;
        animation: float 3s ease-in-out infinite;
      }
      .cloud {
        width: 50px;
        height: 30px;
        background: #bbb;
        border-radius: 20px;
        position: relative;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
      }
      .cloud:before {
        content: '';
        position: absolute;
        top: -15px;
        left: 10px;
        width: 30px;
        height: 30px;
        background: #bbb;
        border-radius: 50%;
      }
      .rain {
        position: absolute;
        top: 30px;
        left: 18px;
        width: 3px;
        height: 10px;
        background: #8bc4ee;
        border-radius: 2px;
        box-shadow: 10px 0 0 #8bc4ee, 20px 0 0 #8bc4ee, 
                    5px 15px 0 #8bc4ee, 15px 15px 0 #8bc4ee;
        animation: rain 1.5s ease-in-out infinite;
      }
      @keyframes rain {
        0% { transform: translateY(-5px); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translateY(15px); opacity: 0; }
      }
    `;
    document.head.appendChild(style);
  } else if (code >= 600 && code < 700) {
    // Snow
    iconWrap.innerHTML = `
      <div class="snow-icon">
        <div class="cloud"></div>
        <div class="snowflakes"></div>
      </div>
    `;
    
    // Add styles
    const style = document.createElement('style');
    style.textContent = `
      .snow-icon {
        position: relative;
        animation: float 3s ease-in-out infinite;
      }
      .cloud {
        width: 50px;
        height: 30px;
        background: #bbb;
        border-radius: 20px;
        position: relative;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
      }
      .cloud:before {
        content: '';
        position: absolute;
        top: -15px;
        left: 10px;
        width: 30px;
        height: 30px;
        background: #bbb;
        border-radius: 50%;
      }
      .snowflakes {
        position: absolute;
        top: 30px;
        left: 15px;
      }
      .snowflakes:before, .snowflakes:after {
        content: '❄';
        font-size: 15px;
        color: white;
        position: absolute;
        animation: snowfall 2s ease-in-out infinite;
      }
      .snowflakes:before {
        left: 0;
        animation-delay: 0s;
      }
      .snowflakes:after {
        left: 20px;
        animation-delay: 1s;
      }
      @keyframes snowfall {
        0% { transform: translateY(-5px); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translateY(15px); opacity: 0; }
      }
    `;
    document.head.appendChild(style);
  } else if (code === 800) {
    // Clear sky
    if (isDay) {
      // Daytime clear - sun
      iconWrap.innerHTML = `
        <div class="sun-icon">
          <div class="sun"></div>
          <div class="rays"></div>
        </div>
      `;
    } else {
      // Nighttime clear - moon
      iconWrap.innerHTML = `
        <div class="moon-icon">
          <div class="moon"></div>
          <div class="stars"></div>
        </div>
      `;
      
      // Add nighttime CSS
      const style = document.createElement('style');
      style.textContent = `
        .moon {
          width: 50px;
          height: 50px;
          background: #f1f1f1;
          border-radius: 50%;
          box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }
        .moon-icon {
          position: relative;
          animation: float 3s ease-in-out infinite;
        }
        .stars {
          position: absolute;
          top: -20px;
          left: -20px;
          right: -20px;
          bottom: -20px;
        }
        .stars:before, .stars:after {
          content: '✦';
          position: absolute;
          color: white;
          font-size: 12px;
        }
        .stars:before {
          top: 10px;
          left: 30px;
          animation: twinkle 2s ease-in-out infinite;
        }
        .stars:after {
          top: 30px;
          left: 10px;
          animation: twinkle 3s ease-in-out infinite;
        }
      `;
      document.head.appendChild(style);
    }
  } else if (code > 800) {
    // Cloudy
    iconWrap.innerHTML = `
      <div class="cloudy-icon">
        <div class="cloud-main"></div>
        <div class="cloud-back"></div>
      </div>
    `;
    
    // Add styles
    const style = document.createElement('style');
    style.textContent = `
      .cloudy-icon {
        position: relative;
        animation: float 3s ease-in-out infinite;
      }
      .cloud-main {
        width: 50px;
        height: 30px;
        background: #ddd;
        border-radius: 20px;
        position: relative;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        z-index: 2;
      }
      .cloud-main:before {
        content: '';
        position: absolute;
        top: -15px;
        left: 10px;
        width: 30px;
        height: 30px;
        background: #ddd;
        border-radius: 50%;
      }
      .cloud-back {
        width: 40px;
        height: 25px;
        background: #f5f5f5;
        border-radius: 15px;
        position: absolute;
        top: -10px;
        right: -10px;
        z-index: 1;
      }
      .cloud-back:before {
        content: '';
        position: absolute;
        top: -10px;
        right: 7px;
        width: 20px;
        height: 20px;
        background: #f5f5f5;
        border-radius: 50%;
      }
    `;
    document.head.appendChild(style);
  } else {
    // Default or atmospheric conditions (fog, mist, etc.)
    iconWrap.innerHTML = `
      <div class="mist-icon">
        <div class="mist-layer"></div>
      </div>
    `;
    
    // Add styles
    const style = document.createElement('style');
    style.textContent = `
      .mist-icon {
        position: relative;
        width: 60px;
        height: 50px;
        animation: float 3s ease-in-out infinite;
      }
      .mist-layer {
        width: 60px;
        height: 15px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        position: absolute;
        top: 10px;
        box-shadow: 0 15px 0 rgba(255, 255, 255, 0.5),
                    0 30px 0 rgba(255, 255, 255, 0.5);
        animation: mistPulse 3s ease-in-out infinite;
      }
      @keyframes mistPulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.7; }
      }
    `;
    document.head.appendChild(style);
  }
  
  return iconWrap;
}

// Class colors with accessibility in mind
const classColors = [
  { bg: '#e3f2fd', border: '#90caf9', text: '#1565c0' }, // Blue
  { bg: '#f3e5f5', border: '#ce93d8', text: '#7b1fa2' }, // Purple
  { bg: '#e8f5e9', border: '#a5d6a7', text: '#2e7d32' }, // Green
  { bg: '#fff8e1', border: '#ffe082', text: '#ff8f00' }, // Amber
  { bg: '#fce4ec', border: '#f48fb1', text: '#c2185b' }, // Pink
  { bg: '#e0f7fa', border: '#80deea', text: '#00838f' }, // Cyan
  { bg: '#fff3e0', border: '#ffcc80', text: '#ef6c00' }, // Orange
  { bg: '#f1f8e9', border: '#c5e1a5', text: '#558b2f' }, // Light Green
];

// Format time (24h to 12h format)
function formatTime(timeStr) {
  if (!timeStr) return '';
  const [hours, minutes] = timeStr.substring(0, 5).split(':');
  const h = parseInt(hours);
  const ampm = h >= 12 ? 'PM' : 'AM';
  const hour12 = h % 12 || 12;
  return `${hour12}:${minutes} ${ampm}`;
}

// Generate random color based on class title for consistent coloring
function getEventColor(title) {
  const colorIndex = Math.abs(title.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0)) % classColors.length;
  return classColors[colorIndex];
}

// Load today's classes
function loadTodayClasses() {
  const container = document.getElementById('today-classes-container');
  if (!container) return;
  
  // Get the current day (0 = Sunday, 1 = Monday, etc.)
  const today = new Date().getDay();
  
  // If it's weekend, show no classes message
  if (today === 0 || today === 6) {
    container.innerHTML = `
      <div class="empty-state">
        <div class="empty-state-icon">
          <i class="mdi mdi-beach"></i>
        </div>
        <h5>No Classes Today</h5>
        <p class="text-muted">Enjoy your weekend!</p>
      </div>
    `;
    return;
  }
  
  // Show loading state
  container.innerHTML = `
    <div class="text-center p-4">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2 text-muted">Loading your classes...</p>
    </div>
  `;
  
  // Fetch schedule data
  fetch(`/AR/schedule/fetch/{{ Session::get('User')->ic ?? '0' }}?type=std`)
    .then(response => response.json())
    .then(data => {
      // Filter for today's classes
      const todayClasses = data.filter(event => {
        const eventDay = event.daysOfWeek ? event.daysOfWeek[0] : null;
        return eventDay === today;
      });
      
      // Sort by start time
      todayClasses.sort((a, b) => {
        return a.startTime.localeCompare(b.startTime);
      });
      
      // If no classes today
      if (todayClasses.length === 0) {
        container.innerHTML = `
          <div class="empty-state">
            <div class="empty-state-icon">
              <i class="mdi mdi-calendar-blank"></i>
            </div>
            <h5>No Classes Today</h5>
            <p class="text-muted">You have no scheduled classes for today.</p>
          </div>
        `;
        return;
      }
      
      // Current time for highlighting
      const now = new Date();
      const currentHour = now.getHours();
      const currentMinute = now.getMinutes();
      const currentTimeMinutes = currentHour * 60 + currentMinute;
      
      // Generate HTML for today's classes
      let classesHTML = '';
      
      todayClasses.forEach((cls, index) => {
        // Parse start and end times to check if class is current
        const [startHour, startMinute] = cls.startTime.split(':').map(Number);
        const startTimeMinutes = startHour * 60 + startMinute;
        
        const [endHour, endMinute] = cls.endTime.split(':').map(Number);
        const endTimeMinutes = endHour * 60 + endMinute;
        
        const isCurrent = currentTimeMinutes >= startTimeMinutes && currentTimeMinutes < endTimeMinutes;
        
        // Generate a consistent color based on the class title
        const color = getEventColor(cls.title);
        
        classesHTML += `
          <div class="class-card ${isCurrent ? 'current-class' : ''} animated-card" 
               style="animation-delay: ${index * 0.1}s; background-color: ${color.bg}; border-color: ${color.border};"
               onclick="showClassDetails('${cls.id}', '${cls.title}', '${cls.startTime}', '${cls.endTime}', '${cls.lectInfo || ''}', '${cls.programInfo || ''}')">
            <div class="d-flex justify-content-between align-items-start">
              <h5 class="mb-1" style="color: ${color.text}">${cls.title}</h5>
              ${isCurrent ? '<span class="badge bg-primary">Now</span>' : ''}
            </div>
            <div class="d-flex justify-content-between align-items-start">
              <h5 class="mb-1" style="color: ${color.text}">${cls.description}</h5>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <div style="color: ${color.text}">
                <i class="mdi mdi-clock-outline"></i> ${formatTime(cls.startTime)} - ${formatTime(cls.endTime)}
              </div>
              ${cls.lectInfo ? `<div style="color: ${color.text}"><i class="mdi mdi-account"></i> ${cls.lectInfo}</div>` : ''}
            </div>
          </div>
        `;
      });
      
      container.innerHTML = classesHTML;
    })
    .catch(error => {
      console.error('Error fetching schedule:', error);
      container.innerHTML = `
        <div class="alert alert-danger" role="alert">
          <i class="mdi mdi-alert-circle"></i> Error loading today's classes. Please try again later.
        </div>
      `;
    });
}

// Show class details in modal
function showClassDetails(id, title, startTime, endTime, lecturer, program) {
  const modalContent = document.getElementById('classDetailsContent');
  const modalTitle = document.getElementById('classDetailsModalLabel');
  
  if (!modalContent || !modalTitle) return;
  
  // Set modal title
  modalTitle.textContent = title;
  
  // Generate modal content
  let content = `
    <div class="mb-4">
      <label class="form-label fw-bold text-primary mb-1">
        <i class="mdi mdi-clock-outline me-1"></i> Time
      </label>
      <div class="p-2 bg-light rounded">${formatTime(startTime)} - ${formatTime(endTime)}</div>
    </div>
  `;
  
  if (lecturer) {
    content += `
      <div class="mb-4">
        <label class="form-label fw-bold text-primary mb-1">
          <i class="mdi mdi-account me-1"></i> Lecturer
        </label>
        <div class="p-2 bg-light rounded">${lecturer}</div>
      </div>
    `;
  }
  
  if (program) {
    content += `
      <div class="mb-4">
        <label class="form-label fw-bold text-primary mb-1">
          <i class="mdi mdi-school me-1"></i> Program
        </label>
        <div class="p-2 bg-light rounded">${program}</div>
      </div>
    `;
  }
  
  modalContent.innerHTML = content;
  
  // Show the modal
  const modal = new bootstrap.Modal(document.getElementById('classDetailsModal'));
  modal.show();
}

// Initialize Weekly Schedule using FullCalendar
// function initializeWeeklySchedule() {
//   const scheduleContainer = document.getElementById('weekly-schedule-container');
//   if (!scheduleContainer) return;
  
//   // Show loading state
//   scheduleContainer.innerHTML = `
//     <div class="text-center p-5">
//       <div class="spinner-border text-primary" role="status">
//         <span class="visually-hidden">Loading...</span>
//       </div>
//       <p class="mt-2 text-muted">Loading your schedule...</p>
//     </div>
//   `;
  
  // Create a div element for the calendar
//   const calendarDiv = document.createElement('div');
//   calendarDiv.id = 'dashboard-calendar';
//   calendarDiv.style.height = '600px'; // Set height for the calendar
//   scheduleContainer.innerHTML = '';
//   scheduleContainer.appendChild(calendarDiv);
  
//   // Initialize FullCalendar
//   const calendar = new FullCalendar.Calendar(calendarDiv, {
//     initialView: 'timeGridWeek',
//     headerToolbar: {
//       left: 'prev,next today',
//       center: 'title',
//       right: 'timeGridWeek,timeGridDay'
//     },
//     hiddenDays: [0, 6], // Hide Sunday(0) & Saturday(6)
//     slotMinTime: '08:30:00',
//     slotMaxTime: '18:00:00', 
//     slotDuration: '00:30:00',
//     height: 'auto',
//     allDaySlot: false,
//     nowIndicator: true,
//     weekNumbers: false,
//     dayHeaderFormat: { weekday: 'long', day: 'numeric' },
    
//     // Events fetching (REHAT + dynamic events)
//     events: function(fetchInfo, successCallback, failureCallback) {
//       // 1) Generate "REHAT" events
//       const rehatEvents = [];
//       let date = new Date(fetchInfo.start);
      
//       while (date < fetchInfo.end) {
//         const dayOfWeek = date.getDay(); 
        
//         if (dayOfWeek >= 1 && dayOfWeek <= 4) {
//           // Monday-Thursday => 13:30 to 14:00
//           rehatEvents.push({
//             id: 'rehat-' + date.toISOString(),
//             title: 'REHAT',
//             start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 13, 30),
//             end: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 14, 0),
//             color: '#e63946',
//             borderColor: '#e63946',
//             textColor: '#ffffff'
//           });
//         } else if (dayOfWeek === 5) {
//           // Friday => 12:30 to 14:30
//           rehatEvents.push({
//             id: 'rehat-' + date.toISOString(),
//             title: 'REHAT',
//             start: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 12, 30),
//             end: new Date(date.getFullYear(), date.getMonth(), date.getDate(), 14, 30),
//             color: '#e63946',
//             borderColor: '#e63946',
//             textColor: '#ffffff'
//           });
//         }
        
//         // Move to next day
//         date.setDate(date.getDate() + 1);
//       }
      
//       // 2) Fetch dynamic events
//       fetch(`/AR/schedule/fetch/{{ Session::get('User')->ic ?? '0' }}?type=std`)
//         .then(response => response.json())
//         .then(data => {
//           const formattedEvents = data.map(event => {
//             // Create events for each day of the week
//             if (event.daysOfWeek && event.startTime && event.endTime) {
//               // Get the specific day of the week from daysOfWeek
//               const dayOfWeek = event.daysOfWeek[0]; // 1 = Monday, 5 = Friday
              
//               // Generate a unique color based on the event title
//               const color = getEventColor(event.title);
              
//               // We need to create actual Date objects for the event
//               // Get the week start and find the corresponding day
//               const weekStart = new Date(fetchInfo.start);
//               const targetDay = new Date(weekStart);
              
//               // Adjust to the correct day of the week
//               const daysToAdd = (dayOfWeek - weekStart.getDay() + 7) % 7;
//               targetDay.setDate(weekStart.getDate() + daysToAdd);
              
//               // Parse start and end times
//               const [startHour, startMinute] = event.startTime.split(':').map(Number);
//               const [endHour, endMinute] = event.endTime.split(':').map(Number);
              
//               // Create Date objects for start and end
//               const startDate = new Date(targetDay);
//               startDate.setHours(startHour, startMinute, 0);
              
//               const endDate = new Date(targetDay);
//               endDate.setHours(endHour, endMinute, 0);
              
//               return {
//                 id: event.id,
//                 title: event.title,
//                 start: startDate,
//                 end: endDate,
//                 extendedProps: {
//                   programInfo: event.programInfo || '',
//                   lectInfo: event.lectInfo || '',
//                   description: event.description || ''
//                 },
//                 backgroundColor: color.bg,
//                 borderColor: color.border,
//                 textColor: color.text
//               };
//             }
//             return null;
//           }).filter(event => event !== null);
          
//           // Combine REHAT events with regular events
//           successCallback([...rehatEvents, ...formattedEvents]);
//         })
//         .catch(error => {
//           console.error('Error fetching events:', error);
//           failureCallback(error);
//         });
//     },
    
//     // Event content formatting
//     eventContent: function(arg) {
//       // For REHAT events, use a simplified display
//       if (arg.event.title === 'REHAT') {
//         return { 
//           html: `<div class="fc-event-title-container">
//                   <div class="fc-event-title fc-sticky">${arg.event.title}</div>
//                 </div>` 
//         };
//       }
      
//       // For regular classes, show more information
//       const timeText = arg.timeText;
//       const title = arg.event.title;
//       const lectInfo = arg.event.extendedProps.lectInfo || '';
//       const programInfo = arg.event.extendedProps.programInfo || '';
      
//       return { 
//         html: `<div class="fc-event-title-container">
//                 <div class="fc-event-time">${timeText}</div>
//                 <div class="fc-event-title fc-sticky">${title}</div>
//                 ${programInfo ? '<div class="event-program"><small>Program: ' + programInfo + '</small></div>' : ''}
//                 ${lectInfo ? '<div class="event-lecturer"><small>Lecturer: ' + lectInfo + '</small></div>' : ''}
//               </div>` 
//       };
//     },
    
//     // When clicking an event
//     eventClick: function(info) {
//       // Show event details in modal
//       showClassDetails(
//         info.event.id,
//         info.event.title, 
//         info.event.start.toTimeString().substring(0, 5), 
//         info.event.end.toTimeString().substring(0, 5),
//         info.event.extendedProps.lectInfo || '',
//         info.event.extendedProps.programInfo || ''
//       );
//     }
//   });
  
//   // Render the calendar
//   calendar.render();
// }

// Function to update the date display in the modern format
function updateCurrentDateDisplay() {
    const dayEl = document.getElementById('current-day');
    const dateEl = document.getElementById('current-date');
    
    if (!dayEl || !dateEl) return;
    
    const now = new Date();
    const dayOptions = { weekday: 'long' };
    const dateOptions = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
    
    dayEl.textContent = now.toLocaleDateString('en-US', dayOptions);
    dateEl.textContent = now.toLocaleDateString('en-US', dateOptions);
  }
  
// Call this function when the document is loaded
document.addEventListener('DOMContentLoaded', function() {
  updateCurrentDateDisplay();
  
  // Initialize dashboard messaging widget
  initializeDashboardMessaging();
});

// Fallback weather data in case API fails
function fallbackWeatherData() {
  console.log('Generating fallback weather data based on current time...');
  const now = new Date();
  const hour = now.getHours();
  const isDay = hour >= 6 && hour < 18;
  console.log('Current hour:', hour, 'Is daytime:', isDay);
  
  // Generate a semi-realistic temperature based on the time of day
  // Typically warmer in the afternoon, cooler at night/morning
  let temperature;
  if (hour >= 12 && hour < 15) {
    // Afternoon (hottest)
    temperature = Math.floor(Math.random() * 3) + 31; // 31-33°C
  } else if ((hour >= 9 && hour < 12) || (hour >= 15 && hour < 18)) {
    // Mid-morning or late afternoon
    temperature = Math.floor(Math.random() * 3) + 29; // 29-31°C
  } else if (hour >= 6 && hour < 9) {
    // Early morning
    temperature = Math.floor(Math.random() * 3) + 26; // 26-28°C
  } else {
    // Night
    temperature = Math.floor(Math.random() * 3) + 24; // 24-26°C
  }
  
  console.log('Generated temperature:', temperature + '°C');
  
  // Weather condition options based on time
  let weatherOptions;
  if (isDay) {
    weatherOptions = [
      { id: 800, description: "Clear sky" },
      { id: 801, description: "Few clouds" },
      { id: 802, description: "Scattered clouds" },
      { id: 500, description: "Light rain" }
    ];
  } else {
    weatherOptions = [
      { id: 800, description: "Clear sky" },
      { id: 801, description: "Partly cloudy" },
      { id: 802, description: "Cloudy" }
    ];
  }
  
  // Select a random weather condition
  const weather = weatherOptions[Math.floor(Math.random() * weatherOptions.length)];
  console.log('Generated weather condition:', weather.description);
  
  return {
    main: { temp: temperature },
    weather: [{ id: weather.id, description: weather.description }]
  };
}

// Manual refresh for debugging
function manualWeatherRefresh() {
  console.log('🔄 Manual weather refresh triggered');
  // Show loading state on button
  const refreshBtn = document.getElementById('refresh-weather-btn');
  if (refreshBtn) {
    refreshBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    refreshBtn.disabled = true;
  }
  
  // Clear console for clean logs
  console.clear();
  console.log('📊 WEATHER API DIAGNOSTIC OUTPUT:');
  console.log('================================');
  
  // Check if we're running inside a browser
  console.log('Browser Info:', navigator.userAgent);
  console.log('Network Status:', navigator.onLine ? 'Online' : 'Offline');
  
  // Fetch weather directly with diagnostic logs
  fetchWeatherData();
  
  // Reset button after 3 seconds
  setTimeout(() => {
    if (refreshBtn) {
      refreshBtn.innerHTML = '<i class="mdi mdi-refresh"></i> Refresh';
      refreshBtn.disabled = false;
    }
  }, 3000);
}

// Dashboard Messaging Widget Functions
let dashboardSearchTimeout;

function initializeDashboardMessaging() {
  console.log('Initializing dashboard messaging widget...');
  
  // Initialize admin message counts
  updateDashboardAdminCounts();
  
  // Initialize student search
  const searchInput = document.getElementById('dashboard-student-search');
  const searchResults = document.getElementById('dashboard-search-results');
  
  if (searchInput && searchResults) {
    searchInput.addEventListener('input', function() {
      clearTimeout(dashboardSearchTimeout);
      const query = this.value.trim();
      
      if (query.length < 2) {
        searchResults.classList.remove('active');
        return;
      }
      
      dashboardSearchTimeout = setTimeout(() => {
        searchDashboardStudents(query);
      }, 300);
    });
    
    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
      if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.remove('active');
      }
    });
  }
  
  // Load student conversations
  loadDashboardStudentConversations();
  
  // Set up periodic refresh
  setInterval(() => {
    updateDashboardAdminCounts();
    loadDashboardStudentConversations();
  }, 5000);
}

function updateDashboardAdminCounts() {
  // Update UKP count
  fetch('/all/massage/student/countMessage?type=FN')
    .then(response => response.json())
    .then(data => {
      const element = document.getElementById('dashboard-ukp-count');
      if (element) {
        if (data.count === 0) {
          element.classList.add('hidden');
        } else {
          element.classList.remove('hidden');
          element.textContent = data.count;
        }
      }
    })
    .catch(error => console.error('Error updating UKP count:', error));
  
  // Update KRP count
  fetch('/all/massage/student/countMessage?type=RGS')
    .then(response => response.json())
    .then(data => {
      const element = document.getElementById('dashboard-krp-count');
      if (element) {
        if (data.count === 0) {
          element.classList.add('hidden');
        } else {
          element.classList.remove('hidden');
          element.textContent = data.count;
        }
      }
    })
    .catch(error => console.error('Error updating KRP count:', error));
  
  // Update AR count
  fetch('/all/massage/student/countMessage?type=AR')
    .then(response => response.json())
    .then(data => {
      const element = document.getElementById('dashboard-ar-count');
      if (element) {
        if (data.count === 0) {
          element.classList.add('hidden');
        } else {
          element.classList.remove('hidden');
          element.textContent = data.count;
        }
      }
    })
    .catch(error => console.error('Error updating AR count:', error));
  
  // Update HEP count
  fetch('/all/massage/student/countMessage?type=HEP')
    .then(response => response.json())
    .then(data => {
      const element = document.getElementById('dashboard-hep-count');
      if (element) {
        if (data.count === 0) {
          element.classList.add('hidden');
        } else {
          element.classList.remove('hidden');
          element.textContent = data.count;
        }
      }
    })
    .catch(error => console.error('Error updating HEP count:', error));
}

function searchDashboardStudents(query) {
  fetch('/all/student/search', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ search: query })
  })
  .then(response => response.json())
  .then(students => {
    const searchResults = document.getElementById('dashboard-search-results');
    
    if (students.length === 0) {
      searchResults.innerHTML = '<div class="dashboard-search-item">No students found</div>';
    } else {
      searchResults.innerHTML = students.map(student => `
        <div class="dashboard-search-item" onclick="startDashboardStudentChat('${student.ic}', '${student.name}')">
          <div class="dashboard-search-avatar">
            ${student.name.charAt(0).toUpperCase()}
          </div>
          <div class="dashboard-search-details">
            <div class="dashboard-search-name">${student.name}</div>
            <div class="dashboard-search-info">${student.no_matric} • ${student.progname}</div>
          </div>
        </div>
      `).join('');
    }
    
    searchResults.classList.add('active');
  })
  .catch(error => {
    console.error('Error searching students:', error);
  });
}

function startDashboardStudentChat(studentIc, studentName) {
  // Hide search results and clear search
  const searchResults = document.getElementById('dashboard-search-results');
  const searchInput = document.getElementById('dashboard-student-search');
  if (searchResults) searchResults.classList.remove('active');
  if (searchInput) searchInput.value = '';
  
  // Check if TextBox component is available
  if (window.textBoxComponent) {
    window.textBoxComponent.openStudentChat(studentIc, studentName);
  } else {
    // Fallback - open messaging panel
    toggleMessagingPanel();
  }
}

function loadDashboardStudentConversations() {
  fetch('/all/student/conversations')
    .then(response => response.json())
    .then(conversations => {
      const container = document.getElementById('dashboard-student-conversations');
      
      if (conversations.length === 0) {
        container.innerHTML = `
          <div class="empty-message-state">
            <i class="mdi mdi-message-outline"></i>
            <p>No recent conversations</p>
            <span>Search for students to start chatting!</span>
          </div>
        `;
      } else {
        // Show only the first 3 conversations for the dashboard widget
        const recentConversations = conversations.slice(0, 3);
        
        container.innerHTML = recentConversations.map(conv => {
          const lastMessage = conv.last_message;
          const student = conv.student;
          const unreadCount = conv.unread_count;
          
          // Format message preview
          let messagePreview = 'No messages yet';
          if (lastMessage) {
            if (lastMessage.message && lastMessage.message.trim()) {
              messagePreview = lastMessage.message;
            } else if (lastMessage.image_url) {
              messagePreview = '📷 Photo';
            }
          }
          
          return `
            <div class="dashboard-conversation-item" onclick="startDashboardStudentChat('${student.ic}', '${student.name}')">
              <div class="dashboard-conversation-avatar online">
                ${student.name.charAt(0).toUpperCase()}
              </div>
              <div class="dashboard-conversation-details">
                <div class="dashboard-conversation-name">${student.name}</div>
                <div class="dashboard-conversation-preview ${unreadCount > 0 ? 'unread' : ''}">
                  ${messagePreview}
                </div>
              </div>
              <div class="dashboard-conversation-meta">
                ${unreadCount > 0 ? `<div class="dashboard-unread-badge">${unreadCount}</div>` : ''}
              </div>
            </div>
          `;
        }).join('');
      }
    })
    .catch(error => {
      console.error('Error loading dashboard conversations:', error);
    });
}
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    @if(Session::has('alert'))
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ Session::get('alert') }}',
        confirmButtonColor: '#3085d6',
      });
    @endif
  });
</script>
@endsection
          
