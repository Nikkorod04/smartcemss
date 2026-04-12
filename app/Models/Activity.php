<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'extension_program_id',
        'title',
        'description',
        'actual_start_date',
        'actual_end_date',
        'venue',
        'status',
        'notes',
        'allocated_budget',
    ];

    protected $casts = [
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
    ];

    public function extensionProgram(): BelongsTo
    {
        return $this->belongsTo(ExtensionProgram::class);
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'activity_faculty');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function budgetUtilizations(): HasMany
    {
        return $this->hasMany(BudgetUtilization::class);
    }

    /**
     * Get the total amount spent on this activity
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->budgetUtilizations()->sum('amount');
    }

    /**
     * Get the remaining budget for this activity
     */
    public function getRemainingBudgetAttribute(): float
    {
        return max(0, $this->allocated_budget - $this->total_spent);
    }

    /**
     * Get the percentage of budget spent
     */
    public function getSpentPercentageAttribute(): float
    {
        if ($this->allocated_budget == 0) return 0;
        return ($this->total_spent / $this->allocated_budget) * 100;
    }

    /**
     * Check if budget is near limit (85% spent)
     */
    public function isBudgetNearLimit(): bool
    {
        return $this->spent_percentage >= 85;
    }
}
