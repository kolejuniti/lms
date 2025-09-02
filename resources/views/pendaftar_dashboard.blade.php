@extends('layouts.pendaftar')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Pendaftar Dashboard</h4>
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
                    <p class="mb-0 text-white-50">Student Registration Department Dashboard - {{ date('l, F j, Y') }}</p>
                  </div>
                  <div class="col-md-4 text-end d-none d-md-block">
                    <i data-feather="users" class="text-white" style="width: 60px; height: 60px;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats Row -->
      <div class="row">
        <div class="col-xl-3 col-md-6 col-12">
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
        </div>
        
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
                  <i data-feather="clock" class="text-warning"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Pending Applications</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['pending_applications']) }}</h4>
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
                  <i data-feather="user-plus" class="text-info"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">New Students Today</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['metrics']['new_students_today']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Student Categories Row -->
      <div class="row">
        <div class="col-xl-4 col-md-4 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-secondary-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="pause-circle" class="text-secondary"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Holding Students</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['student_categories']['holding']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-4 col-md-4 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-success-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="book-open" class="text-success"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Currently Studying</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['student_categories']['studying']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-4 col-md-4 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-warning-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="briefcase" class="text-warning"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Industrial Training</p>
                  <h4 class="mb-0 fw-600">{{ number_format($data['student_categories']['internship']) }}</h4>
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
                  <a href="{{ route('pendaftar') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="users" class="text-primary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Student List</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar.create') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="user-plus" class="text-success mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Create Student</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar.student.edit') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="edit-2" class="text-warning mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Edit Student</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar.student.status') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="user-check" class="text-info mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Update Status</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar.student.statusUpdateBulk') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="users" class="text-secondary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Bulk Status Update</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar.student.studentreport') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="file-text" class="text-primary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Student Reports</p>
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
                    <i data-feather="refresh-cw" class="me-5"></i>Refresh
                  </button>
                  <a href="/all/massage/user" class="btn btn-sm btn-primary flex-fill">
                    <i data-feather="message-square" class="me-5"></i>All Messages
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Row -->
      <div class="row">
        <!-- Recent Students -->
        <div class="col-xl-8 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Recent Students</h4>
              <div class="box-controls pull-right">
                <a href="{{ route('pendaftar') }}" class="btn btn-sm btn-primary">View All</a>
              </div>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Matric No</th>
                      <th>IC</th>
                      <th>Program</th>
                      <th>Status</th>
                      <th>Date Created</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($data['recent_students'] as $student)
                      <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->no_matric ?? 'N/A' }}</td>
                        <td>{{ $student->ic }}</td>
                        <td>{{ $student->progcode }}</td>
                        <td>
                          @if($student->status == 'ACTIVE')
                            <span class="badge badge-success">{{ $student->status }}</span>
                          @elseif($student->status == 'PENDING')
                            <span class="badge badge-warning">{{ $student->status }}</span>
                          @else
                            <span class="badge badge-secondary">{{ $student->status }}</span>
                          @endif
                        </td>
                        <td>{{ $student->date ? date('d/m/Y', strtotime($student->date)) : 'N/A' }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="text-center">No recent students found</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Student Summary & Quick Reports -->
        <div class="col-xl-4 col-12">
          <!-- Additional Metrics -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Additional Metrics</h4>
            </div>
            <div class="box-body">
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>Total Programs</span>
                <span class="fw-600 text-primary">{{ number_format($data['metrics']['programs_count']) }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>International Students</span>
                <span class="fw-600 text-info">{{ number_format($data['metrics']['international_students']) }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>Current Session Students</span>
                <span class="fw-600 text-success">{{ number_format($data['metrics']['current_session_students']) }}</span>
              </div>
            </div>
          </div>

          <!-- Student Status Breakdown -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Student Status Breakdown</h4>
            </div>
            <div class="box-body">
              @forelse($data['status_breakdown']->take(8) as $status)
                <div class="d-flex justify-content-between align-items-center mb-10">
                  <span class="text-truncate" style="max-width: 150px;" title="{{ $status->name }}">{{ $status->name }}</span>
                  <span class="fw-600">{{ number_format($status->count) }}</span>
                </div>
              @empty
                <p class="text-muted text-center">No status data available</p>
              @endforelse
            </div>
          </div>

          <!-- Quick Reports -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Quick Reports</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                <a href="{{ route('pendaftar.student.reportR2') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Statistik Pencapaian R
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('pendaftar.student.reportRA') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Analysis Student R
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('all.student.spm.report') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  SPM Report
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('pendaftar.student.incomeReport') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Family Income Report
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('pendaftar.student.internationalReport') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  International Students
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Program Breakdown & System Info -->
      <div class="row">
        <div class="col-xl-6 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Top Programs by Enrollment</h4>
            </div>
            <div class="box-body">
              @forelse($data['program_breakdown'] as $program)
                <div class="d-flex justify-content-between align-items-center mb-15">
                  <div>
                    <p class="mb-0 fw-500">{{ $program->progcode }}</p>
                    <small class="text-muted">{{ Str::limit($program->progname, 40) }}</small>
                  </div>
                  <span class="badge badge-primary">{{ number_format($program->count) }}</span>
                </div>
              @empty
                <p class="text-muted text-center">No program data available</p>
              @endforelse
            </div>
          </div>
        </div>

        <div class="col-xl-6 col-12">
          <!-- System Information -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">System Information</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                <div class="list-group-item">
                  <div class="d-flex align-items-center">
                    <i data-feather="calendar" class="text-info me-10"></i>
                    <div>
                      <p class="mb-0 fw-500">Academic Year {{ date('Y') }}</p>
                      <small class="text-muted">Current academic session</small>
                    </div>
                  </div>
                </div>
                <div class="list-group-item">
                  <div class="d-flex align-items-center">
                    <i data-feather="users" class="text-success me-10"></i>
                    <div>
                      <p class="mb-0 fw-500">{{ number_format($data['metrics']['active_students']) }} active students</p>
                      <small class="text-muted">Out of {{ number_format($data['metrics']['total_students']) }} total students</small>
                    </div>
                  </div>
                </div>
                <div class="list-group-item">
                  <div class="d-flex align-items-center">
                    <i data-feather="info" class="text-info me-10"></i>
                    <div>
                      <p class="mb-0 fw-500">System running normally</p>
                      <small class="text-muted">Last updated: {{ now()->format('H:i') }}</small>
                    </div>
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

<!-- Vue.js Chat Component Container -->
<div id="app">
  <example-component></example-component>
</div>

<!-- Additional Custom Styles for Pendaftar Dashboard -->
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

/* Student list styles */
#unread-students-list .bg-light {
  border: 1px solid #e9ecef;
  transition: all 0.2s ease;
}

#unread-students-list .bg-light:hover {
  background-color: #f1f3f4 !important;
  border-color: #019ff8;
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

  // Function to fetch students with unread messages
  function fetchUnreadStudents() {
    $('#loading-students').show();
    
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('/all/massage/user/getStudentNewMassage') }}",
      method: 'GET',
      success: function(data) {
        $('#loading-students').hide();
        console.log('Raw response:', data);
        
        const $response = $(data);
        console.log('Parsed response:', $response);
        
        let $tbody = $response;
        if ($response.find('tbody').length > 0) {
          $tbody = $response.find('tbody');
        }
        
        console.log('Found tbody:', $tbody, 'Rows count:', $tbody.find('tr').length);
        processStudentTableData($tbody);
      },
      error: function(xhr, status, error) {
        $('#loading-students').hide();
        console.error('AJAX Error:', {xhr, status, error});
        $('#unread-students-list').html('<div class="text-center text-danger"><small>Error loading messages: ' + error + '</small></div>');
      }
    });
  }

  // Function to process the table data returned from the AJAX call
  function processStudentTableData($tableData) {
    console.log('Processing table data:', $tableData);
    
    let $rows = $tableData.find('tr');
    if ($rows.length === 0) {
      $rows = $tableData.filter('tr');
    }
    if ($rows.length === 0 && $tableData.is('tr')) {
      $rows = $tableData;
    }
    
    console.log('Found rows:', $rows.length);
    
    if ($rows.length === 0) {
      $('#unread-students-list').html('<div class="text-center text-muted"><small>No unread messages</small></div>');
      return;
    }
    
    let studentsHtml = '';
    let studentCount = 0;
    
    $rows.each(function(index) {
      if (studentCount >= 10) return;
      
      const $row = $(this);
      console.log('Processing row:', index, $row.html());
      
      const $cells = $row.find('td');
      console.log('Cells in row:', $cells.length);
      
      if ($cells.length >= 4) {
        const rowNum = $cells.eq(0).text().trim();
        const name = $cells.eq(1).text().trim();
        const ic = $cells.eq(2).text().trim(); 
        const matric = $cells.eq(3).text().trim();
        
        console.log('Student data:', {rowNum, name, ic, matric});
        
        if (name && ic && name !== '' && ic !== '') {
          studentsHtml += `
            <div class="d-flex align-items-center justify-content-between mb-10 p-10 bg-light rounded">
              <div class="flex-grow-1">
                <small class="fw-bold">${name}</small><br>
                <small class="text-muted">${matric || 'No Matric'}</small>
              </div>
              <button class="btn btn-sm btn-success" onclick="getMessage('${ic}', 'RGS', '${name}')"
                <i data-feather="message-circle"></i>
              </button>
            </div>
          `;
          studentCount++;
        }
      }
    });
    
    console.log('Generated HTML for', studentCount, 'students:', studentsHtml);
    
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
    
    // Also fetch unread students when page loads
    fetchUnreadStudents();
  });
</script>

<!-- Vue.js App Script -->
<script src="{{ mix('js/app.js') }}"></script>
@endsection
