<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExtensionProgram extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'goals',
        'objectives',
        'planned_start_date',
        'planned_end_date',
        'target_beneficiaries',
        'beneficiary_categories',
        'allocated_budget',
        'program_lead_id',
        'partners',
        'cover_image',
        'gallery_images',
        'related_communities',
        'attachments',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'beneficiary_categories' => 'json',
        'partners' => 'json',
        'gallery_images' => 'json',
        'related_communities' => 'json',
        'attachments' => 'json',
        'planned_start_date' => 'date',
        'planned_end_date' => 'date',
    ];

    public function programLead(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'program_lead_id');
    }

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class, 'community_extension_program');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function beneficiaries(): BelongsToMany
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiary_extension_program');
    }

    public function budgetUtilizations(): HasMany
    {
        return $this->hasMany(BudgetUtilization::class);
    }

    /**
     * Calculate progress based on activity completion rate
     * @return int Progress percentage (0-100)
     */
    public function getActivityProgressAttribute(): int
    {
        $activities = $this->activities()->get();
        
        if ($activities->isEmpty()) {
            // If no activities, base progress on status
            return match ($this->status) {
                'completed' => 100,
                'ongoing' => 50,
                'draft' => 20,
                'cancelled' => 0,
                default => 0,
            };
        }

        $completedActivities = $activities->where('status', 'completed')->count();
        $totalActivities = $activities->count();

        return $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100) : 0;
    }
}
