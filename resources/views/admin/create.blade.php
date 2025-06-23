@extends('../layouts.admin')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title mb-3">
          <i class="fa fa-user-plus me-3"></i>Lecturer Registration
        </h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Administration</li>
              <li class="breadcrumb-item active" aria-current="page">New Registration</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <!-- Main Form Container -->
          <div class="col-lg-10 col-xl-9">
            <!-- Progress Steps -->
            <div class="card mb-4 box-animated" style="animation-delay: 0.1s;">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="step-item active">
                    <div class="step-circle">
                      <i class="fa fa-user"></i>
                    </div>
                    <span class="step-label">Personal Info</span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-item">
                    <div class="step-circle">
                      <i class="fa fa-briefcase"></i>
                    </div>
                    <span class="step-label">Professional</span>
                  </div>
                  <div class="step-line"></div>
                  <div class="step-item">
                    <div class="step-circle">
                      <i class="fa fa-graduation-cap"></i>
                    </div>
                    <span class="step-label">Qualifications</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Main Registration Form -->
            <div class="card card-primary box-animated" style="animation-delay: 0.2s;">
              <div class="card-header">
                <h3 class="card-title mb-0">
                  <i class="fa fa-user-graduate me-2"></i>
                  Create New Lecturer Account
                </h3>
                <p class="mb-0 mt-2 opacity-75">Fill in the details below to register a new lecturer</p>
              </div>
              
              <!-- Form Start -->
              <form action="{{ route('admin.store') }}" method="POST" id="lecturerForm">
                @csrf
                <div class="card-body">
                  
                  <!-- Step 1: Personal Information -->
                  <div class="form-section active" id="step-1">
                    <div class="section-header mb-4">
                      <h5 class="section-title">
                        <i class="fa fa-user-circle text-primary me-2"></i>
                        Personal Information
                      </h5>
                      <p class="section-subtitle text-muted">Enter the lecturer's basic personal details</p>
                    </div>

                    <div class="row g-4">
                      <div class="col-md-8">
                        <div class="form-group position-relative">
                          <label class="form-label" for="name">
                            <i class="fa fa-user me-2 text-primary"></i>Full Name
                          </label>
                          <input type="text" class="form-control form-control-lg" id="name" 
                                 placeholder="Enter full name" name="name" required>
                          <div class="input-focus-border"></div>
                        </div>
                      </div>
                      
                      <div class="col-md-4">
                        <div class="form-group position-relative">
                          <label class="form-label" for="nostaf">
                            <i class="fa fa-id-badge me-2 text-primary"></i>Staff Number
                          </label>
                          <input type="text" class="form-control form-control-lg" id="nostaf" 
                                 name="nostaf" placeholder="Enter Staff No" maxlength="12" required>
                          <div class="input-focus-border"></div>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group position-relative">
                          <label class="form-label" for="ic">
                            <i class="fa fa-id-card me-2 text-primary"></i>Identity Card Number
                          </label>
                          <input type="text" class="form-control form-control-lg" id="ic" 
                                 name="ic" placeholder="Enter IC number" maxlength="12" required>
                          <div class="input-focus-border"></div>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group position-relative">
                          <label class="form-label" for="email">
                            <i class="fa fa-envelope me-2 text-primary"></i>Email Address
                          </label>
                          <input type="email" class="form-control form-control-lg" id="email" 
                                 name="email" placeholder="Enter email address" required>
                          <div class="input-focus-border"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Step 2: Professional Information -->
                  <div class="form-section" id="step-2">
                    <div class="section-header mb-4">
                      <h5 class="section-title">
                        <i class="fa fa-briefcase text-primary me-2"></i>
                        Professional Details
                      </h5>
                      <p class="section-subtitle text-muted">Define role, faculty and employment period</p>
                    </div>

                    <div class="row g-4">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="usrtype">
                            <i class="fa fa-user-tag me-2 text-primary"></i>User Role
                          </label>
                          <select class="form-select form-select-lg" id="usrtype" name="usrtype" required>
                            <option value="" selected disabled>Select user role</option>
                            <option value="PL">Program Lead</option>
                            <option value="DN">Dean</option>
                            <option value="AO">Administrative Officer</option>
                            <option value="RGS">Student Record</option>
                            <option value="AR">Academic Registrar</option>
                            <option value="LCT">Lecturer</option>
                            <option value="FN">Finance</option>
                            <option value="TS">Treasurer</option>
                            <option value="OTR">Others</option>
                            <option value="COOP">Cooperation</option>
                            <option value="UR">UR</option>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="faculty">
                            <i class="fa fa-university me-2 text-primary"></i>Faculty
                          </label>
                          <select class="form-select form-select-lg" id="faculty" name="faculty" required>
                            <option value="" selected disabled>Select faculty</option>
                            @foreach ($faculty as $fcl)
                            <option value="{{ $fcl->id }}">{{ $fcl->facultyname }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group position-relative">
                          <label class="form-label" for="from">
                            <i class="fa fa-calendar-plus me-2 text-primary"></i>Employment Start Date
                          </label>
                          <input type="date" class="form-control form-control-lg" id="from" 
                                 name="from" required>
                          <div class="input-focus-border"></div>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group position-relative">
                          <label class="form-label" for="to">
                            <i class="fa fa-calendar-minus me-2 text-muted"></i>Employment End Date
                            <small class="text-muted">(Optional)</small>
                          </label>
                          <input type="date" class="form-control form-control-lg" id="to" 
                                 placeholder="Leave empty if not applicable" name="to">
                          <div class="input-focus-border"></div>
                        </div>
                      </div>

                      <!-- Program Selection (Hidden by default) -->
                      <div class="col-12" id="program-card" style="display: none;">
                        <div class="form-group">
                          <label class="form-label" for="program">
                            <i class="fa fa-project-diagram me-2 text-primary"></i>Program Assignment
                          </label>
                          <div class="program-container" id="program">
                            <!-- Dynamic content will be loaded here -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Step 3: Academic Qualifications -->
                  <div class="form-section" id="step-3">
                    <div class="section-header mb-4">
                      <h5 class="section-title">
                        <i class="fa fa-graduation-cap text-primary me-2"></i>
                        Academic Qualifications
                      </h5>
                      <p class="section-subtitle text-muted">Select all applicable qualifications and provide details</p>
                    </div>

                    <div class="qualifications-container">
                      <div class="row g-3">
                        <!-- Diploma -->
                        <div class="col-12">
                          <div class="qualification-card" data-level="DP">
                            <div class="qualification-header">
                              <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="diploma" 
                                       name="academic[]" value="DP">
                                <label for="diploma" class="form-check-label qualification-label">
                                  <i class="fa fa-certificate me-2"></i>
                                  <span class="qualification-title">Diploma</span>
                                </label>
                              </div>
                            </div>
                            <div class="qualification-details" id="details_DP" style="display: none;">
                              <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                  <input type="text" class="form-control" id="prg_DP" 
                                         placeholder="Program name" name="prg[]">
                                </div>
                                <div class="col-md-6">
                                  <input type="text" class="form-control" id="uni_DP" 
                                         placeholder="University/Institution" name="uni[]">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Degree -->
                        <div class="col-12">
                          <div class="qualification-card" data-level="DG">
                            <div class="qualification-header">
                              <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="degree" 
                                       name="academic[]" value="DG">
                                <label for="degree" class="form-check-label qualification-label">
                                  <i class="fa fa-user-graduate me-2"></i>
                                  <span class="qualification-title">Bachelor's Degree</span>
                                </label>
                              </div>
                            </div>
                            <div class="qualification-details" id="details_DG" style="display: none;">
                              <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                  <input type="text" class="form-control" id="prg_DG" 
                                         placeholder="Program name" name="prg[]">
                                </div>
                                <div class="col-md-6">
                                  <input type="text" class="form-control" id="uni_DG" 
                                         placeholder="University/Institution" name="uni[]">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Master -->
                        <div class="col-12">
                          <div class="qualification-card" data-level="MS">
                            <div class="qualification-header">
                              <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="master" 
                                       name="academic[]" value="MS">
                                <label for="master" class="form-check-label qualification-label">
                                  <i class="fa fa-medal me-2"></i>
                                  <span class="qualification-title">Master's Degree</span>
                                </label>
                              </div>
                            </div>
                            <div class="qualification-details" id="details_MS" style="display: none;">
                              <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                  <input type="text" class="form-control" id="prg_MS" 
                                         placeholder="Program name" name="prg[]">
                                </div>
                                <div class="col-md-6">
                                  <input type="text" class="form-control" id="uni_MS" 
                                         placeholder="University/Institution" name="uni[]">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- PhD -->
                        <div class="col-12">
                          <div class="qualification-card" data-level="PHD">
                            <div class="qualification-header">
                              <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="phd" 
                                       name="academic[]" value="PHD">
                                <label for="phd" class="form-check-label qualification-label">
                                  <i class="fa fa-trophy me-2"></i>
                                  <span class="qualification-title">Doctor of Philosophy (PhD)</span>
                                </label>
                              </div>
                            </div>
                            <div class="qualification-details" id="details_PHD" style="display: none;">
                              <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                  <input type="text" class="form-control" id="prg_PHD" 
                                         placeholder="Program name" name="prg[]">
                                </div>
                                <div class="col-md-6">
                                  <input type="text" class="form-control" id="uni_PHD" 
                                         placeholder="University/Institution" name="uni[]">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                
                <!-- Form Footer with Navigation -->
                <div class="card-footer bg-light">
                  <div class="d-flex justify-content-between align-items-center">
                    <button type="button" id="prevBtn" class="btn btn-secondary" style="display: none;">
                      <i class="fa fa-arrow-left me-2"></i>Previous
                    </button>
                    <div class="flex-grow-1"></div>
                    <button type="button" id="nextBtn" class="btn btn-primary">
                      Next<i class="fa fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="btn btn-success" style="display: none;">
                      <i class="fa fa-check me-2"></i>Create Account
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

<style>
/* Custom Styles for Enhanced Form */
.box-animated {
  opacity: 0;
  transform: translateY(20px);
  animation: slideInUp 0.6s ease forwards;
}

@keyframes slideInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Progress Steps */
.step-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  flex: 1;
}

