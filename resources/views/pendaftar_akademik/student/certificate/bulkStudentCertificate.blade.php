@extends('layouts.pendaftar_akademik')

@section('main')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
/* Ensure table takes full width */
#certificates-table {
    width: 100% !important;
}

.dataTables_wrapper {
    width: 100% !important;
}

.table-responsive {
    width: 100% !important;
}

/* Style the buttons container */
.dt-buttons {
    margin-bottom: 10px;
    display: inline-block;
    text-align: left;
}

.dt-buttons .btn {
    margin-right: 5px;
    margin-bottom: 5px;
}

/* Force buttons to stay on the left */
.dataTables_wrapper .dt-buttons {
    float: none !important;
    text-align: left !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .dt-buttons .btn {
        font-size: 12px;
        padding: 5px 10px;
    }
    
    .dt-buttons .btn i {
        margin-right: 3px !important;
    }
}

/* Ensure proper spacing in DataTables controls */
.dataTables_length {
    margin-bottom: 10px;
}

.dataTables_filter {
    margin-bottom: 10px;
}

/* Make sure the wrapper doesn't constrain width */
.card-body .table-responsive {
    overflow-x: auto;
}

/* Force table to use full container width */
.dataTables_wrapper .row {
    margin: 0;
    width: 100%;
}

.dataTables_wrapper .row [class*="col-"] {
    padding: 0 5px;
}

/* Fix DataTables button positioning */
.dataTables_wrapper .row:first-child {
    margin-bottom: 15px;
}

.dataTables_wrapper .row:first-child .dt-buttons {
    text-align: left !important;
    float: none !important;
    display: inline-block;
}

/* Ensure proper table layout */
.dataTables_wrapper {
    position: relative;
    clear: both;
}

/* Remove responsive features that break column alignment */
.dataTables_wrapper .dataTables_scroll {
    overflow-x: auto;
}

.dataTables_wrapper .dataTables_scrollHead {
    overflow: hidden;
}

.dataTables_wrapper .dataTables_scrollBody {
    overflow: auto;
}

/* Ensure table cells don't wrap unnecessarily */
#certificates-table td, #certificates-table th {
    white-space: nowrap;
    padding: 8px 12px;
}

/* Make program column allow text wrapping since it can be long */
#certificates-table td:nth-child(6), #certificates-table th:nth-child(6) {
    white-space: normal;
    max-width: 200px;
    word-wrap: break-word;
}

/* Ensure the table container takes full width */
.card .card-body {
    padding: 15px;
}

/* Fix table header and body alignment */
#certificates-table thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 10;
}

/* Ensure consistent column widths */
#certificates-table th:nth-child(1) { width: 50px; }   /* Checkbox */
#certificates-table th:nth-child(2) { width: 120px; }  /* Serial Number */
#certificates-table th:nth-child(3) { width: 200px; }  /* Student Name */
#certificates-table th:nth-child(4) { width: 120px; }  /* IC Number */
#certificates-table th:nth-child(5) { width: 100px; }  /* Matric Number */
#certificates-table th:nth-child(6) { width: 200px; }  /* Program */
#certificates-table th:nth-child(7) { width: 80px; }   /* Status */
#certificates-table th:nth-child(8) { width: 120px; }  /* Date Generated */
</style>
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Bulk Student Certificate</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Bulk Student Certificate</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Bulk Claim Student Certificates</h3>
              </div>
              <!-- /.card-header -->
              
              <!-- Control Section -->
              <div class="card mb-3">
                <div class="card-header">
                  <div class="d-flex justify-content-between align-items-center">
                    <b>Certificates with NEW Status</b>
                    <div>
                      <button type="button" class="btn btn-info me-2" id="refresh-certificates">
                        <i class="fa fa-refresh"></i> Refresh
                      </button>
                      <button type="button" class="btn btn-success" id="bulk-claim-selected" disabled>
                        <i class="fa fa-check"></i> Claim Selected (<span id="selected-count">0</span>)
                      </button>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> 
                    Select certificates with NEW status to bulk claim them. Only certificates with NEW status can be claimed.
                  </div>
                  <div id="loading-message" class="text-center" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i> Loading certificates...
                  </div>
                  <div id="no-certificates-message" class="text-center text-muted" style="display: none;">
                    <i class="fa fa-info-circle"></i> No certificates with NEW status found.
                  </div>
                </div>
              </div>

              <!-- Certificates Table -->
              <div id="certificates-section">
                <div class="card mb-3">
                  <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                      <b>Available Certificates</b>
                      <div class="form-check">
                        <input type="checkbox" id="select-all" class="filled-in">
                        <label for="select-all">Select All</label>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="certificates-table">
                        <thead>
                          <tr>
                            <th width="50">
                              <input type="checkbox" id="select-all-header" class="filled-in">
                            </th>
                            <th>Serial Number</th>
                            <th>Student Name</th>
                            <th>IC Number</th>
                            <th>Matric Number</th>
                            <th>Program</th>
                            <th>Status</th>
                            <th>Date Generated</th>
                          </tr>
                        </thead>
                        <tbody id="certificates-table-body">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Bulk Action Result -->
              <div id="bulk-result" style="display: none;">
                <div class="card mb-3">
                  <div class="card-header">
                    <b>Bulk Action Result</b>
                  </div>
                  <div class="card-body">
                    <div id="bulk-result-content">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script type="text/javascript">
