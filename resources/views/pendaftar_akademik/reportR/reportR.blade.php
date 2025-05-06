@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : ''))


@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Student Report R</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Student Report R</li>
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
          {{-- <button id="printButton" class="waves-effect waves-light btn btn-primary btn-sm">
            <i class="ti-printer"></i>&nbsp Print
          </button> --}}
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
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="session">Session</label>
                <select class="form-select" id="session" name="session">
                  <option value="-" selected disabled>-</option>
                  @foreach($data['session'] as $ses)
                  <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option>
                  @endforeach
                </select>
              </div>
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

<!-- SheetJS (XLSX) -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

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
    var from = '';
    var to = '';
    var session = '';
    var dataTable = null;

    // Document ready function
    $(function() {
      console.log("Document ready - initializing event handlers");
    });

    $(document).on('change', '#from', function(e){
      from = $(e.target).val();
      getStudent(from,to,session);
    });

    $(document).on('change', '#to', function(e){
      to = $(e.target).val();
      getStudent(from,to,session);
    });

    $(document).on('change', '#session', function(e){
      session = $(e.target).val();
      getStudent(from,to,session);
    });
    
    // Column visibility toggle functionality - use direct binding for dynamically loaded content
    $(document).on('change', '.toggle-column', function() {
      var column = parseInt($(this).attr('data-column'));
      var visible = $(this).prop('checked');
      console.log("Column toggle: ", column, visible);
      
      // Make sure dataTable is initialized
      if (dataTable) {
        dataTable.column(column).visible(visible);
      }
    });
    
    // Handle Show All button
    $(document).on('click', '#show-all-columns', function() {
      console.log("Show all columns clicked");
      $('.toggle-column').prop('checked', true);
      
      // Make sure dataTable is initialized
      if (dataTable) {
        for (var i = 0; i < 13; i++) { // We have 13 columns (0-12)
          dataTable.column(i).visible(true);
        }
      }
    });
    
    // Handle Hide All button
    $(document).on('click', '#hide-all-columns', function() {
      console.log("Hide all columns clicked");
      $('.toggle-column').prop('checked', false);
      
      // Make sure dataTable is initialized
      if (dataTable) {
        for (var i = 0; i < 13; i++) { // We have 13 columns (0-12)
          dataTable.column(i).visible(false);
        }
      }
    });

  function getStudent(from,to,session)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('/AR/reportR/getStudentReportR') }}",
            method   : 'GET',
            data 	 : {from: from, to: to, session: session},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#form-student').html(data);

                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#myTable')) {
                  $('#myTable').DataTable().destroy();
                }
                
                // Initialize new DataTable
                dataTable = $('#myTable').DataTable({
                  dom: 'lBfrtip', 
                  buttons: [
                      {
                          extend: 'copy',
                          exportOptions: {
                              columns: ':visible'
                          },
                          action: function(e, dt, button, config) {
                              var mainTableData = dt.buttons.exportData(config.exportOptions);
                              var agingData = Array.from($('#aging_report table tbody tr').map(function() {
                                  return [$(this).find('td:first').text(), $(this).find('td:last').text()];
                              }));
                              
                              var combinedBody = [...mainTableData.body, [''], ['Student Aging Report'], [''], ...agingData];
                              config.exportOptions.body = combinedBody;
                              $.fn.dataTable.ext.buttons.copyHtml5.action(e, dt, button, config);
                          }
                      },
                      {
                          extend: 'excel',
                          exportOptions: {
                              columns: ':visible'
                          },
                          action: function(e, dt, button, config) {
                              // Prevent default action
                              e.preventDefault();
                              
                              // Get the main table data
                              var mainTableData = dt.buttons.exportData({
                                  columns: ':visible'
                              });
                              
                              // Create a new workbook and worksheet
                              var wb = XLSX.utils.book_new();
                              
                              // Convert main table to worksheet
                              var mainWs = XLSX.utils.aoa_to_sheet([
                                  mainTableData.header,
                                  ...mainTableData.body
                              ]);
                              
                              // Get the total student count from the rendered table footer
                              var studentCount = $('#myTable tfoot td:last').text().trim();
                              
                              // Add total row
                              XLSX.utils.sheet_add_aoa(mainWs, [["TOTAL STUDENTS", "", "", "", "", "", "", "", "", "", "", "", studentCount]], 
                                  { origin: mainTableData.body.length + 1 });
                              
                              // Create aging report data
                              var agingTitle = [["Student Aging Report"]];
                              var agingHeader = [["Days Range", "Number of Students"]];
                              var agingData = Array.from($('#aging_report table tbody tr').map(function() {
                                  return [
                                      $(this).find('td:first').text(),
                                      $(this).find('td:last').text()
                                  ];
                              }));
                              
                              // Calculate the row where aging report starts (main table + 3 rows spacing)
                              var agingStartRow = mainTableData.body.length + 4;
                              
                              // Merge main worksheet with aging report
                              XLSX.utils.sheet_add_aoa(mainWs, [[""], [""]], { origin: agingStartRow - 2 });
                              XLSX.utils.sheet_add_aoa(mainWs, agingTitle, { origin: agingStartRow });
                              XLSX.utils.sheet_add_aoa(mainWs, agingHeader, { origin: agingStartRow + 1 });
                              XLSX.utils.sheet_add_aoa(mainWs, agingData, { origin: agingStartRow + 2 });
                              
                              // Add the worksheet to workbook
                              XLSX.utils.book_append_sheet(wb, mainWs, 'Student Report');
                              
                              // Generate Excel file and trigger download
                              XLSX.writeFile(wb, 'Student_Report_R.xlsx');
                          }
                      },
                      {
                          extend: 'pdf',
                          exportOptions: {
                              columns: ':visible'
                          },
                          customize: function(doc) {
                              doc.content.push(
                                  { text: '\n\nStudent Aging Report', style: 'subheader' },
                                  {
                                      table: {
                                          headerRows: 1,
                                          body: [
                                              ['Days Range', 'Number of Students'],
                                              ...Array.from($('#aging_report table tbody tr').map(function() {
                                                  return [$(this).find('td:first').text(), $(this).find('td:last').text()];
                                              }))
                                          ]
                                      }
                                  }
                              );
                          }
                      },
                      {
                          extend: 'csv',
                          exportOptions: {
                              columns: ':visible'
                          },
                          customize: function(csv) {
                              var agingData = '\n\nStudent Aging Report\n' +
                                  Array.from($('#aging_report table tbody tr').map(function() {
                                      return $(this).find('td:first').text() + ',' + $(this).find('td:last').text();
                                  })).join('\n');
                              return csv + agingData;
                          }
                      },
                      {
                          extend: 'print',
                          exportOptions: {
                              columns: ':visible'
                          },
                          customize: function(win) {
                              $(win.document.body).append(
                                  $('<h2>Student Aging Report</h2>')
                                  .add($('#aging_report table').clone())
                              );
                          }
                      }
                  ],
                  // Apply column visibility based on checkbox states
                  "initComplete": function() {
                    var table = this.api();
                    
                    // Set column visibility based on checkboxes
                    $('.toggle-column').each(function() {
                      var column = parseInt($(this).attr('data-column'));
                      var visible = $(this).prop('checked');
                      table.column(column).visible(visible);
                    });
                  }
                });
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

    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('finance/report/dailyreport/getDailyReport?print=true') }}",
      method: 'GET',
      data: { from: from, to: to },
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
