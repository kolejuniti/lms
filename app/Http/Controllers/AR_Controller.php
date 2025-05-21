<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\student;
use App\Models\subject;
use App\Models\Tblevent;
use App\Models\Tblevent2;
use App\Models\UserStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;
use Illuminate\Support\Facades\Cache;

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
            'prerequisite' => ['nullable'],
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
                'prerequisite_id' => $data['prerequisite'] ?? 881,
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
                'prerequisite_id' => $data['prerequisite'] ?? 881,
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

        $data['allCourse'] = $getCourse->select('student_subjek.sessionid', 'student_subjek.id as IDS', 'student_subjek.courseid', 'student_subjek.semesterid AS semester', 'sessions.SessionName', 'subjek.*')->orderBy('student_subjek.semesterid', 'ASC')->get();

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

        $data['atvSession'] = DB::table('sessions')->where('Status', 'ACTIVE')->pluck('SessionID')->toArray();

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
                    'status' => 'ACTIVE',
                    'credit' => $course->course_credit
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

        $data['allCourse'] = $getCourse->select('student_subjek.sessionid', 'student_subjek.id as IDS', 'student_subjek.courseid', 'student_subjek.semesterid AS semester', 'sessions.SessionName', 'subjek.*')->orderBy('student_subjek.semesterid')->get();

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

        $data['atvSession'] = DB::table('sessions')->where('Status', 'ACTIVE')->pluck('SessionID')->toArray();

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

        $data['allCourse'] = $getCourse->select('student_subjek.sessionid', 'student_subjek.id as IDS', 'student_subjek.courseid', 'student_subjek.semesterid AS semester', 'sessions.SessionName', 'subjek.*')->orderBy('student_subjek.semesterid')->get();

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

        $data['atvSession'] = DB::table('sessions')->where('Status', 'ACTIVE')->pluck('SessionID')->toArray();

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
            'year1' => ['required'],
            'year2' => ['required'],
            'month' => ['required'],
            'start' => ['required'],
            'end' => ['required']
        ]);

        // $start = $this->getYear($data['start']);
        
        // $end = $this->getYear($data['end']);

        $name = $data['month'] . ' ' . $data['year1'] . '/' . $data['year2'];

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

            $id = DB::table('sessions')->insertGetId([
                'SessionName' => $name,
                'Start' => $data['start'],
                'End' => $data['end'],
                'Year' => $data['year'],
                'Status' => 'ACTIVE'
            ]);

            $newId = $id - 1;

            $oldStructure = DB::table('subjek_structure')->where('intake_id', $newId)->get();

            foreach($oldStructure as $os)
            {

                DB::table('subjek_structure')->insert([
                    'courseID' => $os->courseID,
                    'structure' => $os->structure,
                    'intake_id' => $id,
                    'program_id' => $os->program_id,
                    'semester_id' => $os->semester_id
                ]);

            }

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

        $remainingString = substr($data['course']->SessionName, 4);

        $years = explode('/', $remainingString);

        $data['year1'] = $years[0];

        $data['year2'] = $years[1];

        return view('pendaftar_akademik.getSession', compact('data'))->with('id', $request->id);

    }

    public function deleteDelete(Request $request)
    {

        DB::table('sessions')->where('SessionID', $request->id)->delete();

        return true;

    }

    public function batchList()
    {
        $data = [
            'batch' => DB::table('tblbatch')->get(),
            'year' => DB::table('tblyear')->get()
        ];

        return view('pendaftar_akademik.batch', compact('data'));

    }

    public function createBatch(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'start' => ['required'],
            'end' => ['required']
        ]);

        if(isset($request->idS))
        {

            DB::table('tblbatch')->where('BatchID', $request->idS)->update([
                'BatchName' => $data['name'],
                'Start' => $data['start'],
                'End' => $data['end'],
                'Status' => $request->status
                
            ]);

        }else{

            DB::table('tblbatch')->insertGetId([
                'BatchName' => $data['name'],
                'Start' => $data['start'],
                'End' => $data['end'],
                'Status' => 'ACTIVE'
            ]);

        }

        return redirect(route('pendaftar_akademik.batch'));

    }
    public function updateBatch(Request $request)
    {

        $data = [
            'course' => DB::table('tblbatch')->where('BatchID', $request->id)->first(),
            'year' => DB::table('tblyear')->get()
        ];

        return view('pendaftar_akademik.getBatch', compact('data'))->with('id', $request->id);

    }

    public function deleteBatch(Request $request)
    {

        DB::table('tblbatch')->where('BatchID', $request->id)->delete();

        return true;

    }

    public function scheduleIndex()
    {

        $data['type'] = request()->type;

        if(request()->type == 'lct')
        {

            $data = [
                'room' => DB::table('tbllecture_room')->get(),
                'session' => DB::table('sessions')->where('Status', 'ACTIVE')->get(),
                'lecturer' => DB::table('users')->whereIn('usrtype', ['LCT', 'PL', 'AO'])->get()
            ];

        }elseif(request()->type == 'std')
        {

            $data = [
                'student' => DB::table('students')
                             ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                             ->orderBy('students.program')
                             ->where('status', 2)
                             ->get()
            ];

        }elseif(request()->type == 'lcr'){

            $data = [
                'room' => DB::table('tbllecture_room')->get()
            ];

        }

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

    // public function dropzoneStore(Request $request)
    // {

    //     $file = $request->file('file');

    //     $file_name = $file->getClientOriginalName();
    //     $file_ext = $file->getClientOriginalExtension();
    //     $fileInfo = pathinfo($file_name);
    //     $filename = $fileInfo['filename'];
    //     $newname = $filename . "." . $file_ext;

    //     //dd($file_name);

    //     $classmaterial = "classschedule/";
        

    //     if(! file_exists($newname)){
    //         Storage::disk('linode')->putFileAs(
    //             $classmaterial,
    //             $file,
    //             $newname,
    //             'public'
    //           );

    //           return response()->json(['success' => $imageName]);
    //     }


    // }

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

        // }elseif($program != '' && $semester != '')
        // {

        //     $data['campus'] = $query->where([
        //         ['program', $program],
        //         ['semester', $semester]
        //     ])->get();

        //     $data['leave'] = $query2->where([
        //         ['program', $program],
        //         ['semester', $semester]
        //     ])->get();

        // }elseif($session != '' && $semester != '')
        // {

        //     $data['campus'] = $query->where([
        //         ['session', $session],
        //         ['semester', $semester]
        //     ])->get();

        //     $data['leave'] = $query2->where([
        //         ['session', $session],
        //         ['semester', $semester]
        //     ])->get();

        // }elseif($program != '')
        // {

        //     $data['campus'] = $query->where([
        //         ['program', $program]
        //     ])->get();

        //     $data['leave'] = $query2->where([
        //         ['program', $program]
        //     ])->get();

        // }elseif($session != '')
        // {

        //     $data['campus'] = $query->where([
        //         ['session', $session]
        //     ])->get();

        //     $data['leave'] = $query2->where([
        //         ['session', $session]
        //     ])->get();

        // }elseif($semester != '')
        // {

        //     $data['campus'] = $query->where([
        //         ['semester', $semester]
        //     ])->get();

        //     $data['leave'] = $query2->where([
        //         ['semester', $semester]
        //     ])->get();

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
                'kuliah_id' => $student->student_status,
                'campus_id' => 0,
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
                'kuliah_id' => $student->student_status,
                'campus_id' => 1,
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

        // if($request->session != '' && $request->session != $student->session && $request->session > $student->session)
        if($request->session != '' && $request->session != $student->session)
        {
            if($student->status == 2 && $student->campus_id == 0)
            {
                if($student->block_status != 1)
                {

                    if($student->status != 6)
                    {

                        $newsem = $student->semester + 1;

                    }else{

                        $newsem = $student->semester;

                    }

                    if($request->withheld != 1)
                    {

                        DB::table('students')->where('no_matric', $request->no_matric)->update([
                            'session' => $request->session,
                            'semester' => $newsem
                        ]);

                    }else{

                        DB::table(  'students')->where('no_matric', $request->no_matric)->update([
                            'session' => $request->session
                        ]);

                    }

                    $userUpt = UserStudent::where('no_matric', $request->no_matric)->first();

                    DB::table('tblstudent_log')->insert([
                        'student_ic' => $userUpt->ic,
                        'session_id' => $userUpt->session,
                        'semester_id' => $userUpt->semester,
                        'status_id' => $userUpt->status,
                        'campus_id' => $userUpt->campus_id,
                        'date' => date("Y-m-d H:i:s"),
                        'remark' => null,
                        'add_staffID' => Auth::user()->ic
                    ]);

                    if($request->withheld != 1)
                    {

                        $alert = $this->getRegisterClaim($student->ic);

                    }else{

                        return ['message' => 'Success! Not charged for on hold student!'];

                    }

                }else{

                    return ['message' => 'This student is blocked! Please consult the finance department for inquiries.'];

                }

            }else{

                return ['message' => 'Student must be on leave from campus and active to register!'];

            }

        }else{

            return ['message' => 'Please fill all required field and cannot be the same semester!'];

        }

        return $alert;

    }

    private function getRegisterClaim($ic)
    {

        $student = DB::table('students')->where('ic', $ic)->first();

        if(!in_array($student->semester, [7, 8]))
        {

            $claim = DB::table('tblstudentclaimpackage')
                            ->where([
                                ['program_id', $student->program],
                                ['intake_id', $student->intake],
                                ['semester_id', $student->semester]
                                ])->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')->get();

            DB::table('tblclaim')->where([
                ['student_ic', $student->ic],
                ['session_id', $student->session],
                ['semester_id', $student->semester],
                ['program_id', $student->program]
            ])->delete();

            $id = DB::table('tblclaim')->insertGetId([
                'student_ic' => $student->ic,
                'date' => date('Y-m-d'),
                'ref_no' => null,
                'program_id' => $student->program,
                'session_id' => $student->session,
                'semester_id' => $student->semester,
                'process_status_id' => 1,
                'process_type_id' => 2,
                'add_staffID' => Auth::user()->ic,
                'add_date' => date('Y-m-d'),
                'mod_staffID' => Auth::user()->ic,
                'mod_date' => date('Y-m-d')
            ]);

            foreach($claim as $clm)
            {

                DB::table('tblclaimdtl')->insert([
                    'claim_id' => $id,
                    'claim_package_id' => $clm->id,
                    'price' => $clm->pricePerUnit,
                    'unit' => 1,
                    'amount' => $clm->pricePerUnit * 1,
                    'add_staffID' => Auth::user()->ic,
                    'add_date' => date('Y-m-d'),
                    'mod_staffID' => Auth::user()->ic,
                    'mod_date' => date('Y-m-d')
                ]);

            }

            if(count(DB::table('tblclaimdtl')->where('claim_id', $id)->get()) > 0)
            {
                $ref_no = DB::table('tblref_no')
                        ->join('tblclaim', 'tblref_no.process_type_id', 'tblclaim.process_type_id')
                        ->where('tblclaim.id', $id)
                        ->select('tblref_no.*', 'tblclaim.student_ic')->first();

                DB::table('tblref_no')->where('id', $ref_no->id)->update([
                    'ref_no' => $ref_no->ref_no + 1
                ]);

                DB::table('tblclaim')->where('id', $id)->update([
                    'process_status_id' => 2,
                    'ref_no' => $ref_no->code . $ref_no->ref_no + 1
                ]);

                $student = DB::table('students')->where('ic', $ref_no->student_ic)->first();

                $status = (in_array($student->program, [7, 8]) && in_array($student->semester, [6, 7, 8])) || (!in_array($student->program, [7, 8]) && in_array($student->semester, [7, 8])) ? 1 : 2;

                DB::table('students')->where('ic', $student->ic)->update([
                    'status' => $status,
                    'campus_id' => 1
                ]);

                DB::table('tblstudent_log')->insert([
                    'student_ic' => $student->ic,
                    'session_id' => $student->session,
                    'semester_id' => $student->semester,
                    'status_id' => 2,
                    'kuliah_id' => $student->student_status,
                    'campus_id' => 1,
                    'date' => date("Y-m-d H:i:s"),
                    'remark' => null,
                    'add_staffID' => Auth::user()->ic
                ]);

                $student_info = DB::table('tblstudent_personal')->where('student_ic', $ref_no->student_ic)->value('statelevel_id');

                //check if subject exists
                if(DB::table('student_subjek')->where([['student_ic', $student->ic],['sessionid', $student->session],['semesterid', $student->semester]])->exists())
                {
                    $alert =  ['message' => 'Success! Subject for student has already registered for this semester!'];

                }else{

                    $subject = DB::table('subjek')
                    ->join('subjek_structure', function($join){
                        $join->on('subjek.sub_id', 'subjek_structure.courseID');
                    })
                    ->where([
                        ['subjek_structure.program_id','=', $student->program],
                        ['subjek_structure.semester_id','=', $student->semester],
                        ['subjek_structure.intake_id', $student->intake]
                    ])
                    ->select('subjek.*', 'subjek_structure.semester_id')->get();

                    foreach($subject as $key)
                    {

                        if($key->offer == 1)
                        {

                            if($key->prerequisite_id == 881)
                            {

                                student::create([
                                    'student_ic' => $student->ic,
                                    'courseid' => $key->sub_id,
                                    'sessionid' => $student->session,
                                    'semesterid' => $key->semester_id,
                                    'course_status_id' => 15,
                                    'status' => 'ACTIVE',
                                    'credit' => $key->course_credit
                                ]);

                            }else{

                                $check = DB::table('student_subjek')->where('courseid', $key->prerequisite_id)->value('course_status_id');

                                if(isset($check) && $check != 2)
                                {

                                    student::create([
                                        'student_ic' => $student->ic,
                                        'courseid' => $key->sub_id,
                                        'sessionid' => $student->session,
                                        'semesterid' => $key->semester_id,
                                        'course_status_id' => 15,
                                        'status' => 'ACTIVE',
                                        'credit' => $key->course_credit
                                    ]);
                    

                                }

                            }
                        }
                        
                    }

                    $alert = ['message' => 'Success'];

                }

                if($student_info == 1)
                {

                    //PENAJA

                    $claim = DB::table('tblclaim')
                    ->join('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
                    ->where([
                    ['tblclaimdtl.claim_package_id', 9],
                    ['tblclaim.session_id', $student->session],
                    ['tblclaim.semester_id', $student->semester],
                    ['tblclaim.program_id', $student->program],
                    ['tblclaim.student_ic', $student->ic]
                    ])
                    ->select('tblclaim.*')->first();

                    $incentive = DB::table('tblincentive')
                                    ->join('tblincentive_program', 'tblincentive.id', 'tblincentive_program.incentive_id')
                                    ->where('tblincentive_program.program_id', $student->program)
                                    ->select('tblincentive.*')
                                    ->get();

                    foreach($incentive as $key => $icv)
                    {

                        if(($student->intake >= $icv->session_from && $student->intake <= $icv->session_to) || ($student->intake >= $icv->session_from && $icv->session_to == null))
                        {

                            $ref_no = DB::table('tblref_no')->where('id', 8)->first();

                            DB::table('tblref_no')->where('id', $ref_no->id)->update([
                                'ref_no' => $ref_no->ref_no + 1
                            ]);

                            $id = DB::table('tblpayment')->insertGetId([
                                'student_ic' => $student->ic,
                                'date' => date('Y-m-d'),
                                'ref_no' => $ref_no->code . $ref_no->ref_no + 1,
                                'session_id' => $student->session,
                                'semester_id' => $student->semester,
                                'program_id' => $student->program,
                                'amount' => $icv->amount,
                                'process_status_id' => 2,
                                'process_type_id' => 9,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                            DB::table('tblpaymentmethod')->insert([
                                'payment_id' => $id,
                                'claim_method_id' => 10,
                                'bank_id' => 11,
                                'no_document' => 'INS-' . $id,
                                'amount' => $icv->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                            DB::table('tblpaymentdtl')->insert([
                                'payment_id' => $id,
                                'claimDtl_id' => $icv->id,
                                'claim_type_id' => 9,
                                'amount' => $icv->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                    //TABUNGKHAS

                    $sponsors = DB::table('tblpackage_sponsorship')->where('student_ic', $student->ic);

                    if($sponsors->exists())
                    {
                        $sponsor = $sponsors->get();

                        foreach($sponsor as $spn)
                        {
                            $tabungs = DB::table('tbltabungkhas')
                                    ->join('tblprocess_type', 'tbltabungkhas.process_type_id', 'tblprocess_type.id')
                                    ->where([
                                        ['tbltabungkhas.package_id', $spn->package_id],
                                        ['tbltabungkhas.intake_id', $student->intake]
                                    ])->select('tbltabungkhas.*', 'tblprocess_type.code');

                            if($tabungs->exists())
                            {
                                $tabung = $tabungs->get();

                                foreach($tabung as $key => $tbg)
                                {
                                    if(DB::table('tbltabungkhas_program')->where([['tabungkhas_id', $tbg->id],['program_id', $student->program]])->exists())
                                    {
                                        $ref_no = DB::table('tblref_no')->where('id', 8)->first();

                                        DB::table('tblref_no')->where('id', $ref_no->id)->update([
                                            'ref_no' => $ref_no->ref_no + 1
                                        ]);

                                        $id = DB::table('tblpayment')->insertGetId([
                                            'student_ic' => $student->ic,
                                            'date' => date('Y-m-d'),
                                            'ref_no' => $ref_no->code . $ref_no->ref_no + 1,
                                            'session_id' => $student->session,
                                            'semester_id' => $student->semester,
                                            'program_id' => $student->program,
                                            'amount' => $tbg->amount,
                                            'process_status_id' => 2,
                                            'process_type_id' => $tbg->process_type_id,
                                            'add_staffID' => Auth::user()->ic,
                                            'add_date' => date('Y-m-d'),
                                            'mod_staffID' => Auth::user()->ic,
                                            'mod_date' => date('Y-m-d')
                                        ]);

                                        DB::table('tblpaymentmethod')->insert([
                                            'payment_id' => $id,
                                            'claim_method_id' => 10,
                                            'bank_id' => 11,
                                            'no_document' => $tbg->code . $id,
                                            'amount' => $tbg->amount,
                                            'add_staffID' => Auth::user()->ic,
                                            'add_date' => date('Y-m-d'),
                                            'mod_staffID' => Auth::user()->ic,
                                            'mod_date' => date('Y-m-d')
                                        ]);

                                        DB::table('tblpaymentdtl')->insert([
                                            'payment_id' => $id,
                                            'claimDtl_id' => $tbg->id,
                                            'claim_type_id' => 9,
                                            'amount' => $tbg->amount,
                                            'add_staffID' => Auth::user()->ic,
                                            'add_date' => date('Y-m-d'),
                                            'mod_staffID' => Auth::user()->ic,
                                            'mod_date' => date('Y-m-d')
                                        ]);
                                    }
                                }
                            }
                        }
                    }

                    //INSENTIFKHAS

                    $insentif = DB::table('tblinsentifkhas')
                                    ->join('tblprocess_type', 'tblinsentifkhas.process_type_id', 'tblprocess_type.id')
                                    ->where([
                                        ['tblinsentifkhas.intake_id', $student->intake]
                                    ])->select('tblinsentifkhas.*', 'tblprocess_type.code');

                    if($insentif->exists())
                    {
                        $insentifs = $insentif->get();

                        foreach($insentifs as $key => $icv)
                        {
                            if(DB::table('tblinsentifkhas_program')->where([['insentifkhas_id', $icv->id],['program_id', $student->program]])->exists())
                            {
                                $ref_no = DB::table('tblref_no')->where('id', 8)->first();

                                DB::table('tblref_no')->where('id', $ref_no->id)->update([
                                    'ref_no' => $ref_no->ref_no + 1
                                ]);

                                $id = DB::table('tblpayment')->insertGetId([
                                    'student_ic' => $student->ic,
                                    'date' => date('Y-m-d'),
                                    'ref_no' => $ref_no->code . $ref_no->ref_no + 1,
                                    'session_id' => $student->session,
                                    'semester_id' => $student->semester,
                                    'program_id' => $student->program,
                                    'amount' => $icv->amount,
                                    'process_status_id' => 2,
                                    'process_type_id' => $icv->process_type_id,
                                    'add_staffID' => Auth::user()->ic,
                                    'add_date' => date('Y-m-d'),
                                    'mod_staffID' => Auth::user()->ic,
                                    'mod_date' => date('Y-m-d')
                                ]);

                                DB::table('tblpaymentmethod')->insert([
                                    'payment_id' => $id,
                                    'claim_method_id' => 10,
                                    'bank_id' => 11,
                                    'no_document' => $icv->code . $id,
                                    'amount' => $icv->amount,
                                    'add_staffID' => Auth::user()->ic,
                                    'add_date' => date('Y-m-d'),
                                    'mod_staffID' => Auth::user()->ic,
                                    'mod_date' => date('Y-m-d')
                                ]);

                                DB::table('tblpaymentdtl')->insert([
                                    'payment_id' => $id,
                                    'claimDtl_id' => $icv->id,
                                    'claim_type_id' => 9,
                                    'amount' => $icv->amount,
                                    'add_staffID' => Auth::user()->ic,
                                    'add_date' => date('Y-m-d'),
                                    'mod_staffID' => Auth::user()->ic,
                                    'mod_date' => date('Y-m-d')
                                ]);
                            }
                        }
                    }

                }

                return $alert;

            }else{

                return ['message' => 'Please add payment charge details first!'];

            }

        }else{

            $alert = ['message' => 'Success'];

            return $alert;

        }

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

        if(request()->type == 'lct')
        {

            $id = DB::table('tblevents')
                    ->join('sessions', 'tblevents.session_id', '=', 'sessions.SessionID')
                    ->where([
                        ['tblevents.user_ic', request()->id],
                        ['sessions.Status', '=', 'ACTIVE']
                    ])
                    ->groupBy(
                        DB::raw('TIME(tblevents.start)'),      // Group by time part of start
                        DB::raw('TIME(tblevents.end)'),        // Group by time part of end
                        DB::raw('DAYNAME(tblevents.start)')    // Group by day name (e.g., "Wednesday")
                    )
                    ->pluck('tblevents.id'); // Retrieve the ids of grouped rows


                //dd($id);

            $data = [
                // 'lectureInfo' => DB::table('tbllecture')
                //                  ->join('tbllecture_room', 'tbllecture.room_id', 'tbllecture_room.id')
                //                  ->join('sessions', 'tbllecture.session_id', 'sessions.SessionID')
                //                  ->select('tbllecture_room.*', 'sessions.SessionName AS session')
                //                  ->where('tbllecture.id', request()->id)
                //                  ->first(),
                // 'totalBooking' => DB::table('tblevents')->where('lecture_id', request()->id)
                //                   ->select(DB::raw('COUNT(tblevents.id) AS total_booking'))
                //                   ->first(),
                // 'lecturer' => DB::table('users')
                //               ->whereIn('usrtype', ['LCT', 'PL', 'AO'])
                //               ->get(),
                'lecturerInfo' => DB::table('users')->where('ic', request()->id)->first(),
                'session' => DB::table('sessions')->where('Status', 'ACTIVE')->get(),
                'lecture_room' => DB::table('tbllecture_room')->get(),
                'details' => DB::table('user_subjek')
                             ->join('subjek_structure', 'user_subjek.course_id', 'subjek_structure.courseID')
                             ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                             ->where([
                                ['user_subjek.user_ic', request()->id],
                                ['sessions.Status', 'ACTIVE']
                                ])
                             ->select(DB::raw('SUM(subjek_structure.meeting_hour) AS total_hour'))
                             ->groupBy('user_subjek.id')
                             ->get(),
                // 'used' => DB::table('tblevents')
                //           ->join('user_subjek', function($join){
                //             $join->on('tblevents.group_id', 'user_subjek.id');
                //             $join->on('tblevents.session_id', 'user_subjek.session_id');
                //           })
                //           ->join('subjek_structure', 'user_subjek.course_id', 'subjek_structure.courseID')
                //           ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                //           ->where([
                //             ['tblevents.user_ic', request()->id],
                //             ['sessions.Status', 'ACTIVE']
                //           ])
                //           ->select(DB::raw('SUM(subjek_structure.meeting_hour) AS total_hour'))
                //           ->groupBy('user_subjek.id')
                //           ->get(),

                'used' => DB::table('tblevents')
                            ->join('sessions', 'tblevents.session_id', '=', 'sessions.SessionID')
                            ->whereIn('tblevents.id', $id)
                            ->select(DB::raw('SUM(TIMESTAMPDIFF(HOUR, tblevents.start, tblevents.end)) as total_hours'))
                            ->get(),
                'time' => DB::table('tblevents_second')->where('user_ic', request()->id)->value('timestamps'),
            ];

            //dd($data['used']);

            return view('pendaftar_akademik.schedule.schedule', compact('data'));

        }else{

            if(request()->type == 'std')
            {

                $data = [
                    'studentInfo' => DB::table('students')
                                    ->join('sessions', 'students.session', 'sessions.SessionID')
                                    ->where('ic', request()->id)
                                    ->select('students.*', 'sessions.SessionName AS session')
                                    ->first(),
                ];

            }elseif(request()->type == 'lcr'){

                $data = [
                    'roomInfo' => DB::table('tbllecture_room')->where('id', request()->id)->first(),
                ];

            }

            return view('pendaftar_akademik.schedule.schedule2', compact('data'));

        }

        

    }

    public function getSubjectSchedule(Request $request)
    {

        $query1 = DB::table('user_subjek')
                   ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                   ->where([
                      ['user_subjek.user_ic', $request->id],
                      ['user_subjek.session_id', $request->sessionID]
                   ])
                   ->select(DB::raw("CONCAT(subjek.course_name, ' - ', ('Kuliah')) AS name"),'subjek.course_code AS code', 'user_subjek.id AS id', DB::raw("'Kuliah' AS Type"));

        $subject = DB::table('user_subjek')
                   ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                   ->where([
                      ['user_subjek.amali_ic', $request->id],
                      ['user_subjek.session_id', $request->sessionID]
                   ])
                   ->unionAll($query1)
                   ->select(DB::raw("CONCAT(subjek.course_name, ' - ', ('Amali')) AS name"),'subjek.course_code AS code', 'user_subjek.id AS id', DB::raw("'Amali' AS Type"))
                   ->groupBy('user_subjek.id')
                   ->get();

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
        $formattedEvents = [];
        $dayOfWeekMap = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 0];
        
        if(isset(request()->type) && request()->type == 'std')
        {
            // Student schedule
            $query = null;
            
            if(isset(Auth::guard('student')->user()->ic))
            {
                // Using tblevents_second table - optimize with better joins and indexing
                $query = Tblevent2::join('student_subjek', function($join){
                        $join->on('tblevents_second.group_id', 'student_subjek.group_id');
                        $join->on('tblevents_second.group_name', 'student_subjek.group_name');
                    })
                    ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                    ->join('tbllecture_room', 'tblevents_second.lecture_id', 'tbllecture_room.id')
                    ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                    ->join('users', 'tblevents_second.user_ic', 'users.ic')
                    ->where('sessions.Status', 'ACTIVE')
                    ->where('student_subjek.student_ic', request()->id)
                    ->select(
                        'tblevents_second.id',
                        'tblevents_second.start',
                        'tblevents_second.end',
                        'tblevents_second.group_id',
                        'tblevents_second.group_name',
                        'users.name AS lecturer',
                        'subjek.course_code AS code',
                        'subjek.course_name AS subject',
                        'tbllecture_room.name AS room',
                        'sessions.SessionName AS session'
                    );
            }
            else
            {
                // Using tblevents table - optimize with better joins
                $query = Tblevent::join('student_subjek', function($join){
                        $join->on('tblevents.group_id', 'student_subjek.group_id');
                        $join->on('tblevents.group_name', 'student_subjek.group_name');
                    })
                    ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                    ->join('tbllecture_room', 'tblevents.lecture_id', 'tbllecture_room.id')
                    ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                    ->join('users', 'tblevents.user_ic', 'users.ic')
                    ->where('sessions.Status', 'ACTIVE')
                    ->where('student_subjek.student_ic', request()->id)
                    ->select(
                        'tblevents.id',
                        'tblevents.start',
                        'tblevents.end',
                        'tblevents.group_id',
                        'tblevents.group_name',
                        'users.name AS lecturer',
                        'subjek.course_code AS code',
                        'subjek.course_name AS subject',
                        'tbllecture_room.name AS room',
                        'sessions.SessionName AS session'
                    );
            }
            
            // Retrieve events with a limit to improve performance
            $events = $query->limit(100)->get();
            
            if ($events->isEmpty()) {
                return response()->json([]);
            }
            
            // Optimize by only fetching unique group IDs and names
            $groupIds = $events->pluck('group_id')->unique()->values()->toArray();
            $groupNames = $events->pluck('group_name')->unique()->values()->toArray();
            
            // Get student counts with optimized query
            $studentCounts = DB::table('student_subjek')
                ->whereIn('group_id', $groupIds)
                ->whereIn('group_name', $groupNames)
                ->groupBy('group_id', 'group_name')
                ->select(
                    'group_id', 
                    'group_name', 
                    DB::raw('COUNT(student_ic) AS total_student')
                )
                ->get()
                ->keyBy(function($item) {
                    return $item->group_id . '-' . $item->group_name;
                });
                
            // Optimize program info query with indexing hints
            $programInfo = DB::table('student_subjek')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->whereIn('student_subjek.group_id', $groupIds)
                ->whereIn('student_subjek.group_name', $groupNames)
                ->select(
                    'student_subjek.group_id',
                    'student_subjek.group_name',
                    'tblprogramme.progcode'
                )
                ->distinct() // Add distinct to reduce duplicates
                ->get();
                
            // Process program data more efficiently
            $programsByGroup = [];
            foreach ($programInfo as $program) {
                $key = $program->group_id . '-' . $program->group_name;
                if (!isset($programsByGroup[$key])) {
                    $programsByGroup[$key] = [];
                }
                // Only add unique program codes
                if (!in_array($program->progcode, $programsByGroup[$key])) {
                    $programsByGroup[$key][] = $program->progcode;
                }
            }
            
            // Process programs arrays into strings
            foreach ($programsByGroup as $key => $programs) {
                $programsByGroup[$key] = implode(', ', $programs);
            }
            
            // Optimize event mapping with Carbon for better date handling
            $formattedEvents = $events->map(function ($event) use ($studentCounts, $programsByGroup, $dayOfWeekMap) {
                $key = $event->group_id . '-' . $event->group_name;
                $count = isset($studentCounts[$key]) ? $studentCounts[$key]->total_student : 0;
                $programs = $programsByGroup[$key] ?? '';
                
                $carbonStart = Carbon::parse($event->start);
                $carbonEnd = Carbon::parse($event->end);
                $dayOfWeek = $carbonStart->format('N');
                $fullCalendarDayOfWeek = $dayOfWeekMap[$dayOfWeek];
                
                return [
                    'id' => $event->id,
                    'title' => strtoupper($event->room) . ' (' . $event->session . ')',
                    'description' => $event->code . ' - ' . $event->subject . ' (' . $event->group_name . ') | Total Student: ' . $count . ' | Programs: ' . $programs,
                    'startTime' => $carbonStart->format('H:i'),
                    'endTime' => $carbonEnd->format('H:i'),
                    'duration' => $carbonStart->diff($carbonEnd)->format('%H:%I'),
                    'daysOfWeek' => [$fullCalendarDayOfWeek],
                    'programInfo' => $programs,
                    'lectInfo' => $event->lecturer
                ];
            });
        }
        elseif(isset(request()->type))
        {
            if(Auth::user()->usrtype === 'AR') {
                // Lecture room schedule - optimize with better joins and indexing
                $events = Tblevent::join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
                    ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                    ->join('tbllecture_room', 'tblevents.lecture_id', 'tbllecture_room.id')
                    ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                    ->join('users', 'tblevents.user_ic', 'users.ic')
                    ->where('sessions.Status', 'ACTIVE')
                    ->where('tblevents.lecture_id', request()->id)
                    ->select(
                        'tblevents.id',
                        'tblevents.start',
                        'tblevents.end',
                        'tblevents.group_id',
                        'tblevents.group_name',
                        'users.name AS lecturer',
                        'subjek.course_code AS code',
                        'subjek.course_name AS subject',
                        'tbllecture_room.name AS room',
                        'sessions.SessionName AS session'
                    )
                    ->limit(100) // Add limit to prevent excessive data
                    ->get();
            } else {
                // Lecture room schedule - optimize with better joins and indexing
                $events = Tblevent2::join('user_subjek', 'tblevents_second.group_id', 'user_subjek.id')
                    ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                    ->join('tbllecture_room', 'tblevents_second.lecture_id', 'tbllecture_room.id')
                    ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                    ->join('users', 'tblevents_second.user_ic', 'users.ic')
                    ->where('sessions.Status', 'ACTIVE')
                    ->where('tblevents_second.lecture_id', request()->id)
                    ->select(
                        'tblevents_second.id',
                        'tblevents_second.start',
                        'tblevents_second.end',
                        'tblevents_second.group_id',
                        'tblevents_second.group_name',
                        'users.name AS lecturer',
                        'subjek.course_code AS code',
                        'subjek.course_name AS subject',
                        'tbllecture_room.name AS room',
                        'sessions.SessionName AS session'
                    )
                    ->limit(100) // Add limit to prevent excessive data
                    ->get();
            }
            
            if ($events->isEmpty()) {
                return response()->json([]);
            }
            
            // Optimize by only fetching unique group IDs and names
            $groupIds = $events->pluck('group_id')->unique()->values()->toArray();
            $groupNames = $events->pluck('group_name')->unique()->values()->toArray();
            
            // Optimize student counts query
            $studentCounts = DB::table('student_subjek')
                ->whereIn('group_id', $groupIds)
                ->whereIn('group_name', $groupNames)
                ->groupBy('group_id', 'group_name')
                ->select(
                    'group_id', 
                    'group_name', 
                    DB::raw('COUNT(student_ic) AS total_student')
                )
                ->get()
                ->keyBy(function($item) {
                    return $item->group_id . '-' . $item->group_name;
                });
                
            // Optimize program info query
            $programInfo = DB::table('student_subjek')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->whereIn('student_subjek.group_id', $groupIds)
                ->whereIn('student_subjek.group_name', $groupNames)
                ->select(
                    'student_subjek.group_id',
                    'student_subjek.group_name',
                    'tblprogramme.progcode'
                )
                ->distinct() // Add distinct to reduce duplicates
                ->get();
                
            // Process program data more efficiently
            $programsByGroup = [];
            foreach ($programInfo as $program) {
                $key = $program->group_id . '-' . $program->group_name;
                if (!isset($programsByGroup[$key])) {
                    $programsByGroup[$key] = [];
                }
                // Only add unique program codes
                if (!in_array($program->progcode, $programsByGroup[$key])) {
                    $programsByGroup[$key][] = $program->progcode;
                }
            }
            
            // Process programs arrays into strings
            foreach ($programsByGroup as $key => $programs) {
                $programsByGroup[$key] = implode(', ', $programs);
            }
            
            // Optimize event mapping with Carbon for better date handling
            $formattedEvents = $events->map(function ($event) use ($studentCounts, $programsByGroup, $dayOfWeekMap) {
                $key = $event->group_id . '-' . $event->group_name;
                $count = isset($studentCounts[$key]) ? $studentCounts[$key]->total_student : 0;
                $programs = $programsByGroup[$key] ?? '';
                
                $carbonStart = Carbon::parse($event->start);
                $carbonEnd = Carbon::parse($event->end);
                $dayOfWeek = $carbonStart->format('N');
                $fullCalendarDayOfWeek = $dayOfWeekMap[$dayOfWeek];
                
                return [
                    'id' => $event->id,
                    'title' => strtoupper($event->room) . ' (' . $event->session . ')',
                    'description' => $event->code . ' - ' . $event->subject . ' (' . $event->group_name . ') | Total Student: ' . $count,
                    'startTime' => $carbonStart->format('H:i'),
                    'endTime' => $carbonEnd->format('H:i'),
                    'duration' => $carbonStart->diff($carbonEnd)->format('%H:%I'),
                    'daysOfWeek' => [$fullCalendarDayOfWeek],
                    'programInfo' => $programs,
                    'lectInfo' => $event->lecturer
                ];
            });
        }
        else
        {
            // Default schedule (lecturer's schedule) - optimize with better joins and indexing
            $events = Tblevent::join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
                    ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                    ->join('tbllecture_room', 'tblevents.lecture_id', 'tbllecture_room.id')
                    ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                    ->where('sessions.Status', 'ACTIVE')
                    ->where('tblevents.user_ic', request()->id)
                    ->select(
                        'tblevents.id',
                        'tblevents.start',
                        'tblevents.end',
                        'tblevents.group_id',
                        'tblevents.group_name',
                        'subjek.course_code AS code',
                        'subjek.course_name AS subject',
                        'tbllecture_room.name AS room',
                        'sessions.SessionName AS session'
                    )
                    ->limit(100) // Add limit to prevent excessive data
                    ->get();
                
            if ($events->isEmpty()) {
                return response()->json([]);
            }
            
            // Optimize by only fetching unique group IDs and names
            $groupIds = $events->pluck('group_id')->unique()->values()->toArray();
            $groupNames = $events->pluck('group_name')->unique()->values()->toArray();
            
            // Optimize student counts query
            $studentCounts = DB::table('student_subjek')
                ->whereIn('group_id', $groupIds)
                ->whereIn('group_name', $groupNames)
                ->groupBy('group_id', 'group_name')
                ->select(
                    'group_id', 
                    'group_name', 
                    DB::raw('COUNT(student_ic) AS total_student')
                )
                ->get()
                ->keyBy(function($item) {
                    return $item->group_id . '-' . $item->group_name;
                });
                
            // Optimize program info query
            $programInfo = DB::table('student_subjek')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->whereIn('student_subjek.group_id', $groupIds)
                ->whereIn('student_subjek.group_name', $groupNames)
                ->select(
                    'student_subjek.group_id',
                    'student_subjek.group_name',
                    'tblprogramme.progcode'
                )
                ->distinct() // Add distinct to reduce duplicates
                ->get();
                
            // Process program data more efficiently
            $programsByGroup = [];
            foreach ($programInfo as $program) {
                $key = $program->group_id . '-' . $program->group_name;
                if (!isset($programsByGroup[$key])) {
                    $programsByGroup[$key] = [];
                }
                // Only add unique program codes
                if (!in_array($program->progcode, $programsByGroup[$key])) {
                    $programsByGroup[$key][] = $program->progcode;
                }
            }
            
            // Process programs arrays into strings
            foreach ($programsByGroup as $key => $programs) {
                $programsByGroup[$key] = implode(', ', $programs);
            }
            
            // Optimize event mapping with Carbon for better date handling
            $formattedEvents = $events->map(function ($event) use ($studentCounts, $programsByGroup, $dayOfWeekMap) {
                $key = $event->group_id . '-' . $event->group_name;
                $count = isset($studentCounts[$key]) ? $studentCounts[$key]->total_student : 0;
                $programs = $programsByGroup[$key] ?? '';
                
                $carbonStart = Carbon::parse($event->start);
                $carbonEnd = Carbon::parse($event->end);
                $dayOfWeek = $carbonStart->format('N');
                $fullCalendarDayOfWeek = $dayOfWeekMap[$dayOfWeek];
                
                return [
                    'id' => $event->id,
                    'title' => strtoupper($event->room) . ' (' . $event->session . ')',
                    'description' => $event->code . ' - ' . $event->subject . ' (' . $event->group_name . ') | Total Student: ' . $count,
                    'startTime' => $carbonStart->format('H:i'),
                    'endTime' => $carbonEnd->format('H:i'),
                    'duration' => $carbonStart->diff($carbonEnd)->format('%H:%I'),
                    'daysOfWeek' => [$fullCalendarDayOfWeek],
                    'programInfo' => $programs
                ];
            });
        }

        return response()->json($formattedEvents);
    }

    public function fetchExistEvent(Request $request, $id)
    {
        $event = DB::table('tblevents')->where('id', $id)->first();

        $students = DB::table('student_subjek')
                            ->where([
                                ['group_id', $event->group_id],
                                ['group_name', $event->group_name]
                            ])->pluck('student_ic');

        $sessions = DB::table('sessions')
                    ->where('Status', 'ACTIVE')
                    ->pluck('SessionID')->toArray();

        $events = DB::table('tblevents')
                ->join('student_subjek', function($join){
                    $join->on('tblevents.group_id', 'student_subjek.group_id')
                         ->on('tblevents.group_name', 'student_subjek.group_name');
                })
                ->join('user_subjek', function($join){
                    $join->on('tblevents.group_id', 'user_subjek.id');
                })
                ->where('tblevents.id', '!=', $id)
                ->whereIn('student_subjek.student_ic', $students)
                ->WhereIn('tblevents.session_id', $sessions)
                ->groupBy('tblevents.id')
                ->select('tblevents.*');

        $events = DB::table('tblevents')
                ->join('user_subjek', function($join){
                    $join->on('tblevents.group_id', 'user_subjek.id');
                })
                ->where('tblevents.id', '!=', $id)
                ->where('tblevents.user_ic', $event->user_ic)
                ->whereIn('tblevents.session_id', $sessions)
                ->groupBy('tblevents.id')
                ->unionAll($events)
                ->select('tblevents.*')
                ->get();

        $formattedEvents = $events->map(function ($event) {

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
                'startTime' => date('H:i', strtotime($event->start)),
                'endTime' => date('H:i', strtotime($event->end)),
                'duration' => gmdate('H:i', strtotime($event->end) - strtotime($event->start)),
                'daysOfWeek' => [$fullCalendarDayOfWeek] // Recurring on the same day of the week
            ];
        });

        return response()->json($formattedEvents);
    }

    private function roundToNearestHalfHour($carbonInstance) 
    {
        $minute = $carbonInstance->minute;
        $roundedMinute = $minute < 30 ? 0 : 30;
        return $carbonInstance->setMinute($roundedMinute)->setSecond(0);
    }

    public function createEvent(Request $request)
    {   
        // Parse the start and end times from the request
        $startTime = $this->roundToNearestHalfHour(Carbon::parse($request->start));
        $endTime = $this->roundToNearestHalfHour(Carbon::parse($request->end));
        $rehat1 = '13:30:00';
        $rehat2 = '14:00:00';

        $rehat3 = '12:30:00';
        $rehat4 = '14:30:00';

        // Get the day of the week (e.g., Thursday)
        $dayOfWeek = $startTime->format('l');

        // Convert startTime and endTime to only time format
        $startTimeOnly = $startTime->format('H:i');
        $endTimeOnly = $endTime->format('H:i');

        $roomDetails = DB::table('tbllecture_room')
                       ->where('tbllecture_room.id', $request->roomId)
                       ->select('tbllecture_room.*')
                       ->first();
        
        $column = null;

        if($request->groupType == 'Kuliah')
        {
            $column = 'subjek_structure.meeting_hour AS course_credit';

        }elseif($request->groupType == 'Amali')
        {
            $column = 'subjek_structure.amali_hour AS course_credit';
        }
        
        // Run the query only if a valid column is selected
        if ($column) {
            $courseDetails = DB::table('student_subjek')
                ->join('subjek', 'student_subjek.courseid', '=', 'subjek.sub_id')
                ->join('subjek_structure', 'subjek.sub_id', '=', 'subjek_structure.courseID')
                ->where([
                    ['group_id', '=', $request->groupId],
                    ['group_name', '=', $request->groupName]
                ])
                ->select(DB::raw($column))
                ->first();
        }

        $session = DB::table('sessions')
                   ->where('Status', 'ACTIVE')
                   ->pluck('SessionID')->toArray();
                       
 
        if(DB::table('tblevents')
        ->join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
        ->where('tblevents.lecture_id', $request->roomId)
        ->whereIn('tblevents.session_id', $session)
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
                $query->whereRaw('? < TIME(start)', [$startTimeOnly])
                      ->whereRaw('? = TIME(end)', [$endTimeOnly]);
            })
            ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
                $query->whereRaw('? = TIME(start)', [$startTimeOnly])
                      ->whereRaw('? > TIME(end)', [$endTimeOnly]);
            })
            ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
                $query->whereRaw('? < TIME(start)', [$startTimeOnly])
                      ->whereRaw('? > TIME(end)', [$endTimeOnly]);
            });
        })  
        ->exists())
        {

            Log::info('Overlap detected for event on:', [
                'dayOfWeek' => $dayOfWeek,
                'startTime' => $startTime->toDateTimeString(),
                'endTime' => $endTime->toDateTimeString(),
                'overlapStart' => $rehat1,
                'overlapEnd' => $rehat2,
            ]);

            return response()->json(['error' => 'Time selected is already occupied, please select another time! 1']);

        }else{

            if($dayOfWeek == 'Friday')
            {

                if(($startTimeOnly <= $rehat3 && $endTimeOnly >= $rehat4) ||
                ($startTimeOnly >= $rehat3 && $endTimeOnly <= $rehat4) ||
                ($startTimeOnly <= $rehat3 && $endTimeOnly <= $rehat4 && $endTimeOnly > $rehat3))
                {

                    Log::info('Overlap detected for event on:', [
                        'dayOfWeek' => $dayOfWeek,
                        'startTime' => $startTime->toDateTimeString(),
                        'endTime' => $endTime->toDateTimeString(),
                        'overlapStart' => $rehat3,
                        'overlapEnd' => $rehat4,
                    ]);
        
                    return response()->json(['error' => 'Time selected is already occupied, please select another time! 2']);

                }

            }else{


                if(($startTimeOnly <= $rehat1 && $endTimeOnly >= $rehat2) ||
                ($startTimeOnly >= $rehat1 && $endTimeOnly <= $rehat2) ||
                ($startTimeOnly <= $rehat1 && $endTimeOnly <= $rehat2 && $endTimeOnly > $rehat1) ||
                ($startTimeOnly >= $rehat1 && $endTimeOnly >= $rehat2 && $startTimeOnly < $rehat2))
                {

                    Log::info('Overlap detected for event on:', [
                        'dayOfWeek' => $dayOfWeek,
                        'startTime' => $startTime->toDateTimeString(),
                        'endTime' => $endTime->toDateTimeString(),
                        'overlapStart' => $rehat3,
                        'overlapEnd' => $rehat4,
                    ]);
        
                    return response()->json(['error' => 'Time selected is already occupied, please select another time! 3']);
                }

            }

            $events = DB::table('tblevents')
            ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
            ->where('lecture_id', $request->roomId)
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
                            ->first();

                if($capacity->capacity > $roomDetails->capacity)
                {

                    return response()->json(['error' => 'Total student is ' . $capacity->capacity . '. Capacity cannot exceed ' .  $roomDetails->capacity . ', Please try with a different class!']);
                    
                }else{

                    $credit_hour = DB::table('tblevents')
                                    ->join('tbllecture', 'tblevents.lecture_id', 'tbllecture.id')
                                    ->where([
                                        ['tblevents.user_ic', $request->id],
                                        ['tblevents.group_id', $request->groupId],
                                        ['tblevents.group_name', $request->groupName],
                                        ['tblevents.session_id', $request->session],
                                        ['tblevents.title', $request->groupType]
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

                        return response()->json(['error' => 'Total meeting hour is already at ' . $totalCredit . ' for this subject. Trying to add ' .  $newTotalHours . ' more will exceed ' .  $courseDetails->course_credit . '!']);

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
                        ->where('tbllecture.session_id', $request->session)
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
                                $event->lecture_id = $request->roomId;
                                $event->user_ic = $request->id;
                                $event->group_id = $request->groupId;
                                $event->group_name = $request->groupName;
                                $event->session_id = $request->session;
                                $event->title = $request->groupType;
                                $event->start = $startTime->format('Y-m-d H:i:s');
                                $event->end = $endTime->format('Y-m-d H:i:s');
                                $event->save();

                                $events = Tblevent::join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
                                        ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                                        ->join('tbllecture_room', 'tblevents.lecture_id', 'tbllecture_room.id')
                                        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                                        ->where([
                                            ['sessions.Status', 'ACTIVE']
                                            ])
                                        ->where('tblevents.id', $event->id)
                                        ->groupBy('subjek.sub_id', 'tblevents.id')
                                        ->select('tblevents.*', 'subjek.course_code AS code' , 'subjek.course_name AS subject', 'tbllecture_room.name AS room', 'sessions.SessionName AS session')->first();

                                $program = DB::table('student_subjek')
                                            ->join('students', 'student_subjek.student_ic', 'students.ic')
                                            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                                            ->where([
                                            ['student_subjek.group_id', $events->group_id],
                                            ['student_subjek.group_name', $events->group_name]
                                            ])
                                            ->groupBy('tblprogramme.id')
                                            ->select('tblprogramme.*')
                                            ->get();

                                // Convert program information into a string
                                $programInfo = $program->map(function($prog) {
                                    return $prog->progcode; // Assuming 'progname' is the relevant field you want to display
                                })->implode(', ');
        

                                $count = DB::table('student_subjek')
                                        ->where([
                                        ['group_id', $events->group_id],
                                        ['group_name', $events->group_name]
                                        ])
                                        ->select(DB::raw('COUNT(student_ic) AS total_student'))
                                        ->first();

                                return response()->json([

                                    'event' => [
                                        'id' => $events->id,
                                        'title' => strtoupper($events->room) . ' (' . $events->session . ')', 
                                        'description' => $events->code . ' - ' . $events->subject . ' (' . $events->group_name .') ' . '|' . ' Total Student :' . ' ' .$count->total_student,
                                        'start' => $events->start,
                                        'end' => $events->end,
                                        'programInfo' => $programInfo // Add program info to the event object
                                    ]

                                ]);
                                
                            }

                        }

                    }

                }

            }

        }
    }

    public function publishEvent(Request $request)
    {

        try{

            DB::table('tblevents_second')->where('user_ic', $request->id)->delete();

            $event = Tblevent::where('user_ic', $request->id)->get();
    
            foreach($event as $ev)
            {
    
                $events = new Tblevent2;
                $events->lecture_id = $ev->lecture_id;
                $events->user_ic = $ev->user_ic;
                $events->group_id = $ev->group_id;
                $events->group_name = $ev->group_name;
                $events->session_id = $ev->session_id;
                $events->title = $ev->title;
                $events->start = $ev->start;
                $events->end = $ev->end;
                $events->save();
    
            }
    
            return response()->json(['success' => 'Event has been published successfully!']);

        }catch(Exception $e){

            return response()->json(['error' => 'Error: ' . $e->getMessage()]);

        }

    }

    public function resetEvent(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        try{

            DB::table('tblevents')->where('user_ic', $data['ic'])->delete();

            // $event = Tblevent2::where('user_ic', $request->id)->get();
    
            // foreach($event as $ev)
            // {
    
            //     $events = new Tblevent;
            //     $events->lecture_id = $ev->lecture_id;
            //     $events->user_ic = $ev->user_ic;
            //     $events->group_id = $ev->group_id;
            //     $events->group_name = $ev->group_name;
            //     $events->session_id = $ev->session_id;
            //     $events->start = $ev->start;
            //     $events->end = $ev->end;
            //     $events->save();
    
            // }
    
            return response()->json(['success' => 'Event has been resetted successfully!']);

        }catch(Exception $e){

            return response()->json(['error' => 'Error: ' . $e->getMessage()]);

        }

    }

    public function logEvent(Request $request)
    {

        try{

            DB::table('tblevents_log')
            ->where('user_ic', $request->id)
            ->whereDate('date', now()->toDateString())
            ->delete();

            $event = Tblevent::where('user_ic', $request->id)->get();
    
            foreach($event as $ev)
            {

                DB::table('tblevents_log')->insert([
                    'event_id' => $ev->id,
                    'lecture_id' => $ev->lecture_id,
                    'user_ic' => $ev->user_ic,
                    'group_id' => $ev->group_id,
                    'group_name' => $ev->group_name,
                    'session_id' => $ev->session_id,
                    'title' => $ev->title,
                    'start' => $ev->start,
                    'end' => $ev->end,
                    'date' => now()->toDateString()
                ]);
    
            }
    
            return response()->json(['success' => 'Event has been logged successfully!']);

        }catch(Exception $e){

            return response()->json(['error' => 'Error: ' . $e->getMessage()]);

        }

    }

    public function getLoggedSchedule(Request $request)
    {

        $data = DB::table('tblevents_log')
                ->where([
                    ['user_ic', $request->id]
                ])
                ->groupBy('date')
                ->get();

        return response()->json($data);

    }

    public function deleteLogEvent(Request $request)
    {

        try{

            DB::table('tblevents_log')
            ->where('user_ic', $request->id)
            ->where('date', $request->idS)
            ->delete();

            return response()->json(['success' => 'Event log has been deleted successfully!']);

        }catch(Exception $e){

            return response()->json(['error' => 'Error: ' . $e->getMessage()]);

        }

    }

    public function viewLogEvent()
    {

            $data = [
                // 'lectureInfo' => DB::table('tbllecture')
                //                  ->join('tbllecture_room', 'tbllecture.room_id', 'tbllecture_room.id')
                //                  ->join('sessions', 'tbllecture.session_id', 'sessions.SessionID')
                //                  ->select('tbllecture_room.*', 'sessions.SessionName AS session')
                //                  ->where('tbllecture.id', request()->id)
                //                  ->first(),
                // 'totalBooking' => DB::table('tblevents')->where('lecture_id', request()->id)
                //                   ->select(DB::raw('COUNT(tblevents.id) AS total_booking'))
                //                   ->first(),
                // 'lecturer' => DB::table('users')
                //               ->whereIn('usrtype', ['LCT', 'PL', 'AO'])
                //               ->get(),
                'lecturerInfo' => DB::table('users')->where('ic', request()->id)->first(),
                'session' => DB::table('sessions')->where('Status', 'ACTIVE')->get(),
                'lecture_room' => DB::table('tbllecture_room')->get(),
                'details' => DB::table('user_subjek')
                             ->join('subjek_structure', 'user_subjek.course_id', 'subjek_structure.courseID')
                             ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                             ->where([
                                ['user_subjek.user_ic', request()->id],
                                ['sessions.Status', 'ACTIVE']
                                ])
                             ->select(DB::raw('SUM(subjek_structure.meeting_hour) AS total_hour'))
                             ->groupBy('user_subjek.id')
                             ->get(),
                'time' => DB::table('tblevents_second')->where('user_ic', request()->id)->value('timestamps'),
            ];

            //dd($data['used']);

            return view('pendaftar_akademik.schedule.schedule3', compact('data'));

    }

    public function fetchLogEvent(Request $request)
    {
        // $ids = DB::table('tblevents_log')
        //        ->where([
        //         ['user_ic', $request->id],
        //         ['date', $request->idS]
        //        ])
        //        ->pluck('event_id');

        $events = DB::table('tblevents_log')->join('user_subjek', 'tblevents_log.group_id', 'user_subjek.id')
                ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                ->join('tbllecture_room', 'tblevents_log.lecture_id', 'tbllecture_room.id')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->where([
                    ['tblevents_log.date', $request->idS]
                    ])
                ->where('tblevents_log.user_ic', $request->id)
                ->groupBy('subjek.sub_id', 'tblevents_log.id')
                ->select('tblevents_log.*', 'subjek.course_code AS code' , 'subjek.course_name AS subject', 'tbllecture_room.name AS room', 'sessions.SessionName AS session')->get();

        $formattedEvents = $events->map(function ($event) {

            $count = DB::table('student_subjek')
                    ->where([
                    ['group_id', $event->group_id],
                    ['group_name', $event->group_name]
                    ])
                    ->select(DB::raw('COUNT(student_ic) AS total_student'))
                    ->first();

            $program = DB::table('student_subjek')
                        ->join('students', 'student_subjek.student_ic', 'students.ic')
                        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                        ->where([
                        ['student_subjek.group_id', $event->group_id],
                        ['student_subjek.group_name', $event->group_name]
                        ])
                        ->groupBy('tblprogramme.id')
                        ->select('tblprogramme.*')
                        ->get();

            // Convert program information into a string
            $programInfo = $program->map(function($prog) {
                return $prog->progcode; // Assuming 'progname' is the relevant field you want to display
            })->implode(', ');

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
                'title' => strtoupper($event->room) . ' (' . $event->session . ')',
                'description' => $event->code . ' - ' . $event->subject . ' (' . $event->group_name . ') | Total Student: ' . $count->total_student,
                'startTime' => date('H:i', strtotime($event->start)),
                'endTime' => date('H:i', strtotime($event->end)),
                'duration' => gmdate('H:i', strtotime($event->end) - strtotime($event->start)),
                'daysOfWeek' => [$fullCalendarDayOfWeek],
                'programInfo' => $programInfo // Add program info to the event object
            ];
        });

        return response()->json($formattedEvents);
    }

    /**
 * Update an existing event in the timetable system
 * 
 * @param Request $request
 * @param int $id Event ID to update
 * @return JsonResponse
 */
