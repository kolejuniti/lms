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
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Student R Analysis Report</h2>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
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
                    $total_all = $data['totalConvert'] + $data['registered'] + $data['rejected'] + $data['offered'] + $data['KIV'] + $data['others'];
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
    @endif

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        }
    </script>
</body>
</html> 