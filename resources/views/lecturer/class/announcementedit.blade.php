@extends('layouts.lecturer.lecturer')

@section('main')

@if(session()->has('message'))
<div class="card">
<div class="form-group">
    <div class="alert alert-success">
      <span>{{ session()->get('message') }} </span>
    </div>
</div>
</div>
@endif

<style>
  .cke_chrome{
      border:1px solid #eee;
      box-shadow: 0 0 0 #eee;
  }
  
  /* bootstrap select */
.bootstrap-select .hidden{
  display: none;
}
.bootstrap-select div.dropdown-menu.open {
      overflow: hidden;
  }
  .bootstrap-select ul.dropdown-menu.inner{
      max-height: 50em !important;
      overflow-y: auto;
  }

</style>

@if(session()->has('message'))
    <div class="alert alert-success">
        <span>{{ session()->get('message') }}</span>
    </div>
@endif

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Online Class</h4>
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
                <h3 class="card-title">Create Online Class</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="/lecturer/class/onlineclass/list/update/{{ request()->id }}" method="POST">
                @method("PATCH")
                @csrf
                <div class="card-body">
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="group">Group</label>
                        <select class="form-select" id="group" name="group" required>
                            <option value="" disabled>-</option>
                           
                        </select>
                        <span class="text-danger">@error('group')
                          {{ $message }}
                        @enderror</span>
                      </div>
                    </div>   
                    <div class="col-md-6" id = "date-class">
                      <div class="bootstrap-datepicker">
                        <div class="form-group">
                          <label class="form-label" for="date">Date</label>
                          <div class="input-group">
                            <input id="date" name="date" value="{{ $date }}" type="text" class="form-control datepicker" >
                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                          </div>
                          <span class="text-danger">@error('date')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                    </div> 
                    <div class="col-md-6" id = "time-group">
                      <div class="bootstrap-timepicker">
                        <div class="form-group">
                          <label class="form-label" for="time">From</label>
                          <div class="input-group">
                            <input type="time" name="time_from" class="form-control" value="{{ $class->classstarttime }}">
                          </div>
                          <span class="text-danger">@error('time_from')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                    </div> 
                    <div class="col-md-6" id = "time-group">
                      <div class="bootstrap-timepicker">
                        <div class="form-group">
                          <label class="form-label" for="time">To</label>
                          <div class="input-group">
                            <input type="time" name="time_to" class="form-control" value="{{ $class->classendtime }}">
                          </div>
                          <span class="text-danger">@error('time_to')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                    </div>  
                    <div class="col-md-6" id = "class-link">
                      <div class="bootstrap-datepicker">
                        <div class="form-group">
                          <label class="form-label" for="date">Class Link</label>
                          <div class="input-group">
                            <input type="url" name="class_link" class="form-control" value="{{ $class->classlink }}">
                          </div>
                          <span class="text-danger">@error('class_link')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="chapter">Chapters</label>
                        <select class="form-select" id="chapter" name="chapter" required>
                            <option value="" disabled selected>-</option>
                        </select>
                      
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" >
                        <label class="form-label">Sub Chapter List</label>
                        <div class="container mt-1" id="subchapter">
                        </div>
                      </div>
                    </div> 
                    <!--<div class="col-md-12">
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
                  <div class="col-md-12 mt-3">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea id="classdescriptiontxt" name="classdescription" class="mt-2" rows="10" cols="80">
                          {!! $class->classdescription !!}
                        </textarea>
                        <span class="text-danger">@error('classdescription')
                          {{ $message }}
                        @enderror</span>
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


<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>

<script src="{{ asset('assets/assets/vendor_components/dropzone/dropzone.js') }}"></script>

<script type="text/javascript">
var selected_folder = "";
var selected_chapter = "";

$(document).ready(function(){

    var selected_group = "";
    var input_date = "";

    "use strict";
        ClassicEditor
        .create( document.querySelector( '#classdescriptiontxt' ),{ height: '25em' } )
        .then(newEditor =>{editor = newEditor;})
        .catch( error => { console.log( error );});
    
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/class/attendance/getGroup') }}", //+ "?grpid=" + "{{ $class->groupid }}",
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

$(document).on('change', '#folder', async function(e){
    selected_folder = $(e.target).val();

    await getChapters(selected_folder);
});

function getChapters(folder)
{

  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/onlineclass/getChapters') }}",
        method   : 'POST',
        data 	 : {folder: folder},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
  
            $('#chapter').removeAttr('hidden');
                $('#chapter').html(data);
                $('#chapter').selectpicker('refresh');
        }
    });

}

$(document).on('change', '#chapter', async function(e){
    selected_chapter = $(e.target).val();

    await getSubChapters(selected_chapter);
});

function getSubChapters(chapter)
{

  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/onlineclass/getSubChapters') }}",
        method   : 'POST',
        data 	 : {chapter: chapter},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
  
            //$('#subchapter').removeAttr('hidden');
                $('#subchapter').html(data);
                //$('#subchapter').selectpicker('refresh');
        }
    });

};




</script>
@endsection
