<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;

class FacultyPrograms extends Component
{
    public Faculty $faculty;

    public function mount()
    {
        $this->faculty = Faculty::where('user_id', auth()->id())
            ->with('user', 'extensionPrograms', 'activities')
            ->firstOrFail();
    }

    public function render()
    {
        $programsLed = $this->faculty->extensionPrograms()
            ->where('program_lead_id', $this->faculty->id)
            ->get();

        $programsInvolved = $this->faculty->activities()
            ->with('extensionProgram')
            ->distinct()
            ->get()
            ->pluck('extensionProgram')
            ->unique('id');

        $activities = $this->faculty->activities()
            ->with('extensionProgram')
            ->orderBy('actual_start_date', 'desc')
            ->get();

        return view('livewire.faculty-programs', [
            'faculty' => $this->faculty,
            'programsLed' => $programsLed,
            'programsInvolved' => $programsInvolved,
            'activities' => $activities,
        ])->layout('components.faculty-layout', [
            'header' => 'Programs & Activities'
        ]);
    }
}
