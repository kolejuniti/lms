<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\subject;
use App\Models\student;
use App\Models\Tblevent2;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;
use Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Twilio\Rest\Client;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

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
            ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
            ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->where('sessions.Status', 'ACTIVE')
            ->where('tblprogramme.progstatusid', 1)
            ->groupBy('subjek.sub_id', 'user_subjek.session_id')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname','tblprogramme.progcode')
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
        $user = Auth::user();
        
        // Check form type to determine what to update
        if ($request->form_type === 'profile') {
            // Validate profile data
            $data = $request->validate([
                'no_tel' => ['required', 'string', 'max:15'],
            ]);

            // Update phone number
            $user->update([
                'no_tel' => $data['no_tel']
            ]);

            return redirect()->back()->with('alert', 'Your profile has been updated successfully!');
            
        } elseif ($request->form_type === 'profile_image') {
            // Validate image upload
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'], // 5MB max
            ]);

            $imageArray = [];
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $imageName = $request->file('image')->getClientOriginalName();
                $filepath = "storage/";

                // Store image in Linode Object Storage
                Storage::disk('linode')->putFileAs(
                    $filepath,
                    $request->file('image'),
                    $imageName,
                    'public'
                );

                $imagePath = $filepath . $imageName;

                // Resize image using Intervention Image
                try {
                    $image = Image::make(Storage::disk('linode')->url($imagePath))->fit(1000, 1000);
                    $image->save($imagePath);
                } catch (\Exception $e) {
                    // If image processing fails, continue without resizing
                }

                $imageArray = ['image' => $imagePath];
            }

            // Update user image
            $user->update($imageArray);

            return redirect()->back()->with('alert', 'Your profile picture has been updated successfully!');
            
        } elseif ($request->form_type === 'security') {
            // Validate security data
            $data = $request->validate([
                'pass' => ['max:10','required'],
                'conpass' => ['max:10','same:pass']
            ],[
                'conpass.same' => 'The Confirm Password and Password must match!'
            ]);

            // Update password
            $user->update([
                'password' => Hash::make($data['pass'])
            ]);

            return redirect()->back()->with('alert', 'Your password has been updated successfully!');
            
        } else {
            // Legacy support - if no form_type is specified, assume it's password update
            $data = $request->validate([
                'pass' => ['max:10','required'],
                'conpass' => ['max:10','same:pass']
            ],[
                'conpass.same' => 'The Confirm Password and Password must match!'
            ]);

            $user->update([
                'password' => Hash::make($data['pass'])
            ]);

            return redirect()->back()->with('alert', 'You have successfully updated your setting!');
        }
    }

    public function settingTheme(Request $request)
    {
        $user = Auth::user();

        if(DB::table('user_setting')->where('user_ic', $user->ic)->exists())
        {

            DB::table('user_setting')->where('user_ic', $user->ic)->update([
                'theme' => $request->theme
            ]);

        }else{

            DB::table('user_setting')->insert([
                'user_ic' => $user->ic,
                'theme' => $request->theme
            ]);

        }


    }

    public function getCourseList(Request $request)
    {
        if(isset($request->search) && isset($request->session))
        {
        //forgot current session
        Session::forget(['CourseID','SessionID','CourseIDS','SessionIDS']);

        $data = auth()->user()->subjects()
            ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
            ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
            ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->where('sessions.Status', 'ACTIVE')
            ->where('tblprogramme.progstatusid', 1)
            ->groupBy('subjek.sub_id', 'user_subjek.session_id')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname','tblprogramme.progcode')
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            ->orwhere('subjek.course_code','LIKE','%'.$request->search."%")
            ->where('user_subjek.session_id','LIKE','%'.$request->session.'%')
            ->get();

        }elseif(isset($request->search))
        {
        //forgot current session
        Session::forget(['CourseID','SessionID','CourseIDS','SessionIDS']);

        $data = auth()->user()->subjects()
            ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
            ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
            ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->where('sessions.Status', 'ACTIVE')
            ->where('tblprogramme.progstatusid', 1)
            ->groupBy('subjek.sub_id', 'user_subjek.session_id')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname','tblprogramme.progcode')
            ->where('subjek.course_name','LIKE','%'.$request->search."%")
            ->orwhere('subjek.course_code','LIKE','%'.$request->search."%")
            ->get();

        }elseif(isset($request->session))
        {
        //forgot current session
        Session::forget(['CourseID','SessionID','CourseIDS','SessionIDS']);

        $data = auth()->user()->subjects()
            ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
            ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
            ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->where('sessions.Status', 'ACTIVE')
            ->where('tblprogramme.progstatusid', 1)
            ->groupBy('subjek.sub_id', 'user_subjek.session_id')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname','tblprogramme.progcode')
            ->where('user_subjek.session_id','LIKE','%'.$request->session.'%')
            ->get();

        }else{
        //forgot current session
        Session::forget(['CourseID','SessionID','CourseIDS','SessionIDS']);

        $data = auth()->user()->subjects()
            ->join('subjek', 'user_subjek.course_id','=','subjek.sub_id')
            ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
            ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
            ->join('sessions', 'user_subjek.session_id','sessions.SessionID')
            ->where('sessions.Status', 'ACTIVE')
            ->where('tblprogramme.progstatusid', 1)
            ->groupBy('subjek.sub_id', 'user_subjek.session_id')
            ->select('subjek.*','user_subjek.course_id','sessions.SessionName','sessions.SessionID','tblprogramme.progname','tblprogramme.progcode')
            ->get();

        }

        return view('lecturergetcourse', compact('data'));


    }

    /**
     * Get active assessment period for current user and session
     */
    private function getActiveAssessmentPeriod()
    {
        $currentDate = now()->format('Y-m-d');
        $currentUserIc = auth()->user()->ic;
        $currentSessionId = Session::get('SessionID');

        if (!$currentSessionId) {
            return null;
        }

        $period = DB::table('tblassessment_period')
            ->where('Start', '<=', $currentDate)
            ->where('End', '>=', $currentDate)
            ->get()
            ->filter(function ($period) use ($currentUserIc, $currentSessionId) {
                $userIcs = json_decode($period->user_ic, true) ?: [];
                $sessions = json_decode($period->session, true) ?: [];
                
                return in_array($currentUserIc, $userIcs) && 
                       in_array($currentSessionId, $sessions);
            })
            ->first();

        return $period;
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
                  ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                  ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
                  ->where('subjek.id', request()->id)->first();

        $program = DB::table('tblprogramme')
                   ->join('subjek_structure', 'tblprogramme.id', 'subjek_structure.program_id')
                   ->join('subjek', 'subjek_structure.courseID', 'subjek.sub_id')
                   ->where('subjek_structure.courseID', $course->sub_id)
                   ->groupBy('tblprogramme.id')
                   ->get();

        $collection = collect($program);

        $summary = DB::table('subjek')
                ->join('subjek_structure', 'subjek.sub_id', 'subjek_structure.courseID')
                ->join('tblprogramme', 'subjek_structure.program_id', 'tblprogramme.id')
                ->whereIn('subjek.sub_id', $collection->pluck('sub_id'))
                ->whereIn('subjek_structure.program_id', $collection->pluck('program_id'))->groupBy('tblprogramme.id')->get();

        return view('lecturer.coursesummary.coursesummary', compact('course','program','summary'))->with('course_id', request()->id);
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
    
    public function deleteUrl(Request $request)
    {

        DB::table('materialsub_url')->where('DrID', $request->id)->delete();

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

            $url = DB::table('materialsub_url')->where('MaterialDirID', $directory->DrID)->get();

            $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

            $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;

            //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
            $classmaterial  = Storage::disk('linode')->files($dir);

            return view('lecturer.coursecontent.materialsubdirectory', compact('mat_directory', 'url', 'course', 'classmaterial'))->with('dirid', $directory->DrID)->with('prev', $directory->LecturerDirID);
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

        if (isset($request->fileUpload)) {

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

            // List of common video MIME types
            $videoMIMETypes = [
                'video/mp4',
                'video/quicktime',
                'video/x-msvideo',
                'video/x-ms-wmv',
                'video/x-flv',
                'video/webm',
                'video/3gpp',
                'video/3gpp2',
                'video/ogg'
            ];

            $fileMimeType = $file->getMimeType();

            if (in_array($fileMimeType, $videoMIMETypes)) {
                
                return back()->with('alert', 'The uploaded file must not be a video');
            }            

            //dd($file_name);

            $classmaterial = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;

            $dirpath = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B . "/" .$newname;

            if (! file_exists($newname)) {
                Storage::disk('linode')->putFileAs(
                    $classmaterial,
                    $file,
                    $newname,
                    'public'
                );

                return redirect(route('lecturer.subdirectory.prev', ['dir' =>  $request->dir]));
            }

        }elseif(isset($request->url))
        {
            $user = auth()->user()->ic;

            DB::table('materialsub_url')->insert([
                'url' => $request->url,
                'description' => $request->description,
                'MaterialDirID' => $request->dir,
                'Addby' => $user,
            ]);

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

        $url = DB::table('materialsub_url')->where('MaterialDirID', $directory->DrID)->get();

        $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

        $dir = "classmaterial/" . Session::get('CourseID') . "/" . $directory->A . "/" . $directory->B;

        //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
        $classmaterial  = Storage::disk('linode')->files($dir);

        return view('lecturer.coursecontent.materialsubdirectory', compact('mat_directory', 'url', 'course', 'classmaterial'))->with('dirid', $directory->DrID)->with('prev', $directory->LecturerDirID);

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

            $url = DB::table('materialsub_url')->where('MaterialDirID', $password->DrID)->get();

            $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

            $dir = "classmaterial/" . Session::get('CourseID') . "/" . $password->A . "/" . $password->B;

            //this is to get file in the specific folder, unlike AllFiles to get everything from all folder
            $classmaterial  = Storage::disk('linode')->files($dir);

            return view('lecturer.coursecontent.materialsubdirectory', compact('mat_directory', 'url', 'course', 'classmaterial'))->with('dirid', $password->DrID)->with('prev', $password->LecturerDirID);
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

            $url = DB::table('materialsub_url')->where('MaterialSubDirID', $directory->DrID)->get();

            return view('lecturer.coursecontent.coursematerial', compact('classmaterial', 'course', 'url'))->with('dirid', $directory->DrID)->with('prev', $directory->MaterialDirID);
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

        if(isset($request->fileUpload))
        {

            $file = $request->file('fileUpload');

            $file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $fileInfo = pathinfo($file_name);
            $filename = $fileInfo['filename'];
            $newname = $filename . "." . $file_ext;

            // List of common video MIME types
            $videoMIMETypes = [
                'video/mp4',
                'video/quicktime',
                'video/x-msvideo',
                'video/x-ms-wmv',
                'video/x-flv',
                'video/webm',
                'video/3gpp',
                'video/3gpp2',
                'video/ogg'
            ];

            $fileMimeType = $file->getMimeType();

            if (in_array($fileMimeType, $videoMIMETypes)) {
                
                return back()->with('alert', 'The uploaded file must not be a video');
            }   

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

        }elseif(isset($request->url))
        {

            $user = auth()->user()->ic;

            DB::table('materialsub_url')->insert([
                'url' => $request->url,
                'description' => $request->description,
                'MaterialSubDirID' => $request->id,
                'Addby' => $user,
            ]);

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

        $url = DB::table('materialsub_url')->where('MaterialSubDirID', $directory->DrID)->get();

        return view('lecturer.coursecontent.coursematerial', compact('classmaterial', 'course', 'url'))->with('dirid', $directory->DrID)->with('prev', $directory->MaterialDirID);

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

            $url = DB::table('materialsub_url')->where('MaterialSubDirID', $password->DrID)->get();

            return view('lecturer.coursecontent.coursematerial', compact('classmaterial', 'course', 'url'))->with('dirid', $password->DrID)->with('prev', $password->MaterialDirID);

        }else{

            return redirect()->back() ->with('alert', 'Wrong Password! Please try again.');

        }
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
                    <span><strong>'. htmlentities($lecturer->name, ENT_QUOTES) .'</strong></span><br>
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
                    <span><strong>'. htmlentities($lecturer->name, ENT_QUOTES) .'</strong></span><br>
                    <span>'. $lecturer->email .' | <strong class="text-fade"">'.$lecturer->faculty .'</strong></span><br>
                    <span class="text-fade"></span>
                </div>
            </div>\' value='. $grp->id . '|' . $grp->group_name .' ></option>';
        }
        
        return $content;
    }

    public function getStudentProgram(Request $request)
    {

        $group = explode('|', $request->group);

        $program = student::join('students', 'student_subjek.student_ic', 'students.ic')
                    ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                    ->where('student_subjek.group_id', $group[0])->where('student_subjek.group_name', $group[1])
                    ->where('student_subjek.sessionid', Session::get('SessionID'))
                    ->whereNotIn('students.status', [4,5,6,7,16])
                    ->groupBy('tblprogramme.id')
                    ->select('tblprogramme.*')
                    ->get();

        $content = "";

        $content .= "<option value='0' selected disabled>-</option>";
        foreach($program as $prg){

            $content .= '<option data-style="btn-inverse" value="'. $prg->id .'" >'. $prg->progname .' ('. $prg->progcode .')</option>';
        }
        
        return $content;

    }

    public function getStudents(Request $request)
    {
        $group = explode('|', $request->group);

            $student = student::join('students', 'student_subjek.student_ic', 'students.ic')
                        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                        ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                        ->where('group_id', $group[0])->where('group_name', $group[1])
                        ->where('student_subjek.sessionid', Session::get('SessionID'))
                        ->whereNotIn('students.status', [4,5,6,7,16]);

                        if(isset($request->program) && count($request->program) > 1)
                        {

                            $student->orderBy('students.program');

                        }else{

                            $student->orderBy('students.name');

                        }

                        $students = $student->get();

        $content = "";
        $content .= '
        <div class="table-responsive" style="width:99.7%">
        <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
            <thead class="thead-themed">
            <th>Name</th>
            <th>Matric No</th>
            <th>Session</th>
            <th>Program</th>
            <th>Status</th>
            <th></th>
            <th>Excuse</th>
            <th>MC</th>
            <th>NC/LC</th>
            </thead>
            <tbody>
        ';
        $content .= '
            <tr>
                <td >
                    <label class="text-dark"><strong>SELECT ALL</strong></label><br>
                </td>
                <td >
                    <label></label>
                </td>
                <td >
                    <p class="text-bold text-fade"></p>
                </td>
                <td >
                    <p class="text-bold text-fade"></p>
                </td>
                <td >
                    <p class="text-bold text-fade"></p>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="checkboxAll"
                            class="filled-in" name="checkall"
                            onclick="CheckAll(this)"
                        >
                        <label for="checkboxAll"> </label>
                    </div>
                </td>
                <td >
                    <p class="text-bold text-fade"></p>
                </td>
                <td >
                    <p class="text-bold text-fade"></p>
                </td>
            </tr>
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
                    <p class="text-bold text-fade">'.$student->progcode.'</p>
                </td>
                <td >
                    <p class="text-bold text-fade">'.$student->status.'</p>
                </td>';
                if(isset($request->program))
                {

                    if(in_array($student->program, $request->program))
                    {


                        $content .= '<td>
                            <div class="pull-right" >
                                <input type="checkbox" id="student_checkbox_'.$student->no_matric.'"
                                    class="filled-in" name="student[]" value="'.$student->student_ic.'" 
                                >
                                <label for="student_checkbox_'.$student->no_matric.'"></label>
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" id="excuse_'.$student->no_matric.'"
                                    class="form-control" name="excuse[]" onkeyup="getExcuse('.$student->no_matric.')">
                                <input type="hidden" id="ic_'.$student->no_matric.'"
                                class="form-control" name="ic[]" value="'.$student->student_ic.'" disabled>
                                <label for="checkboxAll"> </label>
                            </div>
                        </td>
                        <td>
                            <div class="pull-right" >
                                <input type="checkbox" id="mc_'.$student->no_matric.'"
                                    class="filled-in" name="mc[]" value="'.$student->student_ic.'" onclick="getMC('.$student->no_matric.')"
                                >
                                <label for="mc_'.$student->no_matric.'"></label>
                            </div>
                        </td>';

                    }else{

                        $content .= '<td>
                            <div class="pull-right" >
                                <input type="checkbox" id="student_checkbox_'.$student->no_matric.'"
                                    class="filled-in" name="student[]" value="'.$student->student_ic.'" 
                                disabled>
                                <label for="student_checkbox_'.$student->no_matric.'"></label>
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" id="excuse_'.$student->no_matric.'"
                                    class="form-control" name="excuse[]" onkeyup="getExcuse('.$student->no_matric.')" disabled>
                                <input type="hidden" id="ic_'.$student->no_matric.'"
                                class="form-control" name="ic[]" value="'.$student->student_ic.'" disabled>
                                <label for="checkboxAll"> </label>
                            </div>
                        </td>
                        <td>
                            <div class="pull-right" >
                                <input type="checkbox" id="mc_'.$student->no_matric.'"
                                    class="filled-in" name="mc[]" value="'.$student->student_ic.'" onclick="getMC('.$student->no_matric.')"
                                disabled>
                                <label for="mc_'.$student->no_matric.'"></label>
                            </div>
                        </td>
                        <td>
                            <div class="pull-right" >
                                <input type="checkbox" id="lc_'.$student->no_matric.'"
                                    class="filled-in" name="lc[]" value="'.$student->student_ic.'"
                                checked onclick="event.preventDefault();">
                                <label for="lc_'.$student->no_matric.'"></label>
                            </div>
                        </td>';


                    }

                }else{

    $content .= '<td>
                    <div class="pull-right" >
                        <input type="checkbox" id="student_checkbox_'.$student->no_matric.'"
                            class="filled-in" name="student[]" value="'.$student->student_ic.'" 
                        >
                        <label for="student_checkbox_'.$student->no_matric.'"></label>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" id="excuse_'.$student->no_matric.'"
                            class="form-control" name="excuse[]" onkeyup="getExcuse('.$student->no_matric.')">
                        <input type="hidden" id="ic_'.$student->no_matric.'"
                        class="form-control" name="ic[]" value="'.$student->student_ic.'" disabled>
                        <label for="checkboxAll"> </label>
                    </div>
                </td>
                <td>
                    <div class="pull-right" >
                        <input type="checkbox" id="mc_'.$student->no_matric.'"
                            class="filled-in" name="mc[]" value="'.$student->student_ic.'" onclick="getMC('.$student->no_matric.')"
                        >
                        <label for="mc_'.$student->no_matric.'"></label>
                    </div>
                </td>';

                }
$content .= '</tr>
            ';
            }

            $content .= '
            <tr>
                <td >
                    <label class="text-dark"><strong>ALL ABSENT</strong></label><br>
                </td>
                <td >
                    <label></label>
                </td>
                <td >
                    <p class="text-bold text-fade"></p>
                </td>
                <td >
                    <p class="text-bold text-fade"></p>
                </td>
                <td >
                    <p class="text-bold text-fade"></p>
                </td>
                <td >
                    <div class="pull-right" >
                        <input type="checkbox" id="AbsentAlls"
                            class="filled-in" name="absentall"
                            onclick="AbsentAll(this)"
                        >
                        <label for="AbsentAlls"> </label>
                    </div>
                </td>
            </tr>
            ';
            
            $content .= '</tbody></table>';

            return $content;
    }

    public function printAttendance(Request $request)
    {

        $group = explode('|', $request->group);

        if(!empty($request->program))
        {

            $data['students'] = student::join('students', 'student_subjek.student_ic', 'students.ic')
                        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                        ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                        ->where('group_id', $group[0])->where('group_name', $group[1])
                        ->where('student_subjek.sessionid', Session::get('SessionID'))
                        ->whereIn('students.program', $request->program)
                        ->whereNotIn('students.status', [4,5,6,7,16])
                        ->orderBy('students.program')
                        ->orderBy('students.name')
                        ->get();

        }else{

            $data['students'] = student::join('students', 'student_subjek.student_ic', 'students.ic')
                        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                        ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                        ->where('group_id', $group[0])->where('group_name', $group[1])
                        ->where('student_subjek.sessionid', Session::get('SessionID'))
                        ->whereNotIn('students.status', [4,5,6,7,16])
                        ->orderBy('students.program')
                        ->orderBy('students.name')
                        ->get();

        }

        $data['course'] = DB::table('subjek')->where('id', Session::get('CourseID'))->first();

        $data['session'] = DB::table('sessions')->where('SessionID', Session::get('SessionID'))->first();

        return view('lecturer.class.printAttendance', compact('data'));

    }

    public function printExamination(Request $request)
    {
        $group = explode('|', $request->group);

        // Get all programs for this group
        $programs = student::join('students', 'student_subjek.student_ic', 'students.ic')
                    ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                    ->where('student_subjek.group_id', $group[0])
                    ->where('student_subjek.group_name', $group[1])
                    ->where('student_subjek.sessionid', Session::get('SessionID'))
                    ->whereNotIn('students.status', [4,5,6,7,16])
                    ->groupBy('tblprogramme.id')
                    ->select('tblprogramme.*')
                    ->orderBy('tblprogramme.progcode')
                    ->get();

        // Filter by selected programs if provided
        if(!empty($request->program)) {
            $programs = $programs->whereIn('id', $request->program);
        }

        $data = [];
        
        // Get students grouped by program
        foreach($programs as $program) {
            $students = student::join('students', 'student_subjek.student_ic', 'students.ic')
                        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                        ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                        ->where('student_subjek.group_id', $group[0])
                        ->where('student_subjek.group_name', $group[1])
                        ->where('student_subjek.sessionid', Session::get('SessionID'))
                        ->where('students.program', $program->id)
                        ->whereNotIn('students.status', [4,5,6,7,16])
                        ->orderBy('students.name')
                        ->select('students.*', 'tblprogramme.progcode', 'tblprogramme.progname', 'sessions.SessionName')
                        ->get();

            if($students->count() > 0) {
                $data[] = [
                    'program' => $program,
                    'students' => $students
                ];
            }
        }

        $course = DB::table('subjek')->where('id', Session::get('CourseID'))->first();
        $session = DB::table('sessions')->where('SessionID', Session::get('SessionID'))->first();
        
        // Pass group data to view
        $groupData = [
            'id' => $group[0],
            'name' => $group[1]
        ];

        return view('lecturer.class.printExamination', compact('data', 'course', 'session', 'groupData'));
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

        try {

            $data = $request->validate([
                'group' => ['required'],
                'date' => ['required'],
                'date2' => ['required'],
                'class' => ['required'],
                //'schedule' => ['required'],
                'student' => [],
                'absentall' => [],
                'excuse' => [],
                'ic' => [],
                'mc' => [],
                'lc' => [],
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Failed! Please fill all the input form')->withInput();
        }

        // Parse the times
        $start = Carbon::parse($data['date']);
        $end = Carbon::parse($data['date2']);

        // Calculate the difference
        $totalHours = $end->diffInHours($start);
        $totalMinutes = $end->diffInMinutes($start) % 60; // To get remaining minutes after hours

        //dd($totalHours);

        if($totalHours < 1 || $totalHours > 4 || $totalMinutes != 0)
        {

            return back()->with('alert', 'Total hours cannot be below 1 or above 4, please check the time range');

        }

        //dd(Session::get('CourseID'));

        $group = explode('|', $data['group']);

        //$date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');

        if(isset($data['absentall'])){

            //dd($data['absentall']);

            DB::table('tblclassattendance')->insert([
                'groupid' => $group[0],
                'groupname' => $group[1],
                //'classscheduleid' => $data['schedule'],\
                'classtype' => $data['class'],
                'classdate' => $data['date'],
                'classend' => $data['date2']
            ]);

        }else{

            if(isset($data['student']))
            {

                foreach($data['student'] as $std)
                {
                    if(DB::table('tblclassattendance')->where([['student_ic', $std],['groupid', $group[0]],['groupname', $group[1]],['classdate', $data['date']]])->exists())
                    {
                        
                    }else{
                        DB::table('tblclassattendance')->insert([
                            'student_ic' => $std,
                            'groupid' => $group[0],
                            'groupname' => $group[1],
                            'classtype' => $data['class'],
                            'classdate' => $data['date'],
                            'classend' => $data['date2']
                        ]);
                    }
                }

            }elseif(!empty(array_filter($data['excuse'], function($value) {
                return $value !== null;
            })) && isset($data['mc']) && isset($data['lc']))
            {



            }else{

                DB::table('tblclassattendance')->insert([
                    'groupid' => $group[0],
                    'groupname' => $group[1],
                    'classtype' => $data['class'],
                    'classdate' => $data['date'],
                    'classend' => $data['date2']
                ]);

            }

            
            // Filter the 'excuse' array from $data to remove any NULL values.
            // array_filter() is used to filter the array based on a callback function.
            $filtered_excuse = array_filter($data['excuse'], function ($value) {
                return !is_null($value);  // Returns TRUE if the value is NOT NULL, thus keeping it in the filtered array.
            });

            // Reindex the $filtered_excuse array so the keys start from 0 and go up sequentially.
            // This is useful especially if some items were removed by array_filter, to make sure array keys are consistent.
            $reindexed_excuse = array_values($filtered_excuse);

            // Loop through each excuse in the reindexed array.
            foreach($reindexed_excuse as $key => $exs) {
                
                // Check if a record with the given criteria already exists in the 'tblclassattendance' table.
                // The criteria include the student's IC, group ID, group name, and class date.
                if(DB::table('tblclassattendance')->where([
                    ['student_ic', $data['ic'][$key]],
                    ['groupid', $group[0]],
                    ['groupname', $group[1]],
                    ['classdate', $data['date']]
                ])->exists()) {
                    
                    // If the record exists, do nothing (this section is empty).
                    
                } else {
                    // If the record doesn't exist, insert a new record into the 'tblclassattendance' table.
                    // The data for the new record is populated using values from the $data array, the current excuse from the loop ($exs),
                    // and some additional data like group ID and group name.
                    DB::table('tblclassattendance')->insert([
                        'student_ic' => $data['ic'][$key],  // Student's IC from the $data array, using the current key from the loop.
                        'groupid' => $group[0],             // Group ID (presumably from a previously defined $group array).
                        'groupname' => $group[1],           // Group name (also from the $group array).
                        'excuse' => $exs,                   // The current excuse from the loop.
                        'classtype' => $data['class'],      // Class type from the $data array.
                        'classdate' => $data['date'],       // Class start date from the $data array.
                        'classend' => $data['date2']        // Class end date from the $data array.
                    ]);
                }
            }


            if(isset($data['mc']))
            {

                foreach($data['mc'] as $std)
                {
                    if(DB::table('tblclassattendance')->where([['student_ic', $std],['groupid', $group[0]],['groupname', $group[1]],['classdate', $data['date']]])->exists())
                    {
                        
                    }else{
                        DB::table('tblclassattendance')->insert([
                            'student_ic' => $std,
                            'groupid' => $group[0],
                            'groupname' => $group[1],
                            'mc' => TRUE,
                            'classtype' => $data['class'],
                            'classdate' => $data['date'],
                            'classend' => $data['date2']
                        ]);
                    }
                }
            }

            if(isset($data['lc']))
            {

                foreach($data['lc'] as $std)
                {
                    if(DB::table('tblclassattendance')->where([['student_ic', $std],['groupid', $group[0]],['groupname', $group[1]],['classdate', $data['date']]])->exists())
                    {
                        
                    }else{
                        DB::table('tblclassattendance')->insert([
                            'student_ic' => $std,
                            'groupid' => $group[0],
                            'groupname' => $group[1],
                            'lc' => TRUE,
                            'classtype' => $data['class'],
                            'classdate' => $data['date'],
                            'classend' => $data['date2']
                        ]);
                    }
                }
            }
        }

        //check warning

        $IC = (isset($data['student'])) ? $data['student'] : [];

        $MC = (isset($data['mc'])) ? $data['mc'] : [];

        $LC = (isset($data['lc'])) ? $data['lc'] : [];

        $exists = array_merge($IC,$MC,$LC);

        $absent = DB::table('student_subjek')->where([
            ['group_id', $group[0]],
            ['group_name', $group[1]]
        ])->whereNotIn('student_ic', $exists)->pluck('student_ic');

        $totalclass = DB::table('tblclassattendance')
                 ->where([
                    ['tblclassattendance.groupid', $group[0]],
                    ['tblclassattendance.groupname', $group[1]]
                 ])->groupBy('tblclassattendance.classdate')->count();

        $course_credit = DB::table('subjek')->where('id', Session::get('CourseID'))
                  ->select('course_credit', DB::raw('(course_credit * 14) as total'))->first();


        //try to get the total credit -> total credit (47) (2 x 14) - amount of student absent time (4)

        if($totalclass > 0){  
            
            if(count($absent) > 0)
            {

                foreach($absent as $key => $abs)
                {

                    $subQuery = DB::table('tblclassattendance')
                                    ->select('tblclassattendance.classdate')
                                    ->where([
                                        ['tblclassattendance.groupid', $group[0]],
                                        ['tblclassattendance.groupname', $group[1]],
                                        ['tblclassattendance.student_ic', $abs]
                                    ])
                                    ->groupBy('tblclassattendance.classdate');

                    $total_absent = DB::table('tblclassattendance')
                    ->select(
                        'tblclassattendance.classdate',
                        'tblclassattendance.classend',
                        DB::raw('HOUR(TIMEDIFF(tblclassattendance.classend, tblclassattendance.classdate)) as raw_diff')
                    )
                    ->where([
                        ['tblclassattendance.groupid', $group[0]],
                        ['tblclassattendance.groupname', $group[1]]
                    ])
                    ->whereNotIn('tblclassattendance.classdate', $subQuery)
                    ->groupBy('tblclassattendance.classdate', 'tblclassattendance.classdate', 'tblclassattendance.classend')
                    ->get();

                    $totalhours = 0; // Initialize totalhours to 0 before the loop

                    foreach ($total_absent as $ttl) {
                        $totalhours += $ttl->raw_diff; // Correct operator usage for adding values
                    }

                    $total_absent = $course_credit->total - $totalhours;

                    if(DB::table('tblstudent_warning')->where([['student_ic', $abs],['groupid', $group[0]],['groupname', $group[1]]])->exists())
                    {

                        if(DB::table('tblstudent_warning')->where([['student_ic', $abs],['groupid', $group[0]],['groupname', $group[1]]])->count() == 1)
                        {

                            $threshold = $course_credit->total - ($course_credit->course_credit * 2);

                            if($total_absent <= $threshold)
                            {

                                $percentage = ($total_absent / $course_credit->total) * 100;

                                //dd($percentage);

                                DB::table('tblstudent_warning')->insert([
                                    'student_ic' => $abs,
                                    'groupid' => $group[0],
                                    'groupname' => $group[1],
                                    'balance_attendance' => $total_absent,
                                    'percentage_attendance' => $percentage,
                                    'warning' => 2
                                ]);

                                //dd('try');

                                // $view = view('lecturer.class.surat_amaran.surat_amaran'); // Replace 'your_view_name' with the name of your HTML view

                                // //dd($view);
                                // $pdf = PDF::loadHTML($view->render())->stream();

                                // // Save the PDF to a temporary file
                                // $pdfPath = storage_path('app/public/tmp_pdf_' . time() . '.pdf');
                                // file_put_contents($pdfPath, $pdf->output());

                                // $publicPath = asset('storage/tmp_pdf_' . time() . '.pdf');

                                //dd('try');

                                // // Send to WhatsApp
                                // $sid    = env('TWILIO_SID');
                                // $token  = env('TWILIO_TOKEN');
                                // $twilio = new Client($sid, $token);

                                // $message = $twilio->messages->create(
                                //     'whatsapp:+60162667041', // the recipient's Whatsapp number
                                //     [
                                //         "from" => env('TWILIO_WHATSAPP_FROM'),
                                //         "body" => 'Here is your PDF document:'
                                //     ]
                                // );

                                // Cleanup: Delete the temporary PDF
                                // unlink($pdfPath);

                            }

                        }elseif(DB::table('tblstudent_warning')->where([['student_ic', $abs],['groupid', $group[0]],['groupname', $group[1]]])->count() == 2)
                        {

                            $threshold = $course_credit->total - ($course_credit->course_credit * 3);

                            if($total_absent <= $threshold)
                            {

                                $percentage = ($total_absent / $course_credit->total) * 100;

                                //dd($percentage);

                                DB::table('tblstudent_warning')->insert([
                                    'student_ic' => $abs,
                                    'groupid' => $group[0],
                                    'groupname' => $group[1],
                                    'balance_attendance' => $total_absent,
                                    'percentage_attendance' => $percentage,
                                    'warning' => 3
                                ]);

                                //dd('try');

                                // $view = view('lecturer.class.surat_amaran.surat_amaran'); // Replace 'your_view_name' with the name of your HTML view

                                // //dd($view);
                                // $pdf = PDF::loadHTML($view->render())->stream();

                                // // Save the PDF to a temporary file
                                // $pdfPath = storage_path('app/public/tmp_pdf_' . time() . '.pdf');
                                // file_put_contents($pdfPath, $pdf->output());

                                // $publicPath = asset('storage/tmp_pdf_' . time() . '.pdf');

                                //dd('try');

                                // // Send to WhatsApp
                                // $sid    = env('TWILIO_SID');
                                // $token  = env('TWILIO_TOKEN');
                                // $twilio = new Client($sid, $token);

                                // $message = $twilio->messages->create(
                                //     'whatsapp:+60162667041', // the recipient's Whatsapp number
                                //     [
                                //         "from" => env('TWILIO_WHATSAPP_FROM'),
                                //         "body" => 'Here is your PDF document:'
                                //     ]
                                // );

                                // Cleanup: Delete the temporary PDF
                                // unlink($pdfPath);

                            }

                        }


                    }else{

                        $threshold = $course_credit->total - $course_credit->course_credit;

                        if($total_absent <= $threshold)
                        {

                            $percentage = ($total_absent / $course_credit->total) * 100;

                            //dd($percentage);

                            DB::table('tblstudent_warning')->insert([
                                'student_ic' => $abs,
                                'groupid' => $group[0],
                                'groupname' => $group[1],
                                'balance_attendance' => $total_absent,
                                'percentage_attendance' => $percentage,
                                'warning' => 1
                            ]);

                            //dd('try');

                            // $view = view('lecturer.class.surat_amaran.surat_amaran'); // Replace 'your_view_name' with the name of your HTML view

                            // //dd($view);
                            // $pdf = PDF::loadHTML($view->render())->stream();

                            // // Save the PDF to a temporary file
                            // $pdfPath = storage_path('app/public/tmp_pdf_' . time() . '.pdf');
                            // file_put_contents($pdfPath, $pdf->output());

                            // $publicPath = asset('storage/tmp_pdf_' . time() . '.pdf');

                            //dd('try');

                            // // Send to WhatsApp
                            // $sid    = env('TWILIO_SID');
                            // $token  = env('TWILIO_TOKEN');
                            // $twilio = new Client($sid, $token);

                            // $message = $twilio->messages->create(
                            //     'whatsapp:+60162667041', // the recipient's Whatsapp number
                            //     [
                            //         "from" => env('TWILIO_WHATSAPP_FROM'),
                            //         "body" => 'Here is your PDF document:'
                            //     ]
                            // );

                            // Cleanup: Delete the temporary PDF
                            // unlink($pdfPath);

                        }

                    }

                }

            }

        }

        // set_time_limit(300); // Set time limit to 300 seconds (5 minutes)

        // // $view = view('lecturer.class.surat_amaran.surat_amaran'); // Replace 'your_view_name' with the name of your HTML view
        // // $pdf = PDF::loadHTML($view->render());

        // // // Use a single timestamp for both paths
        // // $timestamp = time();

        // // // Save the PDF to a temporary file
        // // $pdfPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'tmp_pdf_' . $timestamp . '.pdf');
        // // //dd($pdfPath);

        // // // Save the generated PDF to the path
        // // $pdf->save($pdfPath);

        // // // $relativePath = 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'tmp_pdf_' . $timestamp . '.pdf';
        // // // $pdf->save($relativePath);


        // // $publicPath = asset('storage/tmp_pdf_' . $timestamp . '.pdf');

        // // dd($publicPath);

        // // Send to WhatsApp
        // $sid    = env('TWILIO_SID');
        // $token  = env('TWILIO_TOKEN');
        // $twilio = new Client($sid, $token);

        // $message = $twilio->messages->create(
        //     'whatsapp:+60162667041', // the recipient's Whatsapp number
        //     [
        //         "from" => env('TWILIO_WHATSAPP_FROM'),
        //         "body" => 'Here is your PDF document:'
        //     ]
        // );

        // Cleanup: Delete the temporary PDF
        // unlink($pdfPath);


        return redirect()->back()->with('message', 'Student attendance has been submitted!');

    }
    
    public function classAttendanceEdit(Request $request)
    {
        
        $data['student'] = student::join('students', 'student_subjek.student_ic', 'students.ic')
                        ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                        ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                        ->where('group_id', $request->group)->where('group_name', $request->name)
                        ->where('student_subjek.sessionid', Session::get('SessionID'))
                        ->whereNotIn('students.status', [4,5,6,7,16])
                        ->orderBy('students.name')
                        ->get();

        foreach($data['student'] as $std)
        {

            $data['attendance'][] = DB::table('tblclassattendance')
            ->where([
             ['student_ic', $std->ic],
             ['classdate', $request->from],
             ['classend', $request->to],
             ['groupid', $request->group],
             ['groupname', $request->name]
            ])->first();

        }

        //dd($data['attendance']);

        $time = DB::table('tblclassattendance')
                           ->where([
                            ['classdate', $request->from],
                            ['classend', $request->to],
                            ['groupid', $request->group],
                            ['groupname', $request->name]
                           ])->first();

        $data['from'] = $time->classdate;

        $data['to'] = $time->classend;

        $data['type'] = $time->classtype;
        
        $data['group'] = $request->group;

        $data['name'] = $request->name;

        $data['program'] = student::join('students', 'student_subjek.student_ic', 'students.ic')
                    ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                    ->where('student_subjek.group_id', $request->group)->where('student_subjek.group_name', $request->name)
                    ->where('student_subjek.sessionid', Session::get('SessionID'))
                    ->whereNotIn('students.status', [4,5,6,7,16])
                    ->groupBy('tblprogramme.id')
                    ->select('tblprogramme.*')
                    ->get();
        
        return view('lecturer.class.attendance_edit', compact('data'));


    }

    public function updateAttendance(Request $request)
    {
        
        $data = $request->validate([
            'group' => ['required'],
            'date' => ['required'],
            'date2' => ['required'],
            'class' => ['required'],
            //'schedule' => ['required'],
            'student' => [],
            'absentall' => [],
            'excuse' => [],
            'ic' => [],
            'mc' => [],
            'lc' => [],
        ]);

        $group = explode('|', $data['group']);

        DB::table('tblclassattendance')->where([
            ['classdate', $request->oldfrom],
            ['classend', $request->oldto],
            ['groupid', $group[0]],
            ['groupname', $group[1]]
        ])->delete();

        if(isset($data['absentall'])){

            //dd($data['absentall']);

            DB::table('tblclassattendance')->insert([
                'groupid' => $group[0],
                'groupname' => $group[1],
                //'classscheduleid' => $data['schedule'],\
                'classtype' => $data['class'],
                'classdate' => $data['date'],
                'classend' => $data['date2']
            ]);

        }else{

            if(isset($data['student']))
            {

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
                            'classtype' => $data['class'],
                            'classdate' => $data['date'],
                            'classend' => $data['date2']
                        ]);
                    }
                }

            }

            if(isset($data['excuse']))
            {
            
                // Filter the 'excuse' array from $data to remove any NULL values.
                // array_filter() is used to filter the array based on a callback function.
                $filtered_excuse = array_filter($data['excuse'], function ($value) {
                    return !is_null($value);  // Returns TRUE if the value is NOT NULL, thus keeping it in the filtered array.
                });

                // Reindex the $filtered_excuse array so the keys start from 0 and go up sequentially.
                // This is useful especially if some items were removed by array_filter, to make sure array keys are consistent.
                $reindexed_excuse = array_values($filtered_excuse);

                // Loop through each excuse in the reindexed array.
                foreach($reindexed_excuse as $key => $exs) {
                    
                    // Check if a record with the given criteria already exists in the 'tblclassattendance' table.
                    // The criteria include the student's IC, group ID, group name, and class date.
                    if(DB::table('tblclassattendance')->where([
                        ['student_ic', $data['ic'][$key]],
                        ['groupid', $group[0]],
                        ['groupname', $group[1]],
                        ['classdate', $data['date']]
                    ])->exists()) {
                        
                        // If the record exists, do nothing (this section is empty).
                        
                    } else {
                        // If the record doesn't exist, insert a new record into the 'tblclassattendance' table.
                        // The data for the new record is populated using values from the $data array, the current excuse from the loop ($exs),
                        // and some additional data like group ID and group name.
                        DB::table('tblclassattendance')->insert([
                            'student_ic' => $data['ic'][$key],  // Student's IC from the $data array, using the current key from the loop.
                            'groupid' => $group[0],             // Group ID (presumably from a previously defined $group array).
                            'groupname' => $group[1],           // Group name (also from the $group array).
                            'excuse' => $exs,                   // The current excuse from the loop.
                            'classtype' => $data['class'],      // Class type from the $data array.
                            'classdate' => $data['date'],       // Class start date from the $data array.
                            'classend' => $data['date2']        // Class end date from the $data array.
                        ]);
                    }
                }

            }


            if(isset($data['mc']))
            {

                foreach($data['mc'] as $std)
                {
                    if(DB::table('tblclassattendance')->where([['student_ic', $std],['groupid', $group[0]],['groupname', $group[1]],['classdate', $data['date']]])->exists())
                    {
                        
                    }else{
                        DB::table('tblclassattendance')->insert([
                            'student_ic' => $std,
                            'groupid' => $group[0],
                            'groupname' => $group[1],
                            'mc' => TRUE,
                            'classtype' => $data['class'],
                            'classdate' => $data['date'],
                            'classend' => $data['date2']
                        ]);
                    }
                }
            }

            if(isset($data['lc']))
            {

                foreach($data['lc'] as $std)
                {
                    if(DB::table('tblclassattendance')->where([['student_ic', $std],['groupid', $group[0]],['groupname', $group[1]],['classdate', $data['date']]])->exists())
                    {
                        
                    }else{
                        DB::table('tblclassattendance')->insert([
                            'student_ic' => $std,
                            'groupid' => $group[0],
                            'groupname' => $group[1],
                            'lc' => TRUE,
                            'classtype' => $data['class'],
                            'classdate' => $data['date'],
                            'classend' => $data['date2']
                        ]);
                    }
                }
            }
        }

        //Enhanced warning check system - handles both new warnings and adjustment of existing warnings
        
        $IC = (isset($data['student'])) ? $data['student'] : [];
        $MC = (isset($data['mc'])) ? $data['mc'] : [];
        $LC = (isset($data['lc'])) ? $data['lc'] : [];
        
        // Get excuse array and filter for non-null values
        $excuses = (isset($data['excuse'])) ? array_filter($data['excuse'], function($value) { 
            return !is_null($value) && $value !== ''; 
        }) : [];
        
        // Get corresponding ICs for excuses
        $excuse_ics = [];
        if(isset($data['ic']) && count($excuses) > 0) {
            foreach($data['excuse'] as $key => $excuse) {
                if(!is_null($excuse) && $excuse !== '' && isset($data['ic'][$key])) {
                    $excuse_ics[] = $data['ic'][$key];
                }
            }
        }

        $exists = array_merge($IC, $MC, $LC);

        // Get all students in this group
        $all_students = DB::table('student_subjek')->where([
            ['group_id', $group[0]],
            ['group_name', $group[1]]
        ])->pluck('student_ic');

        $absent = $all_students->diff($exists);

        $totalclass = DB::table('tblclassattendance')
                 ->where([
                    ['tblclassattendance.groupid', $group[0]],
                    ['tblclassattendance.groupname', $group[1]]
                 ])->groupBy('tblclassattendance.classdate')->count();

        $course_credit = DB::table('subjek')->where('id', Session::get('CourseID'))
                  ->select('course_credit', DB::raw('(course_credit * 14) as total'))->first();

        if($totalclass > 0){  
            
            // STEP 1: Check and clean up warnings for students who are now present/MC/excuse
            foreach($all_students as $student_ic) {
                
                // Calculate current attendance for this student
                $subQuery = DB::table('tblclassattendance')
                                ->select('tblclassattendance.classdate')
                                ->where([
                                    ['tblclassattendance.groupid', $group[0]],
                                    ['tblclassattendance.groupname', $group[1]],
                                    ['tblclassattendance.student_ic', $student_ic]
                                ])
                                ->groupBy('tblclassattendance.classdate');

                $total_absent_classes = DB::table('tblclassattendance')
                ->select(
                    'tblclassattendance.classdate',
                    'tblclassattendance.classend',
                    DB::raw('HOUR(TIMEDIFF(tblclassattendance.classend, tblclassattendance.classdate)) as raw_diff')
                )
                ->where([
                    ['tblclassattendance.groupid', $group[0]],
                    ['tblclassattendance.groupname', $group[1]]
                ])
                ->whereNotIn('tblclassattendance.classdate', $subQuery)
                ->groupBy('tblclassattendance.classdate', 'tblclassattendance.classdate', 'tblclassattendance.classend')
                ->get();

                $totalhours = 0;
                foreach ($total_absent_classes as $ttl) {
                    $totalhours += $ttl->raw_diff;
                }

                $student_attendance_balance = $course_credit->total - $totalhours;
                
                // Define thresholds
                $threshold_1st = $course_credit->total - $course_credit->course_credit;      // 1st warning threshold
                $threshold_2nd = $course_credit->total - ($course_credit->course_credit * 2); // 2nd warning threshold  
                $threshold_3rd = $course_credit->total - ($course_credit->course_credit * 3); // 3rd warning threshold

                // Get existing warnings for this student
                $existing_warnings = DB::table('tblstudent_warning')
                    ->where([
                        ['student_ic', $student_ic],
                        ['groupid', $group[0]],
                        ['groupname', $group[1]]
                    ])
                    ->orderBy('warning', 'desc')
                    ->get();

                $warning_count = $existing_warnings->count();
                $highest_warning = $warning_count > 0 ? $existing_warnings->first()->warning : 0;

                // Determine what warning level should be based on current attendance
                $should_have_warning = 0;
                if($student_attendance_balance <= $threshold_3rd) {
                    $should_have_warning = 3;
                } elseif($student_attendance_balance <= $threshold_2nd) {
                    $should_have_warning = 2;
                } elseif($student_attendance_balance <= $threshold_1st) {
                    $should_have_warning = 1;
                }

                // CASE 1: Student improved attendance - remove excessive warnings
                if($highest_warning > $should_have_warning) {
                    
                    if($should_have_warning == 0) {
                        // Remove all warnings - student attendance is now good
                        DB::table('tblstudent_warning')
                            ->where([
                                ['student_ic', $student_ic],
                                ['groupid', $group[0]],
                                ['groupname', $group[1]]
                            ])
                            ->delete();
                            
                    } else {
                        // Remove warnings higher than what student should have
                        DB::table('tblstudent_warning')
                            ->where([
                                ['student_ic', $student_ic],
                                ['groupid', $group[0]],
                                ['groupname', $group[1]]
                            ])
                            ->where('warning', '>', $should_have_warning)
                            ->delete();
                    }
                }
                
                // CASE 2: Student needs new warning (attendance got worse)
                elseif($should_have_warning > $highest_warning) {
                    
                    // Only add the next warning level (progressive warnings)
                    $next_warning_level = $highest_warning + 1;
                    
                    if($next_warning_level <= $should_have_warning) {
                        $percentage = ($student_attendance_balance / $course_credit->total) * 100;

                        DB::table('tblstudent_warning')->insert([
                            'student_ic' => $student_ic,
                            'groupid' => $group[0],
                            'groupname' => $group[1],
                            'balance_attendance' => $student_attendance_balance,
                            'percentage_attendance' => $percentage,
                            'warning' => $next_warning_level
                        ]);
                    }
                }
                
                // CASE 3: Update existing warning data if attendance balance changed
                elseif($should_have_warning == $highest_warning && $should_have_warning > 0) {
                    $percentage = ($student_attendance_balance / $course_credit->total) * 100;
                    
                    DB::table('tblstudent_warning')
                        ->where([
                            ['student_ic', $student_ic],
                            ['groupid', $group[0]],
                            ['groupname', $group[1]],
                            ['warning', $highest_warning]
                        ])
                        ->update([
                            'balance_attendance' => $student_attendance_balance,
                            'percentage_attendance' => $percentage
                        ]);
                }
            }
        }

        return redirect()->back()->with('message', 'Student attendance has been updated!');

    }

    public function onlineClass() 
    {

        $user = Auth::user();

        // $courseid = Session::get('CourseID');

        $courseid = DB::table('subjek')->where('id', Session::get('CourseID'))->pluck('sub_id')->first();

        // $folder = DB::table('lecturer_dir')
        // ->where([
        //     ['CourseID', $courseid],
        //     ['Addby', $user->ic]
        //     ])->get();

        $folder = DB::table('lecturer_dir')
        ->join('subjek', 'lecturer_dir.CourseID','subjek.id')
        ->where('subjek.sub_id', $courseid)
        ->where('Addby', $user->ic)->get();

        return view('lecturer.class.onlineclass', compact('folder'));

    }

    public function getChapters(Request $request)
    {
        $chapter = DB::table('material_dir')->where('LecturerDirID', $request->folder)->get();

        $content = "";

        $content .= "<option value='0' disabled selected>-</option>";
        foreach($chapter as $chp){

            //$lecturer = User::where('ic', $grp->user_ic)->first();

            $content .= '<option value='. $chp->DrID .'> Chapter '. $chp->ChapterNo .' : '. (($chp->newDrName != null) ? $chp->newDrName : $chp->DrName) .'</option>';
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
        //dd($request->chapter);

        if(is_array($request->chapter))
        {

            foreach($request->chapter as $chp)
            {
                DB::table('classchapter')->insert([
                    'chapterid' => $chp,
                    'classid' => $id
                ]);
            }
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

        // $courseid = Session::get('CourseID');

        $courseid = DB::table('subjek')->where('id', Session::get('CourseID'))->pluck('sub_id')->first();

        // $folder = DB::table('lecturer_dir')
        // ->where([
        //     ['CourseID', $courseid],
        //     ['Addby', $user->ic]
        //     ])->get();

        $folder = DB::table('lecturer_dir')
        ->join('subjek', 'lecturer_dir.CourseID','subjek.id')
        ->where('subjek.sub_id', $courseid)
        ->where('Addby', $user->ic)->get();

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
                    <label>'.htmlentities($grp->name, ENT_QUOTES).'</label>
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

        //$temp = ['hafiyyaimann1998@gmail.com'];

        //Mail::send('emails.welcome', $data, function($message) use ($temp)
        //{    
        //    $message->to($temp)->subject('Test');    
        //});

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
        $students = [];

        $quiz = [];
        $quizcollection = [];
        $overallquiz = [];
        $quizanswer = [];
        $quizavg = [];
        $quizmax = [];
        $quizmin = [];
        $quizavgoverall = [];

        $test = [];
        $testcollection = [];
        $overalltest = [];
        $testanswer = [];
        $testavg = [];
        $testmax = [];
        $testmin = [];
        $testavgoverall = [];

        $test2 = [];
        $test2collection = [];
        $overalltest2 = [];
        $test2answer = [];
        $test2avg = [];
        $test2max = [];
        $test2min = [];
        $test2avgoverall = [];

        $assign = [];
        $assigncollection = [];
        $overallassign = [];
        $assignanswer = [];
        $assignavg = [];
        $assignmax = [];
        $assignmin = [];
        $assignavgoverall = [];

        $midterm = [];
        $midtermcollection = [];
        $overallmidterm = [];
        $midtermanswer = [];
        $midtermavg = [];
        $midtermmax = [];
        $midtermmin = [];
        $midtermavgoverall = [];

        $final = [];
        $finalcollection = [];
        $overallfinal = [];
        $finalanswer = [];
        $finalavg = [];
        $finalmax = [];
        $finalmin = [];
        $finalavgoverall = [];

        $paperwork = [];
        $paperworkcollection = [];
        $overallpaperwork = [];
        $paperworkanswer = [];
        $paperworkavg = [];
        $paperworkmax = [];
        $paperworkmin = [];
        $paperworkavgoverall = [];

        $practical = [];
        $practicalcollection = [];
        $overallpractical = [];
        $practicalanswer = [];
        $practicalavg = [];
        $practicalmax = [];
        $practicalmin = [];
        $practicalavgoverall = [];

        $other = [];
        $othercollection = [];
        $overallother = [];
        $otheranswer = [];
        $otheravg = [];
        $othermax = [];
        $othermin = [];
        $otheravgoverall = [];

        $extra = [];
        $extracollection = [];
        $overallextra = [];
        $extraanswer = [];
        $extraavg = [];
        $extramax = [];
        $extramin = [];
        $extraavgoverall = [];

        $overallall = [];
        $avgoverall = [];
        $valGrade = [];
        $pointerGrade = [];
        $user = Auth::user();

        $id = request()->id;

        $data['lectInfo'] = DB::table('user_subjek')
                            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                            ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                            ->where([
                                ['user_subjek.user_ic', $user->ic],
                                ['user_subjek.session_id', Session::get('SessionID')],
                                ['subjek.id', request()->id]
                             ])
                            ->select('subjek.*', 'sessions.SessionName AS session')
                            ->first();

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


                $students[] = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id','students.*')
                ->where([
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', Session::get('SessionID')],
                ['subjek.id', request()->id]
                ])->where('student_subjek.group_name', $grp->group_name)
                ->orderBy('students.name')->get();

                $collection = collect($students[$ky]);

                //QUIZ

                $quizs = DB::table('tblclassquiz')
                        ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                        ->where([
                            ['tblclassquiz.classid', request()->id],
                            ['tblclassquiz.sessionid', Session::get('SessionID')],
                            ['tblclassquiz_group.groupname', $grp->group_name],
                            ['tblclassquiz.status', '!=', 3],
                            ['tblclassquiz.addby', $user->ic]
                        ]);

                $quiz[] = $quizs->get();

                $quizid = $quizs->pluck('tblclassquiz.id');

                $totalquiz = $quizs->sum('tblclassquiz.total_mark');

                foreach($quiz[$ky] as $key => $qz)
                {

                    $quizarray = DB::table('tblclassstudentquiz')
                                            ->join('tblclassquiz', 'tblclassstudentquiz.quizid', 'tblclassquiz.id')
                                            ->where('quizid', $qz->quizid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $quizavg[$ky][$key] = number_format((float)$quizarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $quizmax[$ky][$key] = $quizarray->max('final_mark');
                    
                    $quizmin[$ky][$key] = $quizarray->min('final_mark');

                }


                //TEST

                $tests = DB::table('tblclasstest')
                        ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                        ->where([
                            ['tblclasstest.classid', request()->id],
                            ['tblclasstest.sessionid', Session::get('SessionID')],
                            ['tblclasstest_group.groupname', $grp->group_name],
                            ['tblclasstest.status', '!=', 3],
                            ['tblclasstest.addby', $user->ic]
                        ]);

                $test[] = $tests->get();

                $testid = $tests->pluck('tblclasstest.id');

                $totaltest = $tests->sum('tblclasstest.total_mark');

                foreach($test[$ky] as $key => $qz)
                {

                    $testarray = DB::table('tblclassstudenttest')
                                            ->join('tblclasstest', 'tblclassstudenttest.testid', 'tblclasstest.id')
                                            ->where('testid', $qz->testid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $testavg[$ky][$key] = number_format((float)$testarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $testmax[$ky][$key] = $testarray->max('final_mark');
                    
                    $testmin[$ky][$key] = $testarray->min('final_mark');

                }

                //TEST2

                $tests2 = DB::table('tblclasstest2')
                        ->join('tblclasstest2_group', 'tblclasstest2.id', 'tblclasstest2_group.testid')
                        ->where([
                            ['tblclasstest2.classid', request()->id],
                            ['tblclasstest2.sessionid', Session::get('SessionID')],
                            ['tblclasstest2_group.groupname', $grp->group_name],
                            ['tblclasstest2.status', '!=', 3],
                            ['tblclasstest2.addby', $user->ic]
                        ]);

                $test2[] = $tests2->get();

                $test2id = $tests2->pluck('tblclasstest2.id');

                $totaltest2 = $tests2->sum('tblclasstest2.total_mark');

                foreach($test2[$ky] as $key => $qz)
                {

                    $test2array = DB::table('tblclassstudenttest')
                                            ->join('tblclasstest2', 'tblclassstudenttest.testid', 'tblclasstest2.id')
                                            ->where('testid', $qz->testid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $test2avg[$ky][$key] = number_format((float)$test2array->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $test2max[$ky][$key] = $test2array->max('final_mark');
                    
                    $test2min[$ky][$key] = $test2array->min('final_mark');

                }

                //ASSIGNMENT

                $assigns = DB::table('tblclassassign')
                        ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                        ->where([
                            ['tblclassassign.classid', request()->id],
                            ['tblclassassign.sessionid', Session::get('SessionID')],
                            ['tblclassassign_group.groupname', $grp->group_name],
                            ['tblclassassign.status', '!=', 3],
                            ['tblclassassign.addby', $user->ic]
                        ]);

                $assign[] = $assigns->get();

                $assignid = $assigns->pluck('tblclassassign.id');

                $totalassign = $assigns->sum('tblclassassign.total_mark');

                foreach($assign[$ky] as $key => $qz)
                {

                    $assignarray = DB::table('tblclassstudentassign')
                                            ->join('tblclassassign', 'tblclassstudentassign.assignid', 'tblclassassign.id')
                                            ->where('assignid', $qz->assignid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $assignavg[$ky][$key] = number_format((float)$assignarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $assignmax[$ky][$key] = $assignarray->max('final_mark');
                    
                    $assignmin[$ky][$key] = $assignarray->min('final_mark');

                }

                //EXTRA

                $extras = DB::table('tblclassextra')
                        ->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                        ->where([
                            ['tblclassextra.classid', request()->id],
                            ['tblclassextra.sessionid', Session::get('SessionID')],
                            ['tblclassextra_group.groupname', $grp->group_name],
                            ['tblclassextra.status', '!=', 3],
                            ['tblclassextra.addby', $user->ic]
                        ]);

                $extra[] = $extras->get();

                $extraid = $extras->pluck('tblclassextra.id');

                $totalextra = $extras->sum('tblclassextra.total_mark');

                foreach($extra[$ky] as $key => $qz)
                {

                    $extraarray = DB::table('tblclassstudentextra')
                                            ->join('tblclassextra', 'tblclassstudentextra.extraid', 'tblclassextra.id')
                                            ->where('extraid', $qz->extraid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $extraavg[$ky][$key] = number_format((float)$extraarray->sum('tblclassstudentextra.total_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $extramax[$ky][$key] = $extraarray->max('tblclassstudentextra.total_mark');
                    
                    $extramin[$ky][$key] = $extraarray->min('tblclassstudentextra.total_mark');

                }

                //OTHER

                $others = DB::table('tblclassother')
                        ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                        ->where([
                            ['tblclassother.classid', request()->id],
                            ['tblclassother.sessionid', Session::get('SessionID')],
                            ['tblclassother_group.groupname', $grp->group_name],
                            ['tblclassother.status', '!=', 3],
                            ['tblclassother.addby', $user->ic]
                        ]);

                $other[] = $others->get();

                $otherid = $others->pluck('tblclassother.id');

                $totalother = $others->sum('tblclassother.total_mark');

                foreach($other[$ky] as $key => $qz)
                {

                    $otherarray = DB::table('tblclassstudentother')
                                            ->join('tblclassother', 'tblclassstudentother.otherid', 'tblclassother.id')
                                            ->where('otherid', $qz->otherid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $otheravg[$ky][$key] = number_format((float)$otherarray->sum('tblclassstudentother.total_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $othermax[$ky][$key] = $otherarray->max('tblclassstudentother.total_mark');
                    
                    $othermin[$ky][$key] = $otherarray->min('tblclassstudentother.total_mark');

                }

                //MIDTERM

                $midterms = DB::table('tblclassmidterm')
                        ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                        ->where([
                            ['tblclassmidterm.classid', request()->id],
                            ['tblclassmidterm.sessionid', Session::get('SessionID')],
                            ['tblclassmidterm_group.groupname', $grp->group_name],
                            ['tblclassmidterm.status', '!=', 3],
                            ['tblclassmidterm.addby', $user->ic]
                        ]);

                $midterm[] = $midterms->get();

                $midtermid = $midterms->pluck('tblclassmidterm.id');

                $totalmidterm = $midterms->sum('tblclassmidterm.total_mark');

                foreach($midterm[$ky] as $key => $qz)
                {

                    $midtermarray = DB::table('tblclassstudentmidterm')
                                            ->join('tblclassmidterm', 'tblclassstudentmidterm.midtermid', 'tblclassmidterm.id')
                                            ->where('midtermid', $qz->midtermid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $midtermavg[$ky][$key] = number_format((float)$midtermarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $midtermmax[$ky][$key] = $midtermarray->max('final_mark');
                    
                    $midtermmin[$ky][$key] = $midtermarray->min('final_mark');

                }

                //FINAL

                $finals = DB::table('tblclassfinal')
                        ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                        ->where([
                            ['tblclassfinal.classid', request()->id],
                            ['tblclassfinal.sessionid', Session::get('SessionID')],
                            ['tblclassfinal_group.groupname', $grp->group_name],
                            ['tblclassfinal.status', '!=', 3],
                            ['tblclassfinal.addby', $user->ic]
                        ]);

                $final[] = $finals->get();

                $finalid = $finals->pluck('tblclassfinal.id');

                $totalfinal = $finals->sum('tblclassfinal.total_mark');

                foreach($final[$ky] as $key => $qz)
                {

                    $finalarray = DB::table('tblclassstudentfinal')
                                            ->join('tblclassfinal', 'tblclassstudentfinal.finalid', 'tblclassfinal.id')
                                            ->where('finalid', $qz->finalid)
                                            ->whereIn('userid', $collection->pluck('ic'));

                    $finalavg[$ky][$key] = number_format((float)$finalarray->sum('final_mark') / count($collection->pluck('ic')), 2, '.', '');

                    $finalmax[$ky][$key] = $finalarray->max('final_mark');
                    
                    $finalmin[$ky][$key] = $finalarray->min('final_mark');

                }

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
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', request()->id],
                                ['assessment', 'quiz']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    //dd($percentquiz);

                    if($quizs = DB::table('tblclassquiz')
                    ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                    ->where([
                        ['tblclassquiz.classid', request()->id],
                        ['tblclassquiz.sessionid', Session::get('SessionID')],
                        ['tblclassquiz_group.groupname', $grp->group_name],
                        ['tblclassquiz.status', '!=', 3]
                    ])->exists()){
                        if($percentquiz != null)
                        {
                            if(DB::table('tblclassquiz')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalquiz);
                                if ($totalquiz > 0) {
                                    $overallquiz[$ky][$keys] = round(number_format((float)$sumquiz[$ky][$keys] / $totalquiz * $percentquiz->mark_percentage, 2, '.', ''), 1);
                                } else {
                                    $overallquiz[$ky][$keys] = 0;
                                }

                                $quizcollection = collect($overallquiz[$ky]);
                            }else{
                                $overallquiz[$ky][$keys] = 0;

                                $quizcollection = collect($overallquiz[$ky]);
                            }
            
                        }else{
                            $overallquiz[$ky][$keys] = 0;

                            $quizcollection = collect($overallquiz[$ky]);
                        }
                    }else{
                        $overallquiz[$ky][$keys] = 0;

                        $quizcollection = collect($overallquiz[$ky]);
                    }


                    // TEST
                    
                    foreach($test[$ky] as $key =>$qz)
                    {
                    
                    $testanswer[$ky][$keys][$key] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->where('testid', $qz->testid)->first();

                    }

                    $sumtest[$ky][$keys] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->whereIn('testid', $testid)->sum('final_mark');

                    $percenttest = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', request()->id],
                                ['assessment', 'test']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($tests = DB::table('tblclasstest')
                    ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                    ->where([
                        ['tblclasstest.classid', request()->id],
                        ['tblclasstest.sessionid', Session::get('SessionID')],
                        ['tblclasstest_group.groupname', $grp->group_name],
                        ['tblclasstest.status', '!=', 3]
                    ])->exists()){
                        if($percenttest != null)
                        {
                            if(DB::table('tblclasstest')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totaltest);
                                if ($totaltest > 0) {
                                    $overalltest[$ky][$keys] = round(number_format((float)$sumtest[$ky][$keys] / $totaltest * $percenttest->mark_percentage, 2, '.', ''), 1);
                                } else {
                                    $overalltest[$ky][$keys] = 0;
                                }

                                $testcollection = collect($overalltest[$ky]);
                            }else{
                                $overalltest[$ky][$keys] = 0;

                                $testcollection = collect($overalltest[$ky]);
                            }
            
                        }else{
                            $overalltest[$ky][$keys] = 0;

                            $testcollection = collect($overalltest[$ky]);
                        }
                    }else{
                        $overalltest[$ky][$keys] = 0;

                        $testcollection = collect($overalltest[$ky]);
                    }

                    // TEST2
                    
                    foreach($test2[$ky] as $key =>$qz)
                    {
                    
                    $test2answer[$ky][$keys][$key] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->where('testid', $qz->testid)->first();

                    }

                    $sumtest2[$ky][$keys] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->whereIn('testid', $test2id)->sum('final_mark');

                    $percenttest2 = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', request()->id],
                                ['assessment', 'test2']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($tests2 = DB::table('tblclasstest2')
                    ->join('tblclasstest2_group', 'tblclasstest2.id', 'tblclasstest2_group.testid')
                    ->where([
                        ['tblclasstest2.classid', request()->id],
                        ['tblclasstest2.sessionid', Session::get('SessionID')],
                        ['tblclasstest2_group.groupname', $grp->group_name],
                        ['tblclasstest2.status', '!=', 3]
                    ])->exists()){
                        if($percenttest2 != null)
                        {
                            if(DB::table('tblclasstest2')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totaltest);
                                if ($totaltest2 > 0) {
                                    $overalltest2[$ky][$keys] = round(number_format((float)$sumtest2[$ky][$keys] / $totaltest2 * $percenttest2->mark_percentage, 2, '.', ''), 1);
                                } else {
                                    $overalltest2[$ky][$keys] = 0;
                                }

                                $test2collection = collect($overalltest2[$ky]);
                            }else{
                                $overalltest2[$ky][$keys] = 0;

                                $test2collection = collect($overalltest2[$ky]);
                            }
            
                        }else{
                            $overalltest2[$ky][$keys] = 0;

                            $test2collection = collect($overalltest2[$ky]);
                        }
                    }else{
                        $overalltest2[$ky][$keys] = 0;

                        $test2collection = collect($overalltest2[$ky]);
                    }


                    // ASSIGNMENT
                    
                    foreach($assign[$ky] as $key =>$qz)
                    {
                    
                    $assignanswer[$ky][$keys][$key] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->where('assignid', $qz->assignid)->first();

                    }

                    $sumassign[$ky][$keys] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->whereIn('assignid', $assignid)->sum('final_mark');

                    $percentassign = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', request()->id],
                                ['assessment', 'assignment']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($assigns = DB::table('tblclassassign')
                    ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                    ->where([
                        ['tblclassassign.classid', request()->id],
                        ['tblclassassign.sessionid', Session::get('SessionID')],
                        ['tblclassassign_group.groupname', $grp->group_name],
                        ['tblclassassign.status', '!=', 3]
                    ])->exists()){
                        if($percentassign != null)
                        {
                            if(DB::table('tblclassassign')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalassign);
                                if($totalassign == 0)
                                {
                                    // dd('error detectedsssss');

                                    $overallassign[$ky][$keys] = 0; // or any default value or handling logic
                                }
                                else{
                                    $overallassign[$ky][$keys] = round(number_format((float)$sumassign[$ky][$keys] / $totalassign * $percentassign->mark_percentage, 2, '.', ''), 1);
                                }

                                $assigncollection = collect($overallassign[$ky]);
                            }else{
                               $overallassign[$ky][$keys] = 0;

                               $assigncollection = collect($overallassign[$ky]);
                            }
            
                        }else{
                            $overallassign[$ky][$keys] = 0;

                            $assigncollection = collect($overallassign[$ky]);
                        }
                    }else{
                        $overallassign[$ky][$keys] = 0;

                        $assigncollection = collect($overallassign[$ky]);
                    }

                    // EXTRA
                    
                    foreach($extra[$ky] as $key =>$qz)
                    {
                    
                    $extraanswer[$ky][$keys][$key] = DB::table('tblclassstudentextra')->where('userid', $std->ic)->where('extraid', $qz->extraid)->first();

                    }

                    $sumextra[$ky][$keys] = DB::table('tblclassstudentextra')->where('userid', $std->ic)->whereIn('extraid', $extraid)->sum('total_mark');

                    $percentextra = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', request()->id],
                                ['assessment', 'extra']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($extras = DB::table('tblclassextra')
                    ->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                    ->where([
                        ['tblclassextra.classid', request()->id],
                        ['tblclassextra.sessionid', Session::get('SessionID')],
                        ['tblclassextra_group.groupname', $grp->group_name],
                        ['tblclassextra.status', '!=', 3]
                    ])->exists()){
                        if($percentextra != null)
                        {
                            if(DB::table('tblclassextra')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalextra);
                                if ($totalextra > 0) {
                                    $overallextra[$ky][$keys] = round(number_format((float)$sumextra[$ky][$keys] / $totalextra * $percentextra->mark_percentage, 2, '.', ''), 1);
                                } else {
                                    $overallextra[$ky][$keys] = 0;
                                }

                                $extracollection = collect($overallextra[$ky]);
                            }else{
                                $overallextra[$ky][$keys] = 0;

                                $extracollection = collect($overallextra[$ky]);
                            }
            
                        }else{
                            $overallextra[$ky][$keys] = 0;

                            $extracollection = collect($overallextra[$ky]);
                        }
                    }else{
                        $overallextra[$ky][$keys] = 0;

                        $extracollection = collect($overallextra[$ky]);
                    }

                    // OTHER
                    
                    foreach($other[$ky] as $key =>$qz)
                    {
                    
                    $otheranswer[$ky][$keys][$key] = DB::table('tblclassstudentother')->where('userid', $std->ic)->where('otherid', $qz->otherid)->first();

                    }

                    $sumother[$ky][$keys] = DB::table('tblclassstudentother')->where('userid', $std->ic)->whereIn('otherid', $otherid)->sum('total_mark');

                    $percentother = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', request()->id],
                                ['assessment', 'lain-lain']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($others = DB::table('tblclassother')
                    ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                    ->where([
                        ['tblclassother.classid', request()->id],
                        ['tblclassother.sessionid', Session::get('SessionID')],
                        ['tblclassother_group.groupname', $grp->group_name],
                        ['tblclassother.status', '!=', 3]
                    ])->exists()){
                        if($percentother != null)
                        {
                            if(DB::table('tblclassother')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalother);
                                $overallother[$ky][$keys] = round(number_format((float)$sumother[$ky][$keys], 2, '.', ''), 1);

                                $othercollection = collect($overallother[$ky]);
                            }else{
                                $overallother[$ky][$keys] = 0;

                                $othercollection = collect($overallother[$ky]);
                            }
            
                        }else{
                            $overallother[$ky][$keys] = 0;

                            $othercollection = collect($overallother[$ky]);
                        }
                    }else{
                        $overallother[$ky][$keys] = 0;

                        $othercollection = collect($overallother[$ky]);
                    }

                    // MIDTERM
                    
                    foreach($midterm[$ky] as $key =>$qz)
                    {
                    
                    $midtermanswer[$ky][$keys][$key] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->where('midtermid', $qz->midtermid)->first();

                    }

                    $summidterm[$ky][$keys] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->whereIn('midtermid', $midtermid)->sum('final_mark');

                    $percentmidterm = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', request()->id],
                                ['assessment', 'midterm']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($midterms = DB::table('tblclassmidterm')
                    ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                    ->where([
                        ['tblclassmidterm.classid', request()->id],
                        ['tblclassmidterm.sessionid', Session::get('SessionID')],
                        ['tblclassmidterm_group.groupname', $grp->group_name],
                        ['tblclassmidterm.status', '!=', 3]
                    ])->exists()){
                        if($percentmidterm != null)
                        {
                            if(DB::table('tblclassmidterm')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalmidterm);
                                if ($totalmidterm > 0) {
                                    $overallmidterm[$ky][$keys] = round(number_format((float)$summidterm[$ky][$keys] / $totalmidterm * $percentmidterm->mark_percentage, 2, '.', ''), 1);
                                } else {
                                    $overallmidterm[$ky][$keys] = 0;
                                }

                                $midtermcollection = collect($overallmidterm[$ky]);
                            }else{
                                $overallmidterm[$ky][$keys] = 0;

                                $midtermcollection = collect($overallmidterm[$ky]);
                            }
            
                        }else{
                            $overallmidterm[$ky][$keys] = 0;

                            $midtermcollection = collect($overallmidterm[$ky]);
                        }
                    }else{
                        $overallmidterm[$ky][$keys] = 0;

                        $midtermcollection = collect($overallmidterm[$ky]);
                    }

                    // FINAL
                    
                    foreach($final[$ky] as $key =>$qz)
                    {
                    
                    $finalanswer[$ky][$keys][$key] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->where('finalid', $qz->finalid)->first();

                    }

                    $sumfinal[$ky][$keys] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->whereIn('finalid', $finalid)->sum('final_mark');

                    $percentfinal = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', request()->id],
                                ['assessment', 'final']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($finals = DB::table('tblclassfinal')
                    ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                    ->where([
                        ['tblclassfinal.classid', request()->id],
                        ['tblclassfinal.sessionid', Session::get('SessionID')],
                        ['tblclassfinal_group.groupname', $grp->group_name],
                        ['tblclassfinal.status', '!=', 3]
                    ])->exists()){
                        if($percentfinal != null)
                        {
                            if(DB::table('tblclassfinal')
                            ->where([
                                ['classid', request()->id],
                                ['sessionid', Session::get('SessionID')]
                            ])->exists()){
                                //dd($totalfinal);
                                if ($totalfinal > 0) {
                                    $overallfinal[$ky][$keys] = round(number_format((float)$sumfinal[$ky][$keys] / $totalfinal * $percentfinal->mark_percentage, 2, '.', ''), 1);
                                } else {
                                    $overallfinal[$ky][$keys] = 0;
                                }

                                $finalcollection = collect($overallfinal[$ky]);
                            }else{
                                $overallfinal[$ky][$keys] = 0;

                                $finalcollection = collect($overallfinal[$ky]);
            }
            
                        }else{
                            $overallfinal[$ky][$keys] = 0;

                            $finalcollection = collect($overallfinal[$ky]);
                        }
                    }else{
                        $overallfinal[$ky][$keys] = 0;

                        $finalcollection = collect($overallfinal[$ky]);
                    }

                    $overallalls[$ky][$keys] = $overallquiz[$ky][$keys] + $overalltest[$ky][$keys] + $overalltest2[$ky][$keys] + $overallassign[$ky][$keys] + $overallextra[$ky][$keys] + $overallother[$ky][$keys] + $overallmidterm[$ky][$keys] + $overallfinal[$ky][$keys];

                    $overallall2[$ky][$keys] = round($overallalls[$ky][$keys], 1);

                    $overallall[$ky][$keys] = round($overallall2[$ky][$keys]);

                    $collectionall = collect($overallall[$ky]);

                    //check grade
                    $grade = DB::table('tblsubject_grade')->get();

                    foreach($grade as $grd)
                    {

                        if($overallall[$ky][$keys] >= $grd->mark_start && $overallall[$ky][$keys] <= $grd->mark_end)
                        {
                            $valGrade[$ky][$keys] = $grd->code;

                            $pointerGrade[$ky][$keys] = $grd->grade_value;

                            break;
                        }else{

                            $valGrade[$ky][$keys] = null;

                            $pointerGrade[$ky][$keys] = 0;
                        }

                    }

                    DB::table('student_subjek')
                    ->where([
                        ['student_ic', $std->ic],
                        ['sessionid', $std->session_id],
                        ['courseid', $std->course_id]
                        ])->update([
                            'grade' => $valGrade[$ky][$keys],
                            'pointer' => $pointerGrade[$ky][$keys]
                        ]);
            
                }

            $quizavgoverall = number_format((float)$quizcollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $testavgoverall = number_format((float)$testcollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $test2avgoverall = number_format((float)$test2collection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $assignavgoverall = number_format((float)$assigncollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $extraavgoverall = number_format((float)$extracollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $otheravgoverall = number_format((float)$othercollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $midtermavgoverall = number_format((float)$midtermcollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $finalavgoverall = number_format((float)$finalcollection->sum() / count($collection->pluck('ic')), 2, '.', '');

            $avgoverall = number_format((float)$collectionall->sum() / count($collection->pluck('ic')), 2, '.', '');
        }

        

        //dd($valGrade);


        return view('lecturer.courseassessment.studentreport', compact('groups', 'students', 'id',
                                                                       'quiz', 'quizanswer', 'overallquiz', 'quizavg', 'quizmax', 'quizmin', 'quizcollection', 'quizavgoverall',
                                                                       'test', 'testanswer', 'overalltest', 'testavg', 'testmax', 'testmin', 'testcollection','testavgoverall',
                                                                       'test2', 'test2answer', 'overalltest2', 'test2avg', 'test2max', 'test2min', 'test2collection','test2avgoverall',
                                                                       'assign', 'assignanswer', 'overallassign', 'assignavg', 'assignmax', 'assignmin', 'assigncollection','assignavgoverall',
                                                                       'extra', 'extraanswer', 'overallextra', 'extraavg', 'extramax', 'extramin', 'extracollection','extraavgoverall',
                                                                       'other', 'otheranswer', 'overallother', 'otheravg', 'othermax', 'othermin', 'othercollection','otheravgoverall',
                                                                       'midterm', 'midtermanswer', 'overallmidterm', 'midtermavg', 'midtermmax', 'midtermmin', 'midtermcollection','midtermavgoverall',
                                                                       'final', 'finalanswer', 'overallfinal', 'finalavg', 'finalmax', 'finalmin', 'finalcollection','finalavgoverall',
                                                                       'overallall', 'overallall2', 'avgoverall', 'valGrade', 'data'
                                                                    ));

    }

    public function printRowscore($id, $groupName)
    {
        $user = Auth::user();
        
        // Fetch course/program details
        $courseInfo = DB::table('user_subjek')
                        ->join('users', 'user_subjek.user_ic', 'users.ic')
                        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                        ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                        // ->join('tblprogramme', 'subjek.program', 'tblprogramme.id')
                        ->leftJoin('tblfaculty', 'users.faculty', 'tblfaculty.id')
                        ->where([
                            ['user_subjek.user_ic', $user->ic],
                            ['user_subjek.session_id', Session::get('SessionID')],
                            ['subjek.id', $id]
                         ])
                        ->select('subjek.*', 'sessions.SessionName AS session', 'tblfaculty.facultyname')
                        ->first();

        $sub_id = DB::table('subjek')->where('id', $id)->value('sub_id');

        // Fetch students for the specific group
        $students = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id','students.*')
                ->where([
                    ['user_subjek.user_ic', $user->ic],
                    ['user_subjek.session_id', Session::get('SessionID')],
                    ['subjek.id', $id],
                    ['student_subjek.group_name', $groupName]
                ])
                ->orderBy('students.name')->get();

        $collection = collect($students);

        // Initialize arrays
        $quiz = [];
        $quizanswer = [];
        $overallquiz = [];
        $test = [];
        $testanswer = [];
        $overalltest = [];
        $test2 = [];
        $test2answer = [];
        $overalltest2 = [];
        $assign = [];
        $assignanswer = [];
        $overallassign = [];
        $extra = [];
        $extraanswer = [];
        $overallextra = [];
        $other = [];
        $otheranswer = [];
        $overallother = [];
        $midterm = [];
        $midtermanswer = [];
        $overallmidterm = [];
        $final = [];
        $finalanswer = [];
        $overallfinal = [];
        $overallall = [];
        $overallall2 = [];
        $valGrade = [];

        // Fetch QUIZ assessments
        $quizs = DB::table('tblclassquiz')
                ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                ->where([
                    ['tblclassquiz.classid', $id],
                    ['tblclassquiz.sessionid', Session::get('SessionID')],
                    ['tblclassquiz_group.groupname', $groupName],
                    ['tblclassquiz.status', '!=', 3],
                    ['tblclassquiz.addby', $user->ic]
                ]);
        $quiz = $quizs->get();
        $quizid = $quizs->pluck('tblclassquiz.id');
        $totalquiz = $quizs->sum('tblclassquiz.total_mark');

        // Fetch TEST assessments
        $tests = DB::table('tblclasstest')
                ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                ->where([
                    ['tblclasstest.classid', $id],
                    ['tblclasstest.sessionid', Session::get('SessionID')],
                    ['tblclasstest_group.groupname', $groupName],
                    ['tblclasstest.status', '!=', 3],
                    ['tblclasstest.addby', $user->ic]
                ]);
        $test = $tests->get();
        $testid = $tests->pluck('tblclasstest.id');
        $totaltest = $tests->sum('tblclasstest.total_mark');

        // Fetch TEST2 assessments
        $tests2 = DB::table('tblclasstest2')
                ->join('tblclasstest2_group', 'tblclasstest2.id', 'tblclasstest2_group.testid')
                ->where([
                    ['tblclasstest2.classid', $id],
                    ['tblclasstest2.sessionid', Session::get('SessionID')],
                    ['tblclasstest2_group.groupname', $groupName],
                    ['tblclasstest2.status', '!=', 3],
                    ['tblclasstest2.addby', $user->ic]
                ]);
        $test2 = $tests2->get();
        $test2id = $tests2->pluck('tblclasstest2.id');
        $totaltest2 = $tests2->sum('tblclasstest2.total_mark');

        // Fetch ASSIGNMENT assessments
        $assigns = DB::table('tblclassassign')
                ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                ->where([
                    ['tblclassassign.classid', $id],
                    ['tblclassassign.sessionid', Session::get('SessionID')],
                    ['tblclassassign_group.groupname', $groupName],
                    ['tblclassassign.status', '!=', 3],
                    ['tblclassassign.addby', $user->ic]
                ]);
        $assign = $assigns->get();
        $assignid = $assigns->pluck('tblclassassign.id');
        $totalassign = $assigns->sum('tblclassassign.total_mark');

        // Fetch EXTRA assessments
        $extras = DB::table('tblclassextra')
                ->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                ->where([
                    ['tblclassextra.classid', $id],
                    ['tblclassextra.sessionid', Session::get('SessionID')],
                    ['tblclassextra_group.groupname', $groupName],
                    ['tblclassextra.status', '!=', 3],
                    ['tblclassextra.addby', $user->ic]
                ]);
        $extra = $extras->get();
        $extraid = $extras->pluck('tblclassextra.id');
        $totalextra = $extras->sum('tblclassextra.total_mark');

        // Fetch OTHER assessments
        $others = DB::table('tblclassother')
                ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                ->where([
                    ['tblclassother.classid', $id],
                    ['tblclassother.sessionid', Session::get('SessionID')],
                    ['tblclassother_group.groupname', $groupName],
                    ['tblclassother.status', '!=', 3],
                    ['tblclassother.addby', $user->ic]
                ]);
        $other = $others->get();
        $otherid = $others->pluck('tblclassother.id');
        $totalother = $others->sum('tblclassother.total_mark');

        // Fetch MIDTERM assessments
        $midterms = DB::table('tblclassmidterm')
                ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                ->where([
                    ['tblclassmidterm.classid', $id],
                    ['tblclassmidterm.sessionid', Session::get('SessionID')],
                    ['tblclassmidterm_group.groupname', $groupName],
                    ['tblclassmidterm.status', '!=', 3],
                    ['tblclassmidterm.addby', $user->ic]
                ]);
        $midterm = $midterms->get();
        $midtermid = $midterms->pluck('tblclassmidterm.id');
        $totalmidterm = $midterms->sum('tblclassmidterm.total_mark');

        // Fetch FINAL assessments
        $finals = DB::table('tblclassfinal')
                ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                ->where([
                    ['tblclassfinal.classid', $id],
                    ['tblclassfinal.sessionid', Session::get('SessionID')],
                    ['tblclassfinal_group.groupname', $groupName],
                    ['tblclassfinal.status', '!=', 3],
                    ['tblclassfinal.addby', $user->ic]
                ]);
        $final = $finals->get();
        $finalid = $finals->pluck('tblclassfinal.id');
        $totalfinal = $finals->sum('tblclassfinal.total_mark');

        // Fetch mark percentages
        $percentquiz = DB::table('tblclassmarks')->where([['course_id', $sub_id], ['assessment', 'quiz']])->orderBy('id', 'desc')->first();
        $percenttest = DB::table('tblclassmarks')->where([['course_id', $sub_id], ['assessment', 'test']])->orderBy('id', 'desc')->first();
        $percenttest2 = DB::table('tblclassmarks')->where([['course_id', $sub_id], ['assessment', 'test2']])->orderBy('id', 'desc')->first();
        $percentassign = DB::table('tblclassmarks')->where([['course_id', $sub_id], ['assessment', 'assignment']])->orderBy('id', 'desc')->first();
        $percentextra = DB::table('tblclassmarks')->where([['course_id', $sub_id], ['assessment', 'extra']])->orderBy('id', 'desc')->first();
        $percentother = DB::table('tblclassmarks')->where([['course_id', $sub_id], ['assessment', 'lain-lain']])->orderBy('id', 'desc')->first();
        $percentmidterm = DB::table('tblclassmarks')->where([['course_id', $sub_id], ['assessment', 'midterm']])->orderBy('id', 'desc')->first();
        $percentfinal = DB::table('tblclassmarks')->where([['course_id', $sub_id], ['assessment', 'final']])->orderBy('id', 'desc')->first();

        // Process student data
        foreach($students as $keys => $std)
        {
            // QUIZ
            foreach($quiz as $key => $qz)
            {
                $quizanswer[$keys][$key] = DB::table('tblclassstudentquiz')->where('userid', $std->ic)->where('quizid', $qz->quizid)->first();
            }
            $sumquiz[$keys] = DB::table('tblclassstudentquiz')->where('userid', $std->ic)->whereIn('quizid', $quizid)->sum('final_mark');
            
            if(count($quiz) > 0 && $percentquiz != null && $totalquiz > 0) {
                $overallquiz[$keys] = round(number_format((float)$sumquiz[$keys] / $totalquiz * $percentquiz->mark_percentage, 2, '.', ''), 1);
            } else {
                $overallquiz[$keys] = 0;
            }

            // TEST
            foreach($test as $key => $qz)
            {
                $testanswer[$keys][$key] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->where('testid', $qz->testid)->first();
            }
            $sumtest[$keys] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->whereIn('testid', $testid)->sum('final_mark');
            
            if(count($test) > 0 && $percenttest != null && $totaltest > 0) {
                $overalltest[$keys] = round(number_format((float)$sumtest[$keys] / $totaltest * $percenttest->mark_percentage, 2, '.', ''), 1);
            } else {
                $overalltest[$keys] = 0;
            }

            // TEST2
            foreach($test2 as $key => $qz)
            {
                $test2answer[$keys][$key] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->where('testid', $qz->testid)->first();
            }
            $sumtest2[$keys] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->whereIn('testid', $test2id)->sum('final_mark');
            
            if(count($test2) > 0 && $percenttest2 != null && $totaltest2 > 0) {
                $overalltest2[$keys] = round(number_format((float)$sumtest2[$keys] / $totaltest2 * $percenttest2->mark_percentage, 2, '.', ''), 1);
            } else {
                $overalltest2[$keys] = 0;
            }

            // ASSIGNMENT
            foreach($assign as $key => $qz)
            {
                $assignanswer[$keys][$key] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->where('assignid', $qz->assignid)->first();
            }
            $sumassign[$keys] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->whereIn('assignid', $assignid)->sum('final_mark');
            
            if(count($assign) > 0 && $percentassign != null && $totalassign > 0) {
                $overallassign[$keys] = round(number_format((float)$sumassign[$keys] / $totalassign * $percentassign->mark_percentage, 2, '.', ''), 1);
            } else {
                $overallassign[$keys] = 0;
            }

            // EXTRA
            foreach($extra as $key => $qz)
            {
                $extraanswer[$keys][$key] = DB::table('tblclassstudentextra')->where('userid', $std->ic)->where('extraid', $qz->extraid)->first();
            }
            $sumextra[$keys] = DB::table('tblclassstudentextra')->where('userid', $std->ic)->whereIn('extraid', $extraid)->sum('total_mark');
            
            if(count($extra) > 0 && $percentextra != null && $totalextra > 0) {
                $overallextra[$keys] = round(number_format((float)$sumextra[$keys] / $totalextra * $percentextra->mark_percentage, 2, '.', ''), 1);
            } else {
                $overallextra[$keys] = 0;
            }

            // OTHER
            foreach($other as $key => $qz)
            {
                $otheranswer[$keys][$key] = DB::table('tblclassstudentother')->where('userid', $std->ic)->where('otherid', $qz->otherid)->first();
            }
            $sumother[$keys] = DB::table('tblclassstudentother')->where('userid', $std->ic)->whereIn('otherid', $otherid)->sum('total_mark');
            
            if(count($other) > 0 && $percentother != null) {
                $overallother[$keys] = round(number_format((float)$sumother[$keys], 2, '.', ''), 1);
            } else {
                $overallother[$keys] = 0;
            }

            // MIDTERM
            foreach($midterm as $key => $qz)
            {
                $midtermanswer[$keys][$key] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->where('midtermid', $qz->midtermid)->first();
            }
            $summidterm[$keys] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->whereIn('midtermid', $midtermid)->sum('final_mark');
            
            if(count($midterm) > 0 && $percentmidterm != null && $totalmidterm > 0) {
                $overallmidterm[$keys] = round(number_format((float)$summidterm[$keys] / $totalmidterm * $percentmidterm->mark_percentage, 2, '.', ''), 1);
            } else {
                $overallmidterm[$keys] = 0;
            }

            // FINAL
            foreach($final as $key => $qz)
            {
                $finalanswer[$keys][$key] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->where('finalid', $qz->finalid)->first();
            }
            $sumfinal[$keys] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->whereIn('finalid', $finalid)->sum('final_mark');
            
            if(count($final) > 0 && $percentfinal != null && $totalfinal > 0) {
                $overallfinal[$keys] = round(number_format((float)$sumfinal[$keys] / $totalfinal * $percentfinal->mark_percentage, 2, '.', ''), 1);
            } else {
                $overallfinal[$keys] = 0;
            }

            // Calculate overall
            $overallalls[$keys] = $overallquiz[$keys] + $overalltest[$keys] + $overalltest2[$keys] + $overallassign[$keys] + $overallextra[$keys] + $overallother[$keys] + $overallmidterm[$keys] + $overallfinal[$keys];
            $overallall2[$keys] = round($overallalls[$keys], 1);
            $overallall[$keys] = round($overallall2[$keys]);

            // Check grade
            $grade = DB::table('tblsubject_grade')->get();
            foreach($grade as $grd)
            {
                if($overallall[$keys] >= $grd->mark_start && $overallall[$keys] <= $grd->mark_end)
                {
                    $valGrade[$keys] = $grd->code;
                    break;
                } else {
                    $valGrade[$keys] = null;
                }
            }
        }

        // Calculate statistics
        $avgoverall = count($students) > 0 ? number_format((float)array_sum($overallall2) / count($students), 2, '.', '') : 0;
        $maxoverall = count($students) > 0 ? max($overallall2) : 0;
        $minoverall = count($students) > 0 ? min($overallall2) : 0;

        // Calculate grade distribution for chart
        $gradeDistribution = [];
        foreach ($valGrade as $grade) {
            if ($grade) {
                $gradeDistribution[$grade] = ($gradeDistribution[$grade] ?? 0) + 1;
            }
        }

        // Fetch grading scale
        $gradingScale = DB::table('tblsubject_grade')->orderBy('mark_start', 'desc')->get();

        // Generate PDF
        $pdf = Pdf::loadView('lecturer.courseassessment.rowscore_pdf', compact(
            'courseInfo', 'groupName', 'students', 
            'quiz', 'quizanswer', 'overallquiz', 'percentquiz', 'totalquiz',
            'test', 'testanswer', 'overalltest', 'percenttest', 'totaltest',
            'test2', 'test2answer', 'overalltest2', 'percenttest2', 'totaltest2',
            'assign', 'assignanswer', 'overallassign', 'percentassign', 'totalassign',
            'extra', 'extraanswer', 'overallextra', 'percentextra', 'totalextra',
            'other', 'otheranswer', 'overallother', 'percentother', 'totalother',
            'midterm', 'midtermanswer', 'overallmidterm', 'percentmidterm', 'totalmidterm',
            'final', 'finalanswer', 'overallfinal', 'percentfinal', 'totalfinal',
            'overallall', 'overallall2', 'valGrade',
            'avgoverall', 'maxoverall', 'minoverall',
            'gradeDistribution', 'gradingScale'
        ));
        
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream("Rowscore_{$groupName}.pdf");
    }

    public function studentreport()
    {
        $percentagequiz = "";

        $percentagetest = "";

        $percentageassign = "";

        $percentagemidterm = "";

        $percentagefinal = "";

        $percentagepaperwork = "";

        $percentagepractical = "";

        $percentageother = "";

        $percentageextra = "";

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
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'quiz']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

        //dd($percentquiz);
  
        //get marked quiz
        $quiz = DB::table('tblclassstudentquiz')
                ->join('tblclassquiz', 'tblclassstudentquiz.quizid', 'tblclassquiz.id')
                ->where([
                    ['tblclassstudentquiz.userid', request()->student],
                    ['tblclassquiz.classid', request()->id],
                    ['tblclassquiz.sessionid', Session::get('SessionID')],
                    ['tblclassquiz.status', 2]
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

        //TEST

        $percenttest = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'test']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

        //get marked test
        $test = DB::table('tblclassstudenttest')
                ->join('tblclasstest', 'tblclassstudenttest.testid', 'tblclasstest.id')
                ->where([
                    ['tblclassstudenttest.userid', request()->student],
                    ['tblclasstest.classid', request()->id],
                    ['tblclasstest.sessionid', Session::get('SessionID')],
                    ['tblclasstest.status', 2]
                ]);
        
        $totaltest = $test->sum('tblclasstest.total_mark');

        $marktest = $test->sum('tblclassstudenttest.final_mark');

        if($percenttest != null)
        {
            $percentagetest = $percenttest->mark_percentage;
        }

        $testlist = $test->get();

        if($totaltest != 0 && $marktest != 0)
        {
            $total_alltest = round(( (int)$marktest / (int)$totaltest ) * (int)$percentagetest);
        }else{
            $total_alltest = 0;
        }


        //ASSIGNMENT

        $percentassign = DB::table('tblclassmarks')
                        ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                        ['subjek.id', Session::get('CourseID')],
                        ['assessment', 'assignment']
                        ])
                        ->orderBy('tblclassmarks.id', 'desc')
                        ->first();

        //dd($percent);
  
        //get marked assign
        $assign = DB::table('tblclassstudentassign')
                ->join('tblclassassign', 'tblclassstudentassign.assignid', 'tblclassassign.id')
                ->where([
                    ['tblclassstudentassign.userid', request()->student],
                    ['tblclassassign.classid', request()->id],
                    ['tblclassassign.sessionid', Session::get('SessionID')],
                    ['tblclassassign.status', 2]
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
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'midterm']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

        //dd($percent);
  
        $midterm = DB::table('tblclassstudentmidterm')
                ->join('tblclassmidterm', 'tblclassstudentmidterm.midtermid', 'tblclassmidterm.id')
                ->where([
                    ['tblclassstudentmidterm.userid', request()->student],
                    ['tblclassmidterm.classid', request()->id],
                    ['tblclassmidterm.sessionid', Session::get('SessionID')],
                    ['tblclassmidterm.status', 2]
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
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'final']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

        //dd($percent);
  
        $final = DB::table('tblclassstudentfinal')
                ->join('tblclassfinal', 'tblclassstudentfinal.finalid', 'tblclassfinal.id')
                ->where([
                    ['tblclassstudentfinal.userid', request()->student],
                    ['tblclassfinal.classid', request()->id],
                    ['tblclassfinal.sessionid', Session::get('SessionID')],
                    ['tblclassfinal.status', 2]
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
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'paperwork']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

        //dd($percent);
  
        //get marked paperwork
        $paperwork = DB::table('tblclassstudentpaperwork')
                ->join('tblclasspaperwork', 'tblclassstudentpaperwork.paperworkid', 'tblclasspaperwork.id')
                ->where([
                    ['tblclassstudentpaperwork.userid', request()->student],
                    ['tblclasspaperwork.classid', request()->id],
                    ['tblclasspaperwork.sessionid', Session::get('SessionID')],
                    ['tblclasspaperwork.status', 2]
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
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'practical']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

        //dd($percent);
  
        //get marked practical
        $practical = DB::table('tblclassstudentpractical')
                ->join('tblclasspractical', 'tblclassstudentpractical.practicalid', 'tblclasspractical.id')
                ->where([
                    ['tblclassstudentpractical.userid', request()->student],
                    ['tblclasspractical.classid', request()->id],
                    ['tblclasspractical.sessionid', Session::get('SessionID')],
                    ['tblclasspractical.status', 2]
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
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'lain-lain']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

        $other = DB::table('tblclassstudentother')
                ->join('tblclassother', 'tblclassstudentother.otherid', 'tblclassother.id')
                ->where([
                    ['tblclassstudentother.userid', request()->student],
                    ['tblclassother.classid', request()->id],
                    ['tblclassother.sessionid', Session::get('SessionID')],
                    ['tblclassother.status', 2]
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

        //EXTRA

        $percentextra = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', Session::get('CourseID')],
                                ['assessment', 'extra']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

        $extra = DB::table('tblclassstudentextra')
                ->join('tblclassextra', 'tblclassstudentextra.extraid', 'tblclassextra.id')
                ->where([
                    ['tblclassstudentextra.userid', request()->student],
                    ['tblclassextra.classid', request()->id],
                    ['tblclassextra.sessionid', Session::get('SessionID')],
                    ['tblclassextra.status', 2]
                ]);
        
        $totalextra = $extra->sum('tblclassextra.total_mark');

        $markextra = $extra->sum('tblclassstudentextra.final_mark');

        if($percentextra != null)
        {
            $percentageextra = $percentextra->mark_percentage;
        }

        $extralist = $extra->get();

        if($totalextra != 0 && $markextra != 0)
        {
            $total_allextra = round((int)$markextra);
        }else{
            $total_allextra = 0;
        }

        return view('lecturer.courseassessment.reportdetails', compact('student', 'quizlist', 'totalquiz', 'markquiz', 'percentagequiz', 'total_allquiz',
                                                                                  'testlist', 'totaltest', 'marktest', 'percentagetest', 'total_alltest',
                                                                                  'assignlist', 'totalassign', 'markassign', 'percentageassign', 'total_allassign',
                                                                                  'midtermlist', 'totalmidterm', 'markmidterm', 'percentagemidterm', 'total_allmidterm',
                                                                                  'finallist', 'totalfinal', 'markfinal', 'percentagefinal', 'total_allfinal',
                                                                                  'paperworklist', 'totalpaperwork', 'markpaperwork', 'percentagepaperwork', 'total_allpaperwork',
                                                                                  'practicallist', 'totalpractical', 'markpractical', 'percentagepractical', 'total_allpractical',
                                                                                  'otherlist', 'totalother', 'markother', 'percentageother', 'total_allother',
                                                                                  'extralist', 'totalextra', 'markextra', 'percentageextra', 'total_allextra'));
    }

    public function reportAttendance()
    {
        $user = Auth::user();

        $courseid = Session::get('CourseID');

        $sessionid = Session::get('SessionID');

        $students = [];
        $list = [];
        $status = [];



        $groups = DB::table('user_subjek')
                  ->join('users', 'user_subjek.user_ic', 'users.ic')
                  ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                  ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                  ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id', 'users.name', 'subjek.course_name', 'subjek.course_code')
                  ->where([
                     ['user_subjek.user_ic', $user->ic],
                     ['user_subjek.session_id', Session::get('SessionID')],
                     ['subjek.id', $courseid]
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
                ['user_subjek.session_id', $sessionid],
                ['subjek.id', $courseid]
                ])
                ->whereNotIn('students.status', [4,5,6,7,16])
                ->where('student_subjek.group_name', $grp->group_name)
                ->orderBy('students.name')->get();

                $collection = collect($students[$ky]);

                $list[] = DB::table('tblclassattendance')
                ->join('user_subjek', 'tblclassattendance.groupid', 'user_subjek.id')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->where([
                    ['subjek.id', $courseid],
                    ['user_subjek.session_id', $sessionid],
                    ['user_subjek.user_ic', $user->ic],
                    ['tblclassattendance.groupname', $grp->group_name]
                ])->groupBy('tblclassattendance.classdate')
                ->orderBy('tblclassattendance.classdate', 'ASC')
                ->select('tblclassattendance.*')->get();

                //dd($list);

                foreach($students[$ky] as $key => $std)
                {

                    foreach($list[$ky] as $keys => $ls)
                    {
                        $atten = DB::table('tblclassattendance')
                        ->where([
                            ['tblclassattendance.groupid', $ls->groupid],
                            ['tblclassattendance.groupname', $grp->group_name],
                            ['tblclassattendance.student_ic', $std->ic],
                            ['tblclassattendance.classdate', $ls->classdate]
                        ])->select('tblclassattendance.*');
                        
                        $attendance = $atten->first();

                        if($atten->exists())
                        {

                            if($attendance->excuse == null && $attendance->mc == null && $attendance->lc == null)
                            {

                                $status[$ky][$key][$keys] = 'Present';

                            }elseif($attendance->excuse != null){

                                $status[$ky][$key][$keys] = 'THB';

                            }elseif($attendance->mc != null){

                                $status[$ky][$key][$keys] = 'MC';

                            }elseif($attendance->lc != null){

                                $status[$ky][$key][$keys] = 'NC/LC';

                            }


                        }else{

                            $status[$ky][$key][$keys] = 'Absent';

                        }
                        

                    }


                }

        }

        //dd($status[$ky][$key]);

        return view('lecturer.class.attendancereport', compact('groups', 'students', 'list', 'status'));

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
                    ['user_subjek.session_id', $sessionid],
                    ['user_subjek.user_ic', Auth::user()->ic]
                ])->groupBy('tblclassattendance.groupname')->groupBy('tblclassattendance.classdate')
                ->orderBy('tblclassattendance.classdate', 'ASC')->get();

        //dd($list);

        return view('lecturer.class.attendancelist', compact('list'));

    }

    public function reportAttendance2(Request $request)
    {

        $courseid = Session::get('CourseID');

        $sessionid = Session::get('SessionID');

        $student = DB::table('student_subjek')
                    ->join('students', 'student_subjek.student_ic', 'students.ic')->where('student_subjek.group_id', $request->group)->where('student_subjek.group_name', $request->name);
                    //->join('user_subjek', 'student_subjek.group_id', 'user_subjek.id')
                    //->join('subjek', 'user_subjek.course_id', 'subjek.sub_id');
        
        $students = $student->get();

        $group = $student->join('user_subjek', 'student_subjek.group_id', 'user_subjek.id')
        ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
        ->select('user_subjek.*', 'student_subjek.group_name', 'subjek.*')->first();

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

    public function deleteAttendance(Request $request)
    {

        DB::table('tblclassattendance')->where([
            ['classdate', $request->from],
            ['classend', $request->to],
            ['groupid', $request->group],
            ['groupname', $request->name]
        ])->delete();

        return response()->json(['message' => 'Attendance has been successfully deleted!']);

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
        $subid = DB::table('subjek')->where('id', Session::get('CourseID'))->pluck('sub_id');

        $id = DB::table('subjek')->where('sub_id', $subid)->pluck('id');

        $folder = DB::table('lecturer_dir')
                   ->where([
                    ['Addby', $request->ic]
                    ])->whereIn('CourseID', $id)->get();

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
                ->join('tblclassquizstatus', 'tblclassquiz.status', 'tblclassquizstatus.id')
                ->join('sessions', 'tblclassquiz.sessionid', 'sessions.SessionID')
                ->where([
                    ['tblclassquiz.addby', $request->ic],
                    ['tblclassquiz.classid', Session::get('CourseID')]
                ])
                ->select('tblclassquiz.*', 'tblclassquizstatus.statusname', 'sessions.SessionName')->get();


        return view('lecturer.library.getQuiz', compact('quiz'));

    }

    public function getTest(Request $request)
    {

        $test = DB::table('tblclasstest')
                ->join('tblclassteststatus', 'tblclasstest.status', 'tblclassteststatus.id')
                ->join('sessions', 'tblclasstest.sessionid', 'sessions.SessionID')
                ->where([
                    ['tblclasstest.addby', $request->ic],
                    ['tblclasstest.classid', Session::get('CourseID')]
                ])
                ->select('tblclasstest.*', 'tblclassteststatus.statusname', 'sessions.SessionName')->get();


        return view('lecturer.library.getTest', compact('test'));

    }

    public function getAssignment(Request $request)
    {

        $assign = DB::table('tblclassassign')
                ->join('tblclassassignstatus', 'tblclassassign.status', 'tblclassassignstatus.id')
                ->join('sessions', 'tblclassassign.sessionid', 'sessions.SessionID')
                ->where([
                    ['tblclassassign.addby', $request->ic],
                    ['tblclassassign.classid', Session::get('CourseID')]
                ])
                ->select('tblclassassign.*', 'tblclassassignstatus.statusname', 'sessions.SessionName')->get();


        return view('lecturer.library.getAssignment', compact('assign'));

    }

    public function getMidterm(Request $request)
    {

        $midterm = DB::table('tblclassmidterm')
                ->join('tblclassmidtermstatus', 'tblclassmidterm.status', 'tblclassmidtermstatus.id')
                ->join('sessions', 'tblclassmidterm.sessionid', 'sessions.SessionID')
                ->where([
                    ['tblclassmidterm.addby', $request->ic],
                    ['tblclassmidterm.classid', Session::get('CourseID')]
                ])
                ->select('tblclassmidterm.*', 'tblclassmidtermstatus.statusname', 'sessions.SessionName')->get();

    }

    public function getFinal(Request $request)
    {

        $final = DB::table('tblclassfinal')
                ->join('tblclassfinalstatus', 'tblclassfinal.status', 'tblclassfinalstatus.id')
                ->join('sessions', 'tblclassfinal.sessionid', 'sessions.SessionID')
                ->where([
                    ['tblclassfinal.addby', $request->ic],
                    ['tblclassfinal.classid', Session::get('CourseID')]
                ])
                ->select('tblclassfinal.*', 'tblclassfinalstatus.statusname', 'sessions.SessionName')->get();


        return view('lecturer.library.getFinal', compact('final'));

    }

    public function autoudateData()
    {
        $students = [];

        $quiz = [];
        $quizcollection = [];
        $overallquiz = [];
        $quizanswer = [];
        $quizavg = [];
        $quizmax = [];
        $quizmin = [];
        $quizavgoverall = [];

        $test = [];
        $testcollection = [];
        $overalltest = [];
        $testanswer = [];
        $testavg = [];
        $testmax = [];
        $testmin = [];
        $testavgoverall = [];

        $assign = [];
        $assigncollection = [];
        $overallassign = [];
        $assignanswer = [];
        $assignavg = [];
        $assignmax = [];
        $assignmin = [];
        $assignavgoverall = [];

        $midterm = [];
        $midtermcollection = [];
        $overallmidterm = [];
        $midtermanswer = [];
        $midtermavg = [];
        $midtermmax = [];
        $midtermmin = [];
        $midtermavgoverall = [];

        $final = [];
        $finalcollection = [];
        $overallfinal = [];
        $finalanswer = [];
        $finalavg = [];
        $finalmax = [];
        $finalmin = [];
        $finalavgoverall = [];

        $paperwork = [];
        $paperworkcollection = [];
        $overallpaperwork = [];
        $paperworkanswer = [];
        $paperworkavg = [];
        $paperworkmax = [];
        $paperworkmin = [];
        $paperworkavgoverall = [];

        $practical = [];
        $practicalcollection = [];
        $overallpractical = [];
        $practicalanswer = [];
        $practicalavg = [];
        $practicalmax = [];
        $practicalmin = [];
        $practicalavgoverall = [];

        $other = [];
        $othercollection = [];
        $overallother = [];
        $otheranswer = [];
        $otheravg = [];
        $othermax = [];
        $othermin = [];
        $otheravgoverall = [];

        $extra = [];
        $extracollection = [];
        $overallextra = [];
        $extraanswer = [];
        $extraavg = [];
        $extramax = [];
        $extramin = [];
        $extraavgoverall = [];

        $overallall = [];
        $avgoverall = [];

        $groups = DB::table('user_subjek')
                  ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                  ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                  ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id', 'subjek.id as ID')
                  ->groupBy('student_subjek.group_name')->get();

        foreach($groups as $ky => $grp)
        {


                $students[] = $data = DB::table('user_subjek')
                ->join('student_subjek', 'user_subjek.id', 'student_subjek.group_id')
                ->join('students', 'student_subjek.student_ic', 'students.ic')
                ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                ->select('user_subjek.*','student_subjek.group_name','student_subjek.group_id','students.*')
                ->where([
                ['user_subjek.user_ic', $grp->user_ic],
                ['user_subjek.session_id', $grp->session_id],
                ['subjek.id', $grp->ID]
                ])->where('student_subjek.group_name', $grp->group_name)
                ->orderBy('students.name')->get();

                $collection = collect($students[$ky]);

                //QUIZ

                $quizs = DB::table('tblclassquiz')
                        ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                        ->where([
                            ['tblclassquiz.classid', $grp->ID],
                            ['tblclassquiz.sessionid', $grp->session_id],
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
                            ['tblclasstest.classid', $grp->ID],
                            ['tblclasstest.sessionid', $grp->session_id],
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
                            ['tblclassassign.classid', $grp->ID],
                            ['tblclassassign.sessionid', $grp->session_id],
                            ['tblclassassign_group.groupname', $grp->group_name],
                            ['tblclassassign.status', '!=', 3]
                        ]);

                $assign[] = $assigns->get();

                $assignid = $assigns->pluck('tblclassassign.id');

                $totalassign = $assigns->sum('tblclassassign.total_mark');

                //EXTRA

                $extras = DB::table('tblclassextra')
                        ->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                        ->where([
                            ['tblclassextra.classid', $grp->ID],
                            ['tblclassextra.sessionid', $grp->session_id],
                            ['tblclassextra_group.groupname', $grp->group_name],
                            ['tblclassextra.status', '!=', 3]
                        ]);

                $extra[] = $extras->get();

                $extraid = $extras->pluck('tblclassextra.id');

                $totalextra = $extras->sum('tblclassextra.total_mark');

                //OTHER

                $others = DB::table('tblclassother')
                        ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                        ->where([
                            ['tblclassother.classid', $grp->ID],
                            ['tblclassother.sessionid', $grp->session_id],
                            ['tblclassother_group.groupname', $grp->group_name],
                            ['tblclassother.status', '!=', 3]
                        ]);

                $other[] = $others->get();

                $otherid = $others->pluck('tblclassother.id');

                $totalother = $others->sum('tblclassother.total_mark');

                //MIDTERM

                $midterms = DB::table('tblclassmidterm')
                        ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                        ->where([
                            ['tblclassmidterm.classid', $grp->ID],
                            ['tblclassmidterm.sessionid', $grp->session_id],
                            ['tblclassmidterm_group.groupname', $grp->group_name],
                            ['tblclassmidterm.status', '!=', 3]
                        ]);

                $midterm[] = $midterms->get();

                $midtermid = $midterms->pluck('tblclassmidterm.id');

                $totalmidterm = $midterms->sum('tblclassmidterm.total_mark');

                //FINAL

                $finals = DB::table('tblclassfinal')
                        ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                        ->where([
                            ['tblclassfinal.classid', $grp->ID],
                            ['tblclassfinal.sessionid', $grp->session_id],
                            ['tblclassfinal_group.groupname', $grp->group_name],
                            ['tblclassfinal.status', '!=', 3]
                        ]);

                $final[] = $finals->get();

                $finalid = $finals->pluck('tblclassfinal.id');

                $totalfinal = $finals->sum('tblclassfinal.total_mark');

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
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', $grp->ID],
                                ['assessment', 'quiz']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($quizs = DB::table('tblclassquiz')
                    ->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                    ->where([
                        ['tblclassquiz.classid', $grp->ID],
                        ['tblclassquiz.sessionid', $grp->session_id],
                        ['tblclassquiz_group.groupname', $grp->group_name],
                        ['tblclassquiz.status', '!=', 3]
                    ])->exists()){
                        if($percentquiz != null)
                        {
                            if(DB::table('tblclassquiz')
                            ->where([
                                ['classid', $grp->ID],
                                ['sessionid', $grp->session_id]
                            ])->exists()){
                                //dd($totalquiz);
                                if ($totalquiz > 0) {
                                    $overallquiz[$ky][$keys] = number_format((float)$sumquiz[$ky][$keys] / $totalquiz * $percentquiz->mark_percentage, 2, '.', '');
                                } else {
                                    $overallquiz[$ky][$keys] = 0;
                                }
                            }else{
                                $overallquiz[$ky][$keys] = 0;
                            }
                        }else{
                            $overallquiz[$ky][$keys] = 0;
                        }
                    }else{
                        $overallquiz[$ky][$keys] = 0;
                    }


                    // TEST
                    
                    foreach($test[$ky] as $key =>$qz)
                    {
                    
                    $testanswer[$ky][$keys][$key] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->where('testid', $qz->testid)->first();

                    }

                    $sumtest[$ky][$keys] = DB::table('tblclassstudenttest')->where('userid', $std->ic)->whereIn('testid', $testid)->sum('final_mark');

                    $percenttest = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', $grp->ID],
                                ['assessment', 'test']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($tests = DB::table('tblclasstest')
                    ->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                    ->where([
                        ['tblclasstest.classid', $grp->ID],
                        ['tblclasstest.sessionid', $grp->session_id],
                        ['tblclasstest_group.groupname', $grp->group_name],
                        ['tblclasstest.status', '!=', 3]
                    ])->exists()){
                        if($percenttest != null)
                        {
                            if(DB::table('tblclasstest')
                            ->where([
                                ['classid', $grp->ID],
                                ['sessionid', $grp->session_id]
                            ])->exists()){
                                //dd($totaltest);
                                if ($totaltest > 0) {
                                    $overalltest[$ky][$keys] = number_format((float)$sumtest[$ky][$keys] / $totaltest * $percenttest->mark_percentage, 2, '.', '');
                                } else {
                                    $overalltest[$ky][$keys] = 0;
                                }

                                $testcollection = collect($overalltest[$ky]);
                            }else{
                                $overalltest[$ky][$keys] = 0;

                                $testcollection = collect($overalltest[$ky]);
                            }
            
                        }else{
                            $overalltest[$ky][$keys] = 0;

                            $testcollection = collect($overalltest[$ky]);
                        }
                    }else{
                        $overalltest[$ky][$keys] = 0;

                        $testcollection = collect($overalltest[$ky]);
                    }


                    // ASSIGNMENT
                    
                    foreach($assign[$ky] as $key =>$qz)
                    {
                    
                    $assignanswer[$ky][$keys][$key] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->where('assignid', $qz->assignid)->first();

                    }

                    $sumassign[$ky][$keys] = DB::table('tblclassstudentassign')->where('userid', $std->ic)->whereIn('assignid', $assignid)->sum('final_mark');

                    $percentassign = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', $grp->ID],
                                ['assessment', 'assignment']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($assigns = DB::table('tblclassassign')
                    ->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                    ->where([
                        ['tblclassassign.classid', $grp->ID],
                        ['tblclassassign.sessionid', $grp->session_id],
                        ['tblclassassign_group.groupname', $grp->group_name],
                        ['tblclassassign.status', '!=', 3]
                    ])->exists()){
                        if($percentassign != null)
                        {
                            if(DB::table('tblclassassign')
                            ->where([
                                ['classid', $grp->ID],
                                ['sessionid', $grp->session_id]
                            ])->exists()){
                                //dd($totalassign);
                                if ($totalassign > 0) {
                                    $overallassign[$ky][$keys] = number_format((float)$sumassign[$ky][$keys] / $totalassign * $percentassign->mark_percentage, 2, '.', '');
                                } else {
                                    $overallassign[$ky][$keys] = 0;
                                }
                            }else{
                               $overallassign[$ky][$keys] = 0;
                            }
            
                        }else{
                            $overallassign[$ky][$keys] = 0;
                        }
                    }else{
                        $overallassign[$ky][$keys] = 0;
                    }

                    // EXTRA
                    
                    foreach($extra[$ky] as $key =>$qz)
                    {
                    
                    $extraanswer[$ky][$keys][$key] = DB::table('tblclassstudentextra')->where('userid', $std->ic)->where('extraid', $qz->extraid)->first();

                    }

                    $sumextra[$ky][$keys] = DB::table('tblclassstudentextra')->where('userid', $std->ic)->whereIn('extraid', $extraid)->sum('total_mark');

                    $percentextra = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', $grp->ID],
                                ['assessment', 'extra']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($extras = DB::table('tblclassextra')
                    ->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                    ->where([
                        ['tblclassextra.classid', $grp->ID],
                        ['tblclassextra.sessionid', $grp->session_id],
                        ['tblclassextra_group.groupname', $grp->group_name],
                        ['tblclassextra.status', '!=', 3]
                    ])->exists()){
                        if($percentextra != null)
                        {
                            if(DB::table('tblclassextra')
                            ->where([
                                ['classid', $grp->ID],
                                ['sessionid', $grp->session_id]
                            ])->exists()){
                                //dd($totalextra);
                                if ($totalextra > 0) {
                                    $overallextra[$ky][$keys] = number_format((float)$sumextra[$ky][$keys] / $totalextra * $percentextra->mark_percentage, 2, '.', '');
                                } else {
                                    $overallextra[$ky][$keys] = 0;
                                }
                            }else{
                                $overallextra[$ky][$keys] = 0;
                            }
                        }else{
                            $overallextra[$ky][$keys] = 0;
                        }
                    }else{
                        $overallextra[$ky][$keys] = 0;
                    }

                    // OTHER
                    
                    foreach($other[$ky] as $key =>$qz)
                    {
                    
                    $otheranswer[$ky][$keys][$key] = DB::table('tblclassstudentother')->where('userid', $std->ic)->where('otherid', $qz->otherid)->first();

                    }

                    $sumother[$ky][$keys] = DB::table('tblclassstudentother')->where('userid', $std->ic)->whereIn('otherid', $otherid)->sum('total_mark');

                    $percentother = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', $grp->ID],
                                ['assessment', 'lain-lain']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($others = DB::table('tblclassother')
                    ->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                    ->where([
                        ['tblclassother.classid', $grp->ID],
                        ['tblclassother.sessionid', $grp->session_id],
                        ['tblclassother_group.groupname', $grp->group_name],
                        ['tblclassother.status', '!=', 3]
                    ])->exists()){
                        if($percentother != null)
                        {
                            if(DB::table('tblclassother')
                            ->where([
                                ['classid', $grp->ID],
                                ['sessionid', $grp->session_id]
                            ])->exists()){
                                //dd($totalother);
                                if ($totalother > 0) {
                                    $overallother[$ky][$keys] = number_format((float)$sumother[$ky][$keys] / $totalother * $percentother->mark_percentage, 2, '.', '');
                                } else {
                                    $overallother[$ky][$keys] = 0;
                                }
                            }else{
                                $overallother[$ky][$keys] = 0;
                            }
                        }else{
                            $overallother[$ky][$keys] = 0;
                        }
                    }else{
                        $overallother[$ky][$keys] = 0;
                    }

                    // MIDTERM
                    
                    foreach($midterm[$ky] as $key =>$qz)
                    {
                    
                    $midtermanswer[$ky][$keys][$key] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->where('midtermid', $qz->midtermid)->first();

                    }

                    $summidterm[$ky][$keys] = DB::table('tblclassstudentmidterm')->where('userid', $std->ic)->whereIn('midtermid', $midtermid)->sum('final_mark');

                    $percentmidterm = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', $grp->ID],
                                ['assessment', 'midterm']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($midterms = DB::table('tblclassmidterm')
                    ->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                    ->where([
                        ['tblclassmidterm.classid', $grp->ID],
                        ['tblclassmidterm.sessionid', $grp->session_id],
                        ['tblclassmidterm_group.groupname', $grp->group_name],
                        ['tblclassmidterm.status', '!=', 3]
                    ])->exists()){
                        if($percentmidterm != null)
                        {
                            if(DB::table('tblclassmidterm')
                            ->where([
                                ['classid', $grp->ID],
                                ['sessionid', $grp->session_id]
                            ])->exists()){
                                //dd($totalmidterm);
                                if ($totalmidterm > 0) {
                                    $overallmidterm[$ky][$keys] = number_format((float)$summidterm[$ky][$keys] / $totalmidterm * $percentmidterm->mark_percentage, 2, '.', '');
                                } else {
                                    $overallmidterm[$ky][$keys] = 0;
                                }
                            }else{
                                $overallmidterm[$ky][$keys] = 0;
                            }
            
                        }else{
                            $overallmidterm[$ky][$keys] = 0;
                        }
                    }else{
                        $overallmidterm[$ky][$keys] = 0;
                    }

                    // FINAL
                    
                    foreach($final[$ky] as $key =>$qz)
                    {
                    
                    $finalanswer[$ky][$keys][$key] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->where('finalid', $qz->finalid)->first();

                    }

                    $sumfinal[$ky][$keys] = DB::table('tblclassstudentfinal')->where('userid', $std->ic)->whereIn('finalid', $finalid)->sum('final_mark');

                    $percentfinal = DB::table('tblclassmarks')
                                ->join('subjek', 'tblclassmarks.course_id', 'subjek.sub_id')->where([
                                ['subjek.id', $grp->ID],
                                ['assessment', 'final']
                                ])
                                ->orderBy('tblclassmarks.id', 'desc')
                                ->first();

                    if($finals = DB::table('tblclassfinal')
                    ->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                    ->where([
                        ['tblclassfinal.classid', $grp->ID],
                        ['tblclassfinal.sessionid', $grp->session_id],
                        ['tblclassfinal_group.groupname', $grp->group_name],
                        ['tblclassfinal.status', '!=', 3]
                    ])->exists()){
                        if($percentfinal != null)
                        {
                            if(DB::table('tblclassfinal')
                            ->where([
                                ['classid', $grp->ID],
                                ['sessionid', $grp->session_id]
                            ])->exists()){
                                //dd($totalfinal);
                                if ($totalfinal > 0) {
                                    $overallfinal[$ky][$keys] = number_format((float)$sumfinal[$ky][$keys] / $totalfinal * $percentfinal->mark_percentage, 2, '.', '');
                                } else {
                                    $overallfinal[$ky][$keys] = 0;
                                }
                            }else{
                                $overallfinal[$ky][$keys] = 0;
                            }
                        }else{
                            $overallfinal[$ky][$keys] = 0;
                        }
                    }else{
                        $overallfinal[$ky][$keys] = 0;
                    }

                    $overallall[$ky][$keys] = $overallquiz[$ky][$keys] + $overalltest[$ky][$keys] + $overallassign[$ky][$keys] + $overallextra[$ky][$keys] + $overallother[$ky][$keys] + $overallmidterm[$ky][$keys] + $overallfinal[$ky][$keys];

                    $collectionall = collect($overallall[$ky]);
            
                }
        }

        // update the data
        return response()->json(['status' => 'success']);
    }

    public function getSuratAmaran()
    {

        return view('lecturer.class.surat_amaran.surat_amaran');

    }

    public function classSchedule()
    {

        return view('lecturer.schedule.schedule');

    }

    public function fetchEvents()
    {
        $user = Auth::user();

        $events = Tblevent2::join('user_subjek', 'tblevents_second.group_id', 'user_subjek.id')
                  ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
                  ->join('tbllecture_room', 'tblevents_second.lecture_id', 'tbllecture_room.id')
                  ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
                  ->where([
                    ['tblevents_second.user_ic', $user->ic],
                    ['sessions.Status', 'ACTIVE']
                    ])
                  ->groupBy('subjek.sub_id', 'tblevents_second.id')
                  ->select('tblevents_second.*', 'subjek.course_code AS code' , 'subjek.course_name AS subject', 'tbllecture_room.name AS room', 'sessions.SessionName AS session')->get();

        $formattedEvents = $events->map(function ($event) {

            $count = DB::table('student_subjek')
                                ->where([
                                ['group_id', $event->group_id],
                                ['group_name', $event->group_name]
                                ])
                                ->select(DB::raw('COUNT(student_ic) AS total_student'))
                                ->first();

            $program = DB::table('student_subjek')
                                ->join('students', 'student_subjek.student_ic', 'students.ic')
                                ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                                ->where([
                                ['student_subjek.group_id', $event->group_id],
                                ['student_subjek.group_name', $event->group_name]
                                ])
                                ->groupBy('tblprogramme.id')
                                ->select('tblprogramme.*')
                                ->get();

            // Convert program information into a string
            $programInfo = $program->map(function($prog) {
                return $prog->progcode; // Assuming 'progname' is the relevant field you want to display
            })->implode(', ');

            // Map day of the week from PHP (1 for Monday through 7 for Sunday) to FullCalendar (0 for Sunday through 6 for Saturday)
            $dayOfWeekMap = [
                1 => 1, // Monday
                2 => 2, // Tuesday
                3 => 3, // Wednesday
                4 => 4, // Thursday
                5 => 5, // Friday
                6 => 6, // Saturday
                7 => 0  // Sunday
            ];

            $dayOfWeek = date('N', strtotime($event->start));
            $fullCalendarDayOfWeek = $dayOfWeekMap[$dayOfWeek];

            return [
                'id' => $event->id,
                'title' => $event->code . ' - ' . $event->subject . ' (' . $event->group_name . ')',
                'description' => $programInfo . " (" . $event->session . ")" . "<br>" . strtoupper($event->room) . " | " . 'Total Student: ' . $count->total_student,
                'startTime' => date('H:i', strtotime($event->start)),
                'endTime' => date('H:i', strtotime($event->end)),
                'duration' => gmdate('H:i', strtotime($event->end) - strtotime($event->start)),
                'daysOfWeek' => [$fullCalendarDayOfWeek], // Recurring on the same day of the week
                'programInfo' => $programInfo, // Add program info to the event object
            ];
        });

        return response()->json($formattedEvents);
    }

    // Replacement Class Methods
    public function replacementClass()
    {
        // Load lecture rooms directly to avoid AJAX issues
        try {
            $lectureRooms = DB::table('tbllecture_room')
                              ->orderBy('name')
                              ->get();
        } catch (\Exception $e) {
            $lectureRooms = collect(); // Empty collection if table doesn't exist
        }
        
        return view('lecturer.class.replacement_class', compact('lectureRooms'));
    }

    public function replacementClassGetGroup()
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
            // Skip if group_name is empty or null
            if(empty($grp->group_name)) {
                continue;
            }
            
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
                    <span><strong>'. htmlspecialchars($grp->group_name) .'</strong></span><br>
                    <span><strong>'. htmlentities($lecturer->name, ENT_QUOTES) .'</strong></span><br>
                    <span>'. $lecturer->email .' | <strong class="text-fade"">'.$lecturer->faculty .'</strong></span><br>
                    <span class="text-fade"></span>
                </div>
            </div>\' value="'. $grp->id . '|' . htmlspecialchars($grp->group_name) .'" ></option>';
        }
        
        return $content;
    }

    public function replacementClassGetStudentProgram(Request $request)
    {
        $group = explode('|', $request->group);
        
        // Validate that we have both group_id and group_name
        if(count($group) < 2) {
            return "<option value='0' selected disabled>Invalid group data</option>";
        }

        $program = student::join('students', 'student_subjek.student_ic', 'students.ic')
                    ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                    ->where('student_subjek.group_id', $group[0])->where('student_subjek.group_name', $group[1])
                    ->where('student_subjek.sessionid', Session::get('SessionID'))
                    ->whereNotIn('students.status', [4,5,6,7,16])
                    ->groupBy('tblprogramme.id')
                    ->select('tblprogramme.*')
                    ->get();

        $content = "";
        $content .= "<option value='0' selected disabled>-</option>";
        foreach($program as $prg){
            $content .= '<option data-style="btn-inverse" value="'. $prg->id .'" >'. $prg->progname .' ('. $prg->progcode .')</option>';
        }
        
        return $content;
    }

    public function replacementClassGetWakilPelajar(Request $request)
    {
        $group = explode('|', $request->group);
        
        // Validate that we have both group_id and group_name
        if(count($group) < 2) {
            return "<option value='0' selected disabled>Invalid group data</option>";
        }

        $students = student::join('students', 'student_subjek.student_ic', 'students.ic')
                    ->join('tblprogramme', 'students.program', 'tblprogramme.id')
                    ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                    ->leftJoin('tblstudent_personal', 'students.ic', 'tblstudent_personal.student_ic')
                    ->where('group_id', $group[0])->where('group_name', $group[1])
                    ->where('student_subjek.sessionid', Session::get('SessionID'))
                    ->whereNotIn('students.status', [4,5,6,7,16]);

        if(isset($request->program) && count($request->program) > 0) {
            $students->whereIn('students.program', $request->program);
        }

        $students = $students->select('students.ic', 'students.name', 'students.no_matric', 'tblstudent_personal.no_tel', 'tblstudent_personal.no_tel2')
                    ->orderBy('students.name')->get();

        $content = "";
        $content .= "<option value='0' selected disabled>-</option>";
        foreach($students as $student){
            // Get phone number - prioritize no_tel, fallback to no_tel2
            $phoneNumber = $student->no_tel ?: $student->no_tel2;
            
            $content .= '<option value="'. $student->ic .'" data-name="'. htmlentities($student->name, ENT_QUOTES) .'" data-phone="'. ($phoneNumber ?: '') .'" >'. $student->name .' ('. $student->no_matric .')</option>';
        }
        
        return $content;
    }

    public function replacementClassGetLectureRooms()
    {
        try {
            // Debug: Check if table exists and has data
            $tableExists = DB::getSchemaBuilder()->hasTable('tbllecture_room');
            if (!$tableExists) {
                return "<option value='' disabled selected>Lecture room table not found</option>";
            }

            $rooms = DB::table('tbllecture_room')
                       ->orderBy('name')
                       ->get();

            if ($rooms->isEmpty()) {
                return "<option value='' disabled selected>No lecture rooms available</option>";
            }

            $content = "";
            $content .= "<option value='' disabled selected>Choose a room...</option>";
            foreach($rooms as $room){
                $content .= '<option value="'. $room->id .'" >'. htmlentities($room->name, ENT_QUOTES) .'</option>';
            }
            
            return $content;
        } catch (\Exception $e) {
            \Log::error('Error fetching lecture rooms: ' . $e->getMessage());
            return "<option value='' disabled selected>Error: " . $e->getMessage() . "</option>";
        }
    }

    public function storeReplacementClass(Request $request)
    {
        $data = $request->validate([
            'group' => ['required'],
            'program' => ['required', 'array'],
            'tarikh_kuliah_dibatalkan' => ['required', 'date'],
            'sebab_kuliah_dibatalkan' => ['required', 'string'],
            'maklumat_kuliah' => ['nullable', 'string'],
            'wakil_pelajar' => ['required'],
            'wakil_pelajar_nama' => ['required', 'string'],
            'wakil_pelajar_no_tel' => ['required', 'string'],
            'maklumat_kuliah_gantian_tarikh' => ['required', 'date'],
            'maklumat_kuliah_gantian_hari_masa' => ['required', 'string'],
            'lecture_room_id' => ['required', 'integer']
        ]);

        $group = explode('|', $data['group']);
        $user = Auth::user();
        
        // Get the user_subjek record
        $userSubjek = DB::table('user_subjek')
            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->where([
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', Session::get('SessionID')],
                ['subjek.id', Session::get('CourseID')],
                ['user_subjek.id', $group[0]]
            ])
            ->select('user_subjek.id')
            ->first();

        if (!$userSubjek) {
            return redirect()->back()->with('error', 'Invalid group selection!');
        }

        DB::table('replacement_class')->insert([
            'user_subjek_id' => $userSubjek->id,
            'tarikh_kuliah_dibatalkan' => $data['tarikh_kuliah_dibatalkan'],
            'sebab_kuliah_dibatalkan' => $data['sebab_kuliah_dibatalkan'],
            'maklumat_kuliah' => $data['maklumat_kuliah'],
            'wakil_pelajar_nama' => $data['wakil_pelajar_nama'],
            'wakil_pelajar_no_tel' => $data['wakil_pelajar_no_tel'],
            'maklumat_kuliah_gantian_tarikh' => $data['maklumat_kuliah_gantian_tarikh'],
            'maklumat_kuliah_gantian_hari_masa' => $data['maklumat_kuliah_gantian_hari_masa'],
            'lecture_room_id' => $data['lecture_room_id'],
            'group_id' => $group[0],
            'group_name' => $group[1],
            'selected_programs' => json_encode($data['program']),
            'student_ic' => $data['wakil_pelajar'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('message', 'Replacement class application has been submitted successfully!');
    }

    public function replacementClassList()
    {
        $user = Auth::user();
        $courseid = Session::get('CourseID');
        $sessionid = Session::get('SessionID');

        // Get replacement class applications for current lecturer and course
        $applications = DB::table('replacement_class')
            ->join('user_subjek', 'replacement_class.user_subjek_id', 'user_subjek.id')
            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
            ->join('students', 'replacement_class.student_ic', 'students.ic')
            ->join('tbllecture_room', 'replacement_class.lecture_room_id', 'tbllecture_room.id')
            ->leftJoin('tbllecture_room as revised_room', 'replacement_class.revised_room_id', 'revised_room.id')
            ->leftJoin('users as kp_user', 'replacement_class.kp_ic', 'kp_user.ic')
            ->where([
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', $sessionid],
                ['subjek.id', $courseid]
            ])
            ->select(
                'replacement_class.*',
                'subjek.course_name',
                'subjek.course_code', 
                'sessions.SessionName',
                'students.name as student_name',
                'students.no_matric',
                'tbllecture_room.name as room_name',
                'revised_room.name as revised_room_name',
                'kp_user.name as kp_name',
                'kp_user.email as kp_email'
            )
            ->orderBy('replacement_class.created_at', 'desc')
            ->paginate(10);

        // Get program details for each application
        foreach($applications as $app) {
            $programs = DB::table('tblprogramme')
                ->whereIn('id', json_decode($app->selected_programs))
                ->get();
            $app->programs = $programs;
        }

        return view('lecturer.class.replacement_class_list', compact('applications'));
    }

    public function getLectureRooms()
    {
        $rooms = DB::table('tbllecture_room')->get();
        $html = '<option value="" disabled selected>Choose a room...</option>';
        foreach($rooms as $room) {
            $html .= '<option value="'.$room->id.'">'.$room->name.'</option>';
        }
        return $html;
    }

    public function suggestRevisedDate(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:replacement_class,id',
            'revised_date' => 'required|date|after:today',
            'revised_time' => 'required|string|max:255',
            'revised_room_id' => 'required|exists:tbllecture_room,id'
        ]);

        $user = Auth::user();

        // Verify lecturer owns this application
        $application = DB::table('replacement_class')
            ->join('user_subjek', 'replacement_class.user_subjek_id', '=', 'user_subjek.id')
            ->where('replacement_class.id', $request->application_id)
            ->where('user_subjek.user_ic', $user->ic)
            ->first();

        if (!$application) {
            return response()->json(['error' => 'You do not have permission to update this application.'], 403);
        }

        // Check if application is rejected and no revised date has been submitted yet
        if ($application->is_verified !== 'NO' || $application->revised_date) {
            return response()->json(['error' => 'Cannot suggest revised date for this application.'], 400);
        }

        $updateData = [
            'revised_date' => $request->revised_date,
            'revised_time' => $request->revised_time,
            'revised_room_id' => $request->revised_room_id,
            'revised_status' => 'PENDING',
            'revised_rejection_reason' => null,
            'updated_at' => now()
        ];

        DB::table('replacement_class')
            ->where('id', $request->application_id)
            ->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Revised date submitted successfully and is pending approval.'
        ]);
    }

    public function editReplacementClass($id)
    {
        $user = Auth::user();
        $courseid = Session::get('CourseID');
        $sessionid = Session::get('SessionID');

        // Get the replacement class application with all related data
        $application = DB::table('replacement_class')
            ->join('user_subjek', 'replacement_class.user_subjek_id', 'user_subjek.id')
            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->join('sessions', 'user_subjek.session_id', 'sessions.SessionID')
            ->join('students', 'replacement_class.student_ic', 'students.ic')
            ->join('tbllecture_room', 'replacement_class.lecture_room_id', 'tbllecture_room.id')
            ->where([
                ['replacement_class.id', $id],
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', $sessionid],
                ['subjek.id', $courseid]
            ])
            ->select(
                'replacement_class.*',
                'subjek.course_name',
                'subjek.course_code',
                'sessions.SessionName',
                'students.name as student_name',
                'students.no_matric',
                'tbllecture_room.name as room_name'
            )
            ->first();

        if (!$application) {
            return redirect()->route('lecturer.class.replacement_class.list')
                ->with('error', 'Application not found or you do not have permission to edit it.');
        }

        // Check if application can be edited (not verified or has been verified)
        if ($application->is_verified !== 'PENDING' || ($application->revised_status && $application->revised_status !== 'PENDING')) {
            return redirect()->route('lecturer.class.replacement_class.list')
                ->with('error', 'Cannot edit application that has been verified or rejected.');
        }

        // Get programs for this application
        $programs = DB::table('tblprogramme')
            ->whereIn('id', json_decode($application->selected_programs))
            ->get();
        $application->programs = $programs;

        // Get all lecture rooms
        $lectureRooms = DB::table('tbllecture_room')->get();

        return view('lecturer.class.replacement_class_edit', compact('application', 'lectureRooms'));
    }

    public function updateReplacementClass(Request $request, $id)
    {
        $request->validate([
            'group' => 'required',
            'program' => 'required|array|min:1',
            'tarikh_kuliah_dibatalkan' => 'required|date',
            'sebab_kuliah_dibatalkan' => 'required|string|max:255',
            'maklumat_kuliah' => 'nullable|string',
            'wakil_pelajar' => 'required',
            'wakil_pelajar_nama' => 'required|string|max:255',
            'wakil_pelajar_no_tel' => 'required|string|max:20',
            'maklumat_kuliah_gantian_tarikh' => 'required|date|after:tarikh_kuliah_dibatalkan',
            'maklumat_kuliah_gantian_hari_masa' => 'required|string|max:255',
            'lecture_room_id' => 'required|exists:tbllecture_room,id'
        ]);

        $user = Auth::user();
        $courseid = Session::get('CourseID');
        $sessionid = Session::get('SessionID');

        // Verify lecturer owns this application and it can be edited
        $application = DB::table('replacement_class')
            ->join('user_subjek', 'replacement_class.user_subjek_id', '=', 'user_subjek.id')
            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->where([
                ['replacement_class.id', $id],
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', $sessionid],
                ['subjek.id', $courseid]
            ])
            ->select('replacement_class.*')
            ->first();

        if (!$application) {
            return redirect()->route('lecturer.class.replacement_class.list')
                ->with('error', 'Application not found or you do not have permission to edit it.');
        }

        // Check if application can be edited
        if ($application->is_verified !== 'PENDING' || ($application->revised_status && $application->revised_status !== 'PENDING')) {
            return redirect()->route('lecturer.class.replacement_class.list')
                ->with('error', 'Cannot edit application that has been verified or rejected.');
        }

        $updateData = [
            'tarikh_kuliah_dibatalkan' => $request->tarikh_kuliah_dibatalkan,
            'sebab_kuliah_dibatalkan' => $request->sebab_kuliah_dibatalkan,
            'maklumat_kuliah' => $request->maklumat_kuliah ?? '',
            'wakil_pelajar_nama' => $request->wakil_pelajar_nama,
            'wakil_pelajar_no_tel' => $request->wakil_pelajar_no_tel,
            'maklumat_kuliah_gantian_tarikh' => $request->maklumat_kuliah_gantian_tarikh,
            'maklumat_kuliah_gantian_hari_masa' => $request->maklumat_kuliah_gantian_hari_masa,
            'lecture_room_id' => $request->lecture_room_id,
            'group_id' => $request->group,
            'selected_programs' => json_encode($request->program),
            'student_ic' => $request->wakil_pelajar,
            'updated_at' => now()
        ];

        DB::table('replacement_class')
            ->where('id', $id)
            ->update($updateData);

        return redirect()->route('lecturer.class.replacement_class.list')
            ->with('message', 'Replacement class application has been updated successfully!');
    }

    public function deleteReplacementClass($id)
    {
        $user = Auth::user();
        $courseid = Session::get('CourseID');
        $sessionid = Session::get('SessionID');

        // Verify lecturer owns this application and it can be deleted
        $application = DB::table('replacement_class')
            ->join('user_subjek', 'replacement_class.user_subjek_id', '=', 'user_subjek.id')
            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->where([
                ['replacement_class.id', $id],
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', $sessionid],
                ['subjek.id', $courseid]
            ])
            ->select('replacement_class.*')
            ->first();

        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found or you do not have permission to delete it.'
            ], 403);
        }

        // Check if application can be deleted
        if ($application->is_verified !== 'PENDING' || ($application->revised_status && $application->revised_status !== 'PENDING')) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete application that has been verified or rejected.'
            ], 400);
        }

        DB::table('replacement_class')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Replacement class application has been deleted successfully!'
        ]);
    }

    /**
     * Get lecturer materials for current course
     */
    public function getLecturerMaterials()
    {
        $user = Auth::user();
        $courseId = Session::get('CourseID');
        $sessionId = Session::get('SessionID');

        // Get user_subjek.id
        $userSubjek = DB::table('user_subjek')
            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->where([
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', $sessionId],
                ['subjek.id', $courseId]
            ])
            ->select('user_subjek.id')
            ->first();

        if (!$userSubjek) {
            return response()->json(['materials' => []]);
        }

        $materials = DB::table('lecturer_materials')
            ->where('user_subjek_id', $userSubjek->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['materials' => $materials]);
    }

    /**
     * Upload lecturer materials
     */
    public function uploadLecturerMaterials(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,7z,jpg,jpeg,png,gif,bmp', // 10MB max per file
            'category' => 'required|in:Rubrik,Rowscore,Others',
            'description' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        $courseId = Session::get('CourseID');
        $sessionId = Session::get('SessionID');

        // Get user_subjek.id
        $userSubjek = DB::table('user_subjek')
            ->join('subjek', 'user_subjek.course_id', 'subjek.sub_id')
            ->where([
                ['user_subjek.user_ic', $user->ic],
                ['user_subjek.session_id', $sessionId],
                ['subjek.id', $courseId]
            ])
            ->select('user_subjek.id')
            ->first();

        if (!$userSubjek) {
            return response()->json(['success' => false, 'message' => 'Invalid course selection']);
        }

        $uploadedFiles = [];
        $files = $request->file('files', []);

        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            
            // Generate unique filename
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            
            // Store file in materials/{user_subjek_id}
            // Try to use the same storage disk as other files in the application
            $storageDisk = config('app.env') === 'production' ? 'linode' : 'public';
            $actualDisk = $storageDisk;
            
            try {
                if ($storageDisk === 'linode') {
                    // For Linode, use putFileAs with public visibility
                    $filePath = Storage::disk('linode')->putFileAs(
                        'materials/' . $userSubjek->id,
                        $file,
                        $fileName,
                        'public'
                    );
                } else {
                    // For local storage
                    $filePath = $file->storeAs(
                        'materials/' . $userSubjek->id,
                        $fileName,
                        $storageDisk
                    );
                }
            } catch (\Exception $e) {
                // Fallback to public disk if linode fails
                $actualDisk = 'public';
                $filePath = $file->storeAs(
                    'materials/' . $userSubjek->id,
                    $fileName,
                    'public'
                );
            }

            // Save to database
            $materialId = DB::table('lecturer_materials')->insertGetId([
                'user_subjek_id' => $userSubjek->id,
                'file_name' => $originalName,
                'file_path' => $filePath,
                'storage_disk' => $actualDisk,
                'file_type' => $extension,
                'file_size' => $fileSize,
                'category' => $request->category,
                'description' => $request->description,
                'uploaded_by' => $user->ic,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $uploadedFiles[] = [
                'id' => $materialId,
                'name' => $originalName,
                'size' => $fileSize,
                'category' => $request->category
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedFiles) . ' file(s) uploaded successfully',
            'files' => $uploadedFiles
        ]);
    }

    /**
     * Delete lecturer material
     */
    public function deleteLecturerMaterial(Request $request)
    {
        $request->validate([
            'material_id' => 'required|integer'
        ]);

        $user = Auth::user();
        $material = DB::table('lecturer_materials')
            ->where('id', $request->material_id)
            ->where('uploaded_by', $user->ic)
            ->first();

        if (!$material) {
            return response()->json(['success' => false, 'message' => 'Material not found or unauthorized']);
        }

        // Delete file from storage
        $storageDisk = $material->storage_disk ?? 'public';
        if (Storage::disk($storageDisk)->exists($material->file_path)) {
            Storage::disk($storageDisk)->delete($material->file_path);
        }

        // Delete from database
        DB::table('lecturer_materials')->where('id', $request->material_id)->delete();

        return response()->json(['success' => true, 'message' => 'Material deleted successfully']);
    }

    /**
     * Download lecturer material
     */
    public function downloadLecturerMaterial($materialId)
    {
        try {
            $user = Auth::user();
            $material = DB::table('lecturer_materials')
                ->where('id', $materialId)
                ->where('uploaded_by', $user->ic)
                ->first();

            if (!$material) {
                abort(404, 'Material not found or unauthorized');
            }

            // First try the stored disk if available
            $preferredDisk = $material->storage_disk ?? 'public';
            $filePath = null;
            $disk = null;

            // Try the preferred disk first
            if (Storage::disk($preferredDisk)->exists($material->file_path)) {
                $disk = $preferredDisk;
                
                // For linode (S3-compatible), get URL instead of path
                if ($preferredDisk === 'linode') {
                    $fileUrl = Storage::disk($preferredDisk)->url($material->file_path);
                    return redirect($fileUrl);
                } else {
                    $filePath = Storage::disk($preferredDisk)->path($material->file_path);
                }
            } else {
                // Fallback: Try multiple storage locations for compatibility
                $storageDisks = ['linode', 'public', 'local'];
                
                foreach ($storageDisks as $diskName) {
                    if ($diskName !== $preferredDisk && Storage::disk($diskName)->exists($material->file_path)) {
                        $disk = $diskName;
                        
                        // For linode (S3-compatible), get URL instead of path
                        if ($diskName === 'linode') {
                            $fileUrl = Storage::disk($diskName)->url($material->file_path);
                            return redirect($fileUrl);
                        } else {
                            $filePath = Storage::disk($diskName)->path($material->file_path);
                        }
                        break;
                    }
                }
            }

            // If not found in storage disks, try absolute path
            if (!$filePath) {
                $absolutePath = storage_path('app/public/' . $material->file_path);
                if (file_exists($absolutePath)) {
                    $filePath = $absolutePath;
                }
            }

            // Last resort: try public path
            if (!$filePath) {
                $publicPath = public_path('storage/' . $material->file_path);
                if (file_exists($publicPath)) {
                    $filePath = $publicPath;
                }
            }

            if (!$filePath || !file_exists($filePath)) {
                \Log::error("File not found for material ID: {$materialId}, Path: {$material->file_path}");
                abort(404, 'File not found on server');
            }

            // Set appropriate headers
            $headers = [
                'Content-Type' => mime_content_type($filePath),
                'Content-Disposition' => 'attachment; filename="' . $material->file_name . '"',
                'Content-Length' => filesize($filePath),
            ];

            return response()->download($filePath, $material->file_name, $headers);

        } catch (\Exception $e) {
            \Log::error("Download error for material ID {$materialId}: " . $e->getMessage());
            abort(500, 'Error downloading file: ' . $e->getMessage());
        }
    }

    /**
     * Debug storage paths for materials (remove this method after fixing the issue)
     */
    public function debugMaterialStorage($materialId)
    {
        if (config('app.env') !== 'production') {
            $material = DB::table('lecturer_materials')->where('id', $materialId)->first();
            
            if (!$material) {
                return response()->json(['error' => 'Material not found']);
            }

            $debug = [
                'material_id' => $materialId,
                'file_path' => $material->file_path,
                'storage_disk' => $material->storage_disk ?? 'unknown',
                'storage_paths' => [
                    'public_disk_exists' => Storage::disk('public')->exists($material->file_path),
                    'local_disk_exists' => Storage::disk('local')->exists($material->file_path),
                    'linode_disk_exists' => Storage::disk('linode')->exists($material->file_path),
                    'absolute_path' => storage_path('app/public/' . $material->file_path),
                    'absolute_exists' => file_exists(storage_path('app/public/' . $material->file_path)),
                    'public_path' => public_path('storage/' . $material->file_path),
                    'public_exists' => file_exists(public_path('storage/' . $material->file_path)),
                ],
                'environment' => config('app.env'),
                'app_url' => config('app.url'),
            ];

            if (Storage::disk('public')->exists($material->file_path)) {
                $debug['storage_paths']['public_disk_path'] = Storage::disk('public')->path($material->file_path);
            }

            if (Storage::disk('linode')->exists($material->file_path)) {
                $debug['storage_paths']['linode_url'] = Storage::disk('linode')->url($material->file_path);
            }

            return response()->json($debug);
        }
        
        abort(404);
    }

    /**
     * Fix existing private files by making them public (one-time fix)
     */
    public function fixPrivateFiles()
    {
        if (config('app.env') !== 'production') {
            return response()->json(['error' => 'Only available in production']);
        }

        $materials = DB::table('lecturer_materials')
            ->where('storage_disk', 'linode')
            ->get();

        $fixed = 0;
        $errors = [];

        foreach ($materials as $material) {
            try {
                if (Storage::disk('linode')->exists($material->file_path)) {
                    // Set file visibility to public
                    Storage::disk('linode')->setVisibility($material->file_path, 'public');
                    $fixed++;
                }
            } catch (\Exception $e) {
                $errors[] = "Material ID {$material->id}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'fixed_count' => $fixed,
            'total_materials' => count($materials),
            'errors' => $errors
        ]);
    }

  
}


