@extends((Auth::user()->usrtype == "ADM") ? 'layouts.admin' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "TS" ? 'layouts.treasurer' : (Auth::user()->usrtype == "DN" ? 'layouts.deen' : (Auth::user()->usrtype == "OTR" ? 'layouts.other_user' : (Auth::user()->usrtype == "COOP" ? 'layouts.coop' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : 'layouts.ketua_program')))))))))


@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title mb-3">
            <i class="fa fa-cog me-3 text-primary"></i>Account Settings
          </h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <!-- Main Settings Container -->
          <div class="col-lg-8 col-xl-7">
            
            <!-- Profile Overview Card -->
            <div class="card mb-4 box-animated profile-overview-card" style="animation-delay: 0.1s;">
              <div class="card-header">
                <h3 class="card-title mb-0">
                  <i class="fa fa-user-circle me-2"></i>
                  Profile Overview
                </h3>
                <p class="mb-0 mt-2 opacity-75">Current account information</p>
              </div>
                <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-md-3 text-center">
                    <div class="profile-image-container">
                      <div class="profile-avatar">
                        <img src="{{ (Auth::user()->image != null) ? Storage::disk('linode')->url(Auth::user()->image) : asset('assets/images/1.jpg')}}" 
                             class="profile-image rounded-circle" 
                             alt="Profile Image">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="profile-info">
                      <h4 class="profile-name">{{ Auth::user()->name }}</h4>
                      <div class="profile-details">
                        <span class="badge badge-outline-primary me-2">{{ Auth::user()->usrtype }}</span>
                        <span class="text-muted">{{ Auth::user()->email }}</span>
                      </div>
                      <div class="profile-stats mt-3">
                        <div class="stat-item">
                          <span class="stat-label">Staff ID:</span>
                          <span class="stat-value">{{ Auth::user()->no_staf }}</span>
                        </div>
                        <div class="stat-item">
                          <span class="stat-label">Phone:</span>
                          <span class="stat-value">{{ Auth::user()->no_tel ?? 'Not set' }}</span>
                        </div>
                      </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>

            <!-- Enhanced Settings Tabs -->
            <div class="card box-animated modern-tabs-card" style="animation-delay: 0.2s;">
              <div class="card-header tabs-header">
                <div class="tabs-container">
                  <h4 class="tabs-title mb-3">
                    <i class="fa fa-cogs me-2 text-primary"></i>
                    Account Management
                  </h4>
                  <ul class="nav nav-tabs modern-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                        <i class="fa fa-user me-2"></i>
                        <span class="tab-text">Profile</span>
                        <div class="tab-indicator"></div>
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="security-tab" data-bs-toggle="tab" href="#security" role="tab" aria-controls="security" aria-selected="false">
                        <i class="fa fa-shield-alt me-2"></i>
                        <span class="tab-text">Security</span>
                        <div class="tab-indicator"></div>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
              
              <div class="card-body">
                <div class="tab-content" id="settingsTabContent">
                  
                  <!-- Profile Tab -->
                  <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <form action="/lecturer/update" method="POST" id="profileForm">
                      @csrf
                      @method('POST')
                      <input type="hidden" name="form_type" value="profile">
                      
                      <div class="section-header mb-4">
                        <h5 class="section-title">
                          <i class="fa fa-edit text-primary me-2"></i>
                          Update Contact Information
                        </h5>
                        <p class="section-subtitle text-muted">Update your phone number and contact details</p>
                      </div>

                      <div class="row g-4">
                        <div class="col-md-6">
                          <div class="form-group position-relative">
                            <label class="form-label" for="phone">
                              <i class="fa fa-phone me-2 text-primary"></i>Phone Number
                            </label>
                            <input type="tel" class="form-control form-control-lg" id="phone" name="no_tel" 
                                   placeholder="Enter phone number" value="{{ Auth::user()->no_tel }}" 
                                   maxlength="15" required>
                            <div class="input-focus-border"></div>
                            <span class="text-danger">@error('no_tel'){{ $message }}@enderror</span>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group position-relative">
                            <label class="form-label" for="email_display">
                              <i class="fa fa-envelope me-2 text-primary"></i>Email Address
                            </label>
                            <input type="email" class="form-control form-control-lg" id="email_display" 
                                   placeholder="Email address" value="{{ Auth::user()->email }}" disabled>
                            <div class="input-focus-border"></div>
                            <small class="text-muted">Contact admin to change email address</small>
                          </div>
                        </div>
                      </div>

                      <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                          <i class="fa fa-save me-2"></i>Update Profile
                        </button>
                      </div>
                    </form>
                  </div>

                  <!-- Security Tab -->
                  <div class="tab-pane fade" id="security" role="tabpanel">
                    <form action="/lecturer/update" method="POST" id="securityForm">
                      @csrf
                      @method('POST')
                      <input type="hidden" name="form_type" value="security">
                      
                      <div class="section-header mb-4">
                        <h5 class="section-title">
                          <i class="fa fa-key text-primary me-2"></i>
                          Change Password
                        </h5>
                        <p class="section-subtitle text-muted">Update your account password for better security</p>
                      </div>

                      <div class="row g-4">
                        <div class="col-md-6">
                          <div class="form-group position-relative">
                            <label class="form-label" for="pass">
                              <i class="fa fa-lock me-2 text-primary"></i>New Password
                            </label>
                            <input type="password" class="form-control form-control-lg" id="pass" name="pass" 
                                   placeholder="Enter new password" maxlength="10" required>
                            <div class="input-focus-border"></div>
                            <span class="text-danger">@error('pass'){{ $message }}@enderror</span>
                            <div class="password-strength mt-2">
                              <div class="strength-bar">
                                <div class="strength-fill"></div>
                              </div>
                              <small class="strength-text text-muted">Password strength: <span id="strength-level">Weak</span></small>
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group position-relative">
                            <label class="form-label" for="conpass">
                              <i class="fa fa-lock me-2 text-primary"></i>Confirm Password
                            </label>
                            <input type="password" class="form-control form-control-lg" id="conpass" name="conpass" 
                                   placeholder="Confirm new password" maxlength="10" required>
                            <div class="input-focus-border"></div>
                            <span class="text-danger">@error('conpass'){{ $message }}@enderror</span>
                            <div class="password-match mt-2">
                              <small id="match-text" class="text-muted">Passwords must match</small>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="security-tips mt-4 p-3 bg-light rounded">
                        <h6><i class="fa fa-lightbulb text-warning me-2"></i>Password Security Tips</h6>
                        <ul class="mb-0 small text-muted">
                          <li>Use a strong password with mixed characters</li>
                          <li>Don't share your password with anyone</li>
                          <li>Change your password regularly</li>
                        </ul>
                      </div>

                      <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                          <i class="fa fa-shield-alt me-2"></i>Update Password
                        </button>
                </div>
              </form>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<style>
