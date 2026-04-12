<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentSummary extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'community_id',
        'quarter',
        'year',
        'total_responses',
        'gender_distribution',
        'religion_distribution',
        'education_distribution',
        'civil_status_distribution',
        'livelihood_interests',
        'educational_interests',
        'health_problems',
        'family_problems',
        'employment_problems',
        'infrastructure_problems',
        'economic_problems',
        'security_problems',
        'water_sources',
        'house_types',
        'electricity_access_percentage',
        'organization_membership_percentage',
        'training_availability_percentage',
        'avg_service_satisfaction',
        'baseline_satisfaction_score',
        'last_calculated_at',
    ];

    protected $casts = [
        'gender_distribution' => 'json',
        'religion_distribution' => 'json',
        'education_distribution' => 'json',
        'civil_status_distribution' => 'json',
        'livelihood_interests' => 'json',
        'educational_interests' => 'json',
        'health_problems' => 'json',
        'family_problems' => 'json',
        'employment_problems' => 'json',
        'infrastructure_problems' => 'json',
        'economic_problems' => 'json',
        'security_problems' => 'json',
        'water_sources' => 'json',
        'house_types' => 'json',
        'last_calculated_at' => 'datetime',
    ];

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Calculate all metrics from community needs assessments for a specific quarter/year
     */
    public static function calculateForCommunity(Community $community, ?string $quarter = null, ?int $year = null): self
    {
        // If quarter/year not provided, use current quarter
        if (!$quarter || !$year) {
            $quarter = self::getCurrentQuarter();
            $year = now()->year;
        }

        // Get assessments for this quarter and year
        $assessments = $community->needsAssessments()
            ->where('quarter', $quarter)
            ->where('year', $year)
            ->get();
        
        if ($assessments->isEmpty()) {
            return self::updateOrCreate(
                ['community_id' => $community->id, 'quarter' => $quarter, 'year' => $year],
                ['total_responses' => 0]
            );
        }

        $summary = self::updateOrCreate(
            ['community_id' => $community->id, 'quarter' => $quarter, 'year' => $year]
        );

        // Calculate all metrics
        $summary->total_responses = $assessments->count();
        $summary->gender_distribution = self::calculateDistribution($assessments, 'respondent_sex');
        $summary->religion_distribution = self::calculateDistribution($assessments, 'respondent_religion');
        $summary->education_distribution = self::calculateDistribution($assessments, 'respondent_educational_attainment');
        $summary->civil_status_distribution = self::calculateDistribution($assessments, 'respondent_civil_status');
        
        $summary->livelihood_interests = self::calculateMostCommon($assessments, 'livelihood_options');
        $summary->educational_interests = self::calculateMostCommon($assessments, 'areas_of_educational_interest');
        
        $summary->health_problems = self::calculateMostCommon($assessments, 'common_illnesses');
        $summary->family_problems = self::calculateMostCommon($assessments, 'family_problems');
        $summary->employment_problems = self::calculateMostCommon($assessments, 'employment_problems');
        $summary->infrastructure_problems = self::calculateMostCommon($assessments, 'infrastructure_problems');
        $summary->economic_problems = self::calculateMostCommon($assessments, 'economic_problems');
        $summary->security_problems = self::calculateMostCommon($assessments, 'security_problems');
        
        $summary->water_sources = self::calculateDistribution($assessments, 'water_source');
        $summary->house_types = self::calculateDistribution($assessments, 'house_type');
        
        $summary->electricity_access_percentage = self::calculatePercentageYes($assessments, 'has_electricity') * 100;
        $summary->organization_membership_percentage = self::calculatePercentageYes($assessments, 'member_of_organization') * 100;
        $summary->training_availability_percentage = self::calculatePercentageYes($assessments, 'available_for_training') * 100;
        
        $summary->avg_service_satisfaction = self::calculateAverageSatisfaction($assessments);
        $summary->baseline_satisfaction_score = self::calculateBaselineSatisfaction($summary->avg_service_satisfaction);
        
        $summary->last_calculated_at = now();
        $summary->save();

        return $summary;
    }

    /**
     * Get current quarter (Q1, Q2, Q3, Q4)
     */
    private static function getCurrentQuarter(): string
    {
        $month = now()->month;
        
        if ($month <= 3) return 'Q1';
        if ($month <= 6) return 'Q2';
        if ($month <= 9) return 'Q3';
        return 'Q4';
    }

    /**
     * Calculate distribution for single-select fields
     * Returns: ['value1' => percentage, 'value2' => percentage, ...]
     */
    private static function calculateDistribution($assessments, $field): array
    {
        $allItems = [];
        
        // Collect all items, decoding JSON if necessary
        foreach ($assessments as $assessment) {
            $value = $assessment->$field;
            
            // Handle JSON strings - decode them to arrays
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    $allItems = array_merge($allItems, $decoded);
                } elseif (!empty($value)) {
                    $allItems[] = $value;
                }
            } elseif (is_array($value)) {
                $allItems = array_merge($allItems, $value);
            } elseif ($value !== null) {
                $allItems[] = $value;
            }
        }

        if (empty($allItems)) return [];

        $total = count($allItems);
        $counts = array_count_values($allItems);
        $distribution = [];

        foreach ($counts as $value => $count) {
            if ($value !== null) {
                $distribution[$value] = round(($count / $total) * 100, 1);
            }
        }

        arsort($distribution);
        return $distribution;
    }

    /**
     * Calculate most common items from JSON array fields
     * Returns top 5 items with their frequency
     */
    private static function calculateMostCommon($assessments, $field): array
    {
        $allItems = [];
        
        foreach ($assessments as $assessment) {
            $value = $assessment->$field;
            
            // Handle JSON strings - decode them to arrays
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    $allItems = array_merge($allItems, $decoded);
                } elseif (!empty($value)) {
                    $allItems[] = $value;
                }
            } elseif (is_array($value)) {
                $allItems = array_merge($allItems, $value);
            }
        }

        if (empty($allItems)) return [];

        $counts = array_count_values($allItems);
        arsort($counts);

        $result = [];
        foreach (array_slice($counts, 0, 5) as $item => $count) {
            $result[$item] = $count;
        }

        return $result;
    }

    /**
     * Calculate percentage of yes/true responses
     */
    private static function calculatePercentageYes($assessments, $field): float
    {
        $total = $assessments->count();
        if ($total === 0) return 0;

        $yesCount = $assessments->filter(function ($assessment) use ($field) {
            $value = $assessment->$field;
            return in_array($value, ['yes', 'true', true, 'Y', 'Yes', 1], true);
        })->count();

        return $yesCount / $total;
    }

    /**
     * Calculate average service satisfaction rating (1-5 scale)
     */
    private static function calculateAverageSatisfaction($assessments): ?float
    {
        $ratings = [];
        
        foreach ($assessments as $assessment) {
            $barangayRatings = $assessment->barangay_service_ratings;
            
            if (is_array($barangayRatings)) {
                $ratings = array_merge($ratings, array_values($barangayRatings));
            }
        }

        if (empty($ratings)) return null;

        return round(array_sum($ratings) / count($ratings), 2);
    }

    /**
     * Calculate baseline satisfaction score for program baseline
     * Uses average service satisfaction as the baseline (1-5 scale)
     */
    private static function calculateBaselineSatisfaction(?float $avgSatisfaction): ?float
    {
        if ($avgSatisfaction === null) return null;

        // Scale is already 1-5, so use it directly
        return $avgSatisfaction;
    }

    /**
     * Get formatted summary for display
     */
    public function getFormattedSummary(): array
    {
        return [
            'demographics' => [
                'total_responses' => $this->total_responses,
                'gender' => $this->gender_distribution,
                'religion' => $this->religion_distribution,
                'education' => $this->education_distribution,
                'civil_status' => $this->civil_status_distribution,
            ],
            'interests' => [
                'livelihood' => $this->livelihood_interests,
                'educational' => $this->educational_interests,
            ],
            'problems' => [
                'health' => $this->health_problems,
                'family' => $this->family_problems,
                'employment' => $this->employment_problems,
                'infrastructure' => $this->infrastructure_problems,
                'economic' => $this->economic_problems,
                'security' => $this->security_problems,
            ],
            'infrastructure' => [
                'water_sources' => $this->water_sources,
                'house_types' => $this->house_types,
                'electricity_access_percentage' => $this->electricity_access_percentage,
                'organization_membership_percentage' => $this->organization_membership_percentage,
                'training_availability_percentage' => $this->training_availability_percentage,
            ],
            'satisfaction' => [
                'avg_service_satisfaction' => $this->avg_service_satisfaction,
                'baseline_satisfaction_score' => $this->baseline_satisfaction_score,
            ],
        ];
    }
}
