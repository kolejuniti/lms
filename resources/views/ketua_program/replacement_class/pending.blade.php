@php
    $layoutMap = [
        'PL'   => 'layouts.ketua_program',
        'DN'   => 'layouts.ketua_program',
        'AO'   => 'layouts.ketua_program',
    ];
    $userType = Auth::user()->usrtype ?? '';
    $layout = $layoutMap[$userType] ?? '';
@endphp

@extends($layout)

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

  .action-buttons {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
  }

  .btn-approve {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    color: white;
  }

  .btn-reject {
    background: linear-gradient(45deg, #dc3545, #e74c3c);
    border: none;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    color: white;
  }

  .modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
  }

  .modal-header {
    background: linear-gradient(45deg, #dc3545, #e74c3c);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
  }

  .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
    transition: all 0.3s ease;
  }

  .dark-skin .applications-table .table tbody tr:hover {
    background-color: #212744;
  }

  .lecturer-info {
    font-weight: 600;
    color: var(--bs-body-color, #495057);
    margin-bottom: 0.25rem;
  }

  .dark-skin .lecturer-info {
    color: rgba(255, 255, 255, 0.85);
  }

  .course-info {
    color: var(--bs-secondary-color, #6c757d);
    font-size: 0.9rem;
  }

  .dark-skin .course-info {
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

  .action-buttons-compact {
    display: flex;
    gap: 0.25rem;
  }

  .btn-action-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.8rem;
    border-radius: 15px;
    font-weight: 600;
  }

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
      align-items: center;
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
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      justify-content: center;
    }
    
    .dark-skin .mobile-actions {
      border-top-color: #3c3d54;
    }
    
    .mobile-actions .btn {
      flex: 1;
      min-width: 120px;
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
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Pending Replacement Class Applications</h3>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('ketua_program') }}"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Replacement Class</li>
                <li class="breadcrumb-item active" aria-current="page">Pending Applications</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="row mb-3">
        <div class="col-12">
          <div class="d-flex justify-content-end">
            <a href="{{ route('kp.replacement_class.all') }}" class="btn btn-primary">
              <i class="mdi mdi-view-list me-1"></i>
              View All Applications
            </a>
          </div>
        </div>
      </div>
      
      
      <div class="applications-container">
        @if(isset($message))
          <div class="empty-state">
            <div class="empty-state-icon">
              <i class="mdi mdi-information-outline"></i>
            </div>
            <h4 class="text-muted">{{ $message }}</h4>
          </div>
        @elseif($applications->isEmpty())
          <div class="empty-state">
            <div class="empty-state-icon">
              <i class="mdi mdi-clipboard-check-outline"></i>
            </div>
            <h4 class="text-muted">No pending applications</h4>
            <p class="text-muted">All replacement class applications have been reviewed.</p>
          </div>
        @else
          <div class="applications-table">
            <div class="table-responsive">
              <table class="table" id="applications-table">
                <thead>
                  <tr>
                    <th width="8%"># ID</th>
                    <th width="18%">Lecturer & Course</th>
                    <th width="15%">Cancelled Class</th>
                    <th width="15%">Replacement Class</th>
                    <th width="12%">Student Rep</th>
                    <th width="12%">Submitted</th>
                    <th width="20%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($applications as $key => $app)
                  <tr class="application-row" 
                      data-id="{{ $app->id }}"
                      data-submitted="{{ $app->created_at }}"
                      data-lecturer="{{ $app->lecturer_name }}"
                      data-course="{{ $app->course_code }}"
                      data-student="{{ $app->wakil_pelajar_nama }}">
                    <td>
                      <strong class="text-primary">#{{ $app->id }}</strong>
                    </td>
                    <td>
                      <div class="lecturer-info">{{ $app->lecturer_name }}</div>
                      <div class="course-info">
                        <strong>{{ $app->course_code }}</strong> - {{ $app->course_name }}<br>
                        Group: {{ $app->group_name }}<br>
                        <div class="compact-programs mt-1">
                          @if(is_array($app->programs))
                            @foreach($app->programs as $program)
                              <span class="compact-program-badge">{{ $program }}</span>
                            @endforeach
                          @endif
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="date-info">{{ \Carbon\Carbon::parse($app->tarikh_kuliah_dibatalkan)->format('d M Y') }}</div>
                      <div class="reason-info">{{ Str::limit($app->sebab_kuliah_dibatalkan, 35) }}</div>
                    </td>
                    <td>
                      @if($app->revised_date && $app->revised_status === 'PENDING')
                        <div class="badge bg-warning mb-1">Revised Date Pending</div>
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
                      <div class="student-info">{{ $app->wakil_pelajar_nama }}</div>
                      <div class="phone-info">
                        <i class="mdi mdi-phone me-1"></i>{{ $app->wakil_pelajar_no_tel }}
                      </div>
                    </td>
                    <td>
                      <div class="date-info">{{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}</div>
                      <div class="reason-info">{{ \Carbon\Carbon::parse($app->created_at)->format('g:i A') }}</div>
                    </td>
                    <td>
                      @if($app->revised_date && $app->revised_status === 'PENDING')
                        <div class="action-buttons-compact">
                          <button type="button" class="btn btn-success btn-action-sm" onclick="approveRevisedDate({{ $app->id }})" title="Verify Revised Date">
                            <i class="mdi mdi-check me-1"></i>
                            Verify Revised
                          </button>
                          <button type="button" class="btn btn-danger btn-action-sm" onclick="rejectRevisedDate({{ $app->id }})" title="Unverify Revised Date">
                            <i class="mdi mdi-close me-1"></i>
                            Unverify Revised
                          </button>
                        </div>
                      @else
                        <div class="action-buttons-compact">
                          <button type="button" class="btn btn-success btn-action-sm" onclick="approveApplication({{ $app->id }})" title="Verify">
                            <i class="mdi mdi-check me-1"></i>
                            Verify
                          </button>
                          <button type="button" class="btn btn-danger btn-action-sm" onclick="rejectApplication({{ $app->id }})" title="Unverify">
                            <i class="mdi mdi-close me-1"></i>
                            Unverify
                          </button>
                        </div>
                      @endif
                      <button type="button" class="btn btn-outline-info btn-action-sm" 
                              onclick="viewFullDetails({{ $app->id }})" 
                              data-app='@json($app)'
                              title="View Full Details">
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
                <span class="badge bg-warning">
                  @if($app->revised_date && $app->revised_status === 'PENDING')
                    <i class="mdi mdi-clock me-1"></i>Revised Date Pending
                  @else
                    <i class="mdi mdi-clock me-1"></i>Pending Review
                  @endif
                </span>
              </div>
              
              <div class="mobile-info-row">
                <div class="mobile-info-label">Lecturer:</div>
                <div class="mobile-info-value">{{ $app->lecturer_name }}</div>
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
                <div class="mobile-info-label">Cancelled Date:</div>
                <div class="mobile-info-value">{{ \Carbon\Carbon::parse($app->tarikh_kuliah_dibatalkan)->format('d M Y') }}</div>
              </div>
              
              <div class="mobile-info-row">
                <div class="mobile-info-label">Replacement Date:</div>
                <div class="mobile-info-value">
                  @if($app->revised_date && $app->revised_status === 'PENDING')
                    <div class="badge bg-warning mb-1">Revised Date</div>
                    {{ \Carbon\Carbon::parse($app->revised_date)->format('d M Y') }}
                  @else
                    {{ \Carbon\Carbon::parse($app->maklumat_kuliah_gantian_tarikh)->format('d M Y') }}
                  @endif
                </div>
              </div>
              
              <div class="mobile-info-row">
                <div class="mobile-info-label">Student Rep:</div>
                <div class="mobile-info-value">{{ $app->wakil_pelajar_nama }}</div>
              </div>
              
              <div class="mobile-info-row">
                <div class="mobile-info-label">Submitted:</div>
                <div class="mobile-info-value">{{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}</div>
              </div>
              
              <div class="mobile-actions">
                @if($app->revised_date && $app->revised_status === 'PENDING')
                  <button type="button" class="btn btn-success btn-sm" onclick="approveRevisedDate({{ $app->id }})" title="Verify Revised Date">
                    <i class="mdi mdi-check me-1"></i>Verify Revised
                  </button>
                  <button type="button" class="btn btn-danger btn-sm" onclick="rejectRevisedDate({{ $app->id }})" title="Unverify Revised Date">
                    <i class="mdi mdi-close me-1"></i>Unverify Revised
                  </button>
                @else
                  <button type="button" class="btn btn-success btn-sm" onclick="approveApplication({{ $app->id }})" title="Verify">
                    <i class="mdi mdi-check me-1"></i>Verify
                  </button>
                  <button type="button" class="btn btn-danger btn-sm" onclick="rejectApplication({{ $app->id }})" title="Unverify">
                    <i class="mdi mdi-close me-1"></i>Unverify
                  </button>
                @endif
                <button type="button" class="btn btn-outline-info btn-sm" 
                        onclick="viewFullDetails({{ $app->id }})" 
                        data-app='@json($app)'
                        title="View Full Details">
                  <i class="mdi mdi-eye me-1"></i>Details
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

<!-- Unverification Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectionModalLabel">
          <i class="mdi mdi-close-circle me-2"></i>
          Unverify Application
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="rejectionForm" onsubmit="event.preventDefault(); submitUnverification();">
          <input type="hidden" id="rejectionApplicationId" name="application_id">
          <input type="hidden" name="status" value="NO">
          
          <div class="mb-3">
            <label for="rejectionReason" class="form-label">
              <i class="mdi mdi-comment-text me-1"></i>
              Reason for Unverification <span class="text-danger">*</span>
            </label>
            <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="4" 
                      placeholder="Please provide a detailed reason for rejecting this application..." required></textarea>
            <small class="form-text text-muted">The lecturer will be able to suggest a new date after this rejection.</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="mdi mdi-cancel me-1"></i>
          Cancel
        </button>
        <button type="button" class="btn btn-danger" onclick="submitUnverification()">
          <i class="mdi mdi-close me-1"></i>
          Unverify Application
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

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#applications-table').DataTable({
        "responsive": true,
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "order": [[5, "desc"]], // Sort by submitted date (desc)
        "columnDefs": [
            {
                "targets": [6], // Actions column
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
                title: 'Pending Replacement Class Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] // Exclude actions column
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-outline-danger btn-sm me-1',
                text: '<i class="mdi mdi-file-pdf me-1"></i>PDF',
                title: 'Pending Replacement Class Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] // Exclude actions column
                }
            },
            {
                extend: 'print',
                className: 'btn btn-outline-info btn-sm',
                text: '<i class="mdi mdi-printer me-1"></i>Print',
                title: 'Pending Replacement Class Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] // Exclude actions column
                }
            }
        ],
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Search pending applications...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ pending applications",
            "infoEmpty": "Showing 0 to 0 of 0 pending applications",
            "infoFiltered": "(filtered from _MAX_ total pending applications)",
            "emptyTable": "No pending applications found",
            "zeroRecords": "No matching pending applications found"
        }
    });
});

