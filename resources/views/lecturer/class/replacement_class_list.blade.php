@extends('layouts.lecturer.lecturer')

@section('main')

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
  .applications-container {
    background: var(--bs-body-bg, #f8f9fa);
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .dark-skin .applications-container {
    background: #171e32;
  }
  
  .application-card {
    background: var(--bs-card-bg, #ffffff);
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid var(--bs-border-color, #e9ecef);
  }
  
  .dark-skin .application-card {
    background: #293146;
    border-color: #3c3d54;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  }
  
  .application-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
  }
  
  .application-header {
    background: #667eea;
    color: white;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
  }
  
  .application-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
  }
  
  .application-body {
    padding: 1.5rem;
  }
  
  .application-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
  }
  
  .status-badge {
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-size: 0.70rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
  }
  
  .status-verified {
    background: #d4edda;
    color: #155724;
    border: 1px solid #00b894;
  }
  
  .status-not-verified {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #e74c3c;
  }
  
  .program-badge {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    margin-right: 0.5rem;
    margin-bottom: 0.25rem;
    display: inline-block;
  }
  
  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
  }
  
  .info-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    border-left: 4px solid #667eea;
  }
  
  .info-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
  }
  
  .info-content {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.4;
  }
  
  .kp-verification {
    background: rgba(102, 126, 234, 0.05);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 10px;
    padding: 1.25rem;
    margin-top: 1rem;
  }
  
  .rejection-details {
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
  }
  
  .stats-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }
  
  .stats-number {
    font-size: 1.8rem;
    font-weight: bold;
    color: #667eea;
    display: block;
  }
  
  .stats-label {
    color: #6c757d;
    font-size: 0.85rem;
    margin-top: 0.25rem;
  }
  
  .filter-container {
    background: #ffffff;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
  }
  
  .filter-input {
    border: 2px solid #e9ecef;
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
  }
  
  .filter-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .applications-table {
    background: var(--bs-card-bg, #ffffff);
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid var(--bs-border-color, #e9ecef);
  }

  .dark-skin .applications-table {
    background: #293146;
    border-color: #3c3d54;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  }

  .applications-table .table {
    margin-bottom: 0;
    color: var(--bs-body-color, #495057);
  }

  .dark-skin .applications-table .table {
    color: rgba(255, 255, 255, 0.85);
  }

  .applications-table .table thead th {
    background: var(--bs-card-bg, #ffffff);
    color: var(--bs-body-color, #495057);
    border-bottom: 2px solid var(--bs-border-color, #dee2e6);
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
  }

  .dark-skin .applications-table .table thead th {
    background: #293146;
    color: rgba(255, 255, 255, 0.85);
    border-bottom-color: #3c3d54;
  }

  .applications-table .table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-color: var(--bs-border-color-translucent, #f8f9fa);
  }

  .dark-skin .applications-table .table tbody td {
    border-color: #3c3d54;
  }

  .applications-table .table tbody tr:hover {
    background-color: var(--bs-tertiary-bg, #f8f9fa);
    transform: scale(1.005);
    transition: all 0.3s ease;
  }

  .dark-skin .applications-table .table tbody tr:hover {
    background-color: #212744;
  }

  .course-info {
    font-weight: 600;
    color: var(--bs-body-color, #495057);
    margin-bottom: 0.25rem;
  }

  .dark-skin .course-info {
    color: rgba(255, 255, 255, 0.85);
  }

  .group-info {
    color: var(--bs-secondary-color, #6c757d);
    font-size: 0.9rem;
  }

  .dark-skin .group-info {
    color: #a1a4b5;
  }

  .date-info {
    font-weight: 500;
    margin-bottom: 0.25rem;
    color: var(--bs-body-color, #495057);
  }

  .dark-skin .date-info {
    color: rgba(255, 255, 255, 0.85);
  }

  .reason-info {
    color: var(--bs-secondary-color, #6c757d);
    font-size: 0.85rem;
    font-style: italic;
  }

  .dark-skin .reason-info {
    color: #a1a4b5;
  }

  .student-info {
    font-weight: 500;
    margin-bottom: 0.25rem;
    color: var(--bs-body-color, #495057);
  }

  .dark-skin .student-info {
    color: rgba(255, 255, 255, 0.85);
  }

  .phone-info {
    color: var(--bs-secondary-color, #6c757d);
    font-size: 0.85rem;
  }

  .dark-skin .phone-info {
    color: #a1a4b5;
  }

  .compact-programs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
  }

  .compact-program-badge {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 0.15rem 0.5rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
  }
  
  .empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    backdrop-filter: blur(10px);
  }
  
  .empty-state-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 1rem;
  }
  
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
  }
  
  .animate-delay-1 { animation-delay: 0.1s; }
  .animate-delay-2 { animation-delay: 0.2s; }
  .animate-delay-3 { animation-delay: 0.3s; }

  /* DataTables Custom Styling */
  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter,
  .dataTables_wrapper .dataTables_info,
  .dataTables_wrapper .dataTables_paginate {
    margin-bottom: 1rem;
  }

  .dataTables_wrapper .dataTables_filter input {
    border-radius: 25px;
    border: 2px solid #e9ecef;
    padding: 0.5rem 1rem;
    margin-left: 0.5rem;
  }

  .dataTables_wrapper .dataTables_filter input:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .dataTables_wrapper .dataTables_length select {
    border-radius: 15px;
    border: 2px solid #e9ecef;
    padding: 0.25rem 0.5rem;
    margin: 0 0.5rem;
  }

  .page-link {
    border-radius: 20px !important;
    margin: 0 2px;
    border: 1px solid #667eea;
    color: #667eea;
  }

  .page-link:hover {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
  }

  .page-item.active .page-link {
    background-color: #667eea;
    border-color: #667eea;
  }

  /* Mobile Responsive Styles */
  @media (max-width: 768px) {
    .applications-table {
      display: none;
    }
    
    .mobile-applications {
      display: block;
    }
    
    .mobile-app-card {
      background: var(--bs-card-bg, #ffffff);
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 1.5rem;
      padding: 1.5rem;
      border: 1px solid var(--bs-border-color, #e9ecef);
    }
    
    .dark-skin .mobile-app-card {
      background: #293146;
      border-color: #3c3d54;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    
    .mobile-app-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--bs-border-color, #e9ecef);
    }
    
    .dark-skin .mobile-app-header {
      border-bottom-color: #3c3d54;
    }
    
    .mobile-app-id {
      font-size: 1.2rem;
      font-weight: bold;
      color: #667eea;
    }
    
    .mobile-status-section {
      text-align: right;
      flex-shrink: 0;
    }
    
    .mobile-info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.75rem;
      padding: 0.5rem 0;
    }
    
    .mobile-info-label {
      font-weight: 600;
      color: var(--bs-body-color, #495057);
      font-size: 0.9rem;
    }
    
    .dark-skin .mobile-info-label {
      color: rgba(255, 255, 255, 0.85);
    }
    
    .mobile-info-value {
      color: var(--bs-secondary-color, #6c757d);
      font-size: 0.9rem;
      text-align: right;
      flex: 1;
      margin-left: 1rem;
    }
    
    .dark-skin .mobile-info-value {
      color: #a1a4b5;
    }
    
    .mobile-actions {
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid var(--bs-border-color, #e9ecef);
      text-align: center;
    }
    
    .dark-skin .mobile-actions {
      border-top-color: #3c3d54;
    }
    
    .page-header .me-auto {
      margin-bottom: 1rem;
    }
    
    .page-header .ms-auto {
      margin-left: 0 !important;
    }
  }
  
  @media (min-width: 769px) {
    .mobile-applications {
      display: none;
    }
  }
</style>


<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Page Header -->
    <div class="page-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Replacement Class Applications</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Class</li>
                <li class="breadcrumb-item active" aria-current="page">Applications List</li>
              </ol>
            </nav>
          </div>
        </div>
        <div class="ms-auto">
          <div class="stats-card">
            <span class="stats-number">{{ $applications->total() }}</span>
            <div class="stats-label">Total Applications</div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-3">
      <div class="col-12 text-end">
        <a href="{{ route('lecturer.class.replacement_class') }}" class="btn btn-primary btn-lg">
          <i class="mdi mdi-plus me-2"></i>
          New Application
        </a>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Applications Table -->
        @if($applications->count() < 1)
        <div class="empty-state animate-fadeInUp">
          <i class="mdi mdi-file-document-outline empty-state-icon"></i>
          <h3 class="text-muted mb-3">No Applications Found</h3>
          <p class="text-muted mb-4">You haven't submitted any replacement class applications yet.</p>
          <a href="{{ route('lecturer.class.replacement_class') }}" class="btn btn-primary btn-lg">
            <i class="mdi mdi-plus me-2"></i>
            Create Your First Application
          </a>
        </div>
        @else
        <div class="applications-table animate-fadeInUp animate-delay-2">
          <div class="table-responsive">
            <table class="table" id="applications-table">
              <thead>
                <tr>
                  <th width="8%"># ID</th>
                  <th width="20%">Course & Group</th>
                  <th width="15%">Cancelled Class</th>
                  <th width="15%">Replacement Class</th>
                  <th width="12%">Student Rep</th>
                  <th width="10%">Status</th>
                  <th width="12%">Submitted</th>
                  <th width="8%">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($applications as $key => $app)
                <tr class="application-row" 
                    data-id="{{ $app->id }}"
                    data-status="{{ $app->is_verified }}"
                    data-submitted="{{ $app->created_at }}"
                    data-course="{{ $app->course_code }}"
                    data-student="{{ $app->student_name }}">
                  <td>
                    <strong class="text-primary">#{{ $app->id }}</strong>
                  </td>
                  <td>
                    <div class="course-info">{{ $app->course_code }}</div>
                    <div class="group-info">
                      Group: {{ $app->group_name }}<br>
                      <div class="compact-programs mt-1">
                        @foreach($app->programs as $program)
                          <span class="compact-program-badge">{{ $program->progcode }}</span>
                        @endforeach
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="date-info">{{ \Carbon\Carbon::parse($app->tarikh_kuliah_dibatalkan)->format('d M Y') }}</div>
                    <div class="reason-info">{{ Str::limit($app->sebab_kuliah_dibatalkan, 40) }}</div>
                  </td>
                  <td>
                    @if($app->revised_date && $app->revised_status === 'YES')
                      <div class="badge bg-success mb-1">Final: Revised Date</div>
                      <div class="date-info">{{ \Carbon\Carbon::parse($app->revised_date)->format('d M Y') }}</div>
                      <div class="reason-info">
                        {{ $app->revised_time }}<br>
                        <i class="mdi mdi-map-marker me-1"></i>{{ $app->revised_room_name ?? 'Room N/A' }}
                      </div>
                    @else
                      <div class="date-info">{{ \Carbon\Carbon::parse($app->maklumat_kuliah_gantian_tarikh)->format('d M Y') }}</div>
                      <div class="reason-info">
                        {{ $app->maklumat_kuliah_gantian_hari_masa }}<br>
                        <i class="mdi mdi-map-marker me-1"></i>{{ $app->room_name }}
                      </div>
                    @endif
                  </td>
                  <td>
                    <div class="student-info">{{ $app->student_name }}</div>
                    <div class="phone-info">
                      <i class="mdi mdi-phone me-1"></i>{{ $app->wakil_pelajar_no_tel }}
                    </div>
                  </td>
                  <td>
                    <span class="status-badge status-{{ strtolower($app->is_verified) === 'pending' ? 'pending' : (strtolower($app->is_verified) === 'yes' ? 'verified' : 'not-verified') }}">
                      @if(strtolower($app->is_verified) === 'pending')
                        <i class="mdi mdi-clock-outline me-1"></i>Pending
                      @elseif(strtolower($app->is_verified) === 'yes')
                        <i class="mdi mdi-check me-1"></i>Verified
                      @else
                        <i class="mdi mdi-close me-1"></i>Not-Verified
                      @endif
                    </span>
                    @if(strtolower($app->is_verified) === 'no' && !$app->revised_date)
                      <div class="mt-1">
                        <button class="btn btn-sm btn-warning" 
                                onclick="suggestNewDate({{ $app->id }})" 
                                title="Suggest New Date">
                          <i class="mdi mdi-calendar-plus me-1"></i>Suggest New Date
                        </button>
                      </div>
                    @elseif($app->revised_date)
                      <div class="mt-1">
                        <span class="badge bg-info">
                          @if($app->revised_status === 'PENDING')
                            <i class="mdi mdi-clock me-1"></i>Revised Date Pending
                          @elseif($app->revised_status === 'YES')
                            <i class="mdi mdi-check me-1"></i>Revised Date Verified
                          @else
                            <i class="mdi mdi-close me-1"></i>Revised Date Not-Verified
                          @endif
                        </span>
                      </div>
                    @endif
                  </td>
                  <td>
                    <div class="date-info">{{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}</div>
                    <div class="reason-info">{{ \Carbon\Carbon::parse($app->created_at)->format('g:i A') }}</div>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary" 
                            onclick="viewDetails({{ $app->id }})" 
                            data-app='@json($app)'
                            title="View Details">
                      <i class="mdi mdi-eye"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        
        <!-- Mobile Applications View -->
        <div class="mobile-applications">
          @foreach($applications as $key => $app)
          <div class="mobile-app-card">
            <div class="mobile-app-header">
              <div class="mobile-app-id">#{{ $app->id }}</div>
              <div class="mobile-status-section">
                <span class="status-badge status-{{ strtolower($app->is_verified) === 'pending' ? 'pending' : (strtolower($app->is_verified) === 'yes' ? 'verified' : 'not-verified') }}">
                  @if(strtolower($app->is_verified) === 'pending')
                    <i class="mdi mdi-clock-outline me-1"></i>Pending
                  @elseif(strtolower($app->is_verified) === 'yes')
                    <i class="mdi mdi-check me-1"></i>Verified
                  @else
                    <i class="mdi mdi-close me-1"></i>Not-Verified
                  @endif
                </span>
                @if(strtolower($app->is_verified) === 'no' && !$app->revised_date)
                  <div class="mt-1">
                    <button class="btn btn-sm btn-warning" 
                            onclick="suggestNewDate({{ $app->id }})" 
                            title="Suggest New Date">
                      <i class="mdi mdi-calendar-plus me-1"></i>Suggest New Date
                    </button>
                  </div>
                @elseif($app->revised_date)
                  <div class="mt-1">
                    <span class="badge bg-info">
                      @if($app->revised_status === 'PENDING')
                        <i class="mdi mdi-clock me-1"></i>Revised Date Pending
                      @elseif($app->revised_status === 'YES')
                        <i class="mdi mdi-check me-1"></i>Revised Date Verified
                      @else
                        <i class="mdi mdi-close me-1"></i>Revised Date Not-Verified
                      @endif
                    </span>
                  </div>
                @endif
              </div>
            </div>
            
            <div class="mobile-info-row">
              <div class="mobile-info-label">Course:</div>
              <div class="mobile-info-value">{{ $app->course_code }}</div>
            </div>
            
            <div class="mobile-info-row">
              <div class="mobile-info-label">Group:</div>
              <div class="mobile-info-value">{{ $app->group_name }}</div>
            </div>
            
            <div class="mobile-info-row">
              <div class="mobile-info-label">Programs:</div>
              <div class="mobile-info-value">
                @foreach($app->programs as $program)
                  <span class="compact-program-badge">{{ $program->progcode }}</span>
                @endforeach
              </div>
            </div>
            
            <div class="mobile-info-row">
              <div class="mobile-info-label">Cancelled Date:</div>
              <div class="mobile-info-value">{{ \Carbon\Carbon::parse($app->tarikh_kuliah_dibatalkan)->format('d M Y') }}</div>
            </div>
            
            <div class="mobile-info-row">
              <div class="mobile-info-label">Replacement Date:</div>
              <div class="mobile-info-value">
                @if($app->revised_date && $app->revised_status === 'YES')
                  <div class="badge bg-success mb-1">Final: Revised Date</div>
                  {{ \Carbon\Carbon::parse($app->revised_date)->format('d M Y') }}
                @else
                  {{ \Carbon\Carbon::parse($app->maklumat_kuliah_gantian_tarikh)->format('d M Y') }}
                @endif
              </div>
            </div>
            
            <div class="mobile-info-row">
              <div class="mobile-info-label">Student Rep:</div>
              <div class="mobile-info-value">{{ $app->student_name }}</div>
            </div>
            
            <div class="mobile-info-row">
              <div class="mobile-info-label">Contact:</div>
              <div class="mobile-info-value">{{ $app->wakil_pelajar_no_tel }}</div>
            </div>
            
            <div class="mobile-info-row">
              <div class="mobile-info-label">Submitted:</div>
              <div class="mobile-info-value">{{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}</div>
            </div>
            
            <div class="mobile-actions">
              <button class="btn btn-sm btn-outline-primary" 
                      onclick="viewDetails({{ $app->id }})" 
                      data-app='@json($app)'
                      title="View Details">
                <i class="mdi mdi-eye me-1"></i>View Details
              </button>
            </div>
          </div>
          @endforeach
        </div>
        @endif
      </div>
    </section>
  </div>
</div>

<!-- Suggest New Date Modal -->
<div class="modal fade" id="suggestDateModal" tabindex="-1" aria-labelledby="suggestDateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(45deg, #ff9500, #ff6b35); color: white;">
        <h5 class="modal-title" id="suggestDateModalLabel">
          <i class="mdi mdi-calendar-plus me-2"></i>
          Suggest New Replacement Date
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="suggestDateForm">
          <input type="hidden" id="suggestApplicationId" name="application_id">
          
          <div class="row">
            <div class="col-md-4">
              <div class="mb-3">
                <label for="revisedDate" class="form-label">
                  <i class="mdi mdi-calendar me-1"></i>
                  New Replacement Date <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control" id="revisedDate" name="revised_date" 
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label for="revisedTime" class="form-label">
                  <i class="mdi mdi-clock me-1"></i>
                  Time <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="revisedTime" name="revised_time" 
                       placeholder="e.g., Monday, 2:00 PM - 4:00 PM" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label for="revisedRoom" class="form-label">
                  <i class="mdi mdi-map-marker me-1"></i>
                  Venue <span class="text-danger">*</span>
                </label>
                <select class="form-control" id="revisedRoom" name="revised_room_id" required>
                  <option value="" disabled selected>Choose a room...</option>
                  <!-- Rooms will be loaded via AJAX -->
                </select>
              </div>
            </div>
          </div>
          
          <div class="alert alert-info">
            <i class="mdi mdi-information me-2"></i>
            <strong>Note:</strong> Your suggested new date will be sent for approval by the program coordinator. 
            You will be notified once the decision is made.
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="mdi mdi-cancel me-1"></i>
          Cancel
        </button>
        <button type="button" class="btn btn-warning" onclick="submitSuggestedDate()">
          <i class="mdi mdi-send me-1"></i>
          Submit Suggestion
        </button>
      </div>
    </div>
  </div>
</div>

<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize DataTable
    $('#applications-table').DataTable({
        "responsive": true,
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "order": [[6, "desc"]], // Sort by submitted date (desc)
        "columnDefs": [
            {
                "targets": [7], // Actions column
                "orderable": false,
                "searchable": false
            }
        ],
        "dom": '<"row"<"col-md-6"l><"col-md-6"f>>' +
               '<"row"<"col-md-12"B>>' +
               '<"row"<"col-md-12"tr>>' +
               '<"row"<"col-md-5"i><"col-md-7"p>>',
        "buttons": [
            {
                extend: 'copy',
                className: 'btn btn-outline-secondary btn-sm me-1',
                text: '<i class="mdi mdi-content-copy me-1"></i>Copy'
            },
            {
                extend: 'excel',
                className: 'btn btn-outline-success btn-sm me-1',
                text: '<i class="mdi mdi-file-excel me-1"></i>Excel',
                title: 'Replacement Class Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Exclude actions column
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-outline-danger btn-sm me-1',
                text: '<i class="mdi mdi-file-pdf me-1"></i>PDF',
                title: 'Replacement Class Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Exclude actions column
                }
            },
            {
                extend: 'print',
                className: 'btn btn-outline-info btn-sm',
                text: '<i class="mdi mdi-printer me-1"></i>Print',
                title: 'Replacement Class Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Exclude actions column
                }
            }
        ],
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Search applications...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ applications",
            "infoEmpty": "Showing 0 to 0 of 0 applications",
            "infoFiltered": "(filtered from _MAX_ total applications)",
            "emptyTable": "No applications found",
            "zeroRecords": "No matching applications found"
        }
    });
    
    // View details modal function
    window.viewDetails = function(applicationId) {
        // Get the data from the button's data attribute
        const button = event.target.closest('button');
        const appData = JSON.parse(button.getAttribute('data-app'));
        let programsHtml = '';
        if (Array.isArray(appData.programs)) {
            programsHtml = appData.programs.map(program => `<span class="badge bg-primary me-1">${program.progcode || program}</span>`).join('');
        }
        
        let verificationHtml = '';
        if (appData.is_verified !== 'PENDING') {
            verificationHtml = `
                <div class="mt-3">
                    <h6 class="text-start"><i class="mdi mdi-account-check me-1"></i>Verification Details</h6>
                    <div class="text-start">
                        ${appData.kp_name ? 
                            `<p><strong>Verified by:</strong> ${appData.kp_name}<br>
                             <i class="mdi mdi-email me-1"></i>${appData.kp_email || 'Email not available'}</p>` : 
                            '<p><em>KP information not available</em></p>'}
                        
                        ${appData.is_verified === 'NO' ? `
                            <div class="alert alert-danger text-start">
                                ${appData.next_date ? `<p><strong><i class="mdi mdi-calendar-alert me-1"></i>Next Available Date:</strong> ${new Date(appData.next_date).toLocaleDateString()}</p>` : ''}
                                ${appData.rejection_reason ? `<p><strong><i class="mdi mdi-comment-alert me-1"></i>Rejection Reason:</strong> ${appData.rejection_reason}</p>` : ''}
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        }
        
        Swal.fire({
            title: `Application #${applicationId} - Full Details`,
            html: `
                <div class="text-start">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6><i class="mdi mdi-book me-1"></i>Course Information</h6>
                            <p><strong>Course:</strong> ${appData.course_code} - ${appData.course_name}<br>
                            <strong>Group:</strong> ${appData.group_name}<br>
                            <strong>Programs:</strong> ${programsHtml}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="mdi mdi-account me-1"></i>Student Representative</h6>
                            <p><strong>Name:</strong> ${appData.student_name || appData.wakil_pelajar_nama}<br>
                            <strong>Matric No:</strong> ${appData.no_matric || 'N/A'}<br>
                            <i class="mdi mdi-phone me-1"></i><strong>Phone:</strong> ${appData.wakil_pelajar_no_tel}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6><i class="mdi mdi-calendar-remove me-1"></i>Cancelled Class</h6>
                            <p><strong>Date:</strong> ${new Date(appData.tarikh_kuliah_dibatalkan).toLocaleDateString()}<br>
                            <strong>Reason:</strong> ${appData.sebab_kuliah_dibatalkan}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="mdi mdi-calendar-plus me-1"></i>Original Replacement Class</h6>
                            <p><strong>Date:</strong> ${new Date(appData.maklumat_kuliah_gantian_tarikh).toLocaleDateString()}<br>
                            <strong>Time:</strong> ${appData.maklumat_kuliah_gantian_hari_masa}<br>
                            <strong>Venue:</strong> ${appData.room_name}</p>
                        </div>
                    </div>
                    
                    ${appData.revised_date ? `
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6><i class="mdi mdi-calendar-edit me-1"></i>Revised Replacement Class</h6>
                                <div class="alert alert-warning">
                                    <p><strong>New Date:</strong> ${new Date(appData.revised_date).toLocaleDateString()}<br>
                                    <strong>New Time:</strong> ${appData.revised_time}<br>
                                    <strong>New Venue:</strong> ${appData.revised_room_name || 'N/A'}<br>
                                    <strong>Status:</strong> <span class="badge ${appData.revised_status === 'YES' ? 'bg-success' : appData.revised_status === 'NO' ? 'bg-danger' : 'bg-warning'}">${appData.revised_status === 'YES' ? 'Verified' : appData.revised_status === 'NO' ? 'Not-Verified' : 'Pending Review'}</span></p>
                                    ${appData.revised_rejection_reason ? `<p><strong>Rejection Reason:</strong> ${appData.revised_rejection_reason}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${appData.maklumat_kuliah ? `
                        <div class="mb-3">
                            <h6><i class="mdi mdi-information me-1"></i>Additional Information</h6>
                            <div class="alert alert-info text-start">${appData.maklumat_kuliah}</div>
                        </div>
                    ` : ''}
                    
                    <div class="mb-3">
                        <h6><i class="mdi mdi-clock me-1"></i>Application Timeline</h6>
                        <p><strong>Submitted:</strong> ${new Date(appData.created_at).toLocaleString()}<br>
                        <strong>Last Updated:</strong> ${new Date(appData.updated_at).toLocaleString()}</p>
                    </div>
                    
                    ${verificationHtml}
                </div>
            `,
            width: '900px',
            confirmButtonText: 'Close',
            customClass: {
                popup: 'swal-wide'
            }
        });
    };
    
    // Suggest new date function
    window.suggestNewDate = function(applicationId) {
        $('#suggestApplicationId').val(applicationId);
        
        // Load lecture rooms
        $.ajax({
            url: '{{ route("lecturer.replacement_class.getLectureRooms") }}',
            method: 'GET',
            success: function(data) {
                $('#revisedRoom').html(data);
            },
            error: function() {
                console.error('Failed to load lecture rooms');
            }
        });
        
        $('#suggestDateModal').modal('show');
    };
    
    // Submit suggested date function
    window.submitSuggestedDate = function() {
        const form = document.getElementById('suggestDateForm');
        const formData = new FormData(form);
        
        // Validate required fields
        if (!formData.get('revised_date') || !formData.get('revised_time') || !formData.get('revised_room_id')) {
            Swal.fire({
                title: 'Validation Error',
                text: 'Please fill in all required fields.',
                icon: 'error'
            });
            return;
        }
        
        // Submit the suggestion
        $.ajax({
            url: '{{ route("lecturer.replacement_class.suggest_date") }}',
            method: 'POST',
            data: {
                application_id: formData.get('application_id'),
                revised_date: formData.get('revised_date'),
                revised_time: formData.get('revised_time'),
                revised_room_id: formData.get('revised_room_id'),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#suggestDateModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                $('#suggestDateModal').modal('hide');
                let errorMessage = 'An error occurred while submitting the suggestion.';
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error'
                });
            }
        });
    };
    
    // Clear form when modal is hidden
    $('#suggestDateModal').on('hidden.bs.modal', function () {
        $('#suggestDateForm')[0].reset();
    });
});
</script>
@endsection
