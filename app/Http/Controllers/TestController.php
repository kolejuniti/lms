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

class TestController extends Controller
{
    
    public function testlist()
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

        $data = DB::table('tblclasstest')
                ->where([
                    ['classid', Session::get('CourseIDS')],
                    ['sessionid', Session::get('SessionIDS')],
                    ['addby', $user->ic]
                ])->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclasstest_group')
                        ->join('user_subjek', 'tblclasstest_group.groupid', 'user_subjek.id')
                        ->where('tblclasstest_group.testid', $dt->id)->get();

                $chapter[] = DB::table('tblclasstest_chapter')
                        ->join('material_dir', 'tblclasstest_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclasstest_chapter.testid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.test', compact('data', 'group', 'chapter'));
    }

    public function testcreate()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseIDS');

        $sessionid = Session::get('SessionIDS');

        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->where([
            ['subjek.id', $courseid],
            ['user_subjek.session_id', $sessionid],
            ['user_subjek.user_ic', $user->ic]
        ])->groupBy('student_subjek.group_name')
        ->select('user_subjek.*', 'subjek.course_name', 'student_subjek.group_name')->get();

        //dd(Session::get('CourseIDS'));

        $folder = DB::table('lecturer_dir')
        ->where([
            ['CourseID', $courseid],
            ['SessionID', $sessionid],
            ['Addby', $user->ic]
            ])->get();

        //dd($folder);

        return view('lecturer.courseassessment.testcreate', compact(['group', 'folder']));
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

        $test = DB::table('student_subjek')
                ->join('tblclasstest_group', 'student_subjek.group_id', 'tblclasstest_group.groupid')
                ->join('tblclasstest', 'tblclasstest_group.testid', 'tblclasstest.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclasstest.id AS clssid', 'tblclasstest.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclasstest.classid', Session::get('CourseIDS')],
                    ['tblclasstest.sessionid', Session::get('SessionIDS')],
                    ['tblclasstest.id', $request->test],
                    ['tblclasstest.addby', $user->ic],
                    ['tblclasstest_group.groupid', $request->group]
                ])->get();
        
        //dd($test);

        foreach($test as $qz)
        {
            $statu[] = DB::table('tblclassstudenttest')
            ->where([
                ['testid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($test);

        return view('lecturer.courseassessment.getstatustest', compact('test', 'status'));

    }


    public function inserttest(Request $request){
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
            
        $testid = empty($request->test) ? '' : $request->test;

        
        if( !empty($testid) ){
            $q = DB::table('tblclasstest')->where('id', $testid)->update([
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
        }else{
            $q = DB::table('tblclasstest')->insertGetId([
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
                
                DB::table('tblclasstest_group')->insert([
                    "groupid" => $gp[0],
                    "groupname" => $gp[1],
                    "testid" => $q
                ]);
            }

            foreach($chapter as $chp)
            {
                DB::table('tblclasstest_chapter')->insert([
                    "chapterid" => $chp,
                    "testid" => $q
                ]);
            }
        }
        
        
        return true;
    }

    public function lecturerteststatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclasstest_group', 'user_subjek.id', 'tblclasstest_group.groupid')
                ->join('tblclasstest', 'tblclasstest_group.testid', 'tblclasstest.id')
                ->where([
                    ['tblclasstest.classid', Session::get('CourseIDS')],
                    ['tblclasstest.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclasstest.id', request()->test]
                ])->get();
                
            
        //dd($group);

        $test = DB::table('student_subjek')
                ->join('tblclasstest_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclasstest_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclasstest_group.groupname');
                })
                ->join('tblclasstest', 'tblclasstest_group.testid', 'tblclasstest.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclasstest.id AS clssid', 'tblclasstest.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclasstest.classid', Session::get('CourseIDS')],
                    ['tblclasstest.sessionid', Session::get('SessionIDS')],
                    ['tblclasstest.id', request()->test],
                    ['tblclasstest.addby', $user->ic]
                ])->get();
        
        
        
        //dd($test);

        foreach($test as $qz)
        {
            $status[] = DB::table('tblclassstudenttest')
            ->where([
                ['testid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($test);

        return view('lecturer.courseassessment.teststatus', compact('test', 'status', 'group'));

    }

    public function testresult(Request $request){
        
        $id = $request->testid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $test = DB::table('tblclassstudenttest')
            ->join('tblclasstest', 'tblclassstudenttest.testid', 'tblclasstest.id')
            ->leftJoin('students', 'tblclassstudenttest.userid', 'students.ic')
            ->select('tblclassstudenttest.*', 'tblclassstudenttest.testid', 'tblclasstest.title',  
                DB::raw('tblclasstest.content as original_content'), 
                'tblclasstest.questionindex',
                'tblclassstudenttest.userid',
                'tblclassstudenttest.submittime',
                DB::raw('tblclassstudenttest.status as studentteststatus'),
                'tblclasstest.duration','students.name')
            ->where('tblclassstudenttest.testid', $id)
            ->where('tblclassstudenttest.userid', $userid)->get()->first();
       
        $testformdata = json_decode($test->content)->formData;
        $original_testformdata = json_decode($test->original_content)->formData;
        

        $gain_mark = false;
        $correct_label = " <i style='font-size:1.5em' class='fa fa-check text-success'></i>";
        $incorrect_label = " <i style='font-size:1.5em' class='fa fa-close text-danger'></i>";

        foreach($original_testformdata as $index => $q){

        //$radio = "radio-question".$count+1;
        //dd($radio);

            if(!empty($original_testformdata[$index]->name) ){

                if($original_testformdata[$index]->name == "radio-question".$count){
                    $i =0;
                    $correct_answer = $original_testformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($testformdata[$index]->values as $v){
                        
                        if(in_array($v->value, $correct_answer)){
                            $testformdata[$index]->values[$i]->label = $original_testformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $testformdata[$index]->values[$i]->label = $original_testformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                        $count+1;
                    }

                    $userData = !empty($testformdata[$index]->userData[0]) ? $testformdata[$index]->userData[0] : null;

                    if(in_array($userData, $correct_answer)){
                        $gain_mark = true;
                    }
                    
                }
                
                if($original_testformdata[$index]->name == "checkbox-question".$count){
                    $i =0;
                    $correct_answer = $original_testformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($testformdata[$index]->values as $v){
                        if(in_array($v->value, $correct_answer)){
                            $testformdata[$index]->values[$i]->label = $original_testformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $testformdata[$index]->values[$i]->label = $original_testformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                        $count+1;
                    }
                    
                    $userData = !empty($testformdata[$index]->userData) ? $testformdata[$index]->userData : null;

                    if( count( array_diff_assoc($correct_answer, $userData) )  == 0){
                        $gain_mark = true;
                    }
                
                }
            }

            if(!empty($original_testformdata[$index]->className) ){
                
                if(str_contains($original_testformdata[$index]->className, "feedback-text")){
                    $testformdata[$index] = $q;
                  
                    $testformdata[$index]->type = "paragraph";

                    if(!empty($q->userData[0])){
                        $testformdata[$index]->label = $q->userData[0];
                    }else{
                        $testformdata[$index]->label = " ";
                    }
                    $testformdata[$index]->className = "bg-red mb-4 text-danger";
                }

                if(str_contains($original_testformdata[$index]->className, "collected-marks")){

                    $mark_label           = $original_testformdata[$index]->values[0]->label;
                    $mark                 = $original_testformdata[$index]->values[0]->value;
                    
                    //if result is published then use graded data
                    if($test->studentteststatus == 3){
                        $graded_data = empty($testformdata[$index]->userData[0]) ? "" : $testformdata[$index]->userData[0];

                        if($graded_data == $mark){
                            $testformdata[$index]->values[0]->selected = true;
                        }else{
                            $testformdata[$index]->values[0]->selected = false;
                        }
                    }else{
                        //auto correct answer on mcq by matching user answer with original answer
                        $testformdata[$index] = $original_testformdata[$index];

                        if($gain_mark){
                            $testformdata[$index]->values[0]->selected = true;
                        }else{
                            $testformdata[$index]->values[0]->selected = false;
                        }
                        
                        $gain_mark = false;
                    }
                }
            }
        }

       
        $data['test'] = $testformdata;
        $data['comments'] = $test->comments;
        $data['testid'] = $test->testid;
        $data['testtitle'] = $test->title;
        $data['testduration'] = $test->duration;
        $data['testuserid'] = $test->userid;
        $data['fullname'] = $test->name;
        $data['created_at'] = $test->created_at;
        $data['updated_at'] = $test->updated_at;
        $data['submittime'] = $test->submittime;
        $data['questionindex'] = $test->questionindex;
        $data['studentteststatus'] = $test->studentteststatus;

        return view('lecturer.courseassessment.testresult', compact('data'));
    }

    public function updatetestresult(Request $request){
        $test = $request->test;
        $participant = $request->participant;
        $final_mark = $request->final_mark;
        $comments = $request->comments;
        //$total_mark = $request->total_mark;
        $data = $request->data;

      
        $q = \DB::table('tblclassstudenttest')
            ->where('testid', $test)
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




    //This is Student test Controller//

    public function studenttestlist()
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

        $data = DB::table('tblclasstest')
                ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                ->join('student_subjek', function($join){
                    $join->on('tblclasstest_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclasstest_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclasstest_group.groupid', 'user_subjek.id')
                ->select('tblclasstest.*', 'tblclasstest_group.groupname')
                ->where([
                    ['tblclasstest.classid', Session::get('CourseIDS')],
                    ['tblclasstest.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic]
                ])->get();

        //dd($data);

        foreach($data as $dt)
        {
            $chapter[] = DB::table('tblclasstest_chapter')
                      ->join('material_dir', 'tblclasstest_chapter.chapterid', 'material_dir.DrID')
                      ->where('tblclasstest_chapter.testid', $dt->id)->get();
        }

        return view('student.courseassessment.test', compact('data', 'chapter'));

    }

    public function studentteststatus()
    {
        $test = DB::table('student_subjek')
                ->join('tblclasstest_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclasstest_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclasstest_group.groupname');
                })
                ->join('tblclasstest', 'tblclasstest_group.testid', 'tblclasstest.id')
                ->where([
                    ['tblclasstest.classid', Session::get('CourseIDS')],
                    ['tblclasstest.sessionid', Session::get('SessionIDS')],
                    ['tblclasstest.id', request()->test],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();

        //dd($test);

        foreach($test as $qz)
        {
            $status[] = DB::table('tblclassstudenttest')
            ->where([
                ['testid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.teststatus', compact('test', 'status'));
    }

    public function testview(Request $request){

        $id = $request->test;
        $test = DB::table('tblclasstest')
            ->leftjoin('tblclassstudenttest', function($join) 
            {
                $join->on('tblclasstest.id', '=', 'tblclassstudenttest.testid');
                $join->on('tblclassstudenttest.userid',  '=', DB::raw(Session::get('StudInfos')->ic));
            })
            ->leftJoin('students', 'tblclassstudenttest.userid', 'students.ic')
            ->leftJoin('tblclassteststatus', 'tblclasstest.status', 'tblclassteststatus.id')
            ->select('tblclasstest.*', 'tblclassstudenttest.userid', 'tblclassstudenttest.testid','students.name', 
                DB::raw('tblclasstest.status as classteststatus'),
                DB::raw('tblclassstudenttest.status as studentteststatus'), 'tblclassstudenttest.endtime', 'tblclassstudenttest.starttime' , 
                DB::raw('TIMESTAMPDIFF(SECOND, now(), endtime) as timeleft'),
                DB::raw('tblclassstudenttest.content as studenttestcontent')
            )
            ->where('tblclasstest.id', $id)
            ->get()->first();

        //dd($test);

        $testformdata = json_decode($test->content)->formData;

        if(!empty($test->studenttestcontent)){
            $testformdata = json_decode($test->studenttestcontent)->formData;
        }

        foreach($testformdata as $index => $v){

            if(!empty($testformdata[$index]->className) ){
                if(str_contains($testformdata[$index]->className, "collected-marks")){
                    $testformdata[$index]->type = "paragraph";
                    $testformdata[$index]->label = $testformdata[$index]->values[0]->label;
                }

                if(str_contains($testformdata[$index]->className, "correct-answer")){
                    $testformdata[$index]->className = "correct-answer d-none";
                    unset($testformdata[$index]->label);
                }

                if(str_contains($testformdata[$index]->className, "feedback-text")){
                    $testformdata[$index]->className = "feedback-text d-none";
                    unset($testformdata[$index]->label);
                }
            }
        }

        if($test->classteststatus == 2){
            if($test->studentteststatus == 2 || $test->studentteststatus == 3){
                //completed test
                return redirect('/academics/test/'.$test->testid.'/result');
            }else{
                $data['test'] = json_encode($testformdata );
                $data['testid'] = $test->id;
                $data['testtitle'] = $test->title;
                $data['testduration'] = $test->duration;
                $data['testendduration'] = $test->date_to;
                $data['fullname'] = $test->name;
                $data['created_at'] = $test->created_at;
                $data['updated_at'] = $test->updated_at;
                $data['teststarttime'] = $test->starttime;
                $data['testendtime'] = $test->endtime;
                $data['testtimeleft'] = $test->timeleft;
        
                return view('student.courseassessment.testanswer', compact('data'));
            }
        }else{
            return "test is not published yet";
        }
    }

    public function starttest(Request $request){
        $test = $request->test;
        $data = $request->data;
        
        $testduration = DB::table('tblclasstest')->select('duration')->where('id', $test)->first()->duration;
        
        try{
            DB::beginTransaction();
            $q =  DB::table('tblclassstudenttest')->insert([
                "userid" =>  Session::get('StudInfos')->ic,
                "testid" => $test,
                "content" => $data,
                "starttime" =>  DB::raw('now()'),
                "endtime" => DB::raw('now() + INTERVAL '.$testduration.' MINUTE'),
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

    public function savetest(Request $request){
        $data = $request->data;
        $testid = $request->test;


        $q = DB::table('tblclassstudenttest')->where('status', 1)->where('testid',$testid)->where('userid', Session::get('StudInfos')->ic)->update([
            "content" => $data
        ]);

        $q = ($q == 1) ? true : false;

        return $q;
     
    }

    public function submittest(Request $request){
        $data = $request->data;
        $id = $request->id;

        $test = DB::table('tblclasstest')
            ->leftjoin('tblclassstudenttest', function($join) 
            {
                $join->on('tblclasstest.id', '=', 'tblclassstudenttest.testid');
                $join->on('tblclassstudenttest.userid',  '=', DB::raw(Session::get('StudInfos')->ic));
            })
            ->select('tblclasstest.*', 'tblclassstudenttest.userid', DB::raw('tblclassstudenttest.status as studentteststatus'),
             'tblclassstudenttest.testid')
            ->where('tblclasstest.id', $id)
            ->get()->first();

        if($test->studentteststatus == 2 || $test->studentteststatus == 3){
            return ["status"=>false, "message" =>"Sorry, you have completed the test before."];
        }

        $q = DB::table('tblclassstudenttest')->upsert([
            "userid" => Session::get('StudInfos')->ic,
            "testid" => $id,
            "submittime" => DB::raw('now()'),
            "content" => $data,
            "status" => 2
        ],['userid', 'testid']);

        return ["status"=>true, "message" =>$data];
     
    }

    public function testresultstd(Request $request){
        
        $id = $request->testid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $test = DB::table('tblclassstudenttest')
            ->join('tblclasstest', 'tblclassstudenttest.testid', 'tblclasstest.id')
            ->leftJoin('students', 'tblclassstudenttest.userid', 'students.ic')
            ->select('tblclassstudenttest.*', 'tblclassstudenttest.testid', 'tblclasstest.title',  
                DB::raw('tblclasstest.content as original_content'), 
                'tblclasstest.questionindex',
                'tblclassstudenttest.userid',
                'tblclassstudenttest.submittime',
                DB::raw('tblclassstudenttest.status as studentteststatus'),
                'tblclasstest.duration','students.name')
            ->where('tblclassstudenttest.testid', $id)
            ->where('tblclassstudenttest.userid', $userid)->get()->first();
       
        $testformdata = json_decode($test->content)->formData;
        $original_testformdata = json_decode($test->original_content)->formData;
        

        $gain_mark = false;
        $correct_label = " <i style='font-size:1.5em' class='fa fa-check text-success'></i>";
        $incorrect_label = " <i style='font-size:1.5em' class='fa fa-close text-danger'></i>";

        foreach($original_testformdata as $index => $q){

        //$radio = "radio-question".$count+1;
        //dd($radio);

            if(!empty($original_testformdata[$index]->name) ){

                if($original_testformdata[$index]->name == "radio-question".$count){
                    $i =0;
                    $correct_answer = $original_testformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($testformdata[$index]->values as $v){
                        
                        if(in_array($v->value, $correct_answer)){
                            $testformdata[$index]->values[$i]->label = $original_testformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $testformdata[$index]->values[$i]->label = $original_testformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                        $count+1;
                    }

                    $userData = !empty($testformdata[$index]->userData[0]) ? $testformdata[$index]->userData[0] : null;

                    if(in_array($userData, $correct_answer)){
                        $gain_mark = true;
                    }
                    
                }
                
                if($original_testformdata[$index]->name == "checkbox-question".$count){
                    $i =0;
                    $correct_answer = $original_testformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($testformdata[$index]->values as $v){
                        if(in_array($v->value, $correct_answer)){
                            $testformdata[$index]->values[$i]->label = $original_testformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $testformdata[$index]->values[$i]->label = $original_testformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                        $count+1;
                    }
                    
                    $userData = !empty($testformdata[$index]->userData) ? $testformdata[$index]->userData : null;

                    if( count( array_diff_assoc($correct_answer, $userData) )  == 0){
                        $gain_mark = true;
                    }
                
                }
            }

            if(!empty($original_testformdata[$index]->className) ){
                
                if(str_contains($original_testformdata[$index]->className, "feedback-text")){
                    $testformdata[$index] = $q;
                  
                    $testformdata[$index]->type = "paragraph";

                    if(!empty($q->userData[0])){
                        $testformdata[$index]->label = $q->userData[0];
                    }else{
                        $testformdata[$index]->label = " ";
                    }
                    $testformdata[$index]->className = "bg-red mb-4 text-danger";
                }

                if(str_contains($original_testformdata[$index]->className, "collected-marks")){

                    $mark_label           = $original_testformdata[$index]->values[0]->label;
                    $mark                 = $original_testformdata[$index]->values[0]->value;
                    
                    //if result is published then use graded data
                    if($test->studentteststatus == 3){
                        $graded_data = empty($testformdata[$index]->userData[0]) ? "" : $testformdata[$index]->userData[0];

                        if($graded_data == $mark){
                            $testformdata[$index]->values[0]->selected = true;
                        }else{
                            $testformdata[$index]->values[0]->selected = false;
                        }
                    }else{
                        //auto correct answer on mcq by matching user answer with original answer
                        $testformdata[$index] = $original_testformdata[$index];

                        if($gain_mark){
                            $testformdata[$index]->values[0]->selected = true;
                        }else{
                            $testformdata[$index]->values[0]->selected = false;
                        }
                        
                        $gain_mark = false;
                    }
                }
            }
        }

       
        $data['test'] = $testformdata;
        $data['comments'] = $test->comments;

        $data['testid'] = $test->testid;
        $data['testtitle'] = $test->title;
        $data['testduration'] = $test->duration;
        $data['testuserid'] = $test->userid;
        $data['fullname'] = $test->name;
        $data['created_at'] = $test->created_at;
        $data['updated_at'] = $test->updated_at;
        $data['submittime'] = $test->submittime;
        $data['questionindex'] = $test->questionindex;
        $data['studentteststatus'] = $test->studentteststatus;

        return view('student.courseassessment.testresult', compact('data'));
    }
}
