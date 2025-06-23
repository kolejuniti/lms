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
            <i class="fa fa-user-edit me-3"></i>Edit Lecturer Profile
          </h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Administration</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <form action="/admin/{{ $id->id }}" method="POST" enctype="multipart/form-data" id="editLecturerForm">
          @csrf
          @method('PATCH')
          
          <div class="row justify-content-center">
            <!-- Main Content Container -->
            <div class="col-lg-11 col-xl-10">
              
              <!-- Profile Overview Card -->
              <div class="card mb-4 box-animated profile-overview-card" style="animation-delay: 0.1s;">
                <div class="card-header">
                  <h3 class="card-title mb-0">
                    <i class="fa fa-user-circle me-2"></i>
                    Profile Overview
                  </h3>
                  <p class="mb-0 mt-2 opacity-75">Update lecturer information and profile settings</p>
                </div>
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                      <div class="profile-image-container">
                        <div class="profile-image-wrapper">
                          <img src="{{ ($id->image != null) ? Storage::disk('linode')->url($id->image) : asset('assets/images/1.jpg')}}" 
                               height="150" width="150" 
                               id="userimage" 
                               class="profile-image rounded-circle img-thumbnail" 
                               alt="Profile Image">
                          <div class="image-overlay">
                            <label for="inputGroupFile01" class="image-edit-btn">
                              <i class="fa fa-camera"></i>
                            </label>
                          </div>
                        </div>
                        <input type="file" class="d-none" name="image" id="inputGroupFile01" accept="image/*">
                        <p class="mt-3 mb-0 text-muted small">Click to change profile picture</p>
                        <label id="userimage_error" class="text-danger small error-field"></label>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="profile-info">
                        <h4 class="profile-name">{{ $id->name }}</h4>
                        <p class="profile-details">
                          <span class="badge badge-outline-primary me-2">{{ $id->usrtype }}</span>
                          <span class="text-muted">{{ $id->email }}</span>
                        </p>
                        <div class="profile-stats">
                          <div class="stat-item">
                            <span class="stat-label">Staff ID:</span>
                            <span class="stat-value">{{ $id->no_staf }}</span>
                          </div>
                          <div class="stat-item">
                            <span class="stat-label">Status:</span>
                            <span class="badge {{ $id->status == 'ACTIVE' ? 'badge-success' : 'badge-danger' }}">
                              {{ $id->status }}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Main Edit Form -->
              <div class="row g-4">
                
                <!-- Personal Information Section -->
                <div class="col-lg-6 mb-4">
                  <div class="card box-animated" style="animation-delay: 0.2s;">
                    <div class="card-header">
                      <h5 class="card-title mb-0">
                        <i class="fa fa-user text-primary me-2"></i>
                        Personal Information
                      </h5>
                    </div>
                    <div class="card-body">
                      <div class="form-group mb-4">
                        <label class="form-label" for="name">
                          <i class="fa fa-user me-2 text-primary"></i>Full Name
                        </label>
                        <input class="form-control form-control-lg" id="name" name="name" type="text" 
                               placeholder="Enter full name" value="{{ $id->name }}" required>
                      </div>

                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="form-group mb-3">
                            <label class="form-label" for="ic">
                              <i class="fa fa-id-card me-2 text-primary"></i>Identity Card
                            </label>
                            <input class="form-control form-control-lg" id="ic" name="ic" type="text" 
                                   placeholder="Enter IC number" value="{{ $id->ic }}" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group mb-3">
                            <label class="form-label" for="nostaf">
                              <i class="fa fa-id-badge me-2 text-primary"></i>Staff Number
                            </label>
                            <input class="form-control form-control-lg" id="nostaf" name="nostaf" type="text" 
                                   placeholder="Enter staff number" value="{{ $id->no_staf }}" required>
                          </div>
                        </div>
                      </div>

                      <div class="form-group mb-3">
                        <label class="form-label" for="email">
                          <i class="fa fa-envelope me-2 text-primary"></i>Email Address
                        </label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" 
                               placeholder="Enter email address" value="{{ $id->email }}" required>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Professional Information Section -->
                <div class="col-lg-6 mb-4">
                  <div class="card box-animated" style="animation-delay: 0.3s;">
                    <div class="card-header">
                      <h5 class="card-title mb-0">
                        <i class="fa fa-briefcase text-primary me-2"></i>
                        Professional Details
                      </h5>
                    </div>
                    <div class="card-body">
                      <div class="form-group mb-4">
                        <label class="form-label" for="usrtype">
                          <i class="fa fa-user-tag me-2 text-primary"></i>User Role
                        </label>
                        <select class="form-select form-select-lg" id="usrtype" name="usrtype" required>
                          <option value="" disabled>Please choose type</option>
                          <option value="ADM" {{ ($id->usrtype == "ADM") ? 'selected' : '' }}>Admin</option>
                          <option value="PL" {{ ($id->usrtype == "PL") ? 'selected' : '' }}>Program Lead</option>
                          <option value="DN" {{ ($id->usrtype == "DN") ? 'selected' : '' }}>Dean</option>
                          <option value="AO" {{ ($id->usrtype == "AO") ? 'selected' : '' }}>Administrative Officer</option>
                          <option value="RGS" {{ ($id->usrtype == "RGS") ? 'selected' : '' }}>Student Record</option>
                          <option value="AR" {{ ($id->usrtype == "AR") ? 'selected' : '' }}>Academic Registrar</option>
                          <option value="LCT" {{ ($id->usrtype == "LCT") ? 'selected' : '' }}>Lecturer</option>
                          <option value="FN" {{ ($id->usrtype == "FN") ? 'selected' : '' }}>Finance</option>
                          <option value="TS" {{ ($id->usrtype == "TS") ? 'selected' : '' }}>Treasurer</option>
                          <option value="OTR" {{ ($id->usrtype == "OTR") ? 'selected' : '' }}>Others</option>
                          <option value="COOP" {{ ($id->usrtype == "COOP") ? 'selected' : '' }}>Cooperation</option>
                        </select>
                      </div>

                      <div class="form-group mb-4">
                        <label class="form-label" for="faculty">
                          <i class="fa fa-university me-2 text-primary"></i>Faculty
                        </label>
                        <select class="form-select form-select-lg" id="faculty" name="faculty">
                          <option value="-" disabled>Select faculty</option>
                          @foreach ($faculty as $fcl)
                          <option value="{{ $fcl->id }}" {{ ($fcl->id == $id->faculty) ? 'selected' : '' }}>
                            {{ $fcl->facultyname }}
                          </option>
                          @endforeach
                        </select>
                      </div>

                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="form-group mb-3">
                            <label class="form-label" for="from">
                              <i class="fa fa-calendar-plus me-2 text-primary"></i>Start Date
                            </label>
                            <input type="date" class="form-control form-control-lg" id="from" name="from" 
                                   value="{{ $id->start }}" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group mb-3">
                            <label class="form-label" for="to">
                              <i class="fa fa-calendar-minus me-2 text-muted"></i>End Date
                              <small class="text-muted">(Optional)</small>
                            </label>
                            <input type="date" class="form-control form-control-lg" id="to" name="to" 
                                   value="{{ $id->end }}">
                          </div>
                        </div>
                      </div>

                      <!-- Program Assignment (Hidden by default) -->
                      <div class="program-assignment mt-3" id="program-card" style="display: none;">
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
                </div>

                <!-- Academic Qualifications Section -->
                <div class="col-12">
                  <div class="card box-animated" style="animation-delay: 0.4s;">
                    <div class="card-header">
                      <h5 class="card-title mb-0">
                        <i class="fa fa-graduation-cap text-primary me-2"></i>
                        Academic Qualifications
                      </h5>
                      <p class="mb-0 mt-2 opacity-75">Update educational background and qualifications</p>
                    </div>
                    <div class="card-body">
                      <div class="qualifications-container">
                        <div class="row g-3">
                          @foreach ($academics as $key => $ac)
                          @php
                            $ace = explode('|', $ac);
                            $isChecked = isset($academic[$key]) && $academic[$key] != null && $academic[$key]->academic_id == $ace[0];
                          @endphp
                          <div class="col-md-6">
                            <div class="qualification-card {{ $isChecked ? 'border-primary' : '' }}" data-level="{{ $ace[0] }}">
                              <div class="qualification-header">
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" id="{{ $ace[0] }}" 
                                         name="academic[]" value="{{ $ace[0] }}" {{ $isChecked ? 'checked' : '' }}>
                                  <label for="{{ $ace[0] }}" class="form-check-label qualification-label">
                                    <i class="fa fa-{{ $ace[0] == 'DP' ? 'certificate' : ($ace[0] == 'DG' ? 'user-graduate' : ($ace[0] == 'MS' ? 'medal' : 'trophy')) }} me-2"></i>
                                    <span class="qualification-title">{{ $ace[1] }}</span>
                                  </label>
                                </div>
                              </div>
                              <div class="qualification-details" id="details_{{ $ace[0] }}" style="display: {{ $isChecked ? 'block' : 'none' }};">
                                <div class="row g-3 mt-2">
                                  <div class="col-12">
                                    <input type="text" class="form-control" id="prg_{{ $ace[0] }}" 
                                           placeholder="Program name" name="prg[]" 
                                           value="{{ $isChecked ? $academic[$key]->academic_name : '' }}">
                                  </div>
                                  <div class="col-12">
                                    <input type="text" class="form-control" id="uni_{{ $ace[0] }}" 
                                           placeholder="University/Institution" name="uni[]" 
                                           value="{{ $isChecked ? $academic[$key]->university_name : '' }}">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Status & Comments Section -->
                <div class="col-12">
                  <div class="card box-animated" style="animation-delay: 0.5s;">
                    <div class="card-header">
                      <h5 class="card-title mb-0">
                        <i class="fa fa-cog text-primary me-2"></i>
                        Account Status & Notes
                      </h5>
                    </div>
                    <div class="card-body">
                      <div class="row g-4">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="form-label" for="status">
                              <i class="fa fa-toggle-on me-2 text-primary"></i>Account Status
                            </label>
                            <select class="form-select form-select-lg" id="status" name="status" required>
                              <option value="-" disabled>Select status</option>
                              <option value="ACTIVE" {{ ($id->status == "ACTIVE") ? 'selected' : '' }}>
                                <i class="fa fa-check"></i> Active
                              </option>
                              <option value="NOTACTIVE" {{ ($id->status == "NOTACTIVE") ? 'selected' : '' }}>
                                <i class="fa fa-times"></i> Inactive
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-8">
                          <div class="status-info-card">
                            <div class="status-indicator {{ $id->status == 'ACTIVE' ? 'status-active' : 'status-inactive' }}">
                              <i class="fa fa-{{ $id->status == 'ACTIVE' ? 'check-circle' : 'times-circle' }}"></i>
                              <span>Current Status: {{ $id->status }}</span>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Comments Section (shown when inactive) -->
                      <div class="comments-section mt-4" id="comment-card" style="display: {{ $id->status == 'NOTACTIVE' ? 'block' : 'none' }};">
                        <div class="form-group">
                          <label class="form-label">
                            <i class="fa fa-comment-alt me-2 text-primary"></i>
                            Description/Comments
                            <small class="text-muted">(Required for inactive accounts)</small>
                          </label>
                          <textarea id="comments" name="comments" class="form-control" rows="6" 
                                    placeholder="Enter description or reason for account status...">{{ $id->comment }}</textarea>
                          <span class="text-danger">@error('classdescription'){{ $message }}@enderror</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-12">
                  <div class="card box-animated" style="animation-delay: 0.6s;">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="action-info">
                          <p class="mb-0 text-muted">
                            <i class="fa fa-info-circle me-2"></i>
                            Last updated: {{ $id->updated_at ? $id->updated_at->format('M d, Y H:i') : 'Never' }}
                          </p>
                        </div>
                        <div class="action-buttons">
                          <button type="button" class="btn btn-secondary me-3" onclick="history.back()">
                            <i class="fa fa-arrow-left me-2"></i>Cancel
                          </button>
                          <button type="submit" class="btn btn-primary" id="saveBtn">
                            <i class="fa fa-save me-2"></i>Save Changes
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </form>
      </div>
    </section>
  </div>
