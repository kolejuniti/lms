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

class ExtraController extends Controller
{
    //

    public function extralist()
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

        $data = DB::table('tblclassextra')
                ->join('tblextra_title', 'tblclassextra.title', 'tblextra_title.id')
                ->join('users', 'tblclassextra.addby', 'users.ic')
                ->where([
                    ['tblclassextra.classid', Session::get('CourseIDS')],
                    ['tblclassextra.sessionid', Session::get('SessionIDS')],
                    ['tblclassextra.addby', $user->ic],
                    ['tblclassextra.status', '!=', 3]
                ])
                ->select('tblclassextra.*', 'tblextra_title.name AS title', 'users.name AS addby')->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassextra_group')
                        ->join('user_subjek', 'tblclassextra_group.groupid', 'user_subjek.id')
                        ->where('tblclassextra_group.extraid', $dt->id)->get();

                $chapter[] = DB::table('tblclassextra_chapter')
                        ->join('material_dir', 'tblclassextra_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassextra_chapter.extraid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.extra', compact('data', 'group', 'chapter'));
    }

    public function extracreate()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseIDS');

        $sessionid = Session::get('SessionIDS');

        $totalpercent = 0;

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

        $title = DB::table('tblextra_title')->get();

        //dd($folder);

        $percentage = DB::table('tblclassmarks')->where([
            ['course_id', $courseid],
            ['assessment', 'extra']
        ])->first();

        $markextra =  DB::table('tblclassextra')->where([
            ['classid', $courseid],
            ['sessionid', $sessionid],
            ['addby', $user->ic]
        ])->sum('total_mark');

        /*if($percentage != null)
        {

            if($markextra != null)
            {
                $totalpercent = $percentage->mark_percentage - $markextra;
            }else{
                $totalpercent = $percentage->mark_percentage;
            }

            return view('lecturer.courseassessment.extracreate', compact(['group', 'folder', 'title', 'totalpercent']));
        }else{
            return redirect()->back()->with('alert', 'Your Program Lead have not yet set the Percentage mark!');
        }*/

        return view('lecturer.courseassessment.extracreate', compact(['group', 'folder', 'title', 'totalpercent']));
    }


    public function insertextra(Request $request){
        //$data = $request->data;
        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');
        $title = $request->title;
        $group = $request->group;
        $chapter = $request->chapter;
        $marks = $request->marks;

        $user = Auth::user();
            
        $extraid = empty($request->extra) ? '' : $request->extra;

        if($group != null && $chapter != null)
        {
        
            if( !empty($extraid) ){
                $q = DB::table('tblclassextra')->where('id', $extraid)->update([
                    "title" => $title,
                    "total_mark" => $marks,
                    "addby" => $user->ic,
                    "status" => 2
                ]);
            }else{
                $q = DB::table('tblclassextra')->insertGetId([
                    "classid" => $classid,
                    "sessionid" => $sessionid,
                    "title" => $title,
                    "total_mark" => $marks,
                    "addby" => $user->ic,
                    "status" => 2
                ]);

                foreach($group as $grp)
                {
                    $gp = explode('|', $grp);
                    
                    DB::table('tblclassextra_group')->insert([
                        "groupid" => $gp[0],
                        "groupname" => $gp[1],
                        "extraid" => $q
                    ]);
                }

                foreach($chapter as $chp)
                {
                    DB::table('tblclassextra_chapter')->insert([
                        "chapterid" => $chp,
                        "extraid" => $q
                    ]);
                }
            }
        
        }else{

            return redirect()->back()->withErrors(['Please fill in the group and sub-chapter checkbox !']);

        }
        
        return redirect(route('lecturer.extra', ['id' => $classid]));
    }

    public function lecturerextrastatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassextra_group', 'user_subjek.id', 'tblclassextra_group.groupid')
                ->join('tblclassextra', 'tblclassextra_group.extraid', 'tblclassextra.id')
                ->where([
                    ['tblclassextra.classid', Session::get('CourseIDS')],
                    ['tblclassextra.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassextra.id', request()->extra]
                ])->get();
                
            
        //dd($group);

        $extra = DB::table('student_subjek')
                ->join('tblclassextra_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassextra_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassextra_group.groupname');
                })
                ->join('tblclassextra', 'tblclassextra_group.extraid', 'tblclassextra.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassextra.id AS clssid', 'tblclassextra.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassextra.classid', Session::get('CourseIDS')],
                    ['tblclassextra.sessionid', Session::get('SessionIDS')],
                    ['tblclassextra.id', request()->extra],
                    ['tblclassextra.addby', $user->ic]
                ])->orderBy('students.name')->get();
        
        
        
        //dd($extra);

        foreach($extra as $qz)
        {
            //$status[] = DB::table('tblclassstudentextra')
            //->where([
               // ['extraid', $qz->clssid],
               // ['userid', $qz->student_ic]
           // ])->get();

           if(!DB::table('tblclassstudentextra')->where([['extraid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentextra')->insert([
                    'extraid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentextra')
            ->where([
                ['extraid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        //dd($status);

        return view('lecturer.courseassessment.extrastatus', compact('extra', 'status', 'group'));

    }

    public function updateextra(Request $request)
    {
        $user = Auth::user();

        $marks = json_decode($request->marks);

        $ics = json_decode($request->ics);

        $extraid = json_decode($request->extraid);

        $limitpercen = DB::table('tblclassextra')->where('id', $extraid)->first();

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
            'extraid' => $extraid,
            'submittime' => date("Y-m-d H:i:s"),
            'total_mark' => $mrk,
            'status' => 1
            ]);
        }

        DB::table('tblclassstudentextra')->upsert($upsert, ['userid', 'extraid']);

        return ["message"=>"Success", "id" => $ics];

    }
}
