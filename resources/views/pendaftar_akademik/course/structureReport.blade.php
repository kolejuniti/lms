@extends('layouts.pendaftar_akademik')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Structure Report</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item" aria-current="page">Subject</li>
                <li class="breadcrumb-item active" aria-current="page">Structure Report</li>
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
          <h3 class="card-title">Structure Report</h3>
        </div>
        <div class="card-body">
          <div class="row mb-3 d-flex">
            <div class="col-md-12 mb-3">
              <div class="col-md-4 ml-3">
                <div class="form-group">
                    <label class="form-label" for="program">Program</label>
                    <select class="form-select" id="program" name="program">
                    <option value="-" selected disabled>-</option>
                      @foreach ($data['program'] as $prg)
                      <option value="{{ $prg->id }}">{{ $prg->progname }} ({{ $prg->progcode }})</option> 
                      @endforeach
                    </select>
                </div>
              </div>
              <div class="col-md-4 ml-3">
                <div class="form-group">
                    <label class="form-label" for="structure">Structure</label>
                    <select class="form-select" id="structure" name="structure">
                    <option value="-" selected disabled>-</option>
                    </select>
                </div>
              </div>
            </div>
        </div>
        </div>
        
        <!-- Export Buttons -->
        <div class="card-body pb-0" id="exportButtons" style="display: none;">
          <div class="row mb-3">
            <div class="col-md-12">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-success" onclick="exportToExcel()">
                  <i class="fa fa-file-excel"></i> Export to Excel
                </button>
                <button type="button" class="btn btn-danger" onclick="exportToPDF()">
                  <i class="fa fa-file-pdf"></i> Export to PDF
                </button>
                <button type="button" class="btn btn-info" onclick="printTable()">
                  <i class="fa fa-print"></i> Print
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="card-body p-0">
          <table id="myTable" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th style="width: 1%">
                        No.
                    </th>
                    <th style="width: 5%">
                        Semester
                    </th>
                    <th style="width: 5%">
                        Course Code
                    </th>
                    <th style="width: 20%">
                        Course Name
                    </th>
                    <th style="width: 5%">
                        Structure
                    </th>
                    <th style="width: 10%">
                        Credit
                    </th>
                    <th style="width: 10%">
                        Classification
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
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

