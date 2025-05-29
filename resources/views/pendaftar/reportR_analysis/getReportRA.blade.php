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
                <th style="width: 15%">Active before End Date</th>
                <th style="width: 15%">Active after End Date</th>
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
            <td>{{ $data['registered_before_offer'][$key] }}</td>
            <td>{{ $data['registered_after_offer'][$key] }}</td>
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
                <th style="width: 15%">Active before End Date</th>
                <th style="width: 15%">Active after End Date</th>
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
            $total_registered_before_offer = array_sum($data['registered_before_offer']);
            $total_registered_after_offer = array_sum($data['registered_after_offer']);
            $total_registered = array_sum($data['registered']);
            $total_rejected = array_sum($data['rejected']);
            $total_offered = array_sum($data['offered']);
            $total_kiv = array_sum($data['KIV']);
            $total_others = array_sum($data['others']);
            $grand_total = $total_convert + $total_registered + $total_rejected + $total_offered + $total_kiv + $total_others;
            @endphp
            <td>{{ $total_all }}</td>
            <td>{{ $total_convert }}</td>
            <td>{{ $total_registered_before_offer }}</td>
            <td>{{ $total_registered_after_offer }}</td>
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
                <th style="width: 15%">Active before End Date</th>
                <th style="width: 15%">Active after End Date</th>
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
            // Ensure $data is an array before accessing keys
            if (!is_array($data)) {
                $data = [];
            }
            $total_all = ($data['allStudents'] ?? 0) + ($data['totalConvert'] ?? 0) + ($data['registered'] ?? 0) + ($data['rejected'] ?? 0) + ($data['offered'] ?? 0) + ($data['KIV'] ?? 0) + ($data['others'] ?? 0);
            @endphp
            <td>{{ $data['allStudents'] ?? 0 }}</td>
            <td>{{ $data['totalConvert'] ?? 0 }}</td>
            <td>{{ $data['registered_before_offer'] ?? 0 }}</td>
            <td>{{ $data['registered_after_offer'] ?? 0 }}</td>
            <td>{{ ($data['allStudents'] ?? 0) - ($data['totalConvert'] ?? 0) }}</td>
            <td>{{ $data['registered'] ?? 0 }}</td>
            <td>{{ $data['rejected'] ?? 0 }}</td>
            <td>{{ $data['offered'] ?? 0 }}</td>
            <td>{{ $data['KIV'] ?? 0 }}</td>
            <td>{{ $data['others'] ?? 0 }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endif

@if(isset($data['monthlyComparison']) && !empty($data['monthlyComparison']['monthly_data']))
<!-- Date Range Filters for Monthly Comparison -->
<div class="card mb-3" id="date_filters_section">
  <div class="card-header">
    <b>Date Range Filters</b>
    <small class="text-muted ml-2">Add custom date range filters for specific periods</small>
  </div>
  <div class="card-body">
    @foreach($data['monthlyComparison']['years'] as $year)
    <div class="row mb-3">
      <div class="col-md-12">
        <h6 class="text-primary">Year {{ $year }} Filters</h6>
        <div id="filters_container_{{ $year }}" class="border rounded p-3 bg-light">
          <!-- Dynamic filters will be added here -->
        </div>
        <div class="row mt-2">
          <div class="col-md-2">
            <select class="form-control form-control-sm" id="filter_type_{{ $year }}">
              <option value="">Select Filter Type</option>
              <option value="BEFORE RESULT SPM">BEFORE RESULT SPM</option>
              <option value="AFTER RESULT SPM">AFTER RESULT SPM</option>
              <option value="AFTER RESULT UPU">AFTER RESULT UPU</option>
              <option value="AFTER UPU APPEAL">AFTER UPU APPEAL</option>
            </select>
          </div>
          <div class="col-md-2">
            <input type="date" class="form-control form-control-sm" id="filter_from_{{ $year }}" placeholder="From Date">
          </div>
          <div class="col-md-2">
            <input type="date" class="form-control form-control-sm" id="filter_to_{{ $year }}" placeholder="To Date">
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-primary btn-sm" onclick="addFilter({{ $year }})">
              <i class="fa fa-plus"></i> Add Filter
            </button>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- Monthly Comparison Table -->
<style>
#monthly_comparison_table table {
  border: 2px solid black !important;
}

#monthly_comparison_table th {
  border: 1px solid black !important;
}

