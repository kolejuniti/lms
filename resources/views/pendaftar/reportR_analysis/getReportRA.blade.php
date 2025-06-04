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
              <option value="ACTIVE NEXT YEAR">ACTIVE NEXT YEAR</option>
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

/* Sticky Header Styles */
#monthly_comparison_main_table thead th {
  position: sticky !important;
  top: 0 !important;
  background: #f8f9fa !important;
  z-index: 20 !important;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Ensure year header row is sticky */
#year_header_row th {
  position: sticky !important;
  top: 0 !important;
  background: #f8f9fa !important;
  z-index: 22 !important;
}

/* Ensure column header row is sticky and positioned below year header */
#column_header_row th {
  position: sticky !important;
  top: 42px !important; /* Adjust based on year header height */
  background: #f8f9fa !important;
  z-index: 21 !important;
}

/* Special handling for sticky Month and Week columns in header */
#year_header_row th:first-child,
#year_header_row th:nth-child(2) {
  position: sticky !important;
  top: 0 !important;
  left: 0 !important;
  background: #f8f9fa !important;
  z-index: 25 !important;
}

#year_header_row th:nth-child(2) {
  position: sticky !important;
  top: 0 !important;
  left: 80px !important;
  background: #f8f9fa !important;
  z-index: 25 !important;
}

/* Month cell styling for visual grouping */
.month-cell {
  position: sticky !important;
  left: 0 !important;
  background: #fff !important;
  z-index: 15 !important;
  vertical-align: middle !important;
  font-weight: bold !important;
  border: 1px solid black !important;
}

/* First week of month - show month name */
.month-first {
  border-top: 1px solid black !important;
}

.month-visible {
  display: inline !important;
  color: #000 !important;
  font-weight: bold !important;
}

/* Continuation weeks - hide month name but keep cell structure */
.month-continuation {
  border-top: none !important;
  position: relative;
}

.month-hidden {
  display: none !important;
}

/* Add a subtle visual indicator for month grouping on continuation cells */
.month-continuation::before {
  content: '';
  position: absolute;
  left: 50%;
  top: 0;
  bottom: 0;
  width: 1px;
  background-color: #e0e0e0;
  transform: translateX(-50%);
}

/* Ensure data cells maintain their sticky positioning with consistent z-index */
#monthly_comparison_main_table tbody td:first-child {
  position: sticky !important;
  left: 0 !important;
  background: #fff !important;
  z-index: 15 !important;
}

#monthly_comparison_main_table tbody td:nth-child(2) {
  position: sticky !important;
  left: 80px !important;
  background: #fff !important;
  z-index: 15 !important;
}

/* Footer sticky positioning */
#monthly_comparison_main_table tfoot td:first-child {
  position: sticky !important;
  left: 0 !important;
  background: #f8f9fa !important;
  z-index: 16 !important;
}

#monthly_comparison_main_table tfoot td:nth-child(2) {
  position: sticky !important;
  left: 80px !important;
  background: #f8f9fa !important;
  z-index: 16 !important;
}

/* Ensure the table container allows for proper scrolling */
#monthly_comparison_table .table-responsive {
  max-height: 80vh;
  overflow-y: auto;
  border: 1px solid #dee2e6;
}

