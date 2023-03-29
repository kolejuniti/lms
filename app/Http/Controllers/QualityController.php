<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\subject;
use App\Models\student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;
use Mail;
use Intervention\Image\Facades\Image;

class QualityController extends Controller
{
    public function attendanceReport()
    {

        $data['faculty'] = DB::table('tblfaculty')->get();
        
        $data['session'] = DB::table('sessions')->get();

        return view('quality.report.attendance', compact('data'));

    }

    public function getLectAttendance(Request $request)
    {

        $data['lecturer'] = DB::table('user_subjek')
                            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                            ->join('users', 'user_subjek.user_ic', 'users.ic')
                            ->where([
                                ['user_subjek.session_id', $request->session],
                                ['users.faculty', $request->faculty]
                            ])->select('user_subjek.id', 'subjek.course_name AS course', 'subjek.course_code AS code', 'users.*')->get();


        foreach($data['lecturer'] as $key => $lect)
        {

            $data['attendance'][$key] = DB::table('tblclassattendance')
                                        ->where('groupid', $lect->id)
                                        ->groupBy('classdate')
                                        ->groupBy('groupname')
                                        ->get();

        }

        return view('quality.report.attendanceGetLecturer', compact('data'));

    }
}
