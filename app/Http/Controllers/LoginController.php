<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = User::where('email', $request->email)->first();
            if($user->usrtype == 'ADM')
            {
               return redirect()->route('admin');
            }
            elseif($user->usrtype == 'RGS') {
                return redirect()->route('pendaftar');
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
        }
    }

    public function username(){
        return 'email';
    }
}
