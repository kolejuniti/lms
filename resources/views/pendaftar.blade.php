@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : (Auth::user()->usrtype == "HEA" ? 'layouts.hea' : '')))))

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Student List</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Student List</li>
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
          <h3 class="card-title">Search Student</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>

        <!-- Filters Section -->
        <div class="card-body">
          <div class="row mt-3">
            <div class="col-md-6 mr-3" id="year-card">
              <div class="form-group">
                <label class="form-label" for="year">Year</label>
                <select class="form-select" id="year" name="year">
                  <option value="-" selected>-</option>
                  @foreach ($year as $yr)
                    <option value="{{ $yr->year }}">{{ $yr->year }}</option> 
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 mr-3" id="session-card">
              <div class="form-group">
                <label class="form-label" for="session">Session</label>
                <select class="form-select" id="session" name="session">
                  <option value="-" selected>-</option>
                  @foreach ($session as $ses)
                    <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option> 
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 ml-3">
              <div class="form-group">
                <label class="form-label" for="program">Program</label>
                <select class="form-select" id="program" name="program">
                  <option value="-" selected>-</option>
                  @foreach ($program as $prg)
                    <option value="{{ $prg->id }}">{{ $prg->progcode }} - {{ $prg->progname }}</option> 
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 mr-3" id="semester-card">
              <div class="form-group">
                <label class="form-label" for="semester">Semester</label>
                <select class="form-select" id="semester" name="semester">
                  <option value="-" selected>-</option>
                  @foreach ($semester as $ses)
                    <option value="{{ $ses->id }}">{{ $ses->semester_name }}</option> 
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 mr-3" id="status-card">
              <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select class="form-select" id="status" name="status">
                  <option value="-" selected>-</option>
                  @foreach ($status as $sts)
                    <option value="{{ $sts->id }}">{{ $sts->name }}</option> 
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row mt-3" id="group-card" hidden>
            <div class="col-md-6 ml-3">
              <div class="form-group">
                <label class="form-label" for="group">Group</label>
                <select class="form-select" id="group" name="group">
                </select>
              </div>
            </div>        
          </div>
        </div>
        <!-- End Filters Section -->

        <!-- Legend Section (Bootstrap Card) -->
        <div class="card mx-3 mb-3 border">
          <div class="card-body">
            <!-- You can uncomment this if you'd like a title -->
            <!-- <h5 class="card-title">Legend</h5> -->

            <p class="card-text mb-0">
              <span class="d-inline-flex align-items-center me-4">
                <!-- Modern Red Box for Student on Leave -->
                <span class="d-inline-block me-2" 
                      style="width: 20px; height: 20px; border-radius: 4px; 
                            background-color: #f44336; 
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </span>
                Student on Leave
              </span>

              <span class="d-inline-flex align-items-center">
                <!-- Modern White Box for Student on Campus -->
                <span class="d-inline-block me-2" 
                      style="width: 20px; height: 20px; border-radius: 4px; 
                            background-color: #fff; 
                            border: 1px solid #ced4da; 
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </span>
                Student on Campus
              </span>
            </p>
          </div>
        </div>
        <!-- End Legend Section -->


        <!-- Table Section -->
        <div class="card-body p-0">
          <table id="complex_header" class="table table-striped projects display dataTable">
            <thead>
              <tr>
                <th style="width: 1%">No.</th>
                <th style="width: 15%">Name</th>
                <th style="width: 15%">No. IC</th>
                <th style="width: 10%">No. Matric</th>
                <th style="width: 10%">Program</th>
                <th style="width: 10%">Race</th>
                <th style="width: 10%">Religion</th>
                <th style="width: 20%"></th>
              </tr>
            </thead>
            <tbody id="table">
              <!-- Table rows will be populated here -->
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->

        <div id="uploadModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                  &times;
                </button>
              </div>
              <div class="modal-body" id="getModal">
                <!-- Modal body will be loaded here -->
              </div>
            </div>
          </div>
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
  function deleteMaterial(id) {     
    Swal.fire({
      title: "Are you sure?",
      text: "This will be permanent",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!"
    }).then(function(res){
      if (res.isConfirmed) {
        $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: "{{ url('/pendaftar/delete') }}",
          method: 'DELETE',
          data: { id: id },
          error: function(err){
            alert("Error");
            console.log(err);
          },
          success: function(data){
            window.location.reload();
            alert("success");
          }
        });
      }
    });
  }
</script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
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
  var selected_program = "";
  var selected_session = "";
  var selected_year = "";
  var selected_semester = "";
  var selected_status = "";

  $(document).on('change', '#year', function(e) {
    selected_year = $(e.target).val();
    getStudent(selected_program, selected_session, selected_year, selected_semester, selected_status);
  });

  $(document).on('change', '#program', function(e) {
    selected_program = $(e.target).val();
    getStudent(selected_program, selected_session, selected_year, selected_semester, selected_status);
  });

  $(document).on('change', '#session', function(e) {
    selected_session = $(e.target).val();
    getStudent(selected_program, selected_session, selected_year, selected_semester, selected_status);
  });

  $(document).on('change', '#semester', function(e) {
    selected_semester = $(e.target).val();
    getStudent(selected_program, selected_session, selected_year, selected_semester, selected_status);
  });

  $(document).on('change', '#status', async function(e) {
    selected_status = $(e.target).val();
    await getStudent(selected_program, selected_session, selected_year, selected_semester, selected_status);
  });

  // 1) Helper functions to add a custom fill style
