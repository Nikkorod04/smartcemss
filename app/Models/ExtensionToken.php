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
        
        // Use Carbon comparison which respects the app timezone
        return $this->expires_at->isPast();
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
        
        $now = now();
        $expiresAt = $this->expires_at;
        
        // If expired, show that clearly
        if ($expiresAt->isPast()) {
            return 'Expired (' . $expiresAt->format('M d, Y') . ')';
        }
        
        // Calculate remaining time using Carbon's reliable methods
        $hoursRemaining = ($expiresAt->timestamp - $now->timestamp) / 3600;
        $daysRemaining = (int) floor(($expiresAt->timestamp - $now->timestamp) / 86400);
        
        if ($hoursRemaining < 1) {
            return 'Expires in less than 1 hour';
        }
        
        if ($hoursRemaining < 24) {
            return 'Expires in ' . (int) ceil($hoursRemaining) . ' hour' . ((int) ceil($hoursRemaining) !== 1 ? 's' : '');
        }
        
        if ($daysRemaining === 0) {
            return 'Expires today';
        }
        if ($daysRemaining === 1) {
            return 'Expires tomorrow';
        }
        if ($daysRemaining <= 7) {
            return 'Expires in ' . $daysRemaining . ' days';
        }
        
        return 'Expires ' . $expiresAt->format('M d, Y');
    }
}
