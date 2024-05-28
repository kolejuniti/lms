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

class ExtraController extends Controller
{
    //

    public function extralist()
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

        $data = DB::table('tblclassextra')
                ->join('tblextra_title', 'tblclassextra.title', 'tblextra_title.id')
                ->join('users', 'tblclassextra.addby', 'users.ic')
                ->where([
                    ['tblclassextra.classid', Session::get('CourseIDS')],
                    ['tblclassextra.sessionid', Session::get('SessionIDS')],
                    ['tblclassextra.addby', $user->ic],
                    ['tblclassextra.status', '!=', 3]
                ])
                ->select('tblclassextra.*', 'tblextra_title.name AS title', 'users.name AS addby')->get();

        //dd($data);

      
            foreach($data as $dt)
            {
                $group[] = DB::table('tblclassextra_group')
                        ->join('user_subjek', 'tblclassextra_group.groupid', 'user_subjek.id')
                        ->where('tblclassextra_group.extraid', $dt->id)->get();

                $chapter[] = DB::table('tblclassextra_chapter')
                        ->join('material_dir', 'tblclassextra_chapter.chapterid', 'material_dir.DrID')
                        ->where('tblclassextra_chapter.extraid', $dt->id)->get();
            }
      

        return view('lecturer.courseassessment.extra', compact('data', 'group', 'chapter'));
    }

    public function extracreate()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseIDS');

        $sessionid = Session::get('SessionIDS');

        $data['extra'] = null;

        $data['folder'] = null;

        $data['chapter'] = null;

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

        $title = DB::table('tblextra_title')->get();

        if(isset(request()->extraid))
        {

            $data['extra'] = DB::table('tblclassextra')->where('id', request()->extraid)->first();

            //dd($data['folder']);
            
        }

        //dd($folder);

        return view('lecturer.courseassessment.extracreate', compact(['group', 'folder', 'title', 'data']));
    }

    public function insertextra(Request $request)
    {

        //$data = $request->data;
        $classid = Session::get('CourseIDS');
        $sessionid = Session::get('SessionIDS');
        $title = $request->title;
        $no = $request->no;
        $group = $request->group;
        $chapter = $request->chapter;
        $marks = $request->marks;

        $user = Auth::user();
            
        $extraid = empty($request->extra) ? '' : $request->extra;

        if($group != null && $chapter != null)
        {
        
            if( !empty($extraid) ){
      
                $extra = DB::table('tblclassextra')->where('id', $extraid)->first();

                DB::table('tblclassextra_group')->where('extraid', $extraid)->delete();

                DB::table('tblclassextra_chapter')->where('extraid', $extraid)->delete();

                $q = DB::table('tblclassextra')->where('id', $extraid)->update([
                    "title" => $title,
                    "no" => $no,
                    "total_mark" => $marks,
                    "addby" => $user->ic,
                    "status" => 2
                ]);

                foreach($group as $grp)
                {
                    $gp = explode('|', $grp);
                    
                    DB::table('tblclassextra_group')->insert([
                        "groupid" => $gp[0],
                        "groupname" => $gp[1],
                        "extraid" => $extraid
                    ]);
                }

                foreach($chapter as $chp)
                {
                    DB::table('tblclassextra_chapter')->insert([
                        "chapterid" => $chp,
                        "extraid" => $extraid
                    ]);
                }

            }else{
                $q = DB::table('tblclassextra')->insertGetId([
                    "classid" => $classid,
                    "sessionid" => $sessionid,
                    "title" => $title,
                    "no" => $no,
                    "total_mark" => $marks,
                    "addby" => $user->ic,
                    "status" => 2
                ]);

                foreach($group as $grp)
                {
                    $gp = explode('|', $grp);
                    
                    DB::table('tblclassextra_group')->insert([
                        "groupid" => $gp[0],
                        "groupname" => $gp[1],
                        "extraid" => $q
                    ]);
                }

                foreach($chapter as $chp)
                {
                    DB::table('tblclassextra_chapter')->insert([
                        "chapterid" => $chp,
                        "extraid" => $q
                    ]);
                }
            }
        
        }else{

            return redirect()->back()->withErrors(['Please fill in the group and sub-chapter checkbox !']);

        }
        
        return redirect(route('lecturer.extra', ['id' => $classid]));

    }

    public function deleteextra(Request $request)
    {

        try {

            $extra = DB::table('tblclassextra')->where('id', $request->id)->first();

            if($extra->status != 3)
            {
            DB::table('tblclassextra')->where('id', $request->id)->update([
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

    public function lecturerextrastatus()
    {
        $user = Auth::user();

        $group = DB::table('user_subjek')
                ->join('tblclassextra_group', 'user_subjek.id', 'tblclassextra_group.groupid')
                ->join('tblclassextra', 'tblclassextra_group.extraid', 'tblclassextra.id')
                ->where([
                    ['tblclassextra.classid', Session::get('CourseIDS')],
                    ['tblclassextra.sessionid', Session::get('SessionIDS')],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassextra.id', request()->extra]
                ])->get();
                
            
        //dd($group);

        $extra = DB::table('student_subjek')
                ->join('tblclassextra_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassextra_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassextra_group.groupname');
                })
                ->join('tblclassextra', 'tblclassextra_group.extraid', 'tblclassextra.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassextra.id AS clssid', 'tblclassextra.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassextra.classid', Session::get('CourseIDS')],
                    ['tblclassextra.sessionid', Session::get('SessionIDS')],
                    ['tblclassextra.id', request()->extra],
                    ['tblclassextra.addby', $user->ic]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.name')->get();
        
        
        
        //dd($extra);

        foreach($extra as $qz)
        {
            //$status[] = DB::table('tblclassstudentextra')
            //->where([
               // ['extraid', $qz->clssid],
               // ['userid', $qz->student_ic]
           // ])->get();

           if(!DB::table('tblclassstudentextra')->where([['extraid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentextra')->insert([
                    'extraid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentextra')
            ->where([
                ['extraid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        //dd($status);

        return view('lecturer.courseassessment.extrastatus', compact('extra', 'status', 'group'));

    }

    public function extraGetGroup(Request $request)
    {

        $user = Auth::user();

        $extra = DB::table('student_subjek')
                ->join('tblclassextra_group', function($join){
                    $join->on('student_subjek.group_id', 'tblclassextra_group.groupid');
                    $join->on('student_subjek.group_name', 'tblclassextra_group.groupname');
                })
                ->join('tblclassextra', 'tblclassextra_group.extraid', 'tblclassextra.id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->select('student_subjek.*', 'tblclassextra.id AS clssid', 'tblclassextra.total_mark', 'students.no_matric', 'students.name')
                ->where([
                    ['tblclassextra.classid', Session::get('CourseIDS')],
                    ['tblclassextra.sessionid', Session::get('SessionIDS')],
                    ['tblclassextra.id', request()->extra],
                    ['tblclassextra.addby', $user->ic],
                    ['student_subjek.group_id', $request->group]
                ])->whereNotIn('students.status', [4,5,6,7,16])->orderBy('students.name')->get();

        foreach($extra as $qz)
        {

           if(!DB::table('tblclassstudentextra')->where([['extraid', $qz->clssid],['userid', $qz->student_ic]])->exists()){

                DB::table('tblclassstudentextra')->insert([
                    'extraid' => $qz->clssid,
                    'userid' => $qz->student_ic
                ]);

           }

            $status[] = DB::table('tblclassstudentextra')
            ->where([
                ['extraid', $qz->clssid],
                ['userid', $qz->student_ic]
            ])->first();
        }

        $content = "";
        $content .= '<thead>
                        <tr>
                            <th style="width: 1%">No.</th>
                            <th>Name</th>
                            <th>Matric No.</th>
                            <th>Submission Date</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($extra as $key => $qz) {
            $alert = ($status[$key]->total_mark != 0) ? 'badge bg-success' : 'badge bg-danger';

            $content .= '
                <tr>
                    <td style="width: 1%">' . ($key + 1) . '</td>
                    <td>
                        <span class="' . $alert . '">' . $qz->name . '</span>
                    </td>
                    <td>
                        <span>' . $qz->no_matric . '</span>
                    </td>';
            
            if ($status[$key]->total_mark != 0) {
                $content .= '
                    <td>' . $status[$key]->submittime . '</td>';
            } else {
                $content .= '
                    <td>-</td>';
            }
            
            $content .= '
                    <td>
                        <div class="form-inline col-md-6 d-flex">
                            <input type="number" class="form-control" name="marks[]" max="' . $qz->total_mark . '" value="' . $status[$key]->total_mark . '">
                            <input type="text" name="ic[]" value="' . $qz->student_ic . '" hidden>
                            <span>' . $status[$key]->total_mark . ' / ' . $qz->total_mark . '</span>
                        </div>
                    </td>
                </tr>';
        }

        $content .= '</tbody>';

        return response()->json(['message' => 'success', 'content' => $content]);

    }

    // Define a public function named 'updateextra' which takes a Request object as a parameter.
    public function updateextra(Request $request)
    {
        // Retrieve the currently authenticated user and store it in the variable '$user'.
        $user = Auth::user();

        // Decode the JSON string from the 'marks' request parameter and store the resulting object/array in the variable '$marks'.
        $marks = json_decode($request->marks);

        // Decode the JSON string from the 'ics' request parameter and store the resulting object/array in the variable '$ics'.
        $ics = json_decode($request->ics);

        // Decode the JSON string from the 'extraid' request parameter and store the resulting object/array in the variable '$extraid'.
        $extraid = json_decode($request->extraid);

        // Query the 'tblclassextra' database table for a row with an 'id' matching '$extraid', retrieve the first result, and store it in '$limitpercen'.
        $limitpercen = DB::table('tblclassextra')->where('id', $extraid)->first();

        // Iterate over the '$marks' array. For each mark, check if it exceeds the total_mark in '$limitpercen'.
        foreach ($marks as $key => $mrk)
        {
            // If the current mark is greater than 'total_mark' from '$limitpercen', return an error response with a message and the 'ics' array.
            if ($mrk > $limitpercen->total_mark)
            {
                return ["message" => "Field Error", "id" => $ics];
            }
        }

        // Initialize an empty array '$upsert' to hold the data for bulk insertion/update.
        $upsert = [];
        foreach ($marks as $key => $mrk)
        {
            // For each mark, create an associative array with user, extra, submission time, mark, and status information and add it to '$upsert'.
            array_push($upsert, [
                'userid' => $ics[$key],
                'extraid' => $extraid,
                'submittime' => date("Y-m-d H:i:s"),
                'total_mark' => $mrk,
                'status' => 1
            ]);
        }

        // Perform a bulk insert/update operation on the 'tblclassstudentextra' table using the data in '$upsert'.
        // The unique constraint is on both 'userid' and 'extraid' columns.
        DB::table('tblclassstudentextra')->upsert($upsert, ['userid', 'extraid']);

        // Return a success response with the 'ics' array.
        return ["message" => "Success", "id" => $ics];
    }

}
