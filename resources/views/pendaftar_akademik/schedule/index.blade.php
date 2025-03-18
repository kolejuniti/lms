@extends(Auth::user()->usrtype == 'AR' ? 'layouts.pendaftar_akademik' : 'layouts.ketua_program');

@section('main')
<!-- Content Wrapper -->
<div class="content-wrapper">
  <div class="container-full">
    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title fw-bold">
            <span class="text-gradient-primary">{{ request()->type == 'std' ? 'Student' : (request()->type == 'lct' ? 'Lecturer' : 'Room') }}</span> Directory
          </h4>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Glass-morphism Search Card -->
      <div class="card bg-glass rounded-xl mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="row g-4 align-items-center">
            <div class="col-lg-7">
              <div class="search-wrapper">
              <div class="position-relative">
                <input type="text" id="search-input" class="form-control form-control-lg bg-light-primary border-0 rounded-pill ps-5" placeholder="Search by name, ID, or program..." style="text-align: center;">
                <i class="ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-primary"></i>
              </div>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="d-flex gap-2 justify-content-lg-end">
                <button class="btn btn-light-primary btn-lg px-4 rounded-pill" id="filterBtn" type="button" data-bs-toggle="collapse" data-bs-target="#filterOptions">
                  <i class="fa fa-sliders-h me-2"></i> Filters
                </button>
                <div class="btn-group">
                  <button id="view-toggle" type="button" class="btn btn-primary btn-lg px-4 rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-th me-2"></i> View
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center" href="#" id="table-view"><i class="fa fa-table me-2"></i>Table View</a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="#" id="card-view"><i class="fa fa-th-large me-2"></i>Card View</a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="#" id="grid-view"><i class="fa fa-grip-horizontal me-2"></i>Grid View</a></li>
                  </ul>
                </div>
                <div class="btn-group">
                  <button class="btn btn-light-primary btn-lg px-4 rounded-pill" id="exportBtn" data-bs-toggle="dropdown">
                    <i class="fa fa-download me-2"></i> Export
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center" href="#" id="export-excel"><i class="far fa-file-excel me-2 text-success"></i>Excel</a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="#" id="export-pdf"><i class="far fa-file-pdf me-2 text-danger"></i>PDF</a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="#" id="export-csv"><i class="fa fa-file-csv me-2 text-primary"></i>CSV</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Collapsible Filter Options -->
          <div class="collapse mt-4" id="filterOptions">
            <div class="card card-body border-0 bg-light rounded-xl">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label fw-bold">{{ request()->type == 'std' ? 'Program' : (request()->type == 'lct' ? 'Department' : 'Building') }}</label>
                  <select class="form-select form-select-lg rounded-pill" id="filter-category">
                    <option value="">All {{ request()->type == 'std' ? 'Programs' : (request()->type == 'lct' ? 'Departments' : 'Buildings') }}</option>
                    @if(request()->type == 'std')
                      @php
                        $uniquePrograms = collect($data['student'] ?? [])->unique('progcode')->pluck('progcode', 'progname');
                      @endphp
                      @foreach($uniquePrograms as $name => $code)
                        <option value="{{ $code }}">{{ $code }} - {{ $name }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                
                <div class="col-md-4">
                  <label class="form-label fw-bold">Sort By</label>
                  <select class="form-select form-select-lg rounded-pill" id="sort-by">
                    <option value="name-asc">Name (A-Z)</option>
                    <option value="name-desc">Name (Z-A)</option>
                    @if(request()->type == 'std')
                    <option value="program-asc">Program (A-Z)</option>
                    @endif
                  </select>
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                  <button type="button" class="btn btn-primary btn-lg w-100 rounded-pill" id="apply-filters">
                    <i class="fa fa-check me-2"></i> Apply Filters
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Stats Summary -->
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card h-100 border-0 rounded-xl shadow-hover overflow-hidden">
            <div class="card-body p-4">
              <div class="d-flex align-items-center">
                <div class="avatar-lg rounded-xl bg-gradient-primary text-white flex-shrink-0 me-3 d-flex align-items-center justify-content-center">
                  <i class="fa fa-users fs-1"></i>
                </div>
                <div>
                  <p class="text-muted mb-1">Total {{ request()->type == 'std' ? 'Students' : (request()->type == 'lct' ? 'Lecturers' : 'Rooms') }}</p>
                  <h3 class="fw-bold mb-0">{{ request()->type == 'std' ? count($data['student'] ?? []) : (request()->type == 'lct' ? count($data['lecturer'] ?? []) : count($data['room'] ?? [])) }}</h3>
                </div>
              </div>
              <div class="progress mt-4 bg-light-primary" style="height: 6px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card h-100 border-0 rounded-xl shadow-hover overflow-hidden">
            <div class="card-body p-4">
              <div class="d-flex align-items-center">
                <div class="avatar-lg rounded-xl bg-gradient-success text-white flex-shrink-0 me-3 d-flex align-items-center justify-content-center">
                  <i class="fa fa-graduation-cap fs-1"></i>
                </div>
                <div>
                  <p class="text-muted mb-1">{{ request()->type == 'std' ? 'Programs' : (request()->type == 'lct' ? 'Departments' : 'Buildings') }}</p>
                  <h3 class="fw-bold mb-0">{{ request()->type == 'std' ? collect($data['student'] ?? [])->unique('progcode')->count() : '5' }}</h3>
                </div>
              </div>
              <div class="progress mt-4 bg-light-success" style="height: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card h-100 border-0 rounded-xl shadow-hover overflow-hidden">
            <div class="card-body p-4">
              <div class="d-flex align-items-center">
                <div class="avatar-lg rounded-xl bg-gradient-warning text-white flex-shrink-0 me-3 d-flex align-items-center justify-content-center">
                  <i class="fa fa-calendar-alt fs-1"></i>
                </div>
                <div>
                  <p class="text-muted mb-1">Current Session</p>
                  <h3 class="fw-bold mb-0">{{ isset($data['session'][0]->SessionName) ? $data['session'][0]->SessionName : 'N/A' }}</h3>
                </div>
              </div>
              <div class="progress mt-4 bg-light-warning" style="height: 6px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Data Display -->
      <div class="card border-0 rounded-xl shadow-sm overflow-hidden">
        <div class="card-header bg-white border-bottom-0 py-4">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <div class="avatar-md rounded-xl bg-light-primary text-primary me-3">
                <i class="fa {{ request()->type == 'std' ? 'fa-user-graduate' : (request()->type == 'lct' ? 'fa-chalkboard-teacher' : 'fa-door-open') }} fs-3"></i>
              </div>
              <h4 class="fw-bold mb-0">{{ request()->type == 'std' ? 'Student List' : (request()->type == 'lct' ? 'Lecturer List' : 'Room List') }}</h4>
            </div>
            <div>
              <button class="btn btn-sm btn-light-primary rounded-pill" id="refreshBtn">
                <i class="fa fa-sync-alt me-1"></i> Refresh
              </button>
            </div>
          </div>
        </div>
        
        <!-- Tab Navigation for Different Views -->
        <ul class="nav nav-tabs tab-nav-underline nav-justified border-bottom-0 px-4" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active px-4 py-3" id="table-tab" data-bs-toggle="tab" data-bs-target="#table-view-container" type="button" role="tab" aria-selected="true">
              <i class="fa fa-table me-2"></i> Table View
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-3" id="card-tab" data-bs-toggle="tab" data-bs-target="#card-view-container" type="button" role="tab" aria-selected="false">
              <i class="fa fa-th-large me-2"></i> Card View
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-3" id="grid-tab" data-bs-toggle="tab" data-bs-target="#grid-view-container" type="button" role="tab" aria-selected="false">
              <i class="fa fa-grip-horizontal me-2"></i> Grid View
            </button>
          </li>
        </ul>
        
        <div class="tab-content">
          <!-- Table View -->
          <div class="tab-pane fade show active" id="table-view-container" role="tabpanel" aria-labelledby="table-tab">
            <div class="card-body p-0">
              @if(request()->type == 'lct')
              <div class="table-responsive">
                <table id="complex_header" class="table table-hover border-0 mb-0">
                  <thead>
                    <tr class="bg-light">
                      <th class="fw-bold border-0">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="select-all">
                        </div>
                      </th>
                      <th class="fw-bold border-0">Lecturer</th>
                      <th class="fw-bold border-0">IC</th>
                      <th class="fw-bold border-0">Department</th>
                      <th class="fw-bold border-0">Status</th>
                      <th class="fw-bold border-0 text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="table">
                    @if(isset($data['lecturer']) && count($data['lecturer']) > 0)
                      @foreach ($data['lecturer'] as $key => $lct)
                        <tr class="data-row align-middle">
                          <td>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="row-{{ $key }}">
                            </div>
                          </td>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="avatar avatar-md bg-gradient-primary text-white rounded-circle me-3">
                                @php
                                  $nameParts = explode(' ', $lct->name);
                                  if(count($nameParts) >= 2) {
                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                                  } else {
                                    $initials = strtoupper(substr($lct->name, 0, 2));
                                  }
                                @endphp
                                <span>{{ $initials }}</span>
                              </div>
                              <div>
                                <h6 class="mb-0 fw-semibold">{{ $lct->name }}</h6>
                                <small class="text-muted">Lecturer</small>
                              </div>
                            </div>
                          </td>
                          <td>{{ $lct->ic }}</td>
                          <td>Computer Science</td>
                          <td><span class="badge bg-success-light text-success rounded-pill px-3">Active</span></td>
                          <td>
                            <div class="d-flex gap-2 justify-content-end">
                              <a class="btn btn-sm btn-icon btn-primary rounded-circle" href="/AR/schedule/scheduleTable/{{ $lct->ic }}?type={{ request()->type }}" data-bs-toggle="tooltip" title="View Schedule">
                                <i class="ti-calendar"></i>
                              </a>
                              <button class="btn btn-sm btn-icon btn-light-primary rounded-circle view-details" data-id="{{ $lct->ic }}" data-bs-toggle="tooltip" title="View Details">
                                <i class="ti-eye"></i>
                              </button>
                              <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-light-secondary rounded-circle" data-bs-toggle="dropdown">
                                  <i class="ti-more-alt"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-pencil me-2"></i>Edit</a></li>
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-trash me-2"></i>Delete</a></li>
                                </ul>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="6" class="text-center py-5">
                          <div class="py-4">
                            <i class="fa fa-search fa-3x text-light-primary mb-3"></i>
                            <h5>No lecturers found</h5>
                            <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                          </div>
                        </td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              @elseif(request()->type == 'std')
              <div class="table-responsive">
                <table id="complex_header" class="table table-hover border-0 mb-0">
                  <thead>
                    <tr class="bg-light">
                      <th class="fw-bold border-0">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="select-all">
                        </div>
                      </th>
                      <th class="fw-bold border-0">Student</th>
                      <th class="fw-bold border-0">IC</th>
                      <th class="fw-bold border-0">Program</th>
                      <th class="fw-bold border-0">Status</th>
                      <th class="fw-bold border-0 text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="table">
                    @if(isset($data['student']) && count($data['student']) > 0)
                      @foreach ($data['student'] as $key => $std)
                        <tr class="data-row align-middle">
                          <td>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="row-{{ $key }}">
                            </div>
                          </td>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="avatar avatar-md bg-gradient-info text-white rounded-circle me-3">
                                @php
                                  $nameParts = explode(' ', $std->name);
                                  if(count($nameParts) >= 2) {
                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                                  } else {
                                    $initials = strtoupper(substr($std->name, 0, 2));
                                  }
                                @endphp
                                <span>{{ $initials }}</span>
                              </div>
                              <div>
                                <h6 class="mb-0 fw-semibold">{{ $std->name }}</h6>
                                <small class="text-muted">Student</small>
                              </div>
                            </div>
                          </td>
                          <td>{{ $std->ic }}</td>
                          <td>
                            <div class="d-flex align-items-center">
                              <span class="badge bg-primary-light text-primary rounded-pill me-2">{{ $std->progcode }}</span>
                              <span class="text-truncate" style="max-width: 180px;">{{ $std->progname }}</span>
                            </div>
                          </td>
                          <td><span class="badge bg-success-light text-success rounded-pill px-3">Active</span></td>
                          <td>
                            <div class="d-flex gap-2 justify-content-end">
                              <a class="btn btn-sm btn-icon btn-primary rounded-circle" href="/AR/schedule/scheduleTable/{{ $std->ic }}?type={{ request()->type }}" data-bs-toggle="tooltip" title="View Schedule">
                                <i class="ti-calendar"></i>
                              </a>
                              <button class="btn btn-sm btn-icon btn-light-primary rounded-circle view-details" data-id="{{ $std->ic }}" data-bs-toggle="tooltip" title="View Details">
                                <i class="ti-eye"></i>
                              </button>
                              <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-light-secondary rounded-circle" data-bs-toggle="dropdown">
                                  <i class="ti-more-alt"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-pencil me-2"></i>Edit</a></li>
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-trash me-2"></i>Delete</a></li>
                                </ul>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="6" class="text-center py-5">
                          <div class="py-4">
                            <i class="fa fa-search fa-3x text-light-primary mb-3"></i>
                            <h5>No students found</h5>
                            <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                          </div>
                        </td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              @elseif(request()->type == 'lcr')
              <div class="table-responsive">
                <table id="complex_header" class="table table-hover border-0 mb-0">
                  <thead>
                    <tr class="bg-light">
                      <th class="fw-bold border-0">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="select-all">
                        </div>
                      </th>
                      <th class="fw-bold border-0">Room</th>
                      <th class="fw-bold border-0">Time</th>
                      <th class="fw-bold border-0">Capacity</th>
                      <th class="fw-bold border-0">Features</th>
                      <th class="fw-bold border-0 text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="table">
                    @if(isset($data['room']) && count($data['room']) > 0)
                      @foreach ($data['room'] as $key => $rm)
                        <tr class="data-row align-middle">
                          <td>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="row-{{ $key }}">
                            </div>
                          </td>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="avatar avatar-md bg-gradient-warning text-white rounded-circle me-3">
                                @if(is_string($rm->name) && !empty($rm->name))
                                  @php
                                    $nameParts = explode(' ', $rm->name);
                                    if(count($nameParts) >= 2) {
                                      $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                                    } else {
                                      $initials = strtoupper(substr($rm->name, 0, 2));
                                    }
                                  @endphp
                                  {{ $initials }}
                                @else
                                  <i class="fa fa-door-open"></i>
                                @endif
                              </div>
                              <div>
                                <h6 class="mb-0 fw-semibold">{{ $rm->name }}</h6>
                                <small class="text-muted">{{ $rm->total_hour }} hours/day</small>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="d-flex flex-column">
                              <span><i class="far fa-clock text-info me-1"></i> {{ (new DateTime($rm->start))->format('h:i A') }} - {{ (new DateTime($rm->end))->format('h:i A') }}</span>
                              <small class="text-muted">{{ $rm->weekend == 1 ? 'Includes weekends' : 'Weekdays only' }}</small>
                            </div>
                          </td>
                          <td>
                            <span class="badge bg-success-light text-success rounded-pill px-3">
                              <i class="fa fa-user-friends me-1"></i> {{ $rm->capacity }} seats
                            </span>
                          </td>
                          <td>
                            <div class="d-flex gap-2">
                              @if($rm->projector == 'Yes')
                              <span class="badge bg-info-light text-info rounded-pill px-3"><i class="fa fa-projector me-1"></i> Projector</span>
                              @endif
                              
                              @if(!empty($rm->description))
                              <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ strip_tags($rm->description) }}">
                                <i class="fa fa-info-circle text-primary"></i>
                              </a>
                              @endif
                            </div>
                          </td>
                          <td>
                            <div class="d-flex gap-2 justify-content-end">
                              <a class="btn btn-sm btn-icon btn-primary rounded-circle" href="/AR/schedule/scheduleTable/{{ $rm->id }}?type={{ request()->type }}" data-bs-toggle="tooltip" title="View Schedule">
                                <i class="ti-calendar"></i>
                              </a>
                              <button class="btn btn-sm btn-icon btn-light-primary rounded-circle view-details" data-id="{{ $rm->id }}" data-bs-toggle="tooltip" title="View Details">
                                <i class="ti-eye"></i>
                              </button>
                              <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-light-secondary rounded-circle" data-bs-toggle="dropdown">
                                  <i class="ti-more-alt"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-pencil me-2"></i>Edit</a></li>
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-trash me-2"></i>Delete</a></li>
                                </ul>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="6" class="text-center py-5">
                          <div class="py-4">
                            <i class="fa fa-search fa-3x text-light-primary mb-3"></i>
                            <h5>No rooms found</h5>
                            <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                          </div>
                        </td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              @endif
            </div>
          </div>
          
          <!-- Card View -->
          <div class="tab-pane fade" id="card-view-container" role="tabpanel" aria-labelledby="card-tab">
            <div class="card-body p-4">
              <div class="row g-5">
                @if(request()->type == 'lct' && isset($data['lecturer']) && count($data['lecturer']) > 0)
                  @foreach ($data['lecturer'] as $key => $lct)
                    <div class="col-md-6 col-xl-4 data-card">
                      <div class="card border-0 rounded-xl shadow-hover h-100" style="min-height: 300px;">
                        <div class="position-relative">
                          <div class="card-body p-4 pt-5 pb-5">
                            <div class="position-absolute top-0 end-0 m-3">
                              <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-light-secondary rounded-circle" data-bs-toggle="dropdown">
                                  <i class="ti-more-alt">{{ substr($lct->name, 0, 1) }}</i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-pencil me-2"></i>Edit</a></li>
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-trash me-2"></i>Delete</a></li>
                                </ul>
                              </div>
                            </div>
                            <div class="text-center mb-5">
                              <div class="avatar avatar-xl bg-gradient-primary text-white mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                @php
                                  $nameParts = explode(' ', $lct->name);
                                  if(count($nameParts) >= 2) {
                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                                  } else {
                                    $initials = strtoupper(substr($lct->name, 0, 2));
                                  }
                                @endphp
                                <span class="fs-3">{{ $initials }}</span>
                              </div>
                              <h5 class="fw-bold mb-2">{{ $lct->name }}</h5>
                              <span class="badge bg-primary-light text-primary rounded-pill px-3 py-2 mt-2 d-inline-block">Lecturer</span>
                            </div>
                            <div class="list-group list-group-flush mb-4">
                              <div class=" border-0 px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted">IC:</span>
                                <span class="fw-semibold">{{ $lct->ic }}</span>
                              </div>
                              <div class=" border-0 px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted">Department:</span>
                                <span class="fw-semibold">Computer Science</span>
                              </div>
                              <div class=" border-0 px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-success-light text-success rounded-pill px-3">Active</span>
                              </div>
                            </div>
                          </div>
                          <div class="card-footer border-top-dashed bg-light-primary rounded-bottom-xl p-4">
                            <div class="d-flex justify-content-between">
                              <a href="/AR/schedule/scheduleTable/{{ $lct->ic }}?type={{ request()->type }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="ti-calendar me-1"></i> Schedule
                              </a>
                              <button class="btn btn-light-primary btn-sm rounded-pill px-3 view-details" data-id="{{ $lct->ic }}">
                                <i class="ti-eye me-1"></i> Details
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @elseif(request()->type == 'std' && isset($data['student']) && count($data['student']) > 0)
                  @foreach ($data['student'] as $key => $std)
                    <div class="col-md-6 col-xl-4 data-card">
                      <div class="card border-0 rounded-xl shadow-hover h-100" style="min-height: 300px;">
                        <div class="position-relative">
                          <div class="card-body p-4 pt-5 pb-5">
                            <div class="position-absolute top-0 end-0 m-3">
                              <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-light-secondary rounded-circle" data-bs-toggle="dropdown">
                                  <i class="ti-more-alt"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-pencil me-2"></i>Edit</a></li>
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-trash me-2"></i>Delete</a></li>
                                </ul>
                              </div>
                            </div>
                            <div class="text-center mb-5">
                              <div class="avatar avatar-xl bg-gradient-info text-white mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                @php
                                  $nameParts = explode(' ', $std->name);
                                  if(count($nameParts) >= 2) {
                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                                  } else {
                                    $initials = strtoupper(substr($std->name, 0, 2));
                                  }
                                @endphp
                                <span class="fs-3">{{ $initials }}</span>
                              </div>
                              <h5 class="fw-bold mb-2">{{ $std->name }}</h5>
                              <span class="badge bg-primary-light text-primary rounded-pill px-3 py-2 mt-2 d-inline-block">{{ $std->progcode }}</span>
                            </div>
                            <div class="list-group list-group-flush mb-4">
                              <div class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted">IC:</span>
                                <span class="fw-semibold">{{ $std->ic }}</span>
                              </div>
                              <div class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted">Program:</span>
                                <span class="fw-semibold text-truncate" style="max-width: 200px;">{{ $std->progname }}</span>
                              </div>
                              <div class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-success-light text-success rounded-pill px-3">Active</span>
                              </div>
                            </div>
                          </div>
                          <div class="card-footer border-top-dashed bg-light-primary rounded-bottom-xl p-4">
                            <div class="d-flex justify-content-between">
                              <a href="/AR/schedule/scheduleTable/{{ $std->ic }}?type={{ request()->type }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="ti-calendar me-1"></i> Schedule
                              </a>
                              <button class="btn btn-light-primary btn-sm rounded-pill px-3 view-details" data-id="{{ $std->ic }}">
                                <i class="ti-eye me-1"></i> Details
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @elseif(request()->type == 'lcr' && isset($data['room']) && count($data['room']) > 0)
                  @foreach ($data['room'] as $key => $rm)
                    <div class="col-md-6 col-xl-4 data-card">
                      <div class="card border-0 rounded-xl shadow-hover h-100" style="min-height: 300px;">
                        <div class="position-relative">
                          <div class="card-body p-4 pt-5 pb-5">
                            <div class="position-absolute top-0 end-0 m-3">
                              <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-light-secondary rounded-circle" data-bs-toggle="dropdown">
                                  <i class="ti-more-alt"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-pencil me-2"></i>Edit</a></li>
                                  <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-trash me-2"></i>Delete</a></li>
                                </ul>
                              </div>
                            </div>
                            <div class="text-center mb-5">
                              <div class="avatar avatar-xl bg-gradient-warning text-white mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                @if(is_string($rm->name) && !empty($rm->name))
                                  @php
                                    $nameParts = explode(' ', $rm->name);
                                    if(count($nameParts) >= 2) {
                                      $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                                    } else {
                                      $initials = strtoupper(substr($rm->name, 0, 2));
                                    }
                                  @endphp
                                  {{ $initials }}
                                @else
                                  <i class="fa fa-door-open"></i>
                                @endif
                              </div>
                              <h5 class="fw-bold mb-2">{{ $rm->name }}</h5>
                              <span class="badge bg-warning-light text-warning rounded-pill px-3 py-2 mt-2 d-inline-block">{{ $rm->total_hour }} hours/day</span>
                            </div>
                            <div class="list-group list-group-flush mb-4">
                              <div class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted">Time:</span>
                                <span class="fw-semibold">{{ (new DateTime($rm->start))->format('h:i A') }} - {{ (new DateTime($rm->end))->format('h:i A') }}</span>
                              </div>
                              <div class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted">Capacity:</span>
                                <span class="fw-semibold">{{ $rm->capacity }} seats</span>
                              </div>
                              <div class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted">Features:</span>
                                <div>
                                  @if($rm->projector == 'Yes')
                                  <span class="badge bg-info-light text-info rounded-pill px-2">Projector</span>
                                  @endif
                                  @if($rm->weekend == 1)
                                  <span class="badge bg-warning-light text-warning rounded-pill px-2">Weekend</span>
                                  @endif
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-footer border-top-dashed bg-light-primary rounded-bottom-xl p-4">
                            <div class="d-flex justify-content-between">
                              <a href="/AR/schedule/scheduleTable/{{ $rm->id }}?type={{ request()->type }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="ti-calendar me-1"></i> Schedule
                              </a>
                              <button class="btn btn-light-primary btn-sm rounded-pill px-3 view-details" data-id="{{ $rm->id }}">
                                <i class="ti-eye me-1"></i> Details
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="col-12">
                    <div class="text-center py-5">
                      <i class="fa fa-search fa-4x text-light-primary mb-3"></i>
                      <h4>No data available</h4>
                      <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
          
          <!-- Grid View -->
          <div class="tab-pane fade" id="grid-view-container" role="tabpanel" aria-labelledby="grid-tab">
            <div class="card-body">
              <div class="row g-3">
                @if(request()->type == 'lct' && isset($data['lecturer']) && count($data['lecturer']) > 0)
                  @foreach ($data['lecturer'] as $key => $lct)
                    <div class="col-md-4 col-lg-3 col-sm-6 data-card">
                      <div class="card border-0 rounded-xl shadow-hover text-center">
                        <div class="card-body p-3">
                          <div class="position-absolute top-0 end-0 m-2">
                            <div class="dropdown">
                              <button class="btn btn-xs btn-icon btn-light-secondary rounded-circle" data-bs-toggle="dropdown">
                                <i class="ti-more-alt"></i>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-eye me-2"></i>View Details</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-calendar me-2"></i>Schedule</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-pencil me-2"></i>Edit</a></li>
                              </ul>
                            </div>
                          </div>
                          <div class="avatar avatar-xl bg-gradient-primary text-white mx-auto mb-3 rounded-circle">
                            @php
                              $nameParts = explode(' ', $lct->name);
                              if(count($nameParts) >= 2) {
                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                              } else {
                                $initials = strtoupper(substr($lct->name, 0, 2));
                              }
                            @endphp
                            <span class="fs-4">{{ $initials }}</span>
                          </div>
                          <h6 class="fw-bold mb-1 text-truncate">{{ $lct->name }}</h6>
                          <p class="small text-muted mb-2">{{ $lct->ic }}</p>
                          <div class="d-flex justify-content-center gap-1 mt-3">
                            <a href="/AR/schedule/scheduleTable/{{ $lct->ic }}?type={{ request()->type }}" class="btn btn-xs btn-icon btn-primary rounded-circle" data-bs-toggle="tooltip" title="View Schedule">
                              <i class="ti-calendar"></i>
                            </a>
                            <button class="btn btn-xs btn-icon btn-light-primary rounded-circle view-details" data-id="{{ $lct->ic }}" data-bs-toggle="tooltip" title="View Details">
                              <i class="ti-eye"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @elseif(request()->type == 'std' && isset($data['student']) && count($data['student']) > 0)
                  @foreach ($data['student'] as $key => $std)
                    <div class="col-md-4 col-lg-3 col-sm-6 data-card">
                      <div class="card border-0 rounded-xl shadow-hover text-center">
                        <div class="card-body p-3">
                          <div class="position-absolute top-0 end-0 m-2">
                            <div class="dropdown">
                              <button class="btn btn-xs btn-icon btn-light-secondary rounded-circle" data-bs-toggle="dropdown">
                                <i class="ti-more-alt"></i>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-eye me-2"></i>View Details</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-calendar me-2"></i>Schedule</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-pencil me-2"></i>Edit</a></li>
                              </ul>
                            </div>
                          </div>
                          <div class="avatar avatar-xl bg-gradient-info text-white mx-auto mb-3 rounded-circle">
                            @php
                              $nameParts = explode(' ', $std->name);
                              if(count($nameParts) >= 2) {
                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                              } else {
                                $initials = strtoupper(substr($std->name, 0, 2));
                              }
                            @endphp
                            <span class="fs-4">{{ $initials }}</span>
                          </div>
                          <h6 class="fw-bold mb-1 text-truncate">{{ $std->name }}</h6>
                          <p class="small text-muted mb-0">{{ $std->ic }}</p>
                          <span class="badge bg-primary-light text-primary rounded-pill px-2 mb-2">{{ $std->progcode }}</span>
                          <div class="d-flex justify-content-center gap-1 mt-3">
                            <a href="/AR/schedule/scheduleTable/{{ $std->ic }}?type={{ request()->type }}" class="btn btn-xs btn-icon btn-primary rounded-circle" data-bs-toggle="tooltip" title="View Schedule">
                              <i class="ti-calendar"></i>
                            </a>
                            <button class="btn btn-xs btn-icon btn-light-primary rounded-circle view-details" data-id="{{ $std->ic }}" data-bs-toggle="tooltip" title="View Details">
                              <i class="ti-eye"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @elseif(request()->type == 'lcr' && isset($data['room']) && count($data['room']) > 0)
                  @foreach ($data['room'] as $key => $rm)
                    <div class="col-md-4 col-lg-3 col-sm-6 data-card">
                      <div class="card border-0 rounded-xl shadow-hover text-center">
                        <div class="card-body p-3">
                          <div class="position-absolute top-0 end-0 m-2">
                            <div class="dropdown">
                              <button class="btn btn-xs btn-icon btn-light-secondary rounded-circle" data-bs-toggle="dropdown">
                                <i class="ti-more-alt"></i>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-eye me-2"></i>View Details</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-calendar me-2"></i>Schedule</a></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="ti-pencil me-2"></i>Edit</a></li>
                              </ul>
                            </div>
                          </div>
                          <div class="avatar avatar-xl bg-gradient-warning text-white mx-auto mb-3 rounded-circle">
                            @if(is_string($rm->name) && !empty($rm->name))
                              @php
                                $nameParts = explode(' ', $rm->name);
                                if(count($nameParts) >= 2) {
                                  $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                                } else {
                                  $initials = strtoupper(substr($rm->name, 0, 2));
                                }
                              @endphp
                              <span class="fs-4">{{ $initials }}</span>
                            @else
                              <i class="fa fa-door-open fs-4"></i>
                            @endif
                          </div>
                          <h6 class="fw-bold mb-1 text-truncate">{{ $rm->name }}</h6>
                          <p class="small text-muted mb-0">{{ $rm->capacity }} seats</p>
                          <span class="badge bg-warning-light text-warning rounded-pill px-2 mb-2">{{ (new DateTime($rm->start))->format('h:i A') }}</span>
                          <div class="d-flex justify-content-center gap-1 mt-3">
                            <a href="/AR/schedule/scheduleTable/{{ $rm->id }}?type={{ request()->type }}" class="btn btn-xs btn-icon btn-primary rounded-circle" data-bs-toggle="tooltip" title="View Schedule">
                              <i class="ti-calendar"></i>
                            </a>
                            <button class="btn btn-xs btn-icon btn-light-primary rounded-circle view-details" data-id="{{ $rm->id }}" data-bs-toggle="tooltip" title="View Details">
                              <i class="ti-eye"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="col-12">
                    <div class="text-center py-5">
                      <i class="fa fa-search fa-4x text-light-primary mb-3"></i>
                      <h4>No data available</h4>
                      <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.card -->
      
      <!-- Pagination -->
      <div class="card bg-transparent border-0 mt-4">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div class="text-muted">
            Showing <span id="showing-entries" class="fw-bold text-primary">
              {{ request()->type == 'std' ? count($data['student'] ?? []) : (request()->type == 'lct' ? count($data['lecturer'] ?? []) : count($data['room'] ?? [])) }}
            </span> entries
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 rounded-xl shadow overflow-hidden">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title fw-bold">
          {{ request()->type == 'std' ? 'Student Details' : (request()->type == 'lct' ? 'Lecturer Details' : 'Room Details') }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0" id="detailModalBody">
        <div class="d-flex p-4">
          <div class="card-body p-0">
            <div class="text-center mb-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="mt-2">Loading details...</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light border-top-0">
        <button type="button" class="btn btn-light-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
        <a href="#" id="schedule-link" class="btn btn-primary rounded-pill px-4">
          <i class="ti-calendar me-1"></i> View Schedule
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Add these CSS in your stylesheet or in a style tag at the top of your blade file -->
<style>
  /* Glass morphism */
  .bg-glass {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
  }
  
  /* Gradient backgrounds */
  .bg-gradient-primary {
    background: linear-gradient(45deg, #4099ff, #73b4ff);
  }
  
  .bg-gradient-success {
    background: linear-gradient(45deg, #2ed8b6, #59e0c5);
  }
  
  .bg-gradient-warning {
    background: linear-gradient(45deg, #FFB64D, #ffcb80);
  }
  
  .bg-gradient-info {
    background: linear-gradient(45deg, #4099ff, #73b4ff);
  }
  
  /* Text gradients */
  .text-gradient-primary {
    background: linear-gradient(45deg, #4099ff, #73b4ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  
  /* Rounded corners */
  .rounded-xl {
    border-radius: 16px !important;
  }
  
  .rounded-bottom-xl {
    border-bottom-right-radius: 16px !important;
    border-bottom-left-radius: 16px !important;
  }
  
  /* Hover effects */
  .shadow-hover {
    transition: all 0.3s ease;
  }
  
  .shadow-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
  }
  
  /* Button states */
  .btn-light-primary {
    background-color: rgba(64, 153, 255, 0.1);
    color: #4099ff;
    border: none;
  }
  
  .btn-light-primary:hover {
    background-color: rgba(64, 153, 255, 0.2);
    color: #4099ff;
  }
  
  .btn-light-secondary {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border: none;
  }
  
  .hover-primary {
    transition: all 0.2s ease;
  }
  
  .hover-primary:hover {
    background-color: rgba(64, 153, 255, 0.1);
    cursor: pointer;
  }
  
  /* Avatar sizes */
  .avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
  }
  
  .avatar-lg {
    width: 56px;
    height: 56px;
  }
  
  .avatar-xl {
    width: 72px;
    height: 72px;
  }
  
  .avatar-md {
    width: 48px;
    height: 48px;
  }
  
  /* Utilities */
  .border-top-dashed {
    border-top: 1px dashed rgba(0, 0, 0, 0.1) !important;
  }
  
  /* Tab navigation */
  .tab-nav-underline .nav-link {
    border: none;
    position: relative;
    color: #6c757d;
    font-weight: 500;
  }
  
  .tab-nav-underline .nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 3px;
    background: #4099ff;
    transition: all 0.3s ease;
    transform: translateX(-50%);
  }
  
  .tab-nav-underline .nav-link.active {
    color: #4099ff;
    background: transparent;
  }
  
  .tab-nav-underline .nav-link.active::after {
    width: 80%;
  }
  
  /* Pagination */
  .pagination-circle .page-link {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>

<!-- Custom JavaScript -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>

<script>
  $(function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Initialize DataTable
    let dataTable = $('#complex_header').DataTable({
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "pageLength": 10,
      "language": {
        "search": "",
        "searchPlaceholder": "Search...",
        "lengthMenu": "Show _MENU_ entries",
      },
      "dom": '<"top"fl>rt<"bottom"ip>',
      "buttons": [
        { extend: 'excel', className: 'btn btn-success rounded-pill' },
        { extend: 'pdf', className: 'btn btn-danger rounded-pill' },
        { extend: 'csv', className: 'btn btn-primary rounded-pill' }
      ]
    });
    
    // Search input enhancements
    $('#search-input').on('focus', function() {
      $('.search-suggestion-wrapper').removeClass('d-none');
    });
    
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.search-wrapper').length) {
        $('.search-suggestion-wrapper').addClass('d-none');
      }
    });
    
    $('#search-input').on('keyup', function() {
      dataTable.search(this.value).draw();
      filterCardView(this.value);
    });
    
    // Select all checkboxes
    $('#select-all').on('change', function() {
      $('tbody .form-check-input').prop('checked', $(this).prop('checked'));
    });
    
    // View Details
    $('.view-details').on('click', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      const type = '{{ request()->type }}';
      const modal = $('#detailModal');
      
      // Set the schedule link
      $('#schedule-link').attr('href', '/AR/schedule/scheduleTable/' + id + '?type=' + type);
      
      // Show modal with loading state
      modal.modal('show');
      
      // Simulate loading with setTimeout
      setTimeout(function() {
        let detailsHtml = '';
        
        if (type === 'std') {
          // Simulate student details with a modern layout
          detailsHtml = `
            <div class="row g-0">
              <div class="col-md-4 bg-gradient-primary text-white p-4 d-flex flex-column justify-content-center align-items-center">
                <div class="avatar avatar-xxl bg-white text-primary rounded-circle mb-3">
                  <span class="fs-2">ST</span>
                </div>
                <h4 class="mb-1">Student Name</h4>
                <p class="mb-0">990101-01-1234</p>
                <div class="mt-4">
                  <span class="badge bg-white text-primary rounded-pill px-3 py-2">Computer Science</span>
                </div>
              </div>
              <div class="col-md-8 p-4">
                <ul class="nav nav-pills nav-fill mb-4">
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#personal-info">Personal Info</button>
                  </li>
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#academic-info">Academic Info</button>
                  </li>
                </ul>
                
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="personal-info">
                    <div class="list-group list-group-flush">
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-envelope"></i>
                          </div>
                          <span>Email</span>
                        </div>
                        <span class="text-muted">student@example.com</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-phone"></i>
                          </div>
                          <span>Phone</span>
                        </div>
                        <span class="text-muted">+601234567890</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-map-marker-alt"></i>
                          </div>
                          <span>Address</span>
                        </div>
                        <span class="text-muted">Kuala Lumpur, Malaysia</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-user-shield"></i>
                          </div>
                          <span>Status</span>
                        </div>
                        <span class="badge bg-success-light text-success rounded-pill px-3">Active</span>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="academic-info">
                    <div class="list-group list-group-flush">
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-graduation-cap"></i>
                          </div>
                          <span>Program</span>
                        </div>
                        <span class="text-muted">Bachelor of Computer Science</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-calendar-check"></i>
                          </div>
                          <span>Intake Year</span>
                        </div>
                        <span class="text-muted">2023</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-star"></i>
                          </div>
                          <span>CGPA</span>
                        </div>
                        <span class="text-muted">3.75</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-medal"></i>
                          </div>
                          <span>Advisor</span>
                        </div>
                        <span class="text-muted">Dr. Ahmad Bin Abdullah</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
        } else if (type === 'lct') {
          // Simulate lecturer details with a modern layout
          detailsHtml = `
            <div class="row g-0">
              <div class="col-md-4 bg-gradient-primary text-white p-4 d-flex flex-column justify-content-center align-items-center">
                <div class="avatar avatar-xxl bg-white text-primary rounded-circle mb-3">
                  <span class="fs-2">LC</span>
                </div>
                <h4 class="mb-1">Lecturer Name</h4>
                <p class="mb-0">780505-01-4321</p>
                <div class="mt-4">
                  <span class="badge bg-white text-primary rounded-pill px-3 py-2">Computer Science Department</span>
                </div>
              </div>
              <div class="col-md-8 p-4">
                <ul class="nav nav-pills nav-fill mb-4">
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#contact-info">Contact Info</button>
                  </li>
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#academic-details">Academic Details</button>
                  </li>
                </ul>
                
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="contact-info">
                    <div class="list-group list-group-flush">
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-envelope"></i>
                          </div>
                          <span>Email</span>
                        </div>
                        <span class="text-muted">lecturer@university.edu</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-phone"></i>
                          </div>
                          <span>Phone</span>
                        </div>
                        <span class="text-muted">+601987654321</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-building"></i>
                          </div>
                          <span>Office</span>
                        </div>
                        <span class="text-muted">Block A, Room 123</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-user-shield"></i>
                          </div>
                          <span>Status</span>
                        </div>
                        <span class="badge bg-success-light text-success rounded-pill px-3">Active</span>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="academic-details">
                    <div class="list-group list-group-flush">
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-graduation-cap"></i>
                          </div>
                          <span>Qualification</span>
                        </div>
                        <span class="text-muted">PhD in Computer Science</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-chalkboard"></i>
                          </div>
                          <span>Subjects</span>
                        </div>
                        <div>
                          <span class="badge bg-light-primary text-primary rounded-pill px-2 me-1">Programming</span>
                          <span class="badge bg-light-primary text-primary rounded-pill px-2">Database</span>
                        </div>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-calendar-alt"></i>
                          </div>
                          <span>Join Date</span>
                        </div>
                        <span class="text-muted">January 2020</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-3">
                            <i class="fa fa-users"></i>
                          </div>
                          <span>Current Load</span>
                        </div>
                        <span class="text-muted">5 Classes</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
        } else if (type === 'lcr') {
          // Simulate room details with a modern layout
          detailsHtml = `
            <div class="row g-0">
              <div class="col-md-4 bg-gradient-warning text-white p-4 d-flex flex-column justify-content-center align-items-center">
                <div class="avatar avatar-xxl bg-white text-warning rounded-circle mb-3">
                  <span class="fs-2">RM</span>
                </div>
                <h4 class="mb-1">Room Name</h4>
                <p class="mb-2">Building Block A</p>
                <div class="mt-3">
                  <span class="badge bg-white text-warning rounded-pill px-3 py-2">{{ isset($rm->total_hour) ? $rm->total_hour : '8' }} hours/day</span>
                </div>
              </div>
              <div class="col-md-8 p-4">
                <ul class="nav nav-pills nav-fill mb-4">
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#room-info">Room Info</button>
                  </li>
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#room-schedule">Schedule</button>
                  </li>
                </ul>
                
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="room-info">
                    <div class="list-group list-group-flush">
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-warning text-warning rounded-circle me-3">
                            <i class="fa fa-clock"></i>
                          </div>
                          <span>Operating Hours</span>
                        </div>
                        <span class="text-muted">8:00 AM - 5:00 PM</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-warning text-warning rounded-circle me-3">
                            <i class="fa fa-users"></i>
                          </div>
                          <span>Capacity</span>
                        </div>
                        <span class="text-muted">30 seats</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-warning text-warning rounded-circle me-3">
                            <i class="fa fa-tools"></i>
                          </div>
                          <span>Features</span>
                        </div>
                        <div>
                          <span class="badge bg-info-light text-info rounded-pill px-2 me-1">Projector</span>
                          <span class="badge bg-success-light text-success rounded-pill px-2">Air Conditioned</span>
                        </div>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-warning text-warning rounded-circle me-3">
                            <i class="fa fa-calendar-week"></i>
                          </div>
                          <span>Weekend Availability</span>
                        </div>
                        <span class="badge bg-danger-light text-danger rounded-pill px-3">No</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                          <div class="avatar avatar-sm bg-light-warning text-warning rounded-circle me-3">
                            <i class="fa fa-info-circle"></i>
                          </div>
                          <span>Description</span>
                        </div>
                        <span class="text-muted">Multipurpose lecture room with modern facilities.</span>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="room-schedule">
                    <div class="text-center py-4">
                      <i class="fa fa-calendar-alt fa-3x text-light-warning mb-3"></i>
                      <h5>Weekly Schedule</h5>
                      <p class="text-muted">Click the button below to view detailed schedule for this room.</p>
                      <a href="#" id="schedule-link-tab" class="btn btn-warning rounded-pill px-4">
                        <i class="ti-calendar me-1"></i> View Full Schedule
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
        }
        
        $('#detailModalBody').html(detailsHtml);
        
        // Update the schedule link inside the tab as well
        $('#schedule-link-tab').attr('href', $('#schedule-link').attr('href'));
      }, 800);
    });
    
    // Filter card view function
    function filterCardView(searchText) {
      searchText = searchText.toLowerCase();
      
      $('.data-card').each(function() {
        const cardText = $(this).text().toLowerCase();
        if (cardText.includes(searchText)) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    }
    
    // Apply filters
    $('#apply-filters').on('click', function() {
      const programFilter = $('#filter-category').val();
      const sortBy = $('#sort-by').val();
      
      // Apply program filter
      if (programFilter) {
        dataTable.column(3).search(programFilter).draw();
        
        // Also filter cards
        $('.data-card').each(function() {
          if (programFilter === '' || $(this).find('.badge').text().includes(programFilter)) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      } else {
        dataTable.column(3).search('').draw();
        $('.data-card').show();
      }
      
      // Apply sorting
      if (sortBy === 'name-asc') {
        dataTable.order([1, 'asc']).draw();
        
        // Sort cards (simplified for example)
        const cards = $('.data-card').toArray();
        cards.sort((a, b) => {
          const nameA = $(a).find('h5, h6').text().toUpperCase();
          const nameB = $(b).find('h5, h6').text().toUpperCase();
          return nameA.localeCompare(nameB);
        });
        
        $('#card-view-container .row, #grid-view-container .row').html(cards);
        
      } else if (sortBy === 'name-desc') {
        dataTable.order([1, 'desc']).draw();
        
        // Sort cards (simplified for example)
        const cards = $('.data-card').toArray();
        cards.sort((a, b) => {
          const nameA = $(a).find('h5, h6').text().toUpperCase();
          const nameB = $(b).find('h5, h6').text().toUpperCase();
          return nameB.localeCompare(nameA);
        });
        
        $('#card-view-container .row, #grid-view-container .row').html(cards);
      }
    });
    
    // View toggle buttons
    $('#table-view, #table-tab').on('click', function(e) {
      e.preventDefault();
      $('#table-tab').tab('show');
      localStorage.setItem('preferedView', 'table');
    });
    
    $('#card-view, #card-tab').on('click', function(e) {
      e.preventDefault();
      $('#card-tab').tab('show');
      localStorage.setItem('preferedView', 'card');
    });
    
    $('#grid-view, #grid-tab').on('click', function(e) {
      e.preventDefault();
      $('#grid-tab').tab('show');
      localStorage.setItem('preferedView', 'grid');
    });
    
    // Check localStorage for preferred view
    const preferedView = localStorage.getItem('preferedView');
    if (preferedView) {
      $('#' + preferedView + '-tab').tab('show');
    }
    
    // Export buttons
    $('#export-excel').on('click', function(e) {
      e.preventDefault();
      dataTable.button('.buttons-excel').trigger();
    });
    
    $('#export-pdf').on('click', function(e) {
      e.preventDefault();
      dataTable.button('.buttons-pdf').trigger();
    });
    
    $('#export-csv').on('click', function(e) {
      e.preventDefault();
      dataTable.button('.buttons-csv').trigger();
    });
    
    // Refresh button
    $('#refreshBtn').on('click', function() {
      const btn = $(this);
      btn.html('<i class="fa fa-sync-alt fa-spin me-1"></i> Refreshing...');
      btn.attr('disabled', true);
      
      // Simulate refresh
      setTimeout(function() {
        dataTable.ajax.reload();
        btn.html('<i class="fa fa-sync-alt me-1"></i> Refresh');
        btn.attr('disabled', false);
        
        // Show toast notification
        showToast('Data refreshed successfully!', 'success');
      }, 1000);
    });
    
    // Toast notification function
    function showToast(message, type = 'info') {
      // Create toast container if it doesn't exist
      if ($('#toast-container').length === 0) {
        $('body').append('<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1070;"></div>');
      }
      
      const toastId = 'toast-' + Date.now();
      const bgClass = type === 'success' ? 'bg-success' : (type === 'error' ? 'bg-danger' : 'bg-info');
      const iconClass = type === 'success' ? 'fa fa-check-circle' : (type === 'error' ? 'fa fa-exclamation-circle' : 'fa fa-info-circle');
      
      const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 rounded-xl shadow-sm" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
              <i class="${iconClass} me-2"></i> ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      `;
      
      $('#toast-container').append(toastHtml);
      
      const toastElement = new bootstrap.Toast(document.getElementById(toastId), {
        delay: 3000
      });
      
      toastElement.show();
      
      // Remove toast after it's hidden
      $(`#${toastId}`).on('hidden.bs.toast', function() {
        $(this).remove();
      });
    }
  });
</script>

@endsection