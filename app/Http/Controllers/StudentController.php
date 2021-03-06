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
        $student = auth()->guard('student')->user();

        //dd($student);

        $user = Session::put('StudInfo', $student);

        $subject = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
        ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
        ->select('subjek.*','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
        ->groupBy('student_subjek.courseid')
        ->where('student_subjek.student_ic', $student->ic)
        ->get();

        //return dd($subject);

        $sessions = DB::table('sessions')->where('Status', 'ACTIVE')->get();

        return view('student', compact(['subject','sessions']));
    }

    public function getCourseList(Request $request)
    {
        $student = Session::get('StudInfo');

        if(isset($request->search) && isset($request->session))
        {

        $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->select('subjek.*','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
            ->where('student_subjek.sessionid','LIKE','%'.$request->session.'%')
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            ->groupBy('student_subjek.courseid')
            ->where('student_subjek.student_ic', $student->ic)
            ->get();

        }elseif(isset($request->search))
        {

        $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->select('subjek.*','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            ->groupBy('student_subjek.courseid')
            ->where('student_subjek.student_ic', $student->ic)
            ->get();

        }elseif(isset($request->session))
        {

        $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->select('subjek.*','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
            ->where('student_subjek.sessionid','LIKE','%'.$request->session.'%')
            ->groupBy('student_subjek.courseid')
            ->where('student_subjek.student_ic', $student->ic)
            ->get();

        }else{

        $data = student::join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
            ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
            ->select('subjek.*','student_subjek.courseid','sessions.SessionName','sessions.SessionID')
            ->groupBy('student_subjek.courseid')
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

        //dd(request()->session);

        return view('student.coursesummary.coursesummary');
    }

    public function courseContent()
    {
        $folder = DB::table('lecturer_dir')
        ->where([
            ['CourseID', request()->id],
            ['SessionID', Session::get('SessionID')],
            ])->get();

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

            $dir = "classmaterial/" . $directory->A . "/" . $directory->B;

            //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
            $classmaterial  = Storage::disk('linode')->files($dir);

            return view('student.coursecontent.materialsubdirectory', compact('mat_directory', 'classmaterial'))->with('dirid', $directory->DrID)->with('prev', $directory->LecturerDirID);
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

            $dir = "classmaterial/" . $password->A . "/" . $password->B;

            //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
            $classmaterial  = Storage::disk('linode')->files($dir);

            return view('student.coursecontent.materialsubdirectory', compact('mat_directory', 'classmaterial'))->with('dirid', $password->DrID)->with('prev', $password->LecturerDirID);
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

        $dir = "classmaterial/" . $directory->A . "/" . $directory->B;

        //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
        $classmaterial  = Storage::disk('linode')->files($dir);

        return view('student.coursecontent.materialsubdirectory', compact('mat_directory', 'classmaterial'))->with('dirid', $directory->DrID)->with('prev', $directory->LecturerDirID);

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
            
            $dir = "classmaterial/" . $directory->A . "/" . $directory->B . "/" . $directory->C;

            $classmaterial  = Storage::disk('linode')->allFiles( $dir );

            return view('student.coursecontent.coursematerial', compact('classmaterial'))->with('dirid', $directory->DrID)->with('prev', $directory->MaterialDirID);
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

            $dir = "classmaterial/" . $password->A . "/" . $password->B . "/" . $password->C;

            $classmaterial  = Storage::disk('linode')->allFiles( $dir );
    
            return view('student.coursecontent.coursematerial', compact('classmaterial'))->with('dirid', $password->DrID)->with('prev', $password->MaterialDirID);
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

        $percentagemidterm = "";

        $percentagefinal = "";

        $percentageassign = "";

        $percentagepaperwork = "";

        $percentagepractical = "";

        $percentageother = "";

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

        return view('student.courseassessment.reportdetails', compact('student', 'quizlist', 'totalquiz', 'markquiz', 'percentagequiz', 'total_allquiz',
                                                                                 'assignlist', 'totalassign', 'markassign', 'percentageassign', 'total_allassign',
                                                                                 'midtermlist', 'totalmidterm', 'markmidterm', 'percentagemidterm', 'total_allmidterm',
                                                                                 'finallist', 'totalfinal', 'markfinal', 'percentagefinal', 'total_allfinal',
                                                                                 'paperworklist', 'totalpaperwork', 'markpaperwork', 'percentagepaperwork', 'total_allpaperwork',
                                                                                 'practicallist', 'totalpractical', 'markpractical', 'percentagepractical', 'total_allpractical',
                                                                                 'otherlist', 'totalother', 'markother', 'percentageother', 'total_allother',));
    }
}
