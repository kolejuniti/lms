<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\UserStudent;
use Illuminate\Http\Request;

class LoginStudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:student')->except('logout');
    }

    public function login(Request $request)
    {
        
        if(Auth::guard('student')->attempt(['no_matric' => $request->ic, 'password' => $request->password]))
        {
               return redirect()->route('studentDashboard');
        }else{
            return back()->with(["message"=>"Incorrect IC or Password!"]);
        }
    }

    public function username(){
        return 'no_matric';
    }

    protected function guard()
    {
        return Auth::guard('student');
    }
}