public function updateEvent(Request $request, $id)
{
    $event = DB::table('tblevents')->where('id', $id)->first();
    if (!$event) {
        return response()->json(['error' => 'Event not found'], 404);
    }

    // Parse time data
    $timeData = $this->parseTimeData($request);
    extract($timeData); // Creates $startTime, $endTime, $dayOfWeek, $startTimeOnly, $endTimeOnly

    // Check all constraints before updating
    $constraintValidations = [
        $this->validateRoomAvailability($id, $event->lecture_id, $dayOfWeek, $startTimeOnly, $endTimeOnly),
        $this->validateLecturerAvailability($id, $event->user_ic, $dayOfWeek, $startTimeOnly, $endTimeOnly),
        $this->validateBreakTimeConflict($dayOfWeek, $startTimeOnly, $endTimeOnly),
        $this->validateCreditHourLimit($id, $event, $request->start, $request->end),
        $this->validateStudentScheduleConflicts($id, $event, $dayOfWeek, $startTimeOnly, $endTimeOnly)
    ];

    // Check if any constraint validation returned an error
    foreach ($constraintValidations as $validation) {
        if ($validation !== true) {
            return $validation; // Return the error response
        }
    }

    // All validations passed, update the event
    $eventModel = Tblevent::find($id);
    $eventModel->start = $request->start;
    $eventModel->end = $request->end;
    $eventModel->save();

    return response()->json(['message' => 'Event updated successfully']);
}

