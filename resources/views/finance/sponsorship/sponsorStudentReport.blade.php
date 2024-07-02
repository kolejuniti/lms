@extends((Auth::user()->usrtype == "FN") ? 'layouts.finance' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : ''))

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Sponsor Student Payment Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Sponsor Student Payment Report</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
  

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Sponsor Info</b>
        <button id="printButton" class="waves-effect waves-light btn btn-primary btn-sm">
          <i class="ti-printer"></i>&nbsp Print
        </button>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <p>Sponsor &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['info']->sponsor }}</p>
                    </div>
                    <div class="form-group">
                        <p>No. Voucher &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['info']->no_document }}</p>
                    </div>
                </div>
            </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Students</h3>
        </div>
        <div class="card-body p-0" style="overflow-x: auto;">
          <table id="complex_headers" class="w-100 table table-bordered display margin-top-10 w-p100">
            <thead>
                <tr>
                    <th>
                      #
                    </th>
                    <th>
                        Student name
                    </th>
                    <th>
                        Ic/Passport No.
                    </th>
                    <th>
                        Program
                    </th>
                    <th>
                        Matric No.
                    </th>
                    <th style="text-align: center;">
                        Total Sponsorship
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach($data['students'] as $key => $std)
              <tr>
                <td>
                  {{ $key+1 }}
                </td>
                <td>
                  {{ $std->name }}
                </td>
                <td>
                  {{ $std->ic }}
                </td>
                <td>
                  {{ $std->progcode }}
                </td>
                <td>
                  {{ $std->no_matric }}
                </td>
                <td style="text-align: center;">
                  {{ $std->amount }}
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>

      <div class="row justify-content-center">
        <!-- pecahan -->
        <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
          <div class="card-header mx-auto">
          <b>Sponsor Total By Program
            </b>
          </div>
          <div class="card-body p-0">
            <table id="myTable" class="table table-striped projects display dataTable">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          No.
                      </th>
                      <th style="width: 5%">
                          PROGRAM
                      </th>
                      <th style="width: 5%">
                          QUOTE
                      </th>
                  </tr>
              </thead>
              <tbody id="table">
                @foreach ($data['program'] as $key => $prg)
                <tr>
                  <td>
                    {{ $prg->program_ID }}
                  </td>
                  <td>
                    {{ $prg->progcode }}
                  </td>
                  <td>
                    {{ (!empty($data['totalPayment'])) ? number_format($data['totalPayment'][$key], 2) : 0.00}}
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                    <td colspan="2" style="text-align: center">
                        TOTAL
                    </td>
                    <td>
                        {{ number_format(array_sum($data['totalPayment']), 2) }}
                    </td>
                  </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
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

<script type="text/javascript">



</script>

<script>
  $(document).ready(function() {
    $('#complex_headers').DataTable({
      dom: 'lBfrtip',
      buttons: [
        {
          extend: 'copy',
          title: "<h3>" + "Title :" + " {{ $data['info']->sponsor }}" + "</h3>",
        },
        {
          extend: 'csv',
          title: "<h3>" + "Title :" + " {{ $data['info']->sponsor }}" + "</h3>",
        },
        {
          extend: 'excel',
          title: "<h3>" + "Title :" + " {{ $data['info']->sponsor }}" + "</h3>",
        },
        {
          extend: 'pdf',
          title: "<h3>" + "Title :" + " {{ $data['info']->sponsor }}" + "</h3>",
        },
        {
          extend: 'print',
          title: "<h3>" + "Title :" + " {{ $data['info']->sponsor }}" + "</h3>",
          customize: function (win) {
            // Or, if you want it to appear right below the title, you could use:
            $(win.document.body).find('h3').after("No. Voucher :" + " {{ $data['info']->no_document }}");
          }
        }
      ],
    });
  });

  $(document).ready(function() {
    $('#printButton').on('click', function(e) {
      e.preventDefault();
      printReport();
    });
  });

  function printReport() {
    var id = '{{ request()->id }}';

    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('finance/sponsorship/payment/report/showReportStudent?print=true') }}",
      method: 'GET',
      data: { id: id},
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
