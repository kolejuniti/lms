
@extends('layouts.student.student')

@section('main')


<style>


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
                    <h4 class="page-title">{{ $data['practicaltitle'] }}</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Academics</li>
                                <li class="breadcrumb-item" aria-current="page">Groups</li>
                                <li class="breadcrumb-item" aria-current="page">Group List</li>
                                <li class="breadcrumb-item" aria-current="page">Group Content</li>
                                <li class="breadcrumb-item" aria-current="page">Practical</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $data['practicaltitle'] }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <form action="/student/practical/submitpractical?id={{ $data['practicalid'] }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 p-4">
                        <div class="row mb-2">
                            <div class="col-md-3"><b>Participant Name</b></div>
                            <div class="col-md-9">{{ Session::get('StudInfos')->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><b>Practical Title</b></div>
                            <div class="col-md-9">{{ $data['practicaltitle'] }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><b>Due Date</b></div>
                            <div class="col-md-9">{{ $data['practicaldeadline'] }}</div>
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
                    
                    <div class="col-md-12 mb-3">
                        <div class=" d-flex justify-content-center">
                            <div id="" class="card" style="width:800px">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                        <h1 class="pull-left"> {{ $data['practicaltitle'] }}</h1>
                                        {{-- <h1 id="total_mark" class="pull-right badge badge-xl badge-success"></h1> --}}
                                        </div>
                                    </div>
                                    <hr>
                                    <form id="" class="mb-4">
                                        <div class="col-md-4 mb-4">
                                            <label for="total-marks" class="form-label "><strong>Upload Your Answer Sheet here.</strong></label>
                                            <input type="file" id="myPdf" name="myPdf" class="form-control"><br required>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class=" d-flex justify-content-center">
                            <div class="card col-md-12 justify-content-center" >
                                <div class="card-body justify-content-center">
                                    <div class="col-md-12 justify-content-center" style="float: center" id="pdfbox" hidden>
                                        <canvas class="justify-content-center" id="pdfViewer"></canvas>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
    
                                                <button id="submit-btn" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        
    </div>
</div>

@section('javascript')
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

<script>

var selected_practical = "{{ $data['practicalid'] }}";

</script>

<script>
    // Loaded via <script> tag, create shortcut to access PDF.js exports.
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
    // The workerSrc property shall be specified.
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';
    
    $("#myPdf").on("change", function(e){

        document.getElementById("pdfbox").hidden = false;

        var file = e.target.files[0]
        if(file.type == "application/pdf"){
            var fileReader = new FileReader();  
            fileReader.onload = function() {
                var pdfData = new Uint8Array(this.result);
                // Using DocumentInitParameters object to load binary data.
                var loadingTask = pdfjsLib.getDocument({data: pdfData});
                loadingTask.promise.then(function(pdf) {
                  console.log('PDF loaded');
                  
                  // Fetch the first page
                  var pageNumber = 1;
                  pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');
                    
                    var scale = 1.5;
                    var viewport = page.getViewport({scale: scale});
    
                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
    
                    // Render PDF page into canvas context
                    var renderContext = {
                      canvasContext: context,
                      viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function () {
                      console.log('Page rendered');
                    });
                  });
                }, function (reason) {
                  // PDF loading error
                  console.error(reason);
                });
            };
            fileReader.readAsArrayBuffer(file);
        }
    });
    </script>
@endsection

@stop
