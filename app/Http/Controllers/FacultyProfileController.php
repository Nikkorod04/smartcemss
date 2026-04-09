<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyProfileController extends Controller
{
    /**
     * Display the faculty profile
     */
    public function show()
    {
        // Get the currently authenticated user's faculty record
        $faculty = Faculty::where('user_id', auth()->id())
            ->with('user', 'extensionPrograms', 'activities')
            ->firstOrFail();

        // Calculate total rendered hours (future - when hours model exists)
        $totalHours = 0; // Placeholder

        // Get programs where faculty is lead
        $programsLed = $faculty->extensionPrograms()->where('program_lead_id', $faculty->id)->get();

        // Get programs where faculty is involved
        $programsInvolved = $faculty->activities()
            ->with('extensionProgram')
            ->distinct()
            ->get()
            ->pluck('extensionProgram')
            ->unique('id');

        // Get recent activities
        $recentActivities = $faculty->activities()
            ->with('extensionProgram')
            ->orderBy('actual_start_date', 'desc')
            ->take(5)
            ->get();

        return view('faculty.profile', compact(
            'faculty',
            'totalHours',
            'programsLed',
            'programsInvolved',
            'recentActivities'
        ));
    }
}
