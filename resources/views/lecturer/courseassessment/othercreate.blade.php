@extends('layouts.lecturer.lecturer')

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
    display:none;
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
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->	  
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">
                        {{ empty($data['other']->title) ? "Create Others" : $data['other']->title }}
                    </h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Academics</li>
                                <li class="breadcrumb-item" aria-current="page">Groups</li>
                                <li class="breadcrumb-item" aria-current="page">Group List</li>
                                <li class="breadcrumb-item" aria-current="page">Group Content</li>
                                
                                    @if(empty($data['other']->title))
                                        <li class="breadcrumb-item active" aria-current="page">Create Others</li>
                                    @else
                                        <li class="breadcrumb-item active" aria-current="page">Others</li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $data['other']->title }}</li>
                                    @endif
                                </li>
                              
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <form action="/lecturer/other/insert" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-xl-12 col-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="header-setting row">
                                <div class="row col-md-12">
                                    <div class="col-md-3 mb-4">
                                        <div class="form-group">
                                          <label class="form-label" for="title"><strong>Other Title</strong></label>
                                          <select class="form-select" id="title" name="title" required>
                                              <option value="" disabled selected>-</option>
                                              @foreach ($title as $tt)
                                              <option value="{{ $tt->id }}" {{ empty($data['other']->title) ? "" : (($data['other']->title == $tt->id) ? "SELECTED" : "") }}>{{ $tt->name }}</option>
                                              @endforeach
                                          </select>
                                          <span class="text-danger">@error('title')
                                            {{ $message }}
                                          @enderror</span>
                                        </div>
                                    </div>
                                    <input type="text" id="other" name="other" value="{{ empty($data['other']->id) ? "" : $data['other']->id }}" hidden>
                                    <div class="col-md-2 mb-4">
                                        <div class="form-group">
                                          <label class="form-label" for="folder">Lecturer Folder</label>
                                          <select class="form-select" id="folder" name="folder" required>
                                              <option value="" disabled selected>-</option>
                                              @foreach ($folder as $fold)
                                              <option value="{{ $fold->DrID }}">{{ $fold->DrName }}</option>
                                              @endforeach
                                          </select>
                                          <span class="text-danger">@error('folder')
                                            {{ $message }}
                                          @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <label for="total-marks" class="form-label "><strong>Total Marks</strong><span> (%)</span></label>
                                        <input type="number" id="total-marks" name="marks" class="form-control" 
                                        value="{{ empty($data['other']->total_mark) ? "" : $data['other']->total_mark }}" required>
                                    </div>
                                </div>
                                <div class="row col-md-12">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group" >
                                            <label class="form-label">Group List</label>
                                            <div class="table-responsive" style="width:99.7%">
                                                <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                                                    <thead class="thead-themed">
                                                    <th>Name</th>
                                                    <th>Course</th>
                                                    <th></th>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($group as $grp)
                                                        <tr>
                                                            <td >
                                                                <label>{{$grp->group_name}}</label>
                                                            </td>
                                                            <td >
                                                                <label>{{$grp->course_name}}</label>
                                                            </td>
                                                            <td >
                                                                <div class="pull-right" >
                                                                    <input type="checkbox" id="chapter_checkbox_{{$grp->group_name}}"
                                                                        class="filled-in" name="group[]" value="{{$grp->id}}|{{ $grp->group_name }}" >
                                                                    <label for="chapter_checkbox_{{$grp->group_name}}"> </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group" >
                                            <label class="form-label">Chapter List</label>
                                            <div class="container mt-1" id="chapterlist">
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="col-md-3 mb-4">
                                        <label for="total-marks" class="form-label "><strong>Content</strong></label>
                                        <input type="file" id="myPdf" name="myPdf" class="form-control"><br required>
                                    </div>-->
                                </div>


                                <div class="col-md-12" style="float: center">
                                    <canvas id="pdfViewer"></canvas>
                                </div>
                                
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-12">
                    <div class="card" style="width:100%">
                        <div class="card-body">
                            <button id="publish-quiz"  class="btn btn-info pull-right m-1"><i class="mdi mdi-publish"></i> Save & Publish</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </section>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.4.2/form-builder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.4.2/form-render.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

<script>
var selected_folder = "";

$(document).on('change', '#folder', async function(e){
    selected_folder = $(e.target).val();

    await getChapters(selected_folder);
});

function getChapters(folder)
{

  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/other/getChapters') }}",
        method   : 'POST',
        data 	 : {folder: folder},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
  
            //$('#chapter').removeAttr('hidden');
                $('#chapterlist').html(data);
                //$('#chapter').selectpicker('refresh');
        }
    });

}
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
    var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#myPdf").on("change", function(e){
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




@stop
