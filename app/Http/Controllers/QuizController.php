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
                    <label>'.$sub->DrName.'</label>
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
        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');
        $duration = $request->duration;
        $title = $request->title;
        $from = $request->from;
        $to = $request->to;
        $questionindex = $request->questionindex;
        $status = $request->status;
        $group = $request->group;
        $chapter = $request->chapter;
        $marks = $request->marks;

        $user = Auth::user();
            
        $quizid = empty($request->quiz) ? '' : $request->quiz;

        $statusReuse = empty($request->reuse) ? '' : $request->reuse;

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
                ->select('student_subjek.*', 'tblclassquiz.id AS clssid', 'tblclassquiz.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', request()->quiz],
                    ['tblclassquiz.addby', $user->ic]
                ])->orderBy('students.name')->get();
        
        
        
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

    public function quizresult(Request $request){
        
        $id = $request->quizid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

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

        //$radio = "radio-question".$count+1;
        //dd($radio);

            if(!empty($original_quizformdata[$index]->name) ){

                if($original_quizformdata[$index]->name == "radio-question".$count){
                    $i =0;
                    $correct_answer = $original_quizformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($quizformdata[$index]->values as $v){
                        
                        if(in_array($v->value, $correct_answer)){
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }

                    $userData = !empty($quizformdata[$index]->userData[0]) ? $quizformdata[$index]->userData[0] : null;

                    if(in_array($userData, $correct_answer)){
                        $gain_mark = true;
                    }
                    $count++;
                    
                }
                
                if($original_quizformdata[$index]->name == "checkbox-question".$count){
                    $i =0;
                    $correct_answer = $original_quizformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($quizformdata[$index]->values as $v){
                        if(in_array($v->value, $correct_answer)){
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }
                    
                    $userData = !empty($quizformdata[$index]->userData) ? $quizformdata[$index]->userData : null;

                    if( count( array_diff_assoc($correct_answer, $userData) )  == 0){
                        $gain_mark = true;
                    }
                    $count++;
                
                }
            }

            if(!empty($original_quizformdata[$index]->className) ){
                
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

                if(str_contains($original_quizformdata[$index]->className, "collected-marks")){

                    $mark_label           = $original_quizformdata[$index]->values[0]->label;
                    $mark                 = $original_quizformdata[$index]->values[0]->value;
                    
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

      
        $q = \DB::table('tblclassstudentquiz')
            ->where('quizid', $quiz)
            ->where("userid", $participant)
            ->update([
                "content" => $data,
                "final_mark" => $final_mark,
                //"total_mark" => $total_mark,
                "comments" => $comments,
                "status" => 3
            ]);
        
        return true;
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

        //dd($student->ic);

        $data = DB::table('tblclassquiz')
                ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                ->join('student_subjek', function($join){
                    $join->on('tblclassquiz_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassquiz_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassquiz_group.groupid', 'user_subjek.id')
                ->select('tblclassquiz.*', 'tblclassquiz_group.groupname')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
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
        $quiz = DB::table('student_subjek')
                ->join('tblclassquiz_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassquiz_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassquiz_group.groupname');
                })
                ->join('tblclassquiz', 'tblclassquiz_group.quizid', 'tblclassquiz.id')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
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

        //dd(str_replace('"', '', Session::get('StudInfos')->ic));

        //dd(Session::get('StudInfo')->ic)

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

        

        //dd($quiz);

        $quizformdata = json_decode($quiz->content)->formData;

        if(!empty($quiz->studentquizcontent)){
            $quizformdata = json_decode($quiz->studentquizcontent)->formData;
        }

        foreach($quizformdata as $index => $v){

            if(!empty($quizformdata[$index]->className) ){
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

        //dd($id);

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

        //dd($quiz);
       
        $quizformdata = json_decode($quiz->content)->formData;
        $original_quizformdata = json_decode($quiz->original_content)->formData;

        $gain_mark = false;
        $correct_label = " <i style='font-size:1.5em' class='fa fa-check text-success'></i>";
        $incorrect_label = " <i style='font-size:1.5em' class='fa fa-close text-danger'></i>";

        foreach($original_quizformdata as $index => $q){

        //$radio = "radio-question".$count+1;
        //dd($radio);

            if(!empty($original_quizformdata[$index]->name) ){

                if($original_quizformdata[$index]->name == "radio-question".$count){
                    $i =0;
                    $correct_answer = $original_quizformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($quizformdata[$index]->values as $v){
                        
                        if(in_array($v->value, $correct_answer)){
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }

                    $userData = !empty($quizformdata[$index]->userData[0]) ? $quizformdata[$index]->userData[0] : null;

                    if(in_array($userData, $correct_answer)){
                        $gain_mark = true;
                    }
                    $count++;
                    
                }
                
                if($original_quizformdata[$index]->name == "checkbox-question".$count){
                    $i =0;
                    $correct_answer = $original_quizformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($quizformdata[$index]->values as $v){
                        if(in_array($v->value, $correct_answer)){
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $quizformdata[$index]->values[$i]->label = $original_quizformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }
                    
                    $userData = !empty($quizformdata[$index]->userData) ? $quizformdata[$index]->userData : null;

                    if( count( array_diff_assoc($correct_answer, $userData) )  == 0){
                        $gain_mark = true;
                    }
                    $count++;
                
                }
            }

            if(!empty($original_quizformdata[$index]->className) ){
                
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

                if(str_contains($original_quizformdata[$index]->className, "collected-marks")){

                    $mark_label           = $original_quizformdata[$index]->values[0]->label;
                    $mark                 = $original_quizformdata[$index]->values[0]->value;
                    
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

        //dd($data);

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
        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');
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
                ->select('student_subjek.*', 'tblclassquiz.id AS clssid', 'tblclassquiz.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
                    ['tblclassquiz.sessionid', Session::get('SessionIDS')],
                    ['tblclassquiz.id', request()->quiz],
                    ['tblclassquiz.addby', $user->ic]
                ])->orderBy('students.name')->get();
        
        
        
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
            array_push($upsert, [
            'userid' => $ics[$key],
            'quizid' => $quizid,
            'submittime' => date("Y-m-d H:i:s"),
            'final_mark' => $mrk,
            'status' => 1
            ]);
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
                    ['tblclassquiz.classid', Session::get('CourseIDS')],
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
}
