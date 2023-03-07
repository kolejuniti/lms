@extends('../layouts.pendaftar_akademik')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Student Leave</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item" aria-current="page">Students</li>
                <li class="breadcrumb-item active" aria-current="page">Leave</li>
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
                    <h3 class="card-title">Student List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6" id="program-card">
                            <div class="form-group">
                            <label class="form-label" for="program">Program</label>
                            <select class="form-select" id="program" name="program">
                                <option value="-" selected disabled>-</option>
                                @foreach ($data['programs'] as $prg)
                                <option value="{{ $prg->id }}">{{ $prg->progcode }} - {{ $prg->progname }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>       
                        <div class="col-md-3" id="session-card">
                            <div class="form-group">
                            <label class="form-label" for="session">Session</label>
                            <select class="form-select" id="session" name="session">
                                <option value="-" selected disabled>-</option>
                                @foreach ($data['sessions'] as $ses)
                                <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option> 
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="semester-card">
                          <div class="form-group">
                          <label class="form-label" for="semester">Semester</label>
                          <select class="form-select" id="semester" name="semester">
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['semesters'] as $ses)
                              <option value="{{ $ses->id }}">{{ $ses->semester_name }}</option> 
                              @endforeach
                          </select>
                          </div>
                      </div>
                        <div class="col-md-6" id="student-card" hidden>
                            <div class="form-group">
                            <label class="form-label" for="student">Student</label>
                            <select class="form-select" id="student" name="student">
                                <option value="-" selected disabled>-</option>
                            </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="box-body" id="studentList" hidden>

                </div>
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

  var selected_program = '';
  var selected_session = '';
  var selected_semester = '';

  $(document).on('change', '#program', async function(e){
    selected_program = $(e.target).val();

    await getStudent(selected_program, selected_session, selected_semester);

  });


  $(document).on('change', '#session', async function(e){
    selected_session = $(e.target).val();

    await getStudent(selected_program, selected_session, selected_semester);
  });

  $(document).on('change', '#semester', async function(e){
    selected_semester = $(e.target).val();

    await getStudent(selected_program, selected_session, selected_semester);
  });

  function getStudent(program,session,semester)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/leave/getStudentLeave') }}",
            method   : 'GET',
            data 	 : {program: program,session: session,semester: semester},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#studentList').removeAttr('hidden');
                $('#studentList').html(data);
                //$('#student').selectpicker('refresh');

            }
        });
  }

  function onLeave()
  {
    var leave = [];

    $("#campus :selected").each(function() {
      leave.push($(this).val());
    });

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/leave/updateLeave') }}",
            method   : 'POST',
            data 	 : {leave: leave},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                
              getStudent(selected_program, selected_session, selected_semester);

            }
        });
  }

  function onCampus()
  {
    var campus = [];

    $("#leave :selected").each(function() {
      campus.push($(this).val());
    });

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/campus/updatecampus') }}",
            method   : 'POST',
            data 	 : {campus: campus},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                
              getStudent(selected_program, selected_session, selected_semester);

            }
        });
  }

</script>
@endsection
