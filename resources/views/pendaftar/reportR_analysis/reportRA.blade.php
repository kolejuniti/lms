@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : '')))


@section('main')

<!-- Content Header (Page header) -->
<style>
  #loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
  }
  
  .loading-spinner {
    text-align: center;
    padding: 20px;
    border-radius: 8px;
    background-color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
</style>

<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Analysis Student R</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Analysis Student R</li>
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
      <!-- Loading Overlay -->
      <div id="loading-overlay" class="d-none">
        <div class="loading-spinner">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Loading data...</p>
        </div>
      </div>
      
      <!-- /.card-header -->
      <div class="card card-primary">
        <div class="card-header">
          <b>Search Student</b>
          <button id="printButton" class="btn btn-primary">Print / PDF</button>
        </div>
        <div class="card-body">
          <!-- Number of Tables Selector -->
          <div class="row mb-4">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label fw-bold text-primary" for="table_count">
                  <i class="mdi mdi-table-multiple me-2"></i>Number of Tables
                </label>
                <select class="form-control form-select" id="table_count" name="table_count">
                  <option value="">Select number of tables...</option>
                  <option value="1">1 Table</option>
                  <option value="2">2 Tables</option>
                  <option value="3">3 Tables</option>
                  <option value="4">4 Tables</option>
                  <option value="5">5 Tables</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Dynamic Date Range Containers -->
          <div id="date-ranges-container" class="row">
            <!-- Dynamic content will be inserted here -->
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



  <script type="text/javascript">
    var from = '';
    var to = '';

    // Handle dropdown change for number of tables
    $(document).on('change', '#table_count', function(e) {
      const tableCount = parseInt($(e.target).val());
      generateDateRanges(tableCount);
    });

    // Generate dynamic date range boxes
    function generateDateRanges(count) {
      const container = $('#date-ranges-container');
      container.empty();

      if (count > 0) {
        // Add a title for the date ranges section
        container.append(`
          <div class="col-12 mb-3">
            <h5 class="text-secondary">
              <i class="mdi mdi-calendar-range me-2"></i>Date Ranges for ${count} Table${count > 1 ? 's' : ''}
            </h5>
            <hr class="my-2">
          </div>
        `);

        // Create date range boxes
        for (let i = 1; i <= count; i++) {
          const colClass = count <= 2 ? 'col-md-6' : count <= 3 ? 'col-md-4' : 'col-md-3';
          
          container.append(`
            <div class="${colClass} mb-4">
              <div class="card border-primary" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0,123,255,0.15);">
                <div class="card-header bg-primary text-white text-center" style="border-radius: 15px 15px 0 0;">
                  <h6 class="mb-0">
                    <i class="mdi mdi-table me-2"></i>Table ${i}
                  </h6>
                </div>
                <div class="card-body p-3">
                  <div class="form-group mb-3">
                    <label class="form-label fw-bold text-success" for="from_${i}">
                      <i class="mdi mdi-calendar-start me-1"></i>FROM
                    </label>
                    <input type="date" class="form-control date-input" id="from_${i}" name="from_${i}" 
                           style="border-radius: 10px; border: 2px solid #28a745;">
                  </div>
                  <div class="form-group">
                    <label class="form-label fw-bold text-danger" for="to_${i}">
                      <i class="mdi mdi-calendar-end me-1"></i>TO
                    </label>
                    <input type="date" class="form-control date-input" id="to_${i}" name="to_${i}"
                           style="border-radius: 10px; border: 2px solid #dc3545;">
                  </div>
                </div>
              </div>
            </div>
          `);
        }

        // Add animation effect
        container.find('.card').hide().fadeIn(600);
      }
    }

    // Handle date input changes for any dynamic date input
    $(document).on('change', '.date-input', async function(e) {
      // Collect all date ranges
      const dateRanges = collectDateRanges();
      
      if (dateRanges.length > 0) {
        $('#loading-overlay').removeClass('d-none');
        await getStudentWithMultipleRanges(dateRanges);
      }
    });

    // Collect all date ranges from the dynamic inputs
    function collectDateRanges() {
      const ranges = [];
      const tableCount = parseInt($('#table_count').val());
      
      for (let i = 1; i <= tableCount; i++) {
        const fromValue = $(`#from_${i}`).val();
        const toValue = $(`#to_${i}`).val();
        
        if (fromValue && toValue) {
          ranges.push({
            table: i,
            from: fromValue,
            to: toValue
          });
        }
      }
      
      return ranges;
    }

    // Updated function to handle multiple date ranges
    function getStudentWithMultipleRanges(dateRanges) {
      return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('/pendaftar/student/reportRA/getStudentReportRA') }}",
        method: 'GET',
        data: {
          date_ranges: JSON.stringify(dateRanges),
          multiple_tables: true
        },
        beforeSend: function() {
          $('#loading-overlay').removeClass('d-none');
        },
        error: function(err) {
          $('#loading-overlay').addClass('d-none');
          alert("Error");
          console.log(err);
        },
        success: function(data) {
          $('#loading-overlay').addClass('d-none');
          if (data.error) {
            alert(data.error);
          } else {
            $('#form-student').html(data);

            $('#myTable').DataTable({
              dom: 'lfrtip',
              ordering: false
            });

            $('#myTable2').DataTable({
              dom: 'lfrtip'
            });
          }
        }
      });
    }

  //   // Keep original functions for backward compatibility
  //   $(document).on('change', '#from', async function(e){
  //     from = $(e.target).val();
  //     $('#loading-overlay').removeClass('d-none');
  //     await getStudent(from,to);
  //   });

  //   $(document).on('change', '#to', async function(e){
  //     to = $(e.target).val();
  //     $('#loading-overlay').removeClass('d-none');
  //     await getStudent(from,to);
  //   });

  // function getStudent(from,to)
  // {
  //   //alert(from);
  //   return $.ajax({
  //           headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
  //           url      : "{{ url('/pendaftar/student/reportRA/getStudentReportRA') }}",
  //           method   : 'GET',
  //           data 	 : {from: from, to: to},
  //           beforeSend: function() {
  //               $('#loading-overlay').removeClass('d-none');
  //           },
  //           error:function(err){
  //               $('#loading-overlay').addClass('d-none');
  //               alert("Error");
  //               console.log(err);
  //           },
  //           success  : function(data){
  //           $('#loading-overlay').addClass('d-none');
  //           if(data.error)
  //           {
  //             alert(data.error);
  //           }else{
  //               $('#form-student').html(data);

  //               $('#myTable').DataTable({
  //                 dom: 'lfrtip', // Removed B for buttons
  //                 ordering: false // Disable ordering
  //               });

  //               $('#myTable2').DataTable({
  //                 dom: 'lfrtip' // Removed B for buttons
  //               });
                
  //             }
  //           }
  //       });

  // }

  $(document).ready(function() {
    $('#printButton').on('click', function(e) {
      e.preventDefault();
      $('#loading-overlay').removeClass('d-none');
      printReport();
    });
  });

  function printReport() {
    $('#loading-overlay').removeClass('d-none');
    
    // Check if we have data loaded on the page
    if ($('#form-student').children().length === 0) {
      $('#loading-overlay').addClass('d-none');
      alert("No data to print. Please select date ranges and load data first.");
      return;
    }
    
    // Create the print window
    var printWindow = window.open('', '_blank', 'width=1200,height=800');
    
    // Build the HTML content
    var htmlContent = '<!DOCTYPE html>';
    htmlContent += '<html><head><title>Student R Analysis Report</title>';
    htmlContent += '<style>';
    htmlContent += 'body { font-family: Arial, sans-serif; margin: 20px; font-size: 11px; }';
    htmlContent += '.header { text-align: center; margin-bottom: 30px; }';
    htmlContent += '.table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }';
    htmlContent += '.table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: center; }';
    htmlContent += '.table th { background-color: #f2f2f2; font-weight: bold; }';
    htmlContent += '.table-title { font-weight: bold; margin-bottom: 10px; padding: 10px; background-color: #007bff; color: white; text-align: center; }';
    htmlContent += '.summary-title { font-weight: bold; margin-top: 30px; margin-bottom: 10px; padding: 10px; background-color: #28a745; color: white; text-align: center; }';
    htmlContent += '.monthly-title { font-weight: bold; margin-top: 30px; margin-bottom: 10px; padding: 10px; background-color: #17a2b8; color: white; text-align: center; }';
    htmlContent += '.monthly-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 9px; }';
    htmlContent += '.monthly-table th, .monthly-table td { border: 1px solid #000; padding: 4px; text-align: center; }';
    htmlContent += '.monthly-table th { background-color: #f8f9fa; font-weight: bold; }';
    htmlContent += '.monthly-table .month-cell { font-weight: bold; background-color: #fff; }';
    htmlContent += '.monthly-table .week-cell { background-color: #fff; font-size: 8px; }';
    htmlContent += '.monthly-table .total-row { background-color: #f8f9fa; font-weight: bold; }';
    htmlContent += '.monthly-table .date-range { font-size: 7px; color: #666; }';
    htmlContent += '@media print { body { margin: 0; } .no-print { display: none; } }';
    htmlContent += '</style></head><body>';
    
    // Add header
    htmlContent += '<div class="header">';
    htmlContent += '<h2>Student R Analysis Report</h2>';
    htmlContent += '<p>Generated on: ' + new Date().toLocaleString() + '</p>';
    htmlContent += '<div class="no-print"><button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px;">Print Report</button></div>';
    htmlContent += '</div>';
    
    // Extract and add main table data
    $('#form-student .card').each(function() {
        var cardHeader = $(this).find('.card-header b').text();
        var tableRows = $(this).find('tbody tr');
        
        // Skip Monthly Comparison Analysis for now, we'll add it separately
        if (cardHeader.indexOf('Monthly Comparison Analysis') === -1) {
            if (tableRows.length > 0) {
                htmlContent += '<div class="table-title">' + cardHeader + '</div>';
                htmlContent += '<table class="table"><thead><tr>';
                htmlContent += '<th>Total Student R</th><th>Total by Convert</th><th>Balance Student</th>';
                htmlContent += '<th>Student Active</th><th>Student Rejected</th><th>Student Offered</th>';
                htmlContent += '<th>Student KIV</th><th>Student Others</th>';
                htmlContent += '</tr></thead><tbody>';
                
                tableRows.each(function() {
                    var cells = $(this).find('td');
                    if (cells.length > 0) {
                        htmlContent += '<tr>';
                        cells.each(function() {
                            htmlContent += '<td>' + $(this).text().trim() + '</td>';
                        });
                        htmlContent += '</tr>';
                    }
                });
                
                htmlContent += '</tbody></table>';
            }
        }
    });
    
    // Add Monthly Comparison Analysis table if it exists
    var monthlyTable = $('#monthly_comparison_table');
    if (monthlyTable.length > 0) {
        htmlContent += '<div class="monthly-title">Monthly Comparison Analysis</div>';
        htmlContent += '<table class="monthly-table">';
        
        // Add table headers
        var headerRows = monthlyTable.find('thead tr');
        if (headerRows.length > 0) {
            htmlContent += '<thead>';
            headerRows.each(function() {
                htmlContent += '<tr>';
                $(this).find('th').each(function() {
                    var colspan = $(this).attr('colspan') || '1';
                    var rowspan = $(this).attr('rowspan') || '1';
                    var cellText = $(this).text().trim();
                    htmlContent += '<th colspan="' + colspan + '" rowspan="' + rowspan + '">' + cellText + '</th>';
                });
                htmlContent += '</tr>';
            });
            htmlContent += '</thead>';
        }
        
        // Add table body
        var bodyRows = monthlyTable.find('tbody tr');
        if (bodyRows.length > 0) {
            htmlContent += '<tbody>';
            bodyRows.each(function() {
                htmlContent += '<tr>';
                $(this).find('td').each(function() {
                    var colspan = $(this).attr('colspan') || '1';
                    var rowspan = $(this).attr('rowspan') || '1';
                    var cellText = $(this).text().trim();
                    var cellClass = '';
                    
                    // Check for special styling
                    if ($(this).hasClass('text-center')) {
                        cellClass += ' text-center';
                    }
                    if ($(this).find('strong').length > 0) {
                        cellClass += ' month-cell';
                    }
                    if ($(this).find('small').length > 0) {
                        cellClass += ' date-range';
                    }
                    
                    htmlContent += '<td colspan="' + colspan + '" rowspan="' + rowspan + '" class="' + cellClass + '">' + cellText + '</td>';
                });
                htmlContent += '</tr>';
            });
            htmlContent += '</tbody>';
        }
        
        // Add table footer
        var footerRows = monthlyTable.find('tfoot tr');
        if (footerRows.length > 0) {
            htmlContent += '<tfoot>';
            footerRows.each(function() {
                htmlContent += '<tr class="total-row">';
                $(this).find('td').each(function() {
                    var colspan = $(this).attr('colspan') || '1';
                    var cellText = $(this).text().trim();
                    htmlContent += '<td colspan="' + colspan + '">' + cellText + '</td>';
                });
                htmlContent += '</tr>';
            });
            htmlContent += '</tfoot>';
        }
        
        htmlContent += '</table>';
    }
    
    htmlContent += '</body></html>';
    
    // Write content to print window
    printWindow.document.write(htmlContent);
    printWindow.document.close();
    
    // Auto print after a delay
    setTimeout(function() {
        printWindow.print();
    }, 500);
    
    $('#loading-overlay').addClass('d-none');
  }
  </script>
@endsection
