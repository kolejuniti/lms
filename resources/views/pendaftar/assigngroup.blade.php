@extends('../layouts.hea')

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
              <li class="breadcrumb-item" aria-current="page">Extra</li>
              <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
                <h3 class="card-title">Assign Student</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{ route('hea.group.store') }}" method="POST">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="program">Program</label>
                            <select class="form-select" id="program" name="program">
                            <option value="-" selected disabled>-</option>
                                @foreach ($program as $prg)
                                <option value="{{ $prg->id }}">{{ $prg->progname}}</option> 
                                @endforeach
                            </select>
                        </div>
                    </div>        
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="intake">Intake</label>
                        <select class="form-select" id="intake" name="intake">
                          <option value="-" selected disabled>-</option>
                          @foreach ($session as $ses)
                          <option value="{{ $ses->SessionID }}">{{ $ses->SessionName}}</option> 
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">       
                    <div class="col-md-6" id="course-card" hidden>
                      <div class="form-group">
                        <label class="form-label" for="course">Course</label>
                        <select class="form-select" id="course" name="course">
                          <option value="-" selected disabled>-</option>

                        </select>
                      </div>
                    </div>
                    <div class="col-md-6" id="session-card" hidden>
                      <div class="form-group">
                        <label class="form-label" for="session">Session</label>
                        <select class="form-select" id="session" name="session">
                          <option value="-" selected disabled>-</option>
                          @foreach ($session as $ses)
                          <option value="{{ $ses->SessionID }}">{{ $ses->SessionName}}</option> 
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6" id="lecturer-card" hidden>
                      <div class="form-group">
                        <label class="form-label" for="lecturer">Group</label>
                        <select class="form-select" id="lecturer" name="lecturer">
                          <option value="-" selected disabled>-</option>

                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="form-group mt-3">
                            <label class="form-label">Registered Student</label>
                            <div id="add-student-div"></div>
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
    <!-- /.content -->
  </div>
</div>

<script type="text/javascript">
  var selected_program = "";
  var selected_intake = "";
  var selected_course = 0;
  var selected_session = "";
  var course = document.getElementById('course-card');
  var session = document.getElementById('session-card');
  var lecturer = document.getElementById('lecturer-card');

  $(document).on('change', '#program', async function(e){
    selected_program = $(e.target).val();

    await getCourseOptions(selected_program);
    await getStudent(selected_program);
  });

  $(document).on('change', '#intake', async function(e){
    selected_intake = $(e.target).val();

    course.hidden = false;

    await getStudent(selected_program, selected_intake);
  });

  $(document).on('change', '#course', async function(e){
    selected_course = $(e.target).val();

    session.hidden = false;

    await getLecturer(selected_course);
  });

  $(document).on('change', '#session', async function(e){
    selected_session = $(e.target).val();

    lecturer.hidden = false;

    await getLecturer(selected_course, selected_session);
  });

  function getCourseOptions(program){
        return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('HEA/group/getcourseoptions') }}",
            method   : 'POST',
            data 	 : {program: program},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#course-selection-div').removeAttr('hidden');
                $('#course').html(data);
                $('#course').selectpicker('refresh');

            }
        });
    }

  function getStudent(program,intake)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('HEA/group/getStudentTable') }}",
            method   : 'POST',
            data 	 : {program: program,intake: intake},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#add-student-div').removeAttr('hidden');
                $('#add-student-div').html(data);
                $('#add-student-div').selectpicker('refresh');

            }
        });
  }

  function getLecturer(course,session)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('HEA/group/getlectureroptions') }}",
            method   : 'POST',
            data 	 : {course: course,session: session},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#lecturer-selection-div').removeAttr('hidden');
                $('#lecturer').html(data);
                $('#lecturer').selectpicker('refresh');

            }
        });
  }
</script>
@endsection
