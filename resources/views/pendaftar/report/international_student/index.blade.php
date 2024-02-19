@extends('layouts.pendaftar')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">International Student Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">International Student Report</li>
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
          <h3 class="card-title">International Student Report</h3>
        </div>
        <div class="card-body p-0">
          <br>
          <table id="table" class="table table-striped projects display">
            <thead>
                <tr>
                    <th style="width: 1%">
                        No.
                    </th>
                    <th style="width: 15%">
                        Name
                    </th>
                    <th style="width: 10%">
                        Gender
                    </th>
                    <th style="width: 15%">
                        No. IC
                    </th>
                    <th style="width: 10%">
                        No. Matric
                    </th>
                    <th style="width: 10%">
                        Program
                    </th>
                    <th style="width: 10%">
                        Intake
                    </th>
                    <th style="width: 10%">
                        Session
                    </th>
                    <th style="width: 5%">
                        Semester
                    </th>
                    <th style="width: 10%">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($data['student'] as $key => $stud)
              <tr>
                <td>
                  {{ $key+1 }}
                </td>
                <td>
                  {{ $stud->name }}
                </td>
                <td>
                  {{ $stud->code }}
                </td>
                <td>
                  {{ $stud->ic }}
                </td>
                <td>
                  {{ $stud->no_matric }}
                </td>
                <td>
                  {{ $stud->progcode }}
                </td>
                <td>
                  {{ $stud->intake }}
                </td>
                <td>
                  {{ $stud->session }}
                </td>
                <td>
                  {{ $stud->semester }}
                </td>
                <td>
                  {{ $stud->status }}
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
          <br>
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
    $(document).ready( function () {
        $('#table').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );
  </script>
@endsection