</div>

<style>
/* Enhanced Profile Edit Styles */
.profile-overview-card .card-header {
  background: linear-gradient(135deg, var(--folder-color) 0%, var(--folder-hover) 100%);
}

.profile-image-container {
  position: relative;
  display: inline-block;
}

.profile-image-wrapper {
  position: relative;
  display: inline-block;
  border-radius: 50%;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
}

.profile-image-wrapper:hover {
  transform: scale(1.05);
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
}

.profile-image {
  transition: all 0.3s ease;
  border: 4px solid white;
}

.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  cursor: pointer;
  border-radius: 50%;
}

.profile-image-wrapper:hover .image-overlay {
  opacity: 1;
}

.image-edit-btn {
  color: white;
  font-size: 24px;
  cursor: pointer;
  margin: 0;
}

.profile-info {
  padding-left: 20px;
}

.profile-name {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--dark-color);
  margin-bottom: 10px;
}

.profile-details {
  margin-bottom: 20px;
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
  height: 100%;
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

.qualification-card.border-primary {
  border-color: var(--primary-color) !important;
  box-shadow: 0 0 20px rgba(67, 97, 238, 0.2);
  background: linear-gradient(145deg, #ffffff 0%, rgba(67, 97, 238, 0.02) 100%);
}

.qualification-card.border-primary::before {
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
  font-size: 1rem;
}

.qualification-details {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 15px;
  margin-top: 15px;
}

/* Status Section */
.status-info-card {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 20px;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.status-indicator {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1.1rem;
  font-weight: 600;
  padding: 12px 20px;
  border-radius: 50px;
  transition: all 0.3s ease;
}

.status-active {
  background: rgba(60, 179, 113, 0.1);
  color: var(--success-color);
  border: 2px solid rgba(60, 179, 113, 0.2);
}

.status-inactive {
  background: rgba(220, 53, 69, 0.1);
  color: var(--danger-color);
  border: 2px solid rgba(220, 53, 69, 0.2);
}

.comments-section {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 20px;
  border: 2px dashed #dee2e6;
}

/* Enhanced Form Controls */
.form-control-lg, .form-select-lg {
  padding: 15px 20px;
  font-size: 1rem;
  border-radius: 12px;
  border: 2px solid #e9ecef;
  transition: all 0.3s ease;
  position: relative;
  z-index: 1;
}

.form-control-lg:focus, .form-select-lg:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
  transform: translateY(-2px);
  z-index: 10;
}

/* Form Group Spacing */
.form-group {
  margin-bottom: 1.5rem;
  position: relative;
  z-index: 1;
}

.card-body {
  position: relative;
  z-index: 2;
  overflow: visible;
}

/* Ensure proper spacing between sections */
.row.g-4 > * {
  margin-bottom: 1.5rem;
}

.col-lg-6.mb-4 {
  margin-bottom: 2rem !important;
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

/* Action Buttons */
.action-buttons .btn {
  padding: 12px 30px;
  font-weight: 600;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.action-buttons .btn:hover {
  transform: translateY(-2px);
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
  
  .action-buttons {
    width: 100%;
    display: flex;
    gap: 10px;
  }
  
  .action-buttons .btn {
    flex: 1;
  }
}

/* Animation */
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
</style>

<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  var usertype = "";
  var selected_faculty = "";
  var check = "";
  var program_id = "{{ $id->programid }}";
  var user_ic = "{{ $id->ic }}";

  // Initialize form state
  usertype = $("#usrtype").val();
  var status = $("#status").val();

  // Initialize CKEditor for comments
  if (document.querySelector('#comments')) {
    ClassicEditor
      .create(document.querySelector('#comments'), { height: '200px' })
      .then(newEditor => { editor = newEditor; })
      .catch(error => { console.log(error); });
  }

  // Handle user type logic
  if (usertype == 'PL' || usertype == 'AO' || usertype == 'DN') {
    $('#program-card').slideDown(300);
    $('#program').prop('required', true);
    selected_faculty = $("#faculty").val();
    getProgramOption2(selected_faculty, user_ic);
  } else {
    $('#program-card').slideUp(300);
    $('#program').prop('required', false);
  }

  // Handle status logic
  if (status == 'NOTACTIVE') {
    $('#comment-card').slideDown(300);
    $('#comments').prop('required', true);
  } else {
    $('#comment-card').slideUp(300);
    $('#comments').prop('required', false);
  }

  // Profile image preview
  $('#inputGroupFile01').on('change', function(evt) {
    const [file] = this.files;
    if (file) {
      $('#userimage').attr('src', URL.createObjectURL(file));
      
      // Show upload feedback
      $('.profile-image-wrapper').addClass('uploading');
      setTimeout(() => {
        $('.profile-image-wrapper').removeClass('uploading');
      }, 1000);
    }
  });

  // User type change handler
  $('#usrtype').change(function() {
    usertype = $(this).val();
    
    if (usertype == 'PL' || usertype == 'AO' || usertype == 'DN') {
      $('#program-card').slideDown(300);
      $('#program').prop('required', true);
    } else {
      $('#program-card').slideUp(300);
      $('#program').prop('required', false);
    }
  });

  // Status change handler
  $('#status').change(function() {
    var status = $(this).val();
    var statusIndicator = $('.status-indicator');
    
    if (status == 'NOTACTIVE') {
      $('#comment-card').slideDown(300);
      $('#comments').prop('required', true);
      statusIndicator.removeClass('status-active').addClass('status-inactive');
      statusIndicator.html('<i class="fa fa-times-circle"></i><span>Current Status: INACTIVE</span>');
    } else {
      $('#comment-card').slideUp(300);
      $('#comments').prop('required', false);
      statusIndicator.removeClass('status-inactive').addClass('status-active');
      statusIndicator.html('<i class="fa fa-check-circle"></i><span>Current Status: ACTIVE</span>');
    }
  });

  // Faculty change handler
  $('#faculty').change(function() {
    selected_faculty = $(this).val();
    getProgramOption2(selected_faculty, user_ic);
  });

  // Qualification checkbox handlers
  $("input[type='checkbox'][name='academic[]']").change(function() {
    var level = this.value;
    var isChecked = this.checked;
    var detailsContainer = $('#details_' + level);
    var programInput = $('#prg_' + level);
    var universityInput = $('#uni_' + level);
    var card = $(this).closest('.qualification-card');
    
    if (isChecked) {
      detailsContainer.slideDown(300);
      programInput.prop('required', true);
      universityInput.prop('required', true);
      card.addClass('border-primary');
    } else {
      detailsContainer.slideUp(300);
      programInput.prop('required', false);
      universityInput.prop('required', false);
      programInput.val('');
      universityInput.val('');
      card.removeClass('border-primary');
    }
  });

  // Form submission
  $('#editLecturerForm').submit(function(e) {
    // Show loading state
    $('#saveBtn').html('<i class="fa fa-spinner fa-spin me-2"></i>Saving Changes...');
    $('#saveBtn').prop('disabled', true);
  });

  // Real-time validation
  $('input[required], select[required]').on('input change', function() {
    if ($(this).val()) {
      $(this).removeClass('is-invalid').addClass('is-valid');
    } else {
      $(this).removeClass('is-valid');
    }
  });

  // Get program options function
  function getProgramOption2(faculty, ic) {
    if (!faculty) return;
    
    $('#program').addClass('loading');
    
    return $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('admin/getProgramoptions2') }}",
      method: 'POST',
      data: {faculty: faculty, ic: ic},
      success: function(data) {
        $('#program').html(data).removeClass('loading');
      },
      error: function(err) {
        console.log(err);
        $('#program').removeClass('loading');
        // Show error message
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load program options.',
            confirmButtonColor: '#4361ee'
          });
        } else {
          alert('Failed to load program options.');
        }
      }
    });
  }
});
</script>
@endsection
