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
            'course' => DB::table('subjek')->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->select('subjek.*', 'tblprogramme.progname')->get(),
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
                        <th style="width: 10%">
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
                <td>
                '. $crs->progname .'
                </td>
                <td>
                '. $crs->semesterid .'
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
            'id' => ['required'],
            'name' => ['required'],
            'code' => ['required','string'],
            'credit' => ['required'],
            'program2' => ['required'],
            'semester' => ['required']
        ]);

        //dd($request->idS);


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

        }else{

            DB::table('subjek')->insert([
                'sub_id' => $data['id'],
                'course_name' => $data['name'],
                'course_code' => $data['code'],
                'course_credit' => $data['credit'],
                'prgid' => $data['program2'],
                'semesterid' => $data['semester']
            ]);
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
            'course' => DB::table('subjek')->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')->select('subjek.*', 'tblprogramme.progname')->where('subjek.id', $request->id)->first(),
            'program' => DB::table('tblprogramme')->get()
        ];

        return view('pendaftar_akademik.getCourse', compact('data'))->with('id', $request->id);

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

        //dd($student);

        $getCourse =  DB::table('student_subjek')
                      ->join('students', 'student_subjek.student_ic', 'students.ic')
                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                      ->where('students.ic', $data['student']->ic)
                      ->where('subjek.prgid', $data['student']->program);

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS','student_subjek.courseid', 'subjek.*')->orderBy('subjek.semesterid')->get();

        $crsExists = $getCourse->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)->whereIn('semesterid', $loop)->where('prgid', $data['student']->program)->orderBy('semesterid')->get();

        return view('pendaftar_akademik.getAllCourse', compact('data'));
    }

    public function registerCourse(Request $request)
    {

        $data['student'] = UserStudent::where('ic', $request->ic)->first();

        $course = DB::table('subjek')->where('id', $request->id)->first();

        DB::table('student_subjek')->insert([
            'student_ic' => $data['student']->ic,
            'courseid' => $course->sub_id,
            'sessionid' => $data['student']->session,
            'semesterid' => $course->semesterid,
            'status' => 'ACTIVE'
        ]);


        for($i = 0; $i <= $data['student']->semester; $i++)
        {
            $loop[] = $i;
        }

        //dd($student);

        $getCourse =  DB::table('student_subjek')
                      ->join('students', 'student_subjek.student_ic', 'students.ic')
                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                      ->where('students.ic', $data['student']->ic)
                      ->where('subjek.prgid', $data['student']->program);

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS','student_subjek.courseid', 'subjek.*')->orderBy('subjek.semesterid')->get();

        $crsExists = $getCourse->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)->whereIn('semesterid', $loop)->where('prgid', $data['student']->program)->orderBy('semesterid')->get();

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

        //dd($student);

        $getCourse =  DB::table('student_subjek')
                      ->join('students', 'student_subjek.student_ic', 'students.ic')
                      ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                      ->where('students.ic', $data['student']->ic)
                      ->where('subjek.prgid', $data['student']->program);

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS','student_subjek.courseid', 'subjek.*')->orderBy('subjek.semesterid')->get();

        $crsExists = $getCourse->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)->whereIn('semesterid', $loop)->where('prgid', $data['student']->program)->orderBy('semesterid')->get();

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

        return ['message' => 'success'];

    }

    public function updateCampus(Request $request)
    {

        DB::table('students')->whereIn('no_matric', $request->campus)->update([
            'campus_id' => 1,
        ]);

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
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
                           ->where('ic', $request->student)->first();

        $data['session'] = DB::table('sessions')->get();

        $data['semester'] = DB::table('semester')->get();

        return view('pendaftar_akademik.semester.semesterGetStudent', compact('data'));
        
    }

    public function updateSemester(Request $request)
    {

        $student = DB::table('students')->where('no_matric', $request->no_matric)->first();

        if($request->session != '')
        {

            DB::table('students')->where('no_matric', $request->no_matric)->update([
                'session' => $request->session,
                'semester' => $student->semester + 1
            ]);

        }else{

            return ['message' => 'Please fill all required field!'];

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

        return response()->json($events);
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
}
