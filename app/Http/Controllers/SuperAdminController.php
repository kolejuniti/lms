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

            return DB::table('students')->upsert([
                //'id'  => $line['id'],
                'name'  => $line['nama'],
                'ic' => $line['no_kp'],
                'no_matric' => $line['no_matriks'],
                'email'  => $line['email'],
                'program'  => $line['id_program'],
                //'usrtype'  => 'PL',
                'status' => 'ACTIVE',
                //'usrtype' => $line['id_semester'],
                //'faculty'  => $line['faculty'],
                'password' => Hash::make('12345678'),
                //'status' => 'ACTIVE',
            ], 'ic');
            
        //}
    });



     return back()->with('success', 'Excel Data Imported successfully.');
    }
    
}

