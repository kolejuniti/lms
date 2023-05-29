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

        $data['program'] = DB::table('tblprogramme')->get();

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

        $data['program'] = DB::table('tblprogramme')->get();

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
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
                           ->where('ic', $request->student)->first();

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->get();

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

    public function studentClaim()
    {

        return view('finance.payment.claim');

    }

    public function getStudentClaim(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions', 'students.session', 'sessions.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'tblprogramme.id AS programID','sessions.SessionName AS session')
                           ->where('ic', $request->student)->first();

        $data['claim'] = DB::table('tblstudentclaimpackage')
                         ->where([
                            ['program_id', $data['student']->programID],
                            ['intake_id', $data['student']->intake]
                            ])->join('tblstudentclaim', 'tblstudentclaimpackage.claim_id', 'tblstudentclaim.id')
                         ->select('tblstudentclaim.id', 'tblstudentclaim.name')->get();

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

            //check if subject exists
            if(DB::table('student_subjek')->where([['student_ic', $student->ic],['sessionid', $student->session],['semesterid', $student->semester]])->exists())
            {
                $alert =  ['message' => 'Success! Subject for student has already registered for this semester!'];

            }else{

                $subject = DB::table('subjek')->where([
                    ['prgid','=', $student->program],
                    ['semesterid','=', $student->semester]
                ])->get();

                foreach($subject as $key)
                {
                    student::create([
                        'student_ic' => $student->ic,
                        'courseid' => $key->sub_id,
                        'sessionid' => $student->session,
                        'semesterid' => $key->semesterid,
                        'status' => 'ACTIVE'
                    ]);
                }

                $alert = ['message' => 'Success'];

            }

            if($student->semester != 1)
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
                                    ['tbltabungkhas.package_id', $spn->package_id]
                                ])->select('tbltabungkhas.*', 'tblprocess_type.code');

                        if($tabungs->exists())
                        {
                            $tabung = $tabungs->get();

                            foreach($tabung as $key => $tbg)
                            {
                                if($student->intake == $tbg->intake_id)
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

            }else{

                DB::table('students')->where('ic', $student->ic)->update([
                    'date' => date('Y-m-d')
                ]);

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
                           ->select('students.*', 'students.program AS programID', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
                           ->where('ic', $request->student)->first();

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->get();

        $data['tuition'] = DB::table('tblclaimdtl')
                            ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                            ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                            ->where('tblclaim.student_ic', $request->student)
                            ->where('tblclaim.program_id', $data['student']->programID)
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
                ['tblpaymentdtl.claimDtl_id', $tsy->id],
                ['tblpayment.student_ic', $request->student],
                ['tblpaymentdtl.claim_type_id', $tsy->claim_package_id],
                ['tblpayment.session_id', $tsy->session_id],
                ['tblpayment.semester_id', $tsy->semester_id],
                ['tblpayment.program_id', $data['student']->programID],
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

    public function getReceipt(Request $request)
    {
        $data['payment'] = DB::table('tblpayment')->where('id', $request->id)->first();

        $detail = DB::table('tblpaymentdtl')
                          ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                          ->where('tblpaymentdtl.payment_id', $request->id)
                          ->select('tblpaymentdtl.*', 'tblstudentclaim.name', 'tblstudentclaim.groupid');
                          
        $data['detail'] = $detail->get();

        $data['total'] = $detail->sum('tblpaymentdtl.amount');

        $method = DB::table('tblpaymentmethod')
                          ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                          ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
                          ->where('tblpaymentmethod.payment_id', $request->id)
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

        return view('finance.payment.receipt', compact('data'));

    }

    public function getReceipt2(Request $request)
    {
        $data['payment'] = DB::table('tblpayment')->where('id', $request->id)->first();

        $detail = DB::table('tblpaymentdtl')
                          ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                          ->where('tblpaymentdtl.payment_id', $request->id)
                          ->select('tblpaymentdtl.*', 'tblstudentclaim.name', 'tblstudentclaim.groupid');
                          
        $data['detail'] = $detail->get();

        $data['total'] = $detail->sum('tblpaymentdtl.amount');

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
        $data['payment'] = DB::table('tblclaim')->where('id', $request->id)->first();

        $detail = DB::table('tblclaimdtl')
                          ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                          ->where('tblclaimdtl.claim_id', $request->id)
                          ->select('tblclaimdtl.*', 'tblstudentclaim.name', 'tblstudentclaim.groupid');
                          
        $data['detail'] = $detail->get();

        $data['total'] = $detail->sum('tblclaimdtl.amount');

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
                   ->where([['tblpayment.student_ic', ''],['tblpayment.process_status_id',2]])
                   ->select('tblpayment.*', 'tblsponsor_library.name', 'tblsponsor_library.code')->get();

        return view('finance.sponsorship.payment', compact('payment'));

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

                    $data['bank'] = DB::table('tblpayment_bank')->get();
 
                
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
                           ->select('students.*', 'students.program AS programID','tblstudent_status.name AS status', 'tblprogramme.progname AS program')
                           ->where('ic', $request->student)->first();

        $data['tuition'] = DB::table('tblclaimdtl')
                            ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                            ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                            ->where('tblclaim.student_ic', $request->student)
                            ->where('tblclaim.program_id', $data['student']->programID)
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
                ['tblpaymentdtl.claimDtl_id', $tsy->id],
                ['tblpayment.student_ic', $request->student],
                ['tblpaymentdtl.claim_type_id', $tsy->claim_package_id],
                ['tblpayment.session_id', $tsy->session_id],
                ['tblpayment.semester_id', $tsy->semester_id],
                ['tblpayment.program_id', $data['student']->programID],
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

                    $count = DB::table('tblpayment')->where([['sponsor_id', $payment->id],['process_status_id', 2]])->get();

                    if(count($count) > 0)
                    {

                        return ["message" => "Student sponsorship has already been paid!"];

                    }else{

                        if($payment->total > $pymdetails->amount)
                        {

                            return ["message" => "Amount cannot exceed " . $pymdetails->amount . "!"];

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

                    /*$ref_no = DB::table('tblref_no')
                      ->join('tblpayment', 'tblref_no.process_type_id', 'tblpayment.process_type_id')
                      ->where('tblpayment.id', $payment->id)
                      ->select('tblref_no.*','tblpayment.sponsor_id')->first();

                    DB::table('tblref_no')->where('id', $ref_no->id)->update([
                        'ref_no' => $ref_no->ref_no + 1
                    ]);*/

                    $totalall = DB::table('tblpayment')->where('id', $payment->id)->first();

                    $balance =  $totalall->amount - $payment->sum2;

                    DB::table('tblpayment')->where('id', $payment->id)->update([
                        'process_status_id' => 2,
                        'ref_no' => null,
                        'amount' => $balance
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
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
                           ->where('ic', $request->student)->first();

        $record = DB::table('tblpaymentdtl')
        ->leftJoin('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
        ->leftJoin('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
        ->where([
            ['tblpayment.student_ic', $request->student],
            ['tblpayment.process_status_id', 2], 
            ['tblstudentclaim.groupid', 1], 
            ['tblpaymentdtl.amount', '!=', 0]
            ])
        ->select('tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progname AS program');

        $data['record'] = DB::table('tblclaimdtl')
        ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
        ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
        ->where([
            ['tblclaim.student_ic', $request->student],
            ['tblclaim.process_status_id', 2],  
            ['tblstudentclaim.groupid', 1],
            ['tblclaimdtl.amount', '!=', 0]
            ])
        ->unionALL($record)
        ->select('tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progname AS program')
        ->orderBy('date')
        ->get();

        $val = 0;
        $data['sum1'] = 0;
        $data['sum2'] = 0;

        foreach($data['record'] as $key => $req)
        {

            if(array_intersect([2,3,4,5], (array) $req->process_type_id))
            {

                $data['total'][$key] = $val + $req->amount;

                $val = $val + $req->amount;

                $data['sum1'] += $req->amount;
                

            }elseif(array_intersect([1,6,7,8,9,15,16,17,18,19], (array) $req->process_type_id))
            {

                $data['total'][$key] = $val - $req->amount;

                $val = $val - $req->amount;

                $data['sum2'] += $req->amount;

            }

        }

        $data['sum3'] = end($data['total']);


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
        ->select('tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progname AS program');

        $data['record2'] = DB::table('tblclaimdtl')
        ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
        ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
        ->where([
            ['tblclaim.student_ic', $request->student],
            ['tblclaim.process_status_id', 2],  
            ['tblstudentclaim.groupid', 4],
            ['tblclaimdtl.amount', '!=', 0]
            ])        ->unionALL($record2)
        ->select('tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progname AS program')
        ->orderBy('date')
        ->get();

        $val = 0;
        $data['sum1_2'] = 0;
        $data['sum2_2'] = 0;

        foreach($data['record2'] as $key => $req)
        {

            if(array_intersect([2,3,4,5], (array) $req->process_type_id))
            {

                $data['total2'][$key] = $val + $req->amount;

                $val = $val + $req->amount;

                $data['sum1_2'] += $req->amount;
                

            }elseif(array_intersect([1,6,7,8,9,15,16,17,18,19], (array) $req->process_type_id))
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
        ->select('tblpayment.ref_no','tblpayment.date', 'tblstudentclaim.name', 'tblpaymentdtl.amount', 'tblpayment.process_type_id', 'tblprogramme.progname AS program');

        $data['record3'] = DB::table('tblclaimdtl')
        ->leftJoin('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
        ->leftJoin('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
        ->leftjoin('tblprogramme', 'tblclaim.program_id', 'tblprogramme.id')
        ->where([
            ['tblclaim.student_ic', $request->student],
            ['tblclaim.process_status_id', 2],  
            ['tblstudentclaim.groupid', 5],
            ['tblclaimdtl.amount', '!=', 0]
            ])        ->unionALL($record3)
        ->select('tblclaim.ref_no','tblclaim.date', 'tblstudentclaim.name', 'tblclaimdtl.amount', 'tblclaim.process_type_id', 'tblprogramme.progname AS program')
        ->orderBy('date')
        ->get();

        $val = 0;
        $data['sum1_3'] = 0;
        $data['sum2_3'] = 0;

        foreach($data['record3'] as $key => $req)
        {

            if(array_intersect([2,3,4,5], (array) $req->process_type_id))
            {

                $data['total3'][$key] = $val + $req->amount;

                $val = $val + $req->amount;

                $data['sum1_3'] += $req->amount;
                

            }elseif(array_intersect([1,6,7,8,9,15,16,17,18,19], (array) $req->process_type_id))
            {

                $data['total3'][$key] = $val - $req->amount;

                $val = $val - $req->amount;

                $data['sum2_3'] += $req->amount;

            }

        }

        $data['sum3_3'] = end($data['total3']);

        return view('finance.report.statementGetStudent', compact('data'));



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
        ->select('tblpayment.id', 'tblpayment.ref_no','tblpayment.date', 'tblpayment.process_type_id', 'tblpayment.amount', 'tblprocess_status.name AS status', 'students.no_matric', 'students.name AS name', 'students.ic');

        $data['student'] = DB::table('tblclaim')
        ->join('students', 'tblclaim.student_ic', 'students.ic')
        ->join('tblprocess_status', 'tblclaim.process_status_id', 'tblprocess_status.id')
        ->join('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
        ->where('tblclaim.ref_no', 'LIKE', $request->refno."%")
        ->where('tblclaim.process_status_id', 2)
        ->unionALL($reg)
        ->select('tblclaim.id', 'tblclaim.ref_no','tblclaim.date', 'tblclaim.process_type_id', DB::raw('SUM(tblclaimdtl.amount) AS amount'), 'tblprocess_status.name AS status', 'students.no_matric', 'students.name AS name', 'students.ic')
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
        ->select('tblpayment.id', 'tblpayment.ref_no','tblpayment.date', 'tblpayment.process_type_id', 'tblpayment.amount', 'tblprocess_status.name AS status', 'students.no_matric', 'students.name AS name', 'students.ic');

        $reg2 = DB::table('tblclaim')
        ->join('students', 'tblclaim.student_ic', 'students.ic')
        ->leftjoin('tblprocess_status', 'tblclaim.process_status_id', 'tblprocess_status.id')
        ->leftjoin('tblclaimdtl', 'tblclaim.id', 'tblclaimdtl.claim_id')
        ->where('students.name', 'LIKE', $request->search."%")
        ->orwhere('students.ic', 'LIKE', $request->search."%")
        ->orwhere('students.no_matric', 'LIKE', $request->search."%")
        ->where('tblclaim.process_status_id', 2)
        ->groupBy('tblclaim.id')
        ->select('tblclaim.id', 'tblclaim.ref_no','tblclaim.date', 'tblclaim.process_type_id', DB::raw('SUM(tblclaimdtl.amount) AS amount'), 'tblprocess_status.name AS status', 'students.no_matric', 'students.name AS name', 'students.ic');
        
        $data['student'] = $reg->union($reg2)->get();

        }else{

            return false;

        }

        return view('finance.report.getReceiptList', compact('data'));

    }

    public function getReceiptProof(Request $request)
    {

        if(array_intersect([2,3,4,5], (array) $request->type))
        {

            return redirect()->route('receipt3', ['id' => $request->id]);

        }elseif(array_intersect([1,6,7,8,9,15,16,17,18,19], (array) $request->type)){

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
                   ->select('tblpayment.*', 'students.name', 'students.no_matric', 'students.status', 'students.program', 'students.semester')
                   ->whereBetween('tblpayment.add_date', [$request->from, $request->to])
                   ->where('tblpayment.process_status_id', 2)
                   ->whereNotNull('tblpayment.ref_no')
                   ->get();

        $sponsor = DB::table('tblpayment')
                   ->join('students', 'tblpayment.student_ic', 'students.ic')
                   ->select('tblpayment.*', 'students.name', 'students.no_matric', 'students.status', 'students.program', 'students.semester')
                   ->whereBetween('tblpayment.add_date', [$request->from, $request->to])
                   ->where('tblpayment.process_status_id', 2)
                   ->whereNotNull('tblpayment.student_ic')
                   ->whereNotNull('tblpayment.payment_sponsor_id')
                   ->get();

        $data['program'] = DB::table('tblprogramme')->get();

        foreach($payment as $pym)
        {

            if($pym->status == 1 && $pym->sponsor_id == null)
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

            }elseif($pym->status == 2 && $pym->sponsor_id == null && $pym->semester == 1)
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
                ->whereIn('tblstudentclaim.groupid', [5])->exists())
                {


                    $data['other'][] = $pym;

                    $data['otherStudDetail'][] = DB::table('tblpaymentdtl')
                                            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                            ->where('tblpaymentdtl.payment_id', $pym->id)
                                            ->whereIn('tblstudentclaim.groupid', [5])
                                            ->where('tblpaymentdtl.amount', '!=', 0)
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

                //newexcess

                if($pym->process_type_id == 6)
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

                //newtabungkhas

                if($pym->process_type_id == 6)
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

            }elseif($pym->status == 2 && $pym->sponsor_id == null && $pym->semester != 1)
            {

                //oldstudent

                if(DB::table('tblpaymentdtl')
                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                ->where('tblpaymentdtl.payment_id', $pym->id)
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

                //OTHER

                if(DB::table('tblpaymentdtl')
                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                ->where('tblpaymentdtl.payment_id', $pym->id)
                ->whereIn('tblstudentclaim.groupid', [5])->exists())
                {


                    $data['other'][] = $pym;

                    $data['otherStudDetail'][] = DB::table('tblpaymentdtl')
                                                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                                                ->where('tblpaymentdtl.payment_id', $pym->id)
                                                ->whereIn('tblstudentclaim.groupid', [5])
                                                ->where('tblpaymentdtl.amount', '!=', 0)
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

                //oldexcess

                if($pym->process_type_id == 6)
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

                //oldtabungkhas

                if($pym->process_type_id == 6)
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

            }elseif($pym->status == 4 && $pym->sponsor_id == null)
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

            }elseif($pym->status == 8 && $pym->sponsor_id == null)
            {

                //graduate

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
        
        $other = DB::table('tblpaymentdtl')
        ->join('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
        ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
        ->join('tblpaymentmethod', 'tblpayment.id', 'tblpaymentmethod.payment_id')
        ->leftjoin('tblpayment_bank', 'tblpaymentmethod.bank_id', 'tblpayment_bank.id')
        ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
        ->whereBetween('tblpayment.add_date', [$request->from, $request->to])
        ->where('tblpayment.process_status_id', 2)
        ->select('tblpayment.*', 'tblstudentclaim.groupid', 'tblpaymentdtl.amount', 'tblpaymentmethod.no_document', 'tblpayment_method.name AS method', 'tblpayment_bank.name AS bank')
        ->groupBy('tblpaymentdtl.id')->get();


        foreach($other as $ot)
        {
            if(array_intersect([1], (array) $ot->process_type_id) && array_intersect([2], (array) $ot->groupid) && $ot->amount != 0)
            {
                $data['hostel'][] = $ot;

            }elseif(array_intersect([1], (array) $ot->process_type_id) && array_intersect([3], (array) $ot->groupid) && $ot->amount != 0)
            {
                $data['convo'][] = $ot;

            }elseif(array_intersect([1], (array) $ot->process_type_id) && array_intersect([4], (array) $ot->groupid) && $ot->amount != 0)
            {
                $data['fine'][] = $ot;

            }

        }
        
        if(isset($request->print))
        {

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

        $data['oldStudent'] = [];
        $data['oldStudentTotal'] = [];

        $data['debit'] = [];
        $data['debitTotal'] = [];

        $data['fine'] = [];
        $data['fineTotal'] = [];

        
        $data['other'] = [];
        $data['otherDetail'] = [];
        $data['otherTotal'] = [];
        $data['otherTotals'] = [];

        $data['creditFee'] = [];
        $data['creditFeeTotal'] = [];

        $data['creditFine'] = [];
        $data['creditFineTotal'] = [];

        $data['creditDiscount'] = [];
        $data['creditDiscountTotal'] = [];

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
                  ->get();

        //dd($charge);

        $data['program'] = DB::table('tblprogramme')->get();

        $data['otherCharge'] = DB::table('tblstudentclaim')->where('groupid', 5)->get();

        foreach($charge as $crg)
        {

            if($crg->process_type_id == 2 && $crg->process_status_id == 2 && $crg->semester_id == 1)
            {

                $data['newStudent'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
           
                    if($crg->program == $prg->id)
                    {

                        $data['newStudentTotal'][$key] =+  collect($data['newStudent'])->sum('amount');

                    }else{

                        $data['newStudentTotal'][$key] = 0;

                    }

                }

            }elseif($crg->process_type_id == 2 && $crg->process_status_id == 2 && $crg->semester_id >= 2)
            {

                $data['oldStudent'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
           
                    if($crg->program == $prg->id)
                    {

                        $data['oldStudentTotal'][$key] =+  collect($data['oldStudent'])->sum('amount');

                    }else{

                        $data['oldStudentTotal'][$key] = 0;

                    }

                }

            }elseif($crg->process_type_id == 4 && $crg->process_status_id == 2 && $crg->groupid == 1)
            {

                $data['debit'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
           
                    if($crg->program == $prg->id)
                    {

                        $data['debitTotal'][$key] =+  collect($data['debit'])->sum('amount');

                    }else{

                        $data['debitTotal'][$key] = 0;

                    }

                }

            }elseif($crg->process_type_id == 4 && $crg->process_status_id == 2 && $crg->groupid == 4)
            {

                $data['fine'][] = $crg;

            }elseif($crg->process_type_id == 4 && $crg->process_status_id == 2 && $crg->groupid == 5)
            {

                //graduate

                $data['other'][] = $crg;

                $data['otherDetail'][] = DB::table('tblclaimdtl')->where('claim_id', $crg->id)->get();

                //program

                foreach($data['otherCharge'] as $key => $crg)
                {
                    foreach($data['otherDetail'] as $keys => $dtl)
                    {

                        if($dtl->claim_package_id == $crg->id)
                        {

                            $data['otherTotal'][$key][$keys] =+  collect($data['otherDetail'][$keys])->sum('amount');

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
           
                    if($crg->program == $prg->id)
                    {

                        $data['creditFeeTotal'][$key] =+  collect($data['creditFee'])->sum('amount');

                    }else{

                        $data['creditFeeTotal'][$key] = 0;

                    }

                }

            }elseif($crg->process_type_id == 5 && $crg->process_status_id == 2 && $crg->groupid != 1)
            {

                $data['creditFine'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
           
                    if($crg->program == $prg->id)
                    {

                        $data['creditFineTotal'][$key] =+  collect($data['creditFine'])->sum('amount');

                    }else{

                        $data['creditFineTotal'][$key] = 0;

                    }

                }

            }elseif($crg->process_type_id == 5 && $crg->process_status_id == 2 && $crg->groupid == 1 && $crg->reduction_id > 5)
            {

                $data['creditDiscount'][] = $crg;

                //program

                foreach($data['program'] as $key => $prg)
                {
           
                    if($crg->program == $prg->id)
                    {

                        $data['creditDiscountTotal'][$key] =+  collect($data['creditDiscount'])->sum('amount');

                    }else{

                        $data['creditDiscountTotal'][$key] = 0;

                    }

                }

            }
            
        }

        if(isset($request->print))
        {

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
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
                           ->where('ic', $request->student)->first();

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->get();

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

        $data['session'] = DB::table('sessions')->get();

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

    public function Payment()
    {
        $data['package'] = DB::table('tblpackage')->get();

        $data['type'] = DB::table('tblpayment_type')->get();

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
                              ->join('sessions', 'tblpayment_program.session_id', 'sessions.SessionID')
                              ->where('tblpayment_program.payment_package_id', $request->id)
                              ->select('tblprogramme.*', 'sessions.SessionName', 'tblpayment_program.id')
                              ->get();

        $data['unregistered'] = DB::table('tblprogramme')->get();

        $data['session'] = DB::table('sessions')->get();

        $data['id'] = $request->id;

        return view('finance.package.getProgramPayment', compact('data'));
    }

    public function registerPRGPYM(Request $request)
    {
   
        DB::table('tblpayment_program')->insert([
            'payment_package_id' => $request->id,
            'program_id' => $request->prg,
            'session_id' => $request->session
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
}
