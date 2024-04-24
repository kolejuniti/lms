<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Charge;
use Stripe\PaymentIntent;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->join('sessions AS t1', 'students.intake', 't1.SessionID')
                           ->join('sessions AS t2', 'students.session', 't2.SessionID')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program', 'students.program AS progid', 't1.SessionName AS intake_name', 't2.SessionName AS session_name')
                           ->where('ic', Auth::guard('student')->user()->ic)->first();

        $data['method'] = DB::table('tblpayment_method')->get();

        $data['bank'] = DB::table('tblpayment_bank')->orderBy('name', 'asc')->get();

        $data['tuition'] = DB::table('tblclaimdtl')
                            ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
                            ->join('tblstudentclaim', 'tblclaimdtl.claim_package_id', 'tblstudentclaim.id')
                            ->where('tblclaim.student_ic', Auth::guard('student')->user()->ic)
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
                ['tblpaymentdtl.claimDtl_id', $tsy->id],
                ['tblpayment.student_ic', Auth::guard('student')->user()->ic],
                ['tblpaymentdtl.claim_type_id', $tsy->claim_package_id],
                ['tblpayment.session_id', $tsy->session_id],
                ['tblpayment.semester_id', $tsy->semester_id],
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

        return view('student.payment.payment', compact('data'));
    }

    public function submitPayment(Request $request)
    {
        $paymentDetail = $request->paymentDetail;

        $validator = Validator::make($request->all(), [
            'paymentDetail' => 'required'
        ]);

        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

      
                $payment = json_decode($paymentDetail);
                
                if($payment->total != null)
                {
                    $stddetail = DB::table('students')->where('ic', Auth::guard('student')->user()->ic)->first();

                    $id = DB::table('tblpayment')->insertGetId([
                        'student_ic' => Auth::guard('student')->user()->ic,
                        'date' => date('Y-m-d'),
                        'ref_no' => null,
                        'program_id' => $stddetail->program,
                        'session_id' => $stddetail->session,
                        'semester_id' => $stddetail->semester,
                        'amount' => $payment->total,
                        'process_status_id' => 1,
                        'process_type_id' => 1,
                        'add_staffID' => null,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => null,
                        'mod_date' => date('Y-m-d')
                    ]);

                    DB::table('tblpaymentmethod')->insert([
                        'payment_id' => $id,
                        'claim_method_id' => 17,
                        'bank_id' => null,
                        'no_document' => null,
                        'amount' => $payment->total,
                        'add_staffID' => null,
                        'add_date' => date('Y-m-d'),
                        'mod_staffID' => null,
                        'mod_date' => date('Y-m-d')
                    ]);

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
                                    'payment_id' => $id,
                                    'claimDtl_id' => $phy->id,
                                    'claim_type_id' => $claimdtl->claim_package_id,
                                    'amount' => $paymentinput2[$i]->payment,
                                    'add_staffID' => null,
                                    'add_date' => date('Y-m-d'),
                                    'mod_staffID' => null,
                                    'mod_date' => date('Y-m-d')
                                ]);

                            }
                        }

                        return response()->json(['message' => 'Success', 'id' => $id]);

                        // $ref_no = DB::table('tblref_no')
                        // ->join('tblpayment', 'tblref_no.process_type_id', 'tblpayment.process_type_id')
                        // ->where('tblpayment.id', $id)
                        // ->select('tblref_no.*', 'tblpayment.student_ic')->first();

                        // DB::table('tblref_no')->where('id', $ref_no->id)->update([
                        //     'ref_no' => $ref_no->ref_no + 1
                        // ]);

                        // DB::table('tblpayment')->where('id', $id)->update([
                        //     'process_status_id' => 2,
                        //     'ref_no' => $ref_no->code . $ref_no->ref_no + 1
                        // ]);
                    
                    }else{
                        return ["message" => "Please fill all required field!"];
                    }

                
                }else{
                    return ["message" => "Please fill all required field!"];
                }
        




    }

    public function showQuotation()
    {

        $data['payment'] = DB::table('tblpayment')->where('id', request()->id)->first();

        $data['method'] = DB::table('tblpaymentmethod')
                    ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                    ->where('tblpaymentmethod.payment_id', request()->id)
                    ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->first();

        $data['details'] = DB::table('tblpaymentdtl')
                   ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
                   ->where('payment_id', request()->id)
                   ->get();

        return view('student.payment.quotation', compact('data'));

    }

    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $priceInMYR = $request->input('price');
        $priceInCents = $priceInMYR * 100;

        $paymentMethod = $request->input('payment-method');
        $paymentMethodTypes = $paymentMethod === 'fpx' ? ['fpx'] : ['card'];

        $session = Session::create([
            'payment_method_types' => $paymentMethodTypes,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => 'Yuran Pengajianssss',
                    ],
                    'unit_amount' => $priceInCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('yuran-pengajian'),
        ]);

        return response()->json(['id' => $session->id]);
    }


    public function showReceipt(Request $request, $session_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::retrieve($session_id);
        $paymentIntent = PaymentIntent::retrieve($session->payment_intent);

        // Get the payment method type from the Checkout session
        $payment_method_type = isset($session->payment_method_types[0]) ? $session->payment_method_types[0] : 'unknown';

        return view('student.payment.receipt', [
            'amount' => $paymentIntent->amount / 100,
            'currency' => strtoupper($paymentIntent->currency),
            'payment_method' => $payment_method_type,
            'status' => $paymentIntent->status
        ]);
    }

    public function handlePaymentSuccess(Request $request)
    {
        $session_id = $request->get('session_id');

        if ($session_id) {
            return redirect()->route('checkout.receipt', ['session_id' => $session_id]);
        }

        return "Payment successful!";
    }

    // public function securePayCheckoutOLD(Request $request)
    // {

    //     // Validate the request data here
    //     // $validated = $request->validate([
    //     //     'buyer_name' => 'required|string',
    //     //     'buyer_email' => 'required|email',
    //     //     'buyer_phone' => 'required|string',
    //     //     'order_number' => 'required|string',
    //     //     'product_description' => 'required|string',
    //     //     'transaction_amount' => 'required|numeric',
    //     //     'callback_url' => 'nullable|url',
    //     //     'redirect_url' => 'nullable|url',
    //     //     'buyer_bank_code' => 'nullable|string', // If using bank codes
    //     // ]);

    //     // SecurePay API Credentials from .env
    //     $uid = env('SECUREPAY_UID');
    //     $checksum_token = env('SECUREPAY_CHECKSUM_TOKEN');
    //     $auth_token = env('SECUREPAY_AUTH_TOKEN');
    //     $endpoint = 'https://securepay.my/api/v1/payments';

    //     $user = DB::table('students')
    //                 ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
    //                 ->where('students.ic', Auth::guard('student')->user()->ic)
    //                 ->first();

    //     // dd($user);

    //     // Construct the string for checksum calculation
    //     $string = implode('|', [
    //         $user->email,
    //         $request->buyer_name,
    //         $user->no_tel,
    //         $request->callback_url ?? '',
    //         1111111111,
    //         'Payment for order no. ' . 1111111111,
    //         $request->redirect_url ?? '',
    //         $request->amount,
    //         $uid
    //     ]);

        

    //     // Calculate the checksum
    //     $checksum = hash_hmac('sha256', $string, $checksum_token);

    //     // Construct the POST data
    //     $post_data = [
    //         'buyer_name' => $user->name,
    //         'buyer_email' => $user->email,
    //         'buyer_phone' => $user->no_tel,
    //         'order_number' => 1111111111,
    //         'product_description' => 'Payment for order no. ' . 1111111111,
    //         'transaction_amount' => $request->amount,
    //         'callback_url' => $request->callback_url ?? '',
    //         'redirect_url' => $request->redirect_url ?? '',
    //         'token' => $auth_token,
    //         'checksum' => $checksum,
    //     ];

    //     // Add buyer bank code if provided
    //     if (!empty($request->buyer_bank_code)) {
    //         $post_data['buyer_bank_code'] = $request->buyer_bank_code;
    //     }

    //     // Send the POST request using Laravel's HTTP client
    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/x-www-form-urlencoded',
    //     ])->post($endpoint, $post_data);

    //     //dd($response);

    //     // Check the response and handle accordingly
    //     if ($response->successful()) {
    //         // Assuming SecurePay redirects us to a URL after successful payment creation
    //         $paymentUrl = $response->json()['payment_url'] ?? null;
    //         if ($paymentUrl) {
    //             return redirect()->away($paymentUrl);
    //         } else {
    //             // No payment URL provided in the response
    //             return back()->withErrors(['message' => 'No payment URL provided.']);
    //         }
    //     } else {
    //         // Handle errors here
    //         $error = $response->body(); // Get the raw response body
    //         return back()->withErrors(['message' => "Unable to create SecurePay payment session. Error: $error"]);
    //     }
    // }

    public function securePayCheckout(Request $request)
    {

        //Author: amir@p.my, amir@securepay.my
        //Org   : SecurePay
        //We need more contribution on sample codes. Email me.

       
        //Change with your token	
        $uid = env('SECUREPAY_UID');
        $checksum_token = env('SECUREPAY_CHECKSUM_TOKEN');
        $auth_token = env('SECUREPAY_AUTH_TOKEN');
        $url = 'https://securepay.my/api/v1/payments';

        dd($uid);

        #$request->order_number = '20200425132755';
        #$request->buyer_name = 'AHMAD AMSYAR MOHD ALI';
        #$request->buyer_email = 'amsyar@gmail.com';
        #$request->buyer_phone = '+60123121678';
        #$request->transaction_amount = '10.00';
        #$request->product_description = 'Payment for order no 20200425132755';
        #$request->callback_url = "";
        #$request->redirect_url = "";
        #$request->token = $auth_token;
        #$request->redirect_post = "true";

        $user = DB::table('students')
                    ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                    ->where('students.ic', Auth::guard('student')->user()->ic)
                    ->first();

        $order_number = rand(1111111111,9999999999);;
        $buyer_name = $user->name;
        $buyer_phone = $user->no_tel;
        $buyer_email = $user->email;
        $product_description = 'Payment for order no. ' . $order_number;
        $transaction_amount = $request->amount;
        $callback_url = $request->callback_url ?? '';
        $redirect_url = 'http://127.0.0.1:8000/checkout/securePay/receipt?id=' . $request->id;
        $redirect_post = "true";
        if(isset($request->buyer_bank_code)) { 
            $buyer_bank_code = $request->buyer_bank_code; 
        }




        //buyer_email|buyer_name|buyer_phone|callback_url|order_number|product_description|redirect_url|transaction_amount|uid 

        $string = $buyer_email."|".$buyer_name."|".$buyer_phone."|".$callback_url."|".$order_number."|".$product_description."|".$redirect_url ."|".$transaction_amount."|".$uid;


        
        #echo $string . "\n";
        #string = "amsyar@gmail.com|AHMAD AMSYAR MOHD ALI|+60123121678||20200425132755|Payment for order no 20200425132755||1540.40|5d80cc30-1a42-4f9f-9d6b-a69db5d26b01​"


        #$string = "amsyar@gmail.com|AHMAD AMSYAR MOHD ALI|0123121678||20200425132755|Payment for order no 20200425132755||1540.40|2aaa1633-e63f-4371-9b85-91d936aa56a1​";
        #$checksum_token = "159026b3b7348e2390e5a2e7a1c8466073db239c1e6800b8c27e36946b1f8713​";

        $sign = hash_hmac('sha256', $string, $checksum_token);

        #echo $sign . "\n";

        //
        //echo $sign

        //$hashed_string = hash_hmac($checksum_token.urldecode($_POST['product_description']).urldecode($_POST['transaction_amount']).urldecode($_POST['order_number']));

        if(isset($request->buyer_bank_code)) {  

        $post_data = "buyer_name=".urlencode($buyer_name)."&token=". urlencode($auth_token) 
        ."&callback_url=".urlencode($callback_url)."&redirect_url=". urlencode($redirect_url) . 
        "&order_number=".urlencode($order_number)."&buyer_email=".urlencode($buyer_email).
        "&buyer_phone=".urlencode($buyer_phone)."&transaction_amount=".urlencode($transaction_amount).
        "&product_description=".urlencode($product_description)."&redirect_post=".urlencode($redirect_post).
        "&checksum=".urlencode($sign)."&buyer_bank_code=".urlencode($buyer_bank_code);
        }
        else
        {
        $post_data = "buyer_name=".urlencode($buyer_name)."&token=". urlencode($auth_token) 
        ."&callback_url=".urlencode($callback_url)."&redirect_url=". urlencode($redirect_url) . 
        "&order_number=".urlencode($order_number)."&buyer_email=".urlencode($buyer_email).
        "&buyer_phone=".urlencode($buyer_phone)."&transaction_amount=".urlencode($transaction_amount).
        "&product_description=".urlencode($product_description)."&redirect_post=".urlencode($redirect_post).
        "&checksum=".urlencode($sign);	
        }


        #echo $post_data. "\n";

        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);

        $output = curl_exec($ch);
        if(curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        

        echo $output;

     
    }

    public function showReceiptSecurePay(Request $request)
    {

        $data = $request->all();
        ksort($data);

        $merchant_reference_number = $data['merchant_reference_number'];

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

        DB::table('tblpaymentmethod')->where('payment_id', $request->id)->update([
            'no_document' => $merchant_reference_number
        ]);

        // dd($merchant_reference_number);

        return back();

    }

}
