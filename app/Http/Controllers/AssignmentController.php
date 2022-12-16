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
use Response;

class AssignmentController extends Controller
{
    public function assignlist()
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

        $data = DB::table('tblclassassign')->join('tblclassassignstatus', 'tblclassassign.status', 'tblclassassignstatus.id')
                ->where([
                    ['tblclassassign.classid', Session::get('CourseIDS')],
                    ['tblclassassign.sessionid', Session::get('SessionIDS')],
                    ['tblclassassign.addby', $user->ic],
                    ['tblclassassign.status', '!=', 3],
                    ['tblclassassign.deadline','!=', null]
                ])->select('tblclassassign.*', 'tblclassassignstatus.statusname')->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassassign_group')
                        ->join('user_subjek', 'tblclassassign_group.groupid', 'user_subjek.id')
                        ->where('tblclassassign_group.assignid', $dt->id)->get();

                $chapter[] = DB::table('tblclassassign_chapter')
                        ->join('material_dir', 'tblclassassign_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassassign_chapter.assignid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.assignment', compact('data', 'group', 'chapter'));
    }

    public function deleteassign(Request $request)
    {

        try {

            $assign = DB::table('tblclassassign')->where('id', $request->id)->first();

            if($assign->status != 3)
            {
            DB::table('tblclassassign')->where('id', $request->id)->update([
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

    public function assigncreate()
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

        $subid = DB::table('subjek')->where('id', $courseid)->pluck('sub_id');

        $folder = DB::table('lecturer_dir')
                  ->join('subjek', 'lecturer_dir.CourseID','subjek.id')
                  ->where('subjek.sub_id', $subid)
                  ->where('Addby', $user->ic)->get();

        //dd($folder);

        return view('lecturer.courseassessment.assignmentcreate', compact(['group', 'folder']));
    }

    public function getChapters(Request $request)
    {

        $subchapter = DB::table('material_dir')->where('LecturerDirID', $request->folder)->get();

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>Sub Chapter</th>
            <th>Name</th>
            <th></th>
            </thead>
            <tbody>
        ';
        foreach($subchapter as $sub){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <label>'.$sub->ChapterNo.'</label>
                </td>
                <td >
                    <label>'.$sub->DrName.'</label>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="chapter_checkbox_'.$sub->DrID.'"
                            class="filled-in" name="chapter[]" value="'.$sub->DrID.'" 
                        >
                        <label for="chapter_checkbox_'.$sub->DrID.'"> </label>
                    </div>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';

            return $content;

    }

    public function insertassign(Request $request)
    {

        $user = Auth::user();

        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');

        $data = $request->validate([
            'assign-title' => ['required', 'string'],
            'assign-duration' => ['required'],
            'total-marks' => ['required'],
            'myPdf' => 'required', 'mimes:pdf'
        ]);

        $dir = "classassignment/" .  $classid . "/" . $user->name . "/" . $data['assign-title'];

        $classassign  = Storage::disk('linode')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classassignment/" .  $classid . "/" . $user->name . "/" . $data['assign-title'] . "/" . $newname;

        if($request->chapter != null && $request->chapter != null)
        {

            if(! file_exists($newname)){
                Storage::disk('linode')->putFileAs(
                    $dir,
                    $file,
                    $newname,
                    'public'
                );

                $q = DB::table('tblclassassign')->insertGetId([
                    'classid' => $classid,
                    'sessionid' => $sessionid,
                    'status' => 2,
                    'title' => $data['assign-title'],
                    'content' => $newpath,
                    'deadline' => $data['assign-duration'],
                    'total_mark' => $data['total-marks'],
                    'addby' => $user->ic
                ]);

                foreach($request->group as $grp)
                {
                    $gp = explode('|', $grp);

                    DB::table('tblclassassign_group')->insert([
                        "groupid" => $gp[0],
                        "groupname" => $gp[1],
                        "assignid" => $q
                    ]);
                }

                foreach($request->chapter as $chp)
                {
                    DB::table('tblclassassign_chapter')->insert([
                        "chapterid" => $chp,
                        "assignid" => $q
                    ]);
                }

                return redirect(route('lecturer.assign', ['id' => $classid]));
            }

        }else{

            return redirect()->back()->withErrors(['Please fill in the group and sub-chapter checkbox !']);

        }

    }

    public function lecturerassignstatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassassign_group', 'user_subjek.id', 'tblclassassign_group.groupid')
                ->join('tblclassassign', 'tblclassassign_group.assignid', 'tblclassassign.id')
                ->where([
                    ['tblclassassign.classid', Session::get('CourseIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassassign.id', request()->assign]
                ])->get();
            
        //dd($group);

        $assign = DB::table('student_subjek')
                ->join('tblclassassign_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassassign_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassassign_group.groupname');
                })
                ->join('tblclassassign', 'tblclassassign_group.assignid', 'tblclassassign.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassassign.id AS clssid', 'tblclassassign.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassassign.classid', Session::get('CourseIDS')],
                    ['tblclassassign.sessionid', Session::get('SessionIDS')],
                    ['tblclassassign.id', request()->assign],
                    ['tblclassassign.addby', $user->ic]
                ])->orderBy('students.name')->get();
        
        //dd($assign);

        foreach($assign as $qz)
        {
            $status[] = DB::table('tblclassstudentassign')
            ->where([
                ['assignid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($assign);

        return view('lecturer.courseassessment.assignmentstatus', compact('assign', 'status', 'group'));

    }

    public function assignresult(Request $request){
        
        $id = $request->assignid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $assign = DB::table('tblclassstudentassign')
            ->join('tblclassassign', 'tblclassstudentassign.assignid', 'tblclassassign.id')
            ->leftJoin('students', 'tblclassstudentassign.userid', 'students.ic')
            ->select('tblclassstudentassign.*', 'tblclassstudentassign.assignid', 'tblclassassign.title',  
                DB::raw('tblclassassign.content as original_content'),
                'tblclassstudentassign.return_content', 
                'tblclassstudentassign.userid',
                'tblclassstudentassign.subdate',
                'tblclassstudentassign.final_mark',
                DB::raw('tblclassstudentassign.status as studentassignstatus'),
                'tblclassassign.deadline','tblclassassign.total_mark','students.name', 'students.ic')
            ->where('tblclassstudentassign.assignid', $id)
            ->where('tblclassstudentassign.userid', $userid)->get()->first();

       
        $data['assign'] = $assign->content;
        //dd($data['assign']);
        $data['return'] = $assign->return_content;
        $data['comments'] = $assign->comments;
        $data['mark'] = $assign->final_mark;
        $data['assignid'] = $assign->assignid;
        $data['assigntitle'] = $assign->title;
        $data['totalmark'] = $assign->total_mark;
        $data['assigndeadline'] = $assign->deadline;
        $data['assignuserid'] = $assign->userid;
        $data['fullname'] = $assign->name;
        $data['IC'] = $assign->ic;
        $data['created_at'] = $assign->created_at;
        $data['updated_at'] = $assign->updated_at;
        $data['subdate'] = $assign->subdate;
        $data['studentassignstatus'] = $assign->studentassignstatus;

        return view('lecturer.courseassessment.assignmentresult', compact('data'));
    }

    public function updateassignresult(Request $request){
        $assign = $request->id;
        $participant = $request->participant;
        $final_mark = $request->markss;
        $comments = $request->commentss;
        $classid = Session::get('CourseIDS');
        //$total_mark = $request->total_mark;
        //$data = $request->data;

        $assignment = DB::table('tblclassassign')
                      ->join('users', 'tblclassassign.addby', 'users.ic')
                      ->where('tblclassassign.id', $assign)
                      ->first();

        //dd($assignment);

        $dir = "classassignment/" .  $classid . "/" . $assignment->name . "/" . $assignment->title . "/" . $participant . "/return";

        $classassign  = Storage::disk('linode')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classassignment/" .  $classid . "/" . $assignment->name . "/" . $assignment->title . "/" . $participant . "/return" . "/" . $newname;

        Storage::disk('linode')->putFileAs(
            $dir,
            $file,
            $newname,
            'public'
          );
      
        $q = \DB::table('tblclassstudentassign')
            ->where('assignid', $assign)
            ->where("userid", $participant)
            ->update([
                "return_content" => $newpath,
                "final_mark" => $final_mark,
                //"total_mark" => $total_mark,
                "comments" => $comments,
                "status" => 3
            ]);
        
            return redirect(route('lecturer.assign.status',
        
            ['id' => $classid,'assign' => $assign]
           ));
    }


    //STUDENT ASSIGNMENT

    public function studentassignlist()
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

        $data = DB::table('tblclassassign')
                ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                ->join('student_subjek', function($join){
                    $join->on('tblclassassign_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassassign_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassassign_group.groupid', 'user_subjek.id')
                ->select('tblclassassign.*', 'tblclassassign_group.groupname')
                ->where([
                    ['tblclassassign.classid', Session::get('CourseIDS')],
                    ['tblclassassign.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic],
                    ['tblclassassign.status','!=', 3]
                ])->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassassign_group')
                        ->join('user_subjek', 'tblclassassign_group.groupid', 'user_subjek.id')
                        ->where('tblclassassign_group.assignid', $dt->id)->get();

                $chapter[] = DB::table('tblclassassign_chapter')
                        ->join('material_dir', 'tblclassassign_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassassign_chapter.assignid', $dt->id)->get();
            }
      

        return view('student.courseassessment.assignment', compact('data', 'group', 'chapter'));
    }

    public function studentassignstatus()
    {
        $assign = DB::table('student_subjek')
                ->join('tblclassassign_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassassign_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassassign_group.groupname');
                })
                ->join('tblclassassign', 'tblclassassign_group.assignid', 'tblclassassign.id')
                ->where([
                    ['tblclassassign.classid', Session::get('CourseIDS')],
                    ['tblclassassign.sessionid', Session::get('SessionIDS')],
                    ['tblclassassign.id', request()->assign],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();
                        
        foreach($assign as $qz)
        {
            $status[] = DB::table('tblclassstudentassign')
            ->where([
                ['assignid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.assignmentstatus', compact('assign', 'status'));
    }

    public function assignview(Request $request){

        $id = $request->assign;
        $assign = DB::table('tblclassassign')
            ->where('tblclassassign.id', $id)
            ->get()->first();


        
            $data['assignid'] = $assign->id;
            $data['assigntitle'] = $assign->title;
            $data['assigndeadline'] = $assign->deadline;
            $data['created_at'] = $assign->created_at;
            $data['updated_at'] = $assign->updated_at;
    
            return view('student.courseassessment.assignmentanswer', compact('data'));
        
    }

    public function submitassign(Request $request){
        $id = $request->id;

        $assignment = DB::table('tblclassassign')
                      ->join('users', 'tblclassassign.addby', 'users.ic')
                      ->where('tblclassassign.id', $id)
                      ->first();
        
        //dd($assignment);

        $classid = Session::get('CourseIDS');

        $stud = Session::get('StudInfos');

        $dir = "classassignment/" .  $classid . "/" . $assignment->name . "/" . $assignment->title . "/" . $stud->ic;

        //$classassign  = Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classassignment/" .  $classid . "/" . $assignment->name . "/" . $assignment->title . "/" . $stud->ic . "/" . $newname;
        
        $today = date("Y-m-d H:i:s");

        if(! file_exists($newname)){
            Storage::disk('linode')->putFileAs(
                $dir,
                $file,
                $newname,
                'public'
              );
            
              if($today > $assignment->deadline)
              {
                 $status = 2;
              }else {
                 $status = 1;
              }

              $q = DB::table('tblclassstudentassign')->upsert([
                "userid" => Session::get('StudInfos')->ic,
                "assignid" => $id,
                "subdate" => $today,
                "content" => $newpath,
                "status" => 2,
                "status_submission" => $status
            ],['userid', 'assignid']);


            return redirect(route('student.assign.status',
        
             ['id' => $classid,'assign' => $id]
            ));
        }
     
    }


    public function assignresultstd(Request $request){
        
        $id = $request->assignid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $assign = DB::table('tblclassstudentassign')
            ->join('tblclassassign', 'tblclassstudentassign.assignid', 'tblclassassign.id')
            ->leftJoin('students', 'tblclassstudentassign.userid', 'students.ic')
            ->select('tblclassstudentassign.*', 'tblclassstudentassign.assignid', 'tblclassassign.title',  
                DB::raw('tblclassassign.content as original_content'), 
                'tblclassstudentassign.return_content',
                'tblclassstudentassign.userid',
                'tblclassstudentassign.subdate',
                'tblclassstudentassign.final_mark',
                'tblclassassign.deadline',
                DB::raw('tblclassstudentassign.status as studentassignstatus'),
                'students.name')
            ->where('tblclassstudentassign.assignid', $id)
            ->where('tblclassstudentassign.userid', $userid)->get()->first();

       
        $data['assign'] = $assign->content;
        $data['return'] = $assign->return_content;
        $data['mark'] = $assign->final_mark;
        $data['comments'] = $assign->comments;

        $data['assignid'] = $assign->assignid;
        $data['assigntitle'] = $assign->title;
        $data['assignuserid'] = $assign->userid;
        $data['fullname'] = $assign->name;
        $data['created_at'] = $assign->created_at;
        $data['updated_at'] = $assign->updated_at;
        $data['subdate'] = $assign->subdate;
        $data['deadline'] = $assign->deadline;
        $data['studentassignstatus'] = $assign->studentassignstatus;

        return view('student.courseassessment.assignmentresult', compact('data'));
    }

    //THIS IS ASSIGNMENT PART 2


    public function assign2list()
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

        $data = DB::table('tblclassassign')
                ->join('users', 'tblclassassign.addby', 'users.ic')->join('tblclassassignstatus', 'tblclassassign.status', 'tblclassassignstatus.id')
                ->where([
                    ['tblclassassign.classid', Session::get('CourseIDS')],
                    ['tblclassassign.sessionid', Session::get('SessionIDS')],
                    ['tblclassassign.addby', $user->ic],
                    ['tblclassassign.deadline', null],
                    ['tblclassassign.status', '!=', 3]
                ])
                ->select('tblclassassign.*', 'users.name AS addby', 'tblclassassignstatus.statusname')->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassassign_group')
                        ->join('user_subjek', 'tblclassassign_group.groupid', 'user_subjek.id')
                        ->where('tblclassassign_group.assignid', $dt->id)->get();

                $chapter[] = DB::table('tblclassassign_chapter')
                        ->join('material_dir', 'tblclassassign_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassassign_chapter.assignid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.assignment2', compact('data', 'group', 'chapter'));
    }

    public function assign2create()
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
            //['assessment', 'assign']
        //])->first();

        //$markassign =  DB::table('tblclassassign')->where([
           // ['classid', $courseid],
           // ['sessionid', $sessionid],
            //['addby', $user->ic]
        //])->sum('total_mark');

        //if($markassign != null)
        //{
         //   $totalpercent = $percentage->mark_percentage - $markassign;
        //}else{
        //    $totalpercent = $percentage->mark_percentage;
        //}

        //dd($totalpercent);

        return view('lecturer.courseassessment.assignment2create', compact(['group', 'folder']));
    }


    public function insertassign2(Request $request){
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

        $dir = "classassign2/" .  $classid . "/" . $user->name . "/" . $title;
        $classassign2  = Storage::disk('linode')->makeDirectory($dir);
        $file = $request->file('myPdf');
            
        $assignid = empty($request->assign) ? '' : $request->assign;

        if($group != null && $chapter != null)
        {
        
            if( !empty($assignid) ){
                $q = DB::table('tblclassassign')->where('id', $assignid)->update([
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
                $newpath = "classassign2/" .  $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                if(!file_exists($newname)){
                    Storage::disk('linode')->putFileAs(
                        $dir,
                        $file,
                        $newname,
                        'public'
                    );

                    $q = DB::table('tblclassassign')->insertGetId([
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

                        DB::table('tblclassassign_group')->insert([
                            "groupid" => $gp[0],
                            "groupname" => $gp[1],
                            "assignid" => $q
                        ]);
                    }

                    foreach($request->chapter as $chp)
                    {
                        DB::table('tblclassassign_chapter')->insert([
                            "chapterid" => $chp,
                            "assignid" => $q
                        ]);
                    }

                }

            }

        }else{

            return redirect()->back()->withErrors(['Please fill in the group and sub-chapter checkbox !']);

        }
        
        return redirect(route('lecturer.assign2', ['id' => $classid]));
    }

    public function lecturerassign2status()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassassign_group', 'user_subjek.id', 'tblclassassign_group.groupid')
                ->join('tblclassassign', 'tblclassassign_group.assignid', 'tblclassassign.id')
                ->where([
                    ['tblclassassign.classid', Session::get('CourseIDS')],
                    ['tblclassassign.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassassign.id', request()->assign]
                ])->get();
                
            
        //dd($group);

        $assign = DB::table('student_subjek')
                ->join('tblclassassign_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassassign_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassassign_group.groupname');
                })
                ->join('tblclassassign', 'tblclassassign_group.assignid', 'tblclassassign.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassassign.id AS clssid', 'tblclassassign.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassassign.classid', Session::get('CourseIDS')],
                    ['tblclassassign.sessionid', Session::get('SessionIDS')],
                    ['tblclassassign.id', request()->assign],
                    ['tblclassassign.addby', $user->ic]
                ])->get();
        
        
        
        //dd($assign);

        foreach($assign as $qz)
        {
            //$status[] = DB::table('tblclassstudentassign')
            //->where([
               // ['assignid', $qz->clssid],
               // ['userid', $qz->student_ic]
           // ])->get();

           if(!DB::table('tblclassstudentassign')->where([['assignid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentassign')->insert([
                    'assignid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentassign')
            ->where([
                ['assignid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        //dd($status);

        return view('lecturer.courseassessment.assignment2status', compact('assign', 'status', 'group'));

    }

    public function updateassign2(Request $request)
    {
        $user = Auth::user();

        $marks = json_decode($request->marks);

        $ics = json_decode($request->ics);

        $assignid = json_decode($request->assignid);

        $limitpercen = DB::table('tblclassassign')->where('id', $assignid)->first();

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
            'assignid' => $assignid,
            'submittime' => date("Y-m-d H:i:s"),
            'final_mark' => $mrk,
            'status' => 1
            ]);
        }

        DB::table('tblclassstudentassign')->upsert($upsert, ['userid', 'assignid']);

        return ["message"=>"Success", "id" => $ics];

    }

    //This is assignment 2 Student Controller


    public function studentassign2list()
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

        $data = DB::table('tblclassassign')
                ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                ->join('tblclassassignstatus', 'tblclassassign.status', 'tblclassassignstatus.id')
                ->join('student_subjek', function($join){
                    $join->on('tblclassassign_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassassign_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassassign_group.groupid', 'user_subjek.id')
                ->select('tblclassassign.*', 'tblclassassign_group.groupname','tblclassassignstatus.statusname')
                ->where([
                    ['tblclassassign.classid', Session::get('CourseIDS')],
                    ['tblclassassign.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic],
                    ['tblclassassign.deadline', null],
                    ['tblclassassign.status','!=', 3]
                ])->get();

        //dd($data);

        foreach($data as $dt)
        {
            $chapter[] = DB::table('tblclassassign_chapter')
                      ->join('material_dir', 'tblclassassign_chapter.chapterid', 'material_dir.DrID')
                      ->where('tblclassassign_chapter.assignid', $dt->id)->get();

            $marks[] = DB::table('tblclassstudentassign')
                      ->where([
                        ['assignid', $dt->id],
                        ['userid', $student->ic]
                      ])->get();
        }

        //dd($marks);

        return view('student.courseassessment.assignment2', compact('data', 'chapter', 'marks'));

    }

}
