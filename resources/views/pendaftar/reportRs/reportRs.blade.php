@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : '')))


@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Student Report R/R1/R2</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Student R/R1/R2</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      @if($errors->any())
        <div class="form-group">
            <div class="alert alert-success">
              <span>{{$errors->first()}} </span>
            </div>
        </div>
      @endif
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- /.card-header -->
      <div class="card card-primary">
        {{-- <div class="card-header">
          <b>Search Student</b>
          <button id="printButton" class="waves-effect waves-light btn btn-primary btn-sm">
            <i class="ti-printer"></i>&nbsp Print
          </button>
        </div> --}}
        <div class="card-body">
          <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                  <label class="form-label" for="from">FROM</label>
                  <input type="date" class="form-control" id="from" name="from">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                  <label class="form-label" for="name">TO</label>
                  <input type="date" class="form-control" id="to" name="to">
                  </div>
              </div>
          </div>
          {{-- <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="session">Session</label>
                <select class="form-select" id="session" name="session">
                  <option value="-" selected disabled>-</option>
                  @foreach($data['session'] as $ses)
                  <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div> --}}
          <div id="form-student">
            
  
          </div>
        </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Page specific script -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>



  <script type="text/javascript">
    var from = '';
    var to = '';

    $(document).on('change', '#from', async function(e){
      from = $(e.target).val();

      await getStudent(from,to);
    });

    $(document).on('change', '#to', async function(e){
      to = $(e.target).val();

      await getStudent(from,to);
    });

  function getStudent(from,to)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('/pendaftar/student/reportRs/getStudentReportR') }}",
            method   : 'GET',
            data 	 : {from: from, to: to},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#form-student').html(data);

                $('#myTable').DataTable({
                  dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                  
                  buttons: [
                      'copy', 'csv', 'excel', 'pdf', 'print'
                  ],
                });

                $('#myTable2').DataTable({
                  dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                  
                  buttons: [
                      'copy', 'csv', 'excel', 'pdf', 'print'
                  ],
                });

                $('#myTable3').DataTable({
                  dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                  
                  buttons: [
                      'copy', 'csv', 'excel', 'pdf', 'print'
                  ],
                });

                $('#myTable4').DataTable({
                  dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                  
                  buttons: [
                      'copy', 'csv', 'excel', 'pdf', 'print'
                  ],
                });
            }
        });

  }
  </script>
@endsection
