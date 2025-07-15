@extends('layouts.pendaftar_akademik')

@section('main')
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

<script type="text/javascript">

$(document).ready(function() {
    loadNewCertificates();
});

$('#refresh-certificates').on('click', function() {
    loadNewCertificates();
});

$('#select-all, #select-all-header').on('change', function() {
    var isChecked = $(this).is(':checked');
    $('.certificate-checkbox').prop('checked', isChecked);
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
    var totalCheckboxes = $('.certificate-checkbox').length;
    var checkedCheckboxes = $('.certificate-checkbox:checked').length;
    
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