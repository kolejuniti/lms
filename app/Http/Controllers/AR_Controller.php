<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\subject;
use App\Models\student;
use App\Models\Tblevent;
use App\Models\UserStudent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;

class AR_Controller extends Controller
{
    public function dashboard()
    {
        Session::put('User', Auth::user());

        return view('dashboard');
    }
    
    public function courseList()
    {

        $data = [
            'course' => DB::table('subjek')->leftjoin('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->select('subjek.*', 'tblprogramme.progname')->get(),
            'courselist' => DB::table('subjek')->groupBy('sub_id')->get(),
            'program' => DB::table('tblprogramme')->get()
        ];

        return view('pendaftar_akademik', compact('data'));

    }

    public function getCourse(Request $request)
    {
        //if(isset($request->session))
        //{
            //if(isset($request->group))
            //{
                //$students = student::where('courseid', $request->subject)->where('sessionid', $request->session)->where('group_id', $request->group)->get();
            //}else
            //{
                //$students = student::where('courseid', $request->subject)->where('sessionid', $request->session)->get();
            //}
        //}else
        //{
            //$students = student::where('courseid', $request->subject)->get();
        //}

        $course = DB::table('subjek')->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->where('prgid', $request->program)->select('subjek.*', 'tblprogramme.progname')->get();

        $content = "";
        $content .= '<thead>
                    <tr>
                        <th style="width: 1%">
                            No.
                        </th>
                        <th style="width: 20%">
                            Course Name
                        </th>
                        <th style="width: 5%">
                            Course Code
                        </th>
                        <th style="width: 5%">
                            Credit
                        </th>
                        <th style="width: 20%">
                        </th>
                    </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($course as $key => $crs){
            //$registered = ($crs->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 20%">
                '. $crs->course_name .'
                </td>
                <td style="width: 5%">
                '. $crs->course_code .'
                </td>
                <td style="width: 5%">
                '. $crs->course_credit .'
                </td>
                <td class="project-actions text-right" style="text-align: center;">
                <a class="btn btn-info btn-sm btn-sm mr-2" href="#">
                    <i class="ti-pencil-alt">
                    </i>
                    Edit
                </a>
                <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial(\''. $crs->id .'\')">
                    <i class="ti-trash">
                    </i>
                    Delete
                </a>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody>';

            return $content;

    }

    public function createCourse(Request $request)
    {
        $data = $request->validate([
            'id' => [],
            'name' => [],
            'code' => ['string'],
            'credit' => [],
            'program2' => ['required'],
            'semester' => ['required']
        ]);

        //dd($request->upt);


        if(isset($request->idS))
        {

            DB::table('subjek')->where('id', $request->idS)->update([
                'sub_id' => $data['id'],
                'course_name' => $data['name'],
                'course_code' => $data['code'],
                'course_credit' => $data['credit'],
                'prgid' => $data['program2'],
                'semesterid' => $data['semester']
            ]);

        }elseif(isset($request->upt))
        {

            $course = DB::table('subjek')->where('id', $request->course)->first();

            // Check if program2 is a string, and if so, convert it to an array
            if (is_string($data['program2'])) {
                $data['program2'] = [$data['program2']];
            }

            // Do something with the selected programs
            foreach ($data['program2'] as $programId) {

                if(DB::table('subjek')->where([
                    ['sub_id', $course->sub_id],
                    ['prgid', $programId],
                    ['semesterid', $data['semester']]
                    ])->exists())
                {

                }else{

                    DB::table('subjek')->insert([
                        'sub_id' => $course->sub_id,
                        'course_name' => $course->course_name,
                        'course_code' => $course->course_code,
                        'course_credit' => $course->course_credit,
                        'prgid' => $programId,
                        'semesterid' => $data['semester']
                    ]);

                }
            }


        }else
        {

            $recentId = DB::table('subjek')->latest()->value('sub_id');

            $addID = $recentId + 1;

            // Check if program2 is a string, and if so, convert it to an array
            if (is_string($data['program2'])) {
                $data['program2'] = [$data['program2']];
            }

            // Do something with the selected programs
            foreach ($data['program2'] as $programId) {

                DB::table('subjek')->insert([
                    'sub_id' => $addID,
                    'course_name' => $data['name'],
                    'course_code' => $data['code'],
                    'course_credit' => $data['credit'],
                    'prgid' => $programId,
                    'semesterid' => $data['semester']
                ]);

            }
        }

        return redirect(route('pendaftar_akademik'));

    }

    public function deleteCourse(Request $request)
    {

        DB::table('subjek')->where('id', $request->id)->delete();

        return true;

    }

    public function updateCourse(Request $request)
    {

        $data = [
            'course' => DB::table('subjek')->leftjoin('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->select('subjek.*', 'tblprogramme.progname')->where('subjek.id', $request->id)->first(),
            'program' => DB::table('tblprogramme')->get()
        ];

        return view('pendaftar_akademik.getCourse', compact('data'))->with('id', $request->id);

    }

    public function assignCourse()
    {

        $data = [

            // 'course' => DB::table('subjek')->get(),
            'program' => DB::table('tblprogramme')->get(),
            'structure' => DB::table('structure')->get(),
            'intake' => DB::table('sessions')->get(),
            // 'assigned' => DB::table('subjek_structure')
            //             ->join('subjek', function($join)
            //             {
            //                 $join->on('subjek_structure.courseID', 'subjek.sub_id');
            //                 $join->on('subjek_structure.program_id', 'subjek.prgid');
            //                 $join->on('subjek_structure.semester_id', 'subjek.semesterid');
            //             })
            //             ->leftjoin('structure', 'subjek_structure.structure', 'structure.id')
            //             ->leftjoin('sessions', 'subjek_structure.intake_id', 'sessions.SessionID')
            //             ->leftjoin('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            //             ->select('subjek_structure.id', 'subjek.course_name', 'subjek.course_code','structure.structure_name', 'sessions.SessionName', 'tblprogramme.progname')->get()

        ];

        return view('pendaftar_akademik.course.assignSubject', compact('data'));

    }

    public function getCourse0(Request $request)
    {
        $course = DB::table('subjek')->where('prgid', $request->program)->get();

        $content = "";

        $content .= "<option value='0' disabled>-</option>";
        foreach($course as $crs){

            $content .= "<option value='". $crs->id ."'><strong>". $crs->course_name . "</strong> (" . $crs->course_code . ")</option>"; 

        }
        
        return $content;

    }

    public function getCourse2(Request $request)
    {
        //if(isset($request->session))
        //{
            //if(isset($request->group))
            //{
                //$students = student::where('courseid', $request->subject)->where('sessionid', $request->session)->where('group_id', $request->group)->get();
            //}else
            //{
                //$students = student::where('courseid', $request->subject)->where('sessionid', $request->session)->get();
            //}
        //}else
        //{
            //$students = student::where('courseid', $request->subject)->get();
        //}

        if(isset($request->course))
        {

            $course = DB::table('subjek_structure')
                    ->join('subjek', function($join)
                    {
                        $join->on('subjek_structure.courseID', 'subjek.sub_id');
                        $join->on('subjek_structure.program_id', 'subjek.prgid');
                        $join->on('subjek_structure.semester_id', 'subjek.semesterid');
                    })
                    ->leftjoin('structure', 'subjek_structure.structure', 'structure.id')
                    ->leftjoin('sessions', 'subjek_structure.intake_id', 'sessions.SessionID')
                    ->leftjoin('tblprogramme', 'subjek.prgid', 'tblprogramme.id');

            if(isset($request->course))
            {

                $courses = $course->whereIn('subjek.id', $request->course);

            }

            if(isset($request->structure))
            {

                $courses = $course->where('structure.id', $request->structure);

            }

            if(isset($request->intake))
            {

                $courses = $course->whereIn('sessions.SessionID', $request->intake);

            }

            $data['course'] = $courses->select('subjek_structure.id', 'subjek.course_name', 'subjek.course_code','structure.structure_name', 'sessions.SessionName', 'tblprogramme.progname')->get();

            // $ids = DB::table('subjek')->where('id', $request->course)->first();

            // $course = DB::table('subjek')->leftjoin('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            //           ->where([
            //             ['subjek.sub_id', $ids->sub_id],
            //             ['subjek.prgid', '!=', null]
            //           ])->select('subjek.*', 'tblprogramme.progname')->get();

            $content = "";
            $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 20%">
                                Course Name
                            </th>
                            <th style="width: 5%">
                                Course Code
                            </th>
                            <th style="width: 5%">
                                Structure
                            </th>
                            <th style="width: 5%">
                                Program
                            </th>
                            <th style="width: 5%">
                                Semester
                            </th>
                            <th style="width: 20%">
                            </th>
                        </tr>
                        </thead>
                        <tbody id="table">';
                        
            foreach($data['course'] as $key => $crs){
                //$registered = ($crs->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td style="width: 1%">
                    '. $key+1 .'
                    </td>
                    <td style="width: 20%">
                    '. $crs->course_name .'
                    </td>
                    <td style="width: 5%">
                    '. $crs->course_code .'
                    </td>
                    <td style="width: 5%">
                    '. $crs->structure_name .'
                    </td>
                    <td style="width: 5%">
                    '. $crs->progname .'
                    </td>
                    <td style="width: 5%">
                    '. $crs->SessionName .'
                    </td>
                    <td class="project-actions text-right" style="text-align: center;">
                    <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial(\''. $crs->id .'\')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                    </td>
                </tr>
                ';
                }
                $content .= '</tbody>';

                return $content;

        }else{


            return response()->json(['error' => 'Please select subject first!']);

        }

    }

    public function addCourse(Request $request)
    {

        $data = json_decode($request->addCourse);

        if (isset($data->course) && is_array($data->course) && count($data->course) > 0 
            && isset($data->intake) && is_array($data->intake) && count($data->intake) > 0
            && isset($data->structure)) {
            // The 'program' input is not empty, process the data
            $selectedCourse = $data->course;

            $selectedIntake = $data->intake;

            // $course = DB::table('subjek')->where('id', $data->course)->first();
            
            // Do something with the selected programs
            foreach($selectedCourse as $courseID) {
                // Process each selected Course

                $course = DB::table('subjek')->where('id', $courseID)->first();

                foreach($selectedIntake as $intakeID)
                {

                    if(DB::table('subjek_structure')->where([['courseID', $course->sub_id], ['structure', $data->structure], ['intake_id', $intakeID], ['program_id', $course->prgid], ['semester_id', $course->semesterid]])->exists())
                    {


                    }else{

                        DB::table('subjek_structure')->insert([
                            'courseID' => $course->sub_id,
                            'structure' => $data->structure,
                            'intake_id' => $intakeID,
                            'program_id' => $course->prgid,
                            'semester_id' => $course->semesterid
                        ]);

                    }
                }
            }

            $datas = DB::table('subjek_structure')
            ->join('subjek', function($join)
            {
                $join->on('subjek_structure.courseID', 'subjek.sub_id');
                $join->on('subjek_structure.program_id', 'subjek.prgid');
                $join->on('subjek_structure.semester_id', 'subjek.semesterid');
            })
            ->leftjoin('structure', 'subjek_structure.structure', 'structure.id')
            ->leftjoin('sessions', 'subjek_structure.intake_id', 'sessions.SessionID')
            ->leftjoin('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->whereIn('subjek.id', $data->course)
            ->where('structure.id', $data->structure)
            ->whereIn('sessions.SessionID', $data->intake)
            ->select('subjek_structure.id', 'subjek.course_name', 'subjek.course_code','structure.structure_name', 'sessions.SessionName', 'tblprogramme.progname')->get();


            return response()->json(['message' => 'Success', 'data' => $datas]);

        } else {
            // The 'program' input is empty
            // Handle the case when no program is selected

            return response()->json(['message' => 'Please fills in required details']);

        }

    }

    public function deleteCourse2(Request $request)
    {

        DB::table('subjek_structure')->where('id', $request->id)->delete();

        return true;

    }

    public function studentCourse()
    {

        $data = [

            'programs' => DB::table('tblprogramme')->get(),
            'sessions' => DB::table('sessions')->get()
        ];

        return view('pendaftar_akademik.studentCourse', compact('data'));

    }

    public function getStudents(Request $request)
    {
        if($request->program == null)
        {
            $students = DB::table('students')->where('session', $request->session)->get();
        }elseif($request->session == null){
            $students = DB::table('students')->where('program', $request->program)->get();
        }else{
            $students = DB::table('students')->where([
                ['session', $request->session],
                ['program', $request->program]
                ])->get();
        }

        $content = "";

        $content .= "<option value='-' disabled selected>-</option>";
        foreach($students as $std){

            $content .= '<option data-style="btn-inverse"  
            data-content=\'<div class="row" >
                <div class="col-md-2">
                <div class="d-flex justify-content-center">
                    <img src="" 
                        height="auto" width="70%" class="bg-light ms-0 me-2 rounded-circle">
                        </div>
                </div>
                <div class="col-md-10 align-self-center lh-lg">
                    <span><strong>'. $std->name .'</strong></span><br>
                    <span>'. $std->email .' | <strong class="text-fade"">Semester '.$std->semester .'</strong></span><br>
                    <span class="text-fade"></span>
                </div>
            </div>\' value='. $std->ic .'></option>';
        }
        
        return $content;

    }

    public function getCourses(Request $request)
    {
        $data['student'] = UserStudent::where('ic', $request->student)->first();

        for($i = 0; $i <= $data['student']->semester; $i++)
        {
            $loop[] = $i;
        }

        $data['students'] = DB::table('students')
        ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
        ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
        ->where('ic', $request->student)->first();

        $getCourse =  DB::table('student_subjek')
                      ->join('students', 'student_subjek.student_ic', 'students.ic')
                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                      ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                    //   ->where('student_subjek.sessionid', $data['student']->session)
                      ->where('students.ic', $data['student']->ic)
                      ->where('subjek.prgid', $data['student']->program);

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS', 'student_subjek.courseid', 'student_subjek.semesterid AS semester', 'sessions.SessionName', 'subjek.*')->orderBy('student_subjek.semesterid')->get();

        $crsExists = $getCourse->where('student_subjek.course_status_id', '!=', 2)->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)
                             ->join('subjek_structure', function($join){
                                $join->on('subjek.sub_id', 'subjek_structure.courseID');
                                $join->on('subjek.prgid', 'subjek_structure.program_id');
                                $join->on('subjek.semesterid', 'subjek_structure.semester_id');
                             })
                             ->where('subjek_structure.intake_id', $data['student']->intake)
                             ->whereIn('subjek.semesterid', $loop)
                             ->where('prgid', $data['student']->program)
                             ->orderBy('subjek.semesterid')
                             ->select('subjek.*')->get();

        return view('pendaftar_akademik.getAllCourse', compact('data'));
    }

    public function registerCourse(Request $request)
    {

        $data['student'] = UserStudent::where('ic', $request->ic)->first();

        $course = DB::table('subjek')->where('id', $request->id)->first();

        if($course->prerequisite_id == 881)
        {
  
            DB::table('student_subjek')->insert([
                'student_ic' => $data['student']->ic,
                'courseid' => $course->sub_id,
                'sessionid' => $data['student']->session,
                'semesterid' => $data['student']->semester,
                'course_status_id' => 15,
                'status' => 'ACTIVE'
            ]);

            if($data['student']->student_status == 1)
            {

                DB::table('students')->update([
                    'student_status' => 2
                ]);

            }

        }else{

            $check = DB::table('student_subjek')->where('courseid', $course->prerequisite_id)->value('course_status_id');

            if(isset($check) && $check != 2)
            {

                DB::table('student_subjek')->insert([
                    'student_ic' => $data['student']->ic,
                    'courseid' => $course->sub_id,
                    'sessionid' => $data['student']->session,
                    'semesterid' => $data['student']->semester,
                    'course_status_id' => 15,
                    'status' => 'ACTIVE'
                ]);
    
                if($data['student']->student_status == 1)
                {
    
                    DB::table('students')->update([
                        'student_status' => 2
                    ]);
    
                }

            }else{

                $data['error'] = 'Subject from previous semester status FAILED';

            }

        }


        for($i = 0; $i <= $data['student']->semester; $i++)
        {
            $loop[] = $i;
        }

        $data['students'] = DB::table('students')
        ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
        ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
        ->where('ic', $request->ic)->first();

        $getCourse =  DB::table('student_subjek')
                      ->join('students', 'student_subjek.student_ic', 'students.ic')
                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                      ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                    //   ->where('student_subjek.sessionid', $data['student']->session)
                      ->where('students.ic', $data['student']->ic)
                      ->where('subjek.prgid', $data['student']->program);

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS', 'student_subjek.courseid', 'student_subjek.semesterid AS semester', 'sessions.SessionName', 'subjek.*')->orderBy('student_subjek.semesterid')->get();

        $crsExists = $getCourse->where('student_subjek.course_status_id', '!=', 2)->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)
                             ->join('subjek_structure', function($join){
                                $join->on('subjek.sub_id', 'subjek_structure.courseID');
                                $join->on('subjek.prgid', 'subjek_structure.program_id');
                                $join->on('subjek.semesterid', 'subjek_structure.semester_id');
                             })
                             ->where('subjek_structure.intake_id', $data['student']->intake)
                             ->whereIn('subjek.semesterid', $loop)
                             ->where('prgid', $data['student']->program)
                             ->orderBy('subjek.semesterid')
                             ->select('subjek.*')->get();

        return view('pendaftar_akademik.getAllCourse', compact('data'));

    }


    public function unregisterCourse(Request $request)
    {
        $data['student'] = UserStudent::where('ic', $request->ic)->first();

        DB::table('student_subjek')->where('id', $request->id)->delete();

        for($i = 0; $i <= $data['student']->semester; $i++)
        {
            $loop[] = $i;
        }

        $data['students'] = DB::table('students')
        ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
        ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
        ->where('ic', $request->ic)->first();

        $getCourse =  DB::table('student_subjek')
                      ->join('students', 'student_subjek.student_ic', 'students.ic')
                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                      ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                    //   ->where('student_subjek.sessionid', $data['student']->session)
                      ->where('students.ic', $data['student']->ic)
                      ->where('subjek.prgid', $data['student']->program);

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS', 'student_subjek.courseid', 'student_subjek.semesterid AS semester', 'sessions.SessionName', 'subjek.*')->orderBy('student_subjek.semesterid')->get();

        $crsExists = $getCourse->where('student_subjek.course_status_id', '!=', 2)->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)
                             ->join('subjek_structure', function($join){
                                $join->on('subjek.sub_id', 'subjek_structure.courseID');
                                $join->on('subjek.prgid', 'subjek_structure.program_id');
                                $join->on('subjek.semesterid', 'subjek_structure.semester_id');
                             })
                             ->where('subjek_structure.intake_id', $data['student']->intake)
                             ->whereIn('subjek.semesterid', $loop)
                             ->where('prgid', $data['student']->program)
                             ->orderBy('subjek.semesterid')
                             ->select('subjek.*')->get();

        return view('pendaftar_akademik.getAllCourse', compact('data'));

    }

    public function sessionList()
    {
        $data = [
            'session' => DB::table('sessions')->get(),
            'year' => DB::table('tblyear')->get()
        ];

        return view('pendaftar_akademik.session', compact('data'));

    }

    public function createSession(Request $request)
    {
        $data = $request->validate([
            'year' => ['required'],
            'month' => ['required'],
            'start' => ['required'],
            'end' => ['required']
        ]);

        $start = $this->getYear($data['start']);
        $end = $this->getYear($data['end']);

        $name = $data['month'] . ' ' . $start . '/' . $end;

        //dd($name);

        if(isset($request->idS))
        {

            DB::table('sessions')->where('SessionID', $request->idS)->update([
                'SessionName' => $name,
                'Start' => $data['start'],
                'End' => $data['end'],
                'Year' => $data['year'],
                'Status' => $request->status
                
            ]);

        }else{

            DB::table('sessions')->insert([
                'SessionName' => $name,
                'Start' => $data['start'],
                'End' => $data['end'],
                'Year' => $data['year'],
                'Status' => 'ACTIVE'
            ]);
        }

        return redirect(route('pendaftar_akademik.session'));

    }

    private function getYear($date)
    {

        $string = substr($date, 0, 4);

        return $string;
    }

    public function updateSession(Request $request)
    {

        $data = [
            'course' => DB::table('sessions')->where('SessionID', $request->id)->first(),
            'year' => DB::table('tblyear')->get()
        ];

        return view('pendaftar_akademik.getSession', compact('data'))->with('id', $request->id);

    }

    public function deleteDelete(Request $request)
    {

        DB::table('sessions')->where('SessionID', $request->id)->delete();

        return true;

    }

    public function scheduleIndex()
    {

        $path = "classschedule/";

        $files  = Storage::disk('linode')->allFiles($path);

        return view('pendaftar_akademik.schedule.schedule', compact('files'));

    }

    public function dropzoneStore(Request $request)
    {

        $file = $request->file('file');

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;

        //dd($file_name);

        $classmaterial = "classschedule/";
        

        if(! file_exists($newname)){
            Storage::disk('linode')->putFileAs(
                $classmaterial,
                $file,
                $newname,
                'public'
              );

              return response()->json(['success' => $imageName]);
        }


    }

    public function studentLeave()
    {
        $data['programs'] = DB::table('tblprogramme')->get();

        $data['sessions'] = DB::table('sessions')->get();

        $data['semesters'] = DB::table('semester')->get();

        return view('pendaftar_akademik.leave.studentLeave', compact('data'));

    }

    public function getStudentLeave(Request $request)
    {

        $program = $request->program;
        $session = $request->session;
        $semester = $request->semester;

        $query = DB::table('students')->where(function($query) {
            $query->where('campus_id', 1)
                  ->orWhereNull('campus_id');
        });

        $query2 = DB::table('students')->where('campus_id', 0);

        if($program != '' && $session != '' && $semester != '')
        {

            $data['campus'] = $query->where([
                ['program', $program],
                ['session', $session],
                ['semester', $semester]
            ])->get();

            $data['leave'] = $query2->where([
                ['program', $program],
                ['session', $session],
                ['semester', $semester]
            ])->get();

        }elseif($program != '' && $session != '')
        {

            $data['campus'] = $query->where([
                ['program', $program],
                ['session', $session]
            ])->get();

            $data['leave'] = $query2->where([
                ['program', $program],
                ['session', $session]
            ])->get();

        }elseif($program != '' && $semester != '')
        {

            $data['campus'] = $query->where([
                ['program', $program],
                ['semester', $semester]
            ])->get();

            $data['leave'] = $query2->where([
                ['program', $program],
                ['semester', $semester]
            ])->get();

        }elseif($session != '' && $semester != '')
        {

            $data['campus'] = $query->where([
                ['session', $session],
                ['semester', $semester]
            ])->get();

            $data['leave'] = $query2->where([
                ['session', $session],
                ['semester', $semester]
            ])->get();

        }elseif($program != '')
        {

            $data['campus'] = $query->where([
                ['program', $program]
            ])->get();

            $data['leave'] = $query2->where([
                ['program', $program]
            ])->get();

        }elseif($session != '')
        {

            $data['campus'] = $query->where([
                ['session', $session]
            ])->get();

            $data['leave'] = $query2->where([
                ['session', $session]
            ])->get();

        }elseif($semester != '')
        {

            $data['campus'] = $query->where([
                ['semester', $semester]
            ])->get();

            $data['leave'] = $query2->where([
                ['semester', $semester]
            ])->get();

        }


        return view('pendaftar_akademik.leave.getStudents', compact('data'));
    }

    public function updateLeave(Request $request)
    {

        DB::table('students')->whereIn('no_matric', $request->leave)->update([
            'campus_id' => 0,
        ]);

        foreach($request->leave AS $matric)
        {
            $student = UserStudent::where('no_matric', $matric)->first();


            DB::table('tblstudent_log')->insert([
                'student_ic' => $student->ic,
                'session_id' => $student->session,
                'semester_id' => $student->semester,
                'status_id' => $student->status,
                'kuliah_id' => 0,
                'date' => date("Y-m-d H:i:s"),
                'remark' => null,
                'add_staffID' => Auth::user()->ic
            ]);

        }
        
        return ['message' => 'success'];

    }

    public function updateCampus(Request $request)
    {

        DB::table('students')->whereIn('no_matric', $request->campus)->update([
            'campus_id' => 1,
        ]);

        foreach($request->campus AS $campus)
        {
            $student = UserStudent::where('no_matric', $campus)->first();


            DB::table('tblstudent_log')->insert([
                'student_ic' => $student->ic,
                'session_id' => $student->session,
                'semester_id' => $student->semester,
                'status_id' => $student->status,
                'kuliah_id' => 1,
                'date' => date("Y-m-d H:i:s"),
                'remark' => null,
                'add_staffID' => Auth::user()->ic
            ]);

        }

        return ['message' => 'success'];

    }

    public function studentSemester()
    {

        return view('pendaftar_akademik.semester.semester');

    }

    public function getStudentSemester(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['session'] = DB::table('sessions')->get();

        $data['semester'] = DB::table('semester')->get();

        return view('pendaftar_akademik.semester.semesterGetStudent', compact('data'));
        
    }

    public function updateSemester(Request $request)
    {

        $student = DB::table('students')->where('no_matric', $request->no_matric)->first();

        if($request->session != '' && $request->session != $student->session)
        {

            DB::table('students')->where('no_matric', $request->no_matric)->update([
                'session' => $request->session,
                'semester' => $student->semester + 1
            ]);

            $userUpt = UserStudent::where('no_matric', $request->no_matric)->first();

            DB::table('tblstudent_log')->insert([
                'student_ic' => $userUpt->ic,
                'session_id' => $userUpt->session,
                'semester_id' => $userUpt->semester,
                'status_id' => $userUpt->status,
                'kuliah_id' => $userUpt->campus_id,
                'date' => date("Y-m-d H:i:s"),
                'remark' => null,
                'add_staffID' => Auth::user()->ic
            ]);

        }else{

            return ['message' => 'Please fill all required field and cannot be the same semester!'];

        }

        return ['message' => 'Success'];

    }

    public function scheduleDrop()
    {

        return view('pendaftar_akademik.schedule.schedule');

    }

    public function fetchEvents()
    {
        $events = Tblevent::all();

        $formattedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'startTime' => date('H:i:s', strtotime($event->start)),
                'endTime' => date('H:i:s', strtotime($event->end)),
                'duration' => gmdate('H:i:s', strtotime($event->end) - strtotime($event->start)),
                'daysOfWeek' => [date('N', strtotime($event->start))] // Recurring on the same day of the week
            ];
        });

        return response()->json($formattedEvents);
    }


