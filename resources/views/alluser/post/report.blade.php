@extends((Auth::user()->usrtype == "ADM") ? 'layouts.admin' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "TS" ? 'layouts.treasurer' : (Auth::user()->usrtype == "DN" ? 'layouts.deen' : (Auth::user()->usrtype == "LCT" || Auth::user()->usrtype == "PL" || Auth::user()->usrtype == "AO" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "OTR" ? 'layouts.other_user' : ''))))))))

@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Report Posting</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Report Posting</li>
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
        <div class="card-header">
          <b>Search Posts</b>
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
          <div id="form-student">
            <div class="table-responsive">
              <table id="postingTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Staff Name</th>
                    <th>IC</th>
                    <th>Facebook</th>
                    <th>Twitter</th>
                    <th>Instagram</th>
                    <th>YouTube</th>
                    <th>TikTok</th>
                    <th>WhatsApp</th>
                    <th>Total Posts</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data will be loaded here via AJAX -->
                </tbody>
              </table>
            </div>
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
  var dataTable;

  $(document).ready(function() {
    // Initialize DataTable with empty data
    dataTable = $('#postingTable').DataTable({
      dom: 'lBfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ],
      responsive: true,
      lengthChange: true,
      autoWidth: false
    });
  });

  $(document).on('change', '#from', async function(e){
    from = $(e.target).val();
    if(from && to) {
      await getPostingData(from, to);
    }
  });

  $(document).on('change', '#to', async function(e){
    to = $(e.target).val();
    if(from && to) {
      await getPostingData(from, to);
    }
  });

  function getPostingData(from, to) {
    return $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('/posting/admin/report/getPostingReport') }}",
      method: 'GET',
      data: {from: from, to: to},
      error: function(err){
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        if(data.error) {
          alert(data.error);
        } else {
          // Clear the table first
          dataTable.clear();
          
          // Add data rows
          let counter = 1;
          for(let i = 0; i < data.staff.length; i++) {
            dataTable.row.add([
              counter,
              data.staff[i].name,
              data.staff[i].ic,
              data.facebook[i] || 0,
              data.twitter[i] || 0,
              data.instagram[i] || 0,
              data.youtube[i] || 0,
              data.tiktok[i] || 0,
              data.whatsapp[i] || 0,
              data.total[i] || 0
            ]);
            counter++;
          }
          
          // Redraw the table
          dataTable.draw();
        }
      }
    });
  }

  $(document).ready(function() {
    $('#printButton').on('click', function(e) {
      e.preventDefault();
      $('.dt-buttons .buttons-print').click();
    });
  });
</script>
@endsection
