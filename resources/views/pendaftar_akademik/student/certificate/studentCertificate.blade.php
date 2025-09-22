@extends('layouts.pendaftar_akademik')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Student Certificate</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Student Certificate</li>
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
                <h3 class="card-title">Generate Student Certificate</h3>
              </div>
              <!-- /.card-header -->
              <!-- Individual Certificate Generation -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Individual Certificate Generation</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label class="form-label" for="name">Name / No. IC / No. Matric</label>
                            <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="student">Student</label>
                              <select class="form-select" id="student" name="student">
                                <option value="-" selected disabled>-</option>
                              </select>
                            </div>
                        </div>
                    </div>
                </div>
              </div>

              <!-- Bulk Certificate Generation -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Bulk Certificate Generation</b>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="excelFile">File Import (Excel)</label>
                        <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls" />
                        <small class="text-muted">Upload Excel file with student IC numbers</small>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="certificateType">Certificate Type</label>
                        <select class="form-select" id="certificateType" name="certificateType">
                          <option value="NEW">New Certificate</option>
                          <option value="RECLAIMED">Reclaimed Certificate</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-info me-2" id="importBulkButton">
                      <i class="fa fa-upload"></i> Import & Generate Certificates
                    </button>
                    <button type="button" class="btn btn-success" onclick="exportBulkTemplate()">
                      <i class="fa fa-download"></i> Download Template
                    </button>
                  </div>
                </div>
              </div>
              <div id="form-student" style="display: none;">
                <div class="card mb-3">
                  <div class="card-header">
                    <b>Student Information</b>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Student Name</label>
                          <input type="text" class="form-control" id="student_name" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">IC Number</label>
                          <input type="text" class="form-control" id="student_ic" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Matric Number</label>
                          <input type="text" class="form-control" id="student_matric" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Program</label>
                          <input type="text" class="form-control" id="student_program" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-md-12">
                        <div class="form-group mb-3">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="manualSerialToggle">
                            <label class="form-check-label" for="manualSerialToggle">
                              Manual Serial Number Entry
                            </label>
                          </div>
                        </div>
                        
                        <div id="manual-serial-input" style="display: none;" class="mb-3">
                          <div class="form-group">
                            <label class="form-label" for="manualSerialNumber">Manual Serial Number</label>
                            <div class="input-group">
                              <span class="input-group-text" id="serial-prefix">CERT-<span id="current-year"></span>-</span>
                              <input type="text" class="form-control" id="manualSerialNumber" placeholder="0001" maxlength="20" pattern="[A-Za-z0-9\-]+" title="Only letters, numbers, and hyphens are allowed">
                            </div>
                            <small class="text-muted">Enter only the last part of the serial number (e.g., 0001, CUSTOM-001, etc.). This will not increment the automatic counter.</small>
                          </div>
                        </div>
                        
                        <button type="button" class="btn btn-primary me-2" id="generate-certificate">
                          <i class="fa fa-certificate"></i> Generate Certificate Number
                        </button>
                        <button type="button" class="btn btn-warning" id="generate-reclaimed" style="display: none;">
                          <i class="fa fa-refresh"></i> Generate Reclaimed Certificate
                        </button>
                        <div id="generate-warning" class="alert alert-warning mt-2" style="display: none;">
                          <i class="fa fa-exclamation-triangle"></i> This student already has a pending certificate with status "NEW". 
                          Please process the existing certificate before generating a new one.
                        </div>
                        <div id="reclaimed-info" class="alert alert-info mt-2" style="display: none;">
                          <i class="fa fa-info-circle"></i> This will generate a new certificate with status "RECLAIMED" for a lost certificate.
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="certificate-history" style="display: none;">
                <div class="card mb-3">
                  <div class="card-header">
                    <b>Certificate History</b>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="certificate-table">
                        <thead>
                          <tr>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Date Generated</th>
                            <th>Date Claimed</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="certificate-history-body">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div id="certificate-result" style="display: none;">
                <div class="card mb-3">
                  <div class="card-header">
                    <b>New Certificate Generated</b>
                  </div>
                  <div class="card-body">
                    <div class="alert alert-success">
                      <h5>Certificate Generated Successfully!</h5>
                      <p>Serial Number: <strong id="serial-number"></strong></p>
                      <p>Status: <strong id="certificate-status"></strong></p>
                      <p>Date Generated: <strong id="date-generated"></strong></p>
                    </div>
                  </div>
                </div>
              </div>
              <div id="bulk-certificate-result" style="display: none;">
                <div class="card mb-3">
                  <div class="card-header">
                    <b>Bulk Certificate Generation Results</b>
                  </div>
                  <div class="card-body">
                    <div id="bulk-summary" class="alert alert-info mb-3">
                      <!-- Summary will be populated by JavaScript -->
                    </div>
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="bulk-certificate-table">
                        <thead>
                          <tr>
                            <th>Student IC</th>
                            <th>Student Name</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Result</th>
                          </tr>
                        </thead>
                        <tbody id="bulk-certificate-body">
                        </tbody>
                      </table>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script type="text/javascript">