/**
 * Update an event with additional title modifications
 * 
 * @param Request $request
 * @param int $id Event ID to update
 * @return JsonResponse
 */
public function updateEvent2(Request $request, $id)
{
    $event = DB::table('tblevents')->where('id', $id)->first();
    if (!$event) {
        return response()->json(['error' => 'Event not found'], 404);
    }

    // Parse time data
    $timeData = $this->parseTimeData($request);
    extract($timeData); // Creates $startTime, $endTime, $dayOfWeek, $startTimeOnly, $endTimeOnly

    // Check all constraints before updating
    $constraintValidations = [
        $this->validateRoomAvailability($id, $event->lecture_id, $dayOfWeek, $startTimeOnly, $endTimeOnly),
        $this->validateLecturerAvailability($id, $event->user_ic, $dayOfWeek, $startTimeOnly, $endTimeOnly),
        $this->validateBreakTimeConflict($dayOfWeek, $startTimeOnly, $endTimeOnly),
        $this->validateCreditHourLimit($id, $event, $request->start, $request->end),
        $this->validateStudentScheduleConflicts($id, $event, $dayOfWeek, $startTimeOnly, $endTimeOnly)
    ];

    // Check if any constraint validation returned an error
    foreach ($constraintValidations as $validation) {
        if ($validation !== true) {
            return $validation; // Return the error response
        }
    }

    // All validations passed, update the event with title
    $eventModel = Tblevent::find($id);
    $eventModel->title = $request->input('title');
    $eventModel->start = $request->start;
    $eventModel->end = $request->end;
    $eventModel->save();

    return response()->json(['status' => 'success']);
}

