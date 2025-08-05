@extends('layouts.lecturer.lecturer')

@section('main')

<style>
  .announcement-form-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .form-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
  }
  
  .form-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
  }
  
  .form-header {
    background: #667eea;
    color: white;
    padding: 1.5rem 2rem;
    border-radius: 15px 15px 0 0;
    position: relative;
    overflow: hidden;
  }
  
  .form-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
  }
  
  .form-header h3 {
    position: relative;
    z-index: 1;
    margin: 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .form-body {
    padding: 2rem;
  }
  
  .form-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid #667eea;
  }
  
  .section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
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
  
  .group-selection-card .row {
    margin: 0;
    display: flex;
    flex-wrap: wrap;
  }
  
  .group-selection-card .col-md-6 {
    padding: 0.5rem;
    flex: 0 0 50%;
    max-width: 50%;
  }
  
  @media (max-width: 768px) {
    .group-selection-card .col-md-6 {
      flex: 0 0 100%;
      max-width: 100%;
    }
  }
  
  .group-item {
    padding: 0.75rem;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
  }
  
  .group-item:hover {
    background: #e3f2fd;
    border-color: #667eea;
    transform: translateX(5px);
  }
  
  .custom-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .custom-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #667eea;
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
  
  .alert-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 500;
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
          <h4 class="page-title">Create New Announcement</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Announcements</li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
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
            <div class="form-card animate-fadeInUp">
              <div class="form-header">
                <h3>
                  <i class="mdi mdi-bullhorn"></i>
                  Create New Announcement
                </h3>
                <p class="mb-0 opacity-75">Share important information with your students</p>
              </div>
              
              <!-- Form Start -->
              <form action="{{ route('lecturer.announcement.store') }}" method="POST" id="announcementForm">
                @csrf
                <div class="form-body">
                  
                  <!-- Target Groups Section -->
                  <div class="form-section">
                    <h5 class="section-title">
                      <i class="mdi mdi-account-group text-primary"></i>
                      Target Groups
                    </h5>
                    <div class="form-group">
                      <label class="form-label">
                        <i class="mdi mdi-format-list-bulleted"></i>
                        Select Student Groups
                      </label>
                      <div class="group-selection-card" id="group">
                        <div class="text-center py-4">
                          <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading groups...</span>
                          </div>
                          <p class="mt-2 text-muted">Loading student groups...</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Class Information Section -->
                  <div class="form-section">
                    <h5 class="section-title">
                      <i class="mdi mdi-information text-info"></i>
                      Class Information
                    </h5>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="class_link">
                            <i class="mdi mdi-link-variant"></i>
                            Class Link (Optional)
                          </label>
                          <input type="url" name="class_link" id="class_link" class="form-control" placeholder="https://meet.google.com/xxx-xxx-xxx">
                          <span class="text-danger">@error('class_link'){{ $message }}@enderror</span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="folder">
                            <i class="mdi mdi-folder"></i>
                            Lecturer Folder
                          </label>
                          <select class="form-select" id="folder" name="folder">
                            <option value="" disabled selected>Select a folder...</option>
                            @foreach ($folder as $fold)
                            <option value="{{ $fold->DrID }}">{{ $fold->DrName }}</option>
                            @endforeach
                          </select>
                          <span class="text-danger">@error('folder'){{ $message }}@enderror</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Course Content Section -->
                  <div class="form-section">
                    <h5 class="section-title">
                      <i class="mdi mdi-book-open-page-variant text-success"></i>
                      Course Content
                    </h5>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="chapters">
                            <i class="mdi mdi-file-document"></i>
                            Chapters
                          </label>
                          <select class="form-select" id="chapters" name="chapters">
                            <option value="" disabled selected>First select a folder...</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
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

                  <!-- Announcement Content Section -->
                  <div class="form-section">
                    <h5 class="section-title">
                      <i class="mdi mdi-text-box text-warning"></i>
                      Announcement Content
                    </h5>
                    <div class="form-group">
                      <label class="form-label">
                        <i class="mdi mdi-format-text"></i>
                        Description
                      </label>
                      <textarea id="classdescriptiontxt" name="classdescription" class="form-control" rows="8" placeholder="Write your announcement content here..."></textarea>
                      <span class="text-danger">@error('classdescription'){{ $message }}@enderror</span>
                    </div>
                  </div>

                  <!-- Submit Section -->
                  <div class="text-center pt-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                      <i class="mdi mdi-send me-2"></i>
                      Publish Announcement
                    </button>
                  </div>
                  
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
    
    // Load groups with modern styling
    loadGroups();

    // Add form validation
    $('#announcementForm').on('submit', function(e) {
        const selectedGroups = $('input[name="group[]"]:checked').length;
        if (selectedGroups === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'No Groups Selected',
                text: 'Please select at least one student group.',
                confirmButtonColor: '#667eea'
            });
        }
    });

});

function loadGroups() {
    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/announcement/getGroupList') }}",
        method   : 'GET',
        error:function(err){
            $('#group').html(`
                <div class="text-center py-4">
                    <i class="mdi mdi-alert-circle text-danger" style="font-size: 2rem;"></i>
                    <p class="mt-2 text-danger">Failed to load groups. Please refresh the page.</p>
                </div>
            `);
        },
        success  : function(data){
            console.log('Raw data received:', data);
            // Parse the table data and convert to modern cards
            const $table = $(data);
            const rows = $table.find('tbody tr');
            console.log('Found rows:', rows.length);
            let groupCards = '';
            let counter = 0;
            
            rows.each(function() {
                const $row = $(this);
                const groupName = $row.find('td:first').text().trim();
                const lecturerName = $row.find('td:nth-child(2)').text().trim();
                const checkbox = $row.find('input[type="checkbox"]');
                const checkboxId = checkbox.attr('id');
                const checkboxValue = checkbox.attr('value');
                
                // Start new row every 2 items (but not for the first item)
                if (counter % 2 === 0 && counter > 0) {
                    groupCards += '</div><div class="row">';
                } else if (counter % 2 === 0) {
                    groupCards += '<div class="row">';
                }
                
                groupCards += `
                    <div class="col-md-6">
                        <div class="group-item">
                            <div class="custom-checkbox">
                                <input type="checkbox" id="${checkboxId}" name="group[]" value="${checkboxValue}" class="form-check-input">
                                <label for="${checkboxId}" class="form-check-label w-100">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-primary">${groupName}</strong>
                                            <small class="text-muted d-block">${lecturerName}</small>
                                        </div>
                                        <i class="mdi mdi-account-group text-info"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                `;
                counter++;
            });
            
            groupCards += '</div>';
            
            console.log('Final HTML:', groupCards);
            $('#group').html(groupCards || '<p class="text-center text-muted py-4">No groups found</p>');
        }
    });
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
  
            $('#chapters').removeAttr('hidden');
                $('#chapters').html(data);
                $('#chapters').selectpicker('refresh');
        }
    });

}

$(document).on('change', '#chapters', async function(e){
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

