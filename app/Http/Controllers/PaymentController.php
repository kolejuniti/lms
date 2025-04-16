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
            ->join('tblclaimdtl', 'tblpaymentdtl.claimDtl_id', 'tblclaimdtl.id')
            ->join('tblclaim', 'tblclaimdtl.claim_id', 'tblclaim.id')
            ->where([
                ['tblpaymentdtl.claimDtl_id', $tsy->id],
                ['tblpayment.student_ic', Auth::guard('student')->user()->ic],
                ['tblpaymentdtl.claim_type_id', $tsy->claim_package_id],
                ['tblpayment.session_id', $tsy->session_id],
                ['tblclaim.semester_id', $tsy->semester_id],
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
            return ["message"=>"Field Error", "error" => $validator->errors()->all()];
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

        // Get current authenticated user with no_matric field
        $user = DB::table('students')
                    ->leftjoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                    ->where('students.ic', Auth::guard('student')->user()->ic)
                    ->first();

        // Create a secure token for this payment session
        $payment_auth_token = bin2hex(random_bytes(32));
        
        // Store token in session and database with expiry (24 hours)
        $expiry = now()->addHours(24);
        session(['payment_auth_token' => $payment_auth_token]);
        
        // Store in database for more persistence
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $payment_auth_token,
            'created_at' => now()
        ]);
        
        // Store proper login credentials in session - matric number is the login field, NOT IC
        session(['student_no_matric' => $user->no_matric]);  // Store matric number for regular login
        session(['payment_in_progress' => true]);
        
        // Also store the authenticated user in the User session key
        session(['User' => Auth::guard('student')->user()]);

        $order_number = rand(1111111111,9999999999);
        $buyer_name = $user->name;
        $buyer_phone = $user->no_tel;
        $buyer_email = $user->email;
        $product_description = 'Payment for order no. ' . $order_number;
        $transaction_amount = $request->amount;
        $callback_url = $request->callback_url ?? '';
        
        // Add our auth token to the redirect URL
        $redirect_url = url('/checkout/securePay/receipt?id=' . $request->id . '&auth_token=' . $payment_auth_token);
        
        // Add cancel URL to handle payment cancellations
        $cancel_url = url('/checkout/securePay/cancel?id=' . $request->id . '&auth_token=' . $payment_auth_token);
        
        $redirect_post = "true";
        if(isset($request->buyer_bank_code)) { 
            $buyer_bank_code = $request->buyer_bank_code; 
        }

        //buyer_email|buyer_name|buyer_phone|callback_url|order_number|product_description|redirect_url|transaction_amount|uid 
        $string = $buyer_email."|".$buyer_name."|".$buyer_phone."|".$callback_url."|".$order_number."|".$product_description."|".$redirect_url ."|".$transaction_amount."|".$uid;

        $sign = hash_hmac('sha256', $string, $checksum_token);

        if(isset($request->buyer_bank_code)) {  
            $post_data = "buyer_name=".urlencode($buyer_name)."&token=". urlencode($auth_token) 
            ."&callback_url=".urlencode($callback_url)."&redirect_url=". urlencode($redirect_url) . 
            "&order_number=".urlencode($order_number)."&buyer_email=".urlencode($buyer_email).
            "&buyer_phone=".urlencode($buyer_phone)."&transaction_amount=".urlencode($transaction_amount).
            "&product_description=".urlencode($product_description)."&redirect_post=".urlencode($redirect_post).
            "&checksum=".urlencode($sign)."&buyer_bank_code=".urlencode($buyer_bank_code).
            "&cancel_url=".urlencode($cancel_url);
        }
        else
        {
            $post_data = "buyer_name=".urlencode($buyer_name)."&token=". urlencode($auth_token) 
            ."&callback_url=".urlencode($callback_url)."&redirect_url=". urlencode($redirect_url) . 
            "&order_number=".urlencode($order_number)."&buyer_email=".urlencode($buyer_email).
            "&buyer_phone=".urlencode($buyer_phone)."&transaction_amount=".urlencode($transaction_amount).
            "&product_description=".urlencode($product_description)."&redirect_post=".urlencode($redirect_post).
            "&checksum=".urlencode($sign).
            "&cancel_url=".urlencode($cancel_url);	
        }

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

    /**
     * Handle payment cancellation from SecurePay
     */
    public function handleSecurePayCancel(Request $request)
    {
        // First try to restore authentication using the token
        $auth_token = $request->auth_token;
        
        // Similar authentication logic as in showReceiptSecurePay
        if (!Auth::guard('student')->check() && $auth_token) {
            // Find token in database
            $token_record = DB::table('password_resets')
                                ->where('token', $auth_token)
                                ->where('created_at', '>=', now()->subHours(24))
                                ->first();
                                
            if ($token_record) {
                // Find the user by email
                $student = DB::table('students')
                             ->where('students.email', $token_record->email)
                             ->first();

                if ($student) {
                    // Use loginUsingId to authenticate the user
                    Auth::guard('student')->loginUsingId($student->id, true); // true for remember me
                    session(['session_restored' => true]);
                    session(['payment_token_used' => true]);
                    session(['User' => Auth::guard('student')->user()]);
                    session(['student_no_matric' => $student->no_matric]); // Store matric number
                }
            }
        }
        
        // If still not authenticated, try to directly log in using stored matric number
        if (!Auth::guard('student')->check() && session()->has('student_no_matric')) {
            $student = DB::table('students')
                        ->where('no_matric', session('student_no_matric'))
                        ->first();
            
            if ($student) {
                Auth::guard('student')->loginUsingId($student->id, true);
                session(['session_restored' => true]);
                session(['User' => Auth::guard('student')->user()]);
            }
        }
        
        // If we still couldn't restore the session, redirect to login
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login')
                ->with('error', 'Your session has expired. Please log in again.')
                ->with('payment_id', $request->id);
        }
        
        // Mark payment as cancelled in the database if the ID was provided
        if ($request->id) {
            // Update payment status and add cancellation details
            DB::table('tblpayment')->where('id', $request->id)->update([
                'process_status_id' => 3, // Use appropriate status code for cancelled
                'termination_reason' => 'Payment cancelled by user through SecurePay gateway',
                'termination_date' => now(),
                'termination_staffID' => Auth::guard('student')->user()->ic ?? null,
                'mod_staffID' => Auth::guard('student')->user()->ic ?? null,
                'mod_date' => now()
            ]);
        }
        
        // Reset payment_in_progress flag
        session(['payment_in_progress' => false]);
        
        // Clean up any other session variables related to payment
        session()->forget(['payment_auth_token', 'payment_token_used', 'session_restored']);
        
        // Redirect to payment page with cancellation message
        return redirect()->route('yuran-pengajian')
            ->with('warning', 'Your payment was cancelled. No charges were made to your account.');
    }

    public function showReceiptSecurePay(Request $request)
    {
        // First try to restore authentication using the token
        $auth_token = $request->auth_token;
        
        if (!Auth::guard('student')->check() && $auth_token) {
            // Find token in database
            $token_record = DB::table('password_resets')
                                ->where('token', $auth_token)
                                ->where('created_at', '>=', now()->subHours(24))
                                ->first();
                                
            if ($token_record) {
                // Find the user by email
                $student = DB::table('students')
                             ->where('students.email', $token_record->email)
                             ->first();

                if ($student) {
                    // Use no_matric and password for authentication
                    $credentials = [
                        'no_matric' => $student->no_matric,
                        'password' => $student->password // This won't work directly as password is hashed
                    ];
                    
                    // Instead of directly using the hashed password, we'll use loginUsingId
                    // but store proper information in the session
                    Auth::guard('student')->loginUsingId($student->id, true); // true for remember me
                    session(['session_restored' => true]);
                    session(['payment_token_used' => true]);
                    session(['User' => Auth::guard('student')->user()]);
                    session(['student_no_matric' => $student->no_matric]); // Store matric number
                    
                    // Remove used token
                    DB::table('password_resets')
                        ->where('token', $auth_token)
                        ->delete();
                }
            }
        }
        
        // If still not authenticated, try to directly log in using stored matric number
        if (!Auth::guard('student')->check() && session()->has('student_no_matric')) {
            $student = DB::table('students')
                        ->where('no_matric', session('student_no_matric'))
                        ->first();
            
            if ($student) {
                Auth::guard('student')->loginUsingId($student->id, true);
                session(['session_restored' => true]);
                session(['User' => Auth::guard('student')->user()]);
            }
        }

        // If we still couldn't restore the session, redirect to login
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login')
                ->with('error', 'Your session has expired. Please log in again to view your receipt.')
                ->with('payment_id', $request->id); // Pass payment ID to be able to retrieve it after login
        }

        $data = $request->all();
        ksort($data);
        
        // Check for payment status - SecurePay only uses payment_status (boolean true/false)
        $payment_status = isset($data['payment_status']) ? $data['payment_status'] : null;

        // Convert string representation of boolean to actual boolean if needed
        if ($payment_status === 'true') {
            $payment_status = true;
        } elseif ($payment_status === 'false') {
            $payment_status = false;
        }

        // If payment_status is false or not set, treat as failed/cancelled
        if ($payment_status === false) {
            // Mark payment as cancelled in the database
            if ($request->id) {
                DB::table('tblpayment')->where('id', $request->id)->update([
                    'process_status_id' => 3, // Use appropriate status code for cancelled
                    'termination_reason' => 'Payment failed or cancelled',
                    'termination_date' => now(),
                    'termination_staffID' => Auth::guard('student')->user()->ic ?? null,
                    'mod_staffID' => Auth::guard('student')->user()->ic ?? null,
                    'mod_date' => now()
                ]);
            }
            
            // Reset payment_in_progress flag
            session(['payment_in_progress' => false]);
            
            return redirect()->route('yuran-pengajian')
                ->with('warning', 'Your payment was not successful. Please try again.');
        }

        // Only proceed if payment_status is explicitly true
        if ($payment_status !== true) {
            return redirect()->route('yuran-pengajian')
                ->with('warning', 'Invalid payment status received. Please try again or contact support.');
        }

        $merchant_reference_number = $data['merchant_reference_number'] ?? null;

        if (!$merchant_reference_number) {
            return redirect()->route('yuran-pengajian')->with('error', 'Payment reference number is missing.');
        }

        $ref_no = DB::table('tblref_no')
                    ->join('tblpayment', 'tblref_no.process_type_id', 'tblpayment.process_type_id')
                    ->where('tblpayment.id', $request->id)
                    ->select('tblref_no.*','tblpayment.student_ic')->first();

        if (!$ref_no) {
            return redirect()->route('yuran-pengajian')->with('error', 'Reference number not found.');
        }

        DB::table('tblref_no')->where('id', $ref_no->id)->update([
            'ref_no' => $ref_no->ref_no + 1
        ]);

        DB::table('tblpayment')->where('id', $request->id)->update([
            'process_status_id' => 2,
            'ref_no' => $ref_no->code . ($ref_no->ref_no + 1)
        ]);

        DB::table('tblpaymentmethod')->where('payment_id', $request->id)->update([
            'no_document' => $merchant_reference_number
        ]);

        // Reset payment_in_progress flag since we're done
        session(['payment_in_progress' => false]);

        // Make sure the user is stored in the session
        session(['User' => Auth::guard('student')->user()]);

        // Redirect to the receipt page after updating the payment status
        return redirect()->route('checkout.receipt.success', ['id' => $request->id]);
    }

    public function showReceiptSuccess(Request $request)
    {
        // If we're not authenticated, check if we need to redirect to login
        if (!Auth::guard('student')->check()) {
            // If payment_in_progress is not set, don't try session restoration here
            // as we likely navigated to this page directly without payment
            if (!session('payment_in_progress')) {
                return redirect()->route('login')
                    ->with('error', 'Please log in to view your receipt.')
                    ->with('payment_id', $request->id);
            }
            
            // Try to restore session using ID
            if (session()->has('student_no_matric')) {
                $student = DB::table('students')
                           ->where('no_matric', session('student_no_matric'))
                           ->first();
                
                if ($student) {
                    // We can't use passwords here since they're hashed in the DB
                    // Log in directly using ID
                    Auth::guard('student')->loginUsingId($student->id, true); // true for remember me
                    session(['session_restored' => true]);
                    session(['User' => Auth::guard('student')->user()]);
                }
            }
            
            // If still not authenticated, redirect to login
            if (!Auth::guard('student')->check()) {
                return redirect()->route('login')
                    ->with('error', 'Your session has expired. Please log in again to view your receipt.')
                    ->with('payment_id', $request->id);
            }
        }
        
        // Get the payment details
        $data['payment'] = DB::table('tblpayment')->where('id', $request->id)->first();

        if(!$data['payment']) {
            return redirect()->route('yuran-pengajian')->with('error', 'Payment not found.');
        }

        // Get payment method details
        $data['method'] = DB::table('tblpaymentmethod')
                ->join('tblpayment_method', 'tblpaymentmethod.claim_method_id', 'tblpayment_method.id')
                ->where('tblpaymentmethod.payment_id', $request->id)
                ->select('tblpaymentmethod.*', 'tblpayment_method.name AS claim_method_id')->first();

        // Get payment details
        $data['details'] = DB::table('tblpaymentdtl')
               ->join('tblstudentclaim', 'tblpaymentdtl.claim_type_id', 'tblstudentclaim.id')
               ->where('payment_id', $request->id)
               ->get();
           
        // Make sure the user is stored in the session
        session(['User' => Auth::guard('student')->user()]);
        
        // Clean up payment session variables after displaying receipt
        session()->forget(['payment_in_progress', 'student_no_matric', 'payment_token_used', 'session_restored']);

        return view('student.payment.receipt', compact('data'));
    }

}
