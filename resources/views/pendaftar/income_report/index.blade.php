@extends('layouts.pendaftar')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Student Family Income Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Student Family Income Report</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Filters</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
            <div class="row mt-3">
                <div class="mb-4">
                    <b>All State :</b>
                </div>
                <div class="row">
                    @php $counter = 0; @endphp
                    <div class="col-md-4">
                        <div class="form-group">
                            @foreach($data['state'] as $key => $sts)
                                @if($counter != 0 && $counter % 5 == 0)
                                    </div> <!-- Close previous form-group -->
                                </div> <!-- Close previous col-md-6 -->
                                <div class="col-md-4"> <!-- Start new col-md-6 -->
                                    <div class="form-group">
                                @endif
                                <div class="ml-2">
                                    <input type="checkbox" id="{{ $sts->code }}" class="filled-in" name="{{ $sts->code }}" value="{{ $sts->id }}">
                                    <label for="{{ $sts->code }}">{{ $sts->state_name }}</label>
                                </div>
                                @php $counter++; @endphp
                            @endforeach
                        </div>
                    </div> <!-- Close last col-md-6 -->
                </div>
            </div>
            <div class="row mt-3 ">
                <div class="mb-4">
                    <b>B40 Catagory :</b>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="ml-2">
                                <input type="checkbox" id="b40" class="filled-in" name="b40" value="yes">
                                <label for="b40">Yes</label>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            <a id="submit_form" class="btn btn-primary pull-right">Submit</a>
        </div>
        
        <div class="card-body p-0">
          <table id="complex_header" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th>
                        No.
                    </th>
                    <th>
                        Student Name
                    </th>
                    <th>
                        No. IC
                    </th>
                    <th>
                        Gender
                    </th>
                    <th>
                        Program
                    </th>
                    <th>
                        No. Matric
                    </th>
                    <th>
                        Session
                    </th>
                    <th>
                        Semester
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Phone No.
                    </th>
                    <th>
                        Address
                    </th>
                    <th>
                        Dependant
                    </th>
                    <th>
                        Waris Name
                    </th>
                    <th>
                        Waris IC
                    </th>
                    <th>
                        Waris Status
                    </th>
                    <th>
                        Waris 2 Name
                    </th>
                    <th>
                        Waris 2 IC
                    </th>
                    <th>
                        Waris 2 Status
                    </th>
                    <th>
                        Family Income (RM)
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

{{-- @if(session('newStud'))
    <script>
      alert('Success! Student has been registered!')
      window.open('/pendaftar/surat_tawaran?ic={{ session("newStud") }}')
    </script>
@endif --}}

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
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>

  <script type="text/javascript">

    $(document).ready(function(){
        $('#submit_form').click(function(){ // Replace 'yourButtonId' with the ID of the button you want to trigger the action
            var val = $('input[type=checkbox]:checked').map(function(){
                return $(this).val();
            }).get();

            $('#complex_header').DataTable().destroy();

            $.ajax({
                headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                url      : "{{ url('pendaftar/student/incomeReport/getIncomeReport') }}",
                method   : 'POST',
                data 	 : {val: val},
                error:function(err){
                    alert("Error");
                    console.log(err);
                },
                success  : function(data){
                    $('#table').html(data);

                    $('#complex_header').DataTable({
                      dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                      
                      buttons: [
                          'copy', 'csv', 'excel', 'pdf', 'print'
                      ],
                    });
                }
            });
        });
    });
  </script>
@endsection
