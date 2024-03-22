<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\subject;
use App\Models\student;
use App\Models\UserStudent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class StudentController extends Controller
{
    public function index()
    {
        Session::put('User', Auth::guard('student')->user());

        $student = auth()->guard('student')->user();

        if (!$student) {
            // handle the error, e.g., redirect back with an error message
            return redirect()->back()->withErrors(['message' => 'Student is not logged in']);
        }

        $user = Session::put('StudInfo', $student);

        $lecturer = [];

        //dd($student);

        $subject = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                   ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                   ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
                   ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                   ->where([
                        ['sessions.Status', 'ACTIVE'],
                        ['tblprogramme.progstatusid', 1],
                        ['student_subjek.student_ic', $student->ic],
                        ['subjek_structure.program_id', $student->program]
                        ])
                   ->select('subjek.id','subjek.course_name','subjek.course_code','student_subjek.group_id','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
                   ->groupBy('student_subjek.courseid')
                   ->get();

        foreach($subject as $key => $sub)
        {

            $lecturer[$key] = DB::table('user_subjek')
                    ->join('users', 'user_subjek.user_ic', 'users.ic')
                    ->where([
                        ['user_subjek.course_id', $sub->courseid],
                        ['user_subjek.session_id', $sub->SessionID],
                        ['user_subjek.id', $sub->group_id]
                        ])
                    ->select('users.name')
                    ->first();

        }

        //dd($subject);

        // $subject = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
        // ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
        // ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
        // ->join('user_subjek', function($join){
        //     $join->on('student_subjek.courseid', 'user_subjek.course_id');
        //     $join->on('student_subjek.sessionid', 'user_subjek.session_id');
        // })
        // ->join('users', 'user_subjek.user_ic', 'users.ic')
        // ->where([
        //     ['sessions.Status', 'ACTIVE'],
        //     ['tblprogramme.progstatusid', 1],
        //     ['student_subjek.student_ic', $student->ic]
        //     ])
        // ->select('subjek.course_name','subjek.course_code','student_subjek.courseid','sessions.SessionName','sessions.SessionID', 'users.name')
        // ->groupBy('student_subjek.courseid')
        // ->get();

        //dd($subject);

        $sessions = DB::table('sessions')->where('Status', 'ACTIVE')->get();

        return view('student', compact(['subject','sessions', 'lecturer']));
    }

    public function setting()
    {
        $student = DB::table('students')->where('ic', Auth::guard('student')->user()->ic)->first();

        return view('settingStudent', compact('student'));
    }

    public function updateSetting(Request $request)
    {
        $data = $request->validate([
            'email' => ['email', 'required'],
            'pass' => ['nullable','max:10','regex:/^\S*$/u'],
            'conpass' => ['max:10','same:pass','regex:/^\S*$/u']
        ],[
            'conpass.same' => 'The Confirm Password and Password must match!',
            'pass.regex' => 'The Password cannot have spaces!'
        ]);

        //dd($data['pass']);

        if($data['pass'] != null)
        {
            Auth::guard('student')->user()->update([
                'email' => $data['email'],
                'password' =>  Hash::make($data['pass'])
            ]);
        }else{
            Auth::guard('student')->user()->update([
                'email' => $data['email']
            ]);
        }

        return redirect()->back()->with('alert', 'You have successfully updated your setting!');
    }

    public function getCourseList(Request $request)
    {
        $student = Session::get('StudInfo');

        $lecturer = [];

        if(isset($request->search) && isset($request->session))
        {

            $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
            ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->where([
                    ['sessions.Status', 'ACTIVE'],
                    ['tblprogramme.progstatusid', 1],
                    ['student_subjek.student_ic', $student->ic],
                    ['subjek_structure.program_id', $student->program]
                    ])
            ->where('student_subjek.sessionid','LIKE','%'.$request->session.'%')
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            ->select('subjek.id','subjek.course_name','subjek.course_code','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
            ->groupBy('student_subjek.courseid')
            ->get();

            foreach($data as $key => $sub)
            {

                $lecturer[$key] = DB::table('user_subjek')
                        ->join('users', 'user_subjek.user_ic', 'users.ic')
                        ->where([
                            ['user_subjek.course_id', $sub->courseid],
                            ['user_subjek.session_id', $sub->SessionID]
                            ])
                        ->select('users.name')
                        ->first();

            }

        }elseif(isset($request->search))
        {

            $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
            ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->where([
                    ['sessions.Status', 'ACTIVE'],
                    ['tblprogramme.progstatusid', 1],
                    ['student_subjek.student_ic', $student->ic],
                    ['subjek_structure.program_id', $student->program]
                    ])
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            ->select('subjek.id','subjek.course_name','subjek.course_code','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
            ->groupBy('student_subjek.courseid')
            ->get();

            foreach($data as $key => $sub)
            {

                $lecturer[$key] = DB::table('user_subjek')
                        ->join('users', 'user_subjek.user_ic', 'users.ic')
                        ->where([
                            ['user_subjek.course_id', $sub->courseid],
                            ['user_subjek.session_id', $sub->SessionID]
                            ])
                        ->select('users.name')
                        ->first();

            }
            

        }elseif(isset($request->session))
        {

            $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
            ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->where([
                    ['sessions.Status', 'ACTIVE'],
                    ['tblprogramme.progstatusid', 1],
                    ['student_subjek.student_ic', $student->ic],
                    ['subjek_structure.program_id', $student->program]
                    ])
            ->where('student_subjek.sessionid','LIKE','%'.$request->session.'%')
            ->select('subjek.id','subjek.course_name','subjek.course_code','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
            ->groupBy('student_subjek.courseid')
            ->get();

            foreach($data as $key => $sub)
            {

                $lecturer[$key] = DB::table('user_subjek')
                        ->join('users', 'user_subjek.user_ic', 'users.ic')
                        ->where([
                            ['user_subjek.course_id', $sub->courseid],
                            ['user_subjek.session_id', $sub->SessionID]
                            ])
                        ->select('users.name')
                        ->first();

            }

        }else{

            $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
            ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->where([
                    ['sessions.Status', 'ACTIVE'],
                    ['tblprogramme.progstatusid', 1],
                    ['student_subjek.student_ic', $student->ic],
                    ['subjek_structure.program_id', $student->program]
                    ])
            ->select('subjek.id','subjek.course_name','subjek.course_code','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
            ->groupBy('student_subjek.courseid')
            ->get();

            foreach($data as $key => $sub)
            {

                $lecturer[$key] = DB::table('user_subjek')
                        ->join('users', 'user_subjek.user_ic', 'users.ic')
                        ->where([
                            ['user_subjek.course_id', $sub->courseid],
                            ['user_subjek.session_id', $sub->SessionID]
                            ])
                        ->select('users.name')
                        ->first();

            }

        }

        return view('studentgetcourse', compact('data', 'lecturer'));


    }

    public function courseSummary()
    {
        Session::put('CourseID', request()->id);

        if(Session::get('SessionID') == null)
        {
        Session::put('SessionID', request()->session);
        }

        $course = DB::table('subjek')
                  ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                  ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
                  ->where('subjek.id', request()->id)->first();

        return view('student.coursesummary.coursesummary', compact('course'));
    }

    public function courseContent()
    {


        $lecturer = DB::table('user_subjek')->join('student_subjek', 'user_subjek.id','student_subjek.group_id')
                    ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                    ->where([
                        ['student_subjek.student_ic', Session::get('StudInfo')->ic],
                        ['subjek.id', request()->id],
                        ['student_subjek.sessionid', Session::get('SessionID')],
                    ])->select('user_subjek.*')->first();
        
        //dd($lecturer);

        if($lecturer != null)
        {
        
        $subid = DB::table('subjek')->where('id', request()->id)->pluck('sub_id');

        $folder = DB::table('lecturer_dir')
                  ->join('subjek', 'lecturer_dir.CourseID','subjek.id')
                  ->where('subjek.sub_id', $subid)
                  ->where('Addby', $lecturer->user_ic)
                  ->get();

        }else{
            $folder = null;
        }

        //dd(Session::get('SessionID'));
        
        return view('student.coursecontent.index', compact('folder'))->with('course_id', request()->id);
    }

    public function courseDirectory(Request $request)
    {
        $directory = DB::table('lecturer_dir')->where('DrID', $request->dir)->first();

        if(!empty($directory->Password))
        {
            return view('student.coursecontent.passwordfolder')->with('dir', $request->dir)->with('course_id', $request->id);

        }else{

            $mat_directory = DB::table('material_dir')->where('LecturerDirID', $directory->DrID)->get();

            return view('student.coursecontent.materialdirectory', compact('mat_directory'))->with('dirid', $directory->DrID);
        }
    }

    public function passwordDirectory(Request $request)
    {
        $password = DB::table('lecturer_dir')->where('DrID', request()->dir)->first();

        if(Hash::check($request->pass, $password->Password))
        {
            //$dir = 'classmaterial/'. $password->DrName;

            $mat_directory = DB::table('material_dir')->where('LecturerDirID', $password->DrID)->get();

            //$classmaterial  = Storage::disk('public')->allFiles( $dir );

            return view('student.coursecontent.materialdirectory', compact('mat_directory'))->with('dirid', $password->DrID);

        }else{

            return redirect()->back() ->with('alert', 'Wrong Password! Please try again.');

        }
    }

    public function prevcourseDirectory(Request $request)
    {
        $directory = DB::table('lecturer_dir')->where('DrID', $request->dir)->first();

        $mat_directory = DB::table('material_dir')->where('LecturerDirID', $directory->DrID)->get();

        return view('student.coursecontent.materialdirectory', compact('mat_directory'))->with('dirid', $directory->DrID);

    }

    public function courseSubDirectory(Request $request)
    {

        $directory = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*')
                ->where('material_dir.DrID', $request->dir)->first();

        if(!empty($directory->Password))
        {
            return view('student.coursecontent.passwordsubfolder')->with('dir', $request->dir);

        }else{

            $mat_directory = DB::table('materialsub_dir')->where('MaterialDirID', $directory->DrID)->get();

            $url = DB::table('materialsub_url')->where('MaterialDirID', $directory->DrID)->get();

            $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;

            //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
            $classmaterial  = Storage::disk('linode')->files($dir);

            return view('student.coursecontent.materialsubdirectory', compact('mat_directory', 'url', 'classmaterial'))->with('dirid', $directory->DrID)->with('prev', $directory->LecturerDirID);
        }
    }

    public function passwordSubDirectory(Request $request)
    {
        $password = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*')
                ->where('material_dir.DrID', $request->dir)->first();

        if(Hash::check($request->pass, $password->Password))
        {
            //$dir = 'classmaterial/'. $password->DrName;

            $mat_directory = DB::table('materialsub_dir')->where('MaterialDirID', $password->DrID)->get();

            $url = DB::table('materialsub_url')->where('MaterialDirID', $password->DrID)->get();

            $dir = "classmaterial/" . Session::get('CourseID') . "/" . $password->A . "/" . $password->B;

            //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
            $classmaterial  = Storage::disk('linode')->files($dir);

            return view('student.coursecontent.materialsubdirectory', compact('mat_directory', 'url', 'classmaterial'))->with('dirid', $password->DrID)->with('prev', $password->LecturerDirID);
        }else{

            return redirect()->back() ->with('alert', 'Wrong Password! Please try again.');

        }
    }

    public function prevcourseSubDirectory(Request $request)
    {
        $directory = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*')
                ->where('material_dir.DrID', $request->dir)->first();

        $mat_directory = DB::table('materialsub_dir')->where('MaterialDirID', $directory->DrID)->get();

        $url = DB::table('materialsub_url')->where('MaterialDirID', $directory->DrID)->get();

        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;

        //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
        $classmaterial  = Storage::disk('linode')->files($dir);

        return view('student.coursecontent.materialsubdirectory', compact('mat_directory', 'url', 'classmaterial'))->with('dirid', $directory->DrID)->with('prev', $directory->LecturerDirID);

    }

    public function DirectoryContent(Request $request)
    {
        $directory = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'materialsub_dir.DrID')
                ->where('materialsub_dir.DrID', $request->dir)->first();

        //dd($directory);

        if(!empty($directory->Password))
        {
            return view('student.coursecontent.passwordcontent')->with('dir', $request->dir);

        }else{
            
            $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" . $directory->C;

            $classmaterial  = Storage::disk('linode')->allFiles( $dir );

            $url = DB::table('materialsub_url')->where('MaterialSubDirID', $directory->DrID)->get();

            return view('student.coursecontent.coursematerial', compact('classmaterial', 'url'))->with('dirid', $directory->DrID)->with('prev', $directory->MaterialDirID);
        }
    }

    public function passwordContent(Request $request)
    {
        
        $password = DB::table('lecturer_dir')
        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
        ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'materialsub_dir.DrID')
        ->where('materialsub_dir.DrID', $request->dir)->first();


        if(Hash::check($request->pass, $password->Password))
        {
            //$dir = 'classmaterial/'. $password->DrName;

            $dir = "classmaterial/" . Session::get('CourseID') . "/" . $password->A . "/" . $password->B . "/" . $password->C;

            $classmaterial  = Storage::disk('linode')->allFiles( $dir );

            $url = DB::table('materialsub_url')->where('MaterialSubDirID', $password->DrID)->get();
    
            return view('student.coursecontent.coursematerial', compact('classmaterial', 'url'))->with('dirid', $password->DrID)->with('prev', $password->MaterialDirID);
        }else{

            return redirect()->back() ->with('alert', 'Wrong Password! Please try again.');

        }
    }


    public function classSchedule()
    {

        return view('student.class.schedule');

    }

    public function scheduleGetGroup()
    {

        $student = Session::get('StudInfo');

        $courseid = Session::get('CourseID');

        $group = DB::table('subjek')
        ->join('student_subjek', 'subjek.sub_id', 'student_subjek.courseid')
        ->join('user_subjek', 'student_subjek.group_id', 'user_subjek.id')
        ->join('users', 'user_subjek.user_ic', 'users.ic')
        ->select('student_subjek.semesterid','student_subjek.group_name', 'user_subjek.*', 'users.name', 'users.email', 'users.faculty')
        ->where('subjek.id', $courseid)
        ->where('student_subjek.student_ic', $student->ic)
        ->get();

        $content = "";

        $content .= "<option value='0' disabled selected>-</option>";
        foreach($group as $grp){

            $content .= '<option data-style="btn-inverse"  
            data-content=\'<div class="row" >
                <div class="col-md-2">
                <div class="d-flex justify-content-center">
                    <img src="" 
                        height="auto" width="70%" class="bg-light ms-0 me-2 rounded-circle">
                        </div>
                </div>
                <div class="col-md-10 align-self-center lh-lg">
                    <span><strong>'. $grp->group_name .'</strong> ( Semester '. $grp->semesterid.' )</span><br>
                    <span><strong>'. $grp->name .'</strong></span><br>
                    <span>'. $grp->email .' | <strong class="text-fade"">'.$grp->faculty .'</strong></span><br>
                    <span class="text-fade"></span>
                </div>
            </div>\' value='. $grp->id .'></option>';
        }
        
        return $content;

    }

    public function getSchedule(Request $request)
    {
        $schedule = DB::table('tblclassschedule')->where('groupid', $request->group)->orderBy('id')->get();

        if(count($schedule) > 0)
        {
            return view('student.class.getshcedule', compact('schedule'));

        }else{

            return view('student.class.getshcedule')->with('error', 'Group\'s has not been set by Lecturer yet, please inform the designated Lecturer. ');
        }
    }

    public function OnlineClassList()
    {
        $totalstd = [];

        $chapters = [];

        $student = Session::get('StudInfo');

        $courseid = Session::get('CourseID');

        $class = DB::table('onlineclass')
                 ->join('student_subjek', function($join){
                    $join->on('onlineclass.groupid', 'student_subjek.group_id');
                    $join->on('onlineclass.groupname', 'student_subjek.group_name');
                })
                 ->join('user_subjek', 'onlineclass.groupid', 'user_subjek.id')
                 ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                 ->where('student_subjek.student_ic', $student->ic)
                 ->where('subjek.id', $courseid)
                 ->select('onlineclass.*')
                 ->paginate(5);

        //dd($class);

        foreach ($class as $clss)
        {
            $totalstd[] = student::where('group_id', $clss->groupid)->count();

            $chapters[] = DB::table('classchapter')
                        ->join('materialsub_dir', 'classchapter.chapterid', 'materialsub_dir.DrID')
                        ->where('classid', $clss->id)->get();
        }

        //dd($totalstd);
           
        return view('student.class.listonlineclass', compact([
            'class',
            'totalstd',
            'chapters'
        ]));

    }

    public function OnlineClassListView()
    {

        $class = DB::table('onlineclass')
        ->join('user_subjek', 'onlineclass.groupid', 'user_subjek.id')
        ->join('users', 'user_subjek.user_ic', 'users.ic')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->where('onlineclass.id', request()->id)
        ->select('onlineclass.*', 'users.name')
        ->first();

        //dd($class);

     

        $chapters = DB::table('classchapter')
        ->join('materialsub_dir', 'classchapter.chapterid', 'materialsub_dir.DrID')
        ->where('classid', $class->id)->get();


        //dd($class->id);

        return view('student.class.onlineclassview', compact(['class', 'chapters']));

    }

    public function AnnouncementList()
    {
        $totalstd = [];

        $chapters = [];

        $allgroup = [];

        $student = Session::get('StudInfo');

        $courseid = Session::get('CourseID');

        $class = DB::table('announcement')
                 ->join('student_subjek', function($join){
                    $join->on('announcement.groupid', 'student_subjek.group_id');
                })
                 ->join('user_subjek', 'announcement.groupid', 'user_subjek.id')
                 ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                 ->where('student_subjek.student_ic', $student->ic)
                 ->where('subjek.id', $courseid)
                 ->select('announcement.*')
                 ->paginate(5);

        //dd($class);

        foreach ($class as $clss)
        {
            $group = DB::table('announcement_groupname')
                    ->join('student_subjek', function($join){
                        $join->on('announcement_groupname.groupname', 'student_subjek.group_name');
                    })
                    ->where('announcement_groupname.announcementid', $clss->id)
                    ->where('student_subjek.group_id', $clss->groupid);

            $allgroup[] = $group->groupBy('student_subjek.group_name')->get();
            
            $totalstd[] = $group->count();

            $chapters[] = DB::table('classchapter')
                        ->join('materialsub_dir', 'classchapter.chapterid', 'materialsub_dir.DrID')
                        ->where('classid', $clss->id)->get();
        }

        //dd($totalstd);
           
        return view('student.class.listannouncement', compact([
            'class',
            'allgroup',
            'totalstd',
            'chapters'
        ]));

    }

    public function studentreport()
    {
        $students = Session::get('StudInfo');
        
        $quizlist = [];
        $percentagequiz = "";

        $testlist = [];
        $percentagetest = "";

        $midtermlist = [];
        $percentagemidterm = "";

        $finallist = [];
        $percentagefinal = "";

        $assignlist = [];
        $percentageassign = "";

        $otherlist = [];
        $percentageother = "";

        $extralist = [];
        $percentageextra = "";

        //$percentagefinal = "";

        $student = DB::table('students')
                ->join('student_subjek', 'students.ic', 'student_subjek.student_ic')
                ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                ->join('user_subjek', 'student_subjek.group_id', 'user_subjek.id')
                ->where([
                    ['students.ic', $students->ic],
                    ['subjek.id', request()->id],
                    ['student_subjek.sessionid', Session::get('SessionID')]
                    ])
                ->select('students.*', 'student_subjek.group_id', 'student_subjek.group_name')->first();

        //QUIZ

        $percentquiz = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'quiz']
                                ])->first();

        if($percentquiz != null)
        {
            $percentagequiz = $percentquiz->mark_percentage;
        }

        $totalquiz = 0;
        $markquiz = 0;
        
        $quiz = DB::table('tblclassquiz')
                    ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                    ->where([
                        ['tblclassquiz.classid', request()->id],
                        ['tblclassquiz.sessionid', Session::get('SessionID')],
                        ['tblclassquiz_group.groupid', $student->group_id],
                        ['tblclassquiz_group.groupname', $student->group_name],
                        ['status', 2]
                    ])->select('tblclassquiz.*')->get();
        
        foreach($quiz as $key => $qz)
        {
            $quizlist[$key] = DB::table('tblclassstudentquiz')->where([
                                                                ['userid', $student->ic],
                                                                ['quizid', $qz->id],
                                                                ])->first();
        
            // Add the current total_mark to the $totalquiz variable
            $totalquiz += $qz->total_mark;
        
            // If a quizlist record exists, add the current final_mark to the $markquiz variable
            if ($quizlist[$key]) {
                $markquiz += $quizlist[$key]->final_mark;
            }
        }

        if($totalquiz != 0 && $markquiz != 0)
        {
            $total_allquiz = round(( (int)$markquiz / (int)$totalquiz ) * (int)$percentagequiz);
        }else{
            $total_allquiz = 0;
        }

        //TEST

        $percenttest = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'test']
                                ])->first();

        if($percenttest != null)
        {
            $percentagetest = $percenttest->mark_percentage;
        }

        $totaltest = 0;
        $marktest = 0;
        
        $test = DB::table('tblclasstest')
                    ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                    ->where([
                        ['tblclasstest.classid', request()->id],
                        ['tblclasstest.sessionid', Session::get('SessionID')],
                        ['tblclasstest_group.groupid', $student->group_id],
                        ['tblclasstest_group.groupname', $student->group_name],
                        ['status', 2]
                    ])->select('tblclasstest.*')->get();
        
        foreach($test as $key => $qz)
        {
            $testlist[$key] = DB::table('tblclassstudenttest')->where([
                                                                ['userid', $student->ic],
                                                                ['testid', $qz->id],
                                                                ])->first();
        
            // Add the current total_mark to the $totaltest variable
            $totaltest += $qz->total_mark;
        
            // If a testlist record exists, add the current final_mark to the $marktest variable
            if ($testlist[$key]) {
                $marktest += $testlist[$key]->final_mark;
            }
        }

        if($totaltest != 0 && $marktest != 0)
        {
            $total_alltest = round(( (int)$marktest / (int)$totaltest ) * (int)$percentagetest);
        }else{
            $total_alltest = 0;
        }

        //ASSIGNMENT

        $percentassign = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'assignment']
                                ])->first();

        if($percentassign != null)
        {
            $percentageassign = $percentassign->mark_percentage;
        }

        $totalassign = 0;
        $markassign = 0;

        $assign = DB::table('tblclassassign')
                    ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                    ->where([
                        ['tblclassassign.classid', request()->id],
                        ['tblclassassign.sessionid', Session::get('SessionID')],
                        ['tblclassassign_group.groupid', $student->group_id],
                        ['tblclassassign_group.groupname', $student->group_name],
                        ['status', 2]
                    ])->select('tblclassassign.*')->get();
        
        foreach($assign as $key => $qz)
        {
            $assignlist[$key] = DB::table('tblclassstudentassign')->where([
                                                                ['userid', $student->ic],
                                                                ['assignid', $qz->id],
                                                                ])->first();
        
            // Add the current total_mark to the $totalassign variable
            $totalassign += $qz->total_mark;
        
            // If a assignlist record exists, add the current final_mark to the $markassign variable
            if ($assignlist[$key]) {
                $markassign += $assignlist[$key]->final_mark;
            }
        }

        if($totalassign != 0 && $markassign != 0)
        {
            $total_allassign = round(( (int)$markassign / (int)$totalassign ) * (int)$percentageassign);
        }else{
            $total_allassign = 0;
        }

        //MIDTERM

        $percentmidterm = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'midterm']
                                ])->first();

        if($percentmidterm != null)
        {
            $percentagemidterm = $percentmidterm->mark_percentage;
        }

        $totalmidterm = 0;
        $markmidterm = 0;

        $midterm = DB::table('tblclassmidterm')
                    ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                    ->where([
                        ['tblclassmidterm.classid', request()->id],
                        ['tblclassmidterm.sessionid', Session::get('SessionID')],
                        ['tblclassmidterm_group.groupid', $student->group_id],
                        ['tblclassmidterm_group.groupname', $student->group_name],
                        ['status', 2]
                    ])->select('tblclassmidterm.*')->get();
        
        foreach($midterm as $key => $qz)
        {
            $midtermlist[$key] = DB::table('tblclassstudentmidterm')->where([
                                                                ['userid', $student->ic],
                                                                ['midtermid', $qz->id],
                                                                ])->first();
        
            // Add the current total_mark to the $totalmidterm variable
            $totalmidterm += $qz->total_mark;
        
            // If a midtermlist record exists, add the current final_mark to the $markmidterm variable
            if ($midtermlist[$key]) {
                $markmidterm += $midtermlist[$key]->final_mark;
            }
        }

        if($totalmidterm != 0 && $markmidterm != 0)
        {
            $total_allmidterm = round(( (int)$markmidterm / (int)$totalmidterm ) * (int)$percentagemidterm);
        }else{
            $total_allmidterm = 0;
        }

        
        //FINAL

        $percentfinal = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'final']
                                ])->first();

        if($percentfinal != null)
        {
            $percentagefinal = $percentfinal->mark_percentage;
        }

        $totalfinal = 0;
        $markfinal = 0;
        
        $final = DB::table('tblclassfinal')
                    ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                    ->where([
                        ['tblclassfinal.classid', request()->id],
                        ['tblclassfinal.sessionid', Session::get('SessionID')],
                        ['tblclassfinal_group.groupid', $student->group_id],
                        ['tblclassfinal_group.groupname', $student->group_name],
                        ['status', 2]
                    ])->select('tblclassfinal.*')->get();
        
        foreach($final as $key => $qz)
        {
            $finallist[$key] = DB::table('tblclassstudentfinal')->where([
                                                                ['userid', $student->ic],
                                                                ['finalid', $qz->id],
                                                                ])->first();
        
            // Add the current total_mark to the $totalfinal variable
            $totalfinal += $qz->total_mark;
        
            // If a finallist record exists, add the current final_mark to the $markfinal variable
            if ($finallist[$key]) {
                $markfinal += $finallist[$key]->final_mark;
            }
        }

        if($totalfinal != 0 && $markfinal != 0)
        {
            $total_allfinal = round(( (int)$markfinal / (int)$totalfinal ) * (int)$percentagefinal);
        }else{
            $total_allfinal = 0;
        }

        //OTHER

        $percentother = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'lain-lain']
                                ])->first();

        if($percentother != null)
        {
            $percentageother = $percentother->mark_percentage;
        }

        $totalother = 0;
        $markother = 0;

        $other = DB::table('tblclassother')
                    ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                    ->where([
                        ['tblclassother.classid', request()->id],
                        ['tblclassother.sessionid', Session::get('SessionID')],
                        ['tblclassother_group.groupid', $student->group_id],
                        ['tblclassother_group.groupname', $student->group_name],
                        ['status', 2]
                    ])->select('tblclassother.*')->get();
        
        foreach($other as $key => $qz)
        {
            $otherlist[$key] = DB::table('tblclassstudentother')->where([
                                                                ['userid', $student->ic],
                                                                ['otherid', $qz->id],
                                                                ])->first();
        
            // Add the current total_mark to the $totalother variable
            $totalother += $qz->total_mark;
        
            // If a otherlist record exists, add the current final_mark to the $markother variable
            if ($otherlist[$key]) {
                $markother += $otherlist[$key]->final_mark;
            }
        }

        if($totalother != 0 && $markother != 0)
        {
            $total_allother = round(( (int)$markother / (int)$totalother ) * (int)$percentageother);
        }else{
            $total_allother = 0;
        }

        //EXTRA

        $percentextra = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'extra']
                                ])->first();

        if($percentextra != null)
        {
            $percentageextra = $percentextra->mark_percentage;
        }

        $totalextra = 0;
        $markextra = 0;

        $extra = DB::table('tblclassextra')
                    ->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                    ->where([
                        ['tblclassextra.classid', request()->id],
                        ['tblclassextra.sessionid', Session::get('SessionID')],
                        ['tblclassextra_group.groupid', $student->group_id],
                        ['tblclassextra_group.groupname', $student->group_name],
                        ['status', 2]
                    ])->select('tblclassextra.*')->get();
        
        foreach($extra as $key => $qz)
        {
            $extralist[$key] = DB::table('tblclassstudentextra')->where([
                                                                ['userid', $student->ic],
                                                                ['extraid', $qz->id],
                                                                ])->first();
        
            // Add the current total_mark to the $totalextra variable
            $totalextra += $qz->total_mark;
        
            // If a extralist record exists, add the current final_mark to the $markextra variable
            if ($extralist[$key]) {
                $markextra += $extralist[$key]->final_mark;
            }
        }

        if($totalextra != 0 && $markextra != 0)
        {
            $total_allextra = round(( (int)$markextra / (int)$totalextra ) * (int)$percentageextra);
        }else{
            $total_allextra = 0;
        }

        return view('student.courseassessment.reportdetails', compact('student', 'quiz', 'quizlist', 'totalquiz', 'markquiz', 'percentagequiz', 'total_allquiz',
                                                                                  'test', 'testlist', 'totaltest', 'marktest', 'percentagetest', 'total_alltest',
                                                                                  'assign', 'assignlist', 'totalassign', 'markassign', 'percentageassign', 'total_allassign',
                                                                                  'midterm', 'midtermlist', 'totalmidterm', 'markmidterm', 'percentagemidterm', 'total_allmidterm',
                                                                                  'final', 'finallist', 'totalfinal', 'markfinal', 'percentagefinal', 'total_allfinal',
                                                                                  'other', 'otherlist', 'totalother', 'markother', 'percentageother', 'total_allother',
                                                                                  'extra', 'extralist', 'totalextra', 'markextra', 'percentageextra', 'total_allextra'));
    }

    public function warningLetter(Request $request)
    {
        $courseid = Session::get('CourseID');

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS statusName', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic',  Session::get('StudInfo')->ic)->first();

        $data['letter'] = DB::table('tblstudent_warning')
                          ->join('student_subjek', function($join){
                            $join->on('tblstudent_warning.groupid', 'student_subjek.group_id');
                            $join->on('tblstudent_warning.groupname', 'student_subjek.group_name');
                          })
                          ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                          ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                          ->where([
                            ['tblstudent_warning.student_ic', Session::get('StudInfo')->ic],
                            ['subjek.id', $courseid],
                            ['student_subjek.sessionid', Session::get('SessionID')]
                          ])
                          ->groupBy('tblstudent_warning.id')
                          ->select('tblstudent_warning.*', 'subjek.course_name', 'subjek.course_code', 'sessions.SessionName', 'student_subjek.semesterid')
                          ->get();

        return view('student.warningletter.warningLetter', compact('data'));
        
    }

    public function getWarningLetter(Request $request)
    {
        $data['warning'] = DB::table('tblstudent_warning')->where('id', $request->id)->first();

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS statusName', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic',  Session::get('StudInfo')->ic)->first();

        $data['attendance'] = DB::table('tblclassattendance')
                              ->where([
                                ['student_ic', $data['student']->ic],
                                ['groupid', $data['warning']->groupid],
                                ['groupname', $data['warning']->groupname],
                              ])
                              ->where('tblclassattendance.classdate', '<=', $data['warning']->created_at)
                              ->get();                              


        return view('lecturer.class.surat_amaran.surat_amaran', compact('data'));

    }

    public function studentStatement()
    {
        
        $student = Session::get('StudInfo');

        $data['total'] = [];
        $data['total2'] = [];
        $data['total3'] = [];

        $data['student'] = DB::table('students')
                           ->leftjoin('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->leftjoin('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->leftjoin('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $student->ic)->first();

        $record = DB::table('tblpaymentdtl')
        ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
        ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
        ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
        ->where([
            ['tblpayment.student_ic', $student->ic],
            ['tblpayment.process_status_id', 2], 
            ['tblstudentclaim.groupid', 1], 
            ['tblpaymentdtl.amount', '!=', 0]
            ])
        ->select(DB::raw("'payment' as source"), 'tblprocess_type.name AS process', 'tblprocess_type.group_id', 'tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progcode AS program', DB::raw('NULL as remark'));

        $data['record'] = DB::table('tblclaimdtl')
        ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
        ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
        ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
        ->where([
            ['tblclaim.student_ic', $student->ic],
            ['tblclaim.process_status_id', 2],  
            ['tblstudentclaim.groupid', 1],
            ['tblclaimdtl.amount', '!=', 0]
            ])
        ->unionALL($record)
        ->select(DB::raw("'claim' as source"), 'tblprocess_type.name AS process', 'tblprocess_type.group_id', 'tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progcode AS program', 'tblclaim.remark')
        ->orderBy('date')
        ->get();

        $val = 0;
        $data['sum1'] = 0;
        $data['sum2'] = 0;

        foreach($data['record'] as $key => $req)
        {

            if(array_intersect([2], (array) $req->group_id) && $req->source == 'claim')
            {

                $data['total'][$key] = $val + $req->amount;

                $val = $val + $req->amount;

                $data['sum1'] += $req->amount;
                

            }elseif(array_intersect([1], (array) $req->group_id) && $req->source == 'payment')
            {

                $data['total'][$key] = $val - $req->amount;

                $val = $val - $req->amount;

                $data['sum2'] += $req->amount;

            }

        }   

        $data['sum3'] = end($data['total']);

        $data['sponsor'] = DB::table('tblpackage_sponsorship')
                       ->where('student_ic', $student->ic)->first();

        if($data['sponsor'] != null) {

            $data['package'] = DB::table('tblpayment_package')
                            ->join('tblpackage', 'tblpayment_package.package_id', 'tblpackage.id')
                            ->join('tblpayment_type', 'tblpayment_package.payment_type_id', 'tblpayment_type.id')
                            ->join('tblpayment_program', 'tblpayment_package.id', 'tblpayment_program.payment_package_id')
                            ->where([
                                ['tblpayment_package.package_id', $data['sponsor']->package_id],
                                ['tblpayment_package.payment_type_id', $data['sponsor']->payment_type_id],
                                ['tblpayment_program.intake_id', $data['student']->intake],
                                ['tblpayment_program.program_id',$data['student']->progid]
                            ])->select('tblpayment_package.*','tblpackage.name AS package', 'tblpayment_type.name AS type')->first();

            $semester_column = 'semester_' . $data['student']->semester; // e.g., this will be 'semester_2' if $user->semester is 2

            if (isset($data['package']->$semester_column)) {
                $data['value'] = $data['sum3'] - $data['package']->$semester_column;
                // Do something with $semester_value
            } else {
                $data['value'] = 0;
                // Handle case where the column is not set
            }

        }else{

            $data['package'] = null;

        }
                                
        //FINE

        $record2 = DB::table('tblpaymentdtl')
        ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
        ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
        ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
        ->where([
            ['tblpayment.student_ic', $student->ic],
            ['tblpayment.process_status_id', 2],  
            ['tblstudentclaim.groupid', 4],
            ['tblpaymentdtl.amount', '!=', 0]
            ])
        ->select('tblpayment.ref_no', 'tblprocess_type.group_id','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progcode AS program');

        $data['record2'] = DB::table('tblclaimdtl')
        ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
        ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
        ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
        ->where([
            ['tblclaim.student_ic', $student->ic],
            ['tblclaim.process_status_id', 2],  
            ['tblstudentclaim.groupid', 4],
            ['tblclaimdtl.amount', '!=', 0]
            ])        
        ->unionALL($record2)
        ->select('tblclaim.ref_no', 'tblprocess_type.group_id','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progcode AS program')
        ->orderBy('date')
        ->get();

        $val = 0;
        $data['sum1_2'] = 0;
        $data['sum2_2'] = 0;

        foreach($data['record2'] as $key => $req)
        {

            if(array_intersect([2], (array) $req->group_id))
            {

                $data['total2'][$key] = $val + $req->amount;

                $val = $val + $req->amount;

                $data['sum1_2'] += $req->amount;
                

            }elseif(array_intersect([1], (array) $req->group_id))
            {

                $data['total2'][$key] = $val - $req->amount;

                $val = $val - $req->amount;

                $data['sum2_2'] += $req->amount;

            }

        }

        $data['sum3_2'] = end($data['total2']);

        //OTHER

        $record3 = DB::table('tblpaymentdtl')
        ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
        ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
        ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
        ->where([
            ['tblpayment.student_ic', $student->ic],
            ['tblpayment.process_status_id', 2],  
            ['tblstudentclaim.groupid', 5],
            ['tblpaymentdtl.amount', '!=', 0]
            ])
        ->select('tblpayment.ref_no', 'tblprocess_type.group_id', 'tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progcode AS program');

        $data['record3'] = DB::table('tblclaimdtl')
        ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
        ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
        ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
        ->where([
            ['tblclaim.student_ic', $student->ic],
            ['tblclaim.process_status_id', 2],  
            ['tblstudentclaim.groupid', 5],
            ['tblclaimdtl.amount', '!=', 0]
            ])        
        ->unionALL($record3)
        ->select('tblclaim.ref_no', 'tblprocess_type.group_id', 'tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progcode AS program')
        ->orderBy('date')
        ->get();

        $val = 0;
        $data['sum1_3'] = 0;
        $data['sum2_3'] = 0;

        foreach($data['record3'] as $key => $req)
        {

            if(array_intersect([2], (array) $req->group_id))
            {

                $data['total3'][$key] = $val + $req->amount;

                $val = $val + $req->amount;

                $data['sum1_3'] += $req->amount;
                

            }elseif(array_intersect([1], (array) $req->group_id))
            {

                $data['total3'][$key] = $val - $req->amount;

                $val = $val - $req->amount;

                $data['sum2_3'] += $req->amount;

            }

        }

        $data['sum3_3'] = end($data['total3']);

        //TUNGGAKAN KESELURUHAN

        $data['current_balance'] = $data['sum3'];

        $data['total_balance'] = $data['current_balance'];

        $data['pk_balance'] = 0.00;

        //TUNGGAKAN SEMASA

        $package = DB::table('tblpackage_sponsorship')->where('student_ic', $student->ic)->first();

        if($package != null)
        {

            if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
            {

                $discount = abs(DB::table('tblclaim')
                            ->join('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
                            ->where([
                                ['tblclaim.student_ic', $student->ic],
                                ['tblclaim.process_type_id', 5],
                                ['tblclaim.process_status_id', 2],
                                ['tblclaim.remark', 'LIKE', '%Diskaun Yuran Kediaman%']
                            ])->sum('tblclaimdtl.amount'));

            }else{

                $discount = 0;
                
            }

            if($package->package_id == 5)
            {

                $data['current_balance'] = $data['sum3'];

            }else{

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                {

                    if($data['sum3'] <= ($package->amount - $discount))
                    {

                        $data['current_balance'] = 0.00;

                        $data['total_balance'] = 0.00;

                    }elseif($data['sum3'] > ($package->amount - $discount))
                    {

                        $data['current_balance'] = $data['sum3'] - ($package->amount - $discount);

                    }

                }

            }

            //TNUGGAKAN PEMBIAYAAN KHAS

            $stddetail = DB::table('students')->where('ic', $student->ic)->select('program', 'semester')->first();

            if($stddetail->program == 7 || $stddetail->program == 8)
            {

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                {

                    if($data['current_balance'] == 0.00)
                    {

                        $data['pk_balance'] = $data['sum3'];

                    }else{

                        $data['pk_balance'] = ($package->amount - $discount);

                    }

                }

            }else
            {

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                {

                    if($data['current_balance'] == 0.00)
                    {

                        $data['pk_balance'] = $data['sum3'];

                    }else{

                        $data['pk_balance'] = ($package->amount - $discount);

                    }

                }

            }

        }else{

            $data['pk_balance'] = 0.00;

        }

        $data['total_all'] = $data['current_balance'] + $data['pk_balance'];

        return view('student.affair.statement.statement', compact('data'));

    }

    public function studentResult()
    {
        $student = Session::get('StudInfo');

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS statusName', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic',  $student->ic)->first();

        $data['result'] = DB::table('student_transcript')
                ->leftjoin('students', 'student_transcript.student_ic', 'students.ic')
                ->leftjoin('sessions', 'student_transcript.session_id', 'sessions.SessionID')
                ->leftjoin('transcript_status', 'student_transcript.transcript_status_id', 'transcript_status.id')
                ->where([
                    ['student_transcript.student_ic',  $student->ic],
                ])->select('student_transcript.*', 'students.name', 'students.no_matric', 'sessions.SessionName','transcript_status.status_name AS transcript_status_id')
                ->get();

        return view('student.affair.result.studentResult', compact('data'));

    }
}
