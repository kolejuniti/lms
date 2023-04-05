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

        $user = Session::put('StudInfo', $student);

        //dd($student);

        $subject = student::where('student_ic', $student->ic)->get();

        dd($subject);

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

        return view('student', compact(['subject','sessions']));
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

        if(isset($request->search) && isset($request->session))
        {

        $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->join('user_subjek', function($join){
                $join->on('student_subjek.courseid', 'user_subjek.course_id');
                $join->on('student_subjek.sessionid', 'user_subjek.session_id');
            })
            ->join('users', 'user_subjek.user_ic', 'users.ic')
            ->select('subjek.*','student_subjek.courseid','sessions.SessionName','sessions.SessionID','tblprogramme.progname', 'users.name')
            ->groupBy('student_subjek.courseid')
            ->where('sessions.Status', 'ACTIVE')
            ->where('tblprogramme.progstatusid', 1)
            ->where('student_subjek.student_ic', $student->ic)
            ->where('student_subjek.sessionid','LIKE','%'.$request->session.'%')
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            ->get();

        }elseif(isset($request->search))
        {

        $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->join('user_subjek', function($join){
                $join->on('student_subjek.courseid', 'user_subjek.course_id');
                $join->on('student_subjek.sessionid', 'user_subjek.session_id');
            })
            ->join('users', 'user_subjek.user_ic', 'users.ic')
            ->select('subjek.*','student_subjek.courseid','sessions.SessionName','sessions.SessionID','tblprogramme.progname', 'users.name')
            ->groupBy('student_subjek.courseid')
            ->where('sessions.Status', 'ACTIVE')
            ->where('tblprogramme.progstatusid', 1)
            ->where('student_subjek.student_ic', $student->ic)
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            ->get();

        }elseif(isset($request->session))
        {

        $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->join('user_subjek', function($join){
                $join->on('student_subjek.courseid', 'user_subjek.course_id');
                $join->on('student_subjek.sessionid', 'user_subjek.session_id');
            })
            ->join('users', 'user_subjek.user_ic', 'users.ic')
            ->select('subjek.*','student_subjek.courseid','sessions.SessionName','sessions.SessionID','tblprogramme.progname', 'users.name')
            ->groupBy('student_subjek.courseid')
            ->where('sessions.Status', 'ACTIVE')
            ->where('tblprogramme.progstatusid', 1)
            ->where('student_subjek.student_ic', $student->ic)
            ->where('student_subjek.sessionid','LIKE','%'.$request->session.'%')
            ->get();

        }else{

        $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->join('user_subjek', function($join){
                $join->on('student_subjek.courseid', 'user_subjek.course_id');
                $join->on('student_subjek.sessionid', 'user_subjek.session_id');
            })
            ->join('users', 'user_subjek.user_ic', 'users.ic')
            ->select('subjek.*','student_subjek.courseid','sessions.SessionName','sessions.SessionID','tblprogramme.progname', 'users.name')
            ->groupBy('student_subjek.courseid')
            ->where('sessions.Status', 'ACTIVE')
            ->where('tblprogramme.progstatusid', 1)
            ->where('student_subjek.student_ic', $student->ic)
            ->get();

        }

        return view('studentgetcourse', compact('data'));


    }

    public function courseSummary()
    {
        Session::put('CourseID', request()->id);

        if(Session::get('SessionID') == null)
        {
        Session::put('SessionID', request()->session);
        }

        $course = DB::table('subjek')
                  ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
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
        
        $percentagequiz = "";

        $percentagetest = "";

        $percentagemidterm = "";

        $percentagefinal = "";

        $percentageassign = "";

        $percentagepaperwork = "";

        $percentagepractical = "";

        $percentageother = "";

        $percentageextra = "";

        //$percentagefinal = "";

        $student = DB::table('students')
                ->join('student_subjek', 'students.ic', 'student_subjek.student_ic')
                ->where('students.ic', $students->ic)->first();

        //QUIZ

        $percentquiz = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'quiz']
                                ])->first();

        $quiz = DB::table('tblclassstudentquiz')
                ->join('tblclassquiz', 'tblclassstudentquiz.quizid', 'tblclassquiz.id')
                ->where([
                    ['tblclassstudentquiz.userid', $students->ic],
                    ['tblclassquiz.classid', request()->id],
                    ['tblclassquiz.sessionid', Session::get('SessionID')]
                ]);
        
        $totalquiz = $quiz->sum('tblclassquiz.total_mark');

        $markquiz = $quiz->sum('tblclassstudentquiz.final_mark');

        if($percentquiz != null)
        {
            $percentagequiz = $percentquiz->mark_percentage;
        }

        $quizlist = $quiz->get();

        if($totalquiz != 0 && $markquiz != 0)
        {
            $total_allquiz = round(( (int)$markquiz / (int)$totalquiz ) * (int)$percentagequiz);
        }else{
            $total_allquiz = 0;
        }

        //TEST

        $percenttest = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'test']
                                ])->first();

        $test = DB::table('tblclassstudenttest')
                ->join('tblclasstest', 'tblclassstudenttest.testid', 'tblclasstest.id')
                ->where([
                    ['tblclassstudenttest.userid', $students->ic],
                    ['tblclasstest.classid', request()->id],
                    ['tblclasstest.sessionid', Session::get('SessionID')]
                ]);
        
        $totaltest = $test->sum('tblclasstest.total_mark');

        $marktest = $test->sum('tblclassstudenttest.final_mark');

        if($percenttest != null)
        {
            $percentagetest = $percenttest->mark_percentage;
        }

        $testlist = $test->get();

        if($totaltest != 0 && $marktest != 0)
        {
            $total_alltest = round(( (int)$marktest / (int)$totaltest ) * (int)$percentagetest);
        }else{
            $total_alltest = 0;
        }

        //ASSIGNMENT

        $percentassign = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'assignment']
                                ])->first();

        $assign = DB::table('tblclassstudentassign')
                ->join('tblclassassign', 'tblclassstudentassign.assignid', 'tblclassassign.id')
                ->where([
                    ['tblclassstudentassign.userid', $students->ic],
                    ['tblclassassign.classid', request()->id],
                    ['tblclassassign.sessionid', Session::get('SessionID')]
                ]);
        
        $totalassign = $assign->sum('tblclassassign.total_mark');

        $markassign = $assign->sum('tblclassstudentassign.final_mark');

        if($percentassign != null)
        {
            $percentageassign = $percentassign->mark_percentage;
        }

        $assignlist = $assign->get();

        if($totalassign != 0 && $markassign != 0)
        {
            $total_allassign = round(( (int)$markassign / (int)$totalassign ) * (int)$percentageassign);
        }else{
            $total_allassign = 0;
        }

        // MIDTERM

        $percentmidterm = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'midterm']
                                ])->first();

        //dd($percent);
  
        $midterm = DB::table('tblclassstudentmidterm')
                ->join('tblclassmidterm', 'tblclassstudentmidterm.midtermid', 'tblclassmidterm.id')
                ->where([
                    ['tblclassstudentmidterm.userid', $students->ic],
                    ['tblclassmidterm.classid', request()->id],
                    ['tblclassmidterm.sessionid', Session::get('SessionID')]
                ]);
        
        $totalmidterm = $midterm->sum('tblclassmidterm.total_mark');
   
        $markmidterm = $midterm->sum('tblclassstudentmidterm.final_mark');

        if($percentmidterm != null)
        {
            $percentagemidterm = $percentmidterm->mark_percentage;
        }

        $midtermlist = $midterm->get();

        if($totalmidterm != 0 && $markmidterm != 0)
        {
            $total_allmidterm = round(( (int)$markmidterm / (int)$totalmidterm ) * (int)$percentagemidterm);
        }else{
            $total_allmidterm = 0;
        }

        
        //FINAL

        $percentfinal = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'final']
                                ])->first();

        //dd($percent);
  
        $final = DB::table('tblclassstudentfinal')
                ->join('tblclassfinal', 'tblclassstudentfinal.finalid', 'tblclassfinal.id')
                ->where([
                    ['tblclassstudentfinal.userid', $students->ic],
                    ['tblclassfinal.classid', request()->id],
                    ['tblclassfinal.sessionid', Session::get('SessionID')]
                ]);
        
        $totalfinal = $final->sum('tblclassfinal.total_mark');
   
        $markfinal = $final->sum('tblclassstudentfinal.final_mark');

        if($percentfinal != null)
        {
            $percentagefinal = $percentfinal->mark_percentage;
        }

        $finallist = $final->get();

        if($totalfinal != 0 && $markfinal != 0)
        {
            $total_allfinal = round(( (int)$markfinal / (int)$totalfinal ) * (int)$percentagefinal);
        }else{
            $total_allfinal = 0;
        }

        //PAPERWORK

        $percentpaperwork = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'paperwork']
                                ])->first();

        $paperwork = DB::table('tblclassstudentpaperwork')
                ->join('tblclasspaperwork', 'tblclassstudentpaperwork.paperworkid', 'tblclasspaperwork.id')
                ->where([
                    ['tblclassstudentpaperwork.userid', $students->ic],
                    ['tblclasspaperwork.classid', request()->id],
                    ['tblclasspaperwork.sessionid', Session::get('SessionID')]
                ]);
        
        $totalpaperwork = $paperwork->sum('tblclasspaperwork.total_mark');

        $markpaperwork = $paperwork->sum('tblclassstudentpaperwork.final_mark');

        if($percentpaperwork != null)
        {
            $percentagepaperwork = $percentpaperwork->mark_percentage;
        }

        $paperworklist = $paperwork->get();

        if($totalpaperwork != 0 && $markpaperwork != 0)
        {
            $total_allpaperwork = round(( (int)$markpaperwork / (int)$totalpaperwork ) * (int)$percentagepaperwork);
        }else{
            $total_allpaperwork = 0;
        }

        //PRACTICAL

        $percentpractical = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'practical']
                                ])->first();

        $practical = DB::table('tblclassstudentpractical')
                ->join('tblclasspractical', 'tblclassstudentpractical.practicalid', 'tblclasspractical.id')
                ->where([
                    ['tblclassstudentpractical.userid', $students->ic],
                    ['tblclasspractical.classid', request()->id],
                    ['tblclasspractical.sessionid', Session::get('SessionID')]
                ]);
        
        $totalpractical = $practical->sum('tblclasspractical.total_mark');

        $markpractical = $practical->sum('tblclassstudentpractical.final_mark');

        if($percentpractical != null)
        {
            $percentagepractical = $percentpractical->mark_percentage;
        }

        $practicallist = $practical->get();

        if($totalpractical != 0 && $markpractical != 0)
        {
            $total_allpractical = round(( (int)$markpractical / (int)$totalpractical ) * (int)$percentagepractical);
        }else{
            $total_allpractical = 0;
        }

        //OTHER

        $percentother = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'lain-lain']
                                ])->first();

        $other = DB::table('tblclassstudentother')
                ->join('tblclassother', 'tblclassstudentother.otherid', 'tblclassother.id')
                ->where([
                    ['tblclassstudentother.userid', $students->ic],
                    ['tblclassother.classid', request()->id],
                    ['tblclassother.sessionid', Session::get('SessionID')]
                ]);
        
        $totalother = $other->sum('tblclassother.total_mark');

        $markother = $other->sum('tblclassstudentother.final_mark');

        if($percentother != null)
        {
            $percentageother = $percentother->mark_percentage;
        }

        $otherlist = $other->get();

        if($totalother != 0 && $markother != 0)
        {
            $total_allother = round(( (int)$markother / (int)$totalother ) * (int)$percentageother);
        }else{
            $total_allother = 0;
        }

        //EXTRA

        $percentextra = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'extra']
                                ])->first();

        $extra = DB::table('tblclassstudentextra')
                ->join('tblclassextra', 'tblclassstudentextra.extraid', 'tblclassextra.id')
                ->where([
                    ['tblclassstudentextra.userid', $students->ic],
                    ['tblclassextra.classid', request()->id],
                    ['tblclassextra.sessionid', Session::get('SessionID')]
                ]);
        
        $totalextra = $extra->sum('tblclassextra.total_mark');

        $markextra = $extra->sum('tblclassstudentextra.final_mark');

        if($percentextra != null)
        {
            $percentageextra = $percentextra->mark_percentage;
        }

        $extralist = $extra->get();

        if($totalextra != 0 && $markextra != 0)
        {
            $total_allextra = round(( (int)$markextra / (int)$totalextra ) * (int)$percentageextra);
        }else{
            $total_allextra = 0;
        }

        return view('student.courseassessment.reportdetails', compact('student', 'quizlist', 'totalquiz', 'markquiz', 'percentagequiz', 'total_allquiz',
                                                                                  'testlist', 'totaltest', 'marktest', 'percentagetest', 'total_alltest',
                                                                                 'assignlist', 'totalassign', 'markassign', 'percentageassign', 'total_allassign',
                                                                                 'midtermlist', 'totalmidterm', 'markmidterm', 'percentagemidterm', 'total_allmidterm',
                                                                                 'finallist', 'totalfinal', 'markfinal', 'percentagefinal', 'total_allfinal',
                                                                                 'paperworklist', 'totalpaperwork', 'markpaperwork', 'percentagepaperwork', 'total_allpaperwork',
                                                                                 'practicallist', 'totalpractical', 'markpractical', 'percentagepractical', 'total_allpractical',
                                                                                 'otherlist', 'totalother', 'markother', 'percentageother', 'total_allother',
                                                                                 'extralist', 'totalextra', 'markextra', 'percentageextra', 'total_allextra'));
    }
}
