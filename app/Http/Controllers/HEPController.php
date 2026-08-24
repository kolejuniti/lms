<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class HEPController extends Controller
{
    public function dashboard()
    {
        Session::put('User', Auth::user());

        return view('hep.dashboard');
    }
    public function vehicleStickerIndex()
    {
        return view('hep.vehicle_sticker.index');
    }

    public function vehicleStickerSearch(Request $request)
    {
        $search = $request->input('search');

        if (empty($search)) {
            return redirect()->back()->with('error', 'Sila masukkan nama, IC atau no. stiker pencarian.');
        }

        // Get ICs from tblvehicle_sticker matching sticker_number
        $stickerIcs = DB::table('tblvehicle_sticker')
            ->where('sticker_number', 'like', "%{$search}%")
            ->pluck('ic')
            ->toArray();

        // Query for staff
        $staffQuery = DB::table('users')
            ->select(
                'ic',
                'name',
                'no_staf as id_number',
                'no_tel',
                'email',
                DB::raw('NULL as program_name'),
                DB::raw('NULL as session'),
                DB::raw('NULL as semester'),
                'status',
                DB::raw("'staff' as user_type")
            )
            ->where(function($q) use ($search, $stickerIcs) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ic', 'like', "%{$search}%");
                if (!empty($stickerIcs)) {
                    $q->orWhereIn('ic', $stickerIcs);
                }
            });

        // Query for students
        $studentQuery = DB::table('students')
            ->leftJoin('tblprogramme', 'students.program', '=', 'tblprogramme.id')
            ->leftJoin('sessions', 'students.session', '=', 'sessions.SessionID')
            ->leftJoin('tblstudent_status', 'students.status', '=', 'tblstudent_status.id')
            ->select(
                'students.ic',
                'students.name',
                'students.no_matric as id_number',
                DB::raw('NULL as no_tel'),
                'students.email',
                'tblprogramme.progname as program_name',
                'sessions.SessionName as session',
                'students.semester',
                'tblstudent_status.name as status',
                DB::raw("'student' as user_type")
            )
            ->where(function($q) use ($search, $stickerIcs) {
                $q->where('students.name', 'like', "%{$search}%")
                  ->orWhere('students.ic', 'like', "%{$search}%");
                if (!empty($stickerIcs)) {
                    $q->orWhereIn('students.ic', $stickerIcs);
                }
            });

        // Combine queries
        $results = $staffQuery->union($studentQuery)->get();

        if ($results->isEmpty()) {
            return redirect()->back()->with('error', 'Tiada rekod dijumpai.');
        }

        // Get sticker applications for the results
        $ics = $results->pluck('ic')->toArray();
        $applications = DB::table('tblvehicle_sticker')
            ->whereIn('ic', $ics)
            ->get();

        // Group applications by IC for easy access in the view
        $applicationsByIc = $applications->groupBy('ic');

        return view('hep.vehicle_sticker.index', compact('results', 'applicationsByIc', 'search'));
    }

    public function vehicleStickerUpdate(Request $request)
    {
        $request->validate([
            'sticker_id' => 'required|integer'
        ]);

        DB::table('tblvehicle_sticker')
            ->where('id', $request->sticker_id)
            ->update([
                'status' => 'SAH',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Status permohonan telah dikemaskini kepada SAH.');
    }
}