.step-circle {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #e9ecef;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6c757d;
  font-size: 18px;
  transition: all 0.3s ease;
  border: 3px solid transparent;
}

.step-item.active .step-circle {
  background: var(--primary-color);
  color: white;
  border-color: rgba(67, 97, 238, 0.3);
  box-shadow: 0 0 20px rgba(67, 97, 238, 0.3);
}

.step-label {
  margin-top: 10px;
  font-size: 12px;
  font-weight: 600;
  color: #6c757d;
  text-align: center;
}

.step-item.active .step-label {
  color: var(--primary-color);
}

.step-line {
  height: 3px;
  background: #e9ecef;
  flex: 1;
  margin: 0 20px;
  margin-top: -27px;
  position: relative;
  z-index: 0;
}

/* Form Sections */
.form-section {
  display: none;
}

.form-section.active {
  display: block;
}

.section-header {
  text-align: center;
  padding: 20px 0;
  border-bottom: 2px solid #f8f9fa;
  margin-bottom: 30px;
}

.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--dark-color);
  margin-bottom: 10px;
}

.section-subtitle {
  font-size: 1rem;
  margin: 0;
}

/* Enhanced Form Controls */
.form-control-lg, .form-select-lg {
  padding: 15px 20px;
  font-size: 1rem;
  border-radius: 12px;
  border: 2px solid #e9ecef;
  transition: all 0.3s ease;
  position: relative;
}

