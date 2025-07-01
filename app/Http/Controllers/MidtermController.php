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

class MidtermController extends Controller
{
    
    public function midtermlist()
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

        $data = DB::table('tblclassmidterm')
                ->join('users', 'tblclassmidterm.addby', 'users.ic')->join('tblclassmidtermstatus', 'tblclassmidterm.status', 'tblclassmidtermstatus.id')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['tblclassmidterm.addby', $user->ic],
                    ['tblclassmidterm.deadline', null],
                    ['tblclassmidterm.status', '!=', 3]
                ])
                ->select('tblclassmidterm.*', 'users.name AS addby', 'tblclassmidtermstatus.statusname')->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassmidterm_group')
                        ->join('user_subjek', 'tblclassmidterm_group.groupid', 'user_subjek.id')
                        ->where('tblclassmidterm_group.midtermid', $dt->id)->get();

                $chapter[] = DB::table('tblclassmidterm_chapter')
                        ->join('material_dir', 'tblclassmidterm_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassmidterm_chapter.midtermid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.midterm', compact('data', 'group', 'chapter'));
    }

    public function midtermcreate()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseIDS');

        $sessionid = Session::get('SessionIDS');

        $data['midterm'] = null;

        $data['folder'] = null;

        $data['chapter'] = null;

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

        if(isset(request()->midtermid))
        {

            $data['midterm'] = DB::table('tblclassmidterm')->where('id', request()->midtermid)->first();

            //dd($data['folder']);
            
        }

     

        return view('lecturer.courseassessment.midtermcreate', compact(['group', 'folder', 'data']));
    }


    public function insertmidterm(Request $request){
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

        $dir = "classmidterm/" .  $classid . "/" . $user->name . "/" . $title;
        $classmidterm  = Storage::disk('linode')->makeDirectory($dir);
        $file = $request->file('myPdf');
            
        $midtermid = empty($request->midterm) ? '' : $request->midterm;

        if($group != null && $chapter != null)
        {
        
            if( !empty($midtermid) ){
                
                $midterm = DB::table('tblclassmidterm')->where('id', $midtermid)->first();

                Storage::disk('linode')->delete($midterm->content);

                DB::table('tblclassmidterm_group')->where('midtermid', $midtermid)->delete();

                DB::table('tblclassmidterm_chapter')->where('midtermid', $midtermid)->delete();

                $file_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $fileInfo = pathinfo($file_name);
                $filename = $fileInfo['filename'];
                $newname = $filename . "." . $file_ext;
                $newpath = "classmidterm/" .  $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                if(!file_exists($newname)){
                    Storage::disk('linode')->putFileAs(
                        $dir,
                        $file,
                        $newname,
                        'public'
                    );

                    $q = DB::table('tblclassmidterm')->where('id', $midtermid)->update([
                        "title" => $title,
                        'content' => $newpath,
                        "total_mark" => $marks,
                        "status" => 2
                    ]);

                    foreach($request->group as $grp)
                    {
                        $gp = explode('|', $grp);

                        DB::table('tblclassmidterm_group')->insert([
                            "groupid" => $gp[0],
                            "groupname" => $gp[1],
                            "midtermid" => $midtermid
                        ]);
                    }

                    foreach($request->chapter as $chp)
                    {
                        DB::table('tblclassmidterm_chapter')->insert([
                            "chapterid" => $chp,
                            "midtermid" => $midtermid
                        ]);
                    }

                }

            }else{
                $file_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $fileInfo = pathinfo($file_name);
                $filename = $fileInfo['filename'];
                $newname = $filename . "." . $file_ext;
                $newpath = "classmidterm/" .  $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                if(!file_exists($newname)){
                    Storage::disk('linode')->putFileAs(
                        $dir,
                        $file,
                        $newname,
                        'public'
                    );

                    $q = DB::table('tblclassmidterm')->insertGetId([
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

                        DB::table('tblclassmidterm_group')->insert([
                            "groupid" => $gp[0],
                            "groupname" => $gp[1],
                            "midtermid" => $q
                        ]);
                    }

                    foreach($request->chapter as $chp)
                    {
                        DB::table('tblclassmidterm_chapter')->insert([
                            "chapterid" => $chp,
                            "midtermid" => $q
                        ]);
                    }

                }

            }

        }else{

            return redirect()->back()->withErrors(['Please fill in the group and sub-chapter checkbox !']);

        }
        
        
        return redirect(route('lecturer.midterm', ['id' => $classid]));
    }

    public function deletemidterm(Request $request)
    {

        try {

            $midterm = DB::table('tblclassmidterm')->where('id', $request->id)->first();

            if($midterm->status != 3)
            {
            DB::table('tblclassmidterm')->where('id', $request->id)->update([
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

    public function lecturermidtermstatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassmidterm_group', 'user_subjek.id', 'tblclassmidterm_group.groupid')
                ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassmidterm.id', request()->midterm]
                ])->get();
                
            
        //dd($group);

        $midterm = DB::table('student_subjek')
                ->join('tblclassmidterm_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassmidterm_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassmidterm_group.groupname');
                })
                ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclassmidterm.id AS clssid', 'tblclassmidterm.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['tblclassmidterm.id', request()->midterm],
                    ['tblclassmidterm.addby', $user->ic]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();
        
        
        
        //dd($midterm);

        foreach($midterm as $qz)
        {
            //$status[] = DB::table('tblclassstudentmidterm')
            //->where([
               // ['midtermid', $qz->clssid],
               // ['userid', $qz->student_ic]
           // ])->get();

           if(!DB::table('tblclassstudentmidterm')->where([['midtermid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentmidterm')->insert([
                    'midtermid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentmidterm')
            ->where([
                ['midtermid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        //dd($status);

        return view('lecturer.courseassessment.midtermstatus', compact('midterm', 'status', 'group'));

    }

    public function midtermGetGroup(Request $request)
    {

        $user = Auth::user();

        $gp = explode('|', $request->group);

        $midterm = DB::table('student_subjek')
                ->join('tblclassmidterm_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassmidterm_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassmidterm_group.groupname');
                })
                ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclassmidterm.id AS clssid', 'tblclassmidterm.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['tblclassmidterm.id', request()->midterm],
                    ['tblclassmidterm.addby', $user->ic],
                    ['student_subjek.group_id', $gp[0]],
                    ['student_subjek.group_name', $gp[1]]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();
        
        foreach($midterm as $qz)
        {

           if(!DB::table('tblclassstudentmidterm')->where([['midtermid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentmidterm')->insert([
                    'midtermid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentmidterm')
            ->where([
                ['midtermid', $qz->clssid],
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
                            
        foreach ($midterm as $key => $qz) {
            $alert = ($status[$key]->final_mark != 0) ? 'badge bg-success' : 'badge bg-danger';

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
                    </td>';
            
            if ($status[$key]->final_mark != 0) {
                $content .= '
                    <td>
                        ' . $status[$key]->submittime . '
                    </td>';
            } else {
                $content .= '
                    <td>
                        -
                    </td>';
            }
            
            $content .= '
                    <td>
                        <div class="form-inline col-md-6 d-flex">
                            <input type="number" class="form-control" name="marks[]" max="' . $qz->total_mark . '" value="' . $status[$key]->final_mark . '">
                            <input type="text" name="ic[]" value="' . $qz->student_ic . '" hidden>
                            <span>' . $status[$key]->final_mark . ' / ' . $qz->total_mark . '</span>
                        </div>
                    </td>
                </tr>';
        }

        $content .= '</tbody>';

        return response()->json(['message' => 'success', 'content' => $content]);


    }

    public function updatemidterm(Request $request)
    {
        $user = Auth::user();

        $marks = json_decode($request->marks);

        $ics = json_decode($request->ics);

        $midtermid = json_decode($request->midtermid);

        $limitpercen = DB::table('tblclassmidterm')->where('id', $midtermid)->first();

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
            'midtermid' => $midtermid,
            'submittime' => date("Y-m-d H:i:s"),
            'final_mark' => $mrk,
            'status' => 1
            ]);
        }

        DB::table('tblclassstudentmidterm')->upsert($upsert, ['userid', 'midtermid']);

        return ["message"=>"Success", "id" => $ics];

    }

    //This is midterm Student Controller


    public function studentmidtermlist()
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

        $data = DB::table('tblclassmidterm')
                ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                ->join('tblclassmidtermstatus', 'tblclassmidterm.status', 'tblclassmidtermstatus.id')
                ->join('student_subjek', function($join){
                    $join->on('tblclassmidterm_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassmidterm_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassmidterm_group.groupid', 'user_subjek.id')
                ->select('tblclassmidterm.*', 'tblclassmidterm_group.groupname','tblclassmidtermstatus.statusname')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic],
                    ['tblclassmidterm.deadline', null],
                    ['tblclassmidterm.status','!=', 3]
                ])->get();

        //dd($data);

        foreach($data as $dt)
        {
            $chapter[] = DB::table('tblclassmidterm_chapter')
                      ->join('material_dir', 'tblclassmidterm_chapter.chapterid', 'material_dir.DrID')
                      ->where('tblclassmidterm_chapter.midtermid', $dt->id)->get();

            $marks[] = DB::table('tblclassstudentmidterm')
                      ->where([
                        ['midtermid', $dt->id],
                        ['userid', $student->ic]
                      ])->get();
        }

        //dd($marks);

        return view('student.courseassessment.midterm', compact('data', 'chapter', 'marks'));

    }

    public function studentmidtermstatus()
    {
        $midterm = DB::table('student_subjek')
                ->join('tblclassmidterm_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassmidterm_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassmidterm_group.groupname');
                })
                ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['tblclassmidterm.id', request()->midterm],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();
            
        foreach($midterm as $qz)
        {
            $status[] = DB::table('tblclassstudentmidterm')
            ->where([
                ['midtermid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.midtermstatus', compact('midterm', 'status'));
    }
}
