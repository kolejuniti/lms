<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\student;
use App\Models\User;
use App\Models\UserStudent;
use App\Models\subject;
use Input;

class URController extends Controller
{
    public function dashboard()
    {

        return view('dashboard');

    }
}