/* Modern Settings Styles */
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

/* Profile Overview Styles */
.profile-overview-card .card-header {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-color) 100%);
  color: white;
  border-radius: 12px 12px 0 0;
}

.profile-overview-card .card-header .card-title,
.profile-overview-card .card-header p {
  color: white;
}

.profile-image-container {
  position: relative;
  display: inline-block;
}

.profile-avatar {
  position: relative;
  display: inline-block;
  border-radius: 50%;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
}

.profile-image {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border: 4px solid white;
  transition: all 0.3s ease;
}

.profile-info {
  padding-left: 20px;
}

.profile-name {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--dark-color);
  margin-bottom: 10px;
}

.profile-details {
  margin-bottom: 15px;
}

.profile-stats {
  display: flex;
  gap: 30px;
  flex-wrap: wrap;
}

.stat-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.stat-label {
  font-size: 0.85rem;
  color: #6c757d;
  font-weight: 500;
}

.stat-value {
  font-size: 1rem;
  font-weight: 600;
  color: var(--dark-color);
}

/* Modern Tabs Card */
.modern-tabs-card {
  border: none;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

/* Tabs Header */
.tabs-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-bottom: 3px solid #dee2e6;
  padding: 25px 30px 15px 30px;
  border-radius: 20px 20px 0 0;
}

.tabs-container {
  max-width: 100%;
}

.tabs-title {
  color: #495057;
  font-weight: 700;
  font-size: 1.4rem;
  margin-bottom: 20px;
  text-align: center;
}

/* Enhanced Modern Tabs */
.modern-tabs {
  border-bottom: none;
  justify-content: center;
  gap: 15px;
}

.modern-tabs .nav-item {
  margin: 0;
}

.modern-tabs .nav-link {
  border: 3px solid #dee2e6;
  border-radius: 15px;
  padding: 18px 30px;
  font-weight: 700;
  color: #6c757d;
  background: white;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  margin: 0;
  font-size: 1.1rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  position: relative;
  overflow: hidden;
  min-width: 140px;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.modern-tabs .nav-link::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
  transition: left 0.5s ease;
}

.modern-tabs .nav-link:hover::before {
  left: 100%;
}

.modern-tabs .nav-link:hover {
  background: linear-gradient(135deg, rgba(67, 97, 238, 0.15) 0%, rgba(67, 97, 238, 0.08) 100%);
  color: var(--primary-color);
  border-color: var(--primary-color);
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 8px 25px rgba(67, 97, 238, 0.25);
}