/* Add some visual separation for the sticky headers */
#monthly_comparison_main_table thead th {
  border-bottom: 2px solid #333 !important;
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
            <th rowspan="2" style="vertical-align: middle; width: 80px; position: sticky; left: 0; background: #f8f9fa; z-index: 25; border: 1px solid black;">Month</th>
            <th rowspan="2" style="vertical-align: middle; width: 120px; position: sticky; left: 80px; background: #f8f9fa; z-index: 25; border: 1px solid black;">Week</th>
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
            
            // Get date range information from data if available
            $fromMonth = null;
            $toMonth = null;
            if (isset($data['dateRange'])) {
                $fromMonth = $data['dateRange']['from_month'];
                $toMonth = $data['dateRange']['to_month'];
            }
            
            // Collect all months that have data across all years
            foreach($data['monthlyComparison']['years'] as $year) {
              if (isset($data['monthlyComparison']['monthly_data'][$year])) {
                foreach($data['monthlyComparison']['monthly_data'][$year] as $monthNum => $monthData) {
                  if (!empty($monthData['weeks'])) {
                    // Filter based on date range if available
                    if ($fromMonth !== null && $toMonth !== null) {
                        // Check if month falls within the selected range
                        if ($fromMonth <= $toMonth) {
                            // Normal range (e.g., April to June)
                            if ($monthNum >= $fromMonth && $monthNum <= $toMonth) {
                                if (!in_array($monthNum, $monthsWithData)) {
                                    $monthsWithData[] = $monthNum;
                                }
                            }
                        } else {
                            // Cross-year range (e.g., November to February)
                            if ($monthNum >= $fromMonth || $monthNum <= $toMonth) {
                                if (!in_array($monthNum, $monthsWithData)) {
                                    $monthsWithData[] = $monthNum;
                                }
                            }
                        }
                    } else {
                        // Fallback to original logic if no date range available
                        if (!in_array($monthNum, $monthsWithData)) {
                            $monthsWithData[] = $monthNum;
                        }
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
                    <td class="month-cell {{ $weekNum == 1 ? 'month-first' : 'month-continuation' }}" 
                        style="position: sticky; left: 0; background: #fff; z-index: 15; border: 1px solid black; vertical-align: middle; font-weight: bold;" 
                        data-month="{{ $monthName }}">
                      <span class="{{ $weekNum == 1 ? 'month-visible' : 'month-hidden' }}">{{ $monthName }}</span>
                    </td>
                    
                    <td class="text-center" style="position: sticky; left: 80px; background: #fff; font-size: 12px; z-index: 15; padding: 8px 4px; border: 1px solid black;">
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
  @if(isset($data['monthlyComparison']['years']))
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
  @else
    console.log('No monthly comparison data available');
  @endif
  
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
  const fromDate = $('#from_date').val();
  const toDate = $('#to_date').val();
  const selectedYears = [];
  const reportType = $('input[name="report_type"]:checked').val();

  // Collect selected years
  $('.year-checkbox:checked').each(function() {
    selectedYears.push($(this).val());
  });

  // Collect active filter data for export
  const activeFiltersData = {};
  const filterDataForExport = {};
  
  // Collect all active filters and their data (only if data exists)
  @if(isset($data['monthlyComparison']['years']))
    @foreach($data['monthlyComparison']['years'] as $year)
      if (window.activeFilters && window.activeFilters[{{ $year }}]) {
        activeFiltersData[{{ $year }}] = window.activeFilters[{{ $year }}].map(filter => ({
          id: filter.id,
          type: filter.type,
          from: filter.from,
          to: filter.to,
          year: filter.year
        }));
        
        // Collect the actual filter data
        window.activeFilters[{{ $year }}].forEach(filter => {
          if (window.filterData && window.filterData[filter.id]) {
            filterDataForExport[filter.id] = window.filterData[filter.id];
          }
        });
      }
    @endforeach
  @endif
  
  console.log('Exporting with filters:', activeFiltersData);
  console.log('Filter data:', filterDataForExport);
  
  if (fromDate && toDate && selectedYears.length > 0) {
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
    
    // Add filter data for export
    var activeFiltersInput = document.createElement('input');
    activeFiltersInput.type = 'hidden';
    activeFiltersInput.name = 'active_filters';
    activeFiltersInput.value = JSON.stringify(activeFiltersData);
    form.appendChild(activeFiltersInput);
    
    var filterDataInput = document.createElement('input');
    filterDataInput.type = 'hidden';
    filterDataInput.name = 'filter_data';
    filterDataInput.value = JSON.stringify(filterDataForExport);
    form.appendChild(filterDataInput);
    
    // Add main table date range for proper filter context
    var mainFromDateInput = document.createElement('input');
    mainFromDateInput.type = 'hidden';
    mainFromDateInput.name = 'main_from_date';
    mainFromDateInput.value = fromDate;
    form.appendChild(mainFromDateInput);
    
    var mainToDateInput = document.createElement('input');
    mainToDateInput.type = 'hidden';
    mainToDateInput.name = 'main_to_date';
    mainToDateInput.value = toDate;
    form.appendChild(mainToDateInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  } else {
    // Use original single range method with filter data
    var from = $('#from').val();
    var to = $('#to').val();
    var url = "{{ url('pendaftar/student/reportRA/getStudentReportRA') }}";
    
    // Build URL with filter data
    const params = new URLSearchParams({
      excel: 'true',
      from: from,
      to: to,
      active_filters: JSON.stringify(activeFiltersData),
      filter_data: JSON.stringify(filterDataForExport),
      main_from_date: from,
      main_to_date: to
    });

    window.location.href = `${url}?${params.toString()}`;
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

// Helper function to collect date ranges (updated for year-based approach)
function collectDateRanges() {
  const ranges = [];
  const fromDate = $('#from_date').val();
  const toDate = $('#to_date').val();
  const selectedYears = [];

  // Collect selected years
  $('.year-checkbox:checked').each(function() {
    selectedYears.push($(this).val());
  });

  if (fromDate && toDate && selectedYears.length > 0) {
    // Extract day and month from the provided dates
    const fromCarbon = new Date(fromDate);
    const toCarbon = new Date(toDate);
    
    const fromMonth = fromCarbon.getMonth() + 1; // getMonth() returns 0-11
    const fromDay = fromCarbon.getDate();
    const toMonth = toCarbon.getMonth() + 1;
    const toDay = toCarbon.getDate();
    
    selectedYears.forEach((year, index) => {
      // Create date range for this year using the same day/month
      const yearFromDate = `${year}-${String(fromMonth).padStart(2, '0')}-${String(fromDay).padStart(2, '0')}`;
      const yearToDate = `${year}-${String(toMonth).padStart(2, '0')}-${String(toDay).padStart(2, '0')}`;
      
      ranges.push({
        table: index + 1,
        from: yearFromDate,
        to: yearToDate
      });
    });
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
  // Get the main table's date range information
  const mainFromDate = $('#from_date').val() || $('#from').val();
  const mainToDate = $('#to_date').val() || $('#to').val();
  
  $.ajax({
    url: "{{ url('pendaftar/student/reportRA/getFilteredData') }}",
    method: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      year: filter.year,
      from_date: filter.from,
      to_date: filter.to,
      filter_type: filter.type,
      main_from_date: mainFromDate,
      main_to_date: mainToDate
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
  
  // Convert both to strings for comparison to handle type mismatches
  const filterYearStr = String(filter.year);
  const yearHeadersStr = yearHeaders.map(y => String(y));
  const yearIndex = yearHeadersStr.indexOf(filterYearStr);
  
  console.log('Year headers (original):', yearHeaders);
  console.log('Year headers (strings):', yearHeadersStr);
  console.log('Filter year (string):', filterYearStr);
  console.log('Year index:', yearIndex);
  console.log('Current column headers count:', columnHeaderRow.children().length);
  
  // Validate year index
  if (yearIndex === -1) {
    console.error('Year not found in headers:', filter.year);
    alert('Error: Year ' + filter.year + ' not found in table headers');
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
  
  console.log('Calculated insert position:', insertAfterIndex);
  
  // Show what column we're inserting after
  const headerCells = columnHeaderRow.children();
  if (insertAfterIndex >= 0 && insertAfterIndex < headerCells.length) {
    const cellAfter = $(headerCells[insertAfterIndex]).text().trim();
    console.log(`Will insert header AFTER column: "${cellAfter}" at position ${insertAfterIndex}`);
    $(headerCells[insertAfterIndex]).after(filterHeader);
    console.log('Successfully inserted header after position', insertAfterIndex);
  } else {
    console.log('Insert position out of bounds, appending at end');
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
    
    const rowCells = row.children();
    if (insertAfterIndex >= 0 && insertAfterIndex < rowCells.length) {
      $(rowCells[insertAfterIndex]).after(emptyCell);
      console.log(`Added cell to row ${rowIndex} after position ${insertAfterIndex}`);
    } else {
      console.warn(`Row ${rowIndex}: Insert position ${insertAfterIndex} out of bounds (${rowCells.length} cells), appending at end`);
      row.append(emptyCell);
    }
  });
  
  // Add footer cell
  const footerRow = $('tfoot tr');
  const footerCell = `<td class="text-center filter-footer-total filter-footer-${filter.year}" 
                          style="border: 1px solid black; background-color: #e9ecef; font-weight: bold;"
                          data-filter-id="${filter.id}">0</td>`;
  
  const footerCells = footerRow.children();
  if (insertAfterIndex >= 0 && insertAfterIndex < footerCells.length) {
    $(footerCells[insertAfterIndex]).after(footerCell);
    console.log('Added footer cell after position', insertAfterIndex);
  } else {
    footerRow.append(footerCell);
    console.log('Appended footer cell at end');
  }
  
  console.log('Filter column addition completed');
  
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
  console.log('Weekly data keys:', Object.keys(weeklyData || {}));
  
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
  
  // First, let's see what months are actually in the table
  console.log('=== TABLE MONTHS ANALYSIS ===');
  const tableMonths = [];
  $('#table_body tr').each(function(rowIndex) {
    const row = $(this);
    const monthCell = row.children().eq(0);
    const weekCell = row.children().eq(1);
    
    if (monthCell.length && weekCell.length) {
      let monthText = monthCell.data('month') || monthCell.text().trim();
      const weekText = weekCell.text().trim();
      
      if (monthText && monthText !== '' && monthText !== 'TOTAL' && monthNames.includes(monthText)) {
        if (weekText.includes('Week 1')) {
          const monthNum = monthNames.indexOf(monthText) + 1;
          console.log(`Table month found: ${monthText} = ${monthNum}`);
          if (!tableMonths.includes(monthNum)) {
            tableMonths.push(monthNum);
          }
        }
      }
    }
  });
  
  console.log('Table months (numbers):', tableMonths.sort());
  console.log('Weekly data months:', Object.keys(weeklyData).map(key => key.split('_')[0]).filter((v, i, a) => a.indexOf(v) === i));
  
  $('#table_body tr').each(function(rowIndex) {
    const row = $(this);
    const monthCell = row.children().eq(0);
    const weekCell = row.children().eq(1);
    
    if (monthCell.length && weekCell.length) {
      // Get month name from data attribute or text content
      let monthText = monthCell.data('month') || monthCell.text().trim();
      
      // Only update current month if this cell actually contains a month name
      if (monthText && monthText !== '' && monthText !== 'TOTAL' && monthNames.includes(monthText)) {
        // Check if this is the first occurrence of this month (first week)
        const weekText = weekCell.text().trim();
        if (weekText.includes('Week 1')) {
          currentMonth = monthText;
          currentMonthNumber = monthNames.indexOf(currentMonth) + 1;
          currentWeekInMonth = 1;
          console.log('Found new month:', currentMonth, 'number:', currentMonthNumber);
        } else if (currentMonth === monthText) {
          // This is a continuation week for the current month
          const weekMatch = weekText.match(/Week (\d+)/);
          if (weekMatch) {
            currentWeekInMonth = parseInt(weekMatch[1]);
          }
        }
      }
      
      // Process the week data if we have a valid month and week
      if (currentMonthNumber > 0 && currentWeekInMonth > 0) {
        const weekKey = `${currentMonthNumber}_${currentWeekInMonth}`;
        const count = weeklyData[weekKey] || 0;
        
        console.log(`Row ${rowIndex}: ${currentMonth} Week ${currentWeekInMonth} (${weekKey}) = ${count}`);
        
        // Add to total regardless of value (including 0) to match column display
        filterTotal += count;
        
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
          
          // Debug: Show all cells in this row that might be filter cells
          row.children().each(function(cellIndex) {
            const cellText = $(this).text().trim();
            const cellClasses = $(this).attr('class') || '';
            const cellDataId = $(this).data('filter-id') || 'none';
            if (cellClasses.includes('filter') || cellDataId !== 'none') {
              console.log(`  Potential filter cell ${cellIndex}: "${cellText}" (classes: ${cellClasses}, data-filter-id: ${cellDataId})`);
            }
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
    // Debug footer cells
    console.log('Available footer cells:');
    $('tfoot tr').children().each(function(index) {
      const cellText = $(this).text().trim();
      const cellClasses = $(this).attr('class') || '';
      const cellDataId = $(this).data('filter-id') || 'none';
      console.log(`  Footer cell ${index}: "${cellText}" (classes: ${cellClasses}, data-filter-id: ${cellDataId})`);
    });
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