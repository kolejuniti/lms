<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subject;
use App\Models\student;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KP_Controller extends Controller
{
    public function index()
    {
        //forgot current session
        Session::forget(['User','CourseID','SessionID']);

        Session::put('User', Auth::user());

        $kp = Auth::user();

        $data = subject::join('users', 'user_subjek.user_ic', '=', 'users.ic')
                        ->join('subjek', 'user_subjek.course_id','=', 'subjek.sub_id')
                        ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                        ->select('users.name','user_subjek.*','subjek.course_name','subjek.course_code','sessions.SessionName')
                        ->where('users.faculty', $kp->faculty)
                        //->orderBy('sessions.SessionID')
                        ->get();

        return view('ketua_program', compact('data'));
    }

    public function create()
    {
        $users = Auth::user();

        //$programs = DB::table('user_program')->where('user_ic', $users->ic)->pluck('program_id');

        //dd($programs);

        $programs = DB::table('tblprogramme')->get();

        //this will fetch user data where usrtype is not ADM
        $user = User::whereIn('usrtype',['LCT', 'AO', 'PL'])->get();

        //$course = DB::table('subjek')
                  //->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->whereIn('prgid', $programs)->get();
                  

        $session = DB::table('sessions')->where('status', 'ACTIVE')
                ->get();

        return view('ketua_program.create')->with(compact('user','programs','session'));
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
        if(DB::table('user_subjek')->where([['user_ic', $data['lct']],['course_id', $data['course']], ['session_id', $data['session']]])->exists())
        {

            return back()->with('message', 'Lecturer already registered with the same details, please try again!');

        }else{

            subject::create([
                //'group_name' => $data['group'],
                'user_ic' => $data['lct'],
                'course_id' => $data['course'],
                'session_id' => $data['session'],
                'addby' => $users->ic,
            ]);

            //this will redirect user to route named ketua_program
            return back();

        }
    }

    public function delete(Request $request)
    {
        subject::where('id', $request->id)->delete();

        return true;
    }

    public function assessment()
    {
       $sub_id = DB::table('subjek')->where('id', request()->course)->value('sub_id');

       $marks = DB::table('tblclassmarks')->where('course_id', $sub_id);

       $data['classmark'] = $marks->get();

       $type = $marks->pluck('assessment');

       $data['course'] = $sub_id;
               

       $assessment = array(
        "quiz" => "quiz",
        "test" => "test",
        "test2" => "test2",
        "assignment" => "assignment",
        "midterm" => "midterm",
        "final" => "final",
        // "paperwork" => "paperwork",
        // "practical" => "practical",
        "lain-lain" => "lain-lain",
        "extra" => "extra"
       );

       if($data['classmark']->isEmpty())
       {
        //    foreach($assessment as $key){

        //         $datas[] = DB::table('tblclassmarks')->insertGetId([
        //             'assessment' => $key,
        //             'course_id' => $sub_id,
        //             'mark_percentage' => 0
        //         ]);

        //    }

        //    $data['classmark'] = DB::table('tblclassmarks')->whereIn('id', $datas)->get();

        $data['classmark'] = [];

           $data['assessment'] = $assessment;

       }else{

        foreach ($data['classmark'] as $key => $value) {
            if (($key = array_search($value->assessment, $assessment)) !== false) {
                unset($assessment[$key]);
            }
        }

        $data['assessment'] = $assessment;

       }
 
        return view('ketua_program.assessment', compact('data'));
    }

    private function removeElementWithValue($array, $key, $value){
        foreach($array as $subKey => $subArray){
             if($subArray[$key] == $value){
                  unset($array[$subKey]);
             }
        }
        return $array;
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

        

        return back();

    }

    public function insert_marks(Request $request)
    {
        $request->validate([
            'assessment' => 'required'
        ]);
        
        DB::table('tblclassmarks')->insert([
            'assessment' => $request->assessment,
            'course_id' => $request->course,
            'mark_percentage' => 0
        ]);

        return back();
    }

    public function delete_marks(Request $request)
    {

        DB::table('tblclassmarks')->where('id', $request->id)->delete();

        return true;

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

       $programs = DB::table('tblprogramme')->get();

        $data = subject::join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->where('user_subjek.id', request()->group)
            ->first();

        $user = User::whereIn('usrtype',['LCT', 'AO', 'PL'])->get();


        $session = DB::table('sessions')->where('status', 'ACTIVE')
        ->get();
 
        return view('ketua_program.create', compact('data', 'user', 'programs', 'session'));
    }

    public function updategroup(Request $request)
    {
        $data = [
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

        if(Auth::user()->usrtype == 'AR')
        {

            $programs = DB::table('user_program')->join('tblprogramme', 'user_program.program_id', 'tblprogramme.id')->get();

        }else{

            $programs = DB::table('user_program')->join('tblprogramme', 'user_program.program_id', 'tblprogramme.id')->where('user_ic', $users->ic)->get();

        }

        $semester = DB::table('semester')->get();

        //$course = DB::table('subjek')->where('prgid', $users->programid)->get();
        //dd($programs);

        //$course = DB::table('subjek')
                  //->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->whereIn('prgid', $programs)->get();

        $session = DB::table('sessions')->where('Status', 'ACTIVE')->orderBy('SessionID', 'DESC')->get();

        return view('ketua_program.assigngroup', compact('programs', 'session', 'semester'));
    }

    public function getStudentTable(Request $request)
    {

        if(isset($request->session))
        {
            if(isset($request->course))
            {
                $student = DB::table('student_subjek')
                ->select('student_subjek.*', 'students.name', 'students.semester','students.no_matric','sessions.SessionName AS intake')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('sessions', 'students.intake', 'sessions.SessionID')
                ->where('student_subjek.courseid',$request->course)
                ->where('student_subjek.sessionid',$request->session)
                ->where('students.program', $request->program)
                ->where('student_subjek.group_id', null);
                
                if(isset($request->semester))
                {

                   $student->where('student_subjek.semesterid', $request->semester);

                }

                $students = $student->orderBy('students.name')->get();
            }else
            {
                $student = DB::table('student_subjek')
                ->select('student_subjek.*', 'students.name', 'students.semester','students.no_matric','sessions.SessionName AS intake')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('sessions', 'students.intake', 'sessions.SessionID')
                ->where('student_subjek.sessionid',$request->session)
                ->where('students.program', $request->program)
                ->where('student_subjek.group_id', null);

                if(isset($request->semester))
                {

                    $student->where('student_subjek.semesterid', $request->semester);

                }


                $students = $student->orderBy('students.name')->get();
            }
        }
        
        // else
        // {
        //     $students = DB::table('student_subjek')
        //         ->select('student_subjek.*', 'students.name','students.no_matric','students.intake')
        //         ->join('students', 'student_subjek.student_ic', 'students.ic')
        //         ->where('courseid',$request->course)->get();
        // }

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>No.</th>
            <th>Name</th>
            <th>Matric No</th>
            <th>Intake</th>
            <th>Semester</th>
            <th>Group</th>
            <th>Status</th>
            <th></th>
            </thead>
            <tbody>';
$content .= '<tr>
                <td>
                    
                </td>
                <td>
                    <label class="text-dark"><strong>SELECT ALL</strong></label><br>
                </td>
                <td>
                    
                </td>
                <td>
                    
                </td>
                <td>
                    
                </td>
                <td>
                    
                </td>
                <td>
                    
                </td>
                <td>
                    <div class="pull-right" >
                        <input type="checkbox" id="checkboxAll"
                            class="filled-in" name="checkall"
                            onclick="CheckAll(this)"
                        >
                        <label for="checkboxAll"> </label>
                    </div>
                </td>
            </tr>
        ';
        foreach($students as $key => $student){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td>
                    '. $key+1 .'
                </td>
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
                    <p class="text-bold text-fade">'.$student->semester.'</p>
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

    public function getStudentTable2(Request $request)
    {

        if(isset($request->lecturer))
        {
            $students = DB::table('student_subjek')
                ->select('student_subjek.*', 'students.name', 'students.semester','students.no_matric','sessions.SessionName AS intake')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('sessions', 'students.intake', 'sessions.SessionID')
                ->where('student_subjek.courseid',$request->course)
                ->where('student_subjek.sessionid',$request->session)
                ->where('student_subjek.group_id', $request->lecturer)
                ->where('students.program', $request->program)->orderBy('students.name')->get();
        }

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>No.</th>
            <th>Name</th>
            <th>Matric No</th>
            <th>Intake</th>
            <th>Semester</th>
            <th>Group</th>
            <th>Status</th>
            <th></th>
            </thead>
            <tbody>';
$content .= '<tr>
                <td>
                    
                </td>
                <td>
                    <label class="text-dark"><strong>SELECT ALL</strong></label><br>
                </td>
                <td>
                    
                </td>
                <td>
                    
                </td>
                <td>
                    
                </td>
                <td>
                    
                </td>
                <td>
                    
                </td>
                <td>
                    <div class="pull-right" >
                        <input type="checkbox" id="checkboxAll2"
                            class="filled-in" name="checkall2"
                            onclick="CheckAll2(this)"
                        >
                        <label for="checkboxAll2"> </label>
                    </div>
                </td>
            </tr>
        ';
        foreach($students as $key => $student){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td>
                    '. $key+1 .'
                </td>
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
                    <p class="text-bold text-fade">'.$student->semester.'</p>
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
                            class="filled-in" name="student2[]" value="'.$student->id.'" 
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

    public function getLecturerSubject(Request $request)
    {

        //this will fetch user data where usrtype is not ADM
        $user = User::whereIn('usrtype',['LCT', 'AO', 'PL'])->get();

        $data['subject'] = DB::table('user_subjek')
                           ->where('sessions.Status', 'ACTIVE')
                           ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                           ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                           ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                           ->join('users', 'user_subjek.user_ic', 'users.ic')
                           ->where([
                            ['user_subjek.course_id', $request->course],
                            ['user_subjek.session_id', $request->session], 
                            ['subjek_structure.program_id', $request->program]
                            ])
                            ->groupBy('user_subjek.id')
                           ->select('users.name', 'users.no_staf','user_subjek.id','user_subjek.user_ic','user_subjek.amali_ic', 'subjek.course_name', 'subjek.course_code', 'sessions.SessionName')
                           ->get();

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>No.</th>
            <th>Main Lecturer</th>
            <th>Staff No.</th>
            <th>Lecturer Amali</th>
            <th>Course</th>
            <th>Session</th>
            <th></th>
            </thead>
            <tbody>
        ';
        foreach($data['subject'] as $i => $sbj){
            //$registered = ($sbj->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td>
                    '. $i+1 .'
                </td>
                <td>
                    <div class="form-group">
                          <select class="form-select" id="main-'. $sbj->id .'" name="main-'. $sbj->id .'">
                              <option value="-" selected disabled>-</option>';
                              foreach($user as $usr)
                              {
                                $content .= '<option value="'. $usr->ic .'" '. (($sbj->user_ic == $usr->ic) ? 'selected' : '') .'>'. $usr->name  .'</option>';
                              }
              $content .= '</select>
                      </div>
                </td>
                <td>
                    '. $sbj->no_staf .'
                </td>
                <td>
                    <div class="form-group">
                          <select class="form-select" id="lct-'. $sbj->id .'" name="lct-'. $sbj->id .'">
                              <option value=" " selected>-</option>';
                              foreach($user as $usr)
                              {
                                $content .= '<option value="'. $usr->ic .'" '. (($sbj->amali_ic == $usr->ic) ? 'selected' : '') .'>'. $usr->name .'</option>';
                              }
              $content .= '</select>
                      </div>
                </td>
                <td>
                    <p >'.$sbj->course_code.' - '.$sbj->course_name.'</p>
                </td>
                <td>
                    <p>'.$sbj->SessionName.'</p>
                </td>
                <td>
                    <a class="btn btn-info btn-sm" href="#" onclick="updateSubjek(\''. $sbj->id .'\')">
                        <i class="ti-save-alt">
                        </i>
                        Update
                    </a>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deleteSubjek(\''. $sbj->id .'\')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';    

            return $content;
    }

    public function deleteLecturerSubject(Request $request)
    {

        DB::table('student_subjek')->where('group_id', $request->id)
        ->update([
            'group_id' => null,
            'group_name' => null
        ]);

        DB::table('user_subjek')->where('id', $request->id)->delete();

        $data['subject'] = DB::table('user_subjek')->where('user_ic', $request->ic)
                           ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                           ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                           ->select('user_subjek.id', 'subjek.course_name', 'subjek.course_code', 'sessions.SessionName')
                           ->get();

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>Course</th>
            <th>Session</th>
            <th></th>
            </thead>
            <tbody>
        ';
        foreach($data['subject'] as $sbj){
            //$registered = ($sbj->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <p class="text-bold text-fade">'.$sbj->course_code.' - '.$sbj->course_name.'</p>
                </td>
                <td >
                    <p class="text-bold text-fade">'.$sbj->SessionName.'</p>
                </td>
                <td >
                    <a class="btn btn-danger btn-sm" href="#" onclick="deleteSubjek(\''. $sbj->id .'\')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';    

            return $content;

    }

    public function getCourse(Request $request)
    {
        $course = DB::table('subjek')->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                  ->groupBy('subjek_structure.program_id')
                  ->groupBy('subjek.id')
                  ->select('subjek.sub_id', 'subjek.course_name', 'subjek.course_code', 'subjek_structure.semester_id')
                  ->where('subjek_structure.program_id', $request->program)->get();

        $content = "";

        $content .= "<option value='-' disabled selected>-</option>";
        foreach($course as $crs){
            $content .= '<option value='. $crs->sub_id .'>
            '. $crs->course_code .' : '. $crs->course_name.'</option>';
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

    public function updateLecturer(Request $request)
    {
        if($request->main != '' && $request->main != DB::table('user_subjek')->where('id', $request->id)->first()->user_ic)
        {

            $old = DB::table('user_subjek')->where('id', $request->id)->first();

            // Get the course ID from subjek table
            $course = DB::table('subjek')
                       ->where('sub_id', $old->course_id)
                       ->first();

            if (!$course) {
                return response()->json(['message' => 'Course not found'], 404);
            }

            $id = $course->id;

            // Get the lecturer directory record
            $lecturer_dir = DB::table('lecturer_dir')
                         ->where([
                            'CourseID' => $id,
                            'SessionID' => $old->session_id,
                            'Addby' => $old->user_ic
                         ])
                         ->first();

            if($lecturer_dir)
            {
                // Update the lecturer_dir record
                DB::table('lecturer_dir')
                  ->where('id', $lecturer_dir->id)
                  ->update(['Addby' => $request->main]);

                // Get material_dir records before updating
                $material_dirs = DB::table('material_dir')
                     ->where([
                        'LecturerDirID' => $lecturer_dir->id,
                        'Addby' => $old->user_ic
                     ])
                     ->get();

                if($material_dirs->isNotEmpty())
                {
                    // Update material_dir records
                    DB::table('material_dir')
                     ->where([
                        'LecturerDirID' => $lecturer_dir->id,
                        'Addby' => $old->user_ic
                     ])
                     ->update(['Addby' => $request->main]);

                    // Process each material_dir record
                    foreach($material_dirs as $material_dir)
                    {
                        // Update materialsub_dir
                        DB::table('materialsub_dir')
                         ->where([
                            'MaterialDirID' => $material_dir->id,
                            'Addby' => $old->user_ic
                         ])
                         ->update(['Addby' => $request->main]);

                        // Update materialsub_url (direct MaterialDirID reference)
                        DB::table('materialsub_url')
                         ->where([
                            'MaterialDirID' => $material_dir->id,
                            'Addby' => $old->user_ic
                         ])
                         ->update(['Addby' => $request->main]);

                        // Get materialsub_dir records for this material_dir
                        $materialsub_dirs = DB::table('materialsub_dir')
                                          ->where([
                                            'MaterialDirID' => $material_dir->id,
                                            'Addby' => $request->main // Use new addby since we just updated it
                                          ])
                                          ->get();

                        // Update materialsub_url records that reference materialsub_dir
                        foreach($materialsub_dirs as $materialsub_dir)
                        {
                            DB::table('materialsub_url')
                            ->where([
                                'MaterialSubDirID' => $materialsub_dir->id,
                                'Addby' => $old->user_ic
                            ])
                            ->update(['Addby' => $request->main]);
                        }
                    }
                }
                     
            }

            //Change Quiz Lecturer

            DB::table('tblclassquiz')
                     ->where([
                        'classid' => $id,
                        'sessionid' => $old->session_id,
                        'addby' => $old->user_ic
                     ])
                     ->update([
                        'addby' => $request->main
                     ]);

            //Change Test Lecturer

            DB::table('tblclasstest')
                     ->where([
                        'classid' => $id,
                        'sessionid' => $old->session_id,
                        'addby' => $old->user_ic
                     ])
                     ->update([
                        'addby' => $request->main
                     ]);

            //Change Test2 Lecturer

            DB::table('tblclasstest2')
                     ->where([
                        'classid' => $id,
                        'sessionid' => $old->session_id,
                        'addby' => $old->user_ic
                     ])
                     ->update([
                        'addby' => $request->main
                     ]);

            //Change Assignment Lecturer

            DB::table('tblclassassign')
                     ->where([
                        'classid' => $id,
                        'sessionid' => $old->session_id,
                        'addby' => $old->user_ic
                     ])
                     ->update([
                        'addby' => $request->main
                     ]);

            //Change Midterm Lecturer

            DB::table('tblclassmidterm')
                     ->where([
                        'classid' => $id,
                        'sessionid' => $old->session_id,
                        'addby' => $old->user_ic
                     ])
                     ->update([
                        'addby' => $request->main
                     ]);

            //Change Final Lecturer

            DB::table('tblclassfinal')
                     ->where([
                        'classid' => $id,
                        'sessionid' => $old->session_id,
                        'addby' => $old->user_ic
                     ])
                     ->update([
                        'addby' => $request->main
                     ]);
                     
            //Change Practical Lecturer

            DB::table('tblclasspractical')
                     ->where([
                        'classid' => $id,
                        'sessionid' => $old->session_id,
                        'addby' => $old->user_ic
                     ])
                     ->update([
                        'addby' => $request->main
                     ]);
                     
            //Change Other Lecturer

            DB::table('tblclassother')
                     ->where([
                        'classid' => $id,
                        'sessionid' => $old->session_id,
                        'addby' => $old->user_ic
                     ])
                     ->update([
                        'addby' => $request->main
                     ]);  
                    
            //Change Extra Lecturer

            DB::table('tblclassextra')
                     ->where([
                        'classid' => $id,
                        'sessionid' => $old->session_id,
                        'addby' => $old->user_ic
                     ])
                     ->update([
                        'addby' => $request->main
                     ]);  


            //Change Forum Lecturer

            DB::table('tblforum_topic')
                     ->where([
                        'CourseID' => $id,
                        'SessionID' => $old->session_id,
                        'Addby' => $old->user_ic
                     ])
                     ->update([
                        'Addby' => $request->main
                     ]);


            //Change Event Lecturer

            DB::table('tblevents')
                     ->where([
                        'user_ic' => $old->user_ic,
                        'group_id' => $old->id,
                        'session_id' => $old->session_id
                     ])
                     ->update([
                        'user_ic' => $request->main
                     ]);

            DB::table('tblevents_second')
                     ->where([
                        'user_ic' => $old->user_ic,
                        'group_id' => $old->id,
                        'session_id' => $old->session_id
                     ])
                     ->update([
                        'user_ic' => $request->main
                     ]);

            DB::table('tblevents_log')
                     ->where([
                        'user_ic' => $old->user_ic,
                        'group_id' => $old->id,
                        'session_id' => $old->session_id
                     ])
                     ->update([
                        'user_ic' => $request->main
                     ]);
                     
            //Finally Change User Subjek Lecturer

            DB::table('user_subjek')->where('id', $request->id)->update([
                'user_ic' => $request->main
            ]);
        }

        if($request->ic != '')
        {

            DB::table('user_subjek')->where('id', $request->id)->update([
                'amali_ic' => $request->ic
            ]);

        }

        return response()->json(['message' => 'success']);

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
        if(isset($request->student2))
        {

            foreach ($request->student2 as $stud) {

                student::where('id', $stud)->update([
                    'group_id' => null,
                    'group_name' => null
                ]);      
    
            }

        }

        if(isset($request->student))
        {

            if(count($request->student) > 55)
            {

                return redirect()->back()->withErrors(['The limit for student in a group cannot exceed more than 55 !']);

            }else{

                foreach ($request->student as $stud) {
    
                    student::where('id', $stud)->update([
                        'group_id' => $request->lecturer,
                        'group_name' => $request->group
                    ]);      
        
                }

            }

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
                 ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                 ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
                 ->whereIn('subjek_structure.program_id', $programs)
                 ->where('user_subjek.user_ic', $lecturer->ic)->groupBy('student_subjek.group_id')->groupBy('student_subjek.group_name')
                 ->select('user_subjek.*','student_subjek.group_name','tblprogramme.progname','subjek.course_name','subjek.course_code','subjek.course_credit','subjek_structure.semester_id')->get();

        //dd($group);
        

        $sum = subject::where('user_ic', $lecturer->ic)
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                ->whereIn('subjek_structure.program_id', $programs)->sum('subjek.course_credit');

                //dd($group);

        $sumbyses = DB::table('sessions')->get();

        foreach($sumbyses as $semses)
        {
            $sums[] = subject::where('user_ic', $lecturer->ic)
                    ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                    ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                    ->whereIn('subjek_structure.program_id', $programs)
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

        if(Auth::user()->usrtype == 'AR')
        {

            $course = DB::table('subjek')
                  ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                  ->groupBy('subjek_structure.courseID')
                  ->select('subjek.*')->get();

        }else{

            $course = DB::table('subjek')
                  ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                  ->join('user_program', 'subjek_structure.program_id', 'user_program.program_id')
                  ->where('user_program.user_ic', $user->ic)
                  ->groupBy('subjek_structure.courseID')
                  ->select('subjek.*')->get();

        }

        //dd($course);

        return view('ketua_program.course_mark', compact('course'));
    }

    public function lecturerReportFile()
    {
        $user = Auth::user();

        //dd($user);

        $faculty = DB::table('tblfaculty')->where('id', $user->faculty)->get();

        $usrtype = ['LCT', 'KP'];

        foreach($faculty as $key => $fcl)
        {
            //$lecturer[] = DB::table('users')->where('status', 'ACTIVE')->where('faculty', $fcl->id)->whereIn('usrtype', $usrtype)->get();

            $lecturer[] = DB::table('users')->where('status', 'ACTIVE')->where('faculty', $fcl->id)->get();

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

        return view('ketua_program.report.lecturerReport', compact('faculty','lecturer','course'));

    }

    public function assessment2()
    {

        return view('ketua_program.report.assessment2');

    }

    public function getAssessment(Request $request)
    {

        $user = Auth::user();

        $data['quiz'] = DB::table('tblclassquiz')
        ->join('users', 'tblclassquiz.addby', 'users.ic')
        ->where('users.faculty', $user->faculty)
        ->whereBetween('tblclassquiz.created_at', [$request->from, $request->to])
        ->select('tblclassquiz.*', 'users.name')->get();

        $data['test'] = DB::table('tblclasstest')
        ->join('users', 'tblclasstest.addby', 'users.ic')
        ->where('users.faculty', $user->faculty)
        ->whereBetween('tblclasstest.created_at', [$request->from, $request->to])
        ->select('tblclasstest.*', 'users.name')->get();

        $data['assign'] = DB::table('tblclassassign')
        ->join('users', 'tblclassassign.addby', 'users.ic')
        ->where('users.faculty', $user->faculty)
        ->whereBetween('tblclassassign.created_at', [$request->from, $request->to])
        ->select('tblclassassign.*', 'users.name')->get();

        $data['other'] = DB::table('tblclassother')
        ->join('users', 'tblclassother.addby', 'users.ic')
        ->where('users.faculty', $user->faculty)
        ->whereBetween('tblclassother.created_at', [$request->from, $request->to])
        ->select('tblclassother.*', 'users.name')->get();

        $data['extra'] = DB::table('tblclassextra')
        ->join('users', 'tblclassextra.addby', 'users.ic')
        ->where('users.faculty', $user->faculty)
        ->whereBetween('tblclassextra.created_at', [$request->from, $request->to])
        ->select('tblclassextra.*', 'users.name')->get();

        $data['midterm'] = DB::table('tblclassmidterm')
        ->join('users', 'tblclassmidterm.addby', 'users.ic')
        ->where('users.faculty', $user->faculty)
        ->whereBetween('tblclassmidterm.created_at', [$request->from, $request->to])
        ->select('tblclassmidterm.*', 'users.name')->get();

        $data['final'] = DB::table('tblclassfinal')
        ->join('users', 'tblclassfinal.addby', 'users.ic')
        ->where('users.faculty', $user->faculty)
        ->whereBetween('tblclassfinal.created_at', [$request->from, $request->to])
        ->select('tblclassfinal.*', 'users.name')->get();

        return view('ketua_program.report.getAssessment', compact('data'));

    }

    public function meetingHour()
    {

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        return view('ketua_program.assign.subject.meetingHour', compact('data'));

    }

    public function getMeetingHour(Request $request)
    {

        $data['subject'] = DB::table('subjek_structure')
                           ->join('subjek', 'subjek_structure.courseID', 'subjek.sub_id')
                           ->where('subjek_structure.program_id', $request->id)
                           ->groupBy('courseID')
                           ->select('subjek_structure.courseID', 'subjek_structure.meeting_hour', 'subjek_structure.amali_hour', 'subjek.course_name', 'subjek.course_code')
                           ->get();

        return view('ketua_program.assign.subject.getMeetingHour', compact('data'));

    }

    public function submitMeetingHour(Request $request)
    {

        $m_ids = $request->input('m_id');
        $m_hours = $request->input('m_hour');
        $a_hours = $request->input('a_hour');

        if (is_array($m_ids) && is_array($m_hours) && is_array($a_hours)) {
            foreach ($m_ids as $index => $id) {
                DB::table('subjek_structure')
                ->where('courseID', $id)
                ->update([
                    'meeting_hour' => $m_hours[$index],
                    'amali_hour' => $a_hours[$index]
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Successfully updated subject\'s meeting hour!']);

    }
}
