<div class="row mt-3 d-flex">
  <div class="col-md-12 mb-3">
    <div class="pull-right">
      <button id="exportBtn" class="btn btn-success">Export to Excel</button>
      <button id="analyseBtn" class="btn btn-primary ml-2">
        <i class="fa fa-chart-line"></i> Analyse Data
      </button>
    </div>
  </div>
</div>

<!-- Analysis Results Section -->
<div id="analysisSection" class="card mb-3" style="display: none;">
  <div class="card-header bg-info text-white">
    <h5><i class="fa fa-chart-line"></i> AI Data Analysis Report</h5>
  </div>
  <div class="card-body">
    <div id="analysisLoading" class="text-center" style="display: none;">
      <i class="fa fa-spinner fa-spin fa-2x"></i>
      <p class="mt-2">Analyzing data with AI...</p>
    </div>
    <div id="analysisResult">
      <textarea id="analysisTextarea" class="form-control" rows="15" readonly style="background-color: #f8f9fa; border: 1px solid #dee2e6; font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6;"></textarea>
    </div>
  </div>
</div>

@if(isset($data['tableLabels']) && is_array($data['tableLabels']))
  <!-- Multiple Tables Display -->
  @foreach($data['tableLabels'] as $key => $label)
  <div class="card mb-3" id="stud_info_{{ $key }}">
    <div class="card-header">
      <b>{{ $label }}</b>
    </div>
    <div class="small text-muted px-3 py-2">
        Note: Data shown follows the calendar date (Sunday to Saturday)
    </div>
    <div class="card-body p-0">
      <table class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 15%">Total Student R</th>
                <th style="width: 15%">Total by Convert</th>
                <th style="width: 15%">Balance Student</th>
                <th style="width: 15%">Student Active</th>
                <th style="width: 15%">Student Rejected</th>
                <th style="width: 15%">Student Offered</th>
                <th style="width: 15%">Student KIV <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Students whose current date has passed their offered date"></i></th>
                <th style="width: 15%">Student Others <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Includes: GAGAL BERHENTI, TARIK DIRI, MENINGGAL DUNIA, TANGGUH, DIBERHENTIKAN, TAMAT PENGAJIAN, TUKAR PROGRAM, GANTUNG, TUKAR KE KUKB, PINDAH KOLEJ, TIDAK TAMAT PENGAJIAN, TAMAT PENGAJIAN (MENINGGAL DUNIA)"></i></th>
            </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ $data['allStudents'][$key] }}</td>
            <td>{{ $data['totalConvert'][$key] }}</td>
            <td>{{ $data['allStudents'][$key] - $data['totalConvert'][$key] }}</td>
            <td>{{ $data['registered'][$key] }}</td>
            <td>{{ $data['rejected'][$key] }}</td>
            <td>{{ $data['offered'][$key] }}</td>
            <td>{{ $data['KIV'][$key] }}</td>
            <td>{{ $data['others'][$key] }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @endforeach

  @if(count($data['tableLabels']) > 1)
  <!-- Summary Table for Multiple Tables -->
  <div class="card mb-3" id="summary_table">
    <div class="card-header">
      <b>Summary of All Tables</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 15%">Total Student R</th>
                <th style="width: 15%">Total by Convert</th>
                <th style="width: 15%">Balance Student</th>
                <th style="width: 15%">Student Active</th>
                <th style="width: 15%">Student Rejected</th>
                <th style="width: 15%">Student Offered</th>
                <th style="width: 15%">Student KIV</th>
                <th style="width: 15%">Student Others</th>
            </tr>
        </thead>
        <tbody>
          <tr>
            @php
            $total_all = array_sum($data['allStudents']);
            $total_convert = array_sum($data['totalConvert']);
            $total_registered = array_sum($data['registered']);
            $total_rejected = array_sum($data['rejected']);
            $total_offered = array_sum($data['offered']);
            $total_kiv = array_sum($data['KIV']);
            $total_others = array_sum($data['others']);
            $grand_total = $total_convert + $total_registered + $total_rejected + $total_offered + $total_kiv + $total_others;
            @endphp
            <td>{{ $total_all }}</td>
            <td>{{ $total_convert }}</td>
            <td>{{ $grand_total - $total_convert }}</td>
            <td>{{ $total_registered }}</td>
            <td>{{ $total_rejected }}</td>
            <td>{{ $total_offered }}</td>
            <td>{{ $total_kiv }}</td>
            <td>{{ $total_others }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @endif
@else
  <!-- Single Table Display -->
  <div class="card mb-3" id="stud_info">
    <div class="card-header">
      <b>Total Student R Analysis</b>
    </div>
    <div class="small text-muted px-3 py-2">
        Note: Data shown follows the calendar date (Sunday to Saturday)
    </div>  
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 15%">Total Student R</th>
                <th style="width: 15%">Total by Convert</th>
                <th style="width: 15%">Balance Student</th>
                <th style="width: 15%">Student Active</th>
                <th style="width: 15%">Student Rejected</th>
                <th style="width: 15%">Student Offered</th>
                <th style="width: 15%">Student KIV <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Students whose current date has passed their offered date"></i></th>
                <th style="width: 15%">Student Others <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Includes: GAGAL BERHENTI, TARIK DIRI, MENINGGAL DUNIA, TANGGUH, DIBERHENTIKAN, TAMAT PENGAJIAN, TUKAR PROGRAM, GANTUNG, TUKAR KE KUKB, PINDAH KOLEJ, TIDAK TAMAT PENGAJIAN, TAMAT PENGAJIAN (MENINGGAL DUNIA)"></i></th>
            </tr>
        </thead>
        <tbody>
          <tr>
            @php
            $total_all = $data['allStudents'] + $data['totalConvert'] + $data['registered'] + $data['rejected'] + $data['offered'] + $data['KIV'] + $data['others'];
            @endphp
            <td>{{ $data['allStudents'] }}</td>
            <td>{{ $data['totalConvert'] }}</td>
            <td>{{ $data['allStudents'] - $data['totalConvert'] }}</td>
            <td>{{ $data['registered'] }}</td>
            <td>{{ $data['rejected'] }}</td>
            <td>{{ $data['offered'] }}</td>
            <td>{{ $data['KIV'] }}</td>
            <td>{{ $data['others'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endif

@if(isset($data['monthlyComparison']) && !empty($data['monthlyComparison']) && !empty($data['monthlyComparison']['monthly_data']))
<!-- Monthly Comparison Table -->
<div class="card mb-3" id="monthly_comparison_table">
  <div class="card-header">
    <b>Monthly Comparison Analysis</b>
    <div class="float-right">
      <small class="text-muted">Showing {{ count($data['monthlyComparison']['years']) }} years</small>
    </div>
  </div>
  <div class="small text-muted px-3 py-2">
      Note: Data shown follows the calendar date (Sunday to Saturday) for weekly breakdown. Only months with data are displayed.
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-sm">
        <thead class="thead-light">
          <tr>
            <th rowspan="2" style="vertical-align: middle; width: 80px; position: sticky; left: 0; background: #f8f9fa; z-index: 10;">Month</th>
            <th rowspan="2" style="vertical-align: middle; width: 180px; position: sticky; left: 80px; background: #f8f9fa; z-index: 10;">Week (Date Range)</th>
            @foreach($data['monthlyComparison']['years'] as $year)
              <th colspan="3" class="text-center bg-light">Year {{ $year }}</th>
            @endforeach
          </tr>
          <tr>
            @foreach($data['monthlyComparison']['years'] as $year)
              <th style="width: 100px;">Total By Weeks</th>
              <th style="width: 100px;">Total By Converts</th>
              <th style="width: 100px;">Balance Student</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @php
            $monthsWithData = [];
            
            // Collect all months that have data across all years
            foreach($data['monthlyComparison']['years'] as $year) {
              if (isset($data['monthlyComparison']['monthly_data'][$year])) {
                foreach($data['monthlyComparison']['monthly_data'][$year] as $monthNum => $monthData) {
                  if (!empty($monthData['weeks'])) {
                    if (!in_array($monthNum, $monthsWithData)) {
                      $monthsWithData[] = $monthNum;
                    }
                  }
                }
              }
            }
            
            sort($monthsWithData);
            
            $monthNames = [
              1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
              5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 
              9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
          @endphp
          
          @if(empty($monthsWithData))
            <tr>
              <td colspan="{{ 2 + (count($data['monthlyComparison']['years']) * 3) }}" class="text-center text-muted py-4">
                No data available for the selected period
              </td>
            </tr>
          @else
            @foreach($monthsWithData as $monthNumber)
              @php
                $monthName = $monthNames[$monthNumber];
                $maxWeeks = 0;
                
                // Find the maximum number of weeks across all years for this month
                foreach($data['monthlyComparison']['years'] as $year) {
                  if (isset($data['monthlyComparison']['monthly_data'][$year][$monthNumber]['weeks'])) {
                    $maxWeeks = max($maxWeeks, count($data['monthlyComparison']['monthly_data'][$year][$monthNumber]['weeks']));
                  }
                }
              @endphp
              
              @if($maxWeeks > 0)
                @for($weekNum = 1; $weekNum <= $maxWeeks; $weekNum++)
                  <tr>
                    @if($weekNum == 1)
                      <td rowspan="{{ $maxWeeks }}" style="vertical-align: middle; font-weight: bold; position: sticky; left: 0; background: #fff; z-index: 5;">
                        {{ $monthName }}
                      </td>
                    @endif
                    
                    @php
                      // Get the date range for this week from any year that has data
                      $weekDateRange = null;
                      foreach($data['monthlyComparison']['years'] as $year) {
                        if (isset($data['monthlyComparison']['monthly_data'][$year][$monthNumber]['weeks'][$weekNum - 1]['date_range'])) {
                          $weekDateRange = $data['monthlyComparison']['monthly_data'][$year][$monthNumber]['weeks'][$weekNum - 1]['date_range'];
                          break;
                        }
                      }
                    @endphp
                    
                    <td class="text-center" style="position: sticky; left: 80px; background: #fff; font-size: 12px; z-index: 5; padding: 8px 4px;">
                      <div style="line-height: 1.2;">
                        <strong>Week {{ $weekNum }}</strong>
                        @if($weekDateRange)
                          <br><small class="text-muted" style="font-size: 10px;">({{ $weekDateRange }})</small>
                        @endif
                      </div>
                    </td>
                    
                    @foreach($data['monthlyComparison']['years'] as $year)
                      @php
                        $weekData = null;
                        if (isset($data['monthlyComparison']['monthly_data'][$year][$monthNumber]['weeks'][$weekNum - 1])) {
                          $weekData = $data['monthlyComparison']['monthly_data'][$year][$monthNumber]['weeks'][$weekNum - 1];
                        }
                      @endphp
                      
                      @if($weekData)
                        <td class="text-center">{{ number_format($weekData['total_by_weeks']) }}</td>
                        <td class="text-center">{{ number_format($weekData['total_by_converts']) }}</td>
                        <td class="text-center">{{ number_format($weekData['balance_student']) }}</td>
                      @else
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                        <td class="text-center text-muted">-</td>
                      @endif
                    @endforeach
                  </tr>
                @endfor
              @endif
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif

<script>
$(document).ready(function() {
  // Initialize tooltips
  $('[data-toggle="tooltip"]').tooltip();
  
  // Bind export button click handler
  $(document).off('click', '#exportBtn').on('click', '#exportBtn', function(e) {
    e.preventDefault();
    printReport2();
  });

  // Bind analyse button click handler
  $(document).off('click', '#analyseBtn').on('click', '#analyseBtn', function(e) {
    e.preventDefault();
    analyseData();
  });
});

function printReport2() {
  // Check if using multiple tables or single range
  const tableCount = parseInt($('#table_count').val()) || 0;
  
  if (tableCount > 0) {
    // Use multiple date ranges for export
    const dateRanges = collectDateRanges();
    
    // Create base URL without query parameters
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
    
    var dateRangesInput = document.createElement('input');
    dateRangesInput.type = 'hidden';
    dateRangesInput.name = 'date_ranges';
    dateRangesInput.value = JSON.stringify(dateRanges);
    form.appendChild(dateRangesInput);
    
    var multipleTablesInput = document.createElement('input');
    multipleTablesInput.type = 'hidden';
    multipleTablesInput.name = 'multiple_tables';
    multipleTablesInput.value = 'true';
    form.appendChild(multipleTablesInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  } else {
    // Use original single range method
    var from = $('#from').val();
    var to = $('#to').val();
    var url = "{{ url('pendaftar/student/reportRA/getStudentReportRA') }}";

    window.location.href = `${url}?excel=true&from=${from}&to=${to}`;
  }
}

function analyseData() {
  // Show analysis section and loading
  $('#analysisSection').show();
  $('#analysisLoading').show();
  $('#analysisResult').hide();
  
  // Collect table data
  const tableData = collectTableData();
  
  // Send data to backend for AI analysis
  $.ajax({
    url: "{{ url('pendaftar/student/reportRA/analyseData') }}",
    method: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      tableData: JSON.stringify(tableData)
    },
    success: function(response) {
      $('#analysisLoading').hide();
      $('#analysisResult').show();
      
      if (response.success) {
        $('#analysisTextarea').val(response.analysis);
      } else {
        $('#analysisTextarea').val('Error: ' + response.message);
      }
    },
    error: function(xhr, status, error) {
      $('#analysisLoading').hide();
      $('#analysisResult').show();
      $('#analysisTextarea').val('Error occurred while analyzing data: ' + error);
    }
  });
}

function collectTableData() {
  const data = {};
  
  @if(isset($data['tableLabels']) && is_array($data['tableLabels']))
    // Multiple tables
    data.type = 'multiple';
    data.tables = {};
    data.labels = @json($data['tableLabels']);
    
    @foreach($data['tableLabels'] as $key => $label)
      data.tables[{{ $key }}] = {
        label: "{{ $label }}",
        totalStudentR: {{ $data['allStudents'][$key] }},
        totalConvert: {{ $data['totalConvert'][$key] }},
        balanceStudent: {{ $data['allStudents'][$key] - $data['totalConvert'][$key] }},
        studentActive: {{ $data['registered'][$key] }},
        studentRejected: {{ $data['rejected'][$key] }},
        studentOffered: {{ $data['offered'][$key] }},
        studentKIV: {{ $data['KIV'][$key] }},
        studentOthers: {{ $data['others'][$key] }}
      };
    @endforeach
  @else
    // Single table
    data.type = 'single';
    @php
    $total_all = $data['allStudents'] + $data['totalConvert'] + $data['registered'] + $data['rejected'] + $data['offered'] + $data['KIV'] + $data['others'];
    @endphp
    data.table = {
      label: "Total Student R Analysis",
      totalStudentR: {{ $data['allStudents'] }},
      totalConvert: {{ $data['totalConvert'] }},
      balanceStudent: {{ $data['allStudents'] - $data['totalConvert'] }},
      studentActive: {{ $data['registered'] }},
      studentRejected: {{ $data['rejected'] }},
      studentOffered: {{ $data['offered'] }},
      studentKIV: {{ $data['KIV'] }},
      studentOthers: {{ $data['others'] }}
    };
  @endif
  
  return data;
}

// Helper function to collect date ranges (should be available from parent page)
function collectDateRanges() {
  const ranges = [];
  const tableCount = parseInt($('#table_count').val()) || 0;
  
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
</script>