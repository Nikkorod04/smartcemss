<?php

namespace App\Http\Controllers;

use App\Models\FacultyAvailability;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyAvailabilityController extends Controller
{
    /**
     * Show faculty availability management page
     */
    public function index()
    {
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();

        if (!$faculty) {
            abort(403, 'Unauthorized');
        }

        $availabilities = FacultyAvailability::where('faculty_id', $faculty->id)
            ->orderBy('date', 'desc')
            ->paginate(15);

        $timeSlots = [
            'Morning (8:00 AM - 12:00 PM)',
            'Afternoon (1:00 PM - 5:00 PM)',
            'Evening (5:00 PM - 9:00 PM)',
        ];

        return view('faculty.availability.index', compact('availabilities', 'timeSlots'));
    }

    /**
     * Store new availability entry
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();

        if (!$faculty) {
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', 'string', 'max:255'],
        ]);

        // Check if already exists
        $existing = FacultyAvailability::where('faculty_id', $faculty->id)
            ->where('date', $validated['date'])
            ->where('time_slot', $validated['time_slot'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have an availability entry for this date and time slot');
        }

        FacultyAvailability::create([
            'faculty_id' => $faculty->id,
            'date' => $validated['date'],
            'time_slot' => $validated['time_slot'],
            'status' => 'pending', // Requires director approval
            'remarks' => $request->input('remarks'),
        ]);

        return back()->with('success', 'Availability submitted for approval');
    }

    /**
     * Delete an availability entry
     */
    public function destroy(FacultyAvailability $availability)
    {
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();

        if (!$faculty || $availability->faculty_id !== $faculty->id) {
            abort(403, 'Unauthorized');
        }

        // Only allow deleting pending entries
        if ($availability->status !== 'pending') {
            return back()->with('error', 'You can only delete pending availability entries');
        }

        $availability->delete();

        return back()->with('success', 'Availability entry deleted');
    }

    /**
     * Director approval for availability
     */
    public function approve(FacultyAvailability $availability)
    {
        $user = Auth::user();

        if ($user->role !== 'director') {
            abort(403, 'Unauthorized');
        }

        $availability->update([
            'status' => 'approved',
            'approved_by' => $user->id,
        ]);

        return back()->with('success', 'Availability approved');
    }

    /**
     * Director rejection of availability
     */
    public function reject(Request $request, FacultyAvailability $availability)
    {
        $user = Auth::user();

        if ($user->role !== 'director') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'remarks' => ['required', 'string', 'max:500'],
        ]);

        $availability->update([
            'status' => 'rejected',
            'remarks' => $validated['remarks'],
            'approved_by' => $user->id,
        ]);

        return back()->with('success', 'Availability rejected');
    }
}
