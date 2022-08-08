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

class MidtermController extends Controller
{
    
    public function midtermlist()
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

        $data = DB::table('tblclassmidterm')
                ->where([
                    ['classid', Session::get('CourseIDS')],
                    ['sessionid', Session::get('SessionIDS')],
                    ['addby', $user->ic]
                ])->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassmidterm_group')
                        ->join('user_subjek', 'tblclassmidterm_group.groupid', 'user_subjek.id')
                        ->where('tblclassmidterm_group.midtermid', $dt->id)->get();

                $chapter[] = DB::table('tblclassmidterm_chapter')
                        ->join('material_dir', 'tblclassmidterm_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassmidterm_chapter.midtermid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.midterm', compact('data', 'group', 'chapter'));
    }

    public function midtermcreate()
    {
        $user = Auth::user();

        $data['midtermid'] = null;

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
            ['Addby', $user->ic]
            ])->get();

        //dd($folder);

        if(isset(request()->midtermid))
        {

            $midtermid = request()->midtermid;
 
            $data['midtermid'] = $midtermid;

            $data['midterm'] = DB::table('tblclassmidterm')->select('tblclassmidterm.*')
            ->where([
                ['classid', Session::get('CourseIDS')],
                ['sessionid', Session::get('SessionIDS')],
                ['id', $midtermid],
                ['addby', $user->ic],
                ['content','!=', null]
            ])->get()->first();

            $folders = DB::table('tblclassmidterm_chapter')
                        ->join('material_dir', 'tblclassmidterm_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassmidterm_chapter.midtermid', $midtermid);

            $data['folder'] = $folders->join('lecturer_dir', 'material_dir.LecturerDirID', 'lecturer_dir.DrID')
                                     ->select('lecturer_dir.*')->get()->first();

            $data['midtermstatus'] = $data['midterm']->status;

        }

        //dd($data);

        return view('lecturer.courseassessment.midtermcreate', compact(['group', 'folder', 'data']));
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

        $midterm = DB::table('student_subjek')
                ->join('tblclassmidterm_group', 'student_subjek.group_id', 'tblclassmidterm_group.groupid')
                ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassmidterm.id AS clssid', 'tblclassmidterm.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['tblclassmidterm.id', $request->midterm],
                    ['tblclassmidterm.addby', $user->ic],
                    ['tblclassmidterm_group.groupid', $request->group]
                ])->get();
        
        //dd($midterm);

        foreach($midterm as $qz)
        {
            $statu[] = DB::table('tblclassstudentmidterm')
            ->where([
                ['midtermid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($midterm);

        return view('lecturer.courseassessment.getstatusmidterm', compact('midterm', 'status'));

    }


    public function insertmidterm(Request $request){
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
            
        $midtermid = empty($request->midterm) ? '' : $request->midterm;

        
        if( !empty($midtermid) ){
            $q = DB::table('tblclassmidterm')->where('id', $midtermid)->update([
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

            DB::table('tblclassmidterm_group')->where('midtermid',$midtermid)->delete();

            foreach($group as $grp)
            {
                $gp = explode('|', $grp);
                
                DB::table('tblclassmidterm_group')->insert([
                    "groupid" => $gp[0],
                    "groupname" => $gp[1],
                    "midtermid" => $midtermid
                ]);
            }

            DB::table('tblclassmidterm_chapter')->where('midtermid',$midtermid)->delete();

            foreach($chapter as $chp)
            {
                DB::table('tblclassmidterm_chapter')->insert([
                    "chapterid" => $chp,
                    "midtermid" => $midtermid
                ]);
            }

        }else{
            $q = DB::table('tblclassmidterm')->insertGetId([
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
                
                DB::table('tblclassmidterm_group')->insert([
                    "groupid" => $gp[0],
                    "groupname" => $gp[1],
                    "midtermid" => $q
                ]);
            }

            foreach($chapter as $chp)
            {
                DB::table('tblclassmidterm_chapter')->insert([
                    "chapterid" => $chp,
                    "midtermid" => $q
                ]);
            }
        }
        
        
        return true;
    }

    public function lecturermidtermstatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassmidterm_group', 'user_subjek.id', 'tblclassmidterm_group.groupid')
                ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassmidterm.id', request()->midterm]
                ])->get();
                
            
        //dd($group);

        $midterm = DB::table('student_subjek')
                ->join('tblclassmidterm_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassmidterm_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassmidterm_group.groupname');
                })
                ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassmidterm.id AS clssid', 'tblclassmidterm.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['tblclassmidterm.id', request()->midterm],
                    ['tblclassmidterm.addby', $user->ic]
                ])->get();
        
        
        
        //dd($midterm);

        foreach($midterm as $qz)
        {
            $status[] = DB::table('tblclassstudentmidterm')
            ->where([
                ['midtermid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->get();
        }

        //dd($midterm);

        return view('lecturer.courseassessment.midtermstatus', compact('midterm', 'status', 'group'));

    }

    public function midtermresult(Request $request){
        
        $id = $request->midtermid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $midterm = DB::table('tblclassstudentmidterm')
            ->join('tblclassmidterm', 'tblclassstudentmidterm.midtermid', 'tblclassmidterm.id')
            ->leftJoin('students', 'tblclassstudentmidterm.userid', 'students.ic')
            ->select('tblclassstudentmidterm.*', 'tblclassstudentmidterm.midtermid', 'tblclassmidterm.title',  
                DB::raw('tblclassmidterm.content as original_content'), 
                'tblclassmidterm.questionindex',
                'tblclassstudentmidterm.userid',
                'tblclassstudentmidterm.submittime',
                DB::raw('tblclassstudentmidterm.status as studentmidtermstatus'),
                'tblclassmidterm.duration','students.name')
            ->where('tblclassstudentmidterm.midtermid', $id)
            ->where('tblclassstudentmidterm.userid', $userid)->get()->first();
       
        $midtermformdata = json_decode($midterm->content)->formData;
        $original_midtermformdata = json_decode($midterm->original_content)->formData;
        

        $gain_mark = false;
        $correct_label = " <i style='font-size:1.5em' class='fa fa-check text-success'></i>";
        $incorrect_label = " <i style='font-size:1.5em' class='fa fa-close text-danger'></i>";

        foreach($original_midtermformdata as $index => $q){

        //$radio = "radio-question".$count+1;
        //dd($radio);

            if(!empty($original_midtermformdata[$index]->name) ){

                if($original_midtermformdata[$index]->name == "radio-question".$count){
                    $i =0;
                    $correct_answer = $original_midtermformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($midtermformdata[$index]->values as $v){
                        
                        if(in_array($v->value, $correct_answer)){
                            $midtermformdata[$index]->values[$i]->label = $original_midtermformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $midtermformdata[$index]->values[$i]->label = $original_midtermformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }

                    $userData = !empty($midtermformdata[$index]->userData[0]) ? $midtermformdata[$index]->userData[0] : null;

                    if(in_array($userData, $correct_answer)){
                        $gain_mark = true;
                    }
                    $count++;
                    
                }
                
                if($original_midtermformdata[$index]->name == "checkbox-question".$count){
                    $i =0;
                    $correct_answer = $original_midtermformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($midtermformdata[$index]->values as $v){
                        if(in_array($v->value, $correct_answer)){
                            $midtermformdata[$index]->values[$i]->label = $original_midtermformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $midtermformdata[$index]->values[$i]->label = $original_midtermformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }
                    
                    $userData = !empty($midtermformdata[$index]->userData) ? $midtermformdata[$index]->userData : null;

                    if( count( array_diff_assoc($correct_answer, $userData) )  == 0){
                        $gain_mark = true;
                    }
                    $count++;
                
                }
            }

            if(!empty($original_midtermformdata[$index]->className) ){
                
                if(str_contains($original_midtermformdata[$index]->className, "feedback-text")){
                    $midtermformdata[$index] = $q;
                  
                    $midtermformdata[$index]->type = "paragraph";

                    if(!empty($q->userData[0])){
                        $midtermformdata[$index]->label = $q->userData[0];
                    }else{
                        $midtermformdata[$index]->label = " ";
                    }
                    $midtermformdata[$index]->className = "bg-red mb-4 text-danger";
                }

                if(str_contains($original_midtermformdata[$index]->className, "collected-marks")){

                    $mark_label           = $original_midtermformdata[$index]->values[0]->label;
                    $mark                 = $original_midtermformdata[$index]->values[0]->value;
                    
                    //if result is published then use graded data
                    if($midterm->studentmidtermstatus == 3){
                        $graded_data = empty($midtermformdata[$index]->userData[0]) ? "" : $midtermformdata[$index]->userData[0];

                        if($graded_data == $mark){
                            $midtermformdata[$index]->values[0]->selected = true;
                        }else{
                            $midtermformdata[$index]->values[0]->selected = false;
                        }
                    }else{
                        //auto correct answer on mcq by matching user answer with original answer
                        $midtermformdata[$index] = $original_midtermformdata[$index];

                        if($gain_mark){
                            $midtermformdata[$index]->values[0]->selected = true;
                        }else{
                            $midtermformdata[$index]->values[0]->selected = false;
                        }
                        
                        $gain_mark = false;
                    }
                }
            }
        }

       
        $data['midterm'] = $midtermformdata;
        $data['comments'] = $midterm->comments;
        $data['midtermid'] = $midterm->midtermid;
        $data['midtermtitle'] = $midterm->title;
        $data['midtermduration'] = $midterm->duration;
        $data['midtermuserid'] = $midterm->userid;
        $data['fullname'] = $midterm->name;
        $data['created_at'] = $midterm->created_at;
        $data['updated_at'] = $midterm->updated_at;
        $data['submittime'] = $midterm->submittime;
        $data['questionindex'] = $midterm->questionindex;
        $data['studentmidtermstatus'] = $midterm->studentmidtermstatus;

        return view('lecturer.courseassessment.midtermresult', compact('data'));
    }

    public function updatemidtermresult(Request $request){
        $midterm = $request->midterm;
        $participant = $request->participant;
        $final_mark = $request->final_mark;
        $comments = $request->comments;
        //$total_mark = $request->total_mark;
        $data = $request->data;

      
        $q = \DB::table('tblclassstudentmidterm')
            ->where('midtermid', $midterm)
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




    //This is Student midterm Controller//

    public function studentmidtermlist()
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

        $data = DB::table('tblclassmidterm')
                ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                ->join('student_subjek', function($join){
                    $join->on('tblclassmidterm_group.groupid', 'student_subjek.group_id');
                    $join->on('tblclassmidterm_group.groupname', 'student_subjek.group_name');
                })
                ->join('user_subjek', 'tblclassmidterm_group.groupid', 'user_subjek.id')
                ->select('tblclassmidterm.*', 'tblclassmidterm_group.groupname')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['student_subjek.student_ic', $student->ic]
                ])->get();

        //dd($data);

        foreach($data as $dt)
        {
            $chapter[] = DB::table('tblclassmidterm_chapter')
                      ->join('material_dir', 'tblclassmidterm_chapter.chapterid', 'material_dir.DrID')
                      ->where('tblclassmidterm_chapter.midtermid', $dt->id)->get();
        }

        return view('student.courseassessment.midterm', compact('data', 'chapter'));

    }

    public function studentmidtermstatus()
    {
        $midterm = DB::table('student_subjek')
                ->join('tblclassmidterm_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassmidterm_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassmidterm_group.groupname');
                })
                ->join('tblclassmidterm', 'tblclassmidterm_group.midtermid', 'tblclassmidterm.id')
                ->where([
                    ['tblclassmidterm.classid', Session::get('CourseIDS')],
                    ['tblclassmidterm.sessionid', Session::get('SessionIDS')],
                    ['tblclassmidterm.id', request()->midterm],
                    ['student_subjek.student_ic', Session::get('StudInfos')->ic]
                ])->get();

        //dd($midterm);

        foreach($midterm as $qz)
        {
            $status[] = DB::table('tblclassstudentmidterm')
            ->where([
                ['midtermid', $qz->id],
                ['userid', Session::get('StudInfos')->ic]
            ])->first();
        }

        //dd($status);

        return view('student.courseassessment.midtermstatus', compact('midterm', 'status'));
    }

    public function midtermview(Request $request){

        $id = $request->midterm;
        $midterm = DB::table('tblclassmidterm')
            ->leftjoin('tblclassstudentmidterm', function($join) 
            {
                $join->on('tblclassmidterm.id', '=', 'tblclassstudentmidterm.midtermid');
                $join->on('tblclassstudentmidterm.userid',  '=', DB::raw(Session::get('StudInfos')->ic));
            })
            ->leftJoin('students', 'tblclassstudentmidterm.userid', 'students.ic')
            ->leftJoin('tblclassmidtermstatus', 'tblclassmidterm.status', 'tblclassmidtermstatus.id')
            ->select('tblclassmidterm.*', 'tblclassstudentmidterm.userid', 'tblclassstudentmidterm.midtermid','students.name', 
                DB::raw('tblclassmidterm.status as classmidtermstatus'),
                DB::raw('tblclassstudentmidterm.status as studentmidtermstatus'), 'tblclassstudentmidterm.endtime', 'tblclassstudentmidterm.starttime' , 
                DB::raw('TIMESTAMPDIFF(SECOND, now(), endtime) as timeleft'),
                DB::raw('tblclassstudentmidterm.content as studentmidtermcontent')
            )
            ->where('tblclassmidterm.id', $id)
            ->get()->first();

        //dd($midterm);

        $midtermformdata = json_decode($midterm->content)->formData;

        if(!empty($midterm->studentmidtermcontent)){
            $midtermformdata = json_decode($midterm->studentmidtermcontent)->formData;
        }

        foreach($midtermformdata as $index => $v){

            if(!empty($midtermformdata[$index]->className) ){
                if(str_contains($midtermformdata[$index]->className, "collected-marks")){
                    $midtermformdata[$index]->type = "paragraph";
                    $midtermformdata[$index]->label = $midtermformdata[$index]->values[0]->label;
                }

                if(str_contains($midtermformdata[$index]->className, "correct-answer")){
                    $midtermformdata[$index]->className = "correct-answer d-none";
                    unset($midtermformdata[$index]->label);
                }

                if(str_contains($midtermformdata[$index]->className, "feedback-text")){
                    $midtermformdata[$index]->className = "feedback-text d-none";
                    unset($midtermformdata[$index]->label);
                }
            }
        }

        if($midterm->classmidtermstatus == 2){
            if($midterm->studentmidtermstatus == 2 || $midterm->studentmidtermstatus == 3){
                //completed midterm
                return redirect('/academics/midterm/'.$midterm->midtermid.'/result');
            }else{
                $data['midterm'] = json_encode($midtermformdata );
                $data['midtermid'] = $midterm->id;
                $data['midtermtitle'] = $midterm->title;
                $data['midtermduration'] = $midterm->duration;
                $data['midtermendduration'] = $midterm->date_to;
                $data['fullname'] = $midterm->name;
                $data['created_at'] = $midterm->created_at;
                $data['updated_at'] = $midterm->updated_at;
                $data['midtermstarttime'] = $midterm->starttime;
                $data['midtermendtime'] = $midterm->endtime;
                $data['midtermtimeleft'] = $midterm->timeleft;
        
                return view('student.courseassessment.midtermanswer', compact('data'));
            }
        }else{
            return "midterm is not published yet";
        }
    }

    public function startmidterm(Request $request){
        $midterm = $request->midterm;
        $data = $request->data;
        
        $midtermduration = DB::table('tblclassmidterm')->select('duration')->where('id', $midterm)->first()->duration;
        
        try{
            DB::beginTransaction();
            $q =  DB::table('tblclassstudentmidterm')->insert([
                "userid" =>  Session::get('StudInfos')->ic,
                "midtermid" => $midterm,
                "content" => $data,
                "starttime" =>  DB::raw('now()'),
                "endtime" => DB::raw('now() + INTERVAL '.$midtermduration.' MINUTE'),
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

    public function savemidterm(Request $request){
        $data = $request->data;
        $midtermid = $request->midterm;


        $q = DB::table('tblclassstudentmidterm')->where('status', 1)->where('midtermid',$midtermid)->where('userid', Session::get('StudInfos')->ic)->update([
            "content" => $data
        ]);

        $q = ($q == 1) ? true : false;

        return $q;
     
    }

    public function submitmidterm(Request $request){
        $data = $request->data;
        $id = $request->id;

        $midterm = DB::table('tblclassmidterm')
            ->leftjoin('tblclassstudentmidterm', function($join) 
            {
                $join->on('tblclassmidterm.id', '=', 'tblclassstudentmidterm.midtermid');
                $join->on('tblclassstudentmidterm.userid',  '=', DB::raw(Session::get('StudInfos')->ic));
            })
            ->select('tblclassmidterm.*', 'tblclassstudentmidterm.userid', DB::raw('tblclassstudentmidterm.status as studentmidtermstatus'),
             'tblclassstudentmidterm.midtermid')
            ->where('tblclassmidterm.id', $id)
            ->get()->first();

        if($midterm->studentmidtermstatus == 2 || $midterm->studentmidtermstatus == 3){
            return ["status"=>false, "message" =>"Sorry, you have completed the midterm before."];
        }

        $q = DB::table('tblclassstudentmidterm')->upsert([
            "userid" => Session::get('StudInfos')->ic,
            "midtermid" => $id,
            "submittime" => DB::raw('now()'),
            "content" => $data,
            "status" => 2
        ],['userid', 'midtermid']);

        return ["status"=>true, "message" =>$data];
     
    }

    public function midtermresultstd(Request $request){
        
        $id = $request->midtermid;
        $userid = $request->userid;
        $count = 1;

        //dd($id);

        $midterm = DB::table('tblclassstudentmidterm')
            ->join('tblclassmidterm', 'tblclassstudentmidterm.midtermid', 'tblclassmidterm.id')
            ->leftJoin('students', 'tblclassstudentmidterm.userid', 'students.ic')
            ->select('tblclassstudentmidterm.*', 'tblclassstudentmidterm.midtermid', 'tblclassmidterm.title',  
                DB::raw('tblclassmidterm.content as original_content'), 
                'tblclassmidterm.questionindex',
                'tblclassstudentmidterm.userid',
                'tblclassstudentmidterm.submittime',
                DB::raw('tblclassstudentmidterm.status as studentmidtermstatus'),
                'tblclassmidterm.duration','students.name')
            ->where('tblclassstudentmidterm.midtermid', $id)
            ->where('tblclassstudentmidterm.userid', $userid)->get()->first();
       
        $midtermformdata = json_decode($midterm->content)->formData;
        $original_midtermformdata = json_decode($midterm->original_content)->formData;
        

        $gain_mark = false;
        $correct_label = " <i style='font-size:1.5em' class='fa fa-check text-success'></i>";
        $incorrect_label = " <i style='font-size:1.5em' class='fa fa-close text-danger'></i>";

        foreach($original_midtermformdata as $index => $q){

        //$radio = "radio-question".$count+1;
        //dd($radio);

            if(!empty($original_midtermformdata[$index]->name) ){

                if($original_midtermformdata[$index]->name == "radio-question".$count){
                    $i =0;
                    $correct_answer = $original_midtermformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($midtermformdata[$index]->values as $v){
                        
                        if(in_array($v->value, $correct_answer)){
                            $midtermformdata[$index]->values[$i]->label = $original_midtermformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $midtermformdata[$index]->values[$i]->label = $original_midtermformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }

                    $userData = !empty($midtermformdata[$index]->userData[0]) ? $midtermformdata[$index]->userData[0] : null;

                    if(in_array($userData, $correct_answer)){
                        $gain_mark = true;
                    }
                    $count++;
                    
                }
                
                if($original_midtermformdata[$index]->name == "checkbox-question".$count){
                    $i =0;
                    $correct_answer = $original_midtermformdata[$index + 1]->label;
                    $correct_answer = preg_replace('/\s+/', '', $correct_answer );
                    $correct_answer = explode(",", $correct_answer);
                    
                    foreach($midtermformdata[$index]->values as $v){
                        if(in_array($v->value, $correct_answer)){
                            $midtermformdata[$index]->values[$i]->label = $original_midtermformdata[$index]->values[$i]->label . $correct_label;
                        }else{
                            $midtermformdata[$index]->values[$i]->label = $original_midtermformdata[$index]->values[$i]->label . $incorrect_label;
                        }
                        $i++;
                    }
                    
                    $userData = !empty($midtermformdata[$index]->userData) ? $midtermformdata[$index]->userData : null;

                    if( count( array_diff_assoc($correct_answer, $userData) )  == 0){
                        $gain_mark = true;
                    }
                    $count++;
                
                }
            }

            if(!empty($original_midtermformdata[$index]->className) ){
                
                if(str_contains($original_midtermformdata[$index]->className, "feedback-text")){
                    $midtermformdata[$index] = $q;
                  
                    $midtermformdata[$index]->type = "paragraph";

                    if(!empty($q->userData[0])){
                        $midtermformdata[$index]->label = $q->userData[0];
                    }else{
                        $midtermformdata[$index]->label = " ";
                    }
                    $midtermformdata[$index]->className = "bg-red mb-4 text-danger";
                }

                if(str_contains($original_midtermformdata[$index]->className, "collected-marks")){

                    $mark_label           = $original_midtermformdata[$index]->values[0]->label;
                    $mark                 = $original_midtermformdata[$index]->values[0]->value;
                    
                    //if result is published then use graded data
                    if($midterm->studentmidtermstatus == 3){
                        $graded_data = empty($midtermformdata[$index]->userData[0]) ? "" : $midtermformdata[$index]->userData[0];

                        if($graded_data == $mark){
                            $midtermformdata[$index]->values[0]->selected = true;
                        }else{
                            $midtermformdata[$index]->values[0]->selected = false;
                        }
                    }else{
                        //auto correct answer on mcq by matching user answer with original answer
                        $midtermformdata[$index] = $original_midtermformdata[$index];

                        if($gain_mark){
                            $midtermformdata[$index]->values[0]->selected = true;
                        }else{
                            $midtermformdata[$index]->values[0]->selected = false;
                        }
                        
                        $gain_mark = false;
                    }
                }
            }
        }

       
        $data['midterm'] = $midtermformdata;
        $data['comments'] = $midterm->comments;

        $data['midtermid'] = $midterm->midtermid;
        $data['midtermtitle'] = $midterm->title;
        $data['midtermduration'] = $midterm->duration;
        $data['midtermuserid'] = $midterm->userid;
        $data['fullname'] = $midterm->name;
        $data['created_at'] = $midterm->created_at;
        $data['updated_at'] = $midterm->updated_at;
        $data['submittime'] = $midterm->submittime;
        $data['questionindex'] = $midterm->questionindex;
        $data['studentmidtermstatus'] = $midterm->studentmidtermstatus;

        return view('student.courseassessment.midtermresult', compact('data'));
    }
}
