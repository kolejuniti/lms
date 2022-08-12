<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subject;
use App\Models\student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AO_Controller extends Controller
{
    public function index()
    {
        //forgot current session
        Session::forget(['User','CourseID','SessionID']);

        Session::put('User', Auth::user());
        
        $ao = Auth::user();

        $data = subject::leftjoin('users as A', 'user_subjek.user_ic', '=', 'A.ic')
                        ->leftjoin('users as B', 'user_subjek.addby', '=', 'B.ic')
                        ->leftjoin('subjek', 'user_subjek.course_id','=', 'subjek.sub_id')
                        ->leftjoin('user_program', 'subjek.prgid', 'user_program.program_id')
                        ->leftjoin('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                        ->select('A.name as name','B.name as name2','subjek.course_name','subjek.course_code','sessions.SessionName','user_program.id')
                        ->where('A.faculty', $ao->faculty)
                        ->where('user_program.user_ic', $ao->ic)
                        //->orderBy('sessions.SessionID')
                        ->get();

        //dd($data);

        return view('pegawai_takbir', compact('data'));
    }
}