function addFill(styleSheet, color) {
    const fills = $('fills', styleSheet);
    let fillCount = parseInt(fills.attr('count'), 10);
    fillCount++;
    fills.attr('count', fillCount);

    // newFillId is fillCount - 1 (0-based index)
    const newFillId = fillCount - 1;
    // Append a new <fill> with a solid pattern using the specified color
    fills.append(
        `<fill>
           <patternFill patternType="solid">
             <fgColor rgb="${color}"/>
             <bgColor indexed="64"/>
           </patternFill>
         </fill>`
    );
    return newFillId;
}

function addCellStyle(cellXfs, fillId) {
    let xfCount = parseInt(cellXfs.attr('count'), 10);
    xfCount++;
    cellXfs.attr('count', xfCount);

    // newXfId is xfCount - 1
    const newXfId = xfCount - 1;
    // Append a new <xf> referencing our fill
    cellXfs.append(
        `<xf xfId="0" applyFill="1" fillId="${fillId}"
             borderId="0" fontId="0" numFmtId="0"
             applyNumberFormat="0" applyBorder="0" applyAlignment="0"
             applyProtection="0"/>`
    );
    return newXfId;
}


  function getStudent(program, session, year, semester, status) {
    $('#complex_header').DataTable().destroy();
    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('pendaftar/group/getStudentTableIndex') }}",
      method: 'POST',
      data: { program: program, session: session, year: year, semester: semester, status: status },
      beforeSend: function(xhr) {
        $("#complex_header").LoadingOverlay("show", {
          image: `<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                   xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;"
                   xml:space="preserve">
                  <rect x="0" y="10" width="4" height="10" fill="#333" opacity="0.2">
                    <animate attributeName="opacity" attributeType="XML"
                             values="0.2; 1; .2"
                             begin="0s" dur="0.6s" repeatCount="indefinite"/>
                    <animate attributeName="height" attributeType="XML"
                             values="10; 20; 10"
                             begin="0s" dur="0.6s" repeatCount="indefinite"/>
                    <animate attributeName="y" attributeType="XML"
                             values="10; 5; 10"
                             begin="0s" dur="0.6s" repeatCount="indefinite"/>
                  </rect>
                  <rect x="8" y="10" width="4" height="10" fill="#333" opacity="0.2">
                    <animate attributeName="opacity" attributeType="XML"
                             values="0.2; 1; .2"
                             begin="0.15s" dur="0.6s" repeatCount="indefinite"/>
                    <animate attributeName="height" attributeType="XML"
                             values="10; 20; 10"
                             begin="0.15s" dur="0.6s" repeatCount="indefinite"/>
                    <animate attributeName="y" attributeType="XML"
                             values="10; 5; 10"
                             begin="0.15s" dur="0.6s" repeatCount="indefinite"/>
                  </rect>
                  <rect x="16" y="10" width="4" height="10" fill="#333" opacity="0.2">
                    <animate attributeName="opacity" attributeType="XML"
                             values="0.2; 1; .2"
                             begin="0.3s" dur="0.6s" repeatCount="indefinite"/>
                    <animate attributeName="height" attributeType="XML"
                             values="10; 20; 10"
                             begin="0.3s" dur="0.6s" repeatCount="indefinite"/>
                    <animate attributeName="y" attributeType="XML"
                             values="10; 5; 10"
                             begin="0.3s" dur="0.6s" repeatCount="indefinite"/>
                  </rect>
                 </svg>`,
          background: "rgba(255,255,255, 0.3)",
          imageResizeFactor: 1,
          imageAnimation: "2000ms pulse",
          imageColor: "#019ff8",
          text: "Please wait...",
          textResizeFactor: 0.15,
          textColor: "#019ff8"
        });
        $("#complex_header").LoadingOverlay("hide");
      },
      error: function(err) {
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        $('#complex_header').removeAttr('hidden');
        $('#complex_header').html(data);
        $('#complex_header').DataTable({
          dom: 'lBfrtip',
            // Example: Sort by "Name" column ascending on load
            // The marker is column 0, "No." is column 1, so "Name" is column 2
            order: [[1, 'asc']], 
            columnDefs: [
              {
                targets: 0, // marker column
                visible: false
              }
            ],
            buttons: [
                'copy',
                'csv',
                {
                    extend: 'excelHtml5',
                    text: 'Custom Excel', // Custom label
                    exportOptions: {
                        // Include the hidden marker column so we can read "red"
                        columns: ':visible, :hidden'
                    },
                    customize: function (xlsx) {
                        // 1) References to relevant parts of the workbook
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var styleSheet = xlsx.xl['styles.xml'];

                        // 2) Create a new fill for red background
                        var fillId = addFill(styleSheet, 'FFFF0000'); // ARGB for red
                        // 3) Create a cell style (xf) that uses that fill
                        var xfId = addCellStyle($('cellXfs', styleSheet), fillId);

                        // 4) Loop each row (<row>) in sheet1.xml
                        $('row', sheet).each(function() {
                            // The first <c> in the row is our hidden marker
                            var markerCell = $(this).find('c').eq(0);
                            if (!markerCell) return;

                            // If the marker is "red", apply our new style to the entire row
                            if (markerCell.text() === 'red') {
                                $(this).find('c').attr('s', xfId);
                            }
                        });

                        // 5) Hide the first column so it won't be visible in Excel
                        //    (the marker column is still there, but hidden)
                        var firstCol = $('cols col', sheet).eq(0);
                        if (firstCol) {
                            firstCol.attr('hidden', '1');
                        }
                    }
                },
                'pdf',
                'print'
            ]
        });

      }
    });
  }

  function getProgram(ic) {
    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('pendaftar/getProgram') }}",
      method: 'POST',
      data: { ic: ic },
      error: function(err) {
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        $('#getModal').html(data);
        $('#uploadModal').modal('show');
      }
    });
  }
</script>
@endsection
