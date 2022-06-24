@extends('layouts.lecturer.lecturer')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Manage Schedule</h4>
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
                <h3 class="card-title">Create Schedule</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="" method="POST">
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
                  </div>
                  <div class="col-md-12">
                    <div class="form-group" id="schedule">
                        <label class="form-label">Group Weekly Schedule *</label>

                        @php 
                            $classat = ["monday", 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        @endphp
                        <div class="container mt-1">
                        @foreach($classat as $day)
                            <div class="row mb-2">
                                <div class="col-md-3 align-self-center">
                                    <div class="align-middle">
                                        <input type="checkbox" id="basic_checkbox_{{$day}}"
                                            class="filled-in" checked="" name="classday" value="{{$day}}"
                                        >
                                        <label for="basic_checkbox_{{$day}}">{{ ucwords($day) }}</label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="bootstrap-timepicker">
                                                <div class="input-group">
                                                    <input id="{{ $day }}-start-classtime" value="" type="text" class="form-control datetimepicker" value="10:45 AM">
                                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 align-self-center "> <div class="d-flex justify-content-center align-middle">-</div></div>
                                        <div class="col-md-4">
                                            <div class="bootstrap-timepicker">
                                                <div class="input-group">
                                                    <input id="{{ $day }}-end-classtime" value="" type="text" class="form-control datetimepicker" value="10:45 AM">
                                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 align-self-center">
                                            <div class=" align-middle">
                                                <label id="{{ $day }}-duration" class="badge badge-success"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class=" pull-right">
                        <button id="savebtn" class="btn btn-primary"><i class="ti-trash"></i> Save</button>
                    </div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>

<script src="{{ asset('assets/assets/vendor_components/dropzone/dropzone.js') }}"></script>

<script type="text/javascript">
var selected_group = "";
$(function () {
            $('.datetimepicker').datetimepicker({
                format: "hh:mm a",
                defaultTime: '10:45 PM'
            });
        });

$(document).ready(function ()
{
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/class/schedule/getGroup') }}",
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

    await getschedule(selected_group);
})

function getschedule(group)
  {

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/class/schedule/getschedule') }}",
            method   : 'POST',
            data 	 : {group: group},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                
                //$('#lecturer-selection-div').removeAttr('hidden');
                //$('#lecturer').selectpicker('refresh');
                if(data.message == "Reload"){
                    $('.datetimepicker').datetimepicker({
                    format: "hh:mm a"
            });
  
                   
                }else{
                    $('#schedule').html(data);
                    $('.datetimepicker').datetimepicker({
                    format: "hh:mm a"
            });
                   
                }

            }
        });
  }

function msToTime(duration) {
    //ref https://www.codegrepper.com/code-examples/javascript/convert+milliseconds+to+hours+minutes+seconds+javascript
    var milliseconds = parseInt((duration % 1000) / 100),
    seconds = Math.floor((duration / 1000) % 60),
    minutes = Math.floor((duration / (1000 * 60)) % 60),
    hours = Math.floor((duration / (1000 * 60 * 60)) % 24);

    hours = (hours < 10) ?  hours : hours;
    minutes = (minutes < 10) ?  minutes : minutes;
    seconds = (seconds < 10) ?  seconds : seconds;

    return hours + "." + minutes;
}

$(async function () {
        
        "use strict";
        ClassicEditor
        .create( document.querySelector( '#classdescriptiontxt' ),{ height: '25em' } )
        .then(newEditor =>{editor = newEditor;})
        .catch( error => { console.log( error );});

        $(document).on('change', '.datetimepicker', function(e){
            $('[name="classday"]').each((i)=>{
                var checkbox = $('[name="classday"]')[i];
                var day = $(checkbox).val();
                var starttime = $("#"+day+"-start-classtime").val();
                var endtime = $("#"+day+"-end-classtime").val();
                
                var x = new Date("1/1/2013 " +starttime);
                var y = new Date("1/1/2013 " +endtime);
                var z =  y.getTime() - x.getTime();
                var duration = Number(msToTime(z).toString().replace("-","")).toFixed(2);

                $('#'+day+'-duration').html(duration + " hr");
            });
        });

    });

$('#savebtn').click(function (e) {
    var group = [];	
    
    e.preventDefault();    

    var formData = new FormData();
    formData.append('groupselect', $('#group option:selected').val());


    var classdays = $('[name="classday"]');
    var classschedules = [];

    classdays.each((i)=>{
        var checkbox = classdays[i];
        var day = $(checkbox).val();
        var starttime = $("#"+day+"-start-classtime").val();
        var endtime = $("#"+day+"-end-classtime").val();

        classschedules.push({
            day : day,
            starttime: starttime,
            endtime: endtime,
            status: $(checkbox).is(':checked')
        });
    });

    formData.append('groupschedules', JSON.stringify(classschedules));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: '{{ url('/lecturer/class/schedule/insertschedule') }}',
        type: 'POST',
        data: formData,
        cache : false,
        processData: false,
        contentType: false,
        error:function(err){
            console.log(err);
        },
        success:function(res){
            try{
                if(res.message == "Success"){
                    alert("Success! Group's shcedule has been updated/created!")
                }else{
                    $('.error-field').html('');
                    if(res.message == "Field Error"){
                        for (f in res.error) {
                            $('#'+f+'_error').html(res.error[f]);
                        }
                    }
                    else if(res.message == "Group code already existed inside the system"){
                        $('#classcode_error').html(res.message);
                    }
                    else{
                        alert(res.message);
                    }
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                }
            }catch(err){
                alert("Ops sorry, there is an error");
            }
        }
    });
});

</script>
@endsection
