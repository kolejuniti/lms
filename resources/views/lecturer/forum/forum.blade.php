@extends('layouts.lecturer.lecturer')

@section('main')
<div class="content-wrapper">
  <div class="container-full">
    <!-- Header section with animated wave background -->
    <div class="forum-header position-relative overflow-hidden bg-gradient-primary text-white py-4 mb-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h2 class="fw-bold mb-0 d-flex align-items-center">
              <i class="fa fa-comments me-3 forum-icon"></i>
              Course Forum
            </h2>
            <nav aria-label="breadcrumb" class="mt-2">
              <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-white-50"><i class="mdi mdi-home-outline"></i> Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Forum</li>
              </ol>
            </nav>
          </div>
          <div class="col-md-4 text-end">
            <h4 class="course-title">{{ $course->course_name }}</h4>
          </div>
        </div>
      </div>
      <!-- Animated wave SVG -->
      <svg class="position-absolute bottom-0 start-0 w-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100">
        <path fill="#ffffff" fill-opacity="1" d="M0,64L60,53.3C120,43,240,21,360,16C480,11,600,21,720,42.7C840,64,960,96,1080,96C1200,96,1320,64,1380,48L1440,32L1440,100L1380,100C1320,100,1200,100,1080,100C960,100,840,100,720,100C600,100,480,100,360,100C240,100,120,100,60,100L0,100Z"></path>
      </svg>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- Main forum content -->
          <div class="col-md-8">
            <div class="card shadow-sm rounded-lg border-0">
              <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0">
                <div>
                  @if($TopicID != '')
                    <h5 class="mb-0 fw-bold"><i class="fa fa-bookmark text-primary me-2"></i>Topic: {{ $TopicID }}</h5>
                  @else
                    <h5 class="mb-0 fw-bold"><i class="fa fa-bookmark text-primary me-2"></i>Topics</h5>
                  @endif
                </div>
                <div>
                  <button class="btn btn-sm btn-outline-primary" id="refreshBtn">
                    <i class="fa fa-sync-alt me-1"></i> Refresh
                  </button>
                </div>
              </div>
              
              <!-- Forum messages container -->
              <div class="card-body p-0">
                <div class="forum-list-header d-none d-md-flex bg-light p-3">
                  <div class="col-md-2">
                    <span class="text-muted fw-semibold">User</span>
                  </div>
                  <div class="col-md-7">
                    <span class="text-muted fw-semibold">Discussion</span>
                  </div>
                  <div class="col-md-3 text-end">
                    <span class="text-muted fw-semibold">Posted</span>
                  </div>
                </div>
                
                <div class="forum-messages" id="reftable">
                  @include('lecturer.forum.TableForum')
                </div>
              </div>
              
              <!-- Comment form -->
              <div class="card-footer bg-white border-top-0 p-4">
                @if($TopicID != '')
                <form action="/lecturer/forum/{{ Session::get('CourseIDS') }}/topic/insert?tpcID={{ $TopicID }}" method="post" role="form" class="comment-form">
                  @csrf
                  @method('POST')
                  <div class="form-group mb-3">
                    <label class="form-label d-flex align-items-center mb-2">
                      <i class="fa fa-pen-alt me-2 text-primary"></i>
                      <span class="fw-semibold">Add Your Comment</span>
                    </label>
                    <textarea id="upforumtxt" name="upforum" class="form-control" rows="6" placeholder="Share your thoughts..."></textarea>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="form-text text-muted">
                      <i class="fa fa-info-circle me-1"></i> 
                      Be respectful and constructive in your comments
                    </div>
                    <button type="submit" name="addfrm" class="btn btn-primary px-4">
                      <i class="fa fa-paper-plane me-2"></i> Send
                    </button>
                  </div>
                </form>
                @else
                <div class="text-center py-4">
                  <img src="{{ asset('assets/images/select-topic.svg') }}" alt="Select Topic" class="mb-3" style="max-width: 200px;">
                  <h5 class="text-muted">Please select a topic to join the discussion</h5>
                </div>
                @endif
              </div>
            </div>
          </div>
          
          <!-- Topics sidebar -->
          <div class="col-md-4">
            <div class="card shadow-sm rounded-lg border-0">
              <div class="card-header bg-gradient-primary text-white">
                <h5 class="card-title mb-0 d-flex align-items-center">
                  <i class="fa fa-list-alt me-2"></i>
                  <span>Discussion Topics</span>
                </h5>
              </div>
              
              <div class="card-body p-0">
                <div class="list-group list-group-flush">
                  @if(isset($topic) && !empty($topic))
                    @foreach ($topic as $key => $tpc)
                      <a href="/lecturer/forum/{{ Session::get('CourseIDS') }}?TopicID={{ $tpc->TopicID }}" 
                         class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $TopicID == $tpc->TopicID ? 'active' : '' }}">
                        <div class="d-flex align-items-center">
                          <span class="topic-number me-3">{{ $key+1 }}</span>
                          <span class="topic-name">{{ $tpc->TopicName }}</span>
                        </div>
                        <div class="d-flex">
                          <button class="btn btn-sm btn-outline-danger delete-topic-btn" 
                                  data-toggle="tooltip" 
                                  data-placement="top" 
                                  title="Delete Topic">
                            <i class="ti-trash"></i>
                          </button>
                        </div>
                      </a>
                    @endforeach
                  @else
                    <div class="list-group-item text-center py-4">
                      <div class="text-muted mb-2">
                        <i class="fa fa-folder-open fa-2x"></i>
                      </div>
                      <p>No topics available</p>
                    </div>
                  @endif
                </div>
              </div>
              
              <div class="card-footer bg-white">
                <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#uploadModal">
                  <i class="fa fa-plus-circle me-2"></i> Create New Topic
                </button>
              </div>
            </div>
            
            <!-- Additional helpful resources card -->
            <div class="card shadow-sm rounded-lg border-0 mt-4">
              <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                  <i class="fa fa-lightbulb text-warning me-2"></i>
                  Forum Guidelines
                </h5>
              </div>
              <div class="card-body">
                <ul class="list-unstyled forum-tips">
                  <li class="mb-2"><i class="fa fa-check-circle text-success me-2"></i> Be respectful to others</li>
                  <li class="mb-2"><i class="fa fa-check-circle text-success me-2"></i> Stay on topic</li>
                  <li class="mb-2"><i class="fa fa-check-circle text-success me-2"></i> Ask clear questions</li>
                  <li><i class="fa fa-check-circle text-success me-2"></i> Share relevant resources</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Add Topic Modal -->