// View full details function with approve/reject buttons
function viewFullDetails(applicationId) {
    // Get the data from the button's data attribute
    const button = event.target.closest('button');
    const appData = JSON.parse(button.getAttribute('data-app'));
    let programsHtml = '';
    if (Array.isArray(appData.programs)) {
        programsHtml = appData.programs.map(program => `<span class="badge bg-primary me-1">${program}</span>`).join('');
    }
    
    Swal.fire({
        title: `Application #${applicationId} - Full Details`,
        html: `
            <div class="text-start">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6><i class="mdi mdi-account-tie me-1"></i>Lecturer Information</h6>
                        <p><strong>Name:</strong> ${appData.lecturer_name}<br>
                        <strong>Course:</strong> ${appData.course_code} - ${appData.course_name}<br>
                        <strong>Session:</strong> ${appData.SessionName || 'N/A'}<br>
                        <strong>Group:</strong> ${appData.group_name}<br>
                        <strong>Programs:</strong> ${programsHtml}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="mdi mdi-account me-1"></i>Student Representative</h6>
                        <p><strong>Name:</strong> ${appData.wakil_pelajar_nama}<br>
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
                                ${appData.revised_rejection_reason ? `<p><strong>Unverification Reason:</strong> ${appData.revised_rejection_reason}</p>` : ''}
                            </div>
                        </div>
                    </div>
                ` : ''}
                
                ${appData.maklumat_kuliah && appData.maklumat_kuliah.trim() ? `
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
            </div>
        `,
        width: '900px',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '<i class="mdi mdi-check me-1"></i> Verify Application',
        denyButtonText: '<i class="mdi mdi-close me-1"></i> Unverify Application',
        cancelButtonText: 'Close',
        confirmButtonColor: '#28a745',
        denyButtonColor: '#dc3545',
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-success',
            denyButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Verify the application
            updateApplicationStatus(applicationId, 'YES');
        } else if (result.isDenied) {
            // Unverify the application - show rejection modal
            Swal.close();
            rejectApplication(applicationId);
        }
    });
}

