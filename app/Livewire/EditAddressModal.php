<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;

class EditAddressModal extends Component
{
    public Faculty $faculty;
    public $showModal = false;
    public $address = '';

    protected $rules = [
        'address' => 'required|string|max:500',
    ];

    public function mount(Faculty $faculty)
    {
        $this->faculty = $faculty;
        $this->loadData();
    }

    public function loadData()
    {
        $this->address = $this->faculty->address;
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
            'address' => $this->address,
        ]);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->faculty)
            ->event('updated')
            ->log('Faculty profile address updated');

        $this->showModal = false;
        $this->dispatch('alert', type: 'success', message: 'Address updated successfully!');
    }

    public function render()
    {
        return view('livewire.edit-address-modal');
    }
}