$('#search').keyup(function(event){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
        var searchTerm = $(this).val();
        getStudent(searchTerm);
    }
});

$('#student').on('change', function(){
    var selectedStudent = $(this).val();
    getStudInfo(selectedStudent);
});

// Handle manual serial number toggle
$('#manualSerialToggle').on('change', function(){
    if($(this).is(':checked')) {
        // Update the current year in the prefix
        $('#current-year').text(new Date().getFullYear());
        $('#manual-serial-input').show();
    } else {
        $('#manual-serial-input').hide();
        $('#manualSerialNumber').val('');
    }
});

$('#generate-certificate').on('click', function(){
    var studentIc = $('#student_ic').val();
    var isManual = $('#manualSerialToggle').is(':checked');
    var manualSerial = $('#manualSerialNumber').val();
    
    if(!studentIc) {
        alert('Please select a student first');
        return;
    }
    
    if(isManual && !manualSerial.trim()) {
        alert('Please enter a manual serial number or disable manual entry');
        return;
    }
    
    generateCertificate(studentIc, 'NEW', isManual, manualSerial);
});

$('#generate-reclaimed').on('click', function(){
    var studentIc = $('#student_ic').val();
    var isManual = $('#manualSerialToggle').is(':checked');
    var manualSerial = $('#manualSerialNumber').val();
    
    if(!studentIc) {
        alert('Please select a student first');
        return;
    }
    
    if(isManual && !manualSerial.trim()) {
        alert('Please enter a manual serial number or disable manual entry');
        return;
    }
    
    generateCertificate(studentIc, 'RECLAIMED', isManual, manualSerial);
});

function getStudent(search)
{
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/student/certificate/search') }}",
            method   : 'POST',
            data 	 : {search: search},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#student').empty();
                $('#student').append('<option value="-" selected disabled>-</option>');
                
                if(data.length > 0) {
                    $.each(data, function(index, student) {
                        $('#student').append('<option value="' + student.ic + '">' + student.name + ' (' + student.ic + ')</option>');
                    });
                } else {
                    $('#student').append('<option value="">No students found</option>');
                }
            }
        });
}

function getStudInfo(ic)
{
    if(ic === '-' || ic === '') {
        $('#form-student').hide();
        $('#certificate-result').hide();
        $('#certificate-history').hide();
        // Reset manual serial input
        $('#manualSerialToggle').prop('checked', false);
        $('#manual-serial-input').hide();
        $('#manualSerialNumber').val('');
        return;
    }

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/student/certificate/search') }}",
            method   : 'POST',
            data 	 : {search: ic},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                if(data.length > 0) {
                    var student = data[0];
                    $('#student_name').val(student.name);
                    $('#student_ic').val(student.ic);
                    $('#student_matric').val(student.no_matric);
                    $('#student_program').val(student.program || 'N/A');
                    $('#form-student').show();
                    $('#certificate-result').hide();
                    
                    // Load certificate history for this student
                    getCertificateHistory(student.ic);
                }
            }
        });
}

function generateCertificate(studentIc, certificateType, isManual, manualSerial)
{
    var requestData = {
        student_ic: studentIc,
        certificate_type: certificateType
    };
    
    if(isManual && manualSerial) {
        requestData.is_manual = true;
        requestData.manual_serial_number = manualSerial.trim();
    }
    
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/student/certificate/generate') }}",
            method   : 'POST',
            data 	 : requestData,
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                if(data.success) {
                    $('#serial-number').text(data.serial_no);
                    $('#certificate-status').text(data.status);
                    $('#date-generated').text(new Date().toLocaleDateString());
                    $('#certificate-result').show();
                    
                    // Clear manual serial input if it was used
                    if($('#manualSerialToggle').is(':checked')) {
                        $('#manualSerialNumber').val('');
                        $('#manualSerialToggle').prop('checked', false);
                        $('#manual-serial-input').hide();
                    }
                    
                    // Refresh certificate history
                    getCertificateHistory(studentIc);
                    
                    // Show success message
                    $.toast({
                        heading: 'Success',
                        text: data.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500
                    });
                } else {
                    // Show error message
                    $.toast({
                        heading: 'Error',
                        text: data.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500
                    });
                }
            }
        });
}

