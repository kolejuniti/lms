@extends('layouts.pendaftar_akademik')

@section('main')

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
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
                    <!-- Export Buttons Row -->
                    <div class="mb-3" id="export-buttons-row" style="display: none;">
                      <button class="btn btn-info btn-sm" id="export-excel">
                        <i class="fa fa-file-excel-o"></i> Export Excel
                      </button>
                      <button class="btn btn-danger btn-sm" id="export-pdf">
                        <i class="fa fa-file-pdf-o"></i> Export PDF
                      </button>
                      <button class="btn btn-secondary btn-sm" id="export-print">
                        <i class="fa fa-print"></i> Print
                      </button>
                      <button class="btn btn-success btn-sm" id="export-csv">
                        <i class="fa fa-file-text-o"></i> Export CSV
                      </button>
                      <button class="btn btn-warning btn-sm" id="export-copy">
                        <i class="fa fa-copy"></i> Copy
                      </button>
                    </div>
                    
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

<!-- DataTables JavaScript Dependencies -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>

<!-- SheetJS (XLSX) for better Excel export -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script type="text/javascript">

var certificatesDataTable = null;

$(document).ready(function() {
    loadNewCertificates();
});

$('#refresh-certificates').on('click', function() {
    loadNewCertificates();
});

// Removed - replaced with DataTables-compatible version below

$(document).on('change', '.certificate-checkbox', function() {
    updateSelectedCount();
    updateSelectAllState();
});

$('#bulk-claim-selected').on('click', function() {
    var selectedIds = [];
    
    if (certificatesDataTable) {
        // Collect IDs from all pages
        certificatesDataTable.rows().every(function() {
            var row = this.node();
            var checkbox = $(row).find('.certificate-checkbox');
            if (checkbox.is(':checked')) {
                selectedIds.push(checkbox.val());
            }
        });
    } else {
        $('.certificate-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
    }
    
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
    var tbody = $('#certificates-table-body');
    tbody.empty();
    
    // Destroy existing DataTable if it exists
    if (certificatesDataTable) {
        certificatesDataTable.destroy();
        certificatesDataTable = null;
    }
    
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
        
        // Initialize DataTables
        certificatesDataTable = $('#certificates-table').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[1, 'asc']], // Sort by Serial Number
            columnDefs: [
                {
                    targets: 0, // Checkbox column
                    orderable: false,
                    searchable: false,
                    width: "50px"
                }
            ],
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: ':not(:first-child)' // Exclude checkbox column
                    },
                    title: 'Bulk Student Certificates - NEW Status',
                    filename: 'bulk_certificates_new_status'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: ':not(:first-child)' // Exclude checkbox column
                    },
                    title: 'Bulk Student Certificates - NEW Status',
                    filename: 'bulk_certificates_new_status',
                    orientation: 'landscape'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'btn btn-secondary btn-sm',
                    exportOptions: {
                        columns: ':not(:first-child)' // Exclude checkbox column
                    },
                    title: 'Bulk Student Certificates - NEW Status'
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-text-o"></i> CSV',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':not(:first-child)' // Exclude checkbox column
                    },
                    title: 'Bulk Student Certificates - NEW Status',
                    filename: 'bulk_certificates_new_status'
                },
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i> Copy',
                    className: 'btn btn-warning btn-sm',
                    exportOptions: {
                        columns: ':not(:first-child)' // Exclude checkbox column
                    },
                    title: 'Bulk Student Certificates - NEW Status'
                }
            ],
            language: {
                search: "Search certificates:",
                lengthMenu: "Show _MENU_ certificates per page",
                info: "Showing _START_ to _END_ of _TOTAL_ certificates",
                infoEmpty: "Showing 0 to 0 of 0 certificates",
                infoFiltered: "(filtered from _MAX_ total certificates)",
                zeroRecords: "No certificates found",
                emptyTable: "No certificates available"
            }
        });
        
        $('#certificates-section').show();
        $('#export-buttons-row').show();
        updateSelectedCount();
    } else {
        $('#no-certificates-message').show();
        $('#export-buttons-row').hide();
    }
}

