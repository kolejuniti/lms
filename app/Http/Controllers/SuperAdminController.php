<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\User;
use App\Models\subject;
use App\Models\student;

class SuperAdminController extends Controller
{
    public function index()
    {
        return view('super_admin.importDB')->with('success', null);
    }


    function import(Request $request)
    {
     $this->validate($request, [
      'file'  => 'required|mimes:xls,xlsx'
     ]);

     $path = $request->file('file')->getRealPath();

     //$data = Excel::import($path)->get();

     //dd($path);

     $users = (new FastExcel)->import($path, function ($line) {
        //if(DB::table('students')->where('ic', $line['no_kp'])->doesntExist())
        //{

            //this is to preserve number '0' infront of excel data column !
            //dd(str_pad($line['no_tel1'],11,"0", STR_PAD_LEFT));

            return DB::table('user_subjek')->upsert([
                //'id'  => $line['id'],
                //table_student
                //'name'  => $line['name'],
                //'ic' => str_pad($line['ic'],12,"0", STR_PAD_LEFT),
                //'no_matric' => $line['no_matric'],
                //'email' => $line['email'],
                //'intake' => $line['intake'],
                //'batch' => $line['batch'],
                //'session' => $line['session'],
                //'semester' => $line['semester'],
                //'program' => $line['program'],
                //'password' => Hash::make('12345678'),
                //'status' => 'ACTIVE',
                //student_detail
                //'address1' => $line['alamat_1'],
                //'address2' => $line['alamat_2'],
                //'address3' => $line['alamat_3'],
                //'city'  => $line['bandar'],
                //'postcode'  => $line['poskod'],
                //'state_id'  => $line['id_negeri'],
                //'statelevel_id'  => $line['id_tarafNegara'],
                //'citizenship_id' => $line['id_wargaNegara'],
                //'no_tel' => str_pad($line['no_tel1'],11,"0", STR_PAD_LEFT),
                //'no_tel2'  => str_pad($line['no_tel2'],11,"0", STR_PAD_LEFT),
                //'password' => Hash::make('12345678'),
                //'no_telhome' => str_pad($line['no_tel_rumah'],11,"0", STR_PAD_LEFT),
                //table student_subjek
                //'student_ic' => str_pad($line['student_ic'],12,"0", STR_PAD_LEFT),
                //'courseid' => $line['courseid'],
                //'sessionid' => $line['sessionid'],
                //'semesterid' => $line['semesterid'],
                //'group_id' => $line['group_id'],
                //'group_name' => $line['group_name'],
                //'status' => 'ACTIVE',
                //table user_subjek
                'id' => $line['id'],
                'user_ic' => $line['user_ic'],
                'course_id' => $line['course_id'],
                'session_id' => $line['session_id'],
                'addby' => $line['addby'],
            ],['id']);
            
        //}
    });

     return back()->with('success', 'Excel Data Imported successfully.');
    }
    
}

