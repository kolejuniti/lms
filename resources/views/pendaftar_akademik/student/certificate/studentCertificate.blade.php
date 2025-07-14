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
              <div class="card mb-3">
                <div class="card-header">
                  <b>Search Student</b>
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

$('#generate-certificate').on('click', function(){
    var studentIc = $('#student_ic').val();
    if(studentIc) {
        generateCertificate(studentIc, 'NEW');
    } else {
        alert('Please select a student first');
    }
});

$('#generate-reclaimed').on('click', function(){
    var studentIc = $('#student_ic').val();
    if(studentIc) {
        generateCertificate(studentIc, 'RECLAIMED');
    } else {
        alert('Please select a student first');
    }
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

function generateCertificate(studentIc, certificateType)
{
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/student/certificate/generate') }}",
            method   : 'POST',
            data 	 : {
                student_ic: studentIc,
                certificate_type: certificateType
            },
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

</script>

@endsection 