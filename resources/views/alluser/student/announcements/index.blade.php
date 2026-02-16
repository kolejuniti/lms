<!-- @extends('layouts.finance') -->

@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : (Auth::user()->usrtype == "HEA" ? 'layouts.hea' : '')))))

@section('main')

<!-- Include DataTables and other CSS files -->
<link rel="stylesheet" href="{{ asset('css/vendor.css') }}">

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Student Annoucement</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Student Annoucement</li>
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
          <b>Student Annoucement Wall</b>
          {{-- <button id="printButton" class="waves-effect waves-light btn btn-primary btn-sm">
            <i class="ti-printer"></i>&nbsp Print
          </button> --}}
        </div>
        <div class="card-body">
          {{-- <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                  <label class="form-label" for="program">Program</label>
                  <select class="form-select" id="program" name="program">
                  <option value="" selected disabled>-</option>
                    @foreach ($data['program'] as $prg)
                    <option value="{{ $prg->id }}">{{ $prg->progcode }} - {{ $prg->progname }}</option>
          @endforeach
          </select>
        </div>
      </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label class="form-label" for="session">Session</label>
        <select class="form-select" id="session" name="session">
          <option value="" selected disabled>-</option>
          @foreach ($data['session'] as $ses)
          <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label class="form-label" for="semester">Semester</label>
        <select class="form-select" id="semester" name="semester">
          <option value="" selected disabled>-</option>
          @foreach ($data['semester'] as $sem)
          <option value="{{ $sem->id }}">{{ $sem->semester_name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Find</button> --}}
    <div id="announcement-management" data-user-role="{{ Auth::user()->usrtype }}"></div>
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

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown

      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ],
    });

    renderAnnouncementSystem();
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

    var formData = new FormData();

    getInput = {
      program: $('#program').val(),
      session: $('#session').val(),
      semester: $('#semester').val()
    };

    formData.append('submitData', JSON.stringify(getInput))

    // Show the spinner
    $('#loading-spinner').css('display', 'block');

    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('AR/student/senateReport/getSenateReport') }}",
      method: 'POST',
      cache: false,
      processData: false,
      contentType: false,
      data: formData,
      error: function(err) {
        alert("Error");

        // Hide the spinner on error
        $('#loading-spinner').css('display', 'none');
      },
      success: function(data) {

        if (data.error) {
          alert(data.error);

        }

        // Hide the spinner on success
        $('#loading-spinner').css('display', 'none');

        // Log the received data to the console
        console.log(data.data);
        console.log('renderSenateReport is defined:', typeof window.renderSenateReport);
        renderSenateReport(data.data)

        // $('#form-student').html(data);

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
    var formData = new FormData();

    getInput = {
      program: $('#program').val(),
      session: $('#session').val(),
      semester: $('#semester').val()
    };

    formData.append('submitData', JSON.stringify(getInput))

    // Show the spinner
    $('#loading-spinner').css('display', 'block');

    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('AR/student/senateReport/getSenateReport?print=true') }}",
      method: 'POST',
      cache: false,
      processData: false,
      contentType: false,
      data: formData,
      error: function(err) {
        alert("Error");
        console.log(err);

        // Hide the spinner on success
        $('#loading-spinner').css('display', 'none');
      },
      success: function(data) {
        if (data.error) {
          alert(data.error);

        }

        // Hide the spinner on success
        $('#loading-spinner').css('display', 'none');

        var newWindow = window.open();
        newWindow.document.write(data);
        newWindow.document.close();
      }
    });
  }
</script>
@endsection