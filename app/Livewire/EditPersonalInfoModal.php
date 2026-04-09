<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;

class EditPersonalInfoModal extends Component
{
    public Faculty $faculty;
    public $showModal = false;
    public $name = '';
    public $email = '';
    public $phone = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
    ];

    public function mount(Faculty $faculty)
    {
        $this->faculty = $faculty;
        $this->loadData();
    }

    public function loadData()
    {
        $this->name = $this->faculty->user->name;
        $this->email = $this->faculty->user->email;
        $this->phone = $this->faculty->phone;
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

        // Update user info
        $this->faculty->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // Update faculty phone
        $this->faculty->update([
            'phone' => $this->phone,
        ]);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->faculty)
            ->event('updated')
            ->log('Faculty profile personal information updated');

        $this->showModal = false;
        $this->dispatch('show-success-modal', message: 'Personal information has been updated successfully!', title: 'Success');
    }

    public function render()
    {
        return view('livewire.edit-personal-info-modal');
    }
}