<!-- Additional Export Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<!-- Page specific script -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script>
     $(document).ready( function () {
        // Remove initial DataTable initialization to prevent conflicts
        // DataTable will be initialized dynamically when data is loaded
    } );
  </script>

  <script type="text/javascript">
    var selected_program = "";
    var selected_structure = "";

    var url = window.location.href;

    $(document).on('change', '#program', function(e){
      selected_program = $(e.target).val();

       getCourse0(selected_program);

    })

    function getCourse0(program)
    {
        return $.ajax({
                headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                url      : "{{ url('AR/structureReport/getStructure') }}",
                method   : 'POST',
                data 	 : {program: program},
                error:function(err){
                    alert("Error");
                    console.log(err);
                },
                success  : function(response){
                    // Clear existing options
                    $('#structure').empty();
                    
                    // Add default option
                    $('#structure').append('<option value="-" selected disabled>-</option>');
                    
                    // Add new options from response data
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(item) {
                            $('#structure').append(`<option value="${item.id}">${item.structure_name}</option>`);
                        });
                    }
                }
            });
    }

    $(document).on('change', '#structure', async function(e){
      selected_structure = $(e.target).val();
      
      // Get fresh values from dropdowns to ensure accuracy
      var currentProgram = $('#program').val();
      var currentStructure = $('#structure').val();
      
      console.log('Structure changed. Program:', currentProgram, 'Structure:', currentStructure);
      console.log('Variables - selected_program:', selected_program, 'selected_structure:', selected_structure);

      await getCourse(currentProgram, currentStructure);

    })


  function getCourse(program,structure)
  {
      console.log('=== getCourse START ===');
      console.log('Parameters received - program:', program, 'structure:', structure);
      
      // Check if DataTable exists and destroy it properly
      if ($.fn.DataTable.isDataTable('#myTable')) {
          console.log('Destroying existing DataTable');
          $('#myTable').DataTable().clear().destroy();
      }
      
      // Hide export buttons and clear table content completely
      $('#exportButtons').hide();
      $('#myTable').empty().html('');
      
      // Reset table to basic structure
      $('#myTable').html(`
        <thead>
            <tr>
                <th style="width: 1%">No.</th>
                <th style="width: 5%">Semester</th>
                <th style="width: 5%">Course Code</th>
                <th style="width: 20%">Course Name</th>
                <th style="width: 5%">Structure</th>
                <th style="width: 10%">Credit</th>
                <th style="width: 10%">Classification</th>
            </tr>
        </thead>
        <tbody id="table"></tbody>
      `);

      // Use passed parameters or fallback to dropdown values
      program = program || $('#program').val();
      structure = structure || $('#structure').val();
      
      // Validation
      if (!program || program === '-' || !structure || structure === '-') {
          console.log('Invalid parameters, aborting');
          return;
      }
      
      console.log('Making AJAX call with program:', program, 'structure:', structure);

      return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/structureReport/getStructureReport') }}",
            method   : 'POST',
            data 	 : {program: program, structure: structure},
            beforeSend:function(xhr){
              $("#myTable").LoadingOverlay("show", {
                image: `<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                  <rect x="0" y="10" width="4" height="10" fill="#333" opacity="0.2">
                  <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
                  </rect>
                  <rect x="8" y="10" width="4" height="10" fill="#333" opacity="0.2">
                  <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
                  </rect>
                  <rect x="16" y="10" width="4" height="10" fill="#333" opacity="0.2">
                  <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
                  </rect>
                </svg>`,
                background:"rgba(255,255,255, 0.3)",
                imageResizeFactor : 1,    
                imageAnimation : "2000ms pulse" , 
                imageColor: "#019ff8",
                text : "Please wait...",
                textResizeFactor: 0.15,
                textColor: "#019ff8"
              });
            },
            error:function(err){
                $("#myTable").LoadingOverlay("hide");
                alert("Error");
                console.log(err);
                // Hide export buttons on error
                $('#exportButtons').hide();
            },
            success  : function(data){
                $("#myTable").LoadingOverlay("hide");
                console.log('=== AJAX SUCCESS ===');
                console.log('Data received:', typeof data, data.substring ? data.substring(0, 200) + '...' : data);
                
                if(data.error)
                {
                  console.log('Error in response:', data.error);
                  alert(data.error);
                  // Hide export buttons on error
                  $('#exportButtons').hide();
                }else{
                  console.log('Processing successful response');
                  // Clear and populate table with new data
                  $('#myTable').removeAttr('hidden');
                  $('#myTable').html(data);
                  
                  console.log('Table HTML updated, showing export buttons');
                  // Show export buttons
                  $('#exportButtons').show();
                  
                  console.log('Initializing new DataTable');
                  // Initialize DataTable without buttons since we have custom ones
                  $('#myTable').DataTable({
                    dom: 'lfrtip',
                    pageLength: 100,
                    searching: false,
                    ordering: false,
                    info: false,
                    paging: false
                  });
                  console.log('=== getCourse COMPLETE ===');
                }
              }
        });
  }

  // Export Functions
  function exportToExcel() {
    try {
      // Get the table data
      var table = document.getElementById('myTable');
      if (!table) {
        alert('No table data to export');
        return;
      }
      
      // Get program and structure names for filename
      var programText = $('#program option:selected').text() || 'Structure';
      var structureText = $('#structure option:selected').text() || 'Report';
      var filename = programText + ' - ' + structureText + '.xlsx';
      
      // Create workbook
      var wb = XLSX.utils.table_to_book(table, {sheet: "Structure Report"});
      
      // Export to Excel
      XLSX.writeFile(wb, filename);
    } catch (error) {
      console.error('Excel export error:', error);
      alert('Error exporting to Excel. Please try again.');
    }
  }

  function exportToPDF() {
    try {
      const { jsPDF } = window.jspdf;
      var doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
      
      // Get program and structure names for title
      var programText = $('#program option:selected').text() || 'Structure Report';
      var structureText = $('#structure option:selected').text() || '';
      var title = programText + (structureText ? ' - ' + structureText : '');
      
      // Add title
      doc.setFontSize(16);
      doc.text(title, 14, 20);
      
      // Get table data
      var table = document.getElementById('myTable');
      var rows = [];
      var headers = [];
      
      // Get headers
      var headerCells = table.querySelectorAll('thead tr th');
      headerCells.forEach(function(cell) {
        headers.push(cell.textContent.trim());
      });
      
      // Get data rows (exclude JUMLAH rows for cleaner PDF)
      var bodyRows = table.querySelectorAll('tbody tr');
      bodyRows.forEach(function(row) {
        var rowData = [];
        var cells = row.querySelectorAll('td');
        if (cells.length > 0 && cells[0].textContent.trim() !== 'JUMLAH') {
          cells.forEach(function(cell) {
            rowData.push(cell.textContent.trim());
          });
          if (rowData.length > 0) {
            rows.push(rowData);
          }
        }
      });
      
      // Add table
      doc.autoTable({
        head: [headers],
        body: rows,
        startY: 30,
        styles: {
          fontSize: 8,
          cellPadding: 2
        },
        headStyles: {
          fillColor: [54, 162, 235],
          textColor: 255,
          fontStyle: 'bold'
        }
      });
      
      // Save PDF
      var filename = title + '.pdf';
      doc.save(filename);
    } catch (error) {
      console.error('PDF export error:', error);
      alert('Error exporting to PDF. Please try again.');
    }
  }

  function printTable() {
    try {
      // Get program and structure names for title
      var programText = $('#program option:selected').text() || 'Structure Report';
      var structureText = $('#structure option:selected').text() || '';
      var title = programText + (structureText ? ' - ' + structureText : '');
      
      // Get table HTML
      var table = document.getElementById('myTable');
      if (!table) {
        alert('No table data to print');
        return;
      }
      
      // Create print window
      var printWindow = window.open('', '_blank');
      var printHTML = `
        <!DOCTYPE html>
        <html>
        <head>
          <title>${title}</title>
          <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { text-align: center; margin-bottom: 20px; color: #333; }
            table { width: 100%; border-collapse: collapse; margin: 0 auto; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background-color: #f5f5f5; font-weight: bold; text-align: center; }
            .semester-cell { font-weight: bold; text-align: center; vertical-align: middle; }
            .total-row { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; font-weight: bold; }
            .center { text-align: center; }
            @media print {
              body { margin: 0; }
              h1 { page-break-after: avoid; }
            }
          </style>
        </head>
        <body>
          <h1>${title}</h1>
          ${table.outerHTML}
        </body>
        </html>
      `;
      
      printWindow.document.write(printHTML);
      printWindow.document.close();
      
      // Wait for content to load then print
      setTimeout(function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
      }, 500);
    } catch (error) {
      console.error('Print error:', error);
      alert('Error printing table. Please try again.');
    }
  }

  </script>
@endsection