.form-control-lg:focus, .form-select-lg:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
  transform: translateY(-2px);
}

.input-focus-border {
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 3px;
  background: var(--primary-color);
  transition: all 0.3s ease;
  transform: translateX(-50%);
  border-radius: 2px;
}

.form-control:focus + .input-focus-border {
  width: 100%;
}

/* Qualification Cards */
.qualification-card {
  background: white;
  border: 2px solid #f8f9fa;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 15px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.qualification-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 5px;
  height: 100%;
  background: var(--primary-color);
  transform: scaleY(0);
  transition: transform 0.3s ease;
}

.qualification-card:hover {
  border-color: var(--primary-color);
  box-shadow: 0 8px 25px rgba(67, 97, 238, 0.1);
}

.qualification-card:hover::before {
  transform: scaleY(1);
}

.qualification-header {
  display: flex;
  align-items: center;
}

.qualification-label {
  display: flex;
  align-items: center;
  font-weight: 600;
  margin: 0;
  cursor: pointer;
  flex: 1;
}

.qualification-title {
  font-size: 1.1rem;
}

.qualification-details {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 15px;
  margin-top: 15px;
}

/* Form Check Styling */
.form-check-input {
  width: 1.5rem;
  height: 1.5rem;
  margin-right: 15px;
  border: 2px solid #dee2e6;
  border-radius: 4px;
}

