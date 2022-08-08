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
