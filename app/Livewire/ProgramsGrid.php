<?php

namespace App\Livewire;

use App\Models\ExtensionProgram;
use Livewire\Component;
use Livewire\WithPagination;

class ProgramsGrid extends Component
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

    public function deleteProgram($programId)
    {
        try {
            ExtensionProgram::find($programId)->delete();
        } catch (\Exception $e) {
            // Handle error silently
        }
    }

    public function render()
    {
        $query = ExtensionProgram::with('programLead.user', 'communities');

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $programs = $query->orderBy($this->sortBy, $this->sortDirection)
                         ->paginate(12);

        return view('livewire.programs-grid', [
            'programs' => $programs,
        ]);
    }
}
