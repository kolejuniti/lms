@extends('layouts.lecturer.lecturer')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="page-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Library</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Library</li>
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
                <h3 class="card-title">Library</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body d-flex">
                  <div class="box-body col-4" style="border: 1px solid blue;margin-right: 20px;">
                    <div class="accordion" id="libraryAccordion">
                      @foreach ($lecturer as $key => $lct)
                        <div class="accordion-item mb-2">
                          <!-- Main Course Header -->
                          <h2 class="accordion-header" id="heading-{{ $lct->ic }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $lct->ic }}" aria-expanded="false" aria-controls="collapse-{{ $lct->ic }}">
                              <i class="ti-book me-2"></i>
                              {{ $lct->name }} {{ Session::get('CourseID') }}
                            </button>
                          </h2>
                          
                          <!-- Main Course Content -->
                          <div id="collapse-{{ $lct->ic }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $lct->ic }}" data-bs-parent="#libraryAccordion">
                            <div class="accordion-body p-0">
                              <div class="list-group list-group-flush">
                                
                                <!-- Course Content -->
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action" onclick="getContent('{{ $lct->ic }}')">
                                  <i class="ti-folder me-2"></i>
                                  Course Content
                                </a>
                                
                                <!-- Assessment Dropdown -->
                                <div class="list-group-item p-0">
                                  <div class="accordion" id="assessmentAccordion-{{ $key }}">
                                    <div class="accordion-item border-0">
                                      <h2 class="accordion-header" id="assessmentHeading-{{ $key }}">
                                        <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#assessmentCollapse-{{ $key }}" aria-expanded="false" aria-controls="assessmentCollapse-{{ $key }}">
                                          <i class="ti-folder me-2"></i>
                                          Assessment
                                        </button>
                                      </h2>
                                      
                                      <div id="assessmentCollapse-{{ $key }}" class="accordion-collapse collapse" aria-labelledby="assessmentHeading-{{ $key }}" data-bs-parent="#assessmentAccordion-{{ $key }}">
                                        <div class="accordion-body p-0">
                                          <div class="list-group list-group-flush">
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action ps-4" onclick="getQuiz('{{ $lct->ic }}')">
                                              <i class="ti-folder me-2"></i>
                                              Quiz
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action ps-4" onclick="getTest('{{ $lct->ic }}')">
                                              <i class="ti-folder me-2"></i>
                                              Test
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action ps-4" onclick="getAssignment('{{ $lct->ic }}')">
                                              <i class="ti-folder me-2"></i>
                                              Assignment
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action ps-4" onclick="getMidterm('{{ $lct->ic }}')">
                                              <i class="ti-folder me-2"></i>
                                              Midterm
                                            </a>
                                            <a href="javascript:void(0)" class="list-group-item list-group-item-action ps-4" onclick="getFinal('{{ $lct->ic }}')">
                                              <i class="ti-folder me-2"></i>
                                              Final
                                            </a>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                  <div class="box-body col-md-8 mt-2">
                    <div id="showMaterial">

                    </div>
                  </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">

function getContent(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getFolder') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

  
function tryerr(id)
{
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getSubfolder') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

  
  //alert(id);
}

function tryerr2(id)
{
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getSubfolder/getSubfolder2') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function tryerr3(id)
{
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getSubfolder/getSubfolder2/getMaterial') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });
}

function getQuiz(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getQuiz') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function getTest(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getTest') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function getAssignment(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getAssignment') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function getMidterm(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getMidterm') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function getFinal(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getFinal') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

// Enhanced Library Navigation
$(document).ready(function() {
  // Add click feedback for library items
  $('.list-group-item-action').on('click', function() {
    // Remove active class from all items
    $('.list-group-item-action').removeClass('active');
    // Add active class to clicked item
    $(this).addClass('active');
    
    // Add loading state
    $('#showMaterial').html('<div class="text-center p-4"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2">Loading content...</p></div>');
  });
  
  // Add hover effects for better UX
  $('.accordion-button').on('mouseenter', function() {
    $(this).css('background-color', '#e9ecef');
  }).on('mouseleave', function() {
    if ($(this).hasClass('collapsed')) {
      $(this).css('background-color', '#f8f9fa');
    } else {
      $(this).css('background-color', '#e7f3ff');
    }
  });
});

</script>

<style>
/* Custom Library Accordion Styles */
.accordion-item {
  border: 1px solid #dee2e6 !important;
  border-radius: 0.375rem !important;
  margin-bottom: 0.5rem;
}

.accordion-button {
  padding: 0.75rem 1rem;
  font-weight: 500;
  border: none;
  background-color: #f8f9fa;
}

.accordion-button:not(.collapsed) {
  background-color: #e7f3ff;
  color: #0d6efd;
  box-shadow: none;
}

.accordion-button:focus {
  box-shadow: none;
  border-color: rgba(13, 110, 253, 0.25);
}

.accordion-button::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
}

.list-group-item {
  border: none;
  padding: 0.75rem 1rem;
  transition: all 0.2s ease;
}

.list-group-item:hover {
  background-color: #f1f3f4;
  color: #0d6efd;
}

.list-group-item.list-group-item-action:hover {
  background-color: #e3f2fd;
}

/* Assessment sub-accordion styling */
.accordion .accordion .accordion-button {
  background-color: #f1f3f4;
  font-size: 0.95rem;
  padding: 0.6rem 1rem;
}

.accordion .accordion .accordion-button:not(.collapsed) {
  background-color: #dbeafe;
  color: #1e40af;
}

/* Icons styling */
.ti-book, .ti-folder {
  color: #6c757d;
  width: 16px;
  display: inline-block;
}

/* Indentation for sub-items */
.ps-4 {
  padding-left: 2.5rem !important;
}

/* Smooth transitions */
.accordion-collapse {
  transition: all 0.3s ease;
}

/* Active item highlighting */
.list-group-item.active {
  background-color: #0d6efd;
  border-color: #0d6efd;
  color: white;
}

/* Custom scrollbar for the library section */
.accordion {
  max-height: 600px;
  overflow-y: auto;
}

.accordion::-webkit-scrollbar {
  width: 6px;
}

.accordion::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.accordion::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.accordion::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>

@endsection
