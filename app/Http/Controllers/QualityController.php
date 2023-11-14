<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\subject;
use App\Models\student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;
use Mail;
use Intervention\Image\Facades\Image;
use App\Exports\TableExport;
use Maatwebsite\Excel\Facades\Excel;

class QualityController extends Controller
{
    public function attendanceReport()
    {

        $data['faculty'] = DB::table('tblfaculty')->get();
        
        $data['session'] = DB::table('sessions')->get();

        return view('quality.report.attendance', compact('data'));

    }

    public function getLectAttendance(Request $request)
    {

        $data['lecturer'] = DB::table('user_subjek')
                            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                            ->join('users', 'user_subjek.user_ic', 'users.ic')
                            ->where([
                                ['user_subjek.session_id', $request->session],
                                ['users.faculty', $request->faculty]
                            ])->select('user_subjek.id', 'subjek.course_name AS course', 'subjek.course_code AS code', 'users.name')
                            ->groupBy('subjek.sub_id', 'user_subjek.session_id')
                            ->orderBy('users.name')
                            ->get();


        foreach($data['lecturer'] as $key => $lect)
        {

            if (isset($request->from) && isset($request->to)) {

                $data['attendance'][$key] = DB::table('tblclassattendance')
                                            ->where('groupid', $lect->id)
                                            ->whereBetween('classdate', [$request->from, $request->to])
                                            ->groupBy('classdate')
                                            ->groupBy('groupname')
                                            ->get();

            }else{

                $data['attendance'][$key] = DB::table('tblclassattendance')
                                            ->where('groupid', $lect->id)
                                            ->groupBy('classdate')
                                            ->groupBy('groupname')
                                            ->get();

            }

        }

        return view('quality.report.attendanceGetLecturer', compact('data'));

    }

