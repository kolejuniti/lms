
@extends('layouts.student.student')

@section('main')


<style>


#fb-rendered-form {
    clear:both;
    display:none;
    button{
        float:right;
    }
}

.btn.btn-default.get-data{
    /* display:none; */
}

.cb-wrap {

}
.form-wrap.form-builder .frmb-control li{
    font-family: Arial, Helvetica, sans-serif !important;
    font-weight: Bold !important;
}

div.form-actions.btn-group > button{
    font-size:1.2em !important;
    border-radius:0.5em !important;
    padding:0.5em !important;
    min-width:100px;
    margin: 0.5em;
}

input.collected-marks + label{
    float:right;
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->	  
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">{{ $data['midtermtitle'] }}</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Academics</li>
                                <li class="breadcrumb-item" aria-current="page">Groups</li>
                                <li class="breadcrumb-item" aria-current="page">Group List</li>
                                <li class="breadcrumb-item" aria-current="page">Group Content</li>
                                <li class="breadcrumb-item" aria-current="page">Midterm</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $data['midtermtitle'] }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6 p-4">
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Participant Name</b></div>
                        <div class="col-md-9">{{ empty($data['fullname']) ? "N/A" : $data['fullname'] }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Midterm Title</b></div>
                        <div class="col-md-9">{{ $data['midtermtitle'] }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Duration</b></div>
                        <div class="col-md-9">{{ sprintf("%0d hour %02d minute",   floor($data['midtermduration']/60), $data['midtermduration']%60) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Created At</b></div>
                        <div class="col-md-9">{{ date("d-M-Y (h:i:a l)", strtotime($data['created_at'])) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Last Updated</b></div>
                        <div class="col-md-9">{{ date("d-M-Y (h:i:a l)", strtotime($data['updated_at'])) }}</div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <p><b>Note</b> Please finish all the question</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class=" d-flex justify-content-center">
                        <div class="box text-center">
                            <div class="box-body py-5 bg-primary-light px-5">
                                <p class="fw-500 text-primary text-overflow">Time Duration</p>
                            </div>
                            <div class="box-body">
                                <h1 class="countnm fs-40 m-0" id="midterm-timer">
                                    {{ sprintf("%02d:%02d:00",   0, 0) }}
                                </h1>
                            </div>
                            <div class="box-body">
                                <button id="start-midterm-btn" onclick="startMidterm()" class="waves-effect waves-light btn btn-lg btn-primary-light"><i class="fa fa-play"></i> Start Midterm</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class=" d-flex justify-content-center">
                        <div id="fb-rendered-form" class="card" style="width:800px">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                    <h1 class="pull-left"> {{ $data['midtermtitle'] }}</h1>
                                    {{-- <h1 id="total_mark" class="pull-right badge badge-xl badge-success"></h1> --}}
                                    </div>
                                </div>
                                <hr>
                                <form id="fb-render" class="mb-3"></form>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <button id="save-btn" class="tst3 btn btn-secondary">Save</button>
                                            <button id="submit-btn" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </section>
        
    </div>
</div>

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.4.2/form-builder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.4.2/form-render.min.js"></script>
<script>

var questionnum = 1;
var midterm = {!! json_encode($data['midterm']) !!};
var selected_midterm = "{{ $data['midtermid'] }}";
var midtermduration = parseInt("{{ $data['midtermduration'] }}");
var midtermstarttime = parseInt("{{ $data['midtermstarttime'] }}");
var midtermtimeleft = "{{ $data['midtermtimeleft'] }}";


var search_timeout = null;

midterm = JSON.parse(midterm);


if(midtermstarttime){
    startMidterm();
}

function startMidterm(){

    var countDownDate = new Date();

    if(midtermtimeleft.length > 0){
        countDownDate.setSeconds( midtermtimeleft );
    }else{
        countDownDate.setSeconds( midtermduration * 60 );
    }   

    countDownDate = countDownDate.getTime();
    
    //Start count down timer
    var x = setInterval(function() {
        // Get today's date and time
        var now = new Date().getTime();
        
        // Find the distance between now and the count down date
        var distance = countDownDate - now;
        
        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Output the result in an element with id="demo"
        document.getElementById("midterm-timer").innerHTML =  hours + "h "
        + minutes + "m " + seconds + "s ";
        
        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("midterm-timer").innerHTML = "TIME EXPIRED";
            $('#submit-btn').trigger('click');
        }
    }, 1000);

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('student/midterm/startmidterm') }}",
        type: 'POST',
        data:  {
            midterm: selected_midterm,
        },
        error:function(err){
            console.log(err);
        },
        success:function(res){
         
        }
    });

    $('#start-midterm-btn').hide();

    renderForm();
}

function renderForm(formdata){
    jQuery(function($) {
        const fbRender = document.getElementById("fb-render");
        const originalFormData = midterm;

        var formRenderOptions = {
            datatype: 'json',
            formData: JSON.stringify(originalFormData)
        };

        $(fbRender).formRender(formRenderOptions);
        $('#fb-rendered-form').show();
    });
}

jQuery(function($) {
    $(document).on('keyup keypress blur change', function(e){
        clearTimeout(search_timeout);
        search_timeout = setTimeout(function() {
            $('#save-btn').click();
        }, 500);
    });

    document.getElementById('save-btn').addEventListener('click', function() {
       
        const fbRender = document.getElementById("fb-render");
   
        $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('student/midterm/savemidterm') }}",
            type: 'POST',
            data:  {
                midterm: selected_midterm,
                data: window.JSON.stringify( {formData: $(fbRender).formRender("userData") })
            },
            error:function(err){
                console.log(err);
            },
            success:function(res){
                //alert(data);
                if(res){
                    $('#save-btn').html('<span class="animted fadeInRight">Changes saved!</span>');
                }else{
                    $('#save-btn').html('<span class="animted fadeInRight">No changes made</span>');
                }
                setTimeout(() => {
                    $('#save-btn').html('Save');
                }, 1000);
            }
        });
    });

    document.getElementById('submit-btn').addEventListener('click', function() {

        const fbRender = document.getElementById("fb-render");

        $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('student/midterm/submitmidterm') }}",
            type: 'POST',
            data:  {
                id: selected_midterm,
                data: window.JSON.stringify( {formData: $(fbRender).formRender("userData") })
            },
            error:function(err){
                console.log(err);
            },
            success:function(res){
                if(!res.status){
                    alert(res.message);
                    location.href = "/student/midterm/{{ Session::get('CourseIDS') }}/"+ selected_midterm;
                }else{
                    location.href = "/student/midterm/{{ Session::get('CourseIDS') }}/"+ selected_midterm;
                }
            }
        });

    }, false);
});

</script>
@endsection

@stop
