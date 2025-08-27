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
                  <h4 class="mb-0 fw-600">RM {{ number_format(DB::table('tblpayment')->whereDate('created_at', today())->sum('amount'), 2) }}</h4>
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
                  <h4 class="mb-0 fw-600">{{ DB::table('tblpaymentclaim')->where('status', 'pending')->count() }}</h4>
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
                  <p class="mb-5 text-fade">Outstanding Debt</p>
                  <h4 class="mb-0 fw-600">RM {{ number_format(DB::table('tbldebt')->where('balance', '>', 0)->sum('balance'), 2) }}</h4>
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
              <h4 class="box-title">Recent Payments</h4>
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
                      <th>Amount</th>
                      <th>Date</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $recentPayments = DB::table('tblpayment')
                        ->join('students', 'tblpayment.studentic', '=', 'students.ic')
                        ->select('tblpayment.*', 'students.name')
                        ->orderBy('tblpayment.created_at', 'desc')
                        ->limit(5)
                        ->get();
                    @endphp
                    @forelse($recentPayments as $payment)
                      <tr>
                        <td>{{ $payment->receiptno ?? 'N/A' }}</td>
                        <td>{{ $payment->name }}</td>
                        <td>RM {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ date('d/m/Y', strtotime($payment->created_at)) }}</td>
                        <td><span class="badge badge-success">Completed</span></td>
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
                  ->whereMonth('created_at', date('m'))
                  ->whereYear('created_at', date('Y'));
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
                  $pendingClaims = DB::table('tblpaymentclaim')->where('status', 'pending')->count();
                  $pendingRefunds = DB::table('tblpaymentrefund')->where('status', 'pending')->count();
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
                @if($pendingRefunds > 0)
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <i data-feather="arrow-left-circle" class="text-info me-10"></i>
                      Pending Refunds
                    </div>
                    <span class="badge badge-info">{{ $pendingRefunds }}</span>
                  </div>
                @endif
                @if($pendingClaims == 0 && $pendingRefunds == 0)
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
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">System Alerts</h4>
            </div>
            <div class="box-body">
              <div class="list-group list-group-flush">
                @php
                  $overdueStudents = DB::table('tbldebt')
                    ->where('balance', '>', 0)
                    ->where('due_date', '<', now())
                    ->count();
                @endphp
                @if($overdueStudents > 0)
                  <div class="list-group-item">
                    <div class="d-flex align-items-center">
                      <i data-feather="alert-triangle" class="text-danger me-10"></i>
                      <div>
                        <p class="mb-0 fw-500">{{ $overdueStudents }} students with overdue payments</p>
                        <small class="text-muted">Requires immediate attention</small>
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
</style>

<script>
  // Initialize feather icons
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  });
</script>
@endsection
