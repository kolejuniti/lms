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

class FinalController extends Controller
{
    
    public function finallist()
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

        $data = DB::table('tblclassfinal')
                ->where([
                    ['classid', Session::get('CourseIDS')],
                    ['sessionid', Session::get('SessionIDS')],
                    ['addby', $user->ic]
                ])->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassfinal_group')
                        ->join('user_subjek', 'tblclassfinal_group.groupid', 'user_subjek.id')
                        ->where('tblclassfinal_group.finalid', $dt->id)->get();

                $chapter[] = DB::table('tblclassfinal_chapter')
                        ->join('material_dir', 'tblclassfinal_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassfinal_chapter.finalid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.final', compact('data', 'group', 'chapter'));
    }

    public function finalcreate()
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

        return view('lecturer.courseassessment.finalcreate', compact(['group', 'folder']));
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

        $final = DB::table('student_subjek')
                ->join('tblclassfinal_group', 'student_subjek.group_id', 'tblclassfinal_group.groupid')
                ->join('tblclassfinal', 'tblclassfinal_group.finalid', 'tblclassfinal.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassfinal.id AS clssid', 'tblclassfinal.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassfinal.classid', Session::get('CourseIDS')],
                    ['tblclassfinal.sessionid', Session::get('SessionIDS')],
                    ['tblclassfinal.id', $request->final],
                    ['tblclassfinal.addby', $user->ic],
                    ['tblclassfinal_group.groupid', $request->group]
                ])->get();
        
        //dd($final);

        foreach($final as $qz)
        {
            $statu[] = DB::table('tblclassstudentfinal')
            ->where([
                ['finalid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($final);

        return view('lecturer.courseassessment.getstatusfinal', compact('final', 'status'));

    }


    public function insertfinal(Request $request){
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
            
        $finalid = empty($request->final) ? '' : $request->final;

        
        if( !empty($finalid) ){
            $q = DB::table('tblclassfinal')->where('id', $finalid)->update([
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
            $q = DB::table('tblclassfinal')->insertGetId([
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
                
                DB::table('tblclassfinal_group')->insert([
                    "groupid" => $gp[0],
                    "groupname" => $gp[1],
                    "finalid" => $q
                ]);
            }

            foreach($chapter as $chp)
            {
                DB::table('tblclassfinal_chapter')->insert([
                    "chapterid" => $chp,
                    "finalid" => $q
                ]);
            }
        }
        
        
        return true;
    }

    public function lecturerfinalstatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassfinal_group', 'user_subjek.id', 'tblclassfinal_group.groupid')
                ->join('tblclassfinal', 'tblclassfinal_group.finalid', 'tblclassfinal.id')
                ->where([
                    ['tblclassfinal.classid', Session::get('CourseIDS')],
                    ['tblclassfinal.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassfinal.id', request()->final]
                ])->get();
                
            
        //dd($group);

        $final = DB::table('student_subjek')
                ->join('tblclassfinal_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassfinal_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassfinal_group.groupname');
                })
                ->join('tblclassfinal', 'tblclassfinal_group.finalid', 'tblclassfinal.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassfinal.id AS clssid', 'tblclassfinal.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassfinal.classid', Session::get('CourseIDS')],
                    ['tblclassfinal.sessionid', Session::get('SessionIDS')],
                    ['tblclassfinal.id', request()->final],
                    ['tblclassfinal.addby', $user->ic]
                ])->get();
        
        
        
        //dd($final);

        foreach($final as $qz)
        {
            $status[] = DB::table('tblclassstudentfinal')
            ->where([
                ['finalid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($final);

        return view('lecturer.courseassessment.finalstatus', compact('final', 'status', 'group'));

    }

    public function finalresult(Request $request){
        
        $id = $request->finalid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $final = DB::table('tblclassstudentfinal')
            ->join('tblclassfinal', 'tblclassstudentfinal.finalid', 'tblclassfinal.id')
            ->leftJoin('students', 'tblclassstudentfinal.userid', 'students.ic')
            ->select('tblclassstudentfinal.*', 'tblclassstudentfinal.finalid', 'tblclassfinal.title',  
                DB::raw('tblclassfinal.content as original_content'), 
                'tblclassfinal.questionindex',
                'tblclassstudentfinal.userid',
                'tblclassstudentfinal.submittime',
                DB::raw('tblclassstudentfinal.status as studentfinalstatus'),
                'tblclassfinal.duration','students.name')
            ->where('tblclassstudentfinal.finalid', $id)
            ->where('tblclassstudentfinal.userid', $userid)->get()->first();
       
        $finalformdata = json_decode($final->content)->formData;
        $original_finalformdata = json_decode($final->original_content)->formData;
        

        $gain_mark = false;
        $correct_label = " <i style='font-size:1.5em' class='fa fa-check text-success'></i>";
        $incorrect_label = " <i style='font-size:1.5em' class='fa fa-close text-danger'></i>";

        foreach($original_finalformdata as $index => $q){

        //$radio = "radio-question".$count+1;
        //dd($radio);

            if(!empty($original_finalformdata[$index]->name) ){

                if($original_finalformdata[$index]->name == "radio-question".$count){
                    $i =0;
                    $correct_answer = $original_finalformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($finalformdata[$index]->values as $v){
                        
                        if(in_array($v->value, $correct_answer)){
                            $finalformdata[$index]->values[$i]->label = $original_finalformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $finalformdata[$index]->values[$i]->label = $original_finalformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                        $count+1;
                    }

                    $userData = !empty($finalformdata[$index]->userData[0]) ? $finalformdata[$index]->userData[0] : null;

                    if(in_array($userData, $correct_answer)){
                        $gain_mark = true;
                    }
                    
                }
                
                if($original_finalformdata[$index]->name == "checkbox-question".$count){
                    $i =0;
                    $correct_answer = $original_finalformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($finalformdata[$index]->values as $v){
                        if(in_array($v->value, $correct_answer)){
                            $finalformdata[$index]->values[$i]->label = $original_finalformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $finalformdata[$index]->values[$i]->label = $original_finalformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                        $count+1;
                    }
                    
                    $userData = !empty($finalformdata[$index]->userData) ? $finalformdata[$index]->userData : null;

                    if( count( array_diff_assoc($correct_answer, $userData) )  == 0){
                        $gain_mark = true;
                    }
                
                }
            }

            if(!empty($original_finalformdata[$index]->className) ){
                
                if(str_contains($original_finalformdata[$index]->className, "feedback-text")){
                    $finalformdata[$index] = $q;
                  
                    $finalformdata[$index]->type = "paragraph";

                    if(!empty($q->userData[0])){
                        $finalformdata[$index]->label = $q->userData[0];
                    }else{
                        $finalformdata[$index]->label = " ";
                    }
                    $finalformdata[$index]->className = "bg-red mb-4 text-danger";
                }

                if(str_contains($original_finalformdata[$index]->className, "collected-marks")){

                    $mark_label           = $original_finalformdata[$index]->values[0]->label;
                    $mark                 = $original_finalformdata[$index]->values[0]->value;
                    
                    //if result is published then use graded data
                    if($final->studentfinalstatus == 3){
                        $graded_data = empty($finalformdata[$index]->userData[0]) ? "" : $finalformdata[$index]->userData[0];

                        if($graded_data == $mark){
                            $finalformdata[$index]->values[0]->selected = true;
                        }else{
                            $finalformdata[$index]->values[0]->selected = false;
                        }
                    }else{
                        //auto correct answer on mcq by matching user answer with original answer
                        $finalformdata[$index] = $original_finalformdata[$index];

                        if($gain_mark){
                            $finalformdata[$index]->values[0]->selected = true;
                        }else{
                            $finalformdata[$index]->values[0]->selected = false;
                        }
                        
                        $gain_mark = false;
                    }
                }
            }
        }

       
        $data['final'] = $finalformdata;
        $data['comments'] = $final->comments;
        $data['finalid'] = $final->finalid;
        $data['finaltitle'] = $final->title;
        $data['finalduration'] = $final->duration;
        $data['finaluserid'] = $final->userid;
        $data['fullname'] = $final->name;
        $data['created_at'] = $final->created_at;
        $data['updated_at'] = $final->updated_at;
        $data['submittime'] = $final->submittime;
        $data['questionindex'] = $final->questionindex;
        $data['studentfinalstatus'] = $final->studentfinalstatus;

        return view('lecturer.courseassessment.finalresult', compact('data'));
    }

    public function updatefinalresult(Request $request){
        $final = $request->final;
        $participant = $request->participant;
        $final_mark = $request->final_mark;
        $comments = $request->comments;
        //$total_mark = $request->total_mark;
        $data = $request->data;

      
        $q = \DB::table('tblclassstudentfinal')
            ->where('finalid', $final)
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




    //This is Student final Controller//

    public function studentfinallist()
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

        $data = DB::table('tblclassfinal')
                ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                ->join('student_subjek', function($join){
                    $join->on('tblclassfinal_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassfinal_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassfinal_group.groupid', 'user_subjek.id')
                ->select('tblclassfinal.*', 'tblclassfinal_group.groupname')
                ->where([
                    ['tblclassfinal.classid', Session::get('CourseIDS')],
                    ['tblclassfinal.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic]
                ])->get();

        //dd($data);

        foreach($data as $dt)
        {
            $chapter[] = DB::table('tblclassfinal_chapter')
                      ->join('material_dir', 'tblclassfinal_chapter.chapterid', 'material_dir.DrID')
                      ->where('tblclassfinal_chapter.finalid', $dt->id)->get();
        }

        return view('student.courseassessment.final', compact('data', 'chapter'));

    }

    public function studentfinalstatus()
    {
        $final = DB::table('student_subjek')
                ->join('tblclassfinal_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassfinal_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassfinal_group.groupname');
                })
                ->join('tblclassfinal', 'tblclassfinal_group.finalid', 'tblclassfinal.id')
                ->where([
                    ['tblclassfinal.classid', Session::get('CourseIDS')],
                    ['tblclassfinal.sessionid', Session::get('SessionIDS')],
                    ['tblclassfinal.id', request()->final],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();

        //dd($final);

        foreach($final as $qz)
        {
            $status[] = DB::table('tblclassstudentfinal')
            ->where([
                ['finalid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.finalstatus', compact('final', 'status'));
    }

    public function finalview(Request $request){

        $id = $request->final;
        $final = DB::table('tblclassfinal')
            ->leftjoin('tblclassstudentfinal', function($join) 
            {
                $join->on('tblclassfinal.id', '=', 'tblclassstudentfinal.finalid');
                $join->on('tblclassstudentfinal.userid',  '=', DB::raw(Session::get('StudInfos')->ic));
            })
            ->leftJoin('students', 'tblclassstudentfinal.userid', 'students.ic')
            ->leftJoin('tblclassfinalstatus', 'tblclassfinal.status', 'tblclassfinalstatus.id')
            ->select('tblclassfinal.*', 'tblclassstudentfinal.userid', 'tblclassstudentfinal.finalid','students.name', 
                DB::raw('tblclassfinal.status as classfinalstatus'),
                DB::raw('tblclassstudentfinal.status as studentfinalstatus'), 'tblclassstudentfinal.endtime', 'tblclassstudentfinal.starttime' , 
                DB::raw('TIMESTAMPDIFF(SECOND, now(), endtime) as timeleft'),
                DB::raw('tblclassstudentfinal.content as studentfinalcontent')
            )
            ->where('tblclassfinal.id', $id)
            ->get()->first();

        //dd($final);

        $finalformdata = json_decode($final->content)->formData;

        if(!empty($final->studentfinalcontent)){
            $finalformdata = json_decode($final->studentfinalcontent)->formData;
        }

        foreach($finalformdata as $index => $v){

            if(!empty($finalformdata[$index]->className) ){
                if(str_contains($finalformdata[$index]->className, "collected-marks")){
                    $finalformdata[$index]->type = "paragraph";
                    $finalformdata[$index]->label = $finalformdata[$index]->values[0]->label;
                }

                if(str_contains($finalformdata[$index]->className, "correct-answer")){
                    $finalformdata[$index]->className = "correct-answer d-none";
                    unset($finalformdata[$index]->label);
                }

                if(str_contains($finalformdata[$index]->className, "feedback-text")){
                    $finalformdata[$index]->className = "feedback-text d-none";
                    unset($finalformdata[$index]->label);
                }
            }
        }

        if($final->classfinalstatus == 2){
            if($final->studentfinalstatus == 2 || $final->studentfinalstatus == 3){
                //completed final
                return redirect('/academics/final/'.$final->finalid.'/result');
            }else{
                $data['final'] = json_encode($finalformdata );
                $data['finalid'] = $final->id;
                $data['finaltitle'] = $final->title;
                $data['finalduration'] = $final->duration;
                $data['finalendduration'] = $final->date_to;
                $data['fullname'] = $final->name;
                $data['created_at'] = $final->created_at;
                $data['updated_at'] = $final->updated_at;
                $data['finalstarttime'] = $final->starttime;
                $data['finalendtime'] = $final->endtime;
                $data['finaltimeleft'] = $final->timeleft;
        
                return view('student.courseassessment.finalanswer', compact('data'));
            }
        }else{
            return "final is not published yet";
        }
    }

    public function startfinal(Request $request){
        $final = $request->final;
        $data = $request->data;
        
        $finalduration = DB::table('tblclassfinal')->select('duration')->where('id', $final)->first()->duration;
        
        try{
            DB::beginTransaction();
            $q =  DB::table('tblclassstudentfinal')->insert([
                "userid" =>  Session::get('StudInfos')->ic,
                "finalid" => $final,
                "content" => $data,
                "starttime" =>  DB::raw('now()'),
                "endtime" => DB::raw('now() + INTERVAL '.$finalduration.' MINUTE'),
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

    public function savefinal(Request $request){
        $data = $request->data;
        $finalid = $request->final;


        $q = DB::table('tblclassstudentfinal')->where('status', 1)->where('finalid',$finalid)->where('userid', Session::get('StudInfos')->ic)->update([
            "content" => $data
        ]);

        $q = ($q == 1) ? true : false;

        return $q;
     
    }

    public function submitfinal(Request $request){
        $data = $request->data;
        $id = $request->id;

        $final = DB::table('tblclassfinal')
            ->leftjoin('tblclassstudentfinal', function($join) 
            {
                $join->on('tblclassfinal.id', '=', 'tblclassstudentfinal.finalid');
                $join->on('tblclassstudentfinal.userid',  '=', DB::raw(Session::get('StudInfos')->ic));
            })
            ->select('tblclassfinal.*', 'tblclassstudentfinal.userid', DB::raw('tblclassstudentfinal.status as studentfinalstatus'),
             'tblclassstudentfinal.finalid')
            ->where('tblclassfinal.id', $id)
            ->get()->first();

        if($final->studentfinalstatus == 2 || $final->studentfinalstatus == 3){
            return ["status"=>false, "message" =>"Sorry, you have completed the final before."];
        }

        $q = DB::table('tblclassstudentfinal')->upsert([
            "userid" => Session::get('StudInfos')->ic,
            "finalid" => $id,
            "submittime" => DB::raw('now()'),
            "content" => $data,
            "status" => 2
        ],['userid', 'finalid']);

        return ["status"=>true, "message" =>$data];
     
    }

    public function finalresultstd(Request $request){
        
        $id = $request->finalid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $final = DB::table('tblclassstudentfinal')
            ->join('tblclassfinal', 'tblclassstudentfinal.finalid', 'tblclassfinal.id')
            ->leftJoin('students', 'tblclassstudentfinal.userid', 'students.ic')
            ->select('tblclassstudentfinal.*', 'tblclassstudentfinal.finalid', 'tblclassfinal.title',  
                DB::raw('tblclassfinal.content as original_content'), 
                'tblclassfinal.questionindex',
                'tblclassstudentfinal.userid',
                'tblclassstudentfinal.submittime',
                DB::raw('tblclassstudentfinal.status as studentfinalstatus'),
                'tblclassfinal.duration','students.name')
            ->where('tblclassstudentfinal.finalid', $id)
            ->where('tblclassstudentfinal.userid', $userid)->get()->first();
       
        $finalformdata = json_decode($final->content)->formData;
        $original_finalformdata = json_decode($final->original_content)->formData;
        

        $gain_mark = false;
        $correct_label = " <i style='font-size:1.5em' class='fa fa-check text-success'></i>";
        $incorrect_label = " <i style='font-size:1.5em' class='fa fa-close text-danger'></i>";

        foreach($original_finalformdata as $index => $q){

        //$radio = "radio-question".$count+1;
        //dd($radio);

            if(!empty($original_finalformdata[$index]->name) ){

                if($original_finalformdata[$index]->name == "radio-question".$count){
                    $i =0;
                    $correct_answer = $original_finalformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($finalformdata[$index]->values as $v){
                        
                        if(in_array($v->value, $correct_answer)){
                            $finalformdata[$index]->values[$i]->label = $original_finalformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $finalformdata[$index]->values[$i]->label = $original_finalformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                        $count+1;
                    }

                    $userData = !empty($finalformdata[$index]->userData[0]) ? $finalformdata[$index]->userData[0] : null;

                    if(in_array($userData, $correct_answer)){
                        $gain_mark = true;
                    }
                    
                }
                
                if($original_finalformdata[$index]->name == "checkbox-question".$count){
                    $i =0;
                    $correct_answer = $original_finalformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($finalformdata[$index]->values as $v){
                        if(in_array($v->value, $correct_answer)){
                            $finalformdata[$index]->values[$i]->label = $original_finalformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $finalformdata[$index]->values[$i]->label = $original_finalformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                        $count+1;
                    }
                    
                    $userData = !empty($finalformdata[$index]->userData) ? $finalformdata[$index]->userData : null;

                    if( count( array_diff_assoc($correct_answer, $userData) )  == 0){
                        $gain_mark = true;
                    }
                
                }
            }

            if(!empty($original_finalformdata[$index]->className) ){
                
                if(str_contains($original_finalformdata[$index]->className, "feedback-text")){
                    $finalformdata[$index] = $q;
                  
                    $finalformdata[$index]->type = "paragraph";

                    if(!empty($q->userData[0])){
                        $finalformdata[$index]->label = $q->userData[0];
                    }else{
                        $finalformdata[$index]->label = " ";
                    }
                    $finalformdata[$index]->className = "bg-red mb-4 text-danger";
                }

                if(str_contains($original_finalformdata[$index]->className, "collected-marks")){

                    $mark_label           = $original_finalformdata[$index]->values[0]->label;
                    $mark                 = $original_finalformdata[$index]->values[0]->value;
                    
                    //if result is published then use graded data
                    if($final->studentfinalstatus == 3){
                        $graded_data = empty($finalformdata[$index]->userData[0]) ? "" : $finalformdata[$index]->userData[0];

                        if($graded_data == $mark){
                            $finalformdata[$index]->values[0]->selected = true;
                        }else{
                            $finalformdata[$index]->values[0]->selected = false;
                        }
                    }else{
                        //auto correct answer on mcq by matching user answer with original answer
                        $finalformdata[$index] = $original_finalformdata[$index];

                        if($gain_mark){
                            $finalformdata[$index]->values[0]->selected = true;
                        }else{
                            $finalformdata[$index]->values[0]->selected = false;
                        }
                        
                        $gain_mark = false;
                    }
                }
            }
        }

       
        $data['final'] = $finalformdata;
        $data['comments'] = $final->comments;

        $data['finalid'] = $final->finalid;
        $data['finaltitle'] = $final->title;
        $data['finalduration'] = $final->duration;
        $data['finaluserid'] = $final->userid;
        $data['fullname'] = $final->name;
        $data['created_at'] = $final->created_at;
        $data['updated_at'] = $final->updated_at;
        $data['submittime'] = $final->submittime;
        $data['questionindex'] = $final->questionindex;
        $data['studentfinalstatus'] = $final->studentfinalstatus;

        return view('student.courseassessment.finalresult', compact('data'));
    }
}
