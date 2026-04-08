<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;
use App\Models\ExtensionToken;
use Illuminate\Support\Str;

class GenerateTokenModal extends Component
{
    public Faculty $faculty;
    
    public $expirationType = 'never'; // never, days, or date
    public $expiresInDays = 365;
    public $expiresAt = null;
    public $isOpen = false;
    public $generatedToken = null;
    public $generatedExpiration = null;

    protected $rules = [
        'expirationType' => 'required|in:never,days,date',
        'expiresInDays' => 'nullable|integer|min:1|max:1095',
        'expiresAt' => 'nullable|date|after_or_equal:today',
    ];

    public function mount(Faculty $faculty)
    {
        $this->faculty = $faculty;
    }

    public function generateToken()
    {
        // Authorization check
        if (auth()->user()->role !== 'director') {
            $this->dispatch('alert', type: 'error', message: 'Unauthorized. Only Directors can manage faculty.');
            return;
        }

        // Validate based on expiration type
        if ($this->expirationType === 'days') {
            $this->validate(['expiresInDays' => 'required|integer|min:1|max:1095']);
        } elseif ($this->expirationType === 'date') {
            $this->validate(['expiresAt' => 'required|date|after_or_equal:today']);
        }

        $expiresAtTimestamp = null;

        // Calculate expiration date
        if ($this->expirationType === 'days') {
            $expiresAtTimestamp = now()->addDays((int)$this->expiresInDays)->endOfDay();
        } elseif ($this->expirationType === 'date') {
            $expiresAtTimestamp = \Carbon\Carbon::parse($this->expiresAt)->endOfDay();
        }

        // Generate unique token
        $token = Str::random(64);

        // Create token record
        $extensionToken = ExtensionToken::create([
            'faculty_id' => $this->faculty->id,
            'token' => $token,
            'expires_at' => $expiresAtTimestamp,
            'generated_by' => auth()->id(),
        ]);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($extensionToken)
            ->event('created')
            ->log('Access token generated for faculty: ' . $this->faculty->user->name . 
                  '. Expires: ' . ($expiresAtTimestamp ? $expiresAtTimestamp->format('M d, Y') : 'Never'));

        // Store for display
        $this->generatedToken = $token;
        $this->generatedExpiration = $expiresAtTimestamp?->format('M d, Y') ?? 'Never';
        
        // Reset form for next use
        $this->expirationType = 'never';
        $this->expiresInDays = 365;
        $this->expiresAt = null;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        // Reset form and generated token
        $this->expirationType = 'never';
        $this->expiresInDays = 365;
        $this->expiresAt = null;
        $this->generatedToken = null;
        $this->generatedExpiration = null;
    }

    public function render()
    {
        return view('livewire.generate-token-modal');
    }
}
