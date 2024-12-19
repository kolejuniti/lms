<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\subject;
use App\Models\student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;
use Mail;
use Intervention\Image\Facades\Image;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FinanceController extends Controller
{
    public function dashboard()
    {

        return view('dashboard');
    }
    
    public function index()
    {

        $claim = DB::table('tblstudentclaim')->get();

        return view('finance', compact('claim'));

    }

    public function createClaim(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string'],
            'code' => ['required'],
            'group' => ['required']
        ]);

        if(isset($request->idS))
        {
            DB::table('tblstudentclaim')->where('id', $request->idS)->update([
                'name' => $data['name'],
                'code' => $data['code'],
                'groupid' => $data['group']
            ]);
        }else{
            DB::table('tblstudentclaim')->insert([
                'name' => $data['name'],
                'code' => $data['code'],
                'groupid' => $data['group']
            ]);
        }

        return back()->withErrors(['msg' => 'Claim successfully created!']);

    }

    public function updateClaim()
    {

        $claims = DB::table('tblstudentclaim')->where('id', request()->id)->first();

        return view('finance.updateClaim', compact('claims'));

    }

    public function deleteClaim()
    {

        DB::table('tblstudentclaim')->where('id', request()->id)->delete();

        return back();

    }

    public function claimPackage()
    {

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        $data['session'] = DB::table('sessions')->get();

        $data['semester'] = DB::table('semester')->get();

        $data['claim'] = DB::table('tblstudentclaim')->get();

        return view('finance.claimPackage', compact('data'));

    }

    public function getClaim(Request $request)
    {

        if($request->program != 0 && $request->session != 0 && $request->semester != 0)
        {

            $datas = DB::table('tblstudentclaimpackage')->where([
                ['tblstudentclaimpackage.program_id', $request->program],
                ['tblstudentclaimpackage.intake_id', $request->session],
                ['tblstudentclaimpackage.semester_id', $request->semester]
            ]);

        }elseif($request->program != 0 && $request->session != 0)
        {

            $datas = DB::table('tblstudentclaimpackage')->where([
                ['tblstudentclaimpackage.program_id', $request->program],
                ['tblstudentclaimpackage.intake_id', $request->session]
            ]);

        }elseif($request->program != 0 && $request->semester != 0)
        {

            $datas = DB::table('tblstudentclaimpackage')->where([
                ['tblstudentclaimpackage.program_id', $request->program],
                ['tblstudentclaimpackage.semester_id', $request->semester]
            ]);

        }elseif($request->session != 0 && $request->semester != 0)
        {

            $datas = DB::table('tblstudentclaimpackage')->where([
                ['tblstudentclaimpackage.intake_id', $request->session],
                ['tblstudentclaimpackage.semester_id', $request->semester]
            ]);

        }elseif($request->program != 0)
        {

            $datas = DB::table('tblstudentclaimpackage')->where([
                ['tblstudentclaimpackage.program_id', $request->program]
            ]);
              
        }elseif($request->session != 0)
        {

            $datas = DB::table('tblstudentclaimpackage')->where([
                ['tblstudentclaimpackage.intake_id', $request->session]
            ]);


        }elseif($request->semester != 0)
        {

            $datas = DB::table('tblstudentclaimpackage')->where([
                ['tblstudentclaimpackage.semester_id', $request->semester]
            ]);

        }

        $data = $datas->join('tblprogramme', 'tblstudentclaimpackage.program_id', 'tblprogramme.id')
                      ->join('sessions', 'tblstudentclaimpackage.intake_id', 'sessions.SessionID')
                      ->join('semester', 'tblstudentclaimpackage.semester_id', 'semester.id')
                      ->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')
                      ->select('tblstudentclaimpackage.*','tblprogramme.progname','sessions.SessionName','semester.semester_name','tblstudentclaim.name')
                      ->get();

        return view('finance.getClaim', compact('data'));
        
    }

    public function addClaim(Request $request)
    {

        if(!isset($request->idS))
        {
            $data = json_decode($request->addClaim);

            if($data->program != null && $data->intake != null && $data->semester != null && $data->claim != null && $data->price != null)
            {

                try{ 
                    DB::beginTransaction();
                    DB::connection()->enableQueryLog();

                    try{

                        DB::table('tblstudentclaimpackage')->insert([
                            'program_id' => $data->program,
                            'intake_id' => $data->intake,
                            'semester_id' => $data->semester,
                            'claim_id' => $data->claim,
                            'pricePerUnit' => $data->price
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

            }else{

                return ["message"=>"Please select all required field!"];

            }

            $datas = DB::table('tblstudentclaimpackage')->where([
                ['tblstudentclaimpackage.program_id', $data->program],
                ['tblstudentclaimpackage.intake_id', $data->intake],
                ['tblstudentclaimpackage.semester_id', $data->semester]
            ])
            ->join('tblprogramme', 'tblstudentclaimpackage.program_id', 'tblprogramme.id')
            ->join('sessions', 'tblstudentclaimpackage.intake_id', 'sessions.SessionID')
            ->join('semester', 'tblstudentclaimpackage.semester_id', 'semester.id')
            ->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')
            ->select('tblstudentclaimpackage.*','tblprogramme.progname','sessions.SessionName','semester.semester_name','tblstudentclaim.name')
            ->get();

            return response()->json(['message' => 'Success', 'data' => $datas]);

        }else{

            $data = json_decode($request->addClaim);

            DB::table('tblstudentclaimpackage')->where('id', $request->idS)
            ->update([
                'program_id' => $data->program,
                'intake_id' => $data->intake,
                'semester_id' => $data->semester,
                'claim_id' => $data->claim,
                'pricePerUnit' => $data->price
            ]);

            $datas = DB::table('tblstudentclaimpackage')->where([
                ['tblstudentclaimpackage.program_id', $data->program],
                ['tblstudentclaimpackage.intake_id', $data->intake],
                ['tblstudentclaimpackage.semester_id', $data->semester]
            ])
            ->join('tblprogramme', 'tblstudentclaimpackage.program_id', 'tblprogramme.id')
            ->join('sessions', 'tblstudentclaimpackage.intake_id', 'sessions.SessionID')
            ->join('semester', 'tblstudentclaimpackage.semester_id', 'semester.id')
            ->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')
            ->select('tblstudentclaimpackage.*','tblprogramme.progname','sessions.SessionName','semester.semester_name','tblstudentclaim.name')
            ->get();

            return response()->json(['message' => 'Success', 'data' => $datas]);

        }
    }

    public function copyClaim(Request $request)
    {

        $data = json_decode($request->copyClaim);

        if($data->program != null && $data->intake != null && $data->semester != null && $data->semester2 != null)
        {

            try{ 
                DB::beginTransaction();
                DB::connection()->enableQueryLog();

                try{

                    $old = DB::table('tblstudentclaimpackage')->where([
                        ['program_id', $data->program],
                        ['intake_id', $data->intake],
                        ['semester_id', $data->semester],
                    ])->get();

                    foreach($old as $dt)
                    {

                        DB::table('tblstudentclaimpackage')->insert([
                            'program_id' => $dt->program_id,
                            'intake_id' => $dt->intake_id,
                            'semester_id' => $data->semester2,
                            'claim_id' => $dt->claim_id,
                            'pricePerUnit' => $dt->pricePerUnit
                        ]);

                    }

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

        }else{

            return ["message"=>"Please select all required field!"];

        }

        $datas = DB::table('tblstudentclaimpackage')->where([
            ['tblstudentclaimpackage.program_id', $data->program],
            ['tblstudentclaimpackage.intake_id', $data->intake],
            ['tblstudentclaimpackage.semester_id', $data->semester]
        ])
        ->join('tblprogramme', 'tblstudentclaimpackage.program_id', 'tblprogramme.id')
        ->join('sessions', 'tblstudentclaimpackage.intake_id', 'sessions.SessionID')
        ->join('semester', 'tblstudentclaimpackage.semester_id', 'semester.id')
        ->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')
        ->select('tblstudentclaimpackage.*','tblprogramme.progname','sessions.SessionName','semester.semester_name','tblstudentclaim.name')
        ->get();

        return response()->json(['message' => 'Success', 'data' => $datas]);

    }

    public function updatePackage()
    {

        $data['package'] = DB::table('tblstudentclaimpackage')->where('id', request()->id)->first();

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        $data['session'] = DB::table('sessions')->get();

        $data['semester'] = DB::table('semester')->get();

        $data['claim'] = DB::table('tblstudentclaim')->get();

        return view('finance.updatePackage', compact('data'));

    }

    public function deletePackage(Request $request)
    {

        $claim =  DB::table('tblstudentclaimpackage')->where('id', request()->id)->first();

        DB::table('tblstudentclaimpackage')->where('id', request()->id)->delete();

        $datas = DB::table('tblstudentclaimpackage')->where([
            ['tblstudentclaimpackage.program_id', $claim->program_id],
            ['tblstudentclaimpackage.intake_id', $claim->intake_id],
            ['tblstudentclaimpackage.semester_id', $claim->semester_id]
        ])
        ->join('tblprogramme', 'tblstudentclaimpackage.program_id', 'tblprogramme.id')
        ->join('sessions', 'tblstudentclaimpackage.intake_id', 'sessions.SessionID')
        ->join('semester', 'tblstudentclaimpackage.semester_id', 'semester.id')
        ->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')
        ->select('tblstudentclaimpackage.*','tblprogramme.progname','sessions.SessionName','semester.semester_name','tblstudentclaim.name')
        ->get();

        return response()->json(['message' => 'Success', 'data' => $datas]);

    }

    public function studentPayment()
    {

        return view('finance.payment.payment');

    }

    public function getStudentPayment(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->orderBy('name', 'asc')->get();

        return  view('finance.payment.paymentGetStudent', compact('data'));

    }

    public function storePayment(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->total != null)
                {
                    $stddetail = DB::table('students')->where('ic', $payment->ic)->first();

                    $id = DB::table('tblpayment')->insertGetId([
                        'student_ic' => $payment->ic,
                        'date' => date('Y-m-d'),
                        'ref_no' => null,
                        'program_id' => $stddetail->program,
                        'session_id' => $stddetail->session,
                        'semester_id' => $stddetail->semester,
                        'amount' => $payment->total,
                        'process_status_id' => 1,
                        'process_type_id' => $payment->type,
                        'add_staffID' => Auth::user()->ic,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => Auth::user()->ic,
                        'mod_date' => date('Y-m-d')
                    ]);

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $id];

    }

    public function storePaymentDtl(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                
                if($payment->method != null && $payment->amount != null)
                {
                    $total = $payment->amount;

                    $main = DB::table('tblpayment')->where('id', $payment->id)->first();

                    $details = DB::table('tblpaymentdtl')->where('payment_id', $payment->id)->get();

                    if(count($details) > 0)
                    {

                        $total = $total + DB::table('tblpaymentdtl')->where('payment_id', $payment->id)->sum('amount');
                        
                    }

                    if($total > $main->amount)
                    {

                        return ["message" => "Add cannot exceed initial payment value!"];

                    }else{

                        if(($payment->nodoc != null) ? DB::table('tblpaymentmethod')->join('tblpayment', 'tblpaymentmethod.payment_id', 'tblpayment.id')
                        ->where('tblpaymentmethod.no_document', $payment->nodoc)->whereNotIn('tblpayment.process_status_id', [1, 3])->exists() : '')
                        {

                            return ["message" => "Document with the same number already used! Please use a different document no."];

                        }else{

                            DB::table('tblpaymentmethod')->insertGetId([
                                'payment_id' => $payment->id,
                                'claim_method_id' => $payment->method,
                                'bank_id' => $payment->bank,
                                'no_document' => $payment->nodoc,
                                'amount' => $payment->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                            DB::table('tblpaymentdtl')->insert([
                                'payment_id' => $payment->id,
                                'claim_type_id' => $payment->type,
                                'amount' => $payment->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                    $details = DB::table('tblpaymentdtl')
                               ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                               ->where('tblpaymentdtl.payment_id', $payment->id)
                               ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS claim_type_id')->get();

                    $sum = DB::table('tblpaymentdtl')->where('payment_id', $payment->id)->sum('amount');

                    $methods = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->get();

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th style="width: 10%">
                                            Date
                                        </th>
                                        <th style="width: 15%">
                                            Type
                                        </th>
                                        <th style="width: 10%">
                                            Amount
                                        </th>
                                        <th style="width: 20%">
                                            Remark
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($details as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td style="width: 15%">
                            '. $main->date .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->claim_type_id .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->amount .'
                            </td>
                            <td>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $methods[$key]->id .','. $main->id .')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>
                            </td>
                        </tr>
                        ';
                        }
                    $content .= '</tbody>';
                    $content .= '<tfoot>
                        <tr>
                            <td style="width: 1%">
                            
                            </td>
                            <td style="width: 15%">
                            TOTAL AMOUNT
                            </td>
                            <td style="width: 15%">
                            :
                            </td>
                            <td style="width: 30%">
                            '. $sum .'
                            </td>
                            <td>
                        
                            </td>
                        </tr>
                    </tfoot>';

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $content];

    }

    public function deletePayment(Request $request)
    {

        DB::table('tblpaymentdtl')->where('id', $request->dtl)->delete();

        DB::table('tblpaymentmethod')->where('id', $request->meth)->delete();

        $main = DB::table('tblpayment')->where('id', $request->id)->first();

        $details = DB::table('tblpaymentdtl')
                               ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                               ->where('tblpaymentdtl.payment_id', $request->id)
                               ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS claim_type_id')->get();

        $sum = DB::table('tblpaymentdtl')->where('payment_id', $request->id)->sum('amount');

        $methods = DB::table('tblpaymentmethod')->where('payment_id', $request->id)->get();

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 10%">
                                Date
                            </th>
                            <th style="width: 15%">
                                Type
                            </th>
                            <th style="width: 10%">
                                Amount
                            </th>
                            <th style="width: 20%">
                                Remark
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($details as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $main->date .'
                </td>
                <td style="width: 15%">
                '. $dtl->claim_type_id .'
                </td>
                <td style="width: 30%">
                '. $dtl->amount .'
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $methods[$key]->id .', '. $dtl->payment_id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
                <tr>
                    <td style="width: 1%">
                    
                    </td>
                    <td style="width: 15%">
                    TOTAL AMOUNT
                    </td>
                    <td style="width: 15%">
                    :
                    </td>
                    <td style="width: 30%">
                    '. $sum .'
                    </td>
                    <td>
                
                    </td>
                </tr>
            </tfoot>';
        

        return $content;

    }

    public function confirmPayment(Request $request)
    {

        if(count(DB::table('tblpaymentdtl')->where('payment_id', $request->id)->get()) > 0)
        {
        
            $ref_no = DB::table('tblref_no')
                      ->join('tblpayment', 'tblref_no.process_type_id', 'tblpayment.process_type_id')
                      ->where('tblpayment.id', $request->id)
                      ->select('tblref_no.*','tblpayment.student_ic')->first();

            DB::table('tblref_no')->where('id', $ref_no->id)->update([
                'ref_no' => $ref_no->ref_no + 1
            ]);

            DB::table('tblpayment')->where('id', $request->id)->update([
                'process_status_id' => 2,
                'ref_no' => $ref_no->code . $ref_no->ref_no + 1
            ]);

            //check if newstudent & more than 250

            $student = DB::table('students')->where('ic', $ref_no->student_ic)->first();

            $alert = null;

            if($student->no_matric == null)
            {

                if(DB::table('tblpayment')->where('student_ic', $student->ic)->whereNotIn('process_status_id', [1, 3])->sum('amount') >= 250)
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

                    $alert = 'No Matric has been updated!';

                }

            }

        }else{

            return ['message' => 'Please add payment charge details first!'];

        }

        return ['message' => 'Success', 'id' => $request->id, 'alert' => $alert];

    }

    public function studentClaim()
    {

        return view('finance.payment.claim');

    }

    public function getStudentClaim(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['claim'] = DB::table('tblstudentclaimpackage')
                         ->where([
                            ['program_id', $data['student']->progid],
                            ['intake_id', $data['student']->intake]
                            ])->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')
                            ->distinct('tblstudentclaimpackage.claim_id')
                         ->select('tblstudentclaim.id', 'tblstudentclaim.name')->get();

        $data['balancePRE'] = DB::table('tblpayment')
                              ->join('tblpaymentdtl', 'tblpayment.id', 'tblpaymentdtl.payment_id')
                              ->where('tblpayment.student_ic', $request->student)
                              ->select(DB::raw('SUM(tblpayment.amount) AS payment'))
                              ->where('tblpaymentdtl.claim_type_id', 57)
                              ->groupBy('tblpaymentdtl.claim_type_id')
                              ->get();

        return  view('finance.payment.claimGetStudent', compact('data'));

    }

    public function registerClaim(Request $request)
    {

        $student = DB::table('students')->where('ic', $request->ic)->first();

        $claim = DB::table('tblstudentclaimpackage')
                         ->where([
                            ['program_id', $student->program],
                            ['intake_id', $student->intake],
                            ['semester_id', $student->semester]
                            ])->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')->get();

        if(count(DB::table('tblclaim')
           ->where([
            ['student_ic', $student->ic],
            ['session_id', $student->session],
            ['semester_id', $student->semester],
            ['program_id', $student->program],
            ['process_status_id', 2]
           ])->get()) > 0)
        {

            return ["message" => "Student already registered with this semester!"];
        
        }else{

            if($student->status == 1 || $student->status == 2 && $student->campus_id == 0)
            {
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

                $claimlist = DB::table('tblclaimdtl')->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')->where('claim_id', $id)
                            ->select('tblclaimdtl.*', 'tblstudentclaim.name')->get();

                $sum = DB::table('tblclaimdtl')->where('claim_id', $id)->sum('amount');

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th style="width: 1%">
                                        About
                                    </th>
                                    <th style="width: 10%">
                                        Price Per Unit
                                    </th>
                                    <th style="width: 15%">
                                        Unit
                                    </th>
                                    <th style="width: 10%">
                                        Amount
                                    </th>
                                    <th style="width: 20%">
                                        Remark
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">';
                            
                foreach($claimlist as $key => $dtl){
                //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                    <tr>
                        <td style="width: 1%">
                        '. $dtl->name .'
                        </td>
                        <td style="width: 15%">
                        '. $dtl->price .'
                        </td>
                        <td style="width: 15%">
                        '. $dtl->unit .'
                        </td>
                        <td style="width: 30%">
                        '. $dtl->amount .'
                        </td>
                        <td>
                            <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $dtl->claim_id .')">
                                <i class="ti-trash">
                                </i>
                                Delete
                            </a>
                        </td>
                    </tr>
                    ';
                    }
                $content .= '</tbody>';
                $content .= '<tfoot>
                <tr>
                    <td style="width: 1%">
                    
                    </td>
                    <td style="width: 15%">
                    TOTAL AMOUNT
                    </td>
                    <td style="width: 15%">
                    :
                    </td>
                    <td style="width: 30%">
                    '. $sum .'
                    </td>
                    <td>
                
                    </td>
                </tr>
                </tfoot>';

                return ["message" => "Success", "data" => $content, "id" => $id];

            }else{

                return ["message" => "Student must be on leave from campus and active to register!"];

            }

        }

    }


    public function addStudentClaim(Request $request)
    {

        $data = json_decode($request->claimDetail);

        $claim = DB::table('tblstudentclaimpackage')->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')
                 ->where('tblstudentclaim.id', $data->claim)->first();

        if($data->claim != null && $data->price != null && $data->unit != null)
        {

            if(count(DB::table('tblclaimdtl')->where('claim_id', $data->id)->where('claim_package_id', $data->claim)->get()) > 0)
            {

                return ["message" => "Claim type already exists!"];


            }else{

                DB::table('tblclaimdtl')->insert([
                    'claim_id' => $data->id,
                    'claim_package_id' => $data->claim,
                    'price' => $data->price,
                    'unit' => $data->unit,
                    'amount' => $data->price * $data->unit,
                    'add_staffID' => Auth::user()->ic,
                    'add_date' => date('Y-m-d'),
                    'mod_staffID' => Auth::user()->ic,
                    'mod_date' => date('Y-m-d')
                ]);

            }

                $claimlist = DB::table('tblclaimdtl')->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')->where('claim_id', $data->id)
                             ->select('tblclaimdtl.*', 'tblstudentclaim.name')->get();

                $sum = DB::table('tblclaimdtl')->where('claim_id', $data->id)->sum('amount');

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th style="width: 1%">
                                        About
                                    </th>
                                    <th style="width: 10%">
                                        Price Per Unit
                                    </th>
                                    <th style="width: 15%">
                                        Unit
                                    </th>
                                    <th style="width: 10%">
                                        Amount
                                    </th>
                                    <th style="width: 20%">
                                        Remark
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">';
                            
                foreach($claimlist as $key => $dtl){
                //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                    <tr>
                        <td style="width: 1%">
                        '. $dtl->name .'
                        </td>
                        <td style="width: 15%">
                        '. $dtl->price .'
                        </td>
                        <td style="width: 15%">
                        '. $dtl->unit .'
                        </td>
                        <td style="width: 30%">
                        '. $dtl->amount .'
                        </td>
                        <td>
                            <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $dtl->claim_id .')">
                                <i class="ti-trash">
                                </i>
                                Delete
                            </a>
                        </td>
                    </tr>
                    ';
                    }
                $content .= '</tbody>';
                $content .= '<tfoot>
                <tr>
                    <td style="width: 1%">
                    
                    </td>
                    <td style="width: 15%">
                    TOTAL AMOUNT
                    </td>
                    <td style="width: 15%">
                    :
                    </td>
                    <td style="width: 30%">
                    '. $sum .'
                    </td>
                    <td>
                
                    </td>
                </tr>
                </tfoot>';

                return ["message" => "Success", "data" => $content];

        }else{

            return ["message" => "Please fill all required details!"];

        }

    }

    public function deleteStudentClaim(Request $request)
    {

        DB::table('tblclaimdtl')->where('id', $request->dtl)->delete();

        $claimlist = DB::table('tblclaimdtl')->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')->where('claim_id', $request->id)
                        ->select('tblclaimdtl.*', 'tblstudentclaim.name')->get();

        $sum = DB::table('tblclaimdtl')->where('claim_id', $request->id)->sum('amount');

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                About
                            </th>
                            <th style="width: 10%">
                                Price Per Unit
                            </th>
                            <th style="width: 15%">
                                Unit
                            </th>
                            <th style="width: 10%">
                                Amount
                            </th>
                            <th style="width: 20%">
                                Remark
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($claimlist as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $dtl->name .'
                </td>
                <td style="width: 15%">
                '. $dtl->price .'
                </td>
                <td style="width: 15%">
                '. $dtl->unit .'
                </td>
                <td style="width: 30%">
                '. $dtl->amount .'
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $dtl->claim_id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
        <tr>
            <td style="width: 1%">
            
            </td>
            <td style="width: 15%">
            TOTAL AMOUNT
            </td>
            <td style="width: 15%">
            :
            </td>
            <td style="width: 30%">
            '. $sum .'
            </td>
            <td>
        
            </td>
        </tr>
        </tfoot>';

        return ["message" => "Success", "data" => $content];

    }

    public function confirmClaim(Request $request)
    {

        if(count(DB::table('tblclaimdtl')->where('claim_id', $request->id)->get()) > 0)
        {
            $ref_no = DB::table('tblref_no')
                      ->join('tblclaim', 'tblref_no.process_type_id', 'tblclaim.process_type_id')
                      ->where('tblclaim.id', $request->id)
                      ->select('tblref_no.*', 'tblclaim.student_ic')->first();

            DB::table('tblref_no')->where('id', $ref_no->id)->update([
                'ref_no' => $ref_no->ref_no + 1
            ]);

            DB::table('tblclaim')->where('id', $request->id)->update([
                'process_status_id' => 2,
                'ref_no' => $ref_no->code . $ref_no->ref_no + 1
            ]);

            $student = DB::table('students')->where('ic', $ref_no->student_ic)->first();

            DB::table('students')->where('ic', $student->ic)->update([
                'status' => 2,
                'campus_id' => 1
            ]);

            DB::table('tblstudent_log')->insert([
                'student_ic' => $student->ic,
                'session_id' => $student->session,
                'semester_id' => $student->semester,
                'status_id' => 2,
                'kuliah_id' => 1,
                'date' => date("Y-m-d H:i:s"),
                'remark' => null,
                'add_staffID' => Auth::user()->ic
            ]);

            $student_info = DB::table('tblstudent_personal')->where('student_ic', $ref_no->student_ic)->value('statelevel_id');

            if($student->semester != 1)
            {

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

            }else{

                if(DB::connection('mysql2')->table('students')->where('ic', $student->ic)->exists())
                {

                    DB::connection('mysql2')->table('students')->where('ic', $student->ic)->update([
                        'register_at' => now(),
                        'commission' => 300,
                        'status_id' => 20
                    ]);

                }

                DB::table('students')->where('ic', $student->ic)->update([
                    'date' => date('Y-m-d')
                ]);

                $alert = ['message' => 'Success'];

            }

        }else{

            return ['message' => 'Please add payment charge details first!'];

        }

        return $alert;

    }

    public function studentTuition()
    {

        return view('finance.payment.tuition');

    }

    public function getStudentTuition(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->orderBy('name', 'asc')->get();

        $data['tuition'] = DB::table('tblclaimdtl')
                            ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                            ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                            ->where('tblclaim.student_ic', $request->student)
                            ->where('tblclaim.program_id', $data['student']->progid)
                            ->where('tblclaim.process_status_id', 2)->where('tblclaim.process_type_id', '!=', 5)
                            ->select('tblclaimdtl.*', 'tblclaim.session_id', 'tblclaim.semester_id', 'tblstudentclaim.name')->get();

        foreach($data['tuition'] as $key => $tsy)
        {

            $data['amount'][] = $tsy->amount;

            $a = $tsy->amount;

            $balance = DB::table('tblpaymentdtl')
            ->join('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->where([
                ['tblpayment.semester_id', $tsy->semester_id],
                ['tblpayment.student_ic', $request->student],
                ['tblpaymentdtl.claim_type_id', $tsy->claim_package_id],
                ['tblpayment.program_id', $data['student']->progid],
                ['tblpayment.process_status_id', 2]
            ]);

            $data['payment'] = $balance->get();

            $b = $balance->sum('tblpaymentdtl.amount');

            if(count($data['payment']) > 0)
            {

                $data['balance'][] = $a - $b;

            }else{

                $data['balance'][] = $a;

            }

        }

        
        return view('finance.payment.tuitionGetStudent', compact('data'));

    }

    public function storeTuition(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->total != null)
                {
                    $stddetail = DB::table('students')->where('ic', $payment->ic)->first();

                    $id = DB::table('tblpayment')->insertGetId([
                        'student_ic' => $payment->ic,
                        'date' => date('Y-m-d'),
                        'ref_no' => null,
                        'program_id' => $stddetail->program,
                        'session_id' => $stddetail->session,
                        'semester_id' => $stddetail->semester,
                        'amount' => $payment->total,
                        'process_status_id' => 1,
                        'process_type_id' => 1,
                        'add_staffID' => Auth::user()->ic,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => Auth::user()->ic,
                        'mod_date' => date('Y-m-d')
                    ]);

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $id];
        
    }

    public function storeTuitionDtl(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                
                if($payment->method != null && $payment->amount != null)
                {
                    $total = $payment->amount;

                    $main = DB::table('tblpayment')->where('id', $payment->id)->first();

                    $details = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->get();

                    if(count($details) > 0)
                    {

                        $total = $total + DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->sum('amount');
                        
                    }

                    if($total > $main->amount)
                    {

                        return ["message" => "Add cannot exceed initial payment value!"];

                    }else{

                        if(($payment->nodoc != null) ? DB::table('tblpaymentmethod')->join('tblpayment', 'tblpaymentmethod.payment_id', 'tblpayment.id')
                        ->where('tblpaymentmethod.no_document', $payment->nodoc)->whereNotIn('tblpayment.process_status_id', [1, 3])->exists() : '')
                        {

                            return ["message" => "Document with the same number already used! Please use a different document no."];

                        }else{

                            DB::table('tblpaymentmethod')->insert([
                                'payment_id' => $payment->id,
                                'claim_method_id' => $payment->method,
                                'bank_id' => $payment->bank,
                                'no_document' => $payment->nodoc,
                                'amount' => $payment->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                    $methods = DB::table('tblpaymentmethod')
                               ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblpaymentmethod.payment_id', $payment->id)
                               ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->get();

                    $sum = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->sum('amount');

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th style="width: 10%">
                                            Date
                                        </th>
                                        <th style="width: 15%">
                                            Type
                                        </th>
                                        <th style="width: 10%">
                                            Amount
                                        </th>
                                        <th style="width: 10%">
                                            Document No.
                                        </th>
                                        <th style="width: 20%">
                                            Remark
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($methods as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td style="width: 15%">
                            '. $main->date .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->claim_method_id .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->amount .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->no_document .'
                            </td>
                            <td>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $main->id .')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>
                            </td>
                        </tr>
                        ';
                        }
                        $content .= '</tbody>';
                        $content .= '<tfoot>
                        <tr>
                            <td style="width: 1%">
                            
                            </td>
                            <td style="width: 15%">
                            TOTAL AMOUNT
                            </td>
                            <td style="width: 15%">
                            :
                            </td>
                            <td style="width: 30%">
                            '. $sum .'
                            </td>
                            <td>
                                <div class="col-md-6" hidden>
                                     <input type="number" class="form-control" name="sum" id="sum" value="'. $sum .'">
                                </div> 
                            </td>
                        </tr>
                        </tfoot>';

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $content];

    }

    public function confirmTuition(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                $paymentinput = json_decode($request->paymentinput);
                $paymentinput2 = json_decode($request->paymentinput2);
                
                if($paymentinput != null)
                {

                    foreach($paymentinput as $i => $phy)
                    {
                        $claimdtl = DB::table('tblclaimdtl')->where('id', $phy->id)->first();

                        if($paymentinput2[$i]->payment != null && $paymentinput2[$i]->payment != 0)
                        {

                            DB::table('tblpaymentdtl')->insert([
                                'payment_id' => $payment->id,
                                'claimDtl_id' => $phy->id,
                                'claim_type_id' => $claimdtl->claim_package_id,
                                'amount' => $paymentinput2[$i]->payment,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }
                    }

                    $ref_no = DB::table('tblref_no')
                      ->join('tblpayment', 'tblref_no.process_type_id', 'tblpayment.process_type_id')
                      ->where('tblpayment.id', $payment->id)
                      ->select('tblref_no.*', 'tblpayment.student_ic')->first();

                    DB::table('tblref_no')->where('id', $ref_no->id)->update([
                        'ref_no' => $ref_no->ref_no + 1
                    ]);

                    DB::table('tblpayment')->where('id', $payment->id)->update([
                        'process_status_id' => 2,
                        'ref_no' => $ref_no->code . $ref_no->ref_no + 1
                    ]);

                    //check if newstudent & more than 250

                    $student = DB::table('students')->where('ic', $ref_no->student_ic)->first();

                    $alert = null;

                    if($student->no_matric == null)
                    {

                        if($sum = DB::table('tblpayment')->where('student_ic', $student->ic)->whereNotIn('process_status_id', [1, 3])->sum('amount') >= 250)
                        {
                            $intake = DB::table('sessions')->where('SessionID', $student->intake)->first();
                            
                            $year = substr($intake->SessionName, 6, 2) . substr($intake->SessionName, 11, 2);

                            // $lastno = DB::table('tblmatric_no')->where('session', $year)->first();

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

                            $no_matric = $year . $newno;

                            DB::table('students')->where('ic', $student->ic)->update([
                                'no_matric' => $no_matric
                            ]);

                            DB::table('tblmatric_no')->where('session', $year)->update([
                                'final_no' => $newno
                            ]);

                            $alert = 'No Matric has been updated!';

                        }

                    }
                    
                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "id" => $payment->id, 'alert' => $alert];

    }

    public function deleteTuition(Request $request)
    {

        DB::table('tblpaymentmethod')->where('id', $request->dtl)->delete();

        $main = DB::table('tblpayment')->where('id', $request->id)->first();
        
        $methods = DB::table('tblpaymentmethod')
                               ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblpaymentmethod.payment_id', $request->id)
                               ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->get();

        $sum = DB::table('tblpaymentmethod')->where('payment_id', $request->id)->sum('amount');

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 10%">
                                Date
                            </th>
                            <th style="width: 15%">
                                Type
                            </th>
                            <th style="width: 10%">
                                Amount
                            </th>
                            <th style="width: 20%">
                                Remark
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($methods as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $main->date .'
                </td>
                <td style="width: 15%">
                '. $dtl->claim_method_id .'
                </td>
                <td style="width: 30%">
                '. $dtl->amount .'
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .', '. $dtl->payment_id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
                <tr>
                    <td style="width: 1%">
                    
                    </td>
                    <td style="width: 15%">
                    TOTAL AMOUNT
                    </td>
                    <td style="width: 15%">
                    :
                    </td>
                    <td style="width: 30%">
                    '. $sum .'
                    </td>
                    <td>
                
                    </td>
                </tr>
            </tfoot>';
        

        return $content;


    }

    public function studentIncentive()
    {

        return view('finance.payment.incentive');

    }

    public function getStudentIncentive(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['process'] = DB::table('tblprocess_type')
                           ->whereNotIn('id', [2, 3, 4, 5])
                           ->get();
      

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->orderBy('name', 'asc')->get();

        $data['tuition'] = DB::table('tblclaimdtl')
                            ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                            ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                            ->where('tblclaim.student_ic', $request->student)
                            ->where('tblclaim.program_id', $data['student']->progid)
                            ->where('tblclaim.process_status_id', 2)->where('tblclaim.process_type_id', '!=', 5)
                            ->select('tblclaimdtl.*', 'tblclaim.session_id', 'tblclaim.semester_id', 'tblstudentclaim.name')->get();

        foreach($data['tuition'] as $key => $tsy)
        {

            $data['amount'][] = $tsy->amount;

            $a = $tsy->amount;

            $balance = DB::table('tblpaymentdtl')
            ->join('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->where([
                ['tblpayment.semester_id', $tsy->semester_id],
                ['tblpayment.student_ic', $request->student],
                ['tblpaymentdtl.claim_type_id', $tsy->claim_package_id],
                ['tblpayment.program_id', $data['student']->progid],
                ['tblpayment.process_status_id', 2]
            ]);

            $data['payment'] = $balance->get();

            $b = $balance->sum('tblpaymentdtl.amount');

            if(count($data['payment']) > 0)
            {

                $data['balance'][] = $a - $b;

            }else{

                $data['balance'][] = $a;

            }

        }

        
        return view('finance.payment.incentiveGetStudent', compact('data'));

    }

    public function storeIncentive2(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->total != null && $payment->type != null)
                {
                    $stddetail = DB::table('students')->where('ic', $payment->ic)->first();

                    $id = DB::table('tblpayment')->insertGetId([
                        'student_ic' => $payment->ic,
                        'date' => date('Y-m-d'),
                        'ref_no' => null,
                        'program_id' => $stddetail->program,
                        'session_id' => $stddetail->session,
                        'semester_id' => $stddetail->semester,
                        'amount' => $payment->total,
                        'process_status_id' => 1,
                        'process_type_id' => $payment->type,
                        'add_staffID' => Auth::user()->ic,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => Auth::user()->ic,
                        'mod_date' => date('Y-m-d')
                    ]);

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $id];
        
    }

    public function storeIncentiveDtl(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                
                if($payment->method != null && $payment->amount != null)
                {
                    $total = $payment->amount;

                    $main = DB::table('tblpayment')->where('id', $payment->id)->first();

                    $details = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->get();

                    if(count($details) > 0)
                    {

                        $total = $total + DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->sum('amount');
                        
                    }

                    if($total > $main->amount)
                    {

                        return ["message" => "Add cannot exceed initial payment value!"];

                    }else{

                        if(($payment->nodoc != null) ? DB::table('tblpaymentmethod')->join('tblpayment', 'tblpaymentmethod.payment_id', 'tblpayment.id')
                        ->where('tblpaymentmethod.no_document', $payment->nodoc)->whereNotIn('tblpayment.process_status_id', [1, 3])->exists() : '')
                        {

                            return ["message" => "Document with the same number already used! Please use a different document no."];

                        }else{

                            DB::table('tblpaymentmethod')->insert([
                                'payment_id' => $payment->id,
                                'claim_method_id' => $payment->method,
                                'bank_id' => $payment->bank,
                                'no_document' => $payment->nodoc,
                                'amount' => $payment->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                    $methods = DB::table('tblpaymentmethod')
                               ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblpaymentmethod.payment_id', $payment->id)
                               ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->get();

                    $sum = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->sum('amount');

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th style="width: 10%">
                                            Date
                                        </th>
                                        <th style="width: 15%">
                                            Type
                                        </th>
                                        <th style="width: 10%">
                                            Amount
                                        </th>
                                        <th style="width: 10%">
                                            Document No.
                                        </th>
                                        <th style="width: 20%">
                                            Remark
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($methods as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td style="width: 15%">
                            '. $main->date .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->claim_method_id .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->amount .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->no_document .'
                            </td>
                            <td>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $main->id .')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>
                            </td>
                        </tr>
                        ';
                        }
                        $content .= '</tbody>';
                        $content .= '<tfoot>
                        <tr>
                            <td style="width: 1%">
                            
                            </td>
                            <td style="width: 15%">
                            TOTAL AMOUNT
                            </td>
                            <td style="width: 15%">
                            :
                            </td>
                            <td style="width: 30%">
                            '. $sum .'
                            </td>
                            <td>
                                <div class="col-md-6" hidden>
                                     <input type="number" class="form-control" name="sum" id="sum" value="'. $sum .'">
                                </div> 
                            </td>
                        </tr>
                        </tfoot>';

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $content];

    }

    public function confirmIncentive(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                $paymentinput = json_decode($request->paymentinput);
                $paymentinput2 = json_decode($request->paymentinput2);
                
                if($paymentinput != null)
                {

                    foreach($paymentinput as $i => $phy)
                    {
                        $claimdtl = DB::table('tblclaimdtl')->where('id', $phy->id)->first();

                        if($paymentinput2[$i]->payment != null && $paymentinput2[$i]->payment != 0)
                        {

                            DB::table('tblpaymentdtl')->insert([
                                'payment_id' => $payment->id,
                                'claimDtl_id' => $phy->id,
                                'claim_type_id' => $claimdtl->claim_package_id,
                                'amount' => $paymentinput2[$i]->payment,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }
                    }

                    $ref_no = DB::table('tblref_no')
                      ->where('tblref_no.process_type_id', 6)
                      ->select('tblref_no.*')->first();

                    DB::table('tblref_no')->where('id', $ref_no->id)->update([
                        'ref_no' => $ref_no->ref_no + 1
                    ]);

                    DB::table('tblpayment')->where('id', $payment->id)->update([
                        'process_status_id' => 2,
                        'ref_no' => $ref_no->code . $ref_no->ref_no + 1
                    ]);

                    //check if newstudent & more than 250

                    $student = DB::table('students')
                               ->join('tblpayment', 'students.ic', 'tblpayment.student_ic')
                               ->where('tblpayment.id', $payment->id)
                               ->select('students.*')
                               ->first();

                    $alert = null;

                    if($student->no_matric == null)
                    {

                        if($sum = DB::table('tblpayment')->where('student_ic', $student->ic)->whereNotIn('process_status_id', [1, 3])->sum('amount') >= 250)
                        {
                            $intake = DB::table('sessions')->where('SessionID', $student->intake)->first();
                            
                            $year = substr($intake->SessionName, 6, 2) . substr($intake->SessionName, 11, 2);

                            // $lastno = DB::table('tblmatric_no')->where('session', $year)->first();

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

                            $no_matric = $year . $newno;

                            DB::table('students')->where('ic', $student->ic)->update([
                                'no_matric' => $no_matric
                            ]);

                            DB::table('tblmatric_no')->where('session', $year)->update([
                                'final_no' => $newno
                            ]);

                            $alert = 'No Matric has been updated!';

                        }

                    }
                    
                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "id" => $payment->id, 'alert' => $alert];

    }

    public function deleteIncentive(Request $request)
    {

        DB::table('tblpaymentmethod')->where('id', $request->dtl)->delete();

        $main = DB::table('tblpayment')->where('id', $request->id)->first();
        
        $methods = DB::table('tblpaymentmethod')
                               ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblpaymentmethod.payment_id', $request->id)
                               ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->get();

        $sum = DB::table('tblpaymentmethod')->where('payment_id', $request->id)->sum('amount');

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 10%">
                                Date
                            </th>
                            <th style="width: 15%">
                                Type
                            </th>
                            <th style="width: 10%">
                                Amount
                            </th>
                            <th style="width: 20%">
                                Remark
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($methods as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $main->date .'
                </td>
                <td style="width: 15%">
                '. $dtl->claim_method_id .'
                </td>
                <td style="width: 30%">
                '. $dtl->amount .'
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .', '. $dtl->payment_id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
                <tr>
                    <td style="width: 1%">
                    
                    </td>
                    <td style="width: 15%">
                    TOTAL AMOUNT
                    </td>
                    <td style="width: 15%">
                    :
                    </td>
                    <td style="width: 30%">
                    '. $sum .'
                    </td>
                    <td>
                
                    </td>
                </tr>
            </tfoot>';
        

        return $content;


    }

    public function studentRefund()
    {

        return view('finance.payment.refund');

    }

    public function getStudentRefund(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->orderBy('name', 'asc')->get();

        $data['tuition'] = DB::table('tblclaimdtl')
                            ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                            ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                            ->where('tblclaim.student_ic', $request->student)
                            ->where('tblclaim.program_id', $data['student']->progid)
                            ->where('tblclaim.process_status_id', 2)->where('tblclaim.process_type_id', '!=', 5)
                            ->where('tblstudentclaim.groupid', 1)
                            ->select('tblclaimdtl.*', 'tblclaim.session_id', 'tblclaim.semester_id', 'tblstudentclaim.name')->get();

        foreach($data['tuition'] as $key => $tsy)
        {

            $data['amount'][] = $tsy->amount;

            $a = $tsy->amount;

            $balance = DB::table('tblpaymentdtl')
            ->join('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->where([
                ['tblpayment.semester_id', $tsy->semester_id],
                ['tblpayment.student_ic', $request->student],
                ['tblpaymentdtl.claim_type_id', $tsy->claim_package_id],
                ['tblpayment.program_id', $data['student']->progid],
                ['tblpayment.process_status_id', 2]
            ]);

            $data['payment'] = $balance->get();

            $b = $balance->sum('tblpaymentdtl.amount');

            if(count($data['payment']) > 0)
            {

                $data['balance'][] = $a - $b;

            }else{

                $data['balance'][] = 1;

            }

        }

        
        return view('finance.payment.refundGetStudent', compact('data'));

    }

    public function storeRefund(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->total != null)
                {
                    $stddetail = DB::table('students')->where('ic', $payment->ic)->first();

                    $id = DB::table('tblpayment')->insertGetId([
                        'student_ic' => $payment->ic,
                        'date' => date('Y-m-d'),
                        'ref_no' => null,
                        'program_id' => $stddetail->program,
                        'session_id' => $stddetail->session,
                        'semester_id' => $stddetail->semester,
                        'amount' => $payment->total,
                        'process_status_id' => 1,
                        'process_type_id' => 6,
                        'add_staffID' => Auth::user()->ic,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => Auth::user()->ic,
                        'mod_date' => date('Y-m-d')
                    ]);

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $id];
        
    }

    public function storeRefundDtl(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                
                if($payment->method != null && $payment->amount != null)
                {
                    $total = $payment->amount;

                    $main = DB::table('tblpayment')->where('id', $payment->id)->first();

                    $details = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->get();

                    if(count($details) > 0)
                    {

                        $total = $total + DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->sum('amount');
                        
                    }

                    if($total > $main->amount)
                    {

                        return ["message" => "Add cannot exceed initial payment value!"];

                    }else{

                        if(($payment->nodoc != null) ? DB::table('tblpaymentmethod')->join('tblpayment', 'tblpaymentmethod.payment_id', 'tblpayment.id')
                        ->where('tblpaymentmethod.no_document', $payment->nodoc)->whereNotIn('tblpayment.process_status_id', [1, 3])->exists() : '')
                        {

                            return ["message" => "Document with the same number already used! Please use a different document no."];

                        }else{

                            DB::table('tblpaymentmethod')->insert([
                                'payment_id' => $payment->id,
                                'claim_method_id' => $payment->method,
                                'bank_id' => $payment->bank,
                                'no_document' => $payment->nodoc,
                                'amount' => $payment->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                    $methods = DB::table('tblpaymentmethod')
                               ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblpaymentmethod.payment_id', $payment->id)
                               ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->get();

                    $sum = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->sum('amount');

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th style="width: 10%">
                                            Date
                                        </th>
                                        <th style="width: 15%">
                                            Type
                                        </th>
                                        <th style="width: 10%">
                                            Amount
                                        </th>
                                        <th style="width: 10%">
                                            Document No.
                                        </th>
                                        <th style="width: 20%">
                                            Remark
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($methods as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td style="width: 15%">
                            '. $main->date .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->claim_method_id .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->amount .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->no_document .'
                            </td>
                            <td>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $main->id .')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>
                            </td>
                        </tr>
                        ';
                        }
                        $content .= '</tbody>';
                        $content .= '<tfoot>
                        <tr>
                            <td style="width: 1%">
                            
                            </td>
                            <td style="width: 15%">
                            TOTAL AMOUNT
                            </td>
                            <td style="width: 15%">
                            :
                            </td>
                            <td style="width: 30%">
                            '. $sum .'
                            </td>
                            <td>
                                <div class="col-md-6" hidden>
                                     <input type="number" class="form-control" name="sum" id="sum" value="'. $sum .'">
                                </div> 
                            </td>
                        </tr>
                        </tfoot>';

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $content];

    }

    public function confirmRefund(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                $paymentinput = json_decode($request->paymentinput);
                $paymentinput2 = json_decode($request->paymentinput2);
                
                if($paymentinput != null)
                {

                    foreach($paymentinput as $i => $phy)
                    {
                        $claimdtl = DB::table('tblclaimdtl')->where('id', $phy->id)->first();

                        if($paymentinput2[$i]->payment != null)
                        {

                            DB::table('tblpaymentdtl')->insert([
                                'payment_id' => $payment->id,
                                'claimDtl_id' => $phy->id,
                                'claim_type_id' => $claimdtl->claim_package_id,
                                'amount' => $paymentinput2[$i]->payment * -1,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }
                    }

                    $ref_no = DB::table('tblref_no')
                      ->join('tblpayment', 'tblref_no.process_type_id', 'tblpayment.process_type_id')
                      ->where('tblpayment.id', $payment->id)
                      ->select('tblref_no.*', 'tblpayment.student_ic')->first();

                    DB::table('tblref_no')->where('id', $ref_no->id)->update([
                        'ref_no' => $ref_no->ref_no + 1
                    ]);

                    DB::table('tblpayment')->where('id', $payment->id)->update([
                        'process_status_id' => 2,
                        'ref_no' => $ref_no->code . $ref_no->ref_no + 1
                    ]);

                    $alert = null;
                    
                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "id" => $payment->id, 'alert' => $alert];

    }

    public function deleteRefund(Request $request)
    {

        DB::table('tblpaymentmethod')->where('id', $request->dtl)->delete();

        $main = DB::table('tblpayment')->where('id', $request->id)->first();
        
        $methods = DB::table('tblpaymentmethod')
                               ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblpaymentmethod.payment_id', $request->id)
                               ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->get();

        $sum = DB::table('tblpaymentmethod')->where('payment_id', $request->id)->sum('amount');

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 10%">
                                Date
                            </th>
                            <th style="width: 15%">
                                Type
                            </th>
                            <th style="width: 10%">
                                Amount
                            </th>
                            <th style="width: 20%">
                                Remark
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($methods as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $main->date .'
                </td>
                <td style="width: 15%">
                '. $dtl->claim_method_id .'
                </td>
                <td style="width: 30%">
                '. $dtl->amount .'
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .', '. $dtl->payment_id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
                <tr>
                    <td style="width: 1%">
                    
                    </td>
                    <td style="width: 15%">
                    TOTAL AMOUNT
                    </td>
                    <td style="width: 15%">
                    :
                    </td>
                    <td style="width: 30%">
                    '. $sum .'
                    </td>
                    <td>
                
                    </td>
                </tr>
            </tfoot>';
        

        return $content;


    }

    public function getReceipt(Request $request)
    {
        $data['payment'] = DB::table('tblpayment')->where('tblpayment.id', $request->id)
                           ->join('sessions AS A2', 'tblpayment.session_id', 'A2.SessionID')
                           ->join('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
                           ->select('tblpayment.*', 'tblprogramme.progname AS program', 'A2.SessionName AS session')
                           ->first();

        $data['staff'] = DB::table('users')->where('ic', $data['payment']->add_staffID)->first();

        $detail = DB::table('tblpaymentdtl')
                          ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                          ->where('tblpaymentdtl.payment_id', $request->id)
                          ->where('tblpaymentdtl.amount', '!=', 0.00)
                          ->select('tblpaymentdtl.*', DB::raw('SUM(tblpaymentdtl.amount) AS total_amount'), 'tblstudentclaim.name', 'tblstudentclaim.groupid')
                          ->groupBy('tblpaymentdtl.claim_type_id');
                          
        $data['detail'] = $detail->get();

        $data['total'] = DB::table('tblpaymentdtl')
                        ->where('tblpaymentdtl.payment_id', $request->id)
                        ->sum('tblpaymentdtl.amount');


        $method = DB::table('tblpaymentmethod')
                          ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                          ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                          ->where('tblpaymentmethod.payment_id', $request->id)
                          ->select('tblpaymentmethod.*', 'tblpayment_method.name AS method', 'tblpayment_bank.name AS bank', DB::raw('SUM(tblpaymentmethod.amount) AS amount'))
                          ->groupBy('tblpaymentmethod.claim_method_id','tblpaymentmethod.bank_id','tblpaymentmethod.no_document');
                          
        $data['method'] = $method->get();

        $data['total2'] = $method->sum('tblpaymentmethod.amount');

        $data['student'] = DB::table('students')
                           ->join('sessions AS A1', 'students.intake', 'A1.SessionID')
                           ->join('sessions AS A2', 'students.session', 'A2.SessionID')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->select('students.*', 'tblprogramme.progname AS program', 'tblstudent_status.name AS status', 'A1.SessionName AS intake', 'A2.SessionName AS session')
                           ->where('students.ic', $data['payment']->student_ic)
                           ->first();

        $data['date'] = Carbon::createFromFormat('Y-m-d', $data['payment']->date)->format('d/m/Y');

        return view('finance.payment.receipt', compact('data'));

    }

    public function getReceipt2(Request $request)
    {
        $data['payment'] = DB::table('tblpayment')->where('tblpayment.id', $request->id)
                           ->join('sessions AS A2', 'tblpayment.session_id', 'A2.SessionID')
                           ->join('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
                           ->select('tblpayment.*', 'tblprogramme.progname AS program', 'A2.SessionName AS session')
                           ->first();

        $data['staff'] = DB::table('users')->where('ic', $data['payment']->add_staffID)->first();

        $data['detail'] = DB::table('tblpaymentdtl')
                          ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                          ->where('tblpaymentdtl.payment_id', $request->id)
                          ->select('tblpaymentdtl.*', DB::raw('SUM(tblpaymentdtl.amount) AS total_amount'), 'tblstudentclaim.name', 'tblstudentclaim.groupid')
                          ->groupBy('tblstudentclaim.name')->get();
                          
        $data['total'] = DB::table('tblpaymentdtl')
                         ->where('payment_id', $request->id)
                         ->sum('amount');

        $method = DB::table('tblpaymentmethod')
                          ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                          ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                          ->where('tblpaymentmethod.payment_id', $data['payment']->sponsor_id)
                          ->select('tblpaymentmethod.*', 'tblpayment_method.name AS method', 'tblpayment_bank.name AS bank');
                          
        $data['method'] = $method->get();

        $data['total2'] = $method->sum('tblpaymentmethod.amount');

        $data['student'] = DB::table('students')
                           ->join('sessions AS A1', 'students.intake', 'A1.SessionID')
                           ->join('sessions AS A2', 'students.session', 'A2.SessionID')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->select('students.*', 'tblprogramme.progname AS program', 'tblstudent_status.name AS status', 'A1.SessionName AS intake', 'A2.SessionName AS session')
                           ->where('students.ic', $data['payment']->student_ic)
                           ->first();

        $data['date'] = Carbon::createFromFormat('Y-m-d', $data['payment']->date)->format('d/m/Y');

        return view('finance.sponsorship.receipt', compact('data'));

    }

    public function getReceipt3(Request $request)
    {
        $data['payment'] = DB::table('tblclaim')->where('tblclaim.id', $request->id)
                           ->join('sessions AS A2', 'tblclaim.session_id', 'A2.SessionID')
                           ->join('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
                           ->select('tblclaim.*', 'tblprogramme.progname AS program', 'A2.SessionName AS session')
                           ->first();

        $data['staff'] = DB::table('users')->where('ic', $data['payment']->add_staffID)->first();

        $data['detail'] = DB::table('tblclaimdtl')
                          ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                          ->where('tblclaimdtl.claim_id', $request->id)
                          ->select('tblclaimdtl.*', DB::raw('SUM(tblclaimdtl.amount) AS total_amount'), 'tblstudentclaim.name', 'tblstudentclaim.groupid')
                          ->groupBy('tblstudentclaim.name')->get();

        $data['total'] = DB::table('tblclaimdtl')
                         ->where('claim_id', $request->id)
                         ->sum('amount');

        $data['student'] = DB::table('students')
                           ->join('sessions AS A1', 'students.intake', 'A1.SessionID')
                           ->join('sessions AS A2', 'students.session', 'A2.SessionID')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->select('students.*', 'tblprogramme.progname AS program', 'tblstudent_status.name AS status', 'A1.SessionName AS intake', 'A2.SessionName AS session')
                           ->where('students.ic', $data['payment']->student_ic)
                           ->first();

        $data['date'] = Carbon::createFromFormat('Y-m-d', $data['payment']->date)->format('d/m/Y');

        return view('finance.sponsorship.receipt', compact('data'))->with('invois', '1');

    }

    public function paymentAllowance()
    {
        $method = [];

        $payment = DB::table('tblallowance')
                   ->where([['tblallowance.student_ic', null],['tblallowance.process_status_id',2]])
                   ->select('tblallowance.*',)->get();

        foreach($payment as $key => $pym)
        {

            $method[$key] = DB::table('tblallowancemethod')->where('payment_id', $pym->id)->get();

        }

        return view('finance.payment.allowance.allowance', compact('payment','method'));

    }

    public function paymentAllowanceInput()
    {

        return view('finance.payment.allowance.allowanceInput');

    }

    public function deleteAllowance(Request $request)
    {

        DB::table('tblallowance')->where('id', $request->id)->delete();

        DB::table('tblallowancemethod')->where('payment_id', $request->id)->delete();

        return ["message" => "Success"];

    }

    public function paymentAllowanceStore(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->name != null && $payment->total != null)
                {


                    $id = DB::table('tblallowance')->insertGetId([
                        'name' => $payment->name,
                        'date' => date('Y-m-d'),
                        'amount' => $payment->total,
                        'process_status_id' => 1,
                        'process_type_id' => 7,
                        'add_staffID' => Auth::user()->ic,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => Auth::user()->ic,
                        'mod_date' => date('Y-m-d')
                    ]);

                    $data['method'] = DB::table('tblpayment_method')->get();

                    $data['bank'] = DB::table('tblpayment_bank')->orderBy('name', 'asc')->get();
 
                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return view('finance.sponsorship.getSponserMethod', compact('id', 'data'));

    }

    public function paymentAllowanceStore2(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->method != null && $payment->amount != null)
                {
                    $total = $payment->amount;

                    $main = DB::table('tblallowance')->where('id', $payment->id)->first();

                    $details = DB::table('tblallowancemethod')->where('payment_id', $payment->id)->get();

                    if(count($details) > 0)
                    {

                        $total = $total + DB::table('tblallowancemethod')->where('payment_id', $payment->id)->sum('amount');
                        
                    }

                    if($total > $main->amount)
                    {

                        return ["message" => "Add cannot exceed initial payment value!"];

                    }else{

                        if(($payment->nodoc != null) ? DB::table('tblallowancemethod')->join('tblallowance', 'tblallowancemethod.payment_id', 'tblallowance.id')
                        ->where('tblallowancemethod.no_document', $payment->nodoc)->whereNotIn('tblallowance.process_status_id', [1, 3])->exists() : '')
                        {

                            return ["message" => "Document with the same number already used! Please use a different document no."];

                        }else{

                            DB::table('tblallowancemethod')->insert([
                                'payment_id' => $payment->id,
                                'claim_method_id' => $payment->method,
                                'bank_id' => $payment->bank,
                                'no_document' => $payment->nodoc,
                                'amount' => $payment->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                    $methods = DB::table('tblallowancemethod')
                               ->join('tblpayment_method', 'tblallowancemethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblallowancemethod.payment_id', $payment->id)
                               ->select('tblallowancemethod.*', 'tblpayment_method.name AS claim_method_id')->get();

                    $sum = DB::table('tblallowancemethod')->where('payment_id', $payment->id)->sum('amount');

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th style="width: 10%">
                                            Date
                                        </th>
                                        <th style="width: 15%">
                                            Type
                                        </th>
                                        <th style="width: 10%">
                                            Amount
                                        </th>
                                        <th style="width: 10%">
                                            Document No.
                                        </th>
                                        <th style="width: 20%">
                                            Remark
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($methods as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td style="width: 15%">
                            '. $main->date .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->claim_method_id .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->amount .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->no_document .'
                            </td>
                            <td>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $main->id .')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>
                            </td>
                        </tr>
                        ';
                        }
                        $content .= '</tbody>';
                        $content .= '<tfoot>
                        <tr>
                            <td style="width: 1%">
                            
                            </td>
                            <td style="width: 15%">
                            TOTAL AMOUNT
                            </td>
                            <td style="width: 15%">
                            :
                            </td>
                            <td style="width: 30%">
                            '. $sum .'
                            </td>
                            <td>
                                <div class="col-md-6" hidden>
                                     <input type="number" class="form-control" name="sum" id="sum" value="'. $sum .'">
                                </div> 
                            </td>
                        </tr>
                        </tfoot>';

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $content];

    }

    public function paymentAllowanceDelete(Request $request)
    {
        DB::table('tblallowancemethod')->where('id', $request->dtl)->delete();

        $main = DB::table('tblallowance')->where('id', $request->id)->first();

        $sum = DB::table('tblallowancemethod')->where('payment_id', $request->id)->sum('amount');

        $methods = DB::table('tblallowancemethod')
                               ->join('tblpayment_method', 'tblallowancemethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblallowancemethod.payment_id', $request->id)
                               ->select('tblallowancemethod.*', 'tblpayment_method.name AS claim_method_id')->get();

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 10%">
                                Date
                            </th>
                            <th style="width: 15%">
                                Type
                            </th>
                            <th style="width: 10%">
                                Amount
                            </th>
                            <th style="width: 10%">
                                Document No.
                            </th>
                            <th style="width: 20%">
                                Remark
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($methods as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $main->date .'
                </td>
                <td style="width: 15%">
                '. $dtl->claim_method_id .'
                </td>
                <td style="width: 30%">
                '. $dtl->amount .'
                </td>
                <td style="width: 30%">
                '. $dtl->no_document .'
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .', '. $dtl->payment_id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
                <tr>
                    <td style="width: 1%">
                    
                    </td>
                    <td style="width: 15%">
                    TOTAL AMOUNT
                    </td>
                    <td style="width: 15%">
                    :
                    </td>
                    <td style="width: 30%">
                    '. $sum .'
                    </td>
                    <td>
                
                    </td>
                </tr>
            </tfoot>';
        

        return $content;

    }

    public function paymentAllowanceConfirm(Request $request)
    {

        DB::table('tblallowance')->where('id', $request->id)->update([
            'process_status_id' => 2
        ]);

        return true;

    }

    public function paymentStudentAllowance()
    {

        $allowance = DB::table('tblallowance')
                   ->where('tblallowance.id', request()->id)
                   ->first();

        return view('finance.payment.allowance.studentPayment', compact('allowance'));

    }

    public function getStudentAllowance(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();
        
        return view('finance.payment.allowance.allowanceGetStudent', compact('data'));

    }

    public function storeStudentAllowance(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->total != null)
                {
                    $stddetail = DB::table('students')->where('ic', $payment->ic)->first();

                    $pymdetails = DB::table('tblallowance')->where('id', $payment->id)->first();

                    if(count(DB::table('tblallowance')->where([['allowance_id', $payment->id],['process_status_id', 2]])->get()) > 0)
                    {
                        $sum = DB::table('tblallowance')->where([['allowance_id', $payment->id],['process_status_id', 2]])->sum('amount');

                        $balance = $pymdetails->amount - $sum;

                    }else{

                        $balance = $pymdetails->amount;

                    }

                    $count = DB::table('tblallowance')->where([['allowance_id', $payment->id],['process_status_id', 2],['student_ic', $payment->ic]])->get();

                    if(count($count) > 0)
                    {

                        return ["message" => "Student sponsorship has already been paid!"];

                    }else{

                        if($payment->total > $balance)
                        {

                            return ["message" => "Amount cannot exceed " . $balance . "!"];

                        }else{

                            $id = DB::table('tblallowance')->insertGetId([
                                'student_ic' => $payment->ic,
                                'allowance_id' => $payment->id,
                                'date' => date('Y-m-d'),
                                'ref_no' => null,
                                'program_id' => $stddetail->program,
                                'session_id' => $stddetail->session,
                                'semester_id' => $stddetail->semester,
                                'amount' => $payment->total,
                                'process_status_id' => 1,
                                'process_type_id' => 7,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $id, "sum" => $payment->total];

    }

    public function confirmStudentAllowance(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                
                if($payment != null)
                {

                    DB::table('tblallowance')->where('id', $payment->id)->update([
                        'process_status_id' => 2,
                        'ref_no' => null
                    ]);

                
                }else{
                    return ["message" => "Please fill all required field!", "id" => $payment->id];
                }
                
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

        return ["message" => "Success", "id" => $payment->id];

    }

    public function sponsorLibrary()
    {

        $sponsor = DB::table('tblsponsor_library')->get();

        return view('finance.sponsorship.library', compact('sponsor'));

    }

    public function createSponsor(Request $request)
    {

        $data = $request->validate([
            'name' => ['required','string'],
            'code' => ['required']
        ]);

        if(isset($request->idS))
        {
            DB::table('tblsponsor_library')->where('id', $request->idS)->update([
                'name' => $data['name'],
                'code' => $data['code']
            ]);
        }else{
            DB::table('tblsponsor_library')->insert([
                'name' => $data['name'],
                'code' => $data['code']
            ]);
        }

        return back()->withErrors(['msg' => 'Sponsor successfully created/updated!']);

    }

    public function updateSponsor(Request $request)
    {

        $sponsor = DB::table('tblsponsor_library')->where('id', $request->id)->first();

        return view('finance.sponsorship.updateSponsor', compact('sponsor'));

    }

    public function deleteSponsor(Request $request)
    {

        DB::table('tblsponsor_library')->where('id', $request->id)->delete();

        return true;

    }

    public function paymentSponsor()
    {

        $payment = DB::table('tblpayment')
                   ->join('tblsponsor_library', 'tblpayment.payment_sponsor_id', 'tblsponsor_library.id')
                   ->where([['tblpayment.student_ic', null],['tblpayment.process_status_id',2]])
                   ->select('tblpayment.*', 'tblsponsor_library.name', 'tblsponsor_library.code')->get();

        foreach($payment as $key => $pym)
        {

            $method[$key] = DB::table('tblpaymentmethod')->where('payment_id', $pym->id)->get();

        }

        return view('finance.sponsorship.payment', compact('payment','method'));

    }

    public function paymentSponsorInput()
    {

        $sponser = DB::table('tblsponsor_library')->get();

        return view('finance.sponsorship.paymentInput', compact('sponser'));

    }

    public function paymentSponsorStore(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->sponser != null && $payment->total != null)
                {


                    $id = DB::table('tblpayment')->insertGetId([
                        'payment_sponsor_id' => $payment->sponser,
                        'date' => date('Y-m-d'),
                        'amount' => $payment->total,
                        'process_status_id' => 1,
                        'process_type_id' => 7,
                        'add_staffID' => Auth::user()->ic,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => Auth::user()->ic,
                        'mod_date' => date('Y-m-d')
                    ]);

                    $data['method'] = DB::table('tblpayment_method')->get();

                    $data['bank'] = DB::table('tblpayment_bank')->orderBy('name', 'asc')->get();
 
                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return view('finance.sponsorship.getSponserMethod', compact('id', 'data'));

    }

    public function paymentSponsorStore2(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->method != null && $payment->amount != null)
                {
                    $total = $payment->amount;

                    $main = DB::table('tblpayment')->where('id', $payment->id)->first();

                    $details = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->get();

                    if(count($details) > 0)
                    {

                        $total = $total + DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->sum('amount');
                        
                    }

                    if($total > $main->amount)
                    {

                        return ["message" => "Add cannot exceed initial payment value!"];

                    }else{

                        if(($payment->nodoc != null) ? DB::table('tblpaymentmethod')->join('tblpayment', 'tblpaymentmethod.payment_id', 'tblpayment.id')
                        ->where('tblpaymentmethod.no_document', $payment->nodoc)->whereNotIn('tblpayment.process_status_id', [1, 3])->exists() : '')
                        {

                            return ["message" => "Document with the same number already used! Please use a different document no."];

                        }else{

                            DB::table('tblpaymentmethod')->insert([
                                'payment_id' => $payment->id,
                                'claim_method_id' => $payment->method,
                                'bank_id' => $payment->bank,
                                'no_document' => $payment->nodoc,
                                'amount' => $payment->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                    $methods = DB::table('tblpaymentmethod')
                               ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblpaymentmethod.payment_id', $payment->id)
                               ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->get();

                    $sum = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->sum('amount');

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th style="width: 10%">
                                            Date
                                        </th>
                                        <th style="width: 15%">
                                            Type
                                        </th>
                                        <th style="width: 10%">
                                            Amount
                                        </th>
                                        <th style="width: 10%">
                                            Document No.
                                        </th>
                                        <th style="width: 20%">
                                            Remark
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($methods as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td style="width: 15%">
                            '. $main->date .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->claim_method_id .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->amount .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->no_document .'
                            </td>
                            <td>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $main->id .')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>
                            </td>
                        </tr>
                        ';
                        }
                        $content .= '</tbody>';
                        $content .= '<tfoot>
                        <tr>
                            <td style="width: 1%">
                            
                            </td>
                            <td style="width: 15%">
                            TOTAL AMOUNT
                            </td>
                            <td style="width: 15%">
                            :
                            </td>
                            <td style="width: 30%">
                            '. $sum .'
                            </td>
                            <td>
                                <div class="col-md-6" hidden>
                                     <input type="number" class="form-control" name="sum" id="sum" value="'. $sum .'">
                                </div> 
                            </td>
                        </tr>
                        </tfoot>';

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $content];

    }

    public function paymentSponsorDelete(Request $request)
    {
        DB::table('tblpaymentmethod')->where('id', $request->dtl)->delete();

        $main = DB::table('tblpayment')->where('id', $request->id)->first();

        $sum = DB::table('tblpaymentmethod')->where('payment_id', $request->id)->sum('amount');

        $methods = DB::table('tblpaymentmethod')
                               ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                               ->where('tblpaymentmethod.payment_id', $request->id)
                               ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->get();

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 10%">
                                Date
                            </th>
                            <th style="width: 15%">
                                Type
                            </th>
                            <th style="width: 10%">
                                Amount
                            </th>
                            <th style="width: 20%">
                                Remark
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($methods as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $main->date .'
                </td>
                <td style="width: 15%">
                '. $dtl->claim_method_id .'
                </td>
                <td style="width: 30%">
                '. $dtl->amount .'
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .', '. $dtl->payment_id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
                <tr>
                    <td style="width: 1%">
                    
                    </td>
                    <td style="width: 15%">
                    TOTAL AMOUNT
                    </td>
                    <td style="width: 15%">
                    :
                    </td>
                    <td style="width: 30%">
                    '. $sum .'
                    </td>
                    <td>
                
                    </td>
                </tr>
            </tfoot>';
        

        return $content;

    }

    public function paymentSponsorConfirm(Request $request)
    {

        DB::table('tblpayment')->where('id', $request->id)->update([
            'process_status_id' => 2
        ]);

        return true;

    }

    public function paymentStudent()
    {

        $payment = DB::table('tblpayment')
                   ->join('tblsponsor_library', 'tblpayment.payment_sponsor_id', 'tblsponsor_library.id')
                   ->where('tblpayment.id', request()->id)
                   ->select('tblpayment.*', 'tblsponsor_library.name')->first();

        return view('finance.sponsorship.studentPayment', compact('payment'));

    }

    public function getStudentSponsor(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['tuition'] = DB::table('tblclaimdtl')
                            ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                            ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                            ->where('tblclaim.student_ic', $request->student)
                            ->where('tblclaim.program_id', $data['student']->progid)
                            ->where('tblclaim.process_status_id', 2)->where('tblclaim.process_type_id', '!=', 5)
                            ->select('tblclaimdtl.*', 'tblclaim.session_id', 'tblclaim.semester_id', 'tblstudentclaim.name')->get();

        foreach($data['tuition'] as $key => $tsy)
        {

            $data['amount'][] = $tsy->amount;

            $a = $tsy->amount;

            $balance = DB::table('tblpaymentdtl')
            ->join('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->where([
                ['tblpayment.semester_id', $tsy->semester_id],
                ['tblpayment.student_ic', $request->student],
                ['tblpaymentdtl.claim_type_id', $tsy->claim_package_id],
                ['tblpayment.program_id', $data['student']->progid],
                ['tblpayment.process_status_id', 2]
            ]);

            $data['payment'] = $balance->get();

            $b = $balance->sum('tblpaymentdtl.amount');

            if(count($data['payment']) > 0)
            {

                $data['balance'][] = $a - $b;

            }else{

                $data['balance'][] = $a;

            }

        }

        
        return view('finance.sponsorship.paymentGetStudent', compact('data'));

    }

    public function storeStudent(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->total != null)
                {
                    $stddetail = DB::table('students')->where('ic', $payment->ic)->first();

                    $pymdetails = DB::table('tblpayment')->where('id', $payment->id)->first();

                    if(count(DB::table('tblpayment')->where([['sponsor_id', $payment->id],['process_status_id', 2]])->get()) > 0)
                    {
                        $sum = DB::table('tblpayment')->where([['sponsor_id', $payment->id],['process_status_id', 2]])->sum('amount');

                        $balance = $pymdetails->amount - $sum;

                    }else{

                        $balance = $pymdetails->amount;

                    }

                    $count = DB::table('tblpayment')->where([['sponsor_id', $payment->id],['process_status_id', 2],['student_ic', $payment->ic]])->get();

                    if(count($count) > 0)
                    {

                        return ["message" => "Student sponsorship has already been paid!"];

                    }else{

                        if($payment->total > $balance)
                        {

                            return ["message" => "Amount cannot exceed " . $balance . "!"];

                        }else{

                            $id = DB::table('tblpayment')->insertGetId([
                                'student_ic' => $payment->ic,
                                'payment_sponsor_id' => $pymdetails->payment_sponsor_id,
                                'sponsor_id' => $payment->id,
                                'date' => date('Y-m-d'),
                                'ref_no' => null,
                                'program_id' => $stddetail->program,
                                'session_id' => $stddetail->session,
                                'semester_id' => $stddetail->semester,
                                'amount' => $payment->total,
                                'process_status_id' => 1,
                                'process_type_id' => 7,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $id, "sum" => $payment->total];

    }

    public function confirmStudent(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                $paymentinput = json_decode($request->paymentinput);
                $paymentinput2 = json_decode($request->paymentinput2);
                
                if($paymentinput != null)
                {

                    foreach($paymentinput as $i => $phy)
                    {
                        $claimdtl = DB::table('tblclaimdtl')->where('id', $phy->id)->first();

                        if($paymentinput2[$i]->payment != null)
                        {

                            DB::table('tblpaymentdtl')->insert([
                                'payment_id' => $payment->id,
                                'claimDtl_id' => $phy->id,
                                'claim_type_id' => $claimdtl->claim_package_id,
                                'amount' => $paymentinput2[$i]->payment,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }
                    }

                    /*$ref_no = DB::table('tblref_no')
                      ->join('tblpayment', 'tblref_no.process_type_id', 'tblpayment.process_type_id')
                      ->where('tblpayment.id', $payment->id)
                      ->select('tblref_no.*','tblpayment.sponsor_id')->first();

                    DB::table('tblref_no')->where('id', $ref_no->id)->update([
                        'ref_no' => $ref_no->ref_no + 1
                    ]);*/

                    // $totalall = DB::table('tblpayment')->where('id', $payment->id)->first();

                    // $balance =  $totalall->amount - $payment->sum2;

                    DB::table('tblpayment')->where('id', $payment->id)->update([
                        'process_status_id' => 2,
                        'ref_no' => null
                    ]);

                
                }else{
                    return ["message" => "Please fill all required field!", "id" => $payment->id];
                }
                
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

        return ["message" => "Success", "id" => $payment->id];

    }

    public function studentStatement()
    {

        return view('finance.report.statement');

    }

    public function statementGetStudent(Request $request)
    {
        $data['total'] = [];
        $data['total2'] = [];
        $data['total3'] = [];

        $data['student'] = DB::table('students')
                           ->leftjoin('tblstudent_address', 'students.ic', 'tblstudent_address.student_ic')
                           ->leftjoin('tblcountry', 'tblstudent_address.country_id', 'tblcountry.id') 
                           ->leftjoin('tblstate', 'tblstudent_address.state_id', 'tblstate.id')                               
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*','tblstudent_address.*' ,'tblcountry.name AS country','tblstate.state_name AS state', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name', )
                           ->where('ic', $request->student)->first();

        $record = DB::table('tblpaymentdtl')
        ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
        ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
        ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
        ->where([
            ['tblpayment.student_ic', $request->student],
            ['tblpayment.process_status_id', 2], 
            ['tblstudentclaim.groupid', 1], 
            ['tblpaymentdtl.amount', '!=', 0]
            ])
        ->select(DB::raw("'payment' as source"), 'tblprocess_type.name AS process', 'tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 
        'tblpaymentdtl.amount',
        'tblpayment.process_type_id', 'tblprogramme.progcode AS program', DB::raw('NULL as remark'));

        $data['record'] = DB::table('tblclaimdtl')
        ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
        ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
        ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
        ->where([
            ['tblclaim.student_ic', $request->student],
            ['tblclaim.process_status_id', 2],  
            ['tblstudentclaim.groupid', 1],
            ['tblclaimdtl.amount', '!=', 0]
            ])
        ->unionALL($record)
        ->select(DB::raw("'claim' as source"), 'tblprocess_type.name AS process', 'tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 
        'tblclaimdtl.amount',
        'tblclaim.process_type_id', 'tblprogramme.progcode AS program', 'tblclaim.remark')
        ->orderBy('date')
        ->get();

        $val = 0;
        $data['sum1'] = 0;
        $data['sum2'] = 0;

        foreach($data['record'] as $key => $req)
        {

            if(array_intersect([2,3,4,5,11], (array) $req->process_type_id) && $req->source == 'claim')
            {

                $data['total'][$key] = $val + $req->amount;

                $val = $val + $req->amount;

                $data['sum1'] += $req->amount;
                

            }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id) && $req->source == 'payment')
            {

                $data['total'][$key] = $val - $req->amount;

                $val = $val - $req->amount;

                $data['sum2'] += $req->amount;

            }

        }   

        $data['sum3'] = end($data['total']);

        $data['sponsor'] = DB::table('tblpackage_sponsorship')
                            ->join('tblpackage', 'tblpackage_sponsorship.package_id', 'tblpackage.id')
                            ->join('tblpayment_type', 'tblpackage_sponsorship.payment_type_id', 'tblpayment_type.id')
                            ->where('student_ic', $request->student)
                            ->select('tblpackage_sponsorship.*', 'tblpackage.name AS package', 'tblpayment_type.name AS type')
                            ->first();

        if($data['sponsor'] != null) {

            $data['package'] = DB::table('tblpayment_package')
                            ->join('tblpackage', 'tblpayment_package.package_id', 'tblpackage.id')
                            ->join('tblpayment_type', 'tblpayment_package.payment_type_id', 'tblpayment_type.id')
                            ->join('tblpayment_program', 'tblpayment_package.id', 'tblpayment_program.payment_package_id')
                            ->where([
                                ['tblpayment_package.package_id', $data['sponsor']->package_id],
                                ['tblpayment_package.payment_type_id', $data['sponsor']->payment_type_id],
                                ['tblpayment_program.intake_id', $data['student']->intake],
                                ['tblpayment_program.program_id',$data['student']->progid]
                            ])->select('tblpayment_package.*','tblpackage.name AS package', 'tblpayment_type.name AS type')->first();

            $semester_column = 'semester_' . $data['student']->semester; // e.g., this will be 'semester_2' if $user->semester is 2

            if (isset($data['package']->$semester_column)) {
                $data['value'] = $data['sum3'] - $data['package']->$semester_column;
                // Do something with $semester_value
            } else {
                $data['value'] = 0;
                // Handle case where the column is not set
            }

        }else{

            $data['package'] = null;

        }

        //GET SPONSOR

        $data['sponsorStudent'] = DB::table('tblpayment')
                                  ->join('tblsponsor_library', 'tblpayment.payment_sponsor_id', 'tblsponsor_library.id')
                                  ->where([
                                    ['tblpayment.process_type_id', 7],
                                    ['tblpayment.process_status_id', 2],
                                    ['tblpayment.student_ic', $request->student]
                                    ])
                                  ->whereIn('tblsponsor_library.id', [1,2,3])
                                  ->orderBy('tblpayment.id', 'DESC')
                                  ->select('tblsponsor_library.name')
                                  ->first();
                                
        //FINE

        $record2 = DB::table('tblpaymentdtl')
        ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
        ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
        ->where([
            ['tblpayment.student_ic', $request->student],
            ['tblpayment.process_status_id', 2],  
            ['tblstudentclaim.groupid', 4],
            ['tblpaymentdtl.amount', '!=', 0]
            ])
        ->select('tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progcode AS program');

        $data['record2'] = DB::table('tblclaimdtl')
        ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
        ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
        ->where([
            ['tblclaim.student_ic', $request->student],
            ['tblclaim.process_status_id', 2],  
            ['tblstudentclaim.groupid', 4],
            ['tblclaimdtl.amount', '!=', 0]
            ])        
        ->unionALL($record2)
        ->select('tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progcode AS program')
        ->orderBy('date')
        ->get();

        $val = 0;
        $data['sum1_2'] = 0;
        $data['sum2_2'] = 0;

        foreach($data['record2'] as $key => $req)
        {

            if(array_intersect([2,3,4,5,11], (array) $req->process_type_id))
            {

                $data['total2'][$key] = $val + $req->amount;

                $val = $val + $req->amount;

                $data['sum1_2'] += $req->amount;
                

            }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id))
            {

                $data['total2'][$key] = $val - $req->amount;

                $val = $val - $req->amount;

                $data['sum2_2'] += $req->amount;

            }

        }

        $data['sum3_2'] = end($data['total2']);

        //OTHER

        $record3 = DB::table('tblpaymentdtl')
        ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
        ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
        ->where([
            ['tblpayment.student_ic', $request->student],
            ['tblpayment.process_status_id', 2],  
            ['tblstudentclaim.groupid', 5],
            ['tblpaymentdtl.amount', '!=', 0]
            ])
        ->select('tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progcode AS program');

        $data['record3'] = DB::table('tblclaimdtl')
        ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
        ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
        ->where([
            ['tblclaim.student_ic', $request->student],
            ['tblclaim.process_status_id', 2],  
            ['tblstudentclaim.groupid', 5],
            ['tblclaimdtl.amount', '!=', 0]
            ])        
        ->unionALL($record3)
        ->select('tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progcode AS program')
        ->orderBy('date')
        ->get();

        $val = 0;
        $data['sum1_3'] = 0;
        $data['sum2_3'] = 0;

        foreach($data['record3'] as $key => $req)
        {

            if(array_intersect([2,3,4,5,11], (array) $req->process_type_id))
            {

                $data['total3'][$key] = $val + $req->amount;

                $val = $val + $req->amount;

                $data['sum1_3'] += $req->amount;
                

            }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id))
            {

                $data['total3'][$key] = $val - $req->amount;

                $val = $val - $req->amount;

                $data['sum2_3'] += $req->amount;

            }

        }

        $data['sum3_3'] = end($data['total3']);

        //TUNGGAKAN KESELURUHAN

        $data['current_balance'] = $data['sum3'];

        $data['total_balance'] = $data['current_balance'];

        $data['pk_balance'] = 0.00;

        //TUNGGAKAN SEMASA

        $package = DB::table('tblpackage_sponsorship')->where('student_ic', $request->student)->first();

        if($package != null)
        {

            if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
            {

                $discount = abs(DB::table('tblclaim')
                            ->join('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
                            ->where([
                                ['tblclaim.student_ic', $request->student],
                                ['tblclaim.process_type_id', 5],
                                ['tblclaim.process_status_id', 2],
                                ['tblclaim.remark', 'LIKE', '%Diskaun Yuran Kediaman%']
                            ])->sum('tblclaimdtl.amount'));

            }else{

                $discount = 0;
                
            }

            if($package->package_id == 5)
            {

                $data['current_balance'] = $data['sum3'];

            }else{

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                {

                    if($data['sum3'] <= ($package->amount - $discount))
                    {

                        $data['current_balance'] = 0.00;

                        $data['total_balance'] = 0.00;

                    }elseif($data['sum3'] > ($package->amount - $discount))
                    {

                        $data['current_balance'] = $data['sum3'] - ($package->amount - $discount);

                    }

                }

            }

            //TNUGGAKAN PEMBIAYAAN KHAS

            $stddetail = DB::table('students')->where('ic', $request->student)->select('program', 'semester')->first();

            if($stddetail->program == 7 || $stddetail->program == 8)
            {

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14 || $package->payment_type_id == 25 || ($package->package_id == 9 && $package->payment_type_id == 19))
                {

                    if($data['current_balance'] == 0.00)
                    {

                        $data['pk_balance'] = $data['sum3'];

                    }else{

                        $data['pk_balance'] = ($package->amount - $discount);

                    }

                }

            }else
            {

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14 || $package->payment_type_id == 25 || ($package->package_id == 9 && $package->payment_type_id == 19))
                {

                    if($data['current_balance'] == 0.00)
                    {

                        $data['pk_balance'] = $data['sum3'];

                    }else{

                        $data['pk_balance'] = ($package->amount - $discount);

                    }

                }

            }

        }else{

            $data['pk_balance'] = 0.00;

        }

        $data['total_all'] =  $data['current_balance'] + $data['pk_balance'];

        if(isset($request->print))
        {

            return view('finance.report.printStatement', compact('data'));

        }else{

            return view('finance.report.statementGetStudent', compact('data'));

        }

    }

    public function receiptList()
    {

        return view('finance.report.receiptList');

    }


    public function getReceiptList(Request $request)
    {

        if($request->refno != '')
        {

        $reg = DB::table('tblpayment')
        ->join('students', 'tblpayment.student_ic', 'students.ic')
        ->join('tblprocess_status', 'tblpayment.process_status_id', 'tblprocess_status.id')
        ->where('tblpayment.ref_no', 'LIKE', $request->refno."%")
        ->where('tblpayment.process_status_id', 2)
        ->select('tblpayment.id', 'tblpayment.date AS unified_date', 'tblpayment.ref_no','tblpayment.date AS date', 'tblpayment.process_type_id', 'tblpayment.amount', 'tblprocess_status.name AS status', 'students.no_matric', 'students.name AS name', 'students.ic');

        $data['student'] = DB::table('tblclaim')
        ->join('students', 'tblclaim.student_ic', 'students.ic')
        ->join('tblprocess_status', 'tblclaim.process_status_id', 'tblprocess_status.id')
        ->join('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
        ->where('tblclaim.ref_no', 'LIKE', $request->refno."%")
        ->where('tblclaim.process_status_id', 2)
        ->unionALL($reg)
        ->select('tblclaim.id', 'tblclaim.date AS unified_date', 'tblclaim.ref_no','tblclaim.date AS date', 'tblclaim.process_type_id', DB::raw('SUM(tblclaimdtl.amount) AS amount'), 'tblprocess_status.name AS status', 'students.no_matric', 'students.name AS name', 'students.ic')
        ->orderBy('unified_date', 'desc')
        ->get();

        

        }elseif($request->search != '')
        {

        $reg = DB::table('tblpayment')
        ->join('students', 'tblpayment.student_ic', 'students.ic')
        ->join('tblprocess_status', 'tblpayment.process_status_id', 'tblprocess_status.id')
        ->where('students.name', 'LIKE', $request->search."%")
        ->orwhere('students.ic', 'LIKE', $request->search."%")
        ->orwhere('students.no_matric', 'LIKE', $request->search."%")
        ->where('tblpayment.process_status_id', 2)
        ->select('tblpayment.id', 'tblpayment.date AS unified_date', 'tblpayment.ref_no','tblpayment.date AS date', 'tblpayment.process_type_id', 'tblpayment.amount', 'tblprocess_status.name AS status', 'students.no_matric', 'students.name AS name', 'students.ic');

        $reg2 = DB::table('tblclaim')
        ->join('students', 'tblclaim.student_ic', 'students.ic')
        ->leftjoin('tblprocess_status', 'tblclaim.process_status_id', 'tblprocess_status.id')
        ->leftjoin('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
        ->where('students.name', 'LIKE', $request->search."%")
        ->orwhere('students.ic', 'LIKE', $request->search."%")
        ->orwhere('students.no_matric', 'LIKE', $request->search."%")
        ->where('tblclaim.process_status_id', 2)
        ->groupBy('tblclaim.id')
        ->select('tblclaim.id', 'tblclaim.date AS unified_date', 'tblclaim.ref_no','tblclaim.date AS date', 'tblclaim.process_type_id', DB::raw('SUM(tblclaimdtl.amount) AS amount'), 'tblprocess_status.name AS status', 'students.no_matric', 'students.name AS name', 'students.ic');
        
        $data['student'] = $reg->union($reg2)->orderBy('unified_date', 'desc')->get();

        }else{

            return false;

        }

        if(isset($request->cancel))
        {

            return view('finance.payment.getReceiptList', compact('data'));

        }else{

            return view('finance.report.getReceiptList', compact('data'));

        }

    }

    public function getReceiptProof(Request $request)
    {

        if(array_intersect([2,3,4,5,11], (array) $request->type))
        {

            return redirect()->route('receipt3', ['id' => $request->id]);

        }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $request->type)){

            if(array_intersect([7], (array) $request->type))
            {

                return redirect()->route('receipt2', ['id' => $request->id]);

            }else{

                return redirect()->route('receipt', ['id' => $request->id]);

            }

        }

    }

    public function dailyReport()
    {

        return view('finance.report.dailyReport');

    }

    

    public function getDailyReport(Request $request)
    {

        $data['preRegister'] = [];
        $data['preMethod'] = [];
        $data['preDetail'] = [];
        $data['preTotals'] = [];

        $data['newStudent'] = [];
        $data['newStudMethod'] = [];
        $data['newStudDetail'] = [];
        $data['newTotals'] = [];

        $data['oldStudent'] = [];
        $data['oldStudMethod'] = [];
        $data['oldStudDetail'] = [];
        $data['oldTotals'] = [];

        $data['excess'] = [];
        $data['excessStudMethod'] = [];
        $data['excessStudDetail'] = [];
        $data['newexcessTotals'] = [];
        $data['oldexcessTotals'] = [];

        $data['Insentif'] = [];
        $data['InsentifStudMethod'] = [];
        $data['InsentifStudDetail'] = [];
        $data['newInsentifTotals'] = [];
        $data['oldInsentifTotals'] = [];

        $data['InsentifMco'] = [];
        $data['InsentifMcoStudMethod'] = [];
        $data['InsentifMcoStudDetail'] = [];
        $data['newInsentifMcoTotals'] = [];
        $data['oldInsentifMcoTotals'] = [];

        $data['Cov19'] = [];
        $data['Cov19StudMethod'] = [];
        $data['Cov19StudDetail'] = [];
        $data['newCov19Totals'] = [];
        $data['oldCov19Totals'] = [];

        $data['iNed'] = [];
        $data['iNedStudMethod'] = [];
        $data['iNedStudDetail'] = [];
        $data['newiNedTotals'] = [];
        $data['oldiNedTotals'] = [];

        $data['tabungkhas'] = [];
        $data['tabungkhasStudMethod'] = [];
        $data['tabungkhasStudDetail'] = [];
        $data['newtabungkhasTotals'] = [];
        $data['oldtabungkhasTotals'] = [];

        $data['sponsor'] = [];
        $data['sponsorStudMethod'] = [];
        $data['sponsorStudDetail'] = [];
        $data['newsponsorTotals'] = [];
        $data['oldsponsorTotals'] = [];

        $data['withdrawStudent'] = [];
        $data['withdrawStudMethod'] = [];
        $data['withdrawStudDetail'] = [];
        $data['withdrawTotals'] = [];

        $data['graduateStudent'] = [];
        $data['graduateStudMethod'] = [];
        $data['graduateStudDetail'] = [];
        $data['graduateTotals'] = [];

        $data['failStudent'] = [];
        $data['failStudMethod'] = [];
        $data['failStudDetail'] = [];
        $data['failTotals'] = [];

        $data['hostelStudent'] = [];
        $data['hostelStudMethod'] = [];
        $data['hostelStudDetail'] = [];
        $data['hostelTotals'] = [];

        $data['other'] = [];
        $data['otherStudMethod'] = [];
        $data['otherStudDetail'] = [];
        $data['otherTotals'] = [];

        $data['hostel'] = [];
        $data['convo'] = [];
        $data['fine'] = [];

        $payment = DB::table('tblpayment')
                   ->join('students', 'tblpayment.student_ic', 'students.ic')
                   ->select('tblpayment.*', 'students.name', 'students.ic', 'students.no_matric', 'students.status', 'students.program', 'students.semester', 'tblpayment.add_date')
                   ->whereBetween('tblpayment.add_date', [$request->from, $request->to])
                   ->where('tblpayment.process_status_id', 2)
                   ->whereNotNull('tblpayment.ref_no')
                   ->orderBy('tblpayment.ref_no', 'asc')
                   ->get();

        $sponsor = DB::table('tblpayment')
                   ->join('students', 'tblpayment.student_ic', 'students.ic')
                   ->select('tblpayment.*', 'students.name', 'students.ic', 'students.no_matric', 'students.status', 'students.program', 'students.semester', 'tblpayment.add_date')
                   ->whereBetween('tblpayment.add_date', [$request->from, $request->to])
                   ->where('tblpayment.process_status_id', 2)
                   ->whereNotNull('tblpayment.student_ic')
                   ->whereNotNull('tblpayment.payment_sponsor_id')
                   ->orderBy('tblpayment.ref_no', 'asc')
                   ->get();

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        foreach($payment as $pym)
        {

            // Log payment details
    \Log::info('Processing payment:', (array) $pym);

            $status = 0;

            $status = DB::table('tblstudent_log')
                    ->leftJoin('tblstudent_status', 'tblstudent_log.status_id', '=', 'tblstudent_status.id')
                    ->where('tblstudent_log.student_ic', $pym->ic)
                    ->where('tblstudent_log.date', '<=', $pym->add_date)
                    ->orderBy('tblstudent_log.id', 'desc')
                    ->select('tblstudent_status.id')
                    ->first();

                    // Log status query result
    \Log::info('Status query result:', (array) $status);

    if (is_null($status)) {
        \Log::warning('No status found for student IC:', ['student_ic' => $pym->ic, 'add_date' => $pym->add_date]);
      
    }

            if($pym->process_type_id == 6 && $pym->process_status_id == 2)
            {

                //newexcess

                if($pym->semester == 1)
                {

                    $data['excess'][] = $pym;

                    $data['excessStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();

                    $data['excessStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['excess'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['newexcessTotal'][$key][$keys] =+  collect($data['excessStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['newexcessTotal'][$key][$keys] = null;

                            }

                        }

                        $data['newexcessTotals'][$key] =+ array_sum($data['newexcessTotal'][$key]);

                    }
                }

                //oldexcess

                if($pym->semester != 1)
                {

                    $data['excess'][] = $pym;

                    $data['excessStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();

                    $data['excessStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['excess'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['oldexcessTotal'][$key][$keys] =+  collect($data['excessStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['oldexcessTotal'][$key][$keys] = null;

                            }

                        }

                        $data['oldexcessTotals'][$key] =+ array_sum($data['oldexcessTotal'][$key]);

                    }
                }

            }
            elseif(($status->id == 1 || $status->id == 14) && $pym->sponsor_id == null)
            {

                if(DB::table('tblpaymentdtl')
                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                ->where('tblpaymentdtl.payment_id', $pym->id)
                ->whereIn('tblstudentclaim.groupid', [1])->exists())
                {

                    //preregistration

                    $data['preRegister'][] = $pym;

                    $data['preDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->whereIn('tblstudentclaim.groupid', [1])
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['preMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();

                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['preRegister'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['preTotal'][$key][$keys] =+  collect($data['preDetail'][$keys])->sum('amount');

                            }else{

                                $data['preTotal'][$key][$keys] = null;

                            }

                        }

                        $data['preTotals'][$key] =+ array_sum($data['preTotal'][$key]);

                    }
                }

            }elseif($status->id == 2 && $pym->sponsor_id == null && $pym->semester == 1)
            {

                //newstudent

                if(DB::table('tblpaymentdtl')
                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                ->where('tblpaymentdtl.payment_id', $pym->id)
                ->whereIn('tblstudentclaim.groupid', [1])->exists())
                {

                    $data['newStudent'][] = $pym;

                    $data['newStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->whereIn('tblstudentclaim.groupid', [1])
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['newStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();

                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['newStudent'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['newTotal'][$key][$keys] =+  collect($data['newStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['newTotal'][$key][$keys] = null;

                            }

                        }

                        $data['newTotals'][$key] =+ array_sum($data['newTotal'][$key]);

                    }
                }

                //OTHER

                if(DB::table('tblpaymentdtl')
                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                ->where('tblpaymentdtl.payment_id', $pym->id)
                ->where('tblpaymentdtl.amount', '!=', 0)
                ->where('tblpaymentdtl.claim_type_id', '!=', 47)
                ->whereIn('tblstudentclaim.groupid', [5])->exists())
                {


                    $data['other'][] = $pym;

                    $data['otherStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->whereIn('tblstudentclaim.groupid', [5])
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->where('tblpaymentdtl.claim_type_id', '!=', 47)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['otherStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();

    
                }

                //newinsentif

                if($pym->process_type_id == 9)
                {

                    $data['Insentif'][] = $pym;

                    $data['InsentifStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['InsentifStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['Insentif'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['newInsentifTotal'][$key][$keys] =+  collect($data['InsentifStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['newInsentifTotal'][$key][$keys] = null;

                            }

                        }

                        $data['newInsentifTotals'][$key] =+ array_sum($data['newInsentifTotal'][$key]);

                    }
                }

                //newinsentifMco

                if($pym->process_type_id == 15 && $pym->process_type_id == 21)
                {

                    $data['InsentifMco'][] = $pym;

                    $data['InsentifMcoStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['InsentifMcoStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['InsentifMco'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['newInsentifMcoTotal'][$key][$keys] =+  collect($data['InsentifMcoStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['newInsentifMcoTotal'][$key][$keys] = null;

                            }

                        }

                        $data['newInsentifMcoTotals'][$key] =+ array_sum($data['newInsentifMcoTotal'][$key]);

                    }
                }

                //newCov19

                if($pym->process_type_id == 14)
                {

                    $data['Cov19'][] = $pym;

                    $data['Cov19StudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['Cov19StudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['Cov19'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['newCov19Total'][$key][$keys] =+  collect($data['Cov19StudDetail'][$keys])->sum('amount');

                            }else{

                                $data['newCov19Total'][$key][$keys] = null;

                            }

                        }

                        $data['newCov19Totals'][$key] =+ array_sum($data['newCov19Total'][$key]);

                    }
                }

                //newiNed

                if($pym->process_type_id == 10)
                {

                    $data['iNed'][] = $pym;

                    $data['iNedStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['iNedStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['iNed'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['newiNedTotal'][$key][$keys] =+  collect($data['iNedStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['newiNedTotal'][$key][$keys] = null;

                            }

                        }

                        $data['newiNedTotals'][$key] =+ array_sum($data['newiNedTotal'][$key]);

                    }
                }

                //newtabungkhas

                if($pym->process_type_id == 16 && $pym->process_type_id == 17 && $pym->process_type_id == 18 && $pym->process_type_id == 19 && $pym->process_type_id == 20
                && $pym->process_type_id == 22 && $pym->process_type_id == 23 && $pym->process_type_id == 24 && $pym->process_type_id == 25)
                {

                    $data['tabungkhas'][] = $pym;

                    $data['tabungkhasStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['tabungkhasStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['tabungkhas'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['newtabungkhasTotal'][$key][$keys] =+  collect($data['tabungkhasStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['newtabungkhasTotal'][$key][$keys] = null;

                            }

                        }

                        $data['newtabungkhasTotals'][$key] =+ array_sum($data['newtabungkhasTotal'][$key]);

                    }
                }

            }elseif($status->id == 2 && $pym->sponsor_id == null && $pym->semester != 1)
            {

                if($pym->process_type_id == 1)
                {

                    //oldstudent

                    if(DB::table('tblpaymentdtl')
                    ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                    ->where('tblpaymentdtl.payment_id', $pym->id)
                    ->where('tblpaymentdtl.amount', '!=', 0)
                    ->whereIn('tblstudentclaim.groupid', [1])->exists())
                    {

                        $data['oldStudent'][] = $pym;

                        $data['oldStudDetail'][] = DB::table('tblpaymentdtl')
                                                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                                ->where('tblpaymentdtl.payment_id', $pym->id)
                                                ->whereIn('tblstudentclaim.groupid', [1])
                                                ->where('tblpaymentdtl.amount', '!=', 0)
                                                ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                                ->get();
                
                        $data['oldStudMethod'][] = DB::table('tblpaymentmethod')
                                                ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                                ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                                ->where('tblpaymentmethod.payment_id', $pym->id)
                                                ->groupBy('tblpaymentmethod.id')
                                                ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                                ->get();

                        //program

                        foreach($data['program'] as $key => $prg)
                        {
                            foreach($data['oldStudent'] as $keys => $rs)
                            {

                                if($rs->program == $prg->id)
                                {

                                    $data['oldTotal'][$key][$keys] =+  collect($data['oldStudDetail'][$keys])->sum('amount');

                                }else{

                                    $data['oldTotal'][$key][$keys] = null;

                                }

                            }

                            //dd(array_sum($data['oldTotal'][$key]));


                            $data['oldTotals'][$key] =+ array_sum($data['oldTotal'][$key]);

                        }
                    }

                }

                //OTHER

                if(DB::table('tblpaymentdtl')
                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                ->where('tblpaymentdtl.payment_id', $pym->id)
                ->where('tblpaymentdtl.amount', '!=', 0)
                ->where('tblpaymentdtl.claim_type_id', '!=', 47)
                ->whereIn('tblstudentclaim.groupid', [5])->exists())
                {


                    $data['other'][] = $pym;

                    $data['otherStudDetail'][] = DB::table('tblpaymentdtl')
                                                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                                ->where('tblpaymentdtl.payment_id', $pym->id)
                                                ->whereIn('tblstudentclaim.groupid', [5])
                                                ->where('tblpaymentdtl.amount', '!=', 0)
                                                ->where('tblpaymentdtl.claim_type_id', '!=', 47)
                                                ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                                ->get();
                 
                    $data['otherStudMethod'][] = DB::table('tblpaymentmethod')
                                                ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                                ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                                ->where('tblpaymentmethod.payment_id', $pym->id)
                                                ->groupBy('tblpaymentmethod.id')
                                                ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                                ->get();

    
                }

                //oldinsentif

                if($pym->process_type_id == 9)
                {

                    $data['Insentif'][] = $pym;

                    $data['InsentifStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['InsentifStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();

                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['Insentif'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['oldInsentifTotal'][$key][$keys] =+  collect($data['InsentifStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['oldInsentifTotal'][$key][$keys] = null;

                            }

                        }

                        $data['oldInsentifTotals'][$key] =+ array_sum($data['oldInsentifTotal'][$key]);

                    }
                }

                //oldinsentifmco

                if($pym->process_type_id == 15 || $pym->process_type_id == 21)
                {

                    $data['InsentifMco'][] = $pym;

                    $data['InsentifMcoStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['InsentifMcoStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();

                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['InsentifMco'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['oldInsentifMcoTotal'][$key][$keys] =+  collect($data['InsentifMcoStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['oldInsentifMcoTotal'][$key][$keys] = null;

                            }

                        }

                        $data['oldInsentifMcoTotals'][$key] =+ array_sum($data['oldInsentifMcoTotal'][$key]);

                    }
                }

                //oldCov19

                if($pym->process_type_id == 14)
                {

                    $data['Cov19'][] = $pym;

                    $data['Cov19StudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['Cov19StudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();

                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['Cov19'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['oldCov19Total'][$key][$keys] =+  collect($data['Cov19StudDetail'][$keys])->sum('amount');

                            }else{

                                $data['oldCov19Total'][$key][$keys] = null;

                            }

                        }

                        $data['oldCov19Totals'][$key] =+ array_sum($data['oldCov19Total'][$key]);

                    }
                }

                //oldiNed

                if($pym->process_type_id == 10)
                {

                    $data['iNed'][] = $pym;

                    $data['iNedStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['iNedStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();

                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['iNed'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['oldiNedTotal'][$key][$keys] =+  collect($data['iNedStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['oldiNedTotal'][$key][$keys] = null;

                            }

                        }

                        $data['oldiNedTotals'][$key] =+ array_sum($data['oldiNedTotal'][$key]);

                    }
                }

                //oldtabungkhas

                if($pym->process_type_id == 16 || $pym->process_type_id == 17 || $pym->process_type_id == 18 || $pym->process_type_id == 19 || $pym->process_type_id == 20
                   || $pym->process_type_id == 22 || $pym->process_type_id == 23 || $pym->process_type_id == 24 || $pym->process_type_id == 25)
                {

                    $data['tabungkhas'][] = $pym;

                    $data['tabungkhasStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->where('tblpaymentdtl.amount', '!=', 0)
                                            ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                            ->get();
             
                    $data['tabungkhasStudMethod'][] = DB::table('tblpaymentmethod')
                                            ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                            ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                            ->where('tblpaymentmethod.payment_id', $pym->id)
                                            ->groupBy('tblpaymentmethod.id')
                                            ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                            ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['tabungkhas'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['oldtabungkhasTotal'][$key][$keys] =+  collect($data['tabungkhasStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['oldtabungkhasTotal'][$key][$keys] = null;

                            }

                        }

                        $data['oldtabungkhasTotals'][$key] =+ array_sum($data['oldtabungkhasTotal'][$key]);

                    }
                }

            }elseif($status->id == 4 && $pym->sponsor_id == null)
            {

                //withdraw

                $data['withdrawStudent'][] = $pym;

                $data['withdrawStudDetail'][] = DB::table('tblpaymentdtl')
                                                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                                ->where('tblpaymentdtl.payment_id', $pym->id)
                                                ->whereIn('tblstudentclaim.groupid', [1])
                                                ->where('tblpaymentdtl.amount', '!=', 0)
                                                ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                                ->get();
                 
                $data['withdrawStudMethod'][] = DB::table('tblpaymentmethod')
                                                ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                                ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                                ->where('tblpaymentmethod.payment_id', $pym->id)
                                                ->groupBy('tblpaymentmethod.id')
                                                ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                                ->get();

                //program

                foreach($data['program'] as $key => $prg)
                {
                    foreach($data['withdrawStudent'] as $keys => $rs)
                    {

                        if($rs->program == $prg->id)
                        {

                            $data['withdrawTotal'][$key][$keys] =+  collect($data['withdrawStudDetail'][$keys])->sum('amount');

                        }else{

                            $data['withdrawTotal'][$key][$keys] = null;

                        }

                    }

                    $data['withdrawTotals'][$key] =+ array_sum($data['withdrawTotal'][$key]);

                }

            }elseif($status->id == 8 && $pym->sponsor_id == null && $pym->semester != 1)
            {

                if(($pym->process_type_id == 1 || $pym->process_type_id == 8) && $pym->process_status_id == 2)
                {

                    //graduate

                    if(DB::table('tblpaymentdtl')
                    ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                    ->where('tblpaymentdtl.payment_id', $pym->id)
                    ->where('tblpaymentdtl.amount', '!=', 0)
                    ->whereIn('tblstudentclaim.groupid', [1])->exists())
                    {

                        $data['graduateStudent'][] = $pym;

                        $data['graduateStudDetail'][] = DB::table('tblpaymentdtl')
                                                        ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                                        ->where('tblpaymentdtl.payment_id', $pym->id)
                                                        ->whereIn('tblstudentclaim.groupid', [1])
                                                        ->where('tblpaymentdtl.amount', '!=', 0)
                                                        ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                                        ->get();
                        
                        $data['graduateStudMethod'][] = DB::table('tblpaymentmethod')
                                                        ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                                        ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                                        ->where('tblpaymentmethod.payment_id', $pym->id)
                                                        ->groupBy('tblpaymentmethod.id')
                                                        ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                                        ->get();

                        //program

                        foreach($data['program'] as $key => $prg)
                        {
                            foreach($data['graduateStudent'] as $keys => $rs)
                            {

                                if($rs->program == $prg->id)
                                {

                                    $data['graduateTotal'][$key][$keys] =+  collect($data['graduateStudDetail'][$keys])->sum('amount');

                                }else{

                                    $data['graduateTotal'][$key][$keys] = null;

                                }

                            }

                            $data['graduateTotals'][$key] =+ array_sum($data['graduateTotal'][$key]);

                        }

                    }

                    //OTHER

                    if(DB::table('tblpaymentdtl')
                    ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                    ->where('tblpaymentdtl.payment_id', $pym->id)
                    ->where('tblpaymentdtl.amount', '!=', 0)
                    ->where('tblpaymentdtl.claim_type_id', '!=', 47)
                    ->whereIn('tblstudentclaim.groupid', [5])->exists())
                    {


                        $data['other'][] = $pym;

                        $data['otherStudDetail'][] = DB::table('tblpaymentdtl')
                                                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                                ->where('tblpaymentdtl.payment_id', $pym->id)
                                                ->whereIn('tblstudentclaim.groupid', [5])
                                                ->where('tblpaymentdtl.amount', '!=', 0)
                                                ->where('tblpaymentdtl.claim_type_id', '!=', 47)
                                                ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                                ->get();
                
                        $data['otherStudMethod'][] = DB::table('tblpaymentmethod')
                                                ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                                ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                                ->where('tblpaymentmethod.payment_id', $pym->id)
                                                ->groupBy('tblpaymentmethod.id')
                                                ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                                ->get();

        
                    }

                }

            }elseif($status->id == 3 && $pym->sponsor_id == null && $pym->semester != 1)
            {

                if(($pym->process_type_id == 1 || $pym->process_type_id == 8) && $pym->process_status_id == 2)
                {

                    //fail

                    if(DB::table('tblpaymentdtl')
                    ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                    ->where('tblpaymentdtl.payment_id', $pym->id)
                    ->where('tblpaymentdtl.amount', '!=', 0)
                    ->whereIn('tblstudentclaim.groupid', [1])->exists())
                    {

                        $data['failStudent'][] = $pym;

                        $data['failStudDetail'][] = DB::table('tblpaymentdtl')
                                                        ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                                        ->where('tblpaymentdtl.payment_id', $pym->id)
                                                        ->whereIn('tblstudentclaim.groupid', [1])
                                                        ->where('tblpaymentdtl.amount', '!=', 0)
                                                        ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                                        ->get();
                        
                        $data['failStudMethod'][] = DB::table('tblpaymentmethod')
                                                        ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                                        ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                                        ->where('tblpaymentmethod.payment_id', $pym->id)
                                                        ->groupBy('tblpaymentmethod.id')
                                                        ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                                        ->get();

                        //program

                        foreach($data['program'] as $key => $prg)
                        {
                            foreach($data['failStudent'] as $keys => $rs)
                            {

                                if($rs->program == $prg->id)
                                {

                                    $data['failTotal'][$key][$keys] =+  collect($data['failStudDetail'][$keys])->sum('amount');

                                }else{

                                    $data['failTotal'][$key][$keys] = null;

                                }

                            }

                            $data['failTotals'][$key] =+ array_sum($data['failTotal'][$key]);

                        }

                    }
                    
                }

            }

        }

        foreach($sponsor as $key => $spn)
        {

            if($spn->status == 2 && $spn->sponsor_id != null && $spn->semester == 1)
            {

                //newsponsor

                if($spn->process_type_id == 7)
                {

                    $data['sponsor'][] = $spn;

                    $data['sponsorStudDetail'][] = DB::table('tblpaymentdtl')
                                                   ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                                   ->where('tblpaymentdtl.payment_id', $spn->id)
                                                   ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                                   ->get();
                    
                    $data['sponsorStudMethod'][] = DB::table('tblpaymentmethod')
                                                   ->join('tblpayment', 'tblpaymentmethod.payment_id', 'tblpayment.id')
                                                   ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                                   ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                                   ->where('tblpayment.id', $spn->sponsor_id)
                                                   ->groupBy('tblpaymentmethod.id')
                                                   ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                                   ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['sponsor'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['newsponsorTotal'][$key][$keys] =+  collect($data['sponsorStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['newsponsorTotal'][$key][$keys] = null;

                            }

                        }

                        $data['newsponsorTotals'][$key] =+ array_sum($data['newsponsorTotal'][$key]);

                    }
                }

            }elseif($spn->status == 2 && $spn->sponsor_id != null && $spn->semester != 1)
            {

                //oldsponsor

                if($spn->process_type_id == 7)
                {

                    $data['sponsor'][] = $spn;

                    $data['sponsorStudDetail'][] = DB::table('tblpaymentdtl')
                                                   ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                                   ->where('tblpaymentdtl.payment_id', $spn->id)
                                                   ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS type')
                                                   ->get();
                    
                    $data['sponsorStudMethod'][] = DB::table('tblpaymentmethod')
                                                   ->join('tblpayment', 'tblpaymentmethod.payment_id', 'tblpayment.id')
                                                   ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                                                   ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                                                   ->where('tblpayment.id', $spn->sponsor_id)
                                                   ->groupBy('tblpaymentmethod.id')
                                                   ->select('tblpaymentmethod.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                                                   ->get();


                    //program

                    foreach($data['program'] as $key => $prg)
                    {
                        foreach($data['sponsor'] as $keys => $rs)
                        {

                            if($rs->program == $prg->id)
                            {

                                $data['oldsponsorTotal'][$key][$keys] =+  collect($data['sponsorStudDetail'][$keys])->sum('amount');

                            }else{

                                $data['oldsponsorTotal'][$key][$keys] = null;

                            }

                        }

                        $data['oldsponsorTotals'][$key] =+ array_sum($data['oldsponsorTotal'][$key]);

                    }
                }

            }
            
        }
        //dd( $data['otherStudMethod']);
        
        // Subquery for tblpaymentdtl with row numbers
        $paymentDtl = DB::table('tblpaymentdtl')
        ->select('id', 'payment_id', 'claim_type_id', 'amount',
            DB::raw('ROW_NUMBER() OVER (PARTITION BY payment_id ORDER BY id) as row_num')
        );

        // Subquery for tblpaymentmethod with row numbers
        $paymentMethod = DB::table('tblpaymentmethod')
        ->select('id', 'payment_id', 'claim_method_id', 'bank_id', 'no_document',
            DB::raw('ROW_NUMBER() OVER (PARTITION BY payment_id ORDER BY id) as row_num')
        );

        // Main query with join modifications
        $other = DB::table('tblpayment')
        ->joinSub($paymentDtl, 'dtl', function ($join) {
            $join->on('dtl.payment_id', '=', 'tblpayment.id');
        })
        ->joinSub($paymentMethod, 'method', function ($join) {
            $join->on('method.payment_id', '=', 'tblpayment.id')
                ->on('method.row_num', '=', 'dtl.row_num');
        })
        ->join('tblstudentclaim', 'dtl.claim_type_id', '=', 'tblstudentclaim.id')
        ->leftJoin('tblpayment_bank', 'method.bank_id', '=', 'tblpayment_bank.id')
        ->join('tblpayment_method', 'method.claim_method_id', '=', 'tblpayment_method.id')
        ->whereBetween('tblpayment.add_date', [$request->from, $request->to])
        ->where('tblpayment.process_status_id', 2)
        ->select(
            'tblpayment.*',
            'tblstudentclaim.groupid',
            'tblstudentclaim.name AS type',
            'dtl.amount',
            'dtl.claim_type_id',
            'method.no_document',
            'tblpayment_method.name AS method',
            'tblpayment_bank.name AS bank'
        )
        ->orderBy('tblpayment.ref_no', 'asc')
        ->groupBy('dtl.id')
        ->get();


        foreach($other as $ot)
        {
            if(array_intersect([8], (array) $ot->process_type_id) && array_intersect([2], (array) $ot->groupid) && $ot->amount != 0)
            {
                $data['hostel'][] = $ot;

            }elseif(array_intersect([8,1], (array) $ot->process_type_id) && array_intersect([5], (array) $ot->groupid) && $ot->amount != 0 && $ot->claim_type_id == 47)
            {
                $data['convo'][] = $ot;

            }elseif(array_intersect([4], (array) $ot->groupid) && $ot->amount != 0)
            {
                $data['fine'][] = $ot;

            }

        }
        
        if(isset($request->print))
        {

            $data['from'] = Carbon::createFromFormat('Y-m-d', $request->from)->translatedFormat('d F Y'); ;
            $data['to'] = Carbon::createFromFormat('Y-m-d', $request->to)->translatedFormat('d F Y');

            return view('finance.report.printDailyReport', compact('data'));

        }else{

            return view('finance.report.getDailyReport', compact('data'));
            
        }

    }

    public function chargeReport()
    {

        return view('finance.report.chargeReport');

    }

    public function getChargeReport(Request $request)
    {
        $data['newStudent'] = [];
        $data['newStudentTotal'] = [];
        $data['newStudentTotals'] = [];

        $data['oldStudent'] = [];
        $data['oldStudentTotal'] = [];
        $data['oldStudentTotals'] = [];

        $data['debit'] = [];
        $data['debitTotal'] = [];
        $data['debitTotals'] = [];

        $data['fine'] = [];
        $data['fineTotal'] = [];

        
        $data['other'] = [];
        $data['otherDetail'] = [];
        $data['otherTotal'] = [];
        $data['otherTotals'] = [];

        $data['creditFee'] = [];
        $data['creditFeeTotal'] = [];
        $data['creditFeeTotals'] = [];

        $data['creditFine'] = [];
        $data['creditFineTotal'] = [];
        $data['creditFineTotals'] = [];

        $data['creditDiscount'] = [];
        $data['creditDiscountTotal'] = [];
        $data['creditDiscountTotals'] = [];

        $charge = DB::table('tblclaim')
                  ->join('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
                  ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                  ->join('students', 'tblclaim.student_ic', 'students.ic')
                  ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                  ->groupBy('tblclaim.id', 'tblstudentclaim.groupid')
                  ->where('tblclaim.ref_no', '!=', null)
                  ->whereBetween('tblclaim.add_date', [$request->from, $request->to])
                  ->select('tblclaim.*', 
                            DB::raw('SUM(tblclaimdtl.amount) AS amount'), 
                            'students.name', 'students.program', 
                            'students.no_matric', 
                            'tblprogramme.progname', 
                            'tblstudentclaim.groupid',
                            'tblstudentclaim.name AS type')
                  ->orderBy('tblclaim.ref_no', 'desc')
                  ->get();

        

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        $data['otherCharge'] = DB::table('tblstudentclaim')->where('groupid', 5)->get();

        foreach($charge as $i => $crg)
        {

            if($crg->process_type_id == 2 && $crg->process_status_id == 2 && $crg->semester_id == 1)
            {

                $data['newStudent'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
                    foreach($data['newStudent'] as $keys => $dbt)
                    {
           
                        if($dbt->program == $prg->id)
                        {

                            $data['newStudentTotal'][$key][$keys] =+  $dbt->amount;

                        }else{

                            $data['newStudentTotal'][$key][$keys] =+ 0;

                        }

                    }

                    $data['newStudentTotals'][$key] =+ array_sum($data['newStudentTotal'][$key]);

                }

            }elseif($crg->process_type_id == 2 && $crg->process_status_id == 2 && $crg->semester_id >= 2)
            {

                $data['oldStudent'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
                    foreach($data['oldStudent'] as $keys => $dbt)
                    {
           
                        if($dbt->program == $prg->id)
                        {

                            $data['oldStudentTotal'][$key][$keys] =+  $dbt->amount;

                        }else{

                            $data['oldStudentTotal'][$key][$keys] =+ 0;

                        }

                    }

                    $data['oldStudentTotals'][$key] =+ array_sum($data['oldStudentTotal'][$key]);

                }

            }elseif($crg->process_type_id == 4 && $crg->process_status_id == 2 && $crg->groupid == 1)
            {

                $data['debit'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
                    foreach($data['debit'] as $keys => $dbt)
                    {
           
                        if($dbt->program == $prg->id)
                        {

                            $data['debitTotal'][$key][$keys] =+  $dbt->amount;

                        }else{

                            $data['debitTotal'][$key][$keys] =+ 0;

                        }

                    }

                    $data['debitTotals'][$key] =+ array_sum($data['debitTotal'][$key]);

                }

            }elseif($crg->process_type_id == 4 && $crg->process_status_id == 2 && $crg->groupid == 4)
            {

                $data['fine'][] = $crg;

            }elseif($crg->process_type_id == 4 && $crg->process_status_id == 2 && $crg->groupid == 5)
            {

                //graduate

                $data['other'][] = $crg;

                $data['otherDetail'] = DB::table('tblclaimdtl')->where('claim_id', $crg->id)->get();

                //program

                foreach($data['otherCharge'] as $key => $chrgs)
                {
                    foreach($data['otherDetail'] as $keys => $dtl)
                    {

                        if($chrgs->id == $dtl->claim_package_id)
                        {

                            $data['otherTotal'][$key][$keys] =+  $dtl->amount;

                        }else{

                            $data['otherTotal'][$key][$keys] = null;

                        }

                    }

                    $data['otherTotals'][$key] =+ array_sum($data['otherTotal'][$key]);

                }

            }elseif($crg->process_type_id == 5 && $crg->process_status_id == 2 && $crg->groupid == 1 && $crg->reduction_id < 6)
            {

                $data['creditFee'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
                    foreach($data['creditFee'] as $keys => $dbt)
                    {
           
                        if($dbt->program == $prg->id)
                        {

                            $data['creditFeeTotal'][$key][$keys] =+  $dbt->amount;

                        }else{

                            $data['creditFeeTotal'][$key][$keys] =+ 0;

                        }

                    }

                    $data['creditFeeTotals'][$key] =+ array_sum($data['creditFeeTotal'][$key]);

                }

            }elseif($crg->process_type_id == 5 && $crg->process_status_id == 2 && $crg->groupid != 1)
            {

                $data['creditFine'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
                    foreach($data['creditFine'] as $keys => $dbt)
                    {
           
                        if($dbt->program == $prg->id)
                        {

                            $data['creditFineTotal'][$key][$keys] =+  $dbt->amount;

                        }else{

                            $data['creditFineTotal'][$key][$keys] =+ 0;

                        }

                    }

                    $data['creditFineTotals'][$key] =+ array_sum($data['creditFineTotal'][$key]);

                }

            }elseif($crg->process_type_id == 5 && $crg->process_status_id == 2 && $crg->groupid == 1 && $crg->reduction_id > 5)
            {

                $data['creditDiscount'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
                    foreach($data['creditDiscount'] as $keys => $dbt)
                    {
           
                        if($dbt->program == $prg->id)
                        {

                            $data['creditDiscountTotal'][$key][$keys] =+  $dbt->amount;

                        }else{

                            $data['creditDiscountTotal'][$key][$keys] =+ 0;

                        }

                    }

                    $data['creditDiscountTotals'][$key] =+ array_sum($data['creditDiscountTotal'][$key]);

                }

            }
            
        }

        if(isset($request->print))
        {
            $data['from'] = Carbon::createFromFormat('Y-m-d', $request->from)->translatedFormat('d F Y'); ;
            $data['to'] = Carbon::createFromFormat('Y-m-d', $request->to)->translatedFormat('d F Y');

            return view('finance.report.printChargeReport', compact('data'));

        }else{

            return view('finance.report.getChargeReport', compact('data'));
            
        }

    }

    public function studentOtherPayment()
    {

        return view('finance.payment.paymentOther');

    }

    public function getOtherStudentPayment(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->orderBy('name', 'asc')->get();

        $data['type'] = DB::table('tblstudentclaim')->get();

        return  view('finance.payment.paymentOtherGetStudent', compact('data'));

    }

    public function storeOtherPayment(Request $request)
    {

        $paymentData = $request->paymentData;

        $validator = Validator::make($request->all(), [
            'paymentData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentData);
                
                if($payment->total != null)
                {
                    $stddetail = DB::table('students')->where('ic', $payment->ic)->first();

                    $id = DB::table('tblpayment')->insertGetId([
                        'student_ic' => $payment->ic,
                        'date' => date('Y-m-d'),
                        'ref_no' => null,
                        'program_id' => $stddetail->program,
                        'session_id' => $stddetail->session,
                        'semester_id' => $stddetail->semester,
                        'amount' => $payment->total,
                        'process_status_id' => 1,
                        'process_type_id' => 8,
                        'add_staffID' => Auth::user()->ic,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => Auth::user()->ic,
                        'mod_date' => date('Y-m-d')
                    ]);

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $id];

    }

    public function storeOtherPaymentDtl(Request $request)
    {

        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $payment = json_decode($paymentDetail);
                
                if($payment->method != null && $payment->amount != null)
                {
                    $total = $payment->amount;

                    $main = DB::table('tblpayment')->where('id', $payment->id)->first();

                    $details = DB::table('tblpaymentdtl')->where('payment_id', $payment->id)->get();

                    if(count($details) > 0)
                    {

                        $total = $total + DB::table('tblpaymentdtl')->where('payment_id', $payment->id)->sum('amount');
                        
                    }

                    if($total > $main->amount)
                    {

                        return ["message" => "Add cannot exceed initial payment value!"];

                    }else{

                        if(($payment->nodoc != null) ? DB::table('tblpaymentmethod')->join('tblpayment', 'tblpaymentmethod.payment_id', 'tblpayment.id')
                        ->where('tblpaymentmethod.no_document', $payment->nodoc)->whereNotIn('tblpayment.process_status_id', [1, 3])->exists() : '')
                        {

                            return ["message" => "Document with the same number already used! Please use a different document no."];

                        }else{

                            DB::table('tblpaymentmethod')->insertGetId([
                                'payment_id' => $payment->id,
                                'claim_method_id' => $payment->method,
                                'bank_id' => $payment->bank,
                                'no_document' => $payment->nodoc,
                                'amount' => $payment->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                            DB::table('tblpaymentdtl')->insert([
                                'payment_id' => $payment->id,
                                'claim_type_id' => $payment->type,
                                'amount' => $payment->amount,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }

                    }

                    $details = DB::table('tblpaymentdtl')
                               ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                               ->where('tblpaymentdtl.payment_id', $payment->id)
                               ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS claim_type_id')->get();

                    $sum = DB::table('tblpaymentdtl')->where('payment_id', $payment->id)->sum('amount');

                    $methods = DB::table('tblpaymentmethod')->where('payment_id', $payment->id)->get();

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th style="width: 10%">
                                            Date
                                        </th>
                                        <th style="width: 15%">
                                            Type
                                        </th>
                                        <th style="width: 10%">
                                            Amount
                                        </th>
                                        <th style="width: 20%">
                                            Remark
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($details as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td style="width: 15%">
                            '. $main->date .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->claim_type_id .'
                            </td>
                            <td style="width: 30%">
                            '. $dtl->amount .'
                            </td>
                            <td>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $methods[$key]->id .','. $main->id .')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>
                            </td>
                        </tr>
                        ';
                        }
                    $content .= '<tr>
                            <td style="width: 1%">
                            
                            </td>
                            <td style="width: 15%">
                            TOTAL AMOUNT
                            </td>
                            <td style="width: 15%">
                            :
                            </td>
                            <td style="width: 30%">
                            '. $sum .'
                            </td>
                            <td>
                        
                            </td>
                        </tr>';
                    $content .= '</tbody>';

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $content];

    }

    public function deleteOtherPayment(Request $request)
    {

        DB::table('tblpaymentdtl')->where('id', $request->dtl)->delete();

        DB::table('tblpaymentmethod')->where('id', $request->meth)->delete();

        $main = DB::table('tblpayment')->where('id', $request->id)->first();

        $details = DB::table('tblpaymentdtl')
                               ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                               ->where('tblpaymentdtl.payment_id', $request->id)
                               ->select('tblpaymentdtl.*', 'tblstudentclaim.name AS claim_type_id')->get();

        $sum = DB::table('tblpaymentdtl')->where('payment_id', $request->id)->sum('amount');

        $methods = DB::table('tblpaymentmethod')->where('payment_id', $request->id)->get();

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 10%">
                                Date
                            </th>
                            <th style="width: 15%">
                                Type
                            </th>
                            <th style="width: 10%">
                                Amount
                            </th>
                            <th style="width: 20%">
                                Remark
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($details as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $main->date .'
                </td>
                <td style="width: 15%">
                '. $dtl->claim_type_id .'
                </td>
                <td style="width: 30%">
                '. $dtl->amount .'
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .','. $methods[$key]->id .', '. $dtl->payment_id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>
                </td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
                <tr>
                    <td style="width: 1%">
                    
                    </td>
                    <td style="width: 15%">
                    TOTAL AMOUNT
                    </td>
                    <td style="width: 15%">
                    :
                    </td>
                    <td style="width: 30%">
                    '. $sum .'
                    </td>
                    <td>
                
                    </td>
                </tr>
            </tfoot>';
        

        return $content;

    }

    public function confirmOtherPayment(Request $request)
    {

        if(count(DB::table('tblpaymentdtl')->where('payment_id', $request->id)->get()) > 0)
        {
        
            $ref_no = DB::table('tblref_no')
                      ->join('tblpayment', 'tblref_no.process_type_id', 'tblpayment.process_type_id')
                      ->where('tblpayment.id', $request->id)
                      ->select('tblref_no.*','tblpayment.student_ic')->first();

            DB::table('tblref_no')->where('id', $ref_no->id)->update([
                'ref_no' => $ref_no->ref_no + 1
            ]);

            DB::table('tblpayment')->where('id', $request->id)->update([
                'process_status_id' => 2,
                'ref_no' => $ref_no->code . $ref_no->ref_no + 1
            ]);

            //check if newstudent & more than 250

            $student = DB::table('students')->where('ic', $ref_no->student_ic)->first();

            $alert = null;

            if($student->no_matric == null)
            {

                if($sum = DB::table('tblpayment')->where('student_ic', $student->ic)->whereNotIn('process_status_id', [1, 3])->sum('amount') >= 250)
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

                    $alert = 'No Matric has been updated!';

                }

            }

        }else{

            return ['message' => 'Please add payment charge details first!'];

        }

        return ['message' => 'Success', 'id' => $request->id, 'alert' => $alert];

    }

    public function incentive()
    {

        $data['session'] = DB::table('sessions')->get();

        return view('finance.package.incentive', compact('data'));

    }

    public function getIncentive()
    {

        $data['incentive'] = DB::table('tblincentive AS t1')
                             ->join('sessions AS t2_from', 't1.session_from', 't2_from.SessionID')
                             ->leftjoin('sessions AS t2_to', 't1.session_to', 't2_to.SessionID')
                             ->select('t1.*', 't2_from.SessionName AS from', 't2_to.SessionName AS to')
                             ->get();

        return view('finance.package.getIncentive', compact('data'));

    }

    public function storeIncentive(Request $request)
    {

        $data = json_decode($request->formData);

        if($data->from != null && $data->amount != null)
        {

            //$session = DB::table('sessions')
            //           ->whereBetween('SessionID', [$data->from, $data->to])
            //           ->get();

            //foreach($session as $ses)
            //{

            //    DB::table('tblincentive')->insert(
            //        'session'
            //    )

            //}

            DB::table('tblincentive')->insert([
                'session_from' => $data->from,
                'session_to' => $data->to,
                'amount' => $data->amount
            ]);

            return ["message"=>"Success"];

        }else{

            return ["message"=>"Please select all required field!"];

        }
    }

    public function getProgram(Request $request)
    {

        $data['registered'] = DB::table('tblprogramme')
                              ->join('tblincentive_program', 'tblprogramme.id', 'tblincentive_program.program_id')
                              ->where('tblincentive_program.incentive_id', $request->id)
                              ->select('tblprogramme.*')
                              ->get();

        $collection = collect($data['registered']);

        $data['unregistered'] = DB::table('tblprogramme')
                              ->whereNotIn('id', $collection->pluck('id'))
                              ->get();

        $data['id'] = $request->id;

        return view('finance.package.getProgram', compact('data'));
    }

    public function registerPRG(Request $request)
    {
   
        DB::table('tblincentive_program')->insert([
            'incentive_id' => $request->id,
            'program_id' => $request->prg
        ]);

        return response()->json($request->id);

    }

    public function unregisterPRG(Request $request)
    {

        DB::table('tblincentive_program')
        ->where([
            ['incentive_id', $request->id],
            ['program_id', $request->prg]
        ])->delete();

        return response()->json($request->id);

    }

    public function tabungkhas()
    {
        $data['package'] = DB::table('tblpackage')->get();

        $data['type'] = DB::table('tblprocess_type')
                        ->where('name', 'LIKE', '%TABUNG%')->get();

        //dd($data['type']);

        $data['session'] = DB::table('sessions')->orderBy('SessionID', 'desc')->get();

        return view('finance.package.tabungkhas', compact('data'));

    }

    public function getTabungkhas()
    {

        $data['tabungkhas'] = DB::table('tbltabungkhas AS t1')
                             ->join('sessions AS t2_intake', 't1.intake_id', 't2_intake.SessionID')
                             ->join('tblpackage', 't1.package_id', 'tblpackage.id')
                             ->join('tblprocess_type', 't1.process_type_id', 'tblprocess_type.id')
                             ->select('t1.*', 't2_intake.SessionName AS intake', 'tblpackage.name AS package', 'tblprocess_type.name AS type')
                             ->get();

        return view('finance.package.getTabungkhas', compact('data'));

    }

    public function storeTabungkhas(Request $request)
    {

        $data = json_decode($request->formData);

        if($data->intake != null)
        {

            //$session = DB::table('sessions')
            //           ->whereBetween('SessionID', [$data->from, $data->to])
            //           ->get();

            //foreach($session as $ses)
            //{

            //    DB::table('tbltabungkhas')->insert(
            //        'session'
            //    )

            //}

            DB::table('tbltabungkhas')->insert([
                'intake_id' => $data->intake,
                'package_id' => $data->package,
                'process_type_id' => $data->type,
                'amount' => $data->amount
            ]);

            return ["message"=>"Success"];

        }else{

            return ["message"=>"Please select all required field!"];

        }
    }

    public function getProgram2(Request $request)
    {

        $data['registered'] = DB::table('tblprogramme')
                              ->join('tbltabungkhas_program', 'tblprogramme.id', 'tbltabungkhas_program.program_id')
                              ->where('tbltabungkhas_program.tabungkhas_id', $request->id)
                              ->select('tblprogramme.*')
                              ->get();

        $collection = collect($data['registered']);

        $data['unregistered'] = DB::table('tblprogramme')
                              ->whereNotIn('id', $collection->pluck('id'))
                              ->get();

        $data['id'] = $request->id;

        return view('finance.package.getProgram', compact('data'));
    }

    public function registerPRG2(Request $request)
    {
   
        DB::table('tbltabungkhas_program')->insert([
            'tabungkhas_id' => $request->id,
            'program_id' => $request->prg
        ]);

        return response()->json($request->id);

    }

    public function unregisterPRG2(Request $request)
    {

        DB::table('tbltabungkhas_program')
        ->where([
            ['tabungkhas_id', $request->id],
            ['program_id', $request->prg]
        ])->delete();

        return response()->json($request->id);

    }

    public function insentifkhas()
    {
        // $data['package'] = DB::table('tblpackage')->get();

        $data['type'] = DB::table('tblprocess_type')
                        ->where('name', 'NOT LIKE', '%TABUNG%')->get();

        //dd($data['type']);

        $data['session'] = DB::table('sessions')->orderBy('SessionID', 'desc')->get();

        return view('finance.package.insentifkhas', compact('data'));

    }

    public function getInsentifkhas()
    {

        $data['insentifkhas'] = DB::table('tblinsentifkhas AS t1')
                             ->join('sessions AS t2_intake', 't1.intake_id', 't2_intake.SessionID')
                            //  ->join('tblpackage', 't1.package_id', 'tblpackage.id')
                             ->join('tblprocess_type', 't1.process_type_id', 'tblprocess_type.id')
                             ->select('t1.*', 't2_intake.SessionName AS intake', 'tblprocess_type.name AS type')
                             ->get();

        return view('finance.package.getInsentifkhas', compact('data'));

    }

    public function storeInsentifkhas(Request $request)
    {

        $data = json_decode($request->formData);

        if($data->intake != null)
        {

            //$session = DB::table('sessions')
            //           ->whereBetween('SessionID', [$data->from, $data->to])
            //           ->get();

            //foreach($session as $ses)
            //{

            //    DB::table('tblinsentifkhas')->insert(
            //        'session'
            //    )

            //}

            DB::table('tblinsentifkhas')->insert([
                'intake_id' => $data->intake,
                // 'package_id' => $data->package,
                'process_type_id' => $data->type,
                'amount' => $data->amount
            ]);

            return ["message"=>"Success"];

        }else{

            return ["message"=>"Please select all required field!"];

        }
    }

    public function getProgram3(Request $request)
    {

        $data['registered'] = DB::table('tblprogramme')
                              ->join('tblinsentifkhas_program', 'tblprogramme.id', 'tblinsentifkhas_program.program_id')
                              ->where('tblinsentifkhas_program.insentifkhas_id', $request->id)
                              ->select('tblprogramme.*')
                              ->get();

        $collection = collect($data['registered']);

        $data['unregistered'] = DB::table('tblprogramme')
                              ->whereNotIn('id', $collection->pluck('id'))
                              ->get();

        $data['id'] = $request->id;

        return view('finance.package.getProgram', compact('data'));
    }

    public function registerPRG3(Request $request)
    {
   
        DB::table('tblinsentifkhas_program')->insert([
            'insentifkhas_id' => $request->id,
            'program_id' => $request->prg
        ]);

        return response()->json($request->id);

    }

    public function unregisterPRG3(Request $request)
    {

        DB::table('tblinsentifkhas_program')
        ->where([
            ['insentifkhas_id', $request->id],
            ['program_id', $request->prg]
        ])->delete();

        return response()->json($request->id);

    }

    public function Payment()
    {
        $data['package'] = DB::table('tblpackage')->get();

        $data['type'] = DB::table('tblpayment_type')->get();

        $data['session'] = DB::table('sessions')->get();

        return view('finance.package.payment', compact('data'));

    }

    public function getPayment()
    {

        $data['payment'] = DB::table('tblpayment_package AS t1')
                             ->join('tblpackage', 't1.package_id', 'tblpackage.id')
                             ->join('tblpayment_type', 't1.payment_type_id', 'tblpayment_type.id')
                             ->select('t1.*', 'tblpackage.name AS package', 'tblpayment_type.name AS type')
                             ->get();
                           

        return view('finance.package.getPayment', compact('data'));

    }

    public function storePaymentPKG(Request $request)
    {

        $data = json_decode($request->formData);

        if($data->package != null && $data->type != null && $data->sem1 != null && $data->sem2 != null && $data->sem3 != null && $data->sem4 != null && $data->sem5 != null && $data->sem6 != null)
        {

            DB::table('tblpayment_package')->insert([
                'package_id' => $data->package,
                'payment_type_id' => $data->type,
                'semester_1' => $data->sem1,
                'semester_2' => $data->sem2,
                'semester_3' => $data->sem3,
                'semester_4' => $data->sem4,
                'semester_5' => $data->sem5,
                'semester_6' => $data->sem6
            ]);

            return ["message"=>"Success"];

        }else{

            return ["message"=>"Please select all required field!"];

        }
    }

    public function getProgramPayment(Request $request)
    {

        $data['registered'] = DB::table('tblprogramme')
                              ->join('tblpayment_program', 'tblprogramme.id', 'tblpayment_program.program_id')
                              ->join('sessions', 'tblpayment_program.intake_id', 'sessions.SessionID')
                              ->where('tblpayment_program.payment_package_id', $request->id)
                              ->select('tblprogramme.*', 'sessions.SessionName', 'tblpayment_program.id')
                              ->get();

        $data['unregistered'] = DB::table('tblprogramme')->get();

        $data['id'] = $request->id;

        return view('finance.package.getProgramPayment', compact('data'));
    }

    public function registerPRGPYM(Request $request)
    {
   
        DB::table('tblpayment_program')->insert([
            'payment_package_id' => $request->id,
            'program_id' => $request->prg,
            'intake_id' => $request->intake
        ]);

        return response()->json($request->id);

    }

    public function deletePRGPYM(Request $request)
    {

        DB::table('tblpayment_program')
        ->where([
            ['id', $request->prg]
        ])->delete();

        return response()->json($request->id);

    }

    public function sponsorPackage()
    {

        $data['package'] = DB::table('tblpackage')->get();

        $data['method'] = DB::table('tblpayment_type')->get();

        return view('finance.package.sponsorPackage', compact('data'));

    }

    public function getsponsorPackage()
    {

        $data['sponsorPackage'] = DB::table('tblpackage_sponsorship')
                                  ->join('students', 'tblpackage_sponsorship.student_ic', 'students.ic')
                                  ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                                  ->leftjoin('sessions', 'students.intake', 'sessions.SessionID')
                                  ->join('tblpackage', 'tblpackage_sponsorship.package_id', 'tblpackage.id')
                                  ->join('tblpayment_type', 'tblpackage_sponsorship.payment_type_id', 'tblpayment_type.id')
                                  ->where('students.status', 2)
                                  ->select('tblpackage_sponsorship.*', 'tblpackage.name AS package', 'tblpayment_type.name AS type', 
                                           'students.name AS student', 'students.ic', 'students.semester','tblprogramme.progcode', 'sessions.SessionName')
                                  ->get();

        return view('finance.package.getSponsorPackage', compact('data'));

    }

    public function storeSponsorPackage(Request $request)
    {

        $data = json_decode($request->formData);

        if($data->student != null && $data->package != null && $data->method != null && $data->amount != null)
        {

            DB::table('tblpackage_sponsorship')->insert([
                'student_ic' => $data->student,
                'package_id' => $data->package,
                'payment_type_id' => $data->method,
                'amount' => $data->amount,
                'add_staffID' => Auth::user()->ic,
                'add_date' => date('Y-m-d'),
                'mod_staffID' => Auth::user()->ic,
                'mod_date' => date('Y-m-d')
            ]);

            return ["message"=>"Success"];

        }else{

            return ["message"=>"Please select all required field!"];

        }
    }

    public function getEditPackage(Request $request)
    {

        $data['package'] = DB::table('tblpackage')->get();

        $data['method'] = DB::table('tblpayment_type')->get();

        $data['sponsorPackage'] = DB::table('tblpackage_sponsorship')->where('id', $request->id)->first();

        return view('finance.package.getEditPackage', compact('data'))->with('id', $request->id);

    }

    public function updateSponsorPackage(Request $request)
    {

        $data = json_decode($request->formData);

        if($data->package != null && $data->method != null && $data->amount != null)
        {

            DB::table('tblpackage_sponsorship')->where('id', $data->id)->update([
                'package_id' => $data->package,
                'payment_type_id' => $data->method,
                'amount' => $data->amount
            ]);

            return ["message"=>"Success"];

        }else{

            return ["message"=>"Please select all required field!"];

        }
    }

    public function deleteSponsorPackage(Request $request)
    {

        DB::table('tblpackage_sponsorship')->where('id', $request->id)->delete();

        return [ "message" => "success"];

    }

    public function cancelTransaction()
    {

        return view('finance.payment.receiptList');

    }

    public function cancelTransactionConfirm(Request $request)
    {
        if(in_array($request->typeID, [2,4,5]))
        {

            DB::table('tblclaim')->where('id', $request->receiptID)->update([
                'process_status_id' => 3,
                'termination_date' => date('Y-m-d'),
                'termination_reason' => $request->reason,
                'termination_staffID' => Auth::user()->ic
             ]);

        }else{

            DB::table('tblpayment')->where('id', $request->receiptID)->update([
               'process_status_id' => 3,
               'termination_date' => date('Y-m-d'),
               'termination_reason' => $request->reason,
               'termination_staffID' => Auth::user()->ic
            ]);

        }

        return back();

    }

    public function studentVoucher()
    {

        return view('finance.voucher.voucher');

    }

    public function getStudentVoucher(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['voucher'] = DB::table('tblstudent_voucher')
                           ->join('tblprocess_status', 'tblstudent_voucher.status', 'tblprocess_status.id')
                           ->select('tblstudent_voucher.*', 'tblprocess_status.name')
                           ->where('tblstudent_voucher.student_ic', $request->student)
                           ->get();

        $data['sum'] = DB::table('tblstudent_voucher')->where('tblstudent_voucher.student_ic', $request->student)->sum('amount');

        return  view('finance.voucher.voucherGetStudent', compact('data'));

    }

    public function storeVoucherDtl(Request $request)
    {

        $voucherDetail = $request->voucherDetail;

        $validator = Validator::make($request->all(), [
            'voucherDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $voucher = json_decode($voucherDetail);
                
                if($voucher->from != null && $voucher->to != null && $voucher->amount != null && $voucher->expired != null)
                {
                    $student = DB::table('students')->where('ic', $voucher->ic)->first();

                    $prefix = substr($voucher->from, 0, 1); // Get the prefix from the first user input

                    $start = (int) substr($voucher->from, 1); // Get the number from the first user input
                    $end = (int) substr($voucher->to, 1); // Get the number from the second user input

                    $result = []; // The array to store the series

                    for ($i = $start; $i <= $end; $i++) {
                        $formatted_number = str_pad($i, 4, '0', STR_PAD_LEFT);
                        $result[] = $prefix . $formatted_number;
                    }

                    $exists = [];
                    $doesNotExist = [];

                    foreach($result as $rslt)
                    {
                        $record = DB::table('tblstudent_voucher')
                        ->where([
                            ['no_voucher', $rslt]
                            ])->first();

                        if($record)
                        {

                            $exists[] = $rslt;

                        }else{

                            $doesNotExist[] = $rslt;

                        }


                    }

                    if(count($doesNotExist) <= 0)
                    {

                        return ["message" => "All Voucher is used!"];

                    }else{

                        foreach($doesNotExist as $vch)
                        {

                            DB::table('tblstudent_voucher')->insert([
                                'student_ic' => $voucher->ic,
                                'session_id' => $student->session,
                                'semester_id' => $student->semester,
                                'no_voucher' => $vch,
                                'amount' => $voucher->amount,
                                'status' => 1,
                                'redeem_date' => null,
                                'expiry_date' => $voucher->expired,
                                'staff_ic' => Auth::user()->ic,
                                'add_date' => date('Y-m-d')
                            ]);

                        }

                    }

                    $details = DB::table('tblstudent_voucher')
                            ->join('tblprocess_status', 'tblstudent_voucher.status', 'tblprocess_status.id')
                            ->select('tblstudent_voucher.*', 'tblprocess_status.name')
                            ->where([
                                ['tblstudent_voucher.student_ic', $voucher->ic]
                            ])
                            ->get();

                    $sum = DB::table('tblstudent_voucher')->where('tblstudent_voucher.student_ic', $voucher->ic)->sum('amount');

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th>
                                            No. Vouchar
                                        </th>
                                        <th>
                                            Amount
                                        </th>
                                        <th>
                                            Pickup Date
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($details as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->no_voucher .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->amount .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->pickup_date .'
                            </td>
                            <td style="width: 20%">
                            '. $dtl->name .'
                            </td>
                            <td>
                            <a class="btn btn-success btn-sm" href="#" onclick="claimVoucher('. $dtl->id .')">
                                <i class="ti-check">
                                </i>
                                Claim
                            </a>
                            <a class="btn btn-warning btn-sm" href="#" onclick="unclaimVoucher('. $dtl->id .')">
                                <i class="ti-close">
                                </i>
                                Un-Claim
                            </a>
                            ';
                            if($dtl->name != 'SAH')
                            {
                $content .= '<a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>';
                            }
                $content .= '</td>
                        </tr>
                        ';
                        }
                    $content .= '</tbody>';
                    $content .= '<tfoot>
                        <tr>
                            <td style="width: 1%">
                            
                            </td>
                            <td style="width: 15%">
                            TOTAL AMOUNT  :
                            </td>
                            <td style="width: 15%">
                            '. $sum .'
                            </td>
                            <td style="width: 15%">

                            </td>
                            <td style="width: 20%">
                            
                            </td>
                            <td>
                        
                            </td>
                        </tr>
                    </tfoot>';

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $content, 'exists' => $exists];

    }

    public function claimVoucherDtl(Request $request)
    {
        $student = DB::table('tblstudent_voucher')->where('id', $request->id)->first();

        DB::table('tblstudent_voucher')->where('id', $request->id)->update([
            'pickup_date' => date('Y-m-d')
        ]);

        $details = DB::table('tblstudent_voucher')
                            ->join('tblprocess_status', 'tblstudent_voucher.status', 'tblprocess_status.id')
                            ->select('tblstudent_voucher.*', 'tblprocess_status.name')
                            ->where([
                                ['tblstudent_voucher.student_ic', $student->student_ic]
                            ])
                            ->get();

        $sum = DB::table('tblstudent_voucher')->where('tblstudent_voucher.student_ic', $student->student_ic)->sum('amount');

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                No. Vouchar
                            </th>
                            <th>
                                Amountss
                            </th>
                            <th>
                                Pickup Date
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($details as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $dtl->no_voucher .'
                </td>
                <td style="width: 15%">
                '. $dtl->amount .'
                </td>
                <td style="width: 15%">
                '. $dtl->pickup_date .'
                </td>
                <td style="width: 20%">
                '. $dtl->name .'
                </td>
                <td>
                <a class="btn btn-success btn-sm" href="#" onclick="claimVoucher('. $dtl->id .')">
                    <i class="ti-check">
                    </i>
                    Claim
                </a>
                <a class="btn btn-warning btn-sm" href="#" onclick="unclaimVoucher('. $dtl->id .')">
                    <i class="ti-close">
                    </i>
                    Un-Claim
                </a>';
                if($dtl->name != 'SAH')
                {
    $content .= '<a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>';
                }
    $content .= '</td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
            <tr>
                <td style="width: 1%">
                
                </td>
                <td style="width: 15%">
                TOTAL AMOUNT  :
                </td>
                <td style="width: 15%">
                '. $sum .'
                </td>
                <td style="width: 15%">
                
                </td>
                <td style="width: 20%">
                
                </td>
                <td>
            
                </td>
            </tr>
        </tfoot>';
        

        return $content;

    }

    public function unclaimVoucherDtl(Request $request)
    {
        $student = DB::table('tblstudent_voucher')->where('id', $request->id)->first();

        DB::table('tblstudent_voucher')->where('id', $request->id)->update([
            'pickup_date' => null
        ]);

        $details = DB::table('tblstudent_voucher')
                            ->join('tblprocess_status', 'tblstudent_voucher.status', 'tblprocess_status.id')
                            ->select('tblstudent_voucher.*', 'tblprocess_status.name')
                            ->where([
                                ['tblstudent_voucher.student_ic', $student->student_ic]
                            ])
                            ->get();

        $sum = DB::table('tblstudent_voucher')->where('tblstudent_voucher.student_ic', $student->student_ic)->sum('amount');

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                No. Vouchar
                            </th>
                            <th>
                                Amount
                            </th>
                            <th>
                                Pickup Date
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($details as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $dtl->no_voucher .'
                </td>
                <td style="width: 15%">
                '. $dtl->amount .'
                </td>
                <td style="width: 15%">
                '. $dtl->pickup_date .'
                </td>
                <td style="width: 20%">
                '. $dtl->name .'
                </td>
                <td>
                <a class="btn btn-success btn-sm" href="#" onclick="claimVoucher('. $dtl->id .')">
                    <i class="ti-check">
                    </i>
                    Claim
                </a>
                <a class="btn btn-warning btn-sm" href="#" onclick="unclaimVoucher('. $dtl->id .')">
                    <i class="ti-close">
                    </i>
                    Un-Claim
                </a>';
                if($dtl->name != 'SAH')
                {
    $content .= '<a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>';
                }
    $content .= '</td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
            <tr>
                <td style="width: 1%">
                
                </td>
                <td style="width: 15%">
                TOTAL AMOUNT  :
                </td>
                <td style="width: 15%">
                '. $sum .'
                </td>
                <td style="width: 15%">
                
                </td>
                <td style="width: 20%">
                
                </td>
                <td>
            
                </td>
            </tr>
        </tfoot>';
        

        return $content;

    }

    public function deleteVoucherDtl(Request $request)
    {
        $student = DB::table('tblstudent_voucher')->where('id', $request->id)->first();

        DB::table('tblstudent_voucher')->where('id', $request->id)->delete();

        $details = DB::table('tblstudent_voucher')
                            ->join('tblprocess_status', 'tblstudent_voucher.status', 'tblprocess_status.id')
                            ->select('tblstudent_voucher.*', 'tblprocess_status.name')
                            ->where([
                                ['tblstudent_voucher.student_ic', $student->student_ic]
                            ])
                            ->get();

        $sum = DB::table('tblstudent_voucher')->where('tblstudent_voucher.student_ic', $student->student_ic)->sum('amount');

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                No. Vouchar
                            </th>
                            <th>
                                Amount
                            </th>
                            <th>
                                Pickup Date
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($details as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $dtl->no_voucher .'
                </td>
                <td style="width: 15%">
                '. $dtl->amount .'
                </td>
                <td style="width: 15%">
                '. $dtl->pickup_date .'
                </td>
                <td style="width: 20%">
                '. $dtl->name .'
                </td>
                <td>
                <a class="btn btn-success btn-sm" href="#" onclick="claimVoucher('. $dtl->id .')">
                    <i class="ti-check">
                    </i>
                    Claim
                </a>
                <a class="btn btn-warning btn-sm" href="#" onclick="unclaimVoucher('. $dtl->id .')">
                    <i class="ti-close">
                    </i>
                    Un-Claim
                </a>';
                if($dtl->name != 'SAH')
                {
    $content .= '<a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .')">
                        <i class="ti-trash">
                        </i>
                        Delete
                    </a>';
                }
    $content .= '</td>
            </tr>
            ';
            }
        $content .= '</tbody>';
        $content .= '<tfoot>
            <tr>
                <td style="width: 1%">
                
                </td>
                <td style="width: 15%">
                TOTAL AMOUNT  :
                </td>
                <td style="width: 15%">
                '. $sum .'
                </td>
                <td style="width: 15%">
                
                </td>
                <td style="width: 20%">
                
                </td>
                <td>
            
                </td>
            </tr>
        </tfoot>';
        

        return $content;

    }

    public function arrearsReport()
    {

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        $data['status'] = DB::table('tblstudent_status')->get();

        return view('finance.report.arrearsReport', compact('data'));

    }

    public function getArrearsReport(Request $request)
    {

        $filtersData = $request->filtersData;

        $validator = Validator::make($request->all(), [
            'filtersData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{

                $filter = json_decode($filtersData);

                if($filter->program == 'all')
                {

                    $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

                }else{

                    $data['program'] = DB::table('tblprogramme')->where('id', $filter->program)->get();
                    
                }

                foreach($data['program'] as $key => $prg)
                {

                    // Define a function to create the base query
                    $baseQuery = function () use ($filter, $prg) {
                        return DB::table('tblclaim')
                        ->join('students', 'tblclaim.student_ic', 'students.ic')
                        ->join('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
                        ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                        ->whereBetween('tblclaim.add_date', [$filter->from,$filter->to])
                        ->where([
                            ['tblclaim.program_id', $prg->id],
                            ['tblclaim.process_status_id', 2],
                        ])
                        ->when($filter->status != 'all', function ($query) use ($filter) {
                            // Only applies this where condition if $filter->status is not 'all'
                            return $query->where('students.status', '=', $filter->status);
                        });
                    };

                    $data['debt'][$key] = ($baseQuery)()->where([
                                            ['tblclaim.process_type_id', 2],
                                            ['tblstudentclaim.groupid', 1],
                                        ])
                                        ->sum('tblclaimdtl.amount');

                    $data['debtND'][$key] = ($baseQuery)()->where([
                                            ['tblclaim.process_type_id', 4],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1,4,5])
                                        ->sum('tblclaimdtl.amount');

                    $data['debtNK'][$key] = ($baseQuery)()->where([
                                            ['tblclaim.process_type_id', 5],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1,4,5])
                                        ->sum('tblclaimdtl.amount');

                    $baseQuery2 = function () use ($filter, $prg) {
                                            return DB::table('tblpayment')
                                            ->join('students', 'tblpayment.student_ic', 'students.ic')
                                            ->join('tblpaymentdtl', 'tblpayment.id', 'tblpaymentdtl.payment_id')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->whereBetween('tblpayment.add_date', [$filter->from,$filter->to])
                                            ->where([
                                                ['tblpayment.program_id', $prg->id],
                                                ['tblpayment.process_status_id', 2],
                                            ])
                                            ->when($filter->status != 'all', function ($query) use ($filter) {
                                                // Only applies this where condition if $filter->status is not 'all'
                                                return $query->where('students.status', '=', $filter->status);
                                            });
                                        };

                    $data['insentif'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 9],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['iNED'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 10],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1,5])
                                        ->sum('tblpaymentdtl.amount');

                    $data['unitiFund'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 12],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['biasiswa'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 13],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['uef'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 7],
                                            ['tblpayment.payment_sponsor_id', 8],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1,4,5])
                                        ->sum('tblpaymentdtl.amount');

                    $data['dc19'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 14],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['iMCO'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 15],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['iKKU'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 21],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['tkB40'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 16],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['tkM40'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 17],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['tkT20'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 18],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['tk'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 19],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['trB40'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 22],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['trM40'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 23],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['trT20'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 24],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['tr'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 25],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1])
                                        ->sum('tblpaymentdtl.amount');

                    $data['paymentNK'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 5],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1,4,5])
                                        ->sum('tblpaymentdtl.amount');

                    $data['dailyPayment'][$key] = ($baseQuery2)()
                                        ->whereIn('tblpayment.process_type_id', [1,8])
                                        ->whereIn('tblstudentclaim.groupid', [1,4,5])
                                        ->sum('tblpaymentdtl.amount');

                    $data['sponsor'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 7],
                                        ])
                                        ->where('tblpayment.payment_sponsor_id', '!=', 8)
                                        ->whereIn('tblstudentclaim.groupid', [1,4,5])
                                        ->sum('tblpaymentdtl.amount');

                    $data['refund'][$key] = ($baseQuery2)()->where([
                                            ['tblpayment.process_type_id', 6],
                                        ])
                                        ->whereIn('tblstudentclaim.groupid', [1,4,5])
                                        ->sum('tblpaymentdtl.amount');

                    $data['balance'][$key] = ($data['debt'][$key] + $data['debtND'][$key] + $data['debtNK'][$key]) - 
                                             (($data['insentif'][$key] + $data['iNED'][$key] + $data['unitiFund'][$key] + $data['biasiswa'][$key] + $data['uef'][$key] + $data['dc19'][$key] + $data['iMCO'][$key] + $data['iKKU'][$key] + $data['tkB40'][$key] + $data['tkM40'][$key] + $data['tkT20'][$key] + $data['tk'][$key] + $data['trB40'][$key] + $data['trM40'][$key] + $data['trT20'][$key] + $data['tr'][$key]) + 
                                             ($data['paymentNK'][$key] + $data['dailyPayment'][$key] + $data['sponsor'][$key]) + 
                                             ($data['refund'][$key]));

                    // $optimizedQuery = function () use ($filter, $prg) {
                    //     return DB::query()
                    //         ->select(DB::raw("
                    //             SUM(CASE WHEN tblpayment.process_type_id = 9 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS insentif,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 10 AND tblstudentclaim.groupid IN (1,5) THEN tblpaymentdtl.amount ELSE 0 END) AS iNED,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 12 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS unitiFund,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 13 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS biasiswa,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 7 AND tblpayment.payment_sponsor_id = 8 AND tblstudentclaim.groupid IN (1,4,5) THEN tblpaymentdtl.amount ELSE 0 END) AS uef,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 14 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS dc19,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 15 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS iMCO,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 21 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS iKKU,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 16 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS tkB40,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 17 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS tkM40,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 18 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS tkT20,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 19 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS tk,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 22 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS trB40,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 23 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS trM40,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 24 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS trT20,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 25 AND tblstudentclaim.groupid = 1 THEN tblpaymentdtl.amount ELSE 0 END) AS tr,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 5 AND tblstudentclaim.groupid IN (1,4,5) THEN tblpaymentdtl.amount ELSE 0 END) AS paymentNK,
                    //             SUM(CASE WHEN tblpayment.process_type_id IN (1,8) AND tblstudentclaim.groupid IN (1,4,5) THEN tblpaymentdtl.amount ELSE 0 END) AS dailyPayment,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 7 AND tblpayment.payment_sponsor_id != 8 AND tblstudentclaim.groupid IN (1,4,5) THEN tblpaymentdtl.amount ELSE 0 END) AS sponsor,
                    //             SUM(CASE WHEN tblpayment.process_type_id = 6 AND tblstudentclaim.groupid IN (1,4,5) THEN tblpaymentdtl.amount ELSE 0 END) AS refund,
                    //         "))
                    //         ->from('tblpayment')
                    //         ->join('students', 'tblpayment.student_ic', '=', 'students.ic')
                    //         ->join('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.claim_id')
                    //         ->join('tblstudentclaim', 'tblpaymentdtl.claim_package_id', '=', 'tblstudentclaim.id')
                    //         ->join('tblpayment', 'students.ic', '=', 'tblpayment.student_ic')
                    //         ->join('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
                    //         // Assuming tblpayment and tblpayment can be joined on a common condition
                    //         ->whereBetween('tblpayment.add_date', [$filter->from, $filter->to])
                    //         ->where('tblpayment.program_id', $prg->id)
                    //         ->where('tblpayment.process_status_id', 2)
                    //         ->when($filter->status != 'all', function ($query) use ($filter) {
                    //             return $query->where('students.status', '=', $filter->status);
                    //         });
                    // };

                    // // Execute the optimized query
                    // $data = ($optimizedQuery)()->first();

                    // DB::table('tblarrears_report')->insert([
                    //     'add_date' => date("Y-m-d H:i:s"),
                    //     'program_id' => $prg->id,
                    //     'YP' => $data['debt'][$key],
                    //     'ND' => $data['debtND'][$key],
                    //     'NK' => $data['debtNK'][$key],
                    //     'INS' => $data['insentif'][$key],
                    //     'IPI' => $data['iNED'][$key],
                    //     'UF' => $data['unitiFund'][$key],
                    //     'B' => $data['biasiswa'][$key],
                    //     'UEF' => $data['uef'][$key],
                    //     'DC19' => $data['dc19'][$key],
                    //     'IM3' => $data['iMCO'][$key],
                    //     'IKKU' => $data['iKKU'][$key],
                    //     'TKBKU' => $data['tkB40'][$key],
                    //     'YKMKU' => $data['tkM40'][$key],
                    //     'TKTKU' => $data['tkT20'][$key],
                    //     'TKKU' => $data['tk'][$key],
                    //     'TRBKU' => $data['trB40'][$key],
                    //     'TRMKU' => $data['trM40'][$key],
                    //     'TRTKU' => $data['trT20'][$key],
                    //     'RRKU' => $data['tr'][$key],
                    //     'NK2' => $data['paymentNK'][$key],
                    //     'PK' => $data['dailyPayment'][$key],
                    //     'BP' => $data['sponsor'][$key],
                    //     'BL' => $data['refund'][$key],
                    //     'BTU' => $data['balance'][$key],
                    // ]);

                }

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th>  
                                    </th>
                                    <th colspan="3">
                                        A Tuntutan
                                    </th>
                                    <th colspan="16">
                                        B Diskaun Pengajian
                                    </th>
                                    <th colspan="3">
                                        C Pengurangan Yuran
                                    </th>
                                    <th>
                                        D
                                    </th>
                                    <th >
                                    A-(B+C+D)
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        Program
                                    </th>
                                    <th>
                                        Yuran Pengajian (RM)
                                    </th>
                                    <th>
                                        Nota Debit (RM) 
                                    </th>
                                    <th>
                                        Nota Kredit (RM)
                                    </th>
                                    <th>
                                        Insentif Naik Semester (RM)
                                    </th>
                                    <th>
                                        Insentif Pendidikan iNED (RM)
                                    </th>
                                    <th>
                                        UNITI Fund (RM)
                                    </th>
                                    <th>
                                        Biasiswa (RM)
                                    </th>
                                    <th>
                                        Uniti Education Fund (RM)
                                    </th>
                                    <th>
                                        Diskaun Covid-19/Frontliners (RM)
                                    </th>
                                    <th>
                                        Insentif MCO 3.0 (RM)
                                    </th>
                                    <th>
                                        Insentif Khas Kolej UNITI (RM)
                                    </th>
                                    <th>
                                        Tabung Khas B40 Kolej UNITI (RM)
                                    </th>
                                    <th>
                                        Tabung Khas M40 Kolej UNITI (RM)
                                    </th>
                                    <th>
                                        Tabung Khas T20 Kolej UNITI (RM)
                                    </th>
                                    <th>
                                        Tabung Khas Kolej UNITI (RM)
                                    </th>
                                    <th>
                                        Tabung Rahmah B40 Kolej UNITI (RM)
                                    </th>
                                    <th>
                                        Tabung Rahmah M40 Kolej UNITI (RM)
                                    </th>
                                    <th>
                                        Tabung Rahmah T20 Kolej UNITI (RM)
                                    </th>
                                    <th>
                                        Rabung Rahmah Kolej UNITI (RM)
                                    </th>
                                    <th>
                                        Nota Kredit (RM)
                                    </th>
                                    <th>
                                        Penerimaan Kaunter (RM)
                                    </th>
                                    <th>
                                        Bayaran Penaja (RM)
                                    </th>
                                    <th>
                                        Bayaran Lebihan (RM)
                                    </th>
                                    <th>
                                        Baki Tunggakan Yuran (RM)
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">';

                // Assuming $data['debt'] is a numeric array
                $debtCollection = collect($data['debt']);

                // Calculate the sum using the sum method
                $debtTotal = $debtCollection->sum();

                $debtNDCollection = collect($data['debtND']);
                $debtNDTotal = $debtNDCollection->sum();
                
                $debtNKCollection = collect($data['debtNK']);
                $debtNKTotal = $debtNKCollection->sum();

                $insentifCollection = collect($data['insentif']);
                $insentifTotal = $insentifCollection->sum();

                $iNEDCollection = collect($data['iNED']);
                $iNEDTotal = $iNEDCollection->sum();

                $unitiFundCollection = collect($data['unitiFund']);
                $unitiFundTotal = $unitiFundCollection->sum();

                $biasiswaCollection = collect($data['biasiswa']);
                $biasiswaTotal = $biasiswaCollection->sum();

                $uefCollection = collect($data['uef']);
                $uefTotal = $uefCollection->sum();

                $dc19Collection = collect($data['dc19']);
                $dc19Total = $dc19Collection->sum();

                $iMCOCollection = collect($data['iMCO']);
                $iMCOTotal = $iMCOCollection->sum();

                $iKKUCollection = collect($data['iKKU']);
                $iKKUTotal = $iKKUCollection->sum();

                $tkB40Collection = collect($data['tkB40']);
                $tkB40Total = $tkB40Collection->sum();

                $tkM40Collection = collect($data['tkM40']);
                $tkM40Total = $tkM40Collection->sum();

                $tkT20Collection = collect($data['tkT20']);
                $tkT20Total = $tkT20Collection->sum();

                $tkCollection = collect($data['tk']);
                $tkTotal = $tkCollection->sum();

                $trB40Collection = collect($data['trB40']);
                $trB40Total = $trB40Collection->sum();

                $trM40Collection = collect($data['trM40']);
                $trM40Total = $trM40Collection->sum();

                $trT20Collection = collect($data['trT20']);
                $trT20Total = $trT20Collection->sum();

                $trCollection = collect($data['tr']);
                $trTotal = $trCollection->sum();

                $paymentNKCollection = collect($data['paymentNK']);
                $paymentNKTotal = $paymentNKCollection->sum();

                $dailyPaymentCollection = collect($data['dailyPayment']);
                $dailyPaymentTotal = $dailyPaymentCollection->sum();

                $sponsorCollection = collect($data['sponsor']);
                $sponsorTotal = $sponsorCollection->sum();

                $refundCollection = collect($data['refund']);
                $refundTotal = $refundCollection->sum();

                $balanceCollection = collect($data['balance']);
                $balanceTotal = $balanceCollection->sum();
                            
                foreach($data['program'] as $key => $prg){
                    //$registered = ($std->status == 'ACTIVE') ? 'checked' : '';

                    $content .= '
                    <tr>
                        <td>
                        '. $prg->progcode .'
                        </td>
                        <td>
                        '. $data['debt'][$key] .'
                        </td>
                        <td>
                        '. $data['debtND'][$key] .'
                        </td>
                        <td>
                        '. $data['debtNK'][$key] .'
                        </td>
                        <td>
                        '. $data['insentif'][$key] .'
                        </td>
                        <td>
                        '. $data['iNED'][$key] .'
                        </td>
                        <td>
                        '. $data['unitiFund'][$key] .'
                        </td>
                        <td>
                        '. $data['biasiswa'][$key] .'
                        </td>
                        <td>
                        '. $data['uef'][$key] .'
                        </td>
                        <td>
                        '. $data['dc19'][$key] .'
                        </td>
                        <td>
                        '. $data['iMCO'][$key] .'
                        </td>
                        <td>
                        '. $data['iKKU'][$key] .'
                        </td>
                        <td>
                        '. $data['tkB40'][$key] .'
                        </td>
                        <td>
                        '. $data['tkM40'][$key] .'
                        </td>
                        <td>
                        '. $data['tkT20'][$key] .'
                        </td>
                        <td>
                        '. $data['tk'][$key] .'
                        </td>
                        <td>
                        '. $data['trB40'][$key] .'
                        </td>
                        <td>
                        '. $data['trM40'][$key] .'
                        </td>
                        <td>
                        '. $data['trT20'][$key] .'
                        </td>
                        <td>
                        '. $data['tr'][$key] .'
                        </td>
                        <td>
                        '. $data['paymentNK'][$key] .'
                        </td>
                        <td>
                        '. $data['dailyPayment'][$key] .'
                        </td>
                        <td>
                        '. $data['sponsor'][$key] .'
                        </td>
                        <td>
                        '. $data['refund'][$key] .'
                        </td>
                        <td>
                        '. $data['balance'][$key] .'
                        </td>
                    </tr>
                    ';

                    // ob_flush();
                    }

                $content .= '</tbody>';

                $content .= '<tfoot>
                                <tr>
                                    <td>
                                        Total :
                                    </td>
                                    <td>
                                        '. $debtTotal .
                                    '</td>
                                    <td>
                                        '. $debtNDTotal .
                                    '</td>
                                    <td>
                                        '. $debtNKTotal .
                                    '</td>
                                    <td>
                                        '. $insentifTotal .
                                    '</td>
                                    <td>
                                        '. $iNEDTotal .
                                    '</td>
                                    <td>
                                        '. $unitiFundTotal .
                                    '</td>
                                    <td>
                                        '. $biasiswaTotal .
                                    '</td>
                                    <td>
                                        '. $uefTotal .
                                    '</td>
                                    <td>
                                        '. $dc19Total .
                                    '</td>
                                    <td>
                                        '. $iMCOTotal .
                                    '</td>
                                    <td>
                                        '. $iKKUTotal .
                                    '</td>
                                    <td>
                                        '. $tkB40Total .
                                    '</td>
                                    <td>
                                        '. $tkM40Total .
                                    '</td>
                                    <td>
                                        '. $tkT20Total .
                                    '</td>
                                    <td>
                                        '. $tkTotal .
                                    '</td>
                                    <td>
                                        '. $trB40Total .
                                    '</td>
                                    <td>
                                        '. $trM40Total .
                                    '</td>
                                    <td>
                                        '. $trT20Total .
                                    '</td>
                                    <td>
                                        '. $trTotal .
                                    '</td>
                                    <td>
                                        '. $paymentNKTotal .
                                    '</td>
                                    <td>
                                        '. $dailyPaymentTotal .
                                    '</td>
                                    <td>
                                        '. $sponsorTotal .
                                    '</td>
                                    <td>
                                        '. $refundTotal .
                                    '</td>
                                    <td>
                                        '. $balanceTotal .
                                    '</td>
                                </tr>
                            </tfoot>';
                
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

        return response()->json(['message' => 'Success', 'data' => $content]);

    }

    public function urReport()
    {

        return view('finance.report.urReport');

    }

    public function getUrReport(Request $request)
    {

        $filtersData = $request->filtersData;

        $validator = Validator::make($request->all(), [
            'filtersData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{

                $filter = json_decode($filtersData);

                $payment = DB::table('tblpayment')
                           ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
                           ->where('tblpayment.add_date', '>=', $filter->from)
                           ->where('tblpayment.add_date', '<=', $filter->to)
                           ->where('tblpayment.process_status_id', 2)
                           ->where('student_ic', '!=', null)
                           ->whereIn('tblpayment.process_type_id', [1,7])
                           ->select('tblpayment.*', 'tblprogramme.progcode')
                           ->get();


                foreach($payment as $key => $pym)
                {

                    $student[$key] = DB::table('students')
                               ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                               ->leftjoin('tbledu_advisor', 'tblstudent_personal.advisor_id', 'tbledu_advisor.id')
                               ->where('students.ic', $pym->student_ic)
                               ->select('students.name', 'students.ic', 'students.no_matric', 'students.id', 'tbledu_advisor.name AS advisor')
                               ->first();

                    $method[$key] = DB::table('tblpaymentmethod')
                              ->leftjoin('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                              ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                              ->where('tblpaymentmethod.payment_id', $pym->id)
                              ->select('tblpayment_method.name', 'tblpayment_bank.code', 'tblpaymentmethod.no_document', 'tblpaymentmethod.amount')
                              ->get();

                }



                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Receipt No.
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        IC No.
                                    </th>
                                    <th>
                                        Matric No.
                                    </th>
                                    <th>
                                        Student ID No.
                                    </th>
                                    <th>
                                        Payment Method
                                    </th>
                                    <th>
                                        Bank
                                    </th>
                                    <th>
                                        Document No.
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                        Total
                                    </th>
                                    <th>
                                        Program
                                    </th>
                                    <th>
                                        EA
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">';
                $total = 0;
                
                foreach($payment as $key => $pym){
                    //$registered = ($std->status == 'ACTIVE') ? 'checked' : '';

                    $studentID = '';

                    if($student[$key]->id)
                    {

                        $currentLength = strlen($student[$key]->id);
                        $newLength = $currentLength + 1;
                        $studentID = str_pad($student[$key]->id, $newLength, '1', STR_PAD_LEFT);

                    }

                    $content .= '
                    <tr>
                        <td>
                        '. $key+1 .'
                        </td>
                        <td>
                        '. $pym->ref_no .'
                        </td>
                        <td>
                        '. $pym->date .'
                        </td>
                        <td>
                        '. ($student[$key]->name ?? '') .'
                        </td>
                        <td>
                        '. ($student[$key]->ic ?? '') .'
                        </td>
                        <td>
                        '. ($student[$key]->no_matric ?? '') .'
                        </td>
                        <td>
                        '. $studentID .'
                        </td>';

                    $content .= '<td>';
                    foreach ($method[$key] as $key2 => $mtd) {
                        $content .= "<div>{$mtd->name}</div>";
                    }
                    $content .= '</td>';

                    $content .= '<td>';
                    foreach ($method[$key] as $key2 => $mtd) {
                        $content .= "<div>{$mtd->code}</div>";
                    }
                    $content .= '</td>';

                    $content .= '<td>';
                    foreach ($method[$key] as $key2 => $mtd) {
                        $content .= "<div>{$mtd->no_document}</div>";
                    }
                    $content .= '</td>';

                    $content .= '<td>';
                    foreach ($method[$key] as $key2 => $mtd) {
                        $total += $mtd->amount;

                        $content .= "<div>{$mtd->amount}</div>";
                    }
                    $content .= '</td>';

                    $content .= '<td>
                        '. $pym->amount .'
                        </td>
                        <td>
                        '. $pym->progcode .'
                        </td>
                        <td>
                        '. ($student[$key]->advisor ?? '') .'
                        </td>
                    </tr>
                    ';
                    }

                $content .= '</tbody>';

                $content .= '<tfoot>
                                <tr>
                                    <td colspan="10">
                                        Total :
                                    </td>
                                    <td>
                                        '. $total .
                                    '</td>
                                </tr>
                            </tfoot>';
                
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

        return response()->json(['message' => 'Success', 'data' => $content]);

    }

    public function sponsorReport()
    {

        $data['sponsor'] = DB::table('tblsponsor_library')->get();

        return view('finance.sponsorship.sponsorReport', compact('data'));

    }

    public function sponsorGetReport(Request $request)
    {

        $filtersData = $request->filtersData;

        $validator = Validator::make($request->all(), [
            'filtersData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{

                $filter = json_decode($filtersData);

                $data['result'] = DB::table('tblpayment')
                                  ->join('tblpaymentmethod','tblpayment.id','tblpaymentmethod.payment_id')
                                  ->select('tblpayment.*', 'tblpaymentmethod.no_document',
                                            DB::raw('(SELECT SUM(x.amount) FROM tblpayment as x WHERE x.sponsor_id = tblpayment.id AND x.process_status_id = 2) as used_amount'))
                                  ->where('tblpayment.process_status_id', 2)
                                  ->where('tblpayment.payment_sponsor_id', $filter->sponsor)
                                  ->groupBy('tblpayment.id', 'tblpayment.date', 'tblpayment.ref_no')
                                  ->orderBy('tblpayment.date')
                                  ->get();

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th>
                                    #
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Reference No.
                                    </th>
                                    <th>
                                        Voucher No.
                                    </th>
                                    <th>
                                        Total Payment
                                    </th>
                                    <th>
                                        Distributed Amount
                                    </th>
                                    <th style="text-align: center;">
                                        Student List
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">';
                            
                foreach($data['result'] as $key => $rst){
                    //$registered = ($std->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                    <tr>
                        <td>
                        '. $key+1 .'
                        </td>
                        <td>
                        '. $rst->date .'
                        </td>
                        <td>
                        '. $rst->ref_no .'
                        </td>
                        <td>
                        '. $rst->no_document .'
                        </td>
                        <td>
                        '. $rst->amount .'
                        </td>
                        <td>
                        '. $rst->used_amount .'
                        </td>
                        <td style="text-align: center;">
                            <a class="btn btn-info btn-sm" href="/finance/sponsorship/payment/report/showReportStudent?id='. $rst->id .'">
                                Display
                            </a>
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

    public function showReportStudent(Request $request)
    {

        $data['info'] = DB::table('tblsponsor_library')
                        ->join('tblpayment','tblsponsor_library.id','tblpayment.payment_sponsor_id')
                        ->join('tblpaymentmethod','tblpayment.id','tblpaymentmethod.payment_id')
                        ->where('tblpayment.id', $request->id)
                        ->select(DB::raw('CONCAT(tblsponsor_library.code,"-",tblsponsor_library.name) as sponsor'),'tblpaymentmethod.no_document')
                        ->first();

        $data['students'] = DB::table('students')
                            ->join('tblpayment','students.ic','tblpayment.student_ic')
                            ->join('tblprogramme','students.program','tblprogramme.id')
                            ->where([
                                ['tblpayment.process_status_id', 2],
                                ['tblpayment.sponsor_id', $request->id]
                            ])
                            ->select('students.*','tblprogramme.progcode','tblpayment.amount')
                            ->orderBy('students.name')
                            ->get();

        $data['totalPayment'] = [];

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

    

        foreach($data['program'] as $key2 => $prg)
        {
            
            $data['students2'] = DB::table('students')
                            ->join('tblpayment','students.ic','tblpayment.student_ic')
                            ->join('tblprogramme','students.program','tblprogramme.id')
                            ->where([
                                ['tblpayment.process_status_id', 2],
                                ['tblpayment.sponsor_id', $request->id],
                                ['students.program', $prg->id]
                            ])
                            ->select('students.*','tblprogramme.progcode','tblpayment.amount')
                            ->orderBy('students.name')
                            ->get();

            $data['totalPayment'][$key2] = collect($data['students2'])->sum('amount');

        }

        //dd($data['totalPayment']);

        if(isset($request->print))
        {

            return view('finance.sponsorship.printSponsorStudentReport', compact('data'));

        }else{

            return view('finance.sponsorship.sponsorStudentReport', compact('data'));

        }

    }

    public function PTPTNReport()
    {

        $data['semester'] = DB::table('semester')->get();

        $data['session'] = DB::table('sessions')->where('Status', 'ACTIVE')->get();

        $data['package'] = ['FULL', '75%', '50%', 'SENDIRI'];

        $data['total'] = [];

        foreach($data['semester'] as $key => $sm)
        {

            foreach($data['package'] as $key2 => $pcg)
            {

                if($pcg == 'FULL')
                {

                    $packageID = [6,7,8,9,10];

                }elseif($pcg == '75%'){

                    $packageID = [2,11];

                }elseif($pcg == '50%'){

                    $packageID = [3];

                }elseif($pcg == 'SENDIRI'){

                    $packageID = [1,4,5];

                }

                foreach($data['session'] as $key3 => $ssn)
                {

                    $data['total'][$key][$key2][$key3] = DB::table('tblpackage_sponsorship')
                    ->join('students', 'tblpackage_sponsorship.student_ic', 'students.ic')
                    ->join('tblpackage', 'tblpackage_sponsorship.package_id', 'tblpackage.id')
                    ->where([
                        ['students.session', $ssn->SessionID],
                        ['students.semester', $sm->id],
                        ['students.status', 2]
                    ])
                    ->whereIn('tblpackage.id', $packageID)
                    ->count();

                    if($pcg == 'FULL')
                    {

                        $data['amount'][$key][$key2][$key3] = $data['total'][$key][$key2][$key3] * 3900;

                    }elseif($pcg == '75%'){

                        $data['amount'][$key][$key2][$key3] = $data['total'][$key][$key2][$key3] * 2450;

                    }elseif($pcg == '50%'){

                        $data['amount'][$key][$key2][$key3] = $data['total'][$key][$key2][$key3] * 1600;

                    }elseif($pcg == 'SENDIRI'){

                        $data['amount'][$key][$key2][$key3] = 0;

                    }

                }

            }

        }

        //dd($data['total']);

        return view('finance.sponsorship.ptptn_report.ptptnReport', compact('data'));

    }

    public function claimLog()
    {

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        $data['status'] = DB::table('tblstudent_status')->get();

        return view('finance.debt.claim_log.claimLog', compact('data'));

    }

    public function getClaimLog(Request $request)
    {

        if($request->student)
        {

            $data['student'] = DB::table('students')
                        ->where('ic', $request->student)
                        ->select('name', 'ic', 'no_matric')
                        ->get();

        }else{

            $student = DB::table('students');

            if($request->program != 'all')
            {

                $student->where('program', $request->program);

            }

            if($request->status != 'all')
            {

                $student->where('status', $request->status);

            }

            $data['student'] = $student->select('name', 'ic', 'no_matric')->get();
            
        }

        foreach($data['student'] as $key => $std)
        {

            $data['latest'][$key] = DB::table('student_payment_log')
                                       ->where('student_payment_log.student_ic', $std->ic)
                                       ->orderBy('date_of_payment', 'DESC')
                                       ->first();

            $data['payment'][$key] = DB::table('tblpayment')
                        ->select('tblpayment.add_date', DB::raw('SUM(tblpaymentdtl.amount) as amount'), DB::raw('DATEDIFF(CURDATE(), tblpayment.add_date) as days'))
                        ->leftjoin('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
                        ->leftjoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', '=', 'tblstudentclaim.id')
                        ->where('tblpayment.student_ic', $std->ic)
                        ->where('tblpayment.process_status_id', 2)
                        ->whereIn('tblpayment.process_type_id', [1])
                        ->whereIn('tblstudentclaim.groupid', [1])
                        ->groupBy('tblpayment.id')
                        ->orderBy('tblpayment.add_date', 'desc')
                        ->limit(1)
                        ->get();

            //block D

            $data['current_balance'][$key] = 0.00;

            $data['pk_balance'][$key] = 0.00;

            $data['total_balance'][$key] = 0.00;

            $record = DB::table('tblpaymentdtl')
            ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
            ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
            ->where([
                ['tblpayment.student_ic', $std->ic],
                ['tblpayment.process_status_id', 2], 
                ['tblstudentclaim.groupid', 1], 
                ['tblpaymentdtl.amount', '!=', 0]
                ])
            ->select(DB::raw("'payment' as source"), 'tblprocess_type.name AS process', 'tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 
            'tblpaymentdtl.amount',
            'tblpayment.process_type_id', 'tblprogramme.progcode AS program', DB::raw('NULL as remark'));

            $data['record'][$key] = DB::table('tblclaimdtl')
            ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
            ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
            ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
            ->where([
                ['tblclaim.student_ic', $std->ic],
                ['tblclaim.process_status_id', 2],  
                ['tblstudentclaim.groupid', 1],
                ['tblclaimdtl.amount', '!=', 0]
                ])
            ->unionALL($record)
            ->select(DB::raw("'claim' as source"), 'tblprocess_type.name AS process', 'tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 
            'tblclaimdtl.amount',
            'tblclaim.process_type_id', 'tblprogramme.progcode AS program', 'tblclaim.remark')
            ->orderBy('date')
            ->get();

            $val = 0;

            foreach($data['record'][$key] as $key2 => $req)
            {

                if(array_intersect([2,3,4,5,11], (array) $req->process_type_id) && $req->source == 'claim')
                {

                    $val += $req->amount;

                }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id) && $req->source == 'payment')
                {

                    $val -= $req->amount;

                }

            }   

            $data['sum3'] = $val;

            //TUNGGAKAN KESELURUHAN

            $data['current_balance'][$key] = $data['sum3'];

            $data['total_balance'][$key] = $data['current_balance'][$key];

            //TUNGGAKAN SEMASA

            $package = DB::table('tblpackage_sponsorship')->where('student_ic', $request->student)->first();

            if($package != null)
            {

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                {

                    $discount = abs(DB::table('tblclaim')
                                ->join('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
                                ->where([
                                    ['tblclaim.student_ic', $request->student],
                                    ['tblclaim.process_type_id', 5],
                                    ['tblclaim.process_status_id', 2],
                                    ['tblclaim.remark', 'LIKE', '%Diskaun Yuran Kediaman%']
                                ])->sum('tblclaimdtl.amount'));

                }else{

                    $discount = 0;
                    
                }

                if($package->package_id == 5)
                {

                    $data['current_balance'][$key] = $data['sum3'];

                }else{

                    if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                    {

                        if($data['sum3'] <= ($package->amount - $discount))
                        {

                            $data['current_balance'][$key] = 0.00;

                            $data['total_balance'][$key] = 0.00;

                        }elseif($data['sum3'] > ($package->amount - $discount))
                        {

                            $data['current_balance'][$key] = $data['sum3'] - ($package->amount - $discount);

                        }

                    }

                }

                //TNUGGAKAN PEMBIAYAAN KHAS

                $stddetail = DB::table('students')->where('ic', $request->student)->select('program', 'semester')->first();

                if($stddetail->program == 7 || $stddetail->program == 8)
                {

                    if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                    {

                        if($data['current_balance'][$key] == 0.00)
                        {

                            $data['pk_balance'][$key] = $data['sum3'];

                        }else{

                            $data['pk_balance'][$key] = ($package->amount - $discount);

                        }

                    }

                }else
                {

                    if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                    {

                        if($data['current_balance'][$key] == 0.00)
                        {

                            $data['pk_balance'][$key] = $data['sum3'];

                        }else{

                            $data['pk_balance'][$key] = ($package->amount - $discount);

                        }

                    }

                }

            }else{

                $data['current_balance'][$key] = 0.00;

                $data['pk_balance'][$key] = 0.00;

            }   

        }


        return view('finance.debt.claim_log.claimLogGetStudent', compact('data'));

    }

    public function studentClaimLog()
    {

        //A

        $data['student'] = DB::table('students')
        ->select('students.name', 'students.ic', DB::raw("CONCAT(tblprogramme.progcode, ' - ', tblprogramme.progname) as program"), 'students.no_matric',
        DB::raw("CONCAT(tblstudent_address.address1, ',', tblstudent_address.address2, ',', tblstudent_address.address3, ',', tblstudent_address.city, ',', tblstudent_address.postcode, ',', tblstate.state_name) as address"), 'tblstudent_personal.no_tel', 'students.email')
        ->leftjoin('tblprogramme', 'students.program', '=', 'tblprogramme.id')
        ->leftjoin('tblstudent_address', 'students.ic', '=', 'tblstudent_address.student_ic')
        ->leftjoin('tblstudent_personal', 'students.ic', '=', 'tblstudent_personal.student_ic')
        ->leftjoin('tblstate', 'tblstudent_address.state_id', '=', 'tblstate.id')
        ->where('students.ic', request()->ic)
        ->first();

        //B

        $data['sponsorship'] = DB::table('students')
        ->select('tblpackage.name as package_name', 'tblpayment_type.name as payment_type_name', 'tblpackage_sponsorship.amount')
        ->leftjoin('tblpackage_sponsorship', 'students.ic', '=', 'tblpackage_sponsorship.student_ic')
        ->leftjoin('tblpackage', 'tblpackage_sponsorship.package_id', '=', 'tblpackage.id')
        ->leftjoin('tblpayment_type', 'tblpackage_sponsorship.payment_type_id', '=', 'tblpayment_type.id')
        ->where('students.ic', request()->ic)
        ->first();

        //C

        $data['waris'] = DB::table('students')
        ->select('tblstudent_waris.name', 'tblstudent_waris.phone_tel', 'tblstudent_waris.home_tel')
        ->join('tblstudent_waris', 'students.ic', '=', 'tblstudent_waris.student_ic')
        ->where('students.ic', request()->ic)
        ->get();

        //D

        $data['current_balance'] = 0.00;

        $data['pk_balance'] = 0.00;

        $data['total_balance'] = 0.00;

        $record = DB::table('tblpaymentdtl')
            ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
            ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
            ->where([
                ['tblpayment.student_ic', request()->ic],
                ['tblpayment.process_status_id', 2], 
                ['tblstudentclaim.groupid', 1], 
                ['tblpaymentdtl.amount', '!=', 0]
                ])
            ->select(DB::raw("'payment' as source"), 'tblprocess_type.name AS process', 'tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 
            'tblpaymentdtl.amount',
            'tblpayment.process_type_id', 'tblprogramme.progcode AS program', DB::raw('NULL as remark'));

            $data['record'] = DB::table('tblclaimdtl')
            ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
            ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
            ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
            ->where([
                ['tblclaim.student_ic', request()->ic],
                ['tblclaim.process_status_id', 2],  
                ['tblstudentclaim.groupid', 1],
                ['tblclaimdtl.amount', '!=', 0]
                ])
            ->unionALL($record)
            ->select(DB::raw("'claim' as source"), 'tblprocess_type.name AS process', 'tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 
            'tblclaimdtl.amount',
            'tblclaim.process_type_id', 'tblprogramme.progcode AS program', 'tblclaim.remark')
            ->orderBy('date')
            ->get();

            $val = 0;

            foreach($data['record'] as $key2 => $req)
            {

                if(array_intersect([2,3,4,5,11], (array) $req->process_type_id) && $req->source == 'claim')
                {

                    $val += $req->amount;

                }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id) && $req->source == 'payment')
                {

                    $val -= $req->amount;

                }

            }   

            $data['sum3'] = $val;

        //TUNGGAKAN KESELURUHAN

        $data['current_balance'] = $data['sum3'];

        $data['total_balance'] = $data['current_balance'];

        $data['pk_balance'] = 0.00;

        //TUNGGAKAN SEMASA

        $package = DB::table('tblpackage_sponsorship')->where('student_ic', request()->ic)->first();

        if($package != null)
        {

            if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
            {

                $discount = abs(DB::table('tblclaim')
                            ->join('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
                            ->where([
                                ['tblclaim.student_ic', request()->ic],
                                ['tblclaim.process_type_id', 5],
                                ['tblclaim.process_status_id', 2],
                                ['tblclaim.remark', 'LIKE', '%Diskaun Yuran Kediaman%']
                            ])->sum('tblclaimdtl.amount'));

            }else{

                $discount = 0;
                
            }

            if($package->package_id == 5)
            {

                $data['current_balance'] = $data['sum3'];

            }else{

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                {

                    if($data['sum3'] <= ($package->amount - $discount))
                    {

                        $data['current_balance'] = 0.00;

                        $data['total_balance'] = 0.00;

                    }elseif($data['sum3'] > ($package->amount - $discount))
                    {

                        $data['current_balance'] = $data['sum3'] - ($package->amount - $discount);

                    }

                }

            }

            //TNUGGAKAN PEMBIAYAAN KHAS

            $stddetail = DB::table('students')->where('ic', request()->ic)->select('program', 'semester')->first();

            if($stddetail->program == 7 || $stddetail->program == 8)
            {

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                {

                    if($data['current_balance'] == 0.00)
                    {

                        $data['pk_balance'] = $data['sum3'];

                    }else{

                        $data['pk_balance'] = ($package->amount - $discount);

                    }

                }

            }else
            {

                if($package->payment_type_id == 3 || $package->payment_type_id == 11 || $package->payment_type_id == 14)
                {

                    if($data['current_balance'] == 0.00)
                    {

                        $data['pk_balance'] = $data['sum3'];

                    }else{

                        $data['pk_balance'] = ($package->amount - $discount);

                    }

                }

            }

        }else{

            $data['pk_balance'] = 0.00;

        }

        $data['total_all'] = $data['current_balance'] + $data['pk_balance'];

        //E

        $data['payment'] = DB::table('tblpayment')
        ->select('tblpayment.add_date', DB::raw('SUM(tblpaymentdtl.amount) as amount'))
        ->join('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
        ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', '=', 'tblstudentclaim.id')
        ->where('tblpayment.student_ic', request()->ic)
        ->where('tblpayment.process_status_id', 2)
        ->whereIn('tblpayment.process_type_id', [1])
        ->whereIn('tblstudentclaim.groupid', [1])
        ->groupBy('tblpayment.add_date')
        ->orderBy('tblpayment.add_date', 'desc')
        ->limit(1)
        ->get();

        //F

        $data['note'] = DB::table('log_note')->get();

        //G

        $data['log'] = DB::table('students')
        ->join('student_payment_log', 'students.ic', '=', 'student_payment_log.student_ic')
        ->select('student_payment_log.id','student_payment_log.date_of_call', 'student_payment_log.date_of_payment', 'student_payment_log.amount', 'student_payment_log.note')
        ->where('students.ic', '=', request()->ic)
        ->orderBy('student_payment_log.date_of_call', 'DESC')
        ->get();



        return view('finance.debt.claim_log.studentClaimLog', compact('data'));

    }

    public function storeNote(Request $request)
    {

        $data = $request->validate([
            'note' => ['required','string'],
        ]);

        DB::table('log_note')->insert([
            'name' => $data['note']
        ]);

        return back();

    }

    public function storeStudentLog(Request $request)
    {

        $data = $request->validate([
            'date1' => ['required'],
            'date2' => ['required'],
            'payment' => []
        ]);

        // Convert the array to a comma-separated string
        $notesString = ($request->note) ? implode(',', $request->note) : '';

        //dd($notesString);

        DB::table('student_payment_log')->insert([
            'student_ic' => $request->ic,
            'date_of_call' => $data['date1'],
            'date_of_payment' => $data['date2'],
            'amount' => $data['payment'],
            'note' => $notesString,
        ]);

        return redirect()->route('finance.studentClaimLog', ['ic' => $request->ic]);

    }

    public function deleteStudentLog(Request $request)
    {

        DB::table('student_payment_log')->where('id', $request->id)->delete();

        return [ "message" => "success"];

    }

    public function collectionReport()
    {
                           
        return view('finance.debt.collection_report.collectionReport');

    }

    public function getCollectionReport(Request $request)
    {

        //A

        $data['student'] = DB::table('student_payment_log')
                           ->join('students', 'student_payment_log.student_ic', 'students.ic')
                           ->whereBetween('student_payment_log.date_of_payment', [$request->from, $request->to])
                           ->select('students.name', 'students.ic', 'students.no_matric')
                           ->groupBy('students.ic')
                           ->get();
                        
        

        foreach($data['student'] as $key => $std)
        {

            //D

            $data['latest'][$key] = DB::table('student_payment_log')
                              ->orderBy('date_of_payment', 'DESC')
                              ->where('student_payment_log.student_ic', $std->ic)
                              ->first();

            //B

            $data['payments'][$key] = DB::table('tblpayment')
                                      ->join('tblpaymentdtl', 'tblpayment.id', 'tblpaymentdtl.payment_id')
                                      ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                      ->where('tblpayment.student_ic', $std->ic)
                                      ->where('tblpayment.process_status_id', 2)
                                      ->where('tblpayment.process_type_id', 1)
                                      ->where('tblstudentclaim.groupid', 1)
                                      ->whereBetween('tblpayment.add_date', [$request->from, $request->to])
                                      ->groupBy('tblpayment.student_ic')
                                      ->orderBy('tblpayment.add_date')
                                      ->select('tblpayment.add_date as payment_date', DB::raw('SUM(tblpaymentdtl.amount) as amount'))
                                      ->get();

            //D

            $data['total_balance'][$key] = 0.00;

            $record = DB::table('tblpaymentdtl')
            ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
            ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
            ->where([
                ['tblpayment.student_ic', $std->ic],
                ['tblpayment.process_status_id', 2], 
                ['tblstudentclaim.groupid', 1], 
                ['tblpaymentdtl.amount', '!=', 0]
                ])
            ->select(DB::raw("'payment' as source"), 'tblprocess_type.name AS process', 'tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progcode AS program', DB::raw('NULL as remark'));

            $data['record'][$key] = DB::table('tblclaimdtl')
            ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
            ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
            ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
            ->where([
                ['tblclaim.student_ic', $std->ic],
                ['tblclaim.process_status_id', 2],  
                ['tblstudentclaim.groupid', 1],
                ['tblclaimdtl.amount', '!=', 0]
                ])
            ->unionALL($record)
            ->select(DB::raw("'claim' as source"), 'tblprocess_type.name AS process', 'tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progcode AS program', 'tblclaim.remark')
            ->orderBy('date')
            ->get();

            $data['total'][$key] = 0;

            foreach($data['record'][$key] as $keys => $req)
            {

                if(array_intersect([2,3,4,5,11], (array) $req->process_type_id) && $req->source == 'claim')
                {

                    $data['total'][$key] += $req->amount;
                    
                }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id) && $req->source == 'payment')
                {

                    $data['total'][$key] -= $req->amount;

                }

            }

            $data['sum3'] = end($data['total']);

            //TUNGGAKAN KESELURUHAN

            $data['total_balance'][$key] = $data['sum3'];

        }

        return view('finance.debt.collection_report.collectionReportGetStudent', compact('data'));

    }

    public function collectionExpectReport()
    {
                           
        return view('finance.debt.collection_expectation_report.collection2Report');

    }

    public function getCollectionExpectReport(Request $request)
    {

        //A

        // $data['student'] = DB::table('student_payment_log')
        //                    ->join('students', 'student_payment_log.student_ic', 'students.ic')
        //                    ->whereBetween('student_payment_log.date_of_payment', [$request->from, $request->to])
        //                    ->select('students.name', 'students.ic', 'students.no_matric')
        //                    ->groupBy('students.ic')
        //                    ->get();

        $students = DB::table('tblpayment')
                           ->join('students', 'tblpayment.student_ic', 'students.ic')
                           ->whereBetween('tblpayment.add_date', [$request->from, $request->to])
                           ->where('students.semester', '!=', 1)
                           ->where('tblpayment.process_type_id', 1)
                           ->where('tblpayment.process_status_id', 2)
                           ->where('tblpayment.sponsor_id', null)
                           ->select('students.name', 'students.ic', 'students.no_matric', 'tblpayment.id AS pym_id', 'tblpayment.add_date')
                           ->get();

        $filteredStudents = [];

        foreach ($students as $student) {
            $status = DB::table('tblstudent_log')
                        ->join('tblstudent_status', 'tblstudent_log.status_id', '=', 'tblstudent_status.id')
                        ->where('tblstudent_log.student_ic', $student->ic)
                        ->where('tblstudent_log.date', '<=', $student->add_date)
                        ->orderBy('tblstudent_log.id', 'desc')
                        ->select('tblstudent_status.id')
                        ->first();
            
            if ($status && $status->id == 8) {
                $filteredStudents[] = $student;
            }
        }
        
        $data['student'] = collect($filteredStudents);
                        
        

        foreach($data['student'] as $key => $std)
        {

                $data['payments'][] = DB::table('tblpayment')
                                        ->join('tblpaymentdtl', 'tblpayment.id', 'tblpaymentdtl.payment_id')
                                        ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                        ->where('tblpayment.student_ic', $std->ic)
                                        ->where('tblpayment.id', $std->pym_id)
                                        ->whereBetween('tblpayment.add_date', [$request->from, $request->to])
                                        ->where(function ($query){
                                            $query->where('tblpayment.process_type_id', 1);
                                            $query->orWhere('tblpayment.process_type_id', 8);
                                        })
                                        ->where('tblpayment.process_status_id', 2)
                                        ->where('tblstudentclaim.groupid', 1)
                                        ->orderBy('tblpayment.add_date')
                                        ->select('tblpayment.add_date as payment_date', DB::raw('SUM(tblpaymentdtl.amount) as amount'))
                                        ->get();

                //B2

                $data['latest'][$key] = DB::table('student_payment_log')
                                        ->where('student_payment_log.student_ic', $std->ic)
                                        ->whereBetween('student_payment_log.date_of_payment', [$request->from, $request->to])
                                        ->orderBy('date_of_payment', 'DESC')
                                        ->first();

                //D

                $data['total_balance'][$key] = 0.00;

                $record = DB::table('tblpaymentdtl')
                ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
                ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
                ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
                ->where([
                    ['tblpayment.student_ic', $std->ic],
                    ['tblpayment.process_status_id', 2], 
                    ['tblstudentclaim.groupid', 1], 
                    ['tblpaymentdtl.amount', '!=', 0]
                    ])
                ->select(DB::raw("'payment' as source"), 'tblprocess_type.name AS process', 'tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progcode AS program', DB::raw('NULL as remark'));

                $data['record'] = DB::table('tblclaimdtl')
                ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
                ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
                ->where([
                    ['tblclaim.student_ic', $std->ic],
                    ['tblclaim.process_status_id', 2],  
                    ['tblstudentclaim.groupid', 1],
                    ['tblclaimdtl.amount', '!=', 0]
                    ])
                ->unionALL($record)
                ->select(DB::raw("'claim' as source"), 'tblprocess_type.name AS process', 'tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progcode AS program', 'tblclaim.remark')
                ->orderBy('date')
                ->get();

                $data['total'] = 0;

                foreach($data['record'] as $keys => $req)
                {

                    if(array_intersect([2,3,4,5,11], (array) $req->process_type_id) && $req->source == 'claim')
                    {

                        $data['total'] += $req->amount;
                        
                    }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id) && $req->source == 'payment')
                    {

                        $data['total'] -= $req->amount;

                    }

                }

                $data['sum3'] = $data['total'];

                //TUNGGAKAN KESELURUHAN

                $data['total_balance'][$key] = $data['sum3'];

        }

        return view('finance.debt.collection_expectation_report.collection2ReportGetStudent', compact('data'));

    }

    public function monthlyPayment()
    {

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        return view('finance.debt.monthly_payment_report.monthlyReport', compact('data'));

    }

    public function getMonthlyPayment(Request $request)
    {

         // Get current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Set the start date to January of the previous year
        $startYear = $currentYear - 1;
        $startMonth = 1; // January

        // Initialize an array to hold the date range
        $data['dateRange'] = [];

        // Loop to generate the range from start date to current date
        while (($startYear < $currentYear) || ($startYear == $currentYear && $startMonth <= $currentMonth)) {
            $data['dateRange'][] = $startMonth . '/' . $startYear;

            // Increment the month and adjust the year if needed
            $startMonth++;
            if ($startMonth > 12) {
                $startMonth = 1;
                $startYear++;
            }
        }

        //A

        if($request->program != 'all')
        {

            $data['student'] = DB::table('students')
            ->join('sessions', 'students.session', 'sessions.SessionID')
            ->where('students.program', $request->program)
            ->whereIn('students.status', [8])
            ->whereBetween('sessions.Year', [$request->from, $request->to])
            ->select('students.*', 'sessions.Year AS graduate', 'sessions.SessionName AS session')
            ->get();

        }else{


            $data['student'] = DB::table('students')
            ->join('sessions', 'students.session', 'sessions.SessionID')
            ->where('students.program', '!=', 30)
            ->whereIn('students.status', [8])
            ->whereBetween('sessions.Year', [$request->from, $request->to])
            ->select('students.*', 'sessions.Year AS graduate', 'sessions.SessionName AS session')
            ->get();

        }

        

        foreach($data['student'] as $key => $std)
        {

            $data['sponsorStudent'][$key] = DB::table('tblpayment')
                                  ->join('tblsponsor_library', 'tblpayment.payment_sponsor_id', 'tblsponsor_library.id')
                                  ->where([
                                    ['tblpayment.process_type_id', 7],
                                    ['tblpayment.process_status_id', 2],
                                    ['tblpayment.student_ic', $std->ic]
                                    ])
                                  ->whereIn('tblsponsor_library.id', [1,2,3])
                                  ->orderBy('tblpayment.id', 'DESC')
                                  ->select('tblsponsor_library.code AS name')
                                  ->first();

            //B

            $data['sponsor'][$key] = DB::table('tblpackage_sponsorship')
                               ->leftjoin('tblpackage', 'tblpackage_sponsorship.package_id', 'tblpackage.id')
                               ->leftjoin('tblpayment_type', 'tblpackage_sponsorship.payment_type_id', 'tblpayment_type.id')
                               ->where('tblpackage_sponsorship.student_ic', $std->ic)
                               ->select('tblpackage.id AS package_id','tblpackage.name AS package_name', 'tblpayment_type.name AS payment_type_name', 'tblpackage_sponsorship.amount')
                               ->first();

            if($data['sponsor'][$key])
            {

                if(in_array($data['sponsor'][$key]->package_id, [1,4,6,7,8]))
                {

                    $data['type'][$key] = 'B40';

                }elseif(in_array($data['sponsor'][$key]->package_id, [2]))
                {

                    $data['type'][$key] = 'M40';

                }elseif(in_array($data['sponsor'][$key]->package_id, [3,5]))
                {

                    $data['type'][$key] = 'T20';

                }

            }else{


                $data['type'][$key] = ' ';

            }

            //C

            $data['log'][$key] = DB::table('student_payment_log')
                           ->where('student_ic', $std->ic)
                           ->orderBy('id', 'DESC')
                           ->limit(1)
                           ->first();

            //D

            foreach($data['dateRange'] as $key2 => $dr)
            {

                $date = explode('/', $dr);

                $amountResult = DB::table('tblpaymentdtl')
                                        ->join('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
                                        ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                        ->where('tblpayment.student_ic', $std->ic)
                                        ->where('tblpayment.process_status_id', 2)
                                        ->where('tblpayment.process_type_id', 1)
                                        ->whereNotIn('tblstudentclaim.groupid', [4,5])
                                        ->whereYear('tblpayment.add_date', $date[1])
                                        ->whereMonth('tblpayment.add_date', $date[0])
                                        ->selectRaw('IFNULL(SUM(tblpaymentdtl.amount),0) as amount')
                                        ->first();

                // Storing the amount for each month/year combination
                $data['amount'][$key][$key2] = $amountResult ? $amountResult->amount : 0;

            }

            //E

            $data['total_balance'][$key] = 0.00;

            $record = DB::table('tblpaymentdtl')
            ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
            ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
            ->where([
                ['tblpayment.student_ic', $std->ic],
                ['tblpayment.process_status_id', 2], 
                ['tblstudentclaim.groupid', 1], 
                ['tblpaymentdtl.amount', '!=', 0]
                ])
            ->select(DB::raw("'payment' as source"), 'tblprocess_type.name AS process', 'tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progcode AS program', DB::raw('NULL as remark'));

            $data['record'] = DB::table('tblclaimdtl')
            ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
            ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
            ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
            ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
            ->where([
                ['tblclaim.student_ic', $std->ic],
                ['tblclaim.process_status_id', 2],  
                ['tblstudentclaim.groupid', 1],
                ['tblclaimdtl.amount', '!=', 0]
                ])
            ->unionALL($record)
            ->select(DB::raw("'claim' as source"), 'tblprocess_type.name AS process', 'tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progcode AS program', 'tblclaim.remark')
            ->orderBy('date')
            ->get();

            $data['total'] = 0;

            foreach($data['record'] as $keys => $req)
            {

                if(array_intersect([2,3,4,5,11], (array) $req->process_type_id) && $req->source == 'claim')
                {

                    $data['total'] += $req->amount;
                    
                }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id) && $req->source == 'payment')
                {

                    $data['total'] -= $req->amount;

                }

            }

            $data['sum3'] = $data['total'];

            //TUNGGAKAN KESELURUHAN

            $data['total_balance'][$key] = $data['sum3'];

            // $data['amount'] = DB::

            //F
            
            $data['address'][$key] = DB::table('tblstudent_address')
                                    ->leftJoin('tblstate', 'tblstudent_address.state_id', '=', 'tblstate.id')
                                    ->leftJoin('tblcountry', 'tblstudent_address.country_id', '=', 'tblcountry.id')
                                    ->select(
                                        DB::raw("CONCAT(IFNULL(tblstudent_address.address1, ''), 
                                                    IF(tblstudent_address.address1 IS NOT NULL AND tblstudent_address.address2 IS NOT NULL, ',', ''), 
                                                    IFNULL(tblstudent_address.address2, ''), 
                                                    IF((tblstudent_address.address1 IS NOT NULL OR tblstudent_address.address2 IS NOT NULL) AND tblstudent_address.address3 IS NOT NULL, ',', ''), 
                                                    IFNULL(tblstudent_address.address3, '')) AS address"),
                                        'tblstudent_address.city',
                                        'tblstate.state_name',
                                        'tblstudent_address.postcode',
                                        'tblcountry.name AS country_name'
                                    )
                                    ->where('tblstudent_address.student_ic', $std->ic)
                                    ->first();

        }

        return view('finance.debt.monthly_payment_report.monthlyReportGetStudent', compact('data'));

    }

    public function studentKWSPRefund()
    {

        return view('finance.payment.kwspNote');

    }

    public function getStudentKWSPrefund(Request $request)
    {

        $data['student'] = DB::table('students')
        ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
        ->join('sessions AS t1', 'students.intake', 't1.SessionID')
        ->join('sessions AS t2', 'students.session', 't2.SessionID')
        ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
        ->where('ic', $request->student)->first();

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->orderBy('name', 'asc')->get();

        $data['list'] = DB::table('tblkwsp_note')
                        ->leftjoin('tblpayment_bank', 'tblkwsp_note.bank_id', 'tblpayment_bank.id')
                        ->leftjoin('tblpayment_method', 'tblkwsp_note.payment_method_id', 'tblpayment_method.id')
                        ->select('tblkwsp_note.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                        ->where('tblkwsp_note.student_ic', $request->student)
                        ->get();

        return view('finance.payment.kwspNoteGetStudent', compact('data'));

    }

    public function storeKWSPrefund(Request $request)
    {

        $kwspDetails = $request->kwspDetails;

        $validator = Validator::make($request->all(), [
            'kwspDetails' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{
                $data = json_decode($kwspDetails);
                
                if($data->date != null && $data->discount != null && $data->method != null && $data->amount != null)
                {

                    DB::table('tblkwsp_note')->insert([
                        'student_ic' => $data->ic,
                        'date' => $data->date,
                        'payment_method_id' => $data->method,
                        'bank_id' => $data->bank,
                        'document_no' => $data->nodoc,
                        'discount' => $data->discount,
                        'amount' => $data->amount
                    ]);

                    $details = DB::table('tblkwsp_note')
                    ->leftjoin('tblpayment_bank', 'tblkwsp_note.bank_id', 'tblpayment_bank.id')
                    ->leftjoin('tblpayment_method', 'tblkwsp_note.payment_method_id', 'tblpayment_method.id')
                    ->select('tblkwsp_note.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                    ->where('tblkwsp_note.student_ic', $data->ic)
                    ->get();

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th>
                                            Date
                                        </th>
                                        <th>
                                            Payment Method
                                        </th>
                                        <th>
                                            Bank
                                        </th>
                                        <th>
                                            Document No.
                                        </th>
                                        <th>
                                            Discount
                                        </th>
                                        <th>
                                            Amount
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($details as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->date .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->method .'
                            </td>
                            <td style="width: 15%">
                            '. $dtl->bank .'
                            </td>
                            <td style="width: 20%">
                            '. $dtl->document_no .'
                            </td>
                            <td style="width: 20%">
                            '. $dtl->discount .'
                            </td>
                            <td style="width: 20%">
                            '. $dtl->amount .'
                            </td>
                            <td>
                                <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .', '. $dtl->student_ic .')">
                                    <i class="ti-trash">
                                    </i>
                                    Delete
                                </a>
                            </td>
                        </tr>
                        ';
                        }
                    $content .= '</tbody>';

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
                
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

        return ["message" => "Success", "data" => $content];

    }

    public function deleteKWSPrefund(Request $request)
    {

        DB::table('tblkwsp_note')->where('id', $request->id)->delete();

        $details = DB::table('tblkwsp_note')
                    ->leftjoin('tblpayment_bank', 'tblkwsp_note.bank_id', 'tblpayment_bank.id')
                    ->leftjoin('tblpayment_method', 'tblkwsp_note.payment_method_id', 'tblpayment_method.id')
                    ->select('tblkwsp_note.*', 'tblpayment_bank.name AS bank', 'tblpayment_method.name AS method')
                    ->where('tblkwsp_note.student_ic', $request->ic)
                    ->get();

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                Payment Method
                            </th>
                            <th>
                                Bank
                            </th>
                            <th>
                                Document No.
                            </th>
                            <th>
                                Discount
                            </th>
                            <th>
                                Amount
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($details as $key => $dtl){
        //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
        $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td style="width: 15%">
                '. $dtl->date .'
                </td>
                <td style="width: 15%">
                '. $dtl->method .'
                </td>
                <td style="width: 15%">
                '. $dtl->bank .'
                </td>
                <td style="width: 20%">
                '. $dtl->document_no .'
                </td>
                <td style="width: 20%">
                '. $dtl->discount .'
                </td>
                <td style="width: 20%">
                '. $dtl->amount .'
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('. $dtl->id .', '. $dtl->student_ic .')">
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

    public function agingReport()
    {

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        $data['status'] = DB::table('tblstudent_status')->get();

        return view('finance.report.agingReport', compact('data'));

    }

    public function getAgingReport(Request $request)
    {
        $lastKnownBalance = 0; // Default to 0 initially
        $filtersData = $request->filtersData;

        $validator = Validator::make($request->all(), [
            'filtersData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{

                $filter = json_decode($filtersData);

                if($filter->program == 'all')
                {

                    $program = DB::table('tblprogramme')->pluck('id');

                }else{

                    $program = DB::table('tblprogramme')->where('id', $filter->program)->pluck('id');
                    
                }

                // Assuming $request->year has the value of 2019
                $startYear = date('Y', strtotime($filter->from)); // Starting year
                $endYear = date('Y', strtotime($filter->to)); // Starting year
                $currentYear = now()->year; // This gets the current year

                // Create an array of years from start year to current year
                $data['arrayYears'] = range($startYear, $currentYear);

                // Create an array of years from and to
                $data['rangeYears'] = range($startYear, $endYear);

                $data['student'] = DB::table('students')
                                   ->leftjoin('sessions', 'students.session', 'sessions.SessionID')
                                   ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                                   ->leftjoin('tblstudent_status', 'students.status', 'tblstudent_status.id')
                                   ->whereIn('students.program', $program)
                                   ->when($filter->status != 'all', function ($query) use ($filter){
                                        return $query->where('students.status', $filter->status);
                                   })
                                   ->select('students.name','students.ic', 'students.no_matric', 'tblprogramme.progcode', 
                                            'sessions.SessionName', 'students.semester', 'tblstudent_status.name AS status')->get();

                foreach($data['student'] as $key => $std)
                {
                    //B

                    $data['sponsor'][$key] = DB::table('tblpayment')
                                       ->leftjoin('tblsponsor_library', 'tblpayment.payment_sponsor_id', 'tblsponsor_library.id')
                                       ->where([
                                            ['tblpayment.student_ic', $std->ic],
                                            ['tblpayment.process_status_id', 2],
                                            ['tblpayment.process_type_id', 7]
                                       ])
                                       ->orderBy('tblpayment.id', 'DESC')
                                       ->value('code');

                    //C
                    
                    $data['sponsor_dtl'][$key] = DB::table('tblpackage_sponsorship')
                                             ->leftjoin('tblpackage', 'tblpackage_sponsorship.package_id', 'tblpackage.id')
                                             ->leftjoin('tblpayment_type', 'tblpackage_sponsorship.payment_type_id', 'tblpayment_type.id')
                                             ->where('tblpackage_sponsorship.student_ic', $std->ic)
                                             ->select('tblpackage.name AS package_name', 'tblpayment_type.name AS payment_type', 'tblpackage_sponsorship.amount')
                                             ->first();

                    foreach($data['arrayYears'] as $key2 => $year)
                    {

                        if(in_array($year, $data['rangeYears']))
                        {

                            // Create a date instance for the last day of the year using Carbon
                            $lastDayOfYear = Carbon::createFromDate($year)->endOfYear()->toDateString();
                            
                            if($year == $endYear)
                            {

                                $endYear2 = $filter->to;

                            }else{

                                $endYear2 = $lastDayOfYear;

                            }

                            // Define the first part of the union
                            $query = DB::table('tblclaim')
                            ->leftjoin('tblclaimdtl', 'tblclaim.id', '=', 'tblclaimdtl.claim_id')
                            ->leftjoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', '=', 'tblstudentclaim.id')
                            ->where([
                                ['tblclaim.process_status_id', '=', 2],
                                ['tblstudentclaim.groupid', '=', 1],
                                ['tblclaim.student_ic', '=', $std->ic]
                            ])
                            ->whereBetween('tblclaim.add_date', [$filter->from, $endYear2])
                            ->select(DB::raw("IFNULL(SUM(tblclaimdtl.amount), 0) AS claim"), DB::raw('0 as payment'));

                            // Define the second part of the union
                            $subQuery = DB::table('tblpayment')
                            ->leftjoin('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
                            ->leftjoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', '=', 'tblstudentclaim.id')
                            ->where([
                                ['tblpayment.process_status_id', '=', 2],
                                ['tblstudentclaim.groupid', '=', 1],
                                ['tblpayment.student_ic', '=', $std->ic]
                            ])
                            ->whereBetween('tblpayment.add_date', [$filter->from, $endYear2])
                            ->select(DB::raw('0 as claim'), DB::raw("IFNULL(SUM(tblpaymentdtl.amount), 0) AS payment"))
                            ->unionAll($query); // Here, use the Query Builder instance directly

                            // // Now, wrap the subquery and calculate the balance
                            // $data['balance'][$key][$key2] = DB::query()->fromSub($subQuery, 'sub')
                            // ->select(DB::raw('SUM(claim) - SUM(payment) AS balance'))
                            // ->get();

                            $result = DB::query()->fromSub($subQuery, 'sub')
                                ->select(DB::raw('SUM(claim) - SUM(payment) AS balance'))
                                ->get();

                            if ($result->isNotEmpty() && isset($result[0]->balance)) {
                                // Update last known balance if the result is not empty
                                $lastKnownBalance = $result[0]->balance;
                                $data['balance'][$key][$key2] = $result;
                            } else {
                                // If result is empty, ensure last known balance is maintained
                                $data['balance'][$key][$key2] = collect([(object) ['balance' => $lastKnownBalance]]);
                            }

                        }else{

                            // // Set balance to a default object in a collection if the year is not in the array
                            // $data['balance'][$key][$key2] = collect([ (object) ['balance' => 0] ]);

                             // Set balance to the last known balance if the year is not in the array
                            $data['balance'][$key][$key2] = collect([(object) ['balance' => $lastKnownBalance]]);

                        }

                    }

                }

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th>
                                        No
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        IC
                                    </th>
                                    <th>
                                        Program
                                    </th>
                                    <th>
                                        No. Matric
                                    </th>
                                    <th>
                                        Session
                                    </th>
                                    <th>
                                        Semester
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Sponsor
                                    </th>
                                    <th>
                                        Package
                                    </th>
                                    <th>
                                        Payment Method
                                    </th>';
                                    foreach($data['arrayYears'] as $year)
                                    {
                                    $content .= 
                                    '<th>
                                    '. $year .'  
                                    </th>';
                                    }


                    $content .= '</tr>
                            </thead>
                            <tbody id="table">';
                            
                foreach($data['student'] as $key => $std){
                    //$registered = ($std->status == 'ACTIVE') ? 'checked' : '';

                    $content .= '
                    <tr>
                        <td>
                        '. $key+1 .'
                        </td>
                        <td>
                        '. $std->name .'
                        </td>
                        <td>
                        '. $std->ic .'
                        </td>
                        <td>
                        '. $std->progcode .'
                        </td>
                        <td>
                        '. $std->no_matric .'
                        </td>
                        <td>
                        '. $std->SessionName .'
                        </td>
                        <td>
                        '. $std->semester .'
                        </td>
                        <td>
                        '. $std->status .'
                        </td>
                        <td>';
                        if($data['sponsor'][$key] != null)
                        {

                            $content .= ''. $data['sponsor'][$key] .'';

                        }else{
                            $content .= '-';
                        }
            $content .= '</td>
                        <td>';
                        if($data['sponsor_dtl'][$key] != null)
                        {

                            $content .= ''. $data['sponsor_dtl'][$key]->package_name .'';

                        }else{
                            $content .= '-';
                        }
            $content .= '</td>
                        <td>';
                        if($data['sponsor_dtl'][$key] != null)
                        {

                            $content .= ''. $data['sponsor_dtl'][$key]->payment_type .'';

                        }else{
                            $content .= '-';
                        }
            $content .= '</td>';
                        foreach($data['arrayYears'] as $key2 => $year)
                        {
                            foreach($data['balance'][$key][$key2] as $balance)
                            {
                            $content .= 
                            '<td>
                            '. $balance->balance .'  
                            </td>';
                            }
                        }
        $content .='</tr>
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

        return response()->json(['message' => 'Success', 'data' => $content]);

    }

    public function programAgingReport()
    {

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        return view('finance.report.programAgingReport', compact('data'));

    }

    public function getProgramAgingReport(Request $request)
    {

        $lastKnownBalance = 0; // Default to 0 initially
        $filtersData = $request->filtersData;

        $validator = Validator::make($request->all(), [
            'filtersData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{

                $filter = json_decode($filtersData);

                if($filter->program == 'all')
                {

                    $data['program'] = DB::table('tblprogramme')->pluck('id');

                }else{

                    $data['program'] = DB::table('tblprogramme')->where('id', $filter->program)->pluck('id');
                    
                }

                // Assuming $request->year has the value of 2019
                $startYear = date('Y', strtotime($filter->from)); // Starting year
                $endYear = date('Y', strtotime($filter->to)); // Starting year
                $currentYear = now()->year; // This gets the current year

                // Create an array of years from start year to current year
                $data['arrayYears'] = range($startYear, $currentYear);

                // Create an array of years from and to
                $data['rangeYears'] = range($startYear, $endYear);

                foreach($data['program'] as $key => $prg)
                {

                    foreach($data['arrayYears'] as $key2 => $year)
                    {

                        if(in_array($year, $data['rangeYears']))
                        {

                            // Create a date instance for the last day of the year using Carbon
                            $lastDayOfYear = Carbon::createFromDate($year)->endOfYear()->toDateString();
                            
                            if($year == $endYear)
                            {

                                $endYear2 = $filter->to;

                            }else{

                                $endYear2 = $lastDayOfYear;

                            }

                            // Define the first part of the union
                            $query = DB::table('tblclaim')
                            ->leftjoin('tblclaimdtl', 'tblclaim.id', '=', 'tblclaimdtl.claim_id')
                            ->leftjoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', '=', 'tblstudentclaim.id')
                            ->where([
                                ['tblclaim.process_status_id', '=', 2],
                                ['tblstudentclaim.groupid', '=', 1]
                            ])
                            ->where('tblclaim.program_id', $prg)
                            ->whereBetween('tblclaim.add_date', [$filter->from, $endYear2])
                            ->select(DB::raw("IFNULL(SUM(tblclaimdtl.amount), 0) AS claim"), DB::raw('0 as payment'));

                            // Define the second part of the union
                            $subQuery = DB::table('tblpayment')
                            ->leftjoin('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
                            ->leftjoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', '=', 'tblstudentclaim.id')
                            ->where([
                                ['tblpayment.process_status_id', '=', 2],
                                ['tblstudentclaim.groupid', '=', 1]
                            ])
                            ->where('tblpayment.program_id', $prg)
                            ->whereBetween('tblpayment.add_date', [$filter->from, $endYear2])
                            ->select(DB::raw('0 as claim'), DB::raw("IFNULL(SUM(tblpaymentdtl.amount), 0) AS payment"))
                            ->unionAll($query); // Here, use the Query Builder instance directly

                            // // Now, wrap the subquery and calculate the balance
                            // $data['balance'][$key][$key2] = DB::query()->fromSub($subQuery, 'sub')
                            // ->select(DB::raw('SUM(claim) - SUM(payment) AS balance'))
                            // ->get();

                            $result = DB::query()->fromSub($subQuery, 'sub')
                            ->select(DB::raw('SUM(claim) - SUM(payment) AS balance'))
                            ->get();

                            if ($result->isNotEmpty() && isset($result[0]->balance)) {
                                // Update last known balance if the result is not empty
                                $lastKnownBalance = $result[0]->balance;
                                $data['balance'][$key][$key2] = $result;
                            } else {
                                // If result is empty, ensure last known balance is maintained
                                $data['balance'][$key][$key2] = collect([(object) ['balance' => $lastKnownBalance]]);
                            }

                        }else{

                            // // Set balance to a default object in a collection if the year is not in the array
                            // $data['balance'][$key][$key2] = collect([ (object) ['balance' => 0] ]);

                            // Set balance to the last known balance if the year is not in the array
                            $data['balance'][$key][$key2] = collect([(object) ['balance' => $lastKnownBalance]]);

                        }

                    }

                }

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th rowspan="2">
                                        Program
                                    </th>
                                    <th colspan="'. count($data['arrayYears']) .'" style="text-align: center">
                                        AGING REPORT BY PROGRAM AND YEAR from '. $startYear .' UNTIL '. $currentYear; 
                        $content .= '</th>
                                </tr>
                                <tr>';
                                    foreach($data['arrayYears'] as $year)
                                    {
                                    $content .= 
                                    '<th>
                                    '. $year .'  
                                    </th>';
                                    }


                    $content .= '</tr>
                            </thead>
                            <tbody id="table">';

                            // Initialize totals array
                            $totals = array_fill_keys($data['arrayYears'], 0);
                            
                foreach($data['program'] as $key => $prg){
                    //$registered = ($std->status == 'ACTIVE') ? 'checked' : '';

                    $program = DB::table('tblprogramme')->where('id', $prg)->select('progcode', 'progname')->first();

                    $content .= '
                    <tr>
                        <td>
                        '. $program->progcode .' - '. $program->progname .'
                        </td>';
                        foreach($data['arrayYears'] as $key2 => $year)
                        {
                            foreach($data['balance'][$key][$key2] as $balance)
                            {
                            // Add balance to totals
                            $totals[$year] += $balance->balance;

                            $content .= 
                            '<td>
                            '. $balance->balance .'  
                            </td>';
                            }
                        }
        $content .='</tr>
                    ';
                    }

                $content .= '</tbody>';
                $content .= '<tfoot>
                        <tr>
                            <td>
                                TOTAL
                            </td>';
                            // Display totals in the footer
                            foreach($totals as $yearTotal) {
                                $content .= '<td>'. number_format($yearTotal, 2) .'</td>';
                            }
            $content .= '</tr>
                    </tfoot>';

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

        return response()->json(['message' => 'Success', 'data' => $content]);

    }

    public function statusAgingReport()
    {

        $data['status'] = DB::table('tblstudent_status')->get();

        return view('finance.report.statusAgingReport', compact('data'));

    }

    public function getStatusAgingReport(Request $request)
    {
        $lastKnownBalance = 0; // Default to 0 initially
        $filtersData = $request->filtersData;

        $validator = Validator::make($request->all(), [
            'filtersData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{

                $filter = json_decode($filtersData);

                if($filter->status == 'all')
                {

                    $data['status'] = DB::table('tblstudent_status')->pluck('id');

                }else{

                    $data['status'] = DB::table('tblstudent_status')->where('id', $filter->status)->pluck('id');
                    
                }

                // Assuming $request->year has the value of 2019
                $startYear = date('Y', strtotime($filter->from)); // Starting year
                $endYear = date('Y', strtotime($filter->to)); // Starting year
                $currentYear = now()->year; // This gets the current year

                // Create an array of years from start year to current year
                $data['arrayYears'] = range($startYear, $currentYear);

                // Create an array of years from and to
                $data['rangeYears'] = range($startYear, $endYear);

                foreach($data['status'] as $key => $sts)
                {

                    foreach($data['arrayYears'] as $key2 => $year)
                    {

                        if(in_array($year, $data['rangeYears']))
                        {

                            // Create a date instance for the last day of the year using Carbon
                            $lastDayOfYear = Carbon::createFromDate($year)->endOfYear()->toDateString();
                            
                            if($year == $endYear)
                            {

                                $endYear2 = $filter->to;

                            }else{

                                $endYear2 = $lastDayOfYear;

                            }

                            // Define the first part of the union
                            $query = DB::table('students')
                            ->join('tblclaim', 'students.ic', 'tblclaim.student_ic')
                            ->join('tblclaimdtl', 'tblclaim.id', '=', 'tblclaimdtl.claim_id')
                            ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', '=', 'tblstudentclaim.id')
                            ->where([
                                ['tblclaim.process_status_id', '=', 2],
                                ['tblstudentclaim.groupid', '=', 1]
                            ])
                            ->where('students.status', $sts)
                            ->whereBetween('tblclaim.add_date', [$filter->from, $endYear2])
                            ->select(DB::raw("IFNULL(SUM(tblclaimdtl.amount), 0) AS claim"), DB::raw('0 as payment'));

                            // Define the second part of the union
                            $subQuery = DB::table('students')
                            ->join('tblpayment', 'students.ic', 'tblpayment.student_ic')
                            ->join('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', '=', 'tblstudentclaim.id')
                            ->where([
                                ['tblpayment.process_status_id', '=', 2],
                                ['tblstudentclaim.groupid', '=', 1]
                            ])
                            ->where('students.status', $sts)
                            ->whereBetween('tblpayment.add_date', [$filter->from, $endYear2])
                            ->select(DB::raw('0 as claim'), DB::raw("IFNULL(SUM(tblpaymentdtl.amount), 0) AS payment"))
                            ->unionAll($query); // Here, use the Query Builder instance directly

                            // // Now, wrap the subquery and calculate the balance
                            // $data['balance'][$key][$key2] = DB::query()->fromSub($subQuery, 'sub')
                            // ->select(DB::raw('SUM(claim) - SUM(payment) AS balance'))
                            // ->get();

                            $result = DB::query()->fromSub($subQuery, 'sub')
                                ->select(DB::raw('SUM(claim) - SUM(payment) AS balance'))
                                ->get();

                            if ($result->isNotEmpty() && isset($result[0]->balance)) {
                                // Update last known balance if the result is not empty
                                $lastKnownBalance = $result[0]->balance;
                                $data['balance'][$key][$key2] = $result;
                            } else {
                                // If result is empty, ensure last known balance is maintained
                                $data['balance'][$key][$key2] = collect([(object) ['balance' => $lastKnownBalance]]);
                            }

                        }else{

                            // // Set balance to a default object in a collection if the year is not in the array
                            // $data['balance'][$key][$key2] = collect([ (object) ['balance' => 0] ]);

                             // Set balance to the last known balance if the year is not in the array
                            $data['balance'][$key][$key2] = collect([(object) ['balance' => $lastKnownBalance]]);

                        }

                    }

                }

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th rowspan="2">
                                        status
                                    </th>
                                    <th colspan="'. count($data['arrayYears']) .'" style="text-align: center">
                                        AGING REPORT BY STATUS AND YEAR from '. $startYear .' UNTIL '. $currentYear; 
                        $content .= '</th>
                                </tr>
                                <tr>';
                                    foreach($data['arrayYears'] as $year)
                                    {
                                    $content .= 
                                    '<th>
                                    '. $year .'  
                                    </th>';
                                    }


                    $content .= '</tr>
                            </thead>
                            <tbody id="table">';

                            // Initialize totals array
                            $totals = array_fill_keys($data['arrayYears'], 0);
                            
                foreach($data['status'] as $key => $sts){
                    //$registered = ($std->status == 'ACTIVE') ? 'checked' : '';

                    $status = DB::table('tblstudent_status')->where('id', $sts)->select('name')->first();

                    $content .= '
                    <tr>
                        <td>
                        '. $status->name .'
                        </td>';
                        foreach($data['arrayYears'] as $key2 => $year)
                        {
                            foreach($data['balance'][$key][$key2] as $balance)
                            {
                            // Add balance to totals
                            $totals[$year] += $balance->balance;

                            $content .= 
                            '<td>
                            '. $balance->balance .'  
                            </td>';
                            }
                        }
        $content .='</tr>
                    ';
                    }

                $content .= '</tbody>';
                $content .= '<tfoot>
                        <tr>
                            <td>
                                TOTAL
                            </td>';
                            // Display totals in the footer
                            foreach($totals as $yearTotal) {
                                $content .= '<td>'. number_format($yearTotal, 2) .'</td>';
                            }
            $content .= '</tr>
                    </tfoot>';

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

        return response()->json(['message' => 'Success', 'data' => $content]);

    }

    public function ctosReport()
    {

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        return view('finance.debt.ctos_report.ctosReport', compact('data'));

    }

    public function getCtosReport(Request $request)
    {

        //A

        if($request->program == 'all')
        {

            $data['program'] = DB::table('tblprogramme')->pluck('id');

        }else{

            $data['program'] = DB::table('tblprogramme')->where('id', $request->program)->pluck('id');
            
        }

        $data['student'] = DB::table('students')
        ->leftJoin('tblstudent_address', 'students.ic', '=', 'tblstudent_address.student_ic')
        ->leftJoin('tblstate', 'tblstudent_address.state_id', '=', 'tblstate.id')
        ->leftJoin('tblcountry', 'tblstudent_address.country_id', '=', 'tblcountry.id')
        ->leftJoin('tblstudent_personal', 'students.ic', '=', 'tblstudent_personal.student_ic')
        ->leftJoin('sessions', 'students.session', '=', 'sessions.SessionID')
        ->leftJoin('tblnationality AS b', 'tblstudent_personal.nationality_id', '=', 'b.id')
        ->select(
            DB::raw('"" AS CM'),
            DB::raw('"" AS etr'),
            'students.name',
            DB::raw('"" AS old_ic'),
            'students.ic',
            DB::raw('"" AS passport'),
            DB::raw('CASE WHEN tblstudent_personal.sex_id = 1 THEN "Mr" ELSE "Mrs" END AS salution'),
            'tblstudent_personal.sex_id',
            DB::raw('"1" AS marital_status'),
            DB::raw('"3" AS house_status'),
            DB::raw("CONCAT(IFNULL(tblstudent_address.address1, ''), 
                        IF(tblstudent_address.address1 IS NOT NULL AND tblstudent_address.address2 IS NOT NULL, ',', ''), 
                        IFNULL(tblstudent_address.address2, ''), 
                        IF((tblstudent_address.address1 IS NOT NULL OR tblstudent_address.address2 IS NOT NULL) AND tblstudent_address.address3 IS NOT NULL, ',', ''), 
                        IFNULL(tblstudent_address.address3, '')) AS address"),
            'tblstudent_address.city',
            'tblstate.state_name',
            'tblstudent_address.postcode',
            'tblcountry.name AS country_name',
            'tblstudent_personal.date_birth',
            'b.nationality_name AS nationality_name',
            'students.email',
            'tblstudent_personal.no_tel',
            DB::raw('"" AS ref_no'),
            DB::raw('"" AS account_no')
        )
        ->whereBetween('sessions.Year', [$request->from, $request->to])
        ->whereIn('students.program', $data['program'])
        ->whereIn('students.status', [3, 8])
        ->get();

                           

        

        foreach($data['student'] as $key => $std)
        {

            //check current arrears

            // $record = DB::table('tblpaymentdtl')
            // ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
            // ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
            // ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            // ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
            // ->where([
            //     ['tblpayment.student_ic', $std->ic],
            //     ['tblpayment.process_status_id', 2], 
            //     ['tblstudentclaim.groupid', 1], 
            //     ['tblpaymentdtl.amount', '!=', 0]
            //     ])
            // ->select(DB::raw("'payment' as source"), 'tblpaymentdtl.amount', 'tblpayment.process_type_id');

            // $data['record'][$key] = DB::table('tblclaimdtl')
            // ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
            // ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
            // ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
            // ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
            // ->where([
            //     ['tblclaim.student_ic', $std->ic],
            //     ['tblclaim.process_status_id', 2],  
            //     ['tblstudentclaim.groupid', 1],
            //     ['tblclaimdtl.amount', '!=', 0]
            //     ])
            // ->unionALL($record)
            // ->select(DB::raw("'claim' as source"), 'tblclaimdtl.amount', 'tblclaim.process_type_id')
            // ->get();

            // $val = 0;

            // foreach($data['record'][$key] as $key2 => $req)
            // {

            //     if(array_intersect([2,3,4,5,11], (array) $req->process_type_id) && $req->source == 'claim')
            //     {

            //         $val += $req->amount;

            //     }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25], (array) $req->process_type_id) && $req->source == 'payment')
            //     {

            //         $val -= $req->amount;

            //     }

            // }   

            // $data['total_balance'][$key] = $val;

            //B

            $data['waris'][$key] = DB::table('tblstudent_waris')
                                   ->select(
                                    DB::raw('"I" AS sponsor'), DB::raw('"" AS old_ic'), 'tblstudent_waris.ic',
                                    DB::raw('"" AS passport'), 'tblstudent_waris.name', DB::raw('"1" AS sponsor_status'),
                                    DB::raw('"" AS remarks'), DB::raw('"" as notification'), DB::raw('"" AS relationship'),
                                    DB::raw('"1" AS type')
                                   )
                                   ->where('tblstudent_waris.student_ic', $std->ic)
                                   ->first();

            //C

            // Define the first part of the union
            $query = DB::table('students')
            ->leftjoin('tblclaim', 'students.ic', 'tblclaim.student_ic')
            ->leftjoin('tblclaimdtl', 'tblclaim.id', '=', 'tblclaimdtl.claim_id')
            ->leftjoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', '=', 'tblstudentclaim.id')
            ->where([
                ['tblclaim.process_status_id', '=', 2],
                ['tblstudentclaim.groupid', '=', 1]
            ])
            ->where('students.ic', $std->ic)
            ->select(DB::raw("IFNULL(SUM(tblclaimdtl.amount), 0) AS claim"), DB::raw('0 as payment'));

            // Define the second part of the union
            $subQuery = DB::table('students')
            ->leftjoin('tblpayment', 'students.ic', 'tblpayment.student_ic')
            ->leftjoin('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
            ->leftjoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', '=', 'tblstudentclaim.id')
            ->where([
                ['tblpayment.process_status_id', '=', 2],
                ['tblstudentclaim.groupid', '=', 1]
            ])
            ->where('students.ic', $std->ic)
            ->select(DB::raw('0 as claim'), DB::raw("IFNULL(SUM(tblpaymentdtl.amount), 0) AS payment"))
            ->unionAll($query); // Here, use the Query Builder instance directly

            // Now, wrap the subquery and calculate the balance
            $data['balance'][$key] = DB::query()->fromSub($subQuery, 'sub')
            ->select(DB::raw('"" AS date'), DB::raw('SUM(claim) - SUM(payment) AS balance'), DB::raw('"" AS cr_limit'), DB::raw('"" AS cr_term'))
            ->limit(1)
            ->get();

            //D

            $data['lastPayment'][$key] = DB::table('tblpayment')
                                   ->leftjoin('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
                                   ->leftjoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', '=', 'tblstudentclaim.id')
                                   ->where([
                                        ['tblpayment.student_ic', $std->ic],
                                        ['tblpayment.process_type_id', 1],
                                        ['tblpayment.process_status_id', 2],
                                        ['tblstudentclaim.groupid', '=', 1]
                                   ])
                                   ->select(DB::raw('SUM(tblpaymentdtl.amount) AS last_payment'), DB::raw('"1" AS option'), DB::raw('"31" AS debt_type'), DB::raw('"" AS deletion') )
                                   ->groupBy('tblpayment.add_date')
                                   ->orderByDesc('tblpayment.add_date')
                                   ->limit(1)
                                   ->get();

        }

        return view('finance.debt.ctos_report.ctosReportGetStudent', compact('data'));

    }

    public function printArrearNotice(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'student' => 'required',
            'money' => 'required|integer|min:0',
            'period' => 'required|integer|min:0',
            'start' => 'required|date',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['details'] = $request->all();

        $data['startDate'] = strtoupper((Carbon::createFromFormat('Y-m-d', $request->start))->format('F Y'));

        $data['endDate'] = strtoupper((Carbon::createFromFormat('Y-m-d', $request->start)->addMonths($request->period))->format('F Y'));

        $data['student'] = DB::table('students')
                           ->leftjoin('tblstudent_address', 'students.ic', 'tblstudent_address.student_ic')
                           ->leftjoin('tblstate', 'tblstudent_address.state_id', 'tblstate.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->select('students.*', 'tblstudent_address.*', 'tblstate.state_name AS state', 'tblprogramme.progcode AS code', 'tblprogramme.progname AS program')
                           ->where('students.ic', $request->student)->first();
        
        $data['originalDate'] = strtoupper(Carbon::createFromFormat('Y-m-d H:i:s', now())->format('d F Y'));

        // Define the first part of the union
        $query = DB::table('tblclaim')
        ->leftjoin('tblclaimdtl', 'tblclaim.id', '=', 'tblclaimdtl.claim_id')
        ->leftjoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', '=', 'tblstudentclaim.id')
        ->where([
            ['tblclaim.process_status_id', '=', 2],
            ['tblstudentclaim.groupid', '=', 1],
            ['tblclaim.student_ic', '=', $request->student]
        ])
        ->select(DB::raw("IFNULL(SUM(tblclaimdtl.amount), 0) AS claim"), DB::raw('0 as payment'));
        
        // Define the second part of the union
        $subQuery = DB::table('tblpayment')
        ->leftjoin('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
        ->leftjoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', '=', 'tblstudentclaim.id')
        ->where([
            ['tblpayment.process_status_id', '=', 2],
            ['tblstudentclaim.groupid', '=', 1],
            ['tblpayment.student_ic', '=', $request->student]
        ])
        ->select(DB::raw('0 as claim'), DB::raw("IFNULL(SUM(tblpaymentdtl.amount), 0) AS payment"))
        ->unionAll($query); // Here, use the Query Builder instance directly

        // Now, wrap the subquery and calculate the balance
        $data['balance'] = DB::query()->fromSub($subQuery, 'sub')
        ->select(DB::raw('SUM(claim) AS total_claim'), DB::raw('SUM(payment) AS total_payment'), DB::raw('SUM(claim) - SUM(payment) AS balance'))
        ->first();

        //dd($data['details']);

        return view('finance.debt.arrear_notice.printArrearNotice', compact('data'));

    }

    public function printAuthorizeTranscript(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'student' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['student'] = DB::table('students')
                           ->leftjoin('tblstudent_address', 'students.ic', 'tblstudent_address.student_ic')
                           ->leftjoin('tblstate', 'tblstudent_address.state_id', 'tblstate.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->select('students.*', 'tblstudent_address.*', 'tblstate.state_name AS state', 'tblprogramme.progcode AS code', 'tblprogramme.progname AS program')
                           ->where('students.ic', $request->student)->first();

        return view('finance.debt.authorize_transcript.printAuthorizeTranscript', compact('data'));

    }

    public function studentArrearsReport()
    {

        $data['program'] = DB::table('tblprogramme')->orderBy('program_ID')->get();

        $data['session'] = DB::table('sessions')->get();

        $data['status'] = DB::table('tblstudent_status')->get();

        return view('finance.report.studentArrearsReport', compact('data'));

    }

    public function getStudentArrearsReport(Request $request)
    {

        $filtersData = $request->filtersData;

        $validator = Validator::make($request->all(), [
            'filtersData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{

                $filter = json_decode($filtersData);

                if($filter->program == 'all')
                {

                    $program = DB::table('tblprogramme')->pluck('id');

                }else{

                    $program = DB::table('tblprogramme')->where('id', $filter->program)->pluck('id');
                    
                }

                $data['student'] = DB::table('students')
                                   ->leftjoin('sessions', 'students.session', 'sessions.SessionID')
                                   ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                                   ->leftjoin('tblstudent_status', 'students.status', 'tblstudent_status.id')
                                   ->whereIn('students.program', $program)
                                   ->when($filter->status != 'all', function ($query) use ($filter){
                                        return $query->where('students.status', $filter->status);
                                   })
                                   ->when($filter->session != 'all', function ($query) use ($filter){
                                        return $query->where('students.session', $filter->session);
                                    })
                                   ->select('students.name','students.ic', 'students.no_matric', 'tblprogramme.progcode', 
                                            'sessions.SessionName', 'students.semester', 'tblstudent_status.name AS status')
                                   ->get();

                foreach($data['student'] as $key => $std)
                {
                    //B

                    $data['sponsor'][$key] = DB::table('tblpayment')
                                       ->leftjoin('tblsponsor_library', 'tblpayment.payment_sponsor_id', 'tblsponsor_library.id')
                                       ->where([
                                            ['tblpayment.student_ic', $std->ic],
                                            ['tblpayment.process_status_id', 2],
                                            ['tblpayment.process_type_id', 7]
                                       ])
                                       ->orderBy('tblpayment.id', 'DESC')
                                       ->value('code') ?? "SENDIRI";

                    //C
                    
                    $result = DB::table('tblpackage_sponsorship')
                              ->leftjoin('tblpackage', 'tblpackage_sponsorship.package_id', 'tblpackage.id')
                              ->leftjoin('tblpayment_type', 'tblpackage_sponsorship.payment_type_id', 'tblpayment_type.id')
                              ->where('tblpackage_sponsorship.student_ic', $std->ic)
                              ->select('tblpackage.name AS package_name', 'tblpayment_type.name AS payment_type', 'tblpackage_sponsorship.amount')
                              ->first();

                    // Check if the result is null and set default values if it is
                    $data['sponsor_dtl'][$key] = $result ?? (object)[
                        'package_name' => '-',
                        'payment_type' => '-',
                        'amount' => '-'
                    ];

                    // Define the first part of the union
                    $query = DB::table('tblclaim')
                    ->leftjoin('tblclaimdtl', 'tblclaim.id', '=', 'tblclaimdtl.claim_id')
                    ->leftjoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', '=', 'tblstudentclaim.id')
                    ->where([
                        ['tblclaim.process_status_id', '=', 2],
                        ['tblstudentclaim.groupid', '=', 1],
                        ['tblclaim.student_ic', '=', $std->ic]
                    ])
                    ->select(DB::raw("IFNULL(SUM(tblclaimdtl.amount), 0) AS claim"), DB::raw('0 as payment'));

                    // Define the second part of the union
                    $subQuery = DB::table('tblpayment')
                    ->leftjoin('tblpaymentdtl', 'tblpayment.id', '=', 'tblpaymentdtl.payment_id')
                    ->leftjoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', '=', 'tblstudentclaim.id')
                    ->where([
                        ['tblpayment.process_status_id', '=', 2],
                        ['tblstudentclaim.groupid', '=', 1],
                        ['tblpayment.student_ic', '=', $std->ic]
                    ])
                    ->select(DB::raw('0 as claim'), DB::raw("IFNULL(SUM(tblpaymentdtl.amount), 0) AS payment"))
                    ->unionAll($query); // Here, use the Query Builder instance directly

                    // Now, wrap the subquery and calculate the balance
                    $data['balance'][$key] = DB::query()->fromSub($subQuery, 'sub')
                    ->select(DB::raw('SUM(claim) AS total_claim'), DB::raw('SUM(payment) AS total_payment'), DB::raw('SUM(claim) - SUM(payment) AS balance'))
                    ->get();

                }

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th>
                                        No
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        IC
                                    </th>
                                    <th>
                                        No. Matric
                                    </th>
                                    <th>
                                        Program
                                    </th>
                                    <th>
                                        Session
                                    </th>
                                    <th>
                                        Semester
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Sponsor
                                    </th>
                                    <th>
                                        Package
                                    </th>
                                    <th>
                                        Payment Method
                                    </th>
                                    <th>
                                        Fee (RM)
                                    </th>
                                    <th>
                                        Payment (RM)
                                    </th>
                                    <th>
                                        Balance (RM)
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">';
                            
                foreach($data['student'] as $key => $std){
                    //$registered = ($std->status == 'ACTIVE') ? 'checked' : '';

                    $content .= '
                    <tr>
                        <td>
                        '. $key+1 .'
                        </td>
                        <td>
                        '. $std->name .'
                        </td>
                        <td>
                        '. $std->ic .'
                        </td>
                        <td>
                        '. $std->progcode .'
                        </td>
                        <td>
                        '. $std->no_matric .'
                        </td>
                        <td>
                        '. $std->SessionName .'
                        </td>
                        <td>
                        '. $std->semester .'
                        </td>
                        <td>
                        '. $std->status .'
                        </td>
                        <td>';
                        if($data['sponsor'][$key] != null)
                        {

                            $content .= ''. $data['sponsor'][$key] .'';

                        }else{
                            $content .= '-';
                        }
            $content .= '</td>
                        <td>';
                        if($data['sponsor_dtl'][$key] != null)
                        {

                            $content .= ''. $data['sponsor_dtl'][$key]->package_name .'';

                        }else{
                            $content .= '-';
                        }
            $content .= '</td>
                        <td>';
                        if($data['sponsor_dtl'][$key] != null)
                        {

                            $content .= ''. $data['sponsor_dtl'][$key]->payment_type .'';

                        }else{
                            $content .= '-';
                        }
            $content .= '</td>';
                        foreach($data['balance'][$key] as $blc)
                        {

                            $content .= '<td>
                                            '. $blc->total_claim .'
                                        </td>
                                        <td>
                                            '. $blc->total_payment .'
                                        </td>
                                        <td>
                                            '. $blc->balance .'
                                        </td>';

                        }
        $content .='</tr>';
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

        return response()->json(['message' => 'Success', 'data' => $content]);

    }

    public function blockStudentArrears(Request $request)
    {

        $filtersData = $request->filtersData;

        $validator = Validator::make($request->all(), [
            'filtersData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        try{ 
            DB::beginTransaction();
            DB::connection()->enableQueryLog();

            try{

                $filter = json_decode($filtersData);

                if($filter->program == 'all')
                {

                    $program = DB::table('tblprogramme')->pluck('id');

                }else{

                    $program = DB::table('tblprogramme')->where('id', $filter->program)->pluck('id');
                    
                }

                $data['student'] = DB::table('students')
                                   ->leftjoin('sessions', 'students.session', 'sessions.SessionID')
                                   ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                                   ->leftjoin('tblstudent_status', 'students.status', 'tblstudent_status.id')
                                   ->whereIn('students.program', $program)
                                   ->when($filter->status != 'all', function ($query) use ($filter){
                                        return $query->where('students.status', $filter->status);
                                   })
                                   ->when($filter->session != 'all', function ($query) use ($filter){
                                        return $query->where('students.session', $filter->session);
                                    })
                                   ->select('students.*')
                                   ->get();

                foreach($data['student'] as $key => $std)
                {

                    $record = DB::table('tblpaymentdtl')
                    ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
                    ->leftJoin('tblprocess_type', 'tblpayment.process_type_id', 'tblprocess_type.id')
                    ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                    ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
                    ->where([
                        ['tblpayment.student_ic', $std->ic],
                        ['tblpayment.process_status_id', 2], 
                        ['tblstudentclaim.groupid', 1], 
                        ['tblpaymentdtl.amount', '!=', 0]
                        ])
                    ->select(DB::raw("'payment' as source"), 'tblprocess_type.name AS process', 'tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 
                    'tblpaymentdtl.amount',
                    'tblpayment.process_type_id', 'tblprogramme.progcode AS program', DB::raw('NULL as remark'));

                    $data['record'] = DB::table('tblclaimdtl')
                    ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                    ->leftJoin('tblprocess_type', 'tblclaim.process_type_id', 'tblprocess_type.id')
                    ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                    ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
                    ->where([
                        ['tblclaim.student_ic', $std->ic],
                        ['tblclaim.process_status_id', 2],  
                        ['tblstudentclaim.groupid', 1],
                        ['tblclaimdtl.amount', '!=', 0]
                        ])
                    ->unionALL($record)
                    ->select(DB::raw("'claim' as source"), 'tblprocess_type.name AS process', 'tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 
                    'tblclaimdtl.amount',
                    'tblclaim.process_type_id', 'tblprogramme.progcode AS program', 'tblclaim.remark')
                    ->orderBy('date')
                    ->get();

                    $val = 0;

                    foreach($data['record'] as $key => $req)
                    {

                        if(array_intersect([2,3,4,5,11], (array) $req->process_type_id) && $req->source == 'claim')
                        {

                            $data['total'][$key] = $val + $req->amount;

                            $val = $val + $req->amount;
                            

                        }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id) && $req->source == 'payment')
                        {

                            $data['total'][$key] = $val - $req->amount;

                            $val = $val - $req->amount;

                        }

                    }   

                    $data['sum3'] = end($data['total']);

                    if(in_array($std->semester, [7,8]))
                    {

                        if($data['sum3'] > 0)
                        {

                            DB::table('students')->where('ic', $std->ic)->update(['block_status' => 1]);

                        }else{

                            DB::table('students')->where('ic', $std->ic)->update(['block_status' => 0]);

                        }


                    }else{

                        

                        $data['sponsor'] = DB::table('tblpackage_sponsorship')
                                ->join('tblpackage', 'tblpackage_sponsorship.package_id', 'tblpackage.id')
                                ->join('tblpayment_type', 'tblpackage_sponsorship.payment_type_id', 'tblpayment_type.id')
                                ->where('student_ic', $std->ic)
                                ->select('tblpackage_sponsorship.*', 'tblpackage.name AS package', 'tblpayment_type.name AS type')
                                ->first();

                        if($data['sponsor'] != null) {

                            $data['package'] = DB::table('tblpayment_package')
                                            ->join('tblpackage', 'tblpayment_package.package_id', 'tblpackage.id')
                                            ->join('tblpayment_type', 'tblpayment_package.payment_type_id', 'tblpayment_type.id')
                                            ->join('tblpayment_program', 'tblpayment_package.id', 'tblpayment_program.payment_package_id')
                                            ->where([
                                                ['tblpayment_package.package_id', $data['sponsor']->package_id],
                                                ['tblpayment_package.payment_type_id', $data['sponsor']->payment_type_id],
                                                ['tblpayment_program.intake_id', $std->intake],
                                                ['tblpayment_program.program_id',$std->program]
                                            ])->select('tblpayment_package.*','tblpackage.name AS package', 'tblpayment_type.name AS type')->first();

                            $semester_column = 'semester_' . $std->semester; // e.g., this will be 'semester_2' if $user->semester is 2

                            if (isset($data['package']->$semester_column)) {
                                $data['value'] = $data['sum3'] - $data['package']->$semester_column;
                                // Do something with $semester_value
                            } else {
                                $data['value'] = 0;
                                // Handle case where the column is not set
                            }

                        }else{

                            $data['value'] = 0;

                        }

                        if($data['value'] > 0)
                        {

                            DB::table('students')->where('ic', $std->ic)->update(['block_status' => 1]);

                        }else{

                            DB::table('students')->where('ic', $std->ic)->update(['block_status' => 0]);

                        }

                    }

                }

                return response()->json(['message' => 'Success']);

                
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

    }

    public function studentCtos()
    {

        $data = $this->getStudentCtos();

        return view('finance.debt.student_ctos.studentCtos', compact('data'));

    }

    private function getStudentCtos()
    {

        $baseQuery = function () {
            return DB::table('tblstudent_ctos')
            ->leftjoin('students', 'tblstudent_ctos.student_ic', 'students.ic')
            ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
            ->leftjoin('users', 'tblstudent_ctos.user_ic', 'users.ic')
            ->select('tblstudent_ctos.*', 'students.name', 'students.ic', 'users.name AS addBy','students.no_matric', 'tblprogramme.progcode');
        };

        $data['CTOS']  = ($baseQuery)()
                         ->where('tblstudent_ctos.status', 0)
                         ->get();

        $data['CTOSRelease']  = ($baseQuery)()
                         ->where('tblstudent_ctos.status', 1)
                         ->get();

        return $data;

    }

    // public function importCtos(Request $request)
    // {

    //     // Validate the uploaded file
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls'
    //     ]);

    //     // Load the uploaded file
    //     $file = $request->file('file');
    //     $spreadsheet = IOFactory::load($file->getRealPath());

    //     // Get the first sheet of the Excel file
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $rows = $sheet->toArray();

    //     // Iterate over the rows starting from the second row (to skip the header)
    //     foreach ($rows as $index => $row) {
    //         if ($index == 0) continue; // Skip header row

    //         $student_ic = $row[0]; // Assuming the first column is student_ic

    //         // Insert the student_ic value into the student_ctos table
    //         DB::table('student_ctos')->insert([
    //             'student_ic' => $student_ic,
    //             'status' => 0,
    //             'date_ctos' => now(),
    //             'user_ic' => Auth::user()->ic
    //         ]);
    //     }

    //     return response()->json(['success' => 'Students imported successfully.']);

    // }

    public function importCtos(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
                'data' => 'required'
            ]);

            $datas = json_decode($request->data);

            // Load the uploaded file
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());

            // Get the first sheet of the Excel file
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Iterate over the rows starting from the second row (to skip the header)
            foreach ($rows as $index => $row) {
                if ($index == 0) continue; // Skip header row

                $student_ic = $row[0]; // Assuming the first column is student_ic

                if(DB::table('tblstudent_ctos')->where('student_ic', $student_ic)->exists())
                {
                    
                }else{

                    // Insert the student_ic value into the student_ctos table
                    DB::table('tblstudent_ctos')->insert([
                        'student_ic' => $student_ic,
                        'status' => 0,
                        'date_ctos' => $datas->date,
                        'user_ic' => Auth::user()->ic
                    ]);

                }

                
            }

            $data = $this->getStudentCtos();

            return response()->json(['success' => 'Students imported successfully.', 'data' => $data]);

        } catch (Exception $e) {
            // Return the error message as JSON
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function releaseCTOS(Request $request)
    {

        try{

            $request->validate([
                'id' => 'required',
                'date' => 'required|date'
            ]);

            DB::table('tblstudent_ctos')->where('id', $request->id)->update([
                'status' => 1,
                'date_release' => $request->date
            ]);

            $data = $this->getStudentCtos();

            return response()->json(['success' => 'CTOS released successfully.', 'data' => $data]);

        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function deleteCTOS(Request $request)
    {

        try{

            $request->validate([
                'id' => 'required'
            ]);

            DB::table('tblstudent_ctos')->where('id', $request->id)->update([
                'status' => 0,
                'date_release' => null
            ]);

            $data = $this->getStudentCtos();

            return response()->json(['success' => 'CTOS deleted successfully.', 'data' => $data]);

        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function vehicleRecord()
    {

        $data = [

            'vehicles' => DB::table('tblvehicle')->get(),
            'year' => array_reverse(range(2000, now()->year))

        ];

        foreach($data['vehicles'] as $key => $vehicle)
        {
            $odometer = DB::table('tblvehicle_service')->where('vehicle_id', $vehicle->id)->orderBy('id', 'DESC')->first();

            $data['nextService'][$key] = ($odometer) ? $odometer->odometer + 5000 : 0;

            if ($odometer) {
                $data['nextService2'][$key] = Carbon::parse($odometer->date_of_service)->addMonths(3)->toDateString();
            } else {
                $data['nextService2'][$key] = null;
            }
        }


        return view('finance.asset.vehicleRecord.index', compact('data'));

    }

    public function storeVehicle(Request $request)
    {

        try{

            if(isset($request->idS))
            {

                $request->validate([
                    'type' => 'required',
                    'brand' => 'required',
                    'model' => 'required',
                    'colour' => 'required',
                    'year' => 'required|integer',
                    'reNo' => 'required',
                    'roadtax' => 'required|date',
                ]);

                DB::table('tblvehicle')->where('id', $request->idS)->update([
                    'type' => $request->type,
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'colour' => $request->colour,
                    'year' => $request->year,
                    'registration_number' => $request->reNo,
                    'date_of_roadtax' => $request->roadtax
                ]);

                $message = 'Vehicle updated successfully.';

            }
            else{

                $request->validate([
                    'type' => 'required',
                    'brand' => 'required',
                    'model' => 'required',
                    'colour' => 'required',
                    'year' => 'required|integer',
                    'reNo' => 'required',
                    'roadtax' => 'required|date',
                ]);

                DB::table('tblvehicle')->insert([
                    'type' => $request->type,
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'colour' => $request->colour,
                    'year' => $request->year,
                    'registration_number' => $request->reNo,
                    'date_of_roadtax' => $request->roadtax
                ]);

                $message = 'Vehicle added successfully.';

            }

    
            return back()->with('success', $message);

        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function deleteVehicle(Request $request)
    {

        try{

            DB::table('tblvehicle')->where('id', $request->id)->delete();

            return response()->json(['success' => 'Vehicle deleted successfully.']);

        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function updateVehicle(Request $request)
    {

        $data['car'] = DB::table('tblvehicle')->where('id', $request->id)->first();

        $data['year'] = array_reverse(range(2000, now()->year));

        return view('finance.asset.vehicleRecord.getVehicle', compact('data'))->with('id', $request->id);

    }

    public function serviceRecord()
    {

        return view('finance.asset.vehicleRecord.getServiceRecord')->with('id', request()->id);

    }

    public function odometerRecord()
    {

        return view('finance.asset.vehicleRecord.getOdometerRecord')->with('id', request()->id);

    }

    public function getServiceList(Request $request)
    {

        $data['service'] = DB::table('tblvehicle_service')->where('vehicle_id', $request->id)->get();

        foreach($data['service'] as $key => $srv)
        {

            $data['details'][$key] = DB::table('tblservice_details')->where('service_record_id', $srv->id)->get();

        }

        $content = "";

        foreach($data['service'] as $key => $srv)
        {
            $content .= '<div class="card mb-3" id="stud_info">
                <div class="card-body">';

            $content .= '<div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="width: 25%">Service Date</td>
                                <td style="width: 10%">'. $srv->date_of_service .'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="width: 25%">Odometer (KM)</td>
                                <td style="width: 10%">'. $srv->odometer .'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';

            $content .= '<div class="row">
                <div class="col-md-12">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="width: 2%">Company\'s Name & Address</td>
                                <td style="width: 8%">'. $srv->company .'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';

            
            $content .= '<div class="row">
                <div class="col-md-12">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="width: 2%">Service Type</td>
                                <td style="width: 6.7%">';
            
                                // Loop through service details and concatenate the 'type_of_services'
                                foreach ($data['details'][$key] as $detail) {
                                    $content .= $detail->type_of_services. ' - ' . $detail->notes . ' (RM' . $detail->amount . ')' . '<br>';  // Add each service type with line break
                                }
            
            $content .= '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';
        
            

            $content .= '<div class="row">
                <div class="col-md-12">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="width: 2%">Amount (RM)</td>
                                <td style="width: 6.7%">'. $srv->amount .'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';

            $content .= '<div class="row">
                <div class="col-md-12">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="width: 2%">Note</td>
                                <td style="width: 6%">'. $srv->notes .'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';

            $content .= '<div class="form-group pull-right">
                <input type="submit" name="submitService" class="btn btn-warning pull-right" value="Delete" onclick="deleteRecord(\''. $srv->id .'\')">
            </div>';

            $content .= '</div></div>';

        }

        return $content;


    }

    public function getOdometerRecord(Request $request)
    {

        $data['service'] = DB::table('tblvehicle_odometer')->where('vehicle_id', $request->id)->get();

        $content = "";
                    $content .= '<table id="table_projectprogress_course" class="table table-striped projects display dataTable no-footer" style="width: 100%;"><thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th>
                                            Odometer (KM)
                                        </th>
                                        <th>
                                            Date
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($data['service'] as $key => $dtl){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                    $content .= '
                        <tr>
                            <td style="width: 1%">
                            '. $key+1 .'
                            </td>
                            <td>
                            '. $dtl->odometer .'
                            </td>
                            <td>
                            '. $dtl->created_at .'
                            </td>
                            <td>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deleteOdometer('. $dtl->id .')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>
                            </td>
                        </tr>
                        ';
                        }
                    $content .= '</tbody>';
                    $content .= '<tfoot>
                                </tfoot>
                                </table>';

        return $content;


    }

    public function storeService(Request $request)
    {

        $data = json_decode($request->formData);

        // Check if $data is null or if decoding failed
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid JSON data.'
            ], 200);
        }

        // If data is not null, proceed with further processing
        // Example:
        if (!empty($data->date) && !empty($data->meter) && !empty($data->address) && !empty($data->amount) && !empty($data->note)) {

            // Now handle the checkbox and textarea values
            if (!empty($data->checkboxes)) {
                foreach ($data->checkboxes as $checkbox) {
                    // Ensure the textarea value is not empty
                    if (empty($checkbox->textareaValue)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Textarea value for the checkbox is missing.'
                        ], 200);
                    }
                }
            }
            
            $id = DB::table('tblvehicle_service')->insertGetId([
                    'vehicle_id' => $data->id,
                    'date_of_service' => $data->date,
                    'odometer' => $data->meter,
                    'company' => $data->address,
                    'amount' => $data->amount,
                    'notes' => $data->note
            ]);

            // Now handle the checkbox and textarea values
            if (!empty($data->checkboxes)) {
                foreach ($data->checkboxes as $checkbox) {
                    DB::table('tblservice_details')->insert([
                        'service_record_id' => $id,  // Foreign key to the main record
                        'type_of_services' => $checkbox->checkboxValue,  // Checkbox value
                        'notes' => $checkbox->textareaValue,  // Associated textarea value
                        'amount' => $checkbox->inputValue
                    ]);
                }
            }


            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully processed.'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Required fields are missing.'
            ], 200);
        }


    }

    public function storeOdometerRecord(Request $request)
    {

        $data = json_decode($request->formData);

        // Check if $data is null or if decoding failed
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid JSON data.'
            ], 200);
        }

        // If data is not null, proceed with further processing
        // Example:
        if (!empty($data->odometer)) {
            
            $id = DB::table('tblvehicle_odometer')->insertGetId([
                    'vehicle_id' => $data->id,
                    'odometer' => $data->odometer
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully processed.'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Required fields are missing.'
            ], 200);
        }


    }

    public function deleteRecord(Request $request)
    {

        try{

            $id = DB::table('tblvehicle_service')->where('id', $request->id)->value('vehicle_id');

            DB::table('tblvehicle_service')->where('id', $request->id)->delete();

            DB::table('tblservice_details')->where('service_record_id', $request->id)->delete();

            return response()->json(['success' => 'Vehicle deleted successfully.', 'id' => $id]);

        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function deleteOdometerRecord(Request $request)
    {

        try{

            $id = DB::table('tblvehicle_odometer')->where('id', $request->id)->value('vehicle_id');

            DB::table('tblvehicle_odometer')->where('id', $request->id)->delete();

            return response()->json(['success' => 'Odometer record deleted successfully.', 'id' => $id]);

        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function vehicleReport()
    {

        return view('finance.asset.vehicleRecord.report.vehicleReport');

    }

    public function getVehicleReport()
    {

        $data['vehicle'] = DB::table('tblvehicle')->get();

        foreach($data['vehicle'] As $key => $vehicle)
        {

            $data['service'][$key] = DB::table('tblvehicle_service')->where('vehicle_id', $vehicle->id)->get();

            foreach($data['service'][$key] as $key2 => $srv)
            {

                $data['details'][$key][$key2] = DB::table('tblservice_details')->where('service_record_id', $srv->id)->get();

            }

            $data['odometer'][$key] = DB::table('tblvehicle_odometer')->where('vehicle_id', $vehicle->id)->get();

        }

        return response()->json(['data' => $data]);

    }

    public function blockList()
    {

        $data['block'] = DB::table('students')->where('block_status', 1)->get();

        return view('finance.student.block_list.blockList', compact('data'));
        
    }

}
