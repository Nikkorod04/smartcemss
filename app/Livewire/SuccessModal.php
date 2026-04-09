<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class SuccessModal extends Component
{
    public $show = false;
    public $message = '';
    public $title = 'Success';
    public $type = 'success'; // success or error

    #[On('show-success-modal')]
    public function showSuccess($message = 'Changes saved successfully!', $title = 'Success')
    {
        $this->type = 'success';
        $this->title = $title;
        $this->message = $message;
        $this->show = true;
    }

    #[On('show-error-modal')]
    public function showError($message = 'An error occurred', $title = 'Error')
    {
        $this->type = 'error';
        $this->title = $title;
        $this->message = $message;
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
    }

    public function closeAndReload()
    {
        $this->show = false;
        $this->dispatch('reload-page');
    }

    public function render()
    {
        return view('livewire.success-modal');
    }
}
