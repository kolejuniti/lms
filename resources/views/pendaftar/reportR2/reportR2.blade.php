@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : '')))


@section('main')

<!-- Content Header (Page header) -->
<style>
  #loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
  }
  
  .loading-spinner {
    text-align: center;
    padding: 20px;
    border-radius: 8px;
    background-color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
</style>

<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Statistik Pencapaian R</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Statistik Pencapaian R</li>
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
      <!-- Loading Overlay -->
      <div id="loading-overlay" class="d-none">
        <div class="loading-spinner">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Loading data...</p>
        </div>
      </div>
      
      <!-- /.card-header -->
      <div class="card card-primary">
        <div class="card-header">
          <b>Search Student</b>
          <button id="printButton" class="btn btn-primary">Print / PDF</button>
        </div>
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

      $('#loading-overlay').removeClass('d-none');
      await getStudent(from,to);
    });

    $(document).on('change', '#to', async function(e){
      to = $(e.target).val();

      $('#loading-overlay').removeClass('d-none');
      await getStudent(from,to);
    });

  function getStudent(from,to)
  {
    //alert(from);
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('/pendaftar/student/reportR2/getStudentReportR2') }}",
            method   : 'GET',
            data 	 : {from: from, to: to},
            beforeSend: function() {
                $('#loading-overlay').removeClass('d-none');
            },
            error:function(err){
                $('#loading-overlay').addClass('d-none');
                alert("Error");
                console.log(err);
            },
            success  : function(data){
            $('#loading-overlay').addClass('d-none');
            if(data.error)
            {
              alert(data.error);
            }else{
                $('#form-student').html(data);

                $('#myTable').DataTable({
                  dom: 'lfrtip', // Removed B for buttons
                  ordering: false // Disable ordering
                });

                $('#myTable2').DataTable({
                  dom: 'lfrtip' // Removed B for buttons
                });
                
              }
            }
        });

  }

  $(document).ready(function() {
    $('#printButton').on('click', function(e) {
      e.preventDefault();
      $('#loading-overlay').removeClass('d-none');
      printReport();
    });
  });

  function printReport() {
    var from = $('#from').val();
    var to = $('#to').val();

    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('pendaftar/student/reportR2/getStudentReportR2?print=true') }}",
      method: 'GET',
      data: { from: from, to: to },
      beforeSend: function() {
          $('#loading-overlay').removeClass('d-none');
      },
      error: function(err) {
        $('#loading-overlay').addClass('d-none');
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        $('#loading-overlay').addClass('d-none');
        var newWindow = window.open();
        newWindow.document.write(data);
        newWindow.document.close();
      }
    });
  }
  </script>
@endsection
