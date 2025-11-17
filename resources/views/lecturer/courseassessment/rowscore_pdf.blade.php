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
                        
                        @if($assessmentColCount > 0)
                        <th colspan="{{ $assessmentColCount + 2 }}">KERJA KURSUS/MARKAH BULANAN (%)</th>
                        @endif
                        
                        @if(count($final) > 0)
                        <th rowspan="2" class="col-assessment">PEP. AKHIR</th>
                        @endif
                        
                        <!-- MARKAH KESELURUHAN Header -->
                        <th rowspan="2" colspan="2">MARKAH KESELURUHAN</th>
                        
                        <th rowspan="2" colspan="2">CATATAN</th>
                    </tr>
                    <tr>
                        <!-- Individual Assessment Columns with titles and marks -->
                        @foreach($quiz as $key => $qz)
                        <th class="col-assessment">Quiz {{ $key + 1 }}<br>({{ $qz->total_mark }})</th>
                        @endforeach
                        
                        @foreach($test as $key => $ts)
                        <th class="col-assessment">TEST {{ $key + 1 }}<br>({{ $ts->total_mark }})</th>
                        @endforeach
                        
                        @foreach($test2 as $key => $ts2)
                        <th class="col-assessment">TEST {{ count($test) + $key + 1 }}<br>({{ $ts2->total_mark }})</th>
                        @endforeach
                        
                        @foreach($assign as $key => $ag)
                        <th class="col-assessment">Asgn {{ $key + 1 }}<br>({{ $ag->total_mark }})</th>
                        @endforeach
                        
                        @foreach($extra as $key => $ex)
                        <th class="col-assessment">Extra {{ $key + 1 }}<br>({{ $ex->total_mark }})</th>
                        @endforeach
                        
                        @foreach($other as $key => $ot)
                        <th class="col-assessment">Other {{ $key + 1 }}<br>({{ $ot->total_mark }})</th>
                        @endforeach
                        
                        @foreach($midterm as $key => $mt)
                        <th class="col-assessment">Mid-term<br>({{ $mt->total_mark }})</th>
                        @endforeach
                        
                        <th class="col-assessment">Quiz</th>
                        <th class="col-assessment">Attend</th>
                    </tr>
                    
                    <!-- Percentage Weight Row -->
                    <tr class="percent-row">
                        <th>100%</th>
                        <th>20%</th>
                        <th>100%</th>
                        
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
                        @endphp
                        
                        <!-- Individual Quiz columns - show total marks, not percentages -->
                        @foreach($quiz as $qz)
                        <th>{{ $qz->total_mark }}</th>
                        @endforeach
                        
                        <!-- Individual Test columns - show total marks, not percentages -->
                        @foreach($test as $ts)
                        <th>{{ $ts->total_mark }}</th>
                        @endforeach
                        
                        <!-- Individual Test2 columns - show total marks, not percentages -->
                        @foreach($test2 as $ts2)
                        <th>{{ $ts2->total_mark }}</th>
                        @endforeach
                        
                        <!-- Individual Assignment columns - show total marks -->
                        @foreach($assign as $ag)
                        <th>{{ $ag->total_mark }}</th>
                        @endforeach
                        
                        <!-- Individual Extra columns - show total marks -->
                        @foreach($extra as $ex)
                        <th>{{ $ex->total_mark }}</th>
                        @endforeach
                        
                        <!-- Individual Other columns - show total marks -->
                        @foreach($other as $ot)
                        <th>{{ $ot->total_mark }}</th>
                        @endforeach
                        
                        <!-- Individual Midterm columns - show total marks -->
                        @foreach($midterm as $mt)
                        <th>{{ $mt->total_mark }}</th>
                        @endforeach
                        
                        <!-- Overall Quiz percentage -->
                        <th>{{ $percentquiz ? $percentquiz->mark_percentage . '%' : '10%' }}</th>
                        
                        <!-- Attend percentage -->
                        <th>5%</th>
                        
                        <!-- Final percentage -->
                        @if(count($final) > 0)
                        <th>{{ $percentfinal ? $percentfinal->mark_percentage . '%' : '40%' }}</th>
                        @endif
                        
                        <!-- Overall percentages -->
                        <th>60%</th>
                        <th>40%</th>
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
                        
                        <!-- Midterm marks -->
                        @foreach($midterm as $mkey => $mt)
                        <td>{{ isset($midtermanswer[$key][$mkey]) && $midtermanswer[$key][$mkey] ? $midtermanswer[$key][$mkey]->final_mark : '0' }}</td>
                        @endforeach
                        
                        <!-- Overall Quiz -->
                        <td>{{ $overallquiz[$key] ?? '0' }}</td>
                        
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
                        
                        <!-- Catatan columns -->
                        <td></td>
                        <td></td>
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
                        @foreach($testAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @foreach($test2Avgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @foreach($assignAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @foreach($extraAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @foreach($otherAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @foreach($midtermAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        <td>{{ $avgOverallQuiz }}</td>
                        <td>0</td>
                        @if(count($final) > 0)
                        @foreach($finalAvgs as $avg)
                        <td>{{ $avg }}</td>
                        @endforeach
                        @endif
                        <td>{{ $avgoverall }}%</td>
                        <td></td>
                        <td></td>
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
                        @foreach($testMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @foreach($test2Maxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @foreach($assignMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @foreach($extraMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @foreach($otherMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @foreach($midtermMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        <td>{{ $maxOverallQuiz }}</td>
                        <td>0</td>
                        @if(count($final) > 0)
                        @foreach($finalMaxs as $max)
                        <td>{{ $max }}</td>
                        @endforeach
                        @endif
                        <td>{{ $maxoverall }}%</td>
                        <td></td>
                        <td></td>
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
                        @foreach($testMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @foreach($test2Mins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @foreach($assignMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @foreach($extraMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @foreach($otherMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @foreach($midtermMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        <td>{{ $minOverallQuiz }}</td>
                        <td>0</td>
                        @if(count($final) > 0)
                        @foreach($finalMins as $min)
                        <td>{{ $min }}</td>
                        @endforeach
                        @endif
                        <td>{{ $minoverall }}%</td>
                        <td></td>
                        <td></td>
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

