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
use Illuminate\Support\Facades\Log;

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
            // 'course' => DB::table('subjek')->leftjoin('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->select('subjek.*', 'tblprogramme.progname')->get(),
            'course' => DB::table('subjek')
            ->leftjoin('subjek AS b', 'subjek.prerequisite_id', 'b.sub_id')
            ->leftjoin('tblcourse_level', 'subjek.course_level_id', 'tblcourse_level.id')
            ->select('subjek.*', 'b.course_name AS prerequisite', 'tblcourse_level.name AS course_level_id')
            ->get(),
            'courselist' => DB::table('subjek')->groupBy('sub_id')->get(),
            'program' => DB::table('tblprogramme')->get(),
            'level' => DB::table('tblcourse_level')->get()
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

        $course = DB::table('subjek')
                  ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                  ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
                  ->where('program_id', $request->program)->select('subjek.*', 'tblprogramme.progname')->get();

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
            'name' => ['required'],
            'code' => ['required'],
            'credit' => ['required'],
            'prerequisite' => ['required'],
            'clid' => ['required'],
            'offer' => ['required']
        ]);

        //dd($request->upt);


        if(isset($request->idS))
        {

            DB::table('subjek')->where('id', $request->idS)->update([
                'course_name' => $data['name'],
                'course_code' => $data['code'],
                'course_credit' => $data['credit'],
                'prerequisite_id' => $data['prerequisite'],
                'course_level_id' => $data['clid'],
                'offer' => $data['offer']
            ]);

        }else
        {

            $recentId = DB::table('subjek')->orderBy('sub_id', 'desc')->value('sub_id');

            $addID = $recentId + 1;

            DB::table('subjek')->insert([
                'sub_id' => $addID,
                'course_name' => $data['name'],
                'course_code' => $data['code'],
                'course_credit' => $data['credit'],
                'prerequisite_id' => $data['prerequisite'],
                'course_level_id' => $data['clid'],
                'offer' => $data['offer']
            ]);

            // // Check if program2 is a string, and if so, convert it to an array
            // if (is_string($data['program2'])) {
            //     $data['program2'] = [$data['program2']];
            // }

            // // Do something with the selected programs
            // foreach ($data['program2'] as $programId) {

            //     DB::table('subjek')->insert([
            //         'sub_id' => $addID,
            //         'course_name' => $data['name'],
            //         'course_code' => $data['code'],
            //         'course_credit' => $data['credit'],
            //         'prgid' => $programId,
            //         'semesterid' => $data['semester']
            //     ]);

            // }
        }
        
        // elseif(isset($request->upt))
        // {

        //     $course = DB::table('subjek')->where('id', $request->course)->first();

        //     // Check if program2 is a string, and if so, convert it to an array
        //     if (is_string($data['program2'])) {
        //         $data['program2'] = [$data['program2']];
        //     }

        //     // Do something with the selected programs
        //     foreach ($data['program2'] as $programId) {

        //         if(DB::table('subjek')->where([
        //             ['sub_id', $course->sub_id],
        //             ['prgid', $programId],
        //             ['semesterid', $data['semester']]
        //             ])->exists())
        //         {

        //         }else{

        //             DB::table('subjek')->insert([
        //                 'sub_id' => $course->sub_id,
        //                 'course_name' => $course->course_name,
        //                 'course_code' => $course->course_code,
        //                 'course_credit' => $course->course_credit,
        //                 'prgid' => $programId,
        //                 'semesterid' => $data['semester']
        //             ]);

        //         }
        //     }


        // }

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
            'course' => DB::table('subjek')
            ->leftjoin('subjek AS b', 'subjek.prerequisite_id', 'b.sub_id')
            ->select('subjek.*', 'b.course_name AS prerequisite')
            ->where('subjek.id', $request->id)
            ->first(),
            'courselist' => DB::table('subjek')->groupBy('sub_id')->get(),
            'level' => DB::table('tblcourse_level')->get()
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
            'semester' => DB::table('semester')->get(),
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
        $course = DB::table('subjek')->get();

        $content = "";

        $content .= "<option value='0' disabled>-</option>";
        foreach($course as $crs){

            $content .= "<option value='". $crs->id ."'><strong>". $crs->course_code ." - ". $crs->course_name . "</strong></option>"; 

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
                    })
                    ->leftjoin('structure', 'subjek_structure.structure', 'structure.id')
                    ->leftjoin('sessions', 'subjek_structure.intake_id', 'sessions.SessionID')
                    ->leftjoin('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
                    ->where('subjek_structure.program_id', $request->program);

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

            $data['course'] = $courses->select('subjek_structure.id', 'subjek.course_name', 'subjek.course_code','structure.structure_name', 'subjek_structure.semester_id','sessions.SessionName', 'tblprogramme.progname')->get();

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
                                Session
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
                    <td style="width: 5%">
                    '. $crs->semester_id .'
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
            && isset($data->structure) && isset($data->semester) && isset($data->program)) {
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

                    if(DB::table('subjek_structure')->where([['courseID', $course->sub_id], ['structure', $data->structure], ['intake_id', $intakeID], ['program_id', $data->program], ['semester_id', $data->semester]])->exists())
                    {


                    }else{

                        DB::table('subjek_structure')->insert([
                            'courseID' => $course->sub_id,
                            'structure' => $data->structure,
                            'intake_id' => $intakeID,
                            'program_id' => $data->program,
                            'semester_id' => $data->semester
                        ]);

                    }
                }
            }

            $datas = DB::table('subjek_structure')
            ->join('subjek', function($join)
            {
                $join->on('subjek_structure.courseID', 'subjek.sub_id');
            })
            ->leftjoin('structure', 'subjek_structure.structure', 'structure.id')
            ->leftjoin('sessions', 'subjek_structure.intake_id', 'sessions.SessionID')
            ->leftjoin('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->whereIn('subjek.id', $data->course)
            ->where('structure.id', $data->structure)
            ->whereIn('sessions.SessionID', $data->intake)
            ->select('subjek_structure.id', 'subjek.course_name', 'subjek.course_code','structure.structure_name', 'subjek_structure.semester_id', 'sessions.SessionName', 'tblprogramme.progname')->get();


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

        $data['course'] = DB::table('subjek')
                          ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                          ->where('subjek_structure.program_id', $data['student']->program)
                          ->groupBy('subjek.sub_id')
                          ->select('subjek.*')
                          ->get();

        for($i = 0; $i <= $data['student']->semester; $i++)
        {
            $loop[] = $i;
        }

        $data['students'] = DB::table('students')
        ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
        ->join('sessions AS a', 'students.intake', 'a.SessionID')
        ->join('sessions AS b', 'students.session', 'b.SessionID')
        ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'a.SessionName AS intake', 'b.SessionName AS session')
        ->where('ic', $request->student)->first();

        $getCourse =  DB::table('student_subjek')
                      ->join('students', 'student_subjek.student_ic', 'students.ic')
                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                      ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                      //->where('student_subjek.sessionid', $data['student']->session)
                      ->where('students.ic', $data['student']->ic)
                      ->groupBy('student_subjek.courseid')
                      ->groupBy('student_subjek.semesterid');

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS', 'student_subjek.courseid', 'student_subjek.semesterid AS semester', 'sessions.SessionName', 'subjek.*')->orderBy('student_subjek.semesterid', 'ASC')->get();

        $crsExists = $getCourse->where('student_subjek.course_status_id', '!=', 2)->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)
                             ->join('subjek_structure', function($join){
                                $join->on('subjek.sub_id', 'subjek_structure.courseID');
                             })
                             ->whereIn('subjek_structure.semester_id', $loop)
                             ->where([
                                ['subjek_structure.program_id', $data['student']->program],
                                ['subjek_structure.intake_id', $data['student']->intake],
                                ['subjek_structure.semester_id', '<=', $data['student']->semester]
                             ])
                             ->orderBy('subjek_structure.semester_id')
                             ->select('subjek.*', 'subjek_structure.semester_id AS semesterid')->get();

        return view('pendaftar_akademik.getAllCourse', compact('data'));
    }

    public function registerCourse(Request $request)
    {

        $data['student'] = UserStudent::where('ic', $request->ic)->first();

        $data['course'] = DB::table('subjek')
                          ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                          ->where('subjek_structure.program_id', $data['student']->program)
                          ->groupBy('subjek.sub_id')
                          ->select('subjek.*')
                          ->get();

        $course = DB::table('subjek')->where('id', $request->id)->first();

        if($course->prerequisite_id == 881)
        {
  
            DB::table('student_subjek')->insert([
                'student_ic' => $data['student']->ic,
                'courseid' => $course->sub_id,
                'sessionid' => $data['student']->session,
                'semesterid' => $data['student']->semester,
                'course_status_id' => 15,
                'status' => 'ACTIVE',
                'credit' => $course->course_credit
            ]);

            if($data['student']->student_status == 1 && $data['student']->student_status != 4)
            {

                DB::table('students')->where('ic', $data['student']->ic)->update([
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
    
                if($data['student']->student_status == 1  && $data['student']->student_status != 4)
                {
    
                    DB::table('students')->where('ic', $data['student']->ic)->update([
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
        ->join('sessions AS a', 'students.intake', 'a.SessionID')
        ->join('sessions AS b', 'students.session', 'b.SessionID')
        ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'a.SessionName AS intake', 'b.SessionName AS session')
        ->where('ic', $request->ic)->first();

        // $getCourse =  DB::table('student_subjek')
        //               ->join('students', 'student_subjek.student_ic', 'students.ic')
        //               ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
        //               ->join('subjek_structure', function($join){
        //                     $join->on('subjek.sub_id', 'subjek_structure.courseID');
        //                 })
        //               ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
        //             //   ->where('student_subjek.sessionid', $data['student']->session)
        //               ->where('students.ic', $data['student']->ic)
        //               ->where([
        //                     ['subjek_structure.program_id', $data['student']->program],
        //                     ['subjek_structure.intake_id', $data['student']->intake],
        //                     ['subjek_structure.semester_id', '<=', $data['student']->semester]
        //                 ])
        //                 ->groupBy('student_subjek.courseid')
        //                 ->groupBy('student_subjek.semesterid');

         $getCourse =  DB::table('student_subjek')
                      ->join('students', 'student_subjek.student_ic', 'students.ic')
                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                      ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                      //->where('student_subjek.sessionid', $data['student']->session)
                      ->where('students.ic', $data['student']->ic)
                      ->groupBy('student_subjek.courseid')
                      ->groupBy('student_subjek.semesterid');

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS', 'student_subjek.courseid', 'student_subjek.semesterid AS semester', 'sessions.SessionName', 'subjek.*')->orderBy('student_subjek.semesterid')->get();

        $crsExists = $getCourse->where('student_subjek.course_status_id', '!=', 2)->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)
                            ->join('subjek_structure', function($join){
                                $join->on('subjek.sub_id', 'subjek_structure.courseID');
                            })
                            ->where('subjek_structure.intake_id', $data['student']->intake)
                            ->whereIn('subjek_structure.semester_id', $loop)
                            ->where([
                                ['subjek_structure.program_id', $data['student']->program],
                                ['subjek_structure.intake_id', $data['student']->intake],
                                ['subjek_structure.semester_id', '<=', $data['student']->semester]
                            ])
                            ->orderBy('subjek_structure.semester_id')
                            ->select('subjek.*', 'subjek_structure.semester_id AS semesterid')->get();

        return view('pendaftar_akademik.getAllCourse', compact('data'));

    }


    public function unregisterCourse(Request $request)
    {
        $data['student'] = UserStudent::where('ic', $request->ic)->first();

        $data['course'] = DB::table('subjek')
                          ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                          ->where('subjek_structure.program_id', $data['student']->program)
                          ->groupBy('subjek.sub_id')
                          ->select('subjek.*')
                          ->get();

        DB::table('student_subjek')->where('id', $request->id)->delete();

        for($i = 0; $i <= $data['student']->semester; $i++)
        {
            $loop[] = $i;
        }

        $data['students'] = DB::table('students')
        ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
        ->join('sessions AS a', 'students.intake', 'a.SessionID')
        ->join('sessions AS b', 'students.session', 'b.SessionID')
        ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'a.SessionName AS intake', 'b.SessionName AS session')
        ->where('ic', $request->ic)->first();

        $getCourse =  DB::table('student_subjek')
                      ->join('students', 'student_subjek.student_ic', 'students.ic')
                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                      ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                      //->where('student_subjek.sessionid', $data['student']->session)
                      ->where('students.ic', $data['student']->ic)
                      ->groupBy('student_subjek.courseid')
                      ->groupBy('student_subjek.semesterid');

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS', 'student_subjek.courseid', 'student_subjek.semesterid AS semester', 'sessions.SessionName', 'subjek.*')->orderBy('student_subjek.semesterid')->get();

        $crsExists = $getCourse->where('student_subjek.course_status_id', '!=', 2)->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)
                            ->join('subjek_structure', function($join){
                                $join->on('subjek.sub_id', 'subjek_structure.courseID');
                            })
                            ->where('subjek_structure.intake_id', $data['student']->intake)
                            ->whereIn('subjek_structure.semester_id', $loop)
                            ->where([
                                ['subjek_structure.program_id', $data['student']->program],
                                ['subjek_structure.intake_id', $data['student']->intake],
                                ['subjek_structure.semester_id', '<=', $data['student']->semester]
                            ])
                            ->orderBy('subjek_structure.semester_id')
                            ->select('subjek.*', 'subjek_structure.semester_id AS semesterid')->get();

        return view('pendaftar_akademik.getAllCourse', compact('data'));

    }

    public function getSlipExam(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['course'] = DB::table('student_subjek')
                          ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                          ->join('tblcourse_level', 'subjek.course_level_id', 'tblcourse_level.id')
                          ->where([
                            ['student_subjek.student_ic', $request->student],
                            ['student_subjek.semesterid', $data['student']->semester]
                            ])
                            ->groupBy('subjek.sub_id')
                            ->select('subjek.*', 'tblcourse_level.name AS level')
                            ->get();

        return view('pendaftar_akademik.slipExam', compact('data'));

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

    // public function scheduleIndex()
    // {

    //     $path = "classschedule/";

    //     $files  = Storage::disk('linode')->allFiles($path);

    //     return view('pendaftar_akademik.schedule.schedule', compact('files'));

    // }

    public function scheduleIndex()
    {

        $data = [
            'room' => DB::table('tbllecture_room')->get(),
            'session' => DB::table('sessions')->where('Status', 'ACTIVE')->get()
        ];

        return view('pendaftar_akademik.schedule.index', compact('data'));

    }

    public function getLectureRoom(Request $request)
    {

        if(isset($request->session))
        {

            $allRoom = DB::table('tbllecture')
                       ->join('sessions', 'tbllecture.session_id', 'sessions.SessionID')
                       ->join('tbllecture_room', 'tbllecture.room_id', 'tbllecture_room.id')
                       ->select('tbllecture.*', 'sessions.SessionName', 'tbllecture_room.name AS roomName')
                       ->where([
                        ['tbllecture.session_id', $request->session]
                       ])->get();

            $content = "";
            $content .= '<thead>
                            <tr>
                                <th style="width: 1%">
                                    No.
                                </th>
                                <th>
                                    Session
                                </th>
                                <th>
                                    Room Name
                                </th>
                                <th>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">';
                        
            foreach($allRoom as $key => $ar){
                //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td style="width: 1%">
                    '. $key+1 .'
                    </td>
                    <td>
                    '. $ar->SessionName .'
                    </td>
                    <td>
                    '. $ar->roomName .'
                    </td>
                    <td class="project-actions text-right" >
                        <a class="btn btn-info btn-sm" href="/AR/schedule/scheduleTable/'. $ar->id .'">
                            <i class="ti-info-alt">
                            </i>
                            Table
                        </a>
                        <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('. $ar->id .')">
                            <i class="ti-trash">
                            </i>
                            Delete
                        </a>
                    </td>';

                }
                $content .= '</tr></tbody>';
    
                return $content;

        }

    }

    public function createLectureRoom(Request $request)
    {

        $data = json_decode($request->formData);

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{

                DB::table('tbllecture')->insert([
                    'room_id' => $data->room,
                    'session_id' => $data->session
                ]);

            }catch(QueryException $ex){
                DB::rollback();
                if($ex->getCode() == 23000){
                    return ["message"=>"Class code already existed inside the system"];
                }else{
                    \Log::debug($ex);
                    return ["message"=>"DB Error"];
                }
            }

            DB::commit();
        }catch(Exception $ex){
            return ["message"=>"Error"];
        }

        return response()->json(['message' => 'Success']);

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
                  ->where('status', 2)
                  ->orWhereNull('campus_id');
        });

        $query2 = DB::table('students')->where('status', 2)->where('campus_id', 0);

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
            if($student->status != 6)
            {

                $newsem = $student->semester + 1;

            }else{

                $newsem = $student->semester;

            }

            DB::table('students')->where('no_matric', $request->no_matric)->update([
                'session' => $request->session,
                'semester' => $newsem
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

    public function roomIndex()
    {

        $data = [
            'roomList' => DB::table('tbllecture_room')->get(),
        ];

        return view('pendaftar_akademik.schedule.room', compact('data'));

    }

    public function createRoomIndex(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            't_hour' => 'required|integer|min:1',
            'projector' => 'required|integer|min:0',
            'desc' => 'nullable|string|max:1000',
        ]);

        // If the validation fails, return with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->idS)
        {

            DB::table('tbllecture_room')->where('id', $request->idS)->update([
                'name' => $request->name,
                'start' => $request->start,
                'end' => $request->end,
                'capacity' => $request->capacity,
                'total_hour' => $request->t_hour,
                'projector' => $request->projector,
                'weekend' => $request->weekend,
                'description' => $request->desc,
            ]);

            return redirect()->back()->with('success', 'Room updated successfully');

        }else{

            // Insert the data into the database
            DB::table('tbllecture_room')->insert([
                'name' => $request->name,
                'start' => $request->start,
                'end' => $request->end,
                'capacity' => $request->capacity,
                'total_hour' => $request->t_hour,
                'projector' => $request->projector,
                'weekend' => $request->weekend,
                'description' => $request->desc,
            ]);

            return redirect()->back()->with('success', 'Room created successfully');

        }

    }

    public function updateRoomIndex(Request $request)
    {

        $data = [
            'room' => DB::table('tbllecture_room')->where('id', $request->id)->first()
        ];

        return view('pendaftar_akademik.schedule.getRoom', compact('data'));

    }

    public function deleteRoomIndex()
    {

        DB::table('tbllecture_room')->where('id', request()->id)->delete();

        return back();

    }

    public function scheduleTable()
    {

        $data = [
            'lectureInfo' => DB::table('tbllecture')
                             ->join('tbllecture_room', 'tbllecture.room_id', 'tbllecture_room.id')
                             ->join('sessions', 'tbllecture.session_id', 'sessions.SessionID')
                             ->select('tbllecture_room.*', 'sessions.SessionName AS session')
                             ->where('tbllecture.id', request()->id)
                             ->first(),
            'totalBooking' => DB::table('tblevents')->where('lecture_id', request()->id)
                              ->select(DB::raw('COUNT(tblevents.id) AS total_booking'))
                              ->get(),
            'lecturer' => DB::table('users')
                          ->whereIn('usrtype', ['LCT', 'PL', 'AO'])
                          ->get(),
        ];

        dd($data);

        return view('pendaftar_akademik.schedule.schedule', compact('data'));

    }

    public function getSubjectSchedule(Request $request)
    {

        $lecture = DB::table('tbllecture')->where('id', $request->id)->first();

        $subject = DB::table('user_subjek')
                   ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                   ->where([
                      ['user_subjek.user_ic', $request->lecturerId],
                      ['user_subjek.session_id', $lecture->session_id]
                   ])
                   ->select('subjek.course_name AS name', 'user_subjek.id AS id')->get();

        return response()->json($subject);

    }

    public function getGroupSchedule(Request $request)
    {

        $lecture = DB::table('tbllecture')->where('id', $request->id)->first();

        $group = DB::table('student_subjek')
                 ->where([
                    ['student_subjek.group_id', $request->groupID]
                 ])->groupBy('group_name')->get();

        return response()->json($group);

    }

    public function fetchEvents()
    {
        $events = Tblevent::join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
                  ->join('users', 'user_subjek.user_ic', 'users.ic')
                  ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                  ->where('tblevents.lecture_id', request()->id)
                  ->groupBy('subjek.sub_id', 'tblevents.id')
                  ->select('tblevents.*', 'subjek.course_code AS code' , 'subjek.course_name AS subject', 'users.name AS lecturer')->get();

        $formattedEvents = $events->map(function ($event) {

            $count = DB::table('student_subjek')
                                ->where([
                                ['group_id', $event->group_id],
                                ['group_name', $event->group_name]
                                ])
                                ->select(DB::raw('COUNT(student_ic) AS total_student'))
                                ->get();

            // Map day of the week from PHP (1 for Monday through 7 for Sunday) to FullCalendar (0 for Sunday through 6 for Saturday)
            $dayOfWeekMap = [
                1 => 1, // Monday
                2 => 2, // Tuesday
                3 => 3, // Wednesday
                4 => 4, // Thursday
                5 => 5, // Friday
                6 => 6, // Saturday
                7 => 0  // Sunday
            ];

            $dayOfWeek = date('N', strtotime($event->start));
            $fullCalendarDayOfWeek = $dayOfWeekMap[$dayOfWeek];

            return [
                'id' => $event->id,
                'title' => $event->lecturer,
                'description' => $event->code. ' - ' . $event->subject . ' (' . $event->group_name .') ' . '|' . ' Total Student :' . ' ' .$count->value('total_student'),
                'startTime' => date('H:i', strtotime($event->start)),
                'endTime' => date('H:i', strtotime($event->end)),
                'duration' => gmdate('H:i', strtotime($event->end) - strtotime($event->start)),
                'daysOfWeek' => [$fullCalendarDayOfWeek] // Recurring on the same day of the week
            ];
        });

        return response()->json($formattedEvents);
    }


    public function createEvent(Request $request)
    {   
        // Parse the start and end times from the request
        $startTime = Carbon::parse($request->start);
        $endTime = Carbon::parse($request->end);
        $rehat1 = '13:00:00';
        $rehat2 = '14:00:00';

        // Get the day of the week (e.g., Thursday)
        $dayOfWeek = $startTime->format('l');

        // Convert startTime and endTime to only time format
        $startTimeOnly = $startTime->format('H:i');
        $endTimeOnly = $endTime->format('H:i');

        $roomDetails = DB::table('tbllecture')
                       ->join('tbllecture_room', 'tbllecture.room_id', 'tbllecture_room.id')
                       ->where('tbllecture.id', $request->id)
                       ->select('tbllecture_room.*', 'tbllecture.session_id AS session')
                       ->first();

        $courseDetails = DB::table('student_subjek')
                         ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                         ->where([
                            ['group_id', $request->groupId],
                            ['group_name', $request->groupName]
                         ])
                         ->select('subjek.*')
                         ->first();
 
        if(DB::table('tblevents')
        ->where('lecture_id', $request->id)
        ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
        ->where(function ($query) use ($startTimeOnly, $endTimeOnly) {
            $query->where(function ($query) use ($startTimeOnly) {
                $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$startTimeOnly])
                      ->whereRaw('? != TIME(start)', [$startTimeOnly])
                      ->whereRaw('? != TIME(end)', [$startTimeOnly]);
            })
            ->orWhere(function ($query) use ($endTimeOnly) {
                $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$endTimeOnly])
                      ->whereRaw('? != TIME(start)', [$endTimeOnly])
                      ->whereRaw('? != TIME(end)', [$endTimeOnly]);
            })
            ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
                $query->whereRaw('? <= TIME(start)', [$startTimeOnly])
                      ->whereRaw('? >= TIME(end)', [$endTimeOnly]);
            });
        })
        ->exists() || ($startTimeOnly <= $rehat1 && $endTimeOnly >= $rehat2) ||
        ($startTimeOnly >= $rehat1 && $endTimeOnly <= $rehat2) ||
        ($startTimeOnly <= $rehat1 && $endTimeOnly <= $rehat2 && $endTimeOnly > $rehat1) ||
        ($startTimeOnly >= $rehat1 && $endTimeOnly >= $rehat2 && $startTimeOnly < $rehat2))
        {

            Log::info('Overlap detected for event on:', [
                'dayOfWeek' => $dayOfWeek,
                'startTime' => $startTime->toDateTimeString(),
                'endTime' => $endTime->toDateTimeString(),
            ]);

            return response()->json(['error' => 'Time selected is already occupied, please select another time!']);

        }else{

            $events = DB::table('tblevents')
            ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
            ->where('lecture_id', $request->id)
            ->select('start', 'end')
            ->get();

            $totalHours = 0;

            foreach ($events as $event) {
                $start = Carbon::parse($event->start);
                $end = Carbon::parse($event->end);
                $hours = $end->diffInHours($start);
                $totalHours += $hours;
            }

            $start2 = Carbon::parse($request->start);
            $end2 = Carbon::parse($request->end);
            $hours2 = $end2->diffInHours($start2);

            $newTotalHours = $hours2;

            if(($totalHours + $newTotalHours) > $roomDetails->total_hour)
            {

                return response()->json(['error' => 'Total Hour for ' . $dayOfWeek . ' already exceed ' .  $roomDetails->total_hour . '. Please clear any event and try again!']);

            }else{

                $capacity = DB::table('student_subjek')->where([
                                ['group_id', $request->groupId],
                                ['group_name', $request->groupName]
                            ])
                            ->select(DB::raw('COUNT(student_subjek.id) AS capacity'))
                            ->get();

                if($capacity->value('capacity') > $roomDetails->capacity)
                {

                    return response()->json(['error' => 'Total student is ' . $capacity->value('capacity') . '. Capacity cannot exceed ' .  $roomDetails->capacity . ', Please try with a different class!']);
                    
                }else{

                    $credit_hour = DB::table('tblevents')
                                    ->join('tbllecture', 'tblevents.lecture_id', 'tbllecture.id')
                                    ->where([
                                        ['tblevents.group_id', $request->groupId],
                                        ['tblevents.group_name', $request->groupName],
                                        ['tbllecture.session_id', $roomDetails->session]
                                    ])->get();

                    $totalCredit = 0;

                    foreach($credit_hour as $cr)
                    {

                        $start3 = Carbon::parse($cr->start);
                        $end3 = Carbon::parse($cr->end);
                        $hours3 = $end3->diffInHours($start3);
                        $totalCredit += $hours3;

                    }

                    if(($totalCredit + $newTotalHours) > $courseDetails->course_credit)
                    {

                        return response()->json(['error' => 'Total course credit is already at ' . $totalCredit . ' for this subject. Trying to add ' .  $newTotalHours . ' more will exceed ' .  $courseDetails->course_credit . '!']);

                    }else{

                        $students = DB::table('student_subjek')
                                    ->where([
                                        ['group_id', $request->groupId],
                                        ['group_name', $request->groupName]
                                    ])->pluck('student_ic'); 

                        if(DB::table('tblevents')
                        ->join('tbllecture', 'tblevents.lecture_id', 'tbllecture.id')
                        ->join('student_subjek', function($join){
                            $join->on('tblevents.group_id', 'student_subjek.group_id');
                            $join->on('tblevents.group_name', 'student_subjek.group_name');
                        })
                        ->whereIn('student_subjek.student_ic', $students)
                        ->where('tbllecture.session_id', $roomDetails->session)
                        ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
                        ->where(function ($query) use ($startTimeOnly, $endTimeOnly) {
                            $query->where(function ($query) use ($startTimeOnly) {
                                $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$startTimeOnly])
                                      ->whereRaw('? != TIME(start)', [$startTimeOnly])
                                      ->whereRaw('? != TIME(end)', [$startTimeOnly]);
                            })
                            ->orWhere(function ($query) use ($endTimeOnly) {
                                $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$endTimeOnly])
                                      ->whereRaw('? != TIME(start)', [$endTimeOnly])
                                      ->whereRaw('? != TIME(end)', [$endTimeOnly]);
                            })
                            ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
                                $query->whereRaw('? <= TIME(start)', [$startTimeOnly])
                                      ->whereRaw('? >= TIME(end)', [$endTimeOnly]);
                            });
                        })
                        ->exists()){

                            return response()->json(['error' => 'Students in this class is already booked with the same period in another room/class!']);

                        }else{

                            if($startTimeOnly < $roomDetails->start || $endTimeOnly < $roomDetails->start || $startTimeOnly > $roomDetails->end || $endTimeOnly > $roomDetails->end)
                            {

                                return response()->json(['error' => 'Event must be created inside the room time range of ' . date('h:i A', (strtotime($roomDetails->start))) . ' - ' . date('h:i A', (strtotime($roomDetails->end)))]);

                            }else{

                                $event = new Tblevent;
                                $event->lecture_id = $request->id;
                                $event->user_ic = $request->lecturer;
                                $event->group_id = $request->groupId;
                                $event->group_name = $request->groupName;
                                // $event->title = $request->title;
                                $event->start = $request->start;
                                $event->end = $request->end;
                                $event->save();

                                $events = Tblevent::join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
                                        ->join('users', 'user_subjek.user_ic', 'users.ic')
                                        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                                        ->where('tblevents.id', $event->id)
                                        ->groupBy('subjek.sub_id', 'tblevents.id')
                                        ->select('tblevents.*', 'subjek.course_code AS code' , 'subjek.course_name AS subject', 'users.name AS lecturer')->first();

                                $count = DB::table('student_subjek')
                                        ->where([
                                        ['group_id', $events->group_id],
                                        ['group_name', $events->group_name]
                                        ])
                                        ->select(DB::raw('COUNT(student_ic) AS total_student'))
                                        ->get();

                                return response()->json([
                                    'event' => [
                                        'id' => $events->id,
                                        'title' => $events->lecturer, 
                                        'description' => $events->code . ' - ' . $events->subject . ' (' . $events->group_name .') ' . '|' . ' Total Student :' . ' ' .$count->value('total_student'),
                                        'start' => $events->start,
                                        'end' => $events->end
                                    ]
                                ]);
                                
                            }

                        }

                    }

                }

            }

        }
    }

    public function updateEvent(Request $request, $id)
    {
        $event = DB::table('tblevents')->where('id', $id)->first();

        // Parse the start and end times from the request
        $startTime = Carbon::parse($request->start);
        $endTime = Carbon::parse($request->end);
        $rehat1 = '13:00:00';
        $rehat2 = '14:00:00';

        // Get the day of the week (e.g., Thursday)
        $dayOfWeek = $startTime->format('l');

        // Convert startTime and endTime to only time format
        $startTimeOnly = $startTime->format('H:i:s');
        $endTimeOnly = $endTime->format('H:i:s');

        $roomDetails = DB::table('tbllecture')
                       ->join('tbllecture_room', 'tbllecture.room_id', 'tbllecture_room.id')
                       ->where('tbllecture.id', $event->lecture_id)
                       ->select('tbllecture_room.*', 'tbllecture.session_id AS session')
                       ->first();

        $courseDetails = DB::table('student_subjek')
                         ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                         ->where([
                            ['group_id', $event->group_id],
                            ['group_name', $event->group_name]
                         ])
                         ->select('subjek.*')
                         ->first();

        if(DB::table('tblevents')
        ->where('lecture_id', $event->lecture_id)
        ->where('id', '!=', $id)
        ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
        ->where(function ($query) use ($startTimeOnly, $endTimeOnly) {
            $query->where(function ($query) use ($startTimeOnly) {
                $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$startTimeOnly])
                      ->whereRaw('? != TIME(start)', [$startTimeOnly])
                      ->whereRaw('? != TIME(end)', [$startTimeOnly]);
            })
            ->orWhere(function ($query) use ($endTimeOnly) {
                $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$endTimeOnly])
                      ->whereRaw('? != TIME(start)', [$endTimeOnly])
                      ->whereRaw('? != TIME(end)', [$endTimeOnly]);
            })
            ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
                $query->whereRaw('? <= TIME(start)', [$startTimeOnly])
                      ->whereRaw('? >= TIME(end)', [$endTimeOnly]);
            });
        })
        ->exists() || ($startTimeOnly <= $rehat1 && $endTimeOnly >= $rehat2) ||
        ($startTimeOnly >= $rehat1 && $endTimeOnly <= $rehat2) ||
        ($startTimeOnly <= $rehat1 && $endTimeOnly <= $rehat2 && $endTimeOnly > $rehat1) ||
        ($startTimeOnly >= $rehat1 && $endTimeOnly >= $rehat2 && $startTimeOnly < $rehat2))
        {

            return response()->json(['error' => 'Time selected is already occupied, please select another time!']);

        }else{

            $credit_hour = DB::table('tblevents')
                                    ->join('tbllecture', 'tblevents.lecture_id', 'tbllecture.id')
                                    ->where([
                                        ['tblevents.group_id', $event->group_id],
                                        ['tblevents.group_name', $event->group_name],
                                        ['tbllecture.session_id', $roomDetails->session],
                                        ['tblevents.id', '!=', $id]
                                    ])->get();

            $totalCredit = 0;

            foreach($credit_hour as $cr)
            {

                $start3 = Carbon::parse($cr->start);
                $end3 = Carbon::parse($cr->end);
                $hours3 = $end3->diffInHours($start3);
                $totalCredit += $hours3;

            }

            $start2 = Carbon::parse($request->start);
            $end2 = Carbon::parse($request->end);
            $hours2 = $end2->diffInHours($start2);

            $newTotalHours = $hours2;

            if(($totalCredit + $newTotalHours) > $courseDetails->course_credit)
            {

                return response()->json(['error' => 'Total course credit is already at ' . $totalCredit . ' for this subject. Trying to add ' .  $newTotalHours . ' more will exceed ' .  $courseDetails->course_credit . '!']);

            }else{

                $students = DB::table('student_subjek')
                            ->where([
                                ['group_id', $event->group_id],
                                ['group_name', $event->group_name]
                            ])->pluck('student_ic');

                if(DB::table('tblevents')
                ->join('tbllecture', 'tblevents.lecture_id', 'tbllecture.id')
                ->join('student_subjek', function($join){
                    $join->on('tblevents.group_id', 'student_subjek.group_id')
                         ->on('tblevents.group_name', 'student_subjek.group_name');
                })
                ->where('tblevents.id', '!=', $id)
                ->whereIn('student_subjek.student_ic', $students)
                ->where('tbllecture.session_id', $roomDetails->session)
                ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
                ->where(function ($query) use ($startTimeOnly, $endTimeOnly) {
                    $query->where(function ($query) use ($startTimeOnly) {
                        $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$startTimeOnly])
                                ->whereRaw('? != TIME(start)', [$startTimeOnly])
                                ->whereRaw('? != TIME(end)', [$startTimeOnly]);
                    })
                    ->orWhere(function ($query) use ($endTimeOnly) {
                        $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$endTimeOnly])
                                ->whereRaw('? != TIME(start)', [$endTimeOnly])
                                ->whereRaw('? != TIME(end)', [$endTimeOnly]);
                    })
                    ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
                        $query->whereRaw('? <= TIME(start)', [$startTimeOnly])
                                ->whereRaw('? >= TIME(end)', [$endTimeOnly]);
                    });
                })
                ->exists()){

                    return response()->json(['error' => 'Students in this class is already booked with the same period in another room/class!']);

                }else{
            
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

            }

        }
    }

    public function updateEvent2(Request $request, $id)
    {
        $event = DB::table('tblevents')->where('id', $id)->first();

        // Parse the start and end times from the request
        $startTime = Carbon::parse($request->start);
        $endTime = Carbon::parse($request->end);
        $rehat1 = '13:00:00';
        $rehat2 = '14:00:00';

        // Get the day of the week (e.g., Thursday)
        $dayOfWeek = $startTime->format('l');

        // Convert startTime and endTime to only time format
        $startTimeOnly = $startTime->format('H:i:s');
        $endTimeOnly = $endTime->format('H:i:s');

        $roomDetails = DB::table('tbllecture')
                       ->join('tbllecture_room', 'tbllecture.room_id', 'tbllecture_room.id')
                       ->where('tbllecture.id', $event->lecture_id)
                       ->select('tbllecture_room.*', 'tbllecture.session_id AS session')
                       ->first();

        $courseDetails = DB::table('student_subjek')
                         ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                         ->where([
                            ['group_id', $event->group_id],
                            ['group_name', $event->group_name]
                         ])
                         ->select('subjek.*')
                         ->first();

        if(DB::table('tblevents')
        ->where('lecture_id', $event->lecture_id)
        ->where('id', '!=', $id)
        ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
        ->where(function ($query) use ($startTimeOnly, $endTimeOnly) {
            $query->where(function ($query) use ($startTimeOnly) {
                $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$startTimeOnly])
                      ->whereRaw('? != TIME(start)', [$startTimeOnly])
                      ->whereRaw('? != TIME(end)', [$startTimeOnly]);
            })
            ->orWhere(function ($query) use ($endTimeOnly) {
                $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$endTimeOnly])
                      ->whereRaw('? != TIME(start)', [$endTimeOnly])
                      ->whereRaw('? != TIME(end)', [$endTimeOnly]);
            })
            ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
                $query->whereRaw('? <= TIME(start)', [$startTimeOnly])
                      ->whereRaw('? >= TIME(end)', [$endTimeOnly]);
            });
        })
        ->exists() || ($startTimeOnly <= $rehat1 && $endTimeOnly >= $rehat2) ||
        ($startTimeOnly >= $rehat1 && $endTimeOnly <= $rehat2) ||
        ($startTimeOnly <= $rehat1 && $endTimeOnly <= $rehat2 && $endTimeOnly > $rehat1) ||
        ($startTimeOnly >= $rehat1 && $endTimeOnly >= $rehat2 && $startTimeOnly < $rehat2))
        {

            return response()->json(['error' => 'Time selected is already occupied, please select another time!']);

        }else{

            $credit_hour = DB::table('tblevents')
                                    ->join('tbllecture', 'tblevents.lecture_id', 'tbllecture.id')
                                    ->where([
                                        ['tblevents.group_id', $event->group_id],
                                        ['tblevents.group_name', $event->group_name],
                                        ['tbllecture.session_id', $roomDetails->session],
                                        ['tblevents.id', '!=', $id]
                                    ])->get();

            $totalCredit = 0;

            foreach($credit_hour as $cr)
            {

                $start3 = Carbon::parse($cr->start);
                $end3 = Carbon::parse($cr->end);
                $hours3 = $end3->diffInHours($start3);
                $totalCredit += $hours3;

            }

            $start2 = Carbon::parse($request->start);
            $end2 = Carbon::parse($request->end);
            $hours2 = $end2->diffInHours($start2);

            $newTotalHours = $hours2;

            if(($totalCredit + $newTotalHours) > $courseDetails->course_credit)
            {

                return response()->json(['error' => 'Total course credit is already at ' . $totalCredit . ' for this subject. Trying to add ' .  $newTotalHours . ' more will exceed ' .  $courseDetails->course_credit . '!']);

            }else{

                $students = DB::table('student_subjek')
                            ->where([
                                ['group_id', $event->group_id],
                                ['group_name', $event->group_name]
                            ])->pluck('student_ic'); 

                if(DB::table('tblevents')
                ->join('tbllecture', 'tblevents.lecture_id', 'tbllecture.id')
                ->join('student_subjek', function($join){
                    $join->on('tblevents.group_id', 'student_subjek.group_id')
                         ->on('tblevents.group_name', 'student_subjek.group_name');
                })
                ->where('tblevents.id', '!=', $id)
                ->whereIn('student_subjek.student_ic', $students)
                ->where('tbllecture.session_id', $roomDetails->session)
                ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
                ->where(function ($query) use ($startTimeOnly, $endTimeOnly) {
                    $query->where(function ($query) use ($startTimeOnly) {
                        $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$startTimeOnly])
                                ->whereRaw('? != TIME(start)', [$startTimeOnly])
                                ->whereRaw('? != TIME(end)', [$startTimeOnly]);
                    })
                    ->orWhere(function ($query) use ($endTimeOnly) {
                        $query->whereRaw('? BETWEEN TIME(start) AND TIME(end)', [$endTimeOnly])
                                ->whereRaw('? != TIME(start)', [$endTimeOnly])
                                ->whereRaw('? != TIME(end)', [$endTimeOnly]);
                    })
                    ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
                        $query->whereRaw('? <= TIME(start)', [$startTimeOnly])
                                ->whereRaw('? >= TIME(end)', [$endTimeOnly]);
                    });
                })
                ->exists()){

                    return response()->json(['error' => 'Students in this class is already booked with the same period in another room/class!']);

                }else{

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
                
            }
            
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
                              ->leftjoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                              ->where('tblpayment.student_ic', $student->ic)
                              ->where('tblpayment.process_status_id', 2)
                              ->whereNotIn('tblpayment.process_type_id', [8])
                              ->whereNotIn('tblstudentclaim.groupid', [4,5])
                              ->select(

                                DB::raw('CASE
                                            WHEN IFNULL(SUM(tblpaymentdtl.amount), 0) < 250 THEN "R"
                                            WHEN IFNULL(SUM(tblpaymentdtl.amount), 0) >= 250 THEN "R1"
                                         END AS group_alias'),
                                DB::raw('IFNULL(SUM(tblpaymentdtl.amount), 0) AS amount')

                              )->first();

            }

            return view('pendaftar_akademik.reportR.getReportR', compact('data'));

        }

    }

    public function warningLetter()
    {

        return view('pendaftar_akademik.student.warning_letter.warningLetter');

    }

    public function getWarningLetter(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS statusName', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['warning'] = DB::table('tblstudent_warning')
                           ->join('student_subjek', function($join){
                                $join->on('tblstudent_warning.groupid', 'student_subjek.group_id');
                                $join->on('tblstudent_warning.groupname', 'student_subjek.group_name');
                           })
                           ->join('user_subjek', 'student_subjek.group_id', 'user_subjek.id')
                           ->join('users', 'user_subjek.user_ic', 'users.ic')
                           ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                           ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                           ->where('tblstudent_warning.student_ic', $request->student)
                           ->orderBy('subjek.course_name')
                           ->groupBy('tblstudent_warning.id')
                           ->select('tblstudent_warning.*', 'subjek.course_name', 'subjek.course_code', 'sessions.SessionName', 'users.name AS lecturer')
                           ->get();

        return view('pendaftar_akademik.student.warning_letter.getWarningLetter', compact('data'));

    }

    public function printWarningLetter(Request $request)
    {

        //dd($request->id);
        $data['warning'] = DB::table('tblstudent_warning')
                           ->join('student_subjek', function($join){
                                $join->on('tblstudent_warning.groupid', 'student_subjek.group_id');
                                $join->on('tblstudent_warning.groupname', 'student_subjek.group_name');
                           })
                           ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                           ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                           ->where('tblstudent_warning.id', $request->id)
                           ->orderBy('subjek.course_name')
                           ->groupBy('tblstudent_warning.id')
                           ->select('tblstudent_warning.*', 'subjek.course_name', 'subjek.course_code', 'subjek.course_credit', 'subjek.id AS subID','sessions.SessionName')
                           ->first();

        // Define a function to create the base query
        // $baseQuery = function () use ($data) {
        //     return DB::table('tblclassattendance')
        //     ->select('tblclassattendance.classdate')
        //     ->where([
        //         ['tblclassattendance.groupid', $data['warning']->groupid],
        //         ['tblclassattendance.groupname', $data['warning']->groupname]
        //     ])
        //     ->where('tblclassattendance.student_ic', '!=', $data['warning']->student_ic)
        //     ->orderBy('tblclassattendance.classdate')
        //     ->groupBy('tblclassattendance.classdate')
        //     ->select('tblclassattendance.*');
        // };

        
        // if($data['warning']->warning == 1)
        // {

        //     $data['absent'] = ($baseQuery)()
        //         ->get();

        // }elseif($data['warning']->warning == 2)
        // {

        //     $data['absent'] = ($baseQuery)()
        //         ->take(4)
        //         ->get();

        // }elseif($data['warning']->warning == 3)
        // {

        //     $data['absent'] = ($baseQuery)()
        //         ->take(6)
        //         ->get();

        // }

        $baseQuery = function () use ($data) {
            return DB::table('tblclassattendance as ca1')
                ->select('ca1.*', DB::raw('HOUR(TIMEDIFF(ca1.classend, ca1.classdate)) as total_hours'))
                ->where([
                    ['ca1.groupid', $data['warning']->groupid],
                    ['ca1.groupname', $data['warning']->groupname]
                ])
                ->whereRaw('NOT EXISTS (
                    SELECT 1 FROM tblclassattendance as ca2
                    WHERE ca2.classdate = ca1.classdate
                    AND ca2.groupid = ca1.groupid
                    AND ca2.groupname = ca1.groupname
                    AND ca2.student_ic = ?
                )', [$data['warning']->student_ic])
                ->groupBy('ca1.classdate')
                ->orderBy('ca1.classdate');
        };

        $data['absents'] = $baseQuery()->get();

        $totalhours = 0;

        foreach($data['absents'] as $abs)
        {

            $data['absent'][] = $abs;

            $totalhours += $abs->total_hours;

            if ($data['warning']->warning == 1) {
                if($totalhours >= $data['warning']->course_credit)
                {
                    break;
                }
            } elseif ($data['warning']->warning == 2) {
                if($totalhours >= $data['warning']->course_credit*2)
                {
                    break;
                }
            } elseif ($data['warning']->warning == 3) {
                if($totalhours >= $data['warning']->course_credit*3)
                {
                    break;
                }
            }

        }

        //dd(collect($data['absent']));
        
        // if ($data['warning']->warning == 1) {
        //     $data['absent'] = $baseQuery()->take($data['warning']->course_credit)->get();
        // } elseif ($data['warning']->warning == 2) {
        //     $data['absent'] = $baseQuery()->take($data['warning']->course_credit*2)->get();
        // } elseif ($data['warning']->warning == 3) {
        //     $data['absent'] = $baseQuery()->take($data['warning']->course_credit*3)->get();
        // }

        //dd($data['absent']);

        // $data['date'] = $data['absent']->map(function ($item) {
        //     // Extracting the date part from 'classdate'
        //     return Carbon::parse($item->classdate)->format('Y-m-d');
        // });

        // $data['day'] = $data['absent']->map(function ($item) {
        //     // Extracting the date and day name from 'classdate'
        //     return Carbon::parse($item->classdate)->format('l');
        // });

        // $data['time1'] = $data['absent']->map(function ($item) {
        //     // Extracting the time part from 'classdate' and converting it to 12-hour format
        //     return Carbon::parse($item->classdate)->format('h:i A');

        // });

        // $data['time2'] = $data['absent']->map(function ($item) {
        //     // Extracting the time part from 'classdate' and converting it to 12-hour format
        //     return Carbon::parse($item->classend)->format('h:i A');

        // });

        // Set Carbon's locale to Malay
        Carbon::setLocale('ms');

        //combine everything
        $data['absent'] = collect($data['absent'])->map(function ($item) {
            // Parse 'classdate' once since it's used multiple times
            $classdateParsed = Carbon::parse($item->classdate);
            
            return [
                'date' => $classdateParsed->format('d-m-Y'),
                'day' => $classdateParsed->translatedFormat('l'),
                'time1' => $classdateParsed->format('h:i A'),
                'time2' => Carbon::parse($item->classend)->format('h:i A'),
            ];
        });

        $data['courseCredit'] = DB::table('subjek')->where('id', $data['warning']->subID)
                  ->select('course_credit', DB::raw('(course_credit * 14) as total'))->first();

        // Now $data['time2'] is a collection containing the dates with their respective day names
        // To see the result
        //dd($data['absent']);


        $data['student'] = DB::table('students')
                            ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                            ->join('sessions', 'students.intake', 'sessions.SessionID')
                            ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname', 'tblprogramme.progcode', 'sessions.SessionName AS intake')
                            ->where('ic', $data['warning']->student_ic)->first();

        $data['address'] = DB::table('tblstudent_address')
                           ->leftJoin('tblstate', 'tblstudent_address.state_id', 'tblstate.id')
                           ->leftJoin('tblcountry', 'tblstudent_address.country_id', 'tblcountry.id')
                           ->select('tblstudent_address.*', 'tblstate.state_name AS state', 'tblcountry.name AS country')
                           ->where('tblstudent_address.student_ic', $data['warning']->student_ic)->first();

 
        $data['originalDate'] = Carbon::createFromFormat('Y-m-d H:i:s', $data['warning']->created_at)->toDateString();

        // Add one week to the date
        $dateOneWeekLater = Carbon::createFromFormat('Y-m-d', $data['originalDate'])->addWeek();

        $data['date'] = [
            'date' => $dateOneWeekLater->format('d F Y'),
            'day' => $dateOneWeekLater->translatedFormat('l')
        ];

        //dd($data['date']);

        if ($data['warning']->warning == 1 || $data['warning']->warning == 2) {

            return view('pendaftar_akademik.student.warning_letter.printWarningLetter', compact('data'));

        } elseif ($data['warning']->warning == 3) {

            return view('pendaftar_akademik.student.warning_letter.printWarningLetter2', compact('data'));

        }

    }

    // public function groupTable()
    // {

    //     $data['group'] = DB::table('student_subjek')
    //                      ->join('user_subjek', function($join){
    //                         $join->on('student_subjek.group_id', 'user_subjek.id');
    //                         $join->on('student_subjek.courseid', 'user_subjek.course_id');
    //                         $join->on('student_subjek.sessionid', 'user_subjek.session_id');
    //                      })
    //                      ->join('users', 'user_subjek.user_ic', 'users.ic')
    //                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
    //                      ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
    //                      ->groupBy('student_subjek.group_id')
    //                      ->groupBy('student_subjek.group_name')
    //                      ->where('student_subjek.sessionid', 92)
    //                      ->select(DB::raw('COUNT(student_subjek.student_ic) AS student_number'), 'subjek.course_name AS subject', 'sessions.SessionName AS session', 'users.name AS lecturer')
    //                      ->orderBy('users.name')
    //                      ->get();

    //     dd($data['group']);

    // }

}