    public function createEvent(Request $request)
    {
        $event = new Tblevent;
        $event->title = $request->title;
        $event->start = $request->start;
        $event->end = $request->end;
        $event->save();

        // $id = DB::table('tblevents')->insertGetId([
        //     'title' => $request->title,
        //     'start' => $request->start,
        //     'end' => $request->end
        // ]);

        // $event = DB::table('tblevents')->where('id', $id);

        return response()->json([
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end
            ]
        ]);
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Tblevent::find($id);
        if ($event) {
            $event->start = $request->start;
            $event->end = $request->end;
            $event->save();

            return response()->json(['message' => 'Event updated successfully']);
        } else {
            return response()->json(['message' => 'Event not found'], 404);
        }
    }

    public function updateEvent2(Request $request, $id)
    {
        $event = Tblevent::find($id);

        if ($event) {
            $event->title = $request->input('title');
            $event->start = $request->start;
            $event->end = $request->end;
            $event->save();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error'], 404);
        }
    }

    public function deleteEvent($id)
    {
        $event = Tblevent::find($id);

        if ($event) {
            $event->delete();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error'], 404);
        }
    }

    public function studentReportR()
    {

        $data['session'] = DB::table('sessions')->get();

        return view('pendaftar_akademik.reportR.reportR', compact('data'));

    }

    public function getStudentReportR(Request $request)
    {

        if($request->from && $request->to && $request->session)
        {

            $data['student'] = DB::table('students')
                               ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                               ->leftjoin('sessions', 'students.intake', 'sessions.SessionID')
                               ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                               ->leftjoin('tbledu_advisor', 'tblstudent_personal.advisor_id', 'tbledu_advisor.id')
                               ->where('students.status', 1)
                               ->where('students.semester', 1)
                               ->where('students.intake', $request->session)
                               ->whereBetween('students.date_add', [$request->from, $request->to])
                               ->select('students.*', 'tblstudent_personal.no_tel', 'sessions.SessionName', 'tblprogramme.progcode', 'tbledu_advisor.name AS ea')
                               ->get();

            foreach($data['student'] as $key => $student)
            {

            $data['result'][] = DB::table('tblpayment')
                              ->leftjoin('tblpaymentdtl', 'tblpayment.id', 'tblpaymentdtl.payment_id')
                              ->where('tblpayment.student_ic', $student->ic)
                              ->select('tblpayment.amount', 'tblpayment.sponsor_id')->get();

            }

            return view('pendaftar_akademik.reportR.getReportR', compact('data'));

        }

    }

    
}
