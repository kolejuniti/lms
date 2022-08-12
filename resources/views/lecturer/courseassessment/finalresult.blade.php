
@extends('layouts.lecturer.lecturer')

@section('main')


<style>


#fb-rendered-form {
    clear:both;
    /* display:none; */
    button{
        float:right;
    }
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
                    <h4 class="page-title">{{ $data['finaltitle'] }} [Result]</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page">Academics</li>
                                <li class="breadcrumb-item" aria-current="page">Final</li>
                                <li class="breadcrumb-item" aria-current="page">{{ $data['finaltitle'] }} [Result]</li>
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
                        <div class="col-md-3"><b>Final Title</b></div>
                        <div class="col-md-9">{{ $data['finaltitle'] }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Duration</b></div>
                        <div class="col-md-9">{{ sprintf("%0d hour %02d minute",   floor($data['finalduration']/60), $data['finalduration']%60) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Created At</b></div>
                        <div class="col-md-9">{{ date("d-M-Y (h:i:a l)", strtotime($data['created_at'])) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Last Updated</b></div>
                        <div class="col-md-9">{{ date("d-M-Y (h:i:a l)", strtotime($data['updated_at'])) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Submitted At</b></div>
                        <div class="col-md-9">{{ date("d-M-Y (h:i:a l)", strtotime($data['submittime'])) }}</div>
                    </div>
                </div>
                <div class="col-xl-12 col-12">
                    <div class=" d-flex justify-content-center">
                        <div id="fb-rendered-form" class="card" style="width:800px">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h1 class="pull-left"> {{ $data['finaltitle'] }}</h1>
                                        <div class="pull-right  badge badge-xl badge-success" style="font-size:1.2em">
                                                <label id="participant-mark"></label> <!--/ 
                                            <label id="total_mark" ></label>-->
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <form id="fb-render" class="mt-4"></form>
                                <hr>
                                @if ($data['comments'] == null)
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label class="form-label">Comments</label>
                                        <textarea id="commentss" name="commentss" class="form-control col-md-12 mt-3" rows="10" cols="80"></textarea>
                                     
                                    </div>   
                                </div>
                                @else
        
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label class="form-label">Comments</label>
                                        <textarea id="commentss" class="form-control col-md-12 mt-3" rows="10" cols="80" readonly>{{ $data['comments'] }}</textarea>
                                    </div>   
                                </div>
                                   
                                @endif
                                
                                
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <button id="publish-result-btn" class="btn btn-danger pull-right">Publish Result</button>
                                        <a id="done-btn" href="/lecturer/final/{{ Session::get('CourseIDS') }}/{{ $data['finalid'] }}" class="btn btn-success pull-right" hidden>Done</a>
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

<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>


<script>

var final = {!! json_encode($data['final']) !!};
var selected_final = {{ json_encode($data['finalid']) }};
var selected_participant = {!! json_encode($data['finaluserid']) !!};
var final_status = {{ json_encode($data['studentfinalstatus']) }};
var index = "{{ $data['questionindex'] }}";

$(document).ready(function(){

var selected_group = "";
var input_date = "";


"use strict";
    ClassicEditor
    .create( document.querySelector( '#comments' ),{ height: '25em' } )
    .then(newEditor =>{editor = newEditor;})
    .catch( error => { console.log( error );});

})

jQuery(function($) {

 
    /* On Clicks */
    document.getElementById('publish-result-btn').addEventListener('click', function() {
    
            $('[name="radio-question"]').removeAttr('disabled');
            $('[name="checkbox-question[]"]').removeAttr('disabled');

            const fbRender = document.getElementById("fb-render");

            $.ajax({
                headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('lecturer/final/updatefinalresult') }}",
                type: 'POST',
                data:  {
                    final: selected_final,
                    participant: selected_participant,
                    final_mark: $('#participant-mark').html(),
                    comments: $('#commentss').val(),
                    //total_mark: $('#total_mark').html(),
                    data: window.JSON.stringify( {formData: $(fbRender).formRender("userData") })
                },
                error:function(err){
                    console.log(err);
                },
                success:function(res){
                    location.href = "/lecturer/final/"+ selected_final +"/"+selected_participant+"/result";
                }
            });
    }, false);
    
   

   

    renderForm(final);
    
    setTimeout(() => {
        renderMark();
        
        $('[name="radio-question"]').attr("disabled","disabled");
        $('[name="checkbox-question[]"]').attr("disabled","disabled");
        $('[name="subjective-text"]').attr("disabled","disabled");

        //remove editing features if published
        if( final_status == 3 ){ 
            $('.collected-marks').attr("disabled","disabled");
            $('#publish-result-btn').hide();
            $('#done-btn').removeAttr('hidden');
        }
    }, 500);

    /* On Changes */
    $(document).on('change', '.collected-marks', function(e){
        renderMark();
    });
});


function renderForm(formdata){
    return jQuery(function($) {
        const fbRender = document.getElementById("fb-render");
        const originalFormData = formdata;

        var formRenderOptions = {
            datatype: 'json',
            formData: JSON.stringify(originalFormData)
        };

        $(fbRender).formRender(formRenderOptions );

        //$('[name="checkbox-question3[]"]').attr("disabled","disabled");

        for(let i=0; i < index; i++){

        $(`[name="radio-question${i}"]:not(:checked)`).attr('disabled', true);
        $(`[name="checkbox-question${i}[]"]`).click(false);
        $(`[name="subjective-text${i}"]:not(:checked)`).attr('disabled', true);

        //alert(`[name="checkbox-question${i}"]`)
        }
       
        //alert(index);
    });
}

function renderMark(){
    var total_mark = 0, total_correct_mark = 0;
   
    $('.collected-marks').each((i)=>{
        var checkbox = $($('.collected-marks')[i]);

        var mark = checkbox.val();
        mark = parseInt(mark);

        if(checkbox.is(':checked')){
            total_correct_mark = total_correct_mark + mark;
        }
        
        total_mark = total_mark + mark;
    });

    $('#participant-mark').html(total_correct_mark + " Mark");
    //$('#total_mark').html(total_mark + " Mark");
}
</script>

@endsection


@stop
