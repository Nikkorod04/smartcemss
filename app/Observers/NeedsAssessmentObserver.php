<?php

namespace App\Observers;

use App\Models\NeedsAssessment;
use App\Models\AssessmentSummary;

class NeedsAssessmentObserver
{
    /**
     * Handle the NeedsAssessment "created" event.
     */
    public function created(NeedsAssessment $assessment): void
    {
        // Recalculate community assessment summary for this quarter/year
        $summary = AssessmentSummary::calculateForCommunity(
            $assessment->community,
            $assessment->quarter,
            $assessment->year
        );

        // Auto-update baseline_satisfaction_score for all linked programs
        $assessment->community->extensionPrograms()->update([
            'baseline_satisfaction_score' => $summary->baseline_satisfaction_score,
        ]);
    }

    /**
     * Handle the NeedsAssessment "updated" event.
     */
    public function updated(NeedsAssessment $assessment): void
    {
        // Recalculate on update too
        $summary = AssessmentSummary::calculateForCommunity(
            $assessment->community,
            $assessment->quarter,
            $assessment->year
        );

        $assessment->community->extensionPrograms()->update([
            'baseline_satisfaction_score' => $summary->baseline_satisfaction_score,
        ]);
    }

    /**
     * Handle the NeedsAssessment "deleted" event.
     */
    public function deleted(NeedsAssessment $assessment): void
    {
        // Recalculate after deletion
        $summary = AssessmentSummary::calculateForCommunity(
            $assessment->community,
            $assessment->quarter,
            $assessment->year
        );

        $assessment->community->extensionPrograms()->update([
            'baseline_satisfaction_score' => $summary->baseline_satisfaction_score,
        ]);
    }
}
