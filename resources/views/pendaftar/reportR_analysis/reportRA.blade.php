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
          <!-- Date Range and Year Selection -->
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label fw-bold text-primary">
                  <i class="mdi mdi-calendar-range me-2"></i>Date Range (Day/Month will be applied to all selected years)
                </label>
                <div class="row">
                  <div class="col-md-6">
                    <label class="form-label fw-bold text-success" for="from_date">
                      <i class="mdi mdi-calendar-start me-1"></i>FROM
                    </label>
                    <input type="date" class="form-control" id="from_date" name="from_date" 
                           style="border-radius: 10px; border: 2px solid #28a745;">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-bold text-danger" for="to_date">
                      <i class="mdi mdi-calendar-end me-1"></i>TO
                    </label>
                    <input type="date" class="form-control" id="to_date" name="to_date"
                           style="border-radius: 10px; border: 2px solid #dc3545;">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="form-label fw-bold text-info">
                  <i class="mdi mdi-calendar-multiple me-2"></i>Select Years
                </label>
                <div class="card" style="max-height: 200px; overflow-y: auto;">
                  <div class="card-body p-2">
                    @php
                      $currentYear = date('Y');
                      $years = [];
                      for($i = 0; $i < 6; $i++) {
                        $years[] = $currentYear - $i;
                      }
                    @endphp
                    @foreach($years as $year)
                      <div class="form-check">
                        <input class="form-check-input year-checkbox" type="checkbox" value="{{ $year }}" id="year_{{ $year }}">
                        <label class="form-check-label" for="year_{{ $year }}">
                          {{ $year }}
                        </label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="form-label fw-bold text-success">
                  <i class="mdi mdi-file-chart me-2"></i>Report Type
                </label>
                <div class="mt-2">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="report_type" id="report1" value="1" checked>
                    <label class="form-check-label fw-bold text-primary" for="report1">
                      Report 1 (Standard Analysis)
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="report_type" id="report2" value="2">
                    <label class="form-check-label fw-bold text-success" for="report2">
                      Report 2 (Monthly Comparison)
                    </label>
                  </div>
                </div>
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

<!-- Page specific script -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {
    // Handle date input changes
    $(document).on('change', '#from_date, #to_date', function() {
      generateReport();
    });

    // Handle year checkbox changes
    $(document).on('change', '.year-checkbox', function() {
      generateReport();
    });

    // Handle report type changes
    $(document).on('change', 'input[name="report_type"]', function() {
      generateReport();
    });

    $('#printButton').on('click', function(e) {
      e.preventDefault();
      $('#loading-overlay').removeClass('d-none');
      printReport();
    });
  });

  function generateReport() {
    const fromDate = $('#from_date').val();
    const toDate = $('#to_date').val();
    const selectedYears = [];
    const reportType = $('input[name="report_type"]:checked').val();

    // Collect selected years
    $('.year-checkbox:checked').each(function() {
      selectedYears.push($(this).val());
    });

    // Only proceed if we have both dates and at least one year
    if (fromDate && toDate && selectedYears.length > 0) {
      $('#loading-overlay').removeClass('d-none');
      
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('/pendaftar/student/reportRA/getStudentReportRA') }}",
        method: 'GET',
        data: {
          from_date: fromDate,
          to_date: toDate,
          selected_years: JSON.stringify(selectedYears),
          report_type: reportType
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

            // Initialize DataTable for Report 1
            if (reportType === '1') {
              $('#myTable').DataTable({
                dom: 'lfrtip',
                ordering: false
              });

              $('#myTable2').DataTable({
                dom: 'lfrtip'
              });
            }
          }
        }
      });
    }
  }

  function printReport() {
    $('#loading-overlay').removeClass('d-none');
    
    // Check if we have data loaded on the page
    if ($('#form-student').children().length === 0) {
      $('#loading-overlay').addClass('d-none');
      alert("No data to print. Please select date range and years first.");
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
    
    // Extract and add table content from the current page
    htmlContent += $('#form-student').html();
    
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

  function printReport2() {
    const fromDate = $('#from_date').val();
    const toDate = $('#to_date').val();
    const selectedYears = [];
    const reportType = $('input[name="report_type"]:checked').val();

    // Collect selected years
    $('.year-checkbox:checked').each(function() {
      selectedYears.push($(this).val());
    });

    if (fromDate && toDate && selectedYears.length > 0) {
      // Use the same URL for both report types
      var url = "{{ url('pendaftar/student/reportRA/getStudentReportRA') }}";
      
      var form = document.createElement('form');
      form.method = 'GET';
      form.action = url;
      
      // Add excel parameter
      var excelInput = document.createElement('input');
      excelInput.type = 'hidden';
      excelInput.name = 'excel';
      excelInput.value = 'true';
      form.appendChild(excelInput);
      
      // Add report type parameter
      var reportTypeInput = document.createElement('input');
      reportTypeInput.type = 'hidden';
      reportTypeInput.name = 'report_type';
      reportTypeInput.value = reportType;
      form.appendChild(reportTypeInput);

      // Add date parameters
      var fromDateInput = document.createElement('input');
      fromDateInput.type = 'hidden';
      fromDateInput.name = 'from_date';
      fromDateInput.value = fromDate;
      form.appendChild(fromDateInput);

      var toDateInput = document.createElement('input');
      toDateInput.type = 'hidden';
      toDateInput.name = 'to_date';
      toDateInput.value = toDate;
      form.appendChild(toDateInput);
      
      var selectedYearsInput = document.createElement('input');
      selectedYearsInput.type = 'hidden';
      selectedYearsInput.name = 'selected_years';
      selectedYearsInput.value = JSON.stringify(selectedYears);
      form.appendChild(selectedYearsInput);
      
      document.body.appendChild(form);
      form.submit();
      document.body.removeChild(form);
    } else {
      alert("Please select date range and at least one year first.");
    }
  }
</script>
@endsection
