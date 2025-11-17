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
            margin-top: 20px;
        }
        
        .grading-container {
            float: left;
            width: 48%;
            margin-right: 2%;
        }
        
        .chart-wrapper {
            float: left;
            width: 48%;
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
            padding: 10px;
            border: 1px solid #000;
            height: auto;
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
                        
                        @php
                            // Calculate total coursework columns including overall columns
                            $totalCourseWorkCols = $assessmentColCount;
                            // Add overall columns for each assessment type that exists
                            $totalCourseWorkCols += count($test) > 0 ? 1 : 0; // Overall TEST
                            $totalCourseWorkCols += count($test2) > 0 ? 1 : 0; // Overall TEST2
                            $totalCourseWorkCols += count($assign) > 0 ? 1 : 0; // Overall ASSIGNMENT
                            $totalCourseWorkCols += count($extra) > 0 ? 1 : 0; // Overall EXTRA
                            $totalCourseWorkCols += count($other) > 0 ? 1 : 0; // Overall OTHER
                            $totalCourseWorkCols += count($midterm) > 0 ? 1 : 0; // Overall MIDTERM
                            $totalCourseWorkCols += count($quiz) > 0 ? 1 : 0; // Overall Quiz
                            $totalCourseWorkCols += 1; // Attend column
                        @endphp
                        @if($assessmentColCount > 0 || count($quiz) > 0)
                        <th colspan="{{ $totalCourseWorkCols }}">KERJA KURSUS/MARKAH BULANAN (%)</th>
                        @endif
                        
                        @if(count($final) > 0)
                        <th rowspan="2" class="col-assessment">PEP. AKHIR</th>
                        @endif
                        
                        <!-- MARKAH KESELURUHAN Header -->
                        <th rowspan="2" colspan="2">MARKAH KESELURUHAN</th>
                    </tr>
                    <tr>
                        <!-- Individual Assessment Columns with titles and marks -->
                        @foreach($quiz as $key => $qz)
                        <th class="col-assessment">Quiz {{ $key + 1 }}<br>({{ $qz->total_mark }})</th>
                        @endforeach
                        
                        @if(count($quiz) > 0)
                        <th class="col-assessment">Overall Quiz</th>
                        @endif
                        
                        @foreach($test as $key => $ts)
                        <th class="col-assessment">TEST {{ $key + 1 }}<br>({{ $ts->total_mark }})</th>
                        @endforeach
                        
                        @foreach($test2 as $key => $ts2)
                        <th class="col-assessment">TEST {{ count($test) + $key + 1 }}<br>({{ $ts2->total_mark }})</th>
                        @endforeach
                        
                        @if(count($test) > 0)
                        <th class="col-assessment">Overall TEST</th>
                        @endif
                        
                        @if(count($test2) > 0)
                        <th class="col-assessment">Overall TEST 2</th>
                        @endif
                        
                        @foreach($assign as $key => $ag)
                        <th class="col-assessment">Asgn {{ $key + 1 }}<br>({{ $ag->total_mark }})</th>
                        @endforeach
                        
                        @if(count($assign) > 0)
                        <th class="col-assessment">Overall ASSIGNMENT</th>
                        @endif
                        
                        @foreach($extra as $key => $ex)
                        <th class="col-assessment">Extra {{ $key + 1 }}<br>({{ $ex->total_mark }})</th>
                        @endforeach
                        
                        @if(count($extra) > 0)
                        <th class="col-assessment">Overall EXTRA</th>
                        @endif
                        
                        @foreach($other as $key => $ot)
                        <th class="col-assessment">Other {{ $key + 1 }}<br>({{ $ot->total_mark }})</th>
                        @endforeach
                        
                        @if(count($other) > 0)
                        <th class="col-assessment">Overall OTHER</th>
                        @endif
                        
                        @foreach($midterm as $key => $mt)
                        <th class="col-assessment">Mid-term<br>({{ $mt->total_mark }})</th>
                        @endforeach
                        
                        @if(count($midterm) > 0)
                        <th class="col-assessment">Overall MIDTERM</th>
                        @endif
                        
                        <th class="col-assessment">Attend</th>
                    </tr>
                    
                    <!-- Percentage Weight Row -->
                    <tr class="percent-row">
                        <th> </th>
                        <th> </th>
                        <th> </th>
                        
                        @php
                            $sub_id = DB::table('subjek')->where('id', $courseInfo->id ?? 0)->value('sub_id');
                            
                            // Fetch mark percentages from database
                            $percentquiz = DB::table('tblclassmarks')->where([
                                ['course_id', $sub_id],
                                ['assessment', 'quiz']
                            ])->orderBy('tblclassmarks.id', 'desc')->first();
                            
                            $percenttest = DB::table('tblclassmarks')->where([
                                ['course_id', $sub_id],
                                ['assessment', 'test']
                            ])->orderBy('tblclassmarks.id', 'desc')->first();
                            
                            $percenttest2 = DB::table('tblclassmarks')->where([
                                ['course_id', $sub_id],
                                ['assessment', 'test2']
                            ])->orderBy('tblclassmarks.id', 'desc')->first();
                            
                            $percentassign = DB::table('tblclassmarks')->where([
                                ['course_id', $sub_id],
                                ['assessment', 'assignment']
                            ])->orderBy('tblclassmarks.id', 'desc')->first();
                            
                            $percentextra = DB::table('tblclassmarks')->where([
                                ['course_id', $sub_id],
                                ['assessment', 'extra']
                            ])->orderBy('tblclassmarks.id', 'desc')->first();
                            
                            $percentother = DB::table('tblclassmarks')->where([
                                ['course_id', $sub_id],
                                ['assessment', 'lain-lain']
                            ])->orderBy('tblclassmarks.id', 'desc')->first();
                            
                            $percentmidterm = DB::table('tblclassmarks')->where([
                                ['course_id', $sub_id],
                                ['assessment', 'midterm']
                            ])->orderBy('tblclassmarks.id', 'desc')->first();
                            
                            $percentfinal = DB::table('tblclassmarks')->where([
                                ['course_id', $sub_id],
                                ['assessment', 'final']
                            ])->orderBy('tblclassmarks.id', 'desc')->first();
                        @endphp
                        
                        <!-- Individual Quiz columns - show total marks, not percentages -->
                        @foreach($quiz as $qz)
                        <th>{{ $qz->total_mark }}</th>
                        @endforeach
                        
                        <!-- Overall Quiz percentage -->
                        @if(count($quiz) > 0)
                        <th>{{ $percentquiz ? $percentquiz->mark_percentage . '%' : '10%' }}</th>
                        @endif
                        
                        <!-- Individual Test columns - show total marks, not percentages -->
                        @foreach($test as $ts)
                        <th>{{ $ts->total_mark }}</th>
                        @endforeach
                        
                        <!-- Individual Test2 columns - show total marks, not percentages -->
                        @foreach($test2 as $ts2)
                        <th>{{ $ts2->total_mark }}</th>
                        @endforeach
                        
                        <!-- Overall Test percentage -->
                        @if(count($test) > 0)
                        <th>{{ $percenttest ? $percenttest->mark_percentage . '%' : '10%' }}</th>
                        @endif
                        
                        <!-- Overall Test2 percentage -->
                        @if(count($test2) > 0)
                        <th>{{ $percenttest2 ? $percenttest2->mark_percentage . '%' : '10%' }}</th>
                        @endif
                        
                        <!-- Individual Assignment columns - show total marks -->
                        @foreach($assign as $ag)
                        <th>{{ $ag->total_mark }}</th>
                        @endforeach
                        
                        <!-- Overall Assignment percentage -->
                        @if(count($assign) > 0)
                        <th>{{ $percentassign ? $percentassign->mark_percentage . '%' : '10%' }}</th>
                        @endif
                        
                        <!-- Individual Extra columns - show total marks -->
                        @foreach($extra as $ex)
                        <th>{{ $ex->total_mark }}</th>
                        @endforeach
                        
                        <!-- Overall Extra percentage -->
                        @if(count($extra) > 0)
                        <th>{{ $percentextra ? $percentextra->mark_percentage . '%' : '10%' }}</th>
                        @endif
                        
                        <!-- Individual Other columns - show total marks -->
                        @foreach($other as $ot)
                        <th>{{ $ot->total_mark }}</th>
                        @endforeach
                        
                        <!-- Overall Other percentage -->
                        @if(count($other) > 0)
                        <th>{{ $percentother ? $percentother->mark_percentage . '%' : '10%' }}</th>
                        @endif
                        
                        <!-- Individual Midterm columns - show total marks -->
                        @foreach($midterm as $mt)
                        <th>{{ $mt->total_mark }}</th>
                        @endforeach
                        
                        <!-- Overall Midterm percentage -->
                        @if(count($midterm) > 0)
                        <th>{{ $percentmidterm ? $percentmidterm->mark_percentage . '%' : '10%' }}</th>
                        @endif
                        
                        <!-- Attend percentage -->
                        <th> </th>
                        
                        <!-- Final percentage -->
                        @if(count($final) > 0)
                        <th>{{ $percentfinal ? $percentfinal->mark_percentage . '%' : '40%' }}</th>
                        @endif
                        
                        <!-- Overall percentages -->
                        <th> </th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Student Rows -->
                    @foreach($students as $key => $student)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $student->no_matric }}</td>
                        <td class="student-name">{{ strtoupper($student->name) }}</td>
                        
                        <!-- Quiz marks -->
                        @foreach($quiz as $qkey => $qz)
                        <td>{{ isset($quizanswer[$key][$qkey]) && $quizanswer[$key][$qkey] ? $quizanswer[$key][$qkey]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Overall Quiz -->
                        @if(count($quiz) > 0)
                        <td style="background-color: #677ee2">{{ $overallquiz[$key] ?? '0' }}</td>
                        @endif
                        
                        <!-- Test marks -->
                        @foreach($test as $tkey => $ts)
                        <td>{{ isset($testanswer[$key][$tkey]) && $testanswer[$key][$tkey] ? $testanswer[$key][$tkey]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Test2 marks -->
                        @foreach($test2 as $t2key => $ts2)
                        <td>{{ isset($test2answer[$key][$t2key]) && $test2answer[$key][$t2key] ? $test2answer[$key][$t2key]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Overall Test -->
                        @if(count($test) > 0)
                        <td style="background-color: #677ee2">{{ $overalltest[$key] ?? '0' }}</td>
                        @endif
                        
                        <!-- Overall Test2 -->
                        @if(count($test2) > 0)
                        <td style="background-color: #677ee2">{{ $overalltest2[$key] ?? '0' }}</td>
                        @endif
                        
                        <!-- Assignment marks -->
                        @foreach($assign as $akey => $ag)
                        <td>{{ isset($assignanswer[$key][$akey]) && $assignanswer[$key][$akey] ? $assignanswer[$key][$akey]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Overall Assignment -->
                        @if(count($assign) > 0)
                        <td style="background-color: #677ee2">{{ $overallassign[$key] ?? '0' }}</td>
                        @endif
                        
                        <!-- Extra marks -->
                        @foreach($extra as $ekey => $ex)
                        <td>{{ isset($extraanswer[$key][$ekey]) && $extraanswer[$key][$ekey] ? $extraanswer[$key][$ekey]->total_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Overall Extra -->
                        @if(count($extra) > 0)
                        <td style="background-color: #677ee2">{{ $overallextra[$key] ?? '0' }}</td>
                        @endif
                        
                        <!-- Other marks -->
                        @foreach($other as $okey => $ot)
                        <td>{{ isset($otheranswer[$key][$okey]) && $otheranswer[$key][$okey] ? $otheranswer[$key][$okey]->total_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Overall Other -->
                        @if(count($other) > 0)
                        <td style="background-color: #677ee2">{{ $overallother[$key] ?? '0' }}</td>
                        @endif
                        
                        <!-- Midterm marks -->
                        @foreach($midterm as $mkey => $mt)
                        <td>{{ isset($midtermanswer[$key][$mkey]) && $midtermanswer[$key][$mkey] ? $midtermanswer[$key][$mkey]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Overall Midterm -->
                        @if(count($midterm) > 0)
                        <td style="background-color: #677ee2">{{ $overallmidterm[$key] ?? '0' }}</td>
                        @endif
                        
                        <!-- Attend -->
                        <td>0</td>
                        
                        <!-- Final marks -->
                        @if(count($final) > 0)
                        <td>{{ isset($finalanswer[$key][0]) && $finalanswer[$key][0] ? $finalanswer[$key][0]->final_mark : '0' }}</td>
                        @endif
                        
                        <!-- Selang Markah -->
                        <td>{{ $overallall2[$key] ?? '0' }}%</td>
                        
                        <!-- Nilai Gred -->
                        <td>{{ $valGrade[$key] ?? '-' }}</td>
                    </tr>
                    @endforeach
                    
                    <!-- Statistics Rows -->
                    <tr class="stats-row">
                        <td colspan="3" style="text-align: right; padding-right: 10px;">PURATA</td>
                        @php
                            // Calculate averages for each assessment
                            $quizAvgs = [];
                            foreach($quiz as $qkey => $qz) {
                                $sum = 0;
                                $count = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($quizanswer[$skey][$qkey]) && $quizanswer[$skey][$qkey]) {
                                        $sum += floatval($quizanswer[$skey][$qkey]->final_mark);
                                        $count++;
                                    }
                                }
                                $quizAvgs[] = $count > 0 ? number_format($sum / $count, 2) : '0.0';
                            }
                            
                            $testAvgs = [];
                            foreach($test as $tkey => $ts) {
                                $sum = 0;
                                $count = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($testanswer[$skey][$tkey]) && $testanswer[$skey][$tkey]) {
                                        $sum += floatval($testanswer[$skey][$tkey]->final_mark);
                                        $count++;
                                    }
                                }
                                $testAvgs[] = $count > 0 ? number_format($sum / $count, 2) : '0.0';
                            }
                            
                            $test2Avgs = [];
                            foreach($test2 as $t2key => $ts2) {
                                $sum = 0;
                                $count = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($test2answer[$skey][$t2key]) && $test2answer[$skey][$t2key]) {
                                        $sum += floatval($test2answer[$skey][$t2key]->final_mark);
                                        $count++;
                                    }
                                }
                                $test2Avgs[] = $count > 0 ? number_format($sum / $count, 2) : '0.0';
                            }
                            
                            $assignAvgs = [];
                            foreach($assign as $akey => $ag) {
                                $sum = 0;
                                $count = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($assignanswer[$skey][$akey]) && $assignanswer[$skey][$akey]) {
                                        $sum += floatval($assignanswer[$skey][$akey]->final_mark);
                                        $count++;
                                    }
                                }
                                $assignAvgs[] = $count > 0 ? number_format($sum / $count, 2) : '0.0';
                            }
                            
                            $extraAvgs = [];
                            foreach($extra as $ekey => $ex) {
                                $sum = 0;
                                $count = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($extraanswer[$skey][$ekey]) && $extraanswer[$skey][$ekey]) {
                                        $sum += floatval($extraanswer[$skey][$ekey]->total_mark);
                                        $count++;
                                    }
                                }
                                $extraAvgs[] = $count > 0 ? number_format($sum / $count, 2) : '0.0';
                            }
                            
                            $otherAvgs = [];
                            foreach($other as $okey => $ot) {
                                $sum = 0;
                                $count = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($otheranswer[$skey][$okey]) && $otheranswer[$skey][$okey]) {
                                        $sum += floatval($otheranswer[$skey][$okey]->total_mark);
                                        $count++;
                                    }
                                }
                                $otherAvgs[] = $count > 0 ? number_format($sum / $count, 2) : '0.0';
                            }
                            
                            $midtermAvgs = [];
                            foreach($midterm as $mkey => $mt) {
                                $sum = 0;
                                $count = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($midtermanswer[$skey][$mkey]) && $midtermanswer[$skey][$mkey]) {
                                        $sum += floatval($midtermanswer[$skey][$mkey]->final_mark);
                                        $count++;
                                    }
                                }
                                $midtermAvgs[] = $count > 0 ? number_format($sum / $count, 2) : '0.0';
                            }
                            
                            // Calculate average overall test
                            $overallTestSum = 0;
                            $overallTestCount = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overalltest[$skey])) {
                                    $overallTestSum += floatval($overalltest[$skey]);
                                    $overallTestCount++;
                                }
                            }
                            $avgOverallTest = $overallTestCount > 0 ? number_format($overallTestSum / $overallTestCount, 2) : '0.0';
                            
                            // Calculate average overall test2
                            $overallTest2Sum = 0;
                            $overallTest2Count = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overalltest2[$skey])) {
                                    $overallTest2Sum += floatval($overalltest2[$skey]);
                                    $overallTest2Count++;
                                }
                            }
                            $avgOverallTest2 = $overallTest2Count > 0 ? number_format($overallTest2Sum / $overallTest2Count, 2) : '0.0';
                            
                            // Calculate average overall assign
                            $overallAssignSum = 0;
                            $overallAssignCount = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallassign[$skey])) {
                                    $overallAssignSum += floatval($overallassign[$skey]);
                                    $overallAssignCount++;
                                }
                            }
                            $avgOverallAssign = $overallAssignCount > 0 ? number_format($overallAssignSum / $overallAssignCount, 2) : '0.0';
                            
                            // Calculate average overall extra
                            $overallExtraSum = 0;
                            $overallExtraCount = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallextra[$skey])) {
                                    $overallExtraSum += floatval($overallextra[$skey]);
                                    $overallExtraCount++;
                                }
                            }
                            $avgOverallExtra = $overallExtraCount > 0 ? number_format($overallExtraSum / $overallExtraCount, 2) : '0.0';
                            
                            // Calculate average overall other
                            $overallOtherSum = 0;
                            $overallOtherCount = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallother[$skey])) {
                                    $overallOtherSum += floatval($overallother[$skey]);
                                    $overallOtherCount++;
                                }
                            }
                            $avgOverallOther = $overallOtherCount > 0 ? number_format($overallOtherSum / $overallOtherCount, 2) : '0.0';
                            
                            // Calculate average overall midterm
                            $overallMidtermSum = 0;
                            $overallMidtermCount = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallmidterm[$skey])) {
                                    $overallMidtermSum += floatval($overallmidterm[$skey]);
                                    $overallMidtermCount++;
                                }
                            }
                            $avgOverallMidterm = $overallMidtermCount > 0 ? number_format($overallMidtermSum / $overallMidtermCount, 2) : '0.0';
                            
                            // Calculate average overall quiz
                            $overallQuizSum = 0;
                            $overallQuizCount = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallquiz[$skey])) {
                                    $overallQuizSum += floatval($overallquiz[$skey]);
                                    $overallQuizCount++;
                                }
                            }
                            $avgOverallQuiz = $overallQuizCount > 0 ? number_format($overallQuizSum / $overallQuizCount, 2) : '0.0';
                            
                            // Calculate average final
                            $finalAvgs = [];
                            if(count($final) > 0) {
                                $sum = 0;
                                $count = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($finalanswer[$skey][0]) && $finalanswer[$skey][0]) {
                                        $sum += floatval($finalanswer[$skey][0]->final_mark);
                                        $count++;
                                    }
                                }
                                $finalAvgs[] = $count > 0 ? number_format($sum / $count, 2) : '0.0';
                            }
                        @endphp
                        
                        @foreach($quizAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @if(count($quiz) > 0)
                        <td style="background-color: #677ee2">{{ $avgOverallQuiz }}</td>
                        @endif
                        @foreach($testAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @foreach($test2Avgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @if(count($test) > 0)
                        <td style="background-color: #677ee2">{{ $avgOverallTest }}</td>
                        @endif
                        @if(count($test2) > 0)
                        <td style="background-color: #677ee2">{{ $avgOverallTest2 }}</td>
                        @endif
                        @foreach($assignAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @if(count($assign) > 0)
                        <td style="background-color: #677ee2">{{ $avgOverallAssign }}</td>
                        @endif
                        @foreach($extraAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @if(count($extra) > 0)
                        <td style="background-color: #677ee2">{{ $avgOverallExtra }}</td>
                        @endif
                        @foreach($otherAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @if(count($other) > 0)
                        <td style="background-color: #677ee2">{{ $avgOverallOther }}</td>
                        @endif
                        @foreach($midtermAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @if(count($midterm) > 0)
                        <td style="background-color: #677ee2">{{ $avgOverallMidterm }}</td>
                        @endif
                        <td>0</td>
                        @if(count($final) > 0)
                        @foreach($finalAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @endif
                        <td>{{ $avgoverall }}%</td>
                        <td></td>
                    </tr>
                    
                    <tr class="stats-row">
                        <td colspan="3" style="text-align: right; padding-right: 10px;">MAKSIMUM</td>
                        @php
                            // Calculate maximum for each assessment
                            $quizMaxs = [];
                            foreach($quiz as $qkey => $qz) {
                                $max = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($quizanswer[$skey][$qkey]) && $quizanswer[$skey][$qkey]) {
                                        $mark = floatval($quizanswer[$skey][$qkey]->final_mark);
                                        if($mark > $max) $max = $mark;
                                    }
                                }
                                $quizMaxs[] = number_format($max, 2);
                            }
                            
                            $testMaxs = [];
                            foreach($test as $tkey => $ts) {
                                $max = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($testanswer[$skey][$tkey]) && $testanswer[$skey][$tkey]) {
                                        $mark = floatval($testanswer[$skey][$tkey]->final_mark);
                                        if($mark > $max) $max = $mark;
                                    }
                                }
                                $testMaxs[] = number_format($max, 2);
                            }
                            
                            $test2Maxs = [];
                            foreach($test2 as $t2key => $ts2) {
                                $max = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($test2answer[$skey][$t2key]) && $test2answer[$skey][$t2key]) {
                                        $mark = floatval($test2answer[$skey][$t2key]->final_mark);
                                        if($mark > $max) $max = $mark;
                                    }
                                }
                                $test2Maxs[] = number_format($max, 2);
                            }
                            
                            $assignMaxs = [];
                            foreach($assign as $akey => $ag) {
                                $max = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($assignanswer[$skey][$akey]) && $assignanswer[$skey][$akey]) {
                                        $mark = floatval($assignanswer[$skey][$akey]->final_mark);
                                        if($mark > $max) $max = $mark;
                                    }
                                }
                                $assignMaxs[] = number_format($max, 2);
                            }
                            
                            $extraMaxs = [];
                            foreach($extra as $ekey => $ex) {
                                $max = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($extraanswer[$skey][$ekey]) && $extraanswer[$skey][$ekey]) {
                                        $mark = floatval($extraanswer[$skey][$ekey]->total_mark);
                                        if($mark > $max) $max = $mark;
                                    }
                                }
                                $extraMaxs[] = number_format($max, 2);
                            }
                            
                            $otherMaxs = [];
                            foreach($other as $okey => $ot) {
                                $max = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($otheranswer[$skey][$okey]) && $otheranswer[$skey][$okey]) {
                                        $mark = floatval($otheranswer[$skey][$okey]->total_mark);
                                        if($mark > $max) $max = $mark;
                                    }
                                }
                                $otherMaxs[] = number_format($max, 2);
                            }
                            
                            $midtermMaxs = [];
                            foreach($midterm as $mkey => $mt) {
                                $max = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($midtermanswer[$skey][$mkey]) && $midtermanswer[$skey][$mkey]) {
                                        $mark = floatval($midtermanswer[$skey][$mkey]->final_mark);
                                        if($mark > $max) $max = $mark;
                                    }
                                }
                                $midtermMaxs[] = number_format($max, 2);
                            }
                            
                            // Calculate max overall test
                            $maxOverallTest = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overalltest[$skey])) {
                                    $mark = floatval($overalltest[$skey]);
                                    if($mark > $maxOverallTest) $maxOverallTest = $mark;
                                }
                            }
                            $maxOverallTest = number_format($maxOverallTest, 2);
                            
                            // Calculate max overall test2
                            $maxOverallTest2 = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overalltest2[$skey])) {
                                    $mark = floatval($overalltest2[$skey]);
                                    if($mark > $maxOverallTest2) $maxOverallTest2 = $mark;
                                }
                            }
                            $maxOverallTest2 = number_format($maxOverallTest2, 2);
                            
                            // Calculate max overall assign
                            $maxOverallAssign = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallassign[$skey])) {
                                    $mark = floatval($overallassign[$skey]);
                                    if($mark > $maxOverallAssign) $maxOverallAssign = $mark;
                                }
                            }
                            $maxOverallAssign = number_format($maxOverallAssign, 2);
                            
                            // Calculate max overall extra
                            $maxOverallExtra = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallextra[$skey])) {
                                    $mark = floatval($overallextra[$skey]);
                                    if($mark > $maxOverallExtra) $maxOverallExtra = $mark;
                                }
                            }
                            $maxOverallExtra = number_format($maxOverallExtra, 2);
                            
                            // Calculate max overall other
                            $maxOverallOther = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallother[$skey])) {
                                    $mark = floatval($overallother[$skey]);
                                    if($mark > $maxOverallOther) $maxOverallOther = $mark;
                                }
                            }
                            $maxOverallOther = number_format($maxOverallOther, 2);
                            
                            // Calculate max overall midterm
                            $maxOverallMidterm = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallmidterm[$skey])) {
                                    $mark = floatval($overallmidterm[$skey]);
                                    if($mark > $maxOverallMidterm) $maxOverallMidterm = $mark;
                                }
                            }
                            $maxOverallMidterm = number_format($maxOverallMidterm, 2);
                            
                            // Calculate max overall quiz
                            $maxOverallQuiz = 0;
                            foreach($students as $skey => $student) {
                                if(isset($overallquiz[$skey])) {
                                    $mark = floatval($overallquiz[$skey]);
                                    if($mark > $maxOverallQuiz) $maxOverallQuiz = $mark;
                                }
                            }
                            $maxOverallQuiz = number_format($maxOverallQuiz, 2);
                            
                            // Calculate max final
                            $finalMaxs = [];
                            if(count($final) > 0) {
                                $max = 0;
                                foreach($students as $skey => $student) {
                                    if(isset($finalanswer[$skey][0]) && $finalanswer[$skey][0]) {
                                        $mark = floatval($finalanswer[$skey][0]->final_mark);
                                        if($mark > $max) $max = $mark;
                                    }
                                }
                                $finalMaxs[] = number_format($max, 2);
                            }
                        @endphp
                        
                        @foreach($quizMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @if(count($quiz) > 0)
                        <td style="background-color: #677ee2">{{ $maxOverallQuiz }}</td>
                        @endif
                        @foreach($testMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @foreach($test2Maxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @if(count($test) > 0)
                        <td style="background-color: #677ee2">{{ $maxOverallTest }}</td>
                        @endif
                        @if(count($test2) > 0)
                        <td style="background-color: #677ee2">{{ $maxOverallTest2 }}</td>
                        @endif
                        @foreach($assignMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @if(count($assign) > 0)
                        <td style="background-color: #677ee2">{{ $maxOverallAssign }}</td>
                        @endif
                        @foreach($extraMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @if(count($extra) > 0)
                        <td style="background-color: #677ee2">{{ $maxOverallExtra }}</td>
                        @endif
                        @foreach($otherMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @if(count($other) > 0)
                        <td style="background-color: #677ee2">{{ $maxOverallOther }}</td>
                        @endif
                        @foreach($midtermMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @if(count($midterm) > 0)
                        <td style="background-color: #677ee2">{{ $maxOverallMidterm }}</td>
                        @endif
                        <td>0</td>
                        @if(count($final) > 0)
                        @foreach($finalMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @endif
                        <td>{{ $maxoverall }}%</td>
                        <td></td>
                    </tr>
                    
                    <tr class="stats-row">
                        <td colspan="3" style="text-align: right; padding-right: 10px;">MINIMUM</td>
                        @php
                            // Calculate minimum for each assessment
                            $quizMins = [];
                            foreach($quiz as $qkey => $qz) {
                                $min = PHP_INT_MAX;
                                $hasValue = false;
                                foreach($students as $skey => $student) {
                                    if(isset($quizanswer[$skey][$qkey]) && $quizanswer[$skey][$qkey]) {
                                        $mark = floatval($quizanswer[$skey][$qkey]->final_mark);
                                        if($mark < $min) $min = $mark;
                                        $hasValue = true;
                                    }
                                }
                                $quizMins[] = $hasValue ? number_format($min, 2) : '0.0';
                            }
                            
                            $testMins = [];
                            foreach($test as $tkey => $ts) {
                                $min = PHP_INT_MAX;
                                $hasValue = false;
                                foreach($students as $skey => $student) {
                                    if(isset($testanswer[$skey][$tkey]) && $testanswer[$skey][$tkey]) {
                                        $mark = floatval($testanswer[$skey][$tkey]->final_mark);
                                        if($mark < $min) $min = $mark;
                                        $hasValue = true;
                                    }
                                }
                                $testMins[] = $hasValue ? number_format($min, 2) : '0.0';
                            }
                            
                            $test2Mins = [];
                            foreach($test2 as $t2key => $ts2) {
                                $min = PHP_INT_MAX;
                                $hasValue = false;
                                foreach($students as $skey => $student) {
                                    if(isset($test2answer[$skey][$t2key]) && $test2answer[$skey][$t2key]) {
                                        $mark = floatval($test2answer[$skey][$t2key]->final_mark);
                                        if($mark < $min) $min = $mark;
                                        $hasValue = true;
                                    }
                                }
                                $test2Mins[] = $hasValue ? number_format($min, 2) : '0.0';
                            }
                            
                            $assignMins = [];
                            foreach($assign as $akey => $ag) {
                                $min = PHP_INT_MAX;
                                $hasValue = false;
                                foreach($students as $skey => $student) {
                                    if(isset($assignanswer[$skey][$akey]) && $assignanswer[$skey][$akey]) {
                                        $mark = floatval($assignanswer[$skey][$akey]->final_mark);
                                        if($mark < $min) $min = $mark;
                                        $hasValue = true;
                                    }
                                }
                                $assignMins[] = $hasValue ? number_format($min, 2) : '0.0';
                            }
                            
                            $extraMins = [];
                            foreach($extra as $ekey => $ex) {
                                $min = PHP_INT_MAX;
                                $hasValue = false;
                                foreach($students as $skey => $student) {
                                    if(isset($extraanswer[$skey][$ekey]) && $extraanswer[$skey][$ekey]) {
                                        $mark = floatval($extraanswer[$skey][$ekey]->total_mark);
                                        if($mark < $min) $min = $mark;
                                        $hasValue = true;
                                    }
                                }
                                $extraMins[] = $hasValue ? number_format($min, 2) : '0.0';
                            }
                            
                            $otherMins = [];
                            foreach($other as $okey => $ot) {
                                $min = PHP_INT_MAX;
                                $hasValue = false;
                                foreach($students as $skey => $student) {
                                    if(isset($otheranswer[$skey][$okey]) && $otheranswer[$skey][$okey]) {
                                        $mark = floatval($otheranswer[$skey][$okey]->total_mark);
                                        if($mark < $min) $min = $mark;
                                        $hasValue = true;
                                    }
                                }
                                $otherMins[] = $hasValue ? number_format($min, 2) : '0.0';
                            }
                            
                            $midtermMins = [];
                            foreach($midterm as $mkey => $mt) {
                                $min = PHP_INT_MAX;
                                $hasValue = false;
                                foreach($students as $skey => $student) {
                                    if(isset($midtermanswer[$skey][$mkey]) && $midtermanswer[$skey][$mkey]) {
                                        $mark = floatval($midtermanswer[$skey][$mkey]->final_mark);
                                        if($mark < $min) $min = $mark;
                                        $hasValue = true;
                                    }
                                }
                                $midtermMins[] = $hasValue ? number_format($min, 2) : '0.0';
                            }
                            
                            // Calculate min overall test
                            $minOverallTest = PHP_INT_MAX;
                            $hasOverallTestValue = false;
                            foreach($students as $skey => $student) {
                                if(isset($overalltest[$skey])) {
                                    $mark = floatval($overalltest[$skey]);
                                    if($mark < $minOverallTest) $minOverallTest = $mark;
                                    $hasOverallTestValue = true;
                                }
                            }
                            $minOverallTest = $hasOverallTestValue ? number_format($minOverallTest, 2) : '0.0';
                            
                            // Calculate min overall test2
                            $minOverallTest2 = PHP_INT_MAX;
                            $hasOverallTest2Value = false;
                            foreach($students as $skey => $student) {
                                if(isset($overalltest2[$skey])) {
                                    $mark = floatval($overalltest2[$skey]);
                                    if($mark < $minOverallTest2) $minOverallTest2 = $mark;
                                    $hasOverallTest2Value = true;
                                }
                            }
                            $minOverallTest2 = $hasOverallTest2Value ? number_format($minOverallTest2, 2) : '0.0';
                            
                            // Calculate min overall assign
                            $minOverallAssign = PHP_INT_MAX;
                            $hasOverallAssignValue = false;
                            foreach($students as $skey => $student) {
                                if(isset($overallassign[$skey])) {
                                    $mark = floatval($overallassign[$skey]);
                                    if($mark < $minOverallAssign) $minOverallAssign = $mark;
                                    $hasOverallAssignValue = true;
                                }
                            }
                            $minOverallAssign = $hasOverallAssignValue ? number_format($minOverallAssign, 2) : '0.0';
                            
                            // Calculate min overall extra
                            $minOverallExtra = PHP_INT_MAX;
                            $hasOverallExtraValue = false;
                            foreach($students as $skey => $student) {
                                if(isset($overallextra[$skey])) {
                                    $mark = floatval($overallextra[$skey]);
                                    if($mark < $minOverallExtra) $minOverallExtra = $mark;
                                    $hasOverallExtraValue = true;
                                }
                            }
                            $minOverallExtra = $hasOverallExtraValue ? number_format($minOverallExtra, 2) : '0.0';
                            
                            // Calculate min overall other
                            $minOverallOther = PHP_INT_MAX;
                            $hasOverallOtherValue = false;
                            foreach($students as $skey => $student) {
                                if(isset($overallother[$skey])) {
                                    $mark = floatval($overallother[$skey]);
                                    if($mark < $minOverallOther) $minOverallOther = $mark;
                                    $hasOverallOtherValue = true;
                                }
                            }
                            $minOverallOther = $hasOverallOtherValue ? number_format($minOverallOther, 2) : '0.0';
                            
                            // Calculate min overall midterm
                            $minOverallMidterm = PHP_INT_MAX;
                            $hasOverallMidtermValue = false;
                            foreach($students as $skey => $student) {
                                if(isset($overallmidterm[$skey])) {
                                    $mark = floatval($overallmidterm[$skey]);
                                    if($mark < $minOverallMidterm) $minOverallMidterm = $mark;
                                    $hasOverallMidtermValue = true;
                                }
                            }
                            $minOverallMidterm = $hasOverallMidtermValue ? number_format($minOverallMidterm, 2) : '0.0';
                            
                            // Calculate min overall quiz
                            $minOverallQuiz = PHP_INT_MAX;
                            $hasOverallQuizValue = false;
                            foreach($students as $skey => $student) {
                                if(isset($overallquiz[$skey])) {
                                    $mark = floatval($overallquiz[$skey]);
                                    if($mark < $minOverallQuiz) $minOverallQuiz = $mark;
                                    $hasOverallQuizValue = true;
                                }
                            }
                            $minOverallQuiz = $hasOverallQuizValue ? number_format($minOverallQuiz, 2) : '0.0';
                            
                            // Calculate min final
                            $finalMins = [];
                            if(count($final) > 0) {
                                $min = PHP_INT_MAX;
                                $hasValue = false;
                                foreach($students as $skey => $student) {
                                    if(isset($finalanswer[$skey][0]) && $finalanswer[$skey][0]) {
                                        $mark = floatval($finalanswer[$skey][0]->final_mark);
                                        if($mark < $min) $min = $mark;
                                        $hasValue = true;
                                    }
                                }
                                $finalMins[] = $hasValue ? number_format($min, 2) : '0.0';
                            }
                        @endphp
                        
                        @foreach($quizMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @if(count($quiz) > 0)
                        <td style="background-color: #677ee2">{{ $minOverallQuiz }}</td>
                        @endif
                        @foreach($testMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @foreach($test2Mins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @if(count($test) > 0)
                        <td style="background-color: #677ee2">{{ $minOverallTest }}</td>
                        @endif
                        @if(count($test2) > 0)
                        <td style="background-color: #677ee2">{{ $minOverallTest2 }}</td>
                        @endif
                        @foreach($assignMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @if(count($assign) > 0)
                        <td style="background-color: #677ee2">{{ $minOverallAssign }}</td>
                        @endif
                        @foreach($extraMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @if(count($extra) > 0)
                        <td style="background-color: #677ee2">{{ $minOverallExtra }}</td>
                        @endif
                        @foreach($otherMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @if(count($other) > 0)
                        <td style="background-color: #677ee2">{{ $minOverallOther }}</td>
                        @endif
                        @foreach($midtermMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @if(count($midterm) > 0)
                        <td style="background-color: #677ee2">{{ $minOverallMidterm }}</td>
                        @endif
                        <td>0</td>
                        @if(count($final) > 0)
                        @foreach($finalMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @endif
                        <td>{{ $minoverall }}%</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Right Section: Grading Scale and Chart -->
        <div class="right-section clearfix">
            <div class="grading-container">
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
            
            <div class="chart-wrapper">
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
    <div class="signature-section clearfix" style="margin-top: 30px; clear: both;">
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