var dataTable;

$(document).ready(function() {
    loadNewCertificates();
});

$('#refresh-certificates').on('click', function() {
    loadNewCertificates();
});

$('#select-all, #select-all-header').on('change', function() {
    var isChecked = $(this).is(':checked');
    
    // Handle DataTable pagination - select all visible rows
    if (dataTable) {
        dataTable.rows({page: 'current'}).every(function() {
            var checkbox = $(this.node()).find('.certificate-checkbox');
            checkbox.prop('checked', isChecked);
        });
    } else {
        $('.certificate-checkbox').prop('checked', isChecked);
    }
    
    $('#select-all').prop('checked', isChecked);
    $('#select-all-header').prop('checked', isChecked);
    updateSelectedCount();
});

$(document).on('change', '.certificate-checkbox', function() {
    updateSelectedCount();
    updateSelectAllState();
});

$('#bulk-claim-selected').on('click', function() {
    var selectedIds = [];
    $('.certificate-checkbox:checked').each(function() {
        selectedIds.push($(this).val());
    });
    
    if (selectedIds.length === 0) {
        alert('Please select at least one certificate to claim.');
        return;
    }
    
    var confirmMessage = 'Are you sure you want to claim ' + selectedIds.length + ' certificate(s)?';
    if (confirm(confirmMessage)) {
        bulkClaimCertificates(selectedIds);
    }
});

function loadNewCertificates() {
    $('#loading-message').show();
    $('#no-certificates-message').hide();
    $('#certificates-section').hide();
    $('#bulk-result').hide();
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('AR/student/certificate/bulk/getNewCertificates') }}",
        method: 'POST',
        error: function(err) {
            console.log("Error loading certificates:", err);
            $('#loading-message').hide();
            alert("Error loading certificates");
        },
        success: function(data) {
            $('#loading-message').hide();
            
            if (data.success) {
                displayCertificates(data.data);
            } else {
                alert('Error: ' + data.message);
            }
        }
    });
}

function displayCertificates(certificates) {
    // Destroy existing DataTable if it exists
    if (dataTable) {
        dataTable.destroy();
        dataTable = null;
    }
    
    var tbody = $('#certificates-table-body');
    tbody.empty();
    
    if (certificates.length > 0) {
        $.each(certificates, function(index, cert) {
            var dateGenerated = new Date(cert.date).toLocaleDateString();
            var statusBadge = '<span class="badge bg-primary">NEW</span>';
            
            var row = '<tr>' +
                        '<td>' +
                          '<input type="checkbox" id="cert_' + cert.id + '" class="certificate-checkbox filled-in" value="' + cert.id + '">' +
                          '<label for="cert_' + cert.id + '"></label>' +
                        '</td>' +
                        '<td>' + cert.serial_no + '</td>' +
                        '<td>' + cert.student_name + '</td>' +
                        '<td>' + cert.student_ic + '</td>' +
                        '<td>' + cert.student_matric + '</td>' +
                        '<td>' + (cert.student_program || 'N/A') + '</td>' +
                        '<td>' + statusBadge + '</td>' +
                        '<td>' + dateGenerated + '</td>' +
                      '</tr>';
            tbody.append(row);
        });
        
        // Initialize DataTable with export buttons
        dataTable = $('#certificates-table').DataTable({
            "responsive": false,
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[7, "desc"]], // Sort by date generated (desc)
            "scrollX": true,
            "autoWidth": false,
            "fixedHeader": false,
            "columnDefs": [
                {
                    "targets": [0], // Checkbox column
                    "orderable": false,
                    "searchable": false,
                    "width": "50px"
                },
                {
                    "targets": [1], // Serial Number
                    "width": "120px"
                },
                {
                    "targets": [2], // Student Name
                    "width": "200px"
                },
                {
                    "targets": [3], // IC Number
                    "width": "120px"
                },
                {
                    "targets": [4], // Matric Number
                    "width": "100px"
                },
                {
                    "targets": [5], // Program
                    "width": "200px"
                },
                {
                    "targets": [6], // Status
                    "width": "80px"
                },
                {
                    "targets": [7], // Date Generated
                    "width": "120px"
                }
            ],
            "dom": '<"row"<"col-md-8"B><"col-md-2"l><"col-md-2"f>>' +
                   '<"row"<"col-md-12"tr>>' +
                   '<"row"<"col-md-5"i><"col-md-7"p>>',
            "buttons": [
                {
                    extend: 'copy',
                    className: 'btn btn-outline-secondary btn-sm me-1',
                    text: '<i class="fa fa-copy me-1"></i>Copy',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7] // Exclude checkbox column
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-outline-success btn-sm me-1',
                    text: '<i class="fa fa-file-excel-o me-1"></i>Excel',
                    title: 'Student Certificates - ' + new Date().toLocaleDateString(),
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7] // Exclude checkbox column
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-outline-danger btn-sm me-1',
                    text: '<i class="fa fa-file-pdf-o me-1"></i>PDF',
                    title: 'Student Certificates - ' + new Date().toLocaleDateString(),
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7] // Exclude checkbox column
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-info btn-sm',
                    text: '<i class="fa fa-print me-1"></i>Print',
                    title: 'Student Certificates - ' + new Date().toLocaleDateString(),
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7] // Exclude checkbox column
                    }
                }
            ],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search certificates...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ certificates",
                "infoEmpty": "Showing 0 to 0 of 0 certificates",
                "infoFiltered": "(filtered from _MAX_ total certificates)",
                "emptyTable": "No certificates with NEW status found",
                "zeroRecords": "No matching certificates found"
            }
        });
        
        // Add event listener for page changes to update select all state
        dataTable.on('page.dt', function() {
            setTimeout(function() {
                updateSelectAllState();
            }, 100);
        });
        
        // Add event listener for search to update select all state
        dataTable.on('search.dt', function() {
            setTimeout(function() {
                updateSelectAllState();
            }, 100);
        });
        
        $('#certificates-section').show();
        updateSelectedCount();
    } else {
        $('#no-certificates-message').show();
    }
}