.modern-tabs .nav-link.active {
  background: linear-gradient(135deg, #4361ee 0%, #3730a3 100%);
  color: white;
  border-color: #4361ee;
  transform: translateY(-5px) scale(1.05);
  box-shadow: 0 12px 35px rgba(67, 97, 238, 0.4);
  z-index: 10;
}

.modern-tabs .nav-link.active::after {
  content: '';
  position: absolute;
  bottom: -3px;
  left: 50%;
  transform: translateX(-50%);
  width: 80%;
  height: 4px;
  background: white;
  border-radius: 2px;
  animation: slideIn 0.3s ease;
}

@keyframes slideIn {
  0% { width: 0%; }
  100% { width: 80%; }
}

.modern-tabs .nav-link i {
  margin-right: 10px;
  font-size: 1.3rem;
  transition: all 0.3s ease;
}

.modern-tabs .nav-link.active i {
  transform: scale(1.2);
  color: white;
}

.tab-text {
  display: inline-block;
  transition: all 0.3s ease;
}

.modern-tabs .nav-link.active .tab-text {
  transform: translateY(-1px);
  font-weight: 800;
}

.tab-indicator {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: transparent;
  transition: all 0.3s ease;
}

.modern-tabs .nav-link.active .tab-indicator {
  background: white;
  box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
}

/* Enhanced Tab Content */
.tab-content {
  padding: 40px 30px;
  min-height: 450px;
  background: white;
  border-radius: 0 0 20px 20px;
}

/* Tab Animation */
.tab-pane {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.4s ease;
}

.tab-pane.active {
  opacity: 1;
  transform: translateY(0);
}

/* Section Headers */
.section-header {
  text-align: center;
  padding: 20px 0;
  border-bottom: 2px solid #f8f9fa;
  margin-bottom: 30px;
}

.section-title {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--dark-color);
  margin-bottom: 8px;
}

.section-subtitle {
  font-size: 1rem;
  margin: 0;
}

/* Enhanced Form Controls */
.form-control-lg {
  padding: 15px 20px;
  font-size: 1rem;
  border-radius: 12px;
  border: 2px solid #e9ecef;
  transition: all 0.3s ease;
  position: relative;
}

.form-control-lg:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
  transform: translateY(-2px);
}

