<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rowscore Report - {{ $groupName }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            margin: 0;
            padding: 0;
        }
        
        .header {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .header-left {
            float: left;
            width: 10%;
        }
        
        .header-center {
            float: left;
            width: 70%;
            text-align: left;
            padding-left: 10px;
        }
        
        .header-right {
            float: right;
            width: 20%;
            text-align: right;
            font-size: 9pt;
        }
        
        .logo {
            width: 60px;
            height: auto;
        }
        
        .header-center h3 {
            margin: 0;
            padding: 0;
            font-size: 11pt;
            font-weight: bold;
        }
        
        .header-center p {
            margin: 2px 0;
            font-size: 9pt;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .main-container {
            width: 100%;
            margin-top: 10px;
        }
        
        .left-section {
            width: 100%;
            page-break-after: always;
        }
        
        .right-section {
            width: 100%;
            page-break-before: always;
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
        }
        
        .right-section > div {
            width: 45%;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 7pt;
        }
        
        table, th, td {
            border: 1px solid #000;
        }
        
        th, td {
            padding: 3px 4px;
            text-align: center;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .assessment-table th {
            font-size: 7pt;
        }
        
        .percent-row {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        
        .stats-row {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .student-name {
            text-align: left;
            padding-left: 5px;
        }
        
        .grading-table {
            font-size: 7pt;
        }
        
        .grading-table th {
            background-color: #d0d0d0;
        }
        
        .chart-container {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #000;
        }
        
        .chart-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 9pt;
        }
        
        .chart-bars {
            width: 100%;
        }
        
        .bar {
            display: inline-block;
            background-color: #4472C4;
            margin: 2px;
            text-align: center;
            color: white;
            font-weight: bold;
            min-width: 20px;
        }
        
        .bar-label {
            display: inline-block;
            width: 30px;
            text-align: right;
            margin-right: 5px;
        }
        
        .signature-section {
            margin-top: 20px;
            width: 100%;
        }
        
        .signature-left {
            float: left;
            width: 50%;
        }
        
        .signature-right {
            float: right;
            width: 50%;
        }
        
        .col-bil { width: 3%; }
        .col-matric { width: 8%; }
        .col-nama { width: 12%; }
        .col-assessment { width: 4%; }
        .col-overall { width: 5%; }
        .col-grade { width: 4%; }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header clearfix">
        <div class="header-left">
            <img src="{{ public_path('assets/images/logo/Kolej-UNITI.png') }}" alt="Logo" class="logo">
        </div>
        <div class="header-center">
            <h3>KOLEJ UNITI, PORT DICKSON</h3>
            <p><strong>FAKULTI:</strong> {{ $courseInfo->facultyname ?? 'N/A' }}</p>
            <p><strong>PROGRAM:</strong> {{ $courseInfo->course_name ?? 'N/A' }}</p>
            <p><strong>PEPERIKSAAN:</strong> {{ $courseInfo->session ?? 'N/A' }}</p>
            <p><strong>KOD MATA PELAJARAN:</strong> {{ $courseInfo->course_code ?? 'N/A' }}</p>
            <p><strong>KUMPULAN:</strong> {{ $groupName }}</p>
        </div>
        <div class="header-right">
            BPJOLI-PMI (D1.05/01)
        </div>
    </div>
    
    <div class="main-container clearfix">
        <!-- Left Section: Assessment Table -->
        <div class="left-section">
            <table class="assessment-table">
                <thead>
                    <tr>
                        <th rowspan="2" class="col-bil">BIL</th>
                        <th rowspan="2" class="col-matric">NO KAD MATRIK</th>
                        <th rowspan="2" class="col-nama">NAMA</th>
                        
                        <!-- KERJA KURSUS/MARKAH BULANAN (%) Header -->
                        @php
                            $assessmentColCount = 0;
                            $assessmentColCount += count($midterm);
                            $assessmentColCount += count($quiz);
                            $assessmentColCount += count($test);
                            $assessmentColCount += count($test2);
                            $assessmentColCount += count($assign);
                            $assessmentColCount += count($extra);
                            $assessmentColCount += count($other);
                        @endphp
                        
                        @if($assessmentColCount > 0)
                        <th colspan="{{ $assessmentColCount + 2 }}">KERJA KURSUS/MARKAH BULANAN (%)</th>
                        @endif
                        
                        <!-- MARKAH KESELURUHAN Header -->
                        <th rowspan="2" colspan="2">MARKAH KESELURUHAN</th>
                        
                        @if(count($final) > 0)
                        <th rowspan="2" class="col-assessment">PEP. AKHIR</th>
                        @endif
                        
                        <th rowspan="2" colspan="2">CATATAN</th>
                    </tr>
                    <tr>
                        <!-- Individual Assessment Columns -->
                        @foreach($midterm as $key => $mt)
                        <th class="col-assessment">Mid-term</th>
                        @endforeach
                        
                        @foreach($quiz as $key => $qz)
                        <th class="col-assessment">Quiz {{ $key + 1 }}</th>
                        @endforeach
                        
                        @foreach($test as $key => $ts)
                        <th class="col-assessment">TEST {{ $key + 1 }}</th>
                        @endforeach
                        
                        @foreach($test2 as $key => $ts2)
                        <th class="col-assessment">TEST {{ count($test) + $key + 1 }}</th>
                        @endforeach
                        
                        @foreach($assign as $key => $ag)
                        <th class="col-assessment">Asgn {{ $key + 1 }}</th>
                        @endforeach
                        
                        @foreach($extra as $key => $ex)
                        <th class="col-assessment">Extra {{ $key + 1 }}</th>
                        @endforeach
                        
                        @foreach($other as $key => $ot)
                        <th class="col-assessment">Other {{ $key + 1 }}</th>
                        @endforeach
                        
                        <th class="col-assessment">Quiz</th>
                        <th class="col-assessment">Attend</th>
                    </tr>
                    
                    <!-- Percentage Weight Row -->
                    <tr class="percent-row">
                        <th>100%</th>
                        <th>20%</th>
                        <th>100%</th>
                        
                        @foreach($midterm as $mt)
                        <th>{{ $percentmidterm ? $percentmidterm->mark_percentage . '%' : '0%' }}</th>
                        @endforeach
                        
                        @foreach($quiz as $qz)
                        <th>10%</th>
                        @endforeach
                        
                        @foreach($test as $ts)
                        <th>10%</th>
                        @endforeach
                        
                        @foreach($test2 as $ts2)
                        <th>10%</th>
                        @endforeach
                        
                        @foreach($assign as $ag)
                        <th>{{ $percentassign ? number_format($percentassign->mark_percentage / max(count($assign), 1), 0) . '%' : '0%' }}</th>
                        @endforeach
                        
                        @foreach($extra as $ex)
                        <th>{{ $percentextra ? number_format($percentextra->mark_percentage / max(count($extra), 1), 0) . '%' : '0%' }}</th>
                        @endforeach
                        
                        @foreach($other as $ot)
                        <th>{{ $percentother ? number_format($percentother->mark_percentage / max(count($other), 1), 0) . '%' : '0%' }}</th>
                        @endforeach
                        
                        <th>10%</th>
                        <th>5%</th>
                        <th>60%</th>
                        <th>40%</th>
                        @if(count($final) > 0)
                        <th>{{ $percentfinal ? $percentfinal->mark_percentage . '%' : '40%' }}</th>
                        @endif
                        <th>0</th>
                        <th>0</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Student Rows -->
                    @foreach($students as $key => $student)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $student->no_matric }}</td>
                        <td class="student-name">{{ strtoupper($student->name) }}</td>
                        
                        <!-- Midterm marks -->
                        @foreach($midterm as $mkey => $mt)
                        <td>{{ isset($midtermanswer[$key][$mkey]) && $midtermanswer[$key][$mkey] ? $midtermanswer[$key][$mkey]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Quiz marks -->
                        @foreach($quiz as $qkey => $qz)
                        <td>{{ isset($quizanswer[$key][$qkey]) && $quizanswer[$key][$qkey] ? $quizanswer[$key][$qkey]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Test marks -->
                        @foreach($test as $tkey => $ts)
                        <td>{{ isset($testanswer[$key][$tkey]) && $testanswer[$key][$tkey] ? $testanswer[$key][$tkey]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Test2 marks -->
                        @foreach($test2 as $t2key => $ts2)
                        <td>{{ isset($test2answer[$key][$t2key]) && $test2answer[$key][$t2key] ? $test2answer[$key][$t2key]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Assignment marks -->
                        @foreach($assign as $akey => $ag)
                        <td>{{ isset($assignanswer[$key][$akey]) && $assignanswer[$key][$akey] ? $assignanswer[$key][$akey]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Extra marks -->
                        @foreach($extra as $ekey => $ex)
                        <td>{{ isset($extraanswer[$key][$ekey]) && $extraanswer[$key][$ekey] ? $extraanswer[$key][$ekey]->total_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Other marks -->
                        @foreach($other as $okey => $ot)
                        <td>{{ isset($otheranswer[$key][$okey]) && $otheranswer[$key][$okey] ? $otheranswer[$key][$okey]->total_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Overall Quiz -->
                        <td>{{ $overallquiz[$key] ?? '0' }}</td>
                        
                        <!-- Attend -->
                        <td>0</td>
                        
                        <!-- Selang Markah -->
                        <td>{{ $overallall2[$key] ?? '0' }}%</td>
                        
                        <!-- Nilai Gred -->
                        <td>{{ $valGrade[$key] ?? '-' }}</td>
                        
                        <!-- Final marks -->
                        @if(count($final) > 0)
                        <td>{{ isset($finalanswer[$key][0]) && $finalanswer[$key][0] ? $finalanswer[$key][0]->final_mark : '0' }}</td>
                        @endif
                        
                        <!-- Catatan columns -->
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                    
                    <!-- Statistics Rows -->
                    <tr class="stats-row">
                        <td colspan="3" style="text-align: right; padding-right: 10px;">PURATA</td>
                        @foreach($midterm as $mt)
                        <td>0.0</td>
                        @endforeach
                        @foreach($quiz as $qz)
                        <td>0.0</td>
                        @endforeach
                        @foreach($test as $ts)
                        <td>0.0</td>
                        @endforeach
                        @foreach($test2 as $ts2)
                        <td>0.0</td>
                        @endforeach
                        @foreach($assign as $ag)
                        <td>0.0</td>
                        @endforeach
                        @foreach($extra as $ex)
                        <td>0.0</td>
                        @endforeach
                        @foreach($other as $ot)
                        <td>0.0</td>
                        @endforeach
                        <td>0.0</td>
                        <td>0</td>
                        <td>{{ $avgoverall }}%</td>
                        <td></td>
                        @if(count($final) > 0)
                        <td>0</td>
                        @endif
                        <td></td>
                        <td></td>
                    </tr>
                    
                    <tr class="stats-row">
                        <td colspan="3" style="text-align: right; padding-right: 10px;">MAKSIMUM</td>
                        @foreach($midterm as $mt)
                        <td>0.0</td>
                        @endforeach
                        @foreach($quiz as $qz)
                        <td>0.0</td>
                        @endforeach
                        @foreach($test as $ts)
                        <td>0.0</td>
                        @endforeach
                        @foreach($test2 as $ts2)
                        <td>0.0</td>
                        @endforeach
                        @foreach($assign as $ag)
                        <td>0.0</td>
                        @endforeach
                        @foreach($extra as $ex)
                        <td>0.0</td>
                        @endforeach
                        @foreach($other as $ot)
                        <td>0.0</td>
                        @endforeach
                        <td>0.0</td>
                        <td>0</td>
                        <td>{{ $maxoverall }}%</td>
                        <td></td>
                        @if(count($final) > 0)
                        <td>0</td>
                        @endif
                        <td></td>
                        <td></td>
                    </tr>
                    
                    <tr class="stats-row">
                        <td colspan="3" style="text-align: right; padding-right: 10px;">MINIMUM</td>
                        @foreach($midterm as $mt)
                        <td>0.0</td>
                        @endforeach
                        @foreach($quiz as $qz)
                        <td>0.0</td>
                        @endforeach
                        @foreach($test as $ts)
                        <td>0.0</td>
                        @endforeach
                        @foreach($test2 as $ts2)
                        <td>0.0</td>
                        @endforeach
                        @foreach($assign as $ag)
                        <td>0.0</td>
                        @endforeach
                        @foreach($extra as $ex)
                        <td>0.0</td>
                        @endforeach
                        @foreach($other as $ot)
                        <td>0.0</td>
                        @endforeach
                        <td>0.0</td>
                        <td>0</td>
                        <td>{{ $minoverall }}%</td>
                        <td></td>
                        @if(count($final) > 0)
                        <td>0</td>
                        @endif
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Right Section: Grading Scale and Chart -->
        <div class="right-section">
            <div>
                <!-- Grading Scale Table -->
                <table class="grading-table">
                <thead>
                    <tr>
                        <th colspan="4" style="background-color: #d0d0d0;">GRADING SCALE</th>
                    </tr>
                    <tr>
                        <th>MARKAH</th>
                        <th>GRED</th>
                        <th>NILAI MATA</th>
                        <th>CATATAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gradingScale as $grade)
                    <tr>
                        <td>{{ $grade->mark_start }}-{{ $grade->mark_end }}</td>
                        <td>{{ $grade->code }}</td>
                        <td>{{ number_format($grade->grade_value, 2) }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            
            <div>
                <!-- Performance Chart -->
                <div class="chart-container">
                <div class="chart-title">PRESTASI CALON</div>
                <div class="chart-bars">
                    @foreach($gradingScale as $grade)
                        @php
                            $count = $gradeDistribution[$grade->code] ?? 0;
                            $barHeight = $count > 0 ? ($count * 20) : 0;
                        @endphp
                        <div style="margin-bottom: 5px;">
                            <span class="bar-label">{{ $grade->code }}</span>
                            @if($count > 0)
                            <div class="bar" style="width: {{ $barHeight }}px; height: 15px; line-height: 15px;">{{ $count }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <!-- Signature Section -->
    <div class="signature-section clearfix" style="margin-top: 30px;">
        <div class="signature-left">
            <p><strong>TANDATANGAN PENSYARAH:</strong> ______________________</p>
            <p style="margin-top: 30px;"><strong>Tarikh :</strong> ______________________</p>
        </div>
        <div class="signature-right">
            <p><strong>Disahkan oleh :</strong> ______________________</p>
            <p style="margin-top: 30px;"><strong>Tarikh :</strong> ______________________</p>
        </div>
    </div>
</body>
</html>

