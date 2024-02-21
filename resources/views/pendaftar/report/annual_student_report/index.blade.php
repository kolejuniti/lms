@extends('layouts.pendaftar')

@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Annual Student Number Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Annual Student Number Report</li>
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
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="year">Year</label>
                <input type="number" class="form-control" min="1900" max="2099" step="1" placeholder="year" id="year" name="year" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Find</button>
            </div>
          </div>
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
    function submit()
  {

    var year = $('#year').val();

    return $.ajax({
              headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
              url      : "{{ url('pendaftar/student/annualStudentReport/getAnnualStudentReport') }}",
              method   : 'POST',
              data 	 : {year: year},
              error:function(err){
                  alert("Error");
                  console.log(err);
              },
              success  : function(data){
                // var d = new Date();

                // var month = d.getMonth()+1;
                // var day = d.getDate();

                // var output = d.getFullYear() + '/' +
                //     (month<10 ? '0' : '') + month + '/' +
                //     (day<10 ? '0' : '') + day;

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
              }
          });



  }
  
 
  </script>
@endsection