.form-control-lg:disabled {
  background-color: #f8f9fa;
  opacity: 0.7;
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

/* Form Groups */
.form-group {
  margin-bottom: 1.5rem;
  position: relative;
}

.form-label {
  font-weight: 600;
  margin-bottom: 8px;
  color: var(--dark-color);
}

.form-label i {
  transition: all 0.3s ease;
}

.form-group:focus-within .form-label i {
  color: var(--primary-color) !important;
  transform: scale(1.1);
}

/* Password Strength Indicator */
.password-strength {
  margin-top: 8px;
}

.strength-bar {
  width: 100%;
  height: 4px;
  background: #e9ecef;
  border-radius: 2px;
  overflow: hidden;
}

.strength-fill {
  height: 100%;
  width: 0%;
  transition: all 0.3s ease;
  border-radius: 2px;
}

.strength-weak { background: #dc3545; width: 25%; }
.strength-fair { background: #fd7e14; width: 50%; }
.strength-good { background: #ffc107; width: 75%; }
.strength-strong { background: #28a745; width: 100%; }

/* Security Tips */
.security-tips {
  border-left: 4px solid var(--primary-color);
}

.security-tips ul {
  padding-left: 20px;
}

/* Badge Styles */
.badge-outline-primary {
  background: rgba(67, 97, 238, 0.1);
  color: var(--primary-color);
  border: 1px solid rgba(67, 97, 238, 0.3);
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: 500;
}

/* Button Enhancements */
.btn-lg {
  padding: 12px 30px;
  font-weight: 600;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.btn-primary {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-color) 100%);
  border: none;
  box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
  box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
  transform: translateY(-2px);
}

/* Card Enhancements */
.card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
}

/* Responsive Design */
@media (max-width: 768px) {
  .profile-info {
    padding-left: 0;
    text-align: center;
    margin-top: 20px;
  }
  
  .profile-stats {
    justify-content: center;
  }
  
  .nav-tabs .nav-link {
    font-size: 0.9rem;
    padding: 10px 16px;
  }
  
  .section-header {
    text-align: left;
    padding: 15px 0;
  }
  
  .section-title {
    font-size: 1.2rem;
  }
}

/* Success/Error States */
.form-control.is-valid {
  border-color: var(--success-color);
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%233cb371' d='m2.3 6.73.94-.94 2.94 2.94L7.6 7.33 3.24 2.97a.75.75 0 0 0-1.06 0L.76 4.39z'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right calc(0.375em + 0.1875rem) center;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-invalid {
  border-color: var(--danger-color);
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23e63946' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6.6.6M6.4 7.4l-.6-.6m0-2.8.6.6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right calc(0.375em + 0.1875rem) center;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
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

/* Tab Ripple Effect */
.tab-ripple {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.6);
  transform: scale(0);
  animation: ripple 0.6s linear;
  pointer-events: none;
  width: 100px;
  height: 100px;
  left: 50%;
  top: 50%;
  margin-left: -50px;
  margin-top: -50px;
}

@keyframes ripple {
  to {
    transform: scale(2);
    opacity: 0;
  }
}

/* Tab Content Fade Animation */
.tab-fade-in {
  animation: tabFadeIn 0.4s ease-in-out;
}

@keyframes tabFadeIn {
  0% {
    opacity: 0;
    transform: translateY(15px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

<script type="text/javascript">
$(document).ready(function() {
  // Enhanced tab switching with animations
  $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    const targetTab = $(e.target).attr('href');
    $(targetTab).addClass('tab-fade-in');
    
    // Remove animation class after animation completes
    setTimeout(() => {
      $(targetTab).removeClass('tab-fade-in');
    }, 400);
  });
  
  // Add tab switching sound effect (optional)
  $('a[data-bs-toggle="tab"]').on('click', function(e) {
    // Add click ripple effect
    const ripple = $('<div class="tab-ripple"></div>');
    $(this).append(ripple);
    
    setTimeout(() => {
      ripple.remove();
    }, 600);
  });
  
  // Password strength checker
  $('#pass').on('input', function() {
    const password = $(this).val();
    const strengthBar = $('.strength-fill');
    const strengthText = $('#strength-level');
    
    let strength = 0;
    let strengthLevel = 'Weak';
    let strengthClass = 'strength-weak';
    
    if (password.length >= 6) strength += 1;
    if (password.match(/[a-z]+/)) strength += 1;
    if (password.match(/[A-Z]+/)) strength += 1;
    if (password.match(/[0-9]+/)) strength += 1;
    if (password.match(/[$@#&!]+/)) strength += 1;
    
    switch(strength) {
      case 0:
      case 1:
        strengthLevel = 'Weak';
        strengthClass = 'strength-weak';
        break;
      case 2:
        strengthLevel = 'Fair';
        strengthClass = 'strength-fair';
        break;
      case 3:
        strengthLevel = 'Good';
        strengthClass = 'strength-good';
        break;
      case 4:
      case 5:
        strengthLevel = 'Strong';
        strengthClass = 'strength-strong';
        break;
    }
    
    strengthBar.removeClass('strength-weak strength-fair strength-good strength-strong');
    strengthBar.addClass(strengthClass);
    strengthText.text(strengthLevel);
  });
  
  // Password match checker
  $('#conpass').on('input', function() {
    const password = $('#pass').val();
    const confirmPassword = $(this).val();
    const matchText = $('#match-text');
    
    if (confirmPassword === '') {
      matchText.text('Passwords must match').removeClass('text-success text-danger').addClass('text-muted');
    } else if (password === confirmPassword) {
      matchText.text('Passwords match!').removeClass('text-muted text-danger').addClass('text-success');
    } else {
      matchText.text('Passwords do not match').removeClass('text-muted text-success').addClass('text-danger');
    }
  });
  
  // Form submission with loading states
  $('#profileForm').submit(function() {
    $(this).find('button[type="submit"]').html('<i class="fa fa-spinner fa-spin me-2"></i>Updating...');
    $(this).find('button[type="submit"]').prop('disabled', true);
  });
  
  $('#securityForm').submit(function() {
    $(this).find('button[type="submit"]').html('<i class="fa fa-spinner fa-spin me-2"></i>Updating...');
    $(this).find('button[type="submit"]').prop('disabled', true);
  });
  
  // Real-time validation
  $('input[required]').on('input', function() {
    if ($(this).val()) {
      $(this).removeClass('is-invalid').addClass('is-valid');
    } else {
      $(this).removeClass('is-valid');
    }
  });
  
  // Phone number formatting (simple numeric only)
  $('#phone').on('input', function() {
    let value = $(this).val();
    // Allow only numbers, spaces, hyphens, and plus sign
    value = value.replace(/[^0-9\s\-\+]/g, '');
    $(this).val(value);
  });
  
  // Show success/error messages
  @if(Session::has('alert'))
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '{{ Session::get('alert') }}',
      confirmButtonColor: '#4361ee',
      timer: 3000
    });
  @endif
  
  @if($errors->any())
    Swal.fire({
      icon: 'error',
      title: 'Validation Error',
      text: 'Please check the form for errors.',
      confirmButtonColor: '#4361ee'
    });
  @endif
});
</script>
@endsection
