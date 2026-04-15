<?php

namespace App\Http\Controllers;

use App\Models\ExtensionProgram;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    /**
     * Display timeline for a program with all activities
     */
    public function show(ExtensionProgram $program)
    {
        // Get all activities with their dates ordered by planned start date
        $activities = $program->activities()
            ->orderBy('planned_start_date')
            ->get();

        // Calculate timeline data
        $timelineData = $this->generateTimelineData($program, $activities);

        return view('timeline.show', compact('program', 'activities', 'timelineData'));
    }

    /**
     * Generate timeline data for Gantt chart
     */
    private function generateTimelineData(ExtensionProgram $program, $activities)
    {
        // Get the overall date range
        $allDates = [];
        
        // Add program dates
        if ($program->planned_start_date) $allDates[] = $program->planned_start_date;
        if ($program->planned_end_date) $allDates[] = $program->planned_end_date;
        
        // Add activity planned dates
        foreach ($activities as $activity) {
            if ($activity->planned_start_date) $allDates[] = $activity->planned_start_date;
            if ($activity->planned_end_date) $allDates[] = $activity->planned_end_date;
            if ($activity->actual_start_date) $allDates[] = $activity->actual_start_date;
            if ($activity->actual_end_date) $allDates[] = $activity->actual_end_date;
        }

        if (empty($allDates)) {
            return [
                'startDate' => now()->startOfMonth(),
                'endDate' => now()->addMonths(3)->endOfMonth(),
                'totalDays' => 90,
            ];
        }

        $startDate = min($allDates);
        $endDate = max($allDates);

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalDays' => $startDate->diffInDays($endDate) + 1,
        ];
    }

    /**
     * Calculate position and width for timeline bars
     */
    public static function calculateBarPosition($itemStart, $timelineStart, $timelineDays)
    {
        if (!$itemStart) return 0;
        
        $daysOffset = $timelineStart->diffInDays($itemStart);
        return round(($daysOffset / $timelineDays) * 100, 2);
    }

    public static function calculateBarWidth($itemStart, $itemEnd, $timelineStart, $timelineTotalDays)
    {
        if (!$itemStart || !$itemEnd) return 0;
        
        $duration = $itemStart->diffInDays($itemEnd) + 1;
        return round(($duration / $timelineTotalDays) * 100, 2);
    }
}
