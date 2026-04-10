<?php

namespace App\Livewire;

use App\Models\BudgetUtilization;
use Livewire\Component;

class BudgetSourceModal extends Component
{
    public $budgetId;
    public $budget;
    
    public $budgetSource = '';
    public $sourceDescription = '';
    public $peopleInvolved = [];
    public $officesInvolved = [];
    public $approvalStatus = 'pending';
    
    public $personName = '';
    public $personPosition = '';
    public $personOffice = '';
    
    public $officeName = '';
    public $officeContact = '';

    public function mount($budgetId = null)
    {
        if ($budgetId) {
            $this->budgetId = $budgetId;
            $this->budget = BudgetUtilization::find($budgetId);
            
            if ($this->budget) {
                $this->budgetSource = $this->budget->budget_source ?? '';
                $this->sourceDescription = $this->budget->source_description ?? '';
                $this->peopleInvolved = $this->budget->people_involved ?? [];
                $this->officesInvolved = $this->budget->offices_involved ?? [];
                $this->approvalStatus = $this->budget->approval_status ?? 'pending';
            }
        }
    }

    public function addPerson()
    {
        if ($this->personName && $this->personPosition) {
            $this->peopleInvolved[] = [
                'name' => $this->personName,
                'position' => $this->personPosition,
                'office' => $this->personOffice,
            ];
            
            $this->personName = '';
            $this->personPosition = '';
            $this->personOffice = '';
            $this->dispatch('person-added');
        }
    }

    public function removePerson($index)
    {
        unset($this->peopleInvolved[$index]);
        $this->peopleInvolved = array_values($this->peopleInvolved);
    }

    public function addOffice()
    {
        if ($this->officeName) {
            $this->officesInvolved[] = [
                'name' => $this->officeName,
                'contact' => $this->officeContact,
            ];
            
            $this->officeName = '';
            $this->officeContact = '';
            $this->dispatch('office-added');
        }
    }

    public function removeOffice($index)
    {
        unset($this->officesInvolved[$index]);
        $this->officesInvolved = array_values($this->officesInvolved);
    }

    public function saveBudgetSource()
    {
        $this->validate([
            'budgetSource' => 'required|string|max:255',
            'sourceDescription' => 'nullable|string',
        ]);

        if ($this->budget) {
            $this->budget->update([
                'budget_source' => $this->budgetSource,
                'source_description' => $this->sourceDescription,
                'people_involved' => count($this->peopleInvolved) > 0 ? $this->peopleInvolved : null,
                'offices_involved' => count($this->officesInvolved) > 0 ? $this->officesInvolved : null,
                'approval_status' => $this->approvalStatus,
            ]);

            $this->dispatch('budget-source-saved');
            session()->flash('success', 'Budget source information saved successfully!');
        }
    }

    public function render()
    {
        return view('livewire.budget-source-modal');
    }
}
