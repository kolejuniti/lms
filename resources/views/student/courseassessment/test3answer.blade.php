
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
                    <h4 class="page-title">{{ $data['testtitle'] }}</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Academics</li>
                                <li class="breadcrumb-item" aria-current="page">Groups</li>
                                <li class="breadcrumb-item" aria-current="page">Group List</li>
                                <li class="breadcrumb-item" aria-current="page">Group Content</li>
                                <li class="breadcrumb-item" aria-current="page">Test</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $data['testtitle'] }}</li>
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
                        <div class="col-md-3"><b>Test Title</b></div>
                        <div class="col-md-9">{{ $data['testtitle'] }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Duration</b></div>
                        <div class="col-md-9">{{ sprintf("%0d hour %02d minute",   floor($data['testduration']/60), $data['testduration']%60) }}</div>
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
                                <h1 class="countnm fs-40 m-0" id="test-timer">
                                    {{ sprintf("%02d:%02d:00",   0, 0) }}
                                </h1>
                            </div>
                            <div class="box-body">
                                <button id="start-test-btn" onclick="startTest()" class="waves-effect waves-light btn btn-lg btn-primary-light"><i class="fa fa-play"></i> Start Test</button>
                            </div>
                            <div class="box-body">
                                <a class="btn btn-info btn-sm mr-2">
                                    <i class="ti-alert">
                                    </i>
                                    The Test will be auto submit on {{ $data['testendduration'] }}
                                </a>
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
                                    <h1 class="pull-left"> {{ $data['testtitle'] }}</h1>
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
var test = {!! json_encode($data['test']) !!};
var selected_test = "{{ $data['testid'] }}";
var testduration = parseInt("{{ $data['testduration'] }}");
var teststarttime = parseInt("{{ $data['teststarttime'] }}");
var testtimeleft = "{{ $data['testtimeleft'] }}";
var testend = "{{ $data['testendduration'] }}";


var search_timeout = null;

test = JSON.parse(test);


if(teststarttime){
    startTest();
}

function startTest(){

    var countDownDates = new Date();

    if(testtimeleft.length > 0){
        countDownDates.setSeconds( testtimeleft );
    }else{
        countDownDates.setSeconds( testduration * 60 );
    }   

    countDownDate = countDownDates.getTime();


    
    
    //Start count down timer
    var x = setInterval(function() {
        // Get today's date and time
        var now = new Date().getTime();

        var end = new Date();

        // Split timestamp into [ Y, M, D, h, m, s ]
        var t = testend.split(/[- :]/);

        // Apply each element to the Date function
        var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
        
        
        // Find the distance between now and the count down date
        var distance = countDownDate - now;
        
        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Output the result in an element with id="demo"
        document.getElementById("test-timer").innerHTML =  hours + "h "
        + minutes + "m " + seconds + "s ";

        //alert(end);
        
        // If the count down is over, write some text 
        if (end >= d || distance < 0) {
            clearInterval(x);
            document.getElementById("test-timer").innerHTML = "TIME EXPIRED";
            $('#submit-btn').trigger('click');
        }

    }, 1000);

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('student/test3/starttest') }}",
        type: 'POST',
        data:  {
            test: selected_test,
        },
        error:function(err){
            console.log(err);
        },
        success:function(res){
         
        }
    });

    $('#start-test-btn').hide();

    renderForm();
}

function renderForm(formdata){
    jQuery(function($) {
        const fbRender = document.getElementById("fb-render");
        const originalFormData = test;

        var formRenderOptions = {
            datatype: 'json',
            formData: JSON.stringify(test),
            onRender: function() {
                const fileInputs = document.querySelectorAll('#fb-render input[type="file"]');
                fileInputs.forEach(function(fileInput) {
                    if (fileInput.name) {
                        const img = document.createElement('img');
                        img.src = fileInput.name;
                        img.alt = 'uploaded_image';
                        img.className = 'uploaded-image';
                        fileInput.parentNode.insertBefore(img, fileInput.nextSibling);
                        fileInput.style.display = 'none';
                    }
                });
            }
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
            url: "{{ url('student/test3/savetest') }}",
            type: 'POST',
            data:  {
                test: selected_test,
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
            url: "{{ url('student/test3/submittest') }}",
            type: 'POST',
            data:  {
                id: selected_test,
                data: window.JSON.stringify( {formData: $(fbRender).formRender("userData") })
            },
            error:function(err){
                console.log(err);
            },
            success:function(res){
                if(!res.status){
                    alert(res.message);
                    location.href = "/student/test3/{{ Session::get('CourseIDS') }}/"+ selected_test;
                }else{
                    location.href = "/student/test3/{{ Session::get('CourseIDS') }}/"+ selected_test;
                }
            }
        });

    }, false);
});

</script>
@endsection

@stop
