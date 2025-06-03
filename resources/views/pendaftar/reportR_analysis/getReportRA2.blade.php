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

@if(isset($data['monthlyComparison']) && !empty($data['monthlyComparison']['monthly_data']))

<!-- Monthly Comparison Analysis Table - Report 2 Template -->
<style>
#monthly_comparison_table_r2 table {
  border: 2px solid black !important;
}

#monthly_comparison_table_r2 th {
  border: 1px solid black !important;
}

#monthly_comparison_table_r2 td {
  border: 1px solid black !important;
}

#monthly_comparison_table_r2 .table-bordered {
  border: 2px solid black !important;
}

#monthly_comparison_table_r2 .table-bordered th,
#monthly_comparison_table_r2 .table-bordered td {
  border: 1px solid black !important;
}

/* Remove sticky positioning for better full table display */
#monthly_comparison_main_table_r2 thead th {
  background: #f8f9fa !important;
  border: 1px solid black !important;
}

/* Month cell styling for visual grouping */
.month-cell-r2 {
  background: #fff !important;
  vertical-align: middle !important;
  font-weight: bold !important;
  border: 1px solid black !important;
}

#monthly_comparison_main_table_r2 tbody td:first-child {
  background: #fff !important;
  font-weight: bold !important;
}

#monthly_comparison_main_table_r2 tbody td:nth-child(2) {
  background: #fff !important;
  font-weight: bold !important;
}

/* Remove scrolling restrictions - display full table */
#monthly_comparison_table_r2 .table-responsive {
  border: 1px solid #dee2e6;
  /* Removed max-height and overflow restrictions */
}

/* Ensure table takes full width */
#monthly_comparison_main_table_r2 {
  width: 100% !important;
  table-layout: auto !important;
}

/* Print-friendly styles */
@media print {
  #monthly_comparison_table_r2 .table-responsive {
    border: none;
  }
  
  #monthly_comparison_main_table_r2 {
    font-size: 10px !important;
  }
}
</style>

