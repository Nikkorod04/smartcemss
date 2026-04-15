<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentAnalysis extends Model
{
    protected $fillable = [
        'needs_assessment_id',
        'raw_extracted_data',
        'extracted_fields',
        'problems_identified',
        'recommendations',
        'summary',
        'confidence_score',
        'status',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the needs assessment this analysis belongs to
     */
    public function needsAssessment(): BelongsTo
    {
        return $this->belongsTo(NeedsAssessment::class);
    }

    /**
     * Check if analysis is completed successfully
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if analysis failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get extracted fields as array
     */
    public function getExtractedFields(): array
    {
        return json_decode($this->extracted_fields ?? '{}', true);
    }

    /**
     * Get problems as array
     */
    public function getProblems(): array
    {
        return json_decode($this->problems_identified ?? '[]', true);
    }

    /**
     * Get recommendations as array
     */
    public function getRecommendations(): array
    {
        return json_decode($this->recommendations ?? '[]', true);
    }

    /**
     * Get high priority problems
     */
    public function getHighPriorityProblems(): array
    {
        return array_filter(
            $this->getProblems(),
            fn ($problem) => ($problem['severity'] ?? null) === 'high'
        );
    }

    /**
     * Get immediate action recommendations
     */
    public function getImmediateRecommendations(): array
    {
        return array_filter(
            $this->getRecommendations(),
            fn ($rec) => ($rec['priority'] ?? null) === 'immediate'
        );
    }

    /**
     * Get problems by category
     */
    public function getProblemsByCategory(string $category): array
    {
        return array_filter(
            $this->getProblems(),
            fn ($problem) => ($problem['category'] ?? null) === $category
        );
    }

    /**
     * Get recommendations by category
     */
    public function getRecommendationsByCategory(string $category): array
    {
        return array_filter(
            $this->getRecommendations(),
            fn ($rec) => ($rec['category'] ?? null) === $category
        );
    }
}
