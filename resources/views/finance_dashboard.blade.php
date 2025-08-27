@extends('layouts.finance')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Finance Dashboard</h4>
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
                    <p class="mb-0 text-white-50">Finance Department Dashboard - {{ date('l, F j, Y') }}</p>
                  </div>
                  <div class="col-md-4 text-end d-none d-md-block">
                    <i data-feather="dollar-sign" class="text-white" style="width: 60px; height: 60px;"></i>
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
                  <h4 class="mb-0 fw-600">{{ number_format(DB::table('students')->count()) }}</h4>
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
                  <i data-feather="credit-card" class="text-success"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Today's Payments</p>
                  <h4 class="mb-0 fw-600">RM {{ number_format(DB::table('tblpayment')->whereDate('date', today())->where('process_status_id', 2)->sum('amount'), 2) }}</h4>
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
                  <i data-feather="alert-circle" class="text-warning"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Pending Claims</p>
                  <h4 class="mb-0 fw-600">{{ DB::table('tblclaim')->where('process_status_id', 1)->count() }}</h4>
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
                  <i data-feather="file-text" class="text-danger"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-5 text-fade">Total Payments Today</p>
                  <h4 class="mb-0 fw-600">{{ number_format(DB::table('tblpayment')->whereDate('date', today())->where('process_status_id', 2)->count()) }}</h4>
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
                  <a href="{{ route('finance.payment') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="dollar-sign" class="text-primary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Pre-Registration Payment</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('finance.payment.tuition') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="credit-card" class="text-success mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Daily Payment</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('finance.payment.claim') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="folder" class="text-warning mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Process Claims</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('finance.statement') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="file-text" class="text-info mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Account Statements</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('finance.dailyReport') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="clipboard" class="text-secondary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Daily Reports</p>
                  </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-20">
                  <a href="{{ route('pendaftar') }}" class="d-block text-center p-20 bg-light rounded hover-shadow">
                    <i data-feather="users" class="text-primary mb-10" style="width: 30px; height: 30px;"></i>
                    <p class="mb-0 fw-500">Student Management</p>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Row -->
      <div class="row">
        <!-- Recent Payments -->
        <div class="col-xl-8 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Pending FPX Payments</h4>
              <div class="box-controls pull-right">
                <a href="{{ route('finance.receiptList') }}" class="btn btn-sm btn-primary">View All</a>
              </div>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Receipt No</th>
                      <th>Student</th>
                      <th>Ic</th>
                      <th>Amount</th>
                      <th>Date</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $recentPayments = DB::table('tblpayment')
                        ->join('tblpaymentmethod', 'tblpayment.id', '=', 'tblpaymentmethod.payment_id')
                        ->join('students', 'tblpayment.student_ic', '=', 'students.ic')
                        ->where([
                            ['tblpaymentmethod.claim_method_id', 17], 
                            ['tblpayment.process_status_id', 1]])
                        ->select('tblpayment.*', 'students.name', 'students.no_matric', 'students.ic')
                        ->orderBy('tblpayment.date', 'desc')
                        ->limit(10)
                        ->get();
                    @endphp
                    @forelse($recentPayments as $payment)
                      <tr>
                        <td>{{ $payment->ref_no ?? 'N/A' }}</td>
                        <td>{{ $payment->name }} ({{ $payment->no_matric }})</td>
                        <td>{{ $payment->ic }}</td>
                        <td>RM {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ date('d/m/Y', strtotime($payment->date)) }}</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center">No recent payments found</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Finance Summary & Actions -->
        <div class="col-xl-4 col-12">
          <!-- Monthly Summary -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">This Month Summary</h4>
            </div>
            <div class="box-body">
              @php
                $thisMonth = DB::table('tblpayment')
                  ->where('process_status_id', 2)
                  ->whereMonth('date', date('m'))
                  ->whereYear('date', date('Y'));
                $monthlyTotal = $thisMonth->sum('amount');
                $monthlyCount = $thisMonth->count();
              @endphp
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>Total Collections</span>
                <span class="fw-600 text-success">RM {{ number_format($monthlyTotal, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>Total Transactions</span>
                <span class="fw-600">{{ number_format($monthlyCount) }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-15">
                <span>Average per Transaction</span>
                <span class="fw-600">RM {{ $monthlyCount > 0 ? number_format($monthlyTotal / $monthlyCount, 2) : '0.00' }}</span>
              </div>
            </div>
          </div>

          <!-- Quick Reports -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Quick Reports</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                <a href="{{ route('finance.arrearsReport') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Debt & Payment Report
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('finance.agingReport') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Student Aging Report
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('finance.chargeReport') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Charge Report
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
                <a href="{{ route('finance.monthlyPayment') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Monthly Payment Report
                  <i data-feather="chevron-right" class="text-muted"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Tasks & Alerts -->
      <div class="row">
        <div class="col-xl-6 col-12">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">Pending Tasks</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                @php
                  $pendingClaims = DB::table('tblclaim')->where('process_status_id', 1)->count();
                  $totalPayments = DB::table('tblpayment')->where('process_status_id', 1)->count();
                @endphp
                @if($pendingClaims > 0)
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <i data-feather="folder" class="text-warning me-10"></i>
                      Pending Claims to Process
                    </div>
                    <span class="badge badge-warning">{{ $pendingClaims }}</span>
                  </div>
                @endif
                @if($totalPayments > 0)
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <i data-feather="credit-card" class="text-info me-10"></i>
                      Pending Payments
                    </div>
                    <span class="badge badge-info">{{ $totalPayments }}</span>
                  </div>
                @endif
                @if($pendingClaims == 0 && $totalPayments == 0)
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
                  <h4 class="mb-0 fw-600" id="dashboard-message-count">0</h4>
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

          <!-- System Alerts -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">System Alerts</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                @php
                  $totalStudents = DB::table('students')->count();
                  $activeStudents = DB::table('students')->whereNotIn('status', [3,4,5,6,7,8,14,15,16])->count();
                @endphp
                @if($totalStudents > 0)
                  <div class="list-group-item">
                    <div class="d-flex align-items-center">
                      <i data-feather="users" class="text-success me-10"></i>
                      <div>
                        <p class="mb-0 fw-500">{{ $activeStudents }} active students</p>
                        <small class="text-muted">Out of {{ $totalStudents }} total students</small>
                      </div>
                    </div>
                  </div>
                @endif
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

<!-- Chat Modal -->
<div id="chatModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Chat</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="chatModalContent">
        <div id="app">
          <example-component></example-component>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Additional Custom Styles for Finance Dashboard -->
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

/* Chat Modal Styles */
#chatModal .modal-dialog {
  max-width: 800px;
}

#chatModal .modal-body {
  min-height: 400px;
  padding: 20px;
}

#chatModal .modal-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
}

.btn-close {
  background: transparent;
  border: none;
  font-size: 1.25rem;
  color: #000;
  opacity: 0.5;
}

.btn-close:hover {
  opacity: 0.75;
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

.p-10 {
  padding: 10px;
}

.mb-10 {
  margin-bottom: 10px;
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

  // Chat modal functions
  function openChatModal(type, studentData = null) {
    // Show the modal
    $('#chatModal').modal('show');
    
    // You can customize the modal content based on the type
    switch(type) {
      case 'student':
        $('#chatModal .modal-title').text(`Chat - ${studentData.name} (${studentData.no_matric})`);
        break;
      case 'users':
        $('#chatModal .modal-title').text('Chat - All Users');
        break;
      case 'staff':
        $('#chatModal .modal-title').text('Chat - Staff');
        break;
      case 'new':
        $('#chatModal .modal-title').text('New Message');
        break;
    }
  }

  // Function to fetch students with unread messages
  function fetchUnreadStudents() {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('/all/massage/user/getStudentNewMassage') }}",
      method: 'GET',
      success: function(data) {
        $('#loading-students').hide();
        console.log('Raw response:', data); // Debug log
        
        // Parse the HTML response to extract student data
        const $response = $(data);
        console.log('Parsed response:', $response); // Debug log
        
        // Try different selectors to find the table body
        let $tbody = $response.find('tbody#table');
        if ($tbody.length === 0) {
          $tbody = $response.find('tbody');
        }
        if ($tbody.length === 0) {
          $tbody = $response.find('#complex_header2 tbody');
        }
        
        console.log('Found tbody:', $tbody, 'Rows count:', $tbody.find('tr').length); // Debug log
        
        if ($tbody.find('tr').length === 0) {
          $('#unread-students-list').html('<div class="text-center text-muted"><small>No unread messages</small></div>');
          return;
        }
        
        let studentsHtml = '';
        let studentCount = 0;
        
        $tbody.find('tr').each(function(index) {
          const $row = $(this);
          console.log('Processing row:', index, $row.html()); // Debug log
          
          // Get all td elements
          const $cells = $row.find('td');
          console.log('Cells count:', $cells.length); // Debug log
          
          if ($cells.length >= 4 && studentCount < 3) { // Show only first 3 students in dashboard
            const name = $cells.eq(1).text().trim();
            const ic = $cells.eq(2).text().trim();
            const matric = $cells.eq(3).text().trim();
            
            console.log('Student data:', {name, ic, matric}); // Debug log
            
            if (name && ic && name !== '' && ic !== '') {
              studentsHtml += `
                <div class="d-flex align-items-center justify-content-between mb-10 p-10 bg-light rounded">
                  <div class="flex-grow-1">
                    <small class="fw-bold">${name}</small><br>
                    <small class="text-muted">${matric || 'No Matric'}</small>
                  </div>
                  <button class="btn btn-sm btn-success" onclick="openChatModal('student', {name: '${name}', no_matric: '${matric}', ic: '${ic}'})">
                    <i data-feather="message-circle"></i>
                  </button>
                </div>
              `;
              studentCount++;
            }
          }
        });
        
        console.log('Generated HTML:', studentsHtml); // Debug log
        
        if (studentsHtml) {
          $('#unread-students-list').html(studentsHtml);
          // Re-initialize feather icons for new buttons
          if (typeof feather !== 'undefined') {
            feather.replace();
          }
        } else {
          $('#unread-students-list').html('<div class="text-center text-muted"><small>No student data found</small></div>');
        }
      },
      error: function(xhr, status, error) {
        $('#loading-students').hide();
        console.error('AJAX Error:', {xhr, status, error}); // Debug log
        $('#unread-students-list').html('<div class="text-center text-danger"><small>Error loading messages: ' + error + '</small></div>');
      }
    });
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
            <div class="d-flex align-items-center justify-content-between mb-10 p-10 bg-light rounded">
              <div class="flex-grow-1">
                <small class="fw-bold">${name}</small><br>
                <small class="text-muted">${matric || 'No Matric'}</small>
              </div>
              <button class="btn btn-sm btn-success" onclick="openChatModal('student', {name: '${name}', no_matric: '${matric}', ic: '${ic}'})">
                <i data-feather="message-circle"></i>
              </button>
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
          $('#dashboard-message-count').text(count);
          
          // Update the message count display with color based on count
          if(count > 0) {
            $('#dashboard-message-count').removeClass('text-muted').addClass('text-warning fw-bold');
          } else {
            $('#dashboard-message-count').removeClass('text-warning fw-bold').addClass('text-muted');
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
    
    // Also fetch unread students when page loads - try alternative method first
    fetchUnreadStudentsAlternative();
  });
</script>

<!-- Vue.js App Script -->
<script src="{{ mix('js/app.js') }}"></script>
@endsection
