<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportStudentController extends Controller
{
    public function index(Request $request)
    {
        $years = [];
        $currentYear = date('Y');

        // Generate list of 10 latest years
        for ($i = 0; $i < 10; $i++) {
            $years[] = $currentYear - $i;
        }

        $selectedYear = $request->get('year');
        $studentsSem1 = [];
        $studentsSemOthers = [];

        if ($selectedYear) {
            // Subquery to get the latest log ID for each student IC in the selected year
            $latestLogsSubquery = DB::table('tblstudent_log')
                ->select('student_ic', DB::raw('MAX(id) as latest_id'))
                ->whereYear('date', $selectedYear)
                ->groupBy('student_ic');

            // Main query to get student details and their latest log info
            $allStudents = DB::table('students')
                ->joinSub($latestLogsSubquery, 'latest_logs', function ($join) {
                    $join->on('students.ic', '=', 'latest_logs.student_ic');
                })
                ->join('tblstudent_log', 'latest_logs.latest_id', '=', 'tblstudent_log.id')
                ->select(
                    'students.name',
                    'students.ic',
                    'tblstudent_log.date',
                    'tblstudent_log.semester_id'
                )
                ->get();

            // Separate students based on semester_id
            $studentsSem1 = $allStudents->where('semester_id', 1);
            $studentsSemOthers = $allStudents->where('semester_id', '>', 1);
        }

        return view('pendaftar.report.student_by_year', compact(
            'years',
            'selectedYear',
            'studentsSem1',
            'studentsSemOthers'
        ));
    }
}
