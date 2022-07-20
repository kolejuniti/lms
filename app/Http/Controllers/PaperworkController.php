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

class PaperworkController extends Controller
{
    public function paperworklist()
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

        $data = DB::table('tblclasspaperwork')
                ->where([
                    ['classid', Session::get('CourseIDS')],
                    ['sessionid', Session::get('SessionIDS')],
                    ['addby', $user->ic]
                ])->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclasspaperwork_group')
                        ->join('user_subjek', 'tblclasspaperwork_group.groupid', 'user_subjek.id')
                        ->where('tblclasspaperwork_group.paperworkid', $dt->id)->get();

                $chapter[] = DB::table('tblclasspaperwork_chapter')
                        ->join('material_dir', 'tblclasspaperwork_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclasspaperwork_chapter.paperworkid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.paperwork', compact('data', 'group', 'chapter'));
    }

    public function paperworkcreate()
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

        return view('lecturer.courseassessment.paperworkcreate', compact(['group', 'folder']));
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

    public function insertpaperwork(Request $request)
    {

        $user = Auth::user();

        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');

        $data = $request->validate([
            'paperwork-title' => ['required', 'string'],
            'paperwork-duration' => ['required'],
            'total-marks' => ['required'],
            'myPdf' => 'required', 'mimes:pdf'
        ]);

        $dir = "classpaperwork/" .  $classid . "/" . $user->name . "/" . $data['paperwork-title'];

        $classpaperwork  = Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classpaperwork/" .  $classid . "/" . $user->name . "/" . $data['paperwork-title'] . "/" . $newname;

        if(! file_exists($newname)){
            Storage::disk('public')->putFileAs(
                $dir,
                $file,
                $newname,
                'public'
              );

            $q = DB::table('tblclasspaperwork')->insertGetId([
                'classid' => $classid,
                'sessionid' => $sessionid,
                'status' => 2,
                'title' => $data['paperwork-title'],
                'content' => $newpath,
                'deadline' => $data['paperwork-duration'],
                'total_mark' => $data['total-marks'],
                'addby' => $user->ic
            ]);

            foreach($request->group as $grp)
            {
                $gp = explode('|', $grp);

                DB::table('tblclasspaperwork_group')->insert([
                    "groupid" => $gp[0],
                    "groupname" => $gp[1],
                    "paperworkid" => $q
                ]);
            }

            foreach($request->chapter as $chp)
            {
                DB::table('tblclasspaperwork_chapter')->insert([
                    "chapterid" => $chp,
                    "paperworkid" => $q
                ]);
            }

            return redirect(route('lecturer.paperwork', ['id' => $classid]));
        }

    }

    public function lecturerpaperworkstatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclasspaperwork_group', 'user_subjek.id', 'tblclasspaperwork_group.groupid')
                ->join('tblclasspaperwork', 'tblclasspaperwork_group.paperworkid', 'tblclasspaperwork.id')
                ->where([
                    ['tblclasspaperwork.classid', Session::get('CourseIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclasspaperwork.id', request()->paperwork]
                ])->get();
            
        //dd($group);

        $paperwork = DB::table('student_subjek')
                ->join('tblclasspaperwork_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclasspaperwork_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclasspaperwork_group.groupname');
                })
                ->join('tblclasspaperwork', 'tblclasspaperwork_group.paperworkid', 'tblclasspaperwork.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclasspaperwork.id AS clssid', 'tblclasspaperwork.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclasspaperwork.classid', Session::get('CourseIDS')],
                    ['tblclasspaperwork.sessionid', Session::get('SessionIDS')],
                    ['tblclasspaperwork.id', request()->paperwork],
                    ['tblclasspaperwork.addby', $user->ic]
                ])->get();
        
        //dd($paperwork);

        foreach($paperwork as $qz)
        {
            $status[] = DB::table('tblclassstudentpaperwork')
            ->where([
                ['paperworkid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($paperwork);

        return view('lecturer.courseassessment.paperworkstatus', compact('paperwork', 'status', 'group'));

    }

    public function paperworkresult(Request $request){
        
        $id = $request->paperworkid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $paperwork = DB::table('tblclassstudentpaperwork')
            ->join('tblclasspaperwork', 'tblclassstudentpaperwork.paperworkid', 'tblclasspaperwork.id')
            ->leftJoin('students', 'tblclassstudentpaperwork.userid', 'students.ic')
            ->select('tblclassstudentpaperwork.*', 'tblclassstudentpaperwork.paperworkid', 'tblclasspaperwork.title',  
                DB::raw('tblclasspaperwork.content as original_content'),
                'tblclassstudentpaperwork.return_content', 
                'tblclassstudentpaperwork.userid',
                'tblclassstudentpaperwork.subdate',
                'tblclassstudentpaperwork.final_mark',
                DB::raw('tblclassstudentpaperwork.status as studentpaperworkstatus'),
                'tblclasspaperwork.deadline','tblclasspaperwork.total_mark','students.name', 'students.ic')
            ->where('tblclassstudentpaperwork.paperworkid', $id)
            ->where('tblclassstudentpaperwork.userid', $userid)->get()->first();

       
        $data['paperwork'] = $paperwork->content;
        //dd($data['paperwork']);
        $data['return'] = $paperwork->return_content;
        $data['comments'] = $paperwork->comments;
        $data['mark'] = $paperwork->final_mark;
        $data['paperworkid'] = $paperwork->paperworkid;
        $data['paperworktitle'] = $paperwork->title;
        $data['totalmark'] = $paperwork->total_mark;
        $data['paperworkdeadline'] = $paperwork->deadline;
        $data['paperworkuserid'] = $paperwork->userid;
        $data['fullname'] = $paperwork->name;
        $data['IC'] = $paperwork->ic;
        $data['created_at'] = $paperwork->created_at;
        $data['updated_at'] = $paperwork->updated_at;
        $data['subdate'] = $paperwork->subdate;
        $data['studentpaperworkstatus'] = $paperwork->studentpaperworkstatus;

        return view('lecturer.courseassessment.paperworkresult', compact('data'));
    }

    public function updatepaperworkresult(Request $request){
        $paperworkid = $request->id;
        $participant = $request->participant;
        $final_mark = $request->markss;
        $comments = $request->commentss;
        $classid = Session::get('CourseIDS');
        //$total_mark = $request->total_mark;
        //$data = $request->data;

        $paperwork = DB::table('tblclasspaperwork')
                      ->join('users', 'tblclasspaperwork.addby', 'users.ic')
                      ->where('tblclasspaperwork.id', $paperworkid)
                      ->first();

        //dd($paperwork);

        $dir = "classpaperwork/" .  $classid . "/" . $paperwork->name . "/" . $paperwork->title . "/" . $participant . "/return";

        $classpaperwork  = Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classpaperwork/" .  $classid . "/" . $paperwork->name . "/" . $paperwork->title . "/" . $participant . "/return" . "/" . $newname;

        Storage::disk('public')->putFileAs(
            $dir,
            $file,
            $newname,
            'public'
          );
      
        $q = \DB::table('tblclassstudentpaperwork')
            ->where('paperworkid', $paperworkid)
            ->where("userid", $participant)
            ->update([
                "return_content" => $newpath,
                "final_mark" => $final_mark,
                //"total_mark" => $total_mark,
                "comments" => $comments,
                "status" => 3
            ]);
        
            return redirect(route('lecturer.paperwork.status',
        
            ['id' => $classid,'paperwork' => $paperworkid]
           ));
    }


    //STUDENT paperwork

    public function studentpaperworklist()
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

        $data = DB::table('tblclasspaperwork')
                ->join('tblclasspaperwork_group', 'tblclasspaperwork.id', 'tblclasspaperwork_group.paperworkid')
                ->join('student_subjek', function($join){
                    $join->on('tblclasspaperwork_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclasspaperwork_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclasspaperwork_group.groupid', 'user_subjek.id')
                ->select('tblclasspaperwork.*', 'tblclasspaperwork_group.groupname')
                ->where([
                    ['tblclasspaperwork.classid', Session::get('CourseIDS')],
                    ['tblclasspaperwork.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic]
                ])->get();

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclasspaperwork_group')
                        ->join('user_subjek', 'tblclasspaperwork_group.groupid', 'user_subjek.id')
                        ->where('tblclasspaperwork_group.paperworkid', $dt->id)->get();

                $chapter[] = DB::table('tblclasspaperwork_chapter')
                        ->join('material_dir', 'tblclasspaperwork_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclasspaperwork_chapter.paperworkid', $dt->id)->get();
            }
      

        return view('student.courseassessment.paperwork', compact('data', 'group', 'chapter'));
    }

    public function studentpaperworkstatus()
    {
        $paperwork = DB::table('student_subjek')
                ->join('tblclasspaperwork_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclasspaperwork_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclasspaperwork_group.groupname');
                })
                ->join('tblclasspaperwork', 'tblclasspaperwork_group.paperworkid', 'tblclasspaperwork.id')
                ->where([
                    ['tblclasspaperwork.classid', Session::get('CourseIDS')],
                    ['tblclasspaperwork.sessionid', Session::get('SessionIDS')],
                    ['tblclasspaperwork.id', request()->paperwork],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();
            
        foreach($paperwork as $qz)
        {
            $status[] = DB::table('tblclassstudentpaperwork')
            ->where([
                ['paperworkid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.paperworkstatus', compact('paperwork', 'status'));
    }

    public function paperworkview(Request $request){

        $id = $request->paperwork;
        $paperwork = DB::table('tblclasspaperwork')
            ->where('tblclasspaperwork.id', $id)
            ->get()->first();


        
            $data['paperworkid'] = $paperwork->id;
            $data['paperworktitle'] = $paperwork->title;
            $data['paperworkdeadline'] = $paperwork->deadline;
            $data['created_at'] = $paperwork->created_at;
            $data['updated_at'] = $paperwork->updated_at;
    
            return view('student.courseassessment.paperworkanswer', compact('data'));
        
    }

    public function submitpaperwork(Request $request){
        $id = $request->id;

        $paperwork = DB::table('tblclasspaperwork')
                      ->join('users', 'tblclasspaperwork.addby', 'users.ic')
                      ->where('tblclasspaperwork.id', $id)
                      ->first();
        
        //dd($paperwork);

        $classid = Session::get('CourseIDS');

        $stud = Session::get('StudInfos');

        $dir = "classpaperwork/" .  $classid . "/" . $paperwork->name . "/" . $paperwork->title . "/" . $stud->ic;

        //$classpaperwork  = Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classpaperwork/" .  $classid . "/" . $paperwork->name . "/" . $paperwork->title . "/" . $stud->ic . "/" . $newname;
        
        $today = date("Y-m-d H:i:s");

        if(! file_exists($newname)){
            Storage::disk('public')->putFileAs(
                $dir,
                $file,
                $newname,
                'public'
              );
            
              if($today > $paperwork->deadline)
              {
                 $status = 2;
              }else {
                 $status = 1;
              }

              $q = DB::table('tblclassstudentpaperwork')->upsert([
                "userid" => Session::get('StudInfos')->ic,
                "paperworkid" => $id,
                "subdate" => $today,
                "content" => $newpath,
                "status" => 2,
                "status_submission" => $status
            ],['userid', 'paperworkid']);


            return redirect(route('student.paperwork.status',
        
             ['id' => $classid,'paperwork' => $id]
            ));
        }
     
    }


    public function paperworkresultstd(Request $request){
        
        $id = $request->paperworkid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $paperwork = DB::table('tblclassstudentpaperwork')
            ->join('tblclasspaperwork', 'tblclassstudentpaperwork.paperworkid', 'tblclasspaperwork.id')
            ->leftJoin('students', 'tblclassstudentpaperwork.userid', 'students.ic')
            ->select('tblclassstudentpaperwork.*', 'tblclassstudentpaperwork.paperworkid', 'tblclasspaperwork.title',  
                DB::raw('tblclasspaperwork.content as original_content'), 
                'tblclassstudentpaperwork.return_content',
                'tblclassstudentpaperwork.userid',
                'tblclassstudentpaperwork.subdate',
                'tblclassstudentpaperwork.final_mark',
                'tblclasspaperwork.deadline',
                DB::raw('tblclassstudentpaperwork.status as studentpaperworkstatus'),
                'students.name')
            ->where('tblclassstudentpaperwork.paperworkid', $id)
            ->where('tblclassstudentpaperwork.userid', $userid)->get()->first();

       
        $data['paperwork'] = $paperwork->content;
        $data['return'] = $paperwork->return_content;
        $data['mark'] = $paperwork->final_mark;
        $data['comments'] = $paperwork->comments;

        $data['paperworkid'] = $paperwork->paperworkid;
        $data['paperworktitle'] = $paperwork->title;
        $data['paperworkuserid'] = $paperwork->userid;
        $data['fullname'] = $paperwork->name;
        $data['created_at'] = $paperwork->created_at;
        $data['updated_at'] = $paperwork->updated_at;
        $data['subdate'] = $paperwork->subdate;
        $data['deadline'] = $paperwork->deadline;
        $data['studentpaperworkstatus'] = $paperwork->studentpaperworkstatus;

        return view('student.courseassessment.paperworkresult', compact('data'));
    }


}