/**
 * Parse time-related data from the request
 * 
 * @param Request $request
 * @return array
 */
private function parseTimeData(Request $request)
{
    $startTime = Carbon::parse($request->start);
    $endTime = Carbon::parse($request->end);
    $dayOfWeek = $startTime->format('l');
    $startTimeOnly = $startTime->format('H:i:s');
    $endTimeOnly = $endTime->format('H:i:s');

    return [
        'startTime' => $startTime,
        'endTime' => $endTime,
        'dayOfWeek' => $dayOfWeek,
        'startTimeOnly' => $startTimeOnly,
        'endTimeOnly' => $endTimeOnly
    ];
}

/**
 * Validate if the room is available at the specified time
 * 
 * @param int $eventId
 * @param int $lectureId
 * @param string $dayOfWeek
 * @param string $startTimeOnly
 * @param string $endTimeOnly
 * @return bool|JsonResponse True if valid, JsonResponse with error if invalid
 */
private function validateRoomAvailability($eventId, $lectureId, $dayOfWeek, $startTimeOnly, $endTimeOnly)
{
    $session = $this->getActiveSessions();

    $roomConflictExists = DB::table('tblevents')
        ->join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
        ->where('tblevents.id', '!=', $eventId)
        ->where('tblevents.lecture_id', $lectureId)
        ->whereIn('tblevents.session_id', $session)
        ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
        ->where(function ($query) use ($startTimeOnly, $endTimeOnly) {
            $this->applyTimeOverlapConditions($query, $startTimeOnly, $endTimeOnly);
        })
        ->exists();

    if ($roomConflictExists) {
        return response()->json(['error' => 'Time selected is already occupied in the same room. Please select another time!']);
    }

    return true;
}

