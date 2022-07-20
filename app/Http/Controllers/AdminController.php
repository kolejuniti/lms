<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index() 
    {
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
            
            //this is to store file/image in specific folder
            $imagePath = request('image')->store('storage', 'public');

            //this is to resize image  Image need to be declared with 'use Intervention\Image\Facades\Image;'
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();

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
}
