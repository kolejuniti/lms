<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\student;
use App\Models\subject;
use App\Models\Tblevent;
use App\Models\Tblevent2;
use App\Models\UserStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;


class HEA_Controller extends Controller
{
    public function index()
    {

        return view('dashboard');

    }
}