/**
 * Validate if the lecturer is available at the specified time
 * 
 * @param int $eventId
 * @param string $lecturerIc
 * @param string $dayOfWeek
 * @param string $startTimeOnly
 * @param string $endTimeOnly
 * @return bool|JsonResponse True if valid, JsonResponse with error if invalid
 */
private function validateLecturerAvailability($eventId, $lecturerIc, $dayOfWeek, $startTimeOnly, $endTimeOnly)
{
    $session = $this->getActiveSessions();

    $lecturerConflictExists = DB::table('tblevents')
        ->join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
        ->where('tblevents.user_ic', $lecturerIc)
        ->where('tblevents.id', '!=', $eventId)
        ->whereIn('tblevents.session_id', $session)
        ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
        ->where(function ($query) use ($startTimeOnly, $endTimeOnly) {
            $this->applyTimeOverlapConditions($query, $startTimeOnly, $endTimeOnly);
        })
        ->exists();

    if ($lecturerConflictExists) {
        return response()->json(['error' => 'Time selected is already occupied by the same lecturer. Please select another time!']);
    }

    return true;
}

/**
 * Validate that the time doesn't conflict with break times
 * 
 * @param string $dayOfWeek
 * @param string $startTimeOnly
 * @param string $endTimeOnly
 * @return bool|JsonResponse True if valid, JsonResponse with error if invalid
 */
private function validateBreakTimeConflict($dayOfWeek, $startTimeOnly, $endTimeOnly)
{
    // Define break times
    $breakTimes = [
        'regular' => ['start' => '13:30:00', 'end' => '14:00:00'],
        'friday' => ['start' => '12:30:00', 'end' => '14:30:00']
    ];

    if ($dayOfWeek == 'Friday') {
        $rehatStart = $breakTimes['friday']['start'];
        $rehatEnd = $breakTimes['friday']['end'];
    } else {
        $rehatStart = $breakTimes['regular']['start'];
        $rehatEnd = $breakTimes['regular']['end'];
    }

    $hasConflict = ($startTimeOnly <= $rehatStart && $endTimeOnly >= $rehatEnd) ||
                   ($startTimeOnly >= $rehatStart && $startTimeOnly < $rehatEnd) ||
                   ($endTimeOnly > $rehatStart && $endTimeOnly <= $rehatEnd) ||
                   ($startTimeOnly <= $rehatStart && $endTimeOnly > $rehatStart && $endTimeOnly <= $rehatEnd);

    if ($hasConflict) {
        Log::info('Break time overlap detected:', [
            'dayOfWeek' => $dayOfWeek,
            'startTime' => $startTimeOnly,
            'endTime' => $endTimeOnly,
            'breakStart' => $rehatStart,
            'breakEnd' => $rehatEnd,
        ]);

        return response()->json(['error' => 'Time selected conflicts with break time. Please select another time!']);
    }

    return true;
}

/**
 * Validate that the credit hour limit won't be exceeded
 * 
 * @param int $eventId
 * @param object $event
 * @param string $startTime
 * @param string $endTime
 * @return bool|JsonResponse True if valid, JsonResponse with error if invalid
 */
private function validateCreditHourLimit($eventId, $event, $startTime, $endTime)
{
    $courseDetails = $this->getCourseDetails($event);
    if (!$courseDetails) {
        // If course details can't be found, we'll assume there's no credit hour limit
        return true;
    }

    // Calculate total credit hours used for this course
    $creditHours = DB::table('tblevents')
        ->where([
            ['tblevents.user_ic', $event->user_ic],
            ['tblevents.group_id', $event->group_id],
            ['tblevents.group_name', $event->group_name],
            ['tblevents.session_id', $event->session_id],
            ['tblevents.title', $event->title],
            ['tblevents.id', '!=', $eventId]
        ])->get();

    $totalCredit = 0;
    foreach ($creditHours as $credit) {
        $creditStart = Carbon::parse($credit->start);
        $creditEnd = Carbon::parse($credit->end);
        $totalCredit += $creditEnd->diffInHours($creditStart);
    }

    // Calculate new hours being added
    $newStart = Carbon::parse($startTime);
    $newEnd = Carbon::parse($endTime);
    $newHours = $newEnd->diffInHours($newStart);

    // Check if total will exceed limit
    if (($totalCredit + $newHours) > $courseDetails->course_credit) {
        return response()->json([
            'error' => "Total meeting hour is already at {$totalCredit} for this subject. " .
                      "Adding {$newHours} more will exceed {$courseDetails->course_credit}!"
        ]);
    }

    return true;
}

/**
 * Validate that there are no student schedule conflicts
 * 
 * @param int $eventId
 * @param object $event
 * @param string $dayOfWeek
 * @param string $startTimeOnly
 * @param string $endTimeOnly
 * @return bool|JsonResponse True if valid, JsonResponse with error if invalid
 */
private function validateStudentScheduleConflicts($eventId, $event, $dayOfWeek, $startTimeOnly, $endTimeOnly)
{
    $session = $this->getActiveSessions();
    
    // Get all students in this group
    $students = DB::table('student_subjek')
        ->where([
            ['group_id', $event->group_id],
            ['group_name', $event->group_name]
        ])->pluck('student_ic');

    // Check if any of these students have conflicts
    $conflictingStudents = DB::table('tblevents')
        ->join('student_subjek', function($join) {
            $join->on('tblevents.group_id', 'student_subjek.group_id')
                ->on('tblevents.group_name', 'student_subjek.group_name');
        })
        ->join('students', 'student_subjek.student_ic', 'students.ic')
        ->where('tblevents.id', '!=', $eventId)
        ->whereIn('session_id', $session)
        ->whereIn('student_subjek.student_ic', $students)
        ->whereRaw('DAYNAME(start) = ?', [$dayOfWeek])
        ->where(function ($query) use ($startTimeOnly, $endTimeOnly) {
            $this->applyTimeOverlapConditions($query, $startTimeOnly, $endTimeOnly);
        })
        ->select('students.no_matric')
        ->distinct()
        ->get();

    if ($conflictingStudents->count() > 0) {
        return response()->json([
            'error' => 'Students in this class are already booked with the same period in another room/class!',
            'conflicting_students' => $conflictingStudents
        ]);
    }

    return true;
}

/**
 * Get course details including credit hours
 * 
 * @param object $event
 * @return object|null
 */
private function getCourseDetails($event)
{
    $column = $this->determineCreditHourColumn($event);
    
    if (!$column) {
        return null;
    }
    
    return DB::table('student_subjek')
        ->join('subjek', 'student_subjek.courseid', '=', 'subjek.sub_id')
        ->join('subjek_structure', 'subjek.sub_id', '=', 'subjek_structure.courseID')
        ->where([
            ['group_id', '=', $event->group_id],
            ['group_name', '=', $event->group_name]
        ])
        ->select(DB::raw($column))
        ->first();
}

/**
 * Determine which column to use for credit hour based on event
 * 
 * @param object $event
 * @return string|null
 */
private function determineCreditHourColumn($event)
{
    if ($event->title == null) {
        if (DB::table('user_subjek')->where([
            'user_ic' => $event->user_ic,
            'id' => $event->group_id
        ])->exists()) {
            return 'subjek_structure.meeting_hour AS course_credit';
        } elseif (DB::table('user_subjek')->where([
            'amali_ic' => $event->user_ic,
            'id' => $event->group_id
        ])->exists()) {
            return 'subjek_structure.amali_hour AS course_credit';
        }
    } else {
        if ($event->title == 'Kuliah') {
            return 'subjek_structure.meeting_hour AS course_credit';
        } elseif ($event->title == 'Amali') {
            return 'subjek_structure.amali_hour AS course_credit';
        }
    }
    
    return null;
}

/**
 * Get IDs of active sessions
 * 
 * @return array
 */
private function getActiveSessions()
{
    return DB::table('sessions')
        ->where('Status', 'ACTIVE')
        ->pluck('SessionID')
        ->toArray();
}

/**
 * Apply time overlap conditions to a query builder
 * 
 * @param Builder $query
 * @param string $startTimeOnly
 * @param string $endTimeOnly
 */
