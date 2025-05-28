<!DOCTYPE html>
<html>
<head>
    <title>Student R Analysis Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .table-title {
            font-weight: bold;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        .summary-title {
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
        }
        .monthly-title {
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #17a2b8;
            color: white;
            text-align: center;
        }
        .monthly-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }
        .monthly-table th, .monthly-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        .monthly-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .monthly-table .month-cell {
            font-weight: bold;
            background-color: #fff;
        }
        .monthly-table .week-cell {
            background-color: #fff;
            font-size: 9px;
        }
        .monthly-table .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .monthly-table .date-range {
            font-size: 8px;
            color: #666;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
            .monthly-table {
                font-size: 8px;
            }
            .monthly-table th, .monthly-table td {
                padding: 2px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Student R Analysis Report</h2>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
        <!-- Manual print button as fallback -->
        <div style="margin: 10px 0; text-align: center;" class="no-print">
            <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                🖨️ Print This Report
            </button>
            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                If auto-print doesn't work, click the button above or press Ctrl+P (Windows) / Cmd+P (Mac)
            </p>
        </div>
    </div>

    @if(isset($data['tableLabels']) && is_array($data['tableLabels']))
        <!-- Multiple Tables Display -->
        @foreach($data['tableLabels'] as $key => $label)
        <div class="table-title">{{ $label }}</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Total Student R</th>
                    <th>Total by Convert</th>
                    <th>Balance Student</th>
                    <th>Student Active</th>
                    <th>Student Rejected</th>
                    <th>Student Offered</th>
                    <th>Student KIV</th>
                    <th>Student Others</th>
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
        @endforeach

        @if(count($data['tableLabels']) > 1)
        <!-- Summary Table -->
        <div class="summary-title">Summary of All Tables</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Total Student R</th>
                    <th>Total by Convert</th>
                    <th>Balance Student</th>
                    <th>Student Active</th>
                    <th>Student Rejected</th>
                    <th>Student Offered</th>
                    <th>Student KIV</th>
                    <th>Student Others</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @php
                    $total_student_r = array_sum($data['allStudents']);
                    $total_convert = array_sum($data['totalConvert']);
                    $total_registered = array_sum($data['registered']);
                    $total_rejected = array_sum($data['rejected']);
                    $total_offered = array_sum($data['offered']);
                    $total_kiv = array_sum($data['KIV']);
                    $total_others = array_sum($data['others']);
                    $grand_total = $total_convert + $total_registered + $total_rejected + $total_offered + $total_kiv + $total_others;
                    @endphp
                    <td>{{ $total_student_r }}</td>
                    <td>{{ $total_convert }}</td>
                    <td>{{ $total_student_r - $total_convert }}</td>
                    <td>{{ $total_registered }}</td>
                    <td>{{ $total_rejected }}</td>
                    <td>{{ $total_offered }}</td>
                    <td>{{ $total_kiv }}</td>
                    <td>{{ $total_others }}</td>
                </tr>
            </tbody>
        </table>
        @endif
    @else
        <!-- Single Table Display -->
        <div class="table-title">Student R Analysis Report</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Total Student R</th>
                    <th>Total by Convert</th>
                    <th>Balance Student</th>
                    <th>Student Active</th>
                    <th>Student Rejected</th>
                    <th>Student Offered</th>
                    <th>Student KIV</th>
                    <th>Student Others</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @php
                    // Ensure $data is an array before accessing keys
                    if (!is_array($data)) {
                        $data = [];
                    }
                    $total_all = ($data['totalConvert'] ?? 0) + ($data['registered'] ?? 0) + ($data['rejected'] ?? 0) + ($data['offered'] ?? 0) + ($data['KIV'] ?? 0) + ($data['others'] ?? 0);
                    @endphp
                    <td>{{ $data['allStudents'] ?? 0 }}</td>
                    <td>{{ $data['totalConvert'] ?? 0 }}</td>
                    <td>{{ ($data['allStudents'] ?? 0) - ($data['totalConvert'] ?? 0) }}</td>
                    <td>{{ $data['registered'] ?? 0 }}</td>
                    <td>{{ $data['rejected'] ?? 0 }}</td>
                    <td>{{ $data['offered'] ?? 0 }}</td>
                    <td>{{ $data['KIV'] ?? 0 }}</td>
                    <td>{{ $data['others'] ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if(isset($data['monthlyComparison']) && !empty($data['monthlyComparison']) && !empty($data['monthlyComparison']['monthly_data']))
    <!-- Monthly Comparison Table -->
    <div class="monthly-title">Monthly Comparison Analysis</div>
    <p style="text-align: center; margin-bottom: 20px; font-size: 11px;">
        Showing {{ count($data['monthlyComparison']['years']) }} years | 
        Data follows calendar date (Sunday to Saturday) for weekly breakdown | 
        Only months with data are displayed
    </p>
    
    <table class="monthly-table">
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align: middle;">Month</th>
                <th rowspan="2" style="vertical-align: middle;">Week</th>
                @foreach($data['monthlyComparison']['years'] as $year)
                    <th colspan="4">Year {{ $year }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($data['monthlyComparison']['years'] as $year)
                    <th>Range</th>
                    <th>Total By Weeks</th>
                    <th>Total By Converts</th>
                    <th>Balance Student</th>
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
                
                // Initialize totals array for each year
                $yearTotals = [];
                foreach($data['monthlyComparison']['years'] as $year) {
                    $yearTotals[$year] = [
                        'total_by_weeks' => 0,
                        'total_by_converts' => 0,
                        'balance_student' => 0
                    ];
                }
            @endphp
            
            @if(empty($monthsWithData))
                <tr>
                    <td colspan="{{ 2 + (count($data['monthlyComparison']['years']) * 4) }}" style="text-align: center; padding: 20px;">
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
                                    <td rowspan="{{ $maxWeeks }}" class="month-cell" style="vertical-align: middle;">
                                        {{ $monthName }}
                                    </td>
                                @endif
                                
                                <td class="week-cell">Week {{ $weekNum }}</td>
                                
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
                                        @endphp
                                        <td class="date-range">{{ $weekData['date_range'] }}</td>
                                        <td>{{ number_format($weekData['total_by_weeks']) }}</td>
                                        <td>{{ number_format($weekData['total_by_converts']) }}</td>
                                        <td>{{ number_format($weekData['balance_student']) }}</td>
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
                                        <td class="date-range">{{ $dateRange }}</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endfor
                    @endif
                @endforeach
            @endif
        </tbody>
        <tfoot class="total-row">
            <tr>
                <td style="font-weight: bold;">TOTAL</td>
                <td style="font-weight: bold;">All Weeks</td>
                @foreach($data['monthlyComparison']['years'] as $year)
                    <td style="font-weight: bold;">All Ranges</td>
                    <td style="font-weight: bold;">{{ number_format($yearTotals[$year]['total_by_weeks']) }}</td>
                    <td style="font-weight: bold;">{{ number_format($yearTotals[$year]['total_by_converts']) }}</td>
                    <td style="font-weight: bold;">{{ number_format($yearTotals[$year]['balance_student']) }}</td>
                @endforeach
            </tr>
        </tfoot>
    </table>
    @endif

    <script>
        // Simple and reliable auto-print function without jQuery dependency
        function autoPrint() {
            try {
                // Wait for page to fully load
                if (document.readyState === 'complete') {
                    window.print();
                } else {
                    // If not ready, wait a bit more
                    setTimeout(autoPrint, 500);
                }
            } catch (e) {
                console.log('Auto-print failed:', e);
                // Show manual instruction
                setTimeout(function() {
                    alert('Please press Ctrl+P (Windows) or Cmd+P (Mac) to print this page.');
                }, 1000);
            }
        }
        
        // Start auto-print when page loads
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(autoPrint, 1000);
            });
        } else {
            setTimeout(autoPrint, 1000);
        }
    </script>
</body>
</html> 