#monthly_comparison_table td {
  border: 1px solid black !important;
}

#monthly_comparison_table .table-bordered {
  border: 2px solid black !important;
}

#monthly_comparison_table .table-bordered th,
#monthly_comparison_table .table-bordered td {
  border: 1px solid black !important;
}
</style>

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
      <table class="table table-striped table-bordered table-sm" id="monthly_comparison_main_table" style="border: 1px solid black;">
        <thead class="thead-light" id="table_header">
          <tr id="year_header_row">
            <th rowspan="2" style="vertical-align: middle; width: 80px; position: sticky; left: 0; background: #f8f9fa; z-index: 10; border: 1px solid black;">Month</th>
            <th rowspan="2" style="vertical-align: middle; width: 120px; position: sticky; left: 80px; background: #f8f9fa; z-index: 10; border: 1px solid black;">Week</th>
            @foreach($data['monthlyComparison']['years'] as $year)
              <th colspan="9" class="text-center bg-light year-header-{{ $year }}" style="border: 1px solid black;" data-year="{{ $year }}">Year {{ $year }}</th>
            @endforeach
          </tr>
          <tr id="column_header_row">
            @foreach($data['monthlyComparison']['years'] as $year)
              <th style="width: 120px; border: 1px solid black; font-size: 11px;">Range</th>
              <th style="width: 80px; border: 1px solid black;">Total Student R By Weeks</th>
              <th style="width: 80px; border: 1px solid black;">Total Student R By Converts</th>
              <th style="width: 80px; border: 1px solid black;">Balance Student R</th>
              <th style="width: 80px; border: 1px solid black;">Student Active</th>
              <th style="width: 80px; border: 1px solid black;">Student Rejected</th>
              <th style="width: 80px; border: 1px solid black;">Student Offered</th>
              <th style="width: 80px; border: 1px solid black;">Student KIV</th>
              <th style="width: 80px; border: 1px solid black;">Student Others</th>
            @endforeach
          </tr>
        </thead>
        <tbody id="table_body">
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
            
            // Initialize totals array for each year
            $yearTotals = [];
            foreach($data['monthlyComparison']['years'] as $year) {
              $yearTotals[$year] = [
                'total_by_weeks' => 0,
                'total_by_converts' => 0,
                'balance_student' => 0,
                'total_active' => 0,
                'total_rejected' => 0,
                'total_offered' => 0,
                'total_kiv' => 0,
                'total_others' => 0
              ];
            }
          @endphp
          
          @if(empty($monthsWithData))
            <tr>
              <td colspan="{{ 2 + (count($data['monthlyComparison']['years']) * 9) }}" class="text-center text-muted py-4" style="border: 1px solid black;">
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
                      <td rowspan="{{ $maxWeeks }}" style="vertical-align: middle; font-weight: bold; position: sticky; left: 0; background: #fff; z-index: 5; border: 1px solid black;">
                        {{ $monthName }}
                      </td>
                    @endif
                    
                    <td class="text-center" style="position: sticky; left: 80px; background: #fff; font-size: 12px; z-index: 5; padding: 8px 4px; border: 1px solid black;">
                      <div style="line-height: 1.2;">
                        <strong>Week {{ $weekNum }}</strong>
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
                        @php
                          // Add to year totals
                          $yearTotals[$year]['total_by_weeks'] += $weekData['total_by_weeks'];
                          $yearTotals[$year]['total_by_converts'] += $weekData['total_by_converts'];
                          $yearTotals[$year]['balance_student'] += $weekData['balance_student'];
                          $yearTotals[$year]['total_active'] += $weekData['total_active'];
                          $yearTotals[$year]['total_rejected'] += $weekData['total_rejected'];
                          $yearTotals[$year]['total_offered'] += $weekData['total_offered'];
                          $yearTotals[$year]['total_kiv'] += $weekData['total_kiv'];
                          $yearTotals[$year]['total_others'] += $weekData['total_others'];
                        @endphp
                        <td class="text-center" style="border: 1px solid black; font-size: 10px; padding: 4px;">
                          <small class="text-muted">{{ $weekData['date_range'] }}</small>
                        </td>
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($weekData['total_by_weeks']) }}</td>
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($weekData['total_by_converts']) }}</td>
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($weekData['balance_student']) }}</td>
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($weekData['total_active']) }}</td>
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($weekData['total_rejected']) }}</td>
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($weekData['total_offered']) }}</td>
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($weekData['total_kiv']) }}</td>
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($weekData['total_others']) }}</td>
                      @else
                        @php
                          // Generate date range even when no data exists
                          $monthStart = \Carbon\Carbon::createFromDate($year, $monthNumber, 1)->startOfMonth();
                          $monthEnd = \Carbon\Carbon::createFromDate($year, $monthNumber, 1)->endOfMonth();
                          
                          // Calculate week start and end for this specific week number
                          $start = $monthStart->copy();
                          for ($i = 1; $i < $weekNum; $i++) {
                            $weekStart = $start->copy();
                            $daysUntilSaturday = (6 - $weekStart->dayOfWeek) % 7;
                            $weekEnd = $weekStart->copy()->addDays($daysUntilSaturday);
                            if ($weekEnd->gt($monthEnd)) {
                              $weekEnd = $monthEnd->copy();
                            }
                            $start = $weekEnd->copy()->addDay();
                          }
                          
                          // Calculate current week range
                          $weekStart = $start->copy();
                          $daysUntilSaturday = (6 - $weekStart->dayOfWeek) % 7;
                          $weekEnd = $weekStart->copy()->addDays($daysUntilSaturday);
                          if ($weekEnd->gt($monthEnd)) {
                            $weekEnd = $monthEnd->copy();
                          }
                          
                          $dateRange = $weekStart->format('j M Y') . ' - ' . $weekEnd->format('j M Y');
                        @endphp
                        <td class="text-center" style="border: 1px solid black; font-size: 10px; padding: 4px;">
                          <small class="text-muted">{{ $dateRange }}</small>
                        </td>
                        <td class="text-center" style="border: 1px solid black;">0</td>
                        <td class="text-center" style="border: 1px solid black;">0</td>
                        <td class="text-center" style="border: 1px solid black;">0</td>
                        <td class="text-center" style="border: 1px solid black;">0</td>
                        <td class="text-center" style="border: 1px solid black;">0</td>
                        <td class="text-center" style="border: 1px solid black;">0</td>
                        <td class="text-center" style="border: 1px solid black;">0</td>
                        <td class="text-center" style="border: 1px solid black;">0</td>
                      @endif
                    @endforeach
                  </tr>
                @endfor
              @endif
            @endforeach
          @endif
        </tbody>
        <tfoot style="background-color: #f8f9fa; font-weight: bold;">
          <tr>
            <td style="border: 1px solid black; text-align: center; font-weight: bold;">TOTAL</td>
            <td style="border: 1px solid black; text-align: center; font-weight: bold;">All Weeks</td>
            @foreach($data['monthlyComparison']['years'] as $year)
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef; font-size: 10px;">All Ranges</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($yearTotals[$year]['total_by_weeks']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($yearTotals[$year]['total_by_converts']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($yearTotals[$year]['balance_student']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($yearTotals[$year]['total_active']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($yearTotals[$year]['total_rejected']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($yearTotals[$year]['total_offered']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($yearTotals[$year]['total_kiv']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($yearTotals[$year]['total_others']) }}</td>
            @endforeach
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endif

