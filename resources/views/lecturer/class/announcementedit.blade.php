@extends('layouts.lecturer.lecturer')

@section('main')

<style>
  .edit-announcement-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .edit-form-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    overflow: hidden;
  }
  
  .edit-form-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
  }
  
  .edit-form-header {
    background: #667eea;
    color: white;
    padding: 2rem;
    position: relative;
    overflow: hidden;
  }
  
  .edit-form-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
  }
  
  .edit-form-header h3 {
    position: relative;
    z-index: 1;
    margin: 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .edit-form-body {
    padding: 2rem;
  }
  
  .edit-form-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid #667eea;
    position: relative;
  }
  
  .edit-form-section::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 60px;
    height: 60px;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
    transform: translate(20px, -20px);
    border-radius: 50%;
  }
  
  .section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
    z-index: 1;
  }
  
  .form-group {
    margin-bottom: 1.5rem;
  }
  
  .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  
  .form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background: white;
  }
  
  .form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    transform: translateY(-2px);
  }
  
  .group-selection-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    max-height: 300px;
    overflow-y: auto;
  }
  
  .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
  }
  
  .btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    color: white;
  }
  
  .btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
    color: white;
  }
  
  .alert-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    border: none;
    border-radius: 15px;
    color: white;
    font-weight: 500;
    border-left: 5px solid #28a745;
  }
  
  .breadcrumb {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    backdrop-filter: blur(10px);
  }
  
  .ck-editor {
    border-radius: 10px;
    overflow: hidden;
  }
  
  .ck-editor__editable {
    min-height: 200px;
    border-radius: 0 0 10px 10px;
  }
  
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
  }
  
  .icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
  }
  
  .status-indicator {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 2;
  }
  
  .form-actions {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem;
    border-radius: 0 0 20px 20px;
    text-align: center;
    gap: 1rem;
    display: flex;
    justify-content: center;
    align-items: center;
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    @if(session()->has('message'))
    <div class="container-fluid mb-4">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="alert alert-success animate-fadeInUp" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session()->get('message') }}
          </div>
        </div>
      </div>
    </div>
    @endif
    
    <!-- Page Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Edit Announcement</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Announcements</li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <section class="content">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="edit-form-card animate-fadeInUp">
              <!-- Status Indicator -->
              <div class="status-indicator">
                <i class="mdi mdi-pencil me-1"></i>
                Editing Mode
              </div>
              
              <div class="edit-form-header">
                <h3>
                  <i class="mdi mdi-pencil"></i>
                  Update Announcement
                </h3>
                <p class="mb-0 opacity-75">Modify your announcement details and settings</p>
              </div>
              
              <!-- Form Start -->
              <form action="/lecturer/class/onlineclass/list/update/{{ request()->id }}" method="POST" id="editAnnouncementForm">
                @method("PATCH")
                @csrf
                <div class="edit-form-body">
                  
                  <!-- Basic Information Section -->
                  <div class="edit-form-section">
                    <h5 class="section-title">
                      <i class="mdi mdi-information text-primary"></i>
                      Basic Information
                    </h5>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="group">
                            <i class="mdi mdi-account-group"></i>
                            Target Group
                          </label>
                          <select class="form-select" id="group" name="group" required>
                            <option value="" disabled>Select a group...</option>
                          </select>
                          <span class="text-danger">@error('group'){{ $message }}@enderror</span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="date">
                            <i class="mdi mdi-calendar"></i>
                            Class Date
                          </label>
                          <input id="date" name="date" value="{{ $date }}" type="date" class="form-control">
                          <span class="text-danger">@error('date'){{ $message }}@enderror</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Schedule Section -->
                  <div class="edit-form-section">
                    <h5 class="section-title">
                      <i class="mdi mdi-clock text-info"></i>
                      Class Schedule
                    </h5>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="time_from">
                            <i class="mdi mdi-clock-start"></i>
                            Start Time
                          </label>
                          <input type="time" name="time_from" id="time_from" class="form-control" value="{{ $class->classstarttime }}">
                          <span class="text-danger">@error('time_from'){{ $message }}@enderror</span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="time_to">
                            <i class="mdi mdi-clock-end"></i>
                            End Time
                          </label>
                          <input type="time" name="time_to" id="time_to" class="form-control" value="{{ $class->classendtime }}">
                          <span class="text-danger">@error('time_to'){{ $message }}@enderror</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Class Information Section -->
                  <div class="edit-form-section">
                    <h5 class="section-title">
                      <i class="mdi mdi-video text-success"></i>
                      Class Details
                    </h5>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="form-label" for="class_link">
                            <i class="mdi mdi-link-variant"></i>
                            Class Link (Optional)
                          </label>
                          <input type="url" name="class_link" id="class_link" class="form-control" value="{{ $class->classlink }}" placeholder="https://meet.google.com/xxx-xxx-xxx">
                          <span class="text-danger">@error('class_link'){{ $message }}@enderror</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Course Content Section -->
                  <div class="edit-form-section">
                    <h5 class="section-title">
                      <i class="mdi mdi-book-open-page-variant text-warning"></i>
                      Course Content
                    </h5>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="folder">
                            <i class="mdi mdi-folder"></i>
                            Lecturer Folder
                          </label>
                          <select class="form-select" id="folder" name="folder" required>
                            <option value="" disabled selected>Select a folder...</option>
                            @foreach ($folder as $fold)
                            <option value="{{ $fold->DrID }}">{{ $fold->DrName }}</option>
                            @endforeach
                          </select>
                          <span class="text-danger">@error('folder'){{ $message }}@enderror</span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="chapter">
                            <i class="mdi mdi-file-document"></i>
                            Chapters
                          </label>
                          <select class="form-select" id="chapter" name="chapter" required>
                            <option value="" disabled selected>First select a folder...</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="form-label">
                            <i class="mdi mdi-file-document-multiple"></i>
                            Sub Chapters
                          </label>
                          <div class="group-selection-card" id="subchapter">
                            <p class="text-muted text-center py-3">Select a chapter to view sub-chapters</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Content Section -->
                  <div class="edit-form-section">
                    <h5 class="section-title">
                      <i class="mdi mdi-text-box text-danger"></i>
                      Announcement Content
                    </h5>
                    <div class="form-group">
                      <label class="form-label">
                        <i class="mdi mdi-format-text"></i>
                        Description
                      </label>
                      <textarea id="classdescriptiontxt" name="classdescription" class="form-control" rows="10" placeholder="Write your announcement content here...">{!! $class->classdescription !!}</textarea>
                      <span class="text-danger">@error('classdescription'){{ $message }}@enderror</span>
                    </div>
                  </div>
                  
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                  <a href="/lecturer/class/announcement/list" class="btn btn-secondary btn-lg me-3">
                    <i class="mdi mdi-arrow-left me-2"></i>
                    Cancel
                  </a>
                  <button type="submit" class="btn btn-primary btn-lg">
                    <i class="mdi mdi-content-save me-2"></i>
                    Update Announcement
                  </button>
                </div>
              </form>
            </div>
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
        .create( document.querySelector( '#classdescriptiontxt' ),{ 
            height: '250px',
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ]
        })
        .then(newEditor =>{
            editor = newEditor;
            // Add some styling to the editor
            document.querySelector('.ck-editor__editable').style.backgroundColor = '#f8f9fa';
        })
        .catch( error => { console.log( error );});
    
    // Load groups for editing
    loadGroupsForEdit();

    // Add form validation
    $('#editAnnouncementForm').on('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });

});

