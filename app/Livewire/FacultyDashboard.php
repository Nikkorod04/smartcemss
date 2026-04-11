<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;

class FacultyDashboard extends Component
{
    public Faculty $faculty;

    public function mount()
    {
        $this->faculty = Faculty::where('user_id', auth()->id())
            ->with('user', 'extensionPrograms', 'activities')
            ->firstOrFail();
    }

    public function updateAvatar($avatar)
    {
        $this->faculty->update(['avatar' => $avatar]);
        $this->dispatch('close');
    }

    public function render()
    {
        $totalHours = 0;
        $programsLed = $this->faculty->extensionPrograms()
            ->where('program_lead_id', $this->faculty->id)
            ->get();

        $programsInvolved = $this->faculty->activities()
            ->with('extensionProgram')
            ->distinct()
            ->get()
            ->pluck('extensionProgram')
            ->unique('id');

        $recentActivities = $this->faculty->activities()
            ->with('extensionProgram')
            ->orderBy('actual_start_date', 'desc')
            ->take(5)
            ->get();

        return view('livewire.faculty-dashboard', [
            'faculty' => $this->faculty,
            'totalHours' => $totalHours,
            'programsLed' => $programsLed,
            'programsInvolved' => $programsInvolved,
            'recentActivities' => $recentActivities,
        ])->layout('components.faculty-layout', [
            'header' => 'Profile'
        ]);
    }
}
