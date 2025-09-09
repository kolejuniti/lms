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
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .application-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
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
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
  }
  
  .status-approved {
    background: #d4edda;
    color: #155724;
    border: 1px solid #00b894;
  }
  
  .status-rejected {
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

  .filter-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }

  .search-input {
    border: 2px solid #e9ecef;
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
  }
  
  .search-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .stats-row {
    margin-bottom: 2rem;
  }

  .stats-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    border: none;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
  }

  .stats-number {
    font-size: 2.5rem;
    font-weight: bold;
    display: block;
  }

  .stats-label {
    font-size: 0.9rem;
    margin-top: 0.5rem;
    opacity: 0.9;
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

  .applications-table {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #e9ecef;
  }

  .applications-table .table {
    margin-bottom: 0;
  }

  .applications-table .table thead th {
    background: #ffffff;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
  }

  .applications-table .table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-color: #f8f9fa;
  }

  .applications-table .table tbody tr:hover {
    background-color: #f8f9fa;
    transition: all 0.3s ease;
  }

  .lecturer-info {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
  }

  .course-info {
    color: #6c757d;
    font-size: 0.9rem;
  }

  .date-info {
    font-weight: 500;
    margin-bottom: 0.25rem;
  }

  .reason-info {
    color: #6c757d;
    font-size: 0.85rem;
    font-style: italic;
  }

  .student-info {
    font-weight: 500;
    margin-bottom: 0.25rem;
  }

  .phone-info {
    color: #6c757d;
    font-size: 0.85rem;
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

  .kp-info {
    font-size: 0.85rem;
    color: #6c757d;
  }

  .rejection-compact {
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 5px;
    padding: 0.5rem;
    margin-top: 0.25rem;
    font-size: 0.8rem;
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


</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Page Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">All Replacement Class Applications</h3>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('ketua_program') }}"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Replacement Class</li>
                <li class="breadcrumb-item active" aria-current="page">All Applications</li>
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
            <a href="{{ route('kp.replacement_class.pending') }}" class="btn btn-primary">
              <i class="mdi mdi-clock me-1"></i>
              Pending Applications
            </a>
          </div>
        </div>
      </div>
      
      <div class="applications-container">
        
        @if(isset($message))
          <div class="row">
            <div class="col-12">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="mdi mdi-information-outline"></i>
                </div>
                <h4 class="text-muted">{{ $message }}</h4>
              </div>
            </div>
          </div>
        @elseif($applications->isEmpty())
          <div class="row">
            <div class="col-12">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="mdi mdi-clipboard-list-outline"></i>
                </div>
                <h4 class="text-muted">No applications found</h4>
                <p class="text-muted">No replacement class applications have been submitted yet.</p>
              </div>
            </div>
          </div>
        @else
          <!-- Statistics Row -->
          <div class="row stats-row">
            <div class="col-md-3">
              <div class="stats-card">
                <span class="stats-number">{{ $applications->count() }}</span>
                <div class="stats-label">
                  <i class="mdi mdi-clipboard-list me-1"></i>
                  Total Applications
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <span class="stats-number">{{ $applications->where('is_verified', 'YES')->count() }}</span>
                <div class="stats-label">
                  <i class="mdi mdi-check me-1"></i>
                  Approved
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stats-card" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                <span class="stats-number">{{ $applications->where('is_verified', 'PENDING')->count() }}</span>
                <div class="stats-label">
                  <i class="mdi mdi-clock me-1"></i>
                  Pending
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stats-card" style="background: linear-gradient(135deg, #dc3545, #e74c3c);">
                <span class="stats-number">{{ $applications->where('is_verified', 'NO')->count() }}</span>
                <div class="stats-label">
                  <i class="mdi mdi-close me-1"></i>
                  Rejected
                </div>
              </div>
            </div>
          </div>


          <!-- Applications Table -->
          <div class="applications-table" id="applicationsContainer">
            <div class="table-responsive">
              <table class="table" id="applications-table">
                <thead>
                  <tr>
                    <th width="6%"># ID</th>
                    <th width="16%">Lecturer & Course</th>
                    <th width="12%">Cancelled Class</th>
                    <th width="12%">Replacement Class</th>
                    <th width="10%">Student Rep</th>
                    <th width="8%">Status</th>
                    <th width="8%">Submitted</th>
                    <th width="14%">Verification</th>
                    <th width="14%">Details</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($applications as $key => $app)
                  <tr class="application-item" 
                      data-lecturer="{{ strtolower($app->lecturer_name) }}"
                      data-course="{{ strtolower($app->course_code) }}"
                      data-id="{{ $app->id }}"
                      data-status="{{ $app->is_verified }}"
                      data-submitted="{{ $app->created_at }}">
                    <td>
                      <strong class="text-primary">#{{ $app->id }}</strong>
                    </td>
                    <td>
                      <div class="lecturer-info">{{ $app->lecturer_name }}</div>
                      <div class="course-info">
                        <strong>{{ $app->course_code }}</strong> - {{ Str::limit($app->course_name, 20) }}<br>
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
                      <div class="reason-info">{{ Str::limit($app->sebab_kuliah_dibatalkan, 30) }}</div>
                    </td>
                    <td>
                      <div class="date-info">{{ \Carbon\Carbon::parse($app->maklumat_kuliah_gantian_tarikh)->format('d M Y') }}</div>
                      <div class="reason-info">
                        {{ $app->maklumat_kuliah_gantian_hari_masa }}<br>
                        <i class="mdi mdi-map-marker me-1"></i>{{ Str::limit($app->room_name, 15) }}
                      </div>
                    </td>
                    <td>
                      <div class="student-info">{{ $app->wakil_pelajar_nama }}</div>
                      <div class="phone-info">
                        <i class="mdi mdi-phone me-1"></i>{{ $app->wakil_pelajar_no_tel }}
                      </div>
                    </td>
                    <td>
                      <span class="status-badge status-{{ strtolower($app->is_verified) === 'pending' ? 'pending' : (strtolower($app->is_verified) === 'yes' ? 'approved' : 'rejected') }}">
                        @if(strtolower($app->is_verified) === 'pending')
                          <i class="mdi mdi-clock-outline me-1"></i>Pending
                        @elseif(strtolower($app->is_verified) === 'yes')
                          <i class="mdi mdi-check me-1"></i>Approved
                        @else
                          <i class="mdi mdi-close me-1"></i>Rejected
                        @endif
                      </span>
                    </td>
                    <td>
                      <div class="date-info">{{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}</div>
                      <div class="reason-info">{{ \Carbon\Carbon::parse($app->created_at)->format('g:i A') }}</div>
                    </td>
                    <td>
                      @if($app->is_verified !== 'PENDING')
                        @if($app->kp_name)
                          <div class="kp-info">
                            <strong>{{ $app->kp_name }}</strong><br>
                            <i class="mdi mdi-email me-1"></i>{{ Str::limit($app->kp_email, 15) }}
                          </div>
                        @else
                          <em class="text-muted">KP info N/A</em>
                        @endif
                        
                        @if($app->is_verified === 'NO')
                          <div class="rejection-compact">
                            @if($app->next_date)
                            <div class="text-danger">
                              <strong>Next Date:</strong><br>
                              {{ \Carbon\Carbon::parse($app->next_date)->format('d M Y') }}
                            </div>
                            @endif
                          </div>
                        @endif
                      @else
                        <span class="text-muted">Awaiting review</span>
                      @endif
                    </td>
                    <td>
                      <button class="btn btn-sm btn-outline-info" 
                              onclick="viewFullDetails({{ $app->id }})" 
                              data-app='@json($app)'
                              title="View Full Details">
                        <i class="mdi mdi-eye"></i>
                      </button>
                      @if($app->maklumat_kuliah)
                      <button class="btn btn-sm btn-outline-secondary mt-1" 
                              onclick="viewAdditionalInfo({{ $app->id }})" 
                              data-info="{{ $app->maklumat_kuliah }}"
                              title="Additional Info">
                        <i class="mdi mdi-information"></i>
                      </button>
                      @endif
                      @if($app->is_verified === 'NO' && $app->rejection_reason)
                      <button class="btn btn-sm btn-outline-danger mt-1" 
                              onclick="viewRejectionReason({{ $app->id }})" 
                              data-reason="{{ $app->rejection_reason }}"
                              title="Rejection Reason">
                        <i class="mdi mdi-comment-alert"></i>
                      </button>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif
      </div>
    </section>
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
        "order": [[6, "desc"]], // Sort by submitted date (desc)
        "columnDefs": [
            {
                "targets": [8], // Details column (actions)
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
                title: 'All Replacement Class Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude details column
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-outline-danger btn-sm me-1',
                text: '<i class="mdi mdi-file-pdf me-1"></i>PDF',
                title: 'All Replacement Class Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude details column
                }
            },
            {
                extend: 'print',
                className: 'btn btn-outline-info btn-sm',
                text: '<i class="mdi mdi-printer me-1"></i>Print',
                title: 'All Replacement Class Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude details column
                }
            }
        ],
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Search all applications...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ applications",
            "infoEmpty": "Showing 0 to 0 of 0 applications",
            "infoFiltered": "(filtered from _MAX_ total applications)",
            "emptyTable": "No applications found",
            "zeroRecords": "No matching applications found"
        }
    });
});

