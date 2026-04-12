<?php

namespace App\Livewire;

use App\Models\BudgetUtilization;
use App\Models\ExtensionProgram;
use Livewire\Component;

class EditExpenseModal extends Component
{
    public $expenseId;
    public $expense;
    public $activities = [];
    
    public $date_spent;
    public $amount;
    public $description;
    public $transaction_type;
    public $budget_source;
    public $source_description;
    public $approval_status;
    public $activity_id;
    public $people_involved = [];
    public $offices_involved = [];
    
    public $personName = '';
    public $personPosition = '';
    public $personOffice = '';
    
    public $officeName = '';
    public $officeContact = '';

    public function mount($expenseId)
    {
        $this->expenseId = $expenseId;
        $this->loadExpense();
    }

    public function loadExpense()
    {
        $this->expense = BudgetUtilization::find($this->expenseId);
        
        if ($this->expense) {
            $this->date_spent = $this->expense->date_spent->format('Y-m-d');
            $this->amount = $this->expense->amount;
            $this->description = $this->expense->description;
            $this->transaction_type = $this->expense->transaction_type;
            $this->budget_source = $this->expense->budget_source;
            $this->source_description = $this->expense->source_description;
            $this->approval_status = $this->expense->approval_status;
            $this->activity_id = $this->expense->activity_id;
            $this->people_involved = $this->expense->people_involved ?? [];
            $this->offices_involved = $this->expense->offices_involved ?? [];
            
            // Load activities for the program
            $program = $this->expense->extensionProgram;
            if ($program) {
                $this->activities = $program->activities()->get()->toArray();
            }
        }
    }

    public function getSelectedActivityRemainingBudget()
    {
        if (!$this->activity_id || !$this->expense) {
            return null;
        }
        
        $activity = $this->expense->extensionProgram->activities()->find($this->activity_id);
        if ($activity) {
            // Calculate remaining budget excluding the current expense amount
            $currentExpenseAmount = $this->expense->activity_id == $this->activity_id ? $this->expense->amount : 0;
            return $activity->remaining_budget + $currentExpenseAmount;
        }
        return null;
    }

    public function addPerson()
    {
        if ($this->personName && $this->personPosition) {
            $this->people_involved[] = [
                'name' => $this->personName,
                'position' => $this->personPosition,
                'office' => $this->personOffice,
            ];
            
            $this->personName = '';
            $this->personPosition = '';
            $this->personOffice = '';
        }
    }

    public function removePerson($index)
    {
        unset($this->people_involved[$index]);
        $this->people_involved = array_values($this->people_involved);
    }

    public function addOffice()
    {
        if ($this->officeName) {
            $this->offices_involved[] = [
                'name' => $this->officeName,
                'contact' => $this->officeContact,
            ];
            
            $this->officeName = '';
            $this->officeContact = '';
        }
    }

    public function removeOffice($index)
    {
        unset($this->offices_involved[$index]);
        $this->offices_involved = array_values($this->offices_involved);
    }

    public function saveExpense()
    {
        $this->validate([
            'date_spent' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'budget_source' => 'required|string|max:255',
        ]);

        try {
            $this->expense->update([
                'date_spent' => $this->date_spent,
                'amount' => $this->amount,
                'description' => $this->description,
                'transaction_type' => $this->transaction_type,
                'budget_source' => $this->budget_source,
                'source_description' => $this->source_description,
                'activity_id' => $this->activity_id ?: null,
                'people_involved' => count($this->people_involved) > 0 ? $this->people_involved : null,
                'offices_involved' => count($this->offices_involved) > 0 ? $this->offices_involved : null,
                'approval_status' => $this->approval_status,
            ]);

            $this->dispatch('expense-updated');
            $this->dispatch('close-edit-expense-modal');
        } catch (\Exception $e) {
            // Handle error
        }
    }

    public function closeModal()
    {
        $this->dispatch('close-edit-expense-modal');
    }

    public function render()
    {
        return view('livewire.edit-expense-modal');
    }
}
