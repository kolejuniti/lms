<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\student;
use App\Models\User;
use App\Models\subject;

class PendaftarController extends Controller
{
    public function index()
    {
        $student = DB::table('students')->get();

        $program = DB::table('tblprogramme')->get();

        $session = DB::table('sessions')->get();

        return view('pendaftar', compact('student', 'program', 'session'));
    }

    public function create()
    {  
        $program = DB::table('tblprogramme')->get();

        $session = DB::table('sessions')->get();

        return view('pendaftar.create', compact(['program','session']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string'],
            'ic' => ['required','string'],
            'matric' => ['required','string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'session' => ['required'],
            'program' => ['required'],
        ]);

        DB::table('students')->insert([
            'name' => $data['name'],
            'ic' => $data['ic'],
            'no_matric' => $data['matric'],
            'email' => $data['email'],
            'intake' => $data['session'],
            'program' => $data['program'],
            'password' => Hash::make('12345678'),
            'status' => 'ACTIVE',
        ]);

        $subject = DB::table('subjek')->where([
            ['prgid','=', $data['program']],
            ['semesterid','=', 1],
        ])->get();

        foreach($subject as $key)
        {
            student::create([
                'student_ic' => $data['ic'],
                'courseid' => $key->sub_id,
                'sessionid' => $data['session'],
                'semesterid' => 1,
                'status' => 'ACTIVE'
            ]);
        }

        return redirect(route('pendaftar'));
    }


    public function getSubjectOption(Request $request){
        $subject = DB::table('subjek')->where('prgid', $request->program)->get();

        $content = "";

        $content .= "<option value='-' disabled selected>-</option>";
        foreach($subject as $sbj){
            $content .= '<option value='. $sbj->sub_id .'>
            '. $sbj->course_name.'</option>';
        };
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

        $students = DB::table('students')->where('program', $request->program)->get();

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                            No.
                            </th>
                            <th style="width: 15%">
                                Name
                            </th>
                            <th style="width: 15%">
                                No. IC
                            </th>
                            <th style="width: 30%">
                                No. Matric
                            </th>
                            <th style="width: 10%">
                                Program
                            </th>
                            <th style="width: 20%">
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
                <td style="width: 15%">
                '. $student->name .'
                </td>
                <td style="width: 15%">
                '. $student->ic .'
                </td>
                <td style="width: 30%">
                '. $student->no_matric .'
                </td>
                <td>
                '. $student->program .'
                </td>
                <td class="project-actions text-right" >
                <a class="btn btn-info btn-sm btn-sm mr-2" href="#">
                    <i class="ti-pencil-alt">
                    </i>
                    Edit
                </a>
                <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial(\''. $student->ic .'\')">
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

    public function delete(Request $request)
    {

        DB::table('students')->where('ic', $request->id)->delete();

        return true;

    }
}
