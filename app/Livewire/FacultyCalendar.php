<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExtensionProgram;
use App\Models\Activity;
use App\Models\Faculty;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FacultyCalendar extends Component
{
    public $upcomingEvents = [];

    public function mount()
    {
        $this->loadUpcomingEvents();
    }

    public function loadUpcomingEvents()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                $this->upcomingEvents = [];
                return;
            }

            $faculty = Faculty::where('user_id', $user->id)->first();
            if (!$faculty) {
                $this->upcomingEvents = [];
                return;
            }

            $today = Carbon::today();
            $events = [];

            // Get faculty's upcoming programs (as lead)
            $programs = ExtensionProgram::where('program_lead_id', $faculty->id)
                ->where('planned_start_date', '>=', $today)
                ->with('programLead')
                ->orderBy('planned_start_date', 'asc')
                ->take(5)
                ->get();

            foreach ($programs as $program) {
                $events[] = [
                    'id' => 'program-' . $program->id,
                    'title' => $program->title,
                    'type' => 'program',
                    'start_date' => $program->planned_start_date,
                    'end_date' => $program->planned_end_date,
                    'url' => route('faculty.programs.show', $program),
                    'status' => $program->status ?? 'pending',
                    'color' => '#003599',
                    'icon' => 'briefcase',
                ];
            }

            // Get faculty's upcoming activities
            $activities = Activity::whereHas('faculties', function ($q) use ($faculty) {
                $q->where('faculties.id', $faculty->id);
            })
            ->where('actual_start_date', '>=', $today)
            ->orderBy('actual_start_date', 'asc')
            ->take(5)
            ->get();

            foreach ($activities as $activity) {
                $events[] = [
                    'id' => 'activity-' . $activity->id,
                    'title' => $activity->title,
                    'type' => 'activity',
                    'start_date' => $activity->actual_start_date,
                    'end_date' => $activity->actual_end_date,
                    'url' => route('activities.show', $activity),
                    'status' => $activity->status ?? 'pending',
                    'color' => '#28a745',
                    'icon' => 'calendar',
                ];
            }

            // Sort all events by start date and take top 5
            usort($events, function ($a, $b) {
                return $a['start_date']->compare($b['start_date']);
            });

            $this->upcomingEvents = array_slice($events, 0, 5);
        } catch (\Exception $e) {
            \Log::error('Error loading upcoming events: ' . $e->getMessage());
            $this->upcomingEvents = [];
        }
    }

    public function render()
    {
        return view('livewire.faculty-calendar', [
            'upcomingEvents' => $this->upcomingEvents,
        ])->layout('components.faculty-layout', [
            'header' => 'Calendar'
        ]);
    }
}

