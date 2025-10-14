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
use Smalot\PdfParser\Parser;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MyCustomNotification;
use App\Models\UserStudent;
use Illuminate\Support\Facades\Artisan;

class QuizController extends Controller
{
    
    public function quizlist()
    {
        Session::put('CourseIDS', request()->id);

        if(Session::get('SessionIDS') == null)
        {
        Session::put('SessionIDS', request()->session);
        }

        $user = Auth::user();
        $group = array();

        $chapter = array();

        //dd(Session::get('CourseIDS'));

        $data = DB::table('tblclassquiz')->join('tblclassquizstatus', 'tblclassquiz.status', 'tblclassquizstatus.id')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.addby', $user->ic],
                    ['tblclassquiz.status', '!=', 3],
                    ['tblclassquiz.date_from','!=', null]
                ])->select('tblclassquiz.*', 'tblclassquizstatus.statusname')->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassquiz_group')
                        ->join('user_subjek', 'tblclassquiz_group.groupid', 'user_subjek.id')
                        ->where('tblclassquiz_group.quizid', $dt->id)->get();

                $chapter[] = DB::table('tblclassquiz_chapter')
                        ->join('material_dir', 'tblclassquiz_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassquiz_chapter.quizid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.quiz', compact('data', 'group', 'chapter'));
    }

    public function getExtendQuiz(Request $request)
    {

        $data['quiz'] = DB::table('tblclassquiz')->where('id', $request->id)->first();

        return view('lecturer.courseassessment.quizGetExtend', compact('data'));

    }

    public function updateExtendQuiz(Request $request)
    {

        DB::table('tblclassquiz')->where('id', $request->id)->update([
            'date_from' => $request->from,
            'date_to' => $request->to,
            'duration' => $request->duration
        ]);

        return back()->with('message', 'Success!');

    }

    public function deletequiz(Request $request)
    {

        try {

            $quiz = DB::table('tblclassquiz')->where('id', $request->id)->first();

            if($quiz->status != 3)
            {
            DB::table('tblclassquiz')->where('id', $request->id)->update([
                'status' => 3
            ]);

            return true;

            }else{

                die;

            }
          
          } catch (\Exception $e) {
          
              return $e->getMessage();
          }
    }

    public function quizcreate()
    {
        $user = Auth::user();

        $data['quizid'] = null;

        $data['reuse'] = null;

        $courseid = Session::get('CourseID');

        $sessionid = Session::get('SessionID');

        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->where([
            ['subjek.id', $courseid],
            ['user_subjek.session_id', $sessionid],
            ['user_subjek.user_ic', $user->ic]
        ])->groupBy('student_subjek.group_name')
        ->select('user_subjek.*', 'subjek.course_name', 'student_subjek.group_name')->get();

        //dd(Session::get('CourseIDS'));

        $subid = DB::table('subjek')->where('id', $courseid)->pluck('sub_id');

        $folder = DB::table('lecturer_dir')
                  ->join('subjek', 'lecturer_dir.CourseID','subjek.id')
                  ->where('subjek.sub_id', $subid)
                  ->where('Addby', $user->ic)->get();

        //dd($folder);

        if(isset(request()->quizid))
        {

            $quizid = request()->quizid;
 
            $data['quizid'] = $quizid;

            $data['quiz'] = DB::table('tblclassquiz')->select('tblclassquiz.*')
            ->where([
                ['id', $quizid]
            ])->get()->first();

            //dd($data['quiz']);

            $data['quizstatus'] = $data['quiz']->status;

            if(isset(request()->REUSE))
            {
                $data['reuse'] = request()->REUSE;
            }

        }

        //dd($data);

        return view('lecturer.courseassessment.quizcreate', compact(['group', 'folder', 'data']));
    }

    public function getChapters(Request $request)
    {

        $subchapter = DB::table('material_dir')->where('LecturerDirID', $request->folder)->get();

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>Sub Chapter</th>
            <th>Name</th>
            <th></th>
            </thead>
            <tbody>
        ';
        foreach($subchapter as $sub){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <label>'.$sub->ChapterNo.'</label>
                </td>
                <td >
                    <label>'. (($sub->newDrName != null) ? $sub->newDrName : $sub->DrName) .'</label>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="chapter_checkbox_'.$sub->DrID.'"
                            class="filled-in" name="chapter[]" value="'.$sub->DrID.'" 
                        >
                        <label for="chapter_checkbox_'.$sub->DrID.'"> </label>
                    </div>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';

            return $content;

    }

    public function getStatus(Request $request)
    {

        $user = Auth::user();
            
        //dd($group);

        $quiz = DB::table('student_subjek')
                ->join('tblclassquiz_group', 'student_subjek.group_id', 'tblclassquiz_group.groupid')
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassquiz.id AS clssid', 'tblclassquiz.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', $request->quiz],
                    ['tblclassquiz.addby', $user->ic],
                    ['tblclassquiz_group.groupid', $request->group]
                ])->get();
        
        //dd($quiz);

        foreach($quiz as $qz)
        {
            $statu[] = DB::table('tblclassstudentquiz')
            ->where([
                ['quizid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($quiz);

        return view('lecturer.courseassessment.getstatusquiz', compact('quiz', 'status'));

    }


    public function insertquiz(Request $request){
        $data = $request->data;
        $classid = Session::get('CourseID');
        $sessionid = Session::get('SessionID');
        $duration = $request->duration;
        $title = $request->title;
        $from = $request->from;
        $to = $request->to;
        $questionindex = $request->questionindex;
        $status = $request->status;
        $marks = $request->marks;

        $user = Auth::user();
            
        $quizid = empty($request->quiz) ? '' : $request->quiz;

        $statusReuse = empty($request->reuse) ? '' : $request->reuse;

        $groupJSON = $request->input('group');
        $chapterJSON = $request->input('chapter');

        // Decode the JSON strings into PHP arrays
        $group = json_decode($groupJSON, true);
        $chapter = json_decode($chapterJSON, true);

        if( !empty($statusReuse))
        {
            $q = DB::table('tblclassquiz')->insertGetId([
                "classid" => $classid,
                "sessionid" => $sessionid,
                "title" => $title,
                "date_from" => $from,
                "date_to" => $to,
                "content" => $data,
                "duration" => $duration,
                "questionindex" => $questionindex,
                "total_mark" => $marks,
                "addby" => $user->ic,
                "status" => $status
            ]);

            foreach($group as $grp)
            {
                $gp = explode('|', $grp);
                
                DB::table('tblclassquiz_group')->insert([
                    "groupid" => $gp[0],
                    "groupname" => $gp[1],
                    "quizid" => $q
                ]);
            }

            foreach($chapter as $chp)
            {
                DB::table('tblclassquiz_chapter')->insert([
                    "chapterid" => $chp,
                    "quizid" => $q
                ]);
            }
            
        }else{
            if( !empty($quizid) ){
                $q = DB::table('tblclassquiz')->where('id', $quizid)->update([
                    "title" => $title,
                    "date_from" => $from,
                    "date_to" => $to,
                    "content" => $data,
                    "duration" => $duration,
                    "questionindex" => $questionindex,
                    "total_mark" => $marks,
                    "addby" => $user->ic,
                    "status" => $status
                ]);

                DB::table('tblclassquiz_group')->where('quizid',$quizid)->delete();

                foreach($group as $grp)
                {
                    $gp = explode('|', $grp);
                    
                    DB::table('tblclassquiz_group')->insert([
                        "groupid" => $gp[0],
                        "groupname" => $gp[1],
                        "quizid" => $quizid
                    ]);
                }

                DB::table('tblclassquiz_chapter')->where('quizid',$quizid)->delete();

                foreach($chapter as $chp)
                {
                    DB::table('tblclassquiz_chapter')->insert([
                        "chapterid" => $chp,
                        "quizid" => $quizid
                    ]);
                }

            }else{
                $q = DB::table('tblclassquiz')->insertGetId([
                    "classid" => $classid,
                    "sessionid" => $sessionid,
                    "title" => $title,
                    "date_from" => $from,
                    "date_to" => $to,
                    "content" => $data,
                    "duration" => $duration,
                    "questionindex" => $questionindex,
                    "total_mark" => $marks,
                    "addby" => $user->ic,
                    "status" => $status
                ]);

                foreach($group as $grp)
                {
                    $gp = explode('|', $grp);
                    
                    DB::table('tblclassquiz_group')->insert([
                        "groupid" => $gp[0],
                        "groupname" => $gp[1],
                        "quizid" => $q
                    ]);
                }

                foreach($chapter as $chp)
                {
                    DB::table('tblclassquiz_chapter')->insert([
                        "chapterid" => $chp,
                        "quizid" => $q
                    ]);
                }
            }
        }

        // Set the directory path
        $dir = "classquiz/" . Session::get('CourseID') . "/" . "quizimage" . "/" . $q . "/";

        $newNames = [];

        // Set the directory path (STAGING)
        // $dir = "classquiz/" . Session::get('CourseID') . "/" . "quizimage" . "/";

        // Access the uploaded files
        foreach ($_FILES as $inputSubtype => $fileData) {
            // Loop through the array of files
            for ($i = 0; $i < count($fileData['name']); $i++) {
                $uploadedFile = $request->file($inputSubtype . '.' . $i);

                $file_name = $uploadedFile->getClientOriginalName();
                $file_ext = $uploadedFile->getClientOriginalExtension();
                $fileInfo = pathinfo($file_name);
                $filename = $fileInfo['filename'];
                $newname = $filename . "." . $file_ext;

                // Store the new name in the $newNames array, indexed by the "uploaded_image" key and file index
                $originalName = 'uploaded_image[' . $i . ']';
                $newNames[$originalName] = $newname;

                // Check if the file is present
                if ($uploadedFile) {
                    // Validate the file (add your own validation rules)
                    $validatedData = $request->validate([
                        $inputSubtype . '.' . $i => 'mimes:jpg,jpeg,png|max:2048', // For example: images only, max size 2MB
                    ]);

                    // Check if the directory exists in Linode Object Storage
                    if (!Storage::disk('linode')->exists($dir)) {
                        // If the directory doesn't exist, create it
                        Storage::disk('linode')->makeDirectory($dir);
                    }

                    // Store the file in Linode Object Storage with the specified path
                    $filePath = Storage::disk('linode')->putFileAs(
                        $dir,
                        $uploadedFile,
                        $newname,
                        'public'
                    );

                    // Store the file path in the database or another location as per your requirements
                    // For example, you could store the paths in a separate table, or add a column to an existing table
                    // The implementation depends on your application structure

                    // Create an img tag with the uploaded image
                    $imgTag = "<img src='" . env('LINODE_ENDPOINT') . "/" . env('LINODE_BUCKET') . "/" . $dir . $newname . "' />";

                    // Replace the corresponding image input with the img tag in the quiz content
                    $data = str_replace($inputSubtype . '_' . $i, $imgTag, $data);
                }
            }
        }

        
        
        // Decode the JSON content of the quiz into a PHP array
        $quiz_content = json_decode($data, true);

        // Define the Linode Object Storage base URL
        $linode_base_url = rtrim(env('LINODE_ENDPOINT'), '/') . '/' . env('LINODE_BUCKET') . '/' . $dir; // Replace this with your Linode Object Storage base URL

        // Iterate through the "formData" array and update the image URLs
        // Iterate through the "formData" array and update the image URLs
        $fileIndex = 0; // Initialize the file index
        foreach ($quiz_content['formData'] as $index => $item) {
            if ($item['type'] === 'file' && isset($item['name'])) {
                // Construct the original name using the file index
                $originalName2 = 'uploaded_image[' . $fileIndex . ']';

                // Check if a new name exists for this file in the $newNames array
                if (isset($newNames[$originalName2])) {
                    // Prepend the Linode Object Storage base URL to the new name
                    $quiz_content['formData'][$index]['name'] = $linode_base_url . $newNames[$originalName2];
                }

                $fileIndex++; // Increment the file index
            }
        }

        // Re-encode the quiz content to JSON format
        $updated_content = json_encode($quiz_content);

        // Update the content field in the database with the updated content
        DB::table('tblclassquiz')->where('id', $q)->update([
            "content" => $updated_content
        ]);

        $allUsers = collect();

            foreach($group as $grp) {
                $gp = explode('|', $grp);

                $users = UserStudent::join('student_subjek', 'students.ic', '=', 'student_subjek.student_ic')
                    ->where([
                        ['student_subjek.group_id', $gp[0]],
                        ['student_subjek.group_name', $gp[1]]
                    ])
                    ->select('students.*')
                    ->get();

                $allUsers = $allUsers->merge($users);
            }

        $message = "A new online quiz titled " . $title . " has been created.";
        $url = url('/student/quiz/' . $classid . '?session=' . $sessionid);
        $icon = "fa-puzzle-piece fa-lg";
        $iconColor = "#8803a0"; // Example: set to a bright orange

        Notification::send($allUsers, new MyCustomNotification($message, $url, $icon, $iconColor));

        return true;

    }

    public function lecturerquizstatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassquiz_group', 'user_subjek.id', 'tblclassquiz_group.groupid')
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassquiz.id', request()->quiz]
                ])->get();
                
            
        //dd($group);

        $quiz = DB::table('student_subjek')
                ->join('tblclassquiz_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassquiz_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassquiz_group.groupname');
                })
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclassquiz.id AS clssid', 'tblclassquiz.total_mark', 'tblclassquiz.date_from', 'tblclassquiz.date_to', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', request()->quiz],
                    ['tblclassquiz.addby', $user->ic]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();
        
        //dd($quiz);

        foreach($quiz as $qz)
        {
            $status[] = DB::table('tblclassstudentquiz')
            ->where([
                ['quizid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($quiz);

        return view('lecturer.courseassessment.quizstatus', compact('quiz', 'status', 'group'));

    }

    public function quizGetGroup(Request $request)
    {

        $user = Auth::user();

        $gp = explode('|', $request->group);

        $quiz = DB::table('student_subjek')
                ->join('tblclassquiz_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassquiz_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassquiz_group.groupname');
                })
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclassquiz.id AS clssid', 'tblclassquiz.total_mark', 'tblclassquiz.date_from', 'tblclassquiz.date_to', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', request()->quiz],
                    ['tblclassquiz.addby', $user->ic],
                    ['student_subjek.group_id', $gp[0]],
                    ['student_subjek.group_name', $gp[1]]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();
        
        foreach($quiz as $qz)
        {
            $status[] = DB::table('tblclassstudentquiz')
            ->where([
                ['quizid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">No.</th>
                            <th style="width: 15%">Name</th>
                            <th style="width: 5%">Matric No.</th>
                            <th style="width: 5%">Program</th>
                            <th style="width: 20%">Submission Date</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 5%">Marks</th>
                            <th style="width: 20%"></th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($quiz as $key => $qz) {
            $alert = (count($status[$key]) > 0) ? 'badge bg-success' : 'badge bg-danger';

            $content .= '
                <tr>
                    <td style="width: 1%">' . ($key + 1) . '</td>
                    <td style="width: 15%">
                        <span class="' . $alert . '">' . $qz->name . '</span>
                    </td>
                    <td style="width: 5%">
                        <span>' . $qz->no_matric . '</span>
                    </td>
                    <td style="width: 5%">
                        <span>' . $qz->progcode . '</span>
                    </td>';
            
            if (count($status[$key]) > 0) {
                foreach ($status[$key] as $keys => $sts) {
                    $content .= '
                        <td style="width: 20%">' . (empty($sts) ? '-' : $sts->submittime) . '</td>
                        <td>' . (empty($sts) ? '-' : $sts->status) . '</td>
                        <td>' . (empty($sts) ? '-' : $sts->final_mark) . ' / ' . $qz->total_mark . '</td>
                        <td class="project-actions text-center">
                            <a class="btn btn-success btn-sm mr-2" href="/lecturer/quiz/' . request()->quiz . '/' . $sts->userid . '/result">
                                <i class="ti-pencil-alt"></i> Answer
                            </a>';
                    if (date('Y-m-d H:i:s') >= $qz->date_from && date('Y-m-d H:i:s') <= $qz->date_to) {
                        $content .= '
                            <a class="btn btn-danger btn-sm mr-2" onclick="deleteStdQuiz(\'' . $sts->id . '\')">
                                <i class="ti-trash"></i> Delete
                            </a>';
                    }
                    $content .= '
                        </td>';
                }
            } else {
                $content .= '
                    <td style="width: 20%">-</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>';
            }

            $content .= '
                </tr>';
        }

        $content .= '</tbody>';


        return response()->json(['message' => 'success', 'content' => $content]);


    }

    public function deletequizstatus(Request $request)
    {

        DB::table('tblclassstudentquiz')->where('id', $request->id)->delete();

        return true;
        
    }

    public function quizresult(Request $request){
        
        $id = $request->quizid;
        $userid = $request->userid;
        $count = 1;
    
        $quiz = DB::table('tblclassstudentquiz')
            ->join('tblclassquiz', 'tblclassstudentquiz.quizid', 'tblclassquiz.id')
            ->leftJoin('students', 'tblclassstudentquiz.userid', 'students.ic')
            ->select('tblclassstudentquiz.*', 'tblclassstudentquiz.quizid', 'tblclassquiz.title',  
                DB::raw('tblclassquiz.content as original_content'), 
                'tblclassquiz.questionindex',
                'tblclassstudentquiz.userid',
                'tblclassstudentquiz.submittime',
                DB::raw('tblclassstudentquiz.status as studentquizstatus'),
                'tblclassquiz.duration','students.name',
                'tblclassquiz.total_mark')
            ->where('tblclassstudentquiz.quizid', $id)
            ->where('tblclassstudentquiz.userid', $userid)->get()->first();
       
        $quizformdata = json_decode($quiz->content)->formData;
        $original_quizformdata = json_decode($quiz->original_content)->formData;
        
        $gain_mark = false;
        $correct_label = " <i style='font-size:1.5em' class='fa fa-check text-success'></i>";
        $incorrect_label = " <i style='font-size:1.5em' class='fa fa-close text-danger'></i>";
    
        foreach($original_quizformdata as $index => $q){
            if(!empty($original_quizformdata[$index]->name)){
                // Handle radio questions
                if(preg_match('/^radio-question(\d+)$/', $original_quizformdata[$index]->name, $matches)){
                    $question_number = $matches[1];
                    $i = 0;
                    
                    // Get and process correct answer(s) - check if correct answer exists
                    $correct_answer_raw = '';
                    if(isset($original_quizformdata[$index + 1]) && isset($original_quizformdata[$index + 1]->label)){
                        $correct_answer_raw = $original_quizformdata[$index + 1]->label;
                    }
                    $correct_answers = !empty($correct_answer_raw) ? explode(",", $correct_answer_raw) : [];
                    
                    // Trim whitespace from each correct answer
                    $correct_answers = array_map('trim', $correct_answers);
                    
                    foreach($quizformdata[$index]->values as $v){
                        $value_trimmed = trim($v->value);
                        
                        if(in_array($value_trimmed, $correct_answers)){
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $correct_label;
                        } else {
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }
    
                    $userData = !empty($quizformdata[$index]->userData[0]) ? trim($quizformdata[$index]->userData[0]) : null;
    
                    if($userData && in_array($userData, $correct_answers)){
                        $gain_mark = true;
                    }
                    
                    if($question_number == $count) {
                        $count++;
                    }
                }
                
                // Handle checkbox questions
                if(preg_match('/^checkbox-question(\d+)$/', $original_quizformdata[$index]->name, $matches)){
                    $question_number = $matches[1];
                    $i = 0;
                    
                    // Get and process correct answer(s) - check if correct answer exists
                    $correct_answer_raw = '';
                    if(isset($original_quizformdata[$index + 1]) && isset($original_quizformdata[$index + 1]->label)){
                        $correct_answer_raw = $original_quizformdata[$index + 1]->label;
                    }
                    $correct_answers = !empty($correct_answer_raw) ? explode(",", $correct_answer_raw) : [];
                    
                    // Trim whitespace from each correct answer
                    $correct_answers = array_map('trim', $correct_answers);
                    
                    foreach($quizformdata[$index]->values as $v){
                        $value_trimmed = trim($v->value);
                        
                        if(in_array($value_trimmed, $correct_answers)){
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $correct_label;
                        } else {
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }
                    
                    $userData = !empty($quizformdata[$index]->userData) ? $quizformdata[$index]->userData : [];
                    
                    // Trim whitespace from user data
                    $userData = array_map('trim', $userData);
                    
                    // Sort both arrays to ensure consistent comparison
                    sort($userData);
                    sort($correct_answers);
                    
                    // Compare arrays (case-insensitive)
                    $userData_lower = array_map('strtolower', $userData);
                    $correct_answers_lower = array_map('strtolower', $correct_answers);
                    
                    if($userData_lower == $correct_answers_lower){
                        $gain_mark = true;
                    }
                    
                    if($question_number == $count) {
                        $count++;
                    }
                }
            }
    
            if(!empty($original_quizformdata[$index]->className)){
                
                if(str_contains($original_quizformdata[$index]->className, "feedback-text")){
                    $quizformdata[$index] = $q;
                  
                    $quizformdata[$index]->type = "paragraph";
    
                    if(!empty($q->userData[0])){
                        $quizformdata[$index]->label = $q->userData[0];
                    }else{
                        $quizformdata[$index]->label = " ";
                    }
                    $quizformdata[$index]->className = "bg-red mb-4 text-danger";
                }
    
                if(str_contains($original_quizformdata[$index]->className, "inputmark")){
                    $quizformdata[$index]->type = "number";
    
                    if(!empty($q->userData[0])){
                        $quizformdata[$index]->label = $q->userData[0];
                    }else{
                        $quizformdata[$index]->label = " ";
                    }
    
                    $quizformdata[$index]->className = "inputmark form-control";
                }
    
                if(str_contains($original_quizformdata[$index]->className, "collected-marks")){
    
                    $mark_label = $original_quizformdata[$index]->values[0]->label;
                    $mark = $original_quizformdata[$index]->values[0]->value;
                    
                    //if result is published then use graded data
                    if($quiz->studentquizstatus == 3){
                        $graded_data = empty($quizformdata[$index]->userData[0]) ? "" : $quizformdata[$index]->userData[0];
    
                        if($graded_data == $mark){
                            $quizformdata[$index]->values[0]->selected = true;
                        }else{
                            $quizformdata[$index]->values[0]->selected = false;
                        }
                    }else{
                        //auto correct answer on mcq by matching user answer with original answer
                        $quizformdata[$index] = $original_quizformdata[$index];
    
                        if($gain_mark){
                            $quizformdata[$index]->values[0]->selected = true;
                        }else{
                            $quizformdata[$index]->values[0]->selected = false;
                        }
                        
                        $gain_mark = false;
                    }
                }
            }
        }
       
        $data['quiz'] = $quizformdata;
        $data['comments'] = $quiz->comments;
        $data['totalmark'] = $quiz->total_mark;
        $data['quizid'] = $quiz->quizid;
        $data['quiztitle'] = $quiz->title;
        $data['quizduration'] = $quiz->duration;
        $data['quizuserid'] = $quiz->userid;
        $data['fullname'] = $quiz->name;
        $data['created_at'] = $quiz->created_at;
        $data['updated_at'] = $quiz->updated_at;
        $data['submittime'] = $quiz->submittime;
        $data['questionindex'] = $quiz->questionindex;
        $data['studentquizstatus'] = $quiz->studentquizstatus;
    
        return view('lecturer.courseassessment.quizresult', compact('data'));
    }

    public function updatequizresult(Request $request){
        $quiz = $request->quiz;
        $participant = $request->participant;
        $final_mark = $request->final_mark;
        $comments = $request->comments;
        //$total_mark = $request->total_mark;
        $data = $request->data;
      
        DB::table('tblclassstudentquiz')
            ->where('quizid', $quiz)
            ->where("userid", $participant)
            ->update([
                "content" => $data,
                "final_mark" => $final_mark,
                //"total_mark" => $total_mark,
                "comments" => $comments,
                "status" => 3
            ]);

        $message = "Lecturer has marked your quiz.";
        $url = url('/student/quiz/' . $quiz . '/' . $participant . '/result');
        $icon = "fa-check fa-lg";
        $iconColor = "#2b74f3"; // Example: set to a bright orange

        $participant = UserStudent::where('ic', $participant)->first();

        Notification::send($participant, new MyCustomNotification($message, $url, $icon, $iconColor));

        
        return true;
    }

    public function autoMarkQuiz(Request $request)
    {
        try {
            $user = Auth::user();
            $quizId = $request->quiz_id;
            $classid = $request->course_id;
            $sessionid = $request->session_id;
            
            // Get the specific quiz
            $quiz = DB::table('tblclassquiz')
                ->where([
                    ['id', $quizId],
                    ['classid', $classid],
                    ['sessionid', $sessionid], 
                    ['addby', $user->ic],
                    ['status', 2], // Published status
                    ['content', '!=', null]
                ])->first();

            if (!$quiz) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quiz not found or not available for auto marking.'
                ]);
            }

            // Check if quiz contains only radio questions
            $quizContent = json_decode($quiz->content);
            if (!$quizContent || !isset($quizContent->formData)) {
                return response()->json([
                    'success' => false,
                    'message' => "Quiz '{$quiz->title}' has invalid format and cannot be auto-marked."
                ]);
            }

            $hasOnlyRadioQuestions = $this->checkIfOnlyRadioQuestions($quizContent->formData);
            
            if (!$hasOnlyRadioQuestions['isOnlyRadio']) {
                return response()->json([
                    'success' => false,
                    'message' => "Quiz '{$quiz->title}' cannot be auto-marked. {$hasOnlyRadioQuestions['reason']}."
                ]);
            }

            // Get all submitted quizzes for this specific quiz that are not yet marked
            $submittedQuizzes = DB::table('tblclassstudentquiz')
                ->where([
                    ['quizid', $quiz->id],
                    ['status', 2] // Submitted but not marked
                ])->get();

            if ($submittedQuizzes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => "No ungraded submissions found for quiz '{$quiz->title}'."
                ]);
            }

            $autoMarkedCount = 0;
            $failedCount = 0;

            foreach ($submittedQuizzes as $submittedQuiz) {
                $markedSuccessfully = $this->autoMarkSingleQuiz($quiz, $submittedQuiz);
                if ($markedSuccessfully) {
                    $autoMarkedCount++;
                } else {
                    $failedCount++;
                }
            }

            $message = "Auto marking completed for quiz '{$quiz->title}'! ";
            $message .= "Successfully auto-marked: {$autoMarkedCount} student submission(s).";
            
            if ($failedCount > 0) {
                $message .= " Failed to mark: {$failedCount} submission(s).";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'auto_marked' => $autoMarkedCount,
                'failed' => $failedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Auto mark quiz error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error during auto marking: ' . $e->getMessage()
            ], 500);
        }
    }

    private function checkIfOnlyRadioQuestions($formData)
    {
        $hasRadioQuestions = false;
        $questionTypes = [];
        
        foreach ($formData as $item) {
            // Skip elements without type or name
            if (!isset($item->type)) {
                continue;
            }

            // Check for radio-group questions (single choice)
            if ($item->type === 'radio-group' && isset($item->name) && preg_match('/^radio-question\d+$/', $item->name)) {
                $hasRadioQuestions = true;
                $questionTypes[] = 'radio';
                continue;
            }

            // Check for checkbox-group questions (multiple choice) 
            if ($item->type === 'checkbox-group' && isset($item->name) && preg_match('/^checkbox-question\d+$/', $item->name)) {
                $questionTypes[] = 'checkbox';
                return [
                    'isOnlyRadio' => false,
                    'reason' => 'Contains multiple choice (checkbox) questions'
                ];
            }

            // Check for subjective questions (textarea)
            if ($item->type === 'textarea' && isset($item->name) && preg_match('/^subjective-text\d+$/', $item->name)) {
                $questionTypes[] = 'subjective';
                return [
                    'isOnlyRadio' => false,
                    'reason' => 'Contains subjective (textarea) questions'
                ];
            }

            // Check for text input questions (also subjective)
            if ($item->type === 'text' && isset($item->name) && preg_match('/^subjective-text\d+$/', $item->name)) {
                $questionTypes[] = 'text-subjective';
                return [
                    'isOnlyRadio' => false,
                    'reason' => 'Contains subjective (text input) questions'
                ];
            }

            // Skip non-question elements (headers, paragraphs, files, etc.)
            // Elements with className containing 'correct-answer', 'collected-marks', 'feedback-text' are not actual questions
            if (isset($item->className)) {
                if (str_contains($item->className, 'correct-answer') || 
                    str_contains($item->className, 'collected-marks') || 
                    str_contains($item->className, 'feedback-text')) {
                    continue;
                }
            }
        }

        if (!$hasRadioQuestions) {
            return [
                'isOnlyRadio' => false,
                'reason' => 'No radio button questions found'
            ];
        }

        return [
            'isOnlyRadio' => true,
            'reason' => 'Quiz contains only radio button questions'
        ];
    }

    // Test method to validate the quiz structure - can be removed after testing
    public function testQuizValidation(Request $request)
    {
        $sampleData = json_decode($request->quiz_data);
        
        if (!$sampleData || !isset($sampleData->formData)) {
            return response()->json([
                'error' => 'Invalid quiz data format'
            ]);
        }
        
        $result = $this->checkIfOnlyRadioQuestions($sampleData->formData);
        
        return response()->json([
            'isOnlyRadio' => $result['isOnlyRadio'],
            'reason' => $result['reason'],
            'quiz_analysis' => $this->analyzeQuizStructure($sampleData->formData)
        ]);
    }
    
    private function analyzeQuizStructure($formData)
    {
        $analysis = [
            'total_elements' => count($formData),
            'question_elements' => [],
            'support_elements' => []
        ];
        
        foreach ($formData as $index => $item) {
            $elementInfo = [
                'index' => $index,
                'type' => $item->type ?? 'unknown',
                'name' => $item->name ?? 'no-name',
                'className' => $item->className ?? 'no-class'
            ];
            
            if (isset($item->type)) {
                if ($item->type === 'radio-group' && isset($item->name) && preg_match('/^radio-question\d+$/', $item->name)) {
                    $elementInfo['category'] = 'radio-question';
                    $analysis['question_elements'][] = $elementInfo;
                } elseif ($item->type === 'checkbox-group' && isset($item->name) && preg_match('/^checkbox-question\d+$/', $item->name)) {
                    $elementInfo['category'] = 'checkbox-question';
                    $analysis['question_elements'][] = $elementInfo;
                } elseif ($item->type === 'textarea' && isset($item->name) && preg_match('/^subjective-text\d+$/', $item->name)) {
                    $elementInfo['category'] = 'subjective-question';
                    $analysis['question_elements'][] = $elementInfo;
                } else {
                    $elementInfo['category'] = 'support-element';
                    $analysis['support_elements'][] = $elementInfo;
                }
            }
        }
        
        return $analysis;
    }

    private function autoMarkSingleQuiz($quiz, $submittedQuiz)
    {
        try {
            $quizformdata = json_decode($submittedQuiz->content)->formData;
            $original_quizformdata = json_decode($quiz->content)->formData;
            
            $totalMarks = 0;
            $gainedMarks = 0;
            $gain_mark = false;
            $count = 1;
            
            // Process each form element - same logic as quizresult method
            foreach ($original_quizformdata as $index => $originalItem) {
                if (!empty($originalItem->name)) {
                    // Handle radio questions only
                    if (preg_match('/^radio-question(\d+)$/', $originalItem->name, $matches)) {
                        $question_number = $matches[1];
                        
                        // Get correct answer
                        $correct_answer_raw = '';
                        if (isset($original_quizformdata[$index + 1]) && isset($original_quizformdata[$index + 1]->label)) {
                            $correct_answer_raw = $original_quizformdata[$index + 1]->label;
                        }
                        $correct_answers = !empty($correct_answer_raw) ? explode(",", $correct_answer_raw) : [];
                        $correct_answers = array_map('trim', $correct_answers);
                        
                        // Get user's answer
                        $userData = !empty($quizformdata[$index]->userData[0]) ? trim($quizformdata[$index]->userData[0]) : null;
                        
                        // Check if answer is correct
                        if ($userData && in_array($userData, $correct_answers)) {
                            $gain_mark = true;
                        }
                        
                        if ($question_number == $count) {
                            $count++;
                        }
                    }
                }
                
                // Handle marking elements - using same logic as quizresult method
                if (!empty($originalItem->className) && str_contains($originalItem->className, "collected-marks")) {
                    $mark_label = $originalItem->values[0]->label;
                    $mark = $originalItem->values[0]->value;
                    
                    // Auto correct answer on mcq by matching user answer with original answer
                    $quizformdata[$index] = $original_quizformdata[$index];
                    
                    if ($gain_mark) {
                        $quizformdata[$index]->values[0]->selected = true;
                        // Set userData to the mark value so the checkbox appears checked when published
                        $quizformdata[$index]->userData = [$mark];
                        $gainedMarks += floatval($mark);
                    } else {
                        $quizformdata[$index]->values[0]->selected = false;
                        // Set userData to empty/0 so the checkbox appears unchecked when published
                        $quizformdata[$index]->userData = [""];
                    }
                    
                    $totalMarks += floatval($mark);
                    $gain_mark = false; // Reset for next question
                }
            }
            
            // Update the quiz submission with marks
            $updatedContent = json_encode(['formData' => $quizformdata]);
            
            DB::table('tblclassstudentquiz')
                ->where('id', $submittedQuiz->id)
                ->update([
                    'content' => $updatedContent,
                    'final_mark' => $gainedMarks . ' Mark',
                    'comments' => 'Auto-marked by system',
                    'status' => 3 // Marked status
                ]);

            // Send notification to student
            $message = "Your quiz has been auto-marked by the system.";
            $url = url('/student/quiz/' . $quiz->id . '/' . $submittedQuiz->userid . '/result');
            $icon = "fa-check fa-lg";
            $iconColor = "#2b74f3";

            $student = UserStudent::where('ic', $submittedQuiz->userid)->first();
            if ($student) {
                Notification::send($student, new MyCustomNotification($message, $url, $icon, $iconColor));
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Error auto marking single quiz: ' . $e->getMessage());
            return false;
        }
    }




    //This is Student Quiz Controller//

    public function studentquizlist()
    {
        $chapter = [];

        Session::put('CourseIDS', request()->id);

        if(Session::get('SessionIDS') == null)
        {
        Session::put('SessionIDS', request()->session);
        }

        $student = auth()->guard('student')->user();

        Session::put('StudInfos', $student);

        $courseid = DB::table('subjek')->where('id', request()->id)->value('sub_id');

        $group = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('users', 'user_subjek.user_ic', 'users.ic')
                ->where([
                    ['student_subjek.student_ic', $student->ic],
                    ['student_subjek.courseid', $courseid],
                    ['student_subjek.sessionid', request()->session]
                    ])
                ->select('user_subjek.id')
                ->first();

        //dd($group);

        $data = DB::table('tblclassquiz')
                ->join('users', 'tblclassquiz.addby', 'users.ic')
                ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                ->join('student_subjek', function($join){
                    $join->on('tblclassquiz_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassquiz_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassquiz_group.groupid', 'user_subjek.id')
                ->select('tblclassquiz.*', 'tblclassquiz_group.groupname', 'users.name AS addby')
                ->where([
                    ['user_subjek.id', $group->id],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic],
                    ['tblclassquiz.content','!=', null],
                    ['tblclassquiz.status','!=', 3],
                    ['tblclassquiz.date_from','!=', null]
                ])->get();

        //dd($data);

        foreach($data as $dt)
        {
            $chapter[] = DB::table('tblclassquiz_chapter')
                      ->join('material_dir', 'tblclassquiz_chapter.chapterid', 'material_dir.DrID')
                      ->where('tblclassquiz_chapter.quizid', $dt->id)->get();
        }

        return view('student.courseassessment.quiz', compact('data', 'chapter'));

    }

    public function studentquizstatus()
    {
        $courseid = DB::table('subjek')->where('id', Session::get('CourseIDS'))->value('sub_id');

        $group = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('users', 'user_subjek.user_ic', 'users.ic')
                ->where([
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic],
                    ['student_subjek.courseid', $courseid],
                    ['student_subjek.sessionid', Session::get('SessionIDS')]
                    ])
                ->select('user_subjek.id')
                ->first();

        $quiz = DB::table('student_subjek')
                ->join('tblclassquiz_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassquiz_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassquiz_group.groupname');
                })
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->where([
                    ['student_subjek.group_id', $group->id],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', request()->quiz],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();

        //dd(Session::get('StudInfos')->ic);

        foreach($quiz as $qz)
        {
            $status[] = DB::table('tblclassstudentquiz')
            ->where([
                ['quizid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.quizstatus', compact('quiz', 'status'));
    }

    public function quizview(Request $request){

        $id = $request->quiz;

        if(DB::table('tblclassstudentquiz')
        ->where([
            ['userid', Session::get('StudInfo')->ic],
            ['quizid', $id]
         ])->exists()) {

            $quiz = DB::table('tblclassquiz')
            ->leftjoin('tblclassstudentquiz', function($join) 
            {
                $join->on('tblclassquiz.id', '=', 'tblclassstudentquiz.quizid');
            })
            ->where('tblclassstudentquiz.userid',  '=', Session::get('StudInfo')->ic);

         }else{


            $quiz = DB::table('tblclassquiz')
            ->leftjoin('tblclassstudentquiz', function($join) 
            {
                $join->on('tblclassquiz.id', '=', 'tblclassstudentquiz.quizid');
                $join->on('tblclassstudentquiz.userid',  '=', DB::raw('1234'));
            });

         }

         $quiz = $quiz->leftJoin('students', 'tblclassstudentquiz.userid', 'students.ic')
         ->leftJoin('tblclassquizstatus', 'tblclassquiz.status', 'tblclassquizstatus.id')
         ->select('tblclassquiz.*', 'tblclassstudentquiz.userid', 'tblclassstudentquiz.quizid','students.name', 
             DB::raw('tblclassquiz.status as classquizstatus'),
             DB::raw('tblclassstudentquiz.status as studentquizstatus'), 'tblclassstudentquiz.endtime', 'tblclassstudentquiz.starttime' , 
             DB::raw('TIMESTAMPDIFF(SECOND, now(), endtime) as timeleft'),
             DB::raw('tblclassstudentquiz.content as studentquizcontent')
         )
         ->where('tblclassquiz.id', $id)
         ->get()->first();

        $quizformdata = json_decode($quiz->content)->formData;

        if(!empty($quiz->studentquizcontent)){
            $quizformdata = json_decode($quiz->studentquizcontent)->formData;
        }

        foreach($quizformdata as $index => $v){

            if(!empty($quizformdata[$index]->className) ){
                if ($v->type === 'file') {
                    $quizformdata[$index]->disabled = true;
                    $quizformdata[$index]->label = null;
                    $quizformdata[$index]->description = null;
                }

                if(str_contains($quizformdata[$index]->className, "collected-marks")){
                    $quizformdata[$index]->type = "paragraph";
                    $quizformdata[$index]->label = $quizformdata[$index]->values[0]->label;
                }

                if(str_contains($quizformdata[$index]->className, "correct-answer")){
                    $quizformdata[$index]->className = "correct-answer d-none";
                    unset($quizformdata[$index]->label);
                }

                if(str_contains($quizformdata[$index]->className, "feedback-text")){
                    $quizformdata[$index]->className = "feedback-text d-none";
                    unset($quizformdata[$index]->label);
                }

                if(str_contains($quizformdata[$index]->className, "inputmark")){
                    $quizformdata[$index]->className = "inputmark d-none";
                    unset($quizformdata[$index]->label);
                }
            }
        }

        if($quiz->classquizstatus == 2){
            if($quiz->studentquizstatus == 2 || $quiz->studentquizstatus == 3){
                //completed quiz
                return redirect('/academics/quiz/'.$quiz->quizid.'/result');
            }else{
                $data['quiz'] = json_encode($quizformdata );
                $data['quizid'] = $quiz->id;
                $data['quiztitle'] = $quiz->title;
                $data['quizduration'] = $quiz->duration;
                $data['quizendduration'] = $quiz->date_to;
                $data['fullname'] = $quiz->name;
                $data['created_at'] = $quiz->created_at;
                $data['updated_at'] = $quiz->updated_at;
                $data['quizstarttime'] = $quiz->starttime;
                $data['quizendtime'] = $quiz->endtime;
                $data['quiztimeleft'] = $quiz->timeleft;
        
                return view('student.courseassessment.quizanswer', compact('data'));
            }
        }else{
            return "Quiz is not published yet";
        }
    }

    public function startquiz(Request $request){

        $quiz = $request->quiz;
        $data = $request->data;
        
        $quizduration = DB::table('tblclassquiz')->select('duration')->where('id', $quiz)->first()->duration;
        
        try{
            DB::beginTransaction();
            $q =  DB::table('tblclassstudentquiz')->insert([
                "userid" =>  Session::get('StudInfos')->ic,
                "quizid" => $quiz,
                "content" => $data,
                "starttime" =>  DB::raw('now()'),
                "endtime" => DB::raw('now() + INTERVAL '.$quizduration.' MINUTE'),
                "status" => 1
            ]);
            DB::commit();
        }catch(QueryException $ex){
            if($ex->getCode() == 23000){
            }else{
                \Log::debug($ex);
            }
        }
    }

    public function savequiz(Request $request){

        $data = $request->data;
        $quizid = $request->quiz;


        $q = DB::table('tblclassstudentquiz')->where('status', 1)->where('quizid',$quizid)->where('userid', Session::get('StudInfos')->ic)->update([
            "content" => $data
        ]);

        $q = ($q == 1) ? true : false;

        return $q;
     
    }

    public function submitquiz(Request $request){
        $data = $request->data;
        $id = $request->id;

         // Decode the JSON data
        $decodedData = json_decode($data, true);

        // Iterate over formData and update checkbox groups
        foreach ($decodedData['formData'] as &$item) {
            if ($item['type'] == 'checkbox-group') {
                if (empty($item['userData']) || !isset($item['userData'])) {
                    $item['userData'] = [" "];
                }
            }
        }

        // Encode the data back to JSON
        $data = json_encode($decodedData);

        $quiz = DB::table('tblclassquiz')
            ->leftjoin('tblclassstudentquiz', function($join) 
            {
                $join->on('tblclassquiz.id', '=', 'tblclassstudentquiz.quizid');
                $join->on('tblclassstudentquiz.userid',  '=', DB::raw('12345'));
            })
            ->select('tblclassquiz.*', 'tblclassstudentquiz.userid', DB::raw('tblclassstudentquiz.status as studentquizstatus'),
             'tblclassstudentquiz.quizid')
            ->where('tblclassquiz.id', $id)
            ->get()->first();

        if($quiz->studentquizstatus == 2 || $quiz->studentquizstatus == 3){
            return ["status"=>false, "message" =>"Sorry, you have completed the quiz before."];
        }

        $q = DB::table('tblclassstudentquiz')->upsert([
            "userid" => Session::get('StudInfos')->ic,
            "quizid" => $id,
            "submittime" => DB::raw('now()'),
            "content" => $data,
            "status" => 2
        ],['userid', 'quizid']);

        return ["status"=>true, "message" =>$data];
     
    }

    public function quizresultstd(Request $request){
        
        $id = $request->quizid;
        $userid = $request->userid;
        $count = 1;
    
        $quiz = DB::table('tblclassstudentquiz')
            ->join('tblclassquiz', 'tblclassstudentquiz.quizid', 'tblclassquiz.id')
            ->leftJoin('students', 'tblclassstudentquiz.userid', 'students.ic')
            ->select('tblclassstudentquiz.*', 'tblclassstudentquiz.quizid', 'tblclassquiz.title',  
                DB::raw('tblclassquiz.content as original_content'), 
                'tblclassquiz.questionindex',
                'tblclassstudentquiz.userid',
                'tblclassstudentquiz.submittime',
                DB::raw('tblclassstudentquiz.status as studentquizstatus'),
                'tblclassquiz.duration','students.name')
            ->where('tblclassstudentquiz.quizid', $id)
            ->where('tblclassstudentquiz.userid', $userid)->get()->first();
       
        $quizformdata = json_decode($quiz->content)->formData;
        $original_quizformdata = json_decode($quiz->original_content)->formData;
    
        $gain_mark = false;
        $correct_label = " <i style='font-size:1.5em' class='fa fa-check text-success'></i>";
        $incorrect_label = " <i style='font-size:1.5em' class='fa fa-close text-danger'></i>";
    
        foreach($original_quizformdata as $index => $q){
            if(!empty($original_quizformdata[$index]->name)){
                // Handle radio questions
                if(preg_match('/^radio-question(\d+)$/', $original_quizformdata[$index]->name, $matches)){
                    $question_number = $matches[1];
                    $i = 0;
                    
                    // Get and process correct answer(s) - check if correct answer exists
                    $correct_answer_raw = '';
                    if(isset($original_quizformdata[$index + 1]) && isset($original_quizformdata[$index + 1]->label)){
                        $correct_answer_raw = $original_quizformdata[$index + 1]->label;
                    }
                    $correct_answers = !empty($correct_answer_raw) ? explode(",", $correct_answer_raw) : [];
                    
                    // Trim whitespace from each correct answer
                    $correct_answers = array_map('trim', $correct_answers);
                    
                    foreach($quizformdata[$index]->values as $v){
                        $value_trimmed = trim($v->value);
                        
                        if(in_array($value_trimmed, $correct_answers)){
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $correct_label;
                        } else {
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }
    
                    $userData = !empty($quizformdata[$index]->userData[0]) ? trim($quizformdata[$index]->userData[0]) : null;
    
                    if($userData && in_array($userData, $correct_answers)){
                        $gain_mark = true;
                    }
                    
                    if($question_number == $count) {
                        $count++;
                    }
                }
                
                // Handle checkbox questions
                if(preg_match('/^checkbox-question(\d+)$/', $original_quizformdata[$index]->name, $matches)){
                    $question_number = $matches[1];
                    $i = 0;
                    
                    // Get and process correct answer(s) - check if correct answer exists
                    $correct_answer_raw = '';
                    if(isset($original_quizformdata[$index + 1]) && isset($original_quizformdata[$index + 1]->label)){
                        $correct_answer_raw = $original_quizformdata[$index + 1]->label;
                    }
                    $correct_answers = !empty($correct_answer_raw) ? explode(",", $correct_answer_raw) : [];
                    
                    // Trim whitespace from each correct answer
                    $correct_answers = array_map('trim', $correct_answers);
                    
                    foreach($quizformdata[$index]->values as $v){
                        $value_trimmed = trim($v->value);
                        
                        if(in_array($value_trimmed, $correct_answers)){
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $correct_label;
                        } else {
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }
                    
                    $userData = !empty($quizformdata[$index]->userData) ? $quizformdata[$index]->userData : [];
                    
                    // Trim whitespace from user data
                    $userData = array_map('trim', $userData);
                    
                    // Sort both arrays to ensure consistent comparison
                    sort($userData);
                    sort($correct_answers);
                    
                    // Compare arrays (case-insensitive)
                    $userData_lower = array_map('strtolower', $userData);
                    $correct_answers_lower = array_map('strtolower', $correct_answers);
                    
                    if($userData_lower == $correct_answers_lower){
                        $gain_mark = true;
                    }
                    
                    if($question_number == $count) {
                        $count++;
                    }
                }
            }
    
            if(!empty($original_quizformdata[$index]->className)){
                
                if(str_contains($original_quizformdata[$index]->className, "feedback-text")){
                    $quizformdata[$index] = $q;
                  
                    $quizformdata[$index]->type = "paragraph";
    
                    if(!empty($q->userData[0])){
                        $quizformdata[$index]->label = $q->userData[0];
                    }else{
                        $quizformdata[$index]->label = " ";
                    }
                    $quizformdata[$index]->className = "bg-red mb-4 text-danger";
                }
    
                if(str_contains($original_quizformdata[$index]->className, "inputmark")){
                    $quizformdata[$index]->type = "number";
    
                    if(!empty($q->userData[0])){
                        $quizformdata[$index]->label = $q->userData[0];
                    }else{
                        $quizformdata[$index]->label = " ";
                    }
                    $quizformdata[$index]->className = "inputmark form-control";
                }
    
                if(str_contains($original_quizformdata[$index]->className, "collected-marks")){
    
                    $mark_label = $original_quizformdata[$index]->values[0]->label;
                    $mark = $original_quizformdata[$index]->values[0]->value;
                    
                    //if result is published then use graded data
                    if($quiz->studentquizstatus == 3){
                        $graded_data = empty($quizformdata[$index]->userData[0]) ? "" : $quizformdata[$index]->userData[0];
    
                        if($graded_data == $mark){
                            $quizformdata[$index]->values[0]->selected = true;
                        }else{
                            $quizformdata[$index]->values[0]->selected = false;
                        }
                    }else{
                        //auto correct answer on mcq by matching user answer with original answer
                        $quizformdata[$index] = $original_quizformdata[$index];
    
                        if($gain_mark){
                            $quizformdata[$index]->values[0]->selected = true;
                        }else{
                            $quizformdata[$index]->values[0]->selected = false;
                        }
                        
                        $gain_mark = false;
                    }
                }
            }
        }
       
        $data['quiz'] = $quizformdata;
        $data['comments'] = $quiz->comments;
        $data['quizid'] = $quiz->quizid;
        $data['quiztitle'] = $quiz->title;
        $data['quizduration'] = $quiz->duration;
        $data['quizuserid'] = $quiz->userid;
        $data['fullname'] = $quiz->name;
        $data['created_at'] = $quiz->created_at;
        $data['updated_at'] = $quiz->updated_at;
        $data['submittime'] = $quiz->submittime;
        $data['questionindex'] = $quiz->questionindex;
        $data['studentquizstatus'] = $quiz->studentquizstatus;
    
        return view('student.courseassessment.quizresult', compact('data'));
    }



    //THIS IS QUIZ PART 2


    public function quiz2list()
    {
        Session::put('CourseIDS', request()->id);

        if(Session::get('SessionIDS') == null)
        {
        Session::put('SessionIDS', request()->session);
        }

        $user = Auth::user();
        $group = array();

        $chapter = array();

        //dd(Session::get('CourseIDS'));

        $data = DB::table('tblclassquiz')
                ->join('users', 'tblclassquiz.addby', 'users.ic')
                ->join('tblclassquizstatus', 'tblclassquiz.status', 'tblclassquizstatus.id')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.addby', $user->ic],
                    ['tblclassquiz.date_from', null],
                    ['tblclassquiz.status', '!=', 3]
                ])
                ->select('tblclassquiz.*', 'users.name AS addby', 'tblclassquizstatus.statusname')->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassquiz_group')
                        ->join('user_subjek', 'tblclassquiz_group.groupid', 'user_subjek.id')
                        ->where('tblclassquiz_group.quizid', $dt->id)->get();

                $chapter[] = DB::table('tblclassquiz_chapter')
                        ->join('material_dir', 'tblclassquiz_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassquiz_chapter.quizid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.quiz2', compact('data', 'group', 'chapter'));
    }

    public function quiz2create()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseIDS');

        $sessionid = Session::get('SessionIDS');

        $data['quiz'] = null;

        $data['folder'] = null;

        $data['chapter'] = null;

        //$totalpercent = 0;

        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->where([
            ['subjek.id', $courseid],
            ['user_subjek.session_id', $sessionid],
            ['user_subjek.user_ic', $user->ic]
        ])->groupBy('student_subjek.group_name')
        ->select('user_subjek.*', 'subjek.course_name', 'student_subjek.group_name')->get();

        //dd(Session::get('CourseIDS'));

        $subid = DB::table('subjek')->where('id', $courseid)->pluck('sub_id');

        $folder = DB::table('lecturer_dir')
                  ->join('subjek', 'lecturer_dir.CourseID','subjek.id')
                  ->where('subjek.sub_id', $subid)
                  ->where('Addby', $user->ic)->get();

        if(isset(request()->quizid))
        {

            $data['quiz'] = DB::table('tblclassquiz')->where('id', request()->quizid)->first();

            //dd($data['folder']);
            
        }

     

        return view('lecturer.courseassessment.quiz2create', compact(['group', 'folder', 'data']));
    }


    public function insertquiz2(Request $request){
        //$data = $request->data;
        $classid = Session::get('CourseID');
        $sessionid = Session::get('SessionID');
        $title = $request->title;
        $group = $request->group;
        $chapter = $request->chapter;
        $marks = $request->marks;

        $data = $request->validate([
            'myPdf' => 'required', 'mimes:pdf'
        ]);

        $user = Auth::user();

        $dir = "classquiz2/" .  $classid . "/" . $user->name . "/" . $title;
        $classquiz2  = Storage::disk('linode')->makeDirectory($dir);
        $file = $request->file('myPdf');
            
        $quizid = empty($request->quiz) ? '' : $request->quiz;

        if($group != null && $chapter != null)
        {
        
            if( !empty($quizid) ){
                
                $quiz = DB::table('tblclassquiz')->where('id', $quizid)->first();

                Storage::disk('linode')->delete($quiz->content);

                DB::table('tblclassquiz_group')->where('quizid', $quizid)->delete();

                DB::table('tblclassquiz_chapter')->where('quizid', $quizid)->delete();

                $file_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $fileInfo = pathinfo($file_name);
                $filename = $fileInfo['filename'];
                $newname = $filename . "." . $file_ext;
                $newpath = "classquiz2/" .  $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                if(!file_exists($newname)){
                    Storage::disk('linode')->putFileAs(
                        $dir,
                        $file,
                        $newname,
                        'public'
                    );

                    $q = DB::table('tblclassquiz')->where('id', $quizid)->update([
                        "title" => $title,
                        'content' => $newpath,
                        "total_mark" => $marks,
                        "status" => 2
                    ]);

                    foreach($request->group as $grp)
                    {
                        $gp = explode('|', $grp);

                        DB::table('tblclassquiz_group')->insert([
                            "groupid" => $gp[0],
                            "groupname" => $gp[1],
                            "quizid" => $quizid
                        ]);
                    }

                    foreach($request->chapter as $chp)
                    {
                        DB::table('tblclassquiz_chapter')->insert([
                            "chapterid" => $chp,
                            "quizid" => $quizid
                        ]);
                    }

                }

            }else{
                $file_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $fileInfo = pathinfo($file_name);
                $filename = $fileInfo['filename'];
                $newname = $filename . "." . $file_ext;
                $newpath = "classquiz2/" .  $classid . "/" . $user->name . "/" . $title . "/" . $newname;

                if(!file_exists($newname)){
                    Storage::disk('linode')->putFileAs(
                        $dir,
                        $file,
                        $newname,
                        'public'
                    );

                    $q = DB::table('tblclassquiz')->insertGetId([
                        "classid" => $classid,
                        "sessionid" => $sessionid,
                        "title" => $title,
                        'content' => $newpath,
                        "total_mark" => $marks,
                        "addby" => $user->ic,
                        "status" => 2
                    ]);

                    foreach($request->group as $grp)
                    {
                        $gp = explode('|', $grp);

                        DB::table('tblclassquiz_group')->insert([
                            "groupid" => $gp[0],
                            "groupname" => $gp[1],
                            "quizid" => $q
                        ]);
                    }

                    foreach($request->chapter as $chp)
                    {
                        DB::table('tblclassquiz_chapter')->insert([
                            "chapterid" => $chp,
                            "quizid" => $q
                        ]);
                    }

                }

            }

            $allUsers = collect();

            foreach($request->group as $grp) {
                $gp = explode('|', $grp);

                $users = UserStudent::join('student_subjek', 'students.ic', '=', 'student_subjek.student_ic')
                    ->where([
                        ['student_subjek.group_id', $gp[0]],
                        ['student_subjek.group_name', $gp[1]]
                    ])
                    ->select('students.*')
                    ->get();

                $allUsers = $allUsers->merge($users);
            }

            //dd($allUsers);

            $message = "A new offline quiz titled " . $title . " has been created.";
            $url = url('/student/quiz2/' . $classid . '?session=' . $sessionid);
            $icon = "fa-puzzle-piece fa-lg";
            $iconColor = "#8803a0"; // Example: set to a bright orange

            Notification::send($allUsers, new MyCustomNotification($message, $url, $icon, $iconColor));

        }else{

            return redirect()->back()->withErrors(['Please fill in the group and sub-chapter checkbox !']);

        }
        
        
        return redirect(route('lecturer.quiz2', ['id' => $classid]));
    }

    public function lecturerquiz2status()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassquiz_group', 'user_subjek.id', 'tblclassquiz_group.groupid')
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassquiz.id', request()->quiz]
                ])->get();
                
            
        //dd($group);

        $quiz = DB::table('student_subjek')
                ->join('tblclassquiz_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassquiz_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassquiz_group.groupname');
                })
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclassquiz.id AS clssid', 'tblclassquiz.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', request()->quiz],
                    ['tblclassquiz.addby', $user->ic]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();
        
        
        
        //dd($quiz);

        foreach($quiz as $qz)
        {
            //$status[] = DB::table('tblclassstudentquiz')
            //->where([
               // ['quizid', $qz->clssid],
               // ['userid', $qz->student_ic]
           // ])->get();

           if(!DB::table('tblclassstudentquiz')->where([['quizid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentquiz')->insert([
                    'quizid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentquiz')
            ->where([
                ['quizid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        //dd($status);

        return view('lecturer.courseassessment.quiz2status', compact('quiz', 'status', 'group'));

    }

    public function quiz2GetGroup(Request $request)
    {

        $user = Auth::user();

        $gp = explode('|', $request->group);

        $quiz = DB::table('student_subjek')
                ->join('tblclassquiz_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassquiz_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassquiz_group.groupname');
                })
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                ->select('tblprogramme.progcode', 'student_subjek.*', 'tblclassquiz.id AS clssid', 'tblclassquiz.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', request()->quiz],
                    ['tblclassquiz.addby', $user->ic],
                    ['student_subjek.group_id', $gp[0]],
                    ['student_subjek.group_name', $gp[1]]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.program')->get();

        foreach($quiz as $qz)
        {

           if(!DB::table('tblclassstudentquiz')->where([['quizid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentquiz')->insert([
                    'quizid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentquiz')
            ->where([
                ['quizid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
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
                                Matric No.
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Submission Date
                            </th>
                            <th>
                                Marks
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">';
                            
        foreach ($quiz as $key => $qz) {
            $alert = ($status[$key]->final_mark != 0) ? 'badge bg-success' : 'badge bg-danger';

            $content .= '
                <tr>
                    <td style="width: 1%">
                        ' . ($key + 1) . '
                    </td>
                    <td>
                        <span class="' . $alert . '">' . $qz->name . '</span>
                    </td>
                    <td>
                        <span>' . $qz->no_matric . '</span>
                    </td>
                    <td>
                        <span>' . $qz->progcode . '</span>
                    </td>';
            
            if ($status[$key]->final_mark != 0) {
                $content .= '
                    <td>
                        ' . $status[$key]->submittime . '
                    </td>';
            } else {
                $content .= '
                    <td>
                        -
                    </td>';
            }
            
            $content .= '
                    <td>
                        <div class="form-inline col-md-6 d-flex">
                            <input type="number" class="form-control" name="marks[]" max="' . $qz->total_mark . '" value="' . $status[$key]->final_mark . '">
                            <input type="text" name="ic[]" value="' . $qz->student_ic . '" hidden>
                            <span>' . $status[$key]->final_mark . ' / ' . $qz->total_mark . '</span>
                        </div>
                    </td>
                </tr>';
        }

        $content .= '</tbody>';

        return response()->json(['message' => 'success', 'content' => $content]);


    }

    public function updatequiz2(Request $request)
    {
        $user = Auth::user();

        $marks = json_decode($request->marks);

        $ics = json_decode($request->ics);

        $quizid = json_decode($request->quizid);

        $limitpercen = DB::table('tblclassquiz')->where('id', $quizid)->first();

        foreach($marks as $key => $mrk)
        {

            if($mrk > $limitpercen->total_mark)
            {
                return ["message"=>"Field Error", "id" => $ics];
            }

        }

       
        $upsert = [];
        foreach($marks as $key => $mrk){
            $existingMark = DB::table('tblclassstudentquiz')
            ->where('userid', $ics[$key])
            ->where('quizid', $quizid)
            ->value('final_mark');

            array_push($upsert, [
            'userid' => $ics[$key],
            'quizid' => $quizid,
            'submittime' => date("Y-m-d H:i:s"),
            'final_mark' => $mrk,
            'status' => 1
            ]);

            if ($mrk != 0 && $mrk != $existingMark) {
            $message = "Lecturer has marked your offline quiz.";
            $url = url('/student/quiz2/' . $limitpercen->classid . '?session=' . $limitpercen->sessionid);
            $icon = "fa-check fa-lg";
            $iconColor = "#2b74f3"; // Example: set to a bright orange

            $participant = UserStudent::where('ic', $ics[$key])->first();

            Notification::send($participant, new MyCustomNotification($message, $url, $icon, $iconColor));
            }
        }

        DB::table('tblclassstudentquiz')->upsert($upsert, ['userid', 'quizid']);

        return ["message"=>"Success", "id" => $ics];

    }


    //This is quiz 2 Student Controller


    public function studentquiz2list()
    {
        $chapter = [];

        $marks = [];

        Session::put('CourseIDS', request()->id);

        if(Session::get('SessionIDS') == null)
        {
        Session::put('SessionIDS', request()->session);
        }

        $student = auth()->guard('student')->user();

        Session::put('StudInfos', $student);

        //dd($student->ic);

        $courseid = DB::table('subjek')->where('id', request()->id)->value('sub_id');

        $group = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('users', 'user_subjek.user_ic', 'users.ic')
                ->where([
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic],
                    ['student_subjek.courseid', $courseid],
                    ['student_subjek.sessionid', Session::get('SessionIDS')]
                    ])
                ->select('user_subjek.id')
                ->first();

        $data = DB::table('tblclassquiz')
                ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                ->join('tblclassquizstatus', 'tblclassquiz.status', 'tblclassquizstatus.id')
                ->join('student_subjek', function($join){
                    $join->on('tblclassquiz_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassquiz_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassquiz_group.groupid', 'user_subjek.id')
                ->select('tblclassquiz.*', 'tblclassquiz_group.groupname','tblclassquizstatus.statusname')
                ->where([
                    ['user_subjek.id', $group->id],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic],
                    ['tblclassquiz.date_from', null],
                    ['tblclassquiz.status','!=', 3]
                ])->get();

        //dd($data);

        foreach($data as $dt)
        {
            $chapter[] = DB::table('tblclassquiz_chapter')
                      ->join('material_dir', 'tblclassquiz_chapter.chapterid', 'material_dir.DrID')
                      ->where('tblclassquiz_chapter.quizid', $dt->id)->get();

            $marks[] = DB::table('tblclassstudentquiz')
                      ->where([
                        ['quizid', $dt->id],
                        ['userid', $student->ic]
                      ])->get();
        }

        //dd($marks);

        return view('student.courseassessment.quiz2', compact('data', 'chapter', 'marks'));

    }

//     public function generateAIQuiz(Request $request)
// {
//     try {
//         // Step 1: Validate the inputs
//         $request->validate([
//             'document' => 'required|file|mimes:pdf|max:5000',
//             'single_choice_count' => 'required|integer|min:0',
//             'multiple_choice_count' => 'required|integer|min:0',
//             'subjective_count' => 'required|integer|min:0',
//         ]);

//         // Step 2: Save and parse the PDF
//         $filePath = $request->file('document')->store('documents');
//         $parser = new Parser();
//         $pdf = $parser->parseFile(storage_path('app/' . $filePath));
//         $text = $pdf->getText();

//         // Step 3: Generate quiz JSON
//         $singleChoiceCount = $request->input('single_choice_count');
//         $multipleChoiceCount = $request->input('multiple_choice_count');
//         $subjectiveCount = $request->input('subjective_count');

//         $quizJSON = $this->generateFormBuilderJSON($text, $singleChoiceCount, $multipleChoiceCount, $subjectiveCount);

//         // Step 4: Return the quiz JSON
//         return response()->json([
//             'success' => true,
//             'formBuilderJSON' => $quizJSON,
//         ]);

//     } catch (\Exception $e) {
//         // Handle errors gracefully
//         Log::error('Error generating quiz: ' . $e->getMessage());
//         return response()->json([
//             'success' => false,
//             'message' => 'Error generating quiz: ' . $e->getMessage(),
//         ], 500);
//     }
// }



// private function generateFormBuilderJSON($text, $singleChoiceCount, $multipleChoiceCount, $subjectiveCount)
// {
//     $apiKey = env('OPENAI_API_KEY');
//     $client = new Client();

//     try {
//         // Build the AI prompt
//         $prompt = "Create a quiz JSON structure based on the following text. The quiz should include:\n";
//         $prompt .= "- $singleChoiceCount single-choice questions\n";
//         $prompt .= "- $multipleChoiceCount multiple-choice questions\n";
//         $prompt .= "- $subjectiveCount subjective questions\n\n";
//         $prompt .= "Here is the text:\n\n" . $text;

//         // Send the request to OpenAI API
//         $response = $client->post('https://api.openai.com/v1/chat/completions', [
//             'headers' => [
//                 'Authorization' => 'Bearer ' . $apiKey,
//                 'Content-Type' => 'application/json',
//             ],
//             'json' => [
//                 'model' => 'gpt-3.5-turbo-1106', // Ensure this is an available model
//                 'messages' => [
//                     [
//                         'role' => 'system',
//                         'content' => 'You are a system designed to generate quiz questions for FormBuilder.',
//                     ],
//                     [
//                         'role' => 'user',
//                         'content' => $prompt,
//                     ],
//                 ],
//                 'max_tokens' => 2000,
//                 'temperature' => 0.7,
//             ],
//         ]);

//         // Parse the response
//         $responseBody = json_decode($response->getBody(), true);

//         if (isset($responseBody['choices'][0]['message']['content'])) {
//             return $responseBody['choices'][0]['message']['content'];
//         } else {
//             throw new \Exception('Invalid AI response. No content found.');
//         }
//     } catch (\Exception $e) {
//         Log::error('Error communicating with OpenAI: ' . $e->getMessage());
//         throw new \Exception('Error communicating with AI: ' . $e->getMessage());
//     }
// }

// public function generateAIQuiz(Request $request) {
//     try {
//         // Step 1: Validate the inputs
//         $request->validate([
//             'document' => 'required|file|mimes:pdf|max:5000',
//             'single_choice_count' => 'nullable|integer|min:0',
//             'multiple_choice_count' => 'nullable|integer|min:0',
//             'subjective_count' => 'nullable|integer|min:0',
//             'language' => 'required|string|in:english,malay,mix',
//         ]);
        
//         // Step 2: Save and parse the PDF
//         $filePath = $request->file('document')->store('documents');
//         $parser = new Parser();
//         $pdf = $parser->parseFile(storage_path('app/' . $filePath));
//         $text = $pdf->getText();
        
//         // Step 3: Generate quiz JSON
//         $singleChoiceCount = $request->input('single_choice_count', 0);
//         $multipleChoiceCount = $request->input('multiple_choice_count', 0);
//         $subjectiveCount = $request->input('subjective_count', 0);
//         $language = $request->input('language', 'english');
        
//         // Ensure we have at least one question
//         $totalQuestions = $singleChoiceCount + $multipleChoiceCount + $subjectiveCount;
//         if ($totalQuestions <= 0) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Please specify at least one question to generate.',
//             ], 400);
//         }
        
//         $quizJSON = $this->generateFormBuilderJSON($text, $singleChoiceCount, $multipleChoiceCount, $subjectiveCount, $language);
        
//         // Step 4: Return the quiz JSON
//         return response()->json([
//             'success' => true,
//             'formBuilderJSON' => $quizJSON,
//         ]);
        
//     } catch (\Exception $e) {
//         // Handle errors gracefully
//         Log::error('Error generating quiz: ' . $e->getMessage());
//         return response()->json([
//             'success' => false,
//             'message' => 'Error generating quiz: ' . $e->getMessage(),
//         ], 500);
//     }
// }

// private function generateFormBuilderJSON($text, $singleChoiceCount, $multipleChoiceCount, $subjectiveCount, $language) {
//     $apiKey = env('OPENAI_API_KEY');
//     $client = new Client();
    
//     try {
//         // Build the AI prompt with explicit instructions for question types
//         $prompt = "Create a quiz JSON structure based on the following text. The quiz should include:\n";
        
//         if ($singleChoiceCount > 0) {
//             $prompt .= "- $singleChoiceCount single-choice questions\n";
//         } else {
//             $prompt .= "- No single-choice questions\n";
//         }
        
//         if ($multipleChoiceCount > 0) {
//             $prompt .= "- $multipleChoiceCount multiple-choice questions\n";
//         } else {
//             $prompt .= "- No multiple-choice questions\n";
//         }
        
//         if ($subjectiveCount > 0) {
//             $prompt .= "- $subjectiveCount subjective questions\n";
//         } else {
//             $prompt .= "- No subjective questions\n";
//         }
        
//         // Add language instruction
//         $prompt .= "\nLANGUAGE REQUIREMENT:\n";
//         switch ($language) {
//             case 'malay':
//                 $prompt .= "- Generate all questions and answers in Malay (Bahasa Malaysia) only.\n";
//                 break;
//             case 'mix':
//                 $prompt .= "- Generate a mix of questions in both English and Malay (Bahasa Malaysia).\n";
//                 $prompt .= "- Approximately half of the questions should be in English and half in Malay.\n";
//                 break;
//             default: // english
//                 $prompt .= "- Generate all questions and answers in English only.\n";
//                 break;
//         }
        
//         // Add specific formatting instructions for answers
//         $prompt .= "\nANSWER FORMAT REQUIREMENTS:\n";
//         $prompt .= "1. For single-choice questions, provide the correct answer as a single string without any prefixes or labels.\n";
//         $prompt .= "2. For multiple-choice questions, provide the correct answers as a comma-separated string without spaces (e.g., 'Option1,Option3,Option4').\n";
//         $prompt .= "3. For subjective questions, provide a concise sample answer.\n\n";
        
//         // Example JSON structure to guide the AI
//         $prompt .= "Example JSON structure:\n";
//         $prompt .= '{"quiz":{"questions":[';
        
//         if ($singleChoiceCount > 0) {
//             if ($language === 'malay') {
//                 $prompt .= '{"type":"single-choice","question":"Negeri manakah yang dikenali sebagai Negeri Bersejarah?","options":["Melaka","Pahang","Johor","Kedah"],"answer":"Melaka"}';
//             } else {
//                 $prompt .= '{"type":"single-choice","question":"Which state is known as the Historic State?","options":["Melaka","Pahang","Johor","Kedah"],"answer":"Melaka"}';
//             }
//             if ($multipleChoiceCount > 0 || $subjectiveCount > 0) {
//                 $prompt .= ',';
//             }
//         }
        
//         if ($multipleChoiceCount > 0) {
//             if ($language === 'malay') {
//                 $prompt .= '{"type":"multiple-choice","question":"Manakah antara berikut adalah negeri di Malaysia?","options":["Melaka","Singapura","Pahang","Selangor"],"answer":"Melaka,Pahang,Selangor"}';
//             } else {
//                 $prompt .= '{"type":"multiple-choice","question":"Which of the following are states in Malaysia?","options":["Melaka","Singapore","Pahang","Selangor"],"answer":"Melaka,Pahang,Selangor"}';
//             }
//             if ($subjectiveCount > 0) {
//                 $prompt .= ',';
//             }
//         }
        
//         if ($subjectiveCount > 0) {
//             if ($language === 'malay') {
//                 $prompt .= '{"type":"subjective","question":"Terangkan kepentingan Melaka dalam sejarah Malaysia.","answer":"Melaka merupakan pelabuhan perdagangan penting..."}';
//             } else {
//                 $prompt .= '{"type":"subjective","question":"Explain the importance of Melaka in Malaysian history.","answer":"Melaka was an important trading port..."}';
//             }
//         }
        
//         $prompt .= ']}}'."\n\n";
        
//         $prompt .= "Here is the text to generate questions from:\n\n" . $text;
        
//         // Send the request to OpenAI API
//         $response = $client->post('https://api.openai.com/v1/chat/completions', [
//             'headers' => [
//                 'Authorization' => 'Bearer ' . $apiKey,
//                 'Content-Type' => 'application/json',
//             ],
//             'json' => [
//                 'model' => 'gpt-3.5-turbo-1106', 
//                 'messages' => [
//                     [
//                         'role' => 'system',
//                         'content' => 'You are a system designed to generate quiz questions for FormBuilder. Follow the format requirements exactly.' . 
//                                     ($language === 'malay' ? ' You are fluent in Malay (Bahasa Malaysia).' : '') .
//                                     ($language === 'mix' ? ' You are bilingual in English and Malay (Bahasa Malaysia).' : ''),
//                     ],
//                     [
//                         'role' => 'user',
//                         'content' => $prompt,
//                     ],
//                 ],
//                 'max_tokens' => 2000,
//                 'temperature' => 0.7,
//             ],
//         ]);
        
//         // Parse the response
//         $responseBody = json_decode($response->getBody(), true);
        
//         if (isset($responseBody['choices'][0]['message']['content'])) {
//             // Extract the JSON content from the response
//             $jsonContent = $responseBody['choices'][0]['message']['content'];
            
//             // Try to decode the JSON
//             $decodedJson = json_decode($jsonContent, true);
            
//             // Check if the JSON structure is valid
//             if (!$decodedJson || !isset($decodedJson['quiz']) || !isset($decodedJson['quiz']['questions'])) {
//                 // If the structure isn't valid, create a simple one with the requested counts
//                 Log::warning('AI response did not contain valid quiz JSON structure. Creating default structure.');
                
//                 $defaultQuiz = ['quiz' => ['questions' => []]];
                
//                 // Return the default structure as JSON
//                 return json_encode($defaultQuiz);
//             }
            
//             // Log the actual question counts vs requested
//             $questions = $decodedJson['quiz']['questions'];
//             $actualSingleChoice = 0;
//             $actualMultipleChoice = 0;
//             $actualSubjective = 0;
            
//             foreach ($questions as $question) {
//                 if (!isset($question['type'])) continue;
                
//                 if ($question['type'] === 'single-choice') $actualSingleChoice++;
//                 elseif ($question['type'] === 'multiple-choice') $actualMultipleChoice++;
//                 elseif ($question['type'] === 'subjective') $actualSubjective++;
//             }
            
//             Log::info("Question counts - Requested: $singleChoiceCount single, $multipleChoiceCount multiple, $subjectiveCount subjective. Received: $actualSingleChoice single, $actualMultipleChoice multiple, $actualSubjective subjective.");
//             Log::info("Language requested: $language");
            
//             // Return the JSON string as is - let the frontend handle filtering
//             return $jsonContent;
//         } else {
//             throw new \Exception('Invalid AI response. No content found.');
//         }
//     } catch (\Exception $e) {
//         Log::error('Error communicating with OpenAI: ' . $e->getMessage());
//         throw new \Exception('Error communicating with AI: ' . $e->getMessage());
//     }
// }

public function generateAIQuiz(Request $request) {
    try {
        // Clear Laravel cache
        $this->clearApplicationCache();
        
        // Step 1: Validate the inputs
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:5000',
            'single_choice_count' => 'nullable|integer|min:0',
            'multiple_choice_count' => 'nullable|integer|min:0',
            'subjective_count' => 'nullable|integer|min:0',
            'language' => 'required|string|in:english,malay,mix',
        ]);
        
        // Step 2: Save and parse the PDF
        $filePath = $request->file('document')->store('documents');
        $parser = new Parser();
        $pdf = $parser->parseFile(storage_path('app/' . $filePath));
        $text = $pdf->getText();
        
        // Step 3: Generate quiz JSON
        $singleChoiceCount = $request->input('single_choice_count', 0);
        $multipleChoiceCount = $request->input('multiple_choice_count', 0);
        $subjectiveCount = $request->input('subjective_count', 0);
        $language = $request->input('language', 'english');
        
        // Ensure we have at least one question
        $totalQuestions = $singleChoiceCount + $multipleChoiceCount + $subjectiveCount;
        if ($totalQuestions <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Please specify at least one question to generate.',
            ], 400);
        }
        
        $quizJSON = $this->generateFormBuilderJSON($text, $singleChoiceCount, $multipleChoiceCount, $subjectiveCount, $language);
        
        // Step 4: Return the quiz JSON
        return response()->json([
            'success' => true,
            'formBuilderJSON' => $quizJSON,
        ]);
        
    } catch (\Exception $e) {
        // Handle errors gracefully
        Log::error('Error generating quiz: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error generating quiz: ' . $e->getMessage(),
        ], 500);
    }
}

/**
 * Clears various Laravel caches
 * 
 * This method clears application cache, route cache, config cache, and view cache
 * to ensure fresh data on each quiz generation
 */
private function clearApplicationCache()
{
    try {
        // Clear application cache
        Artisan::call('cache:clear');
        Log::info('Application cache cleared');
        
        // Clear route cache
        Artisan::call('route:clear');
        Log::info('Route cache cleared');
        
        // Clear config cache
        Artisan::call('config:clear');
        Log::info('Config cache cleared');
        
        // Clear view cache
        Artisan::call('view:clear');
        Log::info('View cache cleared');
        
        return true;
    } catch (\Exception $e) {
        Log::error('Error clearing cache: ' . $e->getMessage());
        // Continue with the application even if cache clearing fails
        return false;
    }
}

private function generateFormBuilderJSON($text, $singleChoiceCount, $multipleChoiceCount, $subjectiveCount, $language) {
    $apiKey = env('OPENAI_API_KEY');
    $client = new Client();
    
    try {
        // Build the AI prompt with explicit instructions for question types
        $prompt = "Create a quiz JSON structure based on the following text. The quiz should include:\n";
        
        if ($singleChoiceCount > 0) {
            $prompt .= "- $singleChoiceCount single-choice questions\n";
        } else {
            $prompt .= "- No single-choice questions\n";
        }
        
        if ($multipleChoiceCount > 0) {
            $prompt .= "- $multipleChoiceCount multiple-choice questions\n";
        } else {
            $prompt .= "- No multiple-choice questions\n";
        }
        
        if ($subjectiveCount > 0) {
            $prompt .= "- $subjectiveCount subjective questions\n";
        } else {
            $prompt .= "- No subjective questions\n";
        }
        
        // Add language instruction
        $prompt .= "\nLANGUAGE REQUIREMENT:\n";
        switch ($language) {
            case 'malay':
                $prompt .= "- Generate all questions and answers in Malay (Bahasa Malaysia) only.\n";
                break;
            case 'mix':
                $prompt .= "- Generate a mix of questions in both English and Malay (Bahasa Malaysia).\n";
                $prompt .= "- Approximately half of the questions should be in English and half in Malay.\n";
                break;
            default: // english
                $prompt .= "- Generate all questions and answers in English only.\n";
                break;
        }
        
        // Add specific formatting instructions for answers
        $prompt .= "\nANSWER FORMAT REQUIREMENTS:\n";
        $prompt .= "1. For single-choice questions, provide the correct answer as a single string without any prefixes or labels.\n";
        $prompt .= "2. For multiple-choice questions, provide the correct answers as a comma-separated string without spaces (e.g., 'Option1,Option3,Option4').\n";
        $prompt .= "3. For subjective questions, provide a concise sample answer.\n\n";
        
        // Example JSON structure to guide the AI
        $prompt .= "Example JSON structure:\n";
        $prompt .= '{"quiz":{"questions":[';
        
        if ($singleChoiceCount > 0) {
            if ($language === 'malay') {
                $prompt .= '{"type":"single-choice","question":"Negeri manakah yang dikenali sebagai Negeri Bersejarah?","options":["Melaka","Pahang","Johor","Kedah"],"answer":"Melaka"}';
            } else {
                $prompt .= '{"type":"single-choice","question":"Which state is known as the Historic State?","options":["Melaka","Pahang","Johor","Kedah"],"answer":"Melaka"}';
            }
            if ($multipleChoiceCount > 0 || $subjectiveCount > 0) {
                $prompt .= ',';
            }
        }
        
        if ($multipleChoiceCount > 0) {
            if ($language === 'malay') {
                $prompt .= '{"type":"multiple-choice","question":"Manakah antara berikut adalah negeri di Malaysia?","options":["Melaka","Singapura","Pahang","Selangor"],"answer":"Melaka,Pahang,Selangor"}';
            } else {
                $prompt .= '{"type":"multiple-choice","question":"Which of the following are states in Malaysia?","options":["Melaka","Singapore","Pahang","Selangor"],"answer":"Melaka,Pahang,Selangor"}';
            }
            if ($subjectiveCount > 0) {
                $prompt .= ',';
            }
        }
        
        if ($subjectiveCount > 0) {
            if ($language === 'malay') {
                $prompt .= '{"type":"subjective","question":"Terangkan kepentingan Melaka dalam sejarah Malaysia.","answer":"Melaka merupakan pelabuhan perdagangan penting..."}';
            } else {
                $prompt .= '{"type":"subjective","question":"Explain the importance of Melaka in Malaysian history.","answer":"Melaka was an important trading port..."}';
            }
        }
        
        $prompt .= ']}}'."\n\n";
        
        $prompt .= "Here is the text to generate questions from:\n\n" . $text;
        
        // Send the request to OpenAI API
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo-1106', 
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a system designed to generate quiz questions for FormBuilder. Follow the format requirements exactly.' . 
                                    ($language === 'malay' ? ' You are fluent in Malay (Bahasa Malaysia).' : '') .
                                    ($language === 'mix' ? ' You are bilingual in English and Malay (Bahasa Malaysia).' : ''),
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 2000,
                'temperature' => 0.7,
            ],
        ]);
        
        // Parse the response
        $responseBody = json_decode($response->getBody(), true);
        
        if (isset($responseBody['choices'][0]['message']['content'])) {
            // Extract the JSON content from the response
            $jsonContent = $responseBody['choices'][0]['message']['content'];
            
            // Try to decode the JSON
            $decodedJson = json_decode($jsonContent, true);
            
            // Check if the JSON structure is valid
            if (!$decodedJson || !isset($decodedJson['quiz']) || !isset($decodedJson['quiz']['questions'])) {
                // If the structure isn't valid, create a simple one with the requested counts
                Log::warning('AI response did not contain valid quiz JSON structure. Creating default structure.');
                
                $defaultQuiz = ['quiz' => ['questions' => []]];
                
                // Return the default structure as JSON
                return json_encode($defaultQuiz);
            }
            
            // Log the actual question counts vs requested
            $questions = $decodedJson['quiz']['questions'];
            $actualSingleChoice = 0;
            $actualMultipleChoice = 0;
            $actualSubjective = 0;
            
            foreach ($questions as $question) {
                if (!isset($question['type'])) continue;
                
                if ($question['type'] === 'single-choice') $actualSingleChoice++;
                elseif ($question['type'] === 'multiple-choice') $actualMultipleChoice++;
                elseif ($question['type'] === 'subjective') $actualSubjective++;
            }
            
            Log::info("Question counts - Requested: $singleChoiceCount single, $multipleChoiceCount multiple, $subjectiveCount subjective. Received: $actualSingleChoice single, $actualMultipleChoice multiple, $actualSubjective subjective.");
            Log::info("Language requested: $language");
            
            // Return the JSON string as is - let the frontend handle filtering
            return $jsonContent;
        } else {
            throw new \Exception('Invalid AI response. No content found.');
        }
    } catch (\Exception $e) {
        Log::error('Error communicating with OpenAI: ' . $e->getMessage());
        throw new \Exception('Error communicating with AI: ' . $e->getMessage());
    }
}


}
