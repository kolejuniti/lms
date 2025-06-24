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
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class FinanceController extends Controller
{
    

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

    public function updateStartAt3(Request $request)
    {
        try {
            // Update the start_at field in tbltabungkhas
            DB::table('tblinsentifkhas')
                ->where('id', $request->id)
                ->update(['start_at' => $request->start_at]);

            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function voucher()
    {
        $data['package'] = DB::table('tblpackage')->get();

        $data['type'] = DB::table('tblprocess_type')
                        ->where('name', 'LIKE', '%TABUNG%')->get();

        //dd($data['type']);

        $data['session'] = DB::table('sessions')->orderBy('SessionID', 'desc')->get();

        return view('finance.package.voucher', compact('data'));

    }

    public function getVoucher()
    {

        $data['voucher'] = DB::table('tblvoucher AS t1')
                             ->join('sessions AS t2_intake', 't1.intake_id', 't2_intake.SessionID')
                             ->join('tblpackage', 't1.package_id', 'tblpackage.id')
                            //  ->join('tblprocess_type', 't1.process_type_id', 'tblprocess_type.id')
                             ->select('t1.*', 't2_intake.SessionName AS intake', 'tblpackage.name AS package')
                             ->get();

        return view('finance.package.getVoucher', compact('data'));

    }

    public function storeVoucher(Request $request)
    {

        $data = json_decode($request->formData);

        if($data->intake != null)
        {

            //$session = DB::table('sessions')
            //           ->whereBetween('SessionID', [$data->from, $data->to])
            //           ->get();

            //foreach($session as $ses)
            //{

            //    DB::table('tblvoucher')->insert(
            //        'session'
            //    )

            //}

            DB::table('tblvoucher')->insert([
                'intake_id' => $data->intake,
                'package_id' => $data->package,
                // 'process_type_id' => $data->type,
                'amount' => $data->amount
            ]);

            return ["message"=>"Success"];

        }else{

            return ["message"=>"Please select all required field!"];

        }
    }

    public function getProgram4(Request $request)
    {

        $data['registered'] = DB::table('tblprogramme')
                              ->join('tblvoucher_program', 'tblprogramme.id', 'tblvoucher_program.program_id')
                              ->where('tblvoucher_program.voucher_id', $request->id)
                              ->select('tblprogramme.*')
                              ->get();

        $collection = collect($data['registered']);

        $data['unregistered'] = DB::table('tblprogramme')
                              ->whereNotIn('id', $collection->pluck('id'))
                              ->get();

        $data['id'] = $request->id;

        return view('finance.package.getProgram', compact('data'));
    }

    public function registerPRG4(Request $request)
    {
   
        DB::table('tblvoucher_program')->insert([
            'voucher_id' => $request->id,
            'program_id' => $request->prg
        ]);

        return response()->json($request->id);

    }

    public function unregisterPRG4(Request $request)
    {

        DB::table('tblvoucher_program')
        ->where([
            ['voucher_id', $request->id],
            ['program_id', $request->prg]
        ])->delete();

        return response()->json($request->id);

    }

    public function updateStartAt4(Request $request)
    {
        try {
            // Update the start_at field in tbltabungkhas
            DB::table('tblvoucher')
                ->where('id', $request->id)
                ->update(['start_at' => $request->start_at]);

            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
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

                }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27], (array) $req->process_type_id) && $req->source == 'payment')
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

                }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27], (array) $req->process_type_id) && $req->source == 'payment')
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

        //H

        $data['remark'] = DB::table('student_remarks')
                          ->join('categories', 'student_remarks.category_id', 'categories.id')
                          ->where('student_remarks.student_ic', request()->ic)
                          ->select('student_remarks.*', 'categories.name')
                          ->first();



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
                    
                }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27], (array) $req->process_type_id) && $req->source == 'payment')
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
                        
                    }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27], (array) $req->process_type_id) && $req->source == 'payment')
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
        $filterStd = [];

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

        $remark = filter_var($request->input('remark'), FILTER_VALIDATE_BOOLEAN);
        if($remark) {
            $filterStd = DB::table('student_remarks')
                ->select('student_ic')
                ->where('latest_balance', '<=', 0)
                ->get()
                ->pluck('student_ic')
                ->toArray();
        }


        //A

        if($request->program != 'all')
        {

            $data['student'] = DB::table('students')
            ->join('sessions', 'students.session', 'sessions.SessionID')
            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
            ->where('students.program', $request->program)
            ->whereIn('students.status', [8])
            ->whereBetween('sessions.Year', [$request->from, $request->to])
            ->when($request->input('remark'), function($query) use ($filterStd) {
                return $query->whereNotIn('students.ic', $filterStd);
            })

            ->select('students.*', 'sessions.Year AS graduate', 'sessions.SessionName AS session', 'tblprogramme.progcode AS program')
            ->get();

        }else{


            $data['student'] = DB::table('students')
            ->join('sessions', 'students.session', 'sessions.SessionID')
            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
            ->where('students.program', '!=', 30)
            ->whereIn('students.status', [8])
            ->whereBetween('sessions.Year', [$request->from, $request->to])
            ->when($request->input('remark'), function($query) use ($filterStd) {
                return $query->whereNotIn('students.ic', $filterStd);
            })

            ->select('students.*', 'sessions.Year AS graduate', 'sessions.SessionName AS session', 'tblprogramme.progcode AS program')
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
                    
                }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27], (array) $req->process_type_id) && $req->source == 'payment')
                {

                    $data['total'] -= $req->amount;

                }

            }

            $data['sum3'] = $data['total'];

            //TUNGGAKAN KESELURUHAN

            //REMARk

            $data['remark'] = DB::table('student_remarks')
                ->join('categories', 'student_remarks.category_id', 'categories.id')
                ->where('student_remarks.student_ic', $std->ic)
                ->select('student_remarks.*', 'categories.name')
                ->first();

            if ($data['remark']) {
                $data['total_balance'][$key] = number_format($data['sum3'] - $data['remark']->correction_amount, 2, '.', '');
            } else {
                $data['total_balance'][$key] = $data['sum3'];
            }

            // $data['total_balance'][$key] = $data['sum3'];

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
        // Validate request
        $validator = Validator::make($request->all(), [
            'filtersData' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message" => "Field Error", "error" => $validator->messages()->get('*')];
        }

        try {
            $filter = json_decode($request->filtersData);
            
            // Get programs once, outside the loop
            if ($filter->program == 'all') {
                $programs = DB::table('tblprogramme')
                    ->select('id', 'progcode', 'progname')
                    ->get();
            } else {
                $programs = DB::table('tblprogramme')
                    ->select('id', 'progcode', 'progname')
                    ->where('id', $filter->program)
                    ->get();
            }
            
            // Calculate years range
            $startYear = date('Y', strtotime($filter->from));
            $endYear = date('Y', strtotime($filter->to));
            $currentYear = now()->year;
            
            // Create array of years
            $arrayYears = range($startYear, $currentYear);
            $rangeYears = range($startYear, $endYear);
            
            // Initialize results array with proper structure
            $results = [];
            $totals = array_fill_keys($arrayYears, 0);
            
            // Get all needed data in a single query per program instead of per year
            foreach ($programs as $program) {
                $programId = $program->id;
                $yearlyBalances = [];
                $lastKnownBalance = 0;
                
                // Get all claims and payments in one query with year extraction
                $query = DB::select("
                    SELECT 
                        YEAR(transaction_date) as year,
                        SUM(CASE WHEN transaction_type = 'claim' THEN amount ELSE 0 END) as total_claims,
                        SUM(CASE WHEN transaction_type = 'payment' THEN amount ELSE 0 END) as total_payments
                    FROM (
                        SELECT 
                            tblclaim.add_date as transaction_date,
                            'claim' as transaction_type,
                            IFNULL(SUM(tblclaimdtl.amount), 0) as amount
                        FROM students
                        JOIN tblclaim ON students.ic = tblclaim.student_ic
                        LEFT JOIN tblclaimdtl ON tblclaim.id = tblclaimdtl.claim_id
                        LEFT JOIN tblstudentclaim ON tblclaimdtl.claim_package_id = tblstudentclaim.id
                        WHERE tblclaim.process_status_id = 2
                        AND tblstudentclaim.groupid = 1
                        AND tblclaim.program_id = ?
                        AND tblclaim.add_date BETWEEN ? AND ?
                        GROUP BY YEAR(tblclaim.add_date)
                        
                        UNION ALL
                        
                        SELECT 
                            tblpayment.add_date as transaction_date,
                            'payment' as transaction_type,
                            IFNULL(SUM(tblpaymentdtl.amount), 0) as amount
                        FROM students
                        JOIN tblpayment ON students.ic = tblpayment.student_ic
                        LEFT JOIN tblpaymentdtl ON tblpayment.id = tblpaymentdtl.payment_id
                        LEFT JOIN tblstudentclaim ON tblpaymentdtl.claim_type_id = tblstudentclaim.id
                        WHERE tblpayment.process_status_id = 2
                        AND tblstudentclaim.groupid = 1
                        AND tblpayment.program_id = ?
                        AND tblpayment.add_date BETWEEN ? AND ?
                        GROUP BY YEAR(tblpayment.add_date)
                    ) as combined_data
                    GROUP BY year
                    ORDER BY year
                ", [$programId, $filter->from, $filter->to, $programId, $filter->from, $filter->to]);
                
                // Convert query results to a more manageable format
                $yearData = [];
                foreach ($query as $row) {
                    $yearData[$row->year] = $row->total_claims - $row->total_payments;
                }
                
                // Fill in the results for each year
                foreach ($arrayYears as $year) {
                    if (in_array($year, $rangeYears)) {
                        if (isset($yearData[$year])) {
                            // We have data for this year
                            $balance = $lastKnownBalance + $yearData[$year];
                            $lastKnownBalance = $balance;
                        }
                        // If no data for this year, keep last known balance
                    } 
                    
                    $yearlyBalances[$year] = $lastKnownBalance;
                    $totals[$year] += $lastKnownBalance;
                }
                
                $results[] = [
                    'program' => $program,
                    'balances' => $yearlyBalances
                ];
            }
            
            // Build the HTML directly in the function as requested
            $content = "";
            $content .= '<thead>
                            <tr>
                                <th rowspan="2">
                                    Program
                                </th>
                                <th colspan="'. count($arrayYears) .'" style="text-align: center">
                                    AGING REPORT BY PROGRAM AND YEAR from '. $startYear .' UNTIL '. $currentYear; 
            $content .= '</th>
                            </tr>
                            <tr>';
                            foreach($arrayYears as $year) {
                                $content .= '<th>'. $year .'</th>';
                            }
            $content .= '</tr>
                        </thead>
                        <tbody id="table">';
            
            foreach ($results as $result) {
                $program = $result['program'];
                $balances = $result['balances'];
                
                $content .= '<tr>
                                <td>'. $program->progcode .' - '. $program->progname .'</td>';
                
                foreach ($arrayYears as $year) {
                    $content .= '<td>'. $balances[$year] .'</td>';
                }
                
                $content .= '</tr>';
            }
            
            $content .= '</tbody>';
            $content .= '<tfoot>
                            <tr>
                                <td>TOTAL</td>';
            
            foreach ($totals as $yearTotal) {
                $content .= '<td>'. number_format($yearTotal, 2) .'</td>';
            }
            
            $content .= '</tr>
                        </tfoot>';
            
            return response()->json(['message' => 'Success', 'data' => $content]);
            
        } catch (QueryException $ex) {
            \Log::debug($ex);
            return ["message" => "Database Error: " . $ex->getMessage()];
        } catch (Exception $ex) {
            return ["message" => "Error: " . $ex->getMessage()];
        }
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

        $data['session'] = DB::table('sessions')->orderBy('SessionID', 'DESC')->get();

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
                                   ->leftjoin('sessions AS A', 'students.session', 'A.SessionID')
                                   ->leftjoin('sessions AS B', 'students.intake', 'B.SessionID')
                                   ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                                   ->leftjoin('tblstudent_status', 'students.status', 'tblstudent_status.id')
                                   ->whereIn('students.program', $program)
                                   ->when($filter->status != 'all', function ($query) use ($filter){
                                        return $query->where('students.status', $filter->status);
                                   })
                                //    ->when($filter->session != 'all', function ($query) use ($filter){
                                //         return $query->where('students.session', $filter->session);
                                //     })
                                    ->when(is_array($filter->session) && !empty($filter->session), function ($query) use ($filter){
                                        return $query->whereIn('students.session', $filter->session);
                                    })
                                   ->select('students.name','students.ic', 'students.no_matric', 'tblprogramme.progcode', 
                                            'A.SessionName AS session', 'B.SessionName AS intake', 'students.semester', 'tblstudent_status.name AS status')
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
                    ->when($filter->from != '' && $filter->to != '', function ($query) use ($filter){
                        return $query->whereBetween('tblclaim.date', [$filter->from, $filter->to]);
                    })
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
                    ->when($filter->from != '' && $filter->to != '', function ($query) use ($filter){
                        return $query->whereBetween('tblpayment.date', [$filter->from, $filter->to]);
                    })
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
                                        Intake
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
                            
                // Initialize total variables
                $totalFee = 0;
                $totalPayment = 0;
                $totalBalance = 0;
                
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
                        '. $std->no_matric .'
                        </td>
                        <td>
                        '. $std->progcode .'
                        </td>
                        <td>
                        '. $std->session .'
                        </td>
                        <td>
                        '. $std->intake .'
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
                            // Add to totals
                            $totalFee += $blc->total_claim;
                            $totalPayment += $blc->total_payment;
                            $totalBalance += $blc->balance;

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
                
                // Add totals row
                $content .= '<tr>
                    <td><strong>TOTAL</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><strong>'. number_format($totalFee, 2) .'</strong></td>
                    <td><strong>'. number_format($totalPayment, 2) .'</strong></td>
                    <td><strong>'. number_format($totalBalance, 2) .'</strong></td>
                </tr>';

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

                    $data['total'] = [];

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
                            

                        }elseif(array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27], (array) $req->process_type_id) && $req->source == 'payment')
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

                            if($std->status == 4)
                            {

                                $data['value'] = $data['sum3'];

                            }else{

                                if (isset($data['package']->$semester_column)) {
                                    $data['value'] = $data['sum3'] - $data['package']->$semester_column;
                                    // Do something with $semester_value
                                } else {
                                    $data['value'] = 0;
                                    // Handle case where the column is not set
                                }
                                
                            }

                        }else{

                            $data['value'] = 0;

                        }

                        // if($std->ic == '060907101120')
                        // {


                        //     // Return the JSON data as part of the response
                        //     return response()->json([
                        //         'data' => $data['sum3'],
                        //     ]);

                        // }

                        if($data['value'] > 0)
                        {

                            DB::table('students')->where('ic', $std->ic)->update(['block_status' => 1]);

                        }else{

                            DB::table('students')->where('ic', $std->ic)->update(['block_status' => 0]);

                        }

                    }

                }

                return response()->json(['message' => 'Success']);

 

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

    public function studentRemarks()
    {

        return view('finance.debt.student_remarks.studentRemarks');

    }

    public function getStudentRemarks(Request $request)
    {

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS statusName', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', $request->student)->first();

        $data['remarks'] = DB::table('student_remarks')
                           ->join('categories', 'student_remarks.category_id', 'categories.id')
                           ->join('students', 'student_remarks.student_ic', 'students.ic')
                           ->where('student_ic', $request->student)
                           ->select('student_remarks.*', 'categories.name AS category', 'students.name AS student_name')
                           ->get();

        $data['category'] = DB::table('categories')->get();


        return view('finance.debt.student_remarks.updateStudentRemarks', compact('data'));

    }

    public function storeStudentRemarks(Request $request)
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

                $exists = DB::table('student_remarks')
                    ->where('student_ic', $student->ic)
                    ->exists();

                if (!$exists) {
                    DB::table('student_remarks')->insert([
                        'student_ic' => $student->ic,
                        'category_id' => $student->categories,
                        'correction_amount' => $student->correction,
                        'latest_balance' => $student->current,
                        'notes' => $student->comment,
                        'add_staffID' => Auth::user()->ic
                    ]);
                } else {
                    DB::table('student_remarks')
                        ->where('student_ic', $student->ic)
                        ->update([
                            'category_id' => $student->categories,
                            'correction_amount' => $student->correction,
                            'latest_balance' => $student->current,
                            'notes' => $student->comment,
                            'add_staffID' => Auth::user()->ic
                        ]);
                }

                $std_rem = DB::table('student_remarks')
                           ->join('categories', 'student_remarks.category_id', 'categories.id')
                           ->join('students', 'student_remarks.student_ic', 'students.ic')
                           ->where('student_ic', $student->ic)
                           ->select('student_remarks.*', 'categories.name AS category', 'students.name AS student_name')
                           ->get();

                $content = "";
                $content .= '<thead>
                                <tr>
                                    <th style="width: 1%">
                                        No.
                                    </th>
                                    <th>
                                        No. IC
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Category
                                    </th>
                                    <th>
                                        Correction Amount
                                    </th>
                                    <th>
                                        Latest Balance
                                    </th>
                                    <th>
                                        Remark
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">';
                            
                foreach($std_rem as $key => $std){
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
                        '. $std->student_name .'
                        </td>
                        <td>
                        '. $std->category .'
                        </td>
                        <td>
                        '. $std->correction_amount .'
                        </td>
                        <td>
                        '. $std->latest_balance .'
                        </td>
                        <td>
                        '. $std->notes .'
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


    public function vehicleRecord()
    {

        $data = [

            'vehicles' => DB::table('tblvehicle')->get(),
            'year' => array_reverse(range(2000, now()->year))

        ];

        foreach($data['vehicles'] as $key => $vehicle)
        {
            $odometer = DB::table('tblvehicle_service')->where('vehicle_id', $vehicle->id)->orderBy('id', 'DESC')->first();

            $data['nextService'][$key] = ($odometer) ? $odometer->odometer + 10000 : 0;

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

        $data['block'] = DB::table('students')
                        ->leftjoin('sessions', 'students.session', 'sessions.SessionID')
                        ->leftjoin('tblprogramme', 'students.program', 'tblprogramme.id')
                        ->leftjoin('tblstudent_status', 'students.status', 'tblstudent_status.id')
                        ->whereIn('students.block_status', [1])
                        ->select('students.*', 'sessions.SessionName', 'tblprogramme.progcode', 'tblstudent_status.name AS status')
                        ->get();

        return view('finance.student.block_list.blockList', compact('data'));
        
    }

    public function gradReport()
    {

        return view('finance.report.gradReport');

    }

    public function getGradReport(Request $request)
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

    public function graduateReport()
    {
        return view('finance.report.graduateReport');
    }

    public function getGraduateReport(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $format = $request->input('format', 'calendar'); // Default to calendar format
        $isPrint = $request->has('print');

        if (!$from || !$to) {
            return response()->json(['error' => 'Please provide both from and to dates']);
        }

                try {
            // Get graduate payment data grouped by date (daily)
            $graduateData = DB::table('tblpayment')
                ->join('students', 'tblpayment.student_ic', 'students.ic')
                ->join('tblpaymentdtl', 'tblpayment.id', 'tblpaymentdtl.payment_id')
                ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                ->leftjoin('tblprogramme', 'tblpayment.program_id', 'tblprogramme.id')
                ->select(
                    DB::raw('YEAR(tblpayment.add_date) as year'),
                    DB::raw('MONTH(tblpayment.add_date) as month'),
                    DB::raw('DAY(tblpayment.add_date) as day'),
                    DB::raw('MONTHNAME(tblpayment.add_date) as month_name'),
                    DB::raw('DATE(tblpayment.add_date) as payment_date'),
                    DB::raw('COUNT(DISTINCT students.id) as student_count'),
                    DB::raw('SUM(tblpaymentdtl.amount) as total_amount'),
                    DB::raw('COUNT(DISTINCT tblpayment.id) as payment_count')
                )
                ->whereBetween('tblpayment.add_date', [$from, $to])
                ->where('tblpayment.process_status_id', 2)
                ->where('students.status', 8) // Graduate status
                ->whereNotNull('tblpayment.ref_no')
                ->where('tblpaymentdtl.amount', '!=', 0)
                ->whereIn('tblstudentclaim.groupid', [1])
                ->groupBy('year', 'month', 'day', 'month_name', 'payment_date')
                ->orderBy('year', 'ASC')
                ->orderBy('month', 'ASC')
                ->orderBy('day', 'ASC')
                ->get();

            // Group data by year, month, and day
            $yearlyData = [];
            $years = [];
            
            foreach ($graduateData as $data) {
                $year = $data->year;
                $month = $data->month;
                $day = $data->day;
                
                if (!in_array($year, $years)) {
                    $years[] = $year;
                }
                
                if (!isset($yearlyData[$year])) {
                    $yearlyData[$year] = [];
                }
                
                if (!isset($yearlyData[$year][$month])) {
                    $yearlyData[$year][$month] = [
                        'month_name' => $data->month_name,
                        'student_count' => 0,
                        'total_amount' => 0,
                        'payment_count' => 0,
                        'days' => []
                    ];
                }
                
                // Aggregate monthly totals
                $yearlyData[$year][$month]['student_count'] += $data->student_count;
                $yearlyData[$year][$month]['total_amount'] += $data->total_amount;
                $yearlyData[$year][$month]['payment_count'] += $data->payment_count;
                
                // Store daily data
                $yearlyData[$year][$month]['days'][$day] = [
                    'student_count' => $data->student_count,
                    'total_amount' => $data->total_amount,
                    'payment_count' => $data->payment_count,
                    'payment_date' => $data->payment_date
                ];
            }

            // Generate HTML content based on format
            if ($format === 'table') {
                $content = $this->generateGraduateTableReportHTML($yearlyData, $years, $from, $to, $isPrint);
            } elseif ($format === 'collection') {
                $content = $this->generateCollectionFormatHTML($from, $to, $isPrint);
            } else {
                $content = $this->generateGraduateReportHTML($yearlyData, $years, $from, $to, $isPrint);
            }

            if ($isPrint) {
                return $content;
            }

            return $content;

        } catch (\Exception $ex) {
            return response()->json(['error' => 'An error occurred while generating the report']);
        }
    }

    private function generateGraduateReportHTML($yearlyData, $years, $from, $to, $isPrint = false)
    {
        if ($isPrint) {
            $content = '<!DOCTYPE html>
            <html>
            <head>
                <title>Graduate Report</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .date-range { text-align: center; margin-bottom: 30px; font-weight: bold; }
                    .calendar-year { margin-bottom: 30px; }
                    .year-title { text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 20px; }
                    .month-grid { margin-bottom: 30px; }
                    .month-card { border: 2px solid #333; border-radius: 8px; padding: 10px; }
                    .month-header { background-color: #007bff; color: white; text-align: center; font-weight: bold; font-size: 18px; padding: 10px; margin: -10px -10px 10px -10px; }
                    .week-table { width: 100%; border-collapse: collapse; }
                    .week-table th, .week-table td { border: 1px solid #ddd; padding: 6px 4px; text-align: center; font-size: 10px; }
                    .week-table th { background-color: #f8f9fa; font-weight: bold; font-size: 9px; }
                    .week-cell { min-height: 100px; vertical-align: top; padding: 4px; }
                    .total-footer { background-color: #e9ecef; font-weight: bold; font-size: 14px; text-align: center; padding: 10px; margin-top: 10px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Laporan harian dan bulanan kutipan pelajar graduate</h2>
                </div>
                <div class="date-range">Period: ' . date('d/m/Y', strtotime($from)) . ' - ' . date('d/m/Y', strtotime($to)) . '</div>';
            
            foreach ($years as $year) {
                $content .= '<div class="calendar-year">';
                $content .= '<div class="year-title">' . $year . '</div>';
                $content .= $this->generateYearCalendar($yearlyData[$year] ?? [], $year, true);
                $content .= '</div>';
            }
            $content .= '</body></html>';
        } else {
            $content = '<style>
                .calendar-container { margin-top: 20px; }
                .year-tabs .nav-link { background-color: #f8f9fa; border: 1px solid #ddd; margin-right: 5px; }
                .year-tabs .nav-link.active { background-color: #007bff; color: white; }
                .month-grid { margin-top: 20px; }
                .month-card { border: 2px solid #333; border-radius: 8px; padding: 0; overflow: hidden; }
                .month-header { background-color: #007bff; color: white; text-align: center; font-weight: bold; font-size: 18px; padding: 15px; }
                .jan-header { background-color: #dc3545; }
                .feb-header { background-color: #fd7e14; }
                .mar-header { background-color: #ffc107; }
                .apr-header { background-color: #20c997; }
                .may-header { background-color: #28a745; }
                .jun-header { background-color: #17a2b8; }
                .jul-header { background-color: #6f42c1; }
                .aug-header { background-color: #e83e8c; }
                .sep-header { background-color: #6c757d; }
                .oct-header { background-color: #fd7e14; }
                .nov-header { background-color: #20c997; }
                .dec-header { background-color: #dc3545; }
                .week-table { width: 100%; border-collapse: collapse; }
                .week-table th, .week-table td { border: 1px solid #ddd; padding: 8px 4px; text-align: center; font-size: 12px; }
                .week-table th { background-color: #f8f9fa; font-weight: bold; font-size: 10px; padding: 8px 2px; }
                .week-table tbody tr:nth-child(even) { background-color: #f9f9f9; }
                .total-footer { background-color: #e9ecef; font-weight: bold; text-align: center; padding: 15px; }
                .week-cell { min-height: 150px; vertical-align: top; padding: 4px; overflow: hidden; }
            </style>
            
            <div class="card">
                <div class="card-header">
                    <h3>Laporan harian dan bulanan kutipan pelajar graduate</h3>
                    <h5>Period: ' . date('d/m/Y', strtotime($from)) . ' to ' . date('d/m/Y', strtotime($to)) . '</h5>
                </div>
                <div class="card-body">
                    <div class="calendar-container">';
            
            if (count($years) > 1) {
                // Multiple years - show tabs
                $content .= '<ul class="nav nav-tabs year-tabs" role="tablist">';
                foreach ($years as $index => $year) {
                    $active = $index === 0 ? 'active' : '';
                    $content .= '<li class="nav-item">
                        <a class="nav-link ' . $active . '" data-toggle="tab" href="#year' . $year . '" role="tab">' . $year . '</a>
                    </li>';
                }
                $content .= '</ul>
                <div class="tab-content">';
                
                foreach ($years as $index => $year) {
                    $active = $index === 0 ? 'show active' : '';
                    $content .= '<div id="year' . $year . '" class="tab-pane fade ' . $active . '" role="tabpanel">';
                    $content .= $this->generateYearCalendar($yearlyData[$year] ?? [], $year);
                    $content .= '</div>';
                }
                $content .= '</div>';
            } else {
                // Single year - no tabs needed
                if (!empty($years)) {
                    $year = $years[0];
                    $content .= $this->generateYearCalendar($yearlyData[$year] ?? [], $year);
                }
            }
            
            $content .= '</div></div></div>';
        }

        return $content;
    }

    private function generateYearCalendar($yearData, $year, $isPrint = false)
    {
        $months = [
            1 => ['name' => 'JAN', 'full' => 'January', 'class' => 'jan-header'],
            2 => ['name' => 'FEB', 'full' => 'February', 'class' => 'feb-header'],
            3 => ['name' => 'MAR', 'full' => 'March', 'class' => 'mar-header'],
            4 => ['name' => 'APR', 'full' => 'April', 'class' => 'apr-header'],
            5 => ['name' => 'MAY', 'full' => 'May', 'class' => 'may-header'],
            6 => ['name' => 'JUN', 'full' => 'June', 'class' => 'jun-header'],
            7 => ['name' => 'JUL', 'full' => 'July', 'class' => 'jul-header'],
            8 => ['name' => 'AUG', 'full' => 'August', 'class' => 'aug-header'],
            9 => ['name' => 'SEPT', 'full' => 'September', 'class' => 'sep-header'],
            10 => ['name' => 'OCT', 'full' => 'October', 'class' => 'oct-header'],
            11 => ['name' => 'NOV', 'full' => 'November', 'class' => 'nov-header'],
            12 => ['name' => 'DEC', 'full' => 'December', 'class' => 'dec-header']
        ];

        $content = '<div class="month-grid">';
        $yearTotal = 0;

        foreach ($months as $monthNum => $monthInfo) {
            $monthData = $yearData[$monthNum] ?? null;
            $monthTotal = $monthData ? $monthData['total_amount'] : 0;
            $yearTotal += $monthTotal;

            $headerClass = $isPrint ? '' : $monthInfo['class'];
            
            // Calculate the calendar weeks for this month
            $weekGrid = $this->generateMonthWeekGrid($year, $monthNum);
            $totalWeeks = count($weekGrid);
            $totalColumns = $totalWeeks * 2; // 2 columns per week (Days and Amount)
            
            // Create vertical month table like the example
            $content .= '<div class="month-container" style="margin-bottom: 30px; page-break-inside: avoid;">
                <table style="width: 100%; border-collapse: collapse; border: 2px solid #333;">
                    <thead>
                        <tr>
                            <th colspan="' . $totalColumns . '" style="background-color: #007bff; color: white; text-align: center; font-weight: bold; font-size: 18px; padding: 15px; border: 1px solid #333;" class="' . $headerClass . '">
                                ' . $monthInfo['name'] . ' ' . $year . '
                            </th>
                        </tr>
                        <tr style="background-color: #f8f9fa;">';

            // Generate week headers
            for ($week = 1; $week <= $totalWeeks; $week++) {
                $content .= '<th colspan="2" style="border: 1px solid #333; padding: 8px; text-align: center; font-weight: bold; font-size: 12px;">
                    Minggu ke-' . $week . ' ' . $monthInfo['full'] . ' ' . $year . '
                </th>';
            }
            
            $content .= '</tr>
                        <tr style="background-color: #f8f9fa;">';
            
            // Generate Days/Amount subheaders
            for ($week = 1; $week <= $totalWeeks; $week++) {
                $content .= '<th style="border: 1px solid #333; padding: 4px; text-align: center; font-weight: bold; font-size: 10px;">
                    Days
                </th>';
                $content .= '<th style="border: 1px solid #333; padding: 4px; text-align: center; font-weight: bold; font-size: 10px;">
                    Amount
                </th>';
            }
            
            $content .= '</tr>
                    </thead>
                    <tbody>';

            // Find the maximum number of days in any week for this month
            $maxDaysInWeek = 0;
            foreach ($weekGrid as $week) {
                $daysInThisWeek = 0;
                foreach ($week as $dayInfo) {
                    if ($dayInfo['day'] !== null && $dayInfo['is_current_month']) {
                        $daysInThisWeek++;
                    }
                }
                $maxDaysInWeek = max($maxDaysInWeek, $daysInThisWeek);
            }

            // Generate rows for each day position (up to 5 days per week)
            for ($dayPosition = 0; $dayPosition < 5; $dayPosition++) {
                $content .= '<tr>';
                
                foreach ($weekGrid as $weekIndex => $week) {
                    // Get the day info for this position in this week
                    $currentDayInfo = null;
                    $dayCount = 0;
                    
                    foreach ($week as $dayInfo) {
                        if ($dayInfo['day'] !== null && $dayInfo['is_current_month']) {
                            if ($dayCount == $dayPosition) {
                                $currentDayInfo = $dayInfo;
                                break;
                            }
                            $dayCount++;
                        }
                    }
                    
                    if ($currentDayInfo) {
                        $dayData = ($monthData && isset($monthData['days'][$currentDayInfo['day']])) ? $monthData['days'][$currentDayInfo['day']] : null;
                        
                        // Days column
                        $content .= '<td style="border: 1px solid #ddd; padding: 4px; text-align: center; vertical-align: middle; font-size: 10px;">
                            ' . $currentDayInfo['day'] . '-' . $monthInfo['name'] . '
                        </td>';
                        
                        // Amount column
                        if ($dayData) {
                            $content .= '<td style="border: 1px solid #ddd; padding: 4px; text-align: center; vertical-align: middle; font-size: 10px;">
                                RM ' . number_format($dayData['total_amount'], 2) . '<br/>
                                <small style="color: #28a745;">EA: ' . $dayData['student_count'] . '</small>
                            </td>';
                        } else {
                            $content .= '<td style="border: 1px solid #ddd; padding: 4px; text-align: center; vertical-align: middle; font-size: 10px; color: #999;">
                                RM -<br/>
                                <small>EA: -</small>
                            </td>';
                        }
                    } else {
                        // Empty cells
                        $content .= '<td style="border: 1px solid #ddd; padding: 4px; background-color: #f0f0f0;"></td>';
                        $content .= '<td style="border: 1px solid #ddd; padding: 4px; background-color: #f0f0f0;"></td>';
                    }
                }
                
                $content .= '</tr>';
            }
            
            // Weekly totals row
            $content .= '<tr style="background-color: #e9ecef;">';
            foreach ($weekGrid as $week) {
                $weekTotal = 0;
                foreach ($week as $dayInfo) {
                    if ($dayInfo['day'] !== null && $dayInfo['is_current_month']) {
                        $dayData = ($monthData && isset($monthData['days'][$dayInfo['day']])) ? $monthData['days'][$dayInfo['day']] : null;
                        if ($dayData) {
                            $weekTotal += $dayData['total_amount'];
                        }
                    }
                }
                
                $content .= '<td colspan="2" style="border: 1px solid #333; padding: 8px; text-align: center; font-weight: bold; font-size: 11px;">
                    RM ' . number_format($weekTotal, 2) . '
                </td>';
            }
            $content .= '</tr>';

            $content .= '</tbody>
                    <tfoot>
                        <tr>
                            <td colspan="' . $totalColumns . '" style="background-color: #e9ecef; font-weight: bold; text-align: center; padding: 15px; border: 1px solid #333; font-size: 14px;">
                                Laporan Bulanan Kutipan: RM ' . number_format($monthTotal, 2) . '
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>';
        }

        $content .= '</div>';
        
        // Add yearly total
        $content .= '<div style="text-align: center; margin-top: 20px; padding: 15px; background-color: #f8f9fa; border: 2px solid #007bff; border-radius: 8px;">
            <h4>TOTAL RM ' . number_format($yearTotal, 2) . '</h4>
        </div>';

        return $content;
    }

    private function generateMonthWeekGrid($year, $month)
    {
        $firstDay = new \DateTime("$year-$month-01");
        $lastDay = new \DateTime($firstDay->format('Y-m-t'));
        
        // Find the first Monday of the week that contains the first day of the month
        $firstMonday = clone $firstDay;
        $dayOfWeek = $firstDay->format('N'); // 1 = Monday, 7 = Sunday
        if ($dayOfWeek > 1) {
            $firstMonday->modify('-' . ($dayOfWeek - 1) . ' days');
        }
        
        // Find the last Friday of the week that contains the last day of the month
        $lastFriday = clone $lastDay;
        $dayOfWeek = $lastDay->format('N');
        if ($dayOfWeek < 5) {
            $lastFriday->modify('+' . (5 - $dayOfWeek) . ' days');
        } elseif ($dayOfWeek > 5) {
            $lastFriday->modify('+' . (5 + 7 - $dayOfWeek) . ' days');
        }
        
        $weekGrid = [];
        $currentDate = clone $firstMonday;
        
        while ($currentDate <= $lastFriday) {
            $week = [];
            
            // Generate Monday to Friday for this week
            for ($dayOfWeek = 1; $dayOfWeek <= 5; $dayOfWeek++) {
                $isCurrentMonth = ($currentDate->format('n') == $month);
                $week[] = [
                    'day' => $isCurrentMonth ? (int)$currentDate->format('j') : null,
                    'date' => $currentDate->format('Y-m-d'),
                    'is_current_month' => $isCurrentMonth
                ];
                $currentDate->modify('+1 day');
            }
            
            $weekGrid[] = $week;
            
            // Skip Saturday and Sunday
            $currentDate->modify('+2 days');
        }
        
        return $weekGrid;
    }

    private function generateGraduateTableReportHTML($yearlyData, $years, $from, $to, $isPrint = false)
    {
        if ($isPrint) {
            $content = '<!DOCTYPE html>
            <html>
            <head>
                <title>Graduate Report - Table Format</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .date-range { text-align: center; margin-bottom: 30px; font-weight: bold; }
                    .table-container { margin-bottom: 30px; }
                    .main-table { width: 100%; border-collapse: collapse; border: 2px solid #333; }
                    .main-table th, .main-table td { border: 1px solid #333; padding: 8px; text-align: center; font-size: 10px; }
                    .main-table th { background-color: #f8f9fa; font-weight: bold; }
                    .month-header { background-color: #007bff; color: white; font-weight: bold; }
                    .total-row { background-color: #ffeb3b; font-weight: bold; }
                    .grand-total { background-color: #4caf50; color: white; font-weight: bold; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>KUTIPAN BAYARAN TAMAT PENGAJIAN</h2>
                    <h3>(TAHUN ' . implode(' - ', $years) . ')</h3>
                </div>
                <div class="date-range">Period: ' . date('d/m/Y', strtotime($from)) . ' - ' . date('d/m/Y', strtotime($to)) . '</div>';
            
            $content .= $this->generateTableFormatContent($yearlyData, $years);
            $content .= '</body></html>';
        } else {
            $content = '<style>
                .table-container { margin-top: 20px; }
                .main-table { width: 100%; border-collapse: collapse; border: 2px solid #333; }
                .main-table th, .main-table td { border: 1px solid #333; padding: 8px; text-align: center; font-size: 11px; }
                .main-table th { background-color: #f8f9fa; font-weight: bold; }
                .month-header { background-color: #007bff; color: white; font-weight: bold; }
                .total-row { background-color: #ffeb3b; font-weight: bold; }
                .grand-total { background-color: #4caf50; color: white; font-weight: bold; font-size: 13px; }
                .year-tabs .nav-link { background-color: #f8f9fa; border: 1px solid #ddd; margin-right: 5px; }
                .year-tabs .nav-link.active { background-color: #007bff; color: white; }
            </style>
            
            <div class="card">
                <div class="card-header">
                    <h3>KUTIPAN BAYARAN TAMAT PENGAJIAN (TAHUN ' . implode(' - ', $years) . ')</h3>
                    <h5>Period: ' . date('d/m/Y', strtotime($from)) . ' to ' . date('d/m/Y', strtotime($to)) . '</h5>
                </div>
                <div class="card-body">
                    <div class="table-container">';
            
            $content .= $this->generateTableFormatContent($yearlyData, $years);
            $content .= '</div></div></div>';
        }

        return $content;
    }

    private function generateTableFormatContent($yearlyData, $years)
    {
        $content = '';
        
        foreach ($years as $year) {
            $yearData = $yearlyData[$year] ?? [];
            
            // Get months that have data for this year, sorted by month number
            $monthsWithData = [];
            foreach ($yearData as $monthNum => $monthData) {
                if (!empty($monthData['days']) || $monthData['total_amount'] > 0) {
                    $monthsWithData[$monthNum] = $monthData;
                }
            }
            
            // Sort by month number
            ksort($monthsWithData);
            
            if (empty($monthsWithData)) {
                continue;
            }
            
            $content .= '<div class="year-section mt-4">';
            if (count($years) > 1) {
                $content .= '<h4>Year ' . $year . '</h4>';
            }
            
            $content .= '<table class="main-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle; width: 80px;">Tarikh</th>';
            
            // Generate month headers
            foreach ($monthsWithData as $monthNum => $monthData) {
                $monthNames = [
                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                ];
                $monthName = $monthNames[$monthNum] ?? '';
                
                $content .= '<th colspan="2" class="month-header" style="background-color: #007bff; color: white; text-align: center;">' . $monthName . ' ' . $year . '</th>';
            }
            
            $content .= '</tr>
                    <tr>';
            
            // Generate sub-headers for each month
            foreach ($monthsWithData as $monthNum => $monthData) {
                $content .= '<th style="background-color: #f8f9fa; text-align: center; font-size: 10px; padding: 8px;">Number<br/>of<br/>Student</th>';
                $content .= '<th style="background-color: #f8f9fa; text-align: center; font-size: 10px; padding: 8px;">Amount</th>';
            }
            
            $content .= '</tr>
                </thead>
                <tbody>';
            
            // Generate rows for each day (1-31)
            $monthlyTotals = [];
            $grandTotal = 0;
            $grandStudentCount = 0;
            
            for ($day = 1; $day <= 31; $day++) {
                $hasDataForDay = false;
                
                // Check if any month has data for this day
                foreach ($monthsWithData as $monthNum => $monthData) {
                    $dayData = $monthData['days'][$day] ?? null;
                    if ($dayData && $dayData['total_amount'] > 0) {
                        $hasDataForDay = true;
                        break;
                    }
                }
                
                // Only show rows that have data
                if ($hasDataForDay) {
                    $content .= '<tr>';
                    $content .= '<td style="text-align: center; font-weight: bold;">' . $day . ' hb</td>';
                    
                    foreach ($monthsWithData as $monthNum => $monthData) {
                        $dayData = $monthData['days'][$day] ?? null;
                        
                        if ($dayData && $dayData['total_amount'] > 0) {
                            $content .= '<td style="text-align: center;">' . $dayData['student_count'] . '</td>';
                            $content .= '<td style="text-align: center;">' . number_format($dayData['total_amount'], 2) . '</td>';
                            
                            // Accumulate totals
                            if (!isset($monthlyTotals[$monthNum])) {
                                $monthlyTotals[$monthNum] = ['amount' => 0, 'students' => 0];
                            }
                            $monthlyTotals[$monthNum]['amount'] += $dayData['total_amount'];
                            $monthlyTotals[$monthNum]['students'] += $dayData['student_count'];
                        } else {
                            $content .= '<td style="text-align: center;"></td>';
                            $content .= '<td style="text-align: center;"></td>';
                        }
                    }
                    
                    $content .= '</tr>';
                }
            }
            
            $content .= '</tbody>
                <tfoot>
                    <tr style="background-color: #ffeb3b; font-weight: bold;">';
            
            $content .= '<td style="text-align: center; padding: 10px;"></td>';
            
            foreach ($monthsWithData as $monthNum => $monthData) {
                $monthTotal = $monthlyTotals[$monthNum] ?? ['amount' => 0, 'students' => 0];
                $content .= '<td style="text-align: center; background-color: #ffeb3b;">' . $monthTotal['students'] . '</td>';
                $content .= '<td style="text-align: center; background-color: #ffeb3b;">' . number_format($monthTotal['amount'], 2) . '</td>';
                
                $grandTotal += $monthTotal['amount'];
                $grandStudentCount += $monthTotal['students'];
            }
            
            $content .= '</tr>
                </tfoot>
            </table>';
            
            // Grand total
            $content .= '<div style="text-align: center; margin-top: 15px; padding: 15px; background-color: #000; color: white; font-weight: bold; border: 2px solid #333;">
                <strong>' . number_format($grandTotal, 2) . '</strong>
            </div>';
            
            $content .= '</div>';
        }
        
        return $content;
    }

    private function generateCollectionFormatHTML($from, $to, $isPrint = false)
    {
        // Get expected payments from student_payment_log
        $expectedPayments = DB::table('student_payment_log')
            ->join('students', 'student_payment_log.student_ic', 'students.ic')
            ->whereBetween('student_payment_log.date_of_payment', [$from, $to])
            ->where('students.status', 8) // Graduate status
            ->select(
                'students.name',
                'students.ic',
                'students.no_matric',
                'student_payment_log.date_of_call',
                'student_payment_log.date_of_payment',
                'student_payment_log.amount as expected_amount',
                DB::raw('MONTH(student_payment_log.date_of_payment) as month'),
                DB::raw('YEAR(student_payment_log.date_of_payment) as year')
            )
            ->orderBy('student_payment_log.date_of_payment')
            ->get();

        // Get actual payments for the same period and students
        $studentICs = $expectedPayments->pluck('ic')->unique();
        
        $actualPayments = DB::table('tblpayment')
            ->join('tblpaymentdtl', 'tblpayment.id', 'tblpaymentdtl.payment_id')
            ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
            ->whereIn('tblpayment.student_ic', $studentICs)
            ->where('tblpayment.process_status_id', 2)
            ->where('tblpayment.process_type_id', 1)
            ->where('tblstudentclaim.groupid', 1)
            ->whereBetween('tblpayment.add_date', [$from, $to])
            ->select(
                'tblpayment.student_ic as ic',
                'tblpayment.add_date as payment_date',
                DB::raw('SUM(tblpaymentdtl.amount) as actual_amount'),
                DB::raw('MONTH(tblpayment.add_date) as month'),
                DB::raw('YEAR(tblpayment.add_date) as year')
            )
            ->groupBy('tblpayment.student_ic', 'tblpayment.add_date', 'month', 'year')
            ->get();

        // Group data by month-year
        $collectionData = [];
        
        foreach ($expectedPayments as $expected) {
            $monthYear = $expected->year . '-' . str_pad($expected->month, 2, '0', STR_PAD_LEFT);
            
            if (!isset($collectionData[$monthYear])) {
                $collectionData[$monthYear] = [
                    'month_name' => date('F Y', strtotime($expected->year . '-' . $expected->month . '-01')),
                    'total_expected' => 0,
                    'total_actual' => 0,
                    'student_count' => 0,
                    'payment_count' => 0
                ];
            }
            
            $collectionData[$monthYear]['total_expected'] += $expected->expected_amount;
            $collectionData[$monthYear]['student_count']++;
        }

        foreach ($actualPayments as $actual) {
            $monthYear = $actual->year . '-' . str_pad($actual->month, 2, '0', STR_PAD_LEFT);
            
            if (isset($collectionData[$monthYear])) {
                $collectionData[$monthYear]['total_actual'] += $actual->actual_amount;
                $collectionData[$monthYear]['payment_count']++;
            }
        }

        // Generate HTML
        if ($isPrint) {
            $content = '<!DOCTYPE html>
            <html>
            <head>
                <title>Collection Report - Graduate Payments</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .date-range { text-align: center; margin-bottom: 30px; font-weight: bold; }
                    .collection-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    .collection-table th, .collection-table td { border: 1px solid #ddd; padding: 12px; text-align: center; }
                    .collection-table th { background-color: #007bff; color: white; font-weight: bold; }
                    .collection-table tbody tr:nth-child(even) { background-color: #f9f9f9; }
                    .total-row { background-color: #e9ecef; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Collection Report - Graduate Expected vs Actual Payments</h2>
                </div>
                <div class="date-range">Period: ' . date('d/m/Y', strtotime($from)) . ' - ' . date('d/m/Y', strtotime($to)) . '</div>';
        } else {
            $content = '<div class="card">
                <div class="card-header">
                    <h3>Collection Report - Graduate Expected vs Actual Payments</h3>
                    <h5>Period: ' . date('d/m/Y', strtotime($from)) . ' to ' . date('d/m/Y', strtotime($to)) . '</h5>
                </div>
                <div class="card-body">';
        }

        $content .= '<table class="' . ($isPrint ? 'collection-table' : 'table table-bordered table-striped') . '" id="collection_table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Expected Amount (RM)</th>
                                <th>Actual Amount (RM)</th>
                                <th>Difference (RM)</th>
                                <th>Collection Rate (%)</th>
                                <th>Student Count</th>
                                <th>Payment Count</th>
                            </tr>
                        </thead>
                        <tbody>';

        $totalExpected = 0;
        $totalActual = 0;
        $totalStudents = 0;
        $totalPayments = 0;

        ksort($collectionData);
        foreach ($collectionData as $data) {
            $difference = $data['total_actual'] - $data['total_expected'];
            $collectionRate = $data['total_expected'] > 0 ? ($data['total_actual'] / $data['total_expected']) * 100 : 0;
            
            $content .= '<tr>
                <td>' . $data['month_name'] . '</td>
                <td>' . number_format($data['total_expected'], 2) . '</td>
                <td>' . number_format($data['total_actual'], 2) . '</td>
                <td class="' . ($difference >= 0 ? 'text-success' : 'text-danger') . '">' . number_format($difference, 2) . '</td>
                <td>' . number_format($collectionRate, 1) . '%</td>
                <td>' . $data['student_count'] . '</td>
                <td>' . $data['payment_count'] . '</td>
            </tr>';
            
            $totalExpected += $data['total_expected'];
            $totalActual += $data['total_actual'];
            $totalStudents += $data['student_count'];
            $totalPayments += $data['payment_count'];
        }

        $totalDifference = $totalActual - $totalExpected;
        $totalCollectionRate = $totalExpected > 0 ? ($totalActual / $totalExpected) * 100 : 0;

        $content .= '</tbody>
                    <tfoot>
                        <tr class="' . ($isPrint ? 'total-row' : 'table-info font-weight-bold') . '">
                            <td><strong>TOTAL</strong></td>
                            <td><strong>' . number_format($totalExpected, 2) . '</strong></td>
                            <td><strong>' . number_format($totalActual, 2) . '</strong></td>
                            <td class="' . ($totalDifference >= 0 ? 'text-success' : 'text-danger') . '"><strong>' . number_format($totalDifference, 2) . '</strong></td>
                            <td><strong>' . number_format($totalCollectionRate, 1) . '%</strong></td>
                            <td><strong>' . $totalStudents . '</strong></td>
                            <td><strong>' . $totalPayments . '</strong></td>
                        </tr>
                    </tfoot>
                </table>';

        if ($isPrint) {
            $content .= '</body></html>';
        } else {
            $content .= '</div></div>';
            
            // Add JavaScript for DataTable initialization
            $content .= '<script>
                $(document).ready(function() {
                    $("#collection_table").DataTable({
                        dom: "lBfrtip",
                        buttons: ["copy", "csv", "excel", "pdf", "print"],
                        pageLength: 25,
                        responsive: true
                    });
                });
            </script>';
        }

        return $content;
    }

}
