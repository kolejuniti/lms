@extends('layouts.lecturer.lecturer')

@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    @if(session()->has('message'))
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <div class="form-group">
            <div class="alert alert-success">
                <span>{{ session()->get('message') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
  <!-- Content Header (Page header) -->	  
  <div class="page-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Attendance Record</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Extra</li>
              <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
                <h3 class="card-title">Check Attendance</h3>
                @if (session('error'))
                    <script>
                      alert("{{ session('error') }}");
                    </script>
                @endif
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{ route('lecturer.attendance.store') }}" method="POST">
                @csrf
                <div class="card-body">
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="group">Group</label>
                        <select class="form-select" id="group" name="group" required>
                            <option value="" disabled>-</option>
                           
                        </select>
                      </div>
                    </div>   
                    <div class="col-md-6" id ="date-group">
                        <div class="bootstrap-datepicker">
                        <div class="form-group">
                          <label class="form-label" for="date">Class Start</label>
                          <div class="input-group">
                          <input id="date" name="date" value="" type="datetime-local" class="form-control" required>
                          <!--<input id="schedule" name="schedule" value="" type="text" class="form-control" hidden>-->
                          </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="program">Program</label>
                        <select class="form-select" id="program" name="program" style="height: 200px" multiple required>
                            <option value="" disabled>-</option>
                           
                        </select>
                      </div>
                    </div>  
                    <div class="col-md-6" id ="date2-group">
                      <div class="bootstrap-datepicker">
                      <div class="form-group">
                        <label class="form-label" for="date2">Class End</label>
                        <div class="input-group">
                        <input id="date2" name="date2" value="" type="datetime-local" class="form-control" required>
                        <!--<input id="schedule" name="schedule" value="" type="text" class="form-control" hidden>-->
                        </div>
                      </div>
                      </div>
                    </div>
                    <div class="col-md-9 mt-3" id="payment-card">
                      <div class="form-group">
                          <label class="form-label" for="class">Class Type</label>
                          <fieldset>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="class" id="class1" value="1" required>
                                  <label for="class1">
                                      Class
                                  </label>
                                  <input class="form-check-input" type="radio" name="class" id="class2" value="2" required>
                                  <label for="class2">
                                      Replacement Class
                                  </label>
                              </div>
                          </fieldset>
                      </div>
                    </div>
                    <!--<input type="time" min="00:00:00" max="01:30:00">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Group Duration Date *</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="reservation">
                            </div>
                        </div>
                    </div>--> 
                  </div>
                  <div class="card-header" id="export" hidden>
                    <b>Search Student</b>
                    <button id="printAttendanceButton" class="waves-effect waves-light btn btn-primary btn-sm me-2">
                      <i class="ti-printer"></i>&nbsp Export Class Attendance
                    </button>
                    <button id="printExaminationButton" class="waves-effect waves-light btn btn-success btn-sm">
                      <i class="ti-printer"></i>&nbsp Export Examination
                    </button>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group" >
                        <label class="form-label">Student List</label>
                        <div class="container mt-1" id="attendance">
                        </div>
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary pull-right mb-3">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

@if(session('alert'))
    <script>
        alert("{{ session('alert') }}")
    </script>
@endif


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

<script src="{{ asset('assets/assets/vendor_components/dropzone/dropzone.js') }}"></script>

<script type="text/javascript">


$(document).ready(function(){

    var selected_group = "";
    var selected_program = "";
    var input_date = "";
    
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/class/attendance/getGroup') }}",
            method   : 'GET',
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#group').html(data);
                $('#group').selectpicker('refresh');
            }
        });

})


$(document).on('change', '#group', async function(e){
    selected_group = $(e.target).val();

    await getStudents(selected_group);
    await getProgram(selected_group);

    // Get a reference to the button element
    const button = document.getElementById("export");

    // Disable the button
    button.hidden = false;
})

$(document).on('change', '#program', async function(e){
    selected_program = $(e.target).val();

    await getStudents(selected_group,selected_program);
})

function getProgram(group)
{
    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/attendance/getStudentProgram') }}",
        method   : 'POST',
        data 	 : {group: group},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
          $('#program').html(data);
          // $('#program').selectpicker('refresh');
                
        }
    });
}


function getStudents(group,program)
{
    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/attendance/getStudents') }}",
        method   : 'POST',
        data 	 : {group: group, program: program},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
  
            $('#attendance').removeAttr('hidden');
                $('#attendance').html(data);
                $('#table_registerstudent').DataTable();

        }
    });
}

function CheckAll(elem) {
   $('[name="student[]"]').prop("checked", $(elem).prop('checked'));
 }

 function AbsentAll(elem) {
  $('[name="student[]"]').each(function() {
			this.checked = false;
		});
 }

 function getExcuse(data) {
    var value = data;

    if($('#excuse_'+value).val() == '')
    {

      $('#student_checkbox_'+value).prop("disabled", false);

      $('#ic_'+value).prop("disabled", true);

      $('#mc_'+value).prop("disabled", false);

    }else{

      $('#student_checkbox_'+value).prop("disabled", true);

      $('#ic_'+value).prop("disabled", false);

      $('#mc_'+value).prop("disabled", true);

    }
 }

 function getMC(data) {
    var value = data;

    if($('#mc_'+value).prop("checked"))
    {

      $('#student_checkbox_'+value).prop("disabled", true);

      $('#ic_'+value).attr("disabled", true);

      $('#excuse_'+value).attr("disabled", true);

    }else{

      $('#student_checkbox_'+value).prop("disabled", false);

      $('#ic_'+value).prop("disabled", false);

      $('#excuse_'+value).attr("disabled", false);

    }
 }

 $(document).ready(function() {
    $('#printAttendanceButton').on('click', function(e) {
      e.preventDefault();
      printAttendanceReport();
    });

    $('#printExaminationButton').on('click', function(e) {
      e.preventDefault();
      printExaminationReport();
    });
  });


  function printAttendanceReport() {
    var group = $('#group').val();
    var program = $('#program').val();

    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('lecturer/class/attendance/print') }}",
      method: 'GET',
      data: { group: group, program: program },
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

  function printExaminationReport() {
    var group = $('#group').val();
    var program = $('#program').val();

    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('lecturer/class/examination/print') }}",
      method: 'GET',
      data: { group: group, program: program },
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
