<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ExtensionProgram;
use App\Models\Faculty;
use App\Models\Beneficiary;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Display a listing of all activities.
     */
    public function index()
    {
        $activities = Activity::with('extensionProgram', 'faculties')
            ->orderBy('actual_start_date', 'desc')
            ->paginate(10);

        return view('activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create()
    {
        $programs = ExtensionProgram::where('status', '!=', 'cancelled')->get();
        $faculties = Faculty::with('user')->get();
        
        return view('activities.create', compact('programs', 'faculties'));
    }

    /**
     * Store a newly created activity in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'extension_program_id' => 'required|exists:extension_programs,id',
            'title' => 'required|string|min:5|max:150',
            'description' => 'required|string|min:10|max:2000',
            'actual_start_date' => 'required|date',
            'actual_end_date' => 'required|date|after_or_equal:actual_start_date',
            'venue' => 'required|string|max:255',
            'status' => 'required|in:pending,ongoing,completed',
            'faculties' => 'nullable|array',
            'faculties.*' => 'exists:faculties,id',
            'notes' => 'nullable|string|max:1000',
        ], [
            'extension_program_id.required' => 'Program is required.',
            'title.required' => 'Activity title is required.',
            'title.min' => 'Activity title must be at least 5 characters.',
            'description.required' => 'Description is required.',
            'actual_start_date.required' => 'Start date is required.',
            'actual_end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'venue.required' => 'Venue is required.',
            'status.required' => 'Status is required.',
        ]);

        $activity = Activity::create($validated);

        // Attach faculties if provided
        if ($request->has('faculties') && is_array($request->faculties)) {
            $activity->faculties()->attach($request->faculties);
        }

        return redirect()->route('activities.show', $activity)->with('success', 'Activity created successfully!');
    }

    /**
     * Display the specified activity.
     */
    public function show(Activity $activity)
    {
        $activity->load('extensionProgram', 'faculties', 'attendances.beneficiary');
        
        // Calculate attendance statistics
        $attendanceStats = $this->calculateAttendanceStats($activity);
        
        // Get all beneficiaries that can be marked for attendance
        $beneficiaries = $activity->extensionProgram->beneficiaries;
        
        return view('activities.show', compact('activity', 'attendanceStats', 'beneficiaries'));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit(Activity $activity)
    {
        $activity->load('faculties');
        $programs = ExtensionProgram::where('status', '!=', 'cancelled')->get();
        $faculties = Faculty::with('user')->get();
        $selectedFaculties = $activity->faculties->pluck('id')->toArray();
        
        return view('activities.edit', compact('activity', 'programs', 'faculties', 'selectedFaculties'));
    }

    /**
     * Update the specified activity in database.
     */
    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'extension_program_id' => 'required|exists:extension_programs,id',
            'title' => 'required|string|min:5|max:150',
            'description' => 'required|string|min:10|max:2000',
            'actual_start_date' => 'required|date',
            'actual_end_date' => 'required|date|after_or_equal:actual_start_date',
            'venue' => 'required|string|max:255',
            'status' => 'required|in:pending,ongoing,completed',
            'faculties' => 'nullable|array',
            'faculties.*' => 'exists:faculties,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $activity->update($validated);

        // Sync faculties
        if ($request->has('faculties') && is_array($request->faculties)) {
            $activity->faculties()->sync($request->faculties);
        } else {
            $activity->faculties()->detach();
        }

        return redirect()->route('activities.show', $activity)->with('success', 'Activity updated successfully!');
    }

    /**
     * Remove the specified activity from database.
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('activities.index')->with('success', 'Activity deleted successfully!');
    }

    /**
     * Search activities by title or program name.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $activities = Activity::with('extensionProgram', 'faculties')
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhereHas('extensionProgram', function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%");
            })
            ->orderBy('actual_start_date', 'desc')
            ->paginate(10);

        return view('activities.index', compact('activities'));
    }

    /**
     * Filter activities by status.
     */
    public function filterByStatus($status)
    {
        $valid_statuses = ['pending', 'ongoing', 'completed'];
        
        if (!in_array($status, $valid_statuses)) {
            return redirect()->route('activities.index');
        }

        $activities = Activity::with('extensionProgram', 'faculties')
            ->where('status', $status)
            ->orderBy('actual_start_date', 'desc')
            ->paginate(10);

        return view('activities.index', compact('activities'));
    }

    /**
     * Mark attendance for beneficiaries in an activity.
     */
    public function recordAttendance(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,excused',
            'remarks' => 'nullable|string|max:255',
        ]);

        // Check if attendance record already exists
        $attendance = Attendance::where('activity_id', $activity->id)
            ->where('beneficiary_id', $validated['beneficiary_id'])
            ->where('attendance_date', $validated['attendance_date'])
            ->first();

        if ($attendance) {
            $attendance->update([
                'status' => $validated['status'],
                'remarks' => $validated['remarks'],
            ]);
        } else {
            Attendance::create([
                'activity_id' => $activity->id,
                'beneficiary_id' => $validated['beneficiary_id'],
                'attendance_date' => $validated['attendance_date'],
                'status' => $validated['status'],
                'remarks' => $validated['remarks'],
            ]);
        }

        return back()->with('success', 'Attendance recorded successfully!');
    }

    /**
     * Get attendance statistics for an activity.
     */
    private function calculateAttendanceStats($activity)
    {
        $attendances = $activity->attendances;
        $beneficiaries = $activity->extensionProgram->beneficiaries;
        
        return [
            'total_beneficiaries' => $beneficiaries->count(),
            'total_present' => $attendances->where('status', 'present')->count(),
            'total_absent' => $attendances->where('status', 'absent')->count(),
            'total_excused' => $attendances->where('status', 'excused')->count(),
            'total_marked' => $attendances->count(),
            'attendance_percentage' => $attendances->count() > 0 
                ? round(($attendances->where('status', 'present')->count() / $attendances->count()) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get activity progress statistics.
     */
    public function getProgress(Activity $activity)
    {
        $activity->load('attendances', 'faculties');
        
        return response()->json([
            'total_attendance_records' => $activity->attendances->count(),
            'status' => $activity->status,
            'faculties_involved' => $activity->faculties->count(),
            'start_date' => $activity->actual_start_date?->format('Y-m-d'),
            'end_date' => $activity->actual_end_date?->format('Y-m-d'),
            'days_duration' => $activity->actual_start_date && $activity->actual_end_date 
                ? $activity->actual_end_date->diffInDays($activity->actual_start_date) + 1
                : 0,
        ]);
    }
}
