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
              <form action="{{ route('lecturer.attendance.update') }}" method="POST">
                @csrf
                <div class="card-body">
                  <div class="row mb-4">  
                    <input id="group" name="group" type="text" class="form-control" value="{{ $data['group'] }}|{{ $data['name'] }}" hidden>
                    <input id="oldfrom" name="oldfrom" type="datetime-local" class="form-control" value="{{ $data['from'] }}" hidden>
                    <input id="oldto" name="oldto" type="datetime-local" class="form-control" value="{{ $data['to'] }}" hidden>
                    <div class="col-md-6" id ="date-group">
                        <div class="bootstrap-datepicker">
                        <div class="form-group">
                          <label class="form-label" for="date">Class Start</label>
                          <div class="input-group">
                          <input id="date" name="date" type="datetime-local" class="form-control" value="{{ $data['from'] }}">
                          <!--<input id="schedule" name="schedule" value="" type="text" class="form-control" hidden>-->
                          </div>
                        </div>
                        </div>
                    </div>  
                    <div class="col-md-6" id ="date2-group">
                      <div class="bootstrap-datepicker">
                      <div class="form-group">
                        <label class="form-label" for="date2">Class End</label>
                        <div class="input-group">
                        <input id="date2" name="date2" type="datetime-local" class="form-control" value="{{ $data['to'] }}">
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
                            @foreach($data['program'] as $prg)
                            <option value="{{ $prg->id }}">{{ $prg->progcode }} - {{ $prg->progname }}</option> 
                            @endforeach
                        </select>
                      </div>
                    </div> 
                    <div class="col-md-9 mt-3" id="payment-card">
                      <div class="form-group">
                          <label class="form-label" for="class">Class Type</label>
                          <fieldset>
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" name="class" id="class1" value="1" {{ ($data['type'] == 1) ? 'checked' : ''}}>
                                  <label for="class1">
                                      Class
                                  </label>
                                  <input class="form-check-input" type="radio" name="class" id="class2" value="2" {{ ($data['type'] == 2) ? 'checked' : ''}}>
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
                  <div class="col-md-12">
                    <div class="form-group" >
                        <label class="form-label">Student List</label>
                        <div class="container mt-1" id="attendance">
                          <div class="table-responsive" style="width:99.7%">
                            <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                              <thead class="thead-themed">
                                <th>Name</th>
                                <th>Matric No</th>
                                <th>Session</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th></th>
                                <th>Excuse</th>
                                <th>MC</th>
                                <th>NC/LC</th>
                              </thead>
                              <tbody>
                                <tr>
                                  <td >
                                      <label class="text-dark"><strong>SELECT ALL</strong></label><br>
                                  </td>
                                  <td >
                                      <label></label>
                                  </td>
                                  <td >
                                      <p class="text-bold text-fade"></p>
                                  </td>
                                  <td >
                                      <p class="text-bold text-fade"></p>
                                  </td>
                                  <td >
                                      <div class="pull-right" >
                                          <input type="checkbox" id="checkboxAll"
                                              class="filled-in" name="checkall"
                                              onclick="CheckAll(this)"
                                          >
                                          <label for="checkboxAll"> </label>
                                      </div>
                                  </td>
                                  <td >
                                      <p class="text-bold text-fade"></p>
                                  </td>
                                  <td >
                                      <p class="text-bold text-fade"></p>
                                  </td>
                                </tr>
                                @foreach($data['student'] as $key => $student)
                                <tr>
                                  <td >
                                      <label class="text-dark"><strong>{{ $student->name }}</strong></label><br>
                                      <label>IC: {{ $student->student_ic }}</label>
                                  </td>
                                  <td >
                                      <label>{{ $student->no_matric }}</label>
                                  </td>
                                  <td >
                                      <p class="text-bold text-fade">{{ $student->SessionName }}</p>
                                  </td>
                                  <td >
                                    <p class="text-bold text-fade">{{ $student->progcode }}</p>
                                  </td>
                                  <td >
                                      <p class="text-bold text-fade">{{ $student->status }}</p>
                                  </td>
                                  @if((!empty($data['attendance'][$key]) ? $data['attendance'][$key]->lc == null : true))
                                  <td >
                                      <div class="pull-right" >
                                          <input type="checkbox" id="student_checkbox_{{ $student->no_matric }}"
                                              class="filled-in" name="student[]" value="{{ $student->student_ic }}" {{ (!empty($data['attendance'][$key]) && $data['attendance'][$key]->excuse == null && $data['attendance'][$key]->mc == null) ? 'checked' : ''}}
                                          >
                                          <label for="student_checkbox_{{ $student->no_matric }}"></label>
                                      </div>
                                  </td>
                                  <td >
                                      <div>
                                          <input type="text" id="excuse_{{ $student->no_matric }}"
                                              class="form-control" name="excuse[]" onkeyup="getExcuse({{ $student->no_matric }})" value="{{ !empty($data['attendance'][$key]->excuse) ? $data['attendance'][$key]->excuse : '' }}">
                                          <input type="hidden" id="ic_{{ $student->no_matric }}"
                                          class="form-control" name="ic[]" value="{{ $student->student_ic }}" disabled>
                                          <label for="checkboxAll"> </label>
                                      </div>
                                  </td>
                                  <td >
                                      <div class="pull-right" >
                                          <input type="checkbox" id="mc_{{ $student->no_matric }}"
                                              class="filled-in" name="mc[]" value="{{ $student->student_ic }}" onclick="getMC({{ $student->no_matric }})"
                                              {{ (!empty($data['attendance'][$key]) && $data['attendance'][$key]->excuse == null && $data['attendance'][$key]->mc != null) ? 'checked' : ''}} >
                                          <label for="mc_{{ $student->no_matric }}"></label>
                                      </div>
                                  </td>
                                  @else
                                  <td>
                                      <div class="pull-right" >
                                          <input type="checkbox" id="student_checkbox_{{ $student->no_matric }}"
                                              class="filled-in" name="student[]" value="{{ $student->student_ic }}" disabled
                                          >
                                          <label for="student_checkbox_{{ $student->no_matric }}"></label>
                                      </div>
                                  </td>
                                  <td >
                                      <div>
                                          <input type="text" id="excuse_{{ $student->no_matric }}"
                                              class="form-control" name="excuse[]" onkeyup="getExcuse({{ $student->no_matric }})" value="{{ !empty($data['attendance'][$key]->excuse) ? $data['attendance'][$key]->excuse : '' }}" disabled>
                                          <input type="hidden" id="ic_{{ $student->no_matric }}"
                                          class="form-control" name="ic[]" value="{{ $student->student_ic }}" disabled>
                                          <label for="checkboxAll"> </label>
                                      </div>
                                  </td>
                                  <td>
                                      <div class="pull-right" >
                                          <input type="checkbox" id="mc_{{ $student->no_matric }}"
                                              class="filled-in" name="mc[]" value="{{ $student->student_ic }}" onclick="getMC({{ $student->no_matric }})" disabled>
                                          <label for="mc_{{ $student->no_matric }}"></label>
                                      </div>
                                  </td>
                                  <td>
                                    <div class="pull-right" >
                                        <input type="checkbox" id="lc_{{ $student->no_matric }}"
                                            class="filled-in" name="lc[]" value="{{ $student->student_ic }}"  onclick="event.preventDefault();" checked>
                                        <label for="lc_{{ $student->no_matric }}"></label>
                                    </div>
                                </td>
                                  @endif
                                </tr>
                                @endforeach
                                <tr>
                                  <td >
                                      <label class="text-dark"><strong>ALL ABSENT</strong></label><br>
                                  </td>
                                  <td >
                                      <label></label>
                                  </td>
                                  <td >
                                      <p class="text-bold text-fade"></p>
                                  </td>
                                  <td >
                                      <p class="text-bold text-fade"></p>
                                  </td>
                                  <td >
                                      <div class="pull-right" >
                                          <input type="checkbox" id="AbsentAlls"
                                              class="filled-in" name="absentall"
                                              onclick="AbsentAll(this)"
                                          >
                                          <label for="AbsentAlls"> </label>
                                      </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
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

