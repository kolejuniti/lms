<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // set the remember me cookie if the user check the box
        $remember = ($request->get('remember') == 1) ? true : false;

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember))
        {
            User::where('email', $request->email)->update(['lastLogin' => Carbon::now()]);

            $user = User::where('email', $request->email)->first();

            DB::table('tbluser_log')->insert([
                'ic' => $user->ic,
                'remark' => 'LOGIN',
                'date' => Carbon::now()
            ]);
                
            if($user->usrtype == 'PL')
            {
                return redirect()->route('ketua_program');
            }
            elseif($user->usrtype == 'AO')
            {
                return redirect()->route('pegawai_takbir');
            }
            elseif($user->usrtype == 'DN')
            {
                return redirect()->route('dekan');
            }
            elseif($user->usrtype == 'LCT')
            {
                return redirect()->route('lecturer');
            }else
            {
                return back()->with(["message"=>"Please login again using Administrator login!"]);
            }

        }else{
            return back()->with(["message"=>"Incorrect Email or Password!"]);
        }
    }

    public function loginAdmin(Request $request)
    {

        // set the remember me cookie if the user check the box
        $remember = ($request->get('remember') == 1) ? true : false;

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember))
        {
            User::where('email', $request->email)->update(['lastLogin' => Carbon::now()]);
            
            $user = User::where('email', $request->email)->first();
            
            if($user->usrtype == 'ADM' && $request->usertypes == 'Admin')
            {
               return redirect()->route('admin.dashboard');
            }
            elseif($user->usrtype == 'RGS' && $request->usertypes == 'Pendaftar') {
                return redirect()->route('pendaftar.dashboard');
            }
            elseif($user->usrtype == 'AR' && $request->usertypes == 'PendaftarAkademik') {
                return redirect()->route('pendaftar_akademik.dashboard');
            }
            elseif($user->usrtype == 'FN' && $request->usertypes == 'Kewangan')
            {
                return redirect()->route('finance.dashboard');
            }
            elseif($user->usrtype == 'TS' && $request->usertypes == 'Bendahari')
            {
                return redirect()->route('treasurer.dashboard');
            }
            elseif($user->usrtype == 'OTR' && $request->usertypes == 'Others')
            {
                return redirect()->route('others.dashboard');
            }
            else{
                return back()->with(["message"=>"Not Authorized to Enter!"]);
            }
        }else{
            return back()->with(["message"=>"Incorrect Email or Password!"]);
        }

    }

    public function username(){
        return 'email';
    }
}
