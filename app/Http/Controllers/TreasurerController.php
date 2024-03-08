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

class TreasurerController extends Controller
{
    public function dashboard()
    {

        return view('dashboard');

    }

    public function creditNote()
    {

        return view('treasurer.payment.credit');

    }

    public function getStudentCredit(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $first = DB::table('students')
                 ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                 ->where('students.ic', $request->student)
                 ->select('tblprogramme.*');

        $second = DB::table('student_program')
                 ->join('tblprogramme', 'student_program.program_id', 'tblprogramme.id')
                 ->where('student_program.student_ic', $request->student)
                 ->select('tblprogramme.*');

        $data['program'] = $second->unionAll($first)->get();

        return view('treasurer.payment.creditGetStudent', compact('data'));

    }

    public function storeCredit(Request $request)
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
                
                if($payment->discount != null && $payment->remark != null)
                {
                    $stddetail = DB::table('students')->where('ic', $payment->ic)->first();

                    $id = DB::table('tblclaim')->insertGetId([
                        'student_ic' => $payment->ic,
                        'date' => date('Y-m-d'),
                        'ref_no' => null,
                        'session_id' => $stddetail->session,
                        'semester_id' => $stddetail->semester,
                        'program_id' => $payment->program,
                        'process_status_id' => 1,
                        'process_type_id' => 5,
                        'remark' => $payment->remark,
                        'reduction_id' => $payment->discount,
                        'add_staffID' => Auth::user()->ic,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => Auth::user()->ic,
                        'mod_date' => date('Y-m-d')
                    ]);

                    $data['claim'] = DB::table('tblclaimdtl')
                           ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                           ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                           ->where('tblclaim.student_ic', $payment->ic)
                           ->where('tblclaim.program_id', $payment->program)
                           ->where('tblclaim.process_status_id', 2)
                           ->select('tblclaimdtl.*', 'tblclaim.session_id', 'tblclaim.semester_id', 'tblstudentclaim.name')->get();

                    foreach($data['claim'] as $key => $clm)
                    {

                        $a = $clm->amount;

                        $data['amount'][] = $a;

                        $balance = DB::table('tblpaymentdtl')
                        ->join('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
                        ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                        ->where([
                            ['tblpaymentdtl.claimDtl_id', $clm->id],
                            ['tblpayment.student_ic', $payment->ic],
                            ['tblpayment.program_id', $payment->program],
                            ['tblpaymentdtl.claim_type_id', $clm->claim_package_id],
                            ['tblpayment.session_id', $clm->session_id],
                            ['tblpayment.semester_id', $clm->semester_id],
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

                    $content = "";
                    $content .= '<thead>
                                    <tr>
                                        <th style="width: 1%">
                                            No.
                                        </th>
                                        <th style="width: 10%">
                                            About
                                        </th>
                                        <th style="width: 10%">
                                            Amount
                                        </th>
                                        <th style="width: 5%">
                                            Unit
                                        </th>
                                        <th style="width: 10%">
                                            Balance
                                        </th>
                                        <th style="width: 20%">
                                            Discount
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table">';
                                
                    foreach($data['claim'] as $key => $clm){
                    //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                        if($data['balance'][$key] != 0)
                        {
                        $content .= '
                            <tr>
                                <td>
                                '. $key+1 .'
                                </td>
                                <td>
                                '. $clm->name .'
                                </td>
                                <td>
                                '. $data['amount'][$key] .'
                                </td>
                                <td>
                                '. $clm->unit .'
                                </td>
                                <td>
                                '. number_format((float)$data['balance'][$key], 4, '.', '') .'
                                </td>
                                <td>
                                    <div class="col-md-12" id="payment-card">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="phyid[]" id="phyid[]" value="'. $clm->id .'" hidden>';
                                            if($data['balance'][$key] <= 0)
                                            {
                                            $content .= '<input readonly type="number" class="form-control" name="payment[]" id="payment[]" step="0.01" max="'. $data['balance'][$key] .'">';
                                            }else{
                                            $content .= '<input type="number" class="form-control" name="payment[]" id="payment[]" step="0.01" max="'. $data['balance'][$key] .'">';
                                            }
                            $content .='</div>
                                    </div> 
                                </td>
                            </tr>
                            ';
                            }
                        }
                        $content .= '</tbody>';
                        $content .= '<tfoot>
                        <tr>
                            <td>
                            
                            </td>
                            <td>
                            TOTAL AMOUNT
                            </td>
                            <td>
                            :
                            </td>
                            </td>
                            
                            <td>
                            <td>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="text_sum" id="text_sum" readonly>
                                </div> 
                            </td>
                            <td>
                                <div class="col-md-6" hidden>
                                     <input type="number" class="form-control" name="sum" id="sum" value="">
                                </div> 
                            </td>
                        </tr>
                        </tfoot>
                        
                        <script>
                        $(\'input[id="payment[]"]\').keyup(function(){

                            var sum = 0;
                            $(\'input[id="payment[]"]\').each(function() {
                                sum += Number($(this).val());
                            });

                            $(\'#sum2\').val(sum);
                            $(\'#text_sum\').val(sum);

                        });
                        </script>';
                
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

        return ["message" => "Success", "data" => $id, "claim" => $content];

    }

    public function confirmCredit(Request $request)
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
                        $data = DB::table('tblclaimdtl')->where('id', $phy->id)->first();

                        if($paymentinput2[$i]->payment != null)
                        {

                            DB::table('tblclaimdtl')->insert([
                                'claim_id' => $payment->id,
                                'claim_package_id' => $data->claim_package_id,
                                'price' => $data->price,
                                'unit' => $data->unit,
                                'amount' => $paymentinput2[$i]->payment * -1,
                                'add_staffID' => Auth::user()->ic,
                                'add_date' => date('Y-m-d'),
                                'mod_staffID' => Auth::user()->ic,
                                'mod_date' => date('Y-m-d')
                            ]);

                        }
                    }

                    $ref_no = DB::table('tblref_no')
                      ->join('tblclaim', 'tblref_no.process_type_id', 'tblclaim.process_type_id')
                      ->where('tblclaim.id', $payment->id)
                      ->select('tblref_no.*')->first();

                    DB::table('tblref_no')->where('id', $ref_no->id)->update([
                        'ref_no' => $ref_no->ref_no + 1
                    ]);

                    DB::table('tblclaim')->where('id', $payment->id)->update([
                        'process_status_id' => 2,
                        'ref_no' => $ref_no->code . $ref_no->ref_no + 1
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

        return ["message" => "Success"];

    }

    public function debitNote()
    {

        return view('treasurer.payment.debit');

    }

    public function getStudentDebit(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['type'] = DB::table('tblstudentclaim')->get();

        return view('treasurer.payment.debitGetStudent', compact('data'));

    }

    public function storeDebit(Request $request)
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
                
                if($payment->type != null && $payment->unit != null && $payment->amount != null && $payment->remark != null)
                {
                    $stddetail = DB::table('students')->where('ic', $payment->ic)->first();

                    $ref_no = DB::table('tblref_no')
                      ->where('process_type_id', 4)->first();

                    DB::table('tblref_no')->where('id', $ref_no->id)->update([
                        'ref_no' => $ref_no->ref_no + 1
                    ]);

                    $id = DB::table('tblclaim')->insertGetId([
                        'student_ic' => $payment->ic,
                        'date' => date('Y-m-d'),
                        'ref_no' => $ref_no->code . $ref_no->ref_no + 1,
                        'session_id' => $stddetail->session,
                        'semester_id' => $stddetail->semester,
                        'program_id' => $stddetail->program,
                        'process_status_id' => 2,
                        'process_type_id' => 4,
                        'remark' => $payment->remark,
                        'add_staffID' => Auth::user()->ic,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => Auth::user()->ic,
                        'mod_date' => date('Y-m-d')
                    ]);

                    DB::table('tblclaimdtl')->insert([
                        'claim_id' => $id,
                        'claim_package_id' => $payment->type,
                        'price' => $payment->amount,
                        'unit' => $payment->unit,
                        'amount' => $payment->amount * $payment->unit,
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

        return ["message" => "Success"];

    }

    public function getStatement(Request $request)
    {

            $data['claim'] = DB::table('tblclaimdtl')
                           ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                           ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                           ->where('tblclaim.student_ic', $request->ic)
                           ->where('tblclaim.process_status_id', 2)
                           ->select('tblclaimdtl.*', 'tblclaim.session_id', 'tblclaim.semester_id', 'tblstudentclaim.name')->get();

            foreach($data['claim'] as $key => $clm)
            {

                $a = $clm->amount;

                $data['amount'][] = $a;

                $balance = DB::table('tblpaymentdtl')
                ->join('tblpayment', 'tblpaymentdtl.payment_id', 'tblpayment.id')
                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                ->where([
                    ['tblpaymentdtl.claimDtl_id', $clm->id],
                    ['tblpayment.student_ic', $request->ic],
                    ['tblpaymentdtl.claim_type_id', $clm->claim_package_id],
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

            $content = "";
            $content .= '<thead>
                            <tr>
                                <th style="width: 1%">
                                    No.
                                </th>
                                <th style="width: 10%">
                                    About
                                </th>
                                <th style="width: 10%">
                                    Amount
                                </th>
                                <th style="width: 5%">
                                    Unit
                                </th>
                                <th style="width: 10%">
                                    Balance
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">';
                        
            foreach($data['claim'] as $key => $clm){
            //$registered = ($dtl->status == 'ACTIVE') ? 'checked' : '';
                if($data['balance'][$key] != 0)
                {
                $content .= '
                    <tr>
                        <td>
                        '. $key+1 .'
                        </td>
                        <td>
                        '. $clm->name .'
                        </td>
                        <td>
                        '. $data['amount'][$key] .'
                        </td>
                        <td>
                        '. $clm->unit .'
                        </td>
                        <td>
                        '. number_format((float)$data['balance'][$key], 4, '.', '') .'
                        </td>
                    </tr>
                    ';
                    }
                }
                $content .= '</tbody>';
                $content .= '<tfoot>
                <tr>
                    <td>
                    
                    </td>
                    <td>
                    TOTAL AMOUNT
                    </td>
                    <td>
                    :
                    </td>
                    </td>
                    
                    <td>
                </tr>
                </tfoot>';

            return $content;

    }
}