function updateSelectedCount() {
    var selectedCount = $('.certificate-checkbox:checked').length;
    $('#selected-count').text(selectedCount);
    
    if (selectedCount > 0) {
        $('#bulk-claim-selected').prop('disabled', false);
    } else {
        $('#bulk-claim-selected').prop('disabled', true);
    }
}

function updateSelectAllState() {
    var totalCheckboxes, checkedCheckboxes;
    
    if (dataTable) {
        // Count checkboxes in currently visible page
        totalCheckboxes = dataTable.rows({page: 'current'}).nodes().length;
        checkedCheckboxes = 0;
        dataTable.rows({page: 'current'}).every(function() {
            var checkbox = $(this.node()).find('.certificate-checkbox');
            if (checkbox.is(':checked')) {
                checkedCheckboxes++;
            }
        });
    } else {
        totalCheckboxes = $('.certificate-checkbox').length;
        checkedCheckboxes = $('.certificate-checkbox:checked').length;
    }
    
    var selectAllState = false;
    if (totalCheckboxes > 0 && checkedCheckboxes === totalCheckboxes) {
        selectAllState = true;
    }
    
    $('#select-all').prop('checked', selectAllState);
    $('#select-all-header').prop('checked', selectAllState);
}

function bulkClaimCertificates(certificateIds) {
    $('#bulk-claim-selected').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('AR/student/certificate/bulk/claimCertificates') }}",
        method: 'POST',
        data: {
            certificate_ids: certificateIds
        },
        error: function(err) {
            console.log("Error claiming certificates:", err);
            $('#bulk-claim-selected').prop('disabled', false).html('<i class="fa fa-check"></i> Claim Selected (<span id="selected-count">0</span>)');
            alert("Error claiming certificates");
        },
        success: function(data) {
            $('#bulk-claim-selected').html('<i class="fa fa-check"></i> Claim Selected (<span id="selected-count">0</span>)');
            
            if (data.success) {
                // Show success message
                var successHtml = '<div class="alert alert-success">' +
                                    '<h5>Bulk Claim Successful!</h5>' +
                                    '<p>' + data.message + '</p>' +
                                  '</div>';
                $('#bulk-result-content').html(successHtml);
                $('#bulk-result').show();
                
                // Show toast notification
                $.toast({
                    heading: 'Success',
                    text: data.message,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3500
                });
                
                // Refresh the certificates list
                loadNewCertificates();
                
                // Reset selection
                $('#select-all').prop('checked', false);
                $('#select-all-header').prop('checked', false);
                
            } else {
                // Show error message
                var errorHtml = '<div class="alert alert-danger">' +
                                  '<h5>Error!</h5>' +
                                  '<p>' + data.message + '</p>' +
                                '</div>';
                $('#bulk-result-content').html(errorHtml);
                $('#bulk-result').show();
                
                $.toast({
                    heading: 'Error',
                    text: data.message,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3500
                });
                
                $('#bulk-claim-selected').prop('disabled', false);
            }
        }
    });
}

</script>

@endsection 