.form-check-input:checked {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
}

/* Button Enhancements */
.btn {
  padding: 12px 30px;
  font-weight: 600;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .step-line {
    display: none;
  }
  
  .step-item {
    margin-bottom: 20px;
  }
  
  .section-header {
    text-align: left;
    padding: 15px 0;
  }
  
  .section-title {
    font-size: 1.3rem;
  }
}

/* Loading States */
.loading {
  position: relative;
  overflow: hidden;
}

.loading::after {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
  animation: loading 1.5s infinite;
}

@keyframes loading {
  0% { left: -100%; }
  100% { left: 100%; }
}

/* Additional Enhancement - Floating Labels */
.form-floating {
  position: relative;
}

.form-floating > .form-control-lg:focus ~ label,
.form-floating > .form-control-lg:not(:placeholder-shown) ~ label {
  opacity: 0.65;
  transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
}

/* Enhanced Progress Line Animation */
.step-line.active {
  background: linear-gradient(to right, var(--primary-color) 0%, var(--primary-color) 50%, #e9ecef 50%);
  animation: progressLine 0.5s ease-in-out;
}

@keyframes progressLine {
  from { background-size: 0% 100%; }
  to { background-size: 100% 100%; }
}

/* Enhanced Card Hover Effects */
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
}

/* Success State for Form Controls */
.form-control.is-valid, .form-select.is-valid {
  border-color: var(--success-color);
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%233cb371' d='m2.3 6.73.94-.94 2.94 2.94L7.6 7.33 3.24 2.97a.75.75 0 0 0-1.06 0L.76 4.39z'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right calc(0.375em + 0.1875rem) center;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

/* Error State for Form Controls */
.form-control.is-invalid, .form-select.is-invalid {
  border-color: var(--danger-color);
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23e63946' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6.6.6M6.4 7.4l-.6-.6m0-2.8.6.6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right calc(0.375em + 0.1875rem) center;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

/* Enhanced Tooltip-like Effects */
.form-label {
  position: relative;
}

.form-label i {
  transition: all 0.3s ease;
}

.form-group:focus-within .form-label i {
  color: var(--primary-color) !important;
  transform: scale(1.1);
}

/* Improved Button States */
.btn-primary {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-color) 100%);
  border: none;
  box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
  box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
  transform: translateY(-3px);
}

