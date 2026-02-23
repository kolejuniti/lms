<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Charge Report</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        * {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            vertical-align: baseline;
            background: transparent;
            font-size: 8px;
            font-family: Arial, sans-serif;
        }

        h1 {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        h2 {
            font-size: 11px;
            font-weight: bold;
            margin: 8px 0 4px 0;
        }

        b {
            font-weight: bold;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            background: #343a40;
            color: #fff;
            padding: 4px 6px;
            margin-top: 10px;
            margin-bottom: 2px;
        }

        .card-header-plain {
            font-size: 9px;
            font-weight: bold;
            background: #e9ecef;
            padding: 3px 6px;
            margin-top: 8px;
            margin-bottom: 2px;
            border-left: 3px solid #6c757d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        th {
            background-color: #343a40;
            color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 3px 4px;
            border: 1px solid #999;
            font-size: 7.5px;
        }

        td {
            padding: 2px 4px;
            border: 1px solid #ccc;
            vertical-align: top;
            font-size: 7.5px;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        tfoot tr td {
            font-weight: bold;
            background-color: #dee2e6;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Coloured group headers for summary dashboard */
        .th-blue {
            background: #17a2b8;
            color: #fff;
        }

        .th-blue2 {
            background: #138496;
            color: #fff;
        }

        .th-blue3 {
            background: #117a8b;
            color: #fff;
        }

        .th-red {
            background: #dc3545;
            color: #fff;
        }

        .th-red2 {
            background: #c82333;
            color: #fff;
        }

        .th-red3 {
            background: #a71d2a;
            color: #fff;
        }

        .th-red4 {
            background: #881524;
            color: #fff;
        }

        .th-orange {
            background: #fd7e14;
            color: #fff;
        }

        .th-orange2 {
            background: #e8590c;
            color: #fff;
        }

        .th-purple {
            background: #6f42c1;
            color: #fff;
        }

        .th-purple2 {
            background: #5a32a3;
            color: #fff;
        }

        .th-purple3 {
            background: #4c1d8a;
            color: #fff;
        }

        .th-teal {
            background: #20c997;
            color: #fff;
        }

        .th-teal2 {
            background: #199d76;
            color: #fff;
        }

        .th-teal3 {
            background: #168a64;
            color: #fff;
        }

        .th-gray {
            background: #6c757d;
            color: #fff;
        }

        .th-gray2 {
            background: #545b62;
            color: #fff;
        }

        .th-dark {
            background: #343a40;
            color: #fff;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Charge Report: {{ $data['from'] }} hingga {{ $data['to'] }}</h1>
        <br>

        {{-- ========== NEW STUDENT ========== --}}
        <div class="section-title">New Student</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalNewALL = 0; @endphp
                @foreach ($data['newStudent'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalNewALL += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalNewALL, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== OLD STUDENT ========== --}}
        <div class="section-title">Old Student</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalOldALL = 0; @endphp
                @foreach ($data['oldStudent'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalOldALL += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalOldALL, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== SUMMARY BY PROGRAM (New + Old) ========== --}}
        <div class="card-header-plain">Summary by Program</div>
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="th-dark" style="width:5%">No. Program</th>
                    <th rowspan="2" class="th-dark" style="width:10%">Program</th>
                    <th colspan="2" class="th-blue">Student Quote (RM)</th>
                </tr>
                <tr>
                    <th class="th-blue2">New Student</th>
                    <th class="th-blue3">Old Student</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['program'] as $key => $prg)
                <tr>
                    <td class="text-center">{{ $prg->program_ID }}</td>
                    <td><b>{{ $prg->progcode }}</b></td>
                    <td class="text-right">{{ number_format((!empty($data['newStudentTotals'])) ? $data['newStudentTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format((!empty($data['oldStudentTotals'])) ? $data['oldStudentTotals'][$key] : 0, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format(array_sum($data['newStudentTotals']), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum($data['oldStudentTotals']), 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== PROGRAMME SUMMARY DASHBOARD ========== --}}
        <div class="section-title">Programme Summary Dashboard &mdash; All charges grouped by programme</div>
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="th-dark" style="width:4%">#</th>
                    <th rowspan="2" class="th-dark" style="width:8%">Program</th>
                    <th colspan="2" class="th-blue">Student Fee (RM)</th>
                    <th colspan="3" class="th-red">Debit Note (RM)</th>
                    <th colspan="1" class="th-orange">Summons/Fine (RM)</th>
                    <th colspan="2" class="th-purple">Credit Note &ndash; Fee (RM)</th>
                    <th colspan="2" class="th-teal">Credit Note &ndash; Fine (RM)</th>
                    <th colspan="1" class="th-gray">CN Discount (RM)</th>
                </tr>
                <tr>
                    <th class="th-blue2">New</th>
                    <th class="th-blue3">Old</th>
                    <th class="th-red2">Debit</th>
                    <th class="th-red3">Correction</th>
                    <th class="th-red4">Correction Insentif/Tabung</th>
                    <th class="th-orange2">Fine</th>
                    <th class="th-purple2">Active &amp; Withdraw</th>
                    <th class="th-purple3">Graduation</th>
                    <th class="th-teal2">Active &amp; Withdraw</th>
                    <th class="th-teal3">Graduation</th>
                    <th class="th-gray2">Discount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['program'] as $key => $prg)
                <tr>
                    <td class="text-center">{{ $prg->program_ID }}</td>
                    <td><b>{{ $prg->progcode }}</b></td>
                    <td class="text-right">{{ number_format((!empty($data['newStudentTotals'])) ? $data['newStudentTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format((!empty($data['oldStudentTotals'])) ? $data['oldStudentTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format((!empty($data['debitTotals'])) ? $data['debitTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format((!empty($data['debitCorrectionTotals'])) ? $data['debitCorrectionTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format((!empty($data['debitCorrectionIncentifTotals'])) ? $data['debitCorrectionIncentifTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">-</td>
                    <td class="text-right">{{ number_format((!empty($data['creditFeeOldTotals'])) ? $data['creditFeeOldTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format((!empty($data['creditFeeGradTotals'])) ? $data['creditFeeGradTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format((!empty($data['creditFineOldTotals'])) ? $data['creditFineOldTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format((!empty($data['creditFineGradTotals'])) ? $data['creditFineGradTotals'][$key] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format((!empty($data['creditDiscountTotals'])) ? $data['creditDiscountTotals'][$key] : 0, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format(array_sum($data['newStudentTotals']), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum($data['oldStudentTotals']), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum($data['debitTotals']), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum($data['debitCorrectionTotals']), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum($data['debitCorrectionIncentifTotals']), 2) }}</td>
                    <td class="text-right">-</td>
                    <td class="text-right">{{ number_format(array_sum($data['creditFeeOldTotals']), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum($data['creditFeeGradTotals']), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum($data['creditFineOldTotals']), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum($data['creditFineGradTotals']), 2) }}</td>
                    <td class="text-right">{{ number_format(array_sum($data['creditDiscountTotals']), 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== DEBIT NOTE (original: correction null/0) ========== --}}
        <div class="section-title">Debit Note</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <th>Claim</th>
                    <th>Remark</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalDebitALL = 0; @endphp
                @foreach ($data['debit'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <td>{{ $rgs->type }}</td>
                    <td>{{ $rgs->remark }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalDebitALL += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalDebitALL, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== DEBIT NOTE – CORRECTION (correction=1, non-insentif/tabung) ========== --}}
        <div class="section-title">Debit Note – Correction</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <th>Claim</th>
                    <th>Remark</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalDebitCorrectionALL = 0; @endphp
                @foreach ($data['debitCorrection'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <td>{{ $rgs->type }}</td>
                    <td>{{ $rgs->remark }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalDebitCorrectionALL += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalDebitCorrectionALL, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== DEBIT NOTE – CORRECTION INSENTIF/TABUNG ========== --}}
        <div class="section-title">Debit Note – Correction Insentif / Tabung</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <th>Claim</th>
                    <th>Remark</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalDebitIncentifALL = 0; @endphp
                @foreach ($data['debitCorrectionIncentif'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <td>{{ $rgs->type }}</td>
                    <td>{{ $rgs->remark }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalDebitIncentifALL += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalDebitIncentifALL, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== SUMMONS / FINE ========== --}}
        <div class="section-title">Summons / Fine</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <th>Claim</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalFineALL = 0; @endphp
                @foreach ($data['fine'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <td>{{ $rgs->type }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalFineALL += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalFineALL, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== OTHERS ========== --}}
        <div class="section-title">Others</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <th>Claim</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalOtherALL = 0; @endphp
                @foreach ($data['other'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <td>{{ $rgs->type }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalOtherALL += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalOtherALL, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Others – Breakdown by Type --}}
        <div class="card-header-plain">Others – Breakdown by Type</div>
        <table style="width:40%;">
            <thead>
                <tr>
                    <th style="width:8%">No.</th>
                    <th>Type</th>
                    <th style="width:20%">Total (RM)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['otherCharge'] as $key => $chg)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $chg->name }}</td>
                    <td class="text-right">{{ number_format((!empty($data['other'])) ? $data['otherTotals'][$key] : 0, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format(array_sum($data['otherTotals']), 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== CREDIT NOTE ACTIVE & WITHDRAW (FEE) ========== --}}
        <div class="section-title">Credit Note Active &amp; Withdraw Student (Fee)</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Student ID</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <!-- <th>Claim</th> -->
                    <th>Remark</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalcreditFeeALL1 = 0; @endphp
                @foreach ($data['creditFeeOld'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->student_id ? str_pad($rgs->student_id, strlen($rgs->student_id) + 1, '1', STR_PAD_LEFT) : '' }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <!-- <td>{{ $rgs->reduction_id }}</td> -->
                    <td>{{ $rgs->remark }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalcreditFeeALL1 += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalcreditFeeALL1, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== CREDIT NOTE GRADUATION (FEE) ========== --}}
        <div class="section-title">Credit Note Graduation Student (Fee)</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <!-- <th>Claim</th> -->
                    <th>Remark</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalcreditFeeALL2 = 0; @endphp
                @foreach ($data['creditFeeGrad'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <!-- <td>{{ $rgs->reduction_id }}</td> -->
                    <td>{{ $rgs->remark }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalcreditFeeALL2 += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalcreditFeeALL2, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== CREDIT NOTE ACTIVE & WITHDRAW (FINE) ========== --}}
        <div class="section-title">Credit Note Active &amp; Withdraw Student (Fine)</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <!-- <th>Claim</th> -->
                    <th>Remark</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalcreditFineALL1 = 0; @endphp
                @foreach ($data['creditFineOld'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <!-- <td>{{ $rgs->reduction_id }}</td> -->
                    <td>{{ $rgs->remark }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalcreditFineALL1 += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalcreditFineALL1, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== CREDIT NOTE GRADUATION (FINE) ========== --}}
        <div class="section-title">Credit Note Graduation Student (Fine)</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <!-- <th>Claim</th> -->
                    <th>Remark</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalcreditFineALL2 = 0; @endphp
                @foreach ($data['creditFineGrad'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <!-- <td>{{ $rgs->reduction_id }}</td> -->
                    <td>{{ $rgs->remark }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalcreditFineALL2 += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalcreditFineALL2, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- ========== CREDIT NOTE (DISCOUNT) ========== --}}
        <div class="section-title">Credit Note (Discount)</div>
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No.</th>
                    <th>Date</th>
                    <th>No. Resit</th>
                    <th>Name</th>
                    <th>No.KP</th>
                    <th>No.Matric</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <!-- <th>Claim</th> -->
                    <th>Remark</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalcreditDiscountALL = 0; @endphp
                @foreach ($data['creditDiscount'] as $key => $rgs)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $rgs->date }}</td>
                    <td>{{ $rgs->ref_no }}</td>
                    <td>{{ $rgs->name }}</td>
                    <td>{{ $rgs->student_ic }}</td>
                    <td>{{ $rgs->no_matric }}</td>
                    <td>{{ $rgs->progname }}</td>
                    <td>{{ $rgs->semester_id }}</td>
                    <!-- <td>{{ $rgs->reduction_id }}</td> -->
                    <td>{{ $rgs->remark }}</td>
                    <td class="text-right">
                        {{ $rgs->amount }}
                        @php $totalcreditDiscountALL += $rgs->amount; @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalcreditDiscountALL, 2) }}</td>
                </tr>
            </tfoot>
        </table>

    </div>{{-- end .container --}}

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>