$(document).ready(function() {
    // Loop through each input with the name 'excuse[]'
    $('input[name="excuse[]"]').each(function() {
        // Extract the matric number from the input's ID
        var matric = $(this).attr('id').replace('excuse_', '');
        
        // If the input has a value
        if ($(this).val() !== '') {
            $('#student_checkbox_' + matric).prop("disabled", true);
            $('#ic_' + matric).prop("disabled", false);
            $('#mc_' + matric).prop("disabled", true);
        }
        // Else part is optional since it seems like the default state
        else {
            $('#student_checkbox_' + matric).prop("disabled", false);
            $('#ic_' + matric).prop("disabled", true);
            $('#mc_' + matric).prop("disabled", false);
        }
    });

    $('input[name="mc[]"]').each(function(){
      
        var matric = $(this).attr('id').replace('mc_', '');

        if($('#mc_'+matric).prop("checked"))
        {

          $('#student_checkbox_'+matric).prop("disabled", true);
          $('#ic_'+matric).attr("disabled", true);
          $('#excuse_'+matric).attr("disabled", true);

        }else{

          if($('#excuse_'+matric).val() == null)
          {
            $('#student_checkbox_'+matric).prop("disabled", false);
            $('#ic_'+matric).prop("disabled", false);
            $('#excuse_'+matric).attr("disabled", false);
          }
            

      }

    });

    $('input[name="lc[]"]').each(function(){
      
      var matric = $(this).attr('id').replace('lc_', '');

      if($('#lc_'+matric).prop("checked"))
      {

        $('#student_checkbox_'+matric).prop("disabled", true);
        $('#ic_'+matric).attr("disabled", true);
        $('#excuse_'+matric).attr("disabled", true);
        $('#mc_' + matric).prop("disabled", true);

      }
      
  });
});


$(document).on('change', '#program', async function(e){
    selected_group = $('#group').val();
    selected_program = $(e.target).val();

    await getStudents(selected_group,selected_program);
})

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
                $('#attendance').selectpicker('refresh');

                
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

//$(document).on('change', '#date', async function(e){
   // input_date = $(e.target).val();

  //  await getSchedule(selected_group, input_date);
//})

//function getSchedule(group,date)
//{

    //return $.ajax({
        //headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        //url      : "{{ url('lecturer/class/attendance/getDate') }}",
        //method   : 'POST',
        //data 	 : {group: group,date: date},
        //error:function(err){
           // alert("Error");
            //console.log(err);
        //},
        //success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
           // if(data.message == "Fail")
            //{
              //  alert('The date '+data.day+' is not checked in shcedule! Please configure or pick another date.')
                //document.querySelector('#date').value = '';
            //}else
            //{
              //  document.querySelector('#schedule').value = data.dayid;
            //}
        //}
    //});

//}


</script>
@endsection
