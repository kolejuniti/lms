@extends('layouts.lecturer.lecturer')

@section('content')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
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
                    <div class="col-md-6" id = "date-group">
                        <div class="bootstrap-datepicker">
                        <div class="form-group">
                          <label class="form-label" for="date">Date</label>
                          <div class="input-group">
                          <input id="date" name="date" value="" type="text" class="form-control datepicker" >
                          <input id="schedule" name="schedule" value="" type="text" class="form-control" hidden>
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                          </div>
                        </div>
                        </div>
                    </div> 
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
                    </div> 
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

<script src="{{ asset('assets/assets/vendor_components/dropzone/dropzone.js') }}"></script>

<script type="text/javascript">


$(document).ready(function(){

    var selected_group = "";
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

$(function(){
    $('.datepicker').datepicker();
});

$(document).on('change', '#group', async function(e){
    selected_group = $(e.target).val();

    await getStudents(selected_group);
    await getSchedule(selected_group, input_date);
})


function getStudents(group)
{
    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/attendance/getStudents') }}",
        method   : 'POST',
        data 	 : {group: group},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
  
            $('#attendance').removeAttr('hidden');
                $('#attendance').html(data);
                $('#attendance').selectpicker('refresh');
        }
    });
}

$(document).on('change', '#date', async function(e){
    input_date = $(e.target).val();

    await getSchedule(selected_group, input_date);
})

function getSchedule(group,date)
{

    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/attendance/getDate') }}",
        method   : 'POST',
        data 	 : {group: group,date: date},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
            if(data.message == "Fail")
            {
                alert('The date '+data.day+' is not checked in shcedule! Please configure or pick another date.')
                document.querySelector('#date').value = '';
            }else
            {
                document.querySelector('#schedule').value = data.dayid;
            }
        }
    });

}


</script>
@endsection
