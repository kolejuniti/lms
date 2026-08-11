@extends('layouts.pendaftar_akademik')

@section('main')

<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">

    {{-- Page Header --}}
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Exam Result By Program</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Examination</li>
                <li class="breadcrumb-item active" aria-current="page">Exam Result By Program</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    {{-- Main Content --}}
    <section class="content">
      <div class="card card-primary">
        <div class="card-header">
          <b><i class="ti-filter me-1"></i> Filter Options</b>
        </div>
        <div class="card-body">
          <div class="row">

            {{-- Program --}}
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="program">
                  <i class="ti-book me-1 text-primary"></i> Program
                </label>
                <select class="form-select" id="program" name="program">
                  <option value="" selected disabled>-- Select Program --</option>
                  @foreach ($data['programs'] as $prg)
                    <option value="{{ $prg->id }}">{{ $prg->progcode }} - {{ $prg->progname }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- Session --}}
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="session">
                  <i class="ti-calendar me-1 text-primary"></i> Session
                </label>
                <select class="form-select" id="session" name="session">
                  <option value="" selected disabled>-- Select Session --</option>
                  @foreach ($data['sessions'] as $ses)
                    <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- Semester --}}
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="semester">
                  <i class="ti-layers me-1 text-primary"></i> Semester
                </label>
                <select class="form-select" id="semester" name="semester">
                  <option value="" selected disabled>-- Select Semester --</option>
                  <option value="1">Semester 1</option>
                  <option value="2">Semester 2</option>
                  <option value="3">Semester 3</option>
                  <option value="4">Semester 4</option>
                  <option value="5">Semester 5</option>
                  <option value="6">Semester 6</option>
                </select>
              </div>
            </div>

          </div>{{-- end .row --}}
        </div>{{-- end .card-body --}}

        <div class="card-footer d-flex justify-content-end">
          <button id="btn-display" onclick="displayResult()" class="waves-effect waves-light btn btn-primary">
            <i class="ti-printer me-1"></i> Display Result
          </button>
        </div>
      </div>{{-- end .card --}}

      {{-- Info box --}}
      <div class="card" id="info-box" style="display:none;">
        <div class="card-body py-3">
          <div class="d-flex align-items-center gap-3">
            <div class="text-info me-3"><i class="ti-info-alt" style="font-size:22px;"></i></div>
            <div>
              <p class="mb-0 fw-600">The report will open in a new browser tab.</p>
              <small class="text-muted">Only students with existing examination records will be included. 2 student results are printed per A4 page.</small>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>
</div>

<script>
function displayResult() {
    var program  = $('#program').val();
    var session  = $('#session').val();
    var semester = $('#semester').val();

    if (!program || !session || !semester) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Selection',
            text: 'Please select Program, Session, and Semester before displaying the result.',
            confirmButtonText: 'OK'
        });
        return;
    }

    var url = "{{ route('pendaftar_akademik.examResultByProgram.print') }}"
              + "?program=" + program
              + "&session=" + session
              + "&semester=" + semester;

    $('#info-box').show();
    window.open(url, '_blank');
}
</script>

@endsection
