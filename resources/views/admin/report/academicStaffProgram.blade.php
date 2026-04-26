@extends('layouts.admin')

@section('main')
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Academic Staff &amp; Program Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Report</li>
                <li class="breadcrumb-item active" aria-current="page">Academic Staff &amp; Program</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Academic Staff &amp; Program</h3>
              </div>

              <div class="card-body">
                <div class="card mb-3">
                  <div class="card-header">
                    <b>Filter Options</b>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Programme:</label>
                          <select class="form-control select2" id="programme_filter" data-placeholder="Select Programme">
                            <option value=""></option>
                            @foreach(($programmes ?? []) as $programme)
                              <option value="{{ $programme->id }}" {{ ((string)($selectedProgramId ?? '') === (string)$programme->id) ? 'selected' : '' }}>
                                {{ $programme->progcode }} - {{ $programme->progname }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Session:</label>
                          <select class="form-control select2" id="session_filter" data-placeholder="Select Session">
                            <option value=""></option>
                            @foreach(($sessions ?? []) as $session)
                              <option value="{{ $session->SessionID }}" {{ ((string)($selectedSessionId ?? '') === (string)$session->SessionID) ? 'selected' : '' }}>
                                {{ $session->SessionName }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-12 d-flex align-items-end justify-content-end">
                        <div class="form-group mb-0">
                          <button type="button" class="btn btn-success mr-2" id="export_btn" onclick="exportAcademicStaffProgram()">
                            <i class="fa fa-file-excel-o"></i> Export Excel
                          </button>
                          <button type="button" class="btn btn-warning" onclick="clearFilter()">
                            <i class="fa fa-refresh"></i> Clear
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-bordered table-striped" id="academic-staff-program-table">
                    <thead>
                      <tr>
                        <th rowspan="2" style="width:40px;">#</th>
                        <th rowspan="2">Name and Designation of Academic Staff</th>
                        <th rowspan="2">Appointment Status (full-time, part-time, contract, etc.)</th>
                        <th rowspan="2">Nationality</th>
                        <th rowspan="2">Courses Taught in This Programme</th>
                        <th rowspan="2">Courses Taught in Other Programmes</th>
                        <th colspan="4" class="text-center">Academic Qualifications</th>
                        <th rowspan="2">Research Focus Areas (Bachelor and above)</th>
                        <th colspan="3" class="text-center">Past Work Experience</th>
                      </tr>
                      <tr>
                        <th>Qualifications</th>
                        <th>Field of Specialisation</th>
                        <th>Year of Award</th>
                        <th>Name of Awarding Institution and Country</th>
                        <th>Positions Held</th>
                        <th>Employer</th>
                        <th>Years of Service (start and end)</th>
                      </tr>
                    </thead>
                    <tbody>
                      @include('admin.report.partials.academicStaffProgramRows', ['rows' => $rows ?? []])
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<script>
$(document).ready(function() {
  $('.select2').select2({
    allowClear: true,
    width: '100%',
    placeholder: $('#programme_filter').data('placeholder') || '',
    escapeMarkup: function(markup) { return markup; }
  });

  $('#programme_filter').on('change', function() {
    const programmeId = $(this).val();
    const params = new URLSearchParams(window.location.search);
    if (programmeId) {
      params.set('program_id', programmeId);
    } else {
      params.delete('program_id');
    }
    window.location.search = params.toString();
  });

  $('#session_filter').on('change', function() {
    const sessionId = $(this).val();
    const params = new URLSearchParams(window.location.search);
    if (sessionId) {
      params.set('session_id', sessionId);
    } else {
      params.delete('session_id');
    }
    window.location.search = params.toString();
  });

  window.exportAcademicStaffProgram = function() {
    const params = new URLSearchParams();
    const programmeId = $('#programme_filter').val();
    if (programmeId) {
      params.append('program_id', programmeId);
    }
    const sessionId = $('#session_filter').val();
    if (sessionId) {
      params.append('session_id', sessionId);
    }
    window.location.href = '{{ route("admin.report.academicStaffProgram.export") }}' + '?' + params.toString();
  };

  window.clearFilter = function() {
    $('#programme_filter').val('').trigger('change');
    $('#session_filter').val('').trigger('change');
  };
});
</script>
@endsection
