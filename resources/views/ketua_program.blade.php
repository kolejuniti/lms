@extends('layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Program Leader Dashboard</h4>
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
                    <p class="mb-0 text-white-50">Program Leader Dashboard - {{ date('l, F j, Y') }}</p>
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
                  <p class="mb-5 text-fade">Total Lecturers</p>
                  <h4 class="mb-0 fw-600">{{ number_format($dashboardData['total_lecturers']) }}</h4>
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
                  <p class="mb-5 text-fade">Total Students</p>
                  <h4 class="mb-0 fw-600">{{ number_format($dashboardData['total_students']) }}</h4>
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
                  <i data-feather="book-open" class="text-warning"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Active Groups</p>
                  <h4 class="mb-0 fw-600">{{ number_format($dashboardData['total_groups']) }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6 col-12">
          <div class="box">
            <div class="box-body">
              <div class="d-flex align-items-center">
                <div class="me-15 bg-danger-light h-50 w-50 l-h-60 rounded text-center">
                  <i data-feather="alert-circle" class="text-danger"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Pending Replacement Classes</p>
                  <h4 class="mb-0 fw-600">{{ number_format($dashboardData['pending_replacement_classes']) }}</h4>
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
                  <a href="{{ route('kp.create') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="user-plus" class="text-primary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Assign Lecturer</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('kp.group') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="users" class="text-success mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Assign Students</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('kp.coursemark') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="edit" class="text-warning mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Course Marks</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('kp.lecturer') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="file-text" class="text-info mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Lecturer Reports</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('kp.replacement_class.pending') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="calendar" class="text-secondary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Replacement Classes</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('kp.assign.meetingHour') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="clock" class="text-primary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Meeting Hours</p>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Timetable & Schedule Section -->
      <div class="row">
        <div class="col-xl-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">
                <i data-feather="calendar" class="me-10"></i>
                Timetable & Schedule Management
              </h4>
            </div>
            <div class="box-body">
              <div class="row">
                <!-- Lecturer Timetable -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-20">
                  <a href="{{ route('lecturer.class.schedule') }}" class="d-block text-center p-20 bg-light rounded hover-shadow timetable-action">
                    <div class="timetable-icon-wrapper mb-15">
                      <i data-feather="user" class="text-primary" style="width: 35px; height: 35px;"></i>
                    </div>
                    <h6 class="mb-5 fw-600">Lecturer Timetable</h6>
                    <p class="mb-0 text-fade" style="font-size: 12px;">View lecturer schedules</p>
                  </a>
                </div>

                <!-- Lecture Room Timetable -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-20">
                  <a href="/AR/schedule/lecture?type=lcr" class="d-block text-center p-20 bg-light rounded hover-shadow timetable-action">
                    <div class="timetable-icon-wrapper mb-15">
                      <i data-feather="home" class="text-success" style="width: 35px; height: 35px;"></i>
                    </div>
                    <h6 class="mb-5 fw-600">Lecture Room</h6>
                    <p class="mb-0 text-fade" style="font-size: 12px;">Room schedules & bookings</p>
                  </a>
                </div>

                <!-- Student Timetable -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-20">
                  <a href="/AR/schedule/student?type=std" class="d-block text-center p-20 bg-light rounded hover-shadow timetable-action">
                    <div class="timetable-icon-wrapper mb-15">
                      <i data-feather="users" class="text-warning" style="width: 35px; height: 35px;"></i>
                    </div>
                    <h6 class="mb-5 fw-600">Student Timetable</h6>
                    <p class="mb-0 text-fade" style="font-size: 12px;">View student class schedules</p>
                  </a>
                </div>

                <!-- Finals Timetable -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-20">
                  <a href="{{ asset('storage/finals_schedule/Jadual Peperiksaan Akhir UNITI Semester I Sesi 20252026 (Kemasukan Jun).pdf') }}" target="_blank" class="d-block text-center p-20 bg-light rounded hover-shadow timetable-action">
                    <div class="timetable-icon-wrapper mb-15">
                      <i data-feather="file-text" class="text-danger" style="width: 35px; height: 35px;"></i>
                    </div>
                    <h6 class="mb-5 fw-600">Finals Timetable</h6>
                    <p class="mb-0 text-fade" style="font-size: 12px;">Exam schedule & supervision</p>
                  </a>
                </div>
              </div>

              <!-- Old Timetable Link -->
              <div class="row">
                <div class="col-12">
                  <div class="text-center p-10 rounded" style="background-color: #f8f9fa; border: 1px dashed #dee2e6;">
                    <small class="text-muted">
                      <i data-feather="info" class="me-5" style="width: 14px; height: 14px;"></i>
                      Need the old system? 
                      <a href="{{ Storage::disk('linode')->url('classschedule/index.htm') }}" target="_blank" class="text-primary fw-500">
                        Access Legacy Timetable <i data-feather="external-link" style="width: 12px; height: 12px;"></i>
                      </a>
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Row -->
      <div class="row">
        <!-- Recent Lecturer Assignments -->
        <div class="col-xl-8 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Recent Lecturer Assignments</h4>
              <div class="box-controls pull-right">
                <a href="{{ route('kp.create') }}" class="btn btn-sm btn-primary">Assign New</a>
              </div>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Lecturer</th>
                      <th>Subject Code</th>
                      <th>Subject Name</th>
                      <th>Session</th>
                      <th>Date Assigned</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($dashboardData['recent_assignments'] as $assignment)
                      <tr>
                        <td>{{ $assignment->name }}</td>
                        <td>{{ $assignment->course_code }}</td>
                        <td>{{ $assignment->course_name }}</td>
                        <td>{{ $assignment->SessionName }}</td>
                        <td>{{ $assignment->created_at ? date('d/m/Y', strtotime($assignment->created_at)) : 'N/A' }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center">No recent assignments found</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Program Statistics & Quick Info -->
        <div class="col-xl-4 col-12">
          <!-- Program Summary -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Program Summary</h4>
            </div>
            <div class="box-body">
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>Total Programs</span>
                <span class="fw-600 text-primary">{{ number_format($dashboardData['total_programs']) }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>Active Sessions</span>
                <span class="fw-600">{{ number_format($dashboardData['active_sessions']) }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>Today's Assessments</span>
                <span class="fw-600 text-success">{{ number_format($dashboardData['todays_assessments']) }}</span>
              </div>
            </div>
          </div>

          <!-- Program Details -->
          @if(!empty($dashboardData['program_stats']))
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Programs Under Management</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                @foreach($dashboardData['program_stats'] as $program)
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <strong>{{ $program['code'] }}</strong><br>
                      <small class="text-muted">{{ $program['name'] }}</small>
                    </div>
                    <span class="badge badge-primary">{{ $program['student_count'] }} students</span>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>

      <!-- Current Groups Table -->
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Current Lecturer-Subject Assignments</h4>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                  <div class="row">
                    <div class="col-sm-12">
                      <table id="complex_header" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info" data-ordering="false">
                        <thead>
                          <tr>
                            <th style="width: 1%">No.</th>
                            <th>Code</th>
                            <th>Subject</th>
                            <th>Session</th>
                            <th>Lecturer Name</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($data as $key=>$datas)
                          <tr>
                            <td style="width: 1%">{{$key+1}}</td>
                            <td>{{ $datas->course_code }}</td>
                            <td>{{ $datas->course_name }}</td>
                            <td>{{ $datas->SessionName }}</td>
                            <td>{{ $datas->name }}</td>
                            <td class="project-actions text-right">
                              <a class="btn btn-info btn-sm mr-2" href="/KP/{{ $datas->id }}/editgroup">
                                  <i class="ti-pencil-alt"></i>
                                  Edit
                              </a>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('{{ $datas->id }}')">
                                  <i class="ti-trash"></i>
                                  Delete
                              </a>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- System Alerts -->
      <div class="row">
        <div class="col-xl-6 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Pending Tasks</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                @if($dashboardData['pending_replacement_classes'] > 0)
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <i data-feather="calendar" class="text-warning me-10"></i>
                      Pending Replacement Class Applications
                    </div>
                    <span class="badge badge-warning">{{ $dashboardData['pending_replacement_classes'] }}</span>
                  </div>
                @endif
                @if($dashboardData['pending_replacement_classes'] == 0)
                  <div class="list-group-item text-center text-muted">
                    <i data-feather="check-circle" class="text-success mb-10"></i>
                    <p class="mb-0">All tasks completed!</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-6 col-12">
          <!-- System Status -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">System Status</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                <div class="list-group-item">
                  <div class="d-flex align-items-center">
                    <i data-feather="activity" class="text-success me-10"></i>
                    <div>
                      <p class="mb-0 fw-500">Faculty: {{ Auth::user()->faculty_name ?? 'N/A' }}</p>
                      <small class="text-muted">{{ $dashboardData['total_lecturers'] }} active lecturers</small>
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

<!-- Additional Custom Styles for KP Dashboard -->
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

.text-white-50 {
  color: rgba(255, 255, 255, 0.5) !important;
}

.mb-20 {
  margin-bottom: 20px;
}

.me-10 {
  margin-right: 10px;
}

/* Timetable Section Styles - Cohesive with Quick Actions */
.timetable-action {
  text-decoration: none;
  border: 1px solid #e9ecef;
  transition: all 0.3s ease;
  min-height: 140px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.timetable-action:hover {
  border-color: #019ff8;
  background-color: #f8f9fa !important;
}

.timetable-icon-wrapper {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 60px;
  height: 60px;
  margin: 0 auto;
  background-color: rgba(1, 159, 248, 0.08);
  border-radius: 10px;
  transition: all 0.3s ease;
}

.timetable-action:hover .timetable-icon-wrapper {
  transform: translateY(-3px);
  background-color: rgba(1, 159, 248, 0.12);
}

.timetable-action h6 {
  color: #495057;
  transition: color 0.3s ease;
}

.timetable-action:hover h6 {
  color: #019ff8;
}

.mb-15 {
  margin-bottom: 15px;
}

.fw-500 {
  font-weight: 500;
}

.p-10 {
  padding: 10px;
}

.mt-10 {
  margin-top: 10px;
}

.me-5 {
  margin-right: 5px;
}
</style>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
  // Initialize feather icons
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  });

  function deleteMaterial(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/KP/delete') }}",
                  method   : 'DELETE',
                  data 	 : {id:id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      window.location.reload();
                      alert("success");
                  }
              });
          }
      });
  }
</script>
@endsection
