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

  .action-buttons {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
  }

  .btn-approve {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    color: white;
  }

  .btn-reject {
    background: linear-gradient(45deg, #dc3545, #e74c3c);
    border: none;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    color: white;
  }

  .modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
  }

  .modal-header {
    background: linear-gradient(45deg, #dc3545, #e74c3c);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
  }

  .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
          <h3 class="page-title">Pending Replacement Class Applications</h3>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('ketua_program') }}"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Replacement Class</li>
                <li class="breadcrumb-item active" aria-current="page">Pending Applications</li>
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
            <a href="{{ route('kp.replacement_class.all') }}" class="btn btn-primary">
              <i class="mdi mdi-view-list me-1"></i>
              View All Applications
            </a>
          </div>
        </div>
      </div>
      
      <div class="applications-container">
        <div class="row">
          @if(isset($message))
            <div class="col-12">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="mdi mdi-information-outline"></i>
                </div>
                <h4 class="text-muted">{{ $message }}</h4>
              </div>
            </div>
          @elseif($applications->isEmpty())
            <div class="col-12">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="mdi mdi-clipboard-check-outline"></i>
                </div>
                <h4 class="text-muted">No pending applications</h4>
                <p class="text-muted">All replacement class applications have been reviewed.</p>
              </div>
            </div>
          @else
            @foreach($applications as $key => $app)
            <div class="col-lg-6 application-item animate-fadeInUp" style="animation-delay: {{ 0.1 * ($key + 1) }}s;">
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
                      <span class="status-badge status-pending">
                        <i class="mdi mdi-clock-outline me-1"></i>Pending Review
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

                  <!-- Action Buttons -->
                  <div class="action-buttons">
                    <button type="button" class="btn btn-approve" onclick="approveApplication({{ $app->id }})">
                      <i class="mdi mdi-check me-1"></i>
                      Approve
                    </button>
                    <button type="button" class="btn btn-reject" onclick="rejectApplication({{ $app->id }})">
                      <i class="mdi mdi-close me-1"></i>
                      Reject
                    </button>
                  </div>
                </div>

                <div class="application-footer">
                  <div class="row align-items-center">
                    <div class="col">
                      <small class="text-muted">
                        <i class="mdi mdi-clock me-1"></i>
                        Last updated: {{ \Carbon\Carbon::parse($app->updated_at)->diffForHumans() }}
                      </small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          @endif
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectionModalLabel">
          <i class="mdi mdi-close-circle me-2"></i>
          Reject Application
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="rejectionForm">
          <input type="hidden" id="rejectionApplicationId" name="application_id">
          <input type="hidden" name="status" value="NO">
          
          <div class="mb-3">
            <label for="rejectionReason" class="form-label">
              <i class="mdi mdi-comment-text me-1"></i>
              Reason for Rejection <span class="text-danger">*</span>
            </label>
            <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="4" 
                      placeholder="Please provide a detailed reason for rejecting this application..." required></textarea>
          </div>
          
          <div class="mb-3">
            <label for="nextDate" class="form-label">
              <i class="mdi mdi-calendar me-1"></i>
              Suggested Next Available Date
            </label>
            <input type="date" class="form-control" id="nextDate" name="next_date" 
                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            <small class="form-text text-muted">Optional: Suggest an alternative date for the replacement class.</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="mdi mdi-cancel me-1"></i>
          Cancel
        </button>
        <button type="button" class="btn btn-danger" onclick="submitRejection()">
          <i class="mdi mdi-close me-1"></i>
          Reject Application
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function approveApplication(applicationId) {
    Swal.fire({
        title: 'Approve Application?',
        text: 'Are you sure you want to approve this replacement class application?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="mdi mdi-check"></i> Yes, Approve',
        cancelButtonText: '<i class="mdi mdi-cancel"></i> Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateApplicationStatus(applicationId, 'YES');
        }
    });
}

function rejectApplication(applicationId) {
    $('#rejectionApplicationId').val(applicationId);
    $('#rejectionModal').modal('show');
}

function submitRejection() {
    const form = document.getElementById('rejectionForm');
    const formData = new FormData(form);
    
    if (!formData.get('rejection_reason').trim()) {
        Swal.fire({
            title: 'Validation Error',
            text: 'Please provide a reason for rejection.',
            icon: 'error'
        });
        return;
    }
    
    const applicationId = formData.get('application_id');
    updateApplicationStatus(applicationId, 'NO', {
        rejection_reason: formData.get('rejection_reason'),
        next_date: formData.get('next_date')
    });
}

function updateApplicationStatus(applicationId, status, additionalData = {}) {
    const data = {
        application_id: applicationId,
        status: status,
        _token: '{{ csrf_token() }}',
        ...additionalData
    };
    
    $.ajax({
        url: '{{ route("kp.replacement_class.update_status") }}',
        method: 'POST',
        data: data,
        success: function(response) {
            if (response.success) {
                $('#rejectionModal').modal('hide');
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            }
        },
        error: function(xhr) {
            $('#rejectionModal').modal('hide');
            let errorMessage = 'An error occurred while updating the application status.';
            
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
            }
            
            Swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error'
            });
        }
    });
}

// Clear form when modal is hidden
$('#rejectionModal').on('hidden.bs.modal', function () {
    $('#rejectionForm')[0].reset();
});
</script>

@endsection
