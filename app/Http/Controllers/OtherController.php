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

class OtherController extends Controller
{
    public function otherlist()
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

        $data = DB::table('tblclassother')
                ->where([
                    ['classid', Session::get('CourseIDS')],
                    ['sessionid', Session::get('SessionIDS')],
                    ['addby', $user->ic]
                ])->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassother_group')
                        ->join('user_subjek', 'tblclassother_group.groupid', 'user_subjek.id')
                        ->where('tblclassother_group.otherid', $dt->id)->get();

                $chapter[] = DB::table('tblclassother_chapter')
                        ->join('material_dir', 'tblclassother_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassother_chapter.otherid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.other', compact('data', 'group', 'chapter'));
    }

    public function othercreate()
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

        return view('lecturer.courseassessment.othercreate', compact(['group', 'folder']));
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

    public function insertother(Request $request)
    {

        $user = Auth::user();

        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');

        $data = $request->validate([
            'other-title' => ['required', 'string'],
            'other-duration' => ['required'],
            'total-marks' => ['required'],
            'myPdf' => 'required', 'mimes:pdf'
        ]);

        $dir = "classother/" .  $classid . "/" . $user->name . "/" . $data['other-title'];

        $classother  = Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classother/" .  $classid . "/" . $user->name . "/" . $data['other-title'] . "/" . $newname;

        if(! file_exists($newname)){
            Storage::disk('public')->putFileAs(
                $dir,
                $file,
                $newname,
                'public'
              );

            $q = DB::table('tblclassother')->insertGetId([
                'classid' => $classid,
                'sessionid' => $sessionid,
                'status' => 2,
                'title' => $data['other-title'],
                'content' => $newpath,
                'deadline' => $data['other-duration'],
                'total_mark' => $data['total-marks'],
                'addby' => $user->ic
            ]);

            foreach($request->group as $grp)
            {
                $gp = explode('|', $grp);

                DB::table('tblclassother_group')->insert([
                    "groupid" => $gp[0],
                    "groupname" => $gp[1],
                    "otherid" => $q
                ]);
            }

            foreach($request->chapter as $chp)
            {
                DB::table('tblclassother_chapter')->insert([
                    "chapterid" => $chp,
                    "otherid" => $q
                ]);
            }

            return redirect(route('lecturer.other', ['id' => $classid]));
        }

    }

    public function lecturerotherstatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassother_group', 'user_subjek.id', 'tblclassother_group.groupid')
                ->join('tblclassother', 'tblclassother_group.otherid', 'tblclassother.id')
                ->where([
                    ['tblclassother.classid', Session::get('CourseIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassother.id', request()->other]
                ])->get();
            
        //dd($group);

        $other = DB::table('student_subjek')
                ->join('tblclassother_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassother_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassother_group.groupname');
                })
                ->join('tblclassother', 'tblclassother_group.otherid', 'tblclassother.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassother.id AS clssid', 'tblclassother.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassother.classid', Session::get('CourseIDS')],
                    ['tblclassother.sessionid', Session::get('SessionIDS')],
                    ['tblclassother.id', request()->other],
                    ['tblclassother.addby', $user->ic]
                ])->get();
        
        //dd($other);

        foreach($other as $qz)
        {
            $status[] = DB::table('tblclassstudentother')
            ->where([
                ['otherid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($other);

        return view('lecturer.courseassessment.otherstatus', compact('other', 'status', 'group'));

    }

    public function otherresult(Request $request){
        
        $id = $request->otherid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $other = DB::table('tblclassstudentother')
            ->join('tblclassother', 'tblclassstudentother.otherid', 'tblclassother.id')
            ->leftJoin('students', 'tblclassstudentother.userid', 'students.ic')
            ->select('tblclassstudentother.*', 'tblclassstudentother.otherid', 'tblclassother.title',  
                DB::raw('tblclassother.content as original_content'),
                'tblclassstudentother.return_content', 
                'tblclassstudentother.userid',
                'tblclassstudentother.subdate',
                'tblclassstudentother.final_mark',
                DB::raw('tblclassstudentother.status as studentotherstatus'),
                'tblclassother.deadline','tblclassother.total_mark','students.name', 'students.ic')
            ->where('tblclassstudentother.otherid', $id)
            ->where('tblclassstudentother.userid', $userid)->get()->first();

       
        $data['other'] = $other->content;
        //dd($data['other']);
        $data['return'] = $other->return_content;
        $data['comments'] = $other->comments;
        $data['mark'] = $other->final_mark;
        $data['otherid'] = $other->otherid;
        $data['othertitle'] = $other->title;
        $data['totalmark'] = $other->total_mark;
        $data['otherdeadline'] = $other->deadline;
        $data['otheruserid'] = $other->userid;
        $data['fullname'] = $other->name;
        $data['IC'] = $other->ic;
        $data['created_at'] = $other->created_at;
        $data['updated_at'] = $other->updated_at;
        $data['subdate'] = $other->subdate;
        $data['studentotherstatus'] = $other->studentotherstatus;

        return view('lecturer.courseassessment.otherresult', compact('data'));
    }

    public function updateotherresult(Request $request){
        $otherid = $request->id;
        $participant = $request->participant;
        $final_mark = $request->markss;
        $comments = $request->commentss;
        $classid = Session::get('CourseIDS');
        //$total_mark = $request->total_mark;
        //$data = $request->data;

        $other = DB::table('tblclassother')
                      ->join('users', 'tblclassother.addby', 'users.ic')
                      ->where('tblclassother.id', $otherid)
                      ->first();

        //dd($other);

        $dir = "classother/" .  $classid . "/" . $other->name . "/" . $other->title . "/" . $participant . "/return";

        $classother  = Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classother/" .  $classid . "/" . $other->name . "/" . $other->title . "/" . $participant . "/return" . "/" . $newname;

        Storage::disk('public')->putFileAs(
            $dir,
            $file,
            $newname,
            'public'
          );
      
        $q = \DB::table('tblclassstudentother')
            ->where('otherid', $otherid)
            ->where("userid", $participant)
            ->update([
                "return_content" => $newpath,
                "final_mark" => $final_mark,
                //"total_mark" => $total_mark,
                "comments" => $comments,
                "status" => 3
            ]);
        
            return redirect(route('lecturer.other.status',
        
            ['id' => $classid,'other' => $otherid]
           ));
    }


    //STUDENT other

    public function studentotherlist()
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

        $data = DB::table('tblclassother')
                ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                ->join('student_subjek', function($join){
                    $join->on('tblclassother_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassother_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassother_group.groupid', 'user_subjek.id')
                ->select('tblclassother.*', 'tblclassother_group.groupname')
                ->where([
                    ['tblclassother.classid', Session::get('CourseIDS')],
                    ['tblclassother.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic]
                ])->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassother_group')
                        ->join('user_subjek', 'tblclassother_group.groupid', 'user_subjek.id')
                        ->where('tblclassother_group.otherid', $dt->id)->get();

                $chapter[] = DB::table('tblclassother_chapter')
                        ->join('material_dir', 'tblclassother_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassother_chapter.otherid', $dt->id)->get();
            }
      

        return view('student.courseassessment.other', compact('data', 'group', 'chapter'));
    }

    public function studentotherstatus()
    {
        $other = DB::table('student_subjek')
                ->join('tblclassother_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassother_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassother_group.groupname');
                })
                ->join('tblclassother', 'tblclassother_group.otherid', 'tblclassother.id')
                ->where([
                    ['tblclassother.classid', Session::get('CourseIDS')],
                    ['tblclassother.sessionid', Session::get('SessionIDS')],
                    ['tblclassother.id', request()->other],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();
            
        foreach($other as $qz)
        {
            $status[] = DB::table('tblclassstudentother')
            ->where([
                ['otherid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.otherstatus', compact('other', 'status'));
    }

    public function otherview(Request $request){

        $id = $request->other;
        $other = DB::table('tblclassother')
            ->where('tblclassother.id', $id)
            ->get()->first();


        
            $data['otherid'] = $other->id;
            $data['othertitle'] = $other->title;
            $data['otherdeadline'] = $other->deadline;
            $data['created_at'] = $other->created_at;
            $data['updated_at'] = $other->updated_at;
    
            return view('student.courseassessment.otheranswer', compact('data'));
        
    }

    public function submitother(Request $request){
        $id = $request->id;

        $other = DB::table('tblclassother')
                      ->join('users', 'tblclassother.addby', 'users.ic')
                      ->where('tblclassother.id', $id)
                      ->first();
        
        //dd($other);

        $classid = Session::get('CourseIDS');

        $stud = Session::get('StudInfos');

        $dir = "classother/" .  $classid . "/" . $other->name . "/" . $other->title . "/" . $stud->ic;

        //$classother  = Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classother/" .  $classid . "/" . $other->name . "/" . $other->title . "/" . $stud->ic . "/" . $newname;
        
        $today = date("Y-m-d H:i:s");

        if(! file_exists($newname)){
            Storage::disk('public')->putFileAs(
                $dir,
                $file,
                $newname,
                'public'
              );
            
              if($today > $other->deadline)
              {
                 $status = 2;
              }else {
                 $status = 1;
              }

              $q = DB::table('tblclassstudentother')->upsert([
                "userid" => Session::get('StudInfos')->ic,
                "otherid" => $id,
                "subdate" => $today,
                "content" => $newpath,
                "status" => 2,
                "status_submission" => $status
            ],['userid', 'otherid']);


            return redirect(route('student.other.status',
        
             ['id' => $classid,'other' => $id]
            ));
        }
     
    }


    public function otherresultstd(Request $request){
        
        $id = $request->otherid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $other = DB::table('tblclassstudentother')
            ->join('tblclassother', 'tblclassstudentother.otherid', 'tblclassother.id')
            ->leftJoin('students', 'tblclassstudentother.userid', 'students.ic')
            ->select('tblclassstudentother.*', 'tblclassstudentother.otherid', 'tblclassother.title',  
                DB::raw('tblclassother.content as original_content'), 
                'tblclassstudentother.return_content',
                'tblclassstudentother.userid',
                'tblclassstudentother.subdate',
                'tblclassstudentother.final_mark',
                'tblclassother.deadline',
                DB::raw('tblclassstudentother.status as studentotherstatus'),
                'students.name')
            ->where('tblclassstudentother.otherid', $id)
            ->where('tblclassstudentother.userid', $userid)->get()->first();

       
        $data['other'] = $other->content;
        $data['return'] = $other->return_content;
        $data['mark'] = $other->final_mark;
        $data['comments'] = $other->comments;

        $data['otherid'] = $other->otherid;
        $data['othertitle'] = $other->title;
        $data['otheruserid'] = $other->userid;
        $data['fullname'] = $other->name;
        $data['created_at'] = $other->created_at;
        $data['updated_at'] = $other->updated_at;
        $data['subdate'] = $other->subdate;
        $data['deadline'] = $other->deadline;
        $data['studentotherstatus'] = $other->studentotherstatus;

        return view('student.courseassessment.otherresult', compact('data'));
    }


}
