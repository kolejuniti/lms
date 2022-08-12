<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupController extends Controller
{
    //BACKUP QUIZ 2
    
    public function quiz2list()
    {
        Session::put('CourseIDS', request()->id);

        if(Session::get('SessionIDS') == null)
        {
        Session::put('SessionIDS', request()->session);
        }

        $user = Auth::user();
        $group = array();

        $chapter = array();

        //dd(Session::get('CourseIDS'));

        $data = DB::table('tblclassquiz')
                ->where([
                    ['classid', Session::get('CourseIDS')],
                    ['sessionid', Session::get('SessionIDS')],
                    ['addby', $user->ic],
                    ['deadline','!=',null]
                ])->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassquiz_group')
                        ->join('user_subjek', 'tblclassquiz_group.groupid', 'user_subjek.id')
                        ->where('tblclassquiz_group.quizid', $dt->id)->get();

                $chapter[] = DB::table('tblclassquiz_chapter')
                        ->join('material_dir', 'tblclassquiz_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassquiz_chapter.quizid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.quiz2', compact('data', 'group', 'chapter'));
    }

    public function quiz2create()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseIDS');

        $sessionid = Session::get('SessionIDS');

        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->where([
            ['subjek.id', $courseid],
            ['user_subjek.session_id', $sessionid],
            ['user_subjek.user_ic', $user->ic]
        ])->groupBy('student_subjek.group_name')
        ->select('user_subjek.*', 'subjek.course_name', 'student_subjek.group_name')->get();

        //dd(Session::get('CourseIDS'));

        $folder = DB::table('lecturer_dir')
        ->where([
            ['CourseID', $courseid],
            ['SessionID', $sessionid],
            ['Addby', $user->ic]
            ])->get();

        //dd($folder);

        return view('lecturer.courseassessment.quiz2create', compact(['group', 'folder']));
    }

    public function insertquiz2(Request $request)
    {

        $user = Auth::user();

        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');

        $data = $request->validate([
            'quiz2-title' => ['required', 'string'],
            'quiz2-duration' => ['required'],
            'total-marks' => ['required'],
            'classdescription' => ['required'],
            'myPdf' => ['mimes:pdf']
        ]);

        $dir = "classquiz2/" .  $classid . "/" . $user->name . "/" . $data['quiz2-title'];

        $classquiz2  = Storage::disk('linode')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        if($file != null)
        {

            $file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $fileInfo = pathinfo($file_name);
            $filename = $fileInfo['filename'];
            $newname = $filename . "." . $file_ext;
            $newpath = "classquiz2/" .  $classid . "/" . $user->name . "/" . $data['quiz2-title'] . "/" . $newname;

            if(!file_exists($newname)){
                Storage::disk('linode')->putFileAs(
                    $dir,
                    $file,
                    $newname,
                    'public'
                );

                $q = DB::table('tblclassquiz')->insertGetId([
                    'classid' => $classid,
                    'sessionid' => $sessionid,
                    'status' => 2,
                    'title' => $data['quiz2-title'],
                    'content' => $newpath,
                    'description' => $data['classdescription'],
                    'deadline' => $data['quiz2-duration'],
                    'total_mark' => $data['total-marks'],
                    'addby' => $user->ic
                ]);

                foreach($request->group as $grp)
                {
                    $gp = explode('|', $grp);

                    DB::table('tblclassquiz_group')->insert([
                        "groupid" => $gp[0],
                        "groupname" => $gp[1],
                        "quizid" => $q
                    ]);
                }

                foreach($request->chapter as $chp)
                {
                    DB::table('tblclassquiz_chapter')->insert([
                        "chapterid" => $chp,
                        "quizid" => $q
                    ]);
                }

            }

        }else{

            $q = DB::table('tblclassquiz')->insertGetId([
                'classid' => $classid,
                'sessionid' => $sessionid,
                'status' => 2,
                'title' => $data['quiz2-title'],
                'description' => $data['classdescription'],
                'deadline' => $data['quiz2-duration'],
                'total_mark' => $data['total-marks'],
                'addby' => $user->ic
            ]);

            foreach($request->group as $grp)
            {
                $gp = explode('|', $grp);

                DB::table('tblclassquiz_group')->insert([
                    "groupid" => $gp[0],
                    "groupname" => $gp[1],
                    "quizid" => $q
                ]);
            }

            foreach($request->chapter as $chp)
            {
                DB::table('tblclassquiz_chapter')->insert([
                    "chapterid" => $chp,
                    "quizid" => $q
                ]);
            }

        }

        return redirect(route('lecturer.quiz2', ['id' => $classid]));

    }

    public function lecturerquiz2status()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassquiz_group', 'user_subjek.id', 'tblclassquiz_group.groupid')
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassquiz.id', request()->quiz2]
                ])->get();
            
        //dd($group);

        $quiz2 = DB::table('student_subjek')
                ->join('tblclassquiz_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassquiz_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassquiz_group.groupname');
                })
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassquiz.id AS clssid', 'tblclassquiz.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', request()->quiz2],
                    ['tblclassquiz.addby', $user->ic]
                ])->get();
        
        //dd($quiz2);

        foreach($quiz2 as $qz)
        {
            $status[] = DB::table('tblclassstudentquiz')
            ->where([
                ['quizid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($quiz2);

        return view('lecturer.courseassessment.quiz2status', compact('quiz2', 'status', 'group'));

    }

    public function quiz2result(Request $request){
        
        $id = $request->quizid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $quiz2 = DB::table('tblclassstudentquiz')
            ->join('tblclassquiz', 'tblclassstudentquiz.quizid', 'tblclassquiz.id')
            ->leftJoin('students', 'tblclassstudentquiz.userid', 'students.ic')
            ->select('tblclassstudentquiz.*', 'tblclassstudentquiz.quizid', 'tblclassquiz.title',  
                DB::raw('tblclassquiz.content as original_content'),
                'tblclassstudentquiz.return_content', 
                'tblclassstudentquiz.userid',
                'tblclassstudentquiz.subdate',
                'tblclassstudentquiz.final_mark',
                DB::raw('tblclassstudentquiz.status as studentquiz2status'),
                'tblclassquiz.deadline','tblclassquiz.total_mark','students.name', 'students.ic')
            ->where('tblclassstudentquiz.quizid', $id)
            ->where('tblclassstudentquiz.userid', $userid)->get()->first();

       
        $data['quiz2'] = $quiz2->content;
        //dd($data['quiz2']);
        $data['return'] = $quiz2->return_content;
        $data['comments'] = $quiz2->comments;
        $data['mark'] = $quiz2->final_mark;
        $data['quizid'] = $quiz2->quizid;
        $data['quiz2title'] = $quiz2->title;
        $data['totalmark'] = $quiz2->total_mark;
        $data['quiz2deadline'] = $quiz2->deadline;
        $data['quiz2userid'] = $quiz2->userid;
        $data['fullname'] = $quiz2->name;
        $data['IC'] = $quiz2->ic;
        $data['created_at'] = $quiz2->created_at;
        $data['updated_at'] = $quiz2->updated_at;
        $data['subdate'] = $quiz2->subdate;
        $data['studentquiz2status'] = $quiz2->studentquiz2status;

        return view('lecturer.courseassessment.quiz2result', compact('data'));
    }

    public function updatequiz2result(Request $request){
        $quizid = $request->id;
        $participant = $request->participant;
        $final_mark = $request->markss;
        $comments = $request->commentss;
        $classid = Session::get('CourseIDS');
        //$total_mark = $request->total_mark;
        //$data = $request->data;

        $quiz2 = DB::table('tblclassquiz')
                      ->join('users', 'tblclassquiz.addby', 'users.ic')
                      ->where('tblclassquiz.id', $quizid)
                      ->first();

        //dd($quiz2);

        $dir = "classquiz2/" .  $classid . "/" . $quiz2->name . "/" . $quiz2->title . "/" . $participant . "/return";

        $classquiz2  = Storage::disk('linode')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classquiz2/" .  $classid . "/" . $quiz2->name . "/" . $quiz2->title . "/" . $participant . "/return" . "/" . $newname;

        Storage::disk('linode')->putFileAs(
            $dir,
            $file,
            $newname,
            'public'
          );
      
        $q = \DB::table('tblclassstudentquiz')
            ->where('quizid', $quizid)
            ->where("userid", $participant)
            ->update([
                "return_content" => $newpath,
                "final_mark" => $final_mark,
                //"total_mark" => $total_mark,
                "comments" => $comments,
                "status" => 3
            ]);
        
            return redirect(route('lecturer.quiz2.status',
        
            ['id' => $classid,'quiz2' => $quizid]
           ));
    }


    //STUDENT quiz2

    public function studentquiz2list()
    {
        Session::put('CourseIDS', request()->id);

        if(Session::get('SessionIDS') == null)
        {
        Session::put('SessionIDS', request()->session);
        }

        $student = auth()->guard('student')->user();

        Session::put('StudInfos', $student);
       
        $group = array();

        $chapter = array();

        //dd(Session::get('CourseIDS'));

        $data = DB::table('tblclassquiz')
                ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                ->join('student_subjek', function($join){
                    $join->on('tblclassquiz_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassquiz_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassquiz_group.groupid', 'user_subjek.id')
                ->select('tblclassquiz.*', 'tblclassquiz_group.groupname')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic],
                    ['tblclassquiz.deadline','!=',null]
                ])->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassquiz_group')
                        ->join('user_subjek', 'tblclassquiz_group.groupid', 'user_subjek.id')
                        ->where('tblclassquiz_group.quizid', $dt->id)->get();

                $chapter[] = DB::table('tblclassquiz_chapter')
                        ->join('material_dir', 'tblclassquiz_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassquiz_chapter.quizid', $dt->id)->get();
            }
      

        return view('student.courseassessment.quiz2', compact('data', 'group', 'chapter'));
    }

    public function studentquiz2status()
    {
        $quiz2 = DB::table('student_subjek')
                ->join('tblclassquiz_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassquiz_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassquiz_group.groupname');
                })
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', request()->quiz2],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();
            
        foreach($quiz2 as $qz)
        {
            $status[] = DB::table('tblclassstudentquiz')
            ->where([
                ['quizid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.quiz2status', compact('quiz2', 'status'));
    }

    public function quiz2view(Request $request){

        $id = $request->quiz2;
        $quiz2 = DB::table('tblclassquiz')
            ->where('tblclassquiz.id', $id)
            ->get()->first();


        
            $data['quizid'] = $quiz2->id;
            $data['quiz2title'] = $quiz2->title;
            $data['quiz2deadline'] = $quiz2->deadline;
            $data['created_at'] = $quiz2->created_at;
            $data['updated_at'] = $quiz2->updated_at;
    
            return view('student.courseassessment.quiz2answer', compact('data'));
        
    }

    public function submitquiz2(Request $request){
        $id = $request->id;

        $quiz2 = DB::table('tblclassquiz')
                      ->join('users', 'tblclassquiz.addby', 'users.ic')
                      ->where('tblclassquiz.id', $id)
                      ->first();
        
        //dd($quiz2);

        $classid = Session::get('CourseIDS');

        $stud = Session::get('StudInfos');

        $dir = "classquiz2/" .  $classid . "/" . $quiz2->name . "/" . $quiz2->title . "/" . $stud->ic;

        //$classquiz2  = Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classquiz2/" .  $classid . "/" . $quiz2->name . "/" . $quiz2->title . "/" . $stud->ic . "/" . $newname;
        
        $today = date("Y-m-d H:i:s");

        if(! file_exists($newname)){
            Storage::disk('linode')->putFileAs(
                $dir,
                $file,
                $newname,
                'public'
              );
            
              if($today > $quiz2->deadline)
              {
                 $status = 2;
              }else {
                 $status = 1;
              }

              $q = DB::table('tblclassstudentquiz')->upsert([
                "userid" => Session::get('StudInfos')->ic,
                "quizid" => $id,
                "subdate" => $today,
                "content" => $newpath,
                "status" => 2,
                "status_submission" => $status
            ],['userid', 'quizid']);


            return redirect(route('student.quiz2.status',
        
             ['id' => $classid,'quiz2' => $id]
            ));
        }
     
    }


    public function quiz2resultstd(Request $request){
        
        $id = $request->quizid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $quiz2 = DB::table('tblclassstudentquiz')
            ->join('tblclassquiz', 'tblclassstudentquiz.quizid', 'tblclassquiz.id')
            ->leftJoin('students', 'tblclassstudentquiz.userid', 'students.ic')
            ->select('tblclassstudentquiz.*', 'tblclassstudentquiz.quizid', 'tblclassquiz.title',  
                DB::raw('tblclassquiz.content as original_content'), 
                'tblclassstudentquiz.return_content',
                'tblclassstudentquiz.userid',
                'tblclassstudentquiz.subdate',
                'tblclassstudentquiz.final_mark',
                'tblclassquiz.deadline',
                DB::raw('tblclassstudentquiz.status as studentquiz2status'),
                'students.name')
            ->where('tblclassstudentquiz.quizid', $id)
            ->where('tblclassstudentquiz.userid', $userid)->get()->first();

            //dd($id);
       
        $data['quiz2'] = $quiz2->content;
        $data['return'] = $quiz2->return_content;
        $data['mark'] = $quiz2->final_mark;
        $data['comments'] = $quiz2->comments;

        $data['quizid'] = $quiz2->quizid;
        $data['quiz2title'] = $quiz2->title;
        $data['quiz2userid'] = $quiz2->userid;
        $data['fullname'] = $quiz2->name;
        $data['created_at'] = $quiz2->created_at;
        $data['updated_at'] = $quiz2->updated_at;
        $data['subdate'] = $quiz2->subdate;
        $data['deadline'] = $quiz2->deadline;
        $data['studentquiz2status'] = $quiz2->studentquiz2status;

        return view('student.courseassessment.quiz2result', compact('data'));
    }


    function backupforadmincontroller()
    {
        foreach($lecturer[$key] as $key1 => $lct)
            {
                $folder[] = DB::table('lecturer_dir')->where('Addby', $lct->ic)->get();

                //dd($lct->ic);

                foreach($folder[$key1] as $key2 => $fdl)
                {
                    $subfolder[] = DB::table('material_dir')->where([['LecturerDirID', $fdl->DrID],['Addby', $lct->ic]])->get();

                    foreach($subfolder[$key2] as $key3 => $sfdl) 
                    {
                        $subfolder2[] = DB::table('materialsub_dir')->where([['MaterialDirID', $sfdl->DrID],['Addby', $lct->ic]])->get();

                        $directory = DB::table('lecturer_dir')
                        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*')
                        ->where([['material_dir.DrID', $sfdl->DrID]])->first();

                        //dd($directory);

                        $dir = "classmaterial/" . $directory->A . "/" . $directory->B;

                        $submaterial[]  = Storage::disk('linode')->files($dir);

                        foreach($subfolder2[$key3] as $sfd2)
                        {

                            $directorys = DB::table('lecturer_dir')
                            ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                            ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
                            ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'materialsub_dir.DrID')
                            ->where([['materialsub_dir.DrID', $sfd2->DrID]])->first();

                            $dir = "classmaterial/" . $directorys->A . "/" . $directorys->B . "/" . $directorys->C;

                            $classmaterial  = Storage::disk('linode')->allFiles($dir);

                        } 
                    }
                }
            }
    }
}



