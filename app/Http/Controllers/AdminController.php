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

class AdminController extends Controller
{
    public function dashboard()
    {
        Session::put('User', Auth::user());

        return view('dashboard');
    }

    public function index() 
    {

        //dd(Session::get('User'));

        $users = User::all()->sortBy('usrtype');

        //dd($users);

        return view('admin',['users'=>$users]);
    }

    public function create()
    {
        $faculty = DB::table('tblfaculty')->get();

        return view('admin.create', compact('faculty'));
    }

    public function store()
    {
        //this will validate the requested data
        $data = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'nostaf' => ['required', 'string', 'max:45'],
            'ic' => ['required', 'string', 'max:12'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'usrtype' => ['required'],
            'faculty' => ['required'],
        ]);

        //dd(array_values(array_filter(request()->prg,function($v){return !is_null($v);})));

        //this will create data in table [Please be noted that model need to be fillable with the same data]
        User::create([
            'name' => $data['name'],
            'no_staf' => $data['nostaf'],
            'ic' => $data['ic'],
            'email' => $data['email'],
            'password' => Hash::make('12345678'),
            'usrtype' => $data['usrtype'],
            'faculty' => $data['faculty'],
            'start' => request()->from,
            'end' => request()->to
        ]);

        if(isset(request()->program))
        {
            foreach(request()->program as $prg)
            {
                DB::table('user_program')->insert([
                    'user_ic' => $data['ic'],
                    'program_id' => $prg
                ]);
            }
        }

        if(isset(request()->academic))
        {
            $pgname = array_values(array_filter(request()->prg,function($v){return !is_null($v);}));

            $uniname = array_values(array_filter(request()->uni,function($v){return !is_null($v);}));

            foreach(request()->academic as $key => $ac)
            {
                DB::table('tbluser_academic')->insert([
                    'user_ic' => $data['ic'],
                    'academic_id' => $ac,
                    'academic_name' => $pgname[$key],
                    'university_name' => $uniname[$key]
                ]);
            }
        }

