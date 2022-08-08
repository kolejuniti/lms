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

class PracticalController extends Controller
{
    public function practicallist()
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

        $data = DB::table('tblclasspractical')
                ->where([
                    ['classid', Session::get('CourseIDS')],
                    ['sessionid', Session::get('SessionIDS')],
                    ['addby', $user->ic]
                ])->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclasspractical_group')
                        ->join('user_subjek', 'tblclasspractical_group.groupid', 'user_subjek.id')
                        ->where('tblclasspractical_group.practicalid', $dt->id)->get();

                $chapter[] = DB::table('tblclasspractical_chapter')
                        ->join('material_dir', 'tblclasspractical_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclasspractical_chapter.practicalid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.practical', compact('data', 'group', 'chapter'));
    }

    public function practicalcreate()
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
            ['Addby', $user->ic]
            ])->get();

        //dd($folder);

        return view('lecturer.courseassessment.practicalcreate', compact(['group', 'folder']));
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

    public function insertpractical(Request $request)
    {

        $user = Auth::user();

        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');

        $data = $request->validate([
            'practical-title' => ['required', 'string'],
            'practical-duration' => ['required'],
            'total-marks' => ['required'],
            'myPdf' => 'required', 'mimes:pdf'
        ]);

        $dir = "classpractical/" .  $classid . "/" . $user->name . "/" . $data['practical-title'];

        $classpractical  = Storage::disk('linode')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classpractical/" .  $classid . "/" . $user->name . "/" . $data['practical-title'] . "/" . $newname;

        if(! file_exists($newname)){
            Storage::disk('linode')->putFileAs(
                $dir,
                $file,
                $newname,
                'public'
              );

            $q = DB::table('tblclasspractical')->insertGetId([
                'classid' => $classid,
                'sessionid' => $sessionid,
                'status' => 2,
                'title' => $data['practical-title'],
                'content' => $newpath,
                'deadline' => $data['practical-duration'],
                'total_mark' => $data['total-marks'],
                'addby' => $user->ic
            ]);

            foreach($request->group as $grp)
            {
                $gp = explode('|', $grp);

                DB::table('tblclasspractical_group')->insert([
                    "groupid" => $gp[0],
                    "groupname" => $gp[1],
                    "practicalid" => $q
                ]);
            }

            foreach($request->chapter as $chp)
            {
                DB::table('tblclasspractical_chapter')->insert([
                    "chapterid" => $chp,
                    "practicalid" => $q
                ]);
            }

            return redirect(route('lecturer.practical', ['id' => $classid]));
        }

    }

    public function lecturerpracticalstatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclasspractical_group', 'user_subjek.id', 'tblclasspractical_group.groupid')
                ->join('tblclasspractical', 'tblclasspractical_group.practicalid', 'tblclasspractical.id')
                ->where([
                    ['tblclasspractical.classid', Session::get('CourseIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclasspractical.id', request()->practical]
                ])->get();
            
        //dd($group);

        $practical = DB::table('student_subjek')
                ->join('tblclasspractical_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclasspractical_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclasspractical_group.groupname');
                })
                ->join('tblclasspractical', 'tblclasspractical_group.practicalid', 'tblclasspractical.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclasspractical.id AS clssid', 'tblclasspractical.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclasspractical.classid', Session::get('CourseIDS')],
                    ['tblclasspractical.sessionid', Session::get('SessionIDS')],
                    ['tblclasspractical.id', request()->practical],
                    ['tblclasspractical.addby', $user->ic]
                ])->get();
        
        //dd($practical);

        foreach($practical as $qz)
        {
            $status[] = DB::table('tblclassstudentpractical')
            ->where([
                ['practicalid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($practical);

        return view('lecturer.courseassessment.practicalstatus', compact('practical', 'status', 'group'));

    }

    public function practicalresult(Request $request){
        
        $id = $request->practicalid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $practical = DB::table('tblclassstudentpractical')
            ->join('tblclasspractical', 'tblclassstudentpractical.practicalid', 'tblclasspractical.id')
            ->leftJoin('students', 'tblclassstudentpractical.userid', 'students.ic')
            ->select('tblclassstudentpractical.*', 'tblclassstudentpractical.practicalid', 'tblclasspractical.title',  
                DB::raw('tblclasspractical.content as original_content'),
                'tblclassstudentpractical.return_content', 
                'tblclassstudentpractical.userid',
                'tblclassstudentpractical.subdate',
                'tblclassstudentpractical.final_mark',
                DB::raw('tblclassstudentpractical.status as studentpracticalstatus'),
                'tblclasspractical.deadline','tblclasspractical.total_mark','students.name', 'students.ic')
            ->where('tblclassstudentpractical.practicalid', $id)
            ->where('tblclassstudentpractical.userid', $userid)->get()->first();

       
        $data['practical'] = $practical->content;
        //dd($data['practical']);
        $data['return'] = $practical->return_content;
        $data['comments'] = $practical->comments;
        $data['mark'] = $practical->final_mark;
        $data['practicalid'] = $practical->practicalid;
        $data['practicaltitle'] = $practical->title;
        $data['totalmark'] = $practical->total_mark;
        $data['practicaldeadline'] = $practical->deadline;
        $data['practicaluserid'] = $practical->userid;
        $data['fullname'] = $practical->name;
        $data['IC'] = $practical->ic;
        $data['created_at'] = $practical->created_at;
        $data['updated_at'] = $practical->updated_at;
        $data['subdate'] = $practical->subdate;
        $data['studentpracticalstatus'] = $practical->studentpracticalstatus;

        return view('lecturer.courseassessment.practicalresult', compact('data'));
    }

    public function updatepracticalresult(Request $request){
        $practicalid = $request->id;
        $participant = $request->participant;
        $final_mark = $request->markss;
        $comments = $request->commentss;
        $classid = Session::get('CourseIDS');
        //$total_mark = $request->total_mark;
        //$data = $request->data;

        $practical = DB::table('tblclasspractical')
                      ->join('users', 'tblclasspractical.addby', 'users.ic')
                      ->where('tblclasspractical.id', $practicalid)
                      ->first();

        //dd($practical);

        $dir = "classpractical/" .  $classid . "/" . $practical->name . "/" . $practical->title . "/" . $participant . "/return";

        $classpractical  = Storage::disk('linode')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classpractical/" .  $classid . "/" . $practical->name . "/" . $practical->title . "/" . $participant . "/return" . "/" . $newname;

        Storage::disk('linode')->putFileAs(
            $dir,
            $file,
            $newname,
            'public'
          );
      
        $q = \DB::table('tblclassstudentpractical')
            ->where('practicalid', $practicalid)
            ->where("userid", $participant)
            ->update([
                "return_content" => $newpath,
                "final_mark" => $final_mark,
                //"total_mark" => $total_mark,
                "comments" => $comments,
                "status" => 3
            ]);
        
            return redirect(route('lecturer.practical.status',
        
            ['id' => $classid,'practical' => $practicalid]
           ));
    }


    //STUDENT practical

    public function studentpracticallist()
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

        $data = DB::table('tblclasspractical')
                ->join('tblclasspractical_group', 'tblclasspractical.id', 'tblclasspractical_group.practicalid')
                ->join('student_subjek', function($join){
                    $join->on('tblclasspractical_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclasspractical_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclasspractical_group.groupid', 'user_subjek.id')
                ->select('tblclasspractical.*', 'tblclasspractical_group.groupname')
                ->where([
                    ['tblclasspractical.classid', Session::get('CourseIDS')],
                    ['tblclasspractical.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic]
                ])->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclasspractical_group')
                        ->join('user_subjek', 'tblclasspractical_group.groupid', 'user_subjek.id')
                        ->where('tblclasspractical_group.practicalid', $dt->id)->get();

                $chapter[] = DB::table('tblclasspractical_chapter')
                        ->join('material_dir', 'tblclasspractical_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclasspractical_chapter.practicalid', $dt->id)->get();
            }
      

        return view('student.courseassessment.practical', compact('data', 'group', 'chapter'));
    }

    public function studentpracticalstatus()
    {
        $practical = DB::table('student_subjek')
                ->join('tblclasspractical_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclasspractical_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclasspractical_group.groupname');
                })
                ->join('tblclasspractical', 'tblclasspractical_group.practicalid', 'tblclasspractical.id')
                ->where([
                    ['tblclasspractical.classid', Session::get('CourseIDS')],
                    ['tblclasspractical.sessionid', Session::get('SessionIDS')],
                    ['tblclasspractical.id', request()->practical],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();
            
        foreach($practical as $qz)
        {
            $status[] = DB::table('tblclassstudentpractical')
            ->where([
                ['practicalid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.practicalstatus', compact('practical', 'status'));
    }

    public function practicalview(Request $request){

        $id = $request->practical;
        $practical = DB::table('tblclasspractical')
            ->where('tblclasspractical.id', $id)
            ->get()->first();


        
            $data['practicalid'] = $practical->id;
            $data['practicaltitle'] = $practical->title;
            $data['practicaldeadline'] = $practical->deadline;
            $data['created_at'] = $practical->created_at;
            $data['updated_at'] = $practical->updated_at;
    
            return view('student.courseassessment.practicalanswer', compact('data'));
        
    }

    public function submitpractical(Request $request){
        $id = $request->id;

        $practical = DB::table('tblclasspractical')
                      ->join('users', 'tblclasspractical.addby', 'users.ic')
                      ->where('tblclasspractical.id', $id)
                      ->first();
        
        //dd($practical);

        $classid = Session::get('CourseIDS');

        $stud = Session::get('StudInfos');

        $dir = "classpractical/" .  $classid . "/" . $practical->name . "/" . $practical->title . "/" . $stud->ic;

        //$classpractical  = Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classpractical/" .  $classid . "/" . $practical->name . "/" . $practical->title . "/" . $stud->ic . "/" . $newname;
        
        $today = date("Y-m-d H:i:s");

        if(! file_exists($newname)){
            Storage::disk('linode')->putFileAs(
                $dir,
                $file,
                $newname,
                'public'
              );
            
              if($today > $practical->deadline)
              {
                 $status = 2;
              }else {
                 $status = 1;
              }

              $q = DB::table('tblclassstudentpractical')->upsert([
                "userid" => Session::get('StudInfos')->ic,
                "practicalid" => $id,
                "subdate" => $today,
                "content" => $newpath,
                "status" => 2,
                "status_submission" => $status
            ],['userid', 'practicalid']);


            return redirect(route('student.practical.status',
        
             ['id' => $classid,'practical' => $id]
            ));
        }
     
    }


    public function practicalresultstd(Request $request){
        
        $id = $request->practicalid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $practical = DB::table('tblclassstudentpractical')
            ->join('tblclasspractical', 'tblclassstudentpractical.practicalid', 'tblclasspractical.id')
            ->leftJoin('students', 'tblclassstudentpractical.userid', 'students.ic')
            ->select('tblclassstudentpractical.*', 'tblclassstudentpractical.practicalid', 'tblclasspractical.title',  
                DB::raw('tblclasspractical.content as original_content'), 
                'tblclassstudentpractical.return_content',
                'tblclassstudentpractical.userid',
                'tblclassstudentpractical.subdate',
                'tblclassstudentpractical.final_mark',
                'tblclasspractical.deadline',
                DB::raw('tblclassstudentpractical.status as studentpracticalstatus'),
                'students.name')
            ->where('tblclassstudentpractical.practicalid', $id)
            ->where('tblclassstudentpractical.userid', $userid)->get()->first();

       
        $data['practical'] = $practical->content;
        $data['return'] = $practical->return_content;
        $data['mark'] = $practical->final_mark;
        $data['comments'] = $practical->comments;

        $data['practicalid'] = $practical->practicalid;
        $data['practicaltitle'] = $practical->title;
        $data['practicaluserid'] = $practical->userid;
        $data['fullname'] = $practical->name;
        $data['created_at'] = $practical->created_at;
        $data['updated_at'] = $practical->updated_at;
        $data['subdate'] = $practical->subdate;
        $data['deadline'] = $practical->deadline;
        $data['studentpracticalstatus'] = $practical->studentpracticalstatus;

        return view('student.courseassessment.practicalresult', compact('data'));
    }


}
