<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckResultAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply this middleware to student routes
        if (!Auth::guard('student')->check()) {
            return $next($request);
        }

        $student = Auth::guard('student')->user();

        // Check all the same conditions as in the view
        $range = DB::table('tblresult_period')->first();
        $now = now();
        $block_status = $student->block_status;
        $program_list = DB::table('tblresult_program')->pluck('program_id')->toArray();
        $session_list = DB::table('tblresult_session')->pluck('session_id')->toArray();
        $semester_list = DB::table('tblresult_semester')->pluck('semester_id')->toArray();

        // Check if student meets all conditions to access results
        $canAccessResults = $now >= $range->Start && 
                           $now <= $range->End && 
                           in_array($student->program, $program_list) && 
                           in_array($student->session, $session_list) && 
                           in_array($student->semester, $semester_list) && 
                           $block_status == 0;

        if (!$canAccessResults) {
            // Redirect to dashboard with error message
            return redirect()->route('studentDashboard')->with('error', 'Results are not available at this time or you do not have permission to view them.');
        }

        return $next($request);
    }
} 