.btn-secondary {
  background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
  border: none;
  box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.btn-success {
  background: linear-gradient(135deg, var(--success-color) 0%, #2d8f5f 100%);
  border: none;
  box-shadow: 0 4px 15px rgba(60, 179, 113, 0.3);
}

/* Enhanced Qualification Cards */
.qualification-card.border-primary {
  border-color: var(--primary-color) !important;
  box-shadow: 0 0 20px rgba(67, 97, 238, 0.2);
  background: linear-gradient(145deg, #ffffff 0%, rgba(67, 97, 238, 0.02) 100%);
}

/* Pulse animation for active steps */
.step-item.active .step-circle {
  animation: pulse-glow 2s infinite;
}

@keyframes pulse-glow {
  0% { box-shadow: 0 0 20px rgba(67, 97, 238, 0.3); }
  50% { box-shadow: 0 0 30px rgba(67, 97, 238, 0.5); }
  100% { box-shadow: 0 0 20px rgba(67, 97, 238, 0.3); }
}

/* Smooth transitions for form sections */
.form-section {
  opacity: 0;
  transform: translateX(50px);
  transition: all 0.5s ease;
}

.form-section.active {
  opacity: 1;
  transform: translateX(0);
}
</style>

<script type="text/javascript">
$(document).ready(function() {
  let currentStep = 1;
  const totalSteps = 3;

  // Initialize form
  showStep(currentStep);

  // Navigation functions
  $('#nextBtn').click(function() {
    if (validateStep(currentStep)) {
      if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
      }
    }
  });

  $('#prevBtn').click(function() {
    if (currentStep > 1) {
      currentStep--;
      showStep(currentStep);
    }
  });

  function showStep(step) {
    // Hide all sections
    $('.form-section').removeClass('active');
    $('#step-' + step).addClass('active');
    
    // Update progress
    $('.step-item').removeClass('active');
    for (let i = 1; i <= step; i++) {
      $(`.step-item:nth-child(${i * 2 - 1})`).addClass('active');
    }
    
    // Update buttons
    $('#prevBtn').toggle(step > 1);
    $('#nextBtn').toggle(step < totalSteps);
    $('#submitBtn').toggle(step === totalSteps);
  }

  function validateStep(step) {
    let isValid = true;
    let section = $('#step-' + step);
    
    // Validate required fields in current step
    section.find('input[required], select[required]').each(function() {
      if (!$(this).val()) {
        $(this).addClass('is-invalid');
        isValid = false;
      } else {
        $(this).removeClass('is-invalid');
      }
    });
    
    if (!isValid) {
      // Show validation message
      Swal.fire({
        icon: 'warning',
        title: 'Validation Error',
        text: 'Please fill in all required fields before proceeding.',
        confirmButtonColor: '#4361ee'
      });
    }
    
    return isValid;
  }

  // User type change handler
  $('#usrtype').change(function() {
    const usertype = $(this).val();
    const programCard = $('#program-card');
    
    if (usertype === 'PL' || usertype === 'AO' || usertype === 'DN') {
      programCard.slideDown(300);
      $('#program').prop('required', true);
    } else {
      programCard.slideUp(300);
      $('#program').prop('required', false);
    }
  });

  // Faculty change handler
  $('#faculty').change(function() {
    const facultyId = $(this).val();
    if (facultyId) {
      getProgramOption(facultyId);
    }
  });

  // Get program options
  function getProgramOption(faculty) {
    $('#program').addClass('loading');
    
    return $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('admin/getProgramoptions') }}",
      method: 'POST',
      data: {faculty: faculty},
      success: function(data) {
        $('#program').html(data).removeClass('loading');
      },
      error: function(err) {
        console.log(err);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to load program options.',
          confirmButtonColor: '#4361ee'
        });
        $('#program').removeClass('loading');
      }
    });
  }

  // Qualification checkbox handlers
  $("input[type='checkbox'][name='academic[]']").change(function() {
    const level = this.value;
    const isChecked = this.checked;
    const detailsContainer = $('#details_' + level);
    const programInput = $('#prg_' + level);
    const universityInput = $('#uni_' + level);
    
    if (isChecked) {
      detailsContainer.slideDown(300);
      programInput.prop('required', true);
      universityInput.prop('required', true);
      
      // Add visual feedback
      $(this).closest('.qualification-card').addClass('border-primary');
    } else {
      detailsContainer.slideUp(300);
      programInput.prop('required', false);
      universityInput.prop('required', false);
      programInput.val('');
      universityInput.val('');
      
      // Remove visual feedback
      $(this).closest('.qualification-card').removeClass('border-primary');
    }
  });

  // Form submission with validation
  $('#lecturerForm').submit(function(e) {
    if (!validateStep(totalSteps)) {
      e.preventDefault();
      return false;
    }
    
    // Show loading state
    $('#submitBtn').html('<i class="fa fa-spinner fa-spin me-2"></i>Creating Account...');
    $('#submitBtn').prop('disabled', true);
  });

  // Input animations
  $('.form-control, .form-select').on('focus', function() {
    $(this).parent().addClass('focused');
  }).on('blur', function() {
    if (!$(this).val()) {
      $(this).parent().removeClass('focused');
    }
  });

  // Real-time validation
  $('input[required], select[required]').on('input change', function() {
    if ($(this).val()) {
      $(this).removeClass('is-invalid').addClass('is-valid');
    } else {
      $(this).removeClass('is-valid');
    }
  });
});
</script>
@endsection