// View additional info function
function viewAdditionalInfo(applicationId, info) {
    Swal.fire({
        title: `Additional Information - App #${applicationId}`,
        text: info,
        icon: 'info',
        confirmButtonText: 'Close',
        customClass: {
            popup: 'swal-wide'
        }
    });
}

function approveApplication(applicationId) {
    Swal.fire({
        title: 'Verify Application?',
        text: 'Are you sure you want to verify this replacement class application?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="mdi mdi-check"></i> Yes, Verify',
        cancelButtonText: '<i class="mdi mdi-cancel"></i> Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateApplicationStatus(applicationId, 'YES');
        }
    });
}

function rejectApplication(applicationId) {
    $('#rejectionApplicationId').val(applicationId);
    $('#rejectionModal').modal('show');
}

function submitUnverification() {
    const form = document.getElementById('rejectionForm');
    const formData = new FormData(form);
    
    if (!formData.get('rejection_reason').trim()) {
        Swal.fire({
            title: 'Validation Error',
            text: 'Please provide a reason for rejection.',
            icon: 'error'
        });
        return;
    }
    
    const applicationId = formData.get('application_id');
    updateApplicationStatus(applicationId, 'NO', {
        rejection_reason: formData.get('rejection_reason')
    });
}

function updateApplicationStatus(applicationId, status, additionalData = {}) {
    const data = {
        application_id: applicationId,
        status: status,
        _token: '{{ csrf_token() }}',
        ...additionalData
    };
    
    console.log('Making AJAX request with data:', data);
    console.log('URL:', '{{ route("kp.replacement_class.update_status") }}');
    
    $.ajax({
        url: '{{ route("kp.replacement_class.update_status") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        data: data,
        success: function(response) {
            if (response.success) {
                $('#rejectionModal').modal('hide');
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
            $('#rejectionModal').modal('hide');
            let errorMessage = 'An error occurred while updating the application status.';
            
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
}

// Clear form when modal is hidden
$('#rejectionModal').on('hidden.bs.modal', function () {
    $('#rejectionForm')[0].reset();
});

// Verify revised date function
function approveRevisedDate(applicationId) {
    Swal.fire({
        title: 'Verify Revised Date?',
        text: 'Are you sure you want to verify this revised replacement class date?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="mdi mdi-check"></i> Yes, Verify Revised Date',
        cancelButtonText: '<i class="mdi mdi-cancel"></i> Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateRevisedDateStatus(applicationId, 'YES');
        }
    });
}

// Unverify revised date function
function rejectRevisedDate(applicationId) {
    Swal.fire({
        title: 'Unverify Revised Date',
        input: 'textarea',
        inputLabel: 'Reason for Unverification',
        inputPlaceholder: 'Please provide a reason for rejecting the revised date...',
        inputAttributes: {
            'aria-label': 'Type your rejection reason here'
        },
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="mdi mdi-close"></i> Unverify Revised Date',
        cancelButtonText: '<i class="mdi mdi-cancel"></i> Cancel',
        inputValidator: (value) => {
            if (!value) {
                return 'You need to provide a reason for rejection!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            updateRevisedDateStatus(applicationId, 'NO', {
                rejection_reason: result.value
            });
        }
    });
}

// Update revised date status function
function updateRevisedDateStatus(applicationId, status, additionalData = {}) {
    const data = {
        application_id: applicationId,
        status: status,
        _token: '{{ csrf_token() }}',
        ...additionalData
    };
    
    $.ajax({
        url: '{{ route("kp.replacement_class.update_revised_status") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        data: data,
        success: function(response) {
            if (response.success) {
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
            let errorMessage = 'An error occurred while updating the revised date status.';
            
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
}
</script>

@endsection
