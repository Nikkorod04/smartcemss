<?php

namespace App\Http\Controllers;

use App\Models\ExtensionProgram;
use App\Models\Activity;
use App\Models\FacultyAvailability;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Get all events for calendar (programs, activities, personal schedule)
     */
    public function getEvents(Request $request)
    {
        $user = Auth::user();
        $events = [];

        // Determine the user type and get appropriate data
        if ($user->role === 'faculty') {
            $faculty = Faculty::where('user_id', $user->id)->first();
            if (!$faculty) {
                return response()->json([]);
            }

            // Get faculty's programs (as lead)
            $programs = ExtensionProgram::where('program_lead_id', $faculty->id)
                ->where(function ($query) {
                    $query->whereNotNull('planned_start_date')
                          ->whereNotNull('planned_end_date');
                })
                ->get();

            foreach ($programs as $program) {
                $events[] = [
                    'id' => 'program-' . $program->id,
                    'title' => $program->title,
                    'start' => $program->planned_start_date,
                    'end' => $program->planned_end_date,
                    'type' => 'program',
                    'backgroundColor' => '#003599', // LNU Blue
                    'borderColor' => '#003599',
                    'url' => route('faculty.programs.show', $program),
                    'extendedProps' => [
                        'type' => 'program',
                        'model_id' => $program->id,
                        'model_type' => 'program',
                        'status' => $program->status,
                    ]
                ];
            }

            // Get faculty's activities
            $activities = Activity::whereHas('faculties', function ($q) use ($faculty) {
                $q->where('faculties.id', $faculty->id);
            })
            ->where(function ($query) {
                $query->whereNotNull('actual_start_date')
                      ->whereNotNull('actual_end_date');
            })
            ->get();

            foreach ($activities as $activity) {
                $events[] = [
                    'id' => 'activity-' . $activity->id,
                    'title' => $activity->title,
                    'start' => $activity->actual_start_date,
                    'end' => $activity->actual_end_date,
                    'type' => 'activity',
                    'backgroundColor' => '#28a745', // Green
                    'borderColor' => '#28a745',
                    'url' => route('activities.show', $activity),
                    'extendedProps' => [
                        'type' => 'activity',
                        'model_id' => $activity->id,
                        'model_type' => 'activity',
                        'status' => $activity->status,
                    ]
                ];
            }

            // Get faculty's personal schedule (availability blocks - all statuses)
            $availabilities = FacultyAvailability::where('faculty_id', $faculty->id)->get();

            foreach ($availabilities as $avail) {
                // Different colors based on status
                if ($avail->status === 'approved') {
                    $bgColor = '#6c757d'; // Dark gray for approved
                    $borderColor = '#495057';
                    $title = '✓ Available - ' . $avail->time_slot;
                } elseif ($avail->status === 'pending') {
                    $bgColor = '#ffc107'; // Yellow for pending
                    $borderColor = '#e0a800';
                    $title = '⏳ Pending - ' . $avail->time_slot;
                } else { // rejected
                    $bgColor = '#dc3545'; // Red for rejected
                    $borderColor = '#c82333';
                    $title = '✗ Rejected - ' . $avail->time_slot;
                }

                $events[] = [
                    'id' => 'availability-' . $avail->id,
                    'title' => $title,
                    'start' => $avail->date->format('Y-m-d'),
                    'type' => 'availability',
                    'backgroundColor' => $bgColor,
                    'borderColor' => $borderColor,
                    'textColor' => '#fff',
                    'editable' => false,
                    'extendedProps' => [
                        'type' => 'availability',
                        'model_id' => $avail->id,
                        'status' => $avail->status,
                    ]
                ];
            }
        } else {
            // Director/Secretary - Show all programs and activities
            $programs = ExtensionProgram::where(function ($query) {
                $query->whereNotNull('planned_start_date')
                      ->whereNotNull('planned_end_date');
            })->get();

            foreach ($programs as $program) {
                $events[] = [
                    'id' => 'program-' . $program->id,
                    'title' => $program->title,
                    'start' => $program->planned_start_date,
                    'end' => $program->planned_end_date,
                    'type' => 'program',
                    'backgroundColor' => '#003599',
                    'borderColor' => '#003599',
                    'url' => route('programs.show', $program),
                    'extendedProps' => [
                        'type' => 'program',
                        'model_id' => $program->id,
                        'model_type' => 'program',
                    ]
                ];
            }

            $activities = Activity::where(function ($query) {
                $query->whereNotNull('actual_start_date')
                      ->whereNotNull('actual_end_date');
            })->get();

            foreach ($activities as $activity) {
                $events[] = [
                    'id' => 'activity-' . $activity->id,
                    'title' => $activity->title,
                    'start' => $activity->actual_start_date,
                    'end' => $activity->actual_end_date,
                    'type' => 'activity',
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745',
                    'url' => route('activities.show', $activity),
                    'extendedProps' => [
                        'type' => 'activity',
                        'model_id' => $activity->id,
                        'model_type' => 'activity',
                    ]
                ];
            }
        }

        return response()->json($events);
    }

    /**
     * Update event dates (drag-drop rescheduling)
     */
    public function updateEvent(Request $request)
    {
        $user = Auth::user();
        $eventId = $request->input('eventId');
        $start = $request->input('start');
        $end = $request->input('end');

        // Parse the event ID to determine type and model
        [$type, $id] = explode('-', $eventId);

        try {
            if ($type === 'program') {
                $program = ExtensionProgram::findOrFail($id);

                // Check authorization - only program lead or director can modify
                if ($user->role !== 'director') {
                    $faculty = Faculty::where('user_id', $user->id)->first();
                    if (!$faculty || $program->program_lead_id !== $faculty->id) {
                        return response()->json(['error' => 'Unauthorized'], 403);
                    }
                }

                $program->update([
                    'planned_start_date' => $start,
                    'planned_end_date' => $end,
                ]);

                return response()->json(['success' => true, 'message' => 'Program dates updated']);
            } elseif ($type === 'activity') {
                $activity = Activity::findOrFail($id);

                // Check authorization - faculty involved or director
                if ($user->role !== 'director') {
                    $faculty = Faculty::where('user_id', $user->id)->first();
                    if (!$faculty || !$activity->faculties->contains($faculty)) {
                        return response()->json(['error' => 'Unauthorized'], 403);
                    }
                }

                $activity->update([
                    'actual_start_date' => $start,
                    'actual_end_date' => $end,
                ]);

                return response()->json(['success' => true, 'message' => 'Activity dates updated']);
            } elseif ($type === 'availability') {
                // Personal schedule cannot be dragged
                return response()->json(['error' => 'Cannot reschedule availability blocks'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create new activity from calendar
     */
    public function createActivity(Request $request)
    {
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();

        if (!$faculty && $user->role !== 'director') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'extension_program_id' => ['required', 'exists:extension_programs,id'],
            'actual_start_date' => ['required', 'date'],
            'actual_end_date' => ['required', 'date', 'after_or_equal:actual_start_date'],
            'description' => ['nullable', 'string'],
            'venue' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $activity = Activity::create([
                'extension_program_id' => $validated['extension_program_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'actual_start_date' => $validated['actual_start_date'],
                'actual_end_date' => $validated['actual_end_date'],
                'venue' => $validated['venue'] ?? null,
                'status' => 'pending',
            ]);

            // Attach current faculty if faculty is creating
            if ($faculty) {
                $activity->faculties()->attach($faculty->id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Activity created successfully',
                'activity' => $activity,
                'redirect' => route('activities.show', $activity)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
