@extends('layouts.ketua_program')

@section('main')

<style>
  .applications-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .application-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
  }
  
  .application-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
  }
  
  .application-header {
    background: #667eea;
    color: white;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
  }
  
  .application-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
  }
  
  .application-body {
    padding: 1.5rem;
  }
  
  .application-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
  }
  
  .status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
  }
  
  .status-approved {
    background: #d4edda;
    color: #155724;
    border: 1px solid #00b894;
  }
  
  .status-rejected {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #e74c3c;
  }
  
  .program-badge {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    margin-right: 0.5rem;
    margin-bottom: 0.25rem;
    display: inline-block;
  }
  
  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
  }
  
  .info-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    border-left: 4px solid #667eea;
  }
  
  .info-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
  }
  
  .info-content {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.4;
  }
  
  .kp-verification {
    background: rgba(102, 126, 234, 0.05);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 10px;
    padding: 1.25rem;
    margin-top: 1rem;
  }
  
  .rejection-details {
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
  }

  .filter-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }

  .search-input {
    border: 2px solid #e9ecef;
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
  }
  
  .search-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .stats-row {
    margin-bottom: 2rem;
  }

  .stats-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    border: none;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
  }

  .stats-number {
    font-size: 2.5rem;
    font-weight: bold;
    display: block;
  }

  .stats-label {
    font-size: 0.9rem;
    margin-top: 0.5rem;
    opacity: 0.9;
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


</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Page Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">All Replacement Class Applications</h3>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('ketua_program') }}"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Replacement Class</li>
                <li class="breadcrumb-item active" aria-current="page">All Applications</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="row mb-3">
        <div class="col-12">
          <div class="d-flex justify-content-end">
            <a href="{{ route('kp.replacement_class.pending') }}" class="btn btn-primary">
              <i class="mdi mdi-clock me-1"></i>
              Pending Applications
            </a>
          </div>
        </div>
      </div>
      
      <div class="applications-container">
        
        @if(isset($message))
          <div class="row">
            <div class="col-12">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="mdi mdi-information-outline"></i>
                </div>
                <h4 class="text-muted">{{ $message }}</h4>
              </div>
            </div>
          </div>
        @elseif($applications->isEmpty())
          <div class="row">
            <div class="col-12">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="mdi mdi-clipboard-list-outline"></i>
                </div>
                <h4 class="text-muted">No applications found</h4>
                <p class="text-muted">No replacement class applications have been submitted yet.</p>
              </div>
            </div>
          </div>
        @else
          <!-- Statistics Row -->
          <div class="row stats-row">
            <div class="col-md-3">
              <div class="stats-card">
                <span class="stats-number">{{ $applications->count() }}</span>
                <div class="stats-label">
                  <i class="mdi mdi-clipboard-list me-1"></i>
                  Total Applications
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <span class="stats-number">{{ $applications->where('is_verified', 'YES')->count() }}</span>
                <div class="stats-label">
                  <i class="mdi mdi-check me-1"></i>
                  Approved
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stats-card" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                <span class="stats-number">{{ $applications->where('is_verified', 'PENDING')->count() }}</span>
                <div class="stats-label">
                  <i class="mdi mdi-clock me-1"></i>
                  Pending
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stats-card" style="background: linear-gradient(135deg, #dc3545, #e74c3c);">
                <span class="stats-number">{{ $applications->where('is_verified', 'NO')->count() }}</span>
                <div class="stats-label">
                  <i class="mdi mdi-close me-1"></i>
                  Rejected
                </div>
              </div>
            </div>
          </div>

          <!-- Filter Section -->
          <div class="filter-section">
            <div class="row">
              <div class="col-md-8">
                <div class="input-group">
                  <input type="text" class="form-control search-input" 
                         placeholder="Search by lecturer name, course code, or application ID..."
                         id="searchInput" onkeyup="filterApplications()">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="button" style="border-radius: 0 25px 25px 0;">
                      <i class="mdi mdi-magnify"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <select class="form-select" id="statusFilter" onchange="filterApplications()" 
                        style="border-radius: 25px; padding: 0.75rem 1.5rem;">
                  <option value="">All Status</option>
                  <option value="PENDING">Pending</option>
                  <option value="YES">Approved</option>
                  <option value="NO">Rejected</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Applications List -->
          <div class="row" id="applicationsContainer">
            @foreach($applications as $key => $app)
            <div class="col-lg-6 application-item animate-fadeInUp" 
                 style="animation-delay: {{ 0.1 * ($key + 1) }}s;"
                 data-lecturer="{{ strtolower($app->lecturer_name) }}"
                 data-course="{{ strtolower($app->course_code) }}"
                 data-id="{{ $app->id }}"
                 data-status="{{ $app->is_verified }}">
              <div class="application-card">
                <div class="application-header">
                  <div class="row align-items-center position-relative" style="z-index: 1;">
                    <div class="col">
                      <h5 class="mb-1">
                        <i class="mdi mdi-swap-horizontal me-2"></i>
                        Application #{{ $app->id }}
                      </h5>
                      <p class="mb-0 opacity-75">
                        <i class="mdi mdi-calendar me-1"></i>
                        Submitted: {{ \Carbon\Carbon::parse($app->created_at)->format('d M Y, g:i A') }}
                      </p>
                    </div>
                    <div class="col-auto">
                      <span class="status-badge status-{{ strtolower($app->is_verified) === 'pending' ? 'pending' : (strtolower($app->is_verified) === 'yes' ? 'approved' : 'rejected') }}">
                        @if(strtolower($app->is_verified) === 'pending')
                          <i class="mdi mdi-clock-outline me-1"></i>Pending
                        @elseif(strtolower($app->is_verified) === 'yes')
                          <i class="mdi mdi-check me-1"></i>Approved
                        @else
                          <i class="mdi mdi-close me-1"></i>Rejected
                        @endif
                      </span>
                    </div>
                  </div>
                </div>
                
                <div class="application-body">
                  <!-- Lecturer & Course Info -->
                  <div class="mb-3">
                    <h6 class="text-muted mb-2">
                      <i class="mdi mdi-account-tie me-1"></i>
                      Lecturer & Course
                    </h6>
                    <p class="mb-1"><strong>{{ $app->lecturer_name }}</strong></p>
                    <p class="mb-1"><strong>{{ $app->course_code }}</strong> - {{ $app->course_name }}</p>
                    <p class="mb-1"><strong>Session:</strong> {{ $app->SessionName }}</p>
                    <p class="mb-1"><strong>Group:</strong> {{ $app->group_name }}</p>
                    <div>
                      <strong>Programs:</strong>
                      @if(is_array($app->programs))
                        @foreach($app->programs as $program)
                          <span class="program-badge">{{ $program }}</span>
                        @endforeach
                      @endif
                    </div>
                  </div>

                  <!-- Grid Layout for Key Information -->
                  <div class="info-grid">
                    <!-- Cancellation Details -->
                    <div class="info-section">
                      <div class="info-title">
                        <i class="mdi mdi-calendar-remove me-2"></i>
                        Cancelled Class
                      </div>
                      <div class="info-content">
                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($app->tarikh_kuliah_dibatalkan)->format('d M Y') }}<br>
                        <strong>Reason:</strong> {{ $app->sebab_kuliah_dibatalkan }}
                      </div>
                    </div>

                    <!-- Replacement Details -->
                    <div class="info-section">
                      <div class="info-title">
                        <i class="mdi mdi-calendar-plus me-2"></i>
                        Replacement Class
                      </div>
                      <div class="info-content">
                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($app->maklumat_kuliah_gantian_tarikh)->format('d M Y') }}<br>
                        <strong>Time:</strong> {{ $app->maklumat_kuliah_gantian_hari_masa }}<br>
                        <strong>Venue:</strong> {{ $app->room_name }}
                      </div>
                    </div>
                  </div>

                  <!-- Student Representative -->
                  <div class="mb-3">
                    <h6 class="text-muted mb-2">
                      <i class="mdi mdi-account me-1"></i>
                      Student Representative
                    </h6>
                    <p class="mb-0">
                      <strong>{{ $app->wakil_pelajar_nama }}</strong><br>
                      <i class="mdi mdi-phone me-1"></i>{{ $app->wakil_pelajar_no_tel }}
                    </p>
                  </div>

                  <!-- Additional Information -->
                  @if($app->maklumat_kuliah)
                  <div class="mb-3">
                    <h6 class="text-muted mb-2">
                      <i class="mdi mdi-information me-1"></i>
                      Additional Information
                    </h6>
                    <div style="background: white; border-radius: 8px; padding: 1rem; border: 1px solid #e9ecef;">
                      {{ $app->maklumat_kuliah }}
                    </div>
                  </div>
                  @endif

                  <!-- KP Verification Info -->
                  @if($app->is_verified !== 'PENDING')
                  <div class="kp-verification">
                    <h6 class="text-muted mb-2">
                      <i class="mdi mdi-account-check me-1"></i>
                      Verification Details
                    </h6>
                    @if($app->kp_name)
                      <p class="mb-1">
                        <strong>Verified by:</strong> {{ $app->kp_name }}<br>
                        <i class="mdi mdi-email me-1"></i>{{ $app->kp_email }}
                      </p>
                    @else
                      <p class="mb-1"><em>KP information not available</em></p>
                    @endif
                    
                    @if($app->is_verified === 'NO')
                      <div class="rejection-details">
                        @if($app->next_date)
                        <p class="mb-1 text-danger">
                          <strong><i class="mdi mdi-calendar-alert me-1"></i>Next Available Date:</strong> 
                          {{ \Carbon\Carbon::parse($app->next_date)->format('d M Y') }}
                        </p>
                        @endif
                        @if($app->rejection_reason)
                        <p class="mb-0 text-danger">
                          <strong><i class="mdi mdi-comment-alert me-1"></i>Rejection Reason:</strong> 
                          {{ $app->rejection_reason }}
                        </p>
                        @endif
                      </div>
                    @endif
                  </div>
                  @endif
                </div>

                <div class="application-footer">
                  <div class="row align-items-center">
                    <div class="col">
                      <small class="text-muted">
                        <i class="mdi mdi-clock me-1"></i>
                        Last updated: {{ \Carbon\Carbon::parse($app->updated_at)->diffForHumans() }}
                      </small>
                    </div>
                    <div class="col-auto">
                      @if($app->is_verified === 'PENDING')
                        <span class="badge bg-warning">
                          <i class="mdi mdi-clock me-1"></i>
                          Awaiting Review
                        </span>
                      @elseif($app->is_verified === 'YES')
                        <span class="badge bg-success">
                          <i class="mdi mdi-check me-1"></i>
                          Approved
                        </span>
                      @else
                        <span class="badge bg-danger">
                          <i class="mdi mdi-close me-1"></i>
                          Rejected
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        @endif
      </div>
    </section>
  </div>
</div>

<script>
function filterApplications() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const applications = document.querySelectorAll('.application-item');
    
    applications.forEach(function(app) {
        const lecturer = app.getAttribute('data-lecturer');
        const course = app.getAttribute('data-course');
        const id = app.getAttribute('data-id');
        const status = app.getAttribute('data-status');
        
        const matchesSearch = lecturer.includes(searchTerm) || 
                             course.includes(searchTerm) || 
                             id.includes(searchTerm);
        
        const matchesStatus = statusFilter === '' || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            app.style.display = 'block';
        } else {
            app.style.display = 'none';
        }
    });
}
</script>

@endsection
