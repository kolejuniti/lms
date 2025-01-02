@extends('layouts.pendaftar_akademik')

@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Student Assessment Edit</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Student Assessment Edit</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      @if($errors->any())
        <div class="form-group">
            <div class="alert alert-success">
              <span>{{$errors->first()}} </span>
            </div>
        </div>
      @endif
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- /.card-header -->
      <div class="card card-primary">
        <div class="card-header">
          <b>Search Student</b>
          <button id="printButton" class="waves-effect waves-light btn btn-primary btn-sm">
            <i class="ti-printer"></i>&nbsp Print
          </button>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 mr-3">
                <div class="form-group">
                  <label class="form-label" for="lect">Lecturer</label>
                  <select class="form-select" id="lect" name="lect">
                    <option value="-" selected>-</option>
                    @foreach($data['lecturer'] as $lct)
                    <option value="{{ $lct->ic }}">{{ $lct->name }}</option>
                    @endforeach
                  </select>
                </div>
            </div>
            <div class="col-md-4 mr-3">
                <div class="form-group">
                  <label class="form-label" for="subject">Subject</label>
                  <select class="form-select" id="subject" name="subject">
                    <option value="-" selected>-</option>
                  </select>
                </div>
            </div>
            <div class="col-md-4 mr-3">
                <div class="form-group">
                  <label class="form-label" for="group">Group</label>
                  <select class="form-select" id="group" name="group">
                    <option value="-" selected>-</option>
                  </select>
                </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-4 mr-3">
              <div class="form-group">
                <label class="form-label" for="ass">Assessment</label>
                <select class="form-select" id="ass" name="ass">
                  <option value="-" selected disabled>-</option>
                  <option value="quiz" >Quiz</option>
                  <option value="test" >Test</option>
                  <option value="assignment" >Assignment</option>
                  <option value="midterm" >Midterm</option>
                  <option value="other" >Other</option>
                  <option value="extra" >Extra</option>
                  <option value="final" >Final</option>
                </select>
              </div>
          </div>
          </div>
          <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Find</button>
          <div id="form-student">
            
  
          </div>
        </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Page specific script -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script>
     $(document).ready( function () {
        $('#myTable').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );
  </script>

  <script>
    $('#lect').change(function() {
        var lecturerId = $(this).val();
        if (lecturerId) {
            $.ajax({
                url: '/AR/student/studentAssessment/getSubjectLecturer',
                type: "GET",  // You could use POST here if you prefer
                dataType: "json",
                data: { lecturerId: lecturerId },  // Sending the lecturerId as data
                success: function(data) {
                    // alert(data);
                    $('#subject').empty();
                    $('#group').empty();
                    $('#subject').append('<option value="-" selected disabled>Select Subject</option>');
                    $.each(data, function(key, value) {
                        $('#subject').append('<option value="' + value.id + '">'+ value.code + ' - ' + value.name + ' (' + value.session + ')' + '</option>');  // Make sure 'id' and 'name' are correct based on your data structure
                    });
                }
            });
        } else {
            $('#subject').empty();
            $('#group').empty();
            $('#subject').append('<option value="-" selected disabled>Select Subject</option>');
        }
    });

    $('#subject').change(function() {
        var groupID = $(this).val();
        if (groupID) {
            $.ajax({
                url: '/AR/student/studentAssessment/getGroupLecturer',
                type: "GET",  // You could use POST here if you prefer
                dataType: "json",
                data: { groupID: groupID },  // Sending the lecturerId as data
                success: function(data) {
                    $('#group').empty();
                    $('#group').append('<option value="-" selected disabled>Select Group</option>');
                    $.each(data, function(key, value) {
                        $('#group').append('<option value="' + value.group_name + '">' + value.group_name + '</option>');  // Make sure 'id' and 'name' are correct based on your data structure
                    });
                }
            });
        } else {
            $('#group').empty();
            $('#group').append('<option value="-" selected disabled>Select Group</option>');
        }
    });
  </script>

  <script type="text/javascript">
    var lecturer = '';
    var subject = '';
    var group = '';
    var assessment = '';

  function submit()
  {

    var lecturer = $('#lect').val();
    var subject = $('#subject').val();
    var group = $('#group').val();
    var assessment = $('#ass').val();

    // Show the spinner
    $('#loading-spinner').css('display', 'block');

    return $.ajax({
              headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
              url      : "{{ url('AR/student/studentAssessment/getStudentAssessment') }}",
              method   : 'GET',
              data 	 : {lecturer: lecturer, subject: subject, group: group, assessment: assessment},
              error:function(err){
                  alert("Error");
                  console.log("Error details:", err);

                  // // If you want to log specific details from the error object
                  // if (err.responseJSON) {
                  //     console.log("Response JSON:", err.responseJSON);
                  // }
                  // if (err.status) {
                  //     console.log("Status code:", err.status);
                  // }
                  // if (err.statusText) {
                  //     console.log("Status text:", err.statusText);
                  // }
                  // if (err.responseText) {
                  //     console.log("Response text:", err.responseText);
                  // }

                  // Hide the spinner on error
                  $('#loading-spinner').css('display', 'none');
              },
              success  : function(data){

                if(data.error)
                {
                  alert(data.error);

                }

                // Hide the spinner on success
                $('#loading-spinner').css('display', 'none');

                $('#form-student').html(data);
                        
              }
          });
  }
  
  $(document).ready(function() {
    $('#printButton').on('click', function(e) {
      e.preventDefault();
      printReport();
    });
  });

  function printReport() {
    var from = $('#from').val();
    var to = $('#to').val();

    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('finance/report/dailyreport/getDailyReport?print=true') }}",
      method: 'GET',
      data: { from: from, to: to },
      error: function(err) {
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        var newWindow = window.open();
        newWindow.document.write(data);
        newWindow.document.close();
      }
    });
  }
  </script>
@endsection
