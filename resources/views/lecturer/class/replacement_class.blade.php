@extends('layouts.lecturer.lecturer')

@section('main')

<style>
  .form-container {
    background: var(--bs-body-bg, #f8f9fa);
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .dark-skin .form-container {
    background: #171e32;
  }
  
  .form-header {
    background: var(--bs-card-bg, #ffffff);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--bs-border-color, #e9ecef);
  }
  
  .dark-skin .form-header {
    background: #293146;
    border-color: #3c3d54;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
  }
  
  .form-step {
    background: var(--bs-card-bg, #ffffff);
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid var(--bs-border-color, #e9ecef);
  }
  
  .dark-skin .form-step {
    background: #293146;
    border-color: #3c3d54;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  }
  
  .form-step:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
  }
  
  .step-header {
    background: #667eea;
    color: white;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
  }
  
  .step-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
  }
  
  .step-number {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 1rem;
    font-size: 1.2rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    position: relative;
    z-index: 1;
  }
  
  .step-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    position: relative;
    z-index: 1;
  }
  
  .form-body {
    padding: 1.5rem;
  }
  
  .modern-form-group {
    margin-bottom: 1.5rem;
    position: relative;
  }

  .modern-label {
    color: var(--bs-body-color, #555);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
  }

  .dark-skin .modern-label {
    color: rgba(255, 255, 255, 0.85);
  }

  .modern-input {
    border: 2px solid var(--bs-border-color, #e9ecef);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: var(--bs-tertiary-bg, #fafbfc);
    color: var(--bs-body-color, #495057);
  }

  .dark-skin .modern-input {
    background: #191d33;
    border-color: #3c3d54;
    color: #a1a4b5;
  }

  .modern-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    background: var(--bs-card-bg, white);
    transform: translateY(-1px);
  }

  .dark-skin .modern-input:focus {
    background: #293146;
  }

  .modern-select {
    border: 2px solid var(--bs-border-color, #e9ecef);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: var(--bs-tertiary-bg, #fafbfc);
    color: var(--bs-body-color, #495057);
  }

  .dark-skin .modern-select {
    background: #191d33;
    border-color: #3c3d54;
    color: #a1a4b5;
  }

  .modern-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    background: var(--bs-card-bg, white);
    transform: translateY(-1px);
  }

  .dark-skin .modern-select:focus {
    background: #293146;
  }

  .modern-textarea {
    border: 2px solid var(--bs-border-color, #e9ecef);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: var(--bs-tertiary-bg, #fafbfc);
    color: var(--bs-body-color, #495057);
    resize: vertical;
    min-height: 100px;
  }

  .dark-skin .modern-textarea {
    background: #191d33;
    border-color: #3c3d54;
    color: #a1a4b5;
  }

  .modern-textarea:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    background: var(--bs-card-bg, white);
    transform: translateY(-1px);
  }

  .dark-skin .modern-textarea:focus {
    background: #293146;
  }

  .tag-auto {
    background: #e6f3ff;
    color: #0066cc;
    border: 1px solid #b3d9ff;
    padding: 0.25rem 0.75rem;
    font-size: 0.8rem;
    font-weight: 500;
    border-radius: 20px;
    margin-left: 0.5rem;
  }

  .submit-container {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    text-align: center;
    margin-top: 2rem;
  }
  
  .submit-btn {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 1rem 3rem;
    font-weight: 500;
    font-size: 1.1rem;
    transition: all 0.3s ease;
  }
  
  .submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    color: white;
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
  .animate-delay-4 { animation-delay: 0.4s; }

  @media (max-width: 768px) {
    .form-step {
        margin-bottom: 1.5rem;
    }
    
    .step-header {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }
    
    .step-number {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
    
    .form-body {
        padding: 1rem;
    }
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    @if(session()->has('message'))
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-success" style="border-radius: 12px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <i class="fa fa-check-circle me-2"></i>
            <span>{{ session()->get('message') }}</span>
          </div>
        </div>
      </div>
    </div>
    @endif
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Replacement Class Application</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Class</li>
                <li class="breadcrumb-item active" aria-current="page">Replacement Class</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        @if (session('error'))
          <script>
            alert("{{ session('error') }}");
          </script>
        @endif
        
        <!-- Modern Form -->
        <form action="{{ route('lecturer.replacement_class.store') }}" method="POST">
          @csrf
          
          <!-- Step 1: Class Selection -->
          <div class="form-step animate-fadeInUp animate-delay-1">
            <div class="step-header">
              <div class="step-number">1</div>
              <h3 class="step-title">
                <i class="fa fa-users me-2"></i>
                Class & Program Selection
              </h3>
            </div>
            <div class="form-body">
              <div class="row">
              <div class="col-md-6">
                <div class="modern-form-group">
                  <label class="modern-label" for="group">
                    <i class="fa fa-layer-group me-2"></i>Select Group
                  </label>
                  <select class="form-control modern-select" id="group" name="group" required>
                    <option value="" disabled selected>Choose a group...</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="modern-form-group">
                  <label class="modern-label" for="program">
                    <i class="fa fa-graduation-cap me-2"></i>Select Program(s)
                  </label>
                  <select class="form-control modern-select" id="program" name="program[]" style="height: 120px" multiple required>
                    <option value="" disabled>Choose program(s)...</option>
                  </select>
                  <small class="text-muted mt-1 d-block">
                    <i class="fa fa-info-circle me-1"></i>Hold Ctrl/Cmd to select multiple programs
                  </small>
                </div>
              </div>
            </div>
            </div>
          </div>

          <!-- Step 2: Cancellation Details -->
          <div class="form-step animate-fadeInUp animate-delay-2">
            <div class="step-header">
              <div class="step-number">2</div>
              <h3 class="step-title">
                <i class="fa fa-calendar-times me-2"></i>
                Cancellation Details
              </h3>
            </div>
            <div class="form-body">
              <div class="row">
              <div class="col-md-6">
                <div class="modern-form-group">
                  <label class="modern-label" for="tarikh_kuliah_dibatalkan">
                    <i class="fa fa-calendar-alt me-2"></i>Date of Cancelled Class
                    <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control modern-input" id="tarikh_kuliah_dibatalkan" name="tarikh_kuliah_dibatalkan" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="modern-form-group">
                  <label class="modern-label" for="sebab_kuliah_dibatalkan">
                    <i class="fa fa-exclamation-triangle me-2"></i>Reason for Cancellation
                    <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control modern-input" id="sebab_kuliah_dibatalkan" name="sebab_kuliah_dibatalkan" placeholder="e.g., Emergency, Illness, etc." required>
                </div>
              </div>
              <div class="col-md-12">
                <div class="modern-form-group">
                  <label class="modern-label" for="maklumat_kuliah">
                    <i class="fa fa-info-circle me-2"></i>Additional Class Information
                    <span class="text-danger">*</span>
                  </label>
                  <textarea class="form-control modern-textarea" id="maklumat_kuliah" name="maklumat_kuliah" placeholder="Provide any additional details about the cancelled class..." required></textarea>
                </div>
              </div>
            </div>
            </div>
          </div>

          <!-- Step 3: Student Representative -->
          <div class="form-step animate-fadeInUp animate-delay-3">
            <div class="step-header">
              <div class="step-number">3</div>
              <h3 class="step-title">
                <i class="fa fa-user-tie me-2"></i>
                Student Representative
              </h3>
            </div>
            <div class="form-body">
              <div class="row">
              <div class="col-md-4">
                <div class="modern-form-group">
                  <label class="modern-label" for="wakil_pelajar">
                    <i class="fa fa-user-check me-2"></i>Select Student Representative
                  </label>
                  <select class="form-control modern-select" id="wakil_pelajar" name="wakil_pelajar" required>
                    <option value="" disabled selected>Choose a student...</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="modern-form-group">
                  <label class="modern-label" for="wakil_pelajar_nama">
                    <i class="fa fa-user me-2"></i>Student Name
                    <span class="tag tag-auto">Auto-filled</span>
                  </label>
                  <input type="text" class="form-control modern-input" id="wakil_pelajar_nama" name="wakil_pelajar_nama" readonly required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="modern-form-group">
                  <label class="modern-label" for="wakil_pelajar_no_tel">
                    <i class="fa fa-phone me-2"></i>Contact Number
                    <span class="tag tag-auto">Auto-filled</span>
                  </label>
                  <input type="text" class="form-control modern-input" id="wakil_pelajar_no_tel" name="wakil_pelajar_no_tel" readonly required>
                </div>
              </div>
            </div>
            </div>
          </div>

          <!-- Step 4: Replacement Class Details -->
          <div class="form-step animate-fadeInUp animate-delay-4">
            <div class="step-header">
              <div class="step-number">4</div>
              <h3 class="step-title">
                <i class="fa fa-calendar-plus me-2"></i>
                Replacement Class Details
              </h3>
            </div>
            <div class="form-body">
              <div class="row">
              <div class="col-md-4">
                <div class="modern-form-group">
                  <label class="modern-label" for="maklumat_kuliah_gantian_tarikh">
                    <i class="fa fa-calendar-check me-2"></i>Replacement Date
                    <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control modern-input" id="maklumat_kuliah_gantian_tarikh" name="maklumat_kuliah_gantian_tarikh" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="modern-form-group">
                  <label class="modern-label" for="maklumat_kuliah_gantian_hari_masa">
                    <i class="fa fa-clock me-2"></i>Day & Time
                    <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control modern-input" id="maklumat_kuliah_gantian_hari_masa" name="maklumat_kuliah_gantian_hari_masa" placeholder="e.g., Monday, 2:00 PM - 4:00 PM" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="modern-form-group">
                  <label class="modern-label" for="lecture_room_id">
                    <i class="fa fa-map-marker-alt me-2"></i>Venue
                    <span class="text-danger">*</span>
                  </label>
                  <select class="form-control modern-select" id="lecture_room_id" name="lecture_room_id" required>
                    <option value="" disabled selected>Choose a room...</option>
                    @foreach($lectureRooms as $room)
                      <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            </div>
          </div>

          <!-- Submit Section -->
          <div class="submit-container animate-fadeInUp" style="animation-delay: 0.5s;">
            <h4 class="mb-3" style="color: #667eea;">
              <i class="fa fa-paper-plane me-2"></i>
              Ready to Submit?
            </h4>
            <button type="submit" class="submit-btn">
              <i class="fa fa-check me-2"></i>
              Submit Application
            </button>
          </div>
        </form>
      </div>
    </section>
  </div>
</div>

@if(session('alert'))
    <script>
        alert("{{ session('alert') }}")
    </script>
@endif

<script type="text/javascript">

$(document).ready(function(){
    // Load groups on page load
    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/replacement_class/getGroup') }}",
        method   : 'GET',
        error:function(err){
            alert("Error loading groups");
            console.log(err);
        },
        success  : function(data){
            $('#group').html(data);
            $('#group').selectpicker('refresh');
        }
    });
    
    // Note: Lecture rooms are now loaded directly from the controller
})

// When group is selected
$(document).on('change', '#group', async function(e){
    var selected_group = $(e.target).val();
    await getProgram(selected_group);
})

// When program is selected
$(document).on('change', '#program', async function(e){
    var selected_group = $('#group').val();
    var selected_program = $(e.target).val();
    await getWakilPelajar(selected_group, selected_program);
})

// When wakil pelajar is selected, auto-populate name and phone
$(document).on('change', '#wakil_pelajar', function(e){
    var selectedOption = $(this).find('option:selected');
    var studentName = selectedOption.data('name');
    var studentPhone = selectedOption.data('phone');
    
    $('#wakil_pelajar_nama').val(studentName || '');
    $('#wakil_pelajar_no_tel').val(studentPhone || '');
})

function getProgram(group)
{
    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/replacement_class/getStudentProgram') }}",
        method   : 'POST',
        data 	 : {group: group},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            $('#program').html(data);
        }
    });
}

function getWakilPelajar(group, program)
{
    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/class/replacement_class/getWakilPelajar') }}",
        method   : 'POST',
        data 	 : {group: group, program: program},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            $('#wakil_pelajar').html(data);
        }
    });
}

</script>
@endsection
