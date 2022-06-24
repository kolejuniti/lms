@extends('../layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Registration</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Registration {{ (isset($data)) ? "Edit" : ""}}</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Registration {{ (isset($data)) ? "Edit" : ""}}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action={{ (isset($data)) ? "/KP/{group}/updategroup/". $data->id : "/KP/store" }} method="POST">
                @csrf
                @if (isset($data))
                  @method('PATCH')
                @endif
                <div class="card-body">
                  <div class="row"> 
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="lct">Lecturer List</label>
                        <select class="form-select" id="lct" name="lct" {{ (isset($data)) ? 'DISABLED' : '' }}>
                          @foreach ($user as $users)
                          <option value="{{ $users->ic }}" {{ (isset($data)) ? (($data->user_ic == $users->ic) ? 'SELECTED' : '') : '' }}>{{ $users->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>  
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="course">Course</label>
                        <select class="form-select" id="course" name="course" {{ (isset($data)) ? 'DISABLED' : '' }}>
                          @foreach ($course as $courses)
                            <option value="{{ $courses->sub_id }}" {{ (isset($data)) ? (($data->course_id == $courses->sub_id) ? 'SELECTED' : '') : '' }}>{{ $courses->progname }} : {{ $courses->course_name }}(Semester {{ $courses->semesterid }})</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row"> 
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="session">Session</label>
                        <select class="form-select" id="session" name="session">
                          @foreach ($session as $sessions)
                            <option value="{{ $sessions->SessionID }}" {{ (isset($data)) ? (($data->session_id == $sessions->SessionID) ? 'SELECTED' : '') : '' }}>{{ $sessions->SessionName }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary pull-right mb-3">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
@endsection
