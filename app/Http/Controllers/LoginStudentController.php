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
        if(Auth::guard('student')->attempt(['ic' => $request->ic, 'password' => $request->password]))
        {
               return redirect()->route('student');
        }
    }

    public function username(){
        return 'ic';
    }

    protected function guard()
    {
        return Auth::guard('student');
    }
}
