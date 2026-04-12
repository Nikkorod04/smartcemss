<?php

namespace App\Livewire;

use App\Models\ExtensionProgram;
use Livewire\Component;

class MetricsDashboard extends Component
{
    public $programId;
    public $program;
    public $metrics = [];
    public $completed = 0;
    public $ongoing = 0;
    public $pending = 0;

    public function mount($programId)
    {
        $this->programId = $programId;
        $this->program = ExtensionProgram::find($programId);
        
        if ($this->program) {
            $this->loadMetrics();
        }
    }

    public function loadMetrics()
    {
        // Tier 1: Output Metrics
        $this->metrics['tier1'] = [
            'participation_rate' => $this->program->participation_rate,
            'activity_completion_rate' => $this->program->activity_completion_rate,
            'attendance_consistency' => $this->program->attendance_consistency,
            'budget_utilization_rate' => $this->program->budget_utilization_rate,
            'cost_per_beneficiary' => $this->program->cost_per_beneficiary,
        ];

        // Tier 2: Outcome Metrics
        $this->metrics['tier2'] = [
            'average_knowledge_gain' => $this->program->average_knowledge_gain,
            'average_pre_assessment' => $this->program->average_pre_assessment,
            'average_post_assessment' => $this->program->average_post_assessment,
            'knowledge_gain_percentage' => $this->program->knowledge_gain_percentage,
            'skill_proficiency' => $this->program->skill_proficiency,
            'average_satisfaction' => $this->program->average_satisfaction,
        ];

        // Supporting Data
        $this->metrics['supporting'] = [
            'actual_beneficiaries' => $this->program->actual_beneficiaries,
            'target_beneficiaries' => $this->program->target_beneficiaries,
            'actual_attendance' => $this->program->actual_attendance,
            'total_activities' => $this->program->activities()->count(),
            'completed_activities' => $this->program->activities()->where('status', 'completed')->count(),
            'total_spent' => $this->program->total_spent,
            'allocated_budget' => $this->program->allocated_budget,
        ];

        // Activity Status Counts
        $activities = $this->program->activities()->get();
        $this->completed = $activities->where('status', 'completed')->count();
        $this->ongoing = $activities->where('status', 'ongoing')->count();
        $this->pending = $activities->where('status', 'pending')->count();
    }

    public function render()
    {
        return view('livewire.metrics-dashboard');
    }
}