<div class="card mb-3" id="monthly_comparison_table_r2">
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
      <table class="table table-striped table-bordered table-sm" id="monthly_comparison_main_table_r2" style="border: 1px solid black;">
        <thead class="thead-light" id="table_header_r2">
          <tr id="year_header_row_r2">
            <th rowspan="4" style="vertical-align: middle; width: 80px; background: #f8f9fa; border: 1px solid black;">Month</th>
            <th rowspan="4" style="vertical-align: middle; width: 120px; background: #f8f9fa; border: 1px solid black;">Week</th>
            @foreach($data['monthlyComparison']['years'] as $year)
              <th colspan="10" class="text-center bg-light year-header-{{ $year }}" style="border: 1px solid black;" data-year="{{ $year }}">{{ $year }}</th>
            @endforeach
          </tr>
          <tr id="r_header_row_r2">
            @foreach($data['monthlyComparison']['years'] as $year)
              <th colspan="10" class="text-center" style="border: 1px solid black; background-color: #f0f0f0; font-weight: bold;">R</th>
            @endforeach
          </tr>
          <tr id="main_header_row_r2">
            @foreach($data['monthlyComparison']['years'] as $year)
              <th rowspan="2" style="vertical-align: middle; width: 120px; background: #f8f9fa; border: 1px solid black; text-align: center; font-weight: bold;">Range</th>
              <th colspan="2" class="text-center" style="border: 1px solid black; background-color: #e3f2fd;">Registered</th>
              <th colspan="2" class="text-center" style="border: 1px solid black; background-color: #f3e5f5;">R Per Week</th>
              <th colspan="2" class="text-center" style="border: 1px solid black; background-color: #e8f5e8;">Status Balance R</th>
              <th rowspan="2" style="border: 1px solid black; vertical-align: middle; background-color: #fff2e6; text-align: center; font-weight: bold;">Rejected<br><span style="font-size: 14px; color: #000;">F</span></th>
              <th rowspan="2" style="border: 1px solid black; vertical-align: middle; background-color: #f0f8f0; text-align: center; font-weight: bold;">Offered<br><span style="font-size: 14px; color: #000;">G</span></th>
              <th rowspan="2" style="border: 1px solid black; vertical-align: middle; background-color: #f5f5ff; text-align: center; font-weight: bold;">KIV<br><span style="font-size: 14px; color: #000;">H</span></th>
            @endforeach
          </tr>
          <tr id="letter_header_row_r2">
            @foreach($data['monthlyComparison']['years'] as $year)
              <!-- Range column is handled by rowspan in previous row -->
              <th style="border: 1px solid black; font-size: 11px; background-color: #e3f2fd; text-align: center; font-weight: bold;">Actual<br><span style="font-size: 14px; color: #000;">A</span></th>
              <th style="border: 1px solid black; font-size: 11px; background-color: #e3f2fd; text-align: center; font-weight: bold;">Cumulative<br><span style="font-size: 14px; color: #000;">B</span></th>
              <th style="border: 1px solid black; font-size: 11px; background-color: #f3e5f5; text-align: center; font-weight: bold;">Actual<br><span style="font-size: 14px; color: #000;">C</span></th>
              <th style="border: 1px solid black; font-size: 11px; background-color: #f3e5f5; text-align: center; font-weight: bold;">Cumulative<br><span style="font-size: 14px; color: #000;">D</span></th>
              <th style="border: 1px solid black; font-size: 11px; background-color: #e8f5e8; text-align: center; font-weight: bold;">Actual<br><span style="font-size: 12px; color: #000;">E = F + G + H</span></th>
              <th style="border: 1px solid black; font-size: 11px; background-color: #e8f5e8; text-align: center; font-weight: bold;">Cumulative</th>
              <!-- F, G, H are handled by rowspan in previous row -->
            @endforeach
          </tr>
        </thead>
        <tbody id="table_body_r2">
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
              1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 
              5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 
              9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
            ];
            
            // Initialize cumulative totals for each year
            $yearCumulativeTotals = [];
            foreach($data['monthlyComparison']['years'] as $year) {
              $yearCumulativeTotals[$year] = [
                'registered' => 0,
                'r_per_week' => 0,
                'balance' => 0,
                'rejected' => 0,
                'offered' => 0,
                'kiv' => 0
              ];
            }
            
            // Initialize grand totals for footer
            $grandTotals = [];
            foreach($data['monthlyComparison']['years'] as $year) {
              $grandTotals[$year] = [
                'registered_actual' => 0,
                'registered_cumulative' => 0,
                'r_per_week_actual' => 0,
                'r_per_week_cumulative' => 0,
                'balance_actual' => 0,
                'balance_cumulative' => 0,
                'rejected' => 0,
                'offered' => 0,
                'kiv' => 0
              ];
            }
          @endphp
          
          @if(empty($monthsWithData))
            <tr>
              <td colspan="{{ 3 + (count($data['monthlyComparison']['years']) * 10) }}" class="text-center text-muted py-4" style="border: 1px solid black;">
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
                      <td class="month-cell-r2" 
                          rowspan="{{ $maxWeeks }}"
                          style="background: #fff; border: 1px solid black; vertical-align: middle; font-weight: bold; text-align: center;" 
                          data-month="{{ $monthName }}">
                        {{ $monthName }}
                      </td>
                    @endif
                    
                    <td class="text-center" style="background: #fff; font-size: 12px; padding: 8px 4px; border: 1px solid black;">
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
                          // Calculate values for Report 2 template
                          $registeredActual = $weekData['total_by_converts'] ?? 0;
                          $rPerWeekActual = $weekData['total_by_weeks'] ?? 0;
                          $balanceActual = $weekData['balance_student'] ?? 0;
                          $rejected = $weekData['total_rejected'] ?? 0;
                          $offered = $weekData['total_offered'] ?? 0;
                          $kiv = $weekData['total_kiv'] ?? 0;
                          
                          // Update cumulative totals
                          $yearCumulativeTotals[$year]['registered'] += $registeredActual;
                          $yearCumulativeTotals[$year]['r_per_week'] += $rPerWeekActual;
                          $yearCumulativeTotals[$year]['balance'] += $balanceActual;
                          $yearCumulativeTotals[$year]['rejected'] += $rejected;
                          $yearCumulativeTotals[$year]['offered'] += $offered;
                          $yearCumulativeTotals[$year]['kiv'] += $kiv;
                          
                          // Update grand totals
                          $grandTotals[$year]['registered_actual'] += $registeredActual;
                          $grandTotals[$year]['registered_cumulative'] = $yearCumulativeTotals[$year]['registered'];
                          $grandTotals[$year]['r_per_week_actual'] += $rPerWeekActual;
                          $grandTotals[$year]['r_per_week_cumulative'] = $yearCumulativeTotals[$year]['r_per_week'];
                          $grandTotals[$year]['balance_actual'] += $balanceActual;
                          $grandTotals[$year]['balance_cumulative'] = $yearCumulativeTotals[$year]['balance'];
                          $grandTotals[$year]['rejected'] += $rejected;
                          $grandTotals[$year]['offered'] += $offered;
                          $grandTotals[$year]['kiv'] += $kiv;
                        @endphp
                        
                        <!-- Range -->
                        <td class="text-center" style="border: 1px solid black; font-size: 10px; padding: 4px;">
                          <small class="text-muted">{{ $weekData['date_range'] }}</small>
                        </td>
                        
                        <!-- Registered: Actual -->
                        <td class="text-center" style="border: 1px solid black; background-color: #f8f9fa;">{{ number_format($registeredActual) }}</td>
                        
                        <!-- Registered: Cumulative -->
                        <td class="text-center" style="border: 1px solid black; background-color: #e3f2fd;">{{ number_format($yearCumulativeTotals[$year]['registered']) }}</td>
                        
                        <!-- R Per Week: Actual -->
                        <td class="text-center" style="border: 1px solid black; background-color: #f8f9fa;">{{ number_format($rPerWeekActual) }}</td>
                        
                        <!-- R Per Week: Cumulative -->
                        <td class="text-center" style="border: 1px solid black; background-color: #f3e5f5;">{{ number_format($yearCumulativeTotals[$year]['r_per_week']) }}</td>
                        
                        <!-- Status Balance R: Actual -->
                        <td class="text-center" style="border: 1px solid black; background-color: #f8f9fa;">{{ number_format($balanceActual) }}</td>
                        
                        <!-- Status Balance R: Cumulative -->
                        <td class="text-center" style="border: 1px solid black; background-color: #e8f5e8;">{{ number_format($yearCumulativeTotals[$year]['balance']) }}</td>
                        
                        <!-- Rejected -->
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($rejected) }}</td>
                        
                        <!-- Offered -->
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($offered) }}</td>
                        
                        <!-- KIV -->
                        <td class="text-center" style="border: 1px solid black;">{{ number_format($kiv) }}</td>
                        
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
                        
                        <!-- Range -->
                        <td class="text-center" style="border: 1px solid black; font-size: 10px; padding: 4px;">
                          <small class="text-muted">{{ $dateRange }}</small>
                        </td>
                        
                        <!-- All data columns with 0 values -->
                        <td class="text-center" style="border: 1px solid black; background-color: #f8f9fa;">0</td>
                        <td class="text-center" style="border: 1px solid black; background-color: #e3f2fd;">{{ number_format($yearCumulativeTotals[$year]['registered']) }}</td>
                        <td class="text-center" style="border: 1px solid black; background-color: #f8f9fa;">0</td>
                        <td class="text-center" style="border: 1px solid black; background-color: #f3e5f5;">{{ number_format($yearCumulativeTotals[$year]['r_per_week']) }}</td>
                        <td class="text-center" style="border: 1px solid black; background-color: #f8f9fa;">0</td>
                        <td class="text-center" style="border: 1px solid black; background-color: #e8f5e8;">{{ number_format($yearCumulativeTotals[$year]['balance']) }}</td>
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
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($grandTotals[$year]['registered_actual']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($grandTotals[$year]['registered_cumulative']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($grandTotals[$year]['r_per_week_actual']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($grandTotals[$year]['r_per_week_cumulative']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($grandTotals[$year]['balance_actual']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($grandTotals[$year]['balance_cumulative']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($grandTotals[$year]['rejected']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($grandTotals[$year]['offered']) }}</td>
              <td class="text-center" style="border: 1px solid black; background-color: #e9ecef;">{{ number_format($grandTotals[$year]['kiv']) }}</td>
            @endforeach
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@else
  <div class="card mb-3">
    <div class="card-body">
      <div class="text-center text-muted py-4">
        <h5>No data available</h5>
        <p>Please select date ranges to generate the Monthly Comparison Analysis report.</p>
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
  const fromDate = $('#from_date').val();
  const toDate = $('#to_date').val();
  const selectedYears = [];
  const reportType = $('input[name="report_type"]:checked').val();

  // Collect selected years
  $('.year-checkbox:checked').each(function() {
    selectedYears.push($(this).val());
  });

  console.log('Report 2 - Export function called');
  console.log('Report type:', reportType);
  
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
    alert("Please select date range and years first.");
  }
}

function analyseData() {
  // Show analysis section and loading
  $('#analysisSection').show();
  $('#analysisLoading').show();
  $('#analysisResult').hide();
  
  // Collect table data for Report 2
  const tableData = collectTableDataReport2();
  
  // Send data to backend for AI analysis
  $.ajax({
    url: "{{ url('pendaftar/student/reportRA/analyseData') }}",
    method: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      tableData: JSON.stringify(tableData),
      reportType: 2
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

function collectTableDataReport2() {
  const data = {
    type: 'monthly_comparison_r2',
    reportType: 2,
    title: 'Monthly Comparison Analysis - Report 2',
    years: @json($data['monthlyComparison']['years'] ?? []),
    monthlyData: {}
  };
  
  // Collect monthly data from the table
  @if(isset($data['monthlyComparison']['monthly_data']))
    @foreach($data['monthlyComparison']['years'] as $year)
      @if(isset($data['monthlyComparison']['monthly_data'][$year]))
        data.monthlyData[{{ $year }}] = @json($data['monthlyComparison']['monthly_data'][$year]);
      @endif
    @endforeach
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
</script>
