<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExtensionProgram;

class FacultyProgramDetail extends Component
{
    public ExtensionProgram $program;

    public function mount(ExtensionProgram $program)
    {
        $this->program = $program->load('programLead', 'communities', 'activities');
    }

    public function render()
    {
        $faculty = auth()->user()->faculty;
        
        $isFacultyLead = $faculty && $this->program->program_lead_id == $faculty->id;
        $isParticipant = $faculty && $faculty->activities()->whereHas('extensionProgram', function ($query) {
            $query->where('id', $this->program->id);
        })->exists();

        return view('livewire.faculty-program-detail', [
            'program' => $this->program,
            'isFacultyLead' => $isFacultyLead,
            'isParticipant' => $isParticipant,
        ])->layout('components.faculty-layout', [
            'header' => $this->program->program_name
        ]);
    }
}
