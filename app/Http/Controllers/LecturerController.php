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

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        //forgot current session
        Session::forget(['User','CourseID','SessionID','CourseIDS','SessionIDS']);

        Session::put('User', Auth::user());
        
        //this function will get authenticated user and use relational models to join table
        $data = auth()->user()->subjects()
            ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
            ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->where('sessions.Status', 'ACTIVE')
            ->distinct('subjek.sub_id')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname')
            ->get();

            //dd($data);

        $sessions = DB::table('sessions')->where('Status', 'ACTIVE')->get();

        return view('lecturer', compact(['data','sessions']));
    }

    public function setting()
    {
        //dd(Auth::user());

        return view('settingLecturer');

    }

    public function updateSetting(Request $request)
    {

        $data = $request->validate([
            'pass' => ['max:10','required'],
            'conpass' => ['max:10','same:pass']
        ],[
            'conpass.same' => 'The Confirm Password and Password must match!'
        ]);

        Auth::user()->update([
            'password' =>  Hash::make($data['pass'])
        ]);

        return redirect()->back()->with('alert', 'You have successfully updated your setting!');

    }

    public function getCourseList(Request $request)
    {
        if(isset($request->search) && isset($request->session))
        {

        $data = auth()->user()->subjects()
            ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
            ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname')
            ->where('user_subjek.session_id','LIKE','%'.$request->session.'%')
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            //->groupBy('user_subjek.course_id')
            ->get();

        }elseif(isset($request->search))
        {

        $data = auth()->user()->subjects()
            ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
            ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname')
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            //->groupBy('user_subjek.course_id')
            ->get();

        }elseif(isset($request->session))
        {
        $data = auth()->user()->subjects()
            ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
            ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname')
            ->where('user_subjek.session_id','LIKE','%'.$request->session.'%')
            //->groupBy('user_subjek.course_id')
            ->get();
        }else{

        $data = auth()->user()->subjects()
            ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
            ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname')
            //->groupBy('user_subjek.course_id')
            ->get();

        }

        return view('lecturergetcourse', compact('data'));


    }

    public function courseSummary()
    {
        //$group = Auth::user()->subjects()->where('course_id', request()->id)->get();

        //return dd($group);

        Session::put('CourseID', request()->id);
        //$test = session::get('courseID');

        if(Session::get('SessionID') == null)
        {
        Session::put('SessionID', request()->session);
        }

        $course = DB::table('subjek')
                  ->join('tblprogramme', 'subjek.prgid', 'tblprogramme.id')
                  ->where('subjek.id', request()->id)->first();

        //dd($course);


        return view('lecturer.coursesummary.coursesummary', compact('course'))->with('course_id', request()->id);
    }

    public function deleteContent(Request $request)
    {

        $directory = DB::table('lecturer_dir')
                ->select('lecturer_dir.DrName as A')
                ->where('lecturer_dir.DrID', $request->dir)->first();

        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A;

        Storage::disk('linode')->deleteDirectory($dir);

        DB::table('lecturer_dir')->where('DrID', $request->dir)->delete();

        return true;

    }

    public function renameContent(Request $request)
    {

        if($request->name != null)
        {
        $directory = DB::table('lecturer_dir')->where('lecturer_dir.DrID', $request->dir)->update([
            'newDrName' => $request->name
        ]);

        //THIS IS TO RENAME USING HELPER STORAGE
        //$olddir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A;
        //$newdir = "classmaterial/" . Session::get('CourseID') . "/" . $request->name;
        //Storage::disk('linode')->move($olddir, $newdir);
        //DB::table('lecturer_dir')->where('lecturer_dir.DrID', $request->dir)->update([
            //'DrName' => $request->name
        //]);

        return true;

        }else{
            return false;
        }

    }

    public function deleteFolder(Request $request)
    {

        $directory = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B')
                ->where('material_dir.DrID', $request->dir)->first();

        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;

        Storage::disk('linode')->deleteDirectory($dir);

        DB::table('material_dir')->where('DrID', $request->dir)->delete();

        return true;

    }

    public function renameFolder(Request $request)
    {

        if($request->name != null)
        {
            $directory = DB::table('lecturer_dir')
            ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
            ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B')
            ->where('material_dir.DrID', $request->dir)->update([
                'material_dir.newDrName' => $request->name
            ]);

        //$olddir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;
        //$newdir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $request->name;
        //Storage::disk('linode')->move($olddir, $newdir);
        //DB::table('material_dir')->where('material_dir.DrID', $request->dir)->update([
        //    'DrName' => $request->name
        //]);

        return true;

        }else{
            return false;
        }

    }

    public function deleteSubfolder(Request $request)
    {

        $directory = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'materialsub_dir.DrID')
                ->where('materialsub_dir.DrID', $request->dir)->first();

        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" . $directory->C;

        Storage::disk('linode')->deleteDirectory($dir);

        DB::table('materialsub_dir')->where('DrID', $request->dir)->delete();

        return true;
    }

    public function deleteSubfolderFile(Request $request)
    {

        Storage::disk('linode')->delete($request->mats);

        return true;

    }

    public function deleteMaterial(Request $request)
    {

        Storage::disk('linode')->delete($request->mats);

        return true;

    }

    public function renameSubfolder(Request $request)
    {

        if($request->name != null)
        {
            DB::table('materialsub_dir')->where('DrID', $request->dir)->update([
                'newDrName' => $request->name
            ]);

        return true;

        }else{
            return false;
        }

    }

    public function renameFileSubfolder(Request $request)
    {

        if($request->name != null)
        {
        $directory = DB::table('lecturer_dir')
        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B')
        ->where('material_dir.DrID', $request->dir)->first();

        $olddir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" . $request->file;
        $newdir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" . $request->name . "." . $request->ext;
        Storage::disk('linode')->move($olddir, $newdir);

        return true;

        }else{
            return false;
        }

    }

    public function renameMaterial(Request $request)
    {

        if($request->name != null)
        {

        $directory = DB::table('lecturer_dir')
        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
        ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C')
        ->where('materialsub_dir.DrID', $request->dir)->first();

        $olddir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" . $directory->C . "/" . $request->file;
        $newdir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" . $directory->C . "/" . $request->name . "." . $request->ext;
        Storage::disk('linode')->move($olddir, $newdir);

        return true;

        }else{
            return false;
        }

    }

    public function coursecontent()
    {
        $user = Auth::user();

        $subid = DB::table('subjek')->where('id', request()->id)->pluck('sub_id');

        $folder = DB::table('lecturer_dir')
                  ->join('subjek', 'lecturer_dir.CourseID','subjek.id')->where('subjek.sub_id', $subid)->where('Addby', $user->ic)->get();

        $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

        //$test = Session::get('SessionID');

        //dd($folder);
        
        return view('lecturer.coursecontent.index', compact('folder', 'course'))->with('course_id', request()->id);
    }

    public function createContent()
    {
        return view('lecturer.coursecontent.createfolder')->with('course_id', request()->id);
    }

    public function storeContent(Request $request)
    {
        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $request->name;

        if(DB::table('lecturer_dir')->where([['DrName', $request->name],['CourseID', Session::get('CourseID')]])->exists())
        {

            return redirect()->back() ->with('alert', 'Folder already exists! Please try again with a different name.');

        }else{

            $data = $request->validate([
                'name' => ['required','string'],
                'pass' => ['max:10'],
                'conpass' => ['max:10','same:pass']
            ],[
                'conpass.same' => 'The Confirm Password and Password must match!'
            ]);

            $classmaterial  = Storage::disk('linode')->makeDirectory($dir);

            $user = auth()->user()->ic;

            //return dd($user);

            //$session = DB::table('sessions')->orderBy('SessionID', 'DESC')->first();

            $session = Session::get('SessionID');

            //dd($session);

            $pass = ($data['pass'] != null) ? Hash::make($data['pass']) : null;

            DB::table('lecturer_dir')->insert([
                'DrName' => $data['name'],
                'Password' => $pass,
                'CourseID' => $request->id,
                'SessionID' => $session,
                'Addby' => $user,
            ]);

            return redirect(route('lecturer.content', ['id' => $request->id]));

        }
        
    }

    public function courseDirectory(Request $request)
    {
        $directory = DB::table('lecturer_dir')->where('DrID', $request->dir)->first();

        if(!empty($directory->Password))
        {
            return view('lecturer.coursecontent.passwordfolder')->with('dir', $request->dir)->with('course_id', $request->id);

        }else{

            $mat_directory = DB::table('material_dir')->where('LecturerDirID', $directory->DrID)->get();

            $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

            return view('lecturer.coursecontent.materialdirectory', compact('mat_directory', 'course'))->with('dirid', $directory->DrID);
        }
    }

    public function passwordDirectory(Request $request)
    {                                           
        $password = DB::table('lecturer_dir')->where('DrID', request()->dir)->first();

        if(Hash::check($request->pass, $password->Password))
        {
            //$dir = 'classmaterial/'. $password->DrName;

            $mat_directory = DB::table('material_dir')->where('LecturerDirID', $password->DrID)->get();

            $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

            //$classmaterial  = Storage::disk('linode')->allFiles( $dir );

            return view('lecturer.coursecontent.materialdirectory', compact('mat_directory', 'course'))->with('dirid', $password->DrID);

        }else{

            return redirect()->back() ->with('alert', 'Wrong Password! Please try again.');

        }
    }

    public function createDirectory()
    {
        return view('lecturer.coursecontent.createfoldermaterial')->with('dirid', request()->dirid);
    }

    public function storeDirectory(Request $request)
    {
        $lectdir = DB::table('lecturer_dir')->where('DrID', $request->dir)->first();

        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $lectdir->DrName . "/" . $request->name;

        //dd($dir);

        if(DB::table('material_dir')->where('DrName', $request->name)->where('LecturerDirID', $lectdir->DrID)->exists())
        {

            return redirect()->back() ->with('alert', 'Folder already exists! Please try again with a different name.');

        }else{

            $data = $request->validate([
                'chapter' => ['required'],
                'name' => ['required','string'],
                'pass' => ['max:10'],
                'conpass' => ['max:10','same:pass']
            ],[
                'conpass.same' => 'The Confirm Password and Password must match!'
            ]);

            $classmaterial  = Storage::disk('linode')->makeDirectory($dir);

            $user = auth()->user()->ic;

            //return dd($user);

            //$session = DB::table('sessions')->orderBy('SessionID', 'DESC')->first();

            //dd($session);

            $pass = ($data['pass'] != null) ? Hash::make($data['pass']) : null;

            DB::table('material_dir')->insert([
                'ChapterNo' => $data['chapter'],
                'DrName' => $data['name'],
                'Password' => $pass,
                'LecturerDirID' => $request->dir,
                'Addby' => $user,
            ]);

            return redirect(route('lecturer.directory.prev', ['dir' => $request->dir]));

        }
        
    }

    public function prevcourseDirectory(Request $request)
    {
        $directory = DB::table('lecturer_dir')->where('DrID', $request->dir)->first();

        $mat_directory = DB::table('material_dir')->where('LecturerDirID', $directory->DrID)->get();

        $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

        return view('lecturer.coursecontent.materialdirectory', compact('mat_directory', 'course'))->with('dirid', $directory->DrID);

    }

    public function courseSubDirectory(Request $request)
    {
        $directory = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*')
                ->where('material_dir.DrID', $request->dir)->first();

        if(!empty($directory->Password))
        {
            return view('lecturer.coursecontent.passwordsubfolder')->with('dir', $request->dir);

        }else{

            $mat_directory = DB::table('materialsub_dir')->where('MaterialDirID', $directory->DrID)->get();

            $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

            $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;

            //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
            $classmaterial  = Storage::disk('linode')->files($dir);

            return view('lecturer.coursecontent.materialsubdirectory', compact('mat_directory', 'course', 'classmaterial'))->with('dirid', $directory->DrID)->with('prev', $directory->LecturerDirID);
        }
    }

    public function createSubDirectory()
    {
        $chapter = DB::table('material_dir')->where('DrID', request()->dir)->first();

        //dd(request()->dir);
        
        return view('lecturer.coursecontent.createfoldersubmaterial', compact('chapter'))->with('dirid', request()->dir);
    }

    public function storeSubDirectory(Request $request)
    {
        $lectdir = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'lecturer_dir.DrID', 'lecturer_dir.CourseID')
                ->where('material_dir.DrID', $request->dir)->first();

        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $lectdir->A . "/" . $lectdir->B . "/" . $request->name;

        //dd($dir);

        if(DB::table('materialsub_dir')->where('DrName', $request->name)->where('MaterialDirID', $lectdir->DrID)->exists())
        {

            return redirect()->back() ->with('alert', 'Folder already exists! Please try again with a different name.');

        }else{

            $data = $request->validate([
                'chapter' => ['required'],
                'name' => ['required','string'],
                'pass' => ['max:10'],
                'conpass' => ['max:10','same:pass']
            ],[
                'conpass.same' => 'The Confirm Password and Password must match!'
            ]);

            $mat = DB::table('materialsub_dir')->where('MaterialDirID', $request->dir);

            $max = $mat->max('SubChapterNo');

            $check = $mat->get();

            if(count($check) > 0)
            {
                $data['chapter'] = $max + 0.1;
            }

            //dd($data['chapter']);

            $classmaterial  = Storage::disk('linode')->makeDirectory($dir);

            $user = auth()->user()->ic;

            //return dd($user);

            //$session = DB::table('sessions')->orderBy('SessionID', 'DESC')->first();

            //dd($session);

            $pass = ($data['pass'] != null) ? Hash::make($data['pass']) : null;

            DB::table('materialsub_dir')->insert([
                'SubChapterNo' => $data['chapter'],
                'DrName' => $data['name'],
                'Password' => $pass,
                'MaterialDirID' => $request->dir,
                'Addby' => $user,
            ]);

            return redirect(route('lecturer.subdirectory.prev', ['dir' => $request->dir]));

        }
    }

    public function storefileSubDirectory(Request $request)
    {

        $directory = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*', 'lecturer_dir.CourseID')
                ->where('material_dir.DrID', $request->dir)->first();
        
        //dd($dirName);

        $file = $request->file('fileUpload');

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;

        //dd($file_name);

        $classmaterial = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;

        $dirpath = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" .$newname;

        

        if(! file_exists($newname)){
            Storage::disk('linode')->putFileAs(
                $classmaterial,
                $file,
                $newname,
                'public'
              );

            return redirect(route('lecturer.subdirectory.prev', ['dir' =>  $request->dir]));
        }

    }

    public function prevcourseSubDirectory(Request $request)
    {

        $directory = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*')
                ->where('material_dir.DrID', $request->dir)->first();

        $mat_directory = DB::table('materialsub_dir')->where('MaterialDirID', $directory->DrID)->get();

        $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;

        //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
        $classmaterial  = Storage::disk('linode')->files($dir);

        return view('lecturer.coursecontent.materialsubdirectory', compact('mat_directory', 'course', 'classmaterial'))->with('dirid', $directory->DrID)->with('prev', $directory->LecturerDirID);

    }

    public function passwordSubDirectory(Request $request)
    {

        $password = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'material_dir.*')
                ->where('material_dir.DrID', $request->dir)->first();

        if(Hash::check($request->pass, $password->Password))
        {
            //$dir = 'classmaterial/'. $password->DrName;

            $mat_directory = DB::table('materialsub_dir')->where('MaterialDirID', $password->DrID)->get();

            $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

            $dir = "classmaterial/" . Session::get('CourseID') . "/" . $password->A . "/" . $password->B;

            //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
            $classmaterial  = Storage::disk('linode')->files($dir);

            return view('lecturer.coursecontent.materialsubdirectory', compact('mat_directory', 'course', 'classmaterial'))->with('dirid', $password->DrID)->with('prev', $password->LecturerDirID);
        }else{

            return redirect()->back() ->with('alert', 'Wrong Password! Please try again.');

        }
    }

    public function DirectoryContent(Request $request)
    {
        $directory = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'materialsub_dir.DrID')
                ->where('materialsub_dir.DrID', $request->dir)->first();

        //dd($directory);

        if(!empty($directory->Password))
        {
            return view('lecturer.coursecontent.passwordcontent')->with('dir', $request->dir);

        }else{

            $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();
            
            $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" . $directory->C;

            $classmaterial  = Storage::disk('linode')->allFiles($dir);

            //$url = Storage::disk('linode')->url($classmaterial);

            //dd($classmaterial);

            return view('lecturer.coursecontent.coursematerial', compact('classmaterial', 'course'))->with('dirid', $directory->DrID)->with('prev', $directory->MaterialDirID);
        }
    }

    public function uploadMaterial(Request $request)
    {
        $dirName = DB::table('lecturer_dir')
                ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
                ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
                ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'lecturer_dir.CourseID')
                ->where('materialsub_dir.DrID', $request->id)->first();
        
        //dd($dirName);

        $file = $request->file('fileUpload');

        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename . "." . $file_ext;

        //dd($file_name);

        $classmaterial = "classmaterial/" . Session::get('CourseID') . "/" . $dirName->A . "/" . $dirName->B . "/" . $dirName->C;

        $dirpath = "classmaterial/" . Session::get('CourseID') . "/" . $dirName->A . "/" . $dirName->B . "/" . $dirName->C . "/" .$newname;

        

        if(! file_exists($newname)){
            Storage::disk('linode')->putFileAs(
                $classmaterial,
                $file,
                $newname,
                'public'
              );

            return redirect(route('lecturer.directory.content.prev', ['dir' =>  $request->id]));
        }
    }

    public function prevDirectoryContent(Request $request)
    {

        $directory = DB::table('lecturer_dir')
        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
        ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'materialsub_dir.DrID', 'lecturer_dir.CourseID')
        ->where('materialsub_dir.DrID', $request->dir)->first();

        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" . $directory->C;

        $classmaterial  = Storage::disk('linode')->allFiles( $dir );

        $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

        return view('lecturer.coursecontent.coursematerial', compact('classmaterial','course'))->with('dirid', $directory->DrID)->with('prev', $directory->MaterialDirID);

    }

    public function passwordContent(Request $request)
    {
        
        $password = DB::table('lecturer_dir')
        ->join('material_dir', 'lecturer_dir.DrID', 'material_dir.LecturerDirID')
        ->join('materialsub_dir', 'material_dir.DrID', 'materialsub_dir.MaterialDirID')
        ->select('lecturer_dir.DrName as A', 'material_dir.DrName as B', 'materialsub_dir.DrName as C', 'materialsub_dir.Password', 'materialsub_dir.MaterialDirID', 'materialsub_dir.DrID', 'lecturer_dir.CourseID')
        ->where('materialsub_dir.DrID', $request->dir)->first();


        if(Hash::check($request->pass, $password->Password))
        {
            //$dir = 'classmaterial/'. $password->DrName;

            $dir = "classmaterial/" . Session::get('CourseID') . "/". $password->A . "/" . $password->B . "/" . $password->C;

            $classmaterial  = Storage::disk('linode')->allFiles( $dir );

            $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();
    
            return view('lecturer.coursecontent.coursematerial', compact('classmaterial', 'course'))->with('dirid', $password->DrID)->with('prev', $password->MaterialDirID);
        }else{

            return redirect()->back() ->with('alert', 'Wrong Password! Please try again.');

        }
    }

    public function classSchedule()
    {

        return view('lecturer.class.schedule');

    }

    public function scheduleGetGroup()
    {

        $lecturer2 = Auth::user();

        //dd($lecturer);

        $courseid = Session::get('CourseID');

       
        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->where([
                    ['subjek.id', $courseid],
                    ['user_subjek.user_ic', $lecturer2->ic]
                ])->groupBy('student_subjek.group_name')
                ->select('user_subjek.*','student_subjek.group_name')->get();

        //dd($group)

        $content = "";

        $content .= "<option value='0' disabled selected>-</option>";
        foreach($group as $grp){

            $lecturer = User::where('ic', $grp->user_ic)->first();

            $content .= '<option data-style="btn-inverse"  
            data-content=\'<div class="row" >
                <div class="col-md-2">
                <div class="d-flex justify-content-center">
                    <img src="" 
                        height="auto" width="70%" class="bg-light ms-0 me-2 rounded-circle">
                        </div>
                </div>
                <div class="col-md-10 align-self-center lh-lg">
                    <span><strong>'. $grp->group_name .'</strong></span><br>
                    <span><strong>'. $lecturer->name .'</strong></span><br>
                    <span>'. $lecturer->email .' | <strong class="text-fade"">'.$lecturer->faculty .'</strong></span><br>
                    <span class="text-fade"></span>
                </div>
            </div>\' value='. $grp->id . '|' . $grp->group_name .'></option>';
        }
        
        return $content;

    }

    public function getSchedule(Request $request)
    {
        $group = explode('|', $request->group);

        $schedule = DB::table('tblclassschedule')->where('groupid', $group[0])->where('groupname', $group[1])->orderBy('id')->get();

        if(count($schedule) > 0)
        {
            return view('lecturer.class.getshcedule', compact('schedule'));

        }else{

            return view('lecturer.class.getshcedule');
        }
    }

    public function scheduleInsertGroup(Request $request){

        $groups = $request->groupselect;
        $group = explode('|', $groups);
        $groupschedules = $request->groupschedules;
        $classid = 0;
        
  
        $validator = Validator::make($request->all(), [
            'groupselect' => 'required',
            'groupschedules' => 'required',
        ]);
  
        if ($validator->fails()) {
            return ["message"=>"Field Error", "error" => $validator->messages()->get('*')];
        }

        //dd($group);

        if($group != 0)
        {
  
            try{ 
                DB::beginTransaction();
                DB::connection()->enableQueryLog();

                try{
                    $groupschedules = json_decode($groupschedules);
                    $upsert = [];
                    foreach($groupschedules as $schedule){
                        array_push($upsert, [
                        'groupid' => $group[0],
                        'groupname' => $group[1],
                        'classday' => $schedule->day,
                        'classstarttime' => date('H:i:s', strtotime($schedule->starttime)), 
                        'classendtime' =>  date('H:i:s', strtotime($schedule->endtime)),
                        'classstatusid' => $schedule->status
                        ]);
                    }

                    DB::table('tblclassschedule')->upsert($upsert, ['groupid','groupname','classday']);
                }catch(QueryException $ex){
                    DB::rollback();
                    if($ex->getCode() == 23000){
                        return ["message"=>"Class code already existed inside the system"];
                    }else{
                        \Log::debug($ex);
                        return ["message"=>"DB Error"];
                    }
                }

                DB::commit();
            }catch(Exception $ex){
                return ["message"=>"Error"];
            }
        }else{
            return ["message"=>"Please select group from group list first!"];
        }

        return ["message"=>"Success", "id" => $classid];
    }

    public function classAttendance()
    {
        return view('lecturer.class.attendance');
    }

    public function attendanceGetGroup()
    {
        $courseid = Session::get('CourseID');

        $lecturer = Auth::user();

        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->where([
            ['subjek.id', $courseid],
            ['user_subjek.session_id', Session::get('SessionID')],
            ['user_subjek.user_ic', $lecturer->ic]
        ])->groupBy('student_subjek.group_name')
        ->select('user_subjek.*','student_subjek.group_name')->get();

        $content = "";

        $content .= "<option value='0' selected disabled>-</option>";
        foreach($group as $grp){

            $content .= '<option data-style="btn-inverse"
            data-content=\'<div class="row" >
                <div class="col-md-2">
                <div class="d-flex justify-content-center">
                    <img src="" 
                        height="auto" width="70%" class="bg-light ms-0 me-2 rounded-circle">
                        </div>
                </div>
                <div class="col-md-10 align-self-center lh-lg">
                    <span><strong>'. $grp->group_name .'</strong></span><br>
                    <span><strong>'. $lecturer->name .'</strong></span><br>
                    <span>'. $lecturer->email .' | <strong class="text-fade"">'.$lecturer->faculty .'</strong></span><br>
                    <span class="text-fade"></span>
                </div>
            </div>\' value='. $grp->id . '|' . $grp->group_name .' ></option>';
        }
        
        return $content;
    }

    public function getStudents(Request $request)
    {
        $group = explode('|', $request->group);

        $students = student::join('students', 'student_subjek.student_ic', 'students.ic')->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')->where('group_id', $group[0])->where('group_name', $group[1])->where('student_subjek.sessionid', Session::get('SessionID'))->get();

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>Name</th>
            <th>Matric No</th>
            <th>Session</th>
            <th>Status</th>
            <th></th>
            </thead>
            <tbody>
        ';
        foreach($students as $student){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <label class="text-dark"><strong>'.$student->name.'</strong></label><br>
                    <label>IC: '.$student->student_ic.'</label>
                </td>
                <td >
                    <label>'.$student->no_matric.'</label>
                </td>
                <td >
                    <p class="text-bold text-fade">'.$student->SessionName.'</p>
                </td>
                <td >
                    <p class="text-bold text-fade">'.$student->status.'</p>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="student_checkbox_'.$student->student_ic.'"
                            class="filled-in" name="student[]" value="'.$student->student_ic.'" 
                        >
                        <label for="student_checkbox_'.$student->student_ic.'"> </label>
                    </div>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';

            return $content;
    }

    public function getDate(Request $request)
    {
        //$day = DateTime::createFromFormat('d/m/Y', $request->date);
        $day = Carbon::parse($request->date)->format('l');

        $schedule = DB::table('tblclassschedule')
        ->where('groupid', $request->group)->where('classday', $day)->first();

        if($schedule->classstatusid == 1)
        {
            return ["message"=>"Success", 'dayid' => $schedule->id];
        }else{
            return ["message"=>"Fail", "day" => $day];
        }

    }

    public function storeAttendance(Request $request)
    {

        $data = $request->validate([
            'group' => ['required'],
            'date' => ['required'],
            //'schedule' => ['required'],
            'student' => ['required']
        ]);

        $group = explode('|', $data['group']);

        //$date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');

        foreach($data['student'] as $std)
        {
            if(DB::table('tblclassattendance')->where([['student_ic', $std],['groupid', $group[0]],['groupname', $group[1]],['classdate', $data['date']]])->exists())
            {
                
            }else{
                DB::table('tblclassattendance')->insert([
                    'student_ic' => $std,
                    'groupid' => $group[0],
                    'groupname' => $group[1],
                    //'classscheduleid' => $data['schedule'],
                    'classdate' => $data['date']
                ]);
            }
        }

        return redirect()->back()->with('message', 'Student attendance has been submitted!');

    }

    public function onlineClass() 
    {
        $user = Auth::user();

        $courseid = Session::get('CourseID');

        $folder = DB::table('lecturer_dir')
        ->where([
            ['CourseID', $courseid],
            ['Addby', $user->ic]
            ])->get();

        return view('lecturer.class.onlineclass', compact('folder'));

    }

    public function getChapters(Request $request)
    {
        $chapter = DB::table('material_dir')->where('LecturerDirID', $request->folder)->get();

        $content = "";

        $content .= "<option value='0' disabled selected>-</option>";
        foreach($chapter as $chp){

            //$lecturer = User::where('ic', $grp->user_ic)->first();

            $content .= '<option value='. $chp->DrID .'> Chapter '. $chp->ChapterNo .' : '. $chp->DrName .'</option>';
        }
        
        return $content;

    }

    public function getSubChapters(Request $request)
    {

        $subchapter = DB::table('materialsub_dir')->where('MaterialDirID', $request->chapter)->get();

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
                    <label>'.$sub->SubChapterNo.'</label>
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

    public function storeOnlineClass(Request $request)
    {

        $data = $request->validate([
            'group' => ['required'],
            'date' => ['required'],
            'time_from' => ['required'],
            'time_to' => ['required'],
            'class_link' => ['required'],
            'classdescription' => ['']
        ]);

        $group = explode('|', $data['group']);

        //dd($data['date']);

        //$date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');

        $id = DB::table('onlineclass')->insertGetId([
            'groupid' => $group[0],
            'groupname' => $group[1],
            'classdate' => $data['date'],
            'classlink' => $data['class_link'],
            'classdescription' => $data['classdescription'],
            'classstarttime' => $data['time_from'],
            'classendtime' => $data['time_to']
        ]);

        foreach($request->chapter as $chp)
        {
            DB::table('classchapter')->insert([
                'chapterid' => $chp,
                'classid' => $id
            ]);
        }

        return redirect()->back()->with('message', 'Online Class has successfully submitted, please check online class table!');
        

    }

    public function OnlineClassList()
    {
        $totalstd = [];

        $chapters = [];

        $user = Auth::user();

        $course = Session::get('CourseID');

        $class = DB::table('onlineclass')
                 ->join('user_subjek', 'onlineclass.groupid', 'user_subjek.id')
                 ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                 ->select('onlineclass.*')
                 ->where('user_subjek.user_ic', $user->ic)
                 ->where('subjek.id', $course)
                 ->orderBy('onlineclass.id', 'DESC')
                 ->paginate(5);

        //dd($class);

        foreach ($class as $clss)
        {
            $totalstd[] = student::where('group_id', $clss->groupid)->count();

            $chapters[] = DB::table('classchapter')
                        ->join('materialsub_dir', 'classchapter.chapterid', 'materialsub_dir.DrID')
                        ->where('classid', $clss->id)->get();
        }

        //dd($totalstd);
           
        return view('lecturer.class.listonlineclass', compact([
            'class',
            'totalstd',
            'chapters'
        ]));

    }

    public function OnlineClassListDelete(Request $request)
    {

        DB::table('onlineclass')->where('id', $request->id)->delete();

        DB::table('classchapter')->where('classid', $request->id)->delete();

        return true;

    }

    function OnlineClassListEdit()
    {

        $user = Auth::user();

            $courseid = Session::get('CourseID');

            $folder = DB::table('lecturer_dir')
            ->where([
                ['CourseID', $courseid],
                ['Addby', $user->ic]
                ])->get();

            $class = DB::table('onlineclass')->where('id', request()->id)->first();

            $date = Carbon::createFromFormat('Y-m-d', $class->classdate)->format('d/m/Y');

            //dd($class);

            return view('lecturer.class.onlineclassedit', compact(['folder', 'class', 'date']));

    }

    function OnlineClassListUpdate(Request $request)
    {
        //dd($request->id);
        
        $data = $request->validate([
            'group' => ['required'],
            'date' => ['required'],
            'time_from' => ['required'],
            'time_to' => ['required'],
            'class_link' => ['required'],
            'classdescription' => ['']
        ]);

        $date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');

        $id = DB::table('onlineclass')->where('id', $request->id)->update([
            'groupid' => $data['group'],
            'classdate' => $date,
            'classlink' => $data['class_link'],
            'classdescription' => $data['classdescription'],
            'classstarttime' => $data['time_from'],
            'classendtime' => $data['time_to']
        ]);


        //dd($upid->id);
        if(count($request->chapter) > 0)
        {
            DB::table('classchapter')->where('classid', $request->id)->delete();

            foreach($request->chapter as $chp)
            {
                DB::table('classchapter')->insert([
                    'chapterid' => $chp,
                    'classid' => $request->id
                ]);
            }
        }

        return redirect(route('lecturer.onlineclass.list'))->with('message', 'Online Class has successfully submitted, please check online class table!');

    }

    public function announcement() 
    {
        $user = Auth::user();

        $courseid = Session::get('CourseID');

        $folder = DB::table('lecturer_dir')
        ->where([
            ['CourseID', $courseid],
            ['Addby', $user->ic]
            ])->get();

        return view('lecturer.class.announcement', compact('folder'));

    }

    public function announcementGetGroupList(Request $request)
    {

        $courseid = Session::get('CourseID');

        $lecturer = Auth::user();

        $group = subject::join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
        ->join('users', 'user_subjek.user_ic','users.ic')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->where([
            ['subjek.id', $courseid],
            ['user_subjek.user_ic', $lecturer->ic]
        ])->groupBy('student_subjek.group_name')
        ->select('user_subjek.*','student_subjek.group_name', 'users.name')->get();

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>group</th>
            <th>Name</th>
            <th></th>
            </thead>
            <tbody>
        ';
        foreach($group as $grp){
            //$registered = ($student->status == 'ACTIVE') ? 'checked' : '';
            $content .= '
            <tr>
                <td >
                    <label>'.$grp->group_name.'</label>
                </td>
                <td >
                    <label>'.$grp->name.'</label>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="group_checkbox_'.$grp->id . '|' . $grp->group_name.'"
                            class="filled-in" name="group[]" value="'.$grp->id . '|' . $grp->group_name.'" 
                        >
                        <label for="group_checkbox_'.$grp->id . '|' . $grp->group_name.'"> </label>
                    </div>
                </td>
            </tr>
            ';
            }
            $content .= '</tbody></table>';

            return $content;

    }

    public function storeAnnouncement(Request $request)
    {

        $data = $request->validate([
            'group' => ['required'],
            'chapter' => [''],
            'class_link' => [''],
            'classdescription' => ['']
        ]);

        $grps = array_shift($data['group']);

        $grps2 = explode('|', $grps);

        //dd($groups);

        //$date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');

        //dd($group);

        $id = DB::table('announcement')->insertGetId([
            'groupid' => $grps2[0],
            //'groupname' => $group[1],
            //'classdate' => $data['date'],
            'classlink' => $data['class_link'],
            'classdescription' => $data['classdescription'],
            //'classstarttime' => $data['time_from'],
            //'classendtime' => $data['time_to']
        ]);

        foreach($request->group as $grp)
        {
            $group = explode('|', $grp); 
            
            DB::table('announcement_groupname')->insert([
                'groupname' => $group[1],
                'announcementid' => $id
            ]);

            $students = DB::table('students')
                          ->join('student_subjek', 'students.ic', 'student_subjek.student_ic')
                          ->where([
                            ['student_subjek.group_id', $group[0]],
                            ['student_subjek.group_name', $group[1]]
                          ])->pluck('email');               
        }

        $test = array($students);

        //dd($test);

        if($request->chapter != null)
        {
            foreach($request->chapter as $chp)
            {
                DB::table('announcement_chapter')->insert([
                    'chapterid' => $chp,
                    'announcementid' => $id
                ]);
            }
        }

        Mail::send('emails.welcome', $data, function($message) use ($test)
        {    
            $message->to($test)->subject('Test');    
        });

        //dd($data);

        return redirect()->back()->with('message', 'Online Class has successfully submitted, please check online class table!');
        

    }

    public function announcementList()
    {
        $totalstd = [];

        $chapters = [];

        $allgroup = [];

        $user = Auth::user();

        $course = Session::get('CourseID');

        $class = DB::table('announcement')
                 ->join('user_subjek', 'announcement.groupid', 'user_subjek.id')
                 ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                 ->select('announcement.*')
                 ->where('user_subjek.user_ic', $user->ic)
                 ->where('subjek.id', $course)
                 ->orderBy('announcement.id', 'DESC')
                 ->paginate(5);

        //dd($class);

        foreach ($class as $clss)
        {
            //$totalstd[] = student::where('group_id', $clss->groupid)->count();

            $group = DB::table('announcement_groupname')
                    ->join('student_subjek', function($join){
                        $join->on('announcement_groupname.groupname', 'student_subjek.group_name');
                    })
                    ->where('announcement_groupname.announcementid', $clss->id)
                    ->where('student_subjek.group_id', $clss->groupid);

            $allgroup[] = $group->groupBy('student_subjek.group_name')->get();
                    
            $totalstd[] = $group->count();

            $chapters[] = DB::table('announcement_chapter')
                        ->join('materialsub_dir', 'announcement_chapter.chapterid', 'materialsub_dir.DrID')
                        ->where('announcementid', $clss->id)->get();
        }

        //dd($totalstd);
           
        return view('lecturer.class.listannouncement', compact([
            'class',
            'allgroup',
            'totalstd',
            'chapters'
        ]));

    }

    public function announcementListDelete(Request $request)
    {

        DB::table('announcement')->where('id', $request->id)->delete();

        DB::table('announcement_chapter')->where('announcementid', $request->id)->delete();

        DB::table('announcement_groupname')->where('announcementid', $request->id)->delete();

        return true;

    }

    public function assessmentreport()
    {
        $overallquiz = [];
        $quizanswer = [];

        $overalltest = [];
        $testanswer = [];

        $overallassign = [];
        $assignanswer = [];

        $overallmidterm = [];
        $midtermanswer = [];

        $overallfinal = [];
        $finalanswer = [];

        $overallpaperwork = [];
        $paperworkanswer = [];

        $overallpractical = [];
        $practicalanswer = [];

        $overallother = [];
        $otheranswer = [];

        $user = Auth::user();

        $groups = DB::table('user_subjek')
                  ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                  ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                  ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id')
                  ->where([
                     ['user_subjek.user_ic', $user->ic],
                     ['user_subjek.session_id', Session::get('SessionID')],
                     ['subjek.id', request()->id]
                  ])->groupBy('student_subjek.group_name')->get();

        foreach($groups as $ky => $grp)
        {


                $students[] = $data = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id','students.*')
                ->where([
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', Session::get('SessionID')],
                ['subjek.id', request()->id]
                ])->where('student_subjek.group_name', $grp->group_name)->get();

                //QUIZ

                $quizs = DB::table('tblclassquiz')
                        ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                        ->where([
                            ['tblclassquiz.classid', request()->id],
                            ['tblclassquiz.sessionid', Session::get('SessionID')],
                            ['tblclassquiz_group.groupname', $grp->group_name],
                            ['tblclassquiz.status', '!=', 3]
                        ]);

                $quiz[] = $quizs->get();

                $quizid = $quizs->pluck('tblclassquiz.id');

                $totalquiz = $quizs->sum('tblclassquiz.total_mark');

                //TEST

                $tests = DB::table('tblclasstest')
                        ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                        ->where([
                            ['tblclasstest.classid', request()->id],
                            ['tblclasstest.sessionid', Session::get('SessionID')],
                            ['tblclasstest_group.groupname', $grp->group_name],
                            ['tblclasstest.status', '!=', 3]
                        ]);

                $test[] = $tests->get();

                $testid = $tests->pluck('tblclasstest.id');

                $totaltest = $tests->sum('tblclasstest.total_mark');

                //ASSIGNMENT

                $assigns = DB::table('tblclassassign')
                        ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                        ->where([
                            ['tblclassassign.classid', request()->id],
                            ['tblclassassign.sessionid', Session::get('SessionID')],
                            ['tblclassassign_group.groupname', $grp->group_name],
                            ['tblclassassign.status', '!=', 3]
                        ]);

                $assign[] = $assigns->get();

                $assignid = $assigns->pluck('tblclassassign.id');

                $totalassign = $assigns->sum('tblclassassign.total_mark');

                //////////////////////////////////////////////////////////////////////////////////////////
            
                foreach($students[$ky] as $keys => $std)
                {
    
                    // QUIZ

                    foreach($quiz[$ky] as $key =>$qz)
                    {
                    
                    $quizanswer[$ky][$keys][$key] = DB::table('tblclassstudentquiz')->where('userid', $std->ic)->where('quizid', $qz->quizid)->first();

                    }

                    $sumquiz[$ky][$keys] = DB::table('tblclassstudentquiz')->where('userid', $std->ic)->whereIn('quizid', $quizid)->sum('final_mark');

                    $percentquiz = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.sub_id', $std->course_id],
                                ['assessment', 'quiz']
                                ])->first();

                    if($quizs = DB::table('tblclassquiz')
                    ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                    ->where([
                        ['tblclassquiz.classid', request()->id],
                        ['tblclassquiz.sessionid', Session::get('SessionID')],
                        ['tblclassquiz_group.groupname', $grp->group_name]
                    ])->exists()){
                        if($percentquiz != null)
                        {
                            if(DB::table('tblclassquiz')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalquiz);
                                $overallquiz[$ky][$keys] = $sumquiz[$ky][$keys] / $totalquiz * $percentquiz->mark_percentage;
                            }else{
                                array_push($overallquiz, 0);
                            }
            
                        }else{
                            array_push($overallquiz, 0);
                        }
                    }else{
                        array_push($overallquiz, 0);
                    }


                    // TEST
                    
                    foreach($test[$ky] as $key =>$qz)
                    {
                    
                    $testanswer[$ky][$keys][$key] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->where('testid', $qz->testid)->first();

                    }

                    $sumtest[$ky][$keys] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->whereIn('testid', $testid)->sum('final_mark');

                    $percenttest = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.sub_id', $std->course_id],
                                ['assessment', 'test']
                                ])->first();

                    if($tests = DB::table('tblclasstest')
                    ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                    ->where([
                        ['tblclasstest.classid', request()->id],
                        ['tblclasstest.sessionid', Session::get('SessionID')],
                        ['tblclasstest_group.groupname', $grp->group_name]
                    ])->exists()){
                        if($percenttest != null)
                        {
                            if(DB::table('tblclasstest')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totaltest);
                                $overalltest[$ky][$keys] = $sumtest[$ky][$keys] / $totaltest * $percenttest->mark_percentage;
                            }else{
                                array_push($overalltest, 0);
                            }
            
                        }else{
                            array_push($overalltest, 0);
                        }
                    }else{
                        array_push($overalltest, 0);
                    }


                    // ASSIGNMENT
                    
                    foreach($assign[$ky] as $key =>$qz)
                    {
                    
                    $assignanswer[$ky][$keys][$key] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->where('assignid', $qz->assignid)->first();

                    }

                    $sumassign[$ky][$keys] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->whereIn('assignid', $assignid)->sum('final_mark');

                    $percentassign = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.sub_id', $std->course_id],
                                ['assessment', 'assign']
                                ])->first();

                    if($assigns = DB::table('tblclassassign')
                    ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                    ->where([
                        ['tblclassassign.classid', request()->id],
                        ['tblclassassign.sessionid', Session::get('SessionID')],
                        ['tblclassassign_group.groupname', $grp->group_name]
                    ])->exists()){
                        if($percentassign != null)
                        {
                            if(DB::table('tblclassassign')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalassign);
                                $overallassign[$ky][$keys] = $sumassign[$ky][$keys] / $totalassign * $percentassign->mark_percentage;
                            }else{
                                array_push($overallassign, 0);
                            }
            
                        }else{
                            array_push($overallassign, 0);
                        }
                    }else{
                        array_push($overallassign, 0);
                    }
            
                }
        }

        //dd($overallassign);

        return view('lecturer.courseassessment.studentreport', compact('groups', 'students',
                                                                       'quiz', 'quizanswer','overallquiz',
                                                                       'test', 'testanswer','overalltest',
                                                                       'assign', 'assignanswer','overallassign'));

    }

    public function studentreport()
    {
        $percentagequiz = "";

        $percentageassign = "";

        $percentagemidterm = "";

        $percentagefinal = "";

        $percentagepaperwork = "";

        $percentagepractical = "";

        $percentageother = "";

        $student = DB::table('students')
                ->join('student_subjek', 'students.ic', 'student_subjek.student_ic')
                ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                ->where('students.ic', request()->student)
                ->where([
                    ['subjek.id', Session::get('CourseID')],
                    ['student_subjek.sessionid', Session::get('SessionID')]
                ])->first();
        
        //dd($student);

        //QUIZ

        $percentquiz = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'quiz']
                                ])->first();

        //dd($percentquiz);
  
        //get marked quiz
        $quiz = DB::table('tblclassstudentquiz')
                ->join('tblclassquiz', 'tblclassstudentquiz.quizid', 'tblclassquiz.id')
                ->where([
                    ['tblclassstudentquiz.userid', request()->student],
                    ['tblclassquiz.classid', request()->id],
                    ['tblclassquiz.sessionid', Session::get('SessionID')]
                ]);
        
        $totalquiz = $quiz->sum('tblclassquiz.total_mark');

        $markquiz = $quiz->sum('tblclassstudentquiz.final_mark');

        if($percentquiz != null)
        {
            $percentagequiz = $percentquiz->mark_percentage;
        }

        $quizlist = $quiz->get();

        if($totalquiz != 0 && $markquiz != 0)
        {
            $total_allquiz = round(( (int)$markquiz / (int)$totalquiz ) * (int)$percentagequiz);
        }else{
            $total_allquiz = 0;
        }


        //ASSIGNMENT

        $percentassign = DB::table('tblclassmarks')
                        ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                        ['subjek.id', Session::get('CourseID')],
                        ['assessment', 'assignment']
                        ])->first();

        //dd($percent);
  
        //get marked assign
        $assign = DB::table('tblclassstudentassign')
                ->join('tblclassassign', 'tblclassstudentassign.assignid', 'tblclassassign.id')
                ->where([
                    ['tblclassstudentassign.userid', request()->student],
                    ['tblclassassign.classid', request()->id],
                    ['tblclassassign.sessionid', Session::get('SessionID')]
                ]);
        
        $totalassign = $assign->sum('tblclassassign.total_mark');

        $markassign = $assign->sum('tblclassstudentassign.final_mark');

        if($percentassign != null)
        {
            $percentageassign = $percentassign->mark_percentage;
        }

        $assignlist = $assign->get();

        if($totalassign != 0 && $markassign != 0)
        {
            $total_allassign = round(( (int)$markassign / (int)$totalassign ) * (int)$percentageassign);
        }else{
            $total_allassign = 0;
        }


        // MIDTERM

        $percentmidterm = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'midterm']
                                ])->first();

        //dd($percent);
  
        $midterm = DB::table('tblclassstudentmidterm')
                ->join('tblclassmidterm', 'tblclassstudentmidterm.midtermid', 'tblclassmidterm.id')
                ->where([
                    ['tblclassstudentmidterm.userid', request()->student],
                    ['tblclassmidterm.classid', request()->id],
                    ['tblclassmidterm.sessionid', Session::get('SessionID')]
                ]);
        
        $totalmidterm = $midterm->sum('tblclassmidterm.total_mark');
   
        $markmidterm = $midterm->sum('tblclassstudentmidterm.final_mark');

        if($percentmidterm != null)
        {
            $percentagemidterm = $percentmidterm->mark_percentage;
        }

        $midtermlist = $midterm->get();

        if($totalmidterm != 0 && $markmidterm != 0)
        {
            $total_allmidterm = round(( (int)$markmidterm / (int)$totalmidterm ) * (int)$percentagemidterm);
        }else{
            $total_allmidterm = 0;
        }

        
        //FINAL

        $percentfinal = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'final']
                                ])->first();

        //dd($percent);
  
        $final = DB::table('tblclassstudentfinal')
                ->join('tblclassfinal', 'tblclassstudentfinal.finalid', 'tblclassfinal.id')
                ->where([
                    ['tblclassstudentfinal.userid', request()->student],
                    ['tblclassfinal.classid', request()->id],
                    ['tblclassfinal.sessionid', Session::get('SessionID')]
                ]);
        
        $totalfinal = $final->sum('tblclassfinal.total_mark');
   
        $markfinal = $final->sum('tblclassstudentfinal.final_mark');

        if($percentfinal != null)
        {
            $percentagefinal = $percentfinal->mark_percentage;
        }

        $finallist = $final->get();

        if($totalfinal != 0 && $markfinal != 0)
        {
            $total_allfinal = round(( (int)$markfinal / (int)$totalfinal ) * (int)$percentagefinal);
        }else{
            $total_allfinal = 0;
        }

        //PAPERWORK

        $percentpaperwork = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'paperwork']
                                ])->first();

        //dd($percent);
  
        //get marked paperwork
        $paperwork = DB::table('tblclassstudentpaperwork')
                ->join('tblclasspaperwork', 'tblclassstudentpaperwork.paperworkid', 'tblclasspaperwork.id')
                ->where([
                    ['tblclassstudentpaperwork.userid', request()->student],
                    ['tblclasspaperwork.classid', request()->id],
                    ['tblclasspaperwork.sessionid', Session::get('SessionID')]
                ]);
        
        $totalpaperwork = $paperwork->sum('tblclasspaperwork.total_mark');

        $markpaperwork = $paperwork->sum('tblclassstudentpaperwork.final_mark');

        if($percentpaperwork != null)
        {
            $percentagepaperwork = $percentpaperwork->mark_percentage;
        }

        $paperworklist = $paperwork->get();

        if($totalpaperwork != 0 && $markpaperwork != 0)
        {
            $total_allpaperwork = round(( (int)$markpaperwork / (int)$totalpaperwork ) * (int)$percentagepaperwork);
        }else{
            $total_allpaperwork = 0;
        }


        //PRACTICAL

        $percentpractical = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'practical']
                                ])->first();

        //dd($percent);
  
        //get marked practical
        $practical = DB::table('tblclassstudentpractical')
                ->join('tblclasspractical', 'tblclassstudentpractical.practicalid', 'tblclasspractical.id')
                ->where([
                    ['tblclassstudentpractical.userid', request()->student],
                    ['tblclasspractical.classid', request()->id],
                    ['tblclasspractical.sessionid', Session::get('SessionID')]
                ]);
        
        $totalpractical = $practical->sum('tblclasspractical.total_mark');

        $markpractical = $practical->sum('tblclassstudentpractical.final_mark');

        if($percentpractical != null)
        {
            $percentagepractical = $percentpractical->mark_percentage;
        }

        $practicallist = $practical->get();

        if($totalpractical != 0 && $markpractical != 0)
        {
            $total_allpractical = round(( (int)$markpractical / (int)$totalpractical ) * (int)$percentagepractical);
        }else{
            $total_allpractical = 0;
        }


        //OTHER

        $percentother = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'lain-lain']
                                ])->first();

        //dd($percentother);
  
        //get marked other
        $other = DB::table('tblclassstudentother')
                ->join('tblclassother', 'tblclassstudentother.otherid', 'tblclassother.id')
                ->where([
                    ['tblclassstudentother.userid', request()->student],
                    ['tblclassother.classid', request()->id],
                    ['tblclassother.sessionid', Session::get('SessionID')]
                ]);
        
        $totalother = $other->sum('tblclassother.total_mark');

        $markother = $other->sum('tblclassstudentother.final_mark');

        if($percentother != null)
        {
            $percentageother = $percentother->mark_percentage;
        }

        $otherlist = $other->get();

        if($totalother != 0 && $markother != 0)
        {
            $total_allother = round(( (int)$markother / (int)$totalother ) * (int)$percentageother);
        }else{
            $total_allother = 0;
        }

        return view('lecturer.courseassessment.reportdetails', compact('student', 'quizlist', 'totalquiz', 'markquiz', 'percentagequiz', 'total_allquiz',
                                                                                  'assignlist', 'totalassign', 'markassign', 'percentageassign', 'total_allassign',
                                                                                  'midtermlist', 'totalmidterm', 'markmidterm', 'percentagemidterm', 'total_allmidterm',
                                                                                  'finallist', 'totalfinal', 'markfinal', 'percentagefinal', 'total_allfinal',
                                                                                  'paperworklist', 'totalpaperwork', 'markpaperwork', 'percentagepaperwork', 'total_allpaperwork',
                                                                                  'practicallist', 'totalpractical', 'markpractical', 'percentagepractical', 'total_allpractical',
                                                                                  'otherlist', 'totalother', 'markother', 'percentageother', 'total_allother'));
    }


    public function listAttendance()
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

    public function reportAttendance(Request $request)
    {

        $courseid = Session::get('CourseID');

        $sessionid = Session::get('SessionID');

        $student = DB::table('student_subjek')
                    ->join('students', 'student_subjek.student_ic', 'students.ic')->where('student_subjek.group_id', $request->group)->where('student_subjek.group_name', $request->name);
                    //->join('user_subjek', 'student_subjek.group_id', 'user_subjek.id')
                    //->join('subjek', 'user_subjek.course_id', 'subjek.sub_id');
        
        $students = $student->get();

        $group = $student->join('user_subjek', 'student_subjek.group_id', 'user_subjek.id')->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')->select('user_subjek.*', 'student_subjek.group_name', 'subjek.*')->first();

        $date = $request->date;

        foreach($students as $std)
        {

            $list = DB::table('tblclassattendance')
            ->join('user_subjek', 'tblclassattendance.groupid', 'user_subjek.id')
            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->where([
                ['subjek.id', $courseid],
                ['user_subjek.session_id', $sessionid],
                ['tblclassattendance.groupid', $request->group],
                ['tblclassattendance.classdate', $request->date],
                ['tblclassattendance.student_ic', $std->ic]
            ])->groupBy('tblclassattendance.student_ic');

            $lists[] = $list->get();


        }
            
        return view('lecturer.class.attendancereport', compact('lists', 'students', 'group', 'date'));

    }

    public function deletAttendance(Request $request)
    {

        DB::table('tblclassattendance')->where([
            ['classdate', $request->date],
            ['groupid', $request->group],
            ['groupname', $request->name]
        ])->delete();

        return back()->with('message', 'Attendance have successfully deleted!');

    }


    public function libraryIndex()
    {

        $lecturer = DB::table('users')
                    ->join('user_subjek', 'users.ic', 'user_subjek.user_ic')
                    ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                    ->where([
                        ['subjek.id', Session::get('CourseID')],
                        ['user_subjek.session_id', Session::get('SessionID')]
                    ])->get();

        //dd($lecturer);

        return view('lecturer.library.library', compact('lecturer'));

    }

    public function getContent(Request $request)
    {

        $folder = DB::table('lecturer_dir')
                   ->where([
                    ['Addby', $request->ic],
                    ['CourseID', Session::get('CourseID')]
                    ])->get();

        return view('lecturer.library.getSubfolder', compact('folder'));

    }

    public function getSubFolder(Request $request)
    {

        $subfolder = DB::table('material_dir')->where('LecturerDirID', $request->id)->get();

        $prev0 = $folder = DB::table('lecturer_dir')->where('DrID', $request->id)->first();

        return view('lecturer.library.getSubfolder', compact('subfolder','prev0'));

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

        return view('lecturer.library.getSubfolder', compact('subfolder2', 'classmaterial','prev'));

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

        return view('lecturer.library.getSubfolder', compact('classmaterial','prev2'));
    }

    public function getQuiz(Request $request)
    {

        $quiz = DB::table('tblclassquiz')
                ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                ->join('tblclassquiz_chapter', 'tblclassquiz.id', 'tblclassquiz_chapter.quizid')
                ->where([
                    ['tblclassquiz.addby', $request->ic],
                    ['tblclassquiz.classid', Session::get('CourseID')]
                ])->select('tblclassquiz.*')->get();


        return view('lecturer.library.getQuiz', compact('quiz'));

    }

    public function getTest(Request $request)
    {

        $test = DB::table('tblclasstest')
                ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                ->join('tblclasstest_chapter', 'tblclasstest.id', 'tblclasstest_chapter.testid')
                ->where([
                    ['tblclasstest.addby', $request->ic],
                    ['tblclasstest.classid', Session::get('CourseID')]
                ])->select('tblclasstest.*')->get();


        return view('lecturer.library.getTest', compact('test'));

    }

    public function getAssignment(Request $request)
    {

        $assign = DB::table('tblclassassign')
                ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                ->join('tblclassassign_chapter', 'tblclassassign.id', 'tblclassassign_chapter.assignid')
                ->where([
                    ['tblclassassign.addby', $request->ic],
                    ['tblclassassign.classid', Session::get('CourseID')]
                ])->select('tblclassassign.*')->get();


        return view('lecturer.library.getAssignment', compact('assign'));

    }
  
}


