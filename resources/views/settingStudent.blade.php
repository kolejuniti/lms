@extends('layouts.student')


@section('main')

<style>
  :root {
    --primary-color: #4f46e5;
    --primary-dark: #4338ca;
    --primary-light: #6366f1;
    --secondary-color: #64748b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --info-color: #3b82f6;
    --bg-light: #f8fafc;
    --bg-white: #ffffff;
    --border-color: #e2e8f0;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }

  .modern-settings-page {
    background: var(--bg-light);
    min-height: 100vh;
    padding: 2rem 0;
  }

  .page-header-modern {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
    color: white;
  }

  .page-header-modern h4 {
    font-size: 1.875rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
  }

  .page-header-modern .breadcrumb {
    background: transparent;
    margin: 0;
    padding: 0;
  }

  .page-header-modern .breadcrumb-item,
  .page-header-modern .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.9);
  }

  .page-header-modern .breadcrumb-item.active {
    color: white;
  }

  .modern-card {
    background: var(--bg-white);
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .modern-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .modern-card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid var(--primary-color);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .modern-card-header i {
    font-size: 1.5rem;
    color: var(--primary-color);
  }

  .modern-card-header b {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
  }

  .modern-card-body {
    padding: 2rem 1.5rem;
  }

  .form-group-modern {
    margin-bottom: 0;
  }
  
  .row.g-3 {
    margin-bottom: 1rem;
    --bs-gutter-x: 1rem;
    --bs-gutter-y: 1rem;
  }
  
  .row.g-3:last-child {
    margin-bottom: 0;
  }
  
  .row.g-3 > [class*="col-"] {
    padding-right: calc(var(--bs-gutter-x) * 0.5);
    padding-left: calc(var(--bs-gutter-x) * 0.5);
  }

  .form-label-modern {
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
  }

  .form-control-modern,
  .form-select-modern {
    border: 2px solid var(--border-color);
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
    background: white;
    width: 100%;
    display: block;
  }

  .form-control-modern:focus,
  .form-select-modern:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    outline: none;
  }

  .form-control-modern:hover:not(:disabled),
  .form-select-modern:hover:not(:disabled) {
    border-color: var(--primary-light);
  }

  .readonly-field-modern {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-color: var(--border-color);
    cursor: not-allowed;
    color: var(--text-secondary);
  }

  .btn-modern {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .btn-primary-modern {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    box-shadow: var(--shadow-md);
  }

  .btn-primary-modern:hover {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .btn-info-modern {
    background: linear-gradient(135deg, var(--info-color) 0%, #60a5fa 100%);
    color: white;
    box-shadow: var(--shadow-md);
  }

  .btn-info-modern:hover {
    background: linear-gradient(135deg, #2563eb 0%, var(--info-color) 100%);
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .btn-danger-modern {
    background: linear-gradient(135deg, var(--danger-color) 0%, #f87171 100%);
    color: white;
    box-shadow: var(--shadow-md);
  }

  .btn-danger-modern:hover {
    background: linear-gradient(135deg, #dc2626 0%, var(--danger-color) 100%);
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .alert-modern {
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    border: none;
    box-shadow: var(--shadow-sm);
  }

  .alert-danger-modern {
    background: #fef2f2;
    color: #991b1b;
    border-left: 4px solid var(--danger-color);
  }

  .checkbox-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .checkbox-modern input[type="checkbox"] {
    width: 1.25rem;
    height: 1.25rem;
    cursor: pointer;
    accent-color: var(--primary-color);
  }

  .checkbox-modern label {
    margin: 0;
    font-weight: 500;
    cursor: pointer;
    color: var(--text-primary);
  }

  .password-toggle-section {
    overflow: hidden;
    transition: max-height 0.5s ease, opacity 0.5s ease;
  }

  .info-badge {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: inline-block;
  }

  textarea.form-control-modern {
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
  }
  
  /* Ensure select elements have consistent height */
  select.form-select-modern {
    height: auto;
    min-height: calc(0.75rem * 2 + 0.9375rem + 4px);
  }
  
  /* Ensure all form fields in the same row align properly */
  .row.g-3 .form-group-modern {
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  
  .row.g-3 .form-group-modern .form-control-modern,
  .row.g-3 .form-group-modern .form-select-modern {
    flex: 1;
  }

  .waris-card-container {
    position: relative;
  }

  .delete-waris-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 10;
  }

  .form-footer-modern {
    background: var(--bg-light);
    padding: 1.5rem;
    border-top: 2px solid var(--border-color);
    border-radius: 0 0 16px 16px;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
  }

  @media (max-width: 768px) {
    .modern-settings-page {
      padding: 1rem 0;
    }
    
    .page-header-modern {
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    
    .modern-card-body {
      padding: 1.5rem 1rem;
    }
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper modern-settings-page">
  <div class="container-full">
    <!-- Page Header -->	  
    <div class="page-header-modern">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4><i class="mdi mdi-account-cog me-2"></i>My Settings</h4>
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
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <form action="/student/update" method="POST">
              @csrf
              
              @if ($errors->any())
                <div class="alert-modern alert-danger-modern">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi-alert-circle me-2" style="font-size: 1.5rem;"></i>
                    <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              @endif

              <!-- Student Basic Info (Read-Only) -->
              <div class="modern-card">
                <div class="modern-card-header">
                  <i class="mdi mdi-account-circle"></i>
                  <b>Student Information</b>
                  <span class="info-badge ms-auto">Read-Only</span>
                </div>
                <div class="modern-card-body">
                  <div class="row g-3">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="name">Full Name</label>
                        <input type="text" class="form-control-modern readonly-field-modern" id="name" value="{{ $student->name }}" readonly>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="ic">IC / Passport</label>
                        <input type="text" class="form-control-modern readonly-field-modern" id="ic" value="{{ $student->ic }}" readonly>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row g-3">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="matric">No. Matric</label>
                        <input type="text" class="form-control-modern readonly-field-modern" id="matric" value="{{ $student->no_matric }}" readonly>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="program">Program</label>
                        <input type="text" class="form-control-modern readonly-field-modern" id="program" value="{{ $student->progcode }} - {{ $student->progname }}" readonly>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row g-3">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="intake">Intake</label>
                        <input type="text" class="form-control-modern readonly-field-modern" id="intake" value="{{ $student->intake_name }}" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Account Settings -->
              <div class="modern-card">
                <div class="modern-card-header">
                  <i class="mdi mdi-lock-outline"></i>
                  <b>Account Settings</b>
                </div>
                <div class="modern-card-body">
                  <div class="row g-3">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="email">Email Address</label>
                        <input type="email" class="form-control-modern" id="email" name="email" value="{{ $student->email }}">
                        <span class="text-danger">@error('email')
                          {{ $message }}
                        @enderror</span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row g-3 mt-2">
                    <div class="col-12">
                      <button type="button" id="myButton" class="btn-modern btn-info-modern">
                        <i class="mdi mdi-key-variant"></i>
                        Change Password
                      </button>
                    </div>
                  </div>
                  
                  <div id="collapsee" class="password-toggle-section">
                    <div class="row g-3 mt-1">
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="pass">New Password</label>
                          <input type="password" class="form-control-modern" id="pass" name="pass" placeholder="Enter new password">
                          <span class="text-danger">@error('pass')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="conpass">Confirm Password</label>
                          <input type="password" class="form-control-modern" id="conpass" name="conpass" placeholder="Confirm new password">
                          <span class="text-danger">@error('conpass')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contact Information -->
              <div class="modern-card">
                <div class="modern-card-header">
                  <i class="mdi mdi-phone"></i>
                  <b>Contact Information</b>
                </div>
                <div class="modern-card-body">
                  <div class="row g-3">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="np1">Phone Number 1</label>
                        <input type="text" class="form-control-modern" id="np1" placeholder="Enter Phone Number 1" name="np1" value="{{ $student->no_tel }}">
                        <span class="text-danger">@error('np1')
                          {{ $message }}
                        @enderror</span>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="np2">Phone Number 2</label>
                        <input type="text" class="form-control-modern" id="np2" placeholder="Enter Phone Number 2" name="np2" value="{{ $student->no_tel2 }}">
                        <span class="text-danger">@error('np2')
                          {{ $message }}
                        @enderror</span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row g-3">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="np3">Home Phone Number</label>
                        <input type="text" class="form-control-modern" id="np3" placeholder="Enter Home Phone Number" name="np3" value="{{ $student->no_telhome }}">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Additional Information -->
              <div class="modern-card">
                <div class="modern-card-header">
                  <i class="mdi mdi-information-outline"></i>
                  <b>Additional Information</b>
                </div>
                <div class="modern-card-body">
                  <div class="row g-3">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="CL">Citizenship Level</label>
                        <select class="form-select-modern" id="CL" name="CL">
                          <option value="">Select Citizenship Level</option>
                          @foreach ($data['CL'] as $CL)
                            <option value="{{ $CL->id }}" {{ ($student->statelevel_id == $CL->id) ? 'selected' : '' }}>{{ $CL->citizenshiplevel_name}}</option> 
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="descendants">Descendants</label>
                        <select class="form-select-modern" id="descendants" name="descendants">
                          <option value="">Select Descendants</option>
                          @foreach ($data['descendants'] as $desc)
                            <option value="{{ $desc->id }}" {{ ($student->descendants_id == $desc->id) ? 'selected' : '' }}>{{ $desc->descendants_name}}</option> 
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row g-3">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="muet">MUET</label>
                        <select class="form-select-modern" id="muet" name="muet">
                          <option value="">Select MUET</option>
                          @foreach ($data['muet'] as $muet)
                            <option value="{{ $muet->id }}" {{ ($student->muet_id == $muet->id) ? 'selected' : '' }}>{{ $muet->muet_name}}</option> 
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" style="display: block; margin-bottom: 0.75rem;">Special Status</label>
                        <div class="checkbox-modern">
                          <input type="checkbox" id="oku" name="oku" value="1" {{ ($student->oku != null) ? 'checked'  : '' }}>
                          <label for="oku">OKU (Orang Kurang Upaya)</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Permanent Address -->
              <div class="modern-card">
                <div class="modern-card-header">
                  <i class="mdi mdi-home-map-marker"></i>
                  <b>Permanent Address</b>
                </div>
                <div class="modern-card-body">
                  <div class="row g-3">
                    <div class="col-12">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="address1">Address Line 1</label>
                        <input type="text" class="form-control-modern" id="address1" placeholder="Street address, P.O. box" name="address1" value="{{ $student->address1 }}">
                        <span class="text-danger">@error('address1')
                          {{ $message }}
                        @enderror</span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row g-3">
                    <div class="col-12">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="address2">Address Line 2</label>
                        <input type="text" class="form-control-modern" id="address2" placeholder="Apartment, suite, unit, building, floor, etc." name="address2" value="{{ $student->address2 }}">
                      </div>
                    </div>
                  </div>
                  
                  <div class="row g-3">
                    <div class="col-12">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="address3">Address Line 3</label>
                        <input type="text" class="form-control-modern" id="address3" placeholder="Additional address information (optional)" name="address3" value="{{ $student->address3 }}">
                      </div>
                    </div>
                  </div>
                  
                  <div class="row g-3">
                    <div class="col-lg-4 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="postcode">Postcode</label>
                        <input type="text" class="form-control-modern" id="postcode" name="postcode" placeholder="Enter Postcode" value="{{ $student->postcode }}">
                        <span class="text-danger">@error('postcode')
                          {{ $message }}
                        @enderror</span>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="city">City</label>
                        <input type="text" class="form-control-modern" id="city" name="city" placeholder="Enter City" value="{{ $student->city }}">
                        <span class="text-danger">@error('city')
                          {{ $message }}
                        @enderror</span>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <div class="form-group-modern">
                        <label class="form-label-modern" for="state">State</label>
                        <select class="form-select-modern" id="state" name="state">
                          <option value="">Select State</option>
                          @foreach ($data['state'] as $state)
                            <option value="{{ $state->id }}" {{ ($student->state_id == $state->id) ? 'selected' : '' }}>{{ $state->state_name}}</option> 
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Waris (Heir) Information -->
              <div id="forms-container">
                <div class="modern-card waris-card-container" id="card-1">
                  <div class="modern-card-header">
                    <i class="mdi mdi-account-multiple"></i>
                    <b>Heir (Waris) Information</b>
                  </div>
                  <div class="modern-card-body">
                    <div class="row g-3">
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="w_name">Full Name</label>
                          <input type="text" class="form-control-modern" id="w_name" placeholder="Enter Full Name" name="w_name[]">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="w_ic">IC Number</label>
                          <input type="text" class="form-control-modern" id="w_ic" placeholder="Enter IC Number" name="w_ic[]">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row g-3">
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="w_email">Email Address</label>
                          <input type="email" class="form-control-modern" id="w_email" placeholder="Enter Email Address" name="w_email[]">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="w_notel_home">Home Phone Number</label>
                          <input type="text" class="form-control-modern" id="w_notel_home" placeholder="Enter Home Phone" name="w_notel_home[]">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row g-3">
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="w_notel">Mobile Phone Number</label>
                          <input type="text" class="form-control-modern" id="w_notel" placeholder="Enter Mobile Phone" name="w_notel[]">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="occupation">Occupation</label>
                          <select class="form-select-modern" id="occupation" name="occupation[]">
                            <option value="">Select Occupation</option>
                            <option value="SEKTOR KERAJAAN">SEKTOR KERAJAAN</option>
                            <option value="SEKTOR SWASTA">SEKTOR SWASTA</option>
                            <option value="BEKERJA SENDIRI">BEKERJA SENDIRI</option>
                            <option value="PESARA">PESARA</option>
                            <option value="TIDAK BEKERJA">TIDAK BEKERJA</option>
                            <option value="TIDAK BEKERJA/PENERIMA BANTUAN">TIDAK BEKERJA / PENERIMA BANTUAN</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row g-3">
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="dependent">Number of Dependents</label>
                          <input type="text" class="form-control-modern" id="dependent" name="dependent[]" placeholder="Enter Number">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="relationship">Relationship</label>
                          <select class="form-select-modern" id="relationship" name="relationship[]">
                            <option value="">Select Relationship</option>
                            @foreach ($data['relationship'] as $rlp)
                              <option value="{{ $rlp->id }}">{{$rlp->name }}</option> 
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row g-3">
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="w_kasar">Gross Salary (Kasar)</label>
                          <input type="text" class="form-control-modern" id="w_kasar" name="w_kasar[]" placeholder="RM">
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="w_bersih">Net Salary (Bersih)</label>
                          <input type="text" class="form-control-modern" id="w_bersih" name="w_bersih[]" placeholder="RM">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row g-3">
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="w_status">Status</label>
                          <select class="form-select-modern" id="w_status" name="w_status[]">
                            <option value="">Select Status</option>
                            @foreach ($data['wstatus'] as $sts)
                              <option value="{{ $sts->id }}">{{$sts->name }}</option> 
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group-modern">
                          <label class="form-label-modern" for="w_address">Address</label>
                          <textarea class="form-control-modern" id="w_address" name="w_address[]" rows="3" placeholder="Enter Full Address"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-4">
                <div class="col-md-12">
                  <button id="add-form" class="btn-modern btn-primary-modern" type="button">
                    <i class="mdi mdi-plus-circle"></i>
                    Add Another Heir
                  </button>
                </div>
              </div>

              <!-- Form Footer -->
              <div class="form-footer-modern">
                <button type="submit" class="btn-modern btn-primary-modern">
                  <i class="mdi mdi-content-save"></i>
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
var msg = '{{Session::get('alert')}}';
var exist = '{{Session::has('alert')}}';
if(exist){
  alert(msg);
}
</script>
<script>
$(document).ready(function(){
  // Hide password change section initially
  $("#collapsee").hide();
  $("#myButton").click(function(){
    $("#collapsee").slideToggle(500);
  });

  // Function to decode HTML entities
  function decodeHTMLEntities(text) {
    const textArea = document.createElement('textarea');
    textArea.innerHTML = text;
    return textArea.value;
  }

  // Fill the input fields in the original card element with the first row of data
  @if(count($data['waris']) > 0)
    var firstWaris = {!! json_encode($data['waris']->shift()) !!};
    
    // Use decoded values
    $('#w_name').val(decodeHTMLEntities(firstWaris.name || ''));
    $('#w_ic').val(firstWaris.ic || '').addClass('readonly-field-modern').prop('readonly', true);
    $('#w_email').val(firstWaris.email || '');
    $('#w_notel_home').val(firstWaris.home_tel || '');
    $('#w_notel').val(firstWaris.phone_tel || '');
    $('#occupation').val(firstWaris.occupation || '').addClass('readonly-field-modern').prop('disabled', true);
    $('#dependent').val(firstWaris.dependent_no || '').addClass('readonly-field-modern').prop('readonly', true);
    $('#relationship').val(firstWaris.relationship || '').addClass('readonly-field-modern').prop('disabled', true);
    $('#w_kasar').val(firstWaris.kasar || '').addClass('readonly-field-modern').prop('readonly', true);
    $('#w_bersih').val(firstWaris.bersih || '').addClass('readonly-field-modern').prop('readonly', true);
    $('#w_status').val(firstWaris.status || '').addClass('readonly-field-modern').prop('disabled', true);
    $('#w_address').val(firstWaris.address || '');
    
    // Add hidden inputs for disabled selects to ensure values are submitted
    if(firstWaris.occupation) {
      $('#occupation').after('<input type="hidden" name="occupation[]" value="' + firstWaris.occupation + '">');
    }
    if(firstWaris.relationship) {
      $('#relationship').after('<input type="hidden" name="relationship[]" value="' + firstWaris.relationship + '">');
    }
    if(firstWaris.status) {
      $('#w_status').after('<input type="hidden" name="w_status[]" value="' + firstWaris.status + '">');
    }
    
    // Mark the first card as existing data and add badge
    $('#card-1').attr('data-existing', 'true');
    $('#card-1').find('.modern-card-header').append('<span class="info-badge ms-auto">Existing Data</span>');
  @endif

  // Clone the card element with ID #card-1 for each remaining row of data
  @foreach ($data['waris'] as $waris)
    var newForm = $('#card-1').clone();
    newForm.attr('id', 'card-{{ $waris->id }}');
    newForm.attr('data-existing', 'true');
    
    // Use escaping and decoding to handle special characters properly in Blade
    newForm.find('input[name="w_name[]"]').val('{!! addslashes(htmlspecialchars_decode($waris->name ?? '')) !!}');
    newForm.find('input[name="w_ic[]"]').val('{{ $waris->ic ?? '' }}').addClass('readonly-field-modern').prop('readonly', true);
    newForm.find('input[name="w_email[]"]').val('{{ $waris->email ?? '' }}');
    newForm.find('input[name="w_notel_home[]"]').val('{{ $waris->home_tel ?? '' }}');
    newForm.find('input[name="w_notel[]"]').val('{{ $waris->phone_tel ?? '' }}');
    newForm.find('select[name="occupation[]"]').val('{{ $waris->occupation ?? '' }}').addClass('readonly-field-modern').prop('disabled', true);
    newForm.find('input[name="dependent[]"]').val('{{ $waris->dependent_no ?? '' }}').addClass('readonly-field-modern').prop('readonly', true);
    newForm.find('input[name="w_kasar[]"]').val('{{ $waris->kasar ?? '' }}').addClass('readonly-field-modern').prop('readonly', true);
    newForm.find('input[name="w_bersih[]"]').val('{{ $waris->bersih ?? '' }}').addClass('readonly-field-modern').prop('readonly', true);
    newForm.find('select[name="relationship[]"]').val('{{ $waris->relationship ?? '' }}').addClass('readonly-field-modern').prop('disabled', true);
    newForm.find('select[name="w_status[]"]').val('{{ $waris->status ?? '' }}').addClass('readonly-field-modern').prop('disabled', true);
    newForm.find('textarea[name="w_address[]"]').val('{{ $waris->address ?? '' }}');
    
    // Add hidden inputs for disabled selects to ensure values are submitted
    @if(!empty($waris->occupation))
      newForm.find('select[name="occupation[]"]').after('<input type="hidden" name="occupation[]" value="{{ $waris->occupation }}">');
    @endif
    @if(!empty($waris->relationship))
      newForm.find('select[name="relationship[]"]').after('<input type="hidden" name="relationship[]" value="{{ $waris->relationship }}">');
    @endif
    @if(!empty($waris->status))
      newForm.find('select[name="w_status[]"]').after('<input type="hidden" name="w_status[]" value="{{ $waris->status }}">');
    @endif
    
    // Add "Existing Data" badge to existing waris cards
    newForm.find('.modern-card-header .info-badge').remove(); // Remove any existing badge first
    newForm.find('.modern-card-header').append('<span class="info-badge ms-auto">Existing Data</span>');
    
    // Add the new card element to the forms container (no delete button for existing data)
    $('#forms-container').append(newForm);
  @endforeach

  // Add new waris form
  $('#add-form').click(function() {
    // Clone the card element with ID #card-1
    var newForm = $('#card-1').clone();
    // Remove any existing delete button from clone
    newForm.find('.delete-waris-btn').remove();
    // Remove hidden inputs that were added for disabled fields
    newForm.find('input[type="hidden"]').remove();
    // Remove "Existing Data" badge from new forms
    newForm.find('.info-badge').remove();
    // Remove data-existing attribute to mark as new form
    newForm.removeAttr('data-existing');
    // Clear the input values in the cloned form
    newForm.find('input, select, textarea').val('');
    
    // Remove readonly and disabled attributes from new forms (make them editable)
    newForm.find('input[name="w_ic[]"]').removeClass('readonly-field-modern').prop('readonly', false);
    newForm.find('select[name="occupation[]"]').removeClass('readonly-field-modern').prop('disabled', false);
    newForm.find('input[name="dependent[]"]').removeClass('readonly-field-modern').prop('readonly', false);
    newForm.find('select[name="relationship[]"]').removeClass('readonly-field-modern').prop('disabled', false);
    newForm.find('input[name="w_kasar[]"]').removeClass('readonly-field-modern').prop('readonly', false);
    newForm.find('input[name="w_bersih[]"]').removeClass('readonly-field-modern').prop('readonly', false);
    newForm.find('select[name="w_status[]"]').removeClass('readonly-field-modern').prop('disabled', false);
    
    // Add a delete button to the new form
    newForm.find('.modern-card-body').prepend('<div class="delete-waris-btn"><button class="btn-modern btn-danger-modern delete-form" type="button"><i class="mdi mdi-delete"></i> Remove</button></div>');
    // Append the new form to the forms container with smooth animation
    newForm.hide().appendTo('#forms-container').slideDown(400);
  });

  // Attach a click event listener to the delete buttons
  $('#forms-container').on('click', '.delete-form', function() {
    // Remove the parent card element with smooth animation
    $(this).closest('.modern-card').slideUp(400, function() {
      $(this).remove();
    });
  });
});
</script>
@endsection
