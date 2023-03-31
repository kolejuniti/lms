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
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;
use Mail;
use Intervention\Image\Facades\Image;

class AdminController extends Controller
{
    public function dashboard()
    {
        Session::put('User', Auth::user());

        return view('dashboard');
    }

    public function index() 
    {

        //dd(Session::get('User'));

        $users = User::all()->sortBy('usrtype');

        //dd($users);

        return view('admin',['users'=>$users]);
    }

    public function create()
    {
        $faculty = DB::table('tblfaculty')->get();

        return view('admin.create', compact('faculty'));
    }

    public function store()
    {
        //this will validate the requested data
        $data = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'nostaf' => ['required', 'string', 'max:45'],
            'ic' => ['required', 'string', 'max:12'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'usrtype' => ['required'],
            'faculty' => ['required'],
        ]);

        //dd(array_values(array_filter(request()->prg,function($v){return !is_null($v);})));

        //this will create data in table [Please be noted that model need to be fillable with the same data]
        User::create([
            'name' => $data['name'],
            'no_staf' => $data['nostaf'],
            'ic' => $data['ic'],
            'email' => $data['email'],
            'password' => Hash::make('12345678'),
            'usrtype' => $data['usrtype'],
            'faculty' => $data['faculty'],
            'start' => request()->from,
            'end' => request()->to
        ]);

        if(isset(request()->program))
        {
            foreach(request()->program as $prg)
            {
                DB::table('user_program')->insert([
                    'user_ic' => $data['ic'],
                    'program_id' => $prg
                ]);
            }
        }

        if(isset(request()->academic))
        {
            $pgname = array_values(array_filter(request()->prg,function($v){return !is_null($v);}));

            $uniname = array_values(array_filter(request()->uni,function($v){return !is_null($v);}));

            foreach(request()->academic as $key => $ac)
            {
                DB::table('tbluser_academic')->insert([
                    'user_ic' => $data['ic'],
                    'academic_id' => $ac,
                    'academic_name' => $pgname[$key],
                    'university_name' => $uniname[$key]
                ]);
            }
        }

