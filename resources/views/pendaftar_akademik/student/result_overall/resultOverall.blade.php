@extends('layouts.pendaftar_akademik')

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
          <h4 class="page-title">Result Filter</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Result Filter</li>
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
          <b>Select Input</b>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="from">FROM</label>
                <input type="date" class="form-control" id="from" name="from" value="{{ ($data['period']->Start) ?? '' }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="name">TO</label>
                <input type="date" class="form-control" id="to" name="to" value="{{ ($data['period']->End) ?? '' }}">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="program">Program</label>
                <select class="form-select" id="program" name="program" multiple style="height: 250px;">
                    @php
                        // Extract program IDs from program_data
                        $programDataIds = collect($data['program_data'])->pluck('program_id')->toArray();
                    @endphp
            
                    @foreach ($data['program'] as $prg)
                        <option value="{{ $prg->id }}" 
                            @if (in_array($prg->id, $programDataIds)) selected @endif>
                            {{ $prg->progcode }} - {{ $prg->progname }}
                        </option> 
                    @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="session">Session</label>
                <select class="form-select" id="session" name="session" multiple style="height: 250px;">
                    @php
                        // Extract Session IDs from session_data
                        $sessionDataIds = collect($data['session_data'])->pluck('session_id')->toArray();
                    @endphp
            
                    @foreach ($data['session'] as $ses)
                        <option value="{{ $ses->SessionID }}"
                            @if (in_array($ses->SessionID, $sessionDataIds)) selected @endif>
                            {{ $ses->SessionName }}
                        </option> 
                    @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="semester">Semester</label>
                <select class="form-select" id="semester" name="semester" multiple style="height: 250px;">
                    @php
                        // Extract Semester IDs from semester_data
                        $semesterDataIds = collect($data['semester_data'])->pluck('semester_id')->toArray();
                    @endphp
            
                    @foreach ($data['semester'] as $sem)
                        <option value="{{ $sem->id }}"
                            @if (in_array($sem->id, $semesterDataIds)) selected @endif>
                            {{ $sem->semester_name }}
                        </option> 
                    @endforeach
                </select>
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

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>

<script>
     $(document).ready( function () {
        $('#myTable').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );
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

  function submit()
  {

    var formData = new FormData();

    getInput = {
      from : $('#from').val(),
      to : $('#to').val(),
      program : $('#program').val(),
      session : $('#session').val(),
      semester : $('#semester').val()
    };

    console.log(getInput.program); // Verify this shows an array

    // Simple form validation
    if (!getInput.from || !getInput.to || !getInput.program.length || !getInput.session.length || !getInput.semester.length) {
        alert("Please fill in all fields before submitting.");
        return;
    }

    formData.append('submitData', JSON.stringify(getInput))

    // Show the spinner
    $('#loading-spinner').css('display', 'block');

    return $.ajax({
              headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
              url      : "{{ url('AR/student/resultOverall/submit') }}",
              method   : 'POST',
              cache : false,
              processData: false,
              contentType: false,
              data 	 : formData,
              error: function(xhr, status, error) {
                  let errorMessage = xhr.status + ': ' + xhr.statusText;
                  if (xhr.responseJSON && xhr.responseJSON.message) {
                      errorMessage = xhr.responseJSON.message; // Show the server error message
                  }
                  alert('Error - ' + errorMessage);

                  // Hide the spinner on error
                  $('#loading-spinner').css('display', 'none');
              },
              success  : function(data){

                if(data.error)
                {
                  alert(data.error);

                }else{
                    // Hide the spinner on success
                    $('#loading-spinner').css('display', 'none');
                    alert(data.success);

                }
                        
              }
          });
  }
  </script>
@endsection
