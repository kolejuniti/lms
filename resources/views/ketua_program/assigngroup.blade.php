@extends('../layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Assign Group</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item" aria-current="page">Students</li>
                <li class="breadcrumb-item active" aria-current="page">Group</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      @if($errors->any())
      <a class="btn btn-danger btn-sm ml-2 ">
        <i class="ti-na">
        </i>
        {{$errors->first()}}
      </a>
      @endif
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
              <form action="{{ route('kp.group.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6" id="program-card">
                      <div class="form-group">
                        <label class="form-label" for="program">Program</label>
                        <select class="form-select" id="program" name="program">
                          <option value="-" selected disabled>-</option>
                          @foreach ($programs as $prg)
                            <option value="{{ $prg->id }}">{{ $prg->progname }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>       
                    <div class="col-md-6" id="course-card" hidden>
                      <div class="form-group">
                        <label class="form-label" for="course">Course</label>
                        <select class="form-select" id="course" name="course">
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6" id="session-card">
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
                    <div class="col-md-6" id="lecturer-card" hidden>
                      <div class="form-group">
                        <label class="form-label" for="lecturer">Lecturer</label>
                        <select class="form-select" id="lecturer" name="lecturer">
                          <option value="-" selected disabled>-</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="group">Group</label>
                        <select class="form-select" id="group" name="group">
                          <option value="TIADA">TIADA</option>
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                          <option value="D">D</option>
                          <option value="E">E</option>
                          <option value="F">F</option>
                          <option value="G">G</option>
                          <option value="H">H</option>
                          <option value="I">I</option>
                          <option value="J">J</option>
                          <option value="K">K</option>
                          <option value="L">L</option>
                          <option value="M">M</option>
                          <option value="N">N</option>
                          <option value="O">O</option>
                          <option value="P">P</option>
                          <option value="Q">Q</option>
                          <option value="R">R</option>
                          <option value="S">S</option>
                          <option value="T">T</option>
                          <option value="U">U</option>
                          <option value="V">V</option>
                          <option value="W">W</option>
                          <option value="X">X</option>
                          <option value="Y">Y</option>
                          <option value="Z">Z</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="form-group mt-3">
                            <label class="form-label">Unregistered Student</label>
                            <div id="add-student-div"></div>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="form-group mt-3">
                            <label class="form-label">Registered Student</label>
                            <div id="add-student-div2"></div>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">

  var selected_program = 0;
  var selected_course = 0;
  var selected_session = "";
  var lecturer = document.getElementById('lecturer-card');

  $(document).on('change', '#program', async function(e){
    selected_program = $(e.target).val();

    await getCourse(selected_program);

  });

  $(document).on('change', '#course', async function(e){
    selected_course = $(e.target).val();

    await getLecturer(selected_course, selected_session);
    await getStudent(selected_course, selected_session);
  });

  $(document).on('change', '#session', async function(e){
    selected_session = $(e.target).val();

    lecturer.hidden = false;

    await getLecturer(selected_course, selected_session);
    await getStudent(selected_course, selected_session);
  });

  $(document).on('change', '#lecturer', function(e){
    selected_lecturer = $(e.target).val();

    getRegisteredStd(selected_lecturer);

  });

  function getCourse(program)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('KP/group/getcourseoptions') }}",
            method   : 'POST',
            data 	 : {program: program},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#course-card').removeAttr('hidden');
                $('#course').html(data);
                $('#course').selectpicker('refresh');

            }
        });
  }

  function getLecturer(course,session)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('KP/group/getlectureroptions') }}",
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

  function getStudent(course,session)
  {
    program = $('#program').val();

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('KP/group/getStudentTable') }}",
            method   : 'POST',
            data 	 : {course: course,session: session,program: program},
            error:function(err){
                
            },
            success  : function(data){
                $('#add-student-div').removeAttr('hidden');
                $('#add-student-div').html(data);
                $('#add-student-div').selectpicker('refresh');

                var $chkboxes = $('.filled-in');
                var lastChecked = null;

                $chkboxes.click(function(e) {
                    if (!lastChecked) {
                        lastChecked = this;
                        return;
                    }

                    if (e.shiftKey) {
                        var start = $chkboxes.index(this);
                        var end = $chkboxes.index(lastChecked);

                        $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
                    }

                    lastChecked = this;
                });

            }
        });
  }

  function getRegisteredStd(lecturer)
  {

    course = $('#course').val();
    session = $('#session').val();
    program = $('#program').val();

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('KP/group/getStudentTable2') }}",
            method   : 'POST',
            data 	 : {lecturer: lecturer, course: course, session: session, program: program},
            error:function(err){
                
            },
            success  : function(data){
                $('#add-student-div2').removeAttr('hidden');
                $('#add-student-div2').html(data);
                $('#add-student-div2').selectpicker('refresh');

                var $chkboxes = $('.filled-in');
                var lastChecked = null;

                $chkboxes.click(function(e) {
                    if (!lastChecked) {
                        lastChecked = this;
                        return;
                    }

                    if (e.shiftKey) {
                        var start = $chkboxes.index(this);
                        var end = $chkboxes.index(lastChecked);

                        $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
                    }

                    lastChecked = this;
                });

            }
        });

  }

  function CheckAll(elem) {
    $('[name="student[]"]').prop("checked", $(elem).prop('checked'));
  }

  function CheckAll2(elem) {
    $('[name="student2[]"]').prop("checked", $(elem).prop('checked'));
  }

</script>
@endsection
