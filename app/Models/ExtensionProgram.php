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
        'baseline_knowledge_score',
        'baseline_satisfaction_score',
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

    /**
     * Decode partners JSON if stored as string
     */
    public function getPartnersAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    /**
     * Decode beneficiary_categories JSON if stored as string
     */
    public function getBeneficiaryCategoriesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    /**
     * Decode gallery_images JSON if stored as string
     */
    public function getGalleryImagesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    /**
     * Decode attachments JSON if stored as string
     */
    public function getAttachmentsAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    // ===== M&E METRICS - TIER 1: OUTPUT METRICS =====

    /**
     * Participation Rate: (Actual beneficiaries / Target beneficiaries) × 100
     */
    public function getParticipationRateAttribute(): float
    {
        if ($this->target_beneficiaries == 0) return 0;
        $actualBeneficiaries = $this->beneficiaries()->count();
        return min(100, round(($actualBeneficiaries / $this->target_beneficiaries) * 100, 2));
    }

    /**
     * Activity Completion Rate: (Completed activities / Total activities) × 100
     */
    public function getActivityCompletionRateAttribute(): float
    {
        $activities = $this->activities()->get();
        if ($activities->isEmpty()) return 0;
        $completedActivities = $activities->where('status', 'completed')->count();
        return round(($completedActivities / $activities->count()) * 100, 2);
    }

    /**
     * Attendance Consistency: Average attendance % across all activities
     */
    public function getAttendanceConsistencyAttribute(): float
    {
        $activities = $this->activities()->get();
        if ($activities->isEmpty()) return 0;

        $attendanceRates = $activities->map(function ($activity) {
            $attendances = $activity->attendances()->where('status', 'present')->count();
            $totalAttendances = $activity->attendances()->count();
            return $totalAttendances > 0 ? ($attendances / $totalAttendances) * 100 : 0;
        });

        return $attendanceRates->count() > 0 ? round($attendanceRates->avg(), 2) : 0;
    }

    /**
     * Budget Utilization Rate: (Amount spent / Amount allocated) × 100
     */
    public function getBudgetUtilizationRateAttribute(): float
    {
        if ($this->allocated_budget == 0) return 0;
        $spent = $this->budgetUtilizations()->sum('amount');
        return min(100, round(($spent / $this->allocated_budget) * 100, 2));
    }

    /**
     * Cost per Beneficiary: Total spent / Number of beneficiaries
     */
    public function getCostPerBeneficiaryAttribute(): float
    {
        $actualBeneficiaries = $this->beneficiaries()->count();
        if ($actualBeneficiaries == 0) return 0;
        $totalSpent = $this->budgetUtilizations()->sum('amount');
        return round($totalSpent / $actualBeneficiaries, 2);
    }

    // ===== M&E METRICS - TIER 2: OUTCOME METRICS =====

    /**
     * Average Knowledge Gain: Average (post - pre) across activities with assessment data
     */
    public function getAverageKnowledgeGainAttribute(): ?float
    {
        $activitiesWithData = $this->activities()
            ->whereNotNull('pre_assessment_score')
            ->whereNotNull('post_assessment_score')
            ->get();

        if ($activitiesWithData->isEmpty()) return null;

        $gains = $activitiesWithData->map(fn($a) => $a->post_assessment_score - $a->pre_assessment_score);
        return round($gains->avg(), 2);
    }

    /**
     * Average Pre-Assessment Score
     */
    public function getAveragePreAssessmentAttribute(): ?float
    {
        $scores = $this->activities()
            ->whereNotNull('pre_assessment_score')
            ->pluck('pre_assessment_score');

        return $scores->count() > 0 ? round($scores->avg(), 2) : null;
    }

    /**
     * Average Post-Assessment Score
     */
    public function getAveragePostAssessmentAttribute(): ?float
    {
        $scores = $this->activities()
            ->whereNotNull('post_assessment_score')
            ->pluck('post_assessment_score');

        return $scores->count() > 0 ? round($scores->avg(), 2) : null;
    }

    /**
     * Knowledge Gain Percentage: ((Post - Pre) / Pre) × 100
     */
    public function getKnowledgeGainPercentageAttribute(): ?float
    {
        $preAvg = $this->average_pre_assessment;
        $postAvg = $this->average_post_assessment;

        if ($preAvg === null || $postAvg === null || $preAvg == 0) return null;

        return round((($postAvg - $preAvg) / $preAvg) * 100, 2);
    }

    /**
     * Skill Proficiency: % of activities with post-score >= 70
     */
    public function getSkillProficiencyAttribute(): float
    {
        $activitiesWithData = $this->activities()
            ->whereNotNull('post_assessment_score')
            ->get();

        if ($activitiesWithData->isEmpty()) return 0;

        $proficient = $activitiesWithData->where('post_assessment_score', '>=', 70)->count();
        return round(($proficient / $activitiesWithData->count()) * 100, 2);
    }

    /**
     * Average Satisfaction Rating: (1-5 scale)
     */
    public function getAverageSatisfactionAttribute(): ?float
    {
        $ratings = $this->activities()
            ->whereNotNull('satisfaction_rating')
            ->pluck('satisfaction_rating');

        return $ratings->count() > 0 ? round($ratings->avg(), 2) : null;
    }

    /**
     * Total number of actual beneficiaries reached
     */
    public function getActualBeneficiariesAttribute(): int
    {
        return $this->beneficiaries()->count();
    }

    /**
     * Get actual attendance count across all activities
     */
    public function getActualAttendanceAttribute(): int
    {
        return $this->activities()
            ->with('attendances')
            ->get()
            ->sum(fn($a) => $a->attendances->where('status', 'present')->count());
    }

    /**
     * Get total budget spent
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->budgetUtilizations()->sum('amount');
    }

    /**
     * Get remaining budget
     */
    public function getRemainingBudgetAttribute(): float
    {
        return max(0, $this->allocated_budget - $this->total_spent);
    }
}
