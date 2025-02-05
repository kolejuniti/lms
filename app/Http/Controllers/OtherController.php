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

        $data = DB::table('tblclassother')
                ->join('tblextra_title', 'tblclassother.title', 'tblextra_title.id')
                ->join('users', 'tblclassother.addby', 'users.ic')
                ->where([
                    ['tblclassother.classid', Session::get('CourseIDS')],
                    ['tblclassother.sessionid', Session::get('SessionIDS')],
                    ['tblclassother.addby', $user->ic],
                    ['tblclassother.status', '!=', 3]
                ])
                ->select('tblclassother.*', 'tblextra_title.name AS title', 'users.name AS addby')->get();

      
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

    public function othercreate()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseIDS');

        $sessionid = Session::get('SessionIDS');

        $data['other'] = null;

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

        //dd(Session::get('CourseIDS'));

        $subid = DB::table('subjek')->where('id', $courseid)->pluck('sub_id');

        $folder = DB::table('lecturer_dir')
                  ->join('subjek', 'lecturer_dir.CourseID','subjek.id')
                  ->where('subjek.sub_id', $subid)
                  ->where('Addby', $user->ic)->get();

        $title = DB::table('tblextra_title')->get();

        if(isset(request()->otherid))
        {

            $data['other'] = DB::table('tblclassother')->where('id', request()->otherid)->first();

            //dd($data['folder']);
            
        }

        $percentage = DB::table('tblclassmarks')
                        ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                            ['subjek.id', $courseid],
                            ['assessment', 'lain-lain']
                        ])
                        ->orderBy('tblclassmarks.id', 'desc')
                        ->first();

        $total_mark = DB::table('tblclassother')->where([
            ['classid', $courseid],
            ['sessionid', $sessionid],
            ['addby', $user->ic],
            ['status', '!=', 3]
        ])->sum('total_mark');

        $maxMark = $percentage->mark_percentage;

        //dd($folder);

        return view('lecturer.courseassessment.othercreate', compact(['group', 'folder', 'title', 'data', 'maxMark']));
    }

    public function insertother(Request $request)
    {

        //$data = $request->data;
        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');
        $title = $request->title;
        $no = $request->no;
        $group = $request->group;
        $chapter = $request->chapter;
        $marks = $request->marks;

        $data = $request->validate([
            'myPdf' => 'mimes:pdf'
        ]);

        $user = Auth::user();

        $dir = "classother/" .  $classid . "/" . $user->name . "/" . $title;
        $classother  = Storage::disk('linode')->makeDirectory($dir);
        $file = $request->file('myPdf') ?? null;
            
        $otherid = empty($request->other) ? '' : $request->other;

        $percentage = DB::table('tblclassmarks')
                        ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                            ['subjek.id', $classid],
                            ['assessment', 'lain-lain']
                        ])
                        ->orderBy('tblclassmarks.id', 'desc')
                        ->first();

        //dd($percentage);

        if($group != null && $chapter != null)
        {
        
            if( !empty($otherid) ){

                // $total_mark = DB::table('tblclassother')
                // ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')->where([
                //     ['classid', $classid],
                //     ['sessionid', $sessionid],
                //     ['addby', $user->ic],
                //     ['status', '!=', 3]
                // ])->where('tblclassother.id', '!=', $otherid)->sum('total_mark');

                // $fullmarks = $total_mark + $marks;

                // if($fullmarks > $percentage->mark_percentage)
                // {

                //     return redirect()->back()->withErrors(['Marks cannot exceeds ' . $percentage->mark_percentage - $total_mark . ' , please lower the marks']);

                // }else{
      
                    $other = DB::table('tblclassother')->where('id', $otherid)->first();

                    if($other->content != null)
                    {
                        Storage::disk('linode')->delete($other->content);
                    }

                    DB::table('tblclassother_group')->where('otherid', $otherid)->delete();

                    DB::table('tblclassother_chapter')->where('otherid', $otherid)->delete();

                    if($file != null)
                    {

                        $file_name = $file->getClientOriginalName();
                        $file_ext = $file->getClientOriginalExtension();
                        $fileInfo = pathinfo($file_name);
                        $filename = $fileInfo['filename'];
                        $newname = $filename . "." . $file_ext;
                        $newpath = "classother/" .  $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                    }else{

                        $newname = null;
                        $newpath = null;

                    }

                    if($newname != null){
                        Storage::disk('linode')->putFileAs(
                            $dir,
                            $file,
                            $newname,
                            'public'
                        );

                    }

                    $q = DB::table('tblclassother')->where('id', $otherid)->update([
                        "title" => $title,
                        "no" => $no,
                        "content" => $newpath,
                        "total_mark" => $marks,
                        "addby" => $user->ic,
                        "status" => 2
                    ]);

                    foreach($group as $grp)
                    {
                        $gp = explode('|', $grp);
                        
                        DB::table('tblclassother_group')->insert([
                            "groupid" => $gp[0],
                            "groupname" => $gp[1],
                            "otherid" => $otherid
                        ]);
                    }

                    foreach($chapter as $chp)
                    {
                        DB::table('tblclassother_chapter')->insert([
                            "chapterid" => $chp,
                            "otherid" => $otherid
                        ]);
                    }
                // }

            }else{

                if($percentage->mark_percentage != null || $percentage->mark_percentage != 0)
                {

                    // $total_mark = DB::table('tblclassother')->where([
                    //     ['classid', $classid],
                    //     ['sessionid', $sessionid],
                    //     ['addby', $user->ic],
                    //     ['status', '!=', 3]
                    // ])->sum('total_mark');

                    // $fullmarks = $total_mark + $marks;

                    // if($fullmarks > $percentage->mark_percentage)
                    // {

                    //     return redirect()->back()->withErrors(['Marks cannot exceeds ' . $percentage->mark_percentage - $total_mark . ' , please lower the marks']);

                    // }else{

                        if($file != null)
                        {

                            $file_name = $file->getClientOriginalName();
                            $file_ext = $file->getClientOriginalExtension();
                            $fileInfo = pathinfo($file_name);
                            $filename = $fileInfo['filename'];
                            $newname = $filename . "." . $file_ext;
                            $newpath = "classother/" .  $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                        }else{

                            $newname = null;
                            $newpath = null;
                            
                        }

                        if($newname != null){
                            Storage::disk('linode')->putFileAs(
                                $dir,
                                $file,
                                $newname,
                                'public'
                            );

                        }

                        $q = DB::table('tblclassother')->insertGetId([
                            "classid" => $classid,
                            "sessionid" => $sessionid,
                            "title" => $title,
                            "no" => $no,
                            "content" => $newpath,
                            "total_mark" => $marks,
                            "addby" => $user->ic,
                            "status" => 2
                        ]);

                        foreach($group as $grp)
                        {
                            $gp = explode('|', $grp);
                            
                            DB::table('tblclassother_group')->insert([
                                "groupid" => $gp[0],
                                "groupname" => $gp[1],
                                "otherid" => $q
                            ]);
                        }

                        foreach($chapter as $chp)
                        {
                            DB::table('tblclassother_chapter')->insert([
                                "chapterid" => $chp,
                                "otherid" => $q
                            ]);
                        }
                    // }
                }else{

                    return redirect()->back()->withErrors(['Percentage is not set yet, please consult the person in charge (KETUA PROGRAM)']);

                }
            }
        
        }else{

            return back()->withErrors(['Please fill in the group and sub-chapter checkbox !']);

        }
        
        return redirect(route('lecturer.other', ['id' => $classid]));

    }

    public function deleteother(Request $request)
    {

        try {

            $other = DB::table('tblclassother')->where('id', $request->id)->first();

            if($other->status != 3)
            {
            DB::table('tblclassother')->where('id', $request->id)->update([
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

    public function backupinsertother ()
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

        $classother  = Storage::disk('linode')->makeDirectory($dir);

        $file = $request->file('myPdf');

        //dd($file);

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;
        $newpath = "classother/" .  $classid . "/" . $user->name . "/" . $data['other-title'] . "/" . $newname;

        if(! file_exists($newname)){
            Storage::disk('linode')->putFileAs(
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
                    ['tblclassother.sessionid', Session::get('SessionIDS')],
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
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclassother.id AS clssid', 'tblclassother.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassother.classid', Session::get('CourseIDS')],
                    ['tblclassother.sessionid', Session::get('SessionIDS')],
                    ['tblclassother.id', request()->other],
                    ['tblclassother.addby', $user->ic]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();
        
        
        
        //dd($other);

        foreach($other as $qz)
        {
            //$status[] = DB::table('tblclassstudentother')
            //->where([
               // ['otherid', $qz->clssid],
               // ['userid', $qz->student_ic]
           // ])->get();

           if(!DB::table('tblclassstudentother')->where([['otherid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentother')->insert([
                    'otherid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentother')
            ->where([
                ['otherid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        //dd($status);

        return view('lecturer.courseassessment.otherstatus', compact('other', 'status', 'group'));

    }

    public function otherGetGroup(Request $request)
    {

        $user = Auth::user();

        $gp = explode('|', $request->group);

        $other = DB::table('student_subjek')
                ->join('tblclassother_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassother_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassother_group.groupname');
                })
                ->join('tblclassother', 'tblclassother_group.otherid', 'tblclassother.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclassother.id AS clssid', 'tblclassother.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassother.classid', Session::get('CourseIDS')],
                    ['tblclassother.sessionid', Session::get('SessionIDS')],
                    ['tblclassother.id', request()->other],
                    ['tblclassother.addby', $user->ic],
                    ['student_subjek.group_id', $gp[0]],
                    ['student_subjek.group_name', $gp[1]]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();

        foreach($other as $qz)
        {

           if(!DB::table('tblclassstudentother')->where([['otherid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentother')->insert([
                    'otherid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentother')
            ->where([
                ['otherid', $qz->clssid],
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

        foreach ($other as $key => $qz) {
            $alert = ($status[$key]->total_mark != 0) ? 'badge bg-success' : 'badge bg-danger';

            $content .= '
                <tr>
                    <td style="width: 1%">' . ($key + 1) . '</td>
                    <td>
                        <span class="' . $alert . '">' . $qz->name . '</span>
                    </td>
                    <td>
                        <span>' . $qz->no_matric . '</span>
                    </td>
                    <td>
                        <span>' . $qz->progcode . '</span>
                    </td>';
            
            if ($status[$key]->total_mark != 0) {
                $content .= '
                    <td>' . $status[$key]->submittime . '</td>';
            } else {
                $content .= '
                    <td>-</td>';
            }
            
            $content .= '
                    <td>
                        <div class="form-inline col-md-6 d-flex">
                            <input type="number" class="form-control" name="marks[]" max="' . $qz->total_mark . '" value="' . $status[$key]->total_mark . '">
                            <input type="text" name="ic[]" value="' . $qz->student_ic . '" hidden>
                            <span>' . $status[$key]->total_mark . ' / ' . $qz->total_mark . '</span>
                        </div>
                    </td>
                </tr>';
        }

        $content .= '</tbody>';


        return response()->json(['message' => 'success', 'content' => $content]);


    }

    public function updateother(Request $request)
    {
        $user = Auth::user();

        $marks = json_decode($request->marks);

        $ics = json_decode($request->ics);

        $otherid = json_decode($request->otherid);

        $limitpercen = DB::table('tblclassother')->where('id', $otherid)->first();

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
            'otherid' => $otherid,
            'submittime' => date("Y-m-d H:i:s"),
            'total_mark' => $mrk,
            'status' => 1
            ]);
        }

        DB::table('tblclassstudentother')->upsert($upsert, ['userid', 'otherid']);

        return ["message"=>"Success", "id" => $ics];

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
            Storage::disk('linode')->putFileAs(
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
