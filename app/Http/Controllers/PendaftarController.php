<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\student;
use App\Models\User;
use App\Models\UserStudent;
use App\Models\subject;
use Input;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PendaftarController extends Controller
{
    public function dashboard()
    {
        Session::put('User', Auth::user());

        return view('dashboard');
    }
    
    public function index()
    {
        $year = DB::table('tblyear')->get();
        
        $program = DB::table('tblprogramme')->get();

        $session = DB::table('sessions')->get();

        $semester = DB::table('semester')->get();

        $status = DB::table('tblstudent_status')->get();

        return view('pendaftar', compact('program', 'session', 'semester', 'year', 'status'));
    }

    public function studentEdit()
    {
        $year = DB::table('tblyear')->get();

        $program = DB::table('tblprogramme')->get();

        $session = DB::table('sessions')->get();

        $semester = DB::table('semester')->get();

        $status = DB::table('tblstudent_status')->get();

        return view('pendaftar.studentEdit', compact('program', 'session', 'semester', 'year', 'status'));

    }

    
    public function getStudentTableIndex(Request $request)
    {
        $student = DB::table('students')
            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
            ->join('sessions AS a', 'students.intake', 'a.SessionID')
            ->join('sessions AS b', 'students.session', 'b.SessionID')
            ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
            ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
            ->leftJoin('tblqualification_std', 'tblstudent_personal.qualification', 'tblqualification_std.id')
            ->join('tblsex', 'tblstudent_personal.sex_id', 'tblsex.id')
            ->select('students.*', 'tblprogramme.progcode', 'a.SessionName AS intake', 
                     'b.SessionName AS session', 'tblstudent_status.name AS status',
                     'tblstudent_personal.no_tel', 'tblsex.code AS gender', 'tblqualification_std.name AS qualification');

        if(!empty($request->program) && $request->program != '-')
        {
            $student->where('students.program', $request->program);
        }
        
        if(!empty($request->session) && $request->session != '-')
        {
            $student->where('students.session', $request->session);
        }
        
        if(!empty($request->year) && $request->year != '-')
        {
            $student->where('b.Year', $request->year);
        }
        
        if(!empty($request->semester) && $request->semester != '-')
        {
            $student->where('students.semester', $request->semester);
        }
        
        if(!empty($request->status) && $request->status != '-')
        {
            $student->where('students.status', $request->status);
        }

        $students = $student->get();

        foreach($students as $key => $std)
        {

            $sponsor_id[$key] = DB::table('tblpayment')
                                ->where([
                                    ['student_ic', $std->ic],
                                    ['sponsor_id', '!=', null]
                                    ])
                                ->latest('id')->first();

            if($sponsor_id[$key] != null)
            {

                $sponsor[$key] = DB::table('tblpayment')
                                ->join('tblsponsor_library', 'tblpayment.payment_sponsor_id', 'tblsponsor_library.id')
                                ->where('tblpayment.id', $sponsor_id[$key]->sponsor_id)->pluck('tblsponsor_library.code')->first();

            }else{

                $sponsor[$key] = 'SENDIRI';

            }

            if($std->student_status == 1)
            {

                $student_status[$key] = 'Holding';

            }elseif($std->student_status == 2)
            {

                $student_status[$key] = 'Kuliah';

            }elseif($std->student_status == 4)
            {

                $student_status[$key] = 'Latihan Industri';

            }

        }

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
                                Gender
                            </th>
                            <th>
                                No. IC
                            </th>
                            <th>
                                No. Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Intake
                            </th>
                            <th>
                                Current Session
                            </th>
                            <th>
                                Semester
                            </th>
                            <th>
                                Sponsorship
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                No. Phone
                            </th>
                            <th>
                                Campus
                            </th>
                            <th>
                                Qualification
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
                '. $student->gender .'
                </td>
                <td>
                '. $student->ic .'
                </td>
                <td>
                '. $student->no_matric .'
                </td>
                <td>
                '. $student->progcode .'
                </td>
                <td>
                '. $student->intake .'
                </td>
                <td>
                '. $student->session .'
                </td>
                <td>
                '. $student->semester .'
                </td>
                <td>
                '. $sponsor[$key] .'
                </td>
                <td>
                '. $student->status .'
                </td>
                <td>
                '. $student->no_tel .'
                </td>
                <td>
                '. $student_status[$key] .'
                </td>
                <td>
                '. $student->qualification .'
                </td>';
                

                if (isset($request->edit)) {
                    $content .= '<td class="project-actions text-right" >
                                <a class="btn btn-info btn-sm btn-sm mr-2 mb-2" href="/pendaftar/view/'. $student->ic .'">
                                    <i class="ti-pencil-alt">
                                    </i>
                                    View
                                </a>
                                <a class="btn btn-info btn-sm btn-sm mr-2 mb-2" href="/pendaftar/edit/'. $student->ic .'">
                                    <i class="ti-pencil-alt">
                                    </i>
                                    Edit
                                </a>
                                <a class="btn btn-primary btn-sm btn-sm mr-2 mb-2" href="/pendaftar/spm/'. $student->ic .'">
                                    <i class="ti-ruler-pencil">
                                    </i>
                                    SPM/SVM/SKM
                                </a>
                                <a class="btn btn-secondary btn-sm btn-sm mr-2 mb-2" href="#" onclick="getProgram(\''. $student->ic .'\')">
                                    <i class="ti-eye">
                                    </i>
                                    Program History
                                </a>
                                <a class="btn btn-secondary btn-sm btn-sm mr-2 mb-2" target="_blank" href="/AR/student/getSlipExam?student='. $student->ic .'">
                                    <i class="fa fa-info">
                                    </i>
                                    Slip Exam
                                </a>
                                <!-- <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('. $student->ic .')">
                                    <i class="ti-trash">
                                    </i>
                                    Delete
                                </a> -->
                                </td>
                            
                            ';
                }else{
                    $content .= '<td class="project-actions text-right" >
                    <a class="btn btn-secondary btn-sm btn-sm mr-2" href="#" onclick="getProgram(\''. $student->ic .'\')">
                        <i class="ti-eye">
                        </i>
                        Program History
                    </a>
                    </td>
                
                ';

                }
            }
            $content .= '</tr></tbody>';

            return $content;

    }

    public function getStudentTableIndex2(Request $request)
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

        // if(!empty($request->program))
        // {
        //     $student->where('students.program', $request->program);
        // }
        
        // if(!empty($request->session))
        // {
        //     $student->where('students.session', $request->session);
        // }
        
        // if(!empty($request->year))
        // {
        //     $student->where('a.Year', $request->year);
        // }
        
        // if(!empty($request->semester))
        // {
        //     $student->where('students.semester', $request->semester);
        // }
        
        // if(!empty($request->status))
        // {
        //     $student->where('students.status', $request->status);
        // }

        // $students = $student->get();

        foreach($students as $key => $std)
        {

            $sponsor_id[$key] = DB::table('tblpayment')
                                ->where([
                                    ['student_ic', $std->ic],
                                    ['sponsor_id', '!=', null]
                                    ])
                                ->latest('id')->first();

            if($sponsor_id[$key] != null)
            {

                $sponsor[$key] = DB::table('tblpayment')
                                ->join('tblsponsor_library', 'tblpayment.payment_sponsor_id', 'tblsponsor_library.id')
                                ->where('tblpayment.id', $sponsor_id[$key]->sponsor_id)->pluck('tblsponsor_library.name')->first();

            }else{

                $sponsor[$key] = 'SENDIRI';

            }

            if($std->student_status == 1)
            {

                $student_status[$key] = 'Holding';

            }elseif($std->student_status == 2)
            {

                $student_status[$key] = 'Kuliah';

            }elseif($std->student_status == 4)
            {

                $student_status[$key] = 'Latihan Industri';

            }

        }

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
                                Program
                            </th>
                            <th>
                                Intake
                            </th>
                            <th>
                                Current Session
                            </th>
                            <th>
                                Semester
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
                '. $student->progname .'
                </td>
                <td>
                '. $student->intake .'
                </td>
                <td>
                '. $student->session .'
                </td>
                <td>
                '. $student->semester .'
                </td>';
                

            
                $content .= '<td class="project-actions text-right" >
                            <a class="btn btn-success btn-sm btn-sm mr-2 mb-2" href="/pendaftar/view/'. $student->ic .'">
                                <i class="ti-info-alt">
                                </i>
                                View
                            </a>
                            ';
                            if(Auth::user()->usrtype == "RGS" || Auth::user()->usrtype == "FN")
                            {
                $content .= '<a class="btn btn-info btn-sm btn-sm mr-2 mb-2" href="/pendaftar/edit/'. $student->ic .'">
                                <i class="ti-pencil-alt">
                                </i>
                                Edit
                            </a>
                            ';
                            }
                            if(Auth::user()->usrtype == "RGS")
                            {
                $content .= '<a class="btn btn-primary btn-sm btn-sm mr-2 mb-2" href="/pendaftar/spm/'. $student->ic .'">
                                <i class="ti-ruler-pencil">
                                </i>
                                SPM/SVM/SKM
                            </a>
                            <a class="btn btn-secondary btn-sm btn-sm mr-2 mb-2" href="#" onclick="getProgram(\''. $student->ic .'\')">
                                <i class="ti-eye">
                                </i>
                                Program History
                            </a>
                            <a class="btn btn-warning btn-sm btn-sm mr-2 mb-2" target="_blank" href="/AR/student/getSlipExam?student='. $student->ic .'">
                                <i class="fa fa-info">
                                </i>
                                Slip Exam
                            </a>
                            <!-- <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial(\''. $student->ic .'\')">
                                <i class="ti-trash">
                                </i>
                                Delete
                            </a> -->';
                            }
                $content .= '</td>';
           
            }
            $content .= '</tr></tbody>';

            return $content;

    }

    public function delete(Request $request)
    {

        DB::table('students')->where('ic', $request->id)->delete();

        DB::table('tblstudent_address')->where('student_ic', $request->id)->delete();

        DB::table('tblstudent_personal')->where('student_ic', $request->id)->delete();

        return true;

    }

    public function create()
    {  
        $program = DB::table('tblprogramme')->get();

        $session = DB::table('sessions')->get();

        $data['batch'] = DB::table('tblbatch')->get();

        $data['state'] = DB::table('tblstate')->orderBy('state_name')->get();

        $data['gender'] = DB::table('tblsex')->get();

        $data['race'] = DB::table('tblnationality')->orderBy('nationality_name')->get();

        $data['relationship'] = DB::table('tblrelationship')->get();

        $data['wstatus'] = DB::table('tblwaris_status')->get();

        $data['religion'] =  DB::table('tblreligion')->orderBy('religion_name')->get();

        $data['CL'] = DB::table('tblcitizenship_level')->get();

        $data['citizen'] = DB::table('tblcitizenship')->get();

        $data['mstatus'] = DB::table('tblmarriage')->get();

        $data['EA'] = DB::table('tbledu_advisor')->get();

        $data['pass'] = DB::table('tblpass_type')->get();

        $data['country'] = DB::table('tblcountry')->get();
        
        $data['dun'] = DB::table('tbldun')->orderBy('name')->get();

        $data['parlimen'] = DB::table('tblparlimen')->orderBy('name')->get();

        $data['qualification'] = DB::table('tblqualification_std')->get();

        //dd($data['race']);

        return view('pendaftar.create', compact(['program','session','data']));
    }

    public function createSearch(Request $request)
    {

        $students = DB::table('students')->where('name', 'LIKE', "%".$request->search."%")
                                         ->orwhere('ic', 'LIKE', "%".$request->search."%")
                                         ->orwhere('no_matric', 'LIKE', "%".$request->search."%")->get();

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                IC
                            </th>
                            <th>
                                No. Matric
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($students as $key => $std){
            //$registered = ($std->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td>
                '. $std->name .'
                </td>
                <td>
                '. $std->ic .'
                </td>
                <td>
                '. $std->no_matric .'
                </td>
            </tr>
            ';
            }
        $content .= '</tbody>';
                
         

        return $content;

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string'],
            'session' => ['required'],
            'batch' => ['required'],
            'program' => ['required'],
        ]);

        if($request->ic != '')
        {

            $data['id'] = $request->ic;

        }elseif($request->passport != '')
        {

            $data['id'] = $request->passport;

        }

        if(DB::table('students')->where('ic', $data['id'])->exists())
        {

            return false;

        }else{

            //dd($request);

            DB::table('students')->insert([
                'name' => $data['name'],
                'ic' => $data['id'],
                'no_matric' => null,
                'email' =>$request->email,
                'intake' => $data['session'],
                'batch' => $data['batch'],
                'session' => $data['session'],
                'semester' => 1,
                'program' => $data['program'],
                'password' => Hash::make('12345678'),
                'status' => 1,
                'campus_id' => 0,
                'date_offer' => $request->dol,
                'student_status' => 1,
                'stafID_add' => Auth::user()->ic,
                'date_add' => date('Y-m-d'),
                'stafID_mod' => Auth::user()->ic,
                'date_mod' => date('Y-m-d')
            ]);

            DB::table('tblstudent_log')->insert([
                'student_ic' => $data['id'],
                'session_id' => $data['session'],
                'semester_id' => 1,
                'status_id' => 1,
                'kuliah_id' => 0,
                'date' => date("Y-m-d H:i:s"),
                'remark' => null,
                'add_staffID' => Auth::user()->ic
            ]);

            DB::table('tblstudent_personal')->insert([
                'student_ic' => $data['id'],
                'date_birth' => $request->birth_date,
                'advisor_id' => $request->EA,
                'bank_name' => $request->bank_name,
                'bank_no' => $request->bank_number,
                'ptptn_no' => $request->PN,
                'datetime' => $request->dt,
                'religion_id' => $request->religion,
                'nationality_id' => $request->race,
                'sex_id' => $request->gender,
                'state_id' => $request->birth_place,
                'marriage_id' => $request->mstatus,
                'statelevel_id' => $request->CL,
                'citizenship_id' => $request->citizen,
                'no_tel' => $request->np1,
                'no_tel2' => $request->np2,
                'no_telhome' => $request->np3,
                'dun' => $request->dun,
                'parlimen' => $request->parlimen,
                'qualification' => $request->qualification,
                'oku' => $request->oku,
                'no_jkm' => $request->jkm
            ]);

            DB::table('tblstudent_pass')->insert([
                'student_ic' => $data['id'],
                'pass_type' => $request->pt,
                'pass_no' => $request->spn,
                'date_issued' => $request->di,
                'date_expired' => $request->de
            ]);

            DB::table('tblstudent_address')->insert([
                'student_ic' => $data['id'],
                'address1' => $request->address1,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'state_id' => $request->state,
                'country_id' => $request->country
            ]);

            // $numWaris = count($request->input('w_name'));
            // for ($i = 0; $i < $numWaris; $i++) {

            //     if($request->input('w_name')[$i] != '')
            //     {
            //         DB::table('tblstudent_waris')->insert([
            //             'student_ic' => $data['id'],
            //             'name' => $request->input('w_name')[$i],
            //             'ic' => $request->input('w_ic')[$i],
            //             'home_tel' => $request->input('w_notel_home')[$i],
            //             'phone_tel' => $request->input('w_notel')[$i],
            //             'occupation' => $request->input('occupation')[$i],
            //             'dependent_no' => $request->input('dependent')[$i],
            //             'kasar' => $request->input('w_kasar')[$i],
            //             'bersih' => $request->input('w_bersih')[$i],
            //             'relationship' => $request->input('relationship')[$i],
            //             'race' => $request->input('w_race')[$i],
            //             'status' => $request->input('w_status')[$i]
            //         ]);
            //     }
            // }

            DB::table('student_form')->insert([
                'student_ic' => $data['id'],
                'main' => $request->main,
                'pre_registration' => $request->PR,
                // 'c19' => $request->c19,
                'complete_form' => $request->CF,
                'copy_ic' => $request->copyic,
                'copy_birth' => $request->copybc,
                'copy_spm' => $request->copyspm,
                'copy_school' => $request->coppysc,
                'copy_pic' => $request->copypic,
                'copy_pincome' => $request->copypp
            ]);

            /*$subject = DB::table('subjek')->where([
                ['prgid','=', $data['program']],
                ['semesterid','=', 1],
            ])->get();

            foreach($subject as $key)
            {
                student::create([
                    'student_ic' => $data['id'],
                    'courseid' => $key->sub_id,
                    'sessionid' => $data['session'],
                    'semesterid' => 1,
                    'status' => 'ACTIVE'
                ]);
            }*/

            //$this->suratTawaran($data['id']);

        }

        return redirect(route('pendaftar.create'))->with('newStud', $data['id']);
    }

    public function view()
    {
        $student = DB::table('students')
                   ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                   ->leftjoin('tblstudent_address', 'students.ic', 'tblstudent_address.student_ic')
                   ->leftjoin('tblstudent_pass', 'students.ic', 'tblstudent_pass.student_ic')
                   ->leftjoin('student_form', 'students.ic', 'student_form.student_ic')
                   ->join('sessions', 'students.session', 'sessions.SessionID')
                   ->select('students.*', 'tblstudent_personal.*', 'tblstudent_address.*', 'tblstudent_pass.*', 'student_form.*', 'tblstudent_personal.state_id AS place_birth', 'sessions.SessionName AS session')
                   ->where('ic',request()->ic)->first();

        $data['waris'] = DB::table('tblstudent_waris')->where('student_ic', $student->ic)->get();

        //dd($data['waris']);

        $program = DB::table('tblprogramme')->get();

        $session = DB::table('sessions')->get();

        $data['batch'] = DB::table('tblbatch')->get();

        $data['state'] = DB::table('tblstate')->orderBy('state_name')->get();

        $data['gender'] = DB::table('tblsex')->get();

        $data['race'] = DB::table('tblnationality')->orderBy('nationality_name')->get();

        $data['relationship'] = DB::table('tblrelationship')->get();

        $data['wstatus'] = DB::table('tblwaris_status')->get();

        $data['religion'] =  DB::table('tblreligion')->orderBy('religion_name')->get();

        $data['CL'] = DB::table('tblcitizenship_level')->get();

        $data['citizen'] = DB::table('tblcitizenship')->get();

        $data['mstatus'] = DB::table('tblmarriage')->get();

        $data['EA'] = DB::table('tbledu_advisor')->get();

        $data['pass'] = DB::table('tblpass_type')->get();

        $data['country'] = DB::table('tblcountry')->get();
        
        $data['dun'] = DB::table('tbldun')->orderBy('name')->get();

        $data['parlimen'] = DB::table('tblparlimen')->orderBy('name')->get();

        $data['qualification'] = DB::table('tblqualification_std')->get();

        return view('pendaftar.view', compact(['student','program','session','data']));

    }

    public function edit()
    {
        $student = DB::table('students')
                   ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                   ->leftjoin('tblstudent_address', 'students.ic', 'tblstudent_address.student_ic')
                   ->leftjoin('tblstudent_pass', 'students.ic', 'tblstudent_pass.student_ic')
                   ->leftjoin('student_form', 'students.ic', 'student_form.student_ic')
                   ->select('students.*', 'tblstudent_personal.*', 'tblstudent_address.*', 'tblstudent_pass.*', 'student_form.*', 'tblstudent_personal.state_id AS place_birth')
                   ->where('ic',request()->ic)->first();

        $data['waris'] = DB::table('tblstudent_waris')->where('student_ic', $student->ic)->get();

        //dd($data['waris']);

        $program = DB::table('tblprogramme')->get();

        $session = DB::table('sessions')->get();

        $data['batch'] = DB::table('tblbatch')->get();

        $data['state'] = DB::table('tblstate')->orderBy('state_name')->get();

        $data['gender'] = DB::table('tblsex')->get();

        $data['race'] = DB::table('tblnationality')->orderBy('nationality_name')->get();

        $data['relationship'] = DB::table('tblrelationship')->get();

        $data['wstatus'] = DB::table('tblwaris_status')->get();

        $data['religion'] =  DB::table('tblreligion')->orderBy('religion_name')->get();

        $data['CL'] = DB::table('tblcitizenship_level')->get();

        $data['citizen'] = DB::table('tblcitizenship')->get();

        $data['mstatus'] = DB::table('tblmarriage')->get();

        $data['EA'] = DB::table('tbledu_advisor')->orderBy('name')->get();

        $data['pass'] = DB::table('tblpass_type')->get();

        $data['country'] = DB::table('tblcountry')->get();
        
        $data['dun'] = DB::table('tbldun')->orderBy('name')->get();

        $data['parlimen'] = DB::table('tblparlimen')->orderBy('name')->get();

        $data['qualification'] = DB::table('tblqualification_std')->get();

        return view('pendaftar.update', compact(['student','program','session','data']));

    }

    public function update(Request $request)
    {

        //dd($request->name);

        $data = $request->validate([
            'name' => ['required','string'],
            'session' => ['required'],
            'batch' => ['required'],
            'program' => ['required'],
        ]);

        if($request->ic != '')
        {

            $data['id'] = $request->ic;

        }elseif($request->passport != '')
        {

            $data['id'] = $request->passport;

        }

        $oldstd = DB::table('students')->where('ic', $data['id'])->first();

        if($oldstd->program != $data['program'])
        {

            DB::table('student_program')->insert([
                'student_ic' => $oldstd->ic,
                'program_id' => $oldstd->program,
                'comment' => $request->comment,
                'intake' => $oldstd->intake,
                'batch' => $oldstd->batch,
                'session' => $oldstd->session
            ]);

            DB::table('students')->where('ic', $data['id'])->update([
                'status' => 1
            ]);

        }

        DB::table('students')->where('ic', $data['id'])->update([
            'name' => $data['name'],
            'email' =>$request->email,
            'intake' => $data['session'],
            'batch' => $data['batch'],
            'program' => $data['program'],
            'date_offer' => $request->dol
        ]);

        DB::table('tblstudent_personal')->updateOrInsert(
            ['student_ic' => $data['id']], // "where" condition
            [
            'student_ic' => $data['id'],
            'date_birth' => $request->birth_date,
            'advisor_id' => $request->EA,
            'bank_name' => $request->bank_name,
            'bank_no' => $request->bank_number,
            'ptptn_no' => $request->PN,
            'datetime' => $request->dt,
            'religion_id' => $request->religion,
            'nationality_id' => $request->race,
            'sex_id' => $request->gender,
            'state_id' => $request->birth_place,
            'marriage_id' => $request->mstatus,
            'statelevel_id' => $request->CL,
            'citizenship_id' => $request->citizen,
            'no_tel' => $request->np1,
            'no_tel2' => $request->np2,
            'no_telhome' => $request->np3,
            'dun' => $request->dun,
            'parlimen' => $request->parlimen,
            'qualification' => $request->qualification,
            'oku' => $request->oku,
            'no_jkm' => $request->jkm
            ]
        );

        DB::table('tblstudent_pass')->updateOrInsert(
            ['student_ic' => $data['id']], // "where" condition
            [
                'student_ic' => $data['id'],
                'pass_type' => $request->pt,
                'pass_no' => $request->spn,
                'date_issued' => $request->di,
                'date_expired' => $request->de
            ]
        );

        DB::table('tblstudent_address')->updateOrInsert(
            ['student_ic' => $data['id']],
            [
            'student_ic' => $data['id'],
            'address1' => $request->address1,
            'address2' => $request->address2,
            'address3' => $request->address3,
            'city' => $request->city,
            'postcode' => $request->postcode,
            'state_id' => $request->state,
            'country_id' => $request->country
            ]
        );

        DB::table('tblstudent_waris')->where('student_ic', $data['id'])->delete();

        $numWaris = count($request->input('w_name'));
        for ($i = 0; $i < $numWaris; $i++) {
            
            if($request->input('w_name')[$i] != '')
            {
                DB::table('tblstudent_waris')->insert([
                    'student_ic' => $data['id'],
                    'name' => $request->input('w_name')[$i],
                    'ic' => $request->input('w_ic')[$i],
                    'email' => $request->input('w_email')[$i],
                    'home_tel' => $request->input('w_notel_home')[$i],
                    'phone_tel' => $request->input('w_notel')[$i],
                    'occupation' => $request->input('occupation')[$i],
                    'dependent_no' => $request->input('dependent')[$i],
                    'kasar' => $request->input('w_kasar')[$i],
                    'bersih' => $request->input('w_bersih')[$i],
                    'relationship' => $request->input('relationship')[$i],
                    //'race' => $request->input('w_race')[$i],
                    'status' => $request->input('w_status')[$i]
                ]);
            }
        }

        DB::table('student_form')->updateOrInsert(
            ['student_ic' => $data['id']],
            [
            'student_ic' => $data['id'],
            'main' => $request->main,
            'pre_registration' => $request->PR,
            // 'c19' => $request->c19,
            'complete_form' => $request->CF,
            'copy_ic' => $request->copyic,
            'copy_birth' => $request->copybc,
            'copy_spm' => $request->copyspm,
            'copy_school' => $request->coppysc,
            'copy_pic' => $request->copypic,
            'copy_pincome' => $request->copypp
            ]
        );

        return back();

    }

    public function getProgram(Request $request)
    {

        $programs = DB::table('student_program')
                    ->join('students', 'student_program.student_ic', 'students.ic')
                    ->join('tblprogramme', 'student_program.program_id', 'tblprogramme.id')
                    ->where('student_ic', $request->ic)->get();
        
        $intake = DB::table('student_program')
                  ->join('sessions', 'student_program.intake', 'sessions.SessionID')
                  ->where('student_ic', $request->ic)->get();

        $batch = DB::table('student_program')
                 ->join('sessions', 'student_program.batch', 'sessions.SessionID')
                 ->where('student_ic', $request->ic)->get();
                   

        return view('pendaftar.getProgram', compact(['programs', 'intake', 'batch']));

    }


    public function getSubjectOption(Request $request){
        $subject = DB::table('subjek')->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')->where('subjek_structure.program_id', $request->program)->get();

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

    public function spmIndex()
    {
        
        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', request()->ic)->first();

        $data['spm'] = DB::table('tblstudent_spm')
                       ->join('tblspm_dtl', 'tblstudent_spm.student_ic', 'tblspm_dtl.student_spm_ic')
                       ->where('tblstudent_spm.student_ic', request()->ic)->get();

        $data['info'] = DB::table('tblstudent_spm')->where('student_ic', request()->ic)->first();

        $data['subject'] = DB::table('tblsubject_spm')->get();

        $data['grade'] = DB::table('tblgrade_spm')->get();

        $data['spmv'] = DB::table('tblstudent_spmv')->where('student_ic', request()->ic)->first();

        $data['skm'] = DB::table('tblstudent_skm')->where('student_ic', request()->ic)->first();

 
        return view('pendaftar.spm.spm', compact('data'));

    }

    public function spmStore(Request $request)
    {

        //dd($request->subject);

        //$student = DB::table('tblstudent_spm')->where('student_ic', $request->ic)->first();

        //dd($request->grade);

        DB::table('tblstudent_spm')->updateOrInsert(
            ['student_ic' => $request->ic], 
            [
                'year' => $request->year,
                'number_turn' => $request->turn
            ]);

        $filter = array_filter($request->subject);

        if(count($filter) !== count(array_unique($filter)))
        {
            return back()->with('error', 'Cannot have same subject! Please check and re-submit.')->withInput();

        }else{

            if(DB::table('tblspm_dtl')->where('student_spm_ic',$request->ic)->exists())
            {
                
                DB::table('tblspm_dtl')->where('student_spm_ic',$request->ic)->delete();

            }

            foreach($request->subject as $key => $sub)
            {
                
                DB::table('tblspm_dtl')->insert([
                    'student_spm_ic' => $request->ic,
                    'subject_spm_id' => $sub,
                    'grade_spm_id' => $request->grade[$key]
                ]);

            }

            return back()->with('success', 'Successfully saved SPM data')->withInput();
        }

    }

    public function SPMVStore(Request $request)
    {

        if($request->year && $request->turn)
        {

            DB::table('tblstudent_spmv')->updateOrInsert(
                ['student_ic' => $request->ic], 
                [
                    'year' => $request->year,
                    'number_turn' => $request->turn,
                    'cert_type' => $request->class,
                    'pngka' => $request->pngka,
                    'pngkv' => $request->pngkv,
                    'bmkv' => $request->bmkv,
                    'sejarahspm' => $request->sejarahspm,
                ]);

        }else{

            return back()->with('error', 'Please complete the SPMV form.')->withInput();

        }

            return back()->with('success', 'Successfully saved SPMV data')->withInput();

    }

    public function SKMStore(Request $request)
    {

        if($request->class != null && $request->program != null)
        {

            DB::table('tblstudent_skm')->updateOrInsert(
                ['student_ic' => $request->ic], 
                [
                    'tahap3' => $request->level ?? 0,
                    'in_field' => $request->class,
                    'program' => $request->program
                ]);

        }else{

            return back()->with('error', 'Please complete the SKM form.')->withInput();

        }

            return back()->with('success', 'Successfully saved SKM data')->withInput();

    }

    public function studentStatus()
    {

        return view('pendaftar.updateStatus');

    }

    public function getStudentList(Request $request)
    {
        $students = DB::table('students')->where('name', 'LIKE', "%".$request->search."%")
                                         ->orwhere('ic', 'LIKE', "%".$request->search."%")
                                         ->orwhere('no_matric', 'LIKE', "%".$request->search."%")->get();

        $content = "";

        $content .= "<option value='0' selected disabled>-</option>";
        foreach($students as $std){

            $content .= "<option data-style=\"btn-inverse\"
            data-content=\"<div class='row'>
                <div class='col-md-2'>
                <div class='d-flex justify-content-center'>
                    <img src='' 
                        height='auto' width='70%' class='bg-light ms-0 me-2 rounded-circle'>
                        </div>
                </div>
                <div class='col-md-10 align-self-center lh-lg'>
                    <span><strong>". $std->name ."</strong></span><br>
                    <span>". $std->email ." | <strong class='text-fade'>". $std->ic ."</strong></span><br>
                    <span class='text-fade'></span>
                </div>
            </div>\" value='". $std->ic ."' ></option>";

        }
        
        return $content;

    }

    public function getStudentInfo(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS statusName', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['history'] = DB::table('tblstudent_log')
                           ->leftjoin('users', 'tblstudent_log.add_staffID', 'users.ic')
                           ->join('sessions', 'tblstudent_log.session_id', 'sessions.SessionID')
                           ->join('tblstudent_status', 'tblstudent_log.status_id', 'tblstudent_status.id')
                           ->where('student_ic', $request->student)
                           ->select('tblstudent_log.*', 'sessions.SessionName', 'tblstudent_status.name', 'users.name AS staff')
                           ->get();

        if(count($data['history']) > 0)
        {

            foreach($data['history'] as $key => $log)
            {

                // if($log->kuliah_id == 1)
                // {

                //     $kuliah[$key] = 'Holding';

                // }elseif($log->kuliah_id == 2)
                // {

                //     $kuliah[$key] = 'Kuliah';

                // }elseif($log->kuliah_id == 4)
                // {

                //     $kuliah[$key] = 'Latihan Industri';

                // }else{

                //     $kuliah[$key] = '';

                // }

                if($log->kuliah_id == 0)
                {

                    $kuliah[$key] = 'No';

                }elseif($log->kuliah_id == 2)
                {

                    $kuliah[$key] = 'Yes';

                }else{

                    $kuliah[$key] = '';

                }

            }

        }else{

            $kuliah = [];

        }

        $data['session'] = DB::table('sessions')->orderBy('SessionID', 'DESC')->get();

        $data['semester'] = DB::table('semester')->get();

        if(Auth::user()->usrtype == "AR")
        {

            $data['status'] = DB::table('tblstudent_status')->whereIn('id', [3,8])->get();
        
        }else{

            $data['status'] = DB::table('tblstudent_status')->get();

        }

        $data['batch'] = DB::table('tblbatch')->get();

        return view('pendaftar.updateGetStudent', compact('data', 'kuliah'));

    }

    public function storeStudentInfo(Request $request)
    {

        $studentData = $request->studentData;

        $validator = Validator::make($request->all(), [
            'studentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $student = json_decode($studentData);

                $stds = UserStudent::where('ic', $student->ic)->value('campus_id');
                
                DB::table('students')->where('ic', $student->ic)->update([
                    'intake' => $student->intake,
                    'batch' => $student->batch,
                    'session' => $student->session,
                    'semester' => $student->semester,
                    'status' => $student->status,
                    'student_status' => $student->kuliah,
                    'block_status' => $student->block
                ]);

                DB::table('tblstudent_log')->insert([
                    'student_ic' => $student->ic,
                    'session_id' => $student->session,
                    'semester_id' => $student->semester,
                    'status_id' => $student->status,
                    'kuliah_id' => $stds,
                    'block_id' => $student->block,
                    'date' => date("Y-m-d H:i:s"),
                    'remark' => $student->comment,
                    'add_staffID' => Auth::user()->ic
                ]);

                $std_log = DB::table('tblstudent_log')
                           ->join('users', 'tblstudent_log.add_staffID', 'users.ic')
                           ->join('sessions', 'tblstudent_log.session_id', 'sessions.SessionID')
                           ->join('tblstudent_status', 'tblstudent_log.status_id', 'tblstudent_status.id')
                           ->where('student_ic', $student->ic)
                           ->select('tblstudent_log.*', 'sessions.SessionName', 'tblstudent_status.name', 'users.name AS staff')
                           ->get();

                foreach($std_log as $key => $log)
                {

                    // if($log->kuliah_id == 1)
                    // {

                    //     $kuliah[$key] = 'Holding';

                    // }elseif($log->kuliah_id == 2)
                    // {

                    //     $kuliah[$key] = 'Kuliah';

                    // }elseif($log->kuliah_id == 4)
                    // {

                    //     $kuliah[$key] = 'Latihan Industri';

                    // }else{

                    //     $kuliah[$key] = '';

                    // }

                    if($log->kuliah_id == 0)
                    {

                        $kuliah[$key] = 'No';

                    }elseif($log->kuliah_id == 2)
                    {

                        $kuliah[$key] = 'Yes';

                    }else{

                        $kuliah[$key] = '';

                    }

                }

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th style="width: 1%">
                                        No.
                                    </th>
                                    <th style="width: 10%">
                                        No. IC
                                    </th>
                                    <th style="width: 15%">
                                        Semester
                                    </th>
                                    <th style="width: 10%">
                                        Session
                                    </th>
                                    <th style="width: 10%">
                                        Status
                                    </th>
                                    <th style="width: 10%">
                                        Lectures Status
                                    </th>
                                    <th style="width: 10%">
                                        Block Status
                                    </th>
                                    <th style="width: 10%">
                                        Date
                                    </th>
                                    <th style="width: 20%">
                                        Remark
                                    </th>
                                    <th style="width: 10%">
                                        Staff
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">';
                            
                foreach($std_log as $key => $std){
                    //$registered = ($std->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                    <tr>
                        <td>
                        '. $key+1 .'
                        </td>
                        <td>
                        '. $std->student_ic .'
                        </td>
                        <td>
                        '. $std->semester_id .'
                        </td>
                        <td>
                        '. $std->SessionName .'
                        </td>
                        <td>
                        '. $std->name .'
                        </td>
                        <td>
                        '. $kuliah[$key] .'
                        </td>
                        <td>
                        '. ($std->block_id == 1 ? 'Blocked' : 'Not Blocked') .'
                        </td>
                        <td>
                        '. $std->date .'
                        </td>
                        <td>
                        '. $std->remark .'
                        </td>
                        <td>
                        '. $std->staff .'
                        </td>
                    </tr>
                    ';
                    }
                $content .= '</tbody>';
                
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

        return ["message"=>"Success", "data" => $content];

    }

    public function generateMatric(Request $request)
    {

        if($request->ic)
        {

            $student = DB::table('students')
                     ->where('ic', $request->ic)->first();

            if($student->no_matric == '' || $student->no_matric == null)
            {

                $intake = DB::table('sessions')->where('SessionID', $student->intake)->first();
                    
                $year = substr($intake->SessionName, 6, 2) . substr($intake->SessionName, 11, 2);

                if(DB::table('tblmatric_no')->where('session', $year)->exists())
                {

                    $lastno = DB::table('tblmatric_no')->where('session', $year)->first();

                    $newno = sprintf("%04s", $lastno->final_no + 1);

                }else{

                    DB::table('tblmatric_no')->insert([
                        'session' => $year,
                        'final_no' => 0001
                    ]);

                    $lastno = DB::table('tblmatric_no')->where('session', $year)->first();

                    $newno = sprintf("%04s", $lastno->final_no);

                }

                $newno = sprintf("%04s", $lastno->final_no + 1);

                $no_matric = $year . $newno;

                DB::table('students')->where('ic', $student->ic)->update([
                    'no_matric' => $no_matric
                ]);

                DB::table('tblmatric_no')->where('session', $year)->update([
                    'final_no' => $newno
                ]);

                return ["success" => "Student's matric no. have successfully generated!"];

            }else{

                return ["error" => "Student's matric no. already exists! Please try again with another student."];

            }


        }
        
    }

    public function viewStatus()
    {

        return view('pendaftar.viewStatus');

    }

    public function getReportStd(Request $request)
    {

        $data['dismissed'] = DB::table('students')->whereBetween('date', [$request->from,$request->to])->where('status', '3')->get();

        $data['active'] = DB::table('students')->whereBetween('date', [$request->from,$request->to])->where('status', '2')->get();

        return view('pendaftar.getReportStudent', compact('data'));
        
    }

    public function studentReport()
    {
        $data['ms1'] = [];

        $data['sum'] = [];

        $data['program'] = DB::table('tblprogramme')
                           ->join('tblfaculty', 'tblprogramme.facultyid', 'tblfaculty.id')
                           ->select('tblprogramme.*', 'tblfaculty.facultyname', 'tblfaculty.facultycode')->get();

        $data['faculty'] = DB::table('tblfaculty')->get();

        $data['sessions'] = DB::table('sessions')
                            ->join('students', 'sessions.SessionID', 'students.intake')
                            ->where('students.semester', 1)
                            ->where('students.status', 2)
                            ->groupBy('sessions.SessionID')
                            ->select('sessions.*')
                            ->get();

        //dd($data['sessions']);

        foreach($data['faculty'] as $fcl)
        {

            $data['count'][] = count(DB::table('tblprogramme')->where('facultyid', $fcl->id)->get());

        }

        //dd($data['count']);

        foreach($data['program'] as $key => $prg)
        {

            $data['sum'][$key] = count(DB::table('students')
                                       ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                                       ->join('tblfaculty', 'tblprogramme.facultyid', 'tblfaculty.id')
                                       ->where('tblfaculty.id', $prg->facultyid)
                                       ->get());

            $data['holding_m1'][$key] = count(DB::table('students')
                                       ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                       ->where([
                                       ['students.program', $prg->id],
                                       ['students.status', 2],
                                       ['students.student_status', 1],
                                       ['tblstudent_personal.sex_id', 1]
                                       ])->get());
   
            $data['holding_f1'][$key] = count(DB::table('students')
                                       ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                       ->where([
                                       ['students.program', $prg->id],
                                       ['students.status', 2],
                                       ['students.student_status', 1],
                                       ['tblstudent_personal.sex_id', 2]
                                       ])->get());

            $data['ms1'][$key] = count(DB::table('students')
                                 ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                 ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 1],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1],
                                    ['students.campus_id', 1]
                                    ])->get());
            
            $data['fs1'][$key] = count(DB::table('students')
                                 ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                 ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 1],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2],
                                    ['students.campus_id', 1]
                                    ])->get());
            
            $data['ms2'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 2],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['fs2'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 2],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['ms3'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 3],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['fs3'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 3],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2],
                                    ['students.campus_id', 1]
                                    ])->get());
                                    
            $data['ms4'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 4],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['fs4'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 4],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['ms5'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                     ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 5],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['fs5'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                     ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 5],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['ms6'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 6],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['fs6'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 6],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['ms7'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 7],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['fs7'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 7],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['ms8'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 8],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['fs8'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 8],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['industry'][$key] = count(DB::table('students')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.status', 2],
                                    ['students.student_status', 4],
                                    ['students.campus_id', 1]
                                    ])->get());

            $data['active'][$key] = count(DB::table('students')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.status', 2],
                                    ['students.campus_id', 1],
                                    ['students.student_status', 2]
                                    ])->get());

            $data['active_leave'][$key] = count(DB::table('students')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.status', 2],
                                    ['students.campus_id', 0]
                                    ])->whereIn('students.student_status', [2,4])->get());
                                    
            $data['postpone'][$key] = count(DB::table('students')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.status', 6]
                                    ])->get());

            $data['dismissed'][$key] = count(DB::table('students')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.status', 4]
                                    ])->get());

                                 

        }

        foreach($data['sessions'] as $key => $ses)
        {

            $data['holding'][$key] = count(DB::table('students')
                                           ->where([
                                            ['students.semester', 1],
                                            ['students.status', 2],
                                            ['students.student_status', 1],
                                            ['students.campus_id', 1],
                                            ['students.intake', $ses->SessionID]
                                           ])->get());

            $data['kuliah'][$key] = count(DB::table('students')
                                           ->where([
                                            ['students.semester', 1],
                                            ['students.status', 2],
                                            ['students.student_status', 2],
                                            ['students.campus_id', 1],
                                            ['students.intake', $ses->SessionID]
                                           ])->get());

        }

        return view('pendaftar.studentReport', compact('data'));

    }

    //public function getStudentReport(Request $request)
    //{

        
        
    //}

    public function suratTawaran(Request $request)
    {
        $data['student'] = DB::table('students')
                            ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                            ->join('sessions', 'students.intake', 'sessions.SessionID')
                            ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname', 'sessions.SessionName AS intake')
                            ->where('ic', $request->ic)->first();

        $data['address'] = DB::table('tblstudent_address')
                           ->leftJoin('tblstate', 'tblstudent_address.state_id', 'tblstate.id')
                           ->leftJoin('tblcountry', 'tblstudent_address.country_id', 'tblcountry.id')
                           ->select('tblstudent_address.*', 'tblstate.state_name AS state', 'tblcountry.name AS country')
                           ->where('tblstudent_address.student_ic', $request->ic)->first();

        if($data['student']->program != 7 && $data['student']->program != 8)
        {

            return view('pendaftar.surat_tawaran.surat_tawaran', compact('data'));

        }else{

            return view('pendaftar.surat_tawaran.surat_tawaran2', compact('data'));

        }

        

    }

    public function studentTranscript()
    {

        $data = [
            'program' => DB::table('tblprogramme')->get(),
            'session' => DB::table('sessions')->get(),
            'semester' => DB::table('semester')->get()
        ];

        return view('pendaftar.studentTranscript', compact('data'));

    }
    
    public function getTranscript(Request $request)
    {

        $data = DB::table('student_transcript')
                    ->join('students', 'student_transcript.student_ic', 'students.ic')
                    ->join('sessions', 'student_transcript.session_id', 'sessions.SessionID')
                    ->join('transcript_status', 'student_transcript.transcript_status_id', 'transcript_status.id')
                    ->where([
                        ['students.program', $request->program],
                        ['student_transcript.session_id', $request->session],
                        ['student_transcript.semester', $request->semester]
                    ])->select('student_transcript.*', 'students.name', 'sessions.SessionName','transcript_status.status_name AS transcript_status_id')
                    ->get();

                    // ob_start();
                    // dd($data); // This will output debug information
                    // $debugOutput = ob_get_clean();

                    // Convert the data to JSON format
                    $jsonData = $data->toJson();
   

        return response()->json(['message' => 'Success', 'data' => $data, 'log' => $jsonData]);

    }

    public function addTranscript(Request $request)
    {

        $data = json_decode($request->addTranscript);

        $transcript_status_id = '';

        if($data->program != null && $data->session != null && $data->semester != null)
        {

            DB::table('student_transcript')
            ->join('students', 'student_transcript.student_ic', 'students.ic')
            ->where([
                ['students.program', $data->program],
                ['student_transcript.session_id', $data->session],
                ['student_transcript.semester', $data->semester]
            ])->delete();

            $students = DB::table('students')->where([
                ['program', $data->program]
            ])->pluck('ic');

            foreach($students as $std)
            {
                
                if(DB::table('student_subjek')->where([
                    ['student_ic', $std],
                    ['sessionid', $data->session],
                    ['semesterid', $data->semester],
                    ['group_id','!=',null]
                ])->count() > 0)
                {

                    $total_credit_s = DB::table('student_subjek')->where([
                        ['student_ic', $std],
                        ['sessionid', $data->session],
                        ['semesterid', $data->semester],
                        ['group_id','!=',null]
                    ])->whereIn('course_status_id', [1,2,12,15])->sum('credit');

                    $passed_credit_s = DB::table('student_subjek')->where([
                        ['student_ic', $std],
                        ['sessionid', $data->session],
                        ['semesterid', $data->semester],
                        ['group_id','!=',null]
                    ])->whereIn('course_status_id', [1])->sum('credit');

                    $grade_pointer_s = DB::table('student_subjek')
                    ->where([
                        ['student_ic', $std],
                        ['sessionid', $data->session],
                        ['semesterid', $data->semester],
                        ['group_id','!=',null]
                    ])
                    ->whereIn('course_status_id', [1,2,12,15])
                    ->selectRaw('SUM(credit * pointer) as total')
                    ->value('total');

                    $gpa = DB::table('student_subjek')
                    ->where([
                        ['student_ic', $std],
                        ['sessionid', $data->session],
                        ['semesterid', $data->semester],
                        ['group_id','!=',null]
                    ])
                    ->whereIn('course_status_id', [1,2,12,15])
                    ->selectRaw('SUM(credit * pointer) / SUM(credit) as total')
                    ->value('total');

                    $total_credit_c = DB::table('student_subjek')->where([
                        ['student_ic', $std],
                        ['group_id','!=',null]
                    ])->where('semesterid', '<=', $data->semester)
                    ->whereIn('course_status_id', [1,2,12,15])
                    ->sum('credit');

                    $passed_credit_c = DB::table('student_subjek')->where([
                        ['student_ic', $std],
                        ['group_id','!=',null]
                    ])->where('semesterid', '<=', $data->semester)
                    ->whereIn('course_status_id', [1])->sum('credit');

                    $distinct_courses = DB::table('student_subjek')
                    ->where([
                        ['student_ic', $std],
                        ['group_id','!=',null]
                    ])
                    ->where('semesterid', '<=', $data->semester)
                    ->whereIn('course_status_id', [1, 2, 12, 15])
                    ->distinct()
                    ->select('courseid', 'credit');

                    $count_credit_c = DB::table(DB::raw("({$distinct_courses->toSql()}) as sub"))
                    ->mergeBindings($distinct_courses) // Needed to pass the bindings from the subquery
                    ->sum('credit');
                    
                    // $count_credit_c = DB::table('student_subjek')->where([
                    //     ['student_ic', $std]
                    // ])->where('semesterid', '<=', $data->semester)
                    // ->whereIn('course_status_id', [1,2,12,15])
                    // ->distinct('courseid')
                    // ->selectRaw('SUM(credit) as total')
                    // ->value('total');

                    // $grade_pointer = DB::table('student_subjek')
                    //     ->selectRaw('MAX(id) as max_id')
                    //     ->where([
                    //         ['student_ic', $std],
                    //         ['group_id','!=',null]
                    //         ])
                    //     ->where('semesterid', '<=', $data->semester)
                    //     ->whereIn('course_status_id', [1, 2, 12, 15])
                    //     ->groupBy('courseid')
                    //     ->get();

                    // $grade_pointer_c = DB::table('student_subjek')
                    //     ->whereIn('id', $grade_pointer->pluck('max_id'))
                    //     ->selectRaw('SUM(credit * pointer) as total')
                    //     ->value('total');

                    $subquery = DB::table('student_subjek')
                        ->select('courseid', DB::raw('MAX(semesterid) as max_semesterid'))
                        ->where('student_ic', $std)
                        ->whereNotNull('group_id')
                        ->where('semesterid', '<=', $data->semester)
                        ->whereIn('course_status_id', [1, 2, 12, 15])
                        ->groupBy('courseid');

                    $grade_pointer = DB::table('student_subjek as ss')
                        ->joinSub($subquery, 'sub', function ($join) {
                            $join->on('ss.courseid', '=', 'sub.courseid')
                                ->on('ss.semesterid', '=', 'sub.max_semesterid');
                        })
                        ->where('ss.student_ic', $std)
                        ->select('ss.id as max_id')
                        ->get();

                    $grade_pointer_c = DB::table('student_subjek')
                        ->whereIn('id', $grade_pointer->pluck('max_id'))
                        ->selectRaw('SUM(credit * pointer) as total')
                        ->value('total');

                    // $grade_pointer_c = DB::table('student_subjek')
                    // ->where([
                    //     ['student_ic', $std]
                    // ])->where('semesterid', '<=', $data->semester)
                    // ->whereIn('course_status_id', [1, 2, 12, 15])
                    // ->selectRaw('SUM(credit * pointer) as total')
                    // ->selectRaw('MAX(id) as max_id')
                    // ->groupBy('courseid')
                    // ->value('total');


                    // $sub_query = DB::table('student_subjek')
                    //             ->where([
                    //                 ['student_ic', $std]
                    //             ])->where('semesterid', '<=', $data->semester)
                    //             ->select('courseid', DB::raw('MAX(id) as cid'))
                    //             ->groupBy('courseid');

                    // $result = DB::table(DB::raw("({$sub_query->toSql()}) as a"))
                    //             ->mergeBindings($sub_query)
                    //             ->join('student_subjek as b', 'a.cid', '=', 'b.courseid')
                    //             ->where([
                    //                 ['b.student_ic', $std]
                    //             ])->where('b.semesterid', '<=', $data->semester)
                    //             ->select(DB::raw('SUM(b.pointer*b.credit) as grade_c'))
                    //             ->value('grade_c');
                            
                    // $grade_pointer_c = $result;

                    // $grade_pointers = DB::table('student_subjek')
                    //     ->where('student_ic', $std)
                    //     ->where('semesterid', '<=', $data->semester)
                    //     ->whereIn('course_status_id', [1, 2, 12, 15])
                    //     ->select('courseid', DB::raw('SUM(credit * pointer) as total'))
                    //     ->groupBy('courseid')
                    //     ->get();

                    // // Now you can iterate through the collection to access each course's total
                    // foreach ($grade_pointers as $grade_pointer) {
                    //     $courseid = $grade_pointer->courseid;
                    //     $total = $grade_pointer->total;
                    //     // Do something with $courseid and $total
                    // }

                    // $cgpa = DB::table('student_subjek')
                    // ->select('courseid', DB::raw('ROUND(SUM(credit * pointer) / 2) as total'))
                    // ->where([
                    //     ['student_ic', $std]
                    // ])->where('semesterid', '<=', $data->semester)
                    // ->whereIn('course_status_id', [1,2,12,15])
                    // ->groupBy('courseid')
                    // ->groupBy(DB::raw('(SELECT MAX(id) FROM student_subjek as ss2 WHERE ss2.courseid = student_subjek.courseid)'))
                    // ->value('total');

                    // $cgpa_old = DB::table('student_subjek')
                    //     ->selectRaw('MAX(id) as max_id')
                    //     ->where([
                    //         ['student_ic', $std],
                    //         ['group_id','!=',null]
                    //         ])
                    //     ->where('semesterid', '<=', $data->semester)
                    //     ->whereIn('course_status_id', [1, 2, 12, 15])
                    //     ->groupBy('courseid')
                    //     ->get();

                    // $cgpa = DB::table('student_subjek')
                    //     ->whereIn('id', $cgpa_old->pluck('max_id'))
                    //     ->selectRaw('ROUND(SUM(credit * pointer) / SUM(credit), 2) as total')
                    //     ->value('total');

                    // $subquery = DB::table('student_subjek')
                    //     ->select('courseid', DB::raw('MAX(semesterid) as max_semesterid'))
                    //     ->where('student_ic', $std)
                    //     ->whereNotNull('group_id')
                    //     ->where('semesterid', '<=', $data->semester)
                    //     ->whereIn('course_status_id', [1, 2, 12, 15])
                    //     ->groupBy('courseid');

                    $cgpa_old = DB::table('student_subjek as ss')
                        ->joinSub($subquery, 'sub', function ($join) {
                            $join->on('ss.courseid', '=', 'sub.courseid')
                                ->on('ss.semesterid', '=', 'sub.max_semesterid');
                        })
                        ->where('ss.student_ic', $std)
                        ->select('ss.id as max_id')
                        ->get();

                    $cgpa = DB::table('student_subjek')
                        ->whereIn('id', $cgpa_old->pluck('max_id'))
                        ->selectRaw('ROUND(SUM(credit * pointer) / SUM(credit), 2) as total')
                        ->value('total');

                    if($cgpa >= 3.67 && $cgpa <= 4)
                    {

                        $transcript_status_id = 6;

                    }elseif($cgpa >= 3 && $cgpa <= 3.66)
                    {

                        $transcript_status_id = 1;

                    }elseif($cgpa >= 2 && $cgpa <= 2.99)
                    {

                        $transcript_status_id = 2;

                    }elseif($cgpa >= 1 && $cgpa <= 1.99)
                    {

                        $transcript_status_id = 3;

                    }elseif($cgpa >= 0.50 && $cgpa <= 0.99)
                    {

                        $transcript_status_id = 4;

                    }elseif($cgpa >= 0 && $cgpa <= 0.49)
                    {

                        $transcript_status_id = 5;

                    }

                    if($data->semester != 1)
                    {

                        if($transcript_status_id == 3)
                        {

                            $total = DB::table('student_transcript')
                                     ->where('student_ic', $std)
                                     ->where('semester', '<', $data->semester)
                                     ->whereIn('transcript_status_id', [3,4])
                                     ->count();

                            if($total > 0)
                            {

                                $transcript_status_id = 4;

                            }

                        }

                        if($transcript_status_id == 4)
                        {

                            $total = DB::table('student_transcript')
                                     ->where('student_ic', $std)
                                     ->where('semester', '<', $data->semester)
                                     ->whereIn('transcript_status_id', [4,5])
                                     ->count();

                            if($total > 0)
                            {

                                $transcript_status_id = 5;

                            }

                        }

                    }

                    DB::table('student_transcript')->insert([
                        'student_ic' => $std,
                        'session_id' => $data->session,
                        'semester' => $data->semester,
                        'total_credit_s' => $total_credit_s,
                        'passed_credit_s' => $passed_credit_s,
                        'grade_pointer_s' => $grade_pointer_s,
                        'gpa' => $gpa,
                        'total_credit_c' => $total_credit_c,
                        'passed_credit_c' => $passed_credit_c,
                        'count_credit_c' => $count_credit_c,
                        'grade_pointer_c' => $grade_pointer_c,
                        'cgpa' => $cgpa,
                        'transcript_status_id' => $transcript_status_id
                    ]);

                }
  
            }

            $data = DB::table('student_transcript')
                    ->join('students', 'student_transcript.student_ic', 'students.ic')
                    ->join('sessions', 'student_transcript.session_id', 'sessions.SessionID')
                    ->join('transcript_status', 'student_transcript.transcript_status_id', 'transcript_status.id')
                    ->where([
                        ['students.program', $data->program],
                        ['student_transcript.session_id', $data->session],
                        ['student_transcript.semester', $data->semester]
                    ])->select('student_transcript.*', 'students.name', 'sessions.SessionName','transcript_status.status_name AS transcript_status_id')
                    ->get();

            return response()->json(['message' => 'Success', 'data' => $data]);

        }

    }

    public function studentResult()
    {

        $data['range'] = DB::table('tblresult_period')->first();

        return view('pendaftar.studentResult', compact('data'));

    }

    public function getStudentResult(Request $request)
    {
        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS statusName', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['result'] = DB::table('student_transcript')
                ->join('students', 'student_transcript.student_ic', 'students.ic')
                ->join('sessions', 'student_transcript.session_id', 'sessions.SessionID')
                ->join('transcript_status', 'student_transcript.transcript_status_id', 'transcript_status.id')
                ->where([
                    ['student_transcript.student_ic', $request->student],
                ])->select('student_transcript.*', 'students.name', 'students.no_matric', 'sessions.SessionName','transcript_status.status_name AS transcript_status_id')
                ->get();

        return view('pendaftar.getStudentResult', compact('data'));

    }

    public function storeResultPeriod(Request $request)
    {

        DB::table('tblresult_period')->upsert([
            'id' => 1,
            'Start' => $request->from,
            'END' =>$request->to
        ],['id']);

        return response()->json(['success' => 'Successfully updated result period!']);

    }

    public function overallResult(Request $request)
    {

        $data['transcript'] = DB::table('student_transcript')
                            ->join('students', 'student_transcript.student_ic', 'students.ic')
                            ->join('sessions', 'student_transcript.session_id', 'sessions.SessionID')
                            ->join('transcript_status', 'student_transcript.transcript_status_id', 'transcript_status.id')
                            ->where([
                                ['student_transcript.id', $request->id],
                            ])->select('student_transcript.*', 'students.name', 'students.no_matric', 'sessions.SessionName AS session','transcript_status.status_name AS transcript_status_id')
                            ->first();

        $data['student'] = DB::table('students')
                           ->join('sessions AS A1', 'students.intake', 'A1.SessionID')
                           ->join('sessions AS A2', 'students.session', 'A2.SessionID')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->select('students.*', 'tblprogramme.progname AS program', 'tblprogramme.progcode AS code', 'tblprogramme.credit_hour AS limit_credit', 'tblstudent_status.name AS status', 'A1.SessionName AS intake', 'A2.SessionName AS session')
                           ->where('students.ic', $data['transcript']->student_ic)
                           ->first();

        $data['subject'] = DB::table('student_subjek')
                           ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                           ->where([
                            ['student_subjek.student_ic', $data['student']->ic],
                            ['student_subjek.semesterid', $data['transcript']->semester],
                            ['student_subjek.group_id','!=',null]
                           ])
                           ->groupBy('student_subjek.courseid')
                           ->select('student_subjek.*', 'subjek.course_name', 'subjek.course_code')
                           ->get();

        return view('pendaftar.results', compact('data'));

    }

    public function studentReportRs()
    {

        $data['session'] = DB::table('sessions')->get();

        return view('pendaftar.reportRs.reportRs', compact('data'));

    }

    public function getStudentReportRs(Request $request)
    {

        if($request->from && $request->to)
        {

            
            $data['R1M'] = 0;
            $data['R1F'] = 0;

            $data['R2M'] = 0;
            $data['R2F'] = 0;

            $data['WM'] = 0;
            $data['WF'] = 0;

            $data['NAM'] = 0;
            $data['NAF'] = 0;

            // Define a function to create the base query
            $baseQuery = function () use ($request) {
                return DB::table('students')
                    ->leftjoin('tblstudent_personal', 'students.ic', '=', 'tblstudent_personal.student_ic')
                    ->leftjoin('sessions', 'students.intake', '=', 'sessions.SessionID')
                    ->leftjoin('tblprogramme', 'students.program', '=', 'tblprogramme.id')
                    ->leftjoin('tbledu_advisor', 'tblstudent_personal.advisor_id', '=', 'tbledu_advisor.id')
                    ->leftjoin('tblsex', 'tblstudent_personal.sex_id', '=', 'tblsex.id')
                    ->leftjoin('tblstudent_status', 'students.status', '=', 'tblstudent_status.id')
                    ->where('students.semester', 1)
                    ->whereBetween('students.date_offer', [$request->from, $request->to])
                    ->select(
                        'students.*', 'tblstudent_personal.no_tel', 'tblstudent_personal.qualification', 'sessions.SessionName',
                        'tblprogramme.progcode', 'tbledu_advisor.name AS ea', 'tblsex.code AS sex',
                        'tblstudent_status.name AS status'
                    );
            };

            // Use the base query for studentOne
            $studentOneQuery = ($baseQuery)()->where('students.status', 1);
            $data['studentR1'] = $studentOneQuery->get();

            // Use the base query for studentR2
            $data['studentR2'] = ($baseQuery)()
                ->wherein('students.status', [2,6,16,17])
                ->get();

            // Use the base query for studentR2
            $data['withdraw'] = ($baseQuery)()
                ->wherein('students.status', [4])
                ->get();

            // Use the base query for studentR2
            $data['notActive'] = ($baseQuery)()
                ->wherein('students.status', [14])
                ->get();

            $data['ref1'] = [];
            $data['ref2'] = [];

            foreach($data['studentR1'] as $key => $student)
            {

                $results = [];

                $data['resultR1'][] = DB::table('tblpayment')
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

                if($student->sex == 'L')
                {
                    $data['R1M'] = $data['R1M'] + 1;

                }elseif($student->sex == 'P') 
                {

                    $data['R1F'] = $data['R1F'] + 1;
                    
                }

                $data['quaR1'][$key] = DB::table('tblqualification_std')->where('id', $student->qualification)->value('name');

            }

            foreach($data['studentR2'] as $key => $student)
            {

                $results = [];

                $data['resultR2'][] = DB::table('tblpayment')
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

                if($student->sex == 'L')
                {
                    $data['R2M'] = $data['R2M'] + 1;

                }elseif($student->sex == 'P') 
                {

                    $data['R2F'] = $data['R2F'] + 1;
                    
                }

                $data['quaR2'][$key] = DB::table('tblqualification_std')->where('id', $student->qualification)->value('name');

            }

            foreach($data['withdraw'] as $key => $student)
            {

                $results = [];

                $data['resultWithdraw'][] = DB::table('tblpayment')
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

                if($student->sex == 'L')
                {
                    $data['WM'] = $data['WM'] + 1;

                }elseif($student->sex == 'P') 
                {

                    $data['WF'] = $data['WF'] + 1;
                    
                }

                $data['quaW'][$key] = DB::table('tblqualification_std')->where('id', $student->qualification)->value('name');

            }

            foreach($data['notActive'] as $key => $student)
            {

                $results = [];

                $data['resultNA'][] = DB::table('tblpayment')
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

                if($student->sex == 'L')
                {
                    $data['NAM'] = $data['NAM'] + 1;

                }elseif($student->sex == 'P') 
                {

                    $data['NAF'] = $data['NAF'] + 1;
                    
                }

                $data['quaNA'][$key] = DB::table('tblqualification_std')->where('id', $student->qualification)->value('name');

            }

            return view('pendaftar.reportRs.getReportRs', compact('data'));

        }

    }

    public function studentReportR2()
    {
        // $fromDate = '15-06-2024'; // Example from date
        // $toDate = '15-07-2024';   // Example to date

        // $start = Carbon::createFromFormat('d-m-Y', $fromDate);
        // $end = Carbon::createFromFormat('d-m-Y', $toDate);

        // $data['dateRange'] = [];
        // $currentWeek = [];
        // $currentMonth = $start->month;
        // $currentMonthStart = $start->copy()->startOfMonth();
        // $currentWeekNumber = $start->diffInWeeks($currentMonthStart) + 1;

        // while ($start <= $end) {
        //     // Check if the current date is in a new month
        //     if ($start->month != $currentMonth) {
        //         // If there are days collected for the previous month, add them to the dateRange
        //         if (!empty($currentWeek)) {
        //             $data['dateRange'][] = [
        //                 'week' => $currentWeekNumber,
        //                 'days' => $currentWeek
        //             ];
        //         }
        //         // Reset for the new month
        //         $currentWeek = [];
        //         $currentMonth = $start->month;
        //         $currentMonthStart = $start->copy()->startOfMonth();
        //         $currentWeekNumber = $start->diffInWeeks($currentMonthStart) + 1;
        //     }

        //     $currentWeek[] = $start->format('Y-m-d');

        //     // Check if the end of the week or the end of the range is reached
        //     if ($start->dayOfWeek == Carbon::SATURDAY || $start == $end) {
        //         $data['dateRange'][] = [
        //             'week' => $currentWeekNumber,
        //             'days' => $currentWeek
        //         ];
        //         $currentWeek = [];
        //         $currentWeekNumber++;
        //     }

        //     $start->addDay();
        // }

        // // If the last week isn't already added (for cases where $end doesn't fall on a Saturday)
        // if (!empty($currentWeek)) {
        //     $data['dateRange'][] = [
        //         'week' => $currentWeekNumber,
        //         'days' => $currentWeek
        //     ];
        // }

        // foreach($data['dateRange'] as $key => $week) {
        //     $startDate = reset($week['days']); // Get the first date of the week
        //     $endDate = end($week['days']);     // Get the last date of the week
        
        //     $data['totalWeek'][$key] = DB::table('tblpayment')
        //                             ->where([
        //                                 ['tblpayment.process_status_id', 2],
        //                                 ['tblpayment.process_type_id', 1], 
        //                                 ['tblpayment.semester_id', 1]
        //                             ])
        //                             ->whereBetween('tblpayment.add_date', [$startDate, $endDate])
        //                             ->select(DB::raw('COUNT(tblpayment.id) as total_week'))
        //                             ->first();
            
        //     $data['week'][$key] = $week['days'];

        //     foreach($data['week'][$key] AS $key2 => $day)
        //     {

        //         $data['totalDay'][$key][$key2] = DB::table('tblpayment')
        //                             ->where([
        //                             ['tblpayment.process_status_id', 2],
        //                             ['tblpayment.process_type_id', 1], 
        //                             ['tblpayment.semester_id', 1]
        //                             ])
        //                             ->where('tblpayment.add_date', $day)
        //                             ->select(DB::raw('COUNT(tblpayment.id) as total_day'))
        //                             ->first();

        //     }
        // }


        return view('pendaftar.reportR2.reportR2');

    }

    public function getStudentReportR2(Request $request)
    {

        if($request->from && $request->to)
        {

            $fromDate = '15-06-2024'; // Example from date
            $toDate = '15-07-2024';   // Example to date
    
            $start = Carbon::parse($request->from);
            $end = Carbon::parse($request->to);
            $end2 = $start->copy()->endOfMonth();

            if($end <= $end2)
            {

                $data['totalAll'] = DB::table('tblpayment')
                                    ->join('students', 'tblpayment.student_ic', '=', 'students.ic')
                                    ->where([
                                        ['tblpayment.process_status_id', '=', 2],
                                        ['tblpayment.process_type_id', '=', 1],
                                        ['tblpayment.semester_id', '=', 1]
                                    ])
                                    ->whereColumn('tblpayment.date', '=', 'students.date_add')  // Use whereColumn for comparing two columns
                                    ->whereBetween('tblpayment.date', [$start, $end])
                                    ->select('tblpayment.id')
                                    ->groupBy('tblpayment.student_ic')
                                    ->get()
                                    ->count();  // Use count() to get the count directly


                $totalStudentCount = $data['totalAll'] ? $data['totalAll'] : 0;
                $data['totalAll'] = (object) ['total_student' => $totalStudentCount];

        
                $data['dateRange'] = [];
                $currentWeek = [];
                $currentMonth = $start->month;
                $currentMonthStart = $start->copy()->startOfMonth();
                $currentWeekNumber = $start->diffInWeeks($currentMonthStart) + 1;
                $alreadyCountedStudents = [];
                $alreadyCountedStudents2 = [];

                while ($start <= $end) {
                    // Check if the current date is in a new month
                    if ($start->month != $currentMonth) {
                        // If there are days collected for the previous month, add them to the dateRange
                        if (!empty($currentWeek)) {
                            $data['dateRange'][] = [
                                'week' => $currentWeekNumber,
                                'month' => $currentMonth,
                                'days' => $currentWeek
                            ];
                        }
                        // Reset for the new month
                        $currentWeek = [];
                        $currentMonth = $start->month;
                        $currentMonthStart = $start->copy()->startOfMonth();
                        $currentWeekNumber = $start->diffInWeeks($currentMonthStart) + 1;
                    }

                    $currentWeek[] = $start->format('Y-m-d');

                    // Check if the end of the week or the end of the range is reached
                    if ($start->dayOfWeek == Carbon::SATURDAY || $start == $end) {
                        $data['dateRange'][] = [
                            'week' => $currentWeekNumber,
                            'month' => $currentMonth,
                            'days' => $currentWeek
                        ];
                        $currentWeek = [];
                        $currentWeekNumber++;
                    }

                    $start->addDay();
                }

                // If the last week isn't already added (for cases where $end doesn't fall on a Saturday)
                if (!empty($currentWeek)) {
                    $data['dateRange'][] = [
                        'week' => $currentWeekNumber,
                        'month' => $currentMonth,
                        'days' => $currentWeek
                    ];
                }

                foreach($data['dateRange'] as $key => $week) {
                    $startDate = reset($week['days']); // Get the first date of the week
                    $endDate = end($week['days']);     // Get the last date of the week
                
                    // Fetch the student_ic values for the current week, excluding already counted ones
                    $currentWeekStudents = DB::table('tblpayment')
                                            ->join('students', 'tblpayment.student_ic', '=', 'students.ic')
                                            ->where([
                                                ['tblpayment.process_status_id', 2],
                                                ['tblpayment.process_type_id', 1], 
                                                ['tblpayment.semester_id', 1]
                                            ])
                                            ->whereColumn('tblpayment.date', '=', 'students.date_add')  // Use whereColumn for comparing two columns
                                            ->whereBetween('tblpayment.add_date', [$startDate, $endDate])
                                            ->whereNotIn('tblpayment.student_ic', $alreadyCountedStudents)
                                            ->pluck('tblpayment.student_ic')
                                            ->unique()
                                            ->toArray();

                    // Count the number of unique student_ic values for the current week
                    $totalWeekCount = count($currentWeekStudents);

                    // Update the already counted students set
                    $alreadyCountedStudents = array_merge($alreadyCountedStudents, $currentWeekStudents);

                    $data['totalWeek'][$key] = (object) ['total_week' => $totalWeekCount];
                    $data['week'][$key] = $week['days'];

                    // $totalStudentCount2 = $data['totalWeek'][$key] ? $data['totalWeek'][$key] : 0;
                    // $data['totalWeek'][$key] = (object) ['total_week' => $totalStudentCount2];
                    
                    $data['week'][$key] = $week['days'];

                    foreach($data['week'][$key] AS $key2 => $day)
                    {

                        $data['totalDay'][$key][$key2] = count(DB::table('tblpayment')
                                                        ->join('students', 'tblpayment.student_ic', '=', 'students.ic')
                                                        ->where([
                                                            ['tblpayment.process_status_id', 2],
                                                            ['tblpayment.process_type_id', 1], 
                                                            ['tblpayment.semester_id', 1]
                                                        ])
                                                        ->whereColumn('tblpayment.date', '=', 'students.date_add')  // Use whereColumn for comparing two columns
                                                        ->where('tblpayment.date', $day)
                                                        ->select('tblpayment.id')
                                                        ->groupBy('tblpayment.student_ic')
                                                        ->get());

                        // Fetch the student_ic values for the current week, excluding already counted ones
                        $currentWeekStudents2 = DB::table('tblpayment')
                                        ->join('students', 'tblpayment.student_ic', '=', 'students.ic')
                                        ->where([
                                            ['tblpayment.process_status_id', 2],
                                            ['tblpayment.process_type_id', 1], 
                                            ['tblpayment.semester_id', 1]
                                        ])
                                        ->whereColumn('tblpayment.date', '=', 'students.date_add')  // Use whereColumn for comparing two columns
                                        ->where('tblpayment.date', $day)
                                        ->whereNotIn('tblpayment.student_ic', $alreadyCountedStudents2)
                                        ->pluck('tblpayment.student_ic')
                                        ->unique()
                                        ->toArray();

                        // Count the number of unique student_ic values for the current week
                        $totalDaysCount = count($currentWeekStudents2);

                        // Update the already counted students set
                        $alreadyCountedStudents2 = array_merge($alreadyCountedStudents2, $currentWeekStudents2);

                        $data['totalDay'][$key][$key2] = (object) ['total_day' => $totalDaysCount];                        

                        // $totalStudentCount3 = $data['totalDay'][$key][$key2] ? $data['totalDay'][$key][$key2] : 0;
                        // $data['totalDay'][$key][$key2] = (object) ['total_day' => $totalStudentCount3];

                    }
                }


                if(isset($request->print))
                {
                    
                    $data['from'] = Carbon::createFromFormat('Y-m-d', $request->from)->translatedFormat('d F Y'); ;
                    $data['to'] = Carbon::createFromFormat('Y-m-d', $request->to)->translatedFormat('d F Y');

                    return view('pendaftar.reportR2.printReportR2', compact('data'));

                } elseif (isset($request->excel)) {

                    return $this->exportToExcel($data);

                }else{

                    return view('pendaftar.reportR2.getReportR2', compact('data'));
                    
                }

            }else{

                return response()->json([
                    'error' => 'The end date cannot exceed the from date\'s month'
                ]);

            }

        }

    }

    private function exportToExcel($data)
    {
        Log::info('exportToExcel function called');
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Add Total Payment By Weeks data
        $sheet->setCellValue('A1', 'Week');
        $sheet->setCellValue('B1', 'Month');
        $sheet->setCellValue('C1', 'Total');
    
        $row = 2;
        $total_allW = 0;
        foreach ($data['dateRange'] as $key => $week) {
            $sheet->setCellValue('A' . $row, $week['week']);
            $sheet->setCellValue('B' . $row, $week['month']);
            $sheet->setCellValue('C' . $row, $data['totalWeek'][$key]->total_week);
            $total_allW += $data['totalWeek'][$key]->total_week;
            $row++;
        }
    
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('C' . $row, number_format($total_allW, 2));
    
        // Add Total Payment By Days data
        $row += 2; // Add some space between tables
        $sheet->setCellValue('A' . $row, 'Date');
        $sheet->setCellValue('B' . $row, 'Total');
    
        $row++;
        $total_allD = 0;
        foreach ($data['dateRange'] as $key => $week) {
            foreach ($data['week'][$key] as $key2 => $day) {
                $sheet->setCellValue('A' . $row, $day);
                $sheet->setCellValue('B' . $row, $data['totalDay'][$key][$key2]->total_day);
                $total_allD += $data['totalDay'][$key][$key2]->total_day;
                $row++;
            }
        }
    
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('B' . $row, number_format($total_allD, 2));
    
        $writer = new Xlsx($spreadsheet);
        $fileName = 'report.xlsx';
        $filePath = 'reports/' . $fileName;
    
        ob_start();
        $writer->save('php://output');
        $fileContents = ob_get_clean();
        Storage::disk('linode')->put($filePath, $fileContents);
    
        Log::info('File saved to Linode storage at: ' . $filePath);
    
        if (Storage::disk('linode')->exists($filePath)) {
            Log::info('File exists on Linode storage, preparing to generate URL');
    
            // Generate a temporary URL valid for 1 hour
            $url = Storage::disk('linode')->temporaryUrl($filePath, now()->addHour());
    
            return redirect($url);
        } else {
            Log::error('File not created on Linode storage');
            abort(500, 'File not created');
        }
    }


    public function incomeReport()
    {
        $loop = 21;

        for($i = 15; $i <= $loop; $i++)
        {

            $stateNot[] = $i;
            
        }

        $data['state'] = DB::table('tblstate')->whereNotIn('id', $stateNot)->get();

        return view('pendaftar.income_report.index', compact('data'));
    }

    public function getIncomeReport(Request $request)
    {

        $data['value'] = [];
        $data['b40'] = [];

        if($request->val)
        {
            foreach ($request->val as $value) {
                if (is_numeric($value)) {
                    $data['value'][] = $value;
                } elseif ($value === 'yes') {
                    $data['b40'] = $value;
                }
            }
        }
            

        $query = DB::table('students')
                          ->leftjoin('tblstudent_personal','students.ic','tblstudent_personal.student_ic')
                          ->leftjoin('tblsex','tblstudent_personal.sex_id','tblsex.id')
                          ->leftjoin('tblprogramme','students.program','tblprogramme.id')
                          ->leftjoin('sessions','students.session','sessions.SessionID')
                          ->leftjoin('tblstudent_status','students.status','tblstudent_status.id')
                          ->leftjoin('tblstudent_address','students.ic','tblstudent_address.student_ic')
                          ->leftjoin('tblstate','tblstudent_address.state_id','tblstate.id')
                          ->leftjoin('tblstudent_waris','students.ic','tblstudent_waris.student_ic')
                          ->where([
                            ['students.status', 2],
                            ['students.campus_id', 1]
                          ])
                          ->whereIn('students.student_status', [1,2,4])
                          ->groupBy('students.ic')
                          ->orderBy('students.name')
                          ->select('students.*', 'tblsex.code', 'tblprogramme.progcode', 'sessions.SessionName', 'tblstudent_status.name AS status','tblstudent_personal.no_tel',
                                    DB::raw('CONCAT_WS(", ", tblstudent_address.address1, tblstudent_address.address2, tblstudent_address.address3, tblstudent_address.city, tblstudent_address.postcode, tblstate.state_name) AS full_address'),
                                    'tblstudent_waris.dependent_no', DB::raw('SUM(tblstudent_waris.kasar) AS gajikasar'));

        if($data['b40'])
        {

            $query = $query->havingRaw('SUM(tblstudent_waris.kasar) <= 4850');

        }

        if($data['value'])
        {

            $query = $query->whereIn('tblstudent_address.state_id', $data['value']);

        }

        $data['students'] = $query->get();

        // // Return the data as part of the response
        // return response()->json([
        //     'data' => $data['result'],
        // ]);

        return view('pendaftar.income_report.getStudents', compact('data'));

    }

    public function internationalReport()
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                           ->join('tblsex', 'tblstudent_personal.sex_id', 'tblsex.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions as intake', 'students.intake', 'intake.SessionID')
                           ->join('sessions as sessions', 'students.session', 'sessions.SessionID')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->where('tblstudent_personal.statelevel_id', '!=', 1)
                           ->select('students.*', 'tblsex.code', 'tblprogramme.progcode', 'intake.SessionName AS intake', 
                                    'sessions.SessionName AS session', 'tblstudent_status.name AS status')
                           ->orderBy('students.name')
                           ->get();

        return view('pendaftar.report.international_student.index', compact('data'));

    }

    public function annualStudentReport()
    {

        return view('pendaftar.report.annual_student_report.index');

    }

    public function getAnnualStudentReport(Request $request)
    {

        $ic = DB::table('tblstudent_log')
        ->join('sessions', 'tblstudent_log.session_id', 'sessions.SessionID')
        ->whereIn('tblstudent_log.student_ic', function($query){
          $query->select('students.ic')
                ->from('students')
                ->where('students.no_matric', '!=', null)
                ->where('students.status', '<>', 9);
        })
        ->where('sessions.Year', $request->year)
        ->distinct()
        ->pluck('tblstudent_log.student_ic');


        $sub1 = DB::table('tblstudent_log')
            ->join('sessions', 'tblstudent_log.session_id', '=', 'sessions.SessionID')
            ->join('students', 'tblstudent_log.student_ic', '=', 'students.ic')
            ->select('tblstudent_log.student_ic', DB::raw('MAX(tblstudent_log.id) as latest_id'))
            ->whereIn('tblstudent_log.student_ic', $ic)
            ->where('tblstudent_log.semester_id', 1)
            ->whereYear('tblstudent_log.date', '=', $request->year)
            ->groupBy('tblstudent_log.student_ic');

        $filteredSub1 = DB::table('tblstudent_log as latest_log')
            ->joinSub($sub1, 'sub1', function($join){
                $join->on('latest_log.id', '=', 'sub1.latest_id');
            })
            ->join('sessions', 'latest_log.session_id', '=', 'sessions.SessionID')
            ->select('latest_log.student_ic', 'latest_log.id AS latest_id')
            ->whereYear('latest_log.date', '=', $request->year);


        $sub2 = DB::table('tblstudent_log')
               ->leftjoin('sessions', 'tblstudent_log.session_id', 'sessions.SessionID')
               ->join('students', function($join){
                    $join->on('tblstudent_log.student_ic', 'students.ic');
               })
               ->select('tblstudent_log.student_ic', DB::raw('MAX(tblstudent_log.id) as latest_id'))
               ->whereIn('tblstudent_log.student_ic', $ic)
               ->whereYear('tblstudent_log.date', '=', $request->year)
               ->where('tblstudent_log.semester_id', '>', 1)
               ->groupBy('tblstudent_log.student_ic');

        $baseQuery = function () use ($ic, $request) {
        return DB::table('students')
        ->leftjoin('tblstudent_log', 'students.ic', 'tblstudent_log.student_ic')
        ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
        ->leftjoin('tblsex', 'tblstudent_personal.sex_id', 'tblsex.id')
        ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
        ->leftjoin('sessions', 'tblstudent_log.session_id', 'sessions.SessionID')
        ->leftjoin('tblstudent_status', 'tblstudent_log.status_id', 'tblstudent_status.id')
        ->whereIn('students.ic', $ic)
        ->whereYear('tblstudent_log.date', '=', $request->year);
        };

        $data['student1'] = ($baseQuery)()  // Make sure $baseQuery is defined correctly
        ->joinSub($filteredSub1, 'latest_logs', function ($join) {
            $join->on('tblstudent_log.student_ic', '=', 'latest_logs.student_ic')
                ->on('tblstudent_log.id', '=', 'latest_logs.latest_id');
        })
        ->where('tblstudent_log.semester_id', 1)
        ->select('students.name', 'students.ic', 'students.no_matric', 'tblsex.code as gender', 
                'tblprogramme.progcode', 'sessions.SessionName AS session', 
                'tblstudent_log.semester_id AS semester', 'tblstudent_log.date', 
                'tblstudent_log.remark', 'tblstudent_status.name AS status')
        ->get();

        $data['student2'] = ($baseQuery)()
        ->joinSub($sub2, 'latest_logs', function ($join) {
            $join->on('tblstudent_log.student_ic', '=', 'latest_logs.student_ic')
                 ->on('tblstudent_log.id', '=', 'latest_logs.latest_id');
        })
        ->where('tblstudent_log.semester_id', '>', 1)
        ->select('students.name', 'students.ic', 'students.no_matric', 'tblsex.code as gender', 'tblprogramme.progcode',
                'sessions.SessionName AS session', 'tblstudent_log.semester_id AS semester', 'tblstudent_log.date', 'tblstudent_log.remark',
                'tblstudent_status.name AS status')
        ->get();

        return view('pendaftar.report.annual_student_report.getStudent', compact('data'));

    }
    
}
