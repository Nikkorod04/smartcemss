<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtensionToken extends Model
{
    protected $fillable = [
        'faculty_id',
        'token',
        'expires_at',
        'generated_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Check if token is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false; // No expiration set
        }
        return now()->isAfter($this->expires_at);
    }

    /**
     * Check if token is valid (not expired)
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Get human-readable expiration status
     */
    public function getExpirationStatus(): string
    {
        if (!$this->expires_at) {
            return 'Never expires';
        }
        
        if ($this->isExpired()) {
            return 'Expired (' . $this->expires_at->format('M d, Y') . ')';
        }
        
        // Check if less than 24 hours remaining
        $hours = (float) now()->diffInRealHours($this->expires_at);
        if ($hours < 24) {
            return 'Expires in ' . (int) ceil($hours) . ' hours';
        }
        
        $days = (int) now()->diffInDays($this->expires_at);
        if ($days === 0) {
            return 'Expires today';
        }
        if ($days === 1) {
            return 'Expires tomorrow';
        }
        if ($days <= 7) {
            return 'Expires in ' . $days . ' days';
        }
        
        return 'Expires ' . $this->expires_at->format('M d, Y');
    }
}
