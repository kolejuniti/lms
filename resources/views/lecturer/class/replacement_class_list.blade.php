@extends('layouts.lecturer.lecturer')

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
    transform: scale(1.005);
    transition: all 0.3s ease;
  }

  .course-info {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
  }

  .group-info {
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
                    <div class="date-info">{{ \Carbon\Carbon::parse($app->maklumat_kuliah_gantian_tarikh)->format('d M Y') }}</div>
                    <div class="reason-info">
                      {{ $app->maklumat_kuliah_gantian_hari_masa }}<br>
                      <i class="mdi mdi-map-marker me-1"></i>{{ $app->room_name }}
                    </div>
                  </td>
                  <td>
                    <div class="student-info">{{ $app->student_name }}</div>
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
    };
});
</script>
@endsection
