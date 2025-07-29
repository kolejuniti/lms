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
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

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
        // Handle image upload if provided
        $imagePath = null;
        
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Check if posting directory exists, if not create it
            if (!Storage::disk('linode')->exists('posting')) {
                Storage::disk('linode')->makeDirectory('posting', 'public');
            }
            
            // Get the file and generate a unique name
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Store the file with public visibility
            $path = $file->storeAs('posting', $fileName, [
                'disk' => 'linode',
                'visibility' => 'public'
            ]);
            $imagePath = $path;
        }

        if(isset($request->idS))
        {
            // Get current post to check if it has an image
            $currentPost = DB::table('tblposting')->where('id', $request->idS)->first();
            
            // If there's a new image and the post already has an image, delete the old one
            if ($imagePath && $currentPost->image) {
                Storage::disk('linode')->delete($currentPost->image);
            }
            
            // If no new image was uploaded, keep the existing image path
            if (!$imagePath && $currentPost->image) {
                $imagePath = $currentPost->image;
            }

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
                'update_save' => date('Y-m-d'),
                'image' => $imagePath
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
                'total_save' => $request->save,
                'image' => $imagePath
            ]);

            $alert =  'Post successfully added!';

        }

        return back()->with('success', $alert);
    }

    public function postingDelete(Request $request)
    {
        // Get the post details to check if it has an image
        $post = DB::table('tblposting')->where('id', $request->id)->first();
        
        // If the post has an image, delete it from storage
        if ($post && $post->image) {
            Storage::disk('linode')->delete($post->image);
        }

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

        return view('alluser.post.adminGetStaffTbody', compact('data'));

    }

    public function postingReport()
    {

        return view('alluser.post.report');

    }

    public function getPostingReport(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $data['staff'] = DB::table('users')->get();

        foreach($data['staff'] as $key => $staff)
        {
            $data['post'][$key] = DB::table('tblposting')
            ->whereBetween('post_date', [$from, $to])
            ->where('staff_ic', $staff->ic)->get();

            // Initialize counters before the loop
            $facebookCount = 0;
            $twitterCount = 0;
            $instagramCount = 0;
            $youtubeCount = 0;
            $tiktokCount = 0;
            $whatsappCount = 0;
            $totalCount = 0;
            
            foreach($data['post'][$key] as $key2 => $post)
            {
                if ($post->channel == 'facebook') {
                    $facebookCount++;
                }
                if ($post->channel == 'twitter') {
                    $twitterCount++;
                }
                if ($post->channel == 'instagram') {
                    $instagramCount++;
                }
                if ($post->channel == 'youtube') {
                    $youtubeCount++;
                }
                if ($post->channel == 'tiktok') {
                    $tiktokCount++;
                }
                if ($post->channel == 'whatsapp') {
                    $whatsappCount++;
                }
                $totalCount++;
            }

            // Store the counts in $data for this $key
            $data['facebook'][$key] = $facebookCount;
            $data['twitter'][$key] = $twitterCount;
            $data['instagram'][$key] = $instagramCount;
            $data['youtube'][$key] = $youtubeCount;
            $data['tiktok'][$key] = $tiktokCount;
            $data['whatsapp'][$key] = $whatsappCount;
            $data['total'][$key] = $totalCount;
        }

        return response()->json($data);
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
                    ->select('students.*', 'sessions.SessionName', 'tblstudent_status.name AS status');

        if($request->program != '')
        {
            $query->where('students.program', $request->program);

        }

        if($request->year != '')
        {
            $query->where('b.Year', $request->year);

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


            // Check if the fetched data is less than 11
            $fetchedDataCount = count($data['spm'][$key]);
            if ($fetchedDataCount < 11) {
                // Fill the remaining elements with null
                for ($i = $fetchedDataCount; $i < 11; $i++) {
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

            $data['total_grade'][$key] = DB::table('tblspm_dtl')
            ->join('tblgrade_spm', 'tblspm_dtl.grade_spm_id', 'tblgrade_spm.id')
            ->join('tblsubject_spm', 'tblspm_dtl.subject_spm_id', 'tblsubject_spm.id')
            ->where('tblspm_dtl.student_spm_ic', $std->ic)
            ->whereNotNull('tblgrade_spm.grade_value')
            ->select(DB::raw('SUM(tblgrade_spm.grade_value) AS total_grade_value'))
            ->value('total_grade_value') ?? 0;

            $data['total_grade_overall'][$key] = DB::table('tblspm_dtl')
            ->join('tblgrade_spm', 'tblspm_dtl.grade_spm_id', 'tblgrade_spm.id')
            ->join('tblsubject_spm', 'tblspm_dtl.subject_spm_id', 'tblsubject_spm.id')
            ->where('tblspm_dtl.student_spm_ic', $std->ic)
            ->whereNotNull('tblgrade_spm.grade_value')
            ->orderBy('tblgrade_spm.grade_value', 'asc')
            ->limit(3)
            ->pluck('tblgrade_spm.grade_value')
            ->sum();


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

    public function getStudentOldMassage(Request $request)
    {

        $students = DB::table('students')
            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
            ->join('sessions AS a', 'students.intake', 'a.SessionID')
            ->join('sessions AS b', 'students.session', 'b.SessionID')
            ->join('tblmessage_dtl', 'students.ic', 'tblmessage_dtl.sender')
            ->join('tblmessage', 'tblmessage_dtl.message_id', 'tblmessage.id')
            ->select('students.*', 'tblprogramme.progname', 'a.SessionName AS intake', 
                     'b.SessionName AS session')
            ->where('tblmessage_dtl.status', 'READ')
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
        // Handle image upload if present
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $this->uploadMessageImage($request->file('image'), $request->ic, $request->type);
        }

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
                'message' => $request->message ?? '',
                'image_url' => $imageUrl,
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
                'message' => $request->message ?? '',
                'image_url' => $imageUrl,
                'status' => 'NEW'
            ]);

        }

        return response()->json([
            'message' => $request->message ?? '',
            'image_url' => $imageUrl
        ]);
    }

    private function uploadMessageImage($image, $ic, $type)
    {
        try {
            // Validate image
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!in_array($image->getMimeType(), $allowedTypes)) {
                throw new \Exception('Invalid image type. Only JPEG, PNG, JPG, GIF, and WebP are allowed.');
            }

            // Check file size (max 5MB)
            if ($image->getSize() > 5 * 1024 * 1024) {
                throw new \Exception('Image size must be less than 5MB.');
            }

            // Generate unique filename
            $extension = $image->getClientOriginalExtension();
            $filename = 'msg_' . $ic . '_' . $type . '_' . time() . '_' . uniqid() . '.' . $extension;
            
            // Create directory path
            $directory = 'messages/' . date('Y') . '/' . date('m');
            
            // Check if directory exists, if not create it
            if (!Storage::disk('linode')->exists($directory)) {
                Storage::disk('linode')->makeDirectory($directory, 'public');
            }
            
            // Upload to Linode storage using the same pattern as existing code
            $path = $image->storeAs($directory, $filename, [
                'disk' => 'linode',
                'visibility' => 'public'
            ]);

            // Return the full URL using environment variables like other controllers
            return env('LINODE_ENDPOINT') . '/' . env('LINODE_BUCKET') . '/' . $path;

        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
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
                ->where('tblmessage_dtl.status', 'NEW') // Only update NEW messages
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
                ->where('tblmessage_dtl.status', 'NEW') // Only update NEW messages
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

    public function deleteMassage(Request $request)
    {
        try {
            // Validate the request
            if (!$request->message_id || !$request->ic || !$request->type) {
                return response()->json(['error' => 'Missing required parameters'], 400);
            }

            // Update the message status to 'DELETED' instead of actually deleting it
            $updated = DB::table('tblmessage_dtl')
                ->where('id', $request->message_id)
                ->update(['status' => 'DELETED']);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Message deleted successfully'
                ]);
            } else {
                return response()->json(['error' => 'Message not found or already deleted'], 404);
            }

        } catch (\Exception $e) {
            Log::error('Delete message failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete message'], 500);
        }
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


    // Display a listing of the announcements
    public function indexAnnouncements()
    {
        $userRole = Auth::user()->usrtype;

        if ($userRole === 'ADM') {
            $type = 'Admin';
        } else if ($userRole === 'FN') {
            $type = 'Finance';
        } else if ($userRole === 'AR') {
            $type = 'Pendaftar Akademik';
        } else if ($userRole === 'RGS') {
            $type = 'Pendaftar';
        }

        $announcements = DB::table('tblstdannoucement')->where('department', $type)->get();

        // Debugging: Check the data being fetched
        error_log($announcements);

        return response()->json($announcements);
    }


    // Store a newly created announcement
    public function storeAnnouncements(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'department' => 'required|string|max:100',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        $id = DB::table('tblstdannoucement')->insertGetId($validated);

        return response()->json(['message' => 'Announcement created successfully', 'id' => $id]);
    }

    // Display the specified announcement
    public function showAnnouncements($id)
    {
        $announcement = DB::table('tblstdannoucement')->where('id', $id)->first();

        if (!$announcement) {
            return response()->json(['message' => 'Announcement not found'], 404);
        }

        return response()->json($announcement);
    }

    // Update the specified announcement
    public function updateAnnouncements(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'department' => 'sometimes|required|string|max:100',
            'priority' => 'sometimes|required|string|in:low,medium,high',
        ]);

        $updated = DB::table('tblstdannoucement')->where('id', $id)->update($validated);

        if ($updated) {
            return response()->json(['message' => 'Announcement updated successfully']);
        }

        return response()->json(['message' => 'No changes made or announcement not found'], 404);
    }

    // Remove the specified announcement
    public function destroyAnnouncements($id)
    {
        $deleted = DB::table('tblstdannoucement')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Announcement deleted successfully']);
        }

        return response()->json(['message' => 'Announcement not found'], 404);
    }

    public function getBannerAnnouncement()
    {
        $announcements = DB::table('tblstdannoucement')
        ->whereDate('start_date', '<=', now()) // Fetch rows where start_date is before or equal to today
        ->whereDate('end_date', '>=', now())   // Fetch rows where end_date is after or equal to today
        ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')") // Sort by priority
        ->orderBy('created_at', 'desc') // Optional: Further sort by creation date
        ->get();


        return response()->json($announcements);
    }

    public function fixImagesVisibility()
    {
        $posts = DB::table('tblposting')->whereNotNull('image')->get();
        
        foreach($posts as $post) {
            if (Storage::disk('linode')->exists($post->image)) {
                Storage::disk('linode')->setVisibility($post->image, 'public');
            }
        }
        
        return back()->with('success', 'Images visibility updated successfully!');
    }

    // PDF Export functionality
    public function pdfExportIndex()
    {
        return view('alluser.pdf_export.index');
    }

    public function pdfExportUpload(Request $request)
    {
        try {
            // Validate the uploaded files
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
                'word_template' => 'required|file|mimes:docx,doc|max:10240', // 10MB max
            ]);

            // Store the uploaded files temporarily
            $excelPath = $request->file('excel_file')->store('temp/excel', 'local');
            $templatePath = $request->file('word_template')->store('temp/templates', 'local');

            // Read Excel data
            $data = Excel::toArray([], storage_path('app/' . $excelPath));
            
            if (empty($data) || empty($data[0])) {
                return back()->with('error', 'Excel file is empty or invalid.');
            }

            $excelData = $data[0];
            $headers = array_shift($excelData); // Get headers from first row

            // Process each row and generate PDFs
            $generatedFiles = [];
            
            foreach ($excelData as $index => $row) {
                if (empty(array_filter($row))) continue; // Skip empty rows
                
                // Create associative array with headers as keys
                $rowData = array_combine($headers, $row);
                
                // Generate PDF for this row
                $pdfFileName = $this->generatePdfFromTemplate(
                    storage_path('app/' . $templatePath), 
                    $rowData, 
                    $index + 1
                );
                
                if ($pdfFileName) {
                    $generatedFiles[] = $pdfFileName;
                }
            }

            // Clean up temporary files
            Storage::disk('local')->delete($excelPath);
            Storage::disk('local')->delete($templatePath);

            if (empty($generatedFiles)) {
                return back()->with('error', 'No PDFs were generated. Please check your data.');
            }

            // If only one file, download directly
            if (count($generatedFiles) == 1) {
                return redirect()->route('all.pdf.export.download', ['filename' => $generatedFiles[0]]);
            }

            // If multiple files, create a zip
            $zipFileName = $this->createZipFile($generatedFiles);
            
            return back()->with('success', 'PDFs generated successfully!')
                        ->with('download_link', route('all.pdf.export.download', ['filename' => $zipFileName]));

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return back()->with('error', 'Error generating PDFs: ' . $e->getMessage());
        }
    }

    private function generatePdfFromTemplate($templatePath, $data, $index)
    {
        try {
            // Create a copy of the template
            $templateProcessor = new TemplateProcessor($templatePath);
            
            // Replace placeholders with data
            foreach ($data as $key => $value) {
                $templateProcessor->setValue($key, $value ?? '');
            }

            // Save the processed document
            $processedDocPath = storage_path('app/temp/processed_' . $index . '.docx');
            $templateProcessor->saveAs($processedDocPath);

            // Convert Word to HTML first, then to PDF
            $htmlContent = $this->convertWordToHtml($processedDocPath, $data);
            
            // Generate PDF using DomPDF
            $pdf = PDF::loadHTML($htmlContent);
            $pdfFileName = 'generated_' . $index . '_' . time() . '.pdf';
            $pdfPath = storage_path('app/temp/pdfs/' . $pdfFileName);
            
            // Ensure directory exists
            if (!file_exists(dirname($pdfPath))) {
                mkdir(dirname($pdfPath), 0755, true);
            }
            
            $pdf->save($pdfPath);

            // Clean up processed document
            if (file_exists($processedDocPath)) {
                unlink($processedDocPath);
            }

            return $pdfFileName;

        } catch (\Exception $e) {
            Log::error('Template processing error: ' . $e->getMessage());
            return null;
        }
    }

    private function convertWordToHtml($docPath, $data)
    {
        // Simple conversion - replace placeholders in a basic HTML template
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Generated Document</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; }
                .content { line-height: 1.6; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>Generated Document</h2>
            </div>
            <div class="content">';

        foreach ($data as $key => $value) {
            $html .= '<div class="field"><span class="label">' . ucfirst(str_replace('_', ' ', $key)) . ':</span> ' . htmlspecialchars($value ?? '') . '</div>';
        }

        $html .= '
            </div>
        </body>
        </html>';

        return $html;
    }

    private function createZipFile($pdfFiles)
    {
        $zipFileName = 'generated_pdfs_' . time() . '.zip';
        $zipPath = storage_path('app/temp/pdfs/' . $zipFileName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($pdfFiles as $pdfFile) {
                $pdfPath = storage_path('app/temp/pdfs/' . $pdfFile);
                if (file_exists($pdfPath)) {
                    $zip->addFile($pdfPath, $pdfFile);
                }
            }
            $zip->close();
        }

        return $zipFileName;
    }

    public function pdfExportDownload($filename)
    {
        $filePath = storage_path('app/temp/pdfs/' . $filename);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function downloadTemplate()
    {
        $templatePath = public_path('template/template_example.txt');
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template example file not found.');
        }

        return response()->download($templatePath, 'word_template_example.txt');
    }

    public function downloadSample()
    {
        $samplePath = public_path('template/sample_data.csv');
        
        if (!file_exists($samplePath)) {
            return back()->with('error', 'Sample file not found.');
        }

        return response()->download($samplePath, 'sample_data.csv');
    }

    // Student-to-student messaging methods
    public function searchStudents(Request $request)
    {
        $currentStudentIc = Auth::guard('student')->user()->ic;
        
        $students = DB::table('students')
            ->join('tblprogramme', 'students.program', 'tblprogramme.id')
            ->join('sessions AS a', 'students.intake', 'a.SessionID')
            ->join('sessions AS b', 'students.session', 'b.SessionID')
            ->join('tblstudent_status', 'students.status', 'tblstudent_status.id')
            ->select('students.ic', 'students.name', 'students.no_matric', 'tblprogramme.progname', 
                     'a.SessionName AS intake', 'b.SessionName AS session', 'students.semester')
            ->where('students.ic', '!=', $currentStudentIc) // Exclude current student
            ->where(function($query) use ($request) {
                $query->where('students.name', 'LIKE', "%".$request->search."%")
                      ->orWhere('students.ic', 'LIKE', "%".$request->search."%")
                      ->orWhere('students.no_matric', 'LIKE', "%".$request->search."%");
            })
            ->limit(10) // Limit results for performance
            ->get();

        return response()->json($students);
    }

    public function sendStudentMessage(Request $request)
    {
        // Handle image upload if present
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $this->uploadMessageImage($request->file('image'), $request->recipient_ic, 'STUDENT_TO_STUDENT');
        }

        $currentStudentIc = Auth::guard('student')->user()->ic;
        $recipientIc = $request->recipient_ic;

        // Create a unique conversation ID by sorting the ICs
        $conversationId = ($currentStudentIc < $recipientIc) 
            ? $currentStudentIc . '_' . $recipientIc 
            : $recipientIc . '_' . $currentStudentIc;

        // Check if conversation exists
        if (!DB::table('tblmessage')->where([
            ['user_type', 'STUDENT_CONVERSATION'],
            ['recipient', $conversationId]
        ])->exists()) {
            $id = DB::table('tblmessage')->insertGetId([
                'sender' => null,
                'user_type' => 'STUDENT_CONVERSATION',
                'recipient' => $conversationId
            ]);
        } else {
            $id = DB::table('tblmessage')->where([
                ['user_type', 'STUDENT_CONVERSATION'],
                ['recipient', $conversationId]
            ])->value('id');
        }

        DB::table('tblmessage_dtl')->insert([
            'message_id' => $id,
            'sender' => $currentStudentIc,
            'user_type' => 'STUDENT_TO_STUDENT',
            'message' => $request->message ?? '',
            'image_url' => $imageUrl,
            'status' => 'NEW'
        ]);

        return response()->json([
            'message' => $request->message ?? '',
            'image_url' => $imageUrl,
            'conversation_id' => $conversationId
        ]);
    }

    public function getStudentMessages(Request $request)
    {
        $currentStudentIc = Auth::guard('student')->user()->ic;
        $recipientIc = $request->recipient_ic;

        // Create conversation ID
        $conversationId = ($currentStudentIc < $recipientIc) 
            ? $currentStudentIc . '_' . $recipientIc 
            : $recipientIc . '_' . $currentStudentIc;

        // Mark messages as read (only messages from the other student)
        DB::table('tblmessage')
            ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
            ->where('tblmessage.user_type', 'STUDENT_CONVERSATION')
            ->where('tblmessage.recipient', $conversationId)
            ->where('tblmessage_dtl.sender', '!=', $currentStudentIc)
            ->where('tblmessage_dtl.status', 'NEW')
            ->update(['tblmessage_dtl.status' => 'READ']);

        // Fetch messages
        $messages = DB::table('tblmessage')
            ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
            ->join('students', 'tblmessage_dtl.sender', '=', 'students.ic')
            ->where('tblmessage.user_type', 'STUDENT_CONVERSATION')
            ->where('tblmessage.recipient', $conversationId)
            ->where('tblmessage_dtl.status', '!=', 'DELETED')
            ->select('tblmessage_dtl.*', 'students.name as sender_name', 'tblmessage.datetime as message_datetime')
            ->orderBy('tblmessage_dtl.id', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function countStudentMessages(Request $request)
    {
        $currentStudentIc = Auth::guard('student')->user()->ic;
        $recipientIc = $request->recipient_ic;

        // Create conversation ID
        $conversationId = ($currentStudentIc < $recipientIc) 
            ? $currentStudentIc . '_' . $recipientIc 
            : $recipientIc . '_' . $currentStudentIc;

        $count = DB::table('tblmessage')
                    ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
                    ->where('tblmessage.user_type', 'STUDENT_CONVERSATION')
                    ->where('tblmessage.recipient', $conversationId)
                    ->where('tblmessage_dtl.sender', '!=', $currentStudentIc)
                    ->where('tblmessage_dtl.status', 'NEW')
                    ->count();

        return response()->json(['count' => $count]);
    }

    public function getStudentConversations()
    {
        $currentStudentIc = Auth::guard('student')->user()->ic;

        // Get all conversations where current student is involved
        $conversations = DB::table('tblmessage')
            ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
            ->where('tblmessage.user_type', 'STUDENT_CONVERSATION')
            ->where('tblmessage.recipient', 'LIKE', '%' . $currentStudentIc . '%')
            ->select('tblmessage.recipient as conversation_id', DB::raw('MAX(tblmessage_dtl.id) as last_message_id'))
            ->groupBy('tblmessage.recipient')
            ->get();

        $conversationList = [];

        foreach ($conversations as $conversation) {
            // Extract the other student's IC from conversation ID
            $parts = explode('_', $conversation->conversation_id);
            $otherStudentIc = ($parts[0] == $currentStudentIc) ? $parts[1] : $parts[0];

            // Get other student details
            $otherStudent = DB::table('students')
                ->where('ic', $otherStudentIc)
                ->select('ic', 'name', 'no_matric')
                ->first();

            if ($otherStudent) {
                // Get last message
                $lastMessage = DB::table('tblmessage_dtl')
                    ->where('id', $conversation->last_message_id)
                    ->first();

                // Count unread messages
                $unreadCount = DB::table('tblmessage')
                    ->join('tblmessage_dtl', 'tblmessage.id', '=', 'tblmessage_dtl.message_id')
                    ->where('tblmessage.user_type', 'STUDENT_CONVERSATION')
                    ->where('tblmessage.recipient', $conversation->conversation_id)
                    ->where('tblmessage_dtl.sender', '!=', $currentStudentIc)
                    ->where('tblmessage_dtl.status', 'NEW')
                    ->count();

                $conversationList[] = [
                    'student' => $otherStudent,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                    'conversation_id' => $conversation->conversation_id
                ];
            }
        }

        // Sort by last message time
        usort($conversationList, function($a, $b) {
            return strtotime($b['last_message']->datetime ?? '1970-01-01') - strtotime($a['last_message']->datetime ?? '1970-01-01');
        });

        return response()->json($conversationList);
    }

}
