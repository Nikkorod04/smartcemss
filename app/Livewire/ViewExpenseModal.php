<?php

namespace App\Livewire;

use App\Models\BudgetUtilization;
use Livewire\Component;

class ViewExpenseModal extends Component
{
    public $expense;
    public $expenseId;

    public function mount($expenseId)
    {
        $this->expenseId = $expenseId;
        $this->loadExpense();
    }

    public function loadExpense()
    {
        $this->expense = BudgetUtilization::find($this->expenseId);
    }

    public function closeModal()
    {
        $this->dispatch('close-view-expense-modal');
    }

    public function render()
    {
        return view('livewire.view-expense-modal');
    }
}