    public function allReport(Request $request)
    {

        $data['faculty'] = DB::table('tblfaculty')->get();

        $data['assessment'] = [];

        foreach($data['faculty'] as $key => $fcl)
        {

            $baseQuery = function () use ($fcl){

                return DB::table('users')->where('status', 'ACTIVE')->where('faculty', $fcl->id)->whereIn('usrtype', ['LCT','PL']);

            };

            $data['lecturer'][$key] = ($baseQuery)()->get();

            $data['countCecturer'][$key] = ($baseQuery)()->count();

            foreach($data['lecturer'][$key] as $key1 => $lct)
            {
                $data['course'][$key][$key1] = DB::table('user_subjek')
                    ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
                    ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
                    ->where('user_subjek.user_ic', $lct->ic)
                    ->select('subjek.*','user_subjek.id AS groupId','user_subjek.course_id','sessions.SessionName','sessions.SessionID')
                    ->groupBy('subjek.sub_id', 'user_subjek.session_id')
                    ->get();

                foreach($data['course'][$key][$key1] as $key2 => $crs)
                {

                    if(count(DB::table('tblclassquiz')
                       ->join('tblclassstudentquiz', 'tblclassquiz.id', 'tblclassstudentquiz.quizid')
                       ->where([
                        ['tblclassquiz.classid', $crs->id],
                        ['tblclassquiz.sessionid', $crs->SessionID],
                        ['tblclassquiz.addby', $lct->ic],
                        ['tblclassquiz.status', 2],
                        ])
                       ->whereNotIn('tblclassstudentquiz.final_mark', [0])->get()) > 0)
                    {

                        $data['assessment'][$key][$key1][$key2] = 'MARKED';


                    }elseif(count(DB::table('tblclasstest')
                    ->join('tblclassstudenttest', 'tblclasstest.id', 'tblclassstudenttest.testid')
                    ->where([
                        ['tblclasstest.classid', $crs->id],
                        ['tblclasstest.sessionid', $crs->SessionID],
                        ['tblclasstest.addby', $lct->ic],
                        ['tblclasstest.status', 2],
                        ])
                    ->whereNotIn('tblclassstudenttest.final_mark', [0])->get()) > 0)
                    {

                        $data['assessment'][$key][$key1][$key2] = 'MARKED';


                    }elseif(count(DB::table('tblclassassign')
                    ->join('tblclassstudentassign', 'tblclassassign.id', 'tblclassstudentassign.assignid')
                    ->where([
                        ['tblclassassign.classid', $crs->id],
                        ['tblclassassign.sessionid', $crs->SessionID],
                        ['tblclassassign.addby', $lct->ic],
                        ['tblclassassign.status', 2],
                        ])
                    ->whereNotIn('tblclassstudentassign.final_mark', [0])->get()) > 0)
                    {

                        $data['assessment'][$key][$key1][$key2] = 'MARKED';


                    }else{

                        $data['assessment'][$key][$key1][$key2] = 'NOT MARKED';

                        

                    }

                    $data['content'][$key][$key1][$key2] = DB::table('material_dir')
                                                           ->join('lecturer_dir','material_dir.LecturerDirID','lecturer_dir.DrID')
                                                           ->where([
                                                            ['lecturer_dir.AddBy', $lct->ic],
                                                            ['lecturer_dir.CourseID', $crs->id],
                                                            ['lecturer_dir.SessionID', $crs->SessionID],
                                                            ])
                                                            ->select('material_dir.*')->get();

                    $data['quiz'][$key][$key1][$key2] = count(DB::table('tblclassquiz')
                                                        ->where([
                                                            ['tblclassquiz.classid', $crs->id],
                                                            ['tblclassquiz.sessionid', $crs->SessionID],
                                                            ['tblclassquiz.addby', $lct->ic],
                                                            ['tblclassquiz.status', 2],
                                                        ])->get());

                    $data['test'][$key][$key1][$key2] = count(DB::table('tblclasstest')
                                                        ->where([
                                                            ['tblclasstest.classid', $crs->id],
                                                            ['tblclasstest.sessionid', $crs->SessionID],
                                                            ['tblclasstest.addby', $lct->ic],
                                                            ['tblclasstest.status', 2],
                                                        ])->get());

                    $data['assignment'][$key][$key1][$key2] = count(DB::table('tblclassassign')
                                                        ->where([
                                                            ['tblclassassign.classid', $crs->id],
                                                            ['tblclassassign.sessionid', $crs->SessionID],
                                                            ['tblclassassign.addby', $lct->ic],
                                                            ['tblclassassign.status', 2],
                                                        ])->get());
                    
                    $data['usage'][$key][$key1][$key2] = (count(DB::table('student_subjek')
                                                         ->join('tblsubject_grade','student_subjek.grade','tblsubject_grade.code')
                                                         ->where([
                                                            ['student_subjek.courseid', $crs->sub_id],
                                                            ['student_subjek.sessionid', $crs->SessionID],
                                                            ['student_subjek.sessionid', $crs->groupId]
                                                         ])
                                                         ->whereNotIn('tblsubject_grade.id', [13,15])->get()) > 0) ? 'GRADED' : 'NOT GRADED';

                }

            }

        }

        //dd($data['content']);

        return view('quality.report.allReport.index', compact('data'));

    }

