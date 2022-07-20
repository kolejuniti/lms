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

class ForumController extends Controller
{
    public function lectForum(Request $request)
    {
        $TopicID = '';

        if(isset($request->TopicID))
        {
            $TopicID = $request->TopicID;
        }

        //Session::put('CourseIDS', $request->id);

       
        Session::put('CourseIDS', $request->id);
        

        if(Session::get('SessionIDS') == null)
        {
        Session::put('SessionIDS', $request->session);
        }

        $course = DB::table('subjek')->where('id', Session::get('CourseIDS'))->first();

        $topic = DB::table('tblforum_topic')->where([
            ['CourseID', Session::get('CourseIDS')],
            ['SessionID', Session::get('SessionIDS')]
        ])->get();

        //dd(Session::get('CourseIDS'));

        return view('lecturer.forum.forum', compact('topic'))->with('course', $course)->with('TopicID', $TopicID);

    }

    public function insertTopic(Request $request){

        $user = Auth::user();

        //dd($user);

        DB::table('tblforum_topic')->insert([
            'TopicName' => $request->inputTitle,
            'CourseID' => Session::get('CourseIDS'),
            'SessionID' => Session::get('SessionIDS'),
            'Addby' => $user->ic
        ]);

        return redirect('/lecturer/forum/'. Session::get('CourseIDS') . '?session='.  Session::get('SessionIDS'));

    }

    public function insertForum(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'upforum' => ['required','string']
        ]);

        DB::table('tblforum')->insert([
            'TopicID' => $request->tpcID,
            'Content' => $data['upforum'],
            'CourseID' => $request->id,
            'UpdatedBy' => $user->ic
        ]);

        //dd($request->id);

        return redirect('/lecturer/forum/'. $request->id .'?TopicID='. $request->tpcID);

    }


    //this is student's saction

    public function studForum(Request $request)
    {
        $TopicID = '';

        if(isset($request->TopicID))
        {
            $TopicID = $request->TopicID;
        }

        Session::put('CourseIDS', $request->id);

        if(Session::get('SessionIDS') == null)
        {
        Session::put('SessionIDS', $request->session);
        }

        $course = DB::table('subjek')->where('id', Session::get('CourseIDS'))->first();

        $topic = DB::table('tblforum_topic')->where([
            ['CourseID', Session::get('CourseIDS')],
            ['SessionID', Session::get('SessionIDS')]
        ])->get();

        //dd($topic);

        return view('student.forum.forum', compact('topic'))->with('course', $course)->with('TopicID', $TopicID);

    }

    public function studinsertForum(Request $request)
    {
        $user = Auth::guard('student')->user();

        $data = $request->validate([
            'upforum' => ['required','string']
        ]);

        DB::table('tblforum')->insert([
            'TopicID' => $request->tpcID,
            'Content' => $data['upforum'],
            'CourseID' => $request->id,
            'UpdatedBy' => $user->ic
        ]);

        return redirect('/student/forum/'. $request->id .'?TopicID='. $request->tpcID);

    }
}
