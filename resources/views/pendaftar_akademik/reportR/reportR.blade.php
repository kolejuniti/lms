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
          <div class="row">
            <div class="col-md-3 mb-1">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="convert" value="convert" checked>
                <label class="form-check-label" for="convert">Include Student Convert</label>
              </div>
            </div>
            <div class="col-md-3 mb-1">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="offered" value="offered" checked>
                <label class="form-check-label" for="offered">Include Student Offered (Tawaran)</label>
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
    var convert = true; // Initialize as true since checkbox is checked by default
    var dataTable = null;
    var offered = true;

    // Document ready function
    $(function() {
      console.log("Document ready - initializing event handlers");
    });

    $(document).on('change', '#from', function(e){
      from = $(e.target).val();
      updateCombinedExportButtons();
      getStudent(from,to,session,EA,convert.toString(),offered.toString());
    });

    $(document).on('change', '#to', function(e){
      to = $(e.target).val();
      updateCombinedExportButtons();
      getStudent(from,to,session,EA,convert.toString(),offered.toString());
    });

    $(document).on('change', '#session', function(e){
      session = $(e.target).val();
      getStudent(from,to,session,EA,convert.toString(),offered.toString());
    });

    $(document).on('change', '#EA', function(e){
      EA = $(e.target).val();
      getStudent(from,to,session,EA,convert.toString(),offered.toString());
    });

    $(document).on('change', '#convert', function(e){
      convert = $(e.target).prop('checked');
      // Convert to string "true" or "false" to match PHP's expected format
      getStudent(from,to,session,EA,convert.toString(),offered.toString());
    });

    $(document).on('change', '#offered', function(e){
      offered = $(e.target).prop('checked');
      // Convert to string "true" or "false" to match PHP's expected format
      getStudent(from,to,session,EA,convert.toString(),offered.toString());
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

  function getStudent(from,to,session,EA,convert,offered)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('/AR/reportR/getStudentReportR') }}",
            method   : 'GET',
            data 	 : {from: from, to: to, session: session, EA: EA, convert: convert, offered: offered},
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
      getStudent(from,to,session,EA,convert.toString(),offered.toString());
    });

    // Handle Select2 clear event
    $('#EA').on('select2:clear', function (e) {
      EA = '-';
      getStudent(from,to,session,EA,convert.toString(),offered.toString());
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
    
    // Check if it's a DataTable
    var isDataTable = $.fn.DataTable.isDataTable('#' + tableId);
    var ws;
    
    if (isDataTable) {
      // Get all data from DataTable API (not just visible page)
      var dt = $('#' + tableId).DataTable();
      
      // Get headers
      var headers = [];
      table.find('thead th').each(function() {
        headers.push($(this).text().trim() || '');
      });
      
      // Get all rows from DataTable API (across all pages)
      var allData = dt.rows().data().toArray();
      
      // Convert to array format suitable for XLSX
      var xlsxData = [headers];
      allData.forEach(function(row) {
        var rowData = [];
        for (var i = 0; i < row.length; i++) {
          // Handle HTML content by extracting text
          var cellText = row[i];
          if (typeof cellText === 'string' && cellText.includes('<')) {
            var temp = document.createElement('div');
            temp.innerHTML = cellText;
            cellText = temp.textContent || temp.innerText || '';
          }
          rowData.push(cellText);
        }
        xlsxData.push(rowData);
      });
      
      // Get footer if exists
      var footer = [];
      table.find('tfoot tr td').each(function() {
        footer.push($(this).text().trim() || '');
      });
      if (footer.length > 0) {
        xlsxData.push(footer);
      }
      
      // Add title and date range at the beginning
      xlsxData.unshift([''], [dateRangeText], [title]);
      
      ws = XLSX.utils.aoa_to_sheet(xlsxData);
    } else {
      // Fallback to get from DOM for non-DataTable
      ws = XLSX.utils.table_to_sheet(table[0]);
      
      // Add title and date range at the top
      var ref = XLSX.utils.decode_range(ws['!ref']);
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
      XLSX.utils.sheet_add_aoa(ws, [['']], { origin: { r: 2, c: 0 } });
    }
    
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
    
    // Get table data - handle DataTable if available
    var tableData = [];
    if ($.fn.DataTable.isDataTable('#' + tableId)) {
      var dt = $('#' + tableId).DataTable();
      
      // Get headers
      var headerRow = [];
      table.find('thead th').each(function() {
        headerRow.push($(this).text().trim() || '');
      });
      tableData.push(headerRow);
      
      // Get all rows from DataTable API (across all pages)
      var allData = dt.rows().data().toArray();
      allData.forEach(function(row) {
        var rowData = [];
        for (var i = 0; i < row.length; i++) {
          // Handle HTML content by extracting text
          var cellText = row[i];
          if (typeof cellText === 'string' && cellText.includes('<')) {
            var temp = document.createElement('div');
            temp.innerHTML = cellText;
            cellText = temp.textContent || temp.innerText || '';
          }
          rowData.push(cellText);
        }
        tableData.push(rowData);
      });
      
      // Get footer if exists
      table.find('tfoot tr').each(function() {
        var row = [];
        $(this).find('td').each(function() {
          row.push($(this).text().trim() || '');
        });
        if (row.length > 0) {
          tableData.push(row);
        }
      });
    } else {
      // Fallback to using DOM for non-DataTable
      tableData = getTableData(table);
    }
    
    // Create document definition
    var docDefinition = {
      content: [
        { text: title, style: 'header' },
        { text: dateRangeText, style: 'subheader' },
        { text: '\n' },
        {
          table: {
            headerRows: 1,
            body: tableData
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
    
    // Get table data with all pages if it's a DataTable
    if ($.fn.DataTable.isDataTable('#' + tableId)) {
      var dt = $('#' + tableId).DataTable();
      
      // Create new table for printing with all data
      printWindow.document.write('<table>');
      
      // Add header
      printWindow.document.write('<thead><tr>');
      table.find('thead th').each(function() {
        printWindow.document.write('<th>' + $(this).text().trim() + '</th>');
      });
      printWindow.document.write('</tr></thead>');
      
      // Add body with all rows from DataTable API
      printWindow.document.write('<tbody>');
      var allData = dt.rows().data().toArray();
      allData.forEach(function(row) {
        printWindow.document.write('<tr>');
        for (var i = 0; i < row.length; i++) {
          // Handle HTML content by extracting text
          var cellText = row[i];
          if (typeof cellText === 'string' && cellText.includes('<')) {
            var temp = document.createElement('div');
            temp.innerHTML = cellText;
            cellText = temp.textContent || temp.innerText || '';
          }
          printWindow.document.write('<td>' + cellText + '</td>');
        }
        printWindow.document.write('</tr>');
      });
      printWindow.document.write('</tbody>');
      
      // Add footer if exists
      if (table.find('tfoot').length > 0) {
        printWindow.document.write('<tfoot>');
        table.find('tfoot tr').each(function() {
          printWindow.document.write('<tr>');
          $(this).find('td').each(function() {
            var colspan = $(this).attr('colspan') || 1;
            printWindow.document.write('<td colspan="' + colspan + '">' + $(this).text().trim() + '</td>');
          });
          printWindow.document.write('</tr>');
        });
        printWindow.document.write('</tfoot>');
      }
      
      printWindow.document.write('</table>');
    } else {
      // Fallback to clone for non-DataTable
      var clonedTable = table.clone();
      clonedTable.find('th, td').css('width', 'auto');
      printWindow.document.write(clonedTable[0].outerHTML);
    }
    
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
      headerRow.push($(this).text().trim() || '');
    });
    data.push(headerRow);
    
    // Extract body rows
    table.find('tbody tr').each(function() {
      var row = [];
      $(this).find('td').each(function() {
        row.push($(this).text().trim() || '');
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
        row.push($(this).text().trim() || '');
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
    
    // Check if both tables exist
    if (studentsTable.length === 0 || agingTable.length === 0) {
      alert('Tables not found or data not loaded yet');
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
      
      // Check if it's a DataTable and get all data
      if ($.fn.DataTable.isDataTable('#myTable')) {
        var dt = $('#myTable').DataTable();
        var allData = dt.rows().data().toArray();
        
        allData.forEach(function(row) {
          var rowData = [];
          for (var i = 0; i < row.length; i++) {
            // Handle HTML content by extracting text
            var cellText = row[i];
            if (typeof cellText === 'string' && cellText.includes('<')) {
              var temp = document.createElement('div');
              temp.innerHTML = cellText;
              cellText = temp.textContent || temp.innerText || '';
            }
            rowData.push(cellText);
          }
          studentsData.push(rowData);
        });
      } else {
        // Fallback to using DOM for non-DataTable
        studentsTable.find('tbody tr').each(function() {
          var row = [];
          $(this).find('td').each(function() {
            row.push($(this).text().trim() || '');
          });
          studentsData.push(row);
        });
      }
      
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
      
      // Check if it's a DataTable and get all data
      if ($.fn.DataTable.isDataTable('#aging-table')) {
        var agingDt = $('#aging-table').DataTable();
        var agingAllData = agingDt.rows().data().toArray();
        
        agingAllData.forEach(function(row) {
          var rowData = [];
          for (var i = 0; i < row.length; i++) {
            // Handle HTML content by extracting text
            var cellText = row[i];
            if (typeof cellText === 'string' && cellText.includes('<')) {
              var temp = document.createElement('div');
              temp.innerHTML = cellText;
              cellText = temp.textContent || temp.innerText || '';
            }
            rowData.push(cellText);
          }
          agingData.push(rowData);
        });
      } else {
        // Fallback to using DOM for non-DataTable
        agingTable.find('tbody tr').each(function() {
          var row = [];
          $(this).find('td').each(function() {
            row.push($(this).text().trim() || '');
          });
          agingData.push(row);
        });
      }
      
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
      
      // Get students table data for PDF using DataTable API if available
      var studentsTableData = [];
      if ($.fn.DataTable.isDataTable('#myTable')) {
        var dt = $('#myTable').DataTable();
        
        // Get headers
        var headerRow = [];
        var headerCount = 0;
        studentsTable.find('thead th').each(function() {
          headerRow.push($(this).text().trim() || '');
          headerCount++;
        });
        studentsTableData.push(headerRow);
        
        // Get all rows from DataTable API
        var allData = dt.rows().data().toArray();
        allData.forEach(function(row) {
          var rowData = [];
          // Ensure each row has the same number of cells as the header
          for (var i = 0; i < headerCount; i++) {
            // Handle HTML content by extracting text
            var cellText = '';
            if (i < row.length) {
              cellText = row[i];
              if (typeof cellText === 'string' && cellText.includes('<')) {
                var temp = document.createElement('div');
                temp.innerHTML = cellText;
                cellText = temp.textContent || temp.innerText || '';
              }
            }
            // Make sure the cell has a valid value
            rowData.push(cellText || '');
          }
          studentsTableData.push(rowData);
        });
        
        // Get footer if exists
        studentsTable.find('tfoot tr').each(function() {
          var row = [];
          // Ensure the footer row has the same number of cells as the header
          for (var i = 0; i < headerCount; i++) {
            var cell = $(this).find('td').eq(i);
            row.push(cell.length > 0 ? (cell.text().trim() || '') : '');
          }
          if (row.length > 0) {
            studentsTableData.push(row);
          }
        });
      } else {
        // Fallback to DOM
        studentsTableData = getTableDataSafe(studentsTable);
      }
      
      // Get aging table data for PDF
      var agingTableData = [];
      if ($.fn.DataTable.isDataTable('#aging-table')) {
        var agingDt = $('#aging-table').DataTable();
        
        // Get headers
        var agingHeaderRow = [];
        var agingHeaderCount = 0;
        agingTable.find('thead th').each(function() {
          agingHeaderRow.push($(this).text().trim() || '');
          agingHeaderCount++;
        });
        agingTableData.push(agingHeaderRow);
        
        // Get all rows from DataTable API
        var agingAllData = agingDt.rows().data().toArray();
        agingAllData.forEach(function(row) {
          var rowData = [];
          // Ensure each row has the same number of cells as the header
          for (var i = 0; i < agingHeaderCount; i++) {
            // Handle HTML content by extracting text
            var cellText = '';
            if (i < row.length) {
              cellText = row[i];
              if (typeof cellText === 'string' && cellText.includes('<')) {
                var temp = document.createElement('div');
                temp.innerHTML = cellText;
                cellText = temp.textContent || temp.innerText || '';
              }
            }
            // Make sure the cell has a valid value
            rowData.push(cellText || '');
          }
          agingTableData.push(rowData);
        });
        
        // Get footer if exists
        agingTable.find('tfoot tr').each(function() {
          var row = [];
          // Ensure the footer row has the same number of cells as the header
          for (var i = 0; i < agingHeaderCount; i++) {
            var cell = $(this).find('td').eq(i);
            row.push(cell.length > 0 ? (cell.text().trim() || '') : '');
          }
          if (row.length > 0) {
            agingTableData.push(row);
          }
        });
      } else {
        // Fallback to DOM
        agingTableData = getTableDataSafe(agingTable);
      }
      
      // Verify that all tables have data and consistent structure
      if (studentsTableData.length < 1 || agingTableData.length < 1) {
        alert('No data available for export');
        return;
      }
      
      // Ensure all rows have consistent cell count
      var studentsColCount = studentsTableData[0].length;
      var agingColCount = agingTableData[0].length;
      
      // Create a simplified document structure
      var docDefinition = {
        pageOrientation: 'landscape',
        content: [
          { text: 'Student Report R', style: 'header' },
          { text: dateRangeText, style: 'subheader' },
          { text: '\n' },
          { text: 'Students Information', style: 'tableHeader' },
          {
            table: {
              headerRows: 1,
              body: studentsTableData,
              widths: Array(studentsColCount).fill('*')
            },
            layout: {
              fillColor: function(rowIndex) {
                return (rowIndex === 0) ? '#f2f2f2' : null;
              }
            }
          },
          { text: '\n\n' },
          { text: 'Student Aging Report', style: 'tableHeader' },
          {
            table: {
              headerRows: 1,
              body: agingTableData,
              widths: Array(agingColCount).fill('*')
            },
            layout: {
              fillColor: function(rowIndex) {
                return (rowIndex === 0) ? '#f2f2f2' : null;
              }
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
      alert('Error generating PDF: ' + (error.message || 'Unknown error'));
    }
  }
  
  // Helper function to safely extract table data with consistent structure
  function getTableDataSafe(table) {
    var data = [];
    
    // Extract header row
    var headerRow = [];
    var colCount = 0;
    table.find('thead th').each(function() {
      headerRow.push($(this).text().trim() || '');
      colCount++;
    });
    data.push(headerRow);
    
    // Extract body rows
    table.find('tbody tr').each(function() {
      var row = [];
      // Ensure each row has the same number of cells
      for (var i = 0; i < colCount; i++) {
        var cell = $(this).find('td').eq(i);
        var cellText = cell.length > 0 ? (cell.text().trim() || '') : '';
        row.push(cellText);
      }
      // Only add rows that have at least one non-empty cell
      if (row.some(function(cell) { return cell !== ''; })) {
        data.push(row);
      }
    });
    
    // Add footer row if exists
    table.find('tfoot tr').each(function() {
      var row = [];
      // Ensure each row has the same number of cells
      for (var i = 0; i < colCount; i++) {
        var cell = $(this).find('td').eq(i);
        var cellText = cell.length > 0 ? (cell.text().trim() || '') : '';
        row.push(cellText);
      }
      if (row.length > 0) {
        data.push(row);
      }
    });
    
    return data;
  }
  
  // Function to print combined tables
  function printCombinedTables() {
    // Get tables
    var studentsTable = $('#myTable');
    var agingTable = $('#aging-table');
    
    // Check if both tables exist
    if (studentsTable.length === 0 || agingTable.length === 0) {
      alert('Tables not found or data not loaded yet');
      return;
    }
    
    // Get date range
    var fromDate = $('#from').val() || 'N/A';
    var toDate = $('#to').val() || 'N/A';
    var dateRangeText = 'Date Range: ' + fromDate + ' to ' + toDate;
    
    // Create a new window for printing
    var printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>Student Report R</title>');
    
    // Add necessary styles
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
    
    // Add students table with all data if it's a DataTable
    printWindow.document.write('<h2>Students Information</h2>');
    if ($.fn.DataTable.isDataTable('#myTable')) {
      var dt = $('#myTable').DataTable();
      
      // Create new table for printing with all data
      printWindow.document.write('<table>');
      
      // Add header
      printWindow.document.write('<thead><tr>');
      studentsTable.find('thead th').each(function() {
        printWindow.document.write('<th>' + $(this).text().trim() + '</th>');
      });
      printWindow.document.write('</tr></thead>');
      
      // Add body with all rows from DataTable API
      printWindow.document.write('<tbody>');
      var allData = dt.rows().data().toArray();
      allData.forEach(function(row) {
        printWindow.document.write('<tr>');
        for (var i = 0; i < row.length; i++) {
          // Handle HTML content by extracting text
          var cellText = row[i];
          if (typeof cellText === 'string' && cellText.includes('<')) {
            var temp = document.createElement('div');
            temp.innerHTML = cellText;
            cellText = temp.textContent || temp.innerText || '';
          }
          printWindow.document.write('<td>' + cellText + '</td>');
        }
        printWindow.document.write('</tr>');
      });
      printWindow.document.write('</tbody>');
      
      // Add footer if exists
      if (studentsTable.find('tfoot').length > 0) {
        printWindow.document.write('<tfoot>');
        studentsTable.find('tfoot tr').each(function() {
          printWindow.document.write('<tr>');
          $(this).find('td').each(function() {
            var colspan = $(this).attr('colspan') || 1;
            printWindow.document.write('<td colspan="' + colspan + '">' + $(this).text().trim() + '</td>');
          });
          printWindow.document.write('</tr>');
        });
        printWindow.document.write('</tfoot>');
      }
      
      printWindow.document.write('</table>');
    } else {
      // Fallback to clone for non-DataTable
      var studentsClone = studentsTable.clone();
      studentsClone.find('th, td').css('width', 'auto');
      printWindow.document.write(studentsClone[0].outerHTML);
    }
    
    // Add aging table with all data if it's a DataTable
    printWindow.document.write('<h2>Student Aging Report</h2>');
    if ($.fn.DataTable.isDataTable('#aging-table')) {
      var agingDt = $('#aging-table').DataTable();
      
      // Create new table for printing with all data
      printWindow.document.write('<table>');
      
      // Add header
      printWindow.document.write('<thead><tr>');
      agingTable.find('thead th').each(function() {
        printWindow.document.write('<th>' + $(this).text().trim() + '</th>');
      });
      printWindow.document.write('</tr></thead>');
      
      // Add body with all rows from DataTable API
      printWindow.document.write('<tbody>');
      var agingAllData = agingDt.rows().data().toArray();
      agingAllData.forEach(function(row) {
        printWindow.document.write('<tr>');
        for (var i = 0; i < row.length; i++) {
          // Handle HTML content by extracting text
          var cellText = row[i];
          if (typeof cellText === 'string' && cellText.includes('<')) {
            var temp = document.createElement('div');
            temp.innerHTML = cellText;
            cellText = temp.textContent || temp.innerText || '';
          }
          printWindow.document.write('<td>' + cellText + '</td>');
        }
        printWindow.document.write('</tr>');
      });
      printWindow.document.write('</tbody>');
      
      // Add footer if exists
      if (agingTable.find('tfoot').length > 0) {
        printWindow.document.write('<tfoot>');
        agingTable.find('tfoot tr').each(function() {
          printWindow.document.write('<tr>');
          $(this).find('td').each(function() {
            var colspan = $(this).attr('colspan') || 1;
            printWindow.document.write('<td colspan="' + colspan + '">' + $(this).text().trim() + '</td>');
          });
          printWindow.document.write('</tr>');
        });
        printWindow.document.write('</tfoot>');
      }
      
      printWindow.document.write('</table>');
    } else {
      // Fallback to clone for non-DataTable
      var agingClone = agingTable.clone();
      agingClone.find('th, td').css('width', 'auto');
      printWindow.document.write(agingClone[0].outerHTML);
    }
    
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

  // Aging Report Modal Functionality
  $(document).ready(function() {
    // Handle clicks on clickable counts in aging report
    $(document).on('click', '.clickable-count', function() {
      const category = $(this).data('category');
      const statusType = $(this).data('status');
      const count = $(this).text().trim();
      
      // Only proceed if count is greater than 0
      if (parseInt(count) === 0) {
        return;
      }
      
      // Get current filter values
      const from = $('#from').val();
      const to = $('#to').val();
      const session = $('#session').val();
      const EA = $('#EA').val();
      const convert = $('#convert').is(':checked');
      const offered = $('#offered').is(':checked');
      
      // Validate required fields
      if (!from || !to) {
        alert('Please select date range first');
        return;
      }
      
      // Show modal
      $('#studentListModal').modal('show');
      
      // Reset modal content
      $('#modalLoadingSpinner').show();
      $('#modalErrorMessage').hide();
      $('#studentListContainer').hide();
      
      // Set modal title
      let categoryTitle = '';
      let statusTitle = '';
      
      switch(category) {
        case 'below5': categoryTitle = 'Less than 5 days'; break;
        case 'below10': categoryTitle = '5-9 days'; break;
        case 'below15': categoryTitle = '10-14 days'; break;
        case 'below20': categoryTitle = '15-19 days'; break;
        case 'below25': categoryTitle = '20-24 days'; break;
        case 'below30': categoryTitle = '25-29 days'; break;
        case 'above30': categoryTitle = '30+ days'; break;
        case 'total': categoryTitle = 'All Categories'; break;
      }
      
      switch(statusType) {
        case 'total': statusTitle = 'All Students'; break;
        case 'willregister': statusTitle = 'Will Register'; break;
        case 'kiv': statusTitle = 'KIV Students'; break;
        case 'convert': statusTitle = 'Convert Students'; break;
        case 'active': statusTitle = 'Active Students'; break;
        case 'rejected': statusTitle = 'Rejected Students'; break;
        case 'others': statusTitle = 'Other Status'; break;
      }
      
      $('#modalCategoryTitle').text(categoryTitle + ' - ' + statusTitle);
      $('#modalStudentCount').text('Total: ' + count + ' students');
      
      // Prepare request data
      const requestData = {
        from: from,
        to: to,
        category: category,
        status_type: statusType,
        _token: $('meta[name="csrf-token"]').attr('content')
      };
      
      // Add optional filters
      if (session && session !== '-') {
        requestData.session = session;
      }
      if (EA && EA !== '-') {
        requestData.EA = EA;
      }
      if (!convert) {
        requestData.convert = 'false';
      }
      if (!offered) {
        requestData.offered = 'false';
      }
      
      // Make AJAX request
      $.ajax({
        url: '/AR/reportR/getAgingStudents',
        method: 'POST',
        data: requestData,
        success: function(response) {
          $('#modalLoadingSpinner').hide();
          
          if (response.success && response.students) {
            populateStudentTable(response.students, response.qualifications);
            $('#studentListContainer').show();
            $('#exportModalStudents').show();
          } else {
            showError('No students found for the selected criteria.');
            $('#exportModalStudents').hide();
          }
        },
        error: function(xhr) {
          $('#modalLoadingSpinner').hide();
          let errorMessage = 'An error occurred while loading student data.';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          }
          
          showError(errorMessage);
        }
      });
    });
    
    function populateStudentTable(students, qualifications) {
      const tbody = $('#modalStudentTableBody');
      tbody.empty();
      
      if (students.length === 0) {
        tbody.append('<tr><td colspan="8" class="text-center">No students found</td></tr>');
        return;
      }
      
      students.forEach((student, index) => {
        const qualification = qualifications[student.ic] || 'N/A';
        const phone = student.no_tel || 'N/A';
        const gender = student.sex || 'N/A';
        const program = student.progcode || 'N/A';
        const session = student.SessionName || 'N/A';
        
        const row = `
          <tr>
            <td>${index + 1}</td>
            <td>${student.name}</td>
            <td>${student.ic}</td>
            <td>${student.no_matric || 'N/A'}</td>
            <td>${phone}</td>
            <td>${gender}</td>
            <td>${program}</td>
            <td>${session}</td>
          </tr>
        `;
        tbody.append(row);
      });
    }
    
    function showError(message) {
      $('#modalErrorMessage').text(message).show();
      $('#studentListContainer').hide();
    }
    
    // Export functionality for modal
    $('#exportModalStudents').click(function() {
      const students = [];
      $('#modalStudentTable tbody tr').each(function() {
        const cells = $(this).find('td');
        if (cells.length > 1) { // Skip "no students found" row
          const student = [];
          cells.each(function() {
            student.push($(this).text().trim());
          });
          students.push(student);
        }
      });
      
      if (students.length === 0) {
        alert('No data to export');
        return;
      }
      
      // Create Excel export
      const headers = ['No.', 'Name', 'IC No.', 'Matric No.', 'Phone', 'Gender', 'Program', 'Session'];
      const data = [headers, ...students];
      
      const ws = XLSX.utils.aoa_to_sheet(data);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Student List');
      
      const fileName = 'Aging_Report_Students_' + new Date().toISOString().split('T')[0] + '.xlsx';
      XLSX.writeFile(wb, fileName);
    });
  });
  </script>
@endsection