function loadGroupsForEdit() {
    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/attendance/getGroup') }}",
        method   : 'GET',
        error:function(err){
            $('#group').html('<option value="" disabled>Error loading groups</option>');
            Swal.fire({
                icon: 'error',
                title: 'Loading Error',
                text: 'Failed to load groups. Please refresh the page.',
                confirmButtonColor: '#667eea'
            });
        },
        success  : function(data){
            $('#group').html(data);
            // Pre-select the current group if needed
            $('#group').val('{{ $class->groupid ?? "" }}');
        }
    });
}

function validateForm() {
    let isValid = true;
    let errorMessage = '';
    
    // Validate required fields
    if (!$('#group').val()) {
        errorMessage += 'Please select a target group.\n';
        isValid = false;
    }
    
    if (!$('#date').val()) {
        errorMessage += 'Please select a class date.\n';
        isValid = false;
    }
    
    if (!editor.getData().trim()) {
        errorMessage += 'Please enter announcement content.\n';
        isValid = false;
    }
    
    // Validate time range if both times are provided
    const timeFrom = $('#time_from').val();
    const timeTo = $('#time_to').val();
    
    if (timeFrom && timeTo && timeFrom >= timeTo) {
        errorMessage += 'End time must be after start time.\n';
        isValid = false;
    }
    
    if (!isValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation Error',
            text: errorMessage,
            confirmButtonColor: '#667eea'
        });
    }
    
    return isValid;
}

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