    public function exportTableToExcel()
    {

        $data['faculty'] = DB::table('tblfaculty')->get();

        foreach($data['faculty'] as $key => $fcl)
        {

            $baseQuery = function () use ($fcl){

                return DB::table('users')->where('status', 'ACTIVE')->where('faculty', $fcl->id)->whereIn('usrtype', ['LCT','PL']);

            };

            $data['lecturer'][$key] = ($baseQuery)()->get();

            $data['countCecturer'][$key] = ($baseQuery)()->count();

            foreach($data['lecturer'][$key] as $key1 => $lct)
            {
                $data['course'][$key][$key1] = DB::table('user_subjek')
                    ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
                    ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
                    ->where('user_subjek.user_ic', $lct->ic)
                    ->select('subjek.*','user_subjek.id AS groupId','user_subjek.course_id','sessions.SessionName','sessions.SessionID')
                    ->groupBy('subjek.sub_id', 'user_subjek.session_id')
                    ->get();

                    foreach($data['course'][$key][$key1] as $key2 => $crs)
                    {
    
                        if(count(DB::table('tblclassquiz')
                           ->join('tblclassstudentquiz', 'tblclassquiz.id', 'tblclassstudentquiz.quizid')
                           ->where([
                            ['tblclassquiz.classid', $crs->id],
                            ['tblclassquiz.sessionid', $crs->SessionID],
                            ['tblclassquiz.addby', $lct->ic],
                            ['tblclassquiz.status', 2],
                            ])
                           ->whereNotIn('tblclassstudentquiz.final_mark', [0])->get()) > 0)
                        {
    
                            $data['assessment'][$key][$key1][$key2] = 'MARKED';
    
    
                        }elseif(count(DB::table('tblclasstest')
                        ->join('tblclassstudenttest', 'tblclasstest.id', 'tblclassstudenttest.testid')
                        ->where([
                            ['tblclasstest.classid', $crs->id],
                            ['tblclasstest.sessionid', $crs->SessionID],
                            ['tblclasstest.addby', $lct->ic],
                            ['tblclasstest.status', 2],
                            ])
                        ->whereNotIn('tblclassstudenttest.final_mark', [0])->get()) > 0)
                        {
    
                            $data['assessment'][$key][$key1][$key2] = 'MARKED';
    
    
                        }elseif(count(DB::table('tblclassassign')
                        ->join('tblclassstudentassign', 'tblclassassign.id', 'tblclassstudentassign.assignid')
                        ->where([
                            ['tblclassassign.classid', $crs->id],
                            ['tblclassassign.sessionid', $crs->SessionID],
                            ['tblclassassign.addby', $lct->ic],
                            ['tblclassassign.status', 2],
                            ])
                        ->whereNotIn('tblclassstudentassign.final_mark', [0])->get()) > 0)
                        {
    
                            $data['assessment'][$key][$key1][$key2] = 'MARKED';
    
    
                        }else{
    
                            $data['assessment'][$key][$key1][$key2] = 'NOT MARKED';
    
                            
    
                        }
    
                        $data['content'][$key][$key1][$key2] = DB::table('material_dir')
                                                               ->join('lecturer_dir','material_dir.LecturerDirID','lecturer_dir.DrID')
                                                               ->where([
                                                                ['lecturer_dir.AddBy', $lct->ic],
                                                                ['lecturer_dir.CourseID', $crs->id],
                                                                ['lecturer_dir.SessionID', $crs->SessionID],
                                                                ])
                                                                ->select('material_dir.*')->get();
    
                        $data['quiz'][$key][$key1][$key2] = count(DB::table('tblclassquiz')
                                                            ->where([
                                                                ['tblclassquiz.classid', $crs->id],
                                                                ['tblclassquiz.sessionid', $crs->SessionID],
                                                                ['tblclassquiz.addby', $lct->ic],
                                                                ['tblclassquiz.status', 2],
                                                            ])->get());
    
                        $data['test'][$key][$key1][$key2] = count(DB::table('tblclasstest')
                                                            ->where([
                                                                ['tblclasstest.classid', $crs->id],
                                                                ['tblclasstest.sessionid', $crs->SessionID],
                                                                ['tblclasstest.addby', $lct->ic],
                                                                ['tblclasstest.status', 2],
                                                            ])->get());
    
                        $data['assignment'][$key][$key1][$key2] = count(DB::table('tblclassassign')
                                                            ->where([
                                                                ['tblclassassign.classid', $crs->id],
                                                                ['tblclassassign.sessionid', $crs->SessionID],
                                                                ['tblclassassign.addby', $lct->ic],
                                                                ['tblclassassign.status', 2],
                                                            ])->get());
                        
                        $data['usage'][$key][$key1][$key2] = (count(DB::table('student_subjek')
                                                             ->join('tblsubject_grade','student_subjek.grade','tblsubject_grade.code')
                                                             ->where([
                                                                ['student_subjek.courseid', $crs->sub_id],
                                                                ['student_subjek.sessionid', $crs->SessionID],
                                                                ['student_subjek.sessionid', $crs->groupId]
                                                             ])
                                                             ->whereNotIn('tblsubject_grade.id', [13,15])->get()) > 0) ? 'GRADED' : 'NOT GRADED';
    
                    }

            }

        }

        return Excel::download(new TableExport($data), 'table.xlsx');

    }
}
