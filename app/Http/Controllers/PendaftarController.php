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
            ->join('tblsex', 'tblstudent_personal.sex_id', 'tblsex.id')
            ->select('students.*', 'tblprogramme.progname', 'a.SessionName AS intake', 
                     'b.SessionName AS session', 'tblstudent_status.name AS status',
                     'tblstudent_personal.no_tel', 'tblsex.sex_name AS gender');

        if(!empty($request->program))
        {
            $student->where('students.program', $request->program);
        }
        
        if(!empty($request->session))
        {
            $student->where('students.session', $request->session);
        }
        
        if(!empty($request->year))
        {
            $student->where('a.Year', $request->year);
        }
        
        if(!empty($request->semester))
        {
            $student->where('students.semester', $request->semester);
        }
        
        if(!empty($request->status))
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
                </td>';
                

                if (isset($request->edit)) {
                    $content .= '<td class="project-actions text-right" >
                                <a class="btn btn-info btn-sm btn-sm mr-2 mb-2" href="/pendaftar/edit/'. $student->ic .'">
                                    <i class="ti-pencil-alt">
                                    </i>
                                    Edit
                                </a>
                                <a class="btn btn-primary btn-sm btn-sm mr-2 mb-2" href="/pendaftar/spm/'. $student->ic .'">
                                    <i class="ti-ruler-pencil">
                                    </i>
                                    SPM
                                </a>
                                <a class="btn btn-secondary btn-sm btn-sm mr-2 mb-2" href="#" onclick="getProgram(\''. $student->ic .'\')">
                                    <i class="ti-eye">
                                    </i>
                                    Program History
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
            ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
            ->join('tblsex', 'tblstudent_personal.sex_id', 'tblsex.id')
            ->select('students.*', 'tblprogramme.progname', 'a.SessionName AS intake', 
                     'b.SessionName AS session', 'tblstudent_status.name AS status',
                     'tblstudent_personal.no_tel', 'tblsex.sex_name AS gender')
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
                            <a class="btn btn-info btn-sm btn-sm mr-2 mb-2" href="/pendaftar/edit/'. $student->ic .'">
                                <i class="ti-pencil-alt">
                                </i>
                                Edit
                            </a>
                            <a class="btn btn-primary btn-sm btn-sm mr-2 mb-2" href="/pendaftar/spm/'. $student->ic .'">
                                <i class="ti-ruler-pencil">
                                </i>
                                SPM
                            </a>
                            <a class="btn btn-secondary btn-sm btn-sm mr-2 mb-2" href="#" onclick="getProgram(\''. $student->ic .'\')">
                                <i class="ti-eye">
                                </i>
                                Program History
                            </a>
                            <!-- <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial(\''. $student->ic .'\')">
                                <i class="ti-trash">
                                </i>
                                Delete
                            </a> -->
                            </td>
                        
                        ';
           
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

            $numWaris = count($request->input('w_name'));
            for ($i = 0; $i < $numWaris; $i++) {

                if($request->input('w_name')[$i] != '')
                {
                    DB::table('tblstudent_waris')->insert([
                        'student_ic' => $data['id'],
                        'name' => $request->input('w_name')[$i],
                        'ic' => $request->input('w_ic')[$i],
                        'home_tel' => $request->input('w_notel_home')[$i],
                        'phone_tel' => $request->input('w_notel')[$i],
                        'occupation' => $request->input('occupation')[$i],
                        'dependent_no' => $request->input('dependent')[$i],
                        'kasar' => $request->input('w_kasar')[$i],
                        'bersih' => $request->input('w_bersih')[$i],
                        'relationship' => $request->input('relationship')[$i],
                        'race' => $request->input('w_race')[$i],
                        'status' => $request->input('w_status')[$i]
                    ]);
                }
            }

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

    public function edit()
    {
        $student = DB::table('students')
                   ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                   ->leftjoin('tblstudent_address', 'students.ic', 'tblstudent_address.student_ic')
                   ->leftjoin('tblstudent_pass', 'students.ic', 'tblstudent_pass.student_ic')
                   ->leftjoin('student_form', 'students.ic', 'student_form.student_ic')
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

        DB::table('tblstudent_personal')->where('student_ic', $data['id'])->update([
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
                    'home_tel' => $request->input('w_notel_home')[$i],
                    'phone_tel' => $request->input('w_notel')[$i],
                    'occupation' => $request->input('occupation')[$i],
                    'dependent_no' => $request->input('dependent')[$i],
                    'kasar' => $request->input('w_kasar')[$i],
                    'bersih' => $request->input('w_bersih')[$i],
                    'relationship' => $request->input('relationship')[$i],
                    'race' => $request->input('w_race')[$i],
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
                           ->join('sessions', 'tblstudent_log.session_id', 'sessions.SessionID')
                           ->join('tblstudent_status', 'tblstudent_log.status_id', 'tblstudent_status.id')
                           ->where('student_ic', $request->student)
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

        $data['session'] = DB::table('sessions')->get();

        $data['semester'] = DB::table('semester')->get();

        $data['status'] = DB::table('tblstudent_status')->get();

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
                    'student_status' => $student->kuliah
                ]);

                DB::table('tblstudent_log')->insert([
                    'student_ic' => $student->ic,
                    'session_id' => $student->session,
                    'semester_id' => $student->semester,
                    'status_id' => $student->status,
                    'kuliah_id' => $stds,
                    'date' => date("Y-m-d H:i:s"),
                    'remark' => $student->comment,
                    'add_staffID' => Auth::user()->ic
                ]);

                $std_log = DB::table('tblstudent_log')
                           ->join('sessions', 'tblstudent_log.session_id', 'sessions.SessionID')
                           ->join('tblstudent_status', 'tblstudent_log.status_id', 'tblstudent_status.id')
                           ->where('student_ic', $student->ic)
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
                                        Date
                                    </th>
                                    <th style="width: 20%">
                                        Remark
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
                        '. $std->date .'
                        </td>
                        <td>
                        '. $std->remark .'
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

        $data['program'] = DB::table('tblprogramme')->join('tblfaculty', 'tblprogramme.facultyid', 'tblfaculty.id')->select('tblprogramme.*', 'tblfaculty.facultyname')->get();

        $data['faculty'] = DB::table('tblfaculty')->get();

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
                                    ['tblstudent_personal.sex_id', 1]
                                    ])->get());
            
            $data['fs1'][$key] = count(DB::table('students')
                                 ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                 ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 1],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2]
                                    ])->get());
            
            $data['ms2'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 2],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1]
                                    ])->get());

            $data['fs2'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 2],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2]
                                    ])->get());

            $data['ms3'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 3],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1]
                                    ])->get());

            $data['fs3'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 3],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2]
                                    ])->get());
                                    
            $data['ms4'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 4],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1]
                                    ])->get());

            $data['fs4'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 4],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2]
                                    ])->get());

            $data['ms5'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                     ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 5],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1]
                                    ])->get());

            $data['fs5'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                     ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 5],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2]
                                    ])->get());

            $data['ms6'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 6],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1]
                                    ])->get());

            $data['fs6'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 6],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2]
                                    ])->get());

            $data['ms7'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 7],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1]
                                    ])->get());

            $data['fs7'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 7],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2]
                                    ])->get());

            $data['ms8'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 8],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 1]
                                    ])->get());

            $data['fs8'][$key] = count(DB::table('students')
                                    ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.semester', 8],
                                    ['students.status', 2],
                                    ['students.student_status', 2],
                                    ['tblstudent_personal.sex_id', 2]
                                    ])->get());

            $data['industry'][$key] = count(DB::table('students')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.status', 2],
                                    ['students.student_status', 4],
                                    //['students.campus_id', 1]
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
                                    ['students.campus_id', 0],
                                    ['students.student_status', 2]
                                    ])->get());
                                    
            $data['postpone'][$key] = count(DB::table('students')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.status', 3],
                                    ['students.campus_id', 0],
                                    ['students.student_status', 2]
                                    ])->get());

            $data['dismissed'][$key] = count(DB::table('students')
                                    ->where([
                                    ['students.program', $prg->id],
                                    ['students.status', 4]
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

    public function addTranscript(Request $request)
    {

        $data = json_decode($request->addTranscript);

        $transcript_status_id = '';

        if($data->program != null && $data->session != null && $data->semester != null)
        {

            $students = DB::table('students')->where([
                ['program', $data->program]
            ])->pluck('ic');

            foreach($students as $std)
            {
                DB::table('student_transcript')->where([
                    ['student_ic', $std],
                    ['session_id', $data->session],
                    ['semester', $data->semester]
                ])->delete();

                $total_credit_s = DB::table('student_subjek')->where([
                    ['student_ic', $std],
                    ['sessionid', $data->session],
                    ['semesterid', $data->semester]
                ])->whereIn('course_status_id', [1,2,12,15])->sum('credit');

                $passed_credit_s = DB::table('student_subjek')->where([
                    ['student_ic', $std],
                    ['sessionid', $data->session],
                    ['semesterid', $data->semester]
                ])->whereIn('course_status_id', [1])->sum('credit');

                $grade_pointer_s = DB::table('student_subjek')
                ->where([
                    ['student_ic', $std],
                    ['sessionid', $data->session],
                    ['semesterid', $data->semester]
                ])
                ->whereIn('course_status_id', [1,2,12,15])
                ->selectRaw('SUM(credit * pointer) as total')
                ->value('total');

                $gpa = DB::table('student_subjek')
                ->where([
                    ['student_ic', $std],
                    ['sessionid', $data->session],
                    ['semesterid', $data->semester]
                ])
                ->whereIn('course_status_id', [1,2,12,15])
                ->selectRaw('SUM(credit * pointer) / SUM(credit) as total')
                ->value('total');

                $total_credit_c = DB::table('student_subjek')->where([
                    ['student_ic', $std]
                ])->where('semesterid', '<=', $data->semester)
                ->whereIn('course_status_id', [1,2,12,15])->sum('credit');

                $passed_credit_c = DB::table('student_subjek')->where([
                    ['student_ic', $std]
                ])->where('semesterid', '<=', $data->semester)
                ->whereIn('course_status_id', [1])->sum('credit');

                $count_credit_c = DB::table('student_subjek')->where([
                    ['student_ic', $std]
                ])->where('semesterid', '<=', $data->semester)
                ->whereIn('course_status_id', [1,2,12,15])
                ->distinct('courseid')
                ->sum('credit');

                $grade_pointer_c = DB::table('student_subjek')
                ->select('courseid', DB::raw('SUM(credit * pointer) as total'))
                ->where([
                    ['student_ic', $std]
                ])->where('semesterid', '<=', $data->semester)
                ->whereIn('course_status_id', [1,2,12,15])
                ->groupBy('courseid')
                ->groupBy(DB::raw('(SELECT MAX(id) FROM student_subjek as ss2 WHERE ss2.courseid = student_subjek.courseid)'))
                ->value('total');

                $cgpa = DB::table('student_subjek')
                ->select('courseid', DB::raw('ROUND(SUM(credit * pointer) / 2) as total'))
                ->where([
                    ['student_ic', $std]
                ])->where('semesterid', '<=', $data->semester)
                ->whereIn('course_status_id', [1,2,12,15])
                ->groupBy('courseid')
                ->groupBy(DB::raw('(SELECT MAX(id) FROM student_subjek as ss2 WHERE ss2.courseid = student_subjek.courseid)'))
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

            $data = DB::table('student_transcript')
                    ->join('students', 'student_transcript.student_ic', 'students.ic')
                    ->join('sessions', 'student_transcript.session_id', 'sessions.SessionID')
                    ->where([
                        ['students.program', $data->program],
                        ['student_transcript.session_id', $data->session],
                        ['student_transcript.semester', $data->semester]
                    ])->select('student_transcript.*', 'students.name', 'sessions.SessionName')
                    ->get();

            return response()->json(['message' => 'Success', 'data' => $data]);

        }

    }

    
}
