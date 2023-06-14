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

class AllController extends Controller
{
    public function staffPosting()
    {

        $data['user'] = DB::table('users')
                        ->join('tblfaculty', 'users.faculty', 'tblfaculty.id')
                        ->join('tbluser_type', 'users.usrtype', 'tbluser_type.code')
                        ->select('users.*', 'tbluser_type.name AS type', 'tblfaculty.facultyname AS faculty')
                        ->where('users.ic', Auth::user()->ic)->first();

        $data['post'] = DB::table('tblposting')->where('staff_ic', $data['user']->ic)->get();

        return view('alluser.post.post', compact('data'));

    }

    public function postingCreate(Request $request)
    {

        if(isset($request->idS))
        {

            DB::table('tblposting')->where('id', $request->idS)->update([
                'date' => date('Y-m-d'),
                'post_date' => $request->date,
                'title' => $request->title,
                'channel' => $request->channel,
                'link' => $request->link,
                'channel_type' => $request->type,
                'status' => $request->status,
                'total_view' => $request->view,
                'total_comment' => $request->comment,
                'total_like' => $request->like,
                'total_share' => $request->share
            ]);

            $alert =  'Post successfully updated!';

        }else{

            DB::table('tblposting')->insert([
                'staff_ic' => Auth::user()->ic,
                'date' => date('Y-m-d'),
                'post_date' => $request->date,
                'title' => $request->title,
                'channel' => $request->channel,
                'link' => $request->link,
                'channel_type' => $request->type,
                'status' => $request->status,
                'total_view' => $request->view,
                'total_comment' => $request->comment,
                'total_like' => $request->like,
                'total_share' => $request->share
            ]);

            $alert =  'Post successfully added!';

        }

        return back()->with('success', $alert);

    }

    public function postingDelete(Request $request)
    {

        DB::table('tblposting')->where('id', $request->id)->delete();

        return back()->with('deleted', 'Post successfully deleted!');

    }

    public function postingUpdate(Request $request)
    {

        $data['post'] = DB::table('tblposting')->where('id', $request->id)->first();

        return view('alluser.post.updatePost', compact('data'));

    }

    public function adminPosting()
    {

        $data['faculty'] = DB::table('tblfaculty')->get();

        return view('alluser.post.admin', compact('data'));

    }

    public function getStaffList(Request $request)
    {
        $staffs = DB::table('users')->where('name', 'LIKE', "%".$request->search."%")
                                         ->orwhere('ic', 'LIKE', "%".$request->search."%")
                                         ->orwhere('no_staf', 'LIKE', "%".$request->search."%")->get();

        $content = "";

        $content .= "<option value='0' selected disabled>-</option>";
        foreach($staffs as $stf){

            $content .= "<option data-style=\"btn-inverse\"
            data-content=\"<div class='row'>
                <div class='col-md-2'>
                <div class='d-flex justify-content-center'>
                    <img src='' 
                        height='auto' width='70%' class='bg-light ms-0 me-2 rounded-circle'>
                        </div>
                </div>
                <div class='col-md-10 align-self-center lh-lg'>
                    <span><strong>". $stf->name ."</strong></span><br>
                    <span>". $stf->email ." | <strong class='text-fade'>". $stf->ic ."</strong></span><br>
                    <span class='text-fade'></span>
                </div>
            </div>\" value='". $stf->ic ."' ></option>";

        }
        
        return $content;

    }

    public function getStaffPost(Request $request)
    {

        $data['post'] = [];

        if(isset($request->staff))
        {

            $post = DB::table('tblposting')
                    ->join('users', 'tblposting.staff_ic', 'users.ic')
                    ->where('tblposting.staff_ic', $request->staff);

        }

        if(isset($request->faculty))
        {

            $post = DB::table('tblposting')
                    ->join('users', 'tblposting.staff_ic', 'users.ic')
                    ->where('users.faculty', $request->faculty);

        }

        if(isset($request->from) && isset($request->to))
        {

            $post = DB::table('tblposting')
                    ->join('users', 'tblposting.staff_ic', 'users.ic')
                    ->whereBetween('tblposting.post_date', [$request->from, $request->to]);

        }

        if(isset($post))
        {

            $data['post'] = $post->get();

        }

        return view('alluser.post.adminGetStaff', compact('data'));

    }
}