<div id="uploadModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-lg shadow">
      <div class="modal-header bg-light">
        <h5 class="modal-title"><i class="fa fa-plus-circle text-primary me-2"></i>Create New Topic</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form action="/lecturer/forum/{{ Session::get('CourseIDS') }}/insert" method="post" role="form" enctype="multipart/form-data" id="newTopicForm">
          @csrf
          @method('POST')
          <div class="form-group mb-4">
            <label class="form-label fw-semibold">Topic Title</label>
            <input type="text" name="inputTitle" id="inputTitle" class="form-control" placeholder="Enter a descriptive title">
            <div class="form-text text-muted">
              Choose a clear title that describes what the discussion is about
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-light me-2" data-dismiss="modal">Cancel</button>
            <button type="submit" name="addtopic" class="btn btn-primary">
              <i class="fa fa-plus-circle me-1"></i> Create Topic
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Toast notification for successful actions -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-success text-white">
      <strong class="me-auto"><i class="fa fa-check-circle me-2"></i>Success</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Action completed successfully!
    </div>
  </div>
</div>

<!-- Load required scripts -->
<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function(){
    "use strict";
    
    // Initialize CKEditor
    ClassicEditor
      .create(document.querySelector('#upforumtxt'), {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'undo', 'redo'],
        placeholder: 'Type your message here...',
      })
      .then(newEditor => {
        editor = newEditor;
      })
      .catch(error => {
        console.log(error);
      });
    
    // Auto-refresh forum messages
    var table = $('#reftable');
    var refresher = setInterval(function(){
      table.load("lecturer.forum.TableForum");
    }, 30000); // Refresh every 30 seconds
    
    setTimeout(function() {
      clearInterval(refresher);
    }, 1800000); // Stop refreshing after 30 minutes
    
    // Manual refresh button
    $('#refreshBtn').click(function() {
      table.load("lecturer.forum.TableForum");
      
      // Show refresh animation
      $(this).html('<i class="fa fa-circle-notch fa-spin me-1"></i> Refreshing...');
      setTimeout(() => {
        $(this).html('<i class="fa fa-sync-alt me-1"></i> Refresh');
      }, 1000);
    });
    
    // Form validation for new topic
    $('#newTopicForm').submit(function(e) {
      if ($('#inputTitle').val().trim() === '') {
        e.preventDefault();
        $('#inputTitle').addClass('is-invalid');
        return false;
      }
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Show success toast after form submission (if success)
    if (window.location.search.includes('success=true')) {
      $('#successToast').toast('show');
    }
  });

  // Delete topic confirmation
  $('.delete-topic-btn').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    Swal.fire({
      title: "Delete Topic?",
      text: "This will permanently remove this topic and all its messages",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "Cancel",
      confirmButtonColor: "#dc3545",
      cancelButtonColor: "#6c757d",
    }).then(function(result) {
      if (result.isConfirmed) {
        // Here you would call your delete endpoint
        // For now just showing a success message
        Swal.fire({
          title: "Deleted!",
          text: "The topic has been deleted.",
          icon: "success",
          confirmButtonColor: "#4caf50"
        });
      }
    });
  });

  // Add custom styles
  document.head.insertAdjacentHTML('beforeend', `
    <style>
      .forum-header {
        background: linear-gradient(135deg, #3f51b5 0%, #2196f3 100%);
      }
      
      .forum-icon {
        font-size: 1.5rem;
      }
      
      .course-title {
        display: inline-block;
        background-color: rgba(255, 255, 255, 0.2);
        padding: 8px 15px;
        border-radius: 30px;
      }
      
      .topic-number {
        width: 24px;
        height: 24px;
        background-color: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: bold;
      }
      
      .list-group-item.active .topic-number {
        background-color: rgba(255, 255, 255, 0.3);
      }
      
      .forum-messages {
        max-height: 500px;
        overflow-y: auto;
      }
      
      .forum-tips li {
        position: relative;
        padding-left: 0;
      }
      
      .ck-editor__editable {
        min-height: 200px;
        border-radius: 0 0 5px 5px !important;
      }
      
      .toast {
        opacity: 0.95;
      }
      
      /* Animations */
      @keyframes slideInUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
      }
      
      .card {
        animation: slideInUp 0.3s ease-out;
      }
      
      /* Make the forum responsive */
      @media (max-width: 768px) {
        .course-title {
          margin-top: 10px;
        }
      }
    </style>
  `);
</script>
@endsection