function getCertificateHistory(studentIc)
{
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/student/certificate/history') }}",
            method   : 'POST',
            data 	 : {student_ic: studentIc},
            error:function(err){
                console.log("Error getting certificate history:", err);
            },
            success  : function(data){
                displayCertificateHistory(data);
            }
        });
}

function displayCertificateHistory(certificates)
{
    var tbody = $('#certificate-history-body');
    tbody.empty();
    
    var hasNewStatus = false;
    var hasClaimedStatus = false;
    var hasReclaimedStatus = false;
    
    if(certificates.length > 0) {
        $.each(certificates, function(index, cert) {
            var statusBadge = getStatusBadge(cert.status);
            var dateGenerated = new Date(cert.date).toLocaleDateString();
            var dateClaimed = cert.date_claimed ? new Date(cert.date_claimed).toLocaleDateString() : '-';
            var actionButtons = getActionButtons(cert);
            
            // Check certificate statuses
            if(cert.status === 'NEW') {
                hasNewStatus = true;
            }
            if(cert.status === 'CLAIMED') {
                hasClaimedStatus = true;
            }
            if(cert.status === 'RECLAIMED') {
                hasReclaimedStatus = true;
            }
            
            var row = '<tr>' +
                        '<td>' + cert.serial_no + '</td>' +
                        '<td>' + statusBadge + '</td>' +
                        '<td>' + dateGenerated + '</td>' +
                        '<td>' + dateClaimed + '</td>' +
                        '<td>' + actionButtons + '</td>' +
                      '</tr>';
            tbody.append(row);
        });
        $('#certificate-history').show();
    } else {
        var row = '<tr>' +
                    '<td colspan="5" class="text-center text-muted">No certificate history found</td>' +
                  '</tr>';
        tbody.append(row);
        $('#certificate-history').show();
    }
    
    // Handle button visibility and states
    if(hasNewStatus) {
        // Has NEW certificate - disable normal generate, hide reclaimed button
        $('#generate-warning').show();
        $('#generate-certificate').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
        $('#generate-reclaimed').hide();
        $('#reclaimed-info').hide();
    } else if(hasClaimedStatus) {
        // Has CLAIMED certificate - disable normal generate, show reclaimed button
        $('#generate-warning').hide();
        $('#generate-certificate').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
        $('#generate-reclaimed').show();
        $('#reclaimed-info').show();
    } else if(hasReclaimedStatus) {
        // Has RECLAIMED certificate - disable normal generate, hide reclaimed button
        $('#generate-warning').hide();
        $('#generate-certificate').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
        $('#generate-reclaimed').hide();
        $('#reclaimed-info').hide();
    } else {
        // No certificates - enable normal generate button
        $('#generate-warning').hide();
        $('#generate-certificate').prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
        $('#generate-reclaimed').hide();
        $('#reclaimed-info').hide();
    }
}

function getStatusBadge(status)
{
    var badgeClass = '';
    switch(status) {
        case 'NEW':
            badgeClass = 'badge bg-primary';
            break;
        case 'CLAIMED':
            badgeClass = 'badge bg-success';
            break;
        case 'RECLAIMED':
            badgeClass = 'badge bg-warning';
            break;
        default:
            badgeClass = 'badge bg-secondary';
    }
    return '<span class="' + badgeClass + '">' + status + '</span>';
}

function getActionButtons(cert)
{
    var buttons = '';
    
    switch(cert.status) {
        case 'NEW':
            buttons = '<button class="btn btn-success btn-sm me-1" onclick="updateCertificateStatus(' + cert.id + ', \'CLAIMED\')" title="Mark as Claimed">' +
                        '<i class="fa fa-check"></i> Claim' +
                      '</button>';
            break;
        case 'CLAIMED':
            buttons = '<span class="text-success"><i class="fa fa-check-circle"></i> Certificate Claimed</span>';
            break;
        case 'RECLAIMED':
            buttons = '<span class="text-warning"><i class="fa fa-refresh"></i> Reclaimed Certificate</span>';
            break;
        default:
            buttons = '-';
    }
    
    return buttons;
}

function updateCertificateStatus(certificateId, newStatus)
{
    // Confirm action
    var confirmMessage = 'Are you sure you want to mark this certificate as ' + newStatus + '?';
    if (!confirm(confirmMessage)) {
        return;
    }

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/student/certificate/updateStatus') }}",
            method   : 'POST',
            data 	 : {
                certificate_id: certificateId,
                status: newStatus
            },
            error:function(err){
                console.log("Error updating certificate status:", err);
                alert("Error updating certificate status");
            },
            success  : function(data){
                if(data.success) {
                    // Show success message
                    $.toast({
                        heading: 'Success',
                        text: data.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500
                    });
                    
                    // Refresh certificate history
                    var studentIc = $('#student_ic').val();
                    getCertificateHistory(studentIc);
                } else {
                    // Show error message
                    $.toast({
                        heading: 'Error',
                        text: data.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500
                    });
                }
            }
        });
}

