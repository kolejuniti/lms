@extends('layouts.student.student')

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
                <li class="breadcrumb-item"><a href="#" class="text-white-50">Online Class</a></li>
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
                  @include('student.forum.TableForum')
                </div>
              </div>
              
              <!-- Comment form -->
              <div class="card-footer bg-white border-top-0 p-4">
                @if($TopicID != '')
                <form action="/student/forum/{{ Session::get('CourseIDS') }}/topic/insert?tpcID={{ $TopicID }}" method="post" role="form" class="comment-form">
                  @csrf
                  @method('POST')
                  <div class="form-group mb-3">
                    <label class="form-label d-flex align-items-center mb-2">
                      <i class="fa fa-pen-alt me-2 text-primary"></i>
                      <span class="fw-semibold">Add Your Comment</span>
                    </label>
                    <textarea id="upforum" name="upforum" class="form-control" rows="4" placeholder="Share your thoughts..."></textarea>
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
                    <img src="{{ asset('storage/storage/forum2.png') }}" alt="Select Topic" class="mb-3" style="max-width: 200px;">
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
                      <a href="/student/forum/{{ Session::get('CourseIDS') }}?TopicID={{ $tpc->TopicID }}" 
                         class="list-group-item list-group-item-action d-flex align-items-center {{ $TopicID == $tpc->TopicID ? 'active' : '' }}">
                        <div class="topic-number me-3">{{ $key+1 }}</div>
                        <div class="topic-name">{{ $tpc->TopicName }}</div>
                      </a>
                    @endforeach
                  @else
                    <div class="list-group-item text-center py-4">
                      <div class="text-muted mb-2">
                        <i class="fa fa-folder-open fa-2x"></i>
                      </div>
                      <p class="mb-0">No topics available</p>
                    </div>
                  @endif
                </div>
              </div>
              
              <!-- Additional helpful resources card -->
              <div class="card mt-4 shadow-sm rounded-lg border-0">
                <div class="card-header bg-white">
                  <h5 class="card-title mb-0">
                    <i class="fa fa-lightbulb text-warning me-2"></i>
                    Forum Tips
                  </h5>
                </div>
                <div class="card-body">
                  <ul class="list-unstyled forum-tips">
                    <li class="mb-2"><i class="fa fa-check-circle text-success me-2"></i> Ask clear, specific questions</li>
                    <li class="mb-2"><i class="fa fa-check-circle text-success me-2"></i> Share your own insights</li>
                    <li class="mb-2"><i class="fa fa-check-circle text-success me-2"></i> Check for existing answers first</li>
                    <li><i class="fa fa-check-circle text-success me-2"></i> Engage with classmates' posts</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
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
      Your comment was posted successfully!
    </div>
  </div>
</div>

<!-- Load required scripts -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function(){
    "use strict";
    
    // Auto-refresh forum messages
    var table = $('#reftable');
    var refresher = setInterval(function(){
      table.load("student.forum.TableForum");
    }, 30000); // Refresh every 30 seconds
    
    setTimeout(function() {
      clearInterval(refresher);
    }, 1800000); // Stop refreshing after 30 minutes
    
    // Manual refresh button
    $('#refreshBtn').click(function() {
      table.load("student.forum.TableForum");
      
      // Show refresh animation
      $(this).html('<i class="fa fa-circle-notch fa-spin me-1"></i> Refreshing...');
      setTimeout(() => {
        $(this).html('<i class="fa fa-sync-alt me-1"></i> Refresh');
      }, 1000);
    });
    
    // Form validation
    $('.comment-form').submit(function(e) {
      if ($('#upforum').val().trim() === '') {
        e.preventDefault();
        $('#upforum').addClass('is-invalid');
        return false;
      }
    });
    
    // Focus input when topic is selected
    @if($TopicID != '')
      setTimeout(() => {
        $('#upforum').focus();
      }, 500);
    @endif
    
    // Show success toast after form submission (if success)
    if (window.location.search.includes('success=true')) {
      $('#successToast').toast('show');
    }
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