        return redirect('admin');
    }

    public function delete(Request $request)
    {

        User::where('ic', $request->ic)->delete();

        return true;
        
    }

    public function edit(User $id)
    {
        $faculty = DB::table('tblfaculty')->get();

        //dd($id);

        $academics = array("DP|DIPLOMA", 'DG|DEGREE', 'MS|MASTER', 'PHD|PHD');

        foreach($academics as $ac)
        {
            $ace = explode('|', $ac);

            $academic[] = DB::table('tbluser_academic')->where('user_ic', $id->ic)->where('academic_id', $ace[0])->first();
        }

        //dd($academic);


        return view('admin.edit' , compact('id', 'faculty', 'academic', 'academics'));
    }

    //a function with (Modal $variable) is to make sql query *example = select * from User where id = $id
    public function update(User $id)
    {
        $data = request()->validate([
            'name' => 'required',
            'ic' => 'required',
            'usrtype' => 'required',
            'email' => 'required',
        ]);

        $data2 = [
                    'no_staf' => request()->nostaf,
                    'faculty' => request()->faculty,
                    'start' => request()->from,
                    'end' => request()->to,
                    'status' => request()->status,
                    'comment' => request()->comments
                 ];

        //this is to check if image is not empty
        if(request('image'))
        {
            $imageName = request('image')->getClientOriginalName(); 

            $filepath = "storage/";

            //this is to store file/image in specific folder
            //$imagePath = request('image')->storeAs('storage', $imageName, 'linode', 'public');

            Storage::disk('linode')->putFileAs(
                $filepath,
                request('image'),
                $imageName,
                'public'
              );

              $imagePath = $filepath . $imageName;

            //dd($imagePath);

            //this is to resize image  Image need to be declared with 'use Intervention\Image\Facades\Image;'
            $image = Image::make(Storage::disk('linode')->url($imagePath))->fit(1000, 1000);
            //dd($image);
            $image->save($imagePath);

            //store path in image parameter and store in $imageArray variable
            $imageArray = ['image' => $imagePath];
        }

        //array_merge is a function to merge two variable together
        User::where('id', $id->id)->update(array_merge(
            $data,
            $data2,
            $imageArray ?? []
        ));

        //dd(request()->program);

        if(request()->program != null)
        {
            DB::table('user_program')->where('user_ic', $data['ic'])->delete();
            
            foreach(request()->program as $prg)
            {

                DB::table('user_program')->insert([
                    'user_ic' => $data['ic'],
                    'program_id' => $prg
                ]);
            }
        }

        if(request()->academic != null)
        {
            $pgname = $pgname = array_values(array_filter(request()->prg,function($v){return !is_null($v);}));

            $uniname = array_values(array_filter(request()->uni,function($v){return !is_null($v);}));

            DB::table('tbluser_academic')->where('user_ic', $data['ic'])->delete();

            foreach(request()->academic as $key => $ac)
            {
                DB::table('tbluser_academic')->insert([
                    'user_ic' => $data['ic'],
                    'academic_id' => $ac,
                    'academic_name' => $pgname[$key],
                    'university_name' => $uniname[$key]
                ]);
            }
        }

        return redirect("/admin");
    }

    public function getProgramoptions(Request $request)
    {
        $program = DB::table('tblprogramme')->where('facultyid', $request->faculty)->get();

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
        foreach($program as $key => $prg){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <label>'.$key+1 .'</label>
                </td>
                <td >
                    <label>'.$prg->progname.'</label>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="program_checkbox_'.$prg->id.'"
                            class="filled-in" name="program[]" value="'.$prg->id.'" 
                        >
                        <label for="program_checkbox_'.$prg->id.'"> </label>
                    </div>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';

            return $content;
    }


    public function getProgramoptions2(Request $request)
    {

        $program = DB::table('tblprogramme')->where('facultyid', $request->faculty)->get();

        foreach($program as $prg)
        {

            $programs[] = DB::table('user_program')->where('user_ic', $request->ic)->where('program_id', $prg->id)->get();

        }

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
        foreach($program as $key => $prg){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <label>'.$key+1 .'</label>
                </td>
                <td >
                    <label>'.$prg->progname.'</label>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="program_checkbox_'.$prg->id.'"
                            class="filled-in" name="program[]" value="'.$prg->id.'"
                        ';
                        if(count($programs[$key]) > 0)
                        {
                            $content .= 'checked'; 
                        }
                        
                        $content .= '
                        >
                        <label for="program_checkbox_'.$prg->id.'"> </label>
                    </div>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';

            return $content;

        return view('admin.getprogram', compact('program', 'id'));
    }

    
    public function getReportLecturer()
    {

        $faculty = DB::table('tblfaculty')->get();

        foreach($faculty as $key => $fcl)
        {
            $lecturer[] = DB::table('users')->where('status', 'ACTIVE')->where('faculty', $fcl->id)->whereIn('usrtype', ['LCT','PL'])->get();

            //dd($lecturer);

            foreach($lecturer[$key] as $key1 => $lct)
            {
                $course[$key][$key1] = DB::table('user_subjek')
                    ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
                    ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
                    ->where('user_subjek.user_ic', $lct->ic)
                    ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID')
                    ->groupBy('subjek.sub_id', 'user_subjek.session_id')
                    ->get();

            }
        }

        //dd($course);

        return view('admin.lecturerReport', compact('faculty','lecturer','course'));

    }

    public function getFolder(Request $request)
    {
        Session::put('CourseID', $request->id);

        Session::put('SessionID', $request->ses);

        Session::put('LectIC', $request->ic);

        $folder = DB::table('lecturer_dir')->where('Addby', $request->ic)->where('CourseID', $request->id)->get();

        return view('admin.getSubfolder', compact('folder'));
    }

    public function getSubFolder(Request $request)
    {

        $subfolder = DB::table('material_dir')->where('LecturerDirID', $request->id)->get();

        $prev0 = $folder = DB::table('lecturer_dir')->where('DrID', $request->id)->first();

        return view('admin.getSubfolder', compact('subfolder','prev0'));

    }

    public function getSubFolder2(Request $request)
    {

        $directory = DB::table('lecturer_dir')
        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*', 'lecturer_dir.CourseID')
        ->where('material_dir.DrID', $request->id)->first();

        $subfolder2 = DB::table('materialsub_dir')->where('MaterialDirID', $request->id)->get();

        $dir = "classmaterial/" . $directory->CourseID . "/" . $directory->A . "/" . $directory->B;

        //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
        $classmaterial  = Storage::disk('linode')->files($dir);

        $prev = $directory->LecturerDirID;

        return view('admin.getSubfolder', compact('subfolder2', 'classmaterial','prev'));

    }

    public function getMaterial(Request $request)
    {

        $directory = DB::table('lecturer_dir')
        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
        ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'materialsub_dir.DrID', 'lecturer_dir.CourseID')
        ->where('materialsub_dir.DrID', $request->id)->first();

        $dir = "classmaterial/" . $directory->CourseID . "/" . $directory->A . "/" . $directory->B . "/" . $directory->C;

        $classmaterial  = Storage::disk('linode')->allFiles($dir);

        $prev2 = $directory->MaterialDirID;

        return view('admin.getSubfolder', compact('classmaterial','prev2'));
    }

    public function listAttendance(Request $request)
    {
        $guess = 1;
        
        $user = Session::get('LectIC');

        $courseid = Session::get('CourseID');

        $sessionid = Session::get('SessionID');

        $students = [];
        $list = [];
        $status = [];



        $groups = DB::table('user_subjek')
                  ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                  ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                  ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id')
                  ->where([
                     ['user_subjek.user_ic', $user],
                     ['user_subjek.session_id', Session::get('SessionID')],
                     ['subjek.id', $courseid]
                  ])->groupBy('student_subjek.group_name')->get();

        foreach($groups as $ky => $grp)
        {


                $students[] = $data = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id','students.*')
                ->where([
                ['user_subjek.user_ic', $user],
                ['user_subjek.session_id', $sessionid],
                ['subjek.id', $courseid]
                ])->where('student_subjek.group_name', $grp->group_name)
                ->orderBy('students.name')->get();

                $collection = collect($students[$ky]);

                $list[] = DB::table('tblclassattendance')
                ->join('user_subjek', 'tblclassattendance.groupid', 'user_subjek.id')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->where([
                    ['subjek.id', $courseid],
                    ['user_subjek.session_id', $sessionid],
                    ['user_subjek.user_ic', $user],
                    ['tblclassattendance.groupname', $grp->group_name]
                ])->groupBy('tblclassattendance.classdate')
                ->orderBy('tblclassattendance.classdate', 'ASC')
                ->select('tblclassattendance.*')->get();

                //dd($list);

                foreach($students[$ky] as $key => $std)
                {

                    foreach($list[$ky] as $keys => $ls)
                    {
                        $atten = DB::table('tblclassattendance')
                        ->where([
                            ['tblclassattendance.groupid', $ls->groupid],
                            ['tblclassattendance.groupname', $grp->group_name],
                            ['tblclassattendance.student_ic', $std->ic],
                            ['tblclassattendance.classdate', $ls->classdate]
                        ])->select('tblclassattendance.*');
                        
                        $attendance = $atten->first();

                        if($atten->exists())
                        {

                            if($attendance->excuse == null && $attendance->mc == null)
                            {

                                $status[$ky][$key][$keys] = 'Present';

                            }elseif($attendance->excuse != null){

                                $status[$ky][$key][$keys] = 'THB';

                            }elseif($attendance->mc != null){

                                $status[$ky][$key][$keys] = 'MC';

                            }


                        }else{

                            $status[$ky][$key][$keys] = 'Absent';

                        }
                        

                    }


                }

        }

        //dd($status[$ky][$key]);

        return view('lecturer.class.attendancereport', compact('groups', 'students', 'list', 'status', 'guess'));

    }

    /*public function getAssessment(Request $request)
    {

        if($request->from != '' && $request->to != '')
        {
            $course = Session::get('CourseID', $request->id);

            $session = Session::get('SessionID', $request->ses);

            $ic = Session::get('LectIC', $request->ic);

            $assessment = DB::table('tblclassquiz')
            ->where([
                      ['classid', $course],
                      ['sessionid', $session],
                      ['addby', $ic]
                      ])->whereBetween('created_at', [$request->from, $request->to])->get();

            $assessment2 = DB::table('tblclasstest')
            ->where([
                    ['classid', $course],
                    ['sessionid', $session],
                    ['addby', $ic]
                    ])->whereBetween('created_at', [$request->from, $request->to])->get();

            $assessment3 = DB::table('tblclassassign')
                ->where([
                        ['classid', $course],
                        ['sessionid', $session],
                        ['addby', $ic]
                        ])->whereBetween('created_at', [$request->from, $request->to])->get();

            $content = "";
            $content .= '
            <div class="table-responsive" style="width:99.7%">
            <table id="table_registerstudent" class="w-100 table table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead class="thead-themed">
                <th>No</th>
                <th>Title</th>
                <th>Date</th>
                <th></th>
                </thead>
                <tbody>
            ';
            foreach($assessment as $key => $as){
                //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td >
                        <label>'.$key+1 .'</label>
                    </td>
                    <td >
                        <label>'.$as->title.'</label>
                    </td>
                    <td >
                        <label>'.$as->created_at.'</label>
                    </td>
                </tr>
                ';
            }

            foreach($assessment2 as $key => $as){
                //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td >
                        <label>'.$key+1 .'</label>
                    </td>
                    <td >
                        <label>'.$as->title.'</label>
                    </td>
                    <td >
                        <label>'.$as->created_at.'</label>
                    </td>
                </tr>
                ';
            }

            foreach($assessment3 as $key => $as){
                //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td >
                        <label>'.$key+1 .'</label>
                    </td>
                    <td >
                        <label>'.$as->title.'</label>
                    </td>
                    <td >
                        <label>'.$as->created_at.'</label>
                    </td>
                </tr>
                ';
            }
                $content .= '</tbody></table>
                
                <script>
                $(\'#table_registerstudent\').DataTable({
                    dom: \'lBfrtip\', // if you remove this line you will see the show entries dropdown
                    
                    buttons: [
                        \'copy\', \'csv\', \'excel\', \'pdf\', \'print\'
                    ],
                  });
                </script>';

            return $content;

        }

    }*/

    public function assessment()
    {

        return view('admin.report.assessment');

    }

    public function getAssessment(Request $request)
    {

        $data['quiz'] = DB::table('tblclassquiz')
        ->join('users', 'tblclassquiz.addby', 'users.ic')
        ->whereBetween('tblclassquiz.created_at', [$request->from, $request->to])
        ->select('tblclassquiz.*', 'users.name')->get();

        $data['test'] = DB::table('tblclasstest')
        ->join('users', 'tblclasstest.addby', 'users.ic')
        ->whereBetween('tblclasstest.created_at', [$request->from, $request->to])
        ->select('tblclasstest.*', 'users.name')->get();

        $data['assign'] = DB::table('tblclassassign')
        ->join('users', 'tblclassassign.addby', 'users.ic')
        ->whereBetween('tblclassassign.created_at', [$request->from, $request->to])
        ->select('tblclassassign.*', 'users.name')->get();

        $data['other'] = DB::table('tblclassother')
        ->join('users', 'tblclassother.addby', 'users.ic')
        ->whereBetween('tblclassother.created_at', [$request->from, $request->to])
        ->select('tblclassother.*', 'users.name')->get();

        $data['extra'] = DB::table('tblclassextra')
        ->join('users', 'tblclassextra.addby', 'users.ic')
        ->whereBetween('tblclassextra.created_at', [$request->from, $request->to])
        ->select('tblclassextra.*', 'users.name')->get();

        $data['midterm'] = DB::table('tblclassmidterm')
        ->join('users', 'tblclassmidterm.addby', 'users.ic')
        ->whereBetween('tblclassmidterm.created_at', [$request->from, $request->to])
        ->select('tblclassmidterm.*', 'users.name')->get();

        $data['final'] = DB::table('tblclassfinal')
        ->join('users', 'tblclassfinal.addby', 'users.ic')
        ->whereBetween('tblclassfinal.created_at', [$request->from, $request->to])
        ->select('tblclassfinal.*', 'users.name')->get();

        return view('admin.report.getAssessment', compact('data'));

    }


    public function getUserLog(Request $request)
    {

        if($request->from != '' && $request->to != '')
        {

            $log = DB::table('tbluser_log')
            ->where([
                      ['ic', $request->user]
                      ])->whereBetween('date', [$request->from, $request->to])->get();

            $content = "";
            $content .= '
            <div class="table-responsive" style="width:99.7%">
            <table id="table_registerstudent" class="w-100 table table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead class="thead-themed">
                <th>No</th>
                <th>Remark</th>
                <th>Date</th>
                <th></th>
                </thead>
                <tbody>
            ';
            foreach($log as $key => $lg){
                //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td >
                        <label>'.$key+1 .'</label>
                    </td>
                    <td >
                        <label>'.$lg->remark.'</label>
                    </td>
                    <td >
                        <label>'.$lg->date.'</label>
                    </td>
                </tr>
                ';
            }

                $content .= '</tbody></table>
                
                <script>
                $(\'#table_registerstudent\').DataTable({
                    dom: \'lBfrtip\', // if you remove this line you will see the show entries dropdown
                    
                    buttons: [
                        \'copy\', \'csv\', \'excel\', \'pdf\', \'print\'
                    ],
                  });
                </script>';

            return $content;

        }

    }

    public function assessmentreport()
    {
        $students = [];

        $quiz = [];
        $quizcollection = [];
        $overallquiz = [];
        $quizanswer = [];
        $quizavg = [];
        $quizmax = [];
        $quizmin = [];
        $quizavgoverall = [];

        $test = [];
        $testcollection = [];
        $overalltest = [];
        $testanswer = [];
        $testavg = [];
        $testmax = [];
        $testmin = [];
        $testavgoverall = [];

        $assign = [];
        $assigncollection = [];
        $overallassign = [];
        $assignanswer = [];
        $assignavg = [];
        $assignmax = [];
        $assignmin = [];
        $assignavgoverall = [];

        $midterm = [];
        $midtermcollection = [];
        $overallmidterm = [];
        $midtermanswer = [];
        $midtermavg = [];
        $midtermmax = [];
        $midtermmin = [];
        $midtermavgoverall = [];

        $final = [];
        $finalcollection = [];
        $overallfinal = [];
        $finalanswer = [];
        $finalavg = [];
        $finalmax = [];
        $finalmin = [];
        $finalavgoverall = [];

        $paperwork = [];
        $paperworkcollection = [];
        $overallpaperwork = [];
        $paperworkanswer = [];
        $paperworkavg = [];
        $paperworkmax = [];
        $paperworkmin = [];
        $paperworkavgoverall = [];

        $practical = [];
        $practicalcollection = [];
        $overallpractical = [];
        $practicalanswer = [];
        $practicalavg = [];
        $practicalmax = [];
        $practicalmin = [];
        $practicalavgoverall = [];

        $other = [];
        $othercollection = [];
        $overallother = [];
        $otheranswer = [];
        $otheravg = [];
        $othermax = [];
        $othermin = [];
        $otheravgoverall = [];

        $extra = [];
        $extracollection = [];
        $overallextra = [];
        $extraanswer = [];
        $extraavg = [];
        $extramax = [];
        $extramin = [];
        $extraavgoverall = [];

        $overallall = [];
        $avgoverall = [];
        $valGrade = [];
        $user = User::where('ic', Session::get('LectIC'))->first();

        $id = Session::get('CourseID');

        $groups = DB::table('user_subjek')
                  ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                  ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                  ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id')
                  ->where([
                     ['user_subjek.user_ic', $user->ic],
                     ['user_subjek.session_id', Session::get('SessionID')],
                     ['subjek.id',$id]
                  ])->groupBy('student_subjek.group_name')->get();

        foreach($groups as $ky => $grp)
        {


                $students[] = $data = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id','students.*')
                ->where([
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', Session::get('SessionID')],
                ['subjek.id',$id]
                ])->where('student_subjek.group_name', $grp->group_name)
                ->orderBy('students.name')->get();

                $collection = collect($students[$ky]);

                //QUIZ

                $quizs = DB::table('tblclassquiz')
                        ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                        ->where([
                            ['tblclassquiz.classid',$id],
                            ['tblclassquiz.sessionid', Session::get('SessionID')],
                            ['tblclassquiz_group.groupname', $grp->group_name],
                            ['tblclassquiz.status', '!=', 3]
                        ]);

                $quiz[] = $quizs->get();

                $quizid = $quizs->pluck('tblclassquiz.id');

                $totalquiz = $quizs->sum('tblclassquiz.total_mark');

                foreach($quiz[$ky] as $key => $qz)
                {

                    $quizarray = DB::table('tblclassstudentquiz')
                                            ->join('tblclassquiz', 'tblclassstudentquiz.quizid', 'tblclassquiz.id')
                                            ->where('quizid', $qz->quizid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $quizavg[$ky][$key] = number_format((float)$quizarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $quizmax[$ky][$key] = $quizarray->max('final_mark');
                    
                    $quizmin[$ky][$key] = $quizarray->min('final_mark');

                }


                //TEST

                $tests = DB::table('tblclasstest')
                        ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                        ->where([
                            ['tblclasstest.classid',$id],
                            ['tblclasstest.sessionid', Session::get('SessionID')],
                            ['tblclasstest_group.groupname', $grp->group_name],
                            ['tblclasstest.status', '!=', 3]
                        ]);

                $test[] = $tests->get();

                $testid = $tests->pluck('tblclasstest.id');

                $totaltest = $tests->sum('tblclasstest.total_mark');

                foreach($test[$ky] as $key => $qz)
                {

                    $testarray = DB::table('tblclassstudenttest')
                                            ->join('tblclasstest', 'tblclassstudenttest.testid', 'tblclasstest.id')
                                            ->where('testid', $qz->testid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $testavg[$ky][$key] = number_format((float)$testarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $testmax[$ky][$key] = $testarray->max('final_mark');
                    
                    $testmin[$ky][$key] = $testarray->min('final_mark');

                }

                //ASSIGNMENT

                $assigns = DB::table('tblclassassign')
                        ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                        ->where([
                            ['tblclassassign.classid',$id],
                            ['tblclassassign.sessionid', Session::get('SessionID')],
                            ['tblclassassign_group.groupname', $grp->group_name],
                            ['tblclassassign.status', '!=', 3]
                        ]);

                $assign[] = $assigns->get();

                $assignid = $assigns->pluck('tblclassassign.id');

                $totalassign = $assigns->sum('tblclassassign.total_mark');

                foreach($assign[$ky] as $key => $qz)
                {

                    $assignarray = DB::table('tblclassstudentassign')
                                            ->join('tblclassassign', 'tblclassstudentassign.assignid', 'tblclassassign.id')
                                            ->where('assignid', $qz->assignid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $assignavg[$ky][$key] = number_format((float)$assignarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $assignmax[$ky][$key] = $assignarray->max('final_mark');
                    
                    $assignmin[$ky][$key] = $assignarray->min('final_mark');

                }

                //EXTRA

                $extras = DB::table('tblclassextra')
                        ->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                        ->where([
                            ['tblclassextra.classid',$id],
                            ['tblclassextra.sessionid', Session::get('SessionID')],
                            ['tblclassextra_group.groupname', $grp->group_name],
                            ['tblclassextra.status', '!=', 3]
                        ]);

                $extra[] = $extras->get();

                $extraid = $extras->pluck('tblclassextra.id');

                $totalextra = $extras->sum('tblclassextra.total_mark');

                foreach($extra[$ky] as $key => $qz)
                {

                    $extraarray = DB::table('tblclassstudentextra')
                                            ->join('tblclassextra', 'tblclassstudentextra.extraid', 'tblclassextra.id')
                                            ->where('extraid', $qz->extraid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $extraavg[$ky][$key] = number_format((float)$extraarray->sum('tblclassstudentextra.total_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $extramax[$ky][$key] = $extraarray->max('tblclassstudentextra.total_mark');
                    
                    $extramin[$ky][$key] = $extraarray->min('tblclassstudentextra.total_mark');

                }

                //OTHER

                $others = DB::table('tblclassother')
                        ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                        ->where([
                            ['tblclassother.classid',$id],
                            ['tblclassother.sessionid', Session::get('SessionID')],
                            ['tblclassother_group.groupname', $grp->group_name],
                            ['tblclassother.status', '!=', 3]
                        ]);

                $other[] = $others->get();

                $otherid = $others->pluck('tblclassother.id');

                $totalother = $others->sum('tblclassother.total_mark');

                foreach($other[$ky] as $key => $qz)
                {

                    $otherarray = DB::table('tblclassstudentother')
                                            ->join('tblclassother', 'tblclassstudentother.otherid', 'tblclassother.id')
                                            ->where('otherid', $qz->otherid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $otheravg[$ky][$key] = number_format((float)$otherarray->sum('tblclassstudentother.total_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $othermax[$ky][$key] = $otherarray->max('tblclassstudentother.total_mark');
                    
                    $othermin[$ky][$key] = $otherarray->min('tblclassstudentother.total_mark');

                }

                //MIDTERM

                $midterms = DB::table('tblclassmidterm')
                        ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                        ->where([
                            ['tblclassmidterm.classid',$id],
                            ['tblclassmidterm.sessionid', Session::get('SessionID')],
                            ['tblclassmidterm_group.groupname', $grp->group_name],
                            ['tblclassmidterm.status', '!=', 3]
                        ]);

                $midterm[] = $midterms->get();

                $midtermid = $midterms->pluck('tblclassmidterm.id');

                $totalmidterm = $midterms->sum('tblclassmidterm.total_mark');

                foreach($midterm[$ky] as $key => $qz)
                {

                    $midtermarray = DB::table('tblclassstudentmidterm')
                                            ->join('tblclassmidterm', 'tblclassstudentmidterm.midtermid', 'tblclassmidterm.id')
                                            ->where('midtermid', $qz->midtermid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $midtermavg[$ky][$key] = number_format((float)$midtermarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $midtermmax[$ky][$key] = $midtermarray->max('final_mark');
                    
                    $midtermmin[$ky][$key] = $midtermarray->min('final_mark');

                }

                //FINAL

                $finals = DB::table('tblclassfinal')
                        ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                        ->where([
                            ['tblclassfinal.classid',$id],
                            ['tblclassfinal.sessionid', Session::get('SessionID')],
                            ['tblclassfinal_group.groupname', $grp->group_name],
                            ['tblclassfinal.status', '!=', 3]
                        ]);

                $final[] = $finals->get();

                $finalid = $finals->pluck('tblclassfinal.id');

                $totalfinal = $finals->sum('tblclassfinal.total_mark');

                foreach($final[$ky] as $key => $qz)
                {

                    $finalarray = DB::table('tblclassstudentfinal')
                                            ->join('tblclassfinal', 'tblclassstudentfinal.finalid', 'tblclassfinal.id')
                                            ->where('finalid', $qz->finalid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $finalavg[$ky][$key] = number_format((float)$finalarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $finalmax[$ky][$key] = $finalarray->max('final_mark');
                    
                    $finalmin[$ky][$key] = $finalarray->min('final_mark');

                }

                //////////////////////////////////////////////////////////////////////////////////////////
            
                foreach($students[$ky] as $keys => $std)
                {
    
                    // QUIZ

                    foreach($quiz[$ky] as $key =>$qz)
                    {
                    
                    $quizanswer[$ky][$keys][$key] = DB::table('tblclassstudentquiz')->where('userid', $std->ic)->where('quizid', $qz->quizid)->first();

                    }

                    $sumquiz[$ky][$keys] = DB::table('tblclassstudentquiz')->where('userid', $std->ic)->whereIn('quizid', $quizid)->sum('final_mark');

                    $percentquiz = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id',$id],
                                ['assessment', 'quiz']
                                ])->first();

                    if($quizs = DB::table('tblclassquiz')
                    ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                    ->where([
                        ['tblclassquiz.classid',$id],
                        ['tblclassquiz.sessionid', Session::get('SessionID')],
                        ['tblclassquiz_group.groupname', $grp->group_name],
                        ['tblclassquiz.status', '!=', 3]
                    ])->exists()){
                        if($percentquiz != null)
                        {
                            if(DB::table('tblclassquiz')
                            ->where([
                                ['classid',$id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalquiz);
                                $overallquiz[$ky][$keys] = number_format((float)$sumquiz[$ky][$keys] / $totalquiz * $percentquiz->mark_percentage, 2, '.', '');

                                $quizcollection = collect($overallquiz[$ky]);
                            }else{
                                $overallquiz[$ky][$keys] = 0;

                                $quizcollection = collect($overallquiz[$ky]);
                            }
            
                        }else{
                            $overallquiz[$ky][$keys] = 0;

                            $quizcollection = collect($overallquiz[$ky]);
                        }
                    }else{
                        $overallquiz[$ky][$keys] = 0;

                        $quizcollection = collect($overallquiz[$ky]);
                    }


                    // TEST
                    
                    foreach($test[$ky] as $key =>$qz)
                    {
                    
                    $testanswer[$ky][$keys][$key] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->where('testid', $qz->testid)->first();

                    }

                    $sumtest[$ky][$keys] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->whereIn('testid', $testid)->sum('final_mark');

                    $percenttest = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id',$id],
                                ['assessment', 'test']
                                ])->first();

                    if($tests = DB::table('tblclasstest')
                    ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                    ->where([
                        ['tblclasstest.classid',$id],
                        ['tblclasstest.sessionid', Session::get('SessionID')],
                        ['tblclasstest_group.groupname', $grp->group_name],
                        ['tblclasstest.status', '!=', 3]
                    ])->exists()){
                        if($percenttest != null)
                        {
                            if(DB::table('tblclasstest')
                            ->where([
                                ['classid',$id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totaltest);
                                $overalltest[$ky][$keys] = number_format((float)$sumtest[$ky][$keys] / $totaltest * $percenttest->mark_percentage, 2, '.', '');

                                $testcollection = collect($overalltest[$ky]);
                            }else{
                                $overalltest[$ky][$keys] = 0;

                                $testcollection = collect($overalltest[$ky]);
                            }
            
                        }else{
                            $overalltest[$ky][$keys] = 0;

                            $testcollection = collect($overalltest[$ky]);
                        }
                    }else{
                        $overalltest[$ky][$keys] = 0;

                        $testcollection = collect($overalltest[$ky]);
                    }


                    // ASSIGNMENT
                    
                    foreach($assign[$ky] as $key =>$qz)
                    {
                    
                    $assignanswer[$ky][$keys][$key] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->where('assignid', $qz->assignid)->first();

                    }

                    $sumassign[$ky][$keys] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->whereIn('assignid', $assignid)->sum('final_mark');

                    $percentassign = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id',$id],
                                ['assessment', 'assignment']
                                ])->first();

                    if($assigns = DB::table('tblclassassign')
                    ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                    ->where([
                        ['tblclassassign.classid',$id],
                        ['tblclassassign.sessionid', Session::get('SessionID')],
                        ['tblclassassign_group.groupname', $grp->group_name],
                        ['tblclassassign.status', '!=', 3]
                    ])->exists()){
                        if($percentassign != null)
                        {
                            if(DB::table('tblclassassign')
                            ->where([
                                ['classid',$id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalassign);
                                $overallassign[$ky][$keys] = number_format((float)$sumassign[$ky][$keys] / $totalassign * $percentassign->mark_percentage, 2, '.', '');

                                $assigncollection = collect($overallassign[$ky]);
                            }else{
                               $overallassign[$ky][$keys] = 0;

                               $assigncollection = collect($overallassign[$ky]);
                            }
            
                        }else{
                            $overallassign[$ky][$keys] = 0;

                            $assigncollection = collect($overallassign[$ky]);
                        }
                    }else{
                        $overallassign[$ky][$keys] = 0;

                        $assigncollection = collect($overallassign[$ky]);
                    }

                    // EXTRA
                    
                    foreach($extra[$ky] as $key =>$qz)
                    {
                    
                    $extraanswer[$ky][$keys][$key] = DB::table('tblclassstudentextra')->where('userid', $std->ic)->where('extraid', $qz->extraid)->first();

                    }

                    $sumextra[$ky][$keys] = DB::table('tblclassstudentextra')->where('userid', $std->ic)->whereIn('extraid', $extraid)->sum('total_mark');

                    $percentextra = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id',$id],
                                ['assessment', 'extra']
                                ])->first();

                    if($extras = DB::table('tblclassextra')
                    ->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                    ->where([
                        ['tblclassextra.classid',$id],
                        ['tblclassextra.sessionid', Session::get('SessionID')],
                        ['tblclassextra_group.groupname', $grp->group_name],
                        ['tblclassextra.status', '!=', 3]
                    ])->exists()){
                        if($percentextra != null)
                        {
                            if(DB::table('tblclassextra')
                            ->where([
                                ['classid',$id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalextra);
                                $overallextra[$ky][$keys] = number_format((float)$sumextra[$ky][$keys] / $totalextra * $percentextra->mark_percentage, 2, '.', '');

                                $extracollection = collect($overallextra[$ky]);
                            }else{
                                $overallextra[$ky][$keys] = 0;

                                $extracollection = collect($overallextra[$ky]);
                            }
            
                        }else{
                            $overallextra[$ky][$keys] = 0;

                            $extracollection = collect($overallextra[$ky]);
                        }
                    }else{
                        $overallextra[$ky][$keys] = 0;

                        $extracollection = collect($overallextra[$ky]);
                    }

                    // OTHER
                    
                    foreach($other[$ky] as $key =>$qz)
                    {
                    
                    $otheranswer[$ky][$keys][$key] = DB::table('tblclassstudentother')->where('userid', $std->ic)->where('otherid', $qz->otherid)->first();

                    }

                    $sumother[$ky][$keys] = DB::table('tblclassstudentother')->where('userid', $std->ic)->whereIn('otherid', $otherid)->sum('total_mark');

                    $percentother = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id',$id],
                                ['assessment', 'other']
                                ])->first();

                    if($others = DB::table('tblclassother')
                    ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                    ->where([
                        ['tblclassother.classid',$id],
                        ['tblclassother.sessionid', Session::get('SessionID')],
                        ['tblclassother_group.groupname', $grp->group_name],
                        ['tblclassother.status', '!=', 3]
                    ])->exists()){
                        if($percentother != null)
                        {
                            if(DB::table('tblclassother')
                            ->where([
                                ['classid',$id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalother);
                                $overallother[$ky][$keys] = number_format((float)$sumother[$ky][$keys] / $totalother * $percentother->mark_percentage, 2, '.', '');

                                $othercollection = collect($overallother[$ky]);
                            }else{
                                $overallother[$ky][$keys] = 0;

                                $othercollection = collect($overallother[$ky]);
                            }
            
                        }else{
                            $overallother[$ky][$keys] = 0;

                            $othercollection = collect($overallother[$ky]);
                        }
                    }else{
                        $overallother[$ky][$keys] = 0;

                        $othercollection = collect($overallother[$ky]);
                    }

                    // MIDTERM
                    
                    foreach($midterm[$ky] as $key =>$qz)
                    {
                    
                    $midtermanswer[$ky][$keys][$key] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->where('midtermid', $qz->midtermid)->first();

                    }

                    $summidterm[$ky][$keys] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->whereIn('midtermid', $midtermid)->sum('final_mark');

                    $percentmidterm = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id',$id],
                                ['assessment', 'midterm']
                                ])->first();

                    if($midterms = DB::table('tblclassmidterm')
                    ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                    ->where([
                        ['tblclassmidterm.classid',$id],
                        ['tblclassmidterm.sessionid', Session::get('SessionID')],
                        ['tblclassmidterm_group.groupname', $grp->group_name],
                        ['tblclassmidterm.status', '!=', 3]
                    ])->exists()){
                        if($percentmidterm != null)
                        {
                            if(DB::table('tblclassmidterm')
                            ->where([
                                ['classid',$id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalmidterm);
                                $overallmidterm[$ky][$keys] = number_format((float)$summidterm[$ky][$keys] / $totalmidterm * $percentmidterm->mark_percentage, 2, '.', '');

                                $midtermcollection = collect($overallmidterm[$ky]);
                            }else{
                                $overallmidterm[$ky][$keys] = 0;

                                $midtermcollection = collect($overallmidterm[$ky]);
                            }
            
                        }else{
                            $overallmidterm[$ky][$keys] = 0;

                            $midtermcollection = collect($overallmidterm[$ky]);
                        }
                    }else{
                        $overallmidterm[$ky][$keys] = 0;

                        $midtermcollection = collect($overallmidterm[$ky]);
                    }

                    // FINAL
                    
                    foreach($final[$ky] as $key =>$qz)
                    {
                    
                    $finalanswer[$ky][$keys][$key] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->where('finalid', $qz->finalid)->first();

                    }

                    $sumfinal[$ky][$keys] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->whereIn('finalid', $finalid)->sum('final_mark');

                    $percentfinal = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id',$id],
                                ['assessment', 'final']
                                ])->first();

                    if($finals = DB::table('tblclassfinal')
                    ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                    ->where([
                        ['tblclassfinal.classid',$id],
                        ['tblclassfinal.sessionid', Session::get('SessionID')],
                        ['tblclassfinal_group.groupname', $grp->group_name],
                        ['tblclassfinal.status', '!=', 3]
                    ])->exists()){
                        if($percentfinal != null)
                        {
                            if(DB::table('tblclassfinal')
                            ->where([
                                ['classid',$id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalfinal);
                                $overallfinal[$ky][$keys] = number_format((float)$sumfinal[$ky][$keys] / $totalfinal * $percentfinal->mark_percentage, 2, '.', '');

                                $finalcollection = collect($overallfinal[$ky]);
                            }else{
                                $overallfinal[$ky][$keys] = 0;

                                $finalcollection = collect($overallfinal[$ky]);
                            }
            
                        }else{
                            $overallfinal[$ky][$keys] = 0;

                            $finalcollection = collect($overallfinal[$ky]);
                        }
                    }else{
                        $overallfinal[$ky][$keys] = 0;

                        $finalcollection = collect($overallfinal[$ky]);
                    }

                    $overallall[$ky][$keys] = $overallquiz[$ky][$keys] + $overalltest[$ky][$keys] + $overallassign[$ky][$keys] + $overallextra[$ky][$keys] + $overallother[$ky][$keys] + $overallmidterm[$ky][$keys] + $overallfinal[$ky][$keys];

                    $collectionall = collect($overallall[$ky]);

                    //check grade
                    $grade = DB::table('tblsubject_grade')->get();

                    foreach($grade as $grd)
                    {

                        if($overallall[$ky][$keys] >= $grd->mark_start && $overallall[$ky][$keys] <= $grd->mark_end)
                        {
                            $valGrade[$ky][$keys] = $grd->code;

                            break;
                        }else{

                            $valGrade[$ky][$keys] = null;
                        }

                    }

                    DB::table('student_subjek')
                    ->where([
                        ['student_ic', $std->ic],
                        ['sessionid', $std->session_id],
                        ['courseid', $std->course_id]
                        ])->update([
                            'grade' => $valGrade[$ky][$keys]
                        ]);
            
                }

            $quizavgoverall = number_format((float)$quizcollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $testavgoverall = number_format((float)$testcollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $assignavgoverall = number_format((float)$assigncollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $extraavgoverall = number_format((float)$extracollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $otheravgoverall = number_format((float)$othercollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $midtermavgoverall = number_format((float)$midtermcollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $finalavgoverall = number_format((float)$finalcollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $avgoverall = number_format((float)$collectionall->sum() / count($collection->pluck('ic')), 2, '.', '');
        }

        

        //dd($valGrade);


        return view('lecturer.courseassessment.studentreport', compact('groups', 'students', 'id',
                                                                       'quiz', 'quizanswer', 'overallquiz', 'quizavg', 'quizmax', 'quizmin', 'quizcollection', 'quizavgoverall',
                                                                       'test', 'testanswer', 'overalltest', 'testavg', 'testmax', 'testmin', 'testcollection','testavgoverall',
                                                                       'assign', 'assignanswer', 'overallassign', 'assignavg', 'assignmax', 'assignmin', 'assigncollection','assignavgoverall',
                                                                       'extra', 'extraanswer', 'overallextra', 'extraavg', 'extramax', 'extramin', 'extracollection','extraavgoverall',
                                                                       'other', 'otheranswer', 'overallother', 'otheravg', 'othermax', 'othermin', 'othercollection','otheravgoverall',
                                                                       'midterm', 'midtermanswer', 'overallmidterm', 'midtermavg', 'midtermmax', 'midtermmin', 'midtermcollection','midtermavgoverall',
                                                                       'final', 'finalanswer', 'overallfinal', 'finalavg', 'finalmax', 'finalmin', 'finalcollection','finalavgoverall',
                                                                       'overallall', 'avgoverall', 'valGrade'
                                                                    ));

    }
}