// Bulk Certificate Functions
function exportBulkTemplate() {
    // Create worksheet manually to better control formatting
    const ws = XLSX.utils.aoa_to_sheet([
        ['student_ic'],
        ['']
    ]);

    // Set column width for better visibility
    ws['!cols'] = [{ width: 20 }];

    // Format all cells in column A as text to preserve leading zeros
    const range = XLSX.utils.decode_range(ws['!ref']);
    for (let R = range.s.r; R <= range.e.r; ++R) {
        const cellAddress = XLSX.utils.encode_cell({ r: R, c: 0 });
        if (ws[cellAddress]) {
            ws[cellAddress].t = 's'; // Set cell type to string
            ws[cellAddress].z = '@'; // Set number format to text
        }
    }

    // Add instructions as comments or additional sheet
    const instructionText = 'Instructions:\n' +
                           '1. Replace example ICs with actual student IC numbers\n' +
                           '2. Ensure all ICs are 12 digits (pad with leading zeros if needed)\n' +
                           '3. Examples: 911017045043, 030401110460, 701031001121\n' +
                           '4. Save and upload the file';
    
    // Add instruction cell
    ws['B1'] = { v: instructionText, t: 's' };

    // Create a new workbook
    const wb = XLSX.utils.book_new();

    // Append the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Students');

    // Generate Excel file and trigger download
    XLSX.writeFile(wb, 'certificate_template.xlsx');
}

$('#importBulkButton').on('click', function() {
    var fileInput = $('#excelFile')[0];
    if (fileInput.files.length === 0) {
        alert('Please select a file.');
        return;
    }

    var certificateType = $('#certificateType').val();
    
    var formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('certificate_type', certificateType);

    // Show loading state
    $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

    $.ajax({
        url: "{{ url('AR/student/certificate/bulkGenerate') }}",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            displayBulkResults(response);
            
            // Show success message
            $.toast({
                heading: 'Bulk Generation Complete',
                text: response.message,
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'success',
                hideAfter: 3500
            });
        },
        error: function(xhr, status, error) {
            let errorMessage = xhr.status + ': ' + xhr.statusText;
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            // Show error message
            $.toast({
                heading: 'Error',
                text: 'Error - ' + errorMessage,
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'error',
                hideAfter: 3500
            });
        },
        complete: function() {
            // Reset button state
            $('#importBulkButton').prop('disabled', false).html('<i class="fa fa-upload"></i> Import & Generate Certificates');
        }
    });
});

function displayBulkResults(response) {
    var tbody = $('#bulk-certificate-body');
    tbody.empty();
    
    var successCount = 0;
    var errorCount = 0;
    
    if(response.results && response.results.length > 0) {
        $.each(response.results, function(index, result) {
            var statusBadge = '';
            var resultText = '';
            
            if(result.success) {
                successCount++;
                statusBadge = '<span class="badge bg-success">' + result.status + '</span>';
                resultText = '<span class="text-success"><i class="fa fa-check"></i> Generated</span>';
            } else {
                errorCount++;
                statusBadge = '<span class="badge bg-danger">Error</span>';
                resultText = '<span class="text-danger"><i class="fa fa-times"></i> ' + result.message + '</span>';
            }
            
            var row = '<tr>' +
                        '<td>' + result.student_ic + '</td>' +
                        '<td>' + (result.student_name || 'N/A') + '</td>' +
                        '<td>' + (result.serial_no || '-') + '</td>' +
                        '<td>' + statusBadge + '</td>' +
                        '<td>' + resultText + '</td>' +
                      '</tr>';
            tbody.append(row);
        });
        
        // Update summary
        var summaryHtml = '<h6>Bulk Generation Summary</h6>' +
                         '<p><strong>Total Processed:</strong> ' + response.results.length + '</p>' +
                         '<p><strong>Successfully Generated:</strong> <span class="text-success">' + successCount + '</span></p>' +
                         '<p><strong>Errors:</strong> <span class="text-danger">' + errorCount + '</span></p>';
        
        $('#bulk-summary').html(summaryHtml);
        $('#bulk-certificate-result').show();
        
        // Hide individual forms when showing bulk results
        $('#form-student').hide();
        $('#certificate-history').hide();
        $('#certificate-result').hide();
    }
}

</script>

@endsection 