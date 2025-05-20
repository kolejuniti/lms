@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : ''))

@section('main')

<!-- Add Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

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
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="EA">EA</label>
                <select class="form-select select2" id="EA" name="EA" style="width: 100%">
                  <option value="-" selected disabled>-</option>
                  @foreach($data['EA'] as $ea)
                  <option value="{{ $ea->id }}">{{ $ea->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div id="form-student">
            <!-- Combined export buttons will be added after data is loaded -->
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

<!-- Add Select2 JS after jQuery -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    var EA = '';
    var dataTable = null;

    // Document ready function
    $(function() {
      console.log("Document ready - initializing event handlers");
    });

    $(document).on('change', '#from', function(e){
      from = $(e.target).val();
      updateCombinedExportButtons();
      getStudent(from,to,session,EA);
    });

    $(document).on('change', '#to', function(e){
      to = $(e.target).val();
      updateCombinedExportButtons();
      getStudent(from,to,session,EA);
    });

    $(document).on('change', '#session', function(e){
      session = $(e.target).val();
      getStudent(from,to,session,EA);
    });

    $(document).on('change', '#EA', function(e){
      EA = $(e.target).val();
      getStudent(from,to,session,EA);
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

    // Function to update combined export buttons visibility
    function updateCombinedExportButtons() {
      // Remove existing buttons
      $('#combined-export-buttons').remove();
      
      // Only show combined export buttons if both dates are filled
      if (from && to && $('#form-student').html().trim() !== '') {
        $('#form-student').prepend(
          '<div id="combined-export-buttons" class="mb-4">' +
            '<div class="card">' +
              '<div class="card-header bg-primary text-white">' +
                '<b>Student Report R - Combined Export</b>' +
              '</div>' +
              '<div class="card-body">' +
                '<p>Export all tables in a single file:</p>' +
                '<button class="btn btn-info export-combined-excel"><i class="fa fa-file-excel-o"></i> Excel</button> ' +
                '<button class="btn btn-danger export-combined-pdf"><i class="fa fa-file-pdf-o"></i> PDF</button> ' +
                '<button class="btn btn-secondary export-combined-print"><i class="fa fa-print"></i> Print</button>' +
              '</div>' +
            '</div>' +
          '</div>'
        );
      }
    }

  function getStudent(from,to,session,EA)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('/AR/reportR/getStudentReportR') }}",
            method   : 'GET',
            data 	 : {from: from, to: to, session: session, EA: EA},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#form-student').html(data);

                // Add combined export buttons at the top only if date fields have values
                var fromDate = $('#from').val();
                var toDate = $('#to').val();
                
                if (fromDate && toDate) {
                  if (!$('#combined-export-buttons').length) {
                    $('#form-student').prepend(
                      '<div id="combined-export-buttons" class="mb-4">' +
                        '<div class="card">' +
                          '<div class="card-header bg-primary text-white">' +
                            '<b>Student Report R - Combined Export</b>' +
                          '</div>' +
                          '<div class="card-body">' +
                            '<p>Export all tables in a single file:</p>' +
                            '<button class="btn btn-info export-combined-excel"><i class="fa fa-file-excel-o"></i> Excel</button> ' +
                            '<button class="btn btn-danger export-combined-pdf"><i class="fa fa-file-pdf-o"></i> PDF</button> ' +
                            '<button class="btn btn-secondary export-combined-print"><i class="fa fa-print"></i> Print</button>' +
                          '</div>' +
                        '</div>' +
                      '</div>'
                    );
                  }
                } else {
                  // Remove the combined export buttons if date fields are empty
                  $('#combined-export-buttons').remove();
                }

                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#myTable')) {
                  $('#myTable').DataTable().destroy();
                }
                
                // Add individual export buttons to each table
                // Wrap tables in a container with export buttons if not already done in the returned data
                $('.table-container').each(function() {
                  var tableId = $(this).find('table').attr('id');
                  if (!$(this).find('.individual-export-buttons').length) {
                    $(this).prepend(
                      '<div class="individual-export-buttons mb-2">' +
                        '<button class="btn btn-sm btn-info export-excel" data-table="' + tableId + '"><i class="fa fa-file-excel-o"></i> Excel</button> ' +
                        '<button class="btn btn-sm btn-danger export-pdf" data-table="' + tableId + '"><i class="fa fa-file-pdf-o"></i> PDF</button> ' +
                        '<button class="btn btn-sm btn-secondary export-print" data-table="' + tableId + '"><i class="fa fa-print"></i> Print</button>' +
                      '</div>'
                    );
                  }
                });
                
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
                              
                              // Convert total row to proper array of arrays format
                              var totalRow = ["TOTAL STUDENTS"];
                              // Fill empty cells for all columns except last
                              for (var i = 1; i < mainTableData.header.length - 1; i++) {
                                  totalRow.push("");
                              }
                              totalRow.push(studentCount);
                              
                              // Add total row
                              XLSX.utils.sheet_add_aoa(mainWs, [totalRow], 
                                  { origin: mainTableData.body.length + 1 });
                              
                              // Create aging report data
                              var agingTitle = [["Student Aging Report"]];
                              var agingHeader = [["Days Range", "Number of Students"]];
                              
                              // Properly convert aging data to array of arrays
                              var agingData = [];
                              $('#aging_report table tbody tr').each(function() {
                                  agingData.push([
                                      $(this).find('td:first').text().trim(),
                                      $(this).find('td:last').text().trim()
                                  ]);
                              });
                              
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
                
                // Remove any existing event handlers to prevent duplicates
                $(document).off('click', '.export-excel');
                $(document).off('click', '.export-pdf');
                $(document).off('click', '.export-print');
                $(document).off('click', '.export-combined-excel');
                $(document).off('click', '.export-combined-pdf');
                $(document).off('click', '.export-combined-print');
                
                // Handlers for individual table export buttons
                $(document).on('click', '.export-excel', function() {
                  var tableId = $(this).data('table');
                  exportTableToExcel(tableId);
                });
                
                $(document).on('click', '.export-pdf', function() {
                  var tableId = $(this).data('table');
                  exportTableToPdf(tableId);
                });
                
                $(document).on('click', '.export-print', function() {
                  var tableId = $(this).data('table');
                  printTable(tableId);
                });
                
                // Handlers for combined export buttons
                $(document).on('click', '.export-combined-excel', function() {
                  exportCombinedToExcel();
                });
                
                $(document).on('click', '.export-combined-pdf', function() {
                  exportCombinedToPdf();
                });
                
                $(document).on('click', '.export-combined-print', function() {
                  printCombinedTables();
                });
            }
        });
  }
  
  $(document).ready(function() {
    $('#printButton').on('click', function(e) {
      e.preventDefault();
      printReport();
    });

    // Initialize Select2
    $('.select2').select2({
      theme: 'bootstrap-5',
      width: '100%',
      placeholder: 'Search EA...',
      allowClear: true
    });

    // Handle Select2 change event
    $('#EA').on('select2:select', function (e) {
      EA = $(this).val();
      getStudent(from,to,session,EA);
    });

    // Handle Select2 clear event
    $('#EA').on('select2:clear', function (e) {
      EA = '-';
      getStudent(from,to,session,EA);
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

  // Function to export individual table to Excel
  function exportTableToExcel(tableId) {
    var table = $('#' + tableId);
    if (table.length === 0) return;
    
    // Get table title and date range
    var title = table.closest('.card').find('.card-header').text().trim() || tableId;
    var fromDate = $('#from').val() || 'N/A';
    var toDate = $('#to').val() || 'N/A';
    var dateRangeText = 'Date Range: ' + fromDate + ' to ' + toDate;
    
    // Create a new workbook and worksheet
    var wb = XLSX.utils.book_new();
    
    // Get table data
    var ws = XLSX.utils.table_to_sheet(table[0]);
    
    // Add title and date range at the top (3 rows above the table)
    // Get the ref area to determine where to add the header
    var ref = XLSX.utils.decode_range(ws['!ref']);
    
    // Shift all existing content down by 3 rows
    var newRef = {
      s: { r: ref.s.r + 3, c: ref.s.c },
      e: { r: ref.e.r + 3, c: ref.e.c }
    };
    
    // Move all cells down by 3 rows
    var cells = {};
    for (var R = ref.s.r; R <= ref.e.r; ++R) {
      for (var C = ref.s.c; C <= ref.e.c; ++C) {
        var cell_address = XLSX.utils.encode_cell({ r: R, c: C });
        var new_address = XLSX.utils.encode_cell({ r: R + 3, c: C });
        if (ws[cell_address]) {
          cells[new_address] = ws[cell_address];
        }
      }
    }
    
    // Clear existing cells and add the shifted ones
    for (var key in ws) {
      if (key[0] === '!') continue; // Skip special properties
      delete ws[key];
    }
    for (var key in cells) {
      ws[key] = cells[key];
    }
    
    // Update the reference
    ws['!ref'] = XLSX.utils.encode_range(newRef);
    
    // Add title and date range at the top
    XLSX.utils.sheet_add_aoa(ws, [[title]], { origin: { r: 0, c: 0 } });
    XLSX.utils.sheet_add_aoa(ws, [[dateRangeText]], { origin: { r: 1, c: 0 } });
    XLSX.utils.sheet_add_aoa(ws, [['']], { origin: { r: 2, c: 0 } }); // Empty row for spacing
    
    // Add worksheet to workbook
    var fileName = tableId + '_export.xlsx';
    XLSX.utils.book_append_sheet(wb, ws, tableId);
    
    // Generate Excel file and trigger download
    XLSX.writeFile(wb, fileName);
  }
  
  // Function to export individual table to PDF
  function exportTableToPdf(tableId) {
    var table = $('#' + tableId);
    if (table.length === 0) return;
    
    // Get table title and date range
    var title = table.closest('.card').find('.card-header').text().trim() || tableId;
    var fromDate = $('#from').val() || 'N/A';
    var toDate = $('#to').val() || 'N/A';
    var dateRangeText = 'Date Range: ' + fromDate + ' to ' + toDate;
    
    // Create document definition
    var docDefinition = {
      content: [
        { text: title, style: 'header' },
        { text: dateRangeText, style: 'subheader' },
        { text: '\n' },
        {
          table: {
            headerRows: 1,
            body: getTableData(table)
          }
        }
      ],
      styles: {
        header: {
          fontSize: 18,
          bold: true,
          margin: [0, 0, 0, 8]
        },
        subheader: {
          fontSize: 12,
          italics: true,
          margin: [0, 0, 0, 16]
        }
      },
      pageOrientation: 'landscape'
    };
    
    // Generate PDF and download
    pdfMake.createPdf(docDefinition).download(tableId + '_export.pdf');
  }
  
  // Function to print individual table
  function printTable(tableId) {
    var table = $('#' + tableId);
    if (table.length === 0) return;
    
    // Get table title and date range
    var title = table.closest('.card').find('.card-header').text().trim() || tableId;
    var fromDate = $('#from').val() || 'N/A';
    var toDate = $('#to').val() || 'N/A';
    var dateRangeText = 'Date Range: ' + fromDate + ' to ' + toDate;
    
    // Create a new window for printing
    var printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>' + title + '</title>');
    
    // Add necessary styles with improved print styling
    printWindow.document.write('<style>');
    printWindow.document.write('@media print {');
    printWindow.document.write('  @page { size: landscape; margin: 10mm; }');
    printWindow.document.write('}');
    printWindow.document.write('body { font-family: Arial, sans-serif; }');
    printWindow.document.write('h2 { text-align: center; margin-bottom: 8px; }');
    printWindow.document.write('.date-range { text-align: center; font-style: italic; margin-bottom: 20px; }');
    printWindow.document.write('table { border-collapse: collapse; width: 100%; table-layout: auto; font-size: 12px; }');
    printWindow.document.write('th, td { border: 1px solid #ddd; padding: 6px; text-align: left; overflow: visible; white-space: normal; }');
    printWindow.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    
    // Add title and date range
    printWindow.document.write('<h2>' + title + '</h2>');
    printWindow.document.write('<div class="date-range">' + dateRangeText + '</div>');
    
    // Clone the table and modify for printing
    var clonedTable = table.clone();
    
    // Remove any fixed width constraints on columns
    clonedTable.find('th, td').css('width', 'auto');
    
    // Write the modified table to the print window
    printWindow.document.write(clonedTable[0].outerHTML);
    
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Wait for content to load before printing
    printWindow.onload = function() {
      printWindow.print();
      // Don't close the window immediately to allow the print dialog to complete
      setTimeout(function() {
        printWindow.close();
      }, 300);
    };
  }
  
  // Helper function to extract table data for PDF
  function getTableData(table) {
    var data = [];
    
    // Extract header row
    var headerRow = [];
    table.find('thead th').each(function() {
      headerRow.push($(this).text().trim() || '');  // Use empty string if text is undefined
    });
    data.push(headerRow);
    
    // Extract body rows
    table.find('tbody tr').each(function() {
      var row = [];
      $(this).find('td').each(function() {
        row.push($(this).text().trim() || '');  // Use empty string if text is undefined
      });
      // Only add rows that have at least one non-empty cell
      if (row.some(cell => cell !== '')) {
        data.push(row);
      }
    });
    
    // Add footer row if exists
    table.find('tfoot tr').each(function() {
      var row = [];
      $(this).find('td').each(function() {
        row.push($(this).text().trim() || '');  // Use empty string if text is undefined
      });
      if (row.length > 0) {
        data.push(row);
      }
    });
    
    return data;
  }

  // Function to export combined tables to Excel
  function exportCombinedToExcel() {
    // Get tables
    var studentsTable = $('#myTable');
    var agingTable = $('#aging-table');
    
    // Check if both tables exist and have rows
    if (studentsTable.length === 0 || agingTable.length === 0) {
      alert('Tables not found or data not loaded yet');
      return;
    }
    
    // Check if there's data in the tables
    if (studentsTable.find('tbody tr').length === 0 || agingTable.find('tbody tr').length === 0) {
      alert('No data available for export');
      return;
    }
    
    try {
      // Get date range
      var fromDate = $('#from').val() || 'N/A';
      var toDate = $('#to').val() || 'N/A';
      var dateRangeText = 'Date Range: ' + fromDate + ' to ' + toDate;
      var title = 'Student Report R';
      
      // Create a new workbook
      var wb = XLSX.utils.book_new();
      
      // Create first sheet - Students Report
      var studentsData = [
        [title],
        [dateRangeText],
        []  // Empty row for spacing
      ];
      
      // Get header row
      var headerRow = [];
      studentsTable.find('thead th').each(function() {
        headerRow.push($(this).text().trim() || '');
      });
      studentsData.push(headerRow);
      
      // Get data rows
      studentsTable.find('tbody tr').each(function() {
        var row = [];
        $(this).find('td').each(function() {
          row.push($(this).text().trim() || '');
        });
        studentsData.push(row);
      });
      
      // Add footer row
      studentsTable.find('tfoot tr').each(function() {
        var row = [];
        $(this).find('td').each(function() {
          row.push($(this).text().trim() || '');
        });
        if (row.length > 0) {
          studentsData.push(row);
        }
      });
      
      var studentsWs = XLSX.utils.aoa_to_sheet(studentsData);
      XLSX.utils.book_append_sheet(wb, studentsWs, 'Students Report');
      
      // Create second sheet - Aging Report
      var agingData = [
        ['Student Aging Report'],
        [dateRangeText],
        []  // Empty row for spacing
      ];
      
      // Get header row
      var agingHeaderRow = [];
      agingTable.find('thead th').each(function() {
        agingHeaderRow.push($(this).text().trim() || '');
      });
      agingData.push(agingHeaderRow);
      
      // Get data rows
      agingTable.find('tbody tr').each(function() {
        var row = [];
        $(this).find('td').each(function() {
          row.push($(this).text().trim() || '');
        });
        agingData.push(row);
      });
      
      // Add footer row
      agingTable.find('tfoot tr').each(function() {
        var row = [];
        $(this).find('td').each(function() {
          row.push($(this).text().trim() || '');
        });
        if (row.length > 0) {
          agingData.push(row);
        }
      });
      
      var agingWs = XLSX.utils.aoa_to_sheet(agingData);
      XLSX.utils.book_append_sheet(wb, agingWs, 'Aging Report');
      
      // Generate and download Excel file
      XLSX.writeFile(wb, 'Student_Report_R_Combined.xlsx');
    } catch (error) {
      console.error('Excel generation error:', error);
      alert('Error generating Excel file: ' + error.message);
    }
  }
  
  // Function to export combined tables to PDF
  function exportCombinedToPdf() {
    try {
      // Get tables
      var studentsTable = $('#myTable');
      var agingTable = $('#aging-table');
      
      if (studentsTable.length === 0 || agingTable.length === 0) {
        alert('Tables not found or data not loaded yet');
        return;
      }
      
      // Get date range
      var fromDate = $('#from').val() || 'N/A';
      var toDate = $('#to').val() || 'N/A';
      var dateRangeText = 'Date Range: ' + fromDate + ' to ' + toDate;
      
      // Create a simplified document structure with minimal table formatting
      var docDefinition = {
        pageOrientation: 'landscape',
        content: [
          { text: 'Student Report R', style: 'header' },
          { text: dateRangeText, style: 'subheader' },
          { text: '\n' },
          { text: 'Students Information', style: 'tableHeader' },
          createSimplifiedTableForPdf(studentsTable),
          { text: '\n\n' },
          { text: 'Student Aging Report', style: 'tableHeader' },
          createSimplifiedTableForPdf(agingTable)
        ],
        styles: {
          header: {
            fontSize: 18,
            bold: true,
            margin: [0, 0, 0, 8]
          },
          subheader: {
            fontSize: 12,
            italics: true,
            margin: [0, 0, 0, 16]
          },
          tableHeader: {
            fontSize: 14,
            bold: true,
            margin: [0, 10, 0, 5]
          }
        },
        defaultStyle: {
          fontSize: 10
        }
      };
      
      // Generate PDF and download
      pdfMake.createPdf(docDefinition).download('Student_Report_R_Combined.pdf');
    } catch (error) {
      console.error('PDF generation error:', error);
      alert('Error generating PDF: ' + error.message);
    }
  }
  
  // Helper function to create a simplified table for PDF with strict validation
  function createSimplifiedTableForPdf(table) {
    // Safely get table data with extensive validation
    var headers = [];
    var numColumns = 0;
    
    // Get headers and count columns
    table.find('thead th').each(function() {
      var headerText = $(this).text().trim() || ' ';
      headers.push(headerText);
      numColumns++;
    });
    
    // If no columns found, use a default
    if (numColumns === 0) {
      numColumns = 1;
      headers = [' '];
    }
    
    // Get rows with validation
    var rows = [];
    table.find('tbody tr').each(function() {
      var rowData = [];
      
      // Fill each cell in the row
      for (var i = 0; i < numColumns; i++) {
        var cell = $(this).find('td').eq(i);
        if (cell.length > 0) {
          rowData.push(cell.text().trim() || ' ');
        } else {
          // Add empty cell if missing
          rowData.push(' ');
        }
      }
      
      // Only add rows that have at least one non-empty cell
      if (rowData.some(function(cell) { return cell.trim() !== ''; })) {
        rows.push(rowData);
      }
    });
    
    // Handle empty rows case
    if (rows.length === 0) {
      rows = [Array(numColumns).fill('No data available')];
    }
    
    // Add footer rows with validation
    table.find('tfoot tr').each(function() {
      var footerRow = [];
      
      // Process each cell in the footer row
      for (var i = 0; i < numColumns; i++) {
        var cell = $(this).find('td').eq(i);
        if (cell.length > 0) {
          footerRow.push(cell.text().trim() || ' ');
        } else {
          // Add empty cell if missing
          footerRow.push(' ');
        }
      }
      
      // Only add footer row if it contains data
      if (footerRow.length > 0) {
        rows.push(footerRow);
      }
    });
    
    // Create the table definition with auto-widths and ensured consistency
    return {
      table: {
        headerRows: 1,
        body: [headers].concat(rows),
        widths: Array(numColumns).fill('*')
      },
      layout: {
        fillColor: function(rowIndex) {
          return (rowIndex === 0) ? '#f2f2f2' : null;
        }
      }
    };
  }
  
  // Function to print combined tables
  function printCombinedTables() {
    // Get tables
    var studentsTable = $('#myTable');
    var agingTable = $('#aging-table');
    
    // Check if both tables exist and have rows
    if (studentsTable.length === 0 || agingTable.length === 0) {
      alert('Tables not found or data not loaded yet');
      return;
    }
    
    // Check if there's data in the tables
    if (studentsTable.find('tbody tr').length === 0 || agingTable.find('tbody tr').length === 0) {
      alert('No data available for export');
      return;
    }
    
    // Get date range
    var fromDate = $('#from').val() || 'N/A';
    var toDate = $('#to').val() || 'N/A';
    var dateRangeText = 'Date Range: ' + fromDate + ' to ' + toDate;
    
    // Create a new window for printing
    var printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>Student Report R</title>');
    
    // Add necessary styles with improved print styling
    printWindow.document.write('<style>');
    printWindow.document.write('@media print {');
    printWindow.document.write('  @page { size: landscape; margin: 10mm; }');
    printWindow.document.write('}');
    printWindow.document.write('body { font-family: Arial, sans-serif; }');
    printWindow.document.write('h1 { text-align: center; margin-bottom: 8px; }');
    printWindow.document.write('h2 { text-align: center; margin-top: 20px; margin-bottom: 8px; }');
    printWindow.document.write('.date-range { text-align: center; font-style: italic; margin-bottom: 20px; }');
    printWindow.document.write('table { border-collapse: collapse; width: 100%; table-layout: auto; font-size: 12px; margin-bottom: 30px; }');
    printWindow.document.write('th, td { border: 1px solid #ddd; padding: 6px; text-align: left; overflow: visible; white-space: normal; }');
    printWindow.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    
    // Add main title and date range
    printWindow.document.write('<h1>Student Report R</h1>');
    printWindow.document.write('<div class="date-range">' + dateRangeText + '</div>');
    
    // Clone and add students table
    var studentsClone = studentsTable.clone();
    studentsClone.find('th, td').css('width', 'auto');
    printWindow.document.write('<h2>Students Information</h2>');
    printWindow.document.write(studentsClone[0].outerHTML);
    
    // Clone and add aging table
    var agingClone = agingTable.clone();
    agingClone.find('th, td').css('width', 'auto');
    printWindow.document.write('<h2>Student Aging Report</h2>');
    printWindow.document.write(agingClone[0].outerHTML);
    
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Wait for content to load before printing
    printWindow.onload = function() {
      printWindow.print();
      // Don't close the window immediately to allow the print dialog to complete
      setTimeout(function() {
        printWindow.close();
      }, 300);
    };
  }
  </script>
@endsection
