<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Tblevent;
use App\Models\Tblevent2;

class OptimizedController extends Controller
{
    /**
     * Optimized version of fetchEvents to reduce server load
     */
    public function fetchEvents()
    {
        // Use caching to prevent database hits on every request
        $cacheKey = 'events_' . request()->type . '_' . (request()->id ?? Auth::guard('student')->user()->ic ?? 'guest');
        $cacheDuration = 30; // Cache for 30 minutes (in minutes)
        
        // Return cached result if available
        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }
        
        $events = [];
        
        if(request()->type == 'std')
        {
            if(isset(Auth::guard('student')->user()->ic))
            {
                // Retrieve limited data with better indexing
                $events = DB::table('tblevents_second')
                    ->join('student_subjek', function($join){
                        $join->on('tblevents_second.group_id', 'student_subjek.group_id');
                        $join->on('tblevents_second.group_name', 'student_subjek.group_name');
                    })
                    ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                    ->join('tbllecture_room', 'tblevents_second.lecture_id', 'tbllecture_room.id')
                    ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                    ->join('users', 'tblevents_second.user_ic', 'users.ic')
                    ->where('sessions.Status', 'ACTIVE')
                    ->where('student_subjek.student_ic', request()->id)
                    ->select(
                        'tblevents_second.id',
                        'tblevents_second.group_id',
                        'tblevents_second.group_name',
                        'tblevents_second.start',
                        'tblevents_second.end',
                        'users.name AS lecturer',
                        'subjek.course_code AS code',
                        'subjek.course_name AS subject',
                        'tbllecture_room.name AS room'
                    )
                    ->distinct()
                    ->limit(30) // Limit results to reduce load
                    ->get();
            } else {
                // Retrieve limited data with better indexing
                $events = DB::table('tblevents')
                    ->join('student_subjek', function($join){
                        $join->on('tblevents.group_id', 'student_subjek.group_id');
                        $join->on('tblevents.group_name', 'student_subjek.group_name');
                    })
                    ->join('sessions', 'student_subjek.sessionid', 'sessions.SessionID')
                    ->join('tbllecture_room', 'tblevents.lecture_id', 'tbllecture_room.id')
                    ->join('subjek', 'student_subjek.courseid', 'subjek.sub_id')
                    ->join('users', 'tblevents.user_ic', 'users.ic')
                    ->where('sessions.Status', 'ACTIVE')
                    ->where('student_subjek.student_ic', request()->id)
                    ->select(
                        'tblevents.id',
                        'tblevents.group_id', 
                        'tblevents.group_name',
                        'tblevents.start',
                        'tblevents.end',
                        'users.name AS lecturer',
                        'subjek.course_code AS code',
                        'subjek.course_name AS subject',
                        'tbllecture_room.name AS room'
                    )
                    ->distinct()
                    ->limit(30) // Limit results to reduce load
                    ->get();
            }

            // Only proceed if events were found
            if ($events->isEmpty()) {
                return response()->json([]);
            }
            
            // Extract group IDs and names for efficient counting
            $groupIds = $events->pluck('group_id')->unique()->toArray();
            $groupNames = $events->pluck('group_name')->unique()->toArray();
            
            // Get student counts in a single query instead of per-event
            $studentCounts = DB::table('student_subjek')
                ->whereIn('group_id', $groupIds)
                ->whereIn('group_name', $groupNames)
                ->select(
                    'group_id',
                    'group_name',
                    DB::raw('COUNT(student_ic) AS total_student')
                )
                ->groupBy('group_id', 'group_name')
                ->get()
                ->keyBy(function($item) {
                    return $item->group_id . '|' . $item->group_name;
                });

            // Map day of week once (reused for all events)
            $dayOfWeekMap = [
                1 => 1, // Monday
                2 => 2, // Tuesday
                3 => 3, // Wednesday
                4 => 4, // Thursday
                5 => 5, // Friday
                6 => 6, // Saturday
                7 => 0  // Sunday
            ];

            // Format events more efficiently
            $formattedEvents = $events->map(function ($event) use ($studentCounts, $dayOfWeekMap) {
                $key = $event->group_id . '|' . $event->group_name;
                $studentCount = isset($studentCounts[$key]) ? $studentCounts[$key]->total_student : 0;
                
                $dayOfWeek = date('N', strtotime($event->start));
                $fullCalendarDayOfWeek = $dayOfWeekMap[$dayOfWeek];

                return [
                    'id' => $event->id,
                    'title' => $event->lecturer,
                    'description' => $event->code . ' - ' . $event->subject . ' (' . $event->group_name .') ' . '|' . ' Total Student: ' . $studentCount,
                    'startTime' => date('H:i', strtotime($event->start)),
                    'endTime' => date('H:i', strtotime($event->end)),
                    'duration' => gmdate('H:i', strtotime($event->end) - strtotime($event->start)),
                    'daysOfWeek' => [$fullCalendarDayOfWeek]
                ];
            });
            
            // Store in cache for future requests
            Cache::put($cacheKey, $formattedEvents, $cacheDuration);
            
            return response()->json($formattedEvents);
        }
        
        // Return empty for other cases
        return response()->json([]);
    }
} 