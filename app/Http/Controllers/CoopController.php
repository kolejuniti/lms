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

class CoopController extends Controller
{
    public function dashboard()
    {
        Session::put('User', Auth::user());

        return view('dashboard');
    }

    public function voucher()
    {

        return view('coop.voucher.voucher');

    }

    private function getVoucherData($voucher) {
        return DB::table('tblstudent_voucher')
                  ->join('tblprocess_status', 'tblstudent_voucher.status', 'tblprocess_status.id')
                  ->select('tblstudent_voucher.*', 'tblprocess_status.name AS status')
                  ->where('tblstudent_voucher.no_voucher', $voucher)->first();
    }

    public function findVoucher(Request $request)
    {

        $data['voucher'] = $this->getVoucherData($request->search);

        if($data['voucher'] != null)
        {
            
            $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
                           ->where('ic', $data['voucher']->student_ic)->first();

            return view('coop.voucher.voucherGetStudent', compact('data'));

        }else{

            return ["message" => "Error"];

        }

    }

    public function redeemVoucher(Request $request)
    {
        $error = '';
        
        ($request->date != null) ? $today = $request->date : $today = date('Y-m-d');

        $voucher = DB::table('tblstudent_voucher')->where('id', $request->id)->first();

        if($today >= $voucher->expiry_date) {

            $error = 'Voucher Expired!';

        }else{

            DB::table('tblstudent_voucher')->where('id', $request->id)->update([
                'status' => 2,
                'redeem_date' => $today
            ]);

        }

        $voucher = DB::table('tblstudent_voucher')->where('id', $request->id)->first();

        $data['voucher'] = $this->getVoucherData($voucher->no_voucher);

        $data['student'] = DB::table('students')
                           ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                           ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                           ->select('students.*', 'tblstudent_status.name AS status', 'tblprogramme.progname AS program')
                           ->where('ic', $data['voucher']->student_ic)->first();

        return view('coop.voucher.voucherGetStudent', compact('data'))->with('error', $error);
    }

    public function dailyReport()
    {

        return view('coop.voucher.dailyReport');

    }

    public function getDailyReport(Request $request)
    {
        $data['voucher'] = [];
        $data['sum'] = [];

        $voucher = DB::table('tblstudent_voucher')
                  ->join('tblprocess_status', 'tblstudent_voucher.status', 'tblprocess_status.id')
                  ->join('students', 'tblstudent_voucher.student_ic', 'students.ic')
                  ->join('users', 'tblstudent_voucher.staff_ic', 'users.ic')
                  ->select('tblstudent_voucher.*', 'tblprocess_status.name AS status', 'students.name AS student', 'users.name AS staff')
                  ->whereBetween('tblstudent_voucher.redeem_date', [$request->from, $request->to]);

        $data['sum'] = $voucher->sum('amount');

        $data['voucher'] = $voucher->get();

        return view('coop.voucher.getDailyReport', compact('data'));

    }

}