        return redirect('admin');
    }

    public function delete(Request $request)
    {

        User::where('ic', $request->ic)->delete();

        return true;
        
    }

    public function edit(User $id)
    {
        $faculty = DB::table('tblfaculty')->get();

        //dd($id);

        $academics = array("DP|DIPLOMA", 'DG|DEGREE', 'MS|MASTER', 'PHD|PHD');

        foreach($academics as $ac)
        {
            $ace = explode('|', $ac);

            $academic[] = DB::table('tbluser_academic')->where('user_ic', $id->ic)->where('academic_id', $ace[0])->first();
        }

        //dd($academic);


        return view('admin.edit' , compact('id', 'faculty', 'academic', 'academics'));
    }

    //a function with (Modal $variable) is to make sql query *example = select * from User where id = $id
    public function update(User $id)
    {
        $data = request()->validate([
            'name' => 'required',
            'ic' => 'required',
            'usrtype' => 'required',
            'email' => 'required',
        ]);

        $data2 = [
                    'no_staf' => request()->nostaf,
                    'faculty' => request()->faculty,
                    'start' => request()->from,
                    'end' => request()->to,
                    'status' => request()->status,
                    'comment' => request()->comments
                 ];

        //this is to check if image is not empty
        if(request('image'))
        {
            $imageName = request('image')->getClientOriginalName(); 

            $filepath = "storage/";

            //this is to store file/image in specific folder
            //$imagePath = request('image')->storeAs('storage', $imageName, 'linode', 'public');

            Storage::disk('linode')->putFileAs(
                $filepath,
                request('image'),
                $imageName,
                'public'
              );

              $imagePath = $filepath . $imageName;

            //dd($imagePath);

            //this is to resize image  Image need to be declared with 'use Intervention\Image\Facades\Image;'
            $image = Image::make(Storage::disk('linode')->url($imagePath))->fit(1000, 1000);
            //dd($image);
            $image->save($imagePath);

            //store path in image parameter and store in $imageArray variable
            $imageArray = ['image' => $imagePath];
        }

        //array_merge is a function to merge two variable together
        User::where('id', $id->id)->update(array_merge(
            $data,
            $data2,
            $imageArray ?? []
        ));

        //dd(request()->program);

        if(request()->program != null)
        {
            DB::table('user_program')->where('user_ic', $data['ic'])->delete();
            
            foreach(request()->program as $prg)
            {

                DB::table('user_program')->insert([
                    'user_ic' => $data['ic'],
                    'program_id' => $prg
                ]);
            }
        }

        if(request()->academic != null)
        {
            $pgname = $pgname = array_values(array_filter(request()->prg,function($v){return !is_null($v);}));

            $uniname = array_values(array_filter(request()->uni,function($v){return !is_null($v);}));

            DB::table('tbluser_academic')->where('user_ic', $data['ic'])->delete();

            foreach(request()->academic as $key => $ac)
            {
                DB::table('tbluser_academic')->insert([
                    'user_ic' => $data['ic'],
                    'academic_id' => $ac,
                    'academic_name' => $pgname[$key],
                    'university_name' => $uniname[$key]
                ]);
            }
        }

        return redirect("/admin");
    }

    public function getProgramoptions(Request $request)
    {
        $program = DB::table('tblprogramme')->where('facultyid', $request->faculty)->get();

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
        foreach($program as $key => $prg){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <label>'.$key+1 .'</label>
                </td>
                <td >
                    <label>'.$prg->progname.'</label>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="program_checkbox_'.$prg->id.'"
                            class="filled-in" name="program[]" value="'.$prg->id.'" 
                        >
                        <label for="program_checkbox_'.$prg->id.'"> </label>
                    </div>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';

            return $content;
    }


    public function getProgramoptions2(Request $request)
    {

        $program = DB::table('tblprogramme')->where('facultyid', $request->faculty)->get();

        foreach($program as $prg)
        {

            $programs[] = DB::table('user_program')->where('user_ic', $request->ic)->where('program_id', $prg->id)->get();

        }

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
        foreach($program as $key => $prg){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <label>'.$key+1 .'</label>
                </td>
                <td >
                    <label>'.$prg->progname.'</label>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="program_checkbox_'.$prg->id.'"
                            class="filled-in" name="program[]" value="'.$prg->id.'"
                        ';
                        if(count($programs[$key]) > 0)
                        {
                            $content .= 'checked'; 
                        }
                        
                        $content .= '
                        >
                        <label for="program_checkbox_'.$prg->id.'"> </label>
                    </div>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';

            return $content;

        return view('admin.getprogram', compact('program', 'id'));
    }

    
    public function getReportLecturer()
    {

        $faculty = DB::table('tblfaculty')->get();

        foreach($faculty as $key => $fcl)
        {
            $lecturer[] = DB::table('users')->where('status', 'ACTIVE')->where('faculty', $fcl->id)->whereIn('usrtype', ['LCT','PL'])->get();

            //dd($lecturer);

            foreach($lecturer[$key] as $key1 => $lct)
            {
                $course[$key][$key1] = DB::table('user_subjek')
                    ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
                    ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
                    ->where('user_subjek.user_ic', $lct->ic)
                    ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID')
                    ->groupBy('user_subjek.course_id')
                    ->get();

            }
        }

        //dd($course);

        return view('admin.lecturerReport', compact('faculty','lecturer','course'));

    }

    public function getFolder(Request $request)
    {
        Session::put('CourseID', $request->id);

        Session::put('SessionID', $request->ses);

        Session::put('LectIC', $request->ic);

        $folder = DB::table('lecturer_dir')->where('Addby', $request->ic)->where('CourseID', $request->id)->get();

        return view('admin.getSubfolder', compact('folder'));
    }

    public function getSubFolder(Request $request)
    {

        $subfolder = DB::table('material_dir')->where('LecturerDirID', $request->id)->get();

        $prev0 = $folder = DB::table('lecturer_dir')->where('DrID', $request->id)->first();

        return view('admin.getSubfolder', compact('subfolder','prev0'));

    }

    public function getSubFolder2(Request $request)
    {

        $directory = DB::table('lecturer_dir')
        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*', 'lecturer_dir.CourseID')
        ->where('material_dir.DrID', $request->id)->first();

        $subfolder2 = DB::table('materialsub_dir')->where('MaterialDirID', $request->id)->get();

        $dir = "classmaterial/" . $directory->CourseID . "/" . $directory->A . "/" . $directory->B;

        //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
        $classmaterial  = Storage::disk('linode')->files($dir);

        $prev = $directory->LecturerDirID;

        return view('admin.getSubfolder', compact('subfolder2', 'classmaterial','prev'));

    }

    public function getMaterial(Request $request)
    {

        $directory = DB::table('lecturer_dir')
        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
        ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'materialsub_dir.DrID', 'lecturer_dir.CourseID')
        ->where('materialsub_dir.DrID', $request->id)->first();

        $dir = "classmaterial/" . $directory->CourseID . "/" . $directory->A . "/" . $directory->B . "/" . $directory->C;

        $classmaterial  = Storage::disk('linode')->allFiles($dir);

        $prev2 = $directory->MaterialDirID;

        return view('admin.getSubfolder', compact('classmaterial','prev2'));
    }

    public function listAttendance(Request $request)
    {
        $courseid = Session::get('CourseID');

        $sessionid = Session::get('SessionID');

        $list = DB::table('tblclassattendance')
                ->join('user_subjek', 'tblclassattendance.groupid', 'user_subjek.id')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->where([
                    ['subjek.id', $courseid],
                    ['user_subjek.session_id', $sessionid]
                ])->groupBy('tblclassattendance.groupname')->groupBy('tblclassattendance.classdate')
                ->orderBy('tblclassattendance.classdate', 'ASC')->get();

        //dd($list);

        return view('lecturer.class.attendancelist', compact('list'));

    }

    public function getAssessment(Request $request)
    {

        if($request->from != '' && $request->to != '')
        {
            $course = Session::get('CourseID', $request->id);

            $session = Session::get('SessionID', $request->ses);

            $ic = Session::get('LectIC', $request->ic);

            $assessment = DB::table('tblclassquiz')
            ->where([
                      ['classid', $course],
                      ['sessionid', $session],
                      ['addby', $ic]
                      ])->whereBetween('created_at', [$request->from, $request->to])->get();

            $assessment2 = DB::table('tblclasstest')
            ->where([
                    ['classid', $course],
                    ['sessionid', $session],
                    ['addby', $ic]
                    ])->whereBetween('created_at', [$request->from, $request->to])->get();

            $assessment3 = DB::table('tblclassassign')
                ->where([
                        ['classid', $course],
                        ['sessionid', $session],
                        ['addby', $ic]
                        ])->whereBetween('created_at', [$request->from, $request->to])->get();

            /*if($request->as == 'QUIZ')
            {

                $assessment = DB::table('tblclassquiz')
                      ->where([
                                ['classid', $course],
                                ['sessionid', $session],
                                ['addby', $ic]
                                ])->whereBetween('created_at', [$request->from, $request->to])->get();


            }elseif($request->as == 'TEST')
            {

                $assessment = DB::table('tblclasstest')
                      ->where([
                                ['classid', $course],
                                ['sessionid', $session],
                                ['addby', $ic]
                                ])->whereBetween('created_at', [$request->from, $request->to])->get();

            }elseif($request->as == 'ASSIGNMENT')
            {

                $assessment = DB::table('tblclassassign')
                      ->where([
                                ['classid', $course],
                                ['sessionid', $session],
                                ['addby', $ic]
                                ])->whereBetween('created_at', [$request->from, $request->to])->get();

            }*/

            $content = "";
            $content .= '
            <div class="table-responsive" style="width:99.7%">
            <table id="table_registerstudent" class="w-100 table table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead class="thead-themed">
                <th>No</th>
                <th>Title</th>
                <th>Date</th>
                <th></th>
                </thead>
                <tbody>
            ';
            foreach($assessment as $key => $as){
                //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td >
                        <label>'.$key+1 .'</label>
                    </td>
                    <td >
                        <label>'.$as->title.'</label>
                    </td>
                    <td >
                        <label>'.$as->created_at.'</label>
                    </td>
                </tr>
                ';
            }

            foreach($assessment2 as $key => $as){
                //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td >
                        <label>'.$key+1 .'</label>
                    </td>
                    <td >
                        <label>'.$as->title.'</label>
                    </td>
                    <td >
                        <label>'.$as->created_at.'</label>
                    </td>
                </tr>
                ';
            }

            foreach($assessment3 as $key => $as){
                //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td >
                        <label>'.$key+1 .'</label>
                    </td>
                    <td >
                        <label>'.$as->title.'</label>
                    </td>
                    <td >
                        <label>'.$as->created_at.'</label>
                    </td>
                </tr>
                ';
            }
                $content .= '</tbody></table>
                
                <script>
                $(\'#table_registerstudent\').DataTable({
                    dom: \'lBfrtip\', // if you remove this line you will see the show entries dropdown
                    
                    buttons: [
                        \'copy\', \'csv\', \'excel\', \'pdf\', \'print\'
                    ],
                  });
                </script>';

            return $content;

        }

    }


    public function getUserLog(Request $request)
    {

        if($request->from != '' && $request->to != '')
        {

            $log = DB::table('tbluser_log')
            ->where([
                      ['ic', $request->user]
                      ])->whereBetween('date', [$request->from, $request->to])->get();

            $content = "";
            $content .= '
            <div class="table-responsive" style="width:99.7%">
            <table id="table_registerstudent" class="w-100 table table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead class="thead-themed">
                <th>No</th>
                <th>Remark</th>
                <th>Date</th>
                <th></th>
                </thead>
                <tbody>
            ';
            foreach($log as $key => $lg){
                //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
                $content .= '
                <tr>
                    <td >
                        <label>'.$key+1 .'</label>
                    </td>
                    <td >
                        <label>'.$lg->remark.'</label>
                    </td>
                    <td >
                        <label>'.$lg->date.'</label>
                    </td>
                </tr>
                ';
            }

                $content .= '</tbody></table>
                
                <script>
                $(\'#table_registerstudent\').DataTable({
                    dom: \'lBfrtip\', // if you remove this line you will see the show entries dropdown
                    
                    buttons: [
                        \'copy\', \'csv\', \'excel\', \'pdf\', \'print\'
                    ],
                  });
                </script>';

            return $content;

        }

    }
}
