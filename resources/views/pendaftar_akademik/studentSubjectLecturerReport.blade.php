@extends('layouts.pendaftar_akademik')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Student Subject - Lecturer Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Report</li>
                <li class="breadcrumb-item active" aria-current="page">Student Subject - Lecturer</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Filter Report</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Program</label>
                    <select class="form-control select2" id="program" name="program">
                      <option value="">- All Program -</option>
                      @foreach($programs as $prog)
                        <option value="{{ $prog->id }}">{{ $prog->progcode }} - {{ $prog->progname }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Session</label>
                    <select class="form-control select2" id="session" name="session">
                      <option value="">- All Session -</option>
                      @foreach($sessions as $sess)
                        <option value="{{ $sess->SessionID }}">{{ $sess->SessionName }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Semester</label>
                    <select class="form-control select2" id="semester" name="semester">
                      <option value="">- All Semester -</option>
                      @for($i=1; $i<=8; $i++)
                        <option value="{{ $i }}">Semester {{ $i }}</option>
                      @endfor
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group" style="margin-top: 25px;">
                    <button type="button" class="btn btn-primary" onclick="getReport()">Filter</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Report Result</h3>
              <div class="box-controls pull-right">
                <button type="button" class="btn btn-success btn-sm" onclick="exportReport('excel')"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="exportReport('pdf')"><i class="fa fa-file-pdf-o"></i> Export PDF</button>
              </div>
            </div>
            <div class="box-body" id="report_container">
              <!-- Report result will be loaded here -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<script>
function getReport() {
    var program = $('#program').val();
    var session = $('#session').val();
    var semester = $('#semester').val();

    $('#report_container').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');

    $.ajax({
        url: "{{ route('pendaftar_akademik.getStudentSubjectLecturerReport') }}",
        type: "POST",
        data: {
            program: program,
            session: session,
            semester: semester,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            $('#report_container').html(response);
            if($.fn.DataTable.isDataTable('#report_table')) {
                $('#report_table').DataTable().destroy();
            }
            $('#report_table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        },
        error: function(xhr) {
            $('#report_container').html('<div class="alert alert-danger">Error loading report. Please try again.</div>');
        }
    });
}

function exportReport(type) {
    var program = $('#program').val();
    var session = $('#session').val();
    var semester = $('#semester').val();
    
    var url = "{{ route('pendaftar_akademik.exportStudentSubjectLecturerReport') }}" + 
              "?type=" + type + 
              "&program=" + program + 
              "&session=" + session + 
              "&semester=" + semester;
              
    window.location.href = url;
}
</script>
@endsection
