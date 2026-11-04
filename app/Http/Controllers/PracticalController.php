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
                    ['addby', $user->ic],
                    ['deadline', '!=', null],
                    ['status', '!=', 3],
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

    public function deletepractical(Request $request)
    {

        try {
            $user = Auth::user();

            $practical = DB::table('tblclasspractical')->where('id', $request->id)->where('addby', $user->ic)->first();

            if($practical){
                if($practical->status != 3)
                {
                DB::table('tblclasspractical')->where('id', $request->id)->where('addby', $user->ic)->update([
                    'status' => 3
                ]);

                return true;

                }else{

                    die;

                }
            } else {
                return false;
            }
          
          } catch (\Exception $e) {
          
              return $e->getMessage();
          }
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
                    <label>'.(($sub->newDrName != null) ? $sub->newDrName : $sub->DrName).'</label>
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

    public function deletepracticalstatus(Request $request)
    {
        $data = DB::table('tblclassstudentpractical')->where('id', $request->id)->first();

        Storage::disk('linode')->delete($data->content);

        Storage::disk('linode')->delete($data->return_content);

        DB::table('tblclassstudentpractical')->where('id', $request->id)->delete();

        return true;
        
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
                    ['student_subjek.student_ic', $student->ic],
                    ['tblclasspractical.deadline', '!=', null],
                    ['tblclasspractical.status', '!=', 3]
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

    public function practical2list()
    {
        Session::put('CourseIDS', request()->id);

        if(Session::get('SessionIDS') == null)
        {
            Session::put('SessionIDS', request()->session);
        }

        $user = Auth::user();
        $group = array();
        $chapter = array();

        $data = DB::table('tblclasspractical')
                ->where([
                    ['classid', Session::get('CourseIDS')],
                    ['sessionid', Session::get('SessionIDS')],
                    ['addby', $user->ic],
                    ['deadline', null],
                    ['status', '!=', 3]
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

        return view('lecturer.courseassessment.practical2', compact('data', 'group', 'chapter'));
    }

    public function practical2create()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');

        $data['practical'] = null;
        $data['folder'] = null;
        $data['chapter'] = null;

        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->where([
            ['subjek.id', $courseid],
            ['user_subjek.session_id', $sessionid],
            ['user_subjek.user_ic', $user->ic]
        ])->groupBy('student_subjek.group_name')
        ->select('user_subjek.*', 'subjek.course_name', 'student_subjek.group_name')->get();

        $subid = DB::table('subjek')->where('id', $courseid)->pluck('sub_id');

        $folder = DB::table('lecturer_dir')
                  ->join('subjek', 'lecturer_dir.CourseID','subjek.id')
                  ->where('subjek.sub_id', $subid)
                  ->where('Addby', $user->ic)->get();

        if(isset(request()->practicalid))
        {
            $data['practical'] = DB::table('tblclasspractical')->where('id', request()->practicalid)->first();
        }

        return view('lecturer.courseassessment.practical2create', compact(['group', 'folder', 'data']));
    }

    public function insertpractical2(Request $request)
    {
        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');
        $title = $request->title;
        $group = $request->group;
        $chapter = $request->chapter;
        $marks = $request->marks;
        $practicalid = empty($request->practical) ? '' : $request->practical;

        $user = Auth::user();

        if($group != null && $chapter != null)
        {
            if(!empty($practicalid))
            {
                $practical = DB::table('tblclasspractical')->where('id', $practicalid)->first();

                DB::table('tblclasspractical_group')->where('practicalid', $practicalid)->delete();
                DB::table('tblclasspractical_chapter')->where('practicalid', $practicalid)->delete();

                $updateData = [
                    "title" => $title,
                    "total_mark" => $marks,
                    "status" => 2
                ];

                if($request->hasFile('myPdf'))
                {
                    $file = $request->file('myPdf');
                    $dir = "classpractical2/" . $classid . "/" . $user->name . "/" . $title;
                    Storage::disk('linode')->makeDirectory($dir);
                    $file_name = $file->getClientOriginalName();
                    $file_ext = $file->getClientOriginalExtension();
                    $fileInfo = pathinfo($file_name);
                    $filename = $fileInfo['filename'];
                    $newname = $filename . "." . $file_ext;
                    $newpath = "classpractical2/" . $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                    if($practical && $practical->content)
                    {
                        Storage::disk('linode')->delete($practical->content);
                    }

                    Storage::disk('linode')->putFileAs($dir, $file, $newname, 'public');
                    $updateData['content'] = $newpath;
                }

                DB::table('tblclasspractical')->where('id', $practicalid)->update($updateData);

                foreach($request->group as $grp)
                {
                    $gp = explode('|', $grp);
                    DB::table('tblclasspractical_group')->insert([
                        "groupid" => $gp[0],
                        "groupname" => $gp[1],
                        "practicalid" => $practicalid
                    ]);
                }

                foreach($request->chapter as $chp)
                {
                    DB::table('tblclasspractical_chapter')->insert([
                        "chapterid" => $chp,
                        "practicalid" => $practicalid
                    ]);
                }
            }
            else
            {
                $newpath = null;
                if($request->hasFile('myPdf'))
                {
                    $file = $request->file('myPdf');
                    $dir = "classpractical2/" . $classid . "/" . $user->name . "/" . $title;
                    Storage::disk('linode')->makeDirectory($dir);
                    $file_name = $file->getClientOriginalName();
                    $file_ext = $file->getClientOriginalExtension();
                    $fileInfo = pathinfo($file_name);
                    $filename = $fileInfo['filename'];
                    $newname = $filename . "." . $file_ext;
                    $newpath = "classpractical2/" . $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                    Storage::disk('linode')->putFileAs($dir, $file, $newname, 'public');
                }

                $q = DB::table('tblclasspractical')->insertGetId([
                    "classid" => $classid,
                    "sessionid" => $sessionid,
                    "title" => $title,
                    'content' => $newpath,
                    "total_mark" => $marks,
                    "addby" => $user->ic,
                    "deadline" => null,
                    "status" => 2
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
            }
        }
        else
        {
            return redirect()->back()->withErrors(['Please fill in the group and sub-chapter checkbox !']);
        }

        return redirect(route('lecturer.practical2', ['id' => $classid]));
    }

    public function lecturerpractical2status()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclasspractical_group', 'user_subjek.id', 'tblclasspractical_group.groupid')
                ->join('tblclasspractical', 'tblclasspractical_group.practicalid', 'tblclasspractical.id')
                ->where([
                    ['tblclasspractical.classid', Session::get('CourseIDS')],
                    ['tblclasspractical.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclasspractical.id', request()->practical]
                ])->get();

        $practical = DB::table('student_subjek')
                ->join('tblclasspractical_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclasspractical_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclasspractical_group.groupname');
                })
                ->join('tblclasspractical', 'tblclasspractical_group.practicalid', 'tblclasspractical.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclasspractical.id AS clssid', 'tblclasspractical.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclasspractical.classid', Session::get('CourseIDS')],
                    ['tblclasspractical.sessionid', Session::get('SessionIDS')],
                    ['tblclasspractical.id', request()->practical],
                    ['tblclasspractical.addby', $user->ic]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();

        $status = [];
        foreach($practical as $qz)
        {
            if(!DB::table('tblclassstudentpractical')->where([['practicalid', $qz->clssid],['userid', $qz->student_ic]])->exists()){
                DB::table('tblclassstudentpractical')->insert([
                    'practicalid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);
            }

            $status[] = DB::table('tblclassstudentpractical')
            ->where([
                ['practicalid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        return view('lecturer.courseassessment.practical2status', compact('practical', 'status', 'group'));
    }

    public function practical2GetGroup(Request $request)
    {
        $user = Auth::user();

        $gp = explode('|', $request->group);

        $practical = DB::table('student_subjek')
                ->join('tblclasspractical_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclasspractical_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclasspractical_group.groupname');
                })
                ->join('tblclasspractical', 'tblclasspractical_group.practicalid', 'tblclasspractical.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclasspractical.id AS clssid', 'tblclasspractical.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclasspractical.classid', Session::get('CourseIDS')],
                    ['tblclasspractical.sessionid', Session::get('SessionIDS')],
                    ['tblclasspractical.id', request()->practical],
                    ['tblclasspractical.addby', $user->ic],
                    ['student_subjek.group_id', $gp[0]],
                    ['student_subjek.group_name', $gp[1]]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();

        $status = [];
        foreach($practical as $qz)
        {
            if(!DB::table('tblclassstudentpractical')->where([['practicalid', $qz->clssid],['userid', $qz->student_ic]])->exists()){
                DB::table('tblclassstudentpractical')->insert([
                    'practicalid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);
            }

            $status[] = DB::table('tblclassstudentpractical')
            ->where([
                ['practicalid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Matric No.
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Submission Date
                            </th>
                            <th>
                                Marks
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';

        foreach ($practical as $key => $qz) {
            $alert = ($status[$key]->final_mark != 0) ? "badge bg-success" : "badge bg-danger";
            $subdate = ($status[$key]->final_mark != 0 && !empty($status[$key]->subdate)) ? $status[$key]->subdate : "-";

            $content .= '
                <tr>
                    <td style="width: 1%">
                        ' . ($key + 1) . '
                    </td>
                    <td>
                        <span class="' . $alert . '">' . $qz->name . '</span>
                    </td>
                    <td>
                        <span>' . $qz->no_matric . '</span>
                    </td>
                    <td>
                        <span>' . $qz->progcode . '</span>
                    </td>
                    <td>
                        ' . $subdate . '
                    </td>
                    <td>
                        <div class="form-inline col-md-6 d-flex">
                            <input type="number" class="form-control" name="marks[]" max="' . $qz->total_mark . '" value="' . $status[$key]->final_mark . '">
                            <input type="text" name="ic[]" value="' . $qz->student_ic . '" hidden>
                            <span>&nbsp; ' . $status[$key]->final_mark . ' / ' . $qz->total_mark . '</span>
                        </div>
                    </td>
                </tr>';
        }

        $content .= '</tbody>';

        return response()->json(['message' => 'success', 'content' => $content]);
    }

    public function updatepractical2(Request $request)
    {
        $user = Auth::user();

        $marks = json_decode($request->marks);
        $ics = json_decode($request->ics);
        $practicalid = json_decode($request->practicalid);

        $limitpercen = DB::table('tblclasspractical')->where('id', $practicalid)->first();

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
                'practicalid' => $practicalid,
                'subdate' => Carbon::now(),
                'final_mark' => $mrk,
                'status' => 1
            ]);
        }

        DB::table('tblclassstudentpractical')->upsert($upsert, ['userid', 'practicalid']);

        return ["message"=>"Success", "id" => $ics];
    }

    public function studentpractical2list()
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

        $courseid = DB::table('subjek')->where('id', request()->id)->value('sub_id');

        $group = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('users', 'user_subjek.user_ic', 'users.ic')
                ->where([
                    ['student_subjek.student_ic', $student->ic],
                    ['student_subjek.courseid', $courseid],
                    ['student_subjek.sessionid', Session::get('SessionIDS')]
                ])
                ->select('user_subjek.id')
                ->first();

        if(!$group)
        {
            $data = [];
            return view('student.courseassessment.practical2', compact('data', 'chapter', 'marks'));
        }

        $data = DB::table('tblclasspractical')
                ->join('tblclasspractical_group', 'tblclasspractical.id', 'tblclasspractical_group.practicalid')
                ->join('student_subjek', function($join){
                    $join->on('tblclasspractical_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclasspractical_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclasspractical_group.groupid', 'user_subjek.id')
                ->select('tblclasspractical.*', 'tblclasspractical_group.groupname')
                ->where([
                    ['user_subjek.id', $group->id],
                    ['tblclasspractical.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic],
                    ['tblclasspractical.deadline', null],
                    ['tblclasspractical.status', '!=', 3]
                ])->get();

        foreach($data as $dt)
        {
            $chapter[] = DB::table('tblclasspractical_chapter')
                      ->join('material_dir', 'tblclasspractical_chapter.chapterid', 'material_dir.DrID')
                      ->where('tblclasspractical_chapter.practicalid', $dt->id)->get();

            $marks[] = DB::table('tblclassstudentpractical')
                      ->where([
                        ['practicalid', $dt->id],
                        ['userid', $student->ic]
                      ])->get();
        }

        return view('student.courseassessment.practical2', compact('data', 'chapter', 'marks'));
    }


}
