<?php

namespace App\Http\Controllers;
use Auth;
use Hash;
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
    $students = UserStudent::where('no_matric', $request->ic)->paginate(10);

    foreach ($students as $student) {
        if (Hash::check($request->password, $student->password)) {
            Auth::guard('student')->login($student);
            return redirect()->route('student');
        }
    }

    return redirect()->route('login')->with(["message"=>"Incorrect IC or Password!"]);
}

    public function username(){
        return 'no_matric';
    }

    protected function guard()
    {
        return Auth::guard('student');
    }
}
