@extends('../layouts.pendaftar')

@section('main')
<style>
  @media print {

  @page {size: A4 landscape;max-height:100%; max-width:100%}

  /* use width if in portrait (use the smaller size to try 
    and prevent image from overflowing page... */
  img { height: 90%; margin: 0; padding: 0; }

  body{width:100%;
  height:100%;
  -webkit-transform: rotate(-90deg) scale(.68,.68); 
  -moz-transform:rotate(-90deg) scale(.58,.58) }    }
</style>
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Lecturer Attendance</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item" aria-current="page">Report</li>
              <li class="breadcrumb-item active" aria-current="page">Attendance</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div id="printableArea">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Lecturer Attendance</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Find Lecturer</b>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="faculty">Faculty</label>
                        <select class="form-select" id="faculty" name="faculty" required>
                          <option value="-" selected disabled>-</option>
                            @foreach ($data['faculty'] as $fcl)
                            <option value="{{ $fcl->id }}">{{ $fcl->facultyname }}</option> 
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="session">Session</label>
                        <select class="form-select" id="session" name="session" required>
                          <option value="-" selected disabled>-</option>
                            @foreach ($data['session'] as $ses)
                            <option value="{{ $ses->SessionID }}">{{ $ses->SessionName}}</option> 
                            @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div id="form-student">
              
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
  </div>
    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script type="text/javascript">

var faculty;
var session;

$('#faculty').on('change', function(){
    faculty = $(this).val();
    getAttendance(faculty,session);
    //alert(faculty);
});

$('#session').on('change', function(){
    session = $(this).val();
    getAttendance(faculty,session);
});

function getAttendance(faculty,session)
{

        return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('quality/report/attendance/getLecturer') }}",
            method   : 'POST',
            data 	 : {faculty: faculty, session: session},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
              $('#form-student').html(data);

            }
        });
}

</script>
@endsection
