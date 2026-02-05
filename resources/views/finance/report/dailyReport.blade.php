@extends('layouts.finance')

@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Daily Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Daily Report</li>
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
          <b>Search Student</b>
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
                <label class="form-label" for="name">TO</label>
                <input type="date" class="form-control" id="to" name="to">
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Find</button>
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

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown

      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ],
    });
  });
</script>

<script type="text/javascript">
  var from = '';
  var to = '';

  // $(document).on('change', '#from', async function(e){
  //     from = $(e.target).val();

  //     await getStudent(from,to);
  //   });

  //   $(document).on('change', '#to', async function(e){
  //     to = $(e.target).val();

  //     await getStudent(from,to);
  //   });


  // function getStudent(from,to)
  // {
  //   return $.ajax({
  //           headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
  //           url      : "{{ url('finance/report/dailyreport/getDailyReport') }}",
  //           method   : 'GET',
  //           data 	 : {from: from, to: to},
  //           error:function(err){
  //               alert("Error");
  //               console.log(err);
  //           },
  //           success  : function(data){
  //               $('#form-student').html(data);
  //           }
  //       });

  // }

  function submit() {

    var from = $('#from').val();
    var to = $('#to').val();

    // Show the spinner
    $('#loading-spinner').css('display', 'block');

    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('finance/report/dailyreport/getDailyReport') }}",
      method: 'GET',
      data: {
        from: from,
        to: to
      },
      error: function(err) {
        alert("Error");
        console.log("Error details:", err);

        // If you want to log specific details from the error object
        if (err.responseJSON) {
          console.log("Response JSON:", err.responseJSON);
        }
        if (err.status) {
          console.log("Status code:", err.status);
        }
        if (err.statusText) {
          console.log("Status text:", err.statusText);
        }
        if (err.responseText) {
          console.log("Response text:", err.responseText);
        }

        // Hide the spinner on error
        $('#loading-spinner').css('display', 'none');
      },
      success: function(data) {

        if (data.error) {
          alert(data.error);

        }

        // Hide the spinner on success
        $('#loading-spinner').css('display', 'none');

        $('#form-student').html(data);

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

    var filters = {
      'yuran-pengajian': $('#chk-yuran-pengajian').is(':checked'),
      'yuran-konvokesyen': $('#chk-yuran-konvokesyen').is(':checked'),
      'denda-lain': $('#chk-denda-lain').is(':checked'),
      'bayaran-lebihan': $('#chk-bayaran-lebihan').is(':checked'),
      'bayaran-insentif': $('#chk-bayaran-insentif').is(':checked'),
      'penajaan-elaun': $('#chk-penajaan-elaun').is(':checked')
    };

    // Check if any checkbox is actually checked
    var anyChecked = $('.table-filter:checked').length > 0;

    // If no checkboxes are checked, default to true (show all)
    // This matches the behavior of the screen view (default view = all)
    if (!anyChecked) {
      for (var key in filters) {
        filters[key] = true;
      }
    }

    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('finance/report/dailyreport/getDailyReport?print=true') }}",
      method: 'GET',
      data: {
        from: from,
        to: to,
        filters: filters
      },
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