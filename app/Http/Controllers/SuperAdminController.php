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

            return DB::table('tblpaymentmethod')->insert([
                'id'  => $line['id'],  
                'payment_id'  => $line['bayaran_id'],  
                'claim_method_id'  => $line['kaedah_bayaran_id'],
                'bank_id' => $line['bank_id'],
                'no_document' => $line['no_dokumen'],
                'amount' => $line['amaun'],
                //'ref_no'  => $line['no_rujukan'],
                //'session_id'  => $line['sesi_id'],
                //'semester_id'  => $line['semester_id'],
                //'amount'  => $line['jumlah'],
                //'process_status_id'  => $line['status_proses_id'],
                //'process_type_id'  => $line['jenis_proses_id'],
                'add_staffID'  => $line['stafID_add'],
                'add_date' => $line['tarikh_add'],
                'mod_staffID' => $line['stafID_mod'],
                'mod_staffID' => $line['tarikh_mod'],
                //'no_tel' => str_pad($line['no_tel1'],11,"0", STR_PAD_LEFT),
                //'no_tel2'  => str_pad($line['no_tel2'],11,"0", STR_PAD_LEFT),
                //'password' => Hash::make('12345678'),
                //'no_telhome' => str_pad($line['no_tel_rumah'],11,"0", STR_PAD_LEFT),
            ]);
            
        //}
    });



     return back()->with('success', 'Excel Data Imported successfully.');
    }
    
}

