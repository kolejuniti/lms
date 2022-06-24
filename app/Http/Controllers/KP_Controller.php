<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subject;
use App\Models\student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KP_Controller extends Controller
{
    public function index()
    {
        $kp = Auth::user();

        $data = subject::join('users', 'user_subjek.user_ic', '=', 'users.ic')
                        ->join('subjek', 'user_subjek.course_id','=', 'subjek.sub_id')
                        ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                        ->select('users.name','user_subjek.*','subjek.course_name','subjek.course_code','sessions.SessionName')
                        ->where('users.faculty', $kp->faculty)
                        ->where('user_subjek.addby', $kp->ic)
                        //->orderBy('sessions.SessionID')
                        ->get();

        return view('ketua_program', compact('data'));
    }

    public function create()
    {
        $users = Auth::user();

        $programs = DB::table('user_program')->where('user_ic', $users->ic)->pluck('program_id');

        //dd($programs);

        //this will fetch user data where usrtype is not ADM
        $user = User::whereNot('usrtype',['ADM'])->where('faculty', $users->faculty)->get();

        $course = DB::table('subjek')
                  ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->whereIn('prgid', $programs)->get();
                  

        $session = DB::table('sessions')
                ->get();

        return view('ketua_program.create')->with(compact('user','course','session'));
    }

    public function store()
    {
        $users = Auth::user();

        //dd($users);

        //this will validate the requested data
        $data = request()->validate([
            //'group' => ['required','string'],
            'lct' => ['required','string'],
            'course' => ['required'],
            'session' => ['required'],
        ]);

        //this will create data in table [Please be noted that model need to be fillable with the same data]
        subject::create([
            //'group_name' => $data['group'],
            'user_ic' => $data['lct'],
            'course_id' => $data['course'],
            'session_id' => $data['session'],
            'addby' => $users->ic,
        ]);

        //this will redirect user to route named ketua_program
        return redirect(($users->usrtype == 'KP') ? route('ketua_program') : route('pegawai_takbir'));
    }

    public function delete(Request $request)
    {
        subject::where('id', $request->id)->delete();

        return true;
    }

    public function assessment()
    {

       $data['classmark'] = DB::table('tblclassmarks')->where('course_id', request()->course)
               ->get();

       $data['course'] = request()->course;
               

       $assessment = array(
        "quiz" => "quiz",
        "assignment" => "assignment",
        "midterm" => "midterm",
        "final" => "final",
        "paperwork" => "paperwork",
        "practical" => "practical",
        "lain-lain" => "lain-lain"
       );

       if($data['classmark']->isEmpty())
       {
           foreach($assessment as $key){

                $datas[] = DB::table('tblclassmarks')->insertGetId([
                    'assessment' => $key,
                    'course_id' => request()->course,
                    'mark_percentage' => 0
                ]);

           }

           //dd($data);

            $data['classmark'] = DB::table('tblclassmarks')->whereIn('id', $datas)->get();

            //$data['course'] = request()->course;

            //dd($data);
       }
 
        return view('ketua_program.assessment', compact('data'));
    }

    public function update_marks(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'marks' => 'required',
        ]);

        $all = $request->marks;

        $marks = DB::table('tblclassmarks')->where('course_id', $request->course)->get();

        //dd($marks);
        
        foreach($marks as $key=>$mark)
        {
                DB::table('tblclassmarks')->where([['assessment', $mark->assessment],['course_id', $request->course]])
                ->update([
                    'mark_percentage' => $all[$key],
                ]);
        }

        

        return redirect()->route('ketua_program');

    }

    public function edit()
    {

       $data = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
               ->join('students', 'student_subjek.student_ic', 'students.ic')
               ->where('user_subjek.id', request()->group)
               ->select('student_subjek.*', 'students.name', 'students.no_matric')
               ->get();

        //dd($data);

        return view('ketua_program.edit', compact('data'));
    }

    public function editgroup()
    {
       $users = Auth::user();

       $data = subject::where('id', request()->group)->first();

       $programs = DB::table('user_program')->where('user_ic', $users->ic)->pluck('program_id');

       //dd($data);

       //this will fetch user data where usrtype is not ADM
       $user = User::whereNot('usrtype',['ADM'])->where('faculty', $users->faculty)->get();

       $course = DB::table('subjek')
                  ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->whereIn('prgid', $programs)->get();

       $session = DB::table('sessions')
               ->get();
 
        return view('ketua_program.create', compact('data', 'user', 'course', 'session'));
    }

    public function updategroup(Request $request)
    {
        $data = [
                    'group_name' => $request->group,
                    'user_ic' => $request->lct,
                    'course_id' => $request->course,
                    'session_id' => $request->session
                ];

        //dd($request->id);

        DB::table('user_subjek')->where('id', $request->id)->update($data);

        return redirect(route('ketua_program'));

    }

    //this function is to update group ID into user_student table because of migrating table from external database//
    public function student() 
    {
        DB::table('user_student')
        ->join('user_subjek', function($join)
        {
        $join ->on('user_student.user_ic','=','user_subjek.user_ic');
        $join ->on('user_student.course_id','=','user_subjek.course_id');
        $join ->on('user_student.session_id','=','user_subjek.session_id');
        })
        ->select('user_subjek.*')
        ->update([
            'user_student.group_id' => DB::raw('user_subjek.id')
        ]);

        dd('successful');
    }

    public function update(Request $request)
    {
        $request->validate([
            'students' => 'required',
        ]);

        $stud = student::find($request->students);

        //dd($stud);
        
        foreach($stud as $key)
        {
            if($key->status == "ACTIVE")
            {
                $key->update([
                    'status' => 'NOTACTIVE',
                ]);
            }else{
                $key->update([
                    'status' => 'ACTIVE',
                ]);
            }
        }

        return redirect()->route('kp.edit', $request->group);
    }

    
    public function create_group()
    {
        $users = Auth::user();

        $programs = DB::table('user_program')->join('tblprogramme', 'user_program.program_id', 'tblprogramme.id')->where('user_ic', $users->ic)->get();

        //$course = DB::table('subjek')->where('prgid', $users->programid)->get();
        //dd($programs);

        //$course = DB::table('subjek')
                  //->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->whereIn('prgid', $programs)->get();

        $session = DB::table('sessions')->get();

        return view('ketua_program.assigngroup', compact('programs', 'session'));
    }

    public function getStudentTable(Request $request)
    {
        $students = DB::table('student_subjek')
        ->select('student_subjek.*', 'students.name','students.no_matric','students.intake')
        ->join('students', 'student_subjek.student_ic', 'students.ic')
       
        ->where('courseid',$request->course)
        ->where('sessionid',$request->session)
        
        ->get();

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>Name</th>
            <th>Matric No</th>
            <th>Intake</th>
            <th>Group</th>
            <th>Status</th>
            <th></th>
            </thead>
            <tbody>
        ';
        foreach($students as $student){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <label class="text-dark"><strong>'.$student->name.'</strong></label><br>
                    <label>IC: '.$student->student_ic.'</label>
                </td>
                <td >
                    <label>'.$student->no_matric.'</label>
                </td>
                <td >
                    <p class="text-bold text-fade">'.$student->intake.'</p>
                </td>
                <td >
                    <p class="text-bold text-fade">'.$student->group_name.'</p>
                </td>
                <td >
                    <p class="text-bold text-fade">'.$student->status.'</p>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="student_checkbox_'.$student->id.'"
                            class="filled-in" name="student[]" value="'.$student->id.'" 
                        >
                        <label for="student_checkbox_'.$student->id.'"> </label>
                    </div>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';

            return $content;

    }

    public function getCourse(Request $request)
    {
        $course = DB::table('subjek')->where('prgid', $request->program)->get();

        $content = "";

        $content .= "<option value='-' disabled selected>-</option>";
        foreach($course as $crs){
            $content .= '<option value='. $crs->sub_id .'>
            '. $crs->course_code .' : '. $crs->course_name.' (Semester '. $crs->semesterid .')</option>';
        };
        return $content;

    }

    public function getLecturer(Request $request)
    {
        $lecturer = subject::where('course_id', $request->course)->where('session_id', $request->session)->get();

        $content = "";

        $content .= "<option value='-' disabled selected>-</option>";
        foreach($lecturer as $lct){

            $lecturer = User::where('ic', $lct->user_ic)->first();

            $content .= '<option data-style="btn-inverse"  
            data-content=\'<div class="row" >
                <div class="col-md-2">
                <div class="d-flex justify-content-center">
                    <img src="" 
                        height="auto" width="70%" class="bg-light ms-0 me-2 rounded-circle">
                        </div>
                </div>
                <div class="col-md-10 align-self-center lh-lg">
                    <span><strong>'. $lecturer->name .'</strong></span><br>
                    <span>'. $lecturer->email .' | <strong class="text-fade"">'.$lecturer->faculty .'</strong></span><br>
                    <span class="text-fade"></span>
                </div>
            </div>\' value='. $lct->id .'></option>';
        }
        
        return $content;
    }

    public function getGroupOption(Request $request){
        $group = subject::where('course_id', $request->subject)->where('session_id', $request->session)->get();

        $content = "";

        $content .= "<option value='-' disabled selected>-</option>";
        foreach($group as $grp){
            $content .= '<option value='. $grp->id .'>
            '. $grp->group_name.'</option>';
        };
        return $content;
    }

    public function getStudentTableIndex(Request $request)
    {
        if(isset($request->session))
        {
            if(isset($request->group))
            {
                $students = student::where('courseid', $request->subject)->where('sessionid', $request->session)->where('group_id', $request->group)->get();
            }else
            {
                $students = student::where('courseid', $request->subject)->where('sessionid', $request->session)->get();
            }
        }else
        {
            $students = student::where('courseid', $request->subject)->get();
        }

        $content = "";
        foreach($students as $key => $student){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td style="width: 1%">
                  '. $key+1 .'
                </td>
                <td style="width: 15%">
                  '. $student->student_ic .'
                </td>
                <td style="width: 30%">
                '. $student->sessionid .'
                </td>
                <td>
                '. $student->courseid .'
                </td>
                <td class="project-actions text-right" >
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="#">
                      <i class="ti-pencil-alt">
                      </i>
                      Edit
                  </a>
                  <a class="btn btn-danger btn-sm" href="#">
                      <i class="ti-trash">
                      </i>
                      Delete
                  </a>
                </td>
              </tr>
              ';
            }

            return $content;

    }

    public function update_group(Request $request)
    {
        //dd($request->student);

        //$register = $request->validate([
            //'program' => 'required',
            //'course' => 'required',
           // 'session' => 'required',
           // 'lecturer' => 'required',
           // 'student' => 'required',
        //]);



        
        //dd($request->lecturer);

        foreach ($request->student as $stud) {
 
            student::where('id', $stud)->update([
                'group_id' => $request->lecturer,
                'group_name' => $request->group
            ]);      

        }

        return redirect(route('kp.group'));
    }

    public function lecturerindex() 
    {
        $faculty = Auth::user()->faculty;

        $lecturer = User::where('faculty', $faculty)->paginate(5);

        return view('ketua_program.lecturerlist', compact('lecturer'));
    }

    public function getLecturerTable(Request $request)
    {
        $faculty = Auth::user()->faculty;

        $lecturer = User::where('faculty', $faculty)
        ->where('name','LIKE','%'.$request->search."%")
        ->paginate(5);

        return view('ketua_program.getlecturerlist', compact('lecturer'));
    }

    public function lecturer_report(Request $request)
    {
        $user = Auth::user();

        $programs = DB::table('user_program')->where('user_ic', $user->ic)->pluck('program_id');

        //dd($programs);

        $lecturer = User::find($request->id);

        //$groupz = subject::where('user_ic', $lecturer->ic)
                // ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                // ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
                // ->whereIn('subjek.prgid', $programs)
                // ->select('user_subjek.*','tblprogramme.progname','subjek.course_name','subjek.course_code','subjek.course_credit','subjek.semesterid')
                // ->get();

        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                 ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                 ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
                 ->whereIn('subjek.prgid', $programs)
                 ->where('user_subjek.user_ic', $lecturer->ic)->groupBy('student_subjek.group_id')->groupBy('student_subjek.group_name')
                 ->select('user_subjek.*','student_subjek.group_name','tblprogramme.progname','subjek.course_name','subjek.course_code','subjek.course_credit','subjek.semesterid')->get();

        //dd($group);
        

        $sum = subject::where('user_ic', $lecturer->ic)
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->whereIn('subjek.prgid', $programs)->sum('subjek.course_credit');

                //dd($group);

        $sumbyses = DB::table('sessions')->get();

        foreach($sumbyses as $semses)
        {
            $sums[] = subject::where('user_ic', $lecturer->ic)
                    ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                    ->whereIn('subjek.prgid', $programs)
                    ->where('user_subjek.session_id', $semses->SessionID)->sum('subjek.course_credit');
        }

        //dd($sums);

        if($group->isNotEmpty())
        {
            foreach($group as $grp)
            {
                $student[] = student::where([
                    ['group_name', $grp->group_name],
                    ['courseid', $grp->course_id],
                    ['sessionid', $grp->session_id],
                    ['group_id', $grp->id]
                ])
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->get();
            }

            //dd($student);
            

            foreach($group as $grp2)
            {
                $numStud[] = student::where([
                    ['group_name', $grp2->group_name],
                    ['courseid', $grp2->course_id],
                    ['sessionid', $grp2->session_id],
                    ['group_id', $grp2->id]
                ])
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->count();
            }
        }else{
            $student = null;

            $numStud = null;
        }

        //dd($student);


        //dd($numStud);

                 //dd($group);

        return view('ketua_program.lecturer_report', compact(['lecturer', 'group', 'student', 'sum','sums','sumbyses','numStud']));
    }

    public function courseMark()
    {
        $user = Auth::user();

        $course = DB::table('subjek')
                  ->join('user_program', 'subjek.prgid', 'user_program.program_id')
                  ->where('user_program.user_ic', $user->ic)
                  ->select('subjek.*')->get();

        //dd($course);

        return view('ketua_program.course_mark', compact('course'));
    }
}
