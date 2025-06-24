@extends('layouts.finance')

@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Graduate Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Graduate Report</li>
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
          <b>Search Graduate Report</b>
          <button id="printButton" class="waves-effect waves-light btn btn-primary btn-sm">
            <i class="ti-printer"></i>&nbsp Print
          </button>
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
                  <label class="form-label" for="to">TO</label>
                  <input type="date" class="form-control" id="to" name="to">
                  </div>
              </div>
          </div>
          
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label class="form-label fw-bold text-info">
                          <i class="mdi mdi-file-chart me-2"></i>Report Format
                      </label>
                      <div class="mt-2">
                          <div class="form-check">
                              <input class="form-check-input" type="radio" name="format" id="format_calendar" value="calendar" checked>
                              <label class="form-check-label fw-bold text-primary" for="format_calendar">
                                  Calendar Format (Weekly View)
                              </label>
                          </div>
                          <div class="form-check">
                              <input class="form-check-input" type="radio" name="format" id="format_table" value="table">
                              <label class="form-check-label fw-bold text-success" for="format_table">
                                  Table Format (Monthly Columns)
                              </label>
                          </div>
                          <div class="form-check">
                              <input class="form-check-input" type="radio" name="format" id="format_payment" value="payment">
                              <label class="form-check-label fw-bold text-warning" for="format_payment">
                                  Payment Comparison Format (Expected vs Actual)
                              </label>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          
          <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Find</button>

          {{-- <div class="row">
              <div class="col-md-12">
                  <button type="submit" class="btn btn-primary btn-lg me-2" onclick="submit()">
                      <i class="mdi mdi-magnify me-2"></i>Find
                  </button>
              </div>
          </div> --}}

          <!-- Loading spinner -->
          <div id="loading-spinner" style="display: none; text-align: center; padding: 20px;">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p>Loading report...</p>
          </div>

          <div id="form-student">
            <!-- Report content will be loaded here -->
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

<script>
  $(document).ready( function () {
    // Initialize any DataTables if needed
  });
</script>

<script type="text/javascript">
  var from = '';
  var to = '';

  function submit()
  {
    var from = $('#from').val();
    var to = $('#to').val();
    var format = $('input[name="format"]:checked').val();

    if (!from || !to) {
      alert("Please select both from and to dates");
      return;
    }

    // Show the spinner
    $('#loading-spinner').css('display', 'block');

    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('finance/report/graduatereport/getGraduateReport') }}",
        method   : 'GET',
        data 	 : {from: from, to: to, format: format},
        error:function(err){
            alert("Error");
            console.log("Error details:", err);

            // Hide the spinner on error
            $('#loading-spinner').css('display', 'none');
        },
        success  : function(data){
          if(data.error)
          {
            alert(data.error);
          }

          // Hide the spinner on success
          $('#loading-spinner').css('display', 'none');

          $('#form-student').html(data);
          
          // Initialize Bootstrap tabs functionality
          setTimeout(function() {
            // Enable Bootstrap tab functionality
            $('.nav-tabs a').click(function (e) {
              e.preventDefault();
              $(this).tab('show');
            });
            
            // Make sure the first tab is active
            $('.nav-tabs a:first').tab('show');
          }, 100);
        }
    });
  }
  
  $(document).ready(function() {
    $('#printButton').on('click', function(e) {
      e.preventDefault();
      printReport();
    });
  });

  function printReport() {
    var from = $('#from').val();
    var to = $('#to').val();
    var format = $('input[name="format"]:checked').val();

    if (!from || !to) {
      alert("Please select both from and to dates first");
      return;
    }

    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('finance/report/graduatereport/getGraduateReport?print=true') }}",
      method: 'GET',
      data: { from: from, to: to, format: format },
      error: function(err) {
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        var newWindow = window.open();
        newWindow.document.write(data);
        newWindow.document.close();
      }
    });
  }
</script>
@endsection 