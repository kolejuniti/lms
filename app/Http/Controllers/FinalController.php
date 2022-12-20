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

class FinalController extends Controller
{
    
    public function finallist()
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

        $data = DB::table('tblclassfinal')
                ->join('users', 'tblclassfinal.addby', 'users.ic')->join('tblclassfinalstatus', 'tblclassfinal.status', 'tblclassfinalstatus.id')
                ->where([
                    ['tblclassfinal.classid', Session::get('CourseIDS')],
                    ['tblclassfinal.sessionid', Session::get('SessionIDS')],
                    ['tblclassfinal.addby', $user->ic],
                    ['tblclassfinal.deadline', null],
                    ['tblclassfinal.status', '!=', 3]
                ])
                ->select('tblclassfinal.*', 'users.name AS addby', 'tblclassfinalstatus.statusname')->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassfinal_group')
                        ->join('user_subjek', 'tblclassfinal_group.groupid', 'user_subjek.id')
                        ->where('tblclassfinal_group.finalid', $dt->id)->get();

                $chapter[] = DB::table('tblclassfinal_chapter')
                        ->join('material_dir', 'tblclassfinal_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassfinal_chapter.finalid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.final', compact('data', 'group', 'chapter'));
    }

    public function finalcreate()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseIDS');

        $sessionid = Session::get('SessionIDS');

        //$totalpercent = 0;

        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->where([
            ['subjek.id', $courseid],
            ['user_subjek.session_id', $sessionid],
            ['user_subjek.user_ic', $user->ic]
        ])->groupBy('student_subjek.group_name')
        ->select('user_subjek.*', 'subjek.course_name', 'student_subjek.group_name')->get();

        //dd(Session::get('CourseIDS'));

        $subid = DB::table('subjek')->where('id', $courseid)->pluck('sub_id');

        $folder = DB::table('lecturer_dir')
                  ->join('subjek', 'lecturer_dir.CourseID','subjek.id')
                  ->where('subjek.sub_id', $subid)
                  ->where('Addby', $user->ic)->get();

        //dd($folder);

        //$percentage = DB::table('tblclassmarks')->where([
            //['course_id', $courseid],
            //['assessment', 'final']
        //])->first();

        //$markfinal =  DB::table('tblclassfinal')->where([
           // ['classid', $courseid],
           // ['sessionid', $sessionid],
            //['addby', $user->ic]
        //])->sum('total_mark');

        //if($markfinal != null)
        //{
         //   $totalpercent = $percentage->mark_percentage - $markfinal;
        //}else{
        //    $totalpercent = $percentage->mark_percentage;
        //}

        //dd($totalpercent);

        return view('lecturer.courseassessment.finalcreate', compact(['group', 'folder']));
    }

    public function deletefinal(Request $request)
    {

        try {

            $final = DB::table('tblclassfinal')->where('id', $request->id)->first();

            if($final->status != 3)
            {
            DB::table('tblclassfinal')->where('id', $request->id)->update([
                'status' => 3
            ]);

            return true;

            }else{

                die;

            }
          
          } catch (\Exception $e) {
          
              return $e->getMessage();
          }
    }


    public function insertfinal(Request $request){
        //$data = $request->data;
        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');
        $title = $request->title;
        $group = $request->group;
        $chapter = $request->chapter;
        $marks = $request->marks;

        $data = $request->validate([
            'myPdf' => 'required', 'mimes:pdf'
        ]);

        $user = Auth::user();

        $dir = "classfinal/" .  $classid . "/" . $user->name . "/" . $title;
        $classfinal  = Storage::disk('linode')->makeDirectory($dir);
        $file = $request->file('myPdf');
            
        $finalid = empty($request->final) ? '' : $request->final;

        if($group != null && $chapter != null)
        {
        
            if( !empty($finalid) ){
                $q = DB::table('tblclassfinal')->where('id', $finalid)->update([
                    "title" => $title,
                    "total_mark" => $marks,
                    "addby" => $user->ic,
                    "status" => 2
                ]);
            }else{
                $file_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $fileInfo = pathinfo($file_name);
                $filename = $fileInfo['filename'];
                $newname = $filename . "." . $file_ext;
                $newpath = "classfinal/" .  $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                if(!file_exists($newname)){
                    Storage::disk('linode')->putFileAs(
                        $dir,
                        $file,
                        $newname,
                        'public'
                    );

                    $q = DB::table('tblclassfinal')->insertGetId([
                        "classid" => $classid,
                        "sessionid" => $sessionid,
                        "title" => $title,
                        'content' => $newpath,
                        "total_mark" => $marks,
                        "addby" => $user->ic,
                        "status" => 2
                    ]);

                    foreach($request->group as $grp)
                    {
                        $gp = explode('|', $grp);

                        DB::table('tblclassfinal_group')->insert([
                            "groupid" => $gp[0],
                            "groupname" => $gp[1],
                            "finalid" => $q
                        ]);
                    }

                    foreach($request->chapter as $chp)
                    {
                        DB::table('tblclassfinal_chapter')->insert([
                            "chapterid" => $chp,
                            "finalid" => $q
                        ]);
                    }

                }

            }

        }else{

            return redirect()->back()->withErrors(['Please fill in the group and sub-chapter checkbox !']);

        }
        
        return redirect(route('lecturer.final', ['id' => $classid]));
    }

    public function lecturerfinalstatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassfinal_group', 'user_subjek.id', 'tblclassfinal_group.groupid')
                ->join('tblclassfinal', 'tblclassfinal_group.finalid', 'tblclassfinal.id')
                ->where([
                    ['tblclassfinal.classid', Session::get('CourseIDS')],
                    ['tblclassfinal.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassfinal.id', request()->final]
                ])->get();
                
            
        //dd($group);

        $final = DB::table('student_subjek')
                ->join('tblclassfinal_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassfinal_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassfinal_group.groupname');
                })
                ->join('tblclassfinal', 'tblclassfinal_group.finalid', 'tblclassfinal.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassfinal.id AS clssid', 'tblclassfinal.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassfinal.classid', Session::get('CourseIDS')],
                    ['tblclassfinal.sessionid', Session::get('SessionIDS')],
                    ['tblclassfinal.id', request()->final],
                    ['tblclassfinal.addby', $user->ic]
                ])->get();
        
        
        
        //dd($final);

        foreach($final as $qz)
        {
            //$status[] = DB::table('tblclassstudentfinal')
            //->where([
               // ['finalid', $qz->clssid],
               // ['userid', $qz->student_ic]
           // ])->get();

           if(!DB::table('tblclassstudentfinal')->where([['finalid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentfinal')->insert([
                    'finalid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentfinal')
            ->where([
                ['finalid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        //dd($status);

        return view('lecturer.courseassessment.finalstatus', compact('final', 'status', 'group'));

    }

    public function updatefinal(Request $request)
    {
        $user = Auth::user();

        $marks = json_decode($request->marks);

        $ics = json_decode($request->ics);

        $finalid = json_decode($request->finalid);

        $limitpercen = DB::table('tblclassfinal')->where('id', $finalid)->first();

        foreach($marks as $key => $mrk)
        {

            if($mrk > $limitpercen->total_mark)
            {
                return ["message"=>"Field Error", "id" => $ics];
            }

        }

       
        $upsert = [];
        foreach($marks as $key => $mrk){
            array_push($upsert, [
            'userid' => $ics[$key],
            'finalid' => $finalid,
            'submittime' => date("Y-m-d H:i:s"),
            'final_mark' => $mrk,
            'status' => 1
            ]);
        }

        DB::table('tblclassstudentfinal')->upsert($upsert, ['userid', 'finalid']);

        return ["message"=>"Success", "id" => $ics];

    }

    //This is final 2 Student Controller


    public function studentfinallist()
    {
        $chapter = [];

        $marks = [];
        
        Session::put('CourseIDS', request()->id);

        if(Session::get('SessionIDS') == null)
        {
        Session::put('SessionIDS', request()->session);
        }

        $student = auth()->guard('student')->user();

        Session::put('StudInfos', $student);

        //dd($student->ic);

        $data = DB::table('tblclassfinal')
                ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                ->join('tblclassfinalstatus', 'tblclassfinal.status', 'tblclassfinalstatus.id')
                ->join('student_subjek', function($join){
                    $join->on('tblclassfinal_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassfinal_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassfinal_group.groupid', 'user_subjek.id')
                ->select('tblclassfinal.*', 'tblclassfinal_group.groupname','tblclassfinalstatus.statusname')
                ->where([
                    ['tblclassfinal.classid', Session::get('CourseIDS')],
                    ['tblclassfinal.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic],
                    ['tblclassfinal.deadline', null],
                    ['tblclassfinal.status','!=', 3]
                ])->get();

        //dd($data);

        foreach($data as $dt)
        {
            $chapter[] = DB::table('tblclassfinal_chapter')
                      ->join('material_dir', 'tblclassfinal_chapter.chapterid', 'material_dir.DrID')
                      ->where('tblclassfinal_chapter.finalid', $dt->id)->get();

            $marks[] = DB::table('tblclassstudentfinal')
                      ->where([
                        ['finalid', $dt->id],
                        ['userid', $student->ic]
                      ])->get();
        }

        //dd($marks);

        return view('student.courseassessment.final', compact('data', 'chapter', 'marks'));

    }
}
