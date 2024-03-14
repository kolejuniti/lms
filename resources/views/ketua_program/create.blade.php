@extends('../layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    @if(session()->has('message'))
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <div class="form-group">
            <div class="alert alert-success">
                <span>{{ session()->get('message') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Assign Lecturer</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Assign Lecturer {{ (isset($data)) ? "Edit" : ""}}</li>
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
                <h3 class="card-title">Assign Lecturer {{ (isset($data)) ? "Edit" : ""}}</h3>
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
                    <div class="col-md-6" id="program-card">
                      <div class="form-group">
                        <label class="form-label" for="program">Program</label>
                        <select class="form-select" id="program" name="program">
                          <option value="-" selected disabled>-</option>
                          @foreach ($programs as $prg)
                            <option value="{{ $prg->id }}" {{ (isset($data)) ? (($data->prgid == $prg->id) ? 'SELECTED' : '') : '' }}>{{ $prg->progname }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>  
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="course">Course</label>
                        <select class="form-select" id="course" name="course">
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
                    <div class="col-md-6">
                      <div class="form-group">
                          <label class="form-label" for="lct">Lecturer List</label>
                          <select class="form-select" id="lct" name="lct" {{ isset($data) ? 'disabled' : '' }}>
                              <option value="-" selected disabled>-</option>
                              @foreach ($user as $usr)
                                  <option value="{{ $usr->ic }}" {{ (isset($data) && $data->user_ic == $usr->ic) ? 'selected' : '' }}>{{ $usr->name }}</option>
                              @endforeach
                          </select>
                      </div>
                    </div>
                  </div>
                  <div>
                    <button type="submit" class="btn btn-primary pull-right mb-3">Submit</button>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="form-group mt-3">
                            <label class="form-label">Registered Subject</label>
                            <div id="add-student-div"></div>
                        </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  
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

<script type="text/javascript">

  var selected_program = 0;
  var course = '';

  $(document).ready(function(){
    
    selected_program = $( "#program option:selected" ).val();

    course = "{{ (isset($data)) ? $data->prgid : '' }}"

    getCourse(selected_program,course);
  })

  $(document).on('change', '#program', async function(e){
    selected_program = $(e.target).val();

    await getCourse(selected_program,course);
    await getLecturer(selected_program,selected_course,selected_session);

  });

  $(document).on('change', '#course', async function(e){
    selected_course = $(e.target).val();

    await getLecturer(selected_program,selected_course,selected_session);

  });

  $(document).on('change', '#session', async function(e){
    selected_session = $(e.target).val();

    await getLecturer(selected_program,selected_course,selected_session);

  });

  function getCourse(program,course)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('KP/group/getcourseoptions') }}",
            method   : 'POST',
            data 	 : {program: program, course: course},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#course').html(data);
                $('#course').selectpicker('refresh');

            }
        });
  }

  function getLecturer(program,course,session)
  {

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('KP/group/getLecturerSubject') }}",
            method   : 'POST',
            data 	 : {program: program, course: course, session: session},
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

  function deleteSubjek(id)
  {
    ic = $('#lct').val();

    Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
    }).then(function(res){
      
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('KP/group/deleteLecturerSubject') }}",
                  method   : 'POST',
                  data 	 : {id: id, ic: ic},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      alert("success");
                      $('#add-student-div').removeAttr('hidden');
                      $('#add-student-div').html(data);
                      $('#add-student-div').selectpicker('refresh');
                  }
              });
          }
      });

  }

</script>
@endsection
