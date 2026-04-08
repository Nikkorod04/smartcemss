<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExtensionToken;
use App\Models\Faculty;

class RevokeTokenModal extends Component
{
    public ExtensionToken $token;
    public Faculty $faculty;
    public $showConfirm = false;
    public $showSuccess = false;
    public $tokenPreview = '';

    public function mount(ExtensionToken $token, Faculty $faculty)
    {
        $this->token = $token;
        $this->faculty = $faculty;
        // Show first 16 and last 8 chars of token
        $this->tokenPreview = substr($token->token, 0, 16) . '...' . substr($token->token, -8);
    }

    public function openConfirmation()
    {
        $this->showConfirm = true;
    }

    public function closeConfirmation()
    {
        $this->showConfirm = false;
    }

    public function revokeToken()
    {
        // Authorization check
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        $tokenData = $this->token;
        $faculty = $this->faculty;
        $this->token->delete();

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($tokenData)
            ->event('deleted')
            ->log('Access token revoked for faculty: ' . $faculty->user->name);

        // Close confirmation modal and show success modal
        $this->showConfirm = false;
        $this->showSuccess = true;
    }

    public function closeSuccess()
    {
        $this->showSuccess = false;
    }

    public function render()
    {
        return view('livewire.revoke-token-modal');
    }
}
