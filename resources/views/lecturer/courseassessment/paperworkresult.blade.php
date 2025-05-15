
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
        <div class="page-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">{{ $data['paperworktitle'] }} [Result]</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page">Academics</li>
                                <li class="breadcrumb-item" aria-current="page">Paperwork</li>
                                <li class="breadcrumb-item" aria-current="page">{{ $data['paperworktitle'] }} [Result]</li>
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
                        <div class="col-md-3"><b>paperwork Title</b></div>
                        <div class="col-md-9">{{ $data['paperworktitle'] }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><b>Duedate</b></div>
                        <div class="col-md-9">{{ $data['paperworkdeadline'] }}</div>
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
                        <div class="col-md-3"><b>Submisison Date</b></div>
                        <div class="col-md-9">{{ $data['subdate'] }}</div>
                    </div>
                </div>
                <div class="col-xl-12 col-12">
                    <div class=" d-flex justify-content-center">
                        <div id="fb-rendered-form" class="card" style="width:800px">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h1 class="pull-left"> {{ $data['paperworktitle'] }}</h1>
                                        <div class="pull-right  badge badge-xl badge-success" style="font-size:1.2em">
                                            @if ($data['mark'] != null)
                                            <label >{{ $data['mark'] }} Mark</label>
                                            @else
                                                <label id="participant-mark"></label>
                                            @endif
                                            <!--/ 
                                            <label id="total_mark" ></label>-->
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <form action="/lecturer/paperwork/updatepaperworkresult?id={{ request()->paperworkid }}&participant={{ $data['IC'] }}" id="form-submit"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-md-4 mb-4">
                                        <label for="total-marks" class="form-label "><strong>Upload Your Answer Sheet here.</strong></label>
                                        <input type="file" id="myPdf" name="myPdf" class="form-control"><br required>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="marks" class="form-label "><strong>Total Mark</strong></label>
                                        <input type="number" id="markss" name="markss" class="form-control collected-marks" max="{{ $data['totalmark'] }}"><br required>
                                    </div>
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
                                
                                
                                    <div class="col-md-12 justify-content-center" style="float: center" id="pdfbox" hidden>
                                        <canvas class="justify-content-center" id="pdfViewer"></canvas>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <button id="publish-result-btn" class="btn btn-danger pull-right">Publish Result</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="form-show" hidden>
                                    <div class="col-md-12 justify-content-center" style="float: center">
                                        <div class="col-md-3 text-center mb-3">
                                            <a href="{{ ($data['return'] == null) ? '' : Storage::disk('linode')->url($data['paperwork']) }}">
                                                <svg width="5em" height="5em" enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" >
                                                    <path d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z" fill="#E2E5E7"/>
                                                    <path d="m384 128h96l-128-128v96c0 17.6 14.4 32 32 32z" fill="#B0B7BD"/>
                                                    <polygon points="480 224 384 128 480 128" fill="#CAD1D8"/>
                                                    <path d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16  V416z" fill="#F15642"/>
                                                    <g fill="#fff">
                                                        <path d="m101.74 303.15c0-4.224 3.328-8.832 8.688-8.832h29.552c16.64 0 31.616 11.136 31.616 32.48 0 20.224-14.976 31.488-31.616 31.488h-21.36v16.896c0 5.632-3.584 8.816-8.192 8.816-4.224 0-8.688-3.184-8.688-8.816v-72.032zm16.88 7.28v31.872h21.36c8.576 0 15.36-7.568 15.36-15.504 0-8.944-6.784-16.368-15.36-16.368h-21.36z"/>
                                                        <path d="m196.66 384c-4.224 0-8.832-2.304-8.832-7.92v-72.672c0-4.592 4.608-7.936 8.832-7.936h29.296c58.464 0 57.184 88.528 1.152 88.528h-30.448zm8.064-72.912v57.312h21.232c34.544 0 36.08-57.312 0-57.312h-21.232z"/>
                                                        <path d="m303.87 312.11v20.336h32.624c4.608 0 9.216 4.608 9.216 9.072 0 4.224-4.608 7.68-9.216 7.68h-32.624v26.864c0 4.48-3.184 7.92-7.664 7.92-5.632 0-9.072-3.44-9.072-7.92v-72.672c0-4.592 3.456-7.936 9.072-7.936h44.912c5.632 0 8.96 3.344 8.96 7.936 0 4.096-3.328 8.704-8.96 8.704h-37.248v0.016z"/>
                                                    </g>
                                                    <path d="m400 432h-304v16h304c8.8 0 16-7.2 16-16v-16c0 8.8-7.2 16-16 16z" fill="#CAD1D8"/>
                                                </svg>
                                                <div class="p-3">
                                                    {{ basename($data['paperwork'])}} / {{ $extension = pathinfo(storage_path($data['paperwork']), PATHINFO_EXTENSION); }}
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label class="form-label">Comments</label>
                                            <textarea id="commentss" class="form-control col-md-12 mt-3" rows="10" cols="80" readonly>{{ $data['comments'] }}</textarea>
                                        </div>   
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <a id="done-btn" href="/lecturer/paperwork/{{ Session::get('CourseIDS') }}/{{ $data['paperworkid'] }}" class="btn btn-success pull-right">Done</a>
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

<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>


<script>

var paperwork_status = {{ json_encode($data['studentpaperworkstatus']) }};


jQuery(function($) {

    
    setTimeout(() => {
        renderMark();

        //remove editing features if published
        if( paperwork_status == 3 ){ 
            $('#markss').attr("disabled","disabled");
            $('#publish-result-btn').hide();
            $('#form-submit').hide();
            $('#form-show').removeAttr('hidden');
            $('#done-btn').removeAttr('hidden');
        }
    }, 500);

    /* On Changes */
    $(document).on('keyup', '#markss', function(e){
        renderMark();
    });
});

function renderMark(){
    var total_mark = 0;
   
        var checkbox = $('#markss');

        var mark = checkbox.val();
        mark = parseInt(mark);
        
        total_mark = mark;

    $('#participant-mark').html(total_mark + " Mark");
    //$('#total_mark').html(total_mark + " Mark");
}
</script>

@endsection


@stop
