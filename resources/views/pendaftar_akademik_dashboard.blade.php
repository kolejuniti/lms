@extends('layouts.pendaftar_akademik')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Academic Registrar Dashboard</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Main content -->
    <section class="content">
      <!-- Welcome Section -->
      <div class="row">
        <div class="col-xl-12 col-12">
          <div class="box bg-gradient-primary">
            <div class="box-body d-flex p-30">
              <div class="flex-grow-1">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <h2 class="mb-10 fw-600 text-white">Welcome Back, {{ Auth::user()->name }}!</h2>
                    <p class="mb-0 text-white-50">Academic Registrar Department Dashboard - {{ date('l, F j, Y') }}</p>
                  </div>
                  <div class="col-md-4 text-end d-none d-md-block">
                    <i data-feather="book-open" class="text-white" style="width: 60px; height: 60px;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats Row -->
      <div class="row">
        {{-- <div class="col-xl-3 col-md-6 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="users" class="text-primary"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Total Students</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['total_students']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
        
        <div class="col-xl-3 col-md-6 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-success-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="user-check" class="text-success"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Active Students</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['active_students']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-warning-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="book" class="text-warning"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Total Subjects</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['total_subjects']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-info-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="calendar" class="text-info"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Active Sessions</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['active_sessions']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Academic Status Row -->
      <div class="row">
        <div class="col-xl-2 col-md-4 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-secondary-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="layers" class="text-secondary"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Programs</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['total_programs']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-warning-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="file-text" class="text-warning"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Pending Transcripts</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['pending_transcripts']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-success-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="award" class="text-success"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Certificates Pending</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['certificates_pending']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-danger-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="alert-triangle" class="text-danger"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Warning Letters Today</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['warning_letters_issued']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="clipboard" class="text-primary"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Pending Assessments</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['assessment_stats']['pending_assessments']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-success-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="check-circle" class="text-success"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Completed Today</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['assessment_stats']['completed_today']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions Section -->
      <div class="row">
        <div class="col-xl-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Quick Actions</h4>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar_akademik') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="book" class="text-primary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Subject List</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar_akademik.assignCourse') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="layers" class="text-success mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Structure List</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar_akademik.student') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="user-plus" class="text-warning mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Assign Subject</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar_akademik.transcript') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="file-text" class="text-info mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Transcript</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar_akademik.student.certificate') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="award" class="text-secondary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Student Certificate</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar_akademik.warningLetter') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="alert-triangle" class="text-danger mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Warning Letters</p>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Messages Widget -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Messages</h4>
              <div class="box-controls pull-right">
                <a href="/all/massage/user" class="btn btn-sm btn-primary">View All</a>
              </div>
            </div>
            <div class="box-body">
              <div class="d-flex align-items-center mb-20">
                <div class="me-15 bg-warning-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="message-square" class="text-warning"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Unread Messages</p>
                  <div class="d-flex align-items-center">
                    <h4 class="mb-0 fw-600 me-10">Messages</h4>
                    <span id="dashboard-message-count" class="badge badge-danger notification-badge">0</span>
                  </div>
                </div>
                <div>
                  <a href="/all/massage/user" class="btn btn-sm btn-outline-primary">
                    <i data-feather="message-circle" class="me-5"></i>View Messages
                  </a>
                </div>
              </div>
              
              <!-- Quick Chat Access -->
              <div class="border-top pt-15">
                <h6 class="mb-10">Students with Unread Messages</h6>
                <div id="unread-students-list" class="mb-15">
                  <!-- This will be populated by AJAX -->
                  <div class="text-center text-muted" id="loading-students">
                    <small>Loading...</small>
                  </div>
                </div>
                <div class="d-flex gap-10 mb-10">
                  <button class="btn btn-sm btn-outline-primary flex-fill" onclick="refreshUnreadStudents()">
                    <i data-feather="refresh-cw" class="me-5"></i>Method 1
                  </button>
                  <button class="btn btn-sm btn-outline-secondary flex-fill" onclick="fetchUnreadStudentsAlternative()">
                    <i data-feather="search" class="me-5"></i>Method 2
                  </button>
                </div>
                <div class="d-flex gap-10">
                  <a href="/all/massage/user" class="btn btn-sm btn-primary btn-block">
                    <i data-feather="message-square" class="me-5"></i>View All Messages
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Row -->
      <div class="row">
        <!-- Recent Transcripts -->
        <div class="col-xl-8 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Recent Student Transcripts</h4>
              <div class="box-controls pull-right">
                <a href="{{ route('pendaftar_akademik.transcript') }}" class="btn btn-sm btn-primary">View All</a>
              </div>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Student Name</th>
                      <th>Matric No</th>
                      <th>Semester</th>
                      <th>GPA</th>
                      <th>CGPA</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($data['recent_transcripts'] as $transcript)
                      <tr>
                        <td>{{ $transcript->name }}</td>
                        <td>{{ $transcript->no_matric ?? 'N/A' }}</td>
                        <td>{{ $transcript->semester }}</td>
                        <td>{{ number_format($transcript->gpa, 2) }}</td>
                        <td>{{ number_format($transcript->cgpa, 2) }}</td>
                        <td>
                          @if($transcript->status_name == 'APPROVED')
                            <span class="badge badge-success">{{ $transcript->status_name }}</span>
                          @elseif($transcript->status_name == 'PENDING')
                            <span class="badge badge-warning">{{ $transcript->status_name }}</span>
                          @else
                            <span class="badge badge-secondary">{{ $transcript->status_name }}</span>
                          @endif
                        </td>
                        <td>{{ date('d/m/Y', strtotime($transcript->created_at)) }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center">No recent transcripts found</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Academic Summary & Actions -->
        <div class="col-xl-4 col-12">
          <!-- Semester Distribution -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Student Distribution by Semester</h4>
            </div>
            <div class="box-body">
              @for($i = 1; $i <= 6; $i++)
                <div class="d-flex justify-content-between align-items-center mb-15">
                  <span>Semester {{ $i }}</span>
                  <div class="d-flex align-items-center">
                    <span class="fw-600 text-primary me-10">{{ number_format($data['semester_summary'][$i]['active']) }}</span>
                    <small class="text-muted">/ {{ number_format($data['semester_summary'][$i]['total']) }}</small>
                  </div>
                </div>
              @endfor
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>Internship</span>
                <span class="fw-600 text-warning">{{ number_format($data['academic_status']['internship']) }}</span>
              </div>
            </div>
          </div>

          <!-- Quick Reports -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Academic Reports</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                <a href="{{ route('pendaftar_akademik.senateReport') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Senate Report
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('pendaftar_akademik.resultReport') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Result Report
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('pendaftar_akademik.structureReport') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Structure Report
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('pendaftar_akademik.resultOverall') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Result Filter
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('pendaftar_akademik.assessmentFilter') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Assessment Filter
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activities & System Info -->
      <div class="row">
        <div class="col-xl-6 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Recent Warning Letters</h4>
            </div>
            <div class="box-body">
              @forelse($data['recent_warnings'] as $warning)
                <div class="d-flex justify-content-between align-items-center mb-15">
                  <div>
                    <p class="mb-0 fw-500">{{ $warning->name }} ({{ $warning->no_matric }})</p>
                    <small class="text-muted">{{ $warning->course_code }} - {{ Str::limit($warning->course_name, 30) }}</small>
                  </div>
                  <div class="text-end">
                    <span class="badge badge-warning">Warning {{ $warning->warning }}</span>
                    <small class="text-muted d-block">{{ date('d/m/Y', strtotime($warning->created_at)) }}</small>
                  </div>
                </div>
              @empty
                <p class="text-muted text-center">No recent warning letters</p>
              @endforelse
            </div>
          </div>
        </div>

        <div class="col-xl-6 col-12">
          <!-- Subject Statistics -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Top Programs by Subject Count</h4>
            </div>
            <div class="box-body">
              @forelse($data['subject_stats'] as $stat)
                <div class="d-flex justify-content-between align-items-center mb-15">
                  <div>
                    <p class="mb-0 fw-500">{{ $stat->progcode }}</p>
                    <small class="text-muted">{{ Str::limit($stat->progname, 40) }}</small>
                  </div>
                  <span class="badge badge-primary">{{ number_format($stat->subject_count) }}</span>
                </div>
              @empty
                <p class="text-muted text-center">No subject statistics available</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Student Subject Registrations -->
      <div class="row">
        <div class="col-xl-12 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Recent Student Subject Registrations</h4>
              <div class="box-controls pull-right">
                <a href="{{ route('pendaftar_akademik.student') }}" class="btn btn-sm btn-primary">View All</a>
              </div>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Student Name</th>
                      <th>Matric No</th>
                      <th>Subject Code</th>
                      <th>Subject Name</th>
                      <th>Semester</th>
                      <th>Registration Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($data['recent_student_subjects'] as $registration)
                      <tr>
                        <td>{{ $registration->name }}</td>
                        <td>{{ $registration->no_matric ?? 'N/A' }}</td>
                        <td>{{ $registration->course_code }}</td>
                        <td>{{ Str::limit($registration->course_name, 40) }}</td>
                        <td>{{ $registration->semesterid }}</td>
                        <td>{{ date('d/m/Y H:i', strtotime($registration->created_at)) }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="text-center">No recent registrations found</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- Vue.js Chat Component Container -->
<div id="app">
  <example-component></example-component>
</div>

<!-- Additional Custom Styles for Academic Registrar Dashboard -->
<style>
.hover-shadow:hover {
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  transform: translateY(-2px);
  transition: all 0.3s ease;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.badge {
  font-size: 11px;
  padding: 4px 8px;
}

.list-group-item-action:hover {
  background-color: #f8f9fa;
}

.box {
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  border: 1px solid #e9ecef;
}

.box-header {
  border-bottom: 1px solid #e9ecef;
  padding: 15px 20px;
}

.box-body {
  padding: 20px;
}

.bg-primary-light {
  background-color: rgba(0, 123, 255, 0.1) !important;
}

.bg-success-light {
  background-color: rgba(40, 167, 69, 0.1) !important;
}

.bg-warning-light {
  background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-danger-light {
  background-color: rgba(220, 53, 69, 0.1) !important;
}

.bg-info-light {
  background-color: rgba(23, 162, 184, 0.1) !important;
}

.bg-secondary-light {
  background-color: rgba(108, 117, 125, 0.1) !important;
}

.text-white-50 {
  color: rgba(255, 255, 255, 0.5) !important;
}

/* Message widget styles */
.gap-10 {
  gap: 10px;
}

.btn-block {
  width: 100%;
}

.border-top {
  border-top: 1px solid #e9ecef;
}

.pt-15 {
  padding-top: 15px;
}

.mb-20 {
  margin-bottom: 20px;
}

.mt-10 {
  margin-top: 10px;
}

.me-5 {
  margin-right: 5px;
}

.me-10 {
  margin-right: 10px;
}

/* Vue.js component styles handled by app.js */

/* Student list styles */
#unread-students-list .bg-light {
  border: 1px solid #e9ecef;
  transition: all 0.2s ease;
}

#unread-students-list .bg-light:hover {
  background-color: #f1f3f4 !important;
  border-color: #019ff8;
}

/* Enhanced student chat row styles */
.student-chat-row {
  border: 2px solid #e9ecef !important;
  transition: all 0.3s ease;
  cursor: pointer;
}

.student-chat-row:hover {
  background-color: #e3f2fd !important;
  border-color: #2196f3 !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
}

.student-chat-row .chat-action {
  opacity: 0.7;
  transition: opacity 0.3s ease;
}

.student-chat-row:hover .chat-action {
  opacity: 1;
}

.student-chat-row .btn {
  pointer-events: none; /* Prevent double click */
}

.cursor-pointer {
  cursor: pointer;
}

.p-15 {
  padding: 15px;
}

.p-10 {
  padding: 10px;
}

.mb-10 {
  margin-bottom: 10px;
}

/* Notification badge styles */
.notification-badge {
  font-size: 14px !important;
  padding: 6px 12px !important;
  border-radius: 20px !important;
  font-weight: bold !important;
  color: white !important;
  background-color: #dc3545 !important;
  border: 2px solid white !important;
  box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3) !important;
  min-width: 30px;
  text-align: center;
  animation: pulse-notification 2s infinite;
}

.notification-badge:empty {
  display: none;
}

.notification-badge.zero {
  background-color: #6c757d !important;
  animation: none;
}

@keyframes pulse-notification {
  0% {
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
  }
  50% {
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.6);
    transform: scale(1.05);
  }
  100% {
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
  }
}
</style>

