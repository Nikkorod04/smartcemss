<?php

namespace App\Livewire;

use App\Models\ExtensionProgram;
use App\Models\BudgetUtilization;
use Livewire\Component;

class BudgetUtilizationTable extends Component
{
    public $programId;
    public $program;
    public $budgets = [];
    public $totalAllocated = 0;
    public $totalSpent = 0;
    public $percentageUsed = 0;
    public $showAddExpenseModal = false;
    public $showViewExpenseModal = false;
    public $showEditExpenseModal = false;
    public $selectedExpenseId = null;

    #[\Livewire\Attributes\On('expense-added')]
    public function refreshBudgets()
    {
        $this->loadBudgets();
    }

    #[\Livewire\Attributes\On('expense-updated')]
    public function onExpenseUpdated()
    {
        $this->loadBudgets();
    }

    #[\Livewire\Attributes\On('close-modal')]
    public function closeAddExpenseModal()
    {
        $this->showAddExpenseModal = false;
    }

    #[\Livewire\Attributes\On('close-view-expense-modal')]
    public function closeViewExpenseModal()
    {
        $this->showViewExpenseModal = false;
        $this->selectedExpenseId = null;
    }

    #[\Livewire\Attributes\On('close-edit-expense-modal')]
    public function closeEditExpenseModal()
    {
        $this->showEditExpenseModal = false;
        $this->selectedExpenseId = null;
    }

    public function openAddExpenseModal()
    {
        $this->showAddExpenseModal = true;
    }

    public function openViewExpenseModal($expenseId)
    {
        $this->selectedExpenseId = $expenseId;
        $this->showViewExpenseModal = true;
    }

    public function openEditExpenseModal($expenseId)
    {
        $this->selectedExpenseId = $expenseId;
        $this->showEditExpenseModal = true;
    }

    public function mount($programId)
    {
        $this->programId = $programId;
        $this->program = ExtensionProgram::find($programId);
        
        if ($this->program) {
            $this->loadBudgets();
        }
    }

    public function loadBudgets()
    {
        $this->budgets = BudgetUtilization::where('extension_program_id', $this->programId)
            ->orderBy('date_spent', 'desc')
            ->get();

        $this->totalAllocated = $this->program->allocated_budget ?? 0;
        $this->totalSpent = $this->budgets->sum('amount');
        $this->percentageUsed = $this->totalAllocated > 0 
            ? round(($this->totalSpent / $this->totalAllocated) * 100, 2)
            : 0;
    }

    public function deleteExpense($id)
    {
        try {
            BudgetUtilization::find($id)->delete();
            $this->loadBudgets();
        } catch (\Exception $e) {
            // Handle error silently or dispatch error event
        }
    }

    public function render()
    {
        return view('livewire.budget-utilization-table');
    }
}

