<?php

namespace App\Livewire;

use Livewire\Component;

class FacultyCalendar extends Component
{
    public function render()
    {
        return view('livewire.faculty-calendar')->layout('components.faculty-layout', [
            'header' => 'Calendar'
        ]);
    }
}
