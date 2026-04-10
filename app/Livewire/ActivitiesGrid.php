<?php

namespace App\Livewire;

use App\Models\Activity;
use Livewire\Component;
use Livewire\WithPagination;

class ActivitiesGrid extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'status', 'sortBy', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function deleteActivity($activityId)
    {
        try {
            Activity::find($activityId)->delete();
        } catch (\Exception $e) {
            // Handle error silently
        }
    }

    public function render()
    {
        $query = Activity::with('extensionProgram', 'faculties', 'attendances');

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('extensionProgram', function ($q) {
                      $q->where('title', 'like', '%' . $this->search . '%');
                  });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $activities = $query->orderBy($this->sortBy, $this->sortDirection)
                            ->paginate(12);

        return view('livewire.activities-grid', [
            'activities' => $activities,
        ]);
    }
}
