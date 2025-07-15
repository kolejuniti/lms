<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\student;
use App\Models\User;
use App\Models\UserStudent;
use App\Models\subject;
use Input;

class URController extends Controller
{
    public function dashboard()
    {

        return view('dashboard');

    }

    public function educationAdvisor()
    {

        $data['ea'] = DB::table('tbledu_advisor')->get();

        return view('uniti_resources.staff.education_advisor.educationAdvisor', compact('data'));

    }

    public function postEducationAdvisor(Request $request)
    {

        DB::table('tbledu_advisor')->insert([
            'name' => $request->name,
            'ic' => $request->ic
        ]);

        return;

    }

    public function updateEducationAdvisor(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|integer|exists:tbledu_advisor,id',
            'name' => 'required|string|max:255',
            'ic' => 'required|string|max:20'
        ]);

        // Update the education advisor record
        DB::table('tbledu_advisor')
            ->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'ic' => $request->ic
            ]);

        return response()->json(['success' => true, 'message' => 'Education advisor updated successfully']);
    }

    public function deleteEducationAdvisor()
    {

        DB::table('tbledu_advisor')->where('id', request()->id)->delete();


        return true;

    }
}
