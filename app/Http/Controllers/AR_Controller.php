<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subject;
use App\Models\student;
use App\Models\User;
use App\Models\UserStudent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;

class AR_Controller extends Controller
{
    public function courseList()
    {
        Session::put('User', Auth::user());

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

        $getCourse =  DB::table('student_subjek')->join('students', 'student_subjek.student_ic', 'students.ic')->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')->where('students.ic', $data['student']->ic);

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

        $getCourse =  DB::table('student_subjek')->join('students', 'student_subjek.student_ic', 'students.ic')->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')->where('students.ic', $data['student']->ic);

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

        $getCourse =  DB::table('student_subjek')->join('students', 'student_subjek.student_ic', 'students.ic')->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')->where('students.ic', $data['student']->ic);

        $data['allCourse'] = $getCourse->select('student_subjek.id as IDS','student_subjek.courseid', 'subjek.*')->orderBy('subjek.semesterid')->get();

        $crsExists = $getCourse->pluck('student_subjek.courseid')->toArray();

        $data['regCourse'] = DB::table('subjek')->whereNotIn('sub_id', $crsExists)->whereIn('semesterid', $loop)->where('prgid', $data['student']->program)->orderBy('semesterid')->get();

        return view('pendaftar_akademik.getAllCourse', compact('data'));

    }
}
