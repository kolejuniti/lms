<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class LogoutController extends Controller
{
    public function store(Request $request) {

        DB::table('tbluser_log')->insert([
            'ic' => Auth::user()->ic,
            'remark' => 'LOGOUT',
            'date' => Carbon::now()
        ]);

        auth()->logout();
        return redirect()->route('login');
    }
}
