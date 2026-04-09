<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;

class EditProfessionalInfoModal extends Component
{
    public Faculty $faculty;
    public $showModal = false;
    public $department = '';
    public $position = '';
    public $specialization = '';

    protected $rules = [
        'department' => 'required|string|max:100',
        'position' => 'required|string|max:100',
        'specialization' => 'nullable|string|max:255',
    ];

    public function mount(Faculty $faculty)
    {
        $this->faculty = $faculty;
        $this->loadData();
    }

    public function loadData()
    {
        $this->department = $this->faculty->department;
        $this->position = $this->faculty->position;
        $this->specialization = $this->faculty->specialization;
    }

    public function openModal()
    {
        $this->loadData();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveChanges()
    {
        $this->validate();

        $this->faculty->update([
            'department' => $this->department,
            'position' => $this->position,
            'specialization' => $this->specialization,
        ]);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->faculty)
            ->event('updated')
            ->log('Faculty profile professional information updated');

        $this->showModal = false;
        $this->dispatch('show-success-modal', message: 'Professional information has been updated successfully!', title: 'Success');
    }

    public function render()
    {
        return view('livewire.edit-professional-info-modal');
    }
}
