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
                        ->leftjoin('tblfaculty', 'users.faculty', 'tblfaculty.id')
                        ->join('tbluser_type', 'users.usrtype', 'tbluser_type.code')
                        ->select('users.*', 'tbluser_type.name AS type', 'tblfaculty.facultyname AS faculty')
                        ->where('users.ic', Auth::user()->ic)->first();

        $data['post'] = DB::table('tblposting')->where('staff_ic', $data['user']->ic)->get();

        foreach($data['post'] as $key => $post)
        {

            $data['history'][$key] = DB::table('tblposting_history')->where('post_id', $post->id)->get();

        }

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
                'total_share' => $request->share,
                'total_save' => $request->save,
                'update_view' => date('Y-m-d'),
                'update_comment' => date('Y-m-d'),
                'update_like' => date('Y-m-d'),
                'update_share' => date('Y-m-d'),
                'update_save' => date('Y-m-d')
            ]);

            DB::table('tblposting_history')->insert([
                'post_id' => $request->idS,
                'update_date' => date('Y-m-d'),
                'total_view' => $request->view,
                'total_comment' => $request->comment,
                'total_like' => $request->like,
                'total_share' => $request->share,
                'total_save' => $request->save,
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
                'total_share' => $request->share,
                'total_save' => $request->save
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

            if($request->faculty == 'all')
            {

                $post = DB::table('tblposting')
                    ->join('users', 'tblposting.staff_ic', 'users.ic');

            }else{

                $post = DB::table('tblposting')
                    ->join('users', 'tblposting.staff_ic', 'users.ic')
                    ->where('users.faculty', $request->faculty);

            }

        }

        if(isset($request->from) && isset($request->to))
        {

            $post = DB::table('tblposting')
                    ->join('users', 'tblposting.staff_ic', 'users.ic')
                    ->whereBetween('tblposting.post_date', [$request->from, $request->to]);

        }

        if(isset($request->from2) && isset($request->to2))
        {

            $post = DB::table('tblposting')
                    ->join('users', 'tblposting.staff_ic', 'users.ic')
                    ->whereBetween('tblposting.date', [$request->from2, $request->to2]);

        }

        if(isset($post))
        {

            $data['post'] = $post->leftjoin('tblfaculty', 'users.faculty', 'tblfaculty.id')->get();

        }

        return view('alluser.post.adminGetStaff', compact('data'));

    }

    public function studentSPM()
    {

        $data['program'] = DB::table('tblprogramme')->get();

        $data['faculty'] = DB::table('tblfaculty')->get();

        $data['year'] = DB::table('tblyear')->get();

        return view('alluser.student.spm.index', compact('data'));

    }

    public function getStudentSPM(Request $request)
    {

        $query = DB::table('students')
                    ->join('sessions', 'students.session', 'sessions.SessionID')
                    ->join('sessions AS b', 'students.intake', 'b.SessionID')
                    ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
                    ->whereIn('students.status', [2,6])
                    ->select('students.*', 'sessions.SessionName', 'tblstudent_status.name AS status');

        if($request->program != '')
        {
            $query->where('students.program', $request->program);

        }

        if($request->year != '')
        {
            $query->where('sessions.Year', $request->year);

        }

        $data['student'] = $query->get();

        foreach($data['student'] as $key => $std)
        {

            // Fetch the first four specific records (1, 2, 5, 6)
            $firstFour = DB::table('tblspm_dtl')
            ->join('tblsubject_spm', 'tblspm_dtl.subject_spm_id', 'tblsubject_spm.id')
            ->join('tblgrade_spm', 'tblspm_dtl.grade_spm_id', 'tblgrade_spm.id')
            ->where('tblspm_dtl.student_spm_ic', $std->ic)
            ->whereIn('tblsubject_spm.id', [1, 2, 5, 6]) // Filter for the specific subjects
            ->orderByRaw(DB::raw("FIELD(tblsubject_spm.id, 1, 2, 5, 6)")) // Order them in specific order
            ->select('tblsubject_spm.name AS subject', 'tblgrade_spm.name AS grade')
            ->get();

            // Fetch the rest of the records in random order
            $randomSubjects = DB::table('tblspm_dtl')
            ->join('tblsubject_spm', 'tblspm_dtl.subject_spm_id', 'tblsubject_spm.id')
            ->join('tblgrade_spm', 'tblspm_dtl.grade_spm_id', 'tblgrade_spm.id')
            ->where('tblspm_dtl.student_spm_ic', $std->ic)
            ->whereNotIn('tblsubject_spm.id', [1, 2, 5, 6]) // Exclude the first four subjects
            ->inRandomOrder() // Randomize the rest
            ->select('tblsubject_spm.name AS subject', 'tblgrade_spm.name AS grade')
            ->get();

            // Merge the two collections
            $data['spm'][$key] = $firstFour->merge($randomSubjects);


            // Check if the fetched data is less than 10
            $fetchedDataCount = count($data['spm'][$key]);
            if ($fetchedDataCount < 10) {
                // Fill the remaining elements with null
                for ($i = $fetchedDataCount; $i < 10; $i++) {
                    $data['spm'][$key][] = null;
                }
            }

            // Count the subjects excluding grades 8, 9, 10, 19, 20, 21, 22, with specific conditions first
            $firstFourCount = DB::table('tblspm_dtl')
            ->join('tblsubject_spm', 'tblspm_dtl.subject_spm_id', 'tblsubject_spm.id')
            ->join('tblgrade_spm', 'tblspm_dtl.grade_spm_id', 'tblgrade_spm.id')
            ->where('tblspm_dtl.student_spm_ic', $std->ic)
            ->whereNotIn('tblgrade_spm.id', [8, 9, 10, 19, 20, 21, 22]) // Exclude these specific grades
            ->whereIn('tblsubject_spm.id', [1, 2, 5, 6]) // Filter for specific subjects first
            ->select(DB::raw('COUNT(tblspm_dtl.id) AS count'))
            ->value('count');

            // Count the rest of the subjects (excluding both specific grades and specific subjects)
            $randomCount = DB::table('tblspm_dtl')
            ->join('tblsubject_spm', 'tblspm_dtl.subject_spm_id', 'tblsubject_spm.id')
            ->join('tblgrade_spm', 'tblspm_dtl.grade_spm_id', 'tblgrade_spm.id')
            ->where('tblspm_dtl.student_spm_ic', $std->ic)
            ->whereNotIn('tblgrade_spm.id', [8, 9, 10, 19, 20, 21, 22]) // Exclude the specific grades
            ->whereNotIn('tblsubject_spm.id', [1, 2, 5, 6]) // Exclude the specific subjects
            ->select(DB::raw('COUNT(tblspm_dtl.id) AS count'))
            ->value('count');

            // Sum the two counts to get the final result
            $data['result'][$key] = $firstFourCount + $randomCount;


            $data['spmv'][$key] = DB::table('tblstudent_spmv')
                                  ->where('student_ic', $std->ic)
                                  ->first();

            $data['skm'][$key] = DB::table('tblstudent_skm')
                                 ->where('student_ic', $std->ic)
                                 ->first();
                                 
        }

        return view('alluser.student.spm.indexGetSPM', compact('data'));

    }

    public function studentMassage()
    {

        return view('alluser.message.student_list.index');

    }

    public function getStudentMassage(Request $request)
    {

        $students = DB::table('students')
            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
            ->join('sessions AS a', 'students.intake', 'a.SessionID')
            ->join('sessions AS b', 'students.session', 'b.SessionID')
            ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
            ->join('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
            ->join('tblsex', 'tblstudent_personal.sex_id', 'tblsex.id')
            ->select('students.*', 'tblprogramme.progname', 'a.SessionName AS intake', 
                     'b.SessionName AS session', 'tblstudent_status.name AS status',
                     'tblstudent_personal.no_tel', 'tblsex.sex_name AS gender')
            ->where('students.name', 'LIKE', "%".$request->search."%")
            ->orwhere('students.ic', 'LIKE', "%".$request->search."%")
            ->orwhere('students.no_matric', 'LIKE', "%".$request->search."%")->get();

        foreach($students as $key => $std)
        {

            $sponsor_id[$key] = DB::table('tblpayment')
                                ->where([
                                    ['student_ic', $std->ic],
                                    ['sponsor_id', '!=', null]
                                    ])
                                ->latest('id')->first();

            if($sponsor_id[$key] != null)
            {

                $sponsor[$key] = DB::table('tblpayment')
                                ->join('tblsponsor_library', 'tblpayment.payment_sponsor_id', 'tblsponsor_library.id')
                                ->where('tblpayment.id', $sponsor_id[$key]->sponsor_id)->pluck('tblsponsor_library.name')->first();

            }else{

                $sponsor[$key] = 'SENDIRI';

            }

            if($std->student_status == 1)
            {

                $student_status[$key] = 'Holding';

            }elseif($std->student_status == 2)
            {

                $student_status[$key] = 'Kuliah';

            }elseif($std->student_status == 4)
            {

                $student_status[$key] = 'Latihan Industri';

            }

        }

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No. IC
                            </th>
                            <th>
                                No. Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Intake
                            </th>
                            <th>
                                Current Session
                            </th>
                            <th>
                                Semester
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($students as $key => $student){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td>
                '. $student->name .'
                </td>
                <td>
                '. $student->ic .'
                </td>
                <td>
                '. $student->no_matric .'
                </td>
                <td>
                '. $student->progname .'
                </td>
                <td>
                '. $student->intake .'
                </td>
                <td>
                '. $student->session .'
                </td>
                <td>
                '. $student->semester .'
                </td>';
                

            
                $content .= '<td class="project-actions text-right" >
                                <a class="btn btn-secondary btn-sm btn-sm mr-2 mb-2" href="#" onclick="getMessage(\''. $student->ic .'\')">
                                    <i class="ti-eye">
                                    </i>
                                    Massage
                                </a>
                            </td>
                        
                        ';
           
            }
            $content .= '</tr></tbody>';

            return $content;

    }

    public function getStudentNewMassage(Request $request)
    {

        $students = DB::table('students')
            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
            ->join('sessions AS a', 'students.intake', 'a.SessionID')
            ->join('sessions AS b', 'students.session', 'b.SessionID')
            ->join('tblmessage_dtl', 'students.ic', 'tblmessage_dtl.sender')
            ->join('tblmessage', 'tblmessage_dtl.message_id', 'tblmessage.id')
            ->select('students.*', 'tblprogramme.progname', 'a.SessionName AS intake', 
                     'b.SessionName AS session')
            ->where('tblmessage_dtl.status', 'NEW')
            ->where('tblmessage.user_type', Auth::user()->usrtype)
            ->distinct('students.ic')
            ->get();

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No. IC
                            </th>
                            <th>
                                No. Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Intake
                            </th>
                            <th>
                                Current Session
                            </th>
                            <th>
                                Semester
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                    
        foreach($students as $key => $student){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td style="width: 1%">
                '. $key+1 .'
                </td>
                <td>
                '. $student->name .'
                </td>
                <td>
                '. $student->ic .'
                </td>
                <td>
                '. $student->no_matric .'
                </td>
                <td>
                '. $student->progname .'
                </td>
                <td>
                '. $student->intake .'
                </td>
                <td>
                '. $student->session .'
                </td>
                <td>
                '. $student->semester .'
                </td>';
                

            
                $content .= '<td class="project-actions text-right" >
                                <a class="btn btn-secondary btn-sm btn-sm mr-2 mb-2" href="#" onclick="getMessage(\''. $student->ic .'\')">
                                    <i class="ti-eye">
                                    </i>
                                    Massage
                                </a>
                            </td>
                        
                        ';
           
            }
            $content .= '</tr></tbody>';

            return $content;

    }

    public function sendMassage(Request $request)
    {

        if($request->type != 'STUDENT')
        {

            if(!DB::table('tblmessage')->where([
                ['user_type', Auth::user()->usrtype], 
                ['recipient', $request->ic]
            ])->exists())
            {

                $id = DB::table('tblmessage')->insertGetId([
                        'sender' => Auth::user()->ic,
                        'user_type' => Auth::user()->usrtype,
                        'recipient' => $request->ic
                    ]);

            }else{

                $id = DB::table('tblmessage')->where([
                    ['user_type', Auth::user()->usrtype], 
                    ['recipient', $request->ic]
                ])->value('id');

            }

            DB::table('tblmessage_dtl')->insert([
                'message_id' => $id,
                'sender' => Auth::user()->ic,
                'user_type' => $request->type,
                'message' => $request->message,
                'status' => 'NEW'
            ]);

        }else{


            if(!DB::table('tblmessage')->where([
                ['user_type', $request->ic], 
                ['recipient', Auth::guard('student')->user()->ic]
            ])->exists())
            {
    
                $id = DB::table('tblmessage')->insertGetId([
                        'sender' => null,
                        'user_type' => $request->ic,
                        'recipient' => Auth::guard('student')->user()->ic
                    ]);
    
            }else{
    
                $id = DB::table('tblmessage')->where([
                    ['user_type', $request->ic], 
                    ['recipient', Auth::guard('student')->user()->ic]
                ])->value('id');
    
            }

            DB::table('tblmessage_dtl')->insert([
                'message_id' => $id,
                'sender' => Auth::guard('student')->user()->ic,
                'user_type' => $request->type,
                'message' => $request->message,
                'status' => 'NEW'
            ]);

        }

        


        return response()->json(['message' => $request->message]);

    }

    public function getMassage(Request $request)
    {

        if($request->type != 'STUDENT')
        {

            DB::table('tblmessage')
                ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
                ->where('tblmessage.user_type', $request->type)
                ->where('tblmessage.recipient', $request->ic)
                ->where('tblmessage_dtl.sender', '!=', Auth::user()->ic)
                ->update([
                    'status' => 'READ'
                ]);

            $messages = DB::table('tblmessage')
            ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
            ->where('tblmessage.user_type', $request->type)
            ->where('tblmessage.recipient', $request->ic)
            // If you want to include messages where the user is the recipient, uncomment the line below:
            //->orWhere('tblmessage.recipient', Auth::user()->ic)
            ->select('tblmessage_dtl.*', 'tblmessage_dtl.user_type', 'tblmessage.recipient', 'tblmessage.datetime as message_datetime')
            ->get();

        }else{

            DB::table('tblmessage')
                ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
                ->where('tblmessage.user_type', $request->ic)
                ->where('tblmessage.recipient', Auth::guard('student')->user()->ic)
                ->where('tblmessage_dtl.sender', '!=', Auth::guard('student')->user()->ic)
                ->update([
                    'status' => 'READ'
                ]);

            // Fetch messages and their details
            $messages = DB::table('tblmessage')
                ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
                ->where('tblmessage.user_type', $request->ic)
                ->where('tblmessage.recipient', Auth::guard('student')->user()->ic)
                // If you want to include messages where the user is the recipient, uncomment the line below:
                //->orWhere('tblmessage.recipient', Auth::user()->ic)
                ->select('tblmessage_dtl.*', 'tblmessage_dtl.user_type', 'tblmessage.recipient', 'tblmessage.datetime as message_datetime')
                ->get();

        }

        return response()->json($messages);
    }

    public function countMessage(Request $request)
    {

        $count = DB::table('tblmessage')
                    ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
                    ->where('tblmessage.user_type', $request->type)
                    ->where('tblmessage.recipient', Auth::guard('student')->user()->ic)
                    ->where('tblmessage_dtl.sender', '!=', Auth::guard('student')->user()->ic)
                    ->where('tblmessage_dtl.status', 'NEW')
                    ->count();

        return response()->json(['count' => $count]);

    }

    public function countMassageAdmin(Request $request)
    {

        $count = DB::table('tblmessage')
        ->where('user_type', Auth::user()->usrtype)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('tblmessage_dtl')
                ->whereColumn('tblmessage_dtl.message_id', 'tblmessage.id')
                ->where('tblmessage_dtl.status', 'NEW')
                ->where('tblmessage_dtl.user_type', '!=', Auth::user()->usrtype);
        })
        ->count();


        return response()->json(['count' => $count]);

    }


}
