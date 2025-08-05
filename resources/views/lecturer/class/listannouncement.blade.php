@extends('layouts.lecturer.lecturer')

@section('main')

<style>
  .announcements-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .announcements-header {
    background: #ffffff;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
  }
  
  .announcement-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
  }
  
  .announcement-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
  }
  
  .announcement-header {
    background: #667eea;
    color: white;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
  }
  
  .announcement-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
  }
  
  .announcement-body {
    padding: 1.5rem;
  }
  
  .announcement-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
  }
  
  .group-badge {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    display: inline-block;
  }
  
  .chapter-badge {
    background: linear-gradient(45deg, #56ab2f, #a8e6cf);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    margin-right: 0.5rem;
    margin-bottom: 0.25rem;
    display: inline-block;
  }
  
  .stats-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 1rem;
    margin-bottom: 1rem;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
  }
  
  .stats-number {
    font-size: 2rem;
    font-weight: bold;
    color: #667eea;
    display: block;
  }
  
  .stats-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 0.25rem;
  }
  
  .action-btn {
    border: none;
    border-radius: 10px;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    transition: all 0.3s ease;
    font-weight: 500;
  }
  
  .btn-edit {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
  }
  
  .btn-delete {
    background: linear-gradient(45deg, #ff6b6b, #ee5a52);
    color: white;
  }
  
  .btn-edit:hover, .btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }
  
  .search-container {
    background: #ffffff;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
  }
  
  .search-input {
    border: 2px solid #e9ecef;
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
  }
  
  .search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }
  
  .announcement-content {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    margin: 1rem 0;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    max-height: 200px;
    overflow-y: auto;
  }
  
  .announcement-link {
    color: #667eea;
    text-decoration: none;
    padding: 0.5rem 1rem;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 10px;
    border: 2px solid rgba(102, 126, 234, 0.2);
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .announcement-link:hover {
    background: rgba(102, 126, 234, 0.2);
    transform: translateX(5px);
  }
  
  .empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    backdrop-filter: blur(10px);
  }
  
  .empty-state-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 1rem;
  }
  
  .pagination {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 1rem;
    backdrop-filter: blur(10px);
    margin-top: 2rem;
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
  
  .animate-delay-1 { animation-delay: 0.1s; }
  .animate-delay-2 { animation-delay: 0.2s; }
  .animate-delay-3 { animation-delay: 0.3s; }
</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Page Header -->
    <div class="page-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">My Announcements</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Announcements</li>
                <li class="breadcrumb-item active" aria-current="page">List</li>
              </ol>
            </nav>
          </div>
        </div>
        <div class="ms-auto">
          <div class="stats-card">
            <span class="stats-number">{{ count($class) }}</span>
            <div class="stats-label">Total Announcements</div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Search Section -->
    <div class="search-container animate-fadeInUp animate-delay-1">
      <div class="row align-items-center">
        <div class="col-md-8">
          <div class="form-group mb-0">
            <label class="form-label text-muted mb-2">
              <i class="mdi mdi-magnify me-2"></i>
              Search Announcements
            </label>
            <div class="input-group">
              <input id="search-txt" placeholder="Search by content, group, or chapter..." type="text" class="form-control search-input" autocomplete="off">
              <span class="input-group-text bg-primary text-white">
                <i class="mdi mdi-magnify"></i>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-4 text-end">
          <a href="/lecturer/class/announcement" class="btn btn-primary btn-lg">
            <i class="mdi mdi-plus me-2"></i>
            Create New
          </a>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Announcements List -->
        <div class="row" id="announcements-container">
          @if(count($class) < 1)
          <div class="col-12">
            <div class="empty-state animate-fadeInUp">
              <i class="mdi mdi-bullhorn-outline empty-state-icon"></i>
              <h3 class="text-muted mb-3">No Announcements Found</h3>
              <p class="text-muted mb-4">You haven't created any announcements yet. Start engaging with your students!</p>
              <a href="/lecturer/class/announcement" class="btn btn-primary btn-lg">
                <i class="mdi mdi-plus me-2"></i>
                Create Your First Announcement
              </a>
            </div>
          </div>
          @else
          
          @php $i = 1; @endphp
          @foreach($class as $key => $cls)
          <div class="col-lg-6 announcement-item animate-fadeInUp" style="animation-delay: {{ 0.1 * ($key + 1) }}s;">
            <div class="announcement-card">
              <div class="announcement-header">
                <div class="row align-items-center position-relative" style="z-index: 1;">
                  <div class="col">
                    <h5 class="mb-1">
                      <i class="mdi mdi-bullhorn me-2"></i>
                      Announcement #{{ $cls->id }}
                    </h5>
                    <p class="mb-0 opacity-75">
                      <i class="mdi mdi-calendar me-1"></i>
                      {{ $cls->classdate ?? 'Recently created' }}
                    </p>
                  </div>
                  <div class="col-auto">
                    <div class="dropdown">
                      <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="dropdown">
                        <i class="mdi mdi-dots-vertical"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a href="/lecturer/class/announcement/list/edit/{{ $cls->id }}" class="dropdown-item">
                          <i class="mdi mdi-pencil me-2"></i>Edit
                        </a>
                        <a href="javascript:void(0);" onclick="deleteAnnouncement('{{ $cls->id }}')" class="dropdown-item text-danger">
                          <i class="mdi mdi-delete me-2"></i>Delete
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="announcement-body">
                <!-- Target Groups -->
                <div class="mb-3">
                  <h6 class="text-muted mb-2">
                    <i class="mdi mdi-account-group me-1"></i>
                    Target Groups
                  </h6>
                  @if(isset($allgroup[$key]) && count($allgroup[$key]) > 0)
                    @foreach($allgroup[$key] as $group)
                    <span class="group-badge">{{ $group->group_name }}</span>
                    @endforeach
                  @else
                    <span class="text-muted">No specific groups</span>
                  @endif
                </div>

                <!-- Chapters -->
                @if(isset($chapters[$key]) && count($chapters[$key]) > 0)
                <div class="mb-3">
                  <h6 class="text-muted mb-2">
                    <i class="mdi mdi-book-open-page-variant me-1"></i>
                    Related Chapters
                  </h6>
                  @foreach ($chapters[$key] as $chp)
                  <span class="chapter-badge">
                    Ch.{{ $chp->SubChapterNo ?? 'N/A' }}: {{ strtoupper($chp->DrName) }}
                  </span>
                  @endforeach
                </div>
                @endif

                <!-- Content -->
                <div class="mb-3">
                  <h6 class="text-muted mb-2">
                    <i class="mdi mdi-text-box me-1"></i>
                    Content
                  </h6>
                  <div class="announcement-content">
                    {!! $cls->classdescription !!}
                  </div>
                </div>

                <!-- Class Link -->
                @if($cls->classlink)
                <div class="mb-3">
                  <h6 class="text-muted mb-2">
                    <i class="mdi mdi-link-variant me-1"></i>
                    Class Link
                  </h6>
                  <a href="{{ $cls->classlink }}" target="_blank" class="announcement-link">
                    <i class="mdi mdi-open-in-new"></i>
                    Join Online Class
                  </a>
                </div>
                @endif
              </div>

              <div class="announcement-footer">
                <div class="row align-items-center">
                  <div class="col">
                    <small class="text-muted">
                      <i class="mdi mdi-account-group me-1"></i>
                      {{ $totalstd[$key] ?? 0 }} students in target groups
                    </small>
                  </div>
                  <div class="col-auto">
                    <span class="badge bg-success">
                      <i class="mdi mdi-check me-1"></i>
                      Published
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
          
          @endif
        </div>

        <!-- Pagination -->
        @if(!empty($class) && $class->hasPages())
        <div class="pagination animate-fadeInUp" style="animation-delay: 0.5s;">
          {{ $class->links('vendor.pagination.bootstrap-5') }}
        </div>
        @endif
      </div>
    </section>
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>
<script type="text/javascript">

$(document).ready(function() {
    // Search functionality
    $('#search-txt').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.announcement-item').each(function() {
            const cardText = $(this).text().toLowerCase();
            if (cardText.includes(searchTerm)) {
                $(this).show().addClass('animate-fadeInUp');
            } else {
                $(this).hide();
            }
        });
    });
    
    // Add smooth scroll for pagination
    $('.pagination a').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            $('body').addClass('loading');
            window.location.href = url;
        }
    });
});

function deleteAnnouncement(id) {     
    Swal.fire({
        title: "Delete Announcement?",
        text: "This action cannot be undone",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff6b6b',
        cancelButtonColor: '#6c757d',
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        customClass: {
            popup: 'rounded-3',
            confirmButton: 'rounded-pill',
            cancelButton: 'rounded-pill'
        }
    }).then(function(res){
        if (res.isConfirmed){
            // Show loading state
            Swal.fire({
                title: 'Deleting...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            
            $.ajax({
                headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                url      : "{{ url('lecturer/class/announcement/list/delete') }}",
                method   : 'DELETE',
                data     : {id:id},
                error:function(err){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again.',
                        confirmButtonColor: '#667eea'
                    });
                },
                success  : function(data){
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Announcement has been deleted successfully.',
                        confirmButtonColor: '#667eea',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        }
    });
}

</script>
@endsection