<script>
$(document).ready(function() {
  // Initialize tooltips
  $('[data-toggle="tooltip"]').tooltip();
  
  // Initialize global filter data storage
  window.filterData = {};
  window.activeFilters = {};
  @foreach($data['monthlyComparison']['years'] as $year)
    window.activeFilters[{{ $year }}] = [];
    updateFiltersDisplay({{ $year }});
  @endforeach
  
  console.log('Filter system initialized for years:', @json($data['monthlyComparison']['years']));
  console.log('Active filters initialized:', window.activeFilters);
  
  // Add test function for debugging
  window.debugTable = function() {
    console.log('=== TABLE DEBUG INFO ===');
    console.log('Header row children count:', $('#column_header_row').children().length);
    console.log('Year header row children count:', $('#year_header_row').children().length);
    console.log('First data row children count:', $('#table_body tr').first().children().length);
    console.log('Footer row children count:', $('tfoot tr').children().length);
    
    console.log('Active filters:', window.activeFilters);
    console.log('Filter data:', window.filterData);
    
    // Log header structure
    console.log('=== COLUMN HEADER STRUCTURE ===');
    $('#column_header_row').children().each(function(index) {
      const text = $(this).text().trim();
      const classes = $(this).attr('class') || '';
      console.log(`Header ${index}: "${text}" (classes: ${classes})`);
    });
    
    // Log year header structure
    console.log('=== YEAR HEADER STRUCTURE ===');
    $('#year_header_row').children().each(function(index) {
      const text = $(this).text().trim();
      const colspan = $(this).attr('colspan') || '1';
      const year = $(this).data('year') || 'N/A';
      console.log(`Year Header ${index}: "${text}" (colspan: ${colspan}, year: ${year})`);
    });
    
    // Log filter counts by year
    console.log('=== FILTER COUNTS BY YEAR ===');
    const yearHeaders = @json($data['monthlyComparison']['years']);
    yearHeaders.forEach(year => {
      const filterCount = window.activeFilters[year] ? window.activeFilters[year].length : 0;
      const expectedColspan = 9 + filterCount;
      const actualColspan = $(`[data-year="${year}"]`).attr('colspan');
      console.log(`Year ${year}: ${filterCount} filters, expected colspan: ${expectedColspan}, actual colspan: ${actualColspan}`);
    });
  };
  
  // Call debug function initially
  window.debugTable();
  
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
    $total_all = ($data['allStudents'] ?? 0) + ($data['totalConvert'] ?? 0) + ($data['registered'] ?? 0) + ($data['rejected'] ?? 0) + ($data['offered'] ?? 0) + ($data['KIV'] ?? 0) + ($data['others'] ?? 0);
    @endphp
    data.table = {
      label: "Total Student R Analysis",
      totalStudentR: {{ $data['allStudents'] ?? 0 }},
      totalConvert: {{ $data['totalConvert'] ?? 0 }},
      balanceStudent: {{ ($data['allStudents'] ?? 0) - ($data['totalConvert'] ?? 0) }},
      studentActive: {{ $data['registered'] ?? 0 }},
      studentRejected: {{ $data['rejected'] ?? 0 }},
      studentOffered: {{ $data['offered'] ?? 0 }},
      studentKIV: {{ $data['KIV'] ?? 0 }},
      studentOthers: {{ $data['others'] ?? 0 }}
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

// Completely rewrite addFilter with bulletproof approach
function addFilter(year) {
  const filterType = $(`#filter_type_${year}`).val();
  const fromDate = $(`#filter_from_${year}`).val();
  const toDate = $(`#filter_to_${year}`).val();
  
  // Validation
  if (!filterType || !fromDate || !toDate) {
    alert('Please fill all filter fields');
    return;
  }
  
  if (fromDate > toDate) {
    alert('From date cannot be later than to date');
    return;
  }
  
  // Check if filter type already exists
  const existingFilter = window.activeFilters[year].find(f => f.type === filterType);
  if (existingFilter) {
    alert(`Filter type "${filterType}" already exists for year ${year}`);
    return;
  }
  
  // Create filter object
  const filter = {
    id: Date.now(),
    type: filterType,
    from: fromDate,
    to: toDate,
    year: year
  };
  
  // Add to active filters
  window.activeFilters[year].push(filter);
  
  // Update UI
  updateFiltersDisplay(year);
  
  // Clear input fields
  $(`#filter_type_${year}`).val('');
  $(`#filter_from_${year}`).val('');
  $(`#filter_to_${year}`).val('');
  
  // Fetch data for this filter
  fetchFilterData(filter);
}

// Fetch filter data
function fetchFilterData(filter) {
  $.ajax({
    url: "{{ url('pendaftar/student/reportRA/getFilteredData') }}",
    method: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      year: filter.year,
      from_date: filter.from,
      to_date: filter.to,
      filter_type: filter.type
    },
    success: function(response) {
      console.log('Filter data received:', response);
      
      if (response.success && response.data && response.data.weekly_data) {
        // Store filter data globally
        window.filterData[filter.id] = response.data.weekly_data;
        
        // Add filter column to table
        addFilterColumnToTable(filter);
        
        // Populate filter data in table
        populateFilterData(filter);
        
      } else {
        alert('Error: No data received for filter');
        console.error('Invalid response:', response);
      }
    },
    error: function(xhr, status, error) {
      alert('Error fetching filter data: ' + error);
      console.error('AJAX error:', xhr.responseText);
    }
  });
}

// Add filter column to table header and update colspan
function addFilterColumnToTable(filter) {
  console.log('Adding filter column for:', filter.type, 'year:', filter.year);
  
  const columnHeaderRow = $('#column_header_row');
  const yearHeaders = @json($data['monthlyComparison']['years']);
  const yearIndex = yearHeaders.indexOf(filter.year);
  
  console.log('Year index:', yearIndex, 'Year headers:', yearHeaders);
  console.log('Current column headers count:', columnHeaderRow.children().length);
  
  // Validate year index
  if (yearIndex === -1) {
    console.error('Year not found in headers:', filter.year);
    return;
  }
  
  // Debug: Show current table structure
  console.log('=== CURRENT TABLE STRUCTURE ===');
  $('#column_header_row').children().each(function(index) {
    const text = $(this).text().trim();
    const classes = $(this).attr('class') || '';
    console.log(`Position ${index}: "${text}" (classes: ${classes})`);
  });
  
  // Create the filter header
  const filterHeader = `
    <th class="filter-header filter-${filter.id}" style="width: 80px; border: 1px solid black; background-color: #e3f2fd; font-size: 11px; text-align: center; color: #000;">
      <div style="padding: 2px; word-wrap: break-word;">${filter.type}</div>
    </th>
  `;
  
  // Calculate the exact position to insert the filter column
  // Start after Week column (Month=0, Week=1)
  let insertAfterIndex = 1;
  
  // Add columns for previous years (including their base columns AND existing filters)
  for (let i = 0; i < yearIndex; i++) {
    const prevYear = yearHeaders[i];
    const prevYearFilterCount = window.activeFilters[prevYear] ? window.activeFilters[prevYear].length : 0;
    insertAfterIndex += 9 + prevYearFilterCount; // 9 base columns + filters for previous year
    console.log(`Added ${9 + prevYearFilterCount} columns for previous year ${prevYear}`);
  }
  
  // Add base columns for current year (9 columns: Range through Student Others)
  insertAfterIndex += 9;
  console.log(`Added 9 base columns for current year ${filter.year}`);
  
  // Add existing filters for current year (this positions us at the end of current year's section)
  const existingFiltersForYear = window.activeFilters[filter.year].length - 1;
  insertAfterIndex += existingFiltersForYear;
  console.log(`Added ${existingFiltersForYear} existing filters for current year`);
  
  // For header insertion, we want to insert after the last column of the current year
  let headerInsertIndex = insertAfterIndex - 2;
  
  console.log('Calculated header insert position:', headerInsertIndex);
  console.log('Calculated data row insert position:', insertAfterIndex);
  console.log('This should be after the last column of year', filter.year);
  
  // Show what column we're inserting after
  const headerCells = columnHeaderRow.children();
  if (headerInsertIndex >= 0 && headerInsertIndex < headerCells.length) {
    const cellAfter = $(headerCells[headerInsertIndex]).text().trim();
    console.log(`Will insert header AFTER column: "${cellAfter}" at position ${headerInsertIndex}`);
  } else {
    console.log('Will append header at the end');
  }
  
  console.log('Header cells count:', headerCells.length);
  
  if (headerInsertIndex >= 0 && headerInsertIndex < headerCells.length) {
    $(headerCells[headerInsertIndex]).after(filterHeader);
    console.log('Inserted header AFTER position', headerInsertIndex);
  } else {
    console.warn('Insert position out of bounds, appending at end');
    columnHeaderRow.append(filterHeader);
  }
  
  // Update year header colspan
  const filterCount = window.activeFilters[filter.year].length;
  const yearHeaderSelector = `[data-year="${filter.year}"]`;
  const yearHeaderElement = $(yearHeaderSelector);
  
  console.log('Year header element found:', yearHeaderElement.length);
  console.log('Setting colspan to:', 9 + filterCount);
  
  yearHeaderElement.attr('colspan', 9 + filterCount);
  
  // Add cells to all data rows at the calculated position
  $('#table_body tr').each(function(rowIndex) {
    const row = $(this);
    const emptyCell = `<td class="text-center filter-data filter-${filter.id}" 
                           style="border: 1px solid black; background-color: #f0f8ff; min-width: 80px;" 
                           data-filter-id="${filter.id}" data-row="${rowIndex}">
                        <span style="color: #666;">-</span>
                      </td>`;
    
    // Check if this row has the Month cell (first row of each month group)
    // Rows with Month cell have one more cell than rows without it
    const hasMonthCell = row.children().first().attr('rowspan') !== undefined;
    
    // Adjust insertion index for rows without Month cell
    let rowInsertIndex = insertAfterIndex;
    if (!hasMonthCell) {
      rowInsertIndex = insertAfterIndex - 1; // Subtract 1 because no Month cell
    }
    
    console.log(`Row ${rowIndex}: hasMonthCell=${hasMonthCell}, using insertion index=${rowInsertIndex}`);
    
    // Insert at the calculated position in data rows
    const rowCells = row.children();
    if (rowInsertIndex >= 0 && rowInsertIndex < rowCells.length) {
      $(rowCells[rowInsertIndex]).after(emptyCell);
    } else {
      console.warn(`Row ${rowIndex}: Insert position ${rowInsertIndex} out of bounds (${rowCells.length} cells), appending at end`);
      row.append(emptyCell);
    }
  });
  
  // Add footer cell at the calculated position
  const footerRow = $('tfoot tr');
  const footerCell = `<td class="text-center filter-footer-total filter-footer-${filter.year}" 
                          style="border: 1px solid black; background-color: #e9ecef; font-weight: bold;"
                          data-filter-id="${filter.id}">0</td>`;
  
  const footerCells = footerRow.children();
  if (headerInsertIndex >= 0 && headerInsertIndex < footerCells.length) {
    $(footerCells[headerInsertIndex]).after(footerCell);
  } else {
    footerRow.append(footerCell);
  }
  
  console.log('Added', $('#table_body tr').length, 'data cells and 1 footer cell AFTER position', headerInsertIndex);
  
  // Debug: Show final table structure
  console.log('=== FINAL TABLE STRUCTURE ===');
  $('#column_header_row').children().each(function(index) {
    const text = $(this).text().trim();
    const classes = $(this).attr('class') || '';
    console.log(`Position ${index}: "${text}" (classes: ${classes})`);
  });
}

// Populate filter data in the table
function populateFilterData(filter) {
  const weeklyData = window.filterData[filter.id];
  let filterTotal = 0;
  
  console.log('=== POPULATING FILTER DATA ===');
  console.log('Filter:', filter.type, 'ID:', filter.id);
  console.log('Weekly data:', weeklyData);
  
  if (!weeklyData) {
    console.error('No weekly data found for filter', filter.id);
    return;
  }
  
  // Track current month and week number
  let currentMonth = null;
  let currentMonthNumber = 0;
  let currentWeekInMonth = 0;
  let rowsProcessed = 0;
  
  const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                     'July', 'August', 'September', 'October', 'November', 'December'];
  
  $('#table_body tr').each(function(rowIndex) {
    const row = $(this);
    const monthCell = row.children().eq(0);
    const weekCell = row.children().eq(1);
    
    if (monthCell.length && weekCell.length) {
      // Check if this row has a month name (first row of month group)
      const monthText = monthCell.text().trim();
      
      // Only update current month if this cell actually contains a month name
      if (monthText && monthText !== '' && monthText !== 'TOTAL' && monthNames.includes(monthText)) {
        currentMonth = monthText;
        currentMonthNumber = monthNames.indexOf(currentMonth) + 1;
        currentWeekInMonth = 1; // Reset to week 1 for new month
        console.log('Found new month:', currentMonth, 'number:', currentMonthNumber);
      } else if (currentMonthNumber > 0) {
        // This is a continuation row for the current month, increment week number
        currentWeekInMonth++;
      }
      
      // Process the week data if we have a valid month and week
      if (currentMonthNumber > 0 && currentWeekInMonth > 0) {
        const weekKey = `${currentMonthNumber}_${currentWeekInMonth}`;
        const count = weeklyData[weekKey] || 0;
        
        console.log(`Row ${rowIndex}: ${currentMonth} Week ${currentWeekInMonth} (${weekKey}) = ${count}`);
        
        if (count > 0) {
          filterTotal += count;
        }
        
        // Find the specific filter cell for this row using multiple selectors
        let filterCell = row.find(`.filter-${filter.id}`);
        
        if (filterCell.length === 0) {
          // Try alternative selector
          filterCell = row.find(`[data-filter-id="${filter.id}"]`);
        }
        
        if (filterCell.length > 0) {
          console.log(`Updating cell for ${weekKey}: ${count} (found ${filterCell.length} cells)`);
          filterCell.html(count);
          
          if (count > 0) {
            filterCell.css({
              'font-weight': 'bold',
              'background-color': '#e8f5e8',
              'color': '#2e7d32'
            });
          } else {
            filterCell.css({
              'font-weight': 'normal',
              'background-color': '#f0f8ff',
              'color': '#666'
            });
          }
          rowsProcessed++;
        } else {
          console.warn(`No filter cell found for row ${rowIndex}, week ${weekKey}`);
          console.warn(`Row has ${row.children().length} cells. Looking for filter-${filter.id}`);
          
          // Debug: Show all cells in this row
          row.children().each(function(cellIndex) {
            const cellText = $(this).text().trim();
            const cellClasses = $(this).attr('class') || '';
            const cellDataId = $(this).data('filter-id') || 'none';
            console.log(`  Cell ${cellIndex}: "${cellText}" (classes: ${cellClasses}, data-filter-id: ${cellDataId})`);
          });
        }
      } else {
        console.log(`Row ${rowIndex}: Skipping - currentMonthNumber: ${currentMonthNumber}, currentWeekInMonth: ${currentWeekInMonth}`);
      }
    }
  });
  
  console.log(`Processed ${rowsProcessed} rows`);
  console.log(`Filter total for ${filter.type}:`, filterTotal);
  
  // Update footer total
  let footerFilterCell = $('tfoot tr').find(`.filter-footer-${filter.year}[data-filter-id="${filter.id}"]`);
  
  if (footerFilterCell.length === 0) {
    // Try alternative selector
    footerFilterCell = $('tfoot tr').find(`.filter-${filter.id}`);
  }
  
  if (footerFilterCell.length > 0) {
    footerFilterCell.text(filterTotal);
    console.log('Updated footer total to:', filterTotal);
  } else {
    console.warn('Footer cell not found for filter', filter.id);
  }
}

// Update filters display
function updateFiltersDisplay(year) {
  const container = $(`#filters_container_${year}`);
  container.empty();
  
  if (window.activeFilters[year].length === 0) {
    container.html('<p class="text-muted mb-0">No filters added yet</p>');
    return;
  }
  
  window.activeFilters[year].forEach(filter => {
    const filterHtml = `
      <div class="badge badge-info mr-2 mb-2" style="font-size: 12px; padding: 8px;">
        <strong>${filter.type}</strong><br>
        <small>${filter.from} to ${filter.to}</small>
        <button type="button" class="btn btn-sm btn-link text-white p-0 ml-2" onclick="removeFilter(${year}, ${filter.id})" title="Remove filter">
          <i class="fa fa-times"></i>
        </button>
      </div>
    `;
    container.append(filterHtml);
  });
}

// Remove filter function
function removeFilter(year, filterId) {
  console.log('Removing filter:', filterId, 'for year:', year);
  
  // Remove filter column from table
  $(`.filter-${filterId}`).remove();
  
  // Remove from active filters
  window.activeFilters[year] = window.activeFilters[year].filter(f => f.id !== filterId);
  
  // Remove from filter data
  delete window.filterData[filterId];
  
  // Update year header colspan
  const filterCount = window.activeFilters[year].length;
  const yearHeaderSelector = `[data-year="${year}"]`;
  $(yearHeaderSelector).attr('colspan', 9 + filterCount);
  
  console.log('Updated colspan for year', year, 'to:', 9 + filterCount, 'after removing filter');
  
  // Update UI
  updateFiltersDisplay(year);
}

// Legacy function - keeping for compatibility but replacing with new implementation
function rebuildTableWithFilters() {
  console.log('rebuildTableWithFilters called - using new implementation');
  // This function is now handled by addFilterColumnToTable and populateFilterData
  // Keeping empty for backward compatibility
}

// Update table columns - simplified version
function updateTableColumns() {
  // Update column spans in header for all years
  @foreach($data['monthlyComparison']['years'] as $year)
    const year{{ $year }}FilterCount = window.activeFilters[{{ $year }}].length;
    const year{{ $year }}Colspan = 9 + year{{ $year }}FilterCount;
    $(`[data-year="${$year}"]`).attr('colspan', year{{ $year }}Colspan);
  @endforeach
}

// Update footer totals - simplified version
function updateFooterTotals() {
  // Footer totals are now updated individually in populateFilterData
  console.log('Footer totals updated');
}
</script>