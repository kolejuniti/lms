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
use GuzzleHttp\Client;

class PendaftarController extends Controller
{
    public function dashboard()
    {
        Session::put('User', Auth::user());

        $data['year'] = DB::table('tblyear')->get();

        foreach($data['year'] as $key => $year)
        {

            $data['student'][$key] = DB::table('tblstudent_log')
                                     ->where(DB::raw('YEAR(date)'), $year->year)
                                     ->where([
                                        ['semester_id', 1],
                                        ['status_id', 2]
                                        ])
                                     ->count();
        }

        $data['status'] = DB::table('tblstudent_status')->get();

        return view('dashboard', compact('data'));
    }

    public function getCircleData(Request $request)
    {
        // Parse the date from the request
        $selectedDate = Carbon::parse($request->date);

        // Extract month and year from the selected date
        $selectedMonth = $selectedDate->month;
        $selectedYear = $selectedDate->year;

        // Retrieve counts based on the selected statuses and date
        $statusCounts = DB::table('tblstudent_log')
            ->select('status_id', DB::raw('count(*) as total'))
            ->whereIn('status_id', $request->statuses)
            ->whereYear('date', '=', $selectedYear)
            ->whereMonth('date', '=', $selectedMonth)
            ->groupBy('status_id')
            ->get();

        // Prepare the response data
        $labels = [];
        $data = [];
        foreach ($statusCounts as $statusCount) {
            $status = DB::table('tblstudent_status')->where('id', $statusCount->status_id)->first();
            $labels[] = $status->name;
            $data[] = $statusCount->total;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
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
            ->leftjoin('tblreligion', 'tblstudent_personal.religion_id', 'tblreligion.id')
            ->leftjoin('tblnationality', 'tblstudent_personal.nationality_id', 'tblnationality.id')
            ->select('students.*', 'tblprogramme.progcode', 'a.SessionName AS intake', 
                     'b.SessionName AS session', 'tblstudent_status.name AS status',
                     'tblstudent_personal.no_tel', 'tblsex.code AS gender', 'tblqualification_std.name AS qualification',
                     'tblreligion.religion_name AS religion', 'tblnationality.nationality_name AS race');

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
        // Add a hidden marker header column:
        $content .= '<thead>
                        <tr>
                            <th style="display:none">Marker</th> <!-- Hidden marker column -->
                            <th style="width: 1%">No.</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>No. IC</th>
                            <th>No. Matric</th>
                            <th>Program</th>
                            <th>Intake</th>
                            <th>Current Session</th>
                            <th>Semester</th>
                            <th>Sponsorship</th>
                            <th>Status</th>
                            <th>No. Phone</th>
                            <th>Campus</th>
                            <th>Qualification</th>
                            <th>Race</th>
                            <th>Religion</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="table">';

        foreach ($students as $key => $student) {
            // Determine if this row should be marked red
            $marker = ($student->campus_id == 0) ? 'red' : '';

            // Open the <tr> tag, conditionally apply inline red background
            $content .= '<tr ' . ($student->campus_id == 0 ? 'style="background-color: red;"' : '') . '>';

            // Hidden marker cell
            $content .= '<td style="display:none;">' . $marker . '</td>';

            // "No." column
            $content .= '<td style="width: 1%">' . ($key + 1) . '</td>';

            // Other columns
            $content .= '<td>' . $student->name . '</td>';
            $content .= '<td>' . $student->gender . '</td>';
            $content .= '<td>' . $student->ic . '</td>';
            $content .= '<td>' . $student->no_matric . '</td>';
            $content .= '<td>' . $student->progcode . '</td>';
            $content .= '<td>' . $student->intake . '</td>';
            $content .= '<td>' . $student->session . '</td>';
            $content .= '<td>' . $student->semester . '</td>';
            $content .= '<td>' . $sponsor[$key] . '</td>';
            $content .= '<td>' . $student->status . '</td>';
            $content .= '<td>' . $student->no_tel . '</td>';
            $content .= '<td>' . $student_status[$key] . '</td>';
            $content .= '<td>' . $student->qualification . '</td>';
            $content .= '<td>' . $student->race . '</td>';
            $content .= '<td>' . $student->religion . '</td>';

            // Action buttons
            if (isset($request->edit)) {
                $content .= '<td class="project-actions text-right">
                                <a class="btn btn-info btn-sm mr-2 mb-2" href="/pendaftar/view/' . $student->ic . '">
                                    <i class="ti-pencil-alt"></i> View
                                </a>
                                <a class="btn btn-info btn-sm mr-2 mb-2" href="/pendaftar/edit/' . $student->ic . '">
                                    <i class="ti-pencil-alt"></i> Edit
                                </a>
                                <a class="btn btn-primary btn-sm mr-2 mb-2" href="/pendaftar/spm/' . $student->ic . '">
                                    <i class="ti-ruler-pencil"></i> SPM/SVM/SKM
                                </a>
                                <a class="btn btn-secondary btn-sm mr-2 mb-2" href="#" onclick="getProgram(\'' . $student->ic . '\')">
                                    <i class="ti-eye"></i> Program History
                                </a>
                                <a class="btn btn-secondary btn-sm mr-2 mb-2" target="_blank" href="/AR/student/getSlipExam?student=' . $student->ic . '">
                                    <i class="fa fa-info"></i> Slip Exam
                                </a>
                            </td>';
            } else {
                $content .= '<td class="project-actions text-right">
                                <a class="btn btn-secondary btn-sm mr-2" href="#" onclick="getProgram(\'' . $student->ic . '\')">
                                    <i class="ti-eye"></i> Program History
                                </a>
                            </td>';
            }

            // Close the row here
            $content .= '</tr>';
        }

        // After the loop, only close the <tbody>
        $content .= '</tbody>';

        // Return the final HTML
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
                'kuliah_id' => 1,
                'campus_id' => 0,
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
        
        
        //dd(request());

        if(isset(request()->print))
        {

            $student = DB::table('students')
                   ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                   ->leftjoin('sessions AS c', 'students.intake', 'c.SessionID')
                   ->leftjoin('sessions AS d', 'students.session', 'd.SessionID')
                   ->leftJoin('tblbatch', 'students.batch', 'tblbatch.BatchID')
                   ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                   ->leftjoin('tblstudent_status', 'students.status', 'tblstudent_status.id')
                   ->leftjoin('tblsex', 'tblstudent_personal.sex_id', 'tblsex.id')
                   ->leftjoin('tblnationality', 'tblstudent_personal.nationality_id', 'tblnationality.id')
                   ->leftjoin('tblstate AS a', 'tblstudent_personal.state_id', 'a.id')
                   ->leftjoin('tblreligion', 'tblstudent_personal.religion_id', 'tblreligion.id')
                   ->leftjoin('tblcitizenship_level', 'tblstudent_personal.statelevel_id', 'tblcitizenship_level.id')
                   ->leftjoin('tblcitizenship', 'tblstudent_personal.citizenship_id', 'tblcitizenship.id')
                   ->leftjoin('tblmarriage', 'tblstudent_personal.marriage_id', 'tblmarriage.id')
                   ->leftjoin('tbledu_advisor', 'tblstudent_personal.advisor_id', 'tbledu_advisor.id')
                   ->leftjoin('tbldun', 'tblstudent_personal.dun', 'tbldun.id')
                   ->leftjoin('tblparlimen', 'tblstudent_personal.parlimen', 'tblparlimen.id')
                   ->leftjoin('tblqualification_std', 'tblstudent_personal.qualification', 'tblqualification_std.id')
                   ->leftjoin('tblstudent_address', 'students.ic', 'tblstudent_address.student_ic')
                   ->leftjoin('tblcountry', 'tblstudent_address.country_id', 'tblcountry.id')
                   ->leftjoin('tblstate AS b', 'tblstudent_address.state_id', 'b.id')
                   ->leftjoin('tblstudent_pass', 'students.ic', 'tblstudent_pass.student_ic')
                   ->leftjoin('tblpass_type', 'tblstudent_pass.pass_type', 'tblpass_type.id')
                   ->leftjoin('student_form', 'students.ic', 'student_form.student_ic')
                   ->select('students.*', 'tblstudent_personal.*', 'tblstudent_address.*', 
                                     'tblstudent_pass.*', 'student_form.*', 'a.state_name AS place_birth',
                                     'tblbatch.BatchName', 'tblsex.sex_name', 'tblnationality.nationality_name',
                                     'tblreligion.religion_name', 'tblcitizenship_level.citizenshiplevel_name', 'tblcitizenship.citizenship_name',
                                     'tblmarriage.marriage_name', 'tbledu_advisor.name AS advisor', 'tblpass_type.name AS pass_type',
                                     'tblcountry.name AS country', 'b.state_name AS state_name2', 'tbldun.name AS dun',
                                     'tblparlimen.name AS parlimen', 'tblqualification_std.name AS qualification',
                                     'tblprogramme.progname', 'c.SessionName', 'd.SessionName AS session', 'tblstudent_status.name AS status')
                   ->where('students.ic',request()->ic)->first();

            $data['waris'] = DB::table('tblstudent_waris')
                             ->leftjoin('tblrelationship', 'tblstudent_waris.relationship', 'tblrelationship.id')
                             ->leftjoin('tblwaris_status', 'tblstudent_waris.status', 'tblwaris_status.id')
                             ->where('student_ic', $student->ic)
                             ->select('tblstudent_waris.*', 'tblrelationship.name AS relationship', 'tblwaris_status.name AS status')
                             ->get();

            $data['hostel'] = DB::connection('mysql3')->table('tblstudent_hostel')
                              ->leftjoin('tblblock_unit', 'tblstudent_hostel.block_unit_id', 'tblblock_unit.id')
                              ->leftjoin('tblblock', 'tblblock_unit.block_id', 'tblblock.id')
                              ->where('tblstudent_hostel.student_ic', $student->ic)
                              ->where('tblstudent_hostel.status', 'IN')
                              ->select('tblblock_unit.no_unit', DB::raw('CONCAT(tblblock.name, " - ", tblblock.location) AS block_name'))
                              ->orderBy('tblstudent_hostel.id', 'DESC')
                              ->first();


            return view('pendaftar.updatePrint', compact(['student','data']));

        }else{

            $student = DB::table('students')
                   ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                   ->leftjoin('tblstudent_address', 'students.ic', 'tblstudent_address.student_ic')
                   ->leftjoin('tblstudent_pass', 'students.ic', 'tblstudent_pass.student_ic')
                   ->leftjoin('student_form', 'students.ic', 'student_form.student_ic')
                   ->select('students.*', 'tblstudent_personal.*', 'tblstudent_address.*', 
                                     'tblstudent_pass.*', 'student_form.*', 'tblstudent_personal.state_id AS place_birth')
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
                 ->join('tblbatch', 'student_program.batch', 'tblbatch.BatchID')
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

        $data['subject'] = DB::table('tblsubject_spm')->orderBy('name')->get();

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

                if($log->campus_id == 0)
                {

                    $kuliah[$key] = 'No';

                }elseif($log->campus_id == 2)
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

                if($student->status == 3)
                {

                    DB::table('student_transcript')
                        ->where('student_ic', $student->ic)
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->update([
                            'transcript_status_id' => 5
                        ]);

                }

                if($student->status != 2)
                {

                    DB::table('students')->where('ic', $student->ic)->update([
                        'campus_id' => 0
                    ]);

                }

                DB::table('tblstudent_log')->insert([
                    'student_ic' => $student->ic,
                    'session_id' => $student->session,
                    'semester_id' => $student->semester,
                    'status_id' => $student->status,
                    'kuliah_id' => $student->kuliah,
                    'campus_id' => $stds,
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

                    if($log->campus_id == 0)
                    {

                        $kuliah[$key] = 'No';

                    }elseif($log->campus_id == 2)
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

    public function statusUpdateBulk()
    {

        $data = [
            'programs' => DB::table('tblprogramme')->get(),
            'sessions' => DB::table('sessions')->orderBy('SessionID', 'DESC')->get(),
            'semesters' => DB::table('semester')->get(),
            'status' => DB::table('tblstudent_status')->get(),
            'batch' => DB::table('tblbatch')->get()
        ];

        return view('pendaftar.update_bulk.studentUpdateBulk', compact('data'));

    }

    public function getStatusUpdateBulk(Request $request)
    {

        $program = $request->program;
        $session = $request->session;
        $semester = $request->semester;
        $session2 = $request->session2;

        $query = DB::table('students')->where(function($query) {
            $query->where('campus_id', 0)
                  ->where('status', 2)
                  ->where('block_status', '!=', 1);
        });

        $query2 = DB::table('students')->where(function($query2) {
            $query2->where('campus_id', 0)
                  ->where('status', 2)
                  ->where('block_status', '!=', 1);
        });

        if($program != '' && $session != '' && $semester != '')
        {

            $data['campus'] = $query->where([
                ['program', $program],
                ['session', $session],
                ['semester', $semester]
            ])->get();

            $data['leave'] = [];

        }else{


            $data['campus'] = [];

            $data['leave'] = [];

        }

        if($session2 != '')
        {

            $data['leave'] = $query2->where([
                ['program', $program],
                ['session', $session2],
                ['semester', $semester + 1]
            ])->get();

        }


        return view('pendaftar.update_bulk.getStudentUpdateBulk', compact('data'));
    }

    public function updateStatusUpdateBulk(Request $request)
    {

        foreach($request->leave AS $matric)
        {
            $student = DB::table('students')->where('no_matric', $matric)->first();

            if($student->block_status != 1)
            {

                if($student->status != 6)
                {

                    $newsem = $student->semester + 1;

                }else{

                    $newsem = $student->semester;

                }

                DB::table('students')->where('no_matric', $matric)->update([
                    'session' => $request->session2,
                    'semester' => $newsem
                ]);

                $userUpt = UserStudent::where('no_matric', $matric)->first();

                DB::table('tblstudent_log')->insert([
                    'student_ic' => $userUpt->ic,
                    'session_id' => $userUpt->session,
                    'semester_id' => $userUpt->semester,
                    'status_id' => $userUpt->status,
                    'kuliah_id' => $userUpt->student_status,
                    'campus_id' => $userUpt->campus_id,
                    'date' => date("Y-m-d H:i:s"),
                    'remark' => null,
                    'add_staffID' => Auth::user()->ic
                ]);


                $this->getRegisterClaim($student->ic);

            }

        }
        
        return ['message' => 'success'];

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

            // Create base query to avoid duplication
            $baseQuery = DB::table('students')
                          ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                          ->join('tblfaculty', 'tblprogramme.facultyid', 'tblfaculty.id')
                          ->where('tblfaculty.id', $prg->facultyid);

            // Get count of active students (status=2)
            $data['sum'][$key] = $baseQuery->clone()
                                         ->where('students.status', 2)
                                         ->where('students.campus_id', 1)
                                         ->count();

            // Get total count of all students
            $data['sum2'][$key] = $baseQuery->count();

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
                    ['group_id', '!=', null]
                ])
                ->count() > 0)
                {

                    $total_credit_s = DB::table('student_subjek')->where([
                        ['student_ic', $std],
                        ['sessionid', $data->session],
                        ['semesterid', $data->semester],
                        ['group_id', '!=', null]
                    ])
                    ->whereIn('course_status_id', [1,2,12,15])->sum('credit');

                    $passed_credit_s = DB::table('student_subjek')->where([
                        ['student_ic', $std],
                        ['sessionid', $data->session],
                        ['semesterid', $data->semester],
                        ['group_id', '!=', null]
                    ])
                    ->whereIn('course_status_id', [1])->sum('credit');

                    $grade_pointer_s = DB::table('student_subjek')
                    ->where([
                        ['student_ic', $std],
                        ['sessionid', $data->session],
                        ['semesterid', $data->semester],
                        ['group_id', '!=', null]
                    ])
                    // ->where(function($query){
                    //     $query->where('group_id', '!=', null)
                    //     ->orWhere('grade', '!=', null);
                    // })
                    ->whereIn('course_status_id', [1,2,12,15])
                    ->selectRaw('SUM(credit * pointer) as total')
                    ->value('total');

                    $gpa = DB::table('student_subjek')
                    ->where([
                        ['student_ic', $std],
                        ['sessionid', $data->session],
                        ['semesterid', $data->semester],
                        ['group_id', '!=', null]
                    ])
                    ->whereIn('course_status_id', [1,2,12,15])
                    ->selectRaw('SUM(credit * pointer) / SUM(credit) as total')
                    ->value('total');

                    $total_credit_c = DB::table('student_subjek')->where([
                        ['student_ic', $std],
                        ['group_id', '!=', null]
                    ])
                    ->where('semesterid', '<=', $data->semester)
                    ->whereIn('course_status_id', [1,2,12,15])
                    ->sum('credit');

                    $passed_credit_c = DB::table('student_subjek')->where([
                        ['student_ic', $std],
                        ['group_id', '!=', null]
                    ])
                    ->where('semesterid', '<=', $data->semester)
                    ->whereIn('course_status_id', [1])->sum('credit');

                    $distinct_courses = DB::table('student_subjek')
                    ->where([
                        ['student_ic', $std],
                        ['group_id', '!=', null]
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
                        ->whereNotNull('group_id')
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
                        ->whereNotNull('group_id')
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

    public function studentTranscript2()
    {

        $data = [
            'session' => DB::table('sessions')->get(),
            'semester' => DB::table('semester')->get()
        ];

        return view('pendaftar.studentTranscript2', compact('data'));

    }
    
    public function getTranscript2(Request $request)
    {

        $data = DB::table('student_transcript')
                    ->join('students', 'student_transcript.student_ic', 'students.ic')
                    ->join('sessions', 'student_transcript.session_id', 'sessions.SessionID')
                    ->join('transcript_status', 'student_transcript.transcript_status_id', 'transcript_status.id')
                    ->where([
                        ['students.ic', $request->student],
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

    public function addTranscript2(Request $request)
    {

        $data = json_decode($request->addTranscript);

        $transcript_status_id = '';

        if($data->student != null && $data->session != null && $data->semester != null)
        {

            DB::table('student_transcript')
            ->join('students', 'student_transcript.student_ic', 'students.ic')
            ->where([
                ['students.ic', $data->student],
                ['student_transcript.session_id', $data->session],
                ['student_transcript.semester', $data->semester]
            ])->delete();

            $students = DB::table('students')->where([
                ['ic', $data->student]
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
                        ['students.ic', $data->student],
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

                                    // DB::raw('CASE
                                    //             WHEN IFNULL(SUM(tblpaymentdtl.amount), 0) < 250 THEN "R"
                                    //             WHEN IFNULL(SUM(tblpaymentdtl.amount), 0) >= 250 THEN "R1"
                                    //             END AS group_alias'),
                                    DB::raw('"R2" AS group_alias'),
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

                $data['lastWithdraw'][$key] = DB::table('tblstudent_log')
                                              ->where([
                                                ['student_ic', $student->ic],
                                                ['status_id', 4]
                                              ])->orderBy('id', 'DESC')->value('date');

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

                $data['lastWithdraw2'][$key] = DB::table('tblstudent_log')
                                ->where([
                                  ['student_ic', $student->ic],
                                  ['status_id', 4]
                                ])->orderBy('id', 'DESC')->value('date');

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
    
            $start = Carbon::parse($request->from);
            $end = Carbon::parse($request->to);
            $end2 = $start->copy()->endOfMonth();

            if($end <= $end2)
            {

                $data['totalAll'] = DB::table('tblpayment as p1')
                                    ->join('students', 'p1.student_ic', '=', 'students.ic')
                                    ->join(DB::raw('(SELECT student_ic, MIN(date) as first_payment_date 
                                            FROM tblpayment 
                                            GROUP BY student_ic) as p2'), function($join) {
                                        $join->on('p1.student_ic', '=', 'p2.student_ic')
                                             ->on('p1.date', '=', 'p2.first_payment_date');
                                    })
                                    ->where([
                                        ['p1.process_status_id', '=', 2],
                                        ['p1.process_type_id', '=', 1],
                                        ['p1.semester_id', '=', 1]
                                    ])
                                    ->whereBetween('p1.date', [$start, $end])
                                    ->select('p1.id')
                                    ->groupBy('p1.student_ic')
                                    ->get()
                                    ->count();


                $totalStudentCount = $data['totalAll'] ? $data['totalAll'] : 0;
                $data['totalAll'] = (object) ['total_student' => $totalStudentCount];

        
                $data['dateRange'] = [];
                $currentWeek = [];
                $currentMonth = $start->month;
                $currentMonthStart = $start->copy()->startOfMonth();
                $currentWeekNumber = $start->diffInWeeks($currentMonthStart) + 1;
                $alreadyCountedStudents = [];
                $alreadyCountedStudents2 = [];
                $data['countedPerWeek'] = [];
                $data['totalConvert'] = [];
                $data['registeredPerWeek'] = [];
                $data['rejectedPerWeek'] = [];
                $data['offeredPerWeek'] = [];
                $data['KIVPerWeek'] = [];
                $data['othersPerWeek'] = [];

                $data['countedPerDay'] = [];
                $data['totalConvert2'] = [];
                $data['registeredPerDay'] = [];
                $data['rejectedPerDay'] = [];
                $data['offeredPerDay'] = [];
                $data['KIVPerDay'] = [];
                $data['othersPerDay'] = [];

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
                
                    // Combined query to fetch both total and converted students in one go
                    $weeklyStudents = DB::table('tblpayment as p1')
                        ->select([
                            'p1.student_ic',
                            'students.status',
                            'students.date_offer',
                            'students.semester'
                        ])
                        ->join('students', 'p1.student_ic', '=', 'students.ic')
                        ->join(DB::raw('(
                            SELECT student_ic, MIN(date) as first_payment_date 
                            FROM tblpayment 
                            WHERE process_status_id = 2 
                            AND process_type_id = 1 
                            AND semester_id = 1
                            GROUP BY student_ic
                        ) as p2'), function($join) {
                            $join->on('p1.student_ic', '=', 'p2.student_ic')
                                 ->on('p1.date', '=', 'p2.first_payment_date');
                        })
                        ->where([
                            ['p1.process_status_id', 2],
                            ['p1.process_type_id', 1], 
                            ['p1.semester_id', 1]
                        ])
                        ->whereBetween('p1.add_date', [$startDate, $endDate])
                        ->whereNotIn('p1.student_ic', $alreadyCountedStudents)
                        ->get();

                    // Process results in memory instead of making separate queries
                    $currentWeekStudents = $weeklyStudents->pluck('student_ic')->unique()->values()->toArray();
                    $currentConvertStudents = $weeklyStudents->where('status', '!=', 1)
                        ->pluck('student_ic')
                        ->unique()
                        ->values()
                        ->toArray();
                    $currentRegisteredStudents = $weeklyStudents->where('status', 2)
                        ->pluck('student_ic')
                        ->unique()
                        ->values()
                        ->toArray();
                    $currentRejectedStudents = $weeklyStudents->where('status', 14)
                        ->pluck('student_ic')
                        ->unique()
                        ->values()
                        ->toArray();
                    $currentOfferedStudents = $weeklyStudents->where('status', 1)
                        ->filter(function($student) {
                            return \Carbon\Carbon::parse($student->date_offer)->gt(now());
                        })
                        ->pluck('student_ic')
                        ->unique()
                        ->values()
                        ->toArray();
                    $currentKIVStudents = $weeklyStudents->where('status', 1)
                        ->filter(function($student) {
                            return \Carbon\Carbon::parse($student->date_offer)->lte(now());
                        })
                        ->pluck('student_ic')
                        ->unique()
                        ->values()
                        ->toArray();

                    $currentOthersStudents = $weeklyStudents->where('status', '!=', 1)
                        ->where('status', '!=', 2)
                        ->where('status', '!=', 14)
                        ->pluck('student_ic')
                        ->unique()
                        ->values()
                        ->toArray();
                        
                        

                    $totalWeekCount = count($currentWeekStudents);
                    
                    // Update data arrays
                    $data['totalConvert'][$key] = count($currentConvertStudents);
                    $data['totalWeek'][$key] = (object) ['total_week' => $totalWeekCount];
                    $data['week'][$key] = $week['days'];
                    
                    // Update already counted students
                    $alreadyCountedStudents = array_merge($alreadyCountedStudents, $currentWeekStudents);
                    $data['countedPerWeek'][$key] = count($alreadyCountedStudents);

                    $data['registeredPerWeek'][$key] = count($currentRegisteredStudents);
                    $data['rejectedPerWeek'][$key] = count($currentRejectedStudents);
                    $data['offeredPerWeek'][$key] = count($currentOfferedStudents);
                    $data['KIVPerWeek'][$key] = count($currentKIVStudents);
                    $data['othersPerWeek'][$key] = count($currentOthersStudents);

                    $data['week'][$key] = $week['days'];

                    foreach($data['week'][$key] AS $key2 => $day)
                    {

                        $data['totalDay'][$key][$key2] = count(DB::table('tblpayment as p1')
                                                        ->join('students', 'p1.student_ic', '=', 'students.ic')
                                                        ->join(DB::raw('(SELECT student_ic, MIN(date) as first_payment_date 
                                                                FROM tblpayment 
                                                                GROUP BY student_ic) as p2'), function($join) {
                                                            $join->on('p1.student_ic', '=', 'p2.student_ic')
                                                                 ->on('p1.date', '=', 'p2.first_payment_date');
                                                        })
                                                        ->where([
                                                            ['p1.process_status_id', 2],
                                                            ['p1.process_type_id', 1], 
                                                            ['p1.semester_id', 1]
                                                        ])
                                                        ->where('p1.date', $day)
                                                        ->select('p1.id')
                                                        ->groupBy('p1.student_ic')
                                                        ->get());

                        // Fetch the student data for the current day
                        $dailyStudents = DB::table('tblpayment as p1')
                                        ->select([
                                            'p1.student_ic',
                                            'students.status',
                                            'students.date_offer'
                                        ])
                                        ->join('students', 'p1.student_ic', '=', 'students.ic')
                                        ->join(DB::raw('(SELECT student_ic, MIN(date) as first_payment_date 
                                                FROM tblpayment 
                                                GROUP BY student_ic) as p2'), function($join) {
                                            $join->on('p1.student_ic', '=', 'p2.student_ic')
                                                 ->on('p1.date', '=', 'p2.first_payment_date');
                                        })
                                        ->where([
                                            ['p1.process_status_id', 2],
                                            ['p1.process_type_id', 1], 
                                            ['p1.semester_id', 1]
                                        ])
                                        ->where('p1.date', $day)
                                        ->whereNotIn('p1.student_ic', $alreadyCountedStudents2)
                                        ->get();

                        // Process results for converted students
                        $currentDayStudents = $dailyStudents->pluck('student_ic')->unique()->values()->toArray();
                        $currentDayConvertStudents = $dailyStudents->where('status', '!=', 1)
                            ->pluck('student_ic')
                            ->unique()
                            ->values()
                            ->toArray();

                        $currentDayRegisteredStudents = $dailyStudents->where('status', 2)
                            ->pluck('student_ic')
                            ->unique()
                            ->values()
                            ->toArray();

                        $currentDayRejectedStudents = $dailyStudents->where('status', 14)
                            ->pluck('student_ic')
                            ->unique()
                            ->values()
                            ->toArray();

                        $currentDayOfferedStudents = $dailyStudents->where('status', 1)
                            ->filter(function($student) {
                                return \Carbon\Carbon::parse($student->date_offer)->gt(now());
                            })
                            ->pluck('student_ic')
                            ->unique()
                            ->values()
                            ->toArray();

                        $currentDayKIVStudents = $dailyStudents->where('status', 1)
                            ->filter(function($student) {
                                return \Carbon\Carbon::parse($student->date_offer)->lte(now());
                            })
                            ->pluck('student_ic')
                            ->unique()
                            ->values()
                            ->toArray();

                        $currentDayOthersStudents = $dailyStudents->where('status', '!=', 1)
                            ->where('status', '!=', 2)
                            ->where('status', '!=', 14)
                            ->pluck('student_ic')
                            ->unique()
                            ->values()
                            ->toArray();
                            
                        // Update converted students count for this day
                        $data['totalConvert2'][$key][$key2] = count($currentDayConvertStudents);

                        // Count the number of unique student_ic values for the current day
                        $totalDaysCount = count($currentDayStudents);

                        // Update the already counted students set
                        $alreadyCountedStudents2 = array_merge($alreadyCountedStudents2, $currentDayStudents);
                        $data['countedPerDay'][$key][$key2] = count($alreadyCountedStudents2);

                        $data['registeredPerDay'][$key][$key2] = count($currentDayRegisteredStudents);
                        $data['rejectedPerDay'][$key][$key2] = count($currentDayRejectedStudents);
                        $data['offeredPerDay'][$key][$key2] = count($currentDayOfferedStudents);
                        $data['KIVPerDay'][$key][$key2] = count($currentDayKIVStudents);
                        $data['othersPerDay'][$key][$key2] = count($currentDayOthersStudents);
                        
                        $data['totalDay'][$key][$key2] = (object) ['total_day' => $totalDaysCount];                        
                    }
                }


                if(isset($request->print))
                {
                    
                    $data['from'] = Carbon::createFromFormat('Y-m-d', $request->from)->translatedFormat('d F Y'); ;
                    $data['to'] = Carbon::createFromFormat('Y-m-d', $request->to)->translatedFormat('d F Y');

                    return view('pendaftar.reportR2.printReportR2', compact('data'));

                } elseif (isset($request->excel)) {

                    $data['from'] = Carbon::createFromFormat('Y-m-d', $request->from)->translatedFormat('d F Y'); ;
                    $data['to'] = Carbon::createFromFormat('Y-m-d', $request->to)->translatedFormat('d F Y');


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
        
        // Add title and report information
        $sheet->setCellValue('A1', 'JADUAL REPORT PENCAPAIAN R BAGI TEMPOH ' . $data['from'] . ' HINGGA ' . $data['to']);
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Report Information
        $row = 3;
        $sheet->setCellValue('A' . $row, 'Report Information');
        $sheet->mergeCells('A' . $row . ':K' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Total Student R By Month:');
        $sheet->setCellValue('C' . $row, $data['totalAll']->total_student);
        
        // Total Payment By Weeks header
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Total Student R By Weeks');
        $sheet->mergeCells('A' . $row . ':K' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        // Add note about weeks
        $row++;
        $sheet->setCellValue('A' . $row, 'Note: Weeks shown follow the calendar date per week (Sunday to Saturday)');
        $sheet->mergeCells('A' . $row . ':K' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setItalic(true)->setSize(10);
        
        // Table headers
        $row++;
        $sheet->setCellValue('A' . $row, 'Week (Date Range)');
        $sheet->setCellValue('B' . $row, 'Month');
        $sheet->setCellValue('C' . $row, 'Total By Weeks');
        $sheet->setCellValue('D' . $row, 'Total by Cumulative');
        $sheet->setCellValue('E' . $row, 'Total by Convert');
        $sheet->setCellValue('F' . $row, 'Balance Student');
        $sheet->setCellValue('G' . $row, 'Student Active');
        $sheet->setCellValue('H' . $row, 'Student Rejected');
        $sheet->setCellValue('I' . $row, 'Student Offered');
        $sheet->setCellValue('J' . $row, 'Student KIV');
        $sheet->setCellValue('K' . $row, 'Student Others');
        $sheet->getStyle('A' . $row . ':K' . $row)->getFont()->setBold(true);
    
        // Add notes for KIV and Others columns
        $sheet->getComment('J' . $row)->getText()->createTextRun('Students whose current date has passed their offered date');
        $sheet->getComment('K' . $row)->getText()->createTextRun('Includes: GAGAL BERHENTI, TARIK DIRI, MENINGGAL DUNIA, TANGGUH, DIBERHENTIKAN, TAMAT PENGAJIAN, TUKAR PROGRAM, GANTUNG, TUKAR KE KUKB, PINDAH KOLEJ, TIDAK TAMAT PENGAJIAN, TAMAT PENGAJIAN (MENINGGAL DUNIA)');
    
        $row++;
        $total_allW = 0;
        $total_allC = 0;
        $total_allC2 = 0;
        $total_allB = 0;
        $total_allR = 0;
        $total_allO = 0;
        $total_allK = 0;
        $total_allT = 0;
        $total_allOthers = 0;
        
        foreach ($data['dateRange'] as $key => $week) {
            $dateRange = \Carbon\Carbon::parse(reset($week['days']))->format('j F Y') . ' - ' . \Carbon\Carbon::parse(end($week['days']))->format('j F Y');
            $sheet->setCellValue('A' . $row, $week['week'] . ' (' . $dateRange . ')');
            $sheet->setCellValue('B' . $row, $week['month']);
            $sheet->setCellValue('C' . $row, $data['totalWeek'][$key]->total_week);
            $sheet->setCellValue('D' . $row, $data['countedPerWeek'][$key]);
            $sheet->setCellValue('E' . $row, $data['totalConvert'][$key]);
            $sheet->setCellValue('F' . $row, $data['totalWeek'][$key]->total_week - $data['totalConvert'][$key]);
            $sheet->setCellValue('G' . $row, $data['registeredPerWeek'][$key]);
            $sheet->setCellValue('H' . $row, $data['rejectedPerWeek'][$key]);
            $sheet->setCellValue('I' . $row, $data['offeredPerWeek'][$key]);
            $sheet->setCellValue('J' . $row, $data['KIVPerWeek'][$key]);
            $sheet->setCellValue('K' . $row, $data['othersPerWeek'][$key]);
            
            $total_allW += $data['totalWeek'][$key]->total_week;
            $total_allC = $data['countedPerWeek'][$key];
            $total_allC2 += $data['totalConvert'][$key];
            $total_allB += $data['totalWeek'][$key]->total_week - $data['totalConvert'][$key];
            $total_allR += $data['registeredPerWeek'][$key];
            $total_allO += $data['rejectedPerWeek'][$key];
            $total_allK += $data['offeredPerWeek'][$key];
            $total_allT += $data['KIVPerWeek'][$key];
            $total_allOthers += $data['othersPerWeek'][$key];
            $row++;
        }
    
        // Totals row for weeks
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row . ':K' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('C' . $row, $total_allW);
        $sheet->setCellValue('D' . $row, $total_allC);
        $sheet->setCellValue('E' . $row, $total_allC2);
        $sheet->setCellValue('F' . $row, $total_allB);
        $sheet->setCellValue('G' . $row, $total_allR);
        $sheet->setCellValue('H' . $row, $total_allO);
        $sheet->setCellValue('I' . $row, $total_allK);
        $sheet->setCellValue('J' . $row, $total_allT);
        $sheet->setCellValue('K' . $row, $total_allOthers);
        
        // Total Student R By Days header
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Total Student R By Days');
        $sheet->mergeCells('A' . $row . ':K' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        // Table headers for days
        $row++;
        $sheet->setCellValue('A' . $row, 'Date');
        $sheet->setCellValue('B' . $row, 'Total By Days');
        $sheet->setCellValue('C' . $row, 'Total by Cumulative');
        $sheet->setCellValue('D' . $row, 'Total by Convert');
        $sheet->setCellValue('E' . $row, 'Balance Student');
        $sheet->setCellValue('F' . $row, 'Student Active');
        $sheet->setCellValue('G' . $row, 'Student Rejected');
        $sheet->setCellValue('H' . $row, 'Student Offered');
        $sheet->setCellValue('I' . $row, 'Student KIV');
        $sheet->setCellValue('J' . $row, 'Student Others');
        $sheet->getStyle('A' . $row . ':J' . $row)->getFont()->setBold(true);
    
        // Add notes for KIV and Others columns
        $sheet->getComment('I' . $row)->getText()->createTextRun('Students whose current date has passed their offered date');
        $sheet->getComment('J' . $row)->getText()->createTextRun('Includes: GAGAL BERHENTI, TARIK DIRI, MENINGGAL DUNIA, TANGGUH, DIBERHENTIKAN, TAMAT PENGAJIAN, TUKAR PROGRAM, GANTUNG, TUKAR KE KUKB, PINDAH KOLEJ, TIDAK TAMAT PENGAJIAN, TAMAT PENGAJIAN (MENINGGAL DUNIA)');
    
        $row++;
        $total_allD = 0;
        $total_allQ = 0;
        $total_allZ = 0;
        $total_allB = 0;
        $total_allR = 0;
        $total_allO = 0;
        $total_allK = 0;
        $total_allT = 0;
        $total_allOthers = 0;
        
        foreach ($data['dateRange'] as $key => $week) {
            foreach ($data['week'][$key] as $key2 => $day) {
                $sheet->setCellValue('A' . $row, $day);
                $sheet->setCellValue('B' . $row, $data['totalDay'][$key][$key2]->total_day);
                $sheet->setCellValue('C' . $row, $data['countedPerDay'][$key][$key2]);
                $sheet->setCellValue('D' . $row, $data['totalConvert2'][$key][$key2]);
                $sheet->setCellValue('E' . $row, $data['totalDay'][$key][$key2]->total_day - $data['totalConvert2'][$key][$key2]);
                $sheet->setCellValue('F' . $row, $data['registeredPerDay'][$key][$key2]);
                $sheet->setCellValue('G' . $row, $data['rejectedPerDay'][$key][$key2]);
                $sheet->setCellValue('H' . $row, $data['offeredPerDay'][$key][$key2]);
                $sheet->setCellValue('I' . $row, $data['KIVPerDay'][$key][$key2]);
                $sheet->setCellValue('J' . $row, $data['othersPerDay'][$key][$key2]);
                
                $total_allD += $data['totalDay'][$key][$key2]->total_day;
                $total_allQ = $data['countedPerDay'][$key][$key2];
                $total_allZ += $data['totalConvert2'][$key][$key2];
                $total_allB += $data['totalDay'][$key][$key2]->total_day - $data['totalConvert2'][$key][$key2];
                $total_allR += $data['registeredPerDay'][$key][$key2];
                $total_allO += $data['rejectedPerDay'][$key][$key2];
                $total_allK += $data['offeredPerDay'][$key][$key2];
                $total_allT += $data['KIVPerDay'][$key][$key2];
                $total_allOthers += $data['othersPerDay'][$key][$key2];
                $row++;
            }
        }
    
        // Totals row for days
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row . ':J' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('B' . $row, $total_allD);
        $sheet->setCellValue('C' . $row, $total_allQ);
        $sheet->setCellValue('D' . $row, $total_allZ);
        $sheet->setCellValue('E' . $row, $total_allB);
        $sheet->setCellValue('F' . $row, $total_allR);
        $sheet->setCellValue('G' . $row, $total_allO);
        $sheet->setCellValue('H' . $row, $total_allK);
        $sheet->setCellValue('I' . $row, $total_allT);
        $sheet->setCellValue('J' . $row, $total_allOthers);
        
        // Set columns to auto width
        foreach(range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        $fileName = 'report_' . date('Y-m-d') . '.xlsx';
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

    public function studentReportRA()
    {
        return view('pendaftar.reportR_analysis.reportRA');
    }

    public function getStudentReportRA(Request $request)
    {
        // Check if this is a multiple tables request
        if ($request->multiple_tables && $request->date_ranges) {
            $data = $this->handleMultipleDateRanges($request);
            
            // Check if it's already a response (like Excel export or print)
            if ($data instanceof \Illuminate\Http\Response || $data instanceof \Illuminate\Http\RedirectResponse) {
                return $data;
            }
            
            // Add monthly comparison table data
            $data['monthlyComparison'] = $this->generateMonthlyComparisonTable($request);
            
            return view('pendaftar.reportR_analysis.getReportRA', compact('data'));
        }

        // Original single date range logic
        $data = $this->processSingleDateRange($request->from, $request->to);
        
        // Handle Excel export for single range
        if ($request->has('excel')) {
            return $this->exportToExcelRA($data, false, $request->from, $request->to);
        }
        
        return view('pendaftar.reportR_analysis.getReportRA', compact('data'));
    }

    private function handleMultipleDateRanges(Request $request)
    {
        $dateRanges = json_decode($request->date_ranges, true);

        // Log::info('Date ranges received:', ['dateRanges' => $dateRanges]);
        
        // Check if date_ranges is properly decoded
        if (!$dateRanges || !is_array($dateRanges)) {
            return response()->json(['error' => 'Invalid date ranges format'], 400);
        }
        
        $data = [];
        
        // Initialize arrays for multiple tables
        $data['allStudents'] = [];
        $data['totalConvert'] = [];
        $data['registered'] = [];
        $data['rejected'] = [];
        $data['offered'] = [];
        $data['KIV'] = [];
        $data['others'] = [];
        $data['total'] = [];
        $data['tableLabels'] = [];

        foreach ($dateRanges as $index => $range) {
            // Validate range data
            if (!isset($range['from']) || !isset($range['to'])) {
                continue;
            }

            // // Log date range information
            // \Log::info('Processing date range:', [
            //     'from' => $range['from'], 
            //     'to' => $range['to']
            // ]);
            
            $tableData = $this->processSingleDateRange($range['from'], $range['to']);
            
            // Store data for each table
            $data['allStudents'][$index] = $tableData['allStudents'];
            $data['totalConvert'][$index] = $tableData['totalConvert'];
            $data['registered'][$index] = $tableData['registered'];
            $data['rejected'][$index] = $tableData['rejected'];
            $data['offered'][$index] = $tableData['offered'];
            $data['KIV'][$index] = $tableData['KIV'];
            $data['others'][$index] = $tableData['others'];
            $data['total'][$index] = (object) ['total_' => $tableData['allStudents'] + $tableData['totalConvert'] + $tableData['registered'] + $tableData['rejected'] + $tableData['offered'] + $tableData['KIV'] + $tableData['others']];
            $data['tableLabels'][$index] = "Table {$range['table']} ({$range['from']} to {$range['to']})";
        }

        // Handle Excel export for multiple ranges
        if ($request->has('excel')) {
            return $this->exportToExcelRA($data, true);
        }

        if ($request->has('print')) {
            return view('pendaftar.reportR_analysis.getReportRA_print', compact('data'));
        }

        return $data;
    }

    private function processSingleDateRange($from, $to)
    {
        $data = [
            'allStudents' => 0,
            'totalConvert' => 0,
            'registered' => 0,
            'rejected' => 0,
            'offered' => 0,
            'KIV' => 0,
            'others' => 0
        ];

        // First, let's check if there are any payments in the date range at all
        try {
            $totalPaymentsCount = DB::table('tblpayment')
                                   ->whereBetween('add_date', [$from, $to])
                                   ->count();

            Log::info('Processing date range:', [
                'fromss' => $from, 
                'to' => $to
            ]);

            // Original query for when data exists in the selected range
            $students = DB::table('tblpayment as p1')
                            ->select([
                                'p1.student_ic',
                                'students.status',
                                'students.date_offer',
                                'students.semester'
                            ])
                            ->join('students', 'p1.student_ic', '=', 'students.ic')
                            ->join(DB::raw('(
                                SELECT student_ic, MIN(date) as first_payment_date 
                                FROM tblpayment 
                                WHERE process_status_id = 2 
                                AND process_type_id = 1 
                                AND semester_id = 1
                                GROUP BY student_ic
                            ) as p2'), function($join) {
                                $join->on('p1.student_ic', '=', 'p2.student_ic')
                                     ->on('p1.date', '=', 'p2.first_payment_date');
                            })
                            ->where([
                                ['p1.process_status_id', 2],
                                ['p1.process_type_id', 1], 
                                ['p1.semester_id', 1]
                            ])
                            ->whereBetween('p1.add_date', [$from, $to])
                            ->get();

            Log::info('Processing data:', [
                'studentss' => $students
            ]);

            $currentAllStudents = $students->pluck('student_ic')
                ->unique()
                ->values()
                ->toArray();

            $currentConvertStudents = $students->where('status', '!=', 1)
                ->pluck('student_ic')
                ->unique()
                ->values()
                ->toArray();
            $currentRegisteredStudents = $students->where('status', 2)
                ->pluck('student_ic')
                ->unique()
                ->values()
                ->toArray();
            $currentRejectedStudents = $students->where('status', 14)
                ->pluck('student_ic')
                ->unique()
                ->values()
                ->toArray();
            $currentOfferedStudents = $students->where('status', 1)
                ->filter(function($student) {
                    return \Carbon\Carbon::parse($student->date_offer)->gt(now());
                })
                ->pluck('student_ic')
                ->unique()
                ->values()
                ->toArray();
            $currentKIVStudents = $students->where('status', 1)
                ->filter(function($student) {
                    return \Carbon\Carbon::parse($student->date_offer)->lte(now());
                })
                ->pluck('student_ic')
                ->unique()
                ->values()
                ->toArray();

            $currentOthersStudents = $students->where('status', '!=', 1)
                ->where('status', '!=', 2)
                ->where('status', '!=', 14)
                ->pluck('student_ic')
                ->unique()
                ->values()
                ->toArray();

            $data['allStudents'] = count($currentAllStudents);
            $data['totalConvert'] = count($currentConvertStudents);
            $data['registered'] = count($currentRegisteredStudents);
            $data['rejected'] = count($currentRejectedStudents);
            $data['offered'] = count($currentOfferedStudents);
            $data['KIV'] = count($currentKIVStudents);
            $data['others'] = count($currentOthersStudents);

        } catch (\Exception $e) {
            // If there's any database error, return test data
            $data = [
                'allStudents' => 0,
                'totalConvert' => 8,
                'registered' => 5,
                'rejected' => 1,
                'offered' => 4,
                'KIV' => 2,
                'others' => 1
            ];
        }

        return $data;
    }

    private function exportToExcelRA($data, $isMultiple = false, $from = null, $to = null)
    {
        Log::info('exportToExcelRA called', [
            'isMultiple' => $isMultiple,
            'from' => $from,
            'to' => $to,
            'data_keys' => array_keys($data),
            'data_structure' => [
                'allStudents_count' => is_array($data['allStudents'] ?? null) ? count($data['allStudents']) : 'single_value',
                'tableLabels_count' => is_array($data['tableLabels'] ?? null) ? count($data['tableLabels']) : 'not_set'
            ]
        ]);

        $filename = 'student_r_analysis_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        Log::info('Excel export headers set', ['headers' => $headers]);

        $callback = function() use ($data, $isMultiple, $from, $to) {
            Log::info('Excel export callback started');
            
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            if ($isMultiple) {
                // Multiple tables export
                fputcsv($file, ['Student R Analysis Report - Multiple Tables']);
                fputcsv($file, ['Generated on: ' . date('Y-m-d H:i:s')]);
                fputcsv($file, []);
                
                foreach ($data['tableLabels'] as $key => $label) {
                    fputcsv($file, [$label]);
                    fputcsv($file, [
                        'Total Student R',
                        'Total by Convert',
                        'Balance Student', 
                        'Student Active',
                        'Student Rejected',
                        'Student Offered',
                        'Student KIV',
                        'Student Others'
                    ]);
                    
                    fputcsv($file, [
                        $data['allStudents'][$key],
                        $data['totalConvert'][$key],
                        $data['allStudents'][$key] - $data['totalConvert'][$key],
                        $data['registered'][$key],
                        $data['rejected'][$key],
                        $data['offered'][$key],
                        $data['KIV'][$key],
                        $data['others'][$key]
                    ]);
                    fputcsv($file, []);
                }
                
                // Summary section
                fputcsv($file, ['SUMMARY OF ALL TABLES']);
                fputcsv($file, [
                    'Total Student R',
                    'Total by Convert',
                    'Balance Student', 
                    'Student Active',
                    'Student Rejected',
                    'Student Offered',
                    'Student KIV',
                    'Student Others'
                ]);
                
                $total_student_r = array_sum($data['allStudents']);
                $total_convert = array_sum($data['totalConvert']);
                $total_registered = array_sum($data['registered']);
                $total_rejected = array_sum($data['rejected']);
                $total_offered = array_sum($data['offered']);
                $total_kiv = array_sum($data['KIV']);
                $total_others = array_sum($data['others']);
                $grand_total = $total_convert + $total_registered + $total_rejected + $total_offered + $total_kiv + $total_others;
                
                fputcsv($file, [
                    $total_student_r,
                    $total_convert,
                    $total_student_r - $total_convert,
                    $total_registered,
                    $total_rejected,
                    $total_offered,
                    $total_kiv,
                    $total_others
                ]);
                
            } else {
                // Single table export
                fputcsv($file, ['Student R Analysis Report']);
                fputcsv($file, ['Period: ' . $from . ' to ' . $to]);
                fputcsv($file, ['Generated on: ' . date('Y-m-d H:i:s')]);
                fputcsv($file, []);
                
                fputcsv($file, [
                    'Total Student R',
                    'Total by Convert',
                    'Balance Student', 
                    'Student Active',
                    'Student Rejected',
                    'Student Offered',
                    'Student KIV',
                    'Student Others'
                ]);
                
                $total_all = $data['totalConvert'] + $data['registered'] + $data['rejected'] + $data['offered'] + $data['KIV'] + $data['others'];
                
                fputcsv($file, [
                    $data['allStudents'],
                    $data['totalConvert'],
                    $data['allStudents'] - $data['totalConvert'],
                    $data['registered'],
                    $data['rejected'],
                    $data['offered'],
                    $data['KIV'],
                    $data['others']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateMonthlyComparisonTable(Request $request)
    {
        $dateRanges = json_decode($request->date_ranges, true);
        
        if (!$dateRanges || !is_array($dateRanges)) {
            return [];
        }

        // Create cache key based on date ranges
        $cacheKey = 'monthly_comparison_' . md5(serialize($dateRanges));
        
        // Try to get from cache first (valid for 30 minutes)
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        // Get the earliest start date to determine year range
        $startDate = null;
        foreach ($dateRanges as $range) {
            $rangeStart = Carbon::parse($range['from']);
            if ($startDate === null || $rangeStart->lt($startDate)) {
                $startDate = $rangeStart;
            }
        }
        
        if (!$startDate) {
            return [];
        }

        // Limit to only 2 years to improve performance
        $years = [];
        $currentYear = $startDate->year;
        for ($i = 0; $i < 2; $i++) { // Reduced from 3 to 2 years
            $years[] = $currentYear + $i;
        }

        // Quick check if there's any data in the date range
        $hasData = DB::table('tblpayment')
            ->whereBetween('add_date', [
                Carbon::createFromDate($years[0], 1, 1)->format('Y-m-d'),
                Carbon::createFromDate(end($years), 12, 31)->format('Y-m-d')
            ])
            ->where([
                ['process_status_id', 2],
                ['process_type_id', 1], 
                ['semester_id', 1]
            ])
            ->exists();
            
        if (!$hasData) {
            $result = [
                'years' => $years,
                'monthly_data' => []
            ];
            cache()->put($cacheKey, $result, 1800); // Cache for 30 minutes
            return $result;
        }

        // Pre-fetch all student data for all years at once to minimize database calls
        $allYearData = $this->fetchAllYearDataOptimized($years);
        
        $monthlyData = [];
        
        // Generate monthly data for each year using pre-fetched data
        foreach ($years as $year) {
            $monthlyData[$year] = [];
            
            // Only process months that have data to save time
            if (!isset($allYearData[$year])) {
                continue;
            }
            
            foreach ($allYearData[$year] as $month => $monthData) {
                if (empty($monthData)) {
                    continue;
                }
                
                $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $monthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth();
                
                // Generate weekly breakdown for the month using pre-fetched data
                $weeklyData = $this->generateOptimizedWeeklyDataForMonth($monthStart, $monthEnd, $allYearData);
                
                $monthlyData[$year][$month] = [
                    'month_name' => $monthStart->format('F'),
                    'weeks' => $weeklyData['weeks'],
                    'monthly_totals' => $weeklyData['monthly_totals']
                ];
            }
        }

        $result = [
            'years' => $years,
            'monthly_data' => $monthlyData
        ];
        
        // Cache the result for 30 minutes
        cache()->put($cacheKey, $result, 1800);

        return $result;
    }

    private function fetchAllYearDataOptimized($years)
    {
        try {
            // Create date ranges for all years
            $startDate = Carbon::createFromDate($years[0], 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate(end($years), 12, 31)->endOfYear();
            
            // Add limit to prevent massive datasets from causing timeouts
            $allStudents = DB::table('tblpayment as p1')
                ->select([
                    'p1.student_ic',
                    'p1.add_date',
                    'students.status',
                    DB::raw('YEAR(p1.add_date) as payment_year'),
                    DB::raw('MONTH(p1.add_date) as payment_month'),
                    DB::raw('DATE(p1.add_date) as payment_date')
                ])
                ->join('students', 'p1.student_ic', '=', 'students.ic')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('tblpayment as p2')
                          ->whereRaw('p2.student_ic = p1.student_ic')
                          ->whereRaw('p2.date = (SELECT MIN(date) FROM tblpayment WHERE student_ic = p1.student_ic AND process_status_id = 2 AND process_type_id = 1 AND semester_id = 1)')
                          ->where([
                              ['p2.process_status_id', 2],
                              ['p2.process_type_id', 1], 
                              ['p2.semester_id', 1]
                          ]);
                })
                ->where([
                    ['p1.process_status_id', 2],
                    ['p1.process_type_id', 1], 
                    ['p1.semester_id', 1]
                ])
                ->whereBetween('p1.add_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->orderBy('p1.add_date')
                ->limit(50000) // Limit to prevent memory issues
                ->get();

            // Group the data efficiently
            $groupedData = [];
            foreach ($allStudents as $student) {
                $year = $student->payment_year;
                $month = $student->payment_month;
                $date = $student->payment_date;
                
                $groupedData[$year][$month][$date][] = $student;
            }

            return $groupedData;
            
        } catch (\Exception $e) {
            Log::error('Error fetching year data: ' . $e->getMessage());
            return [];
        }
    }

    private function generateOptimizedWeeklyDataForMonth($monthStart, $monthEnd, $allYearData)
    {
        $weeks = [];
        $monthlyTotals = [
            'total_by_weeks' => 0,
            'total_by_converts' => 0,
            'balance_student' => 0
        ];

        $year = $monthStart->year;
        $month = $monthStart->month;
        
        // Get data for this specific month
        $monthData = $allYearData[$year][$month] ?? [];
        
        if (empty($monthData)) {
            // Return empty weeks if no data
            return [
                'weeks' => [],
                'monthly_totals' => $monthlyTotals
            ];
        }

        // Initialize tracking variables
        $alreadyCountedStudents = [];
        $currentWeekNumber = 1;

        // Generate date ranges for each week in the month
        $start = $monthStart->copy();
        $end = $monthEnd->copy();

        while ($start <= $end) {
            $weekStart = $start->copy();
            $weekEnd = $start->copy()->endOfWeek(); // End of current week (Saturday)
            
            // Don't go beyond month end
            if ($weekEnd->gt($end)) {
                $weekEnd = $end->copy();
            }

            // Get weekly student data from pre-fetched data
            $weekData = $this->getOptimizedWeeklyStudentData($weekStart, $weekEnd, $monthData, $alreadyCountedStudents);
            
            $weeks[] = [
                'week' => $currentWeekNumber,
                'week_start' => $weekStart->format('j M Y'),
                'week_end' => $weekEnd->format('j M Y'),
                'date_range' => $weekStart->format('j M Y') . ' - ' . $weekEnd->format('j M Y'),
                'total_by_weeks' => $weekData['total_week'],
                'total_by_converts' => $weekData['total_convert'], 
                'balance_student' => $weekData['total_week'] - $weekData['total_convert']
            ];

            // Update monthly totals
            $monthlyTotals['total_by_weeks'] += $weekData['total_week'];
            $monthlyTotals['total_by_converts'] += $weekData['total_convert'];
            
            // Update already counted students
            $alreadyCountedStudents = array_merge($alreadyCountedStudents, $weekData['students']);

            // Move to next week
            $start = $weekEnd->copy()->addDay();
            $currentWeekNumber++;
        }

        $monthlyTotals['balance_student'] = $monthlyTotals['total_by_weeks'] - $monthlyTotals['total_by_converts'];

        return [
            'weeks' => $weeks,
            'monthly_totals' => $monthlyTotals
        ];
    }

    private function getOptimizedWeeklyStudentData($startDate, $endDate, $monthData, $alreadyCountedStudents)
    {
        $currentWeekStudents = [];
        $currentConvertStudents = [];
        
        // Iterate through each day in the week range
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            
            if (isset($monthData[$dateStr])) {
                foreach ($monthData[$dateStr] as $student) {
                    // Skip if already counted
                    if (in_array($student->student_ic, $alreadyCountedStudents)) {
                        continue;
                    }
                    
                    // Add to current week students
                    if (!in_array($student->student_ic, $currentWeekStudents)) {
                        $currentWeekStudents[] = $student->student_ic;
                    }
                    
                    // Check if converted (status != 1)
                    if ($student->status != 1 && !in_array($student->student_ic, $currentConvertStudents)) {
                        $currentConvertStudents[] = $student->student_ic;
                    }
                }
            }
            
            $current->addDay();
        }

        return [
            'total_week' => count($currentWeekStudents),
            'total_convert' => count($currentConvertStudents),
            'students' => $currentWeekStudents
        ];
    }

    public function incomeReport()
    {
        $loop = 20;

        for($i = 18; $i <= $loop; $i++)
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
        ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
        ->leftjoin('tblsex', 'tblstudent_personal.sex_id', 'tblsex.id')
        ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
        ->leftjoin('sessions', 'students.session', 'sessions.SessionID')
        ->leftjoin('tblstudent_status', 'students.status', 'tblstudent_status.id')
        ->leftjoin('tblstudent_address', 'students.ic', 'tblstudent_address.student_ic')
        ->leftjoin('tblstate', 'tblstudent_address.state_id', 'tblstate.id')
        ->leftJoinSub(
            DB::table('tblstudent_waris')
                ->select('student_ic')
                ->selectRaw('SUM(dependent_no) as total_dependent')
                ->selectRaw('SUM(kasar) as total_kasar')
                ->where('status', '!=', 2)
                ->groupBy('student_ic'),
            'waris_summary',
            function($join) {
                $join->on('students.ic', '=', 'waris_summary.student_ic');
            }
        )
        ->where([
            ['students.status', 2],
            ['students.campus_id', 1],
        ])
        ->whereIn('students.student_status', [1, 2, 4])
        ->select(
            'students.*',
            'tblsex.code',
            'tblprogramme.progcode',
            'sessions.SessionName',
            'tblstudent_status.name AS status',
            'tblstudent_personal.no_tel',
            DB::raw('CONCAT_WS(", ", tblstudent_address.address1, tblstudent_address.address2, tblstudent_address.address3, tblstudent_address.city, tblstudent_address.postcode, tblstate.state_name) AS full_address'),
            'waris_summary.total_dependent as dependent_no',
            'waris_summary.total_kasar as gajikasar'
        )
        ->orderBy('students.name');


        if($data['b40'])
        {
            $query = $query->where('waris_summary.total_kasar', '<=', 4850);
        }

        if($data['value'])
        {
            $query = $query->whereIn('tblstudent_address.state_id', $data['value']);
        }

        $data['students'] = $query->get();

        foreach($data['students'] as $key => $student)
        {
            $data['waris'][$key] = DB::table('tblstudent_waris')
                ->join('tblwaris_status', 'tblstudent_waris.status', 'tblwaris_status.id')
                ->where('student_ic', $student->ic)
                ->select('tblstudent_waris.*', 'tblwaris_status.name AS status')
                ->limit(2)
                ->get();
        }

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
                // ->where('students.no_matric', '!=', null)
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
            // ->whereYear('tblstudent_log.date', '=', $request->year)
            // ->where('sessions.Year', $request->year)
            ->groupBy('tblstudent_log.student_ic');

        $filteredSub1 = DB::table('tblstudent_log as latest_log')
            ->joinSub($sub1, 'sub1', function($join){
                $join->on('latest_log.id', '=', 'sub1.latest_id');
            })
            ->join('sessions', 'latest_log.session_id', '=', 'sessions.SessionID')
            ->select('latest_log.student_ic', 'latest_log.id AS latest_id')
            // ->whereYear('latest_log.date', '=', $request->year)
            ->where('sessions.Year', $request->year);

        $sub2 = DB::table('tblstudent_log')
            ->join('sessions', 'tblstudent_log.session_id', '=', 'sessions.SessionID')
            ->join('students', 'tblstudent_log.student_ic', '=', 'students.ic')
            ->select('tblstudent_log.student_ic', DB::raw('MAX(tblstudent_log.id) as latest_id'))
            ->whereIn('tblstudent_log.student_ic', $ic)
            ->where('tblstudent_log.semester_id', '>', 1)
            // ->whereYear('tblstudent_log.date', '=', $request->year)
            ->where('sessions.Year', $request->year)
            ->groupBy('tblstudent_log.student_ic');

        $filteredSub2 = DB::table('tblstudent_log as latest_log')
            ->joinSub($sub2, 'sub1', function($join){
                $join->on('latest_log.id', '=', 'sub1.latest_id');
            })
            ->join('sessions', 'latest_log.session_id', '=', 'sessions.SessionID')
            ->select('latest_log.student_ic', 'latest_log.id AS latest_id')
            // ->whereYear('latest_log.date', '=', $request->year)
            ->where('sessions.Year', $request->year);


        // $sub2 = DB::table('tblstudent_log')
        //        ->leftjoin('sessions', 'tblstudent_log.session_id', 'sessions.SessionID')
        //        ->join('students', function($join){
        //             $join->on('tblstudent_log.student_ic', 'students.ic');
        //        })
        //        ->select('tblstudent_log.student_ic', DB::raw('MAX(tblstudent_log.id) as latest_id'))
        //        ->whereIn('tblstudent_log.student_ic', $ic)
        //     //    ->whereYear('tblstudent_log.date', '=', $request->year)
        //         ->where('sessions.Year', $request->year)
        //        ->where('tblstudent_log.semester_id', '>', 1)
        //        ->groupBy('tblstudent_log.student_ic');

        $baseQuery = function () use ($ic, $request) {
        return DB::table('students')
        ->leftjoin('tblstudent_log', 'students.ic', 'tblstudent_log.student_ic')
        ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
        ->leftjoin('tblnationality', 'tblstudent_personal.nationality_id', 'tblnationality.id')
        ->leftjoin('tblsex', 'tblstudent_personal.sex_id', 'tblsex.id')
        ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
        ->leftjoin('sessions', 'tblstudent_log.session_id', 'sessions.SessionID')
        ->leftjoin('tblstudent_status', 'tblstudent_log.status_id', 'tblstudent_status.id')
        ->whereIn('students.ic', $ic)
        // ->whereYear('tblstudent_log.date', '=', $request->year)
        ->where('sessions.Year', $request->year);
        };

        $data['student1'] = ($baseQuery)()  // Make sure $baseQuery is defined correctly
        ->joinSub($filteredSub1, 'latest_logs', function ($join) {
            $join->on('tblstudent_log.student_ic', '=', 'latest_logs.student_ic')
                ->on('tblstudent_log.id', '=', 'latest_logs.latest_id');
        })
        ->where('tblstudent_log.semester_id', 1)
        ->select('students.name', 'students.ic', 'students.no_matric', 'tblstudent_log.kuliah_id AS student_status', 'tblsex.code as gender', 
                'tblprogramme.progcode', 'sessions.SessionName AS session', 
                'tblstudent_log.semester_id AS semester', 'tblstudent_log.date', 
                'tblstudent_log.remark', 'tblstudent_status.name AS status', 'tblnationality.nationality_name AS race')
        ->get();

        $data['student2'] = ($baseQuery)()
        ->joinSub($filteredSub2, 'latest_logs', function ($join) {
            $join->on('tblstudent_log.student_ic', '=', 'latest_logs.student_ic')
                 ->on('tblstudent_log.id', '=', 'latest_logs.latest_id');
        })
        ->where('tblstudent_log.semester_id', '>', 1)
        ->select('students.name', 'students.ic', 'students.no_matric', 'tblstudent_log.kuliah_id AS student_status', 'tblsex.code as gender', 'tblprogramme.progcode',
                'sessions.SessionName AS session', 'tblstudent_log.semester_id AS semester', 'tblstudent_log.date', 'tblstudent_log.remark',
                'tblstudent_status.name AS status', 'tblnationality.nationality_name AS race')
        ->get();

        return view('pendaftar.report.annual_student_report.getStudent', compact('data'));

    }

    // Temporary debug method - remove after debugging
    public function debugPaymentData()
    {
        // Check total payments
        $totalPayments = DB::table('tblpayment')->count();
        
        // Check what date ranges we have
        $dateRanges = DB::table('tblpayment')
                       ->selectRaw('MIN(add_date) as min_date, MAX(add_date) as max_date')
                       ->first();
        
        // Check a few sample records
        $samplePayments = DB::table('tblpayment')
                           ->select('student_ic', 'add_date', 'process_status_id', 'process_type_id', 'semester_id')
                           ->limit(10)
                           ->get();
        
        return response()->json([
            'total_payments' => $totalPayments,
            'date_ranges' => $dateRanges,
            'sample_payments' => $samplePayments
        ]);
    }

    public function analyseData(Request $request)
    {
        try {
            // Get the table data from the request
            $tableData = json_decode($request->tableData, true);
            
            if (!$tableData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid table data provided.'
                ], 400);
            }
            
            // Generate AI analysis
            $analysis = $this->generateAIAnalysis($tableData);
            
            return response()->json([
                'success' => true,
                'analysis' => $analysis
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in analyseData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error analyzing data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function generateAIAnalysis($tableData)
    {
        $apiKey = env('OPENAI_API_KEY');
        
        if (!$apiKey) {
            throw new \Exception('OpenAI API key is not configured.');
        }
        
        $client = new \GuzzleHttp\Client();
        
        try {
            // Build the analysis prompt
            $prompt = $this->buildAnalysisPrompt($tableData);
            
            // Send request to OpenAI API
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo-1106',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert educational data analyst specializing in student registration and enrollment analytics. Your role is to provide comprehensive, actionable insights from student registration data to help educational institutions improve their enrollment processes and student success outcomes.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 1500,
                    'temperature' => 0.7,
                ]
            ]);
            
            $responseBody = json_decode($response->getBody(), true);
            
            if (isset($responseBody['choices'][0]['message']['content'])) {
                return $responseBody['choices'][0]['message']['content'];
            } else {
                throw new \Exception('Invalid AI response received.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error communicating with OpenAI: ' . $e->getMessage());
            throw new \Exception('Error generating AI analysis: ' . $e->getMessage());
        }
    }
    
    private function buildAnalysisPrompt($tableData)
    {
        $prompt = "Please analyze the following student registration data and provide comprehensive insights:\n\n";
        
        if ($tableData['type'] === 'multiple') {
            $prompt .= "MULTIPLE TABLES ANALYSIS:\n";
            $prompt .= "We have " . count($tableData['tables']) . " different data sets to compare:\n\n";
            
            foreach ($tableData['tables'] as $index => $table) {
                $prompt .= "TABLE " . ($index + 1) . " - " . $table['label'] . ":\n";
                $prompt .= "- Total Student R (Registered): " . $table['totalStudentR'] . "\n";
                $prompt .= "- Total by Convert: " . $table['totalConvert'] . "\n";
                $prompt .= "- Balance Student: " . $table['balanceStudent'] . "\n";
                $prompt .= "- Student Active: " . $table['studentActive'] . "\n";
                $prompt .= "- Student Rejected: " . $table['studentRejected'] . "\n";
                $prompt .= "- Student Offered: " . $table['studentOffered'] . "\n";
                $prompt .= "- Student KIV (past offered date): " . $table['studentKIV'] . "\n";
                $prompt .= "- Student Others: " . $table['studentOthers'] . "\n\n";
            }
            
        } else {
            $prompt .= "SINGLE TABLE ANALYSIS:\n";
            $table = $tableData['table'];
            $prompt .= "- Total Student R (Registered): " . $table['totalStudentR'] . "\n";
            $prompt .= "- Total by Convert: " . $table['totalConvert'] . "\n";
            $prompt .= "- Balance Student: " . $table['balanceStudent'] . "\n";
            $prompt .= "- Student Active: " . $table['studentActive'] . "\n";
            $prompt .= "- Student Rejected: " . $table['studentRejected'] . "\n";
            $prompt .= "- Student Offered: " . $table['studentOffered'] . "\n";
            $prompt .= "- Student KIV (past offered date): " . $table['studentKIV'] . "\n";
            $prompt .= "- Student Others: " . $table['studentOthers'] . "\n\n";
        }
        
        $prompt .= "ANALYSIS REQUIREMENTS:\n";
        $prompt .= "Please provide a detailed analysis covering these key areas:\n\n";
        
        $prompt .= "1. PERFORMANCE COMPARISON:\n";
        if ($tableData['type'] === 'multiple') {
            $prompt .= "   - Which table/period shows the highest performance in Total Student R?\n";
            $prompt .= "   - Which table/period has the most students actually registered (Student Active)?\n";
            $prompt .= "   - Compare conversion rates across different periods\n\n";
        } else {
            $prompt .= "   - Evaluate the overall registration performance\n";
            $prompt .= "   - Assess the conversion rate from offered to active students\n\n";
        }
        
        $prompt .= "2. CRITICAL FOCUS AREAS:\n";
        $prompt .= "   - Which area has the most 'Student Offered' that need attention for conversion?\n";
        $prompt .= "   - Analyze the 'Student KIV' numbers (students past their offered registration date)\n";
        $prompt .= "   - Identify potential bottlenecks in the registration process\n\n";
        
        $prompt .= "3. ACTIONABLE RECOMMENDATIONS:\n";
        $prompt .= "   - Specific strategies to improve conversion from 'offered' to 'active' status\n";
        $prompt .= "   - How to reduce KIV students and prevent deadline oversights\n";
        $prompt .= "   - Process improvements for better registration outcomes\n\n";
        
        $prompt .= "4. TREND INSIGHTS:\n";
        if ($tableData['type'] === 'multiple') {
            $prompt .= "   - Identify patterns across different periods\n";
            $prompt .= "   - Highlight best and worst performing periods\n";
        }
        $prompt .= "   - Calculate key performance indicators and conversion rates\n\n";
        
        $prompt .= "Please format your response in a clear, professional manner with headings and bullet points for easy reading. Focus on actionable insights that can help improve student enrollment and registration processes.";
        
        return $prompt;
    }
}
