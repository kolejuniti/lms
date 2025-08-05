@extends('layouts.student.student')

@section('main')

<style>
  .student-announcements-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .hero-section {
    background: #ffffff;
    border-radius: 15px;
    padding: 3rem 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    text-align: center;
  }
  
  .announcement-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid #e9ecef;
    position: relative;
  }
  
  .announcement-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
  }
  
  .announcement-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #667eea, #764ba2, #56ab2f);
    z-index: 1;
  }
  
  .announcement-header {
    background: #667eea;
    color: white;
    padding: 2rem;
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
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
    opacity: 0.6;
  }
  
  .announcement-body {
    padding: 2rem;
  }
  
  .announcement-footer {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 1px solid #e9ecef;
  }
  
  .priority-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 2;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .priority-high {
    background: linear-gradient(45deg, #ff6b6b, #ee5a52);
    color: white;
    animation: pulse 2s infinite;
  }
  
  .priority-medium {
    background: linear-gradient(45deg, #feca57, #ff9ff3);
    color: white;
  }
  
  .priority-low {
    background: linear-gradient(45deg, #48dbfb, #0abde3);
    color: white;
  }
  
  @keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(255, 107, 107, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0); }
  }
  
  .new-badge {
    background: linear-gradient(45deg, #ff6b6b, #ee5a52);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
    animation: bounce 2s infinite;
  }
  
  @keyframes bounce {
    0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
    40%, 43% { transform: translate3d(0,-8px,0); }
    70% { transform: translate3d(0,-4px,0); }
    90% { transform: translate3d(0,-2px,0); }
  }
  
  .group-badge {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .chapter-badge {
    background: linear-gradient(45deg, #56ab2f, #a8e6cf);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    margin-right: 0.5rem;
    margin-bottom: 0.25rem;
    display: inline-block;
  }
  
  .announcement-content {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 15px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
    border-left: 4px solid #667eea;
    position: relative;
    overflow: hidden;
  }
  
  .announcement-content::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
    transform: translate(30px, -30px);
  }
  
  .class-link-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 15px;
    margin: 1rem 0;
    text-decoration: none;
    display: block;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }
  
  .class-link-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    color: white;
    text-decoration: none;
  }
  
  .class-link-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: all 0.5s;
  }
  
  .class-link-card:hover::before {
    left: 100%;
  }
  
  .stats-row {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 1rem;
    margin: 1rem 0;
    backdrop-filter: blur(10px);
  }
  
  .search-container {
    background: #ffffff;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
  }
  
  .search-input {
    border: 3px solid transparent;
    border-radius: 25px;
    padding: 1rem 1.5rem;
    font-size: 1.1rem;
    background: linear-gradient(white, white) padding-box,
                linear-gradient(135deg, #667eea, #764ba2) border-box;
    transition: all 0.3s ease;
  }
  
  .search-input:focus {
    outline: none;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
  }
  
  .filter-chips {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 1rem;
  }
  
  .filter-chip {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    border: 2px solid rgba(102, 126, 234, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
  }
  
  .filter-chip:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
  }
  
  .filter-chip.active {
    background: #667eea;
    color: white;
  }
  
  .empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 25px;
    backdrop-filter: blur(15px);
  }
  
  .empty-state-icon {
    font-size: 5rem;
    color: #6c757d;
    margin-bottom: 2rem;
    animation: float 3s ease-in-out infinite;
  }
  
  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
  }
  
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(50px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .animate-fadeInUp {
    animation: fadeInUp 0.8s ease-out;
  }
  
  .animate-delay-1 { animation-delay: 0.2s; }
  .animate-delay-2 { animation-delay: 0.4s; }
  .animate-delay-3 { animation-delay: 0.6s; }
  
  .notification-indicator {
    position: relative;
  }
  
  .notification-indicator::after {
    content: '';
    position: absolute;
    top: -5px;
    right: -5px;
    width: 12px;
    height: 12px;
    background: #ff6b6b;
    border-radius: 50%;
    border: 2px solid white;
    animation: pulse 2s infinite;
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Class Announcements</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Announcements</li>
                <li class="breadcrumb-item active" aria-current="page">View All</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Search and Filter Section -->
    <div class="search-container animate-fadeInUp animate-delay-1">
      <div class="row">
        <div class="col-12">
          <div class="form-group mb-3">
            <label class="form-label text-muted mb-3 d-flex align-items-center">
              <i class="mdi mdi-magnify me-2"></i>
              <span class="fs-5 fw-bold">Search Announcements</span>
            </label>
            <div class="input-group">
              <input id="search-txt" placeholder="Search by content, lecturer, or subject..." type="text" class="form-control search-input" autocomplete="off">
              <span class="input-group-text bg-primary text-white">
                <i class="mdi mdi-magnify"></i>
              </span>
            </div>
          </div>
          
          <!-- Filter Chips -->
          <div class="filter-chips">
            <div class="filter-chip active" data-filter="all">
              <i class="mdi mdi-view-grid me-1"></i>
              All Announcements
            </div>
            <div class="filter-chip" data-filter="recent">
              <i class="mdi mdi-clock me-1"></i>
              Recent
            </div>
            <div class="filter-chip" data-filter="important">
              <i class="mdi mdi-star me-1"></i>
              Important
            </div>
            <div class="filter-chip" data-filter="with-links">
              <i class="mdi mdi-link me-1"></i>
              With Class Links
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Announcements Grid -->
        <div class="row" id="announcements-container">
          @if(count($class) < 1)
          <div class="col-12">
            <div class="empty-state animate-fadeInUp">
              <i class="mdi mdi-bell-outline empty-state-icon"></i>
              <h3 class="text-muted mb-3">No Announcements Yet</h3>
              <p class="text-muted mb-4 lead">
                Your lecturers haven't posted any announcements yet.<br>
                Check back later for important updates and class information.
              </p>
              <div class="mt-4">
                <button class="btn btn-primary btn-lg" onclick="window.location.reload()">
                  <i class="mdi mdi-refresh me-2"></i>
                  Refresh Page
                </button>
              </div>
            </div>
          </div>
          @else

          @php $i = 1; @endphp
          @foreach($class as $key => $cls)
          @php
            // Simulate priority and new status for demo
            $priorities = ['high', 'medium', 'low'];
            $priority = $priorities[rand(0, 2)];
            $isNew = $key < 2; // First 2 announcements are "new"
          @endphp
          
          <div class="col-xl-6 announcement-item animate-fadeInUp" 
               style="animation-delay: {{ 0.2 * ($key + 1) }}s;"
               data-priority="{{ $priority }}"
               data-has-link="{{ $cls->classlink ? 'true' : 'false' }}">
            <div class="announcement-card">
              <!-- Priority Badge -->
              <div class="priority-badge priority-{{ $priority }}">
                {{ strtoupper($priority) }} Priority
              </div>
              
              <div class="announcement-header">
                <div class="position-relative" style="z-index: 1;">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h4 class="mb-0">
                      <i class="mdi mdi-bullhorn notification-indicator me-2"></i>
                      Class Announcement
                      @if($isNew)
                      <span class="new-badge">NEW</span>
                      @endif
                    </h4>
                  </div>
                  <p class="mb-0 opacity-75">
                    <i class="mdi mdi-calendar me-1"></i>
                    {{ $cls->classdate ?? 'Recently Posted' }}
                  </p>
                </div>
              </div>
              
              <div class="announcement-body">
                <!-- Target Groups -->
                <div class="mb-4">
                  <h6 class="text-muted mb-2 d-flex align-items-center">
                    <i class="mdi mdi-account-group me-2"></i>
                    Your Groups
                  </h6>
                  @if(isset($chapters[$key]) && count($chapters[$key]) > 0)
                    @foreach($chapters[$key] as $group)
                    <span class="group-badge">
                      <i class="mdi mdi-account-circle"></i>
                      Group {{ $group->SubChapterNo ?? 'N/A' }}
                    </span>
                    @endforeach
                  @else
                    <span class="group-badge">
                      <i class="mdi mdi-account-circle"></i>
                      All Students
                    </span>
                  @endif
                </div>

                <!-- Related Chapters -->
                @if(isset($chapters[$key]) && count($chapters[$key]) > 0)
                <div class="mb-4">
                  <h6 class="text-muted mb-2 d-flex align-items-center">
                    <i class="mdi mdi-book-open-page-variant me-2"></i>
                    Related Chapters
                  </h6>
                  @foreach ($chapters[$key] as $chp)
                  <span class="chapter-badge">
                    Ch.{{ $chp->SubChapterNo ?? 'N/A' }}: {{ strtoupper($chp->DrName) }}
                  </span>
                  @endforeach
                </div>
                @endif

                <!-- Announcement Content -->
                <div class="mb-4">
                  <h6 class="text-muted mb-3 d-flex align-items-center">
                    <i class="mdi mdi-message-text me-2"></i>
                    Message
                  </h6>
                  <div class="announcement-content">
                    {!! $cls->classdescription !!}
                  </div>
                </div>

                <!-- Class Link -->
                @if($cls->classlink)
                <div class="mb-3">
                  <h6 class="text-muted mb-2 d-flex align-items-center">
                    <i class="mdi mdi-video me-2"></i>
                    Join Online Class
                  </h6>
                  <a href="{{ $cls->classlink }}" target="_blank" class="class-link-card">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="mb-1">🎥 Online Class Session</h6>
                        <small class="opacity-75">Click to join the live session</small>
                      </div>
                      <i class="mdi mdi-arrow-right fs-4"></i>
                    </div>
                  </a>
                </div>
                @endif
              </div>

              <div class="announcement-footer">
                <div class="stats-row">
                  <div class="row align-items-center">
                    <div class="col">
                      <small class="text-muted d-flex align-items-center">
                        <i class="mdi mdi-account-group me-1"></i>
                        {{ $totalstd[$key] ?? 0 }} students in this announcement
                      </small>
                    </div>
                    <div class="col-auto">
                      <div class="d-flex gap-2">
                        @if($cls->classlink)
                        <span class="badge bg-success">
                          <i class="mdi mdi-video me-1"></i>
                          Has Class Link
                        </span>
                        @endif
                        <span class="badge bg-primary">
                          <i class="mdi mdi-bell me-1"></i>
                          Active
                        </span>
                      </div>
                    </div>
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
        <div class="d-flex justify-content-center mt-4">
          <div class="pagination animate-fadeInUp" style="animation-delay: 0.8s;">
            {{ $class->links('vendor.pagination.bootstrap-5') }}
          </div>
        </div>
        @endif
      </div>
    </section>
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>
<script type="text/javascript">

$(document).ready(function() {
    // Enhanced search functionality
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
        
        // Show "no results" message if no announcements are visible
        const visibleCards = $('.announcement-item:visible').length;
        if (visibleCards === 0 && searchTerm.length > 0) {
            if ($('#no-results').length === 0) {
                $('#announcements-container').append(`
                    <div class="col-12" id="no-results">
                        <div class="empty-state">
                            <i class="mdi mdi-magnify empty-state-icon"></i>
                            <h4 class="text-muted mb-3">No announcements found</h4>
                            <p class="text-muted">Try adjusting your search terms</p>
                        </div>
                    </div>
                `);
            }
        } else {
            $('#no-results').remove();
        }
    });
    
    // Filter functionality
    $('.filter-chip').on('click', function() {
        const filter = $(this).data('filter');
        
        // Update active state
        $('.filter-chip').removeClass('active');
        $(this).addClass('active');
        
        // Apply filter
        $('.announcement-item').each(function() {
            let show = true;
            
            switch(filter) {
                case 'all':
                    show = true;
                    break;
                case 'recent':
                    // Show first 3 items as "recent"
                    show = $(this).index() < 3;
                    break;
                case 'important':
                    show = $(this).data('priority') === 'high';
                    break;
                case 'with-links':
                    show = $(this).data('has-link') === 'true';
                    break;
            }
            
            if (show) {
                $(this).show().addClass('animate-fadeInUp');
            } else {
                $(this).hide();
            }
        });
    });
    
    // Smooth scroll for pagination
    $('.pagination a').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            $('body').addClass('loading');
            window.location.href = url;
        }
    });
    
    // Add notification sound effect (optional)
    if ($('.new-badge').length > 0) {
        // You could add a subtle notification sound here
        // playNotificationSound();
    }
    
    // Auto-refresh every 5 minutes to check for new announcements
    setInterval(function() {
        // You could implement auto-refresh logic here
        // or show a notification when new announcements are available
    }, 300000); // 5 minutes
});

// Optional: Add a notification sound function
function playNotificationSound() {
    // Create audio context for a subtle notification sound
    if (typeof(Audio) !== "undefined") {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 800;
            oscillator.type = "sine";
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        } catch(e) {
            // Silently fail if audio is not supported
        }
    }
}

</script>
@endsection