//THIS IS BACKUP LECTURERCONTROLLER REPORT

public function assessmentreport()
    {
        $overallquiz = [];
        $quizanswer = [];

        $overalltest = [];
        $testanswer = [];

        $overallassign = [];
        $assignanswer = [];

        $overallmidterm = [];
        $midtermanswer = [];

        $overallfinal = [];
        $finalanswer = [];

        $overallpaperwork = [];
        $paperworkanswer = [];

        $overallpractical = [];
        $practicalanswer = [];

        $overallother = [];
        $otheranswer = [];

        $user = Auth::user();

        $data = DB::table('user_subjek')
                  ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                  ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                  ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id')
                  ->where([
                     ['user_subjek.user_ic', $user->ic],
                     ['user_subjek.session_id', Session::get('SessionID')],
                     ['subjek.id', request()->id]
                  ]);
                   
        $groups = $data->groupBy('student_subjek.group_name')->get();

        foreach($groups as $grp)
        {

            $students[] = $data = DB::table('user_subjek')
            ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
            ->join('students', 'student_subjek.student_ic', 'students.ic')
            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id','students.*')
            ->where([
               ['user_subjek.user_ic', $user->ic],
               ['user_subjek.session_id', Session::get('SessionID')],
               ['subjek.id', request()->id]
            ])->where('student_subjek.group_name', $grp->group_name)->get();


        }

        //BACKUP ----------------------------------------------------------------------------------------------


        //QUIZ

        $quizs = DB::table('tblclassquiz')
        ->where([
            ['classid', request()->id],
            ['sessionid', Session::get('SessionID')]
        ]);


        $quiz = $quizs->get();


        $quizid = $quizs->pluck('id');

        $totalquiz = $quizs->sum('total_mark');

        //dd($quizid);


       
            foreach($students as $keys => $std)
            {
                foreach($quiz as $key =>$qz)
                {
                
                $quizanswer[$keys][] = DB::table('tblclassstudentquiz')->where('userid', $std->ic)->where('quizid', $qz->id)->first();

                }

                $sumquiz[] = DB::table('tblclassstudentquiz')->where('userid', $std->ic)->whereIn('quizid', $quizid)->sum('final_mark');
               
            }

        dd($sumquiz);


        //TEST

        $tests = DB::table('tblclasstest')
        ->where([
            ['classid', request()->id],
            ['sessionid', Session::get('SessionID')]
        ]);


        $test = $tests->get();


        $testid = $tests->pluck('id');

        $totaltest = $tests->sum('total_mark');

        foreach($students as $keys => $std)
        {
            foreach($test as $key =>$ts)
            {
            
            $testanswer[$keys][] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->where('testid', $ts->id)->first();

            }

            $sumtest[] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->whereIn('testid', $testid)->sum('final_mark');
            
        }


        //ASSIGNMENT

        $assigns = DB::table('tblclassassign')
        ->where([
            ['classid', request()->id],
            ['sessionid', Session::get('SessionID')]
        ]);


        $assign = $assigns->get();

        $assignid = $assigns->pluck('id');

        $totalassign = $assigns->sum('total_mark');


        foreach($students as $keys => $std)
        {
            foreach($assign as $key =>$as)
            {
            
            $assignanswer[$keys][] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->where('assignid', $as->id)->first();

            }

            $sumassign[] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->whereIn('assignid', $assignid)->sum('final_mark');
            
        }


        //MIDTERM

        $midterms = DB::table('tblclassmidterm')
        ->where([
            ['classid', request()->id],
            ['sessionid', Session::get('SessionID')]
        ]);

        $midterm = $midterms->get();

        $midtermid = $midterms->pluck('id');

        $totalmidterm = $midterms->sum('total_mark');

        foreach($students as $keys => $std)
        {
            foreach($midterm as $key =>$md)
            {
            
            $midtermanswer[$keys][] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->where('midtermid', $md->id)->first();

            }

            $summidterm[] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->whereIn('midtermid', $midtermid)->sum('final_mark');
            
        }

        //FINAL

        $finals = DB::table('tblclassfinal')
        ->where([
            ['classid', request()->id],
            ['sessionid', Session::get('SessionID')]
        ]);

        $final = $finals->get();

        $finalid = $finals->pluck('id');

        $totalfinal = $finals->sum('total_mark');

        foreach($students as $keys => $std)
        {
            foreach($final as $key =>$fn)
            {
            
            $finalanswer[$keys][] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->where('finalid', $fn->id)->first();

            }

            $sumfinal[] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->whereIn('finalid', $finalid)->sum('final_mark');
            
        }


        //PAPERWORK

        $paperworks = DB::table('tblclasspaperwork')
        ->where([
            ['classid', request()->id],
            ['sessionid', Session::get('SessionID')]
        ]);;

        $paperwork = $paperworks->get();

        $paperworkid = $paperworks->pluck('id');

        $totalpaperwork = $paperworks->sum('total_mark');

        foreach($students as $keys => $std)
        {
            foreach($paperwork as $key =>$pw)
            {
            
            $paperworkanswer[$keys][] = DB::table('tblclassstudentpaperwork')->where('userid', $std->ic)->where('paperworkid', $pw->id)->first();

            }

            $sumpaperwork[] = DB::table('tblclassstudentpaperwork')->where('userid', $std->ic)->whereIn('paperworkid', $paperworkid)->sum('final_mark');
            
        }


        //PRACTICAL

        $practicals = DB::table('tblclasspractical')
        ->where([
            ['classid', request()->id],
            ['sessionid', Session::get('SessionID')]
        ]);

        $practical = $practicals->get();

        $practicalid = $practicals->pluck('id');

        $totalpractical = $practicals->sum('total_mark');

        foreach($students as $keys => $std)
        {
            foreach($practical as $key =>$pr)
            {
            
            $practicalanswer[$keys][] = DB::table('tblclassstudentpractical')->where('userid', $std->ic)->where('practicalid', $pr->id)->first();

            }

            $sumpractical[] = DB::table('tblclassstudentpractical')->where('userid', $std->ic)->whereIn('practicalid', $practicalid)->sum('final_mark');
            
        }

        //dd($paperworkanswer);

        //OTHER

        $others = DB::table('tblclassother')
        ->where([
            ['classid', request()->id],
            ['sessionid', Session::get('SessionID')]
        ]);;

        $other = $others->get();

        $otherid = $others->pluck('id');

        $totalother = $others->sum('total_mark');

        foreach($students as $keys => $std)
        {
            foreach($other as $key =>$ot)
            {
            
            $otheranswer[$keys][] = DB::table('tblclassstudentother')->where('userid', $std->ic)->where('otherid', $ot->id)->first();

            }

            $sumother[] = DB::table('tblclassstudentother')->where('userid', $std->ic)->whereIn('otherid', $otherid)->sum('final_mark');
            
        }

        //dd($students);



        foreach($students as $key=>$std)
        {
            //QUIZ

            $percentquiz[] = DB::table('tblclassmarks')
                            ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                            ['subjek.sub_id', $std->course_id],
                            ['assessment', 'quiz']
                        ])->first();

            //dd($std->course_id);

            $totalquiz = DB::table('tblclassquiz')
                        ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                        ->join('student_subjek', function($data){
                            $data->on('tblclassquiz_group.groupname', 'student_subjek.group_name');
                            $data->on('tblclassquiz_group.groupid', 'student_subjek.group_id');
                        })
                        ->where([
                            ['tblclassquiz.classid', request()->id],
                            ['tblclassquiz.sessionid', Session::get('SessionID')],
                            ['student_subjek.student_ic', $std->ic]
                        ])
                        ->sum('total_mark');

            
            //dd($totalquiz);

            if($percentquiz[$key] != null)
            {
                if(DB::table('tblclassquiz')
                ->where([
                    ['classid', request()->id],
                    ['sessionid', Session::get('SessionID')]
                ])->exists()){
                    //dd($totalquiz);
                    $overallquiz[] = $sumquiz[$key] / $totalquiz * $percentquiz[$key]->mark_percentage;
                }else{
                    array_push($overallquiz, 0);
                }

            }else{
                array_push($overallquiz, 0);
            }



            //TEST

            $percenttest[] = DB::table('tblclassmarks')
                            ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                            ['subjek.sub_id', $std->course_id],
                            ['assessment', 'test']
                        ])->first();

            //dd($std->course_id);

            $totaltest = DB::table('tblclasstest')
                        ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                        ->join('student_subjek', function($data){
                            $data->on('tblclasstest_group.groupname', 'student_subjek.group_name');
                            $data->on('tblclasstest_group.groupid', 'student_subjek.group_id');
                        })
                        ->where([
                            ['tblclasstest.classid', request()->id],
                            ['tblclasstest.sessionid', Session::get('SessionID')],
                            ['student_subjek.student_ic', $std->ic]
                        ])
                        ->sum('total_mark');

            
            //dd($totaltest);

            if($percenttest[$key] != null)
            {
                if(DB::table('tblclasstest')
                ->where([
                    ['classid', request()->id],
                    ['sessionid', Session::get('SessionID')]
                ])->exists()){
                    //dd($percenttest[$key]->mark_percentage);
                    $overalltest[] = $sumtest[$key] / $totaltest * $percenttest[$key]->mark_percentage;
                }else{
                    array_push($overalltest, 0);
                }

            }else{
                array_push($overalltest, 0);
            }

            //ASSIGNMENT

            $percentassign[] = DB::table('tblclassmarks')
                            ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                            ['subjek.sub_id', $std->course_id],
                            ['assessment', 'assignment']
                        ])->first();

            $totalassign = DB::table('tblclassassign')
                        ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                        ->join('student_subjek', function($data){
                            $data->on('tblclassassign_group.groupname', 'student_subjek.group_name');
                            $data->on('tblclassassign_group.groupid', 'student_subjek.group_id');
                        })
                        ->where([
                            ['tblclassassign.classid', request()->id],
                            ['tblclassassign.sessionid', Session::get('SessionID')],
                            ['student_subjek.student_ic', $std->ic]
                        ])->sum('total_mark');

            if($percentassign[$key] != null)
            {
                if(DB::table('tblclassassign')
                ->where([
                    ['classid', request()->id],
                    ['sessionid', Session::get('SessionID')]
                ])->exists()){
                    $overallassign[] = $sumassign[$key] / $totalassign * $percentassign[$key]->mark_percentage;
                }else{
                    array_push($overallassign, 0);
                }

            }else{
                array_push($overallassign, 0);
            }

            //MIDTERM

            $percentmidterm[] = DB::table('tblclassmarks')
                            ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                            ['subjek.sub_id', $std->course_id],
                            ['assessment', 'midterm']
                        ])->first();

            $totalmidterm = DB::table('tblclassmidterm')
                        ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                        ->join('student_subjek', function($data){
                            $data->on('tblclassmidterm_group.groupname', 'student_subjek.group_name');
                            $data->on('tblclassmidterm_group.groupid', 'student_subjek.group_id');
                        })
                        ->where([
                            ['tblclassmidterm.classid', request()->id],
                            ['tblclassmidterm.sessionid', Session::get('SessionID')],
                            ['student_subjek.student_ic', $std->ic]
                        ])->sum('total_mark');

            if($percentmidterm[$key] != null)
            {

                if(DB::table('tblclassmidterm')
                ->where([
                    ['classid', request()->id],
                    ['sessionid', Session::get('SessionID')]
                ])->exists()){
                    $overallmidterm[] = $summidterm[$key] / $totalmidterm * $percentmidterm[$key]->mark_percentage;
                }else{
                    array_push($overallmidterm, 0);
                }

            }else{
                array_push($overallmidterm, 0);
            }
            
            //FINAL

            $percentfinal[] = DB::table('tblclassmarks')
                            ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                            ['subjek.sub_id', $std->course_id],
                            ['assessment', 'final']
                        ])->first();

            $totalfinal = DB::table('tblclassfinal')
                        ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                        ->join('student_subjek', function($data){
                            $data->on('tblclassfinal_group.groupname', 'student_subjek.group_name');
                            $data->on('tblclassfinal_group.groupid', 'student_subjek.group_id');
                        })
                        ->where([
                            ['tblclassfinal.classid', request()->id],
                            ['tblclassfinal.sessionid', Session::get('SessionID')],
                            ['student_subjek.student_ic', $std->ic]
                        ])->sum('total_mark');

            if($percentfinal[$key] != null)
            {

                if(DB::table('tblclassfinal')
                ->where([
                    ['classid', request()->id],
                    ['sessionid', Session::get('SessionID')]
                ])->exists()){
                    $overallfinal[] = $sumfinal[$key] / $totalfinal * $percentfinal[$key]->mark_percentage;
                }else{
                    array_push($overallfinal, 0);
                }
           
            }else{
                array_push($overallfinal, 0);
            }

            //PAPERWORK

            $percentpaperwork[] = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.sub_id', $std->course_id],
                                ['assessment', 'paperwork']
                            ])->first();

            $totalpaperwork = DB::table('tblclasspaperwork')
                        ->join('tblclasspaperwork_group', 'tblclasspaperwork.id', 'tblclasspaperwork_group.paperworkid')
                        ->join('student_subjek', function($data){
                            $data->on('tblclasspaperwork_group.groupname', 'student_subjek.group_name');
                            $data->on('tblclasspaperwork_group.groupid', 'student_subjek.group_id');
                        })
                        ->where([
                            ['tblclasspaperwork.classid', request()->id],
                            ['tblclasspaperwork.sessionid', Session::get('SessionID')],
                            ['student_subjek.student_ic', $std->ic]
                        ])->sum('total_mark');

            if($percentpaperwork[$key] != null)
            {

                if(DB::table('tblclasspaperwork')
                ->where([
                    ['classid', request()->id],
                    ['sessionid', Session::get('SessionID')]
                ])->exists()){
                    $overallpaperwork[] = $sumpaperwork[$key] / $totalpaperwork * $percentpaperwork[$key]->mark_percentage;
                }else{
                    array_push($overallpaperwork, 0);
                }

            }else{
                array_push($overallpaperwork, 0);
            }

            //PRACTICAL

            $percentpractical[] = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.sub_id', $std->course_id],
                                ['assessment', 'practical']
                            ])->first();

            $totalpractical = DB::table('tblclasspractical')
                        ->join('tblclasspractical_group', 'tblclasspractical.id', 'tblclasspractical_group.practicalid')
                        ->join('student_subjek', function($data){
                            $data->on('tblclasspractical_group.groupname', 'student_subjek.group_name');
                            $data->on('tblclasspractical_group.groupid', 'student_subjek.group_id');
                        })
                        ->where([
                            ['tblclasspractical.classid', request()->id],
                            ['tblclasspractical.sessionid', Session::get('SessionID')],
                            ['student_subjek.student_ic', $std->ic]
                        ])->sum('total_mark');

            if($percentpractical[$key] != null)
            {
                if(DB::table('tblclasspractical')
                ->where([
                    ['classid', request()->id],
                    ['sessionid', Session::get('SessionID')]
                ])->exists()){
                    $overallpractical[] = $sumpractical[$key] / $totalpractical * $percentpractical[$key]->mark_percentage;
                }else{
                    array_push($overallpractical, 0);
                }

            }else{
                array_push($overallpractical, 0);
            }

            //OTHER

            $percentother[] = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.sub_id', $std->course_id],
                                ['assessment', 'lain-lain']
                            ])->first();

            $totalother = DB::table('tblclassother')
                        ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                        ->join('student_subjek', function($data){
                            $data->on('tblclassother_group.groupname', 'student_subjek.group_name');
                            $data->on('tblclassother_group.groupid', 'student_subjek.group_id');
                        })
                        ->where([
                            ['tblclassother.classid', request()->id],
                            ['tblclassother.sessionid', Session::get('SessionID')],
                            ['student_subjek.student_ic', $std->ic]
                        ])->sum('total_mark');

            if($percentother[$key] != null)
            {
                if(DB::table('tblclassother')
                ->where([
                    ['classid', request()->id],
                    ['sessionid', Session::get('SessionID')]
                ])->exists()){
                    $overallother[] = $sumother[$key] / $totalother * $percentother[$key]->mark_percentage;
                }else{
                    array_push($overallother, 0);
                }

            }else{
                array_push($overallother, 0);
            }
            
        }
        
        //dd($totalquiz);

        return view('lecturer.courseassessment.studentreport', compact('students',
                                                                       'quiz', 'quizanswer',
                                                                       'test', 'testanswer',
                                                                       'assign', 'assignanswer', 
                                                                       'midterm', 'midtermanswer',
                                                                       'final', 'finalanswer',
                                                                       'paperwork', 'paperworkanswer',
                                                                       'practical', 'practicalanswer', 
                                                                       'other', 'otheranswer',
                                                                       'overallquiz', 'overalltest', 'overallmidterm', 'overallfinal', 'overallassign', 'overallpaperwork', 'overallpractical', 'overallother'));

    }