private function applyTimeOverlapConditions($query, $startTimeOnly, $endTimeOnly)
{
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
        $query->whereRaw('? < TIME(start)', [$startTimeOnly])
              ->whereRaw('? = TIME(end)', [$endTimeOnly]);
    })
    ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
        $query->whereRaw('? = TIME(start)', [$startTimeOnly])
              ->whereRaw('? > TIME(end)', [$endTimeOnly]);
    })
    ->orWhere(function ($query) use ($startTimeOnly, $endTimeOnly) {
        $query->whereRaw('? < TIME(start)', [$startTimeOnly])
              ->whereRaw('? > TIME(end)', [$endTimeOnly]);
    });
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

    public function scheduleReport()
    {
        $session = DB::table('sessions')->where('Status', 'ACTIVE')->pluck('SessionID')->toArray();

        $data['lecturer'] = DB::table('users')
                            ->join('user_subjek', 'users.ic', 'user_subjek.user_ic')
                            ->where([['users.status', 'ACTIVE']])
                            ->whereIn('users.usrtype', ['LCT', 'PL', 'AO'])
                            ->whereIn('user_subjek.session_id', $session)
                            ->groupBy('users.ic')
                            ->get();

        foreach($data['lecturer'] as $key => $lct)
        {

            $data['subject'][$key] = DB::table('user_subjek')
                                    ->join('subjek', 'user_subjek.course_id', '=', 'subjek.sub_id')
                                    ->join('sessions', 'user_subjek.session_id', '=', 'sessions.SessionID')
                                    ->join('subjek_structure', 'subjek.sub_id', '=', 'subjek_structure.courseID')
                                    ->where(function($query) use ($lct) {
                                        // Combine both conditions in one query using OR
                                        $query->where('user_subjek.user_ic', $lct->ic)
                                            ->orWhere('user_subjek.amali_ic', $lct->ic);
                                    })
                                    ->whereIn('user_subjek.session_id', $session)
                                    ->select(
                                        'user_subjek.id AS ids', 
                                        'subjek.*', 
                                        'sessions.SessionName AS session',
                                        DB::raw("CASE 
                                                    WHEN user_subjek.user_ic = '$lct->ic' THEN subjek_structure.meeting_hour 
                                                    ELSE subjek_structure.amali_hour 
                                                END AS meeting_hour") // Conditionally select meeting_hour or amali_hour
                                    )
                                    ->groupBy('user_subjek.course_id')
                                    ->get();


            foreach($data['subject'][$key] as $key2 => $sbj)
            {

                $data['group'][$key][$key2] = DB::table('student_subjek')
                                 ->where([
                                    ['group_id', $sbj->ids]
                                 ])
                                 ->groupBy('group_name')
                                 ->select('group_name')
                                 ->get();


                foreach($data['group'][$key][$key2] as $key3 => $grp)
                {

                    $data['detail'][$key][$key2][$key3] = DB::table('tblevents')
                                      ->where([
                                        ['user_ic', $lct->ic],
                                        ['group_id', $sbj->ids],
                                        ['group_name', $grp->group_name]
                                      ])
                                      ->select(DB::raw('SUM(TIMESTAMPDIFF(HOUR, `start`, `end`)) as total_hours'))
                                      ->first();

                    $data['hour_left'][$key][$key2][$key3] = $sbj->meeting_hour - $data['detail'][$key][$key2][$key3]->total_hours;
                                      
                }

            }


        }

        //dd($data['hour_left']);

        return view('pendaftar_akademik.schedule.report_schedule.reportSchedule', compact('data'));

    }

    public function scheduleReport2()
    {

        $data['room'] = DB::table('tbllecture_room')->get();

        $data['time'] = [
            1 => '08:30:00/09:00:00',
            2 => '09:00:00/09:30:00',
            3 => '09:30:00/10:00:00',
            4 => '10:00:00/10:30:00',
            5 => '10:30:00/11:00:00',
            6 => '11:00:00/11:30:00',
            7 => '11:30:00/12:00:00',
            8 => '12:00:00/12:30:00',
            9 => '12:30:00/13:00:00',
            10 => '13:00:00/13:30:00',
            11 => '13:30:00/14:00:00',
            12 => '14:00:00/14:30:00',
            13 => '14:30:00/15:00:00',
            14 => '15:00:00/15:30:00',
            15 => '15:30:00/16:00:00',
            16 => '16:00:00/16:30:00',
            17 => '16:30:00/17:00:00',
            18 => '17:00:00/17:30:00',
            19 => '17:30:00/18:00:00',
        ];

        $data['days'] = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
        ];

        foreach($data['room'] as $key => $room)
        {

            foreach($data['days'] as $key2 => $day)
            {

                foreach($data['time'] as $key3 => $t)
                {

                    $time = explode('/', $t);

                    $data['times'][$key][$key2][$key3] = DB::table('tblevents')
                                 ->join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
                                 ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                                 ->where('tblevents.lecture_id', $room->id)
                                 ->whereRaw('DAYNAME(tblevents.start) = ?', $day)
                                 ->whereRaw('TIME(tblevents.start) >= ?',$time[0])
                                 ->whereRaw('TIME(tblevents.end) >= ?',$time[1])
                                 ->where('sessions.Status', 'ACTIVE')
                                 ->select('tblevents.*')
                                 ->first();

                }

            }

        }
        

        //dd($data['times']);

        return view('pendaftar_akademik.schedule.report_schedule.reportSchedule2', compact('data'));

    }

    public function getEventDetails()
    {

        $data['event'] = DB::table('tblevents')
                        ->join('user_subjek', 'tblevents.group_id', 'user_subjek.id')
                        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                        ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                        ->join('tbllecture_room', 'tblevents.lecture_id', 'tbllecture_room.id')
                        ->join('users', 'tblevents.user_ic', 'users.ic')
                        ->where('tblevents.id', request()->id)
                        ->select('tblevents.*', 'subjek.course_code', 'subjek.course_name', 'sessions.SessionName', 'tbllecture_room.name AS room', 'users.name AS lecturer')
                        ->first();

        return view('pendaftar_akademik.schedule.report_schedule.getEventDetails', compact('data'));

    }

    public function studentReportR()
    {

        $data['session'] = DB::table('sessions')->get();

        $data['EA'] = DB::table('tbledu_advisor')->get();

        return view('pendaftar_akademik.reportR.reportR', compact('data'));

    }

    public function getStudentReportR(Request $request)
    {

        if($request->from && $request->to)
        {
            if(!$request->has('convert') || $request->convert == "false") {
                // This block will run when the checkbox is unchecked
                $data['student'] = DB::table('students')
                               ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                               ->leftjoin('tblsex', 'tblstudent_personal.sex_id', '=', 'tblsex.id')
                               ->leftjoin('sessions', 'students.intake', 'sessions.SessionID')
                               ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                               ->leftjoin('tbledu_advisor', 'tblstudent_personal.advisor_id', 'tbledu_advisor.id')
                               ->join('tblpayment', 'students.ic', '=', 'tblpayment.student_ic')
                               ->where([
                                ['students.status', 1],
                                ['students.semester', 1]
                               ])
                               ->whereBetween('students.date_add', [$request->from, $request->to])
                               ->orderBy('students.date_add', 'asc')
                               ->groupBy('students.ic')
                               ->when($request->session != '', function ($query) use ($request){
                                    return $query->where('students.intake', $request->session);
                               })
                               ->when($request->EA != '', function ($query) use ($request){
                                    return $query->where('tblstudent_personal.advisor_id', $request->EA);
                               })
                               ->select('students.*', 'tblstudent_personal.no_tel','tblstudent_personal.qualification', 'tblsex.code AS sex', 'sessions.SessionName', 'tblprogramme.progcode', 'tbledu_advisor.name AS ea')
                               ->groupBy('students.ic')
                               ->get();

            }
            else
            {
                $data['student'] = DB::table('students')
                               ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                               ->leftjoin('tblsex', 'tblstudent_personal.sex_id', '=', 'tblsex.id')
                               ->leftjoin('sessions', 'students.intake', 'sessions.SessionID')
                               ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                               ->leftjoin('tbledu_advisor', 'tblstudent_personal.advisor_id', 'tbledu_advisor.id')
                               ->join('tblpayment', 'students.ic', '=', 'tblpayment.student_ic')
                               ->joinSub(
                                   DB::table('tblstudent_log')
                                     ->select('student_ic', 'status_id', 'semester_id', 'date')
                                     ->where('status_id', 1)
                                     ->where('semester_id', 1)
                                     ->whereBetween('date', [$request->from, $request->to])
                                     ->orderBy('date', 'asc')
                                     ->groupBy('student_ic'),
                                   'student_log',
                                   function($join) {
                                       $join->on('students.ic', '=', 'student_log.student_ic');
                                   }
                               )
                               ->when($request->session != '', function ($query) use ($request){
                                    return $query->where('students.intake', $request->session);
                               })
                               ->when($request->EA != '', function ($query) use ($request){
                                    return $query->where('tblstudent_personal.advisor_id', $request->EA);
                               })
                               ->select('students.*', 'tblstudent_personal.no_tel','tblstudent_personal.qualification', 'tblsex.code AS sex', 'sessions.SessionName', 'tblprogramme.progcode', 'tbledu_advisor.name AS ea')
                               ->groupBy('students.ic')
                               ->get();
            }

            

            $data['below5'] = 0;
            $data['below5willregister'] = 0;
            $data['below5KIV'] = 0;

            $data['below10'] = 0;
            $data['below10willregister'] = 0;
            $data['below10KIV'] = 0;

            $data['below15'] = 0;
            $data['below15willregister'] = 0;
            $data['below15KIV'] = 0;

            $data['below20'] = 0;
            $data['below20willregister'] = 0;
            $data['below20KIV'] = 0;

            $data['below25'] = 0;
            $data['below25willregister'] = 0;
            $data['below25KIV'] = 0;

            $data['below30'] = 0;
            $data['below30willregister'] = 0;
            $data['below30KIV'] = 0;

            $data['above30'] = 0;
            $data['above30willregister'] = 0;
            $data['above30KIV'] = 0;

            foreach($data['student'] as $key => $student)
            {

                $daysDiff = Carbon::parse($student->date_add)->diffInDays(now() );

                if($daysDiff < 5)
                {
                    $data['below5']++;

                    if(now() > $student->date_offer)
                    {
                        $data['below5KIV']++;
                    }
                    else
                    {
                        $data['below5willregister']++;
                    }
                }
                elseif($daysDiff < 10)
                {
                    $data['below10']++;

                    if(now() > $student->date_offer)
                    {
                        $data['below10KIV']++;
                    }
                    else
                    {
                        $data['below10willregister']++;
                    }
                }
                elseif($daysDiff < 20)
                {
                    $data['below20']++;

                    if(now() > $student->date_offer)
                    {
                        $data['below20KIV']++;
                    }
                    else
                    {
                        $data['below20willregister']++;
                    }
                }
                elseif($daysDiff < 25)
                {
                    $data['below25']++;

                    if(now() > $student->date_offer)
                    {
                        $data['below25KIV']++;
                    }
                    else
                    {
                        $data['below25willregister']++;
                    }
                }
                elseif($daysDiff < 30)
                {
                    $data['below30']++;

                    if(now() > $student->date_offer)
                    {
                        $data['below30KIV']++;
                    }
                    else
                    {
                        $data['below30willregister']++;
                    }
                }
                else
                {
                    $data['above30']++;

                    if(now() > $student->date_offer)
                    {
                        $data['above30KIV']++;
                    }
                    else
                    {
                        $data['above30willregister']++;
                    }
                }

                $payment_query = DB::table('tblpayment')
                                ->leftjoin('tblpaymentdtl', 'tblpayment.id', 'tblpaymentdtl.payment_id')
                                ->leftjoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                ->where('tblpayment.student_ic', $student->ic)
                                ->where('tblpayment.process_status_id', 2)
                                ->whereNotIn('tblpayment.process_type_id', [8])
                                ->whereNotIn('tblstudentclaim.groupid', [4,5])
                                ->select(
                                    'tblpayment.*',
                                    'tblpaymentdtl.amount',
                                    DB::raw('IF(tblpayment.id IS NOT NULL, 
                                        CASE
                                            WHEN IFNULL(tblpaymentdtl.amount, 0) < 250 THEN "R"
                                            WHEN IFNULL(tblpaymentdtl.amount, 0) >= 250 THEN "R1"
                                        END,
                                        NULL) AS group_alias')
                                )
                                ->orderBy('tblpayment.id', 'asc')
                                ->first();

                $data['result'][] = $payment_query ?? (object)[
                    'id' => null,
                    'amount' => null,
                    'group_alias' => null,
                    // Add any other fields that the blade view might be accessing
                ];

                $data['qua'][$key] = DB::table('tblqualification_std')->where('id', $student->qualification)->value('name');

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

    public function senateReport()
    {

        $data = [
            'program' => DB::table('tblprogramme')->orderBy('program_ID')->get(),
            'session' => DB::table('sessions')->get(),
            'semester' => DB::table('semester')->get()
        ];

        return view('pendaftar_akademik.student.senate_report.senateReport', compact('data'));

    }

    public function getSenateReport(Request $request)
    {

        $datas = json_decode($request->submitData);

        if($datas->program != '' && $datas->session != '' && $datas->semester != '')
        {

            if(isset($request->print))
            {

                $data['student'] = DB::table('student_transcript')
                        ->join('students', 'student_transcript.student_ic', 'students.ic')
                        ->join('transcript_status', 'student_transcript.transcript_status_id', 'transcript_status.id')
                        ->where([
                            ['students.program', $datas->program],
                            ['student_transcript.session_id', $datas->session],
                            ['student_transcript.semester', $datas->semester]
                        ])
                        ->select('student_transcript.*', 'students.name', 'students.ic','students.no_matric', 'transcript_status.status_name AS status')
                        ->orderBy('students.name')
                        ->get();

                $data['course'] = DB::table('student_subjek')
                                  ->join('students', 'student_subjek.student_ic', 'students.ic')
                                  ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                                  ->where([
                                    ['students.program', $datas->program],
                                    ['student_subjek.sessionid', $datas->session],
                                    ['student_subjek.semesterid', $datas->semester]
                                  ])
                                  ->groupBy('subjek.sub_id')
                                  ->orderBy('subjek.course_code')
                                  ->get();

                $data['program'] = DB::table('tblprogramme')->where('id', $datas->program)->first();
                $data['session'] = DB::table('sessions')->where('SessionID', $datas->session)->first();
                $data['semester'] = $datas->semester;

                $data['status'] = DB::table('transcript_status')->get();

                // Initializing total counts array
                $data['total'] = array_fill(0, count($data['status']), 0);


                foreach($data['student'] as $key => $std)
                {

                    foreach ($data['status'] as $key2 => $sts) {
                        if ($sts->status_name == $std->status) {
                            $data['total'][$key2]++;
                        }
                    }

                    foreach($data['course'] as $key2 => $crs)
                    {

                        $data['dtl'][$key][$key2] = DB::table('student_subjek')
                                       ->join('students', 'student_subjek.student_ic', 'students.ic')
                                       ->where([
                                         ['students.ic', $std->ic],
                                         ['student_subjek.sessionid', $datas->session],
                                         ['student_subjek.semesterid', $datas->semester],
                                         ['student_subjek.courseid', $crs->sub_id]
                                       ])->select('student_subjek.pointer', 'student_subjek.grade')->first();

                    }

                }


                return view('pendaftar_akademik.student.senate_report.printSenateReport', compact('data'));

            
            }else{

                

                    $data = DB::table('student_transcript')
                                    ->join('students', 'student_transcript.student_ic', 'students.ic')
                                    ->join('transcript_status', 'student_transcript.transcript_status_id', 'transcript_status.id')
                                    ->where([
                                        ['students.program', $datas->program],
                                        ['student_transcript.session_id', $datas->session],
                                        ['student_transcript.semester', $datas->semester]
                                    ])
                                    ->select('student_transcript.*', 'students.name', 'transcript_status.status_name AS status')
                                    ->orderBy('students.name')
                                    ->get();

                    return response()->json(['data' => $data]);

            }

        }else{

            return response()->json(['error' => 'Please fill in all input!']);

        }

    }

    public function resultReport()
    {

        $data = [
            'session' => DB::table('sessions')->orderBy('SessionID', 'DESC')->get()
        ];

        return view('pendaftar_akademik.student.result_report.resultReport', compact('data'));

    }

    public function getResultReport(Request $request)
    {

        $datas = json_decode($request->submitData);

        if($datas->session !=  '' && $datas->start != '' && $datas->end != '')
        {

            $gpa_column = ($datas->type == 'gpa') ? 'GPAs' : 'CGPAs';

            $data = DB::table('students')
                ->join('student_transcript', 'students.ic', 'student_transcript.student_ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->join('transcript_status', 'student_transcript.transcript_status_id', 'transcript_status.id')
                ->select(
                    'student_transcript.*',
                    'students.name', 
                    'students.ic', 
                    'students.no_matric', 
                    'tblprogramme.progcode', 
                    'transcript_status.status_name',
                    DB::raw("'$gpa_column' AS type") // Explicitly cast the gpa or cgpa column as string
                )
                ->whereNotIn('students.status', [4, 9])
                ->where('student_transcript.session_id', $datas->session)
                ->when($datas->type == 'gpa', function ($query) use ($datas) {
                    return $query->whereBetween('student_transcript.gpa', [$datas->start, $datas->end]);
                })
                ->when($datas->type == 'cgpa', function ($query) use ($datas) {
                    return $query->whereBetween('student_transcript.cgpa', [$datas->start, $datas->end]);
                })
                ->orderBy('students.name')
                ->get();


            return response()->json(['data' => $data]);

        }else{

            return response()->json(['error' => 'Please fill in all the input fields!']);

        }

    }

    public function studentAssessment()
    {

        $data = [
            'lecturer' => DB::table('users')
                          ->whereIn('usrtype', ['LCT', 'PL', 'AO'])
                          ->get(),
            'intake' => DB::table('sessions')->orderBy('SessionID', 'DESC')->get()
        ];

        return view('pendaftar_akademik.student.assessment.studentAssessment', compact('data'));

    }

    public function getStudentAssessment(Request $request)
    {

        if($request->lecturer != '' && $request->subject != '' && $request->group != '' && $request->assessment != '')
        {
            $subject = DB::table('user_subjek')
                       ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                       ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                       ->where([
                        ['user_subjek.user_ic', $request->lecturer],
                        ['user_subjek.id', $request->subject],
                        ['student_subjek.group_name', $request->group],
                        ['user_subjek.session_id', '=', DB::raw('student_subjek.sessionid')],
                       ])
                       ->select('user_subjek.*', 'subjek.id AS id')
                       ->first();

            $data['type'] = $request->assessment;

            $data['intake'] = $request->intake;

            //return response()->json($subject);

            if($request->assessment == 'quiz')
            {

                $data['assessment'] = DB::table('tblclassquiz')
                ->join('users', 'tblclassquiz.addby', 'users.ic')
                ->join('tblclassquizstatus', 'tblclassquiz.status', 'tblclassquizstatus.id')
                ->where([
                    ['tblclassquiz.classid', $subject->id],
                    ['tblclassquiz.sessionid', $subject->session_id],
                    ['tblclassquiz.addby', $subject->user_ic],
                    ['tblclassquiz.status', '!=', 3]
                ])
                ->select('tblclassquiz.*', 'users.name AS addby', 'tblclassquizstatus.statusname')->get();

                foreach($data['assessment'] as $dt)
                {
                    $data['group'][] = DB::table('tblclassquiz_group')
                            ->join('user_subjek', 'tblclassquiz_group.groupid', 'user_subjek.id')
                            ->where('tblclassquiz_group.quizid', $dt->id)->get();

                    $data['chapter'][] = DB::table('tblclassquiz_chapter')
                            ->join('material_dir', 'tblclassquiz_chapter.chapterid', 'material_dir.DrID')
                            ->where('tblclassquiz_chapter.quizid', $dt->id)->get();
                }
                
            }

            if($request->assessment == 'test')
            {

                $data['assessment'] = DB::table('tblclasstest')
                ->join('users', 'tblclasstest.addby', 'users.ic')
                ->join('tblclassteststatus', 'tblclasstest.status', 'tblclassteststatus.id')
                ->where([
                    ['tblclasstest.classid', $subject->id],
                    ['tblclasstest.sessionid', $subject->session_id],
                    ['tblclasstest.addby', $subject->user_ic],
                    ['tblclasstest.status', '!=', 3]
                ])
                ->select('tblclasstest.*', 'users.name AS addby', 'tblclassteststatus.statusname')->get();

                foreach($data['assessment'] as $dt)
                {
                    $data['group'][] = DB::table('tblclasstest_group')
                            ->join('user_subjek', 'tblclasstest_group.groupid', 'user_subjek.id')
                            ->where('tblclasstest_group.testid', $dt->id)->get();

                    $data['chapter'][] = DB::table('tblclasstest_chapter')
                            ->join('material_dir', 'tblclasstest_chapter.chapterid', 'material_dir.DrID')
                            ->where('tblclasstest_chapter.testid', $dt->id)->get();
                }
                
            }

            if($request->assessment == 'assignment')
            {

                $data['assessment'] = DB::table('tblclassassign')
                ->join('users', 'tblclassassign.addby', 'users.ic')
                ->join('tblclassassignstatus', 'tblclassassign.status', 'tblclassassignstatus.id')
                ->where([
                    ['tblclassassign.classid', $subject->id],
                    ['tblclassassign.sessionid', $subject->session_id],
                    ['tblclassassign.addby', $subject->user_ic],
                    ['tblclassassign.status', '!=', 3]
                ])
                ->select('tblclassassign.*', 'users.name AS addby', 'tblclassassignstatus.statusname')->get();

                foreach($data['assessment'] as $dt)
                {
                    $data['group'][] = DB::table('tblclassassign_group')
                            ->join('user_subjek', 'tblclassassign_group.groupid', 'user_subjek.id')
                            ->where('tblclassassign_group.assignid', $dt->id)->get();

                    $data['chapter'][] = DB::table('tblclassassign_chapter')
                            ->join('material_dir', 'tblclassassign_chapter.chapterid', 'material_dir.DrID')
                            ->where('tblclassassign_chapter.assignid', $dt->id)->get();
                }
                
            }

            if($request->assessment == 'midterm')
            {

                $data['assessment'] = DB::table('tblclassmidterm')
                ->join('users', 'tblclassmidterm.addby', 'users.ic')
                ->join('tblclassmidtermstatus', 'tblclassmidterm.status', 'tblclassmidtermstatus.id')
                ->where([
                    ['tblclassmidterm.classid', $subject->id],
                    ['tblclassmidterm.sessionid', $subject->session_id],
                    ['tblclassmidterm.addby', $subject->user_ic],
                    ['tblclassmidterm.status', '!=', 3]
                ])
                ->select('tblclassmidterm.*', 'users.name AS addby', 'tblclassmidtermstatus.statusname')->get();

                foreach($data['assessment'] as $dt)
                {
                    $data['group'][] = DB::table('tblclassmidterm_group')
                            ->join('user_subjek', 'tblclassmidterm_group.groupid', 'user_subjek.id')
                            ->where('tblclassmidterm_group.midtermid', $dt->id)->get();

                    $data['chapter'][] = DB::table('tblclassmidterm_chapter')
                            ->join('material_dir', 'tblclassmidterm_chapter.chapterid', 'material_dir.DrID')
                            ->where('tblclassmidterm_chapter.midtermid', $dt->id)->get();
                }
                
            }

            if($request->assessment == 'other')
            {

                $data['assessment'] = DB::table('tblclassother')
                ->join('users', 'tblclassother.addby', 'users.ic')
                ->join('tblclassotherstatus', 'tblclassother.status', 'tblclassotherstatus.id')
                ->where([
                    ['tblclassother.classid', $subject->id],
                    ['tblclassother.sessionid', $subject->session_id],
                    ['tblclassother.addby', $subject->user_ic],
                    ['tblclassother.status', '!=', 3]
                ])
                ->select('tblclassother.*', 'users.name AS addby', 'tblclassotherstatus.statusname')->get();

                foreach($data['assessment'] as $dt)
                {
                    $data['group'][] = DB::table('tblclassother_group')
                            ->join('user_subjek', 'tblclassother_group.groupid', 'user_subjek.id')
                            ->where('tblclassother_group.otherid', $dt->id)->get();

                    $data['chapter'][] = DB::table('tblclassother_chapter')
                            ->join('material_dir', 'tblclassother_chapter.chapterid', 'material_dir.DrID')
                            ->where('tblclassother_chapter.otherid', $dt->id)->get();
                }
                
            }

            if($request->assessment == 'extra')
            {

                $data['assessment'] = DB::table('tblclassextra')
                ->join('users', 'tblclassextra.addby', 'users.ic')
                ->join('tblclassextrastatus', 'tblclassextra.status', 'tblclassextrastatus.id')
                ->where([
                    ['tblclassextra.classid', $subject->id],
                    ['tblclassextra.sessionid', $subject->session_id],
                    ['tblclassextra.addby', $subject->user_ic],
                    ['tblclassextra.status', '!=', 3]
                ])
                ->select('tblclassextra.*', 'users.name AS addby', 'tblclassextrastatus.statusname')->get();

                foreach($data['assessment'] as $dt)
                {
                    $data['group'][] = DB::table('tblclassextra_group')
                            ->join('user_subjek', 'tblclassextra_group.groupid', 'user_subjek.id')
                            ->where('tblclassextra_group.extraid', $dt->id)->get();

                    $data['chapter'][] = DB::table('tblclassextra_chapter')
                            ->join('material_dir', 'tblclassextra_chapter.chapterid', 'material_dir.DrID')
                            ->where('tblclassextra_chapter.extraid', $dt->id)->get();
                }
                
            }

            if($request->assessment == 'final')
            {

                $data['assessment'] = DB::table('tblclassfinal')
                ->join('users', 'tblclassfinal.addby', 'users.ic')
                ->join('tblclassfinalstatus', 'tblclassfinal.status', 'tblclassfinalstatus.id')
                ->where([
                    ['tblclassfinal.classid', $subject->id],
                    ['tblclassfinal.sessionid', $subject->session_id],
                    ['tblclassfinal.addby', $subject->user_ic],
                    ['tblclassfinal.status', '!=', 3]
                ])
                ->select('tblclassfinal.*', 'users.name AS addby', 'tblclassfinalstatus.statusname')->get();

                foreach($data['assessment'] as $dt)
                {
                    $data['group'][] = DB::table('tblclassfinal_group')
                            ->join('user_subjek', 'tblclassfinal_group.groupid', 'user_subjek.id')
                            ->where('tblclassfinal_group.finalid', $dt->id)->get();

                    $data['chapter'][] = DB::table('tblclassfinal_chapter')
                            ->join('material_dir', 'tblclassfinal_chapter.chapterid', 'material_dir.DrID')
                            ->where('tblclassfinal_chapter.finalid', $dt->id)->get();
                }
                
            }

            return view('pendaftar_akademik.student.assessment.getStudentAssessment', compact('data'));

        }else{

            return response()->json(['error' => 'Please make sure that all input fields are filled!']);

        }

    }

    public function assessmentStatus()
    {

        $data['type'] = request()->type;

        $data['id'] = request()->id;

        $data['intake'] = request()->intake;

        if(request()->type == 'quiz')
        {

            $data['group'] = DB::table('user_subjek')
                    ->join('tblclassquiz_group', 'user_subjek.id', 'tblclassquiz_group.groupid')
                    ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                    ->where([
                        ['tblclassquiz.id', request()->id]
                    ])->get();

            $data['assessment'] = DB::table('student_subjek')
                    ->join('tblclassquiz_group', function($join){
                        $join->on('student_subjek.group_id', 'tblclassquiz_group.groupid');
                        $join->on('student_subjek.group_name', 'tblclassquiz_group.groupname');
                    })
                    ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                    ->join('students', 'student_subjek.student_ic', 'students.ic')
                    ->when(request()->intake != '', function($query){
                        return $query->where('students.intake', request()->intake);
                    })
                    ->select('student_subjek.*', 'tblclassquiz.id AS clssid', 'tblclassquiz.total_mark', 'students.no_matric', 'students.name')
                    ->where([
                        ['tblclassquiz.id', request()->id]
                    ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.name')->get();

            foreach($data['assessment'] as $qz)
            {

                if(!DB::table('tblclassstudentquiz')->where([['quizid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                    DB::table('tblclassstudentquiz')->insert([
                        'quizid' => $qz->clssid,
                        'userid' => $qz->student_ic
                    ]);
    
                }

                $data['status'][] = DB::table('tblclassstudentquiz')
                ->where([
                    ['quizid', $qz->clssid],
                    ['userid', $qz->student_ic]
                ])->first();
            }

            return view('pendaftar_akademik.student.assessment.assessmentStatus', compact('data'));

        }

        if(request()->type == 'test')
        {

            $data['group'] = DB::table('user_subjek')
                    ->join('tblclasstest_group', 'user_subjek.id', 'tblclasstest_group.groupid')
                    ->join('tblclasstest', 'tblclasstest_group.testid', 'tblclasstest.id')
                    ->where([
                        ['tblclasstest.id', request()->id]
                    ])->get();

            $data['assessment'] = DB::table('student_subjek')
                    ->join('tblclasstest_group', function($join){
                        $join->on('student_subjek.group_id', 'tblclasstest_group.groupid');
                        $join->on('student_subjek.group_name', 'tblclasstest_group.groupname');
                    })
                    ->join('tblclasstest', 'tblclasstest_group.testid', 'tblclasstest.id')
                    ->join('students', 'student_subjek.student_ic', 'students.ic')
                    ->when(request()->intake != '', function($query){
                        return $query->where('students.intake', request()->intake);
                    })
                    ->select('student_subjek.*', 'tblclasstest.id AS clssid', 'tblclasstest.total_mark', 'students.no_matric', 'students.name')
                    ->where([
                        ['tblclasstest.id', request()->id]
                    ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.name')->get();

            foreach($data['assessment'] as $qz)
            {

                if(!DB::table('tblclassstudenttest')->where([['testid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                    DB::table('tblclassstudenttest')->insert([
                        'testid' => $qz->clssid,
                        'userid' => $qz->student_ic
                    ]);
    
                }

                $data['status'][] = DB::table('tblclassstudenttest')
                ->where([
                    ['testid', $qz->clssid],
                    ['userid', $qz->student_ic]
                ])->first();
            }

            return view('pendaftar_akademik.student.assessment.assessmentStatus', compact('data'));

        }

        if(request()->type == 'assignment')
        {

            $data['group'] = DB::table('user_subjek')
                    ->join('tblclassassign_group', 'user_subjek.id', 'tblclassassign_group.groupid')
                    ->join('tblclassassign', 'tblclassassign_group.assignid', 'tblclassassign.id')
                    ->where([
                        ['tblclassassign.id', request()->id]
                    ])->get();

            $data['assessment'] = DB::table('student_subjek')
                    ->join('tblclassassign_group', function($join){
                        $join->on('student_subjek.group_id', 'tblclassassign_group.groupid');
                        $join->on('student_subjek.group_name', 'tblclassassign_group.groupname');
                    })
                    ->join('tblclassassign', 'tblclassassign_group.assignid', 'tblclassassign.id')
                    ->join('students', 'student_subjek.student_ic', 'students.ic')
                    ->when(request()->intake != '', function($query){
                        return $query->where('students.intake', request()->intake);
                    })
                    ->select('student_subjek.*', 'tblclassassign.id AS clssid', 'tblclassassign.total_mark', 'students.no_matric', 'students.name')
                    ->where([
                        ['tblclassassign.id', request()->id]
                    ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.name')->get();

            foreach($data['assessment'] as $qz)
            {

                if(!DB::table('tblclassstudentassign')->where([['assignid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                    DB::table('tblclassstudentassign')->insert([
                        'assignid' => $qz->clssid,
                        'userid' => $qz->student_ic
                    ]);
    
                }

                $data['status'][] = DB::table('tblclassstudentassign')
                ->where([
                    ['assignid', $qz->clssid],
                    ['userid', $qz->student_ic]
                ])->first();
            }

            return view('pendaftar_akademik.student.assessment.assessmentStatus', compact('data'));

        }

        if(request()->type == 'midterm')
        {

            $data['group'] = DB::table('user_subjek')
                    ->join('tblclassmidterm_group', 'user_subjek.id', 'tblclassmidterm_group.groupid')
                    ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                    ->where([
                        ['tblclassmidterm.id', request()->id]
                    ])->get();

            $data['assessment'] = DB::table('student_subjek')
                    ->join('tblclassmidterm_group', function($join){
                        $join->on('student_subjek.group_id', 'tblclassmidterm_group.groupid');
                        $join->on('student_subjek.group_name', 'tblclassmidterm_group.groupname');
                    })
                    ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                    ->join('students', 'student_subjek.student_ic', 'students.ic')
                    ->when(request()->intake != '', function($query){
                        return $query->where('students.intake', request()->intake);
                    })
                    ->select('student_subjek.*', 'tblclassmidterm.id AS clssid', 'tblclassmidterm.total_mark', 'students.no_matric', 'students.name')
                    ->where([
                        ['tblclassmidterm.id', request()->id]
                    ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.name')->get();

            foreach($data['assessment'] as $qz)
            {

                if(!DB::table('tblclassstudentmidterm')->where([['midtermid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                    DB::table('tblclassstudentmidterm')->insert([
                        'midtermid' => $qz->clssid,
                        'userid' => $qz->student_ic
                    ]);
    
                }

                $data['status'][] = DB::table('tblclassstudentmidterm')
                ->where([
                    ['midtermid', $qz->clssid],
                    ['userid', $qz->student_ic]
                ])->first();
            }

            return view('pendaftar_akademik.student.assessment.assessmentStatus', compact('data'));

        }

        if(request()->type == 'other')
        {

            $data['group'] = DB::table('user_subjek')
                    ->join('tblclassother_group', 'user_subjek.id', 'tblclassother_group.groupid')
                    ->join('tblclassother', 'tblclassother_group.otherid', 'tblclassother.id')
                    ->where([
                        ['tblclassother.id', request()->id]
                    ])->get();

            $data['assessment'] = DB::table('student_subjek')
                    ->join('tblclassother_group', function($join){
                        $join->on('student_subjek.group_id', 'tblclassother_group.groupid');
                        $join->on('student_subjek.group_name', 'tblclassother_group.groupname');
                    })
                    ->join('tblclassother', 'tblclassother_group.otherid', 'tblclassother.id')
                    ->join('students', 'student_subjek.student_ic', 'students.ic')
                    ->when(request()->intake != '', function($query){
                        return $query->where('students.intake', request()->intake);
                    })
                    ->select('student_subjek.*', 'tblclassother.id AS clssid', 'tblclassother.total_mark', 'students.no_matric', 'students.name')
                    ->where([
                        ['tblclassother.id', request()->id]
                    ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.name')->get();

            foreach($data['assessment'] as $qz)
            {

                if(!DB::table('tblclassstudentother')->where([['otherid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                    DB::table('tblclassstudentother')->insert([
                        'otherid' => $qz->clssid,
                        'userid' => $qz->student_ic
                    ]);
    
                }

                $data['status'][] = DB::table('tblclassstudentother')
                ->where([
                    ['otherid', $qz->clssid],
                    ['userid', $qz->student_ic]
                ])->first();
            }

            return view('pendaftar_akademik.student.assessment.assessmentStatus', compact('data'));

        }

        if(request()->type == 'extra')
        {

            $data['group'] = DB::table('user_subjek')
                    ->join('tblclassextra_group', 'user_subjek.id', 'tblclassextra_group.groupid')
                    ->join('tblclassextra', 'tblclassextra_group.extraid', 'tblclassextra.id')
                    ->where([
                        ['tblclassextra.id', request()->id]
                    ])->get();

            $data['assessment'] = DB::table('student_subjek')
                    ->join('tblclassextra_group', function($join){
                        $join->on('student_subjek.group_id', 'tblclassextra_group.groupid');
                        $join->on('student_subjek.group_name', 'tblclassextra_group.groupname');
                    })
                    ->join('tblclassextra', 'tblclassextra_group.extraid', 'tblclassextra.id')
                    ->join('students', 'student_subjek.student_ic', 'students.ic')
                    ->when(request()->intake != '', function($query){
                        return $query->where('students.intake', request()->intake);
                    })
                    ->select('student_subjek.*', 'tblclassextra.id AS clssid', 'tblclassextra.total_mark', 'students.no_matric', 'students.name')
                    ->where([
                        ['tblclassextra.id', request()->id]
                    ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.name')->get();

            foreach($data['assessment'] as $qz)
            {

                if(!DB::table('tblclassstudentextra')->where([['extraid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                    DB::table('tblclassstudentextra')->insert([
                        'extraid' => $qz->clssid,
                        'userid' => $qz->student_ic
                    ]);
    
                }

                $data['status'][] = DB::table('tblclassstudentextra')
                ->where([
                    ['extraid', $qz->clssid],
                    ['userid', $qz->student_ic]
                ])->first();
            }

            return view('pendaftar_akademik.student.assessment.assessmentStatus', compact('data'));

        }

        if(request()->type == 'final')
        {

            $data['group'] = DB::table('user_subjek')
                    ->join('tblclassfinal_group', 'user_subjek.id', 'tblclassfinal_group.groupid')
                    ->join('tblclassfinal', 'tblclassfinal_group.finalid', 'tblclassfinal.id')
                    ->where([
                        ['tblclassfinal.id', request()->id]
                    ])->get();

            $data['assessment'] = DB::table('student_subjek')
                    ->join('tblclassfinal_group', function($join){
                        $join->on('student_subjek.group_id', 'tblclassfinal_group.groupid');
                        $join->on('student_subjek.group_name', 'tblclassfinal_group.groupname');
                    })
                    ->join('tblclassfinal', 'tblclassfinal_group.finalid', 'tblclassfinal.id')
                    ->join('students', 'student_subjek.student_ic', 'students.ic')
                    ->when(request()->intake != '', function($query){
                        return $query->where('students.intake', request()->intake);
                    })
                    ->select('student_subjek.*', 'tblclassfinal.id AS clssid', 'tblclassfinal.total_mark', 'students.no_matric', 'students.name')
                    ->where([
                        ['tblclassfinal.id', request()->id]
                    ])
                    ->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.name')
                    ->get();

            foreach($data['assessment'] as $qz)
            {

                if(!DB::table('tblclassstudentfinal')->where([['finalid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                    DB::table('tblclassstudentfinal')->insert([
                        'finalid' => $qz->clssid,
                        'userid' => $qz->student_ic
                    ]);
    
                }

                $data['status'][] = DB::table('tblclassstudentfinal')
                ->where([
                    ['finalid', $qz->clssid],
                    ['userid', $qz->student_ic]
                ])->first();
            }

            return view('pendaftar_akademik.student.assessment.assessmentStatus', compact('data'));

        }

    }

    public function updateAssessmentStatus(Request $request)
    {
        if($request->type == 'quiz')
        {

            $marks = json_decode($request->marks);

            $ics = json_decode($request->ics);

            $assessmentid = json_decode($request->assessmentid);

            $limitpercen = DB::table('tblclassquiz')->where('id', $assessmentid)->first();

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
                'quizid' => $assessmentid,
                'submittime' => date("Y-m-d H:i:s"),
                'final_mark' => $mrk,
                'status' => 1
                ]);
            }

            DB::table('tblclassstudentquiz')->upsert($upsert, ['userid', 'quizid']);

        }

        if($request->type == 'test')
        {

            $marks = json_decode($request->marks);

            $ics = json_decode($request->ics);

            $assessmentid = json_decode($request->assessmentid);

            $limitpercen = DB::table('tblclasstest')->where('id', $assessmentid)->first();

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
                'testid' => $assessmentid,
                'submittime' => date("Y-m-d H:i:s"),
                'final_mark' => $mrk,
                'status' => 1
                ]);
            }

            DB::table('tblclassstudenttest')->upsert($upsert, ['userid', 'testid']);

        }

        if($request->type == 'assignment')
        {

            $marks = json_decode($request->marks);

            $ics = json_decode($request->ics);

            $assessmentid = json_decode($request->assessmentid);

            $limitpercen = DB::table('tblclassassign')->where('id', $assessmentid)->first();

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
                'assignid' => $assessmentid,
                'submittime' => date("Y-m-d H:i:s"),
                'final_mark' => $mrk,
                'status' => 1
                ]);
            }

            DB::table('tblclassstudentassign')->upsert($upsert, ['userid', 'assignid']);

        }

        if($request->type == 'midterm')
        {

            $marks = json_decode($request->marks);

            $ics = json_decode($request->ics);

            $assessmentid = json_decode($request->assessmentid);

            $limitpercen = DB::table('tblclassmidterm')->where('id', $assessmentid)->first();

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
                'midtermid' => $assessmentid,
                'submittime' => date("Y-m-d H:i:s"),
                'final_mark' => $mrk,
                'status' => 1
                ]);
            }

            DB::table('tblclassstudentmidterm')->upsert($upsert, ['userid', 'midtermid']);

        }

        if($request->type == 'other')
        {

            $marks = json_decode($request->marks);

            $ics = json_decode($request->ics);

            $assessmentid = json_decode($request->assessmentid);

            $limitpercen = DB::table('tblclassother')->where('id', $assessmentid)->first();

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
                'otherid' => $assessmentid,
                'submittime' => date("Y-m-d H:i:s"),
                'final_mark' => $mrk,
                'status' => 1
                ]);
            }

            DB::table('tblclassstudentother')->upsert($upsert, ['userid', 'otherid']);

        }

        if($request->type == 'extra')
        {

            $marks = json_decode($request->marks);

            $ics = json_decode($request->ics);

            $assessmentid = json_decode($request->assessmentid);

            $limitpercen = DB::table('tblclassextra')->where('id', $assessmentid)->first();

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
                'extraid' => $assessmentid,
                'submittime' => date("Y-m-d H:i:s"),
                'final_mark' => $mrk,
                'status' => 1
                ]);
            }

            DB::table('tblclassstudentextra')->upsert($upsert, ['userid', 'extraid']);

        }

        if($request->type == 'final')
        {

            $marks = json_decode($request->marks);

            $ics = json_decode($request->ics);

            $assessmentid = json_decode($request->assessmentid);

            $limitpercen = DB::table('tblclassfinal')->where('id', $assessmentid)->first();

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
                'finalid' => $assessmentid,
                'submittime' => date("Y-m-d H:i:s"),
                'final_mark' => $mrk,
                'status' => 1
                ]);
            }

            DB::table('tblclassstudentfinal')->upsert($upsert, ['userid', 'finalid']);

        }

        return ["message"=>"Success", "id" => $ics];

    }

    public function getSubjectLecturer(Request $request)
    {

        $session = DB::table('sessions')->where('status', 'ACTIVE')->pluck('SessionID')->toArray();

        $subject = DB::table('user_subjek')
                   ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                   ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                   ->where([
                      ['user_subjek.user_ic', $request->lecturerId]
                   ])
                   ->whereIn('session_id', $session)
                   ->select('subjek.course_name AS name','subjek.course_code AS code','sessions.SessionName AS session', 'user_subjek.id AS id')->get();

        return response()->json($subject);

    }

    public function getGroupLecturer(Request $request)
    {

        $group = DB::table('student_subjek')
                 ->where([
                    ['student_subjek.group_id', $request->groupID]
                 ])
                 ->groupBy('group_name')->get();

        return response()->json($group);

    }

    public function transcript()
    {

        return view('pendaftar_akademik.student.transcript.transcript');

    }

    public function getStudentTranscript(Request $request)
    {
        $students = DB::table('students')
            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
            ->join('sessions AS a', 'students.intake', 'a.SessionID')
            ->join('sessions AS b', 'students.session', 'b.SessionID')
            ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
            ->select('students.*', 'tblprogramme.progname', 'a.SessionName AS intake', 
                     'b.SessionName AS session', 'tblstudent_status.name AS status')
            ->where('students.name', 'LIKE', "%".$request->search."%")
            ->orwhere('students.ic', 'LIKE', "%".$request->search."%")
            ->orwhere('students.no_matric', 'LIKE', "%".$request->search."%")->get();

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
                                No. IC
                            </th>
                            <th>
                                No. Matric
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($students as $key => $student){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td>
                '. $student->name .'
                </td>
                <td>
                '. $student->ic .'
                </td>
                <td>
                '. $student->no_matric .'
                </td>
                <td>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="date" class="form-control" id="date-'. $student->ic .'">
                    </div>
                  </div>
                </td>
                <td>';
                
                $content .= '<a class="btn btn-primary btn-sm btn-sm mr-2 mb-2" onClick="print(\''. $student->ic .'\')">
                                <i class="ti-ruler-pencil">
                                </i>
                                Print
                            </a>';

                $content .= '</td></tr>';

            }

            $content .= '</tbody>';

            return $content;

    }

    public function printStudentTranscript(Request $request)
    {
        // Set Carbon's locale to Malay
        Carbon::setLocale('ms');

        $classdateParsed = Carbon::parse($request->date);

        $data['date'] = $classdateParsed->isoFormat('D MMMM Y');

        $data['student'] = DB::table('students')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->select('students.*', 'tblprogramme.progname AS program', 'tblprogramme.mqa_code AS mqa')
                           ->where('students.ic', $request->ic)
                           ->first();

        $data['semesters'] = DB::table('student_subjek')
                             ->groupBy('semesterid')
                             ->where([
                                ['student_ic', $request->ic],
                                ['group_id','!=', null],
                                ['grade', '!=', null]
                                ])
                             ->pluck('semesterid');

        foreach($data['semesters'] as $key => $sm)
        {

            $data['course'][$key] = DB::table('student_subjek')
                                    ->leftJoin('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                                    ->where([
                                        ['student_subjek.student_ic', $request->ic],
                                        ['student_subjek.semesterid', $sm]
                                    ])
                                    ->groupBy('student_subjek.id')
                                    ->select('student_subjek.*','subjek.course_name', 'subjek.course_code')
                                    ->get();

            $data['detail'][$key] = DB::table('student_transcript')
                                    ->leftJoin('sessions', 'sessions.SessionID', 'student_transcript.session_id')
                                    ->where([
                                        ['student_ic', $request->ic],
                                        ['semester', $sm]
                                    ])
                                    ->select('student_transcript.*', 'sessions.SessionName AS session')
                                    ->first();

        }

        $lastDetail = end($data['detail']); // Get the last element of the $data['detail'] array
        $data['lastCGPA'] = $lastDetail->cgpa ?? null; // Access the `cgpa` value

        return view('pendaftar_akademik.student.transcript.printTranscript', compact('data'));

    }

    public function miniTranscript()
    {

        return view('pendaftar_akademik.student.mini_transcript.miniTranscript');

    }

    public function printStudentMiniTranscript(Request $request)
    {
        // Set Carbon's locale to Malay
        Carbon::setLocale('ms');

        $classdateParsed = Carbon::parse($request->date);

        $data['date'] = $classdateParsed->isoFormat('D MMMM Y');

        $data['student'] = DB::table('students')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS a', 'students.intake', 'a.SessionID')
                           ->join('sessions AS b', 'students.session', 'b.SessionID')
                           ->join('tblfaculty', 'tblprogramme.facultyid', 'tblfaculty.id')
                           ->select('students.*', 'tblprogramme.progname AS program', 'tblprogramme.mqa_code AS mqa', 'a.SessionName AS intake', 'b.SessionName AS session', 'tblfaculty.facultyname AS faculty')
                           ->where('students.ic', $request->ic)
                           ->first();

        $data['semesters'] = DB::table('student_subjek')
                             ->groupBy('semesterid')
                             ->where([
                                ['student_ic', $request->ic],
                                ['group_id','!=', null],
                                ['grade', '!=', null]
                                ])
                             ->pluck('semesterid');

        foreach($data['semesters'] as $key => $sm)
        {

            $data['course'][$key] = DB::table('student_subjek')
                                    ->leftJoin('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                                    ->where([
                                        ['student_subjek.student_ic', $request->ic],
                                        ['student_subjek.semesterid', $sm]
                                    ])
                                    ->groupBy('student_subjek.id')
                                    ->select('student_subjek.*','subjek.course_name', 'subjek.course_code')
                                    ->get();

            $data['detail'][$key] = DB::table('student_transcript')
                                    ->leftJoin('sessions', 'sessions.SessionID', 'student_transcript.session_id')
                                    ->where([
                                        ['student_ic', $request->ic],
                                        ['semester', $sm]
                                    ])
                                    ->select('student_transcript.*', 'sessions.SessionName AS session')
                                    ->first();

        }

        $lastDetail = end($data['detail']); // Get the last element of the $data['detail'] array
        $data['lastCGPA'] = $lastDetail->cgpa ?? null; // Access the `cgpa` value

        return view('pendaftar_akademik.student.mini_transcript.printMiniTranscript', compact('data'));

    }

    public function resultOverall()
    {
        $data = [
            'program' => DB::table('tblprogramme')->get(),
            'session' => DB::table('sessions')->get(),
            'semester' => DB::table('semester')->get(),
            'period' => DB::table('tblresult_period')->first(),
            'program_data' => DB::table('tblresult_program')->get(),
            'session_data' => DB::table('tblresult_session')->get(),
            'semester_data' => DB::table('tblresult_semester')->get()
        ];

        return view('pendaftar_akademik.student.result_overall.resultOverall', compact('data'));

    }

    public function resultOverallSubmit(Request $request)
    {

        $data = json_decode($request->submitData);

        DB::table('tblresult_period')->upsert([
            'id' => 1,
            'Start' => $data->from,
            'END' => $data->to
        ],['id']);

        DB::table('tblresult_program')->truncate();

        DB::table('tblresult_session')->truncate();

        DB::table('tblresult_semester')->truncate();

        foreach($data->program as $prg)
        {
            DB::table('tblresult_program')->insert([
                'program_id' => $prg
            ]);
        }

        foreach($data->session as $ses)
        {
            DB::table('tblresult_session')->insert([
                'session_id' => $ses
            ]);
        }

        foreach($data->semester as $sem)
        {
            DB::table('tblresult_semester')->insert([
                'semester_id' => $sem
            ]);
        }

        return response()->json(['success' => 'Data has been updated successfully!']);

    }

    public function assessmentFilter()
    {
        $data = [
            'session' => DB::table('sessions')->get(),
            'lecturer' => DB::table('users')->whereIn('usrtype', ['LCT', 'AO', 'PL'])->get(),
            'period' => DB::table('tblassessment_period')->first()
        ];

        return view('pendaftar_akademik.student.assessment_filter.assessmentFilter', compact('data'));
    }

    public function assessmentFilterSubmit(Request $request)
    {
        $data = json_decode($request->submitData);

        DB::table('tblassessment_period')->upsert([
            'id' => 1,
            'Start' => $data->from,
            'END' => $data->to,
            'session' => json_encode($data->session),
            'user_ic' => json_encode($data->lecturer)
        ],['id']);

        return response()->json(['success' => 'Data has been updated successfully!']);

    }

}