// View full details function
function viewFullDetails(applicationId) {
    // Get the data from the button's data attribute
    const button = event.target.closest('button');
    const appData = JSON.parse(button.getAttribute('data-app'));
    let programsHtml = '';
    if (Array.isArray(appData.programs)) {
        programsHtml = appData.programs.map(program => `<span class="badge bg-primary me-1">${program}</span>`).join('');
    }
    
    let verificationHtml = '';
    if (appData.is_verified !== 'PENDING') {
        verificationHtml = `
            <div class="mt-3">
                <h6 class="text-start"><i class="mdi mdi-account-check me-1"></i>Verification Details</h6>
                <div class="text-start">
                    ${appData.kp_name ? 
                        `<p><strong>Verified by:</strong> ${appData.kp_name}<br>
                         <i class="mdi mdi-email me-1"></i>${appData.kp_email}</p>` : 
                        '<p><em>KP information not available</em></p>'}
                    
                    ${appData.is_verified === 'NO' ? `
                        <div class="alert alert-danger text-start">
                            ${appData.next_date ? `<p><strong><i class="mdi mdi-calendar-alert me-1"></i>Next Available Date:</strong> ${new Date(appData.next_date).toLocaleDateString()}</p>` : ''}
                            ${appData.rejection_reason ? `<p><strong><i class="mdi mdi-comment-alert me-1"></i>Rejection Reason:</strong> ${appData.rejection_reason}</p>` : ''}
                        </div>
                    ` : appData.is_verified === 'YES' ? `
                        <div class="alert alert-success text-start">
                            <p><strong><i class="mdi mdi-check-circle me-1"></i>Status:</strong> Application Approved</p>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }
    
    Swal.fire({
        title: `Application #${applicationId} - Complete Details`,
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
                        
                        <h6><i class="mdi mdi-flag me-1"></i>Current Status</h6>
                        <p><span class="badge ${appData.is_verified === 'YES' ? 'bg-success' : appData.is_verified === 'NO' ? 'bg-danger' : 'bg-warning'} fs-6">
                            ${appData.is_verified === 'YES' ? 'Approved' : appData.is_verified === 'NO' ? 'Rejected' : 'Pending Review'}
                        </span></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6><i class="mdi mdi-calendar-remove me-1"></i>Cancelled Class</h6>
                        <p><strong>Date:</strong> ${new Date(appData.tarikh_kuliah_dibatalkan).toLocaleDateString()}<br>
                        <strong>Reason:</strong> ${appData.sebab_kuliah_dibatalkan}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="mdi mdi-calendar-plus me-1"></i>Replacement Class</h6>
                        <p><strong>Date:</strong> ${new Date(appData.maklumat_kuliah_gantian_tarikh).toLocaleDateString()}<br>
                        <strong>Time:</strong> ${appData.maklumat_kuliah_gantian_hari_masa}<br>
                        <strong>Venue:</strong> ${appData.room_name}</p>
                    </div>
                </div>
                
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
}

// View additional info function
function viewAdditionalInfo(applicationId) {
    const button = event.target.closest('button');
    const info = button.getAttribute('data-info');
    Swal.fire({
        title: `Additional Information - App #${applicationId}`,
        text: info,
        icon: 'info',
        confirmButtonText: 'Close'
    });
}

// View rejection reason function
function viewRejectionReason(applicationId) {
    const button = event.target.closest('button');
    const reason = button.getAttribute('data-reason');
    Swal.fire({
        title: `Rejection Reason - App #${applicationId}`,
        text: reason,
        icon: 'error',
        confirmButtonText: 'Close'
    });
}
</script>

@endsection