function updateSelectedCount() {
    var selectedCount = 0;
    
    if (certificatesDataTable) {
        // Count checked checkboxes across all pages
        certificatesDataTable.rows().every(function() {
            var row = this.node();
            if ($(row).find('.certificate-checkbox').is(':checked')) {
                selectedCount++;
            }
        });
    } else {
        selectedCount = $('.certificate-checkbox:checked').length;
    }
    
    $('#selected-count').text(selectedCount);
    
    if (selectedCount > 0) {
        $('#bulk-claim-selected').prop('disabled', false);
    } else {
        $('#bulk-claim-selected').prop('disabled', true);
    }
}

function updateSelectAllState() {
    var totalCheckboxes = 0;
    var checkedCheckboxes = 0;
    
    if (certificatesDataTable) {
        // Count checkboxes across all pages
        certificatesDataTable.rows().every(function() {
            var row = this.node();
            totalCheckboxes++;
            if ($(row).find('.certificate-checkbox').is(':checked')) {
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

// Export button event handlers
$(document).on('click', '#export-excel', function() {
    if (certificatesDataTable) {
        certificatesDataTable.button('.buttons-excel').trigger();
    }
});

$(document).on('click', '#export-pdf', function() {
    if (certificatesDataTable) {
        certificatesDataTable.button('.buttons-pdf').trigger();
    }
});

$(document).on('click', '#export-print', function() {
    if (certificatesDataTable) {
        certificatesDataTable.button('.buttons-print').trigger();
    }
});

$(document).on('click', '#export-csv', function() {
    if (certificatesDataTable) {
        certificatesDataTable.button('.buttons-csv').trigger();
    }
});

$(document).on('click', '#export-copy', function() {
    if (certificatesDataTable) {
        certificatesDataTable.button('.buttons-copy').trigger();
    }
});

// Handle select all functionality with DataTables
$(document).on('change', '#select-all, #select-all-header', function() {
    var isChecked = $(this).is(':checked');
    
    if (certificatesDataTable) {
        // Select all rows across all pages
        certificatesDataTable.rows().every(function() {
            var row = this.node();
            $(row).find('.certificate-checkbox').prop('checked', isChecked);
        });
        
        // Redraw to update visible checkboxes
        certificatesDataTable.draw('page');
    } else {
        $('.certificate-checkbox').prop('checked', isChecked);
    }
    
    $('#select-all').prop('checked', isChecked);
    $('#select-all-header').prop('checked', isChecked);
    updateSelectedCount();
});

// Update select all state when changing pages or searching
$(document).on('draw.dt', '#certificates-table', function() {
    updateSelectAllState();
});

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

<!-- Custom CSS for DataTables Integration -->
<style>
/* Hide DataTables default buttons since we're using custom ones */
.dt-buttons {
    display: none !important;
}

/* Style the export buttons */
#export-buttons-row .btn {
    margin-right: 5px;
    margin-bottom: 5px;
}

/* DataTables responsive styling */
table.dataTable thead .sorting,
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting_desc {
    background-image: none;
}

table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after {
    opacity: 0.5;
}

/* Ensure consistent table styling */
#certificates-table.dataTable {
    border-collapse: separate !important;
}

/* Style the search box */
.dataTables_filter {
    margin-bottom: 10px;
}

.dataTables_filter input {
    margin-left: 5px;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Style the pagination */
.dataTables_paginate .paginate_button {
    padding: 0.375rem 0.75rem;
    margin-left: -1px;
    color: #495057;
    background-color: #fff;
    border: 1px solid #dee2e6;
}

.dataTables_paginate .paginate_button:hover {
    color: #495057;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.dataTables_paginate .paginate_button.current {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #export-buttons-row .btn {
        margin-bottom: 10px;
        width: 100%;
    }
    
    .dataTables_length,
    .dataTables_filter {
        text-align: center;
        margin-bottom: 15px;
    }
}

/* Info text styling */
.dataTables_info {
    margin-top: 10px;
    color: #6c757d;
}
</style>

@endsection 