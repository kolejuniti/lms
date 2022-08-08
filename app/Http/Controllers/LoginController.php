<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            if($user->usrtype == 'ADM')
            {

               return redirect()->route('admin');
            }
            elseif($user->usrtype == 'RGS') {
                return redirect()->route('pendaftar');
            }
            elseif($user->usrtype == 'AR') {
                return redirect()->route('pendaftar_akademik');
            }
            elseif($user->usrtype == 'PL')
            {
                return redirect()->route('ketua_program');
            }
            elseif($user->usrtype == 'AO')
            {
                return redirect()->route('pegawai_takbir');
            }
            elseif($user->usrtype == 'LCT')
            {
                return redirect()->route('lecturer');
            }
        }else{
            return back()->with(["message"=>"Incorrect Email or Password!"]);
        }
    }

    public function username(){
        return 'email';
    }
}