<script>
  // Initialize feather icons
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  });

  // Laravel session data for Vue.js
  window.Laravel = {
    sessionUserId: '{{ Auth::user()->usrtype }}'
  };

  // Chat functions using Vue.js component (same as finance dashboard)
  // The getMessage function is defined globally in app.js

  // Function to fetch students with unread messages using the same method as finance dashboard
  function fetchUnreadStudents() {
    $('#loading-students').show();
    
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('/all/massage/user/getStudentNewMassage') }}",
      method: 'GET',
      success: function(data) {
        $('#loading-students').hide();
        console.log('Raw response:', data); // Debug log
        
        // Parse the HTML response - the response should contain the complete table HTML
        const $response = $(data);
        console.log('Parsed response:', $response); // Debug log
        
        // Look for tbody with rows - the response is the tbody content itself
        let $tbody = $response;
        if ($response.find('tbody').length > 0) {
          $tbody = $response.find('tbody');
        }
        
        console.log('Found tbody:', $tbody, 'Rows count:', $tbody.find('tr').length); // Debug log
        
        // Process the rows
        processStudentTableData($tbody);
      },
      error: function(xhr, status, error) {
        $('#loading-students').hide();
        console.error('AJAX Error:', {xhr, status, error}); // Debug log
        $('#unread-students-list').html('<div class="text-center text-danger"><small>Error loading messages: ' + error + '</small></div>');
      }
    });
  }

  // Function to process the table data returned from the AJAX call
  function processStudentTableData($tableData) {
    console.log('Processing table data:', $tableData); // Debug log
    
    // Find all rows
    let $rows = $tableData.find('tr');
    if ($rows.length === 0) {
      // If no rows found in children, maybe the data itself contains rows
      $rows = $tableData.filter('tr');
    }
    if ($rows.length === 0 && $tableData.is('tr')) {
      // If the data itself is rows
      $rows = $tableData;
    }
    
    console.log('Found rows:', $rows.length); // Debug log
    
    if ($rows.length === 0) {
      $('#unread-students-list').html('<div class="text-center text-muted"><small>No unread messages</small></div>');
      return;
    }
    
    let studentsHtml = '';
    let studentCount = 0;
    
    $rows.each(function(index) {
      if (studentCount >= 10) return; // Only show first 10 students
      
      const $row = $(this);
      console.log('Processing row:', index, $row.html()); // Debug log
      
      const $cells = $row.find('td');
      console.log('Cells in row:', $cells.length); // Debug log
      
      if ($cells.length >= 4) {
        const rowNum = $cells.eq(0).text().trim();
        const name = $cells.eq(1).text().trim();
        const ic = $cells.eq(2).text().trim(); 
        const matric = $cells.eq(3).text().trim();
        
        console.log('Student data:', {rowNum, name, ic, matric}); // Debug log
        
        if (name && ic && name !== '' && ic !== '') {
          studentsHtml += `
            <div class="student-chat-row d-flex align-items-center justify-content-between mb-10 p-15 bg-light rounded cursor-pointer" 
                 onclick="getMessage('${ic}', 'AR', '${name}', '${matric}')">
              <div class="flex-grow-1">
                <div class="fw-bold text-dark">${name}</div>
                <small class="text-muted">${matric || 'No Matric'}</small>
              </div>
              <div class="chat-action">
                <span class="btn btn-sm btn-primary">
                  <i data-feather="message-circle" class="me-5"></i>Start Chatting
                </span>
              </div>
            </div>
          `;
          studentCount++;
        }
      }
    });
    
    console.log('Generated HTML for', studentCount, 'students:', studentsHtml); // Debug log
    
    if (studentsHtml) {
      $('#unread-students-list').html(studentsHtml);
      // Re-initialize feather icons for new buttons
      if (typeof feather !== 'undefined') {
        feather.replace();
      }
    } else {
      $('#unread-students-list').html('<div class="text-center text-muted"><small>No student data could be extracted</small></div>');
    }
  }

  // Function to refresh unread students list
  function refreshUnreadStudents() {
    $('#loading-students').show();
    $('#unread-students-list').html('<div class="text-center text-muted" id="loading-students"><small>Loading...</small></div>');
    fetchUnreadStudents();
  }

  // Alternative function to fetch students using a different approach
  function fetchUnreadStudentsAlternative() {
    // Try to get the student data using jQuery to load the page content
    $.get("{{ url('/all/massage/user') }}", function(data) {
      $('#loading-students').hide();
      console.log('Alternative fetch - Raw response:', data);
      
      // Look for the "New Message" section specifically
      const $response = $(data);
      const $newMessageTable = $response.find('#complex_header2');
      
      if ($newMessageTable.length > 0) {
        console.log('Found new message table:', $newMessageTable);
        
        // Get the tbody content after the table is populated
        setTimeout(function() {
          const $tbody = $newMessageTable.find('tbody');
          console.log('New message tbody:', $tbody, 'Rows:', $tbody.find('tr').length);
          
          processStudentRows($tbody);
        }, 1000);
      } else {
        $('#unread-students-list').html('<div class="text-center text-muted"><small>Could not find message data</small></div>');
      }
    }).fail(function(xhr, status, error) {
      $('#loading-students').hide();
      console.error('Alternative AJAX Error:', {xhr, status, error});
      $('#unread-students-list').html('<div class="text-center text-danger"><small>Error: ' + error + '</small></div>');
    });
  }

  // Function to process student rows
  function processStudentRows($tbody) {
    if ($tbody.find('tr').length === 0) {
      $('#unread-students-list').html('<div class="text-center text-muted"><small>No unread messages found</small></div>');
      return;
    }
    
    let studentsHtml = '';
    let studentCount = 0;
    
    $tbody.find('tr').each(function(index) {
      if (studentCount >= 3) return; // Only show first 3
      
      const $row = $(this);
      const $cells = $row.find('td');
      
      if ($cells.length >= 4) {
        const name = $cells.eq(1).text().trim();
        const ic = $cells.eq(2).text().trim();
        const matric = $cells.eq(3).text().trim();
        
        if (name && ic && name !== '' && ic !== '') {
          studentsHtml += `
            <div class="student-chat-row d-flex align-items-center justify-content-between mb-10 p-15 bg-light rounded cursor-pointer" 
                 onclick="getMessage('${ic}', 'AR', '${name}', '${matric}')">
              <div class="flex-grow-1">
                <div class="fw-bold text-dark">${name}</div>
                <small class="text-muted">${matric || 'No Matric'}</small>
              </div>
              <div class="chat-action">
                <span class="btn btn-sm btn-primary">
                  <i data-feather="message-circle" class="me-5"></i>Start Chatting
                </span>
              </div>
            </div>
          `;
          studentCount++;
        }
      }
    });
    
    if (studentsHtml) {
      $('#unread-students-list').html(studentsHtml);
      if (typeof feather !== 'undefined') {
        feather.replace();
      }
    } else {
      $('#unread-students-list').html('<div class="text-center text-muted"><small>No valid student data found</small></div>');
    }
  }

  // Message count functionality
  $(document).ready(function() {
    function fetchMessageCount() {
      $.ajax({
        url: '{{ route("all.massage.student.countMassageAdmin") }}',
        type: 'GET',
        success: function(response) {
          const count = response.count || 0;
          const $badge = $('#dashboard-message-count');
          
          // Update badge text and styling
          $badge.text(count);
          
          if(count > 0) {
            $badge.removeClass('zero').addClass('notification-badge');
            $badge.show();
          } else {
            $badge.addClass('zero').removeClass('notification-badge');
            $badge.text('0');
          }
        },
        error: function() {
          console.error('Failed to fetch message count');
          $('#dashboard-message-count').text('--').addClass('text-muted');
        }
      });
    }

    // Fetch the count every 30 seconds
    setInterval(fetchMessageCount, 30000);

    // Initial fetch when page loads
    fetchMessageCount();
    
    // Also fetch unread students when page loads using the same method as finance dashboard
    fetchUnreadStudents();
  });
</script>

<!-- Vue.js App Script -->
<script src="{{ mix('js/app.js') }}"></script